<?php
header("Content-type: text/javascript; charset: UTF-8");
/*header("Cache-Control: no-cache, must-revalidate");
$offset = 60 * 60 * 24 * 7;
$ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
header($ExpStr);*/

ob_start ("ob_gzhandler");
require_once('config.php');
require_once("javascript_constants.php");
require_once("handler.php"); 
//$userData = userInfo();
// base css files

require_once('../js/jquery.js');
require_once('../jquery-ui/js/jquery-1.8.3.min.js');
require_once('../jquery-ui/js/jquery-ui-1.9.2.custom.min.js');
require_once('../jquery-ui/js/jquery.ui.timepicker.js');
require_once('CheckInternetConnection.js');
require_once('jquery.clock.js');
require_once('idletimer.js');
require_once('libs/gumby.js');
require_once('plugins.js');
require_once('main.js');


/** for validate the form data***/
require_once('jquery.validate.js');
require_once('jquery.validate.additional.js');

/*-- AutoComplete -- */
require_once('GrowingInput.js');
require_once('TextboxList.js');
require_once('TextboxList.Autocomplete.js');

require_once('bootstrap-formhelpers-selectbox.js');

/*-- Ajax File Upload --*/

//require_once('http://www.dariowiz.com/scripts/jquery.uploadify3.1Fixed.js');


/*--use for the tooltip  --*/
require_once('jquery.tipsy.js');
require_once('slimScroll.min.js');

/**--Showing alerts in the page-**/
require_once('zebra_dialog.js');

/**--Fixedheader table-**/
require_once('jquery.fixedheadertable.js');
require_once('../fancybox/jquery.mousewheel-3.0.4.pack.js');
require_once('../fancybox/jquery.fancybox-1.3.4.pack.js');
require_once('jquery.treetable.js');

/**--Showing phone number mask in the page-**/
require_once('jquery.mask.js');
require_once('growingautoinputtext.js');

require_once('fullcalendar.min.js');

/**using for selecting color***/
require_once('colorpicker.js');


/**using for assement engime time counter***/
require_once('jquery.countdown.js');

require_once('jquery.uploadify.min.js');


require_once('timeout-dialog.js');

/***  using for Booklet plugins**/
require_once('modernizr.custom.js');
require_once('jquery.bookblock.js');
require_once('sweetalert2.min.js');
require_once('jquery.dataTables.js');
ob_end_flush();
?>
