<?php 

/*$dbname = "synergy_live";
$dbhost = "50.62.146.63";
$dbuser = "synergyliveuser";
$dbpass = "nH7s1Pw3mK8618H";*/

/*$dbname = "synergy_v2_1";
$dbhost = "50.63.157.41";
$dbuser = "userv2_1";
$dbpass = "bX31fVXt77Uwh1E";*/

$dbname = "synergyitc";
$dbhost = "ec2-54-200-195-30.us-west-2.compute.amazonaws.com";
$dbuser = "itcliveuser";
$dbpass = "Kb84R84Xg5";

function dbConnect() {

	// database login details
	global $dbname;
	global $dbhost;
	global $dbuser;
	global $dbpass;

	// link
	global $link;

	// connect to the database
	$link = mysql_connect($dbhost,$dbuser,$dbpass) or die("Not connected");
	mysql_select_db($dbname,$link);

}


?>