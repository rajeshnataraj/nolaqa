<?php 

/*

VS SCORM - setValue.php 
Rev 1.1 - Wednesday, August 12, 2009
Copyright (C) 2009, Addison Robson LLC

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.	See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, 
Boston, MA	02110-1301, USA.

*/

//  essential functions
require "subs.php";

//  read database login information and connect
require "config.php";
dbConnect();

// read GET data
$varname = $_GET['varname'];
$SCOInstanceID = $_GET['SCOInstanceID'] * 1;

// read POST data
$varvalue = $_POST['varvalue'];

// save data to the 'scormvars' table
writeElement($varname,$varvalue);

// return value to the calling program
print "true";

?>