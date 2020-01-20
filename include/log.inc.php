<?php

if (!defined('ROOTPATH')) die("This is a library.");

/*******************************************************************************
 * $Id: log.inc.php,v 1.6 2003/10/22 17:33:44 emailtotom Exp $
 *
 * LOG CLASS
 * 
 * Designed to provided a means of script debugging and error tracking for 
 * moregroupware
 *
 * author: k|p (marcel@meulemans.org)
 * created: 07-10-2002
 ******************************************************************************/

/* log levels
 * level 0 is no logging
 * log levels corespond to bits, no to numbers so it is possible to log only 
 * errors and notices
 *
 * add log levels to enable one or more levels
 */
define("MGW_FATAL", 16);
define("MGW_ERROR", 8);
define("MGW_WARNING", 4);
define("MGW_NOTICE", 2);
define("MGW_INFO", 1);
 
$log_levels = array();
$log_levels[MGW_FATAL] = array("FATAL","F",MGW_FATAL);
$log_levels[MGW_ERROR] = array("ERROR","E",MGW_ERROR);
$log_levels[MGW_WARNING] = array("WARNING","W",MGW_WARNING);
$log_levels[MGW_NOTICE] = array("NOTICE","N",MGW_NOTICE);
$log_levels[MGW_INFO] = array("INFO","I",MGW_INFO);

/* MAIN CLASS DEFINITION*/
class log 
{
    /* log session id */
    var $logId;
    /* log sort */
    var $logSort = 0;
    /* session id */
    var $logSession;
    /* default level to 0, level is set in constructor */
    var $logLevel = 0;
    /* boolean to specify if logging is on */
    var $isOn = true;
    /* one timestamp for a log session */
    var $logStamp;
    /* if no db connection has been made yet, spool log messages */
    var $log_spool = array();
    /* output to file */
    var $output = "db";
    var $fn;
    var $fp;
    
    
    /* constructor */
    function log($loglevel) 
    {
        list($usec, $sec) = explode(' ', microtime());
        mt_srand((float) $sec + ((float) $usec * 100000));
        
        /* set log defaults */
        $this->logId = mt_rand();        
        $this->logLevel = (int)$loglevel;
        $this->logStamp = time();
        $this->logSession = session_id();
    }
    
    /* switch off logging for a while */
    function off() {
        $this->isOn = false;
    }
     
    /* switch logging back on */
    function on() {
        $this->isOn = true;
    }
    
    /* get/set level */
    function level() {
        if (func_num_args()>0) {
            if (is_numeric(func_get_arg(0))) {
                $this->logLevel = func_get_arg(0);
            }
        } else {
            return $this->logLevel;
        }    
    }
    
    /* return true the given level is on */
    function levelison($level) {
        if ($this->logLevel&$level) { 
            return true; 
        } else {
            return false;
        }        
    }
    
    function levelStr() {
    	global $log_levels;    

        $str = "";
        foreach ($log_levels as $level) {
            if ($this->levelison($level[2])) {
                if (strlen($str)>0) $str .= ",";
                $str .= $level[1];
            }
        }
        return $str;
    }
    
    /* For logging to a tmp file */
    function toFile() {
        $this->fn = tempnam("", "MGW");
        if ($this->fp = fopen($this->fn, "w")) {
            $this->output = "file";
        } else {
            die("Cannot open log output file in ".__FILE__." on line ".__LINE__.".");
        }
    }
    
    /* For getting log from a tmp file */
    function endFlush() {
        fclose($this->fp);
        $handle = fopen($this->fn, "r");
        $str = fread($handle, filesize($this->fn));
        fclose ($handle);

        return $str;    
    }
    
    /* log function */
    function message($level, $message, $line, $file, $force_log=false) 
    {
    	global $myEnv, $log_levels;
        
        /* return if logging is off */
        if (!$this->isOn) return true;

        /* return if level is off */
        if (!$this->levelison($level) and !$force_log) return true;
        
        $msg = array();
        $msg["sort"] = $this->logSort;
        $this->logSort++;
        $msg["level"] = $log_levels[$level][2];
        
        /* manualy passed module name */
        if (func_num_args()>5) {
            $msg["module"] = func_get_arg(5);
        } else {
            if (!isset($myEnv["module"])) {
                $msg["module"] = "general";
            } else {
                $msg["module"] = $myEnv["module"];
            }
        }
        
        $msg["line"] = $line;
        $msg["file"] = $file;
        $msg["message"] = $message;
        
        if ($this->output=="file") {
            $data = array();
            $data["id"] = $this->logId;
            $data["sort"] = $msg["sort"];
            $data["session"] = $this->logSession;
            $data["stamp"] = $this->logStamp;
            $data["level"] = $msg["level"];
            $data["module"] = $msg["module"];
            $data["line"] = $msg["line"];
            $data["file"] = $msg["file"];
            $data["message"] = $msg["message"];
            fwrite($this->fp, mgw_log_str_part($data,1));
        } else {
            if (!isset($GLOBALS["conn"])) {
                $this->log_spool[] = $msg;
            } else {
                if (count($this->log_spool)>0) {
                    foreach($this->log_spool as $spoolmsg) {
                        $sql = "INSERT INTO mgw_log (id, sort, session, stamp, level, module, line, file, message) VALUES (".$this->logId.",".$spoolmsg["sort"].",'".$this->logSession."',".$this->logStamp.",".$spoolmsg["level"].",'".$spoolmsg["module"]."',".$spoolmsg["line"].",'".$spoolmsg["file"]."','".$spoolmsg["message"]."')";
                        $GLOBALS["conn"]->Execute($sql);           
                    }
                    $this->log_spool = array();
                }
                $sql = "INSERT INTO mgw_log (id, sort, session, stamp, level, module, line, file, message) VALUES (".$this->logId.",".$msg["sort"].",'".$this->logSession."',".$this->logStamp.",".$msg["level"].",'".$msg["module"]."',".$msg["line"].",'".$msg["file"]."','".$msg["message"]."')";
                $GLOBALS["conn"]->Execute($sql);
            }
        }
    }
}

function mgw_log_str_part($data,$mode) 
{
    global $log_levels;
    
    $str = "";
    $header_length = 10;
    $msg = "";
    
    /* word wrap */
    if (strlen($data["message"])>(80-$header_length)) {
        $first = true;
        $tmp = wordwrap($data["message"],80-$header_length);
        foreach(explode("\n",$tmp) as $line) {
            ($first)?($msg.=$line):($msg.="\n".str_pad("", $header_length).$line);  
            $first = false;
        }
    } else {
        $msg = $data["message"];
    }

    /* build log string */
    if ($mode==0) {
        /* short */
        $str .= str_pad($log_levels[$data["level"]][0].":",$header_length).$msg."\n";
    } else {
        /* no line */
        ($data["line"]==0)?($myline=''):($myline=$data["line"]);
    
        /* long */
        $str .= str_pad(str_pad("",$header_length,"_").$log_levels[$data["level"]][0],80,"_")."\n";
        $str .= str_pad("message:",$header_length).$msg."\n";
        $str .= str_pad("module:",$header_length).$data["module"]."\n";
        $str .= str_pad("file:",$header_length).$data["file"]."\n";
        $str .= str_pad("line:",$header_length).$myline."\n";
        $str .= "\n";
    }
    return $str;
}

?>
