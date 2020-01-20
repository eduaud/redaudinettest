<?php
/* $Id: smarty_plugins.php,v 1.11 2004/12/08 12:01:11 k-fish Exp $ */

if (!defined('ROOTPATH')) die("This is a library.");

// log end function
function smarty_outputfilter_mgw_end_log($tpl_source, &$smarty)
{
    //echo "<pre>";
    //print_r($smarty);
    //echo "<pre>";

    $GLOBALS["log"]->message(MGW_NOTICE, "Smarty sending output for module ".$smarty->_tpl_vars["actmodule"].".",0,'',"general",1);

    return $tpl_source;
}
$smarty->register_outputfilter("smarty_outputfilter_mgw_end_log");


// 13-04-2002, hondzik : <honza@imachina.cz>
// smarty modifiers, that formates date from DB according to user settings 
function smarty_mod_mgwdate($ts) {

    if($ts == '') return '';

    $fmt_string = $_SESSION["USR"]->settings["set_datefmt"];
    $separator = $_SESSION["USR"]->settings["set_dmysep"];

    $trans = array("yyyy"=>"Y", "yy"=>"y", "mm"=>"m", "dd"=>"d");
    $fmt_string = strtr($fmt_string, $trans); 
    $fmt_string = substr($fmt_string,0,1).$separator.substr($fmt_string,1,1).$separator.substr($fmt_string,2,1);

    @$date = date($fmt_string, $ts);

    return $date;
}
$smarty->register_modifier("mgw_date","smarty_mod_mgwdate");

function smarty_mod_mgwtime($ts) {

    if($ts == '') return '';

    $fmt = $_SESSION["USR"]->settings["set_timefmt"]; //24h
    $separator = $_SESSION["USR"]->settings["set_hourminsep"];

    if ($fmt == "24h") {
	$fmt_string = "H".$separator."i".$separator."s";
    } else {
	$fmt_string = "h".$separator."i".$separator."s a";
    }

    @$date = date($fmt_string, $ts);

    return $date;
}
$smarty->register_modifier("mgw_time","smarty_mod_mgwtime");

function smarty_mod_mgwdatetime($ts, $type="datetime")  {

    if($ts == '') return '';

    switch ($type) {
    case "datetime":
	return smarty_mod_mgwdate($ts)." ".smarty_mod_mgwtime($ts);
	break;
    case "date":
	return smarty_mod_mgwdate($ts);
	break;
    case "time":
	return smarty_mod_mgwtime($ts);
	break;
    case "human":
	include_once(ROOTPATH.'/include/date.php');
	$GLOBALS['hour_format'] = ($_SESSION["USR"]->settings["set_timefmt"] == '24h') ? '' : '12-hour clock';
	return getDateString($ts);
	unset($GLOBALS['hour_format']);
	break;
    }

    return "SMARTY: Illegal mgw_datetime modifier parameter!";
}
$smarty->register_modifier("mgw_datetime","smarty_mod_mgwdatetime");

function smarty_mod_mgwfilesize($full_size) {
  if (is_numeric($full_size)) {
    if ($full_size > 1024*1024*1024*1024) {
      $full_size = number_format(($full_size / (1024*1024*1024*1024)),2)." TB";
    } else if ($full_size > 1024*1024*1024) {
      $full_size = number_format(($full_size / (1024*1024*1024)),2)." GB";
    } else if ($full_size > 1024*1024) {
      $full_size = number_format(($full_size / (1024*1024)),1)." MB";
    } else if ($full_size > 1024) {
      $full_size = number_format(($full_size / 1024),1)."K";
    } else {
      $full_size = number_format(($full_size),1)." byte";
    }
  }
  return $full_size;
}
$smarty->register_modifier("mgw_filesize","smarty_mod_mgwfilesize");

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     modifier
 * Name:     datepick
 * Author:   Nichlas Löfdahl
 * modified for mgw by karsten@k-fish.de
 * -------------------------------------------------------------
 */
function smarty_mod_linkalize($text){
    global $appconf;

    $text = preg_replace("/([^\w\/])(www\.[a-z0-9\-]+\.[a-z0-9\-]+)/i",
			 "$1http://$2", $text); //make sure there is an http:// on all URLs
    $text = preg_replace("/([\w]+:\/\/[\w-?&;#~=\.\/\@]+[\w\/])/i",
			 "<a target=\"_blank\" href=\"".ROOTURL."norefer.php?url=$1\">$1</a>",
			 $text); //make all URLs links

    if($_SESSION["USR"]->settings["use_webmail"] == 1)
	$text = preg_replace("/[\w-\.]+@(\w+[\w-]+\.){0,3}\w+[\w-]+\.[a-zA-Z]{2,4}\b/ie","'<a href=\"../webmail2/index.php?rightframe='.rawurlencode(\"sendmail.php?to=$0\").'&amp;".SID."\">\$0</a>'",$text);
    else
	$text = preg_replace("/[\w-\.]+@(\w+[\w-]+\.){0,3}\w+[\w-]+\.[a-zA-Z]{2,4}\b/i","<a href=\"mailto:$0\">$0</a>",$text);

    return $text;
}
$smarty->register_modifier("mgw_linkalize","smarty_mod_linkalize");

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     function
 * Name:     datepick
 * Author:   Monte Ohrt <monte@ispi.net>
 * modified for mgw by karsten@k-fish.de
 * -------------------------------------------------------------
 */
function smarty_function_datepick($params, &$smarty){
    global $appconf;

    // be sure equation parameter is present
    if (empty($params["form"])) {
        $smarty->trigger_error("datepick: missing form parameter");
        return;
    }
    extract($params);

    $theme = $appconf["theme"];
    $path = ROOTURL. "modules/general/templates/".$theme;
    $iconid = $_SESSION["USR"]->settings["iconid"];

    if(strstr($field,',')) {
	// dropdown fields
	$date_fields = explode(',',$field);
	echo '<a href="javascript:void(0)" onclick="javascript:datepick3(\''.ROOTURL.'include/\',\''.$theme.'\',document.'.$form.'.'.$date_fields[0].',document.'.$form.'.'.$date_fields[1].',document.'.$form.'.'.$date_fields[2].')"><img src="'.$path.'/media/cal_icon_'.$iconid.'.gif" width="21" height="22" border="0"></a>';
    } elseif ( empty($field)) {
	// dropdown default
	echo '<a href="javascript:void(0)" onclick="javascript:datepick3(\''.ROOTURL.'include/\',\''.$theme.'\',document.'.$form.'.Date_Year,document.'.$form.'.Date_Month,document.'.$form.'.Date_Day)"><img src="'.$path.'/media/cal_icon_'.$iconid.'.gif" width="21" height="22" border="0"></a>';		
    } else {
	// text field
	echo '<a href="javascript:void(0)" onclick="javascript:datepick(\''.ROOTURL.'include/\',\''.$theme.'\',document.'.$form.'.'.$field.')"><img src="'.$path.'/media/cal_icon_'.$iconid.'.gif" border=0></a>';
    }
}
$smarty->register_function("datepick","smarty_function_datepick");

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     function
 * Name:     html_select_date
 * Version:  1.3
 * Purpose:  Prints the dropdowns for date selection.
 * Author:   Andrei Zmievski
 *
 * ChangeLog: 1.0 initial release
 *            1.1 added support for +/- N syntax for begin
 *                and end year values. (Monte)
 *            1.2 added support for yyyy-mm-dd syntax for
 *                time value. (Jan Rosier)
 *            1.3 added support for choosing format for 
 *                month values (Gary Loescher)
 *            1.3.1 added support for choosing format for
 *                day values (Marcus Bointon)
 * -------------------------------------------------------------
 */
require_once $smarty->_get_plugin_filepath('shared','make_timestamp');
require_once $smarty->_get_plugin_filepath('function','html_options');
function smarty_function_mgw_html_select_date($params, &$smarty){
    /* Default values. */
    $prefix          = "Date_";
    $start_year      = strftime("%Y");
    $end_year        = $start_year;
    $display_days    = true;
    $display_months  = true;
    $display_years   = true;
    $month_format    = "%m";
    /* Write months as numbers by default  GL */
    $month_value_format = "%m";
    $day_format      = "%02d";
    /* Write day values using this format MB */
    $day_value_format = "%d";
    $year_as_text    = false;
    /* Display years in reverse order? Ie. 2000,1999,.... */
    $reverse_years   = false;
    /* Should the select boxes be part of an array when returned from PHP?
       e.g. setting it to "birthday", would create "birthday[Day]",
       "birthday[Month]" & "birthday[Year]". Can be combined with prefix */
    $field_array     = null;
    /* <select size>'s of the different <select> tags.
       If not set, uses default dropdown. */
    $day_size        = null;
    $month_size      = null;
    $year_size       = null;
    /* Unparsed attributes common to *ALL* the <select>/<input> tags.
       An example might be in the template: all_extra ='class ="foo"'. */
    $all_extra       = null;
    /* Separate attributes for the tags. */
    $day_extra       = null;
    $month_extra     = null;
    $year_extra      = null;
    /* Order in which to display the fields.
       "D" -> day, "M" -> month, "Y" -> year. */
    $field_order      = strtoupper($_SESSION['USR']->settings['set_datefmt']);
    /* String printed between the different fields. */
    $field_separator = "\n";
    $time = time();


    extract($params);

    // If $time is not in format yyyy-mm-dd
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $time)) {
	// then $time is empty or unix timestamp or mysql timestamp
	// using smarty_make_timestamp to get an unix timestamp and
	// strftime to make yyyy-mm-dd
	$time = strftime('%Y-%m-%d', smarty_make_timestamp($time));
    }
    // Now split this in pieces, which later can be used to set the select
    $time = explode("-", $time);
  
    // make syntax "+N" or "-N" work with start_year and end_year
    if (preg_match('!^(\+|\-)\s*(\d+)$!', $end_year, $match)) {
	if ($match[1] == '+') {
	    $end_year = strftime('%Y') + $match[2];
	} else {
	    $end_year = strftime('%Y') - $match[2];
	}
    }
    if (preg_match('!^(\+|\-)\s*(\d+)$!', $start_year, $match)) {
	if ($match[1] == '+') {
	    $start_year = strftime('%Y') + $match[2];
	} else {
	    $start_year = strftime('%Y') - $match[2];
	}
    }
  
    $field_order = strtoupper($field_order);

    $html_result = $month_result = $day_result = $year_result = "";

    if ($display_months) {
        $month_names = array();
        $month_values = array();

        for ($i = 1; $i <= 12; $i++) {
            $month_names[] = strftime($month_format, mktime(0, 0, 0, $i, 1, 2000));
            $month_values[] = strftime($month_value_format, mktime(0, 0, 0, $i, 1, 2000));
        }

        $month_result .= '<select name=';
        if (null !== $field_array){
            $month_result .= '"' . $field_array . '[' . $prefix . 'Month]"';
        } else {
            $month_result .= '"' . $prefix . 'Month"';
        }
        if (null !== $month_size){
            $month_result .= ' size="' . $month_size . '"';
        }
        if (null !== $month_extra){
            $month_result .= ' ' . $month_extra;
        }
        if (null !== $all_extra){
            $month_result .= ' ' . $all_extra;
        }
        $month_result .= '>'."\n";
        
        $month_result .= smarty_function_html_options(array('output'     => $month_names,
                                                            'values'     => $month_values,
                                                            'selected'   => $month_values[$time[1]-1],
                                                            'print_result' => false),
                                                      $smarty);
        
        $month_result .= '</select>';
    }

    if ($display_days) {
        $days = array();
        for ($i = 1; $i <= 31; $i++) {
            $days[] = sprintf($day_format, $i);
            $day_values[] = sprintf($day_value_format, $i);
        }

        $day_result .= '<select name=';
        if (null !== $field_array){
            $day_result .= '"' . $field_array . '[' . $prefix . 'Day]"';
        } else {
            $day_result .= '"' . $prefix . 'Day"';
        }
        if (null !== $day_size){
            $day_result .= ' size="' . $day_size . '"';
        }
        if (null !== $all_extra){
            $day_result .= ' ' . $all_extra;
        }
        if (null !== $day_extra){
            $day_result .= ' ' . $day_extra;
        }
        $day_result .= '>'."\n";
        $day_result .= smarty_function_html_options(array('output'     => $days,
                                                          'values'     => $day_values,
                                                          'selected'   => $time[2],
                                                          'print_result' => false),
                                                    $smarty);
        $day_result .= '</select>';
    }

    if ($display_years) {
        if (null !== $field_array){
            $year_name = $field_array . '[' . $prefix . 'Year]';
        } else {
            $year_name = $prefix . 'Year';
        }
        if ($year_as_text) {
            $year_result .= '<input type="text" name="' . $year_name . '" value="' . $time[0] . '" size="4" maxlength="4"';
            if (null !== $all_extra){
                $year_result .= ' ' . $all_extra;
            }
            if (null !== $year_extra){
                $year_result .= ' ' . $year_extra;
            }
            $year_result .= '>';
        } else {
            $years = range((int)$start_year, (int)$end_year);
            if ($reverse_years) {
                rsort($years, SORT_NUMERIC);
            }

            $year_result .= '<select name="' . $year_name . '"';
            if (null !== $year_size){
                $year_result .= ' size="' . $year_size . '"';
            }
            if (null !== $all_extra){
                $year_result .= ' ' . $all_extra;
            }
            if (null !== $year_extra){
                $year_result .= ' ' . $year_extra;
            }
            $year_result .= '>'."\n";
            $year_result .= smarty_function_html_options(array('output' => $years,
                                                               'values' => $years,
                                                               'selected'   => $time[0],
                                                               'print_result' => false),
                                                         $smarty);
            $year_result .= '</select>';
        }
    }

    // Loop thru the field_order field
    for ($i = 0; $i <= 2; $i++){
      $c = substr($field_order, $i, 1);
      switch ($c){
        case 'D':
            $html_result .= $day_result;
            break;

        case 'M':
            $html_result .= $month_result;
            break;

        case 'Y':
            $html_result .= $year_result;
            break;
      }
      // Add the field seperator
	  if($i != 2) {
      	$html_result .= $field_separator;
  	  }
    }

    return $html_result;
}
$smarty->register_function("mgw_html_select_date","smarty_function_mgw_html_select_date");
$smarty->register_modifier("addslash","addslashes");

?>
