<?php 
	@include("sessioncheck.php");
	
	$oper = isset($method['oper']) ? $method['oper'] : '';
/************This is use to save the event***************/
 	if($oper == "saveevent" and $oper != '')
	{	
	  try
	  {
		$eventid = isset($method['eventid']) ? $method['eventid'] : '';
		$eventtitle = isset($method['eventtitle']) ? addslashes($method['eventtitle']) : '';
		$startdate = isset($method['startdate']) ? $method['startdate'] : '';
		$starttime = isset($method['starttime']) ? $method['starttime'] : '';
		$enddate = isset($method['enddate']) ? $method['enddate'] : '';
		$endtime = isset($method['endtime']) ? $method['endtime'] : '';
		
	   $validate_eventid=true;
	   $validate_eventtitle=true;
	   
	   if($eventid!=0)
	   {
			$validate_eventid=validate_datatype($eventid,'int');
			$validate_eventtitle=validate_datas($eventtitle,'lettersonly'); 
	   }
	   
	   if($validate_eventid and $validate_eventtitle)
	   {
	  
		if($eventid!=0 || $eventid!="undefined" )
		{
			$ObjDB->NonQuery("UPDATE itc_calendar_master SET fld_app_name='".$eventtitle."',fld_startdate='".date('Y-m-d',strtotime($startdate))."',fld_starttime='".date('H:i:s',strtotime($starttime))."', fld_enddate='".date('Y-m-d',strtotime($enddate))."',fld_endtime='".date('H:i:s',strtotime($endtime))."', fld_updated_by='".$uid."', fld_updated_date='".date('Y-m-d H:i:s')."' WHERE fld_id='".$eventid."' and fld_delstatus='0'");
			
			echo "success";
		}
		else
		{
			$ObjDB->NonQuery("INSERT INTO itc_calendar_master (fld_app_name,fld_startdate,fld_starttime,fld_enddate,fld_endtime,fld_created_by,fld_created_date) VALUES ('".$eventtitle."','".date('Y-m-d',strtotime($startdate))."','".date('H:i:s',strtotime($starttime))."','".date('Y-m-d',strtotime($enddate))."','".date('H:i:s',strtotime($endtime))."','".$uid."',fld_created_date='".date('Y-m-d H:i:s')."')");
			
			echo "success";
		}
		}
		else
		{
			echo "fail";		
		}
		}
		catch(Exception $e)
		{
			 echo "fail";
		}
	} 
/************Update for time*******It will update the time only*********/
	if($oper == "updatetime" and $oper != '')
	{
		$eventid = isset($method['eventid']) ? $method['eventid'] : '';
		$endtime = isset($method['endtime']) ? $method['endtime'] : '';
		
		$res_endhour= $ObjDB->SelectSinglevalue("SELECT fld_endtime FROM itc_calendar_master WHERE fld_id='".$eventid."' ");
	
		$timemin = strtotime("+".$endtime." minute", strtotime($res_endhour));
		$closemin = date("H:i:s", $timemin); 
		
		$ObjDB->NonQuery("UPDATE itc_calendar_master SET fld_endtime='".$closemin."', fld_updated_by='".$uid."', fld_updated_date='".date('Y-m-d H:i:s')."' WHERE fld_id='".$eventid."' AND fld_delstatus='0'");
	}
/************Update for date *******It willl update the endate only************/
	if($oper == "updatedate" and $oper != '')
	{	
		$enddate = isset($method['enddate']) ? $method['enddate'] : '';
		$eventid = isset($method['eventid']) ? $method['eventid'] : '';
		echo $enddate;
		
		$ObjDB->NonQuery("UPDATE itc_calendar_master SET fld_enddate='".date('Y-m-d',strtotime($enddate))."', fld_updated_by='".$uid."', fld_updated_date='".date('Y-m-d H:i:s')."' WHERE fld_id='".$eventid."' AND fld_delstatus='0'");
		
	}
/***********For drag and drop Month view****It will update the days only************/
	
	if($oper == "updateday" and $oper != '')
	{	
		
		$eventid = isset($method['eventid']) ? $method['eventid'] : '';
		$startdate = isset($method['datestart']) ? $method['datestart'] : '';
		$enddate = isset($method['dateend']) ? $method['dateend'] : '';
		
		$ObjDB->NonQuery("UPDATE itc_calendar_master SET fld_startdate='".date('Y-m-d',strtotime($startdate))."',fld_enddate='".date('Y-m-d',strtotime($enddate))."', fld_updated_by='".$uid."', fld_updated_date='".date('Y-m-d H:i:s')."' WHERE fld_id='".$eventid."' AND fld_delstatus='0'");
	}
	
/**************Update the startday& endtime as well as its times *********************/
	
	if($oper == "updatehour" and $oper != '')
	{	
		
		$eventid = isset($method['eventid']) ? $method['eventid'] : '';
		$startdate = isset($method['datestart']) ? $method['datestart'] : '';
		$enddate = isset($method['dateend']) ? $method['dateend'] : '';
		
		$ObjDB->NonQuery( "UPDATE itc_calendar_master SET fld_startdate='".date('Y-m-d',strtotime($startdate))."',fld_starttime='".date('H:i:s',strtotime($startdate))."', fld_enddate='".date('Y-m-d',strtotime($enddate))."', fld_endtime='".date('H:i:s',strtotime($enddate))."', fld_updated_by='".$uid."', fld_updated_date='".date('Y-m-d  H:i:s')."' WHERE fld_id='".$eventid."' AND fld_delstatus='0'" );
	}
	
/*********This is for delete the event***************/
	
	if($oper == "deleteevent" and $oper != '')
	{	
		$eventid = isset($method['eventid']) ? $method['eventid'] : '';
		$ObjDB->NonQuery( "UPDATE itc_calendar_master SET fld_delstatus='1', fld_deleted_by='".$uid."', fld_deleted_date='".date('Y-m-d  H:i:s')."' WHERE fld_id='".$eventid."'");
	}

	@include("footer.php");