<?php
@include("sessioncheck.php");
/*
* Created By:Narendrakumar (Team Leader)
* Created On: 03/19/2015
* Description: Display students module score
*id[0] = district id
*id[1] = schoolid;
*id[2] = classid;
*id[3] = scheduleid;
*id[4] = moduleid;
*id[5] = typename;
*id[6]= stuname;
*id[7]=stuid */

$method = $_REQUEST;
$id = isset($method['id']) ? $method['id'] : '0';
$studentid = isset($method['studentlist']) ? $method['studentlist'] : '0';
$id = explode(",",$id);


$sessids[] ='';
$scheduletype=$id[5];
$scheduleid=$id[3];
$schoolid=$id[1];

$qrystu=$ObjDB->QueryObject("SELECT fld_student_id as studentid from itc_module_points_master where fld_schedule_id='".$scheduleid."' and fld_module_id='".$id[4]."' and fld_student_id in (".$studentid.") and fld_delstatus='0' group by fld_student_id");

$stuid=array();
if($qrystu->num_rows>0)
{
    while($stu=$qrystu->fetch_assoc())
    {
        extract($stu);
        
        $stuid[]=$studentid;
        
        
    }
}

require_once '../../PHPExcel.php';
require_once '../../PHPExcel/IOFactory.php';

if($id[5]==4 || $id[5]==6)
{
    $newmodid = $id[4];
    
}
else
{
    $newmodid = $id[4];

}




// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
PHPExcel_Shared_Font::setAutoSizeMethod(PHPExcel_Shared_Font::AUTOSIZE_METHOD_EXACT);
$sheet_count = 0;


		$sessiontest = 0;
	
// Add tab label to the sheet
	
	    $objPHPExcel->getActiveSheet()->setTitle('Module Score');
	    $headerpart = "Module Score";
	

$qryschedules = $ObjDB->QueryObject("SELECT fld_session_id
                                    FROM itc_module_points_master 
                                    WHERE fld_module_id='".$newmodid."' AND fld_schedule_id='".$id[3]."' 
                                        AND fld_schedule_type='".$id[5]."' AND fld_session_id IN(0,6) AND fld_delstatus='0'");

$count=0;



if($qryschedules->num_rows > 0)
{ 
$row = 1; // 1-based index


      // Get Module guide points possible //

        $moduleguide=$ObjDB->SelectSingleValueInt("SELECT fld_points_possible  from 
                                              itc_module_points_master 
                                             where fld_schedule_id='".$id[3]."' and fld_module_id='".$newmodid."'  and fld_session_id='0' and fld_type='0' and fld_delstatus='0' and fld_schedule_type='".$scheduletype."'");
        
        $modgpp="Module Guide (".$moduleguide.")";
        
        // End //
        
        // Get RCA points possible //
        
        if($scheduletype<5) //5
			{
				if($scheduletype=='4')
                                    $newtype = 2;
				else
                                    $newtype = 1;
                                
                                
                                
                                $qry = $ObjDB->QueryObject("SELECT fld_points AS pointspossible 
											FROM itc_module_wca_grade 
											WHERE fld_session_id in (1,2,3,4) AND fld_flag='1' AND fld_module_id='".$newmodid."' AND fld_school_id='".$id[1]."' 
												AND fld_user_id='0'  AND fld_type='0' AND fld_schedule_type='".$newtype."'");
                                
				
				if($qry->num_rows <= 0)
				{
					$createdids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_id) FROM `itc_user_master` WHERE fld_profile_id IN (2,3) AND fld_delstatus='0'");
					
					$qry = $ObjDB->QueryObject("SELECT fld_points AS pointspossible 
												FROM itc_module_wca_grade 
												WHERE fld_session_id in (1,2,3,4) AND fld_flag='1' AND fld_module_id='".$newmodid."' AND fld_created_by IN (".$createdids.")
													 AND fld_type='0' AND fld_schedule_type='".$newtype."'");
					if($qry->num_rows <= 0)
					{
                                            
						$qry = $ObjDB->QueryObject("SELECT fld_points AS pointspossible 
													FROM itc_module_grade 
													WHERE fld_session_id in (1,2,3,4) AND fld_flag='1' AND fld_module_id='".$newmodid."'");
					}
				}
			}
			else
			{
				if($scheduletype==6)
					$newtype = 2;
				else if($scheduletype==7)
					$newtype = 7;
				else
					$newtype = 1;
					
				$qry = $ObjDB->QueryObject("SELECT fld_points AS pointspossible 
											FROM itc_module_wca_grade 
											WHERE fld_session_id in (1,2,3,4) AND fld_flag='1' AND fld_module_id='".$newmodid."'	
												AND fld_schedule_id='".$scheduleid."'  AND fld_type='0'");
				if($qry->num_rows <= 0)
				{
					$qry = $ObjDB->QueryObject("SELECT fld_points AS pointspossible 
											FROM itc_module_wca_grade 
											WHERE fld_session_id in (1,2,3,4) AND fld_user_id='0' AND fld_module_id='".$newmodid."' AND fld_school_id='".$schoolid."' 
												AND fld_schedule_type='".$newtype."'  AND fld_schedule_id='".$scheduleid."'
												AND fld_type='0' AND fld_flag='1' ");
												
					if($qry->num_rows <= 0)
					{							
						$createdids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_id) FROM `itc_user_master` WHERE fld_profile_id IN (2,3) AND fld_delstatus='0'");
						
						$qry = $ObjDB->QueryObject("SELECT fld_points AS pointspossible
													FROM itc_module_wca_grade 
													WHERE fld_session_id in (1,2,3,4) AND fld_flag='1' AND fld_module_id='".$newmodid."' AND fld_created_by IN (".$createdids.")
														AND fld_schedule_id='".$scheduleid."'  AND fld_schedule_type='".$newtype."' AND fld_type='0'");
					}
				}
			}
			
			if($qry->num_rows <= 0)
			{
				if($scheduletype!=7) //5
					$qry = $ObjDB->QueryObject("SELECT fld_points AS pointspossible
												FROM itc_module_grade 
												WHERE fld_session_id in (1,2,3,4) AND fld_flag='1' AND fld_module_id='".$newmodid."'");
				else if($scheduletype==7)
					$qry = $ObjDB->QueryObject("SELECT fld_points AS pointspossible
												FROM itc_module_quest_details 
												WHERE fld_section_id in (1,2,3,4) AND fld_flag='1' AND fld_module_id='".$newmodid."'");
			}
                        
                        if($qry->num_rows > 0)
                        {
                            $rcapp=0;
                            while($rowrcap=$qry->fetch_assoc())
                            {
                                extract($rowrcap);
                                $rcapp=$rcapp+$pointspossible;
                            }
                        }
                        
                        $RCAPP="RCA (".$rcapp.")";
                        
                   /************   RCA End *******************/
                        
                  /************* // Get ATTENDANCE points possible //  *********************/    
                        
                        if($scheduletype<5) //5
			{
				if($scheduletype=='4')
                                    $newtype = 2;
				else
                                    $newtype = 1;
                                
                                
                                
                                $qryatten = $ObjDB->QueryObject("SELECT fld_points AS pointspossible 
											FROM itc_module_wca_grade 
											WHERE fld_session_id='0' AND fld_flag='1' AND fld_module_id='".$newmodid."' AND fld_school_id='".$id[1]."' 
												AND fld_user_id='0'  AND fld_type='1' AND fld_schedule_type='".$newtype."'");
                                
				
				if($qryatten->num_rows <= 0)
				{
					$createdids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_id) FROM `itc_user_master` WHERE fld_profile_id IN (2,3) AND fld_delstatus='0'");
					
					$qryatten = $ObjDB->QueryObject("SELECT fld_points AS pointspossible 
												FROM itc_module_wca_grade 
												WHERE fld_session_id='0' AND fld_flag='1' AND fld_module_id='".$newmodid."' AND fld_created_by IN (".$createdids.")
													 AND fld_type='1' AND fld_schedule_type='".$newtype."'");
					if($qryatten->num_rows <= 0)
					{
                                            
						$qryatten = $ObjDB->QueryObject("SELECT fld_points_possible AS pointspossible 
													FROM itc_module_performance_master 
													WHERE  fld_module_id='".$newmodid."' and fld_delstatus='0' and fld_performance_name='Attendance' group by fld_module_id");
					}
				}
			}
			else
			{
				if($scheduletype==6)
					$newtype = 2;
				else if($scheduletype==7)
					$newtype = 7;
				else
					$newtype = 1;
					
				$qryatten = $ObjDB->QueryObject("SELECT fld_points AS pointspossible 
											FROM itc_module_wca_grade 
											WHERE fld_session_id='0' AND fld_flag='1' AND fld_module_id='".$newmodid."'	
												AND fld_schedule_id='".$scheduleid."'  AND fld_type='1'");
				if($qryatten->num_rows <= 0)
				{
					$qryatten = $ObjDB->QueryObject("SELECT fld_points AS pointspossible 
											FROM itc_module_wca_grade 
											WHERE fld_session_id='0' AND fld_user_id='0' AND fld_module_id='".$newmodid."' AND fld_school_id='".$schoolid."' 
												AND fld_schedule_type='".$newtype."'  AND fld_schedule_id='".$scheduleid."'
												AND fld_type='1' AND fld_flag='1' ");
												
					if($qryatten->num_rows <= 0)
					{							
						$createdids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_id) FROM `itc_user_master` WHERE fld_profile_id IN (2,3) AND fld_delstatus='0'");
						
						$qryatten = $ObjDB->QueryObject("SELECT fld_points AS pointspossible
													FROM itc_module_wca_grade 
													WHERE fld_session_id='0' AND fld_flag='1' AND fld_module_id='".$newmodid."' AND fld_created_by IN (".$createdids.")
														AND fld_schedule_id='".$scheduleid."'  AND fld_schedule_type='".$newtype."' AND fld_type='1'");
					}
				}
			}
			
			if($qryatten->num_rows <= 0)
			{
				if($scheduletype!=7) //5
					$qryatten = $ObjDB->QueryObject("SELECT fld_points_possible AS pointspossible 
													FROM itc_module_performance_master 
													WHERE  fld_module_id='".$newmodid."' ans fld_delstatus='0' and fld_performance_name='Attendance' group by fld_module_id");
				
			}
                        
                        if($qryatten->num_rows > 0)
                        {
                            $attendancepp=0;
                            while($rowattpp=$qryatten->fetch_assoc())
                            {
                                extract($rowattpp);
                                $attendancepp=$pointspossible*7;
                            }
                        }
                        
                        $ATTENDANCEPP="Attendance (".$attendancepp.")";
                        
                        /************   ATTENDANCE End *******************/
                        
                        /************* // Get PARTICIPATION points possible //  *********************/  
                        
                        if($scheduletype<5) //5
			{
				if($scheduletype=='4')
                                    $newtype = 2;
				else
                                    $newtype = 1;
                                
                                
                                
                                $qryattenp = $ObjDB->QueryObject("SELECT fld_points AS pointspossible 
											FROM itc_module_wca_grade 
											WHERE fld_session_id='0' AND fld_flag='1' AND fld_module_id='".$newmodid."' AND fld_school_id='".$id[1]."' 
												AND fld_user_id='0'  AND fld_type='2' AND fld_schedule_type='".$newtype."'");
                                
				
				if($qryattenp->num_rows <= 0)
				{
					$createdids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_id) FROM `itc_user_master` WHERE fld_profile_id IN (2,3) AND fld_delstatus='0'");
					
					$qryattenp = $ObjDB->QueryObject("SELECT fld_points AS pointspossible 
												FROM itc_module_wca_grade 
												WHERE fld_session_id='0' AND fld_flag='1' AND fld_module_id='".$newmodid."' AND fld_created_by IN (".$createdids.")
													 AND fld_type='2' AND fld_schedule_type='".$newtype."'");
					if($qryattenp->num_rows <= 0)
					{
                                            
						$qryattenp = $ObjDB->QueryObject("SELECT fld_points_possible AS pointspossible 
													FROM itc_module_performance_master 
													WHERE  fld_module_id='".$newmodid."' and fld_delstatus='0' and fld_performance_name='Participation' group by fld_module_id");
					}
				}
			}
			else
			{
				if($scheduletype==6)
					$newtype = 2;
				else if($scheduletype==7)
					$newtype = 7;
				else
					$newtype = 1;
					
				$qryattenp = $ObjDB->QueryObject("SELECT fld_points AS pointspossible 
											FROM itc_module_wca_grade 
											WHERE fld_session_id='0' AND fld_flag='1' AND fld_module_id='".$newmodid."'	
												AND fld_schedule_id='".$scheduleid."'  AND fld_type='2'");
				if($qryattenp->num_rows <= 0)
				{
					$qryattenp = $ObjDB->QueryObject("SELECT fld_points AS pointspossible 
											FROM itc_module_wca_grade 
											WHERE fld_session_id='0' AND fld_user_id='0' AND fld_module_id='".$newmodid."' AND fld_school_id='".$schoolid."' 
												AND fld_schedule_type='".$newtype."'  AND fld_schedule_id='".$scheduleid."'
												AND fld_type='2' AND fld_flag='1' ");
												
					if($qryattenp->num_rows <= 0)
					{							
						$createdids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_id) FROM `itc_user_master` WHERE fld_profile_id IN (2,3) AND fld_delstatus='0'");
						
						$qryattenp = $ObjDB->QueryObject("SELECT fld_points AS pointspossible
													FROM itc_module_wca_grade 
													WHERE fld_session_id='0' AND fld_flag='1' AND fld_module_id='".$newmodid."' AND fld_created_by IN (".$createdids.")
														AND fld_schedule_id='".$scheduleid."'  AND fld_schedule_type='".$newtype."' AND fld_type='2'");
					}
				}
			}
			
			if($qryatten->num_rows <= 0)
			{
				if($scheduletype!=7) //5
					$qryatten = $ObjDB->QueryObject("SELECT fld_points_possible AS pointspossible 
													FROM itc_module_performance_master 
													WHERE  fld_module_id='".$newmodid."' ans fld_delstatus='0' and fld_performance_name='Participation' group by fld_module_id");
				
			}
                        
                        if($qryattenp->num_rows > 0)
                        {
                            $participationpp=0;
                            while($rowpartipp=$qryattenp->fetch_assoc())
                            {
                                extract($rowpartipp);
                                $participationpp=$pointspossible*7;
                            }
                        }
                        
                        $PARTICIPATIONPP="Participation (".$participationpp.")";
                        
                        /************   PARTICIPATION End *******************/
                        
                        /************   PERFORMANCE ASSESSMENT START *******************/
                        
                        
                        if($scheduletype<5) //5
			{
				if($scheduletype=='4')
                                    $newtype = 2;
				else
                                    $newtype = 1;
                                
                                
                                
                                $qrypass = $ObjDB->QueryObject("SELECT fld_points AS pointspossible 
											FROM itc_module_wca_grade 
											WHERE fld_session_id='0' AND fld_flag='1' AND fld_module_id='".$newmodid."' AND fld_school_id='".$id[1]."' 
												AND fld_user_id='0'  AND fld_type='3' AND fld_schedule_type='".$newtype."'");
                                
				
				if($qrypass->num_rows <= 0)
				{
					$createdids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_id) FROM `itc_user_master` WHERE fld_profile_id IN (2,3) AND fld_delstatus='0'");
					
					$qrypass = $ObjDB->QueryObject("SELECT fld_points AS pointspossible 
												FROM itc_module_wca_grade 
												WHERE fld_session_id='0' AND fld_flag='1' AND fld_module_id='".$newmodid."' AND fld_created_by IN (".$createdids.")
													 AND fld_type='3' AND fld_schedule_type='".$newtype."'");
					if($qrypass->num_rows <= 0)
					{
                                            
						$qrypass = $ObjDB->QueryObject("SELECT fld_points_possible AS pointspossible 
													FROM itc_module_performance_master 
													WHERE  fld_module_id='".$newmodid."' and fld_delstatus='0' and fld_performance_name<>'Attendance' and fld_performance_name<>'Participation' and fld_performance_name<>'Total Pages'");
					}
				}
			}
			else
			{
				if($scheduletype==6)
					$newtype = 2;
				else if($scheduletype==7)
					$newtype = 7;
				else
					$newtype = 1;
					
				$qrypass = $ObjDB->QueryObject("SELECT fld_points AS pointspossible 
											FROM itc_module_wca_grade 
											WHERE fld_session_id='0' AND fld_flag='1' AND fld_module_id='".$newmodid."'	
												AND fld_schedule_id='".$scheduleid."'  AND fld_type='3'");
				if($qrypass->num_rows <= 0)
				{
					$qrypass = $ObjDB->QueryObject("SELECT fld_points AS pointspossible 
											FROM itc_module_wca_grade 
											WHERE fld_session_id='0' AND fld_user_id='0' AND fld_module_id='".$newmodid."' AND fld_school_id='".$schoolid."' 
												AND fld_schedule_type='".$newtype."'  AND fld_schedule_id='".$scheduleid."'
												AND fld_type='3' AND fld_flag='1' ");
												
					if($qrypass->num_rows <= 0)
					{							
						$createdids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_id) FROM `itc_user_master` WHERE fld_profile_id IN (2,3) AND fld_delstatus='0'");
						
						$qrypass = $ObjDB->QueryObject("SELECT fld_points AS pointspossible
													FROM itc_module_wca_grade 
													WHERE fld_session_id='0' AND fld_flag='1' AND fld_module_id='".$newmodid."' AND fld_created_by IN (".$createdids.")
														AND fld_schedule_id='".$scheduleid."'  AND fld_schedule_type='".$newtype."' AND fld_type='3'");
					}
				}
			}
			
			if($qrypass->num_rows <= 0)
			{
				if($scheduletype!=7) //5
					$qrypass = $ObjDB->QueryObject("SELECT fld_points_possible AS pointspossible 
													FROM itc_module_performance_master 
													WHERE  fld_module_id='".$newmodid."' and fld_delstatus='0' and fld_performance_name<>'Attendance' and fld_performance_name<>'Participation' and fld_performance_name<>'Total Pages'");
				
			}
                        
                        if($qrypass->num_rows > 0)
                        {
                            $performancepp=0;
                            while($rowperpp=$qrypass->fetch_assoc())
                            {
                                extract($rowperpp);
                                $performancepp=$performancepp+$pointspossible;
                            }
                        }
                        
                        $PERFORMANCEPP="Performance Assessment (".$performancepp.")";
                        
                        /************   PERFORMANCE ASSESSMENT END *******************/
                        
                        
                        // Get POST TEST points possible //
        
        if($scheduletype<5) //5
			{
				if($scheduletype=='4')
                                    $newtype = 2;
				else
                                    $newtype = 1;
                                
                                
                                
                                $qryposttest = $ObjDB->QueryObject("SELECT fld_points AS pointspossible 
											FROM itc_module_wca_grade 
											WHERE fld_session_id='6' AND fld_flag='1' AND fld_module_id='".$newmodid."' AND fld_school_id='".$id[1]."' 
												AND fld_user_id='0'  AND fld_type='0' AND fld_schedule_type='".$newtype."'");
                                
				
				if($qryposttest->num_rows <= 0)
				{
					$createdids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_id) FROM `itc_user_master` WHERE fld_profile_id IN (2,3) AND fld_delstatus='0'");
					
					$qryposttest = $ObjDB->QueryObject("SELECT fld_points AS pointspossible 
												FROM itc_module_wca_grade 
												WHERE fld_session_id='6' AND fld_flag='1' AND fld_module_id='".$newmodid."' AND fld_created_by IN (".$createdids.")
													 AND fld_type='0' AND fld_schedule_type='".$newtype."'");
					if($qryposttest->num_rows <= 0)
					{
                                            
						$qryposttest = $ObjDB->QueryObject("SELECT fld_points AS pointspossible 
													FROM itc_module_grade 
													WHERE fld_session_id='6' AND fld_flag='1' AND fld_module_id='".$newmodid."'");
					}
				}
			}
			else
			{
				if($scheduletype==6)
					$newtype = 2;
				else if($scheduletype==7)
					$newtype = 7;
				else
					$newtype = 1;
					
				$qryposttest = $ObjDB->QueryObject("SELECT fld_points AS pointspossible 
											FROM itc_module_wca_grade 
											WHERE fld_session_id='6' AND fld_flag='1' AND fld_module_id='".$newmodid."'	
												AND fld_schedule_id='".$scheduleid."'  AND fld_type='0'");
				if($qryposttest->num_rows <= 0)
				{
					$qryposttest = $ObjDB->QueryObject("SELECT fld_points AS pointspossible 
											FROM itc_module_wca_grade 
											WHERE fld_session_id='6' AND fld_user_id='0' AND fld_module_id='".$newmodid."' AND fld_school_id='".$schoolid."' 
												AND fld_schedule_type='".$newtype."'  AND fld_schedule_id='".$scheduleid."'
												AND fld_type='0' AND fld_flag='1' ");
												
					if($qryposttest->num_rows <= 0)
					{							
						$createdids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_id) FROM `itc_user_master` WHERE fld_profile_id IN (2,3) AND fld_delstatus='0'");
						
						$qryposttest = $ObjDB->QueryObject("SELECT fld_points AS pointspossible
													FROM itc_module_wca_grade 
													WHERE fld_session_id='6' AND fld_flag='1' AND fld_module_id='".$newmodid."' AND fld_created_by IN (".$createdids.")
														AND fld_schedule_id='".$scheduleid."'  AND fld_schedule_type='".$newtype."' AND fld_type='0'");
					}
				}
			}
			
			if($qryposttest->num_rows <= 0)
			{
				if($scheduletype!=7) //5
					$qryposttest = $ObjDB->QueryObject("SELECT fld_points AS pointspossible
												FROM itc_module_grade 
												WHERE fld_session_id='6' AND fld_flag='1' AND fld_module_id='".$newmodid."'");
				else if($scheduletype==7)
					$qryposttest = $ObjDB->QueryObject("SELECT fld_points AS pointspossible
												FROM itc_module_quest_details 
												WHERE fld_section_id='6' AND fld_flag='1' AND fld_module_id='".$newmodid."'");
			}
                        
                        if($qryposttest->num_rows > 0)
                        {
                            $posttestpp=0;
                            while($rowpostpp=$qryposttest->fetch_assoc())
                            {
                                extract($rowpostpp);
                                $posttestpp=$posttestpp+$pointspossible;
                            }
                        }
                        
                        $POSTTESTPP="Posttest (".$posttestpp.")";
                        
                   /************   POST TEST End *******************/
                        

                        
                        if($id[6]==1 and $id[7]==0)
                        {
                            $title=array('Student Name',$modgpp,$RCAPP,$PERFORMANCEPP,$PARTICIPATIONPP,$ATTENDANCEPP,$POSTTESTPP);
                        }
                        else if($id[7]==1 and $id[6]==0)
                        {
                            $title=array('ID',$modgpp,$RCAPP,$PERFORMANCEPP,$PARTICIPATIONPP,$ATTENDANCEPP,$POSTTESTPP);
                        }
                        else if($id[7]==1 and $id[6]==1)
                        {
                            $title=array('ID','Student Name',$modgpp,$RCAPP,$PERFORMANCEPP,$PARTICIPATIONPP,$ATTENDANCEPP,$POSTTESTPP);
                        }

	for ($x = 0; $x <=2; $x++) {
	     $col = 0;
		if($row == 1) {
                    if($scheduletype==1)
                    {
                        $modname=$ObjDB->SelectSingleValue("SELECT  CONCAT(a.fld_module_name, ' ', b.fld_version) AS modulename
										 
							  FROM itc_module_master AS a 
							  LEFT JOIN itc_module_version_track AS b  ON b.fld_mod_id = a.fld_id 
							  WHERE a.fld_delstatus = '0' AND  b.fld_delstatus = '0' AND a.fld_id='".$id[4]."'");
                    }
                    else
                    {
                        $modname=$ObjDB->SelectSingleValue("SELECT CONCAT(a.fld_mathmodule_name,' ',b.fld_version) AS mathmodulename
				   		FROM itc_mathmodule_master  a 
						LEFT JOIN itc_module_version_track b ON  a.fld_module_id = b.fld_mod_id
						WHERE a.fld_delstatus='0' AND b.fld_delstatus='0' AND b.fld_mod_id='".$id[4]."'");
    
                    }
		    $value=$modname;
		    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, 0, $value);
		    $range = $objPHPExcel->setActiveSheetIndex($sheet_count)->calculateWorksheetDimension();

		    $objPHPExcel->setActiveSheetIndex($sheet_count)->mergeCells($range);
		    $objPHPExcel->getActiveSheet()->setCellValue(A1, $value);
		    $objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);
		 
		    $style = array('font' =>
				                    array('color' =>
				                      array('rgb' => '000000'),
				                      'bold' => true,
				                    ),
				           'alignment' => array(
				                            
				                      'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				                      'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
				                        ),
				     );
		    $objPHPExcel->getActiveSheet()->getStyle($range)->applyFromArray($style);
		    $objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(25);
		}  // ends of if($row == 1)
		elseif($row == 2) {
			for($c=0;$c<8;$c++) {
				$Questnid = $title[$c];
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($c, $row, $Questnid);
				$style = array('font' =>
				                    array('color' =>
				                      array('rgb' => '000000'),
				                      'bold' => true,
				                    ),
				           'alignment' => array(
				                            
				                      'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				                      'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
				                        ),
				     );
				$range = $objPHPExcel->setActiveSheetIndex($sheet_count)->calculateWorksheetDimension();
				$objPHPExcel->getActiveSheet()->getStyle($range)->applyFromArray($style);
				$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(20);

			}

		}  // ends of elseif($row == 2)
		elseif($row == 3) {
                             for($i=0;$i<sizeof($stuid);$i++)
                             {
                                 
                                $stuname=$ObjDB->SelectSingleValue("SELECT concat(fld_fname,' ',fld_lname) FROM itc_user_master WHERE fld_id='".$stuid[$i]."' AND fld_delstatus='0'");
                                
                                $moduleguideearned=$ObjDB->SelectSingleValueInt("SELECT (SELECT CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' 
                                  THEN fld_teacher_points_earned END) AS pointsearned  from 
                                              itc_module_points_master 
                                             where fld_schedule_id='".$id[3]."' and fld_module_id='".$newmodid."'  and fld_session_id='0' and fld_type='0' and fld_student_id='".$stuid[$i]."' and fld_delstatus='0' and fld_schedule_type='".$scheduletype."'");
                                
                                
                                $rca=$ObjDB->QueryObject("select (SELECT CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' 
                                  THEN fld_teacher_points_earned END) AS pointsearned from itc_module_points_master where fld_schedule_id='".$id[3]."' and fld_module_id='".$id[4]."' and fld_session_id NOT IN(0,6) and fld_student_id='".$stuid[$i]."' and fld_session_id<>'0'  and fld_type='0' and fld_delstatus='0' and fld_schedule_type='".$scheduletype."'");
                                
                                   
                                   
                                   if($rca->num_rows>0)
                                   {
                                       $pointspossible=0;
                                       $earned=0;
                                       while($rowrca=$rca->fetch_assoc())
                                       {
                                           extract($rowrca);
                                           
                                           $earned=$earned+$pointsearned;
                                           
                                       }
                                   }
                                   else
                                   {
                                          $earned=''; 
                                   }
                                   
                                   $pass=$ObjDB->QueryObject("select (SELECT CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' 
                                  THEN fld_teacher_points_earned END) AS pointsearned from itc_module_points_master where fld_schedule_id='".$id[3]."' and fld_module_id='".$id[4]."' and fld_student_id='".$stuid[$i]."' and fld_type='3' and fld_delstatus='0' and fld_schedule_type='".$scheduletype."'");
                                
                                   
                                   if($pass->num_rows>0)
                                   {
                                       $pointspossible=0;
                                       $passearned=0;
                                       while($rowpass=$pass->fetch_assoc())
                                       {
                                           extract($rowpass);
                                           
                                           
                                           $passearned=$passearned+$pointsearned;
                                           
                                       }
                                   }
                                   else
                                   {
                                       $passearned='';
                                   }
                                   
                                   
                                   $participation=$ObjDB->QueryObject("select (SELECT CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' 
                                  THEN fld_teacher_points_earned END) AS pointsearned from itc_module_points_master where fld_schedule_id='".$id[3]."' and fld_module_id='".$id[4]."' and fld_student_id='".$stuid[$i]."' and fld_type='2' and fld_delstatus='0' and fld_schedule_type='".$scheduletype."'");
                                
                                   if($participation->num_rows>0)
                                   {
                                       $pointspossible=0;
                                       $partiearned=0;
                                       while($rowparticipation=$participation->fetch_assoc())
                                       {
                                           extract($rowparticipation);
                                          
                                           $partiearned=$partiearned+$pointsearned;
                                           
                                       }
                                   }
                                   else
                                   {
                                       $partiearned='';
                                   }
                                   
                                   $attendance=$ObjDB->QueryObject("select (SELECT CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' 
                                  THEN fld_teacher_points_earned END) AS pointsearned from itc_module_points_master where fld_schedule_id='".$id[3]."' and fld_module_id='".$id[4]."' and fld_student_id='".$stuid[$i]."' and fld_type='1' and fld_delstatus='0'");
                                
                                   
                                   
                                   if($attendance->num_rows>0)
                                   {
                                       $pointspossible=0;
                                       $attenearned=0;
                                       while($rowatten=$attendance->fetch_assoc())
                                       {
                                           extract($rowatten);
                                           
                                           $attenearned=$attenearned+$pointsearned;
                                           
                                       }
                                   }
                                   else
                                   {
                                       $attenearned='';
                                   }
                                   
                                   $posttest=$ObjDB->QueryObject("select (SELECT CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' 
                                  THEN fld_teacher_points_earned END) AS pointsearned from itc_module_points_master where fld_schedule_id='".$id[3]."' and fld_module_id='".$id[4]."' and fld_session_id='6' and fld_student_id='".$stuid[$i]."' and fld_session_id<>'0'  and fld_type='0' and fld_delstatus='0'");
                                
                                   if($posttest->num_rows>0)
                                   {
                                       $pointspossible=0;
                                       $postearned=0;
                                       while($rowpost=$posttest->fetch_assoc())
                                       {
                                           extract($rowpost);
                                           
                                          $postearned=$postearned+$pointsearned;
                                           
                                       }
                                   }
                                   else
                                   {
                                       $postearned='';
                                   }
                                   
                                   $column=0;
                                   if($id[6]==1 and $id[7]==0)
                                   {
                                       $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, $stuname);
				$style = array('font' =>
				                    array('color' =>
				                      array('rgb' => '000000'),
				                      'bold' => true,
				                    ),
				           'alignment' => array(
				                            
				                      'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				                      'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
				                        ),
				     );
				$range = $objPHPExcel->setActiveSheetIndex($sheet_count)->calculateWorksheetDimension();
				$objPHPExcel->getActiveSheet()->getStyle($range)->applyFromArray($style);
				$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(20);
                                
                                   }
                                   else if($id[7]==1 and $id[6]==0)
                                   {
                                       $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, $stuid[$i]);
				$style = array('font' =>
				                    array('color' =>
				                      array('rgb' => '000000'),
				                      'bold' => true,
				                    ),
				           'alignment' => array(
				                            
				                      'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				                      'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
				                        ),
				     );
				$range = $objPHPExcel->setActiveSheetIndex($sheet_count)->calculateWorksheetDimension();
				$objPHPExcel->getActiveSheet()->getStyle($range)->applyFromArray($style);
				$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(20);
                                   }
                                   else if($id[7]==1 and $id[6]==1)
                                   {
                                       $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, $stuid[$i]);
                                       $style = array('font' =>
				                    array('color' =>
				                      array('rgb' => '000000'),
				                      'bold' => true,
				                    ),
				           'alignment' => array(
                                
				                      'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				                      'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
				                        ),
				     );
				   $range = $objPHPExcel->setActiveSheetIndex($sheet_count)->calculateWorksheetDimension();
				   $objPHPExcel->getActiveSheet()->getStyle($range)->applyFromArray($style);
				   $objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(20);
                                   
                                   $column=$column+1;
                                   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, $stuname);
				$style = array('font' =>
				                    array('color' =>
				                      array('rgb' => '000000'),
				                      'bold' => true,
				                    ),
				           'alignment' => array(
				                            
				                      'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				                      'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
				                        ),
				     );
				$range = $objPHPExcel->setActiveSheetIndex($sheet_count)->calculateWorksheetDimension();
				$objPHPExcel->getActiveSheet()->getStyle($range)->applyFromArray($style);
				$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(20);
                                   }  
                                
                                
				$column=$column+1;
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, $moduleguideearned);
				$style = array('font' =>
				                    array('color' =>
				                      array('rgb' => '000000'),
				                      'bold' => true,
				                    ),
				           'alignment' => array(
				                            
				                      'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				                      'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
				                        ),
				     );
				$range = $objPHPExcel->setActiveSheetIndex($sheet_count)->calculateWorksheetDimension();
				$objPHPExcel->getActiveSheet()->getStyle($range)->applyFromArray($style);
				$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(20);
                                
                                $column=$column+1;
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, $earned);
				$style = array('font' =>
				                    array('color' =>
				                      array('rgb' => '000000'),
				                      'bold' => true,
				                    ),
				           'alignment' => array(
				                            
				                      'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				                      'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
				                        ),
				     );
				$range = $objPHPExcel->setActiveSheetIndex($sheet_count)->calculateWorksheetDimension();
				$objPHPExcel->getActiveSheet()->getStyle($range)->applyFromArray($style);
				$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(20);
                                
                                $column=$column+1;
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, $passearned);
				$style = array('font' =>
				                    array('color' =>
				                      array('rgb' => '000000'),
				                      'bold' => true,
				                    ),
				           'alignment' => array(
				                            
				                      'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				                      'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
				                        ),
				     );
				$range = $objPHPExcel->setActiveSheetIndex($sheet_count)->calculateWorksheetDimension();
				$objPHPExcel->getActiveSheet()->getStyle($range)->applyFromArray($style);
				$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(20);
                                
                                $column=$column+1;
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, $partiearned);
				$style = array('font' =>
				                    array('color' =>
				                      array('rgb' => '000000'),
				                      'bold' => true,
				                    ),
				           'alignment' => array(
				                            
				                      'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				                      'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
				                        ),
				     );
				$range = $objPHPExcel->setActiveSheetIndex($sheet_count)->calculateWorksheetDimension();
				$objPHPExcel->getActiveSheet()->getStyle($range)->applyFromArray($style);
				$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(20);
                                
                                $column=$column+1;
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, $attenearned);
				$style = array('font' =>
				                    array('color' =>
				                      array('rgb' => '000000'),
				                      'bold' => true,
				                    ),
				           'alignment' => array(
				                            
				                      'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				                      'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
				                        ),
				     );
				$range = $objPHPExcel->setActiveSheetIndex($sheet_count)->calculateWorksheetDimension();
				$objPHPExcel->getActiveSheet()->getStyle($range)->applyFromArray($style);
				$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(20);
                                
                                $column=$column+1;
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, $postearned);
				$style = array('font' =>
				                    array('color' =>
				                      array('rgb' => '000000'),
				                      'bold' => true,
				                    ),
				           'alignment' => array(
				                            
				                      'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				                      'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
				                        ),
				     );
				$range = $objPHPExcel->setActiveSheetIndex($sheet_count)->calculateWorksheetDimension();
				$objPHPExcel->getActiveSheet()->getStyle($range)->applyFromArray($style);
				$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(20);
                                
                                $row++;
                } // $stuidforloop end
                
             } // else if end

         $row++;

	}  // ends of for ($x = 0; $x <= 10; $x++)

     } //ends of if($qryschedules->num_rows > 0)





$endvalue = $objPHPExcel->setActiveSheetIndex(0)->getHighestColumn();;

for($col = 'A'; $col !== 'L'; $col++) {
    $objPHPExcel->getActiveSheet()
        ->getColumnDimension($col)
        ->setAutoSize(true);
}

$name="ModuleScore_".date('Y-m-d')."_".date('H:i:s').".xls";

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header("Content-Disposition: attachment;filename=".$name."");
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');


