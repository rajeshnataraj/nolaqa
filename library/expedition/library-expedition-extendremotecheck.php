<?php 
/*
	Created By - Vijayalakshmi PHP Programmer
	Page - library-expedtion-extendremotecheck
	Description:
	   remote control for extend content text name to check whether entered name is already exist in db or not
	   
	History:
	 no - update

*/
@include("sessioncheck.php"); 
$date = date("Y-m-d H:i:s");
$oper= isset($method['oper']) ? $method['oper'] : '';

/*****this opertion  performs to show the form for getting extend text(insert the extend name ****/
if($oper=="checkextendname" and $oper != " " ) {
    $txtextensionname = isset($method['txtextensionname']) ? $ObjDB->EscapeStrAll($method['txtextensionname']) : '';
    $extid = isset($method['extid']) ? $method['extid'] : '0';
    $expid = isset($method['expid']) ? $method['expid'] : '0';
    
    $count = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_extendmaterials_master WHERE fld_extend_text='".$txtextensionname."' AND fld_delstatus='0' AND fld_created_by='".$uid."' AND fld_exp_id='".$expid."' AND fld_id<>'".$extid."'"); 
    if($count == 0){ echo "true"; }	else { echo "false"; }

}
	
@include("footer.php");
