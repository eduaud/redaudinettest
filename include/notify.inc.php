<?php
// $Id: notify.inc.php,v 1.12 2005/02/10 20:01:15 liedekef Exp $ 
if (!defined('ROOTPATH')) die("This is a library.");

define("NOTIFY_MIM", 6);
define("NOTIFY_HTML", 5);
define("NOTIFY_JS", 4);
define("NOTIFY_SMS", 3);
define("NOTIFY_MAIL", 2);
define("NOTIFY_SCREEN", 1);
 
class notify {
    /* constructor */
    function notify() {
	global $conn;

	if(isset($_SESSION['MGW']->userid)){
	    $GLOBALS['log']->message(MGW_INFO, "Initializing notification core.", __LINE__, __FILE__);

	    // assign some basic stuff
	    $GLOBALS['smarty']->assign("jsnotify", $this->isEnabled(NOTIFY_JS));
	    $GLOBALS['smarty']->assign("htmlnotify", $this->isEnabled(NOTIFY_HTML));
	    $GLOBALS['smarty']->assign("mimcount", $this->getMIMCount());
	    $GLOBALS['smarty']->assign("mimnew", $this->getMIMCountNew());
	    $GLOBALS['smarty']->assign("mimframe", isset($_SESSION['MGW']->settings['notify_mim_iframe'])?$_SESSION['MGW']->settings['notify_mim_iframe']:"");
	    $GLOBALS['smarty']->assign("mimrefresh", isset($_SESSION['MGW']->settings['notify_mim_refresh'])?$_SESSION['MGW']->settings['notify_mim_refresh']:"");

	    // check for spooled on-screen notifications
	    // only do this for 'real' page loads (dirty hack)
	    if(!stristr($_SERVER['SCRIPT_FILENAME'], 'modules/general/mim')){
		$sql = 'SELECT * FROM mgw_notifications WHERE recipient='.$_SESSION['MGW']->userid;
		if(!$res = $conn->Execute($sql)) exit(showSQLerror($sql, $conn->ErrorMsg(), __LINE__, __FILE__));

		while($row = $res->FetchRow()){
		    $this->message($row['message'], NOTIFY_SCREEN);
		}

		$sql = 'DELETE FROM mgw_notifications WHERE recipient='.$_SESSION['MGW']->userid;
		if(!$conn->Execute($sql)) exit(showSQLerror($sql, $conn->ErrorMsg(), __LINE__, __FILE__));

		$GLOBALS['log']->message(MGW_INFO, "Processed spooled notifications.", __LINE__, __FILE__);
	    }
	}
    }

    function isEnabled($type){
	switch($type){
	case NOTIFY_JS:
	    return isset($_SESSION['MGW']->settings['notify_js'])?$_SESSION['MGW']->settings['notify_js']:"";
	    break;
	case NOTIFY_HTML:
	    return isset($_SESSION['MGW']->settings['notify_html'])?$_SESSION['MGW']->settings['notify_html']:"";
	    break;
	case NOTIFY_MAIL:
	case NOTIFY_MIM:
	    return 1;
	    break;
	}
    }

    function getMIMCount($user=null){
	global $conn;

	$id = ($user === null) ? $_SESSION['MGW']->userid : (int) $user;
	$sql = "SELECT COUNT(id) AS ims FROM mgw_instmsg WHERE recipient=$id";
	if(($res = $conn->GetRow($sql)) === false) exit(showSQLerror($sql, $conn->ErrorMsg(), __LINE__, __FILE__));
	return $res['ims'];
    }

    function getMIMCountNew($user=null){
	global $conn;

	$id = ($user === null) ? $_SESSION['MGW']->userid : (int) $user;
	$sql = "SELECT COUNT(id) AS ims FROM mgw_instmsg WHERE recipient=$id AND status='N'";
	if(($res = $conn->GetRow($sql)) === false) exit(showSQLerror($sql, $conn->ErrorMsg(), __LINE__, __FILE__));
	return $res['ims'];
    }

    function message($message, $type, $dest=null, $subject=null, $log=false){
	global $smarty, $conn;

	if(is_array($message)){
	    return;
	}

	switch($type){
	    // notifications for immediate on-screen display.
	    // will be shown on next $smarty->display() call
	    // and emit html and/or js depending on settings.
	case NOTIFY_SCREEN:
	    if($dest === null) {
		if($this->isEnabled(NOTIFY_JS))
		    $smarty->append('jsnotifyarray', $message);
		if($this->isEnabled(NOTIFY_HTML))
		    $smarty->append('htmlnotifyarray', $message);
	    }
	    else{
		$dest = explode(':', $dest);
		if(!is_array($dest) || $dest[0] != 'u'){
		    $GLOBALS['log']->message(MGW_ERROR, "Destination for notification incomplete/invalid!", __LINE__, __FILE__);
		    return;
		}

		$dest = ($dest[1] == '') ? get_user_info($_SESSION['MGW']->userid) : get_user_info((int)$dest[1]);

		$id = mgw_genID('mgw__seq_notifications');
		$message = $conn->QMagic($message);
		$sql = "INSERT INTO mgw_notifications (id, sender, recipient, message) VALUES ($id, ".$_SESSION['MGW']->userid.", ".$dest['id'].", $message)";
		if(!$conn->Execute($sql)) exit(showSQLerror($sql, $conn->ErrorMsg(), __LINE__, __FILE__));
	    }
	    break;

	case NOTIFY_MAIL:
	    if($dest === null) {
		$GLOBALS['log']->message(MGW_ERROR, "Destination for mail notification missing!", __LINE__, __FILE__);
		return;
	    }
	    $dest = explode(':', $dest);
	    if(!is_array($dest)){
		$GLOBALS['log']->message(MGW_ERROR, "Destination for mail notification not complete!", __LINE__, __FILE__);
		return;
	    }

	    switch($dest[0]){
	    case 'u':
		$user = ($dest[1] == '') ? get_user_info($_SESSION['MGW']->userid) : get_user_info((int)$dest[1]);
		$dest[1] = $user['email'];
		break;

	    case 'c':
		if(isModuleInstalled('contacts')){
		    $sql = "SELECT email FROM mgw_contacts WHERE id=".(int) $dest[1];
		    if(!$res = $conn->GetRow($sql)) exit(showSQLerror($sql, $conn->ErrorMsg(), __LINE__, __FILE__));
		    $dest[1] = $res['email'];
		}
		else{
		    $GLOBALS['log']->message(MGW_WARNING, "Destination for mail notification not complete, contacts module not installed!", __LINE__, __FILE__);
		    return;
		}
	    }

	    if(!isValidInetAddress($dest[1])){
		$GLOBALS['log']->message(MGW_WARNING, "Destination for mail notification invalid: ".$dest[1], __LINE__, __FILE__);
		return;
	    }

	    $subj = ($subject === null) ? 'more.groupware: '.Lang::getLanguageString('notification') : $subject;
	    $message = implode("\r\n", preg_split("/\r?\n/", $message));
	    mail($dest[1],
		 $subj,
		 $message,
		 "From: ".$_SESSION['MGW']->settings['notify_mail_from']."\r\n".
		 "Reply-To: ".$_SESSION['MGW']->settings['notify_mail_from']."\r\n".
		 "X-Mailer: PHP/".phpversion()."\r\n");
	    $GLOBALS['log']->message(MGW_INFO, "Mail notification sent to: ".$dest[1], __LINE__, __FILE__);
	    break;

	case NOTIFY_MIM:
	    if($dest === null) {
		$GLOBALS['log']->message(MGW_ERROR, "Destination missing for MIM!", __LINE__, __FILE__);
		return;
	    }
	    $dest = explode(':', $dest);
	    if(!is_array($dest) || $dest[0] != 'u'){
		$GLOBALS['log']->message(MGW_ERROR, "Destination for MIM incomplete/invalid!", __LINE__, __FILE__);
		return;
	    }

	    $dest = ($dest[1] == '') ? get_user_info($_SESSION['MGW']->userid) : get_user_info((int)$dest[1]);

	    $id = mgw_genID('mgw__seq_instmsg');
	    $message = $conn->QMagic($message);
	    $subj = ($subject === null) ? "'more.groupware: ".Lang::getLanguageString('notification')."'" : $conn->QMagic($subject);
	    $sql = "INSERT INTO mgw_instmsg (id, sender, recipient, subject, message, recieved) VALUES ($id, ".$_SESSION['MGW']->userid.", ".$dest['id'].", $subj, $message, NOW())";
	    if(!$conn->Execute($sql)) exit(showSQLerror($sql, $conn->ErrorMsg(), __LINE__, __FILE__));
	    break;

	case NOTIFY_SMS:
	    break;
	}
    }
}
?>
