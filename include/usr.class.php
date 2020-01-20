<?php
/* $Id: mgw.class.php,v 1.14 2003/12/13 17:20:51 emailtotom Exp $ */	
if (!defined('ROOTPATH')) die("This is a library.");

// =======================================================
// class holding user rights for each module
// =======================================================

class rights {
    var $modulename;	// name of module
    var $read;		// read priv.
    var $write;		// write priv.
    var $delete;		// delete priv.
    var $delete_others;	// delete others priv.
    var $modify;		// modify priv.
    var $modify_others;	// modify others priv.
    var $module_level;	// level in spec. module

    function rights($name, $read, $write, $del, $delo, $mod, $modo, $mlevel) {
	$this->modulename = $name;
	if($read==1) $this->read = true; else $this->read = false;
	if($write==1) $this->write = true; else $this->write = false;
	if($del==1) $this->delete = true; else $this->delete = false;
	if($delo==1) $this->delete_others = true; else $this->delete_others = false;
	if($mod==1) $this->modify = true; else $this->modify = false;
	if($modo==1) $this->modify_others = true; else $this->modify_others = false;
	$this->module_level = $mlevel;
    }
}

// =======================================================
// class holding user/application attributes for moregw
// =======================================================

class USR {
    var $spkz;			// languagecode which the user selected (for example en=english)
    var $fullusername;		// full name (first and lastname of user logged on)
    var $email;		        // email
    var $username;		// username of user logged on
    var $userid;		// userid of user logged on
    var $login;			// his login
    var $password;		// his pw
    var $charset;		// current HTML charset based on selected language

    var $rights = array();	// user rights (rights-object-array)
    var $settings = array(); 	// user settings (asoc. Array)
    var $groups = array();
    var $groups_names = array();
	var $browser = "";
	
    function USR() {
	/*
	 * instanciate ADOdb, Smarty, phpGACL, settings
	 * make those available as member objects
	 */
    }
}
?>