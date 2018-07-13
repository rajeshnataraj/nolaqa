<?php 

/*

VS SCORM - initialize.php 
Rev 1.5 - Wednesday, August 12, 2009
Copyright (C) 2009, Addison Robson LLC

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, 
Boston, MA  02110-1301, USA.

*/

//  essential functions
require "subs.php";

//  read database login information and connect
require "config.php";
dbConnect();

// read GET data
$SCOInstanceID = $_GET['SCOInstanceID'] * 1;

// ------------------------------------------------------------------------------------
// elements that tell the SCO which other elements are supported by this API
initializeElement('cmi.core._children','student_id,student_name,lesson_location,credit,lesson_status,entry,score,total_time,exit,session_time');
initializeElement('cmi.core.score._children','raw');

// student information
initializeElement('cmi.core.student_name',getFromLMS('cmi.core.student_name'));
initializeElement('cmi.core.student_id',getFromLMS('cmi.core.student_id'));

// mastery test score from IMS manifest file
initializeElement('adlcp:masteryscore',getFromLMS('adlcp:masteryscore'));

// SCO launch data from IMS manifest file 
initializeElement('cmi.launch_data',getFromLMS('cmi.launch_data'));

// progress and completion tracking
initializeElement('cmi.core.credit','credit');
initializeElement('cmi.core.lesson_status','not attempted');
initializeElement('cmi.core.entry','ab initio');

// total seat time
initializeElement('cmi.core.total_time','0000:00:00');

// new session so clear pre-existing session time
clearElement('cmi.core.session_time');

// ------------------------------------------------------------------------------------
// return value to the calling program
print "true";
die;

?>