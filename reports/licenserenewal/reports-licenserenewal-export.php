<?php
//error_reporting(0);
@include("sessioncheck.php");

/*
 This file will generate our CSV table. There is nothing to display on this page, it is simply used
 to generate our CSV file and then exit. That way we won't be re-directed after pressing the export
 to CSV button on the previous page.
*/

//First we'll generate an output variable called out. It'll have all of our text for the CSV file.
$out = '';

//Next we'll check to see if our variables posted and if they did we'll simply append them to out.
$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';

$ids = explode("~",$id);
$categoryid = $ids[0];
$schoolids = explode(",",$ids[1]);
$expdate = $ids[2];
$newdate = date("Y-m-d", strtotime($expdate));

	$name="Licence_Renewal_Report";	
	
	$csv_hdr = "";
	$out .= $csv_hdr;
	
	for($a=0;$a<sizeof($schoolids);$a++)
        {
         if($categoryid=='1')
         { 
                $qryschoolname = $ObjDB->SelectSingleValue("SELECT fld_school_name AS schoolname 
                                                                        FROM itc_school_master 
                                                                        WHERE fld_id='".$schoolids[$a]."' and fld_delstatus='0'");
                $out .= $qryschoolname;
                $out .= "\n\n";
                $out .= " , Licence Holder , Start Date , End Date , Saler Order , Auto Renewal , No Auto Renewal , Seats , ";
                $out .= "\n";
                
                $qryschoolids = $ObjDB->QueryObject("SELECT a.fld_license_name AS licname, b.fld_start_date AS sdate, b.fld_end_date AS edate, a.fld_salesorder AS selorder, b.fld_auto_renewal AS autorenewal,
                                                        b.fld_renewal_count AS noautorenewal, b.fld_no_of_users AS seats
                                                        FROM itc_license_master AS a, itc_license_track AS b
                                                        WHERE a.fld_id = b.fld_license_id and b.fld_school_id = '".$schoolids[$a]."' and b.fld_end_date <= '".$newdate."' and fld_user_id = '0' and a.fld_delstatus = '0'");
                if($qryschoolids->num_rows>0)
                {
                   while($row = $qryschoolids->fetch_assoc())
                   {
                       extract($row);
                       $stdate = date('Y-m-d', strtotime($sdate));
                       $etdate = date('Y-m-d', strtotime($edate));

                       $out .= " , ".$licname." , ".$stdate." , ".$etdate." , ".$selorder." , ".$autorenewal." , ".$noautorenewal." , ".$seats." , ";
                       $out .= "\n";
                   }
                }
            }
            else if($categoryid=='2')
            {

               $qryusername = $ObjDB->SelectSingleValue("SELECT CONCAT(fld_fname,' ',fld_lname) AS stuname 
                                                                                                   FROM itc_user_master 
                                                                                                   WHERE fld_id='".$schoolids[$a]."'");
                $out .= $qryusername;
                $out .= "\n\n";
                $out .= " , Licence Holder , Start Date , End Date , Saler Order , Auto Renewal , No Auto Renewal , Seats , ";
                $out .= "\n";
                $qryschoolids = $ObjDB->QueryObject("SELECT a.fld_license_name AS licname, b.fld_start_date AS sdate, b.fld_end_date AS edate, a.fld_salesorder AS selorder, b.fld_auto_renewal AS autorenewal,
                                                        b.fld_renewal_count AS noautorenewal, b.fld_no_of_users AS seats
                                                        FROM itc_license_master AS a, itc_license_track AS b
                                                        WHERE a.fld_id = b.fld_license_id and b.fld_school_id = '0' and b.fld_user_id = '".$schoolids[$a]."' and b.fld_end_date <= '".$newdate."' and a.fld_delstatus = '0'");
                if($qryschoolids->num_rows>0)
                {
                   while($row = $qryschoolids->fetch_assoc())
                   {
                       extract($row);
                       $stdate = date('Y-m-d', strtotime($sdate));
                       $etdate = date('Y-m-d', strtotime($edate));

                       $out .= " , ".$licname." , ".$stdate." , ".$etdate." , ".$selorder." , ".$autorenewal." , ".$noautorenewal." , ".$seats." , ";
                       $out .= "\n";
                   }
                }
            }
            else{ 

                $qrydistname = $ObjDB->SelectSingleValue("SELECT fld_district_name AS districtname 
                                                                     FROM itc_district_master 
                                                                     WHERE fld_delstatus='0' and fld_id='".$schoolids[$a]."'");
                $out .= $qrydistname;
                $out .= "\n\n";
                $out .= " , Licence Holder , Start Date , End Date , Saler Order , Auto Renewal , No Auto Renewal , Seats , ";
                $out .= "\n";
                $qryschoolids = $ObjDB->QueryObject("SELECT a.fld_license_name AS licname, b.fld_start_date AS sdate, b.fld_end_date AS edate, a.fld_salesorder AS selorder, b.fld_auto_renewal AS autorenewal,
                                                        b.fld_renewal_count AS noautorenewal, b.fld_no_of_users AS seats
                                                        FROM itc_license_master AS a, itc_license_track AS b
                                                        WHERE a.fld_id = b.fld_license_id AND b.fld_district_id = '".$schoolids[$a]."' and b.fld_end_date <= '".$newdate."' and a.fld_delstatus = '0'");
                if($qryschoolids->num_rows>0)
                {
                   while($row = $qryschoolids->fetch_assoc())
                   {
                       extract($row);
                       $stdate = date('Y-m-d', strtotime($sdate));
                       $etdate = date('Y-m-d', strtotime($edate));

                       $out .= " , ".$licname." , ".$stdate." , ".$etdate." , ".$selorder." , ".$autorenewal." , ".$noautorenewal." , ".$seats." , ";
                       $out .= "\n";
                   }
                }
            } 
    $out .="\n\n\n";
}


//Now we're ready to create a file. This method generates a filename based on the current date & time.

$filename = $name."_".date("Y-m-d_H-i",time());

include("footer.php");
//Generate the CSV file header
header('Content-Encoding: UTF-8,UTF-16LE');
header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
header("Content-Disposition: csv" . date("Y-m-d") . ".csv");
header("Content-Disposition: attachment; filename=".$filename.".csv");
echo $out;
exit;