<?php 

/*

VS SCORM 1.2 RTE - rte.php
Rev 2009-11-30-01
Copyright (C) 2009, Addison Robson LLC

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, 
Boston, MA	02110-1301, USA.


http://localhost/synergy/index.php*/
// read SCOInstanceID from the GET parameters
$SCOInstanceID = $_GET['SCOInstanceID'] * 1;
$lessonpath = $_GET['lessonpath'];
$lessonid = $_GET['lessonid'];
$studentid = $_GET['studentid'];
?>
<html>
<head>
	<title>VS SCORM</title>
	<script language="javascript">
		//http://development.pitsco.info/uploaddir/web-lessons/CalculatorsLesson_1340963436/Calculators Lesson/index_lms.html	
		var started = false;
	
		function loadSCO() {
		  if (! started) {
			SCO.location.href = '<?php echo $lessonpath; ?>';
		  }
		  started = true;
		}
	
		function unloadSCO() {
		  setTimeout('API.LMSFinish("");',2000);
		}
	
	  </script>
</head>
    <frameset frameborder="0" framespacing="0" border="0" rows="0,*" cols="*" onbeforeunload="unloadSCO();" onUnload="unloadSCO();" style="background-image: url(../img/questionloader.gif);
	background-repeat:no-repeat;background-position:center;">
      <frame src="api.php?SCOInstanceID=<?php print $SCOInstanceID; ?>&lessonid=<?php echo $lessonid; ?>&studentid=<?php echo $studentid; ?>" name="API" id="API" noresize onload="loadSCO();">
      <frame src="blank.html" name="SCO" id="SCO">
    </frameset><noframes></noframes>
</html>