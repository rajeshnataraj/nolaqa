<?php
header("Content-type: text/css; charset: UTF-8");
/*header("Cache-Control: no-cache, must-revalidate");
$offset = 60 * 60 * 24 * 7;
$ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
header($ExpStr);*/

ob_start ("ob_gzhandler");
require_once("handler.php"); 
$userData = userInfo();
// base css files

require_once('synergy.css');
require_once('jquery.dataTables.css');
//require_once('gumby.hybrid.css');
//require_once('ui.css');
//require_once('style.css');
//require_once('text.css');
//require_once('style-icons.css');
//require_once('fullcalendar.css');
require_once('../jquery-ui/css/itc/jquery-ui-1.9.2.custom.min.css');
require_once('../jquery-ui/css/itc/jquery.ui.timepicker.css');

/*-- AutoComplete -- */
//require_once('TextboxList.css');
//require_once('TextboxList.Autocomplete.css');

/*-- Select Box --*/
//require_once('bootstrap-formhelpers.css');

/*-- Ajax File Upload --*/
//require_once('uploadify.css');
//require_once('synergy.master.styles.css');
//require_once('question.css');

//require_once('zebra_dialog.css');
//require_once('dyadandtriad.css');

/*-- Used for tooltip --*/
//require_once('tipsy.css');

/*-- Used for fancybox --*/
//require_once('fancybox.css');
//require_once('colorpicker.css');
//require_once('timeout-dialog.css');
//require_once('jquery.treetable.css');


require_once('sweetalert2.min.css');



echo "\n";
// addons
// browser
$filename = $userData["user_agent"].'.css';
if (file_exists($filename)) {
	echo "\n/* Included for ".$userData["user_agent"]." */\n";
	include($filename);
}
else{echo "\n/* NO EXTRA BROWSER CSS for ".$userData["user_agent"]." */\n";}
// platform
$filename = $userData["user_os"].'-'.$userData["user_agent"].'.css';
if (file_exists($filename)) {
	echo "\n/* Included for ".$userData["user_os"].'-'.$userData["user_agent"]." */\n";
	include($filename);
}
else{echo "\n/* NO EXTRA BROWSER CSS for ".$userData["user_os"].'-'.$userData["user_agent"]." */\n";}
ob_end_flush();
// brought to you by electrokami.com
// support: http://electrokami.com/how-to-combine-your-separate-css-files-into-one-using-php-and-then-compress-the-css-with-gzip
?>