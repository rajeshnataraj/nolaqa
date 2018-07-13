<?php
ini_set('max_execution_time', 300);
@include("../sessioncheck.php");
error_reporting(0);

$qryclassdetails1=$ObjDB->QueryObject("SELECT a.fld_id as classid
                                        FROM itc_class_master AS a 
                                        WHERE a.fld_delstatus='0' AND a.fld_school_id='".$schoolid."' AND a.fld_user_id='".$indid."' AND ( a.fld_id IN(SELECT fld_class_id FROM itc_class_teacher_mapping 
                                        WHERE fld_teacher_id='".$uid."' AND fld_flag='1'))
                                        GROUP BY a.fld_id ORDER BY a.fld_id DESC");
 if($qryclassdetails1->num_rows>0)
{
    $finalclasspercent='';
    $schcountclass=$qryclassdetails1->num_rows;
    while($rowclassdetails1 = $qryclassdetails1->fetch_assoc())
    {
        extract($rowclassdetails1);
        
        $totschedulepercentage='';
        
        /*******Rotational schedule Developed by naren 1/6/2015***********/
        
         $qryschedulerot=$ObjDB->QueryObject("SELECT a.fld_numberofrotations as totrot,a.fld_id AS scheduleid, a.fld_scheduletype AS stype,a.fld_moduletype as mtype,a.fld_startdate AS startdate, a.fld_enddate AS enddate
                                            FROM itc_class_rotation_schedule_mastertemp AS a LEFT JOIN itc_class_rotation_schedule_student_mappingtemp AS b 
                                                    ON b.fld_schedule_id=a.fld_id
                                            WHERE a.fld_class_id='".$classid."' AND a.fld_delstatus='0' AND a.fld_flag='1' AND b.fld_flag='1' 
                                            GROUP BY scheduleid");
         $rotschcount=$qryschedulerot->num_rows;
         if($qryschedulerot->num_rows>0)
        {
            while($rowqryschedulerot = $qryschedulerot->fetch_assoc())
            {
                extract($rowqryschedulerot);
                
                $totrots=$totrot+1;
                $totrotpercentage='';
                $rotschedulepercentage='';
                for($rot=2;$rot<=$totrots;$rot++)
                {
                        $rotpercentage='';
                        $qrystddetailsrot=$ObjDB->QueryObject("SELECT a.fld_id AS stdid FROM itc_user_master AS a 
                                                        LEFT JOIN itc_class_rotation_schedule_student_mappingtemp AS b ON a.fld_id=b.fld_student_id
                                                        WHERE b.fld_schedule_id='".$scheduleid."' AND b.fld_flag='1' AND a.fld_delstatus='0'");
                        
                        $studcount=$qrystddetailsrot->num_rows;
                        
                        if($qrystddetailsrot->num_rows>0)
                        {
                            $totstdpercentagerot='';
                            
                            while($rowqrystddetailsrot = $qrystddetailsrot->fetch_assoc())
                            {
                                    extract($rowqrystddetailsrot);
                                    
                                    $type='';
                        
                                    if($mtype==1)
                                    {
                                        $type=1;
                                    }
                                    else if($mtype==2)
                                    {
                                        $type=4;
                                    }
                                    
                                    $modid=$ObjDB->SelectSingleValueInt("SELECT fld_module_id FROM itc_class_rotation_schedulegriddet WHERE fld_rotation='".$rot."' AND fld_student_id='".$stdid."' AND fld_schedule_id='".$scheduleid."' AND fld_flag='1'");

                                    $sectionid='';
                                    $pageid='';

                                    $qrytrackrot = $ObjDB->QueryObject("SELECT  max(fld_section_id) as sectionid,fld_page_id as pageid
                                                                                                    FROM itc_module_play_track
                                                                                                    WHERE fld_schedule_id='".$scheduleid."'  AND fld_tester_id='".$stdid."' and fld_schedule_type='".$type."' and fld_module_id='".$modid."' AND fld_delstatus='0' AND fld_page_id<>'0' order by fld_id desc");

                                    if($qrytrackrot->num_rows>0)
                                    {
                                       $rowrot=$qrytrackrot->fetch_assoc();
                                       extract($rowrot);
                                    }
                                    
                                    $sessionid='';
                                    
                                    if($sectionid=='0' OR $sectionid>'0' AND $pageid>'0')
                                    {
                                        $sessionid=$sectionid+1;
                                        $completeprogressrot=($sessionid/7)*100;
                                        $completeprogressrot=round($completeprogressrot,2);
                                    }
                                    else
                                    {
                                        $completeprogressrot=0;
                                    }

                                    $stdpercentagerot[]=$rot."~".$stdid."~".$scheduleid."~".$completeprogressrot."~".$modid;//individual student percentage
                                    
                                    if($totstdpercentagerot=='')
                                    {
                                        $totstdpercentagerot=$completeprogressrot;
                                    }
                                    else
                                    {
                                        $totstdpercentagerot=$totstdpercentagerot+$completeprogressrot;
                                    }
                                    
                                    
                            } // student while loop end
                            
                        } // student if loop end
                        
                                  $rotpercentage=$totstdpercentagerot/$studcount;
                                  
                                  if($totrotpercentage=='')
                                  {
                                      $totrotpercentage=$rotpercentage;
                                  }
                                  else
                                  {
                                      $totrotpercentage=$totrotpercentage+$rotpercentage;
                                  }
                                  
                                  
                                  $rotationpercentagearray[]=$rot."~".$scheduleid."~".$rotpercentage;
                                  
                                  
                } // rotation for loop end 
                
                            $rotschedulepercentage=round($totrotpercentage)/$totrot;
                            
                            $schedulepercentagerot[]=$scheduleid."~".round($rotschedulepercentage);
                            
                            if($totschedulepercentage=='')
                            {
                                $totschedulepercentage=round($rotschedulepercentage);
                            }
                            else
                            {
                                
                               $totschedulepercentage=$totschedulepercentage+round($rotschedulepercentage);
                            }
                            
            } //schedule while loop end
        } // schedule end
        /*******Rotational schedule Developed by naren 1/6/2015***********/
                            
         /*******Rotational schedule for Dyad Developed by Mohan M 15/6/2016***********/
         $qryschedulerotdyad=$ObjDB->QueryObject("SELECT (MIN(DISTINCT(b.fld_rotation))) AS minids, (MAX(DISTINCT(b.fld_rotation))) AS totrot,a.fld_id AS scheduleid, '2' as type
                                                    FROM itc_class_dyad_schedulemaster AS a 
                                                    LEFT JOIN itc_class_dyad_schedulegriddet AS b ON b.fld_schedule_id=a.fld_id
                                                        WHERE b.fld_class_id='".$classid."' AND a.fld_delstatus='0' AND a.fld_flag='1' AND b.fld_flag='1' 
                                                        GROUP BY scheduleid");
        $rotschcount=$qryschedulerotdyad->num_rows;
        if($qryschedulerotdyad->num_rows>0)
        {
            while($rowqryschedulerotdyad = $qryschedulerotdyad->fetch_assoc())
            {
                extract($rowqryschedulerotdyad);
                
                $totrotpercentage='';
                $rotschedulepercentage='';
                for($rot=1;$rot<=$totrot;$rot++)
                {
                    $rotpercentage='';
                
                    $qrystddetailsrot=$ObjDB->QueryObject("SELECT a.fld_id AS stdid FROM itc_user_master AS a 
                                                                LEFT JOIN itc_class_dyad_schedule_studentmapping AS b ON a.fld_id=b.fld_student_id
                                                                WHERE b.fld_schedule_id='".$scheduleid."' AND b.fld_flag='1' AND a.fld_delstatus='0'");

                    $studcount=$qrystddetailsrot->num_rows;

                    if($qrystddetailsrot->num_rows>0)
                    {
                        $totstdpercentagerot='';

                        while($rowqrystddetailsrot = $qrystddetailsrot->fetch_assoc())
                        {
                            extract($rowqrystddetailsrot);
                            

                            $modid=$ObjDB->SelectSingleValueInt("SELECT fld_module_id FROM itc_class_dyad_schedulegriddet WHERE fld_rotation='".$rot."' 
                                                                    AND fld_student_id='".$stdid."' AND fld_schedule_id='".$scheduleid."' AND fld_flag='1'");

                            $sectionid='';
                            $pageid='';

                            $qrytrackrot = $ObjDB->QueryObject("SELECT  max(fld_section_id) as sectionid,fld_page_id as pageid FROM itc_module_play_track
                                                                    WHERE fld_schedule_id='".$scheduleid."'  AND fld_tester_id='".$stdid."' 
                                                                    and fld_schedule_type='".$type."' and fld_module_id='".$modid."' AND fld_delstatus='0'
                                                                     AND fld_page_id<>'0' order by fld_id desc");

                            if($qrytrackrot->num_rows>0)
                            {
                               $rowrot=$qrytrackrot->fetch_assoc();
                               extract($rowrot);
                            }

                            $sessionid='';

                            if($sectionid=='0' OR $sectionid>'0' AND $pageid>'0')
                            {
                                $sessionid=$sectionid+1;
                                $completeprogressrot=($sessionid/7)*100;
                                $completeprogressrot=round($completeprogressrot,2);
                            }
                            else
                            {
                                $completeprogressrot=0;
                            }

                            $stdpercentagerotdyad[]=$rot."~".$stdid."~".$scheduleid."~".$completeprogressrot."~".$modid;//individual student percentage

                            if($totstdpercentagerot=='')
                            {
                                $totstdpercentagerot=$completeprogressrot;
                            }
                            else
                            {
                                $totstdpercentagerot=$totstdpercentagerot+$completeprogressrot;
                            }
                        } // student while loop end
                    } // student if loop end

                    $rotpercentage=$totstdpercentagerot/$studcount;

                    if($totrotpercentage=='')
                    {
                        $totrotpercentage=$rotpercentage;
                    }
                    else
                    {
                        $totrotpercentage=$totrotpercentage+$rotpercentage;
                    }
                    $rotationpercentagearraydyad[]=$rot."~".$scheduleid."~".$rotpercentage;
                } // rotation for loop end 
                
                $rotschedulepercentage=round($totrotpercentage)/$totrot;
                $schedulepercentagerotdyad[]=$scheduleid."~".round($rotschedulepercentage);
                if($totschedulepercentage=='')
                {
                    $totschedulepercentage=round($rotschedulepercentage);
                }
                else
                {

                   $totschedulepercentage=$totschedulepercentage+round($rotschedulepercentage);
                }
                
            } //schedule while loop end
        } // schedule end
        /*******Rotational schedule for Dyad Developed by Mohan M 15/6/2016***********/
        
        /*******Rotational schedule for Triad Developed by Mohan M 15/6/2016***********/
         $qryschedulerottriad=$ObjDB->QueryObject("SELECT (MIN(DISTINCT(b.fld_rotation))) AS minids, (MAX(DISTINCT(b.fld_rotation))) AS totrot,a.fld_id AS scheduleid, '3' as type
                                                    FROM itc_class_triad_schedulemaster AS a 
                                                    LEFT JOIN itc_class_triad_schedulegriddet AS b ON b.fld_schedule_id=a.fld_id
                                                        WHERE b.fld_class_id='".$classid."' AND a.fld_delstatus='0' AND a.fld_flag='1' AND b.fld_flag='1' 
                                                        GROUP BY scheduleid");
        $rotschcount=$qryschedulerottriad->num_rows;
        if($qryschedulerottriad->num_rows>0)
        {
            while($rowqryschedulerottriad = $qryschedulerottriad->fetch_assoc())
            {
                extract($rowqryschedulerottriad);
        
                //$totrots=$totrot+1;
                $totrotpercentage='';
                $rotschedulepercentage='';
                for($rot=1;$rot<=$totrot;$rot++)
                {
                    $rotpercentage='';
        
                    $qrystddetailsrot=$ObjDB->QueryObject("SELECT a.fld_id AS stdid FROM itc_user_master AS a 
                                                                LEFT JOIN itc_class_triad_schedule_studentmapping AS b ON a.fld_id=b.fld_student_id
                                                                WHERE b.fld_schedule_id='".$scheduleid."' AND b.fld_flag='1' AND a.fld_delstatus='0'");
        
                    $studcount=$qrystddetailsrot->num_rows;
        
                    if($qrystddetailsrot->num_rows>0)
                    {
                        $totstdpercentagerot='';
        
                        while($rowqrystddetailsrot = $qrystddetailsrot->fetch_assoc())
                        {
                            extract($rowqrystddetailsrot);
                            

                            $modid=$ObjDB->SelectSingleValueInt("SELECT fld_module_id FROM itc_class_triad_schedulegriddet WHERE fld_rotation='".$rot."' 
                                                                    AND fld_student_id='".$stdid."' AND fld_schedule_id='".$scheduleid."' AND fld_flag='1'");

                            $sectionid='';
                            $pageid='';

                            $qrytrackrot = $ObjDB->QueryObject("SELECT  max(fld_section_id) as sectionid,fld_page_id as pageid FROM itc_module_play_track
                                                                    WHERE fld_schedule_id='".$scheduleid."'  AND fld_tester_id='".$stdid."' 
                                                                    and fld_schedule_type='".$type."' and fld_module_id='".$modid."' AND fld_delstatus='0'
                                                                     AND fld_page_id<>'0' order by fld_id desc");

                            if($qrytrackrot->num_rows>0)
                            {
                               $rowrot=$qrytrackrot->fetch_assoc();
                               extract($rowrot);
                            }

                            $sessionid='';

                            if($sectionid=='0' OR $sectionid>'0' AND $pageid>'0')
                            {
                                $sessionid=$sectionid+1;
                                $completeprogressrot=($sessionid/7)*100;
                                $completeprogressrot=round($completeprogressrot,2);
                            }
                            else
                            {
                                $completeprogressrot=0;
                            }

                            $stdpercentagerotdyad[]=$rot."~".$stdid."~".$scheduleid."~".$completeprogressrot."~".$modid;//individual student percentage

                            if($totstdpercentagerot=='')
                            {
                                $totstdpercentagerot=$completeprogressrot;
                            }
                            else
                            {
                                $totstdpercentagerot=$totstdpercentagerot+$completeprogressrot;
                            }
                        } // student while loop end
                    } // student if loop end

                    $rotpercentage=$totstdpercentagerot/$studcount;

                    if($totrotpercentage=='')
                    {
                        $totrotpercentage=$rotpercentage;
                    }
                    else
                    {
                        $totrotpercentage=$totrotpercentage+$rotpercentage;
                    }
                    $rotationpercentagearraydyad[]=$rot."~".$scheduleid."~".$rotpercentage;
                } // rotation for loop end 
                
                $rotschedulepercentage=round($totrotpercentage)/$totrot;
                $schedulepercentagerotdyad[]=$scheduleid."~".round($rotschedulepercentage);
                if($totschedulepercentage=='')
                {
                    $totschedulepercentage=round($rotschedulepercentage);
                }
                else
                {

                   $totschedulepercentage=$totschedulepercentage+round($rotschedulepercentage);
                }
                
            } //schedule while loop end
        } // schedule end
        /*******Rotational schedule for Triad Developed by Mohan M 15/6/2016***********/
        
        
         $qryschedulemodrot=$ObjDB->QueryObject("SELECT a.fld_numberofrotations as totrot,a.fld_id AS scheduleid, a.fld_scheduletype AS stype,a.fld_startdate AS startdate, a.fld_enddate AS enddate
                                            FROM itc_class_rotation_modexpschedule_mastertemp AS a LEFT JOIN itc_class_rotation_modexpschedule_student_mappingtemp AS b 
                                                    ON b.fld_schedule_id=a.fld_id
                                            WHERE a.fld_class_id='".$classid."' AND a.fld_delstatus='0' AND a.fld_flag='1' AND b.fld_flag='1' 
                                            GROUP BY scheduleid");
         $rotschcount=$qryschedulemodrot->num_rows;
         if($qryschedulemodrot->num_rows>0)
        {
            while($rowqryschedulemodrot = $qryschedulemodrot->fetch_assoc())
            {
                extract($rowqryschedulemodrot);
                
                $totrots=$totrot+1;
                $totrotpercentage='';
                $rotschedulepercentage='';
                for($rot=2;$rot<=$totrots;$rot++)
                {
                        $rotpercentage='';
                        $qrystddetailsrot=$ObjDB->QueryObject("SELECT a.fld_id AS stdid FROM itc_user_master AS a 
                                                        LEFT JOIN itc_class_rotation_modexpschedule_student_mappingtemp AS b ON a.fld_id=b.fld_student_id
                                                        WHERE b.fld_schedule_id='".$scheduleid."' AND b.fld_flag='1' AND a.fld_delstatus='0'");
                        
                        $studcount=$qrystddetailsrot->num_rows;
                        
                        if($qrystddetailsrot->num_rows>0)
                        {
                            $totstdpercentagerotmod='';
                            
                            while($rowqrystddetailsrot = $qrystddetailsrot->fetch_assoc())
                            {
                                    extract($rowqrystddetailsrot);
                                    
                                    $modid=$ObjDB->SelectSingleValueInt("SELECT fld_module_id FROM itc_class_rotation_modexpschedulegriddet WHERE fld_rotation='".$rot."' AND fld_student_id='".$stdid."' AND fld_schedule_id='".$scheduleid."' AND fld_flag='1' AND fld_type='1'");
                                    
                                    if($modid>0)
                                    {
                                        
                                    $sectionid='';
                                    $pageid='';

                                    $qrytrackrot = $ObjDB->QueryObject("SELECT  max(fld_section_id) as sectionid,fld_page_id as pageid
                                                                                                    FROM itc_module_play_track
                                                                                                    WHERE fld_schedule_id='".$scheduleid."'  AND fld_tester_id='".$stdid."' and fld_schedule_type='21' and fld_module_id='".$modid."' AND fld_delstatus='0' AND fld_page_id<>'0' order by fld_id desc");

                                    if($qrytrackrot->num_rows>0)
                                    {
                                       $rowrot=$qrytrackrot->fetch_assoc();
                                       extract($rowrot);
                                    }
                                    
                                    $sessionid='';
                                    
                                    if($sectionid=='0' OR $sectionid>'0' AND $pageid>'0')
                                    {
                                        $sessionid=$sectionid+1;
                                        $completeprogressrot=($sessionid/7)*100;
                                        $completeprogressrot=round($completeprogressrot,2);
                                    }
                                    else
                                    {
                                        $completeprogressrot=0;
                                    }

                                    $stdpercentagerotmod[]=$rot."~".$stdid."~".$scheduleid."~".$completeprogressrot."~".$modid;//individual student percentage
                                    
                                    if($totstdpercentagerotmod=='')
                                    {
                                        $totstdpercentagerotmod=$completeprogressrot;
                                    }
                                    else
                                    {
                                        $totstdpercentagerotmod=$totstdpercentagerotmod+$completeprogressrot;
                                    }
                                    
                                 }
                                    
                                    
                            } // student while loop end
                            
                        } // student if loop end
                        
                                  $rotpercentage=$totstdpercentagerotmod/$studcount;
                                  
                                  if($totrotpercentage=='')
                                  {
                                      $totrotpercentage=$rotpercentage;
                                  }
                                  else
                                  {
                                      $totrotpercentage=$totrotpercentage+$rotpercentage;
                                  }
                                  
                                  
                                  $rotationpercentagearraymod[]=$rot."~".$scheduleid."~".$rotpercentage;
                                  
                                  
                } // rotation for loop end 
                
                            $rotschedulepercentage=round($totrotpercentage)/$totrot;
                            
                            $schedulepercentagerotmod[]=$scheduleid."~".round($rotschedulepercentage);
                            
                            if($totschedulepercentage=='')
                            {
                                $totschedulepercentage=round($rotschedulepercentage);
                            }
                            else
                            {
                                
                               $totschedulepercentage=$totschedulepercentage+round($rotschedulepercentage);
                            }
                            
                            
                
                
            } //schedule while loop end
        } // schedule end
        /************End here*************************/
        
        /***************moduleExpedition rotational schedule start here ***********************************/
        $qryschedulerotexp=$ObjDB->QueryObject("SELECT a.fld_numberofrotations as totrot,a.fld_id AS scheduleid,a.fld_startdate AS startdate, a.fld_enddate AS enddate
                                            FROM itc_class_rotation_modexpschedule_mastertemp AS a LEFT JOIN itc_class_rotation_modexpschedule_student_mappingtemp AS b 
                                                    ON b.fld_schedule_id=a.fld_id
                                            WHERE a.fld_class_id='".$classid."' AND a.fld_delstatus='0' AND a.fld_flag='1' AND b.fld_flag='1' 
                                            GROUP BY scheduleid");
        $rotexpschcount=$qryschedulerotexp->num_rows;
         if($qryschedulerotexp->num_rows>0)
        {
            while($rowqryschedulerotexp = $qryschedulerotexp->fetch_assoc())
            {
                extract($rowqryschedulerotexp);
                
                $totrots=$totrot+1;
                $totexprotpercentage='';
                $exprotschedulepercentage='';
                for($rot=2;$rot<=$totrots;$rot++)
                {
                        $exprotpercentage='';
                        $qrystddetailsrotexp=$ObjDB->QueryObject("SELECT a.fld_id AS stdid FROM itc_user_master AS a 
                                                        LEFT JOIN itc_class_rotation_modexpschedule_student_mappingtemp AS b ON a.fld_id=b.fld_student_id
                                                        WHERE b.fld_schedule_id='".$scheduleid."' AND b.fld_flag='1' AND a.fld_delstatus='0'");
                        
                        $studcount=$qrystddetailsrotexp->num_rows;
                        
                        if($qrystddetailsrotexp->num_rows>0)
                        {
                            $totstdpercentagerotexp='';
                            
                            while($rowqrystddetailsrotexp = $qrystddetailsrotexp->fetch_assoc())
                            {
                                    extract($rowqrystddetailsrotexp);
                                    
                                    $expid=$ObjDB->SelectSingleValueInt("SELECT fld_module_id FROM itc_class_rotation_modexpschedulegriddet WHERE fld_rotation='".$rot."' AND fld_student_id='".$stdid."' AND fld_schedule_id='".$scheduleid."' AND fld_flag='1' and fld_type='2'");
                                    
                                    if($expid>0)
                                    {
                                    $checkrestatuscnt = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_exp_res_status WHERE fld_exp_id='".$expid."' AND fld_school_id='".$senshlid."' AND fld_created_by='".$uid."' AND fld_user_id='".$indid."'");
                                    
                                    if($checkrestatuscnt == '0')
                                    {
                                        $resourcegroupids1=$ObjDB->SelectSingleValue("select 
                                                                                            GROUP_CONCAT(cnt.fld_id) 
                                                                                        from
                                                                                            (SELECT 
                                                                                                a.fld_id
                                                                                            FROM
                                                                                                itc_exp_resource_master AS a
                                                                                            LEFT JOIN itc_exp_task_master AS b ON a.fld_task_id = b.fld_id
                                                                                            LEFT JOIN itc_exp_destination_master AS c ON b.fld_dest_id = c.fld_id
                                                                                            LEFT JOIN itc_exp_res_status AS d ON d.fld_res_id = a.fld_id
                                                                                            LEFT JOIN itc_license_exp_mapping AS e ON c.fld_id = e.fld_dest_id
                                                                                            LEFT JOIN itc_license_track AS f ON e.fld_license_id = f.fld_license_id
                                                                                            LEFT JOIN itc_class_rotation_expschedule_mastertemp as g ON e.fld_license_id = g.fld_license_id
                                                                                            where
                                                                                                c.fld_exp_id = '".$expid."'
                                                                                                    AND g.fld_id = '".$scheduleid."'
                                                                                                    AND d.fld_school_id = '0'
                                                                                                    AND d.fld_user_id = '0'
                                                                                                    and d.fld_status = '1'
                                                                                                    and a.fld_delstatus = '0'
                                                                                                    and b.fld_delstatus = '0'
                                                                                                    and c.fld_delstatus = '0'
                                                                                            GROUP BY a.fld_id) as cnt");

                                        if($resourcegroupids1=='')
                                        {
                                            $resourcegroupids1=0;
                                        }
                                        
                                        $rescomplete = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_res_play_track WHERE fld_res_id IN ('".$resourcegroupids1."') AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='20' AND fld_delstatus='0' AND fld_student_id='".$stdid."' AND fld_read_status='1'");
                                        $totalresource=sizeof(explode(',',$resourcegroupids1));

                                    }
                                    else
                                    {
                                        $resourcegroupids1=$ObjDB->SelectSingleValue("select 
                                                                                            GROUP_CONCAT(cnt.fld_id) 
                                                                                        from
                                                                                            (SELECT 
                                                                                                a.fld_id
                                                                                            FROM
                                                                                                itc_exp_resource_master AS a
                                                                                            LEFT JOIN itc_exp_task_master AS b ON a.fld_task_id = b.fld_id
                                                                                            LEFT JOIN itc_exp_destination_master AS c ON b.fld_dest_id = c.fld_id
                                                                                            LEFT JOIN itc_exp_res_status AS d ON d.fld_res_id = a.fld_id
                                                                                            LEFT JOIN itc_license_exp_mapping AS e ON c.fld_id = e.fld_dest_id
                                                                                            LEFT JOIN itc_license_track AS f ON e.fld_license_id = f.fld_license_id
                                                                                            LEFT JOIN itc_class_rotation_expschedule_mastertemp as g ON e.fld_license_id = g.fld_license_id
                                                                                            where
                                                                                                c.fld_exp_id = '".$expid."'
                                                                                                    AND g.fld_id = '".$scheduleid."'
                                                                                                    AND d.fld_school_id = '".$senshlid."'
                                                                                                    AND d.fld_created_by='".$uid."'
                                                                                                    AND d.fld_user_id = '".$indid."'
                                                                                                    and d.fld_status = '1'
                                                                                                    and a.fld_delstatus = '0'
                                                                                                    and b.fld_delstatus = '0'
                                                                                                    and c.fld_delstatus = '0'
                                                                                            GROUP BY a.fld_id) as cnt");
                                        
                                        if($resourcegroupids1=='')
                                        {
                                            $resourcegroupids1=0;
                                        }

                                        $rescomplete = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_res_play_track WHERE fld_res_id IN ('".$resourcegroupids1."') AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='20' AND fld_delstatus='0' AND fld_student_id='".$stdid."' AND fld_read_status='1'");
                                        $totalresource=sizeof(explode(',',$resourcegroupids1));
                                    }
                                    
                                    if($totalresource==0)
                                    {
                                        $completeprogress=0;
                                    }
                                    else if($totalresource!=0)
                                    {
                                        $completeprogress=($rescomplete/$totalresource);
                                        $completeprogress=round($completeprogress,2);
                                    }

                                    $stdpercentagerotmodexp[]=$rot."~".$stdid."~".$scheduleid."~".$completeprogress."~".$expid;//individual student percentage
                                    
                                    if($totstdpercentagerotexp=='')
                                    {
                                        $totstdpercentagerotexp=$completeprogress;
                                    }
                                    else
                                    {
                                        $totstdpercentagerotexp=$totstdpercentagerotexp+$completeprogress;
                                    }
                                    
                                 }
                                 
                            } // student while loop end
                            
                        } // student if loop end
                        
                                  $exprotpercentage=$totstdpercentagerotexp/$studcount;
                                  
                                  if($totexprotpercentage=='')
                                  {
                                      $totexprotpercentage=$exprotpercentage;
                                  }
                                  else
                                  {
                                      $totexprotpercentage=$totexprotpercentage+$exprotpercentage;
                                  }
                                  
                                  
                                  $modexprotationpercentagearray[]=$rot."~".$scheduleid."~".$exprotpercentage;
                                  
                                  
                } // rotation for loop end 
                
                            $exprotschedulepercentage=$totexprotpercentage/$totrot;
                            
                            $schedulepercentagerotmodexp[]=$scheduleid."~".$exprotschedulepercentage;
                            
                           
                            
                            if($totschedulepercentage=='')
                            {
                                
                                
                                $totschedulepercentage=round($exprotschedulepercentage*100);
                            }
                            else
                            {
                                
                                
                                $totschedulepercentage=$totschedulepercentage+round($exprotschedulepercentage*100);
                            }
                            
                            
                
                
            } //schedule while loop end
        } // schedule end
        /************End here*************************/
        
        /***************Expedition rotational schedule start here ***********************************/
        $qryschedulerotexp=$ObjDB->QueryObject("SELECT a.fld_numberofrotations as totrot,a.fld_id AS scheduleid,a.fld_startdate AS startdate, a.fld_enddate AS enddate
                                            FROM itc_class_rotation_expschedule_mastertemp AS a LEFT JOIN itc_class_rotation_expschedule_student_mappingtemp AS b 
                                                    ON b.fld_schedule_id=a.fld_id
                                            WHERE a.fld_class_id='".$classid."' AND a.fld_delstatus='0' AND a.fld_flag='1' AND b.fld_flag='1' 
                                            GROUP BY scheduleid");
        $rotexpschcount=$qryschedulerotexp->num_rows;
         if($qryschedulerotexp->num_rows>0)
        {
            while($rowqryschedulerotexp = $qryschedulerotexp->fetch_assoc())
            {
                extract($rowqryschedulerotexp);
                
                $totrots=$totrot+1;
                $totexprotpercentage='';
                $exprotschedulepercentage='';
                for($rot=2;$rot<=$totrots;$rot++)
                {
                        $exprotpercentage='';
                        $qrystddetailsrotexp=$ObjDB->QueryObject("SELECT a.fld_id AS stdid FROM itc_user_master AS a 
                                                        LEFT JOIN itc_class_rotation_expschedule_student_mappingtemp AS b ON a.fld_id=b.fld_student_id
                                                        WHERE b.fld_schedule_id='".$scheduleid."' AND b.fld_flag='1' AND a.fld_delstatus='0'");
                        
                        $studcount=$qrystddetailsrotexp->num_rows;
                        
                        if($qrystddetailsrotexp->num_rows>0)
                        {
                            $totstdpercentagerotexp='';
                            
                            while($rowqrystddetailsrotexp = $qrystddetailsrotexp->fetch_assoc())
                            {
                                    extract($rowqrystddetailsrotexp);
                                    
                                    $expid=$ObjDB->SelectSingleValueInt("SELECT fld_expedition_id FROM itc_class_rotation_expschedulegriddet WHERE fld_rotation='".$rot."' AND fld_student_id='".$stdid."' AND fld_schedule_id='".$scheduleid."' AND fld_flag='1'");

                                    $checkrestatuscnt = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_exp_res_status WHERE fld_exp_id='".$expid."' AND fld_school_id='".$senshlid."' AND fld_created_by='".$uid."' AND fld_user_id='".$indid."'");
                                    
                                    if($checkrestatuscnt == '0')
                                    {
                                        $resourcegroupids1=$ObjDB->SelectSingleValue("select 
                                                                                            GROUP_CONCAT(cnt.fld_id) 
                                                                                        from
                                                                                            (SELECT 
                                                                                                a.fld_id
                                                                                            FROM
                                                                                                itc_exp_resource_master AS a
                                                                                            LEFT JOIN itc_exp_task_master AS b ON a.fld_task_id = b.fld_id
                                                                                            LEFT JOIN itc_exp_destination_master AS c ON b.fld_dest_id = c.fld_id
                                                                                            LEFT JOIN itc_exp_res_status AS d ON d.fld_res_id = a.fld_id
                                                                                            LEFT JOIN itc_license_exp_mapping AS e ON c.fld_id = e.fld_dest_id
                                                                                            LEFT JOIN itc_license_track AS f ON e.fld_license_id = f.fld_license_id
                                                                                            LEFT JOIN itc_class_rotation_expschedule_mastertemp as g ON e.fld_license_id = g.fld_license_id
                                                                                            where
                                                                                                c.fld_exp_id = '".$expid."'
                                                                                                    AND g.fld_id = '".$scheduleid."'
                                                                                                    AND d.fld_school_id = '0'
                                                                                                    AND d.fld_user_id = '0'
                                                                                                    and d.fld_status = '1'
                                                                                                    and a.fld_delstatus = '0'
                                                                                                    and b.fld_delstatus = '0'
                                                                                                    and c.fld_delstatus = '0'
                                                                                            GROUP BY a.fld_id) as cnt");

                                        if($resourcegroupids1=='')
                                        {
                                            $resourcegroupids1=0;
                                        }
                                        
                                        $rescomplete = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_res_play_track WHERE fld_res_id IN ('".$resourcegroupids1."') AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='19' AND fld_delstatus='0' AND fld_student_id='".$stdid."' AND fld_read_status='1'");
                                        $totalresource=sizeof(explode(',',$resourcegroupids1));

                                    }
                                    else
                                    {
                                        $resourcegroupids1=$ObjDB->SelectSingleValue("select 
                                                                                            GROUP_CONCAT(cnt.fld_id) 
                                                                                        from
                                                                                            (SELECT 
                                                                                                a.fld_id
                                                                                            FROM
                                                                                                itc_exp_resource_master AS a
                                                                                            LEFT JOIN itc_exp_task_master AS b ON a.fld_task_id = b.fld_id
                                                                                            LEFT JOIN itc_exp_destination_master AS c ON b.fld_dest_id = c.fld_id
                                                                                            LEFT JOIN itc_exp_res_status AS d ON d.fld_res_id = a.fld_id
                                                                                            LEFT JOIN itc_license_exp_mapping AS e ON c.fld_id = e.fld_dest_id
                                                                                            LEFT JOIN itc_license_track AS f ON e.fld_license_id = f.fld_license_id
                                                                                            LEFT JOIN itc_class_rotation_expschedule_mastertemp as g ON e.fld_license_id = g.fld_license_id
                                                                                            where
                                                                                                c.fld_exp_id = '".$expid."'
                                                                                                    AND g.fld_id = '".$scheduleid."'
                                                                                                    AND d.fld_school_id = '".$senshlid."'
                                                                                                    AND d.fld_created_by='".$uid."'
                                                                                                    AND d.fld_user_id = '".$indid."'
                                                                                                    and d.fld_status = '1'
                                                                                                    and a.fld_delstatus = '0'
                                                                                                    and b.fld_delstatus = '0'
                                                                                                    and c.fld_delstatus = '0'
                                                                                            GROUP BY a.fld_id) as cnt");
                                        
                                        if($resourcegroupids1=='')
                                        {
                                            $resourcegroupids1=0;
                                        }

                                        $rescomplete = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_res_play_track WHERE fld_res_id IN ('".$resourcegroupids1."') AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='19' AND fld_delstatus='0' AND fld_student_id='".$stdid."' AND fld_read_status='1'");
                                        $totalresource=sizeof(explode(',',$resourcegroupids1));
                                    }
                                    
                                    if($totalresource==0)
                                    {
                                        $completeprogress=0;
                                    }
                                    else if($totalresource!=0)
                                    {
                                        $completeprogress=($rescomplete/$totalresource);
                                        $completeprogress=round($completeprogress,2);
                                    }

                                    $stdpercentagerotexp[]=$rot."~".$stdid."~".$scheduleid."~".$completeprogress."~".$expid;//individual student percentage
                                    
                                    if($totstdpercentagerotexp=='')
                                    {
                                        $totstdpercentagerotexp=$completeprogress;
                                    }
                                    else
                                    {
                                        $totstdpercentagerotexp=$totstdpercentagerotexp+$completeprogress;
                                    }
                                    
                                    
                            } // student while loop end
                            
                        } // student if loop end
                        
                                  if($studcount>0)
                                  {
                                  $exprotpercentage=$totstdpercentagerotexp/$studcount;
                                  }
                                  else
                                  {
                                      $exprotpercentage=0;
                                  }
                                  
                                  if($totexprotpercentage=='')
                                  {
                                      $totexprotpercentage=$exprotpercentage;
                                  }
                                  else
                                  {
                                      $totexprotpercentage=$totexprotpercentage+$exprotpercentage;
                                  }
                                  
                                  
                                  $exprotationpercentagearray[]=$rot."~".$scheduleid."~".$exprotpercentage;
                                  
                                  
                } // rotation for loop end 
                
                            $exprotschedulepercentage=$totexprotpercentage/$totrot;
                            
                            $schedulepercentagerotexp[]=$scheduleid."~".$exprotschedulepercentage;
                            
                           
                            
                            if($totschedulepercentage=='')
                            {
                                
                                
                                $totschedulepercentage=round($exprotschedulepercentage*100);
                            }
                            else
                            {
                                
                                
                                $totschedulepercentage=$totschedulepercentage+round($exprotschedulepercentage*100);
                            }
                            
                            
                
                
            } //schedule while loop end
        } // schedule end
        /************End here*************************/
        
       /*****IPL Code Start Here Developed By Mohan M 25-11-2015*******/
        $qryscheduledetailsipl=$ObjDB->QueryObject("SELECT a.fld_id AS scheduleid FROM itc_class_sigmath_master AS a 
                                                        WHERE a.fld_class_id = '".$classid."' AND a.fld_flag = '1' AND a.fld_delstatus = '0' GROUP BY scheduleid");
        
        $iplschcount=$qryscheduledetailsipl->num_rows;
        if($qryscheduledetailsipl->num_rows>0)
        {
            $schcountipl=$qryscheduledetailsipl->num_rows;
            $schcntipl=1;
            $totalschpercentipl='';
            while($rowqryscheduledetailsipl = $qryscheduledetailsipl->fetch_assoc())
            {
                extract($rowqryscheduledetailsipl);
                
                $qrystddetailsipl=$ObjDB->QueryObject("SELECT a.fld_id AS stdid FROM itc_user_master AS a 
                                                        LEFT JOIN itc_class_sigmath_student_mapping AS b ON a.fld_id=b.fld_student_id
                                                        WHERE b.fld_sigmath_id='".$scheduleid."' AND b.fld_flag='1' AND a.fld_delstatus='0'");
                if($qrystddetailsipl->num_rows>0)
                {
                    $stdcountipl=$qrystddetailsipl->num_rows;
                    $stdscheduleipl=$stdcountipl."~".$scheduleid;
                    $a=1;
                    $totalstdpercentipl='';
                    while($rowqrystddetailsipl = $qrystddetailsipl->fetch_assoc())
                    {
                        extract($rowqrystddetailsipl);
                       
                        $qryschdateipl=$ObjDB->QueryObject("SELECT MIN(fld_start_date) AS mindateipl,MAX(fld_end_date) AS maxdateipl FROM itc_class_sigmath_master WHERE fld_class_id='".$classid."'");
                        
                        if($qryschdateipl->num_rows>0)
                        {
                            $rowqryschdateipl=$qryschdateipl->fetch_assoc();
                            extract($rowqryschdateipl);
                            $mindateipl = date("d-m-Y", strtotime($mindateipl));
                            $maxdateipl = date("d-m-Y", strtotime($maxdateipl));
                        }

                        $datetimeipl1 = new DateTime($mindateipl);
                        $datetimeipl2 = new DateTime($maxdateipl);
                        $intervalipl = $datetimeipl1->diff($datetimeipl2);
                        $durationipl = $intervalipl->format('%a');

                        if($durationipl==0)
                        {
                            $durationipl=1; 
                        }

                        $totalpxipl=$durationipl*70;
                        $progresspxipl=$totalpxipl;//-2;
                        
                        $lessoncompleted = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
                                                                                        FROM itc_assignment_sigmath_master 
                                                                                        WHERE fld_schedule_id='".$scheduleid."' AND fld_class_id='".$classid."' AND fld_student_id='".$stdid."' AND fld_status<>'0' AND fld_delstatus='0'");
                        
                        $totallesson = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_class_sigmath_lesson_mapping WHERE fld_sigmath_id='".$scheduleid."' AND fld_flag='1'");
                        
                        if($totallesson==0)
                        {
                            $completeprogressipl=0;
                        }
                        else if($totallesson!=0)
                        {
                            $completeprogressipl=($lessoncompleted/$totallesson)*100;
                            $completeprogressipl=round($completeprogressipl,2);
                        }
                        
                        $resprogressipl=($completeprogressipl/100)*$totalpxipl;
                        $progressreportipl=($resprogressipl/$progresspxipl);
                        $stdpercentageipl[]=$stdid."~".$scheduleid."~".$progressreportipl."~".$completeprogressipl;//individual student percentage
                        if($totalstdpercentipl=='')
                        {
                            $totalstdpercentipl=$progressreportipl;
                        }
                        else
                        {
                            $totalstdpercentipl=$totalstdpercentipl+$progressreportipl;
                        }

                        if($stdcountipl==$a)
                        {
                            $schpercentageipl[]=$scheduleid."~".$totalstdpercentipl."~".$stdcountipl;//overall student percentage/schedule
                            $schpercentipl=$totalstdpercentipl/$stdcountipl;
                           
                        }

                        $a++;
                        
                        
                    } //student while loop
                }  //student if condition
                
               
			    if($totschedulepercentage=='')
                            {
                               
                                
                                $totschedulepercentage=$schpercentipl;
                            }
                            else
                            {
                               
                                
                                $totschedulepercentage=$totschedulepercentage+$schpercentipl;
                            }
                   
               
            }// schedule while loop
        } // schedule if condition
	/*****IPL Code End Here Developed By Mohan M 25-11-2015*******/
        
	/*****WCA Code Start Here Developed By Naren 1-12-2015*******/
		$qryschedulewca=$ObjDB->QueryObject("SELECT a.fld_module_id AS modid,a.fld_moduletype as modtype,b.fld_schedule_id AS scheduleid
                                                        FROM itc_class_indassesment_master AS a LEFT JOIN  itc_class_indassesment_student_mapping AS b ON a.fld_id=b.fld_schedule_id
                                                        WHERE a.fld_class_id='".$classid."' AND b.fld_flag='1' AND a.fld_delstatus='0' GROUP BY b.fld_schedule_id");
        $wcaschcount=$qryschedulewca->num_rows;
        if($qryschedulewca->num_rows>0)
        {
           $schcountwca=$qryschedulewca->num_rows;
           $schcntwca=1;
           $totalschpercentwca='';
           $mindatewca=''; 
           $maxdatewca=''; 
           
           while($rowqryschedulewca = $qryschedulewca->fetch_assoc())
           {
                extract($rowqryschedulewca);
                
                $qrystddetailswca=$ObjDB->QueryObject("SELECT a.fld_id AS stdid FROM itc_user_master AS a 
                                                        LEFT JOIN itc_class_indassesment_student_mapping AS b ON a.fld_id=b.fld_student_id
                                                        WHERE b.fld_schedule_id='".$scheduleid."' AND b.fld_flag='1' AND a.fld_delstatus='0'");
                if($qrystddetailswca->num_rows>0)
                {
                    $stdcountwca=$qrystddetailswca->num_rows;
                    $stdschedulewca=$stdcountwca."~".$scheduleid;
                    $a=1;
                    $totalstdpercentwca='';
                    
                    while($rowqrystddetailswca = $qrystddetailswca->fetch_assoc())
                    {
                        extract($rowqrystddetailswca);
                       
                        $qryschdatewca=$ObjDB->QueryObject("SELECT MIN(fld_startdate) AS mindateipl,MAX(fld_enddate) AS maxdateipl FROM itc_class_indassesment_master WHERE fld_class_id='".$classid."'");
                        
                        if($qryschdatewca->num_rows>0)
                        {
                            $rowqryschdatewca=$qryschdatewca->fetch_assoc();
                            extract($rowqryschdatewca);
                            $mindatewca = date("d-m-Y", strtotime($mindatewca));
                            $maxdatewca = date("d-m-Y", strtotime($maxdatewca));
                        }

                        $datetimewca1 = new DateTime($mindatewca);
                        $datetimewca2 = new DateTime($maxdatewca);
                        $intervalwca = $datetimewca1->diff($datetimewca2);
                        $durationwca = $intervalwca->format('%a');

                        if($durationwca==0)
                        {
                            $durationwca=1; 
                        }

                        
                        
                        $type='';
                        
                        if($modtype==1)
                        {
                            $type=5;
                        }
                        else if($modtype==2)
                        {
                            $type=6;
                        }
                        else if($modtype==7)
                        {
                            $type=7;
                        }
                        
                        $sectionid='';
                        $pageid='';
                        
                        $qrytrackwca = $ObjDB->QueryObject("SELECT  max(fld_section_id) as sectionid,fld_page_id as pageid
                                                                                        FROM itc_module_play_track
                                                                                        WHERE fld_schedule_id='".$scheduleid."'  AND fld_tester_id='".$stdid."' and fld_schedule_type='".$type."' and fld_module_id='".$modid."' AND fld_delstatus='0' AND fld_page_id<>'0' order by fld_id desc");
                        
                        if($qrytrackwca->num_rows>0)
                        {
                           $row=$qrytrackwca->fetch_assoc();
                           extract($row);
                        }
                        
                        $sessionid='';
                        if($sectionid=='0' OR $sectionid>'0' AND $pageid>'0')
                        {
                            $sessionid=$sectionid+1;
                            $completeprogresswca=($sessionid/7)*100;
                            $completeprogresswca=round($completeprogresswca,2);
                        }
                        else
                        {
                            $completeprogresswca=0;
                        }
                        
                        $stdpercentagewca[]=$stdid."~".$scheduleid."~".$completeprogresswca;//individual student percentage
                        
                        if($totalstdpercentwca=='')
                        {
                            $totalstdpercentwca=$completeprogresswca;
                        }
                        else
                        {
                            $totalstdpercentwca=$totalstdpercentwca+$completeprogresswca;
                        }
                        
                        if($stdcountwca==$a)
                        {
                            $schpercentagewca[]=$scheduleid."~".$totalstdpercentwca."~".$stdcountwca;//overall student percentage/schedule
                            $schpercentwca=$totalstdpercentwca/$stdcountwca;
                            
                        }

                        $a++;
                         
                } // Student while end
               
              } // Student if end
              
                            
			    if($totschedulepercentage=='')
                            {
                               
                                
                                $totschedulepercentage=round($schpercentwca);
                            }
                            else
                            {
                                
                                
                                $totschedulepercentage=$totschedulepercentage+round($schpercentwca);
                            }
               
               
              
           } // Schedule while loop end
           
        } // Schedule if end
	/*****WCA Code End Here Developed By Naren 1-12-2015*******/
        
        
	//Expedition Code Start Here
        $qryscheduledetails1=$ObjDB->QueryObject("SELECT a.fld_exp_id AS expid,b.fld_schedule_id AS scheduleid
                                                        FROM itc_class_indasexpedition_master AS a LEFT JOIN  itc_class_exp_student_mapping AS b ON a.fld_id=b.fld_schedule_id
                                                        WHERE a.fld_class_id='".$classid."' AND b.fld_flag='1' AND a.fld_delstatus='0' GROUP BY b.fld_schedule_id");
        
        $expschcount=$qryscheduledetails1->num_rows;
        if($qryscheduledetails1->num_rows>0)
        {
            $schcount=$qryscheduledetails1->num_rows;
            $schcnt=1;
            $totalschpercentexp='';
            while($rowqryscheduledetails1 = $qryscheduledetails1->fetch_assoc())
            {
                extract($rowqryscheduledetails1);
                $qrystddetails1=$ObjDB->QueryObject("SELECT a.fld_id AS stdid FROM itc_user_master AS a
                                                            LEFT JOIN itc_class_exp_student_mapping AS b ON a.fld_id=b.fld_student_id WHERE b.fld_schedule_id='".$scheduleid."' AND a.fld_delstatus='0' AND b.fld_flag='1'");
                if($qrystddetails1->num_rows>0)
                {
                    $stdcount=$qrystddetails1->num_rows;
                    $stdschedule=$stdcount."~".$scheduleid;
                    $i=1;
                    $totalstdpercent='';
                    while($rowqrystddetails1 = $qrystddetails1->fetch_assoc())
                    {
                        extract($rowqrystddetails1);
                        
                        $checkrestatuscnt = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_exp_res_status WHERE fld_exp_id='".$expid."' AND fld_school_id='".$senshlid."' AND fld_created_by='".$uid."' AND fld_user_id='".$indid."'");
                        if($checkrestatuscnt == '0')
                        {
                            $resourcegroupids1=$ObjDB->SelectSingleValue("select 
                                                                                GROUP_CONCAT(cnt.fld_id) 
                                                                            from
                                                                                (SELECT 
                                                                                    a.fld_id
                                                                                FROM
                                                                                    itc_exp_resource_master AS a
                                                                                LEFT JOIN itc_exp_task_master AS b ON a.fld_task_id = b.fld_id
                                                                                LEFT JOIN itc_exp_destination_master AS c ON b.fld_dest_id = c.fld_id
                                                                                LEFT JOIN itc_exp_res_status AS d ON d.fld_res_id = a.fld_id
                                                                                LEFT JOIN itc_license_exp_mapping AS e ON c.fld_id = e.fld_dest_id
                                                                                LEFT JOIN itc_license_track AS f ON e.fld_license_id = f.fld_license_id
                                                                                LEFT JOIN itc_class_indasexpedition_master as g ON e.fld_license_id = g.fld_license_id
                                                                                where
                                                                                    c.fld_exp_id = '".$expid."'
                                                                                        AND g.fld_id = '".$scheduleid."'
                                                                                        AND d.fld_school_id = '0'
                                                                                        AND d.fld_user_id = '0'
                                                                                        and d.fld_status = '1'
                                                                                        and a.fld_delstatus = '0'
                                                                                        and b.fld_delstatus = '0'
                                                                                        and c.fld_delstatus = '0'
                                                                                GROUP BY a.fld_id) as cnt");


                            $rescomplete = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_res_play_track WHERE fld_res_id IN ('".$resourcegroupids1."') AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='15' AND fld_delstatus='0' AND fld_student_id='".$stdid."' AND fld_read_status='1'");
                            $totalresource=sizeof(explode(',',$resourcegroupids1));

                        }
                        else
                        {
                            $resourcegroupids1=$ObjDB->SelectSingleValue("select 
                                                                                GROUP_CONCAT(cnt.fld_id) 
                                                                            from
                                                                                (SELECT 
                                                                                    a.fld_id
                                                                                FROM
                                                                                    itc_exp_resource_master AS a
                                                                                LEFT JOIN itc_exp_task_master AS b ON a.fld_task_id = b.fld_id
                                                                                LEFT JOIN itc_exp_destination_master AS c ON b.fld_dest_id = c.fld_id
                                                                                LEFT JOIN itc_exp_res_status AS d ON d.fld_res_id = a.fld_id
                                                                                LEFT JOIN itc_license_exp_mapping AS e ON c.fld_id = e.fld_dest_id
                                                                                LEFT JOIN itc_license_track AS f ON e.fld_license_id = f.fld_license_id
                                                                                LEFT JOIN itc_class_indasexpedition_master as g ON e.fld_license_id = g.fld_license_id
                                                                                where
                                                                                    c.fld_exp_id = '".$expid."'
                                                                                        AND g.fld_id = '".$scheduleid."'
                                                                                        AND d.fld_school_id = '".$senshlid."'
                                                                                        AND d.fld_created_by='".$uid."'
                                                                                        AND d.fld_user_id = '".$indid."'
                                                                                        and d.fld_status = '1'
                                                                                        and a.fld_delstatus = '0'
                                                                                        and b.fld_delstatus = '0'
                                                                                        and c.fld_delstatus = '0'
                                                                                GROUP BY a.fld_id) as cnt");

                            $rescomplete = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_res_play_track WHERE fld_res_id IN ('".$resourcegroupids1."') AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='15' AND fld_delstatus='0' AND fld_student_id='".$stdid."' AND fld_read_status='1'");
                            $totalresource=sizeof(explode(',',$resourcegroupids1));
                        }
                        
                        
                        $qryschdate1=$ObjDB->QueryObject("SELECT MIN(fld_startdate) AS mindate1,MAX(fld_enddate) AS maxdate1 FROM itc_class_indasexpedition_master WHERE fld_class_id='".$classid."'");

                        if($qryschdate1->num_rows>0)
                        {
                            $rowqryschdate1=$qryschdate1->fetch_assoc();
                            extract($rowqryschdate1);
                            $mindate = date("d-m-Y", strtotime($mindate1));
                            $maxdate = date("d-m-Y", strtotime($maxdate1));
                        }

                        $datetime1 = new DateTime($mindate);
                        $datetime2 = new DateTime($maxdate);
                        $interval = $datetime1->diff($datetime2);
                        $duration = $interval->format('%a');

                        if($duration==0)
                        {
                            $duration=1; 
                        }

                        $totalpx=$duration*70;
                        $progresspx=$totalpx;//-2;

                        if($totalresource==0)
                        {
                            $completeprogress=0;
                        }
                        else if($totalresource!=0)
                        {
                            $completeprogress=($rescomplete/$totalresource)*100;
                            $completeprogress=round($completeprogress,2);
                        }

                        $resprogress=($completeprogress/100)*$totalpx;
                        $progressreport=($resprogress/$progresspx);
                        $stdpercentage[]=$stdid."~".$scheduleid."~".$progressreport."~".$completeprogress;//individual student percentage

                        if($totalstdpercent=='')
                        {
                            $totalstdpercent=$progressreport;
                        }
                        else
                        {
                            $totalstdpercent=$totalstdpercent+$progressreport;
                        }

                        if($stdcount==$i)
                        {
                           $schpercentage[]=$scheduleid."~".$totalstdpercent."~".$stdcount;//overall student percentage/schedule
                           $schpercent=$totalstdpercent/$stdcount;
                        }

                        $i++;
                    }//student while loop end
                    
                }
               
                            if($totschedulepercentage=='')
                            {
                               
                                
                                $totschedulepercentage=$schpercent;
                            }
                            else
                            {
                                
                                
                                $totschedulepercentage=$totschedulepercentage+$schpercent;
                            }
                
            }//schedule while end
        } //schedule If end
	//Expedition Code End Here
        
        
	 /*****Mission Code Start Here Developed By Mohan M 26-11-2015*******/
        $qryscheduledetailsmis1=$ObjDB->QueryObject("SELECT a.fld_mis_id AS misid,b.fld_schedule_id AS scheduleid
                                                        FROM itc_class_indasmission_master AS a LEFT JOIN  itc_class_mission_student_mapping AS b ON a.fld_id=b.fld_schedule_id
                                                        WHERE a.fld_class_id='".$classid."' AND b.fld_flag='1' AND a.fld_delstatus='0' GROUP BY b.fld_schedule_id");
        $misschcount=$qryscheduledetailsmis1->num_rows;
        if($qryscheduledetailsmis1->num_rows>0)
        {
            $schcountmis=$qryscheduledetailsmis1->num_rows;
            $schcntmis=1;
            $totalschpercentmis='';
            while($rowqryscheduledetailsmis1 = $qryscheduledetailsmis1->fetch_assoc())
            {
                extract($rowqryscheduledetailsmis1);
                $qrystddetailsmis1=$ObjDB->QueryObject("SELECT a.fld_id AS stdid FROM itc_user_master AS a
                                                            LEFT JOIN itc_class_mission_student_mapping AS b ON a.fld_id=b.fld_student_id WHERE b.fld_schedule_id='".$scheduleid."' AND a.fld_delstatus='0' AND b.fld_flag='1'");
                if($qrystddetailsmis1->num_rows>0)
                {
                    $stdcountmis=$qrystddetailsmis1->num_rows;
                    $stdschedulemis=$stdcountmis."~".$scheduleid;
                    $imis=1;
                    $totalstdpercentmis='';
                    while($rowqrystddetailsmis1 = $qrystddetailsmis1->fetch_assoc())
                    {
                        extract($rowqrystddetailsmis1);
                        $checkrestatuscnt = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_mis_res_status WHERE fld_mis_id='".$misid."' AND fld_school_id='".$senshlid."' AND fld_created_by='".$uid."' AND fld_user_id='".$indid."'");
                        if($checkrestatuscnt == '0')
                        {
                            $resourcegroupids1=$ObjDB->SelectSingleValue("select 
                                                                                GROUP_CONCAT(cnt.fld_id) 
                                                                            from
                                                                                (SELECT 
                                                                                    a.fld_id
                                                                                FROM
                                                                                    itc_mis_resource_master AS a
                                                                                LEFT JOIN itc_mis_task_master AS b ON a.fld_task_id = b.fld_id
                                                                                LEFT JOIN itc_mis_destination_master AS c ON b.fld_dest_id = c.fld_id
                                                                                LEFT JOIN itc_mis_res_status AS d ON d.fld_res_id = a.fld_id
                                                                                LEFT JOIN itc_license_mission_mapping AS e ON c.fld_id = e.fld_dest_id
                                                                                LEFT JOIN itc_license_track AS f ON e.fld_license_id = f.fld_license_id
                                                                                LEFT JOIN itc_class_indasmission_master as g ON e.fld_license_id = g.fld_license_id
                                                                                where
                                                                                    c.fld_mis_id = '".$misid."'
                                                                                        AND g.fld_id = '".$scheduleid."'
                                                                                        AND d.fld_school_id = '0'
                                                                                        AND d.fld_user_id = '0'
                                                                                        and d.fld_status = '1'
                                                                                        and a.fld_delstatus = '0'
                                                                                        and b.fld_delstatus = '0'
                                                                                        and c.fld_delstatus = '0'
                                                                                GROUP BY a.fld_id) as cnt");

                            $rescompletemis = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_res_play_track WHERE fld_res_id IN ('".$resourcegroupids1."') AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='18' AND fld_delstatus='0' AND fld_student_id='".$stdid."' AND fld_read_status='1'");
                            $totalresourcemis=sizeof(explode(',',$resourcegroupids1));

                        }
                        else
                        {
                            $resourcegroupids1=$ObjDB->SelectSingleValue("select 
                                                                                GROUP_CONCAT(cnt.fld_id) 
                                                                            from
                                                                                (SELECT 
                                                                                    a.fld_id
                                                                                FROM
                                                                                    itc_mis_resource_master AS a
                                                                                LEFT JOIN itc_mis_task_master AS b ON a.fld_task_id = b.fld_id
                                                                                LEFT JOIN itc_mis_destination_master AS c ON b.fld_dest_id = c.fld_id
                                                                                LEFT JOIN itc_mis_res_status AS d ON d.fld_res_id = a.fld_id
                                                                                LEFT JOIN itc_license_mission_mapping AS e ON c.fld_id = e.fld_dest_id
                                                                                LEFT JOIN itc_license_track AS f ON e.fld_license_id = f.fld_license_id
                                                                                LEFT JOIN itc_class_indasmission_master as g ON e.fld_license_id = g.fld_license_id
                                                                                where
                                                                                    c.fld_mis_id = '".$misid."'
                                                                                        AND g.fld_id = '".$scheduleid."'
                                                                                        AND d.fld_school_id = '".$senshlid."'
                                                                                        AND d.fld_created_by='".$uid."'
                                                                                        AND d.fld_user_id = '".$indid."'
                                                                                        and d.fld_status = '1'
                                                                                        and a.fld_delstatus = '0'
                                                                                        and b.fld_delstatus = '0'
                                                                                        and c.fld_delstatus = '0'
                                                                                GROUP BY a.fld_id) as cnt");

                            $rescompletemis = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_res_play_track WHERE fld_res_id IN ('".$resourcegroupids1."') AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='18' AND fld_delstatus='0' AND fld_student_id='".$stdid."' AND fld_read_status='1'");
                            $totalresourcemis=sizeof(explode(',',$resourcegroupids1));
                        }
                        
                        
                        $qryschdate1=$ObjDB->QueryObject("SELECT MIN(fld_startdate) AS mindatemis1,MAX(fld_enddate) AS maxdatemis1 FROM itc_class_indasmission_master WHERE fld_class_id='".$classid."'");

                        if($qryschdate1->num_rows>0)
                        {
                            $rowqryschdate1=$qryschdate1->fetch_assoc();
                            extract($rowqryschdate1);
                            $mindatemis = date("d-m-Y", strtotime($mindatemis1));
                            $maxdatemis = date("d-m-Y", strtotime($maxdatemis1));
                        }

                        $datetimemis1 = new DateTime($mindatemis);
                        $datetimemis2 = new DateTime($maxdatemis);
                        $intervalmis = $datetimemis1->diff($datetimemis2);
                        $durationmis = $intervalmis->format('%a');

                        if($durationmis==0)
                        {
                            $durationmis=1; 
                        }

                        $totalpxmis=$durationmis*70;
                        $progresspxmis=$totalpxmis;//-2;

                        if($totalresourcemis==0)
                        {
                            $completeprogressmis=0;
                        }
                        else if($totalresourcemis!=0)
                        {
                          	$completeprogressmis=($rescompletemis/$totalresourcemis)*100;
                            $completeprogressmis=round($completeprogressmis,2);
                        }
                        $resprogressmis=($completeprogressmis/100)*$totalpxmis;
                        $progressreportmis=($resprogressmis/$progresspxmis);
                        $stdpercentagemis[]=$stdid."~".$scheduleid."~".$progressreportmis."~".$completeprogressmis;//individual student percentage

                        if($totalstdpercentmis=='')
                        {
                            $totalstdpercentmis=$progressreportmis;
                        }
                        else
                        {
                            $totalstdpercentmis=$totalstdpercentmis+$progressreportmis;
                        }

                        if($stdcountmis==$imis)
                        {
                           $schpercentagemis[]=$scheduleid."~".$totalstdpercentmis."~".$stdcountmis;//overall student percentage/schedule
                           $schpercentmis=$totalstdpercentmis/$stdcountmis;
                        }
                        $imis++;
                    }//Student While Loop
                }// Student If Condition
                
                
                            if($totschedulepercentage=='')
                            {
                                
                                $totschedulepercentage=$schpercentmis;
                            }
                            else
                            {
                               
                                $totschedulepercentage=$totschedulepercentage+$schpercentmis;
                            }
                
            }//mission Schedule Loop
        }//mission Schedule If Condition
	/*****Mission Code End Here Developed By Mohan M 26-11-2015*******/
        
               
        /***************Mission rotational schedule start here ***********************************/
        $qryschedulerotmission=$ObjDB->QueryObject("SELECT a.fld_numberofrotations as totrot,a.fld_id AS scheduleid,a.fld_startdate AS startdate, a.fld_enddate AS enddate
                                            FROM itc_class_rotation_mission_mastertemp AS a LEFT JOIN itc_class_rotation_mission_student_mappingtemp AS b 
                                                    ON b.fld_schedule_id=a.fld_id
                                            WHERE a.fld_class_id='".$classid."' AND a.fld_delstatus='0' AND a.fld_flag='1' AND b.fld_flag='1' 
                                            GROUP BY scheduleid");
        $rotmissionschcount=$qryschedulerotmission->num_rows;
         if($qryschedulerotmission->num_rows>0)
        {
            while($rowqryschedulerotmission = $qryschedulerotmission->fetch_assoc())
            {
                extract($rowqryschedulerotmission);
                
                $totrots=$totrot+1;
                $totmissionrotpercentage='';
                $missionrotschedulepercentage='';
                for($rot=2;$rot<=$totrots;$rot++)
                {
                        $missionrotpercentage='';
                        $qrystddetailsrotmission=$ObjDB->QueryObject("SELECT a.fld_id AS stdid FROM itc_user_master AS a 
                                                        LEFT JOIN itc_class_rotation_mission_student_mappingtemp AS b ON a.fld_id=b.fld_student_id
                                                        WHERE b.fld_schedule_id='".$scheduleid."' AND b.fld_flag='1' AND a.fld_delstatus='0'");
                        
                        $studcount=$qrystddetailsrotmission->num_rows;
                        
                        if($qrystddetailsrotmission->num_rows>0)
                        {
                            $totstdpercentagerotmission='';
                            
                            while($rowqrystddetailsrotmission = $qrystddetailsrotmission->fetch_assoc())
                            {
                                    extract($rowqrystddetailsrotmission);
                                    
                                    $missionid=$ObjDB->SelectSingleValueInt("SELECT fld_mission_id FROM itc_class_rotation_mission_schedulegriddet WHERE fld_rotation='".$rot."' AND fld_student_id='".$stdid."' AND fld_schedule_id='".$scheduleid."' AND fld_flag='1'");

                                    $checkrestatuscnt = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_mis_res_status WHERE fld_mis_id='".$missionid."' AND fld_school_id='".$senshlid."' AND fld_created_by='".$uid."' AND fld_user_id='".$indid."'");
                                    
                                    if($checkrestatuscnt == '0')
                                    {
                                        $resourcegroupids1=$ObjDB->SelectSingleValue("select 
                                                                                GROUP_CONCAT(cnt.fld_id) 
                                                                            from
                                                                                (SELECT 
                                                                                    a.fld_id
                                                                                FROM
                                                                                    itc_mis_resource_master AS a
                                                                                LEFT JOIN itc_mis_task_master AS b ON a.fld_task_id = b.fld_id
                                                                                LEFT JOIN itc_mis_destination_master AS c ON b.fld_dest_id = c.fld_id
                                                                                LEFT JOIN itc_mis_res_status AS d ON d.fld_res_id = a.fld_id
                                                                                LEFT JOIN itc_license_mission_mapping AS e ON c.fld_id = e.fld_dest_id
                                                                                LEFT JOIN itc_license_track AS f ON e.fld_license_id = f.fld_license_id
                                                                                LEFT JOIN itc_class_indasmission_master as g ON e.fld_license_id = g.fld_license_id
                                                                                where
                                                                                    c.fld_mis_id = '".$missionid."'
                                                                                        AND g.fld_id = '".$scheduleid."'
                                                                                        AND d.fld_school_id = '0'
                                                                                        AND d.fld_user_id = '0'
                                                                                        and d.fld_status = '1'
                                                                                        and a.fld_delstatus = '0'
                                                                                        and b.fld_delstatus = '0'
                                                                                        and c.fld_delstatus = '0'
                                                                                GROUP BY a.fld_id) as cnt");

                                        if($resourcegroupids1=='')
                                        {
                                            $resourcegroupids1=0;
                                        }
                                        
                                        $rescomplete = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_res_play_track WHERE fld_res_id IN ('".$resourcegroupids1."') AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='23' AND fld_delstatus='0' AND fld_student_id='".$stdid."' AND fld_read_status='1'");
                                        $totalresource=sizeof(explode(',',$resourcegroupids1));

                                    }
                                    else
                                    {
                                        $resourcegroupids1=$ObjDB->SelectSingleValue("select 
                                                                                GROUP_CONCAT(cnt.fld_id) 
                                                                            from
                                                                                (SELECT 
                                                                                    a.fld_id
                                                                                FROM
                                                                                    itc_mis_resource_master AS a
                                                                                LEFT JOIN itc_mis_task_master AS b ON a.fld_task_id = b.fld_id
                                                                                LEFT JOIN itc_mis_destination_master AS c ON b.fld_dest_id = c.fld_id
                                                                                LEFT JOIN itc_mis_res_status AS d ON d.fld_res_id = a.fld_id
                                                                                LEFT JOIN itc_license_mission_mapping AS e ON c.fld_id = e.fld_dest_id
                                                                                LEFT JOIN itc_license_track AS f ON e.fld_license_id = f.fld_license_id
                                                                                LEFT JOIN itc_class_indasmission_master as g ON e.fld_license_id = g.fld_license_id
                                                                                where
                                                                                    c.fld_mis_id = '".$missionid."'
                                                                                        AND g.fld_id = '".$scheduleid."'
                                                                                        AND d.fld_school_id = '".$senshlid."'
                                                                                        AND d.fld_created_by='".$uid."'
                                                                                        AND d.fld_user_id = '".$indid."'
                                                                                        and d.fld_status = '1'
                                                                                        and a.fld_delstatus = '0'
                                                                                        and b.fld_delstatus = '0'
                                                                                        and c.fld_delstatus = '0'
                                                                                GROUP BY a.fld_id) as cnt");
                                        
                                        if($resourcegroupids1=='')
                                        {
                                            $resourcegroupids1=0;
                                        }

                                        $rescomplete = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_res_play_track WHERE fld_res_id IN ('".$resourcegroupids1."') AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='23' AND fld_delstatus='0' AND fld_student_id='".$stdid."' AND fld_read_status='1'");
                                        $totalresource=sizeof(explode(',',$resourcegroupids1));
                                    }
                                    
                                    if($totalresource==0)
                                    {
                                        $completeprogressmis=0;
                                    }
                                    else if($totalresource!=0)
                                    {
                                        $completeprogressmis=($rescomplete/$totalresource);
                                        $completeprogressmis=round($completeprogressmis,2);
                                    }

                                    $stdpercentagerotmission[]=$rot."~".$stdid."~".$scheduleid."~".$completeprogressmis."~".$missionid;//individual student percentage
                                    
                                    if($totstdpercentagerotmission=='')
                                    {
                                        $totstdpercentagerotmission=$completeprogressmis;
                                    }
                                    else
                                    {
                                        $totstdpercentagerotmission=$totstdpercentagerotmission+$completeprogressmis;
                                    }
                                    
                                    
                            } // student while loop end
                            
                        } // student if loop end
                        
                                  if($studcount>0)
                                  {
                                  $missionrotpercentage=$totstdpercentagerotmission/$studcount;
                                  }
                                  else
                                  {
                                      $missionrotpercentage=0;
                                  }
                                  
                                  if($totmissionrotpercentage=='')
                                  {
                                      $totmissionrotpercentage=$missionrotpercentage;
                                  }
                                  else
                                  {
                                      $totmissionrotpercentage=$totmissionrotpercentage+$missionrotpercentage;
                                  }
                                  
                                  
                                  $missionrotationpercentagearray[]=$rot."~".$scheduleid."~".$missionrotpercentage;
                                  
                                  
                } // rotation for loop end 
                
                            $missionrotschedulepercentage=$totmissionrotpercentage/$totrot;
                            
                            $schedulepercentagerotmission[]=$scheduleid."~".$missionrotschedulepercentage;
                            
                           
                            
                            if($totschedulepercentage=='')
                            {
                                
                                
                                $totschedulepercentage=round($missionrotschedulepercentage*100);
                            }
                            else
                            {
                                
                                
                                $totschedulepercentage=$totschedulepercentage+round($missionrotschedulepercentage*100);
                            }
                            
                            
                
                
            } //schedule while loop end
        } // schedule end
        
        /************End here*************************/
		$totschcount=$misschcount+$expschcount+$wcaschcount+$iplschcount+$rotexpschcount+$rotschcount+$rotmissionschcount;
                $classpercentage[]=$classid."~".$totschedulepercentage."~".$totschcount;//overall schedule percentage/class
                
       }//class while end
}

$qryclassdetails=$ObjDB->QueryObject("SELECT fld_id as classid,fld_class_name as classname,DATE_FORMAT(fld_start_date, '%d-%m-%Y') AS stdate,DATE_FORMAT(fld_end_date, '%d-%m-%Y') AS enddate 
                                        FROM itc_class_master 
                                        WHERE fld_delstatus='0' AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND (fld_id IN(SELECT fld_class_id FROM itc_class_teacher_mapping 
                                        WHERE fld_teacher_id='".$uid."' AND fld_flag='1'))
                                        GROUP BY fld_id ORDER BY fld_id DESC");


?>
<!DOCTYPE html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<title>Progress Analytics</title>
</head>

<link rel="stylesheet" type="text/css" href="../fancybox/jquery.fancybox-1.3.4.css" media="screen" />
<link rel="stylesheet" href="dhtmlxgantt.css" type="text/css" media="screen" title="no title" charset="utf-8">
<link rel="stylesheet" href="../css/zebra_dialog.css" type="text/css" media="screen" title="no title" charset="utf-8">

<script language="javascript" type="text/javascript" src='../js/jquery.js'></script>
<script language="javascript" type="text/javascript" src='../fancybox/jquery.mousewheel-3.0.4.pack.js'></script>
<script language="javascript" type="text/javascript" src='ganttfancy.js'></script>
<script language="javascript" type="text/javascript" src="stdprogress.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="dhtmlxgantt_tooltip.js"></script>
<script language="javascript" type="text/javascript" src="../js/zebra_dialog.js"></script>


	<style type="text/css">
		html, body{ height:100%; padding:0px; margin:0px; overflow: hidden;}
	</style>
<body>
	<div id="gantt_here" style='width:100%; height:100%;'></div>
	<script type="text/javascript">
        var tasks =  {
            data:[
                <?php
                    if($qryclassdetails->num_rows>0)
                    {
                        $classcount=$qryclassdetails->num_rows;
                        while($rowclassdetails = $qryclassdetails->fetch_assoc())
                        {
                            extract($rowclassdetails);
                            
                                 $mindate = date("d-m-Y", strtotime($stdate));
                                 $maxdate = date("d-m-Y", strtotime($enddate));
                                 $maxdate = date('d-m-Y', strtotime("+1 day", strtotime($maxdate))); 
                                    $datetime1 = new DateTime($mindate);
                                    $datetime2 = new DateTime($maxdate);
                                    $interval = $datetime1->diff($datetime2);
                                    $duration = $interval->format('%a');
                                    if($duration==0)
                                    {
                                       $duration=1; 
                                    }
                                    
                                    foreach($classpercentage as $key=>$classpercent)
                                    {
                                        $classpercent1=explode("~",$classpercent);
                                        if($classpercent1[0]==$classid)
                                        {
                                          if($classpercent1[2]!=0)
                                          {
                                           $pclasspercentage=($classpercent1[1]/$classpercent1[2])/100;
                                        }
                                          else
                                          {
                                              $pclasspercentage=0;
                                    }
                                        }
                                    }
                            
                            ?>
                            {id:<?php echo $classid; ?>, text:"<?php echo $classname." / ".'Class';?>", start_date:"<?php echo $mindate;?>", duration:<?php echo $duration;?>,order:0,
                             progress:<?php echo $pclasspercentage;?>, open: false},
                            
                            /*****IPL Code Start Here Developed By Mohan M 25-11-2015*******/
                            <?php
                            $qryscheduledetailsipl1=$ObjDB->QueryObject("SELECT a.fld_schedule_name AS schedulename,a.fld_id AS scheduleid,
                                                                                DATE_FORMAT(a.fld_start_date, '%d-%m-%Y') AS sch_stdateipl,DATE_FORMAT(a.fld_end_date, '%d-%m-%Y') AS sch_enddateipl    
                                                                                FROM itc_class_sigmath_master AS a 
                                                                                LEFT JOIN  itc_class_sigmath_student_mapping AS b ON a.fld_id=b.fld_sigmath_id
                                                                                WHERE a.fld_class_id='".$classid."' AND b.fld_flag='1' AND a.fld_delstatus='0' GROUP BY b.fld_sigmath_id");

                            if($qryscheduledetailsipl1->num_rows>0)
                            {
                                $studentoverallipl='';
                                while($rowscheduledetailsipl = $qryscheduledetailsipl1->fetch_assoc())
                                {
                                    extract($rowscheduledetailsipl);
                                    
                                    $sch_enddateipl = date('d-m-Y', strtotime("+1 day", strtotime($sch_enddateipl)));
                                    $datetimeipl1 = new DateTime($sch_stdateipl);
                                    $datetimeipl2 = new DateTime($sch_enddateipl);
                                    $intervalipl = $datetimeipl1->diff($datetimeipl2);
                                    $durationipl = $intervalipl->format('%a');

                                    if($durationipl==0)
                                    {
                                        $durationipl=1;
                                    }

                                    foreach($schpercentageipl as $key=>$schvalipl)
                                    {
                                       $schvalipl1=explode("~",$schvalipl);
                                        if($schvalipl1[0]==$scheduleid)
                                        {
                                            if($schvalipl1[2]==0)
                                            {
                                                $studentoverallipl=0;
                                            }
                                            else
                                            {
                                                $studentoverallipl=$schvalipl1[1]/$schvalipl1[2];
                                            }
                                        }
                                    }
                                    
                                    ?>
                                    {id: '<?php echo $scheduleid."@".$classid;?>', text:"<?php echo $schedulename." / ".'Schedule';?>", start_date:"<?php echo $sch_stdateipl;?>", duration:<?php echo $durationipl; ?>, order:10,
                                    progress:<?php echo $studentoverallipl; ?>, parent:<?php echo $classid; ?>}, 

                                    <?php
                                    $qrystddetailsipl=$ObjDB->QueryObject("SELECT a.fld_id AS stdid, CONCAT(a.fld_lname,' ', a.fld_fname) AS stdname FROM itc_user_master AS a
                                                                              LEFT JOIN itc_class_student_mapping AS b ON a.fld_id=b.fld_student_id 
                                                                              LEFT JOIN itc_class_sigmath_student_mapping AS c ON c.fld_student_id=a.fld_id
                                                                              WHERE b.fld_class_id='".$classid."' AND a.fld_delstatus='0' AND b.fld_flag='1' AND c.fld_flag='1' AND c.fld_sigmath_id='".$scheduleid."' order by a.fld_lname ASC");
                                    if($qrystddetailsipl->num_rows>0)
                                    {
                                        while($rowstddetailsipl = $qrystddetailsipl->fetch_assoc())
                                        {
                                            extract($rowstddetailsipl);
                                            $datetimeipl1 = new DateTime($sch_stdateipl);
                                            $datetimeipl2 = new DateTime($sch_enddateipl);
                                            $intervalipl = $datetimeipl1->diff($datetimeipl2);
                                            $durationipl = $intervalipl->format('%a');
                                            
                                            if($durationipl==0)
                                            {
                                                $durationipl=1;
                                            }

                                            foreach($stdpercentageipl as $key=>$stdvalipl)
                                            {
                                                $stdvalipl1=explode("~",$stdvalipl);
                                                if($stdvalipl1[0]==$stdid && $stdvalipl1[1]==$scheduleid)
                                                {
                                                    $stdindvidualprogressipl=$stdvalipl1[3]/100;
                                                }
                                            }
                                            ?>
                                            {id: '<?php echo $scheduleid."~".$stdid."~".$classid."~1";?>', text:"<?php echo $stdname." / ".'Student';?>", start_date:"<?php echo $sch_stdateipl;?>", duration:<?php echo $durationipl;?>, order:10,
                                            progress:<?php echo $stdindvidualprogressipl; ?>,parent:'<?php echo $scheduleid."@".$classid; ?>'},
                                            <?php
                                        } // student while end
                                    }
											
											
                                } //schedule while end
                            } //schedule If end
                            ?>
                            /*****IPL Code Start Here Developed By Mohan M 25-11-2015*******/
                            
                            /*****************Rotational schedule developed by naren 1-7-2016*******************/
                            <?php
                                        $qryschedulerot1=$ObjDB->QueryObject("SELECT a.fld_moduletype as mtype,a.fld_schedule_name AS schedulename,a.fld_numberofrotations as totrot,a.fld_id AS scheduleid,DATE_FORMAT(a.fld_startdate, '%d-%m-%Y') AS startdaterot, DATE_FORMAT(a.fld_enddate, '%d-%m-%Y') AS enddaterot
                                            FROM itc_class_rotation_schedule_mastertemp AS a LEFT JOIN itc_class_rotation_schedule_student_mappingtemp AS b 
                                                    ON b.fld_schedule_id=a.fld_id
                                            WHERE a.fld_class_id='".$classid."' AND a.fld_delstatus='0' AND a.fld_flag='1' AND b.fld_flag='1' 
                                            GROUP BY scheduleid");
                                        
                                            if($qryschedulerot1->num_rows>0)
                                           {
                                               $studentoverallrot='';
                                               while($rowqryschedulerot1 = $qryschedulerot1->fetch_assoc())
                                               {
                                                   extract($rowqryschedulerot1);
                                                   
                                                    $enddaterot = date('d-m-Y', strtotime("+1 day", strtotime($enddaterot)));
                                                    $datetimerot1 = new DateTime($startdaterot);
                                                    $datetimerot2 = new DateTime($enddaterot);
                                                    $intervalrot = $datetimerot1->diff($datetimerot2);
                                                    $durationrot = $intervalrot->format('%a');

                                                    if($durationrot==0)
                                                    {
                                                        $durationrot=1;
                                                    }
                                                    
                                                    foreach($schedulepercentagerot as $key=>$schvalrot)
                                                    {
                                                        $schvalrot1=explode("~",$schvalrot);
                                                        if($schvalrot1[0]==$scheduleid)
                                                        {
                                                            $schpercentage=$schvalrot1[1]/100;
                                                        }
                                                    }
                                                   
                                               ?>
                                                   {id: '<?php echo $scheduleid."@".$classid;?>', text:"<?php echo $schedulename." / ".'Schedule';?>", start_date:"<?php echo $startdaterot;?>", duration:<?php echo $durationrot; ?>, order:10,
                                                    progress:<?php echo $schpercentage; ?>, parent:<?php echo $classid; ?>}, 

                                               <?php

                                                   $totrots=$totrot+1;
                                                   for($rot=2;$rot<=$totrots;$rot++)
                                                   {
                                                       $rotdet=$ObjDB->QueryObject("SELECT DATE_FORMAT(fld_startdate, '%d-%m-%Y') AS startdaterot, DATE_FORMAT(fld_enddate, '%d-%m-%Y') AS enddaterot FROM itc_class_rotation_scheduledate where fld_schedule_id='".$scheduleid."' and fld_rotation='".$rot."' and fld_flag='1'");
                                                       
                                                       if($rotdet->num_rows>0)
                                                       {
                                                           $rowrotdet=$rotdet->fetch_assoc();
                                                           extract($rowrotdet);
                                                       }
                                                       
                                                    $enddaterot = date('d-m-Y', strtotime("+1 day", strtotime($enddaterot)));
                                                    $datetimerot1 = new DateTime($startdaterot);
                                                    $datetimerot2 = new DateTime($enddaterot);
                                                    $intervalrot = $datetimerot1->diff($datetimerot2);
                                                    $durationrot = $intervalrot->format('%a');

                                                    if($durationrot==0)
                                                    {
                                                        $durationrot=1;
                                                    }
                                                    
                                                    foreach($rotationpercentagearray as $key=>$schvalrot)
                                                    {
                                                        $schvalrot1=explode("~",$schvalrot);
                                                        if($schvalrot1[0]==$rot AND $schvalrot1[1]==$scheduleid)
                                                        {
                                                            $rotpercentage=$schvalrot1[2]/100;
                                                        }
                                                    }
                                                    
                                                    $rotation=$rot-1;
                                                   
                                                  ?>
                                                      {id: '<?php echo $rot."@".$scheduleid;?>', text:"<?php echo "Rotation ".$rotation." / ".'Rotation';?>", start_date:"<?php echo $startdaterot;?>", duration:<?php echo $durationrot; ?>, order:10,
                                                           progress:<?php echo $rotpercentage; ?>, parent:'<?php echo $scheduleid."@".$classid; ?>'}, 

                                                  <?php
                                                       
                                                                $type='';
                        
                                                                if($mtype==1)
                                                                {
                                                                    $type=1;
                                                                }
                                                                else if($mtype==2)
                                                                {
                                                                    $type=4;
                                                                }
                                                       
                                                     
                                                           $qrystddetailsrot1=$ObjDB->QueryObject("SELECT a.fld_id AS stdid, CONCAT(a.fld_lname,' ', a.fld_fname) AS stdname FROM itc_user_master AS a
                                                                              LEFT JOIN itc_class_student_mapping AS b ON a.fld_id=b.fld_student_id 
                                                                              LEFT JOIN itc_class_rotation_schedule_student_mappingtemp AS c ON c.fld_student_id=a.fld_id
                                                                              WHERE b.fld_class_id='".$classid."' AND a.fld_delstatus='0' AND b.fld_flag='1' AND c.fld_flag='1' AND c.fld_schedule_id='".$scheduleid."' order by a.fld_lname ASC");

                                                           
                                                           if($qrystddetailsrot1->num_rows>0)
                                                           {
                                                               
                                                               while($rowqrystddetailsrot1 = $qrystddetailsrot1->fetch_assoc())
                                                               {
                                                                       extract($rowqrystddetailsrot1);

                                                                       foreach($stdpercentagerot as $key=>$schvalrot)
                                                                       {
                                                                            $schvalrot1=explode("~",$schvalrot);
                                                                            if($schvalrot1[0]==$rot AND $schvalrot1[1]==$stdid AND $schvalrot1[2]==$scheduleid)
                                                                            {
                                                                                $stdpercentagemodrot=$schvalrot1[3]/100;
                                                                                $modid=$schvalrot1[4];
                                                                            }
                                                                       }
                                                                       
                                                                       ?>
                                                                            {id: '<?php echo $scheduleid."~".$stdid."~".$classid."~2~".$modid."~".$type."~".$stdpercentagemodrot;?>', text:"<?php echo $stdname." / ".'Student';?>", start_date:"<?php echo $startdaterot;?>", duration:<?php echo $durationrot;?>, order:10,
                                                                            progress:<?php echo $stdpercentagemodrot; ?>,parent:'<?php echo $rot."@".$scheduleid; ?>'},
                                                                     <?php

                                                               } // student while loop end
                                                            } // student if loop end
                                                       } // rotation for loop end 
                                                } //schedule while loop end
                                           } // schedule end
                                     
                                        
                            ?>
                            /***************Rotational schedule end here *************************/
                            
                            
                            /*******Rotational schedule for Dyad Developed by Mohan M 15/6/2016***********/
                            <?php
                            $qryschedulerot1=$ObjDB->QueryObject("SELECT '2' as mtype, a.fld_schedule_name AS schedulename, (MIN(DISTINCT(b.fld_rotation))) AS minids, 
                                                                    (MAX(DISTINCT(b.fld_rotation))) AS totrot,a.fld_id AS scheduleid,
                                                                    DATE_FORMAT(b.fld_startdate, '%d-%m-%Y') AS startdaterot, DATE_FORMAT(b.fld_enddate, '%d-%m-%Y') AS enddaterot
                                                                        FROM itc_class_dyad_schedulemaster AS a 
                                                                        LEFT JOIN itc_class_dyad_schedulegriddet AS b ON b.fld_schedule_id=a.fld_id
                                                                        WHERE b.fld_class_id='".$classid."' AND a.fld_delstatus='0' AND a.fld_flag='1' AND b.fld_flag='1' 
                                                                        GROUP BY scheduleid");
                            
                            if($qryschedulerot1->num_rows>0)
                            {
                                $studentoverallrot='';
                                while($rowqryschedulerot1 = $qryschedulerot1->fetch_assoc())
                                {
                                    extract($rowqryschedulerot1);
                                                   
                                    $enddaterot = date('d-m-Y', strtotime("+1 day", strtotime($enddaterot)));
                                    $datetimerot1 = new DateTime($startdaterot);
                                    $datetimerot2 = new DateTime($enddaterot);
                                    $intervalrot = $datetimerot1->diff($datetimerot2);
                                    $durationrot = $intervalrot->format('%a');

                                    if($durationrot==0)
                                    {
                                        $durationrot=1;
                                    }

                                    foreach($schedulepercentagerotdyad as $key=>$schvalrot)
                                    {
                                        $schvalrot1=explode("~",$schvalrot);
                                        if($schvalrot1[0]==$scheduleid)
                                        {
                                            $schpercentage=$schvalrot1[1]/100;
                                        }
                                    }
                                                   
                                    ?>
                                        {id: '<?php echo $scheduleid."@".$classid;?>', text:"<?php echo $schedulename." / ".'Schedule';?>", start_date:"<?php echo $startdaterot;?>", duration:<?php echo $durationrot; ?>, order:10,
                                         progress:<?php echo $schpercentage; ?>, parent:<?php echo $classid; ?>}, 

                            <?php
                                    
                                    for($rot=1;$rot<=$totrot;$rot++)
                                    {
                                        $rotdet=$ObjDB->QueryObject("SELECT DATE_FORMAT(fld_startdate, '%d-%m-%Y') AS startdaterot, DATE_FORMAT(fld_enddate, '%d-%m-%Y') AS enddaterot 
                                                                         FROM itc_class_dyad_schedulegriddet 
                                                                         where fld_schedule_id='".$scheduleid."' and fld_rotation='".$rot."' and fld_flag='1'");

                                        if($rotdet->num_rows>0)
                                        {
                                            $rowrotdet=$rotdet->fetch_assoc();
                                            extract($rowrotdet);
                                        }
                                                       
                                        $enddaterot = date('d-m-Y', strtotime("+1 day", strtotime($enddaterot)));
                                        $datetimerot1 = new DateTime($startdaterot);
                                        $datetimerot2 = new DateTime($enddaterot);
                                        $intervalrot = $datetimerot1->diff($datetimerot2);
                                        $durationrot = $intervalrot->format('%a');

                                        if($durationrot==0)
                                        {
                                            $durationrot=1;
                                        }

                                        foreach($rotationpercentagearraydyad as $key=>$schvalrot)
                                        {
                                            $schvalrot1=explode("~",$schvalrot);
                                            if($schvalrot1[0]==$rot AND $schvalrot1[1]==$scheduleid)
                                            {
                                                $rotpercentage=$schvalrot1[2]/100;
                                            }
                                        }

                                                   $rotation=$rot;
                                        ?>
                                            {id: '<?php echo $rot."@".$scheduleid;?>', text:"<?php echo "Rotation ".$rotation." / ".'Rotation';?>", start_date:"<?php echo $startdaterot;?>", duration:<?php echo $durationrot; ?>, order:10,
                                                 progress:<?php echo $rotpercentage; ?>, parent:'<?php echo $scheduleid."@".$classid; ?>'}, 

                                        <?php
                                                     
                                        $qrystddetailsrot1=$ObjDB->QueryObject("SELECT a.fld_id AS stdid, CONCAT(a.fld_lname,' ', a.fld_fname) AS stdname FROM itc_user_master AS a
                                                                                LEFT JOIN itc_class_student_mapping AS b ON a.fld_id=b.fld_student_id 
                                                                                LEFT JOIN itc_class_dyad_schedule_studentmapping AS c ON c.fld_student_id=a.fld_id
                                                                                WHERE b.fld_class_id='".$classid."' AND a.fld_delstatus='0' AND b.fld_flag='1' 
                                                                                AND c.fld_flag='1' AND c.fld_schedule_id='".$scheduleid."' order by a.fld_lname ASC");


                                        if($qrystddetailsrot1->num_rows>0)
                                        {

                                            while($rowqrystddetailsrot1 = $qrystddetailsrot1->fetch_assoc())
                                            {
                                                extract($rowqrystddetailsrot1);

                                                foreach($stdpercentagerotdyad as $key=>$schvalrot)
                                                {
                                                    $schvalrot1=explode("~",$schvalrot);
                                                    if($schvalrot1[0]==$rot AND $schvalrot1[1]==$stdid AND $schvalrot1[2]==$scheduleid)
                                                    {
                                                        $stdpercentagemodrot=$schvalrot1[3]/100;
                                                        $modid=$schvalrot1[4];
                                                    }
                                                }

                                                ?>
                                                    {id: '<?php echo $scheduleid."~".$stdid."~".$classid."~2~".$modid."~24~".$stdpercentagemodrot;?>', text:"<?php echo $stdname." / ".'Student';?>", start_date:"<?php echo $startdaterot;?>", duration:<?php echo $durationrot;?>, order:10,
                                                    progress:<?php echo $stdpercentagemodrot; ?>,parent:'<?php echo $rot."@".$scheduleid; ?>'},
                                                <?php

                                            } // student while loop end
                                         } // student if loop end
                                    } // rotation for loop end 
                                } //schedule while loop end
                            } // schedule end
                            ?>
                            /*******Rotational schedule for Dyad Developed by Mohan M 15/6/2016***********/
                            
                            
                            /*******Rotational schedule for triad Developed by Mohan M 15/6/2016***********/
                            <?php
                            $qryschedulerottriad=$ObjDB->QueryObject("SELECT '3' as mtype, a.fld_schedule_name AS schedulename, (MIN(DISTINCT(b.fld_rotation))) AS minids, 
                                                                    (MAX(DISTINCT(b.fld_rotation))) AS totrot,a.fld_id AS scheduleid,
                                                                    DATE_FORMAT(b.fld_startdate, '%d-%m-%Y') AS startdaterot, DATE_FORMAT(b.fld_enddate, '%d-%m-%Y') AS enddaterot
                                                                        FROM itc_class_triad_schedulemaster AS a 
                                                                        LEFT JOIN itc_class_triad_schedulegriddet AS b ON b.fld_schedule_id=a.fld_id
                                                                        WHERE b.fld_class_id='".$classid."' AND a.fld_delstatus='0' AND a.fld_flag='1' AND b.fld_flag='1' 
                                                                        GROUP BY scheduleid");
                                        
                            if($qryschedulerottriad->num_rows>0)
                            {
                                $studentoverallrot='';
                                while($rowqryschedulerottriad = $qryschedulerottriad->fetch_assoc())
                                {
                                    extract($rowqryschedulerottriad);
                                                   
                                    $enddaterot = date('d-m-Y', strtotime("+1 day", strtotime($enddaterot)));
                                    $datetimerot1 = new DateTime($startdaterot);
                                    $datetimerot2 = new DateTime($enddaterot);
                                    $intervalrot = $datetimerot1->diff($datetimerot2);
                                    $durationrot = $intervalrot->format('%a');

                                    if($durationrot==0)
                                    {
                                        $durationrot=1;
                                    }

                                    foreach($schedulepercentagerotdyad as $key=>$schvalrot)
                                    {
                                        $schvalrot1=explode("~",$schvalrot);
                                        if($schvalrot1[0]==$scheduleid)
                                        {
                                            $schpercentage=$schvalrot1[1]/100;
                                        }
                                    }
                                                   
                                    ?>
                                        {id: '<?php echo $scheduleid."@".$classid;?>', text:"<?php echo $schedulename." / ".'Schedule';?>", start_date:"<?php echo $startdaterot;?>", duration:<?php echo $durationrot; ?>, order:10,
                                         progress:<?php echo $schpercentage; ?>, parent:<?php echo $classid; ?>}, 

                                    <?php

                                    for($rot=1;$rot<=$totrot;$rot++)
                                    {
                                        $rotdet=$ObjDB->QueryObject("SELECT DATE_FORMAT(fld_startdate, '%d-%m-%Y') AS startdaterot, DATE_FORMAT(fld_enddate, '%d-%m-%Y') AS enddaterot 
                                                                         FROM itc_class_triad_schedulegriddet 
                                                                         where fld_schedule_id='".$scheduleid."' and fld_rotation='".$rot."' and fld_flag='1'");

                                        if($rotdet->num_rows>0)
                                        {
                                            $rowrotdet=$rotdet->fetch_assoc();
                                            extract($rowrotdet);
                                        }
                                                       
                                        $enddaterot = date('d-m-Y', strtotime("+1 day", strtotime($enddaterot)));
                                        $datetimerot1 = new DateTime($startdaterot);
                                        $datetimerot2 = new DateTime($enddaterot);
                                        $intervalrot = $datetimerot1->diff($datetimerot2);
                                        $durationrot = $intervalrot->format('%a');

                                        if($durationrot==0)
                                        {
                                            $durationrot=1;
                                        }

                                        foreach($rotationpercentagearraydyad as $key=>$schvalrot)
                                        {
                                            $schvalrot1=explode("~",$schvalrot);
                                            if($schvalrot1[0]==$rot AND $schvalrot1[1]==$scheduleid)
                                            {
                                                $rotpercentage=$schvalrot1[2]/100;
                                            }
                                        }

                                                   $rotation=$rot;
                                        ?>
                                            {id: '<?php echo $rot."@".$scheduleid;?>', text:"<?php echo "Rotation ".$rotation." / ".'Rotation';?>", start_date:"<?php echo $startdaterot;?>", duration:<?php echo $durationrot; ?>, order:10,
                                                 progress:<?php echo $rotpercentage; ?>, parent:'<?php echo $scheduleid."@".$classid; ?>'}, 

                                        <?php
                                                     
                                        $qrystddetailsrot1=$ObjDB->QueryObject("SELECT a.fld_id AS stdid, CONCAT(a.fld_lname,' ', a.fld_fname) AS stdname FROM itc_user_master AS a
                                                                                LEFT JOIN itc_class_student_mapping AS b ON a.fld_id=b.fld_student_id 
                                                                                LEFT JOIN itc_class_triad_schedule_studentmapping AS c ON c.fld_student_id=a.fld_id
                                                                                WHERE b.fld_class_id='".$classid."' AND a.fld_delstatus='0' AND b.fld_flag='1' 
                                                                                AND c.fld_flag='1' AND c.fld_schedule_id='".$scheduleid."' order by a.fld_lname ASC");


                                        if($qrystddetailsrot1->num_rows>0)
                                        {

                                            while($rowqrystddetailsrot1 = $qrystddetailsrot1->fetch_assoc())
                                            {
                                                extract($rowqrystddetailsrot1);

                                                foreach($stdpercentagerotdyad as $key=>$schvalrot)
                                                {
                                                    $schvalrot1=explode("~",$schvalrot);
                                                    if($schvalrot1[0]==$rot AND $schvalrot1[1]==$stdid AND $schvalrot1[2]==$scheduleid)
                                                    {
                                                        $stdpercentagemodrot=$schvalrot1[3]/100;
                                                        $modid=$schvalrot1[4];
                                                    }
                                                }

                                                ?>
                                                    {id: '<?php echo $scheduleid."~".$stdid."~".$classid."~2~".$modid."~25~".$stdpercentagemodrot;?>', text:"<?php echo $stdname." / ".'Student';?>", start_date:"<?php echo $startdaterot;?>", duration:<?php echo $durationrot;?>, order:10,
                                                    progress:<?php echo $stdpercentagemodrot; ?>,parent:'<?php echo $rot."@".$scheduleid; ?>'},
                                                <?php

                                            } // student while loop end
                                         } // student if loop end
                                    } // rotation for loop end 
                                } //schedule while loop end
                            } // schedule end
                            ?>
                            /*******Rotational schedule for triad Developed by Mohan M 15/6/2016***********/
                            
                            /*****************ModexpRotational schedule developed by naren 1-7-2016*******************/
                            <?php
                                        $qryschedulerotmodexp=$ObjDB->QueryObject("SELECT a.fld_schedule_name AS schedulename,a.fld_numberofrotations as totrot,a.fld_id AS scheduleid,DATE_FORMAT(a.fld_startdate, '%d-%m-%Y') AS startdaterot, DATE_FORMAT(a.fld_enddate, '%d-%m-%Y') AS enddaterot
                                            FROM itc_class_rotation_modexpschedule_mastertemp AS a LEFT JOIN itc_class_rotation_modexpschedule_student_mappingtemp AS b 
                                                    ON b.fld_schedule_id=a.fld_id
                                            WHERE a.fld_class_id='".$classid."' AND a.fld_delstatus='0' AND a.fld_flag='1' AND b.fld_flag='1' 
                                            GROUP BY scheduleid");
                                        
                                            if($qryschedulerotmodexp->num_rows>0)
                                           {
                                               $studentoverallrot='';
                                               while($rowqryschedulerotmodexp = $qryschedulerotmodexp->fetch_assoc())
                                               {
                                                   extract($rowqryschedulerotmodexp);
                                                   
                                                    $enddaterot = date('d-m-Y', strtotime("+1 day", strtotime($enddaterot)));
                                                    $datetimerot1 = new DateTime($startdaterot);
                                                    $datetimerot2 = new DateTime($enddaterot);
                                                    $intervalrot = $datetimerot1->diff($datetimerot2);
                                                    $durationrot = $intervalrot->format('%a');

                                                    if($durationrot==0)
                                                    {
                                                        $durationrot=1;
                                                    }
                                                    
                                                    foreach($schedulepercentagerotmod as $key=>$schvalrotmod)
                                                    {
                                                        $schvalrotmod1=explode("~",$schvalrotmod);
                                                        if($schvalrotmod1[0]==$scheduleid)
                                                        {
                                                            $rotmodschpercentage=$schvalrotmod1[1]/100;
                                                        }
                                                    }
                                                    
                                                    foreach($schedulepercentagerotmodexp as $key=>$schvalrotmodexp)
                                                    {
                                                        $schvalrotmodexp1=explode("~",$schvalrotmodexp);
                                                        if($schvalrotmodexp1[0]==$scheduleid)
                                                        {
                                                            $rotexpschpercentage=$schvalrotmodexp1[1]/100;
                                                        }
                                                    }
                                                    
                                                    if($rotmodschpercentage>0 AND $rotexpschpercentage>0)
                                                    {
                                                        $schpercentage=($rotmodschpercentage+$rotexpschpercentage)/2;
                                                    }
                                                    else if($rotmodschpercentage==0)
                                                    {
                                                        $schpercentage=$rotexpschpercentage;
                                                    }
                                                    else if($rotexpschpercentage==0)
                                                    {
                                                        $schpercentage=$rotmodschpercentage;
                                                    }
                                                   
                                               ?>
                                                   {id: '<?php echo $scheduleid."@".$classid;?>', text:"<?php echo $schedulename." / ".'Schedule';?>", start_date:"<?php echo $startdaterot;?>", duration:<?php echo $durationrot; ?>, order:10,
                                                    progress:<?php echo $schpercentage; ?>, parent:<?php echo $classid; ?>}, 

                                               <?php

                                                   $totrots=$totrot+1;
                                                   for($rot=2;$rot<=$totrots;$rot++)
                                                   {
                                                       $rotdet=$ObjDB->QueryObject("SELECT DATE_FORMAT(fld_startdate, '%d-%m-%Y') AS startdaterot, DATE_FORMAT(fld_enddate, '%d-%m-%Y') AS enddaterot FROM itc_class_rotation_modexpscheduledate where fld_schedule_id='".$scheduleid."' and fld_rotation='".$rot."' and fld_flag='1'");
                                                       
                                                       if($rotdet->num_rows>0)
                                                       {
                                                           $rowrotdet=$rotdet->fetch_assoc();
                                                           extract($rowrotdet);
                                                       }
                                                       
                                                    $enddaterot = date('d-m-Y', strtotime("+1 day", strtotime($enddaterot)));
                                                    $datetimerot1 = new DateTime($startdaterot);
                                                    $datetimerot2 = new DateTime($enddaterot);
                                                    $intervalrot = $datetimerot1->diff($datetimerot2);
                                                    $durationrot = $intervalrot->format('%a');

                                                    if($durationrot==0)
                                                    {
                                                        $durationrot=1;
                                                    }
                                                    
                                                    foreach($rotationpercentagearraymod as $key=>$schvalrot)
                                                    {
                                                        $schvalrot1=explode("~",$schvalrot);
                                                        if($schvalrot1[0]==$rot AND $schvalrot1[1]==$scheduleid)
                                                        {
                                                            $modrotpercentage=$schvalrot1[2]/100;
                                                        }
                                                    }
                                                    
                                                    foreach($modexprotationpercentagearray as $key=>$schvalrotexp)
                                                    {
                                                        $schvalrotexp1=explode("~",$schvalrotexp);
                                                        if($schvalrotexp1[0]==$rot AND $schvalrotexp1[1]==$scheduleid)
                                                        {
                                                            $exprotpercentage=$schvalrotexp1[2]/100;
                                                        }
                                                    }
                                                    
                                                    if($modrotpercentage>0 AND $exprotpercentage>0)
                                                    {
                                                        $rotpercentage=($modrotpercentage+$exprotpercentage)/2;
                                                    }
                                                    else if($modrotpercentage==0)
                                                    {
                                                        $rotpercentage=$modrotpercentage;
                                                    }
                                                    else if($exprotpercentage==0)
                                                    {
                                                        $rotpercentage=$exprotpercentage;
                                                    }
                                                    
                                                    $rotation=$rot-1;
                                                   
                                                  ?>
                                                      {id: '<?php echo $rot."@".$scheduleid;?>', text:"<?php echo "Rotation ".$rotation." / ".'Rotation';?>", start_date:"<?php echo $startdaterot;?>", duration:<?php echo $durationrot; ?>, order:10,
                                                           progress:<?php echo $rotpercentage; ?>, parent:'<?php echo $scheduleid."@".$classid; ?>'}, 

                                                  <?php
                                                       
                                                           $qrystddetailsrot1=$ObjDB->QueryObject("SELECT a.fld_id AS stdid, CONCAT(a.fld_lname,' ', a.fld_fname) AS stdname FROM itc_user_master AS a
                                                                              LEFT JOIN itc_class_student_mapping AS b ON a.fld_id=b.fld_student_id 
                                                                              LEFT JOIN itc_class_rotation_modexpschedule_student_mappingtemp AS c ON c.fld_student_id=a.fld_id
                                                                              WHERE b.fld_class_id='".$classid."' AND a.fld_delstatus='0' AND b.fld_flag='1' AND c.fld_flag='1' AND c.fld_schedule_id='".$scheduleid."' order by a.fld_lname ASC");

                                                           
                                                           if($qrystddetailsrot1->num_rows>0)
                                                           {
                                                               
                                                               while($rowqrystddetailsrot1 = $qrystddetailsrot1->fetch_assoc())
                                                               {
                                                                       extract($rowqrystddetailsrot1);

                                                                       foreach($stdpercentagerotmod as $key=>$schvalrot)
                                                                       {
                                                                            $schvalrot1=explode("~",$schvalrot);
                                                                            if($schvalrot1[0]==$rot AND $schvalrot1[1]==$stdid AND $schvalrot1[2]==$scheduleid)
                                                                            {
                                                                                $stdpercentagemodrot=$schvalrot1[3]/100;
                                                                                $modid=$schvalrot1[4];
                                                                                $type=21;
                                                                                $fn_type=2;
                                                                            }
                                                                       }
                                                                       
                                                                       foreach($stdpercentagerotmodexp as $key=>$schvalrotexp)
                                                                       {
                                                                            $schvalrotexp1=explode("~",$schvalrotexp);
                                                                            if($schvalrotexp1[0]==$rot AND $schvalrotexp1[1]==$stdid AND $schvalrotexp1[2]==$scheduleid)
                                                                            {
                                                                                $stdpercentagemodrot=$schvalrotexp1[3]/100;
                                                                                $modid=$schvalrotexp1[4];
                                                                                $type=20;
                                                                                $fn_type=3;
                                                                            }
                                                                       }
                                                                       
                                                                       ?>
                                                                            {id: '<?php echo $scheduleid."~".$stdid."~".$classid."~".$fn_type."~".$modid."~".$type."~".$stdpercentagemodrot;?>', text:"<?php echo $stdname." / ".'Student';?>", start_date:"<?php echo $startdaterot;?>", duration:<?php echo $durationrot;?>, order:10,
                                                                            progress:<?php echo $stdpercentagemodrot; ?>,parent:'<?php echo $rot."@".$scheduleid; ?>'},
                                                                     <?php

                                                               } // student while loop end
                                                            } // student if loop end
                                                       } // rotation for loop end 
                                                } //schedule while loop end
                                           } // schedule end
                                     
                                        
                            ?>
                            /***************ModexpRotational schedule end here *************************/
                            
                            /*****************Rotational expedition schedule developed by naren 1-7-2016*******************/
                            <?php
                                        $qryschedulerotexp1=$ObjDB->QueryObject("SELECT a.fld_schedule_name AS schedulename,a.fld_numberofrotations as totrot,a.fld_id AS scheduleid,DATE_FORMAT(a.fld_startdate, '%d-%m-%Y') AS startdaterotexp, DATE_FORMAT(a.fld_enddate, '%d-%m-%Y') AS enddaterotexp
                                            FROM itc_class_rotation_expschedule_mastertemp AS a LEFT JOIN itc_class_rotation_expschedule_student_mappingtemp AS b 
                                                    ON b.fld_schedule_id=a.fld_id
                                            WHERE a.fld_class_id='".$classid."' AND a.fld_delstatus='0' AND a.fld_flag='1' AND b.fld_flag='1' 
                                            GROUP BY scheduleid");
                                        
                                            if($qryschedulerotexp1->num_rows>0)
                                           {
                                               $studentoverallrotexp='';
                                               while($rowqryschedulerotexp1 = $qryschedulerotexp1->fetch_assoc())
                                               {
                                                   extract($rowqryschedulerotexp1);
                                                   
                                                    $enddaterotexp = date('d-m-Y', strtotime("+1 day", strtotime($enddaterotexp)));
                                                    $datetimerotexp1 = new DateTime($startdaterotexp);
                                                    $datetimerotexp2 = new DateTime($enddaterotexp);
                                                    $intervalrotexp = $datetimerotexp1->diff($datetimerotexp2);
                                                    $durationrotexp = $intervalrotexp->format('%a');

                                                    if($durationrotexp==0)
                                                    {
                                                        $durationrotexp=1;
                                                    }
                                                    
                                                    foreach($schedulepercentagerotexp as $key=>$schvalrotexp)
                                                    {
                                                        $schvalrotexp1=explode("~",$schvalrotexp);
                                                        if($schvalrotexp1[0]==$scheduleid)
                                                        {
                                                            $schpercentage=$schvalrotexp1[1];
                                                        }
                                                    }
                                                   
                                               ?>
                                                   {id: '<?php echo $scheduleid."@".$classid;?>', text:"<?php echo $schedulename." / ".'Schedule';?>", start_date:"<?php echo $startdaterotexp;?>", duration:<?php echo $durationrotexp; ?>, order:10,
                                                    progress:<?php echo $schpercentage; ?>, parent:<?php echo $classid; ?>}, 

                                               <?php

                                                   $totrots=$totrot+1;
                                                   for($rot=2;$rot<=$totrots;$rot++)
                                                   {
                                                       $rotdet=$ObjDB->QueryObject("SELECT DATE_FORMAT(fld_startdate, '%d-%m-%Y') AS startdaterot, DATE_FORMAT(fld_enddate, '%d-%m-%Y') AS enddaterot FROM itc_class_rotation_expscheduledate where fld_schedule_id='".$scheduleid."' and fld_rotation='".$rot."' and fld_flag='1'");
                                                       
                                                       if($rotdet->num_rows>0)
                                                       {
                                                           $rowrotdet=$rotdet->fetch_assoc();
                                                           extract($rowrotdet);
                                                       }
                                                       
                                                    $enddaterot = date('d-m-Y', strtotime("+1 day", strtotime($enddaterot)));
                                                    $datetimerot1 = new DateTime($startdaterot);
                                                    $datetimerot2 = new DateTime($enddaterot);
                                                    $intervalrot = $datetimerot1->diff($datetimerot2);
                                                    $durationrot = $intervalrot->format('%a');

                                                    if($durationrot==0)
                                                    {
                                                        $durationrot=1;
                                                    }
                                                    
                                                    foreach($exprotationpercentagearray as $key=>$schvalrot)
                                                    {
                                                        $schvalrot1=explode("~",$schvalrot);
                                                        if($schvalrot1[0]==$rot AND $schvalrot1[1]==$scheduleid)
                                                        {
                                                            $rotpercentage=$schvalrot1[2];
                                                        }
                                                    }
                                                    
                                                    $rotation=$rot-1;
                                                   
                                                  ?>
                                                      {id: '<?php echo $rot."@".$scheduleid;?>', text:"<?php echo "Rotation ".$rotation." / ".'Rotation';?>", start_date:"<?php echo $startdaterot;?>", duration:<?php echo $durationrot; ?>, order:10,
                                                           progress:<?php echo $rotpercentage; ?>, parent:'<?php echo $scheduleid."@".$classid; ?>'}, 

                                                  <?php
                                                       
                                                              
                                                       
                                                     
                                                           $qrystddetailsrot1=$ObjDB->QueryObject("SELECT a.fld_id AS stdid, CONCAT(a.fld_lname,' ', a.fld_fname) AS stdname FROM itc_user_master AS a
                                                                              LEFT JOIN itc_class_student_mapping AS b ON a.fld_id=b.fld_student_id 
                                                                              LEFT JOIN itc_class_rotation_expschedule_student_mappingtemp AS c ON c.fld_student_id=a.fld_id
                                                                              WHERE b.fld_class_id='".$classid."' AND a.fld_delstatus='0' AND b.fld_flag='1' AND c.fld_flag='1' AND c.fld_schedule_id='".$scheduleid."' order by a.fld_lname ASC");

                                                           
                                                           if($qrystddetailsrot1->num_rows>0)
                                                           {
                                                               
                                                               while($rowqrystddetailsrot1 = $qrystddetailsrot1->fetch_assoc())
                                                               {
                                                                       extract($rowqrystddetailsrot1);

                                                                       foreach($stdpercentagerotexp as $key=>$schvalrot)
                                                                       {
                                                                            $schvalrot1=explode("~",$schvalrot);
                                                                            if($schvalrot1[0]==$rot AND $schvalrot1[1]==$stdid AND $schvalrot1[2]==$scheduleid)
                                                                            {
                                                                                $stdpercentageexprot=$schvalrot1[3];
                                                                                $expid=$schvalrot1[4];
                                                                            }
                                                                       }
                                                                       
                                                                       ?>
                                                                            {id: '<?php echo $scheduleid."~".$stdid."~".$classid."~3~".$expid."~19";?>', text:"<?php echo $stdname." / ".'Student';?>", start_date:"<?php echo $startdaterot;?>", duration:<?php echo $durationrot;?>, order:10,
                                                                            progress:<?php echo $stdpercentageexprot; ?>,parent:'<?php echo $rot."@".$scheduleid; ?>'},
                                                                     <?php

                                                               } // student while loop end
                                                            } // student if loop end
                                                       } // rotation for loop end 
                                                } //schedule while loop end
                                           } // schedule end
                                     
                                        
                            ?>
                            /***************Rotational expedition schedule end here *************************/
                            
                            
                             /*****WCA Code Start Here Developed By naren 2-12-2015*******/
                            <?php
                            $qryscheduledetailswca1=$ObjDB->QueryObject("SELECT a.fld_schedule_name AS schedulename,a.fld_id AS scheduleid,a.fld_moduletype as modtype,
                                                                                DATE_FORMAT(a.fld_startdate, '%d-%m-%Y') AS sch_stdatewca,DATE_FORMAT(a.fld_enddate, '%d-%m-%Y') AS sch_enddatewca    
                                                                                FROM itc_class_indassesment_master AS a 
                                                                                LEFT JOIN  itc_class_indassesment_student_mapping AS b ON a.fld_id=b.fld_schedule_id
                                                                                WHERE a.fld_class_id='".$classid."' AND b.fld_flag='1' AND a.fld_delstatus='0' GROUP BY b.fld_schedule_id");

                            if($qryscheduledetailswca1->num_rows>0)
                            {
                                $studentoverallwca='';
                                while($rowscheduledetailswca = $qryscheduledetailswca1->fetch_assoc())
                                {
                                    extract($rowscheduledetailswca);
                                    
                                    $sch_enddatewca = date('d-m-Y', strtotime("+1 day", strtotime($sch_enddatewca)));
                                    $datetimewca1 = new DateTime($sch_stdatewca);
                                    $datetimewca2 = new DateTime($sch_enddatewca);
                                    $intervalwca = $datetimewca1->diff($datetimewca2);
                                    $durationwca = $intervalwca->format('%a');

                                    if($durationwca==0)
                                    {
                                        $durationwca=1;
                                    }

                                    foreach($schpercentagewca as $key=>$schvalwca)
                                    {
                                       $schvalwca1=explode("~",$schvalwca);
                                        if($schvalwca1[0]==$scheduleid)
                                        {
                                            if($schvalwca1[2]==0)
                                            {
                                                $studentoverallwca=0;
                                            }
                                            else
                                            {
                                                $studentoverallwca=($schvalwca1[1]/$schvalwca1[2])/100;
                                            }
                                        }
                                    }
                                    
                                    ?>
                                    {id: '<?php echo $scheduleid."@".$classid;?>', text:"<?php echo $schedulename." / ".'Schedule';?>", start_date:"<?php echo $sch_stdatewca;?>", duration:<?php echo $durationwca; ?>, order:10,
                                    progress:<?php echo $studentoverallwca; ?>, parent:<?php echo $classid; ?>}, 

                                    <?php
                                    
                                    $qrystddetailswca=$ObjDB->QueryObject("SELECT a.fld_id AS stdid, CONCAT(a.fld_lname,' ', a.fld_fname) AS stdname FROM itc_user_master AS a
                                                                              LEFT JOIN itc_class_student_mapping AS b ON a.fld_id=b.fld_student_id 
                                                                              LEFT JOIN itc_class_indassesment_student_mapping AS c ON c.fld_student_id=a.fld_id
                                                                              WHERE b.fld_class_id='".$classid."' AND a.fld_delstatus='0' AND b.fld_flag='1' AND c.fld_flag='1' AND c.fld_schedule_id='".$scheduleid."' order by a.fld_lname ASC");
                                    if($qrystddetailswca->num_rows>0)
                                    {
                                        while($rowstddetailswca = $qrystddetailswca->fetch_assoc())
                                        {
                                            extract($rowstddetailswca);
                                            $datetimewca1 = new DateTime($sch_stdatewca);
                                            $datetimewca2 = new DateTime($sch_enddatewca);
                                            $intervalwca = $datetimewca1->diff($datetimewca2);
                                            $durationwca = $intervalwca->format('%a');
                                            
                                            if($durationwca==0)
                                            {
                                                $durationwca=1;
                                            }

                                            foreach($stdpercentagewca as $key=>$stdvalwca)
                                            {
                                                $stdvalwca1=explode("~",$stdvalwca);
                                                if($stdvalwca1[0]==$stdid && $stdvalwca1[1]==$scheduleid)
                                                {
                                                  
                                                    $stdindvidualprogresswca=$stdvalwca1[2]/100;
                                                }
                                            }
                                            
                                                    
                                            ?>
                                            {id: '<?php echo $scheduleid."~".$stdid."~".$classid."~5~".$stdindvidualprogresswca;?>', text:"<?php echo $stdname." / ".'Student';?>", start_date:"<?php echo $sch_stdatewca;?>", duration:<?php echo $durationwca;?>, order:10,
                                            progress:<?php echo $stdindvidualprogresswca; ?>,parent:'<?php echo $scheduleid."@".$classid; ?>'},
                                            <?php
                                        } // student while end
                                    }
                                } //schedule while end
                            } //schedule If end
                            ?>
                            /*****WCA Code end Here Developed By Naren 2-12-2015*******/
                            
                            
                            //Expedition Code Start Here
                            <?php
                            $qryscheduledetails=$ObjDB->QueryObject("SELECT a.fld_schedule_name AS schedulename,a.fld_exp_id AS expid,b.fld_schedule_id AS scheduleid,
                                                                        DATE_FORMAT(a.fld_startdate, '%d-%m-%Y') AS sch_stdate,DATE_FORMAT(a.fld_enddate, '%d-%m-%Y') AS sch_enddate    
                                                                        FROM itc_class_indasexpedition_master AS a LEFT JOIN  itc_class_exp_student_mapping AS b ON a.fld_id=b.fld_schedule_id
                                                                        WHERE a.fld_class_id='".$classid."' AND b.fld_flag='1' AND a.fld_delstatus='0' GROUP BY b.fld_schedule_id");

                            if($qryscheduledetails->num_rows>0)
                            {
                                $studentoverall='';
                                while($rowscheduledetails = $qryscheduledetails->fetch_assoc())
                                {
                                    extract($rowscheduledetails);
                                    $sch_enddate = date('d-m-Y', strtotime("+1 day", strtotime($sch_enddate)));
                                    $datetime1 = new DateTime($sch_stdate);
                                    $datetime2 = new DateTime($sch_enddate);
                                    $interval = $datetime1->diff($datetime2);
                                    $duration = $interval->format('%a');

                                    if($duration==0)
                                    {
                                        $duration=1;
                                    }

                                    foreach($schpercentage as $key=>$schval)
                                    {
                                       $schval1=explode("~",$schval);
                                        if($schval1[0]==$scheduleid)
                                        {
                                            if($schval1[2]==0)
                                            {
                                                $studentoverall=0;
                                            }
                                            else
                                            {
                                                $studentoverall=$schval1[1]/$schval1[2];
                                            }
                                        }
                                    }
                                    
                                        if($studentoverall=='')
                                        {
                                            $studentoverall=0;
                                        }
                                    ?>
                                    {id: '<?php echo $scheduleid."@".$classid;?>', text:"<?php echo $schedulename." / ".'Schedule';?>", start_date:"<?php echo $sch_stdate;?>", duration:<?php echo $duration; ?>, order:10,
                                    progress:<?php echo $studentoverall; ?>, parent:<?php echo $classid; ?>}, 

                                    <?php
                                    $qrystddetails=$ObjDB->QueryObject("SELECT a.fld_id AS stdid, CONCAT(a.fld_lname,' ', a.fld_fname) AS stdname FROM itc_user_master AS a
                                                                              LEFT JOIN itc_class_student_mapping AS b ON a.fld_id=b.fld_student_id 
                                                                              LEFT JOIN itc_class_exp_student_mapping AS c ON c.fld_student_id=a.fld_id
                                                                              WHERE b.fld_class_id='".$classid."' AND a.fld_delstatus='0' AND b.fld_flag='1' AND c.fld_flag='1' AND c.fld_schedule_id='".$scheduleid."' order by a.fld_lname ASC");
                                    if($qrystddetails->num_rows>0)
                                    {
                                        while($rowstddetails = $qrystddetails->fetch_assoc())
                                        {
                                            extract($rowstddetails);
                                                  $datetime1 = new DateTime($sch_stdate);
                                                  $datetime2 = new DateTime($sch_enddate);
                                                  $interval = $datetime1->diff($datetime2);
                                                  $duration = $interval->format('%a');
                                                  if($duration==0)
                                                  {
                                                       $duration=1; 
                                                  }
                                                  foreach($stdpercentage as $key=>$stdval)
                                                  {
                                                      $stdval1=explode("~",$stdval);
                                                      if($stdval1[0]==$stdid && $stdval1[1]==$scheduleid)
                                                      {
                                                          $stdindvidualprogress=$stdval1[3]/100;
                                                      }
                                                  }
                                                  
                                                    if($stdindvidualprogress=='')
                                                    {
                                                        $stdindvidualprogress=0;
                                                    }
                                            ?>
                                            {id: '<?php echo $scheduleid."~".$stdid."~".$classid."~4";?>', text:"<?php echo $stdname." / ".'Student';?>", start_date:"<?php echo $sch_stdate;?>", duration:<?php echo $duration;?>, order:10,
                                            progress:<?php echo $stdindvidualprogress; ?>,parent:'<?php echo $scheduleid."@".$classid; ?>'},
                                            <?php
                                        } // student while end
                                    }
                                }//schedule while end
                            } //schedule If end
                            //Expedition Code End Here
                            
                            /*****Mission Code Start Here Developed By Mohan M 26-11-2015*******/
                            $qryscheduledetailsmis=$ObjDB->QueryObject("SELECT a.fld_schedule_name AS schedulename,a.fld_mis_id AS misid,b.fld_schedule_id AS scheduleid,
                                                                            DATE_FORMAT(a.fld_startdate, '%d-%m-%Y') AS sch_stdatemis,DATE_FORMAT(a.fld_enddate, '%d-%m-%Y') AS sch_enddatemis    
                                                                        FROM itc_class_indasmission_master AS a 
                                                                        LEFT JOIN  itc_class_mission_student_mapping AS b ON a.fld_id=b.fld_schedule_id
                                                                        WHERE a.fld_class_id='".$classid."' AND b.fld_flag='1' AND a.fld_delstatus='0' GROUP BY b.fld_schedule_id");

                            if($qryscheduledetailsmis->num_rows>0)
                            {
                                $studentoverallmis='';
                                while($rowscheduledetailsmis = $qryscheduledetailsmis->fetch_assoc())
                                {
                                    extract($rowscheduledetailsmis);
                                    $sch_enddate = date('d-m-Y', strtotime("+1 day", strtotime($sch_enddatemis)));
                                    $datetime1mis = new DateTime($sch_stdatemis);
                                    $datetime2mis = new DateTime($sch_enddatemis);
                                    $intervalmis = $datetime1mis->diff($datetime2mis);
                                    $durationmis = $intervalmis->format('%a');

                                    if($durationmis==0)
                                    {
                                        $durationmis=1;
                                    }
                                    
                                    foreach($schpercentagemis as $key=>$schvalmis)
                                    {
                                       $schvalmis1=explode("~",$schvalmis);
                                        if($schvalmis1[0]==$scheduleid)
                                        {
                                            if($schvalmis1[2]==0)
                                            {
                                                $studentoverallmis=0;
                                            }
                                            else
                                            {
                                                $studentoverallmis=$schvalmis1[1]/$schvalmis1[2];
                                            }
                                        }
                                    }
                                    ?>
                                                
                                    {id: '<?php echo $scheduleid."@".$classid;?>', text:"<?php echo $schedulename." / ".'Schedule';?>", start_date:"<?php echo $sch_stdatemis;?>", duration:<?php echo $durationmis; ?>, order:10,
                                    progress:<?php echo $studentoverallmis; ?>, parent:<?php echo $classid; ?>}, 

                                    <?php
                                    $qrystddetails=$ObjDB->QueryObject("SELECT a.fld_id AS stdid, CONCAT(a.fld_lname,' ', a.fld_fname) AS stdname FROM itc_user_master AS a
                                                                              LEFT JOIN itc_class_student_mapping AS b ON a.fld_id=b.fld_student_id 
                                                                              LEFT JOIN itc_class_mission_student_mapping AS c ON c.fld_student_id=a.fld_id
                                                                              WHERE b.fld_class_id='".$classid."' AND a.fld_delstatus='0' AND b.fld_flag='1' AND c.fld_flag='1' AND c.fld_schedule_id='".$scheduleid."' order by a.fld_lname ASC");
                                    if($qrystddetails->num_rows>0)
                                    {
                                        while($rowstddetails = $qrystddetails->fetch_assoc())
                                        {
                                            extract($rowstddetails);
                                            
                                            foreach($stdpercentagemis as $key=>$stdvalmis)
                                            {
                                                $stdvalmis1=explode("~",$stdvalmis);
                                                if($stdvalmis1[0]==$stdid && $stdvalmis1[1]==$scheduleid)
                                                {
                                                    $stdindvidualprogressmis=$stdvalmis1[3]/100;
                                                }
                                            }
                                            ?>
                                            {id: '<?php echo $scheduleid."~".$stdid."~".$classid."~18";?>', text:"<?php echo $stdname." / ".'Student';?>", start_date:"<?php echo $sch_stdatemis;?>", duration:<?php echo $durationmis;?>, order:10,
                                            progress:<?php echo $stdindvidualprogressmis; ?>,parent:'<?php echo $scheduleid."@".$classid; ?>'},
                                            <?php
                                        } // student while end
                                    } // student If end
                                }//Mission Schedule while Loop End
                            }//Mission Schedule If Condition End

                            
                            
                            
                            /*****Mission Code End Here Developed By Mohan M 26-11-2015*******/
                                    
                           /*****************Rotational mission schedule developed by naren 5-25-2016*******************/
                                        $qryschedulerotmission1=$ObjDB->QueryObject("SELECT a.fld_schedule_name AS schedulename,a.fld_numberofrotations as totrot,a.fld_id AS scheduleid,DATE_FORMAT(a.fld_startdate, '%d-%m-%Y') AS startdaterotmission, DATE_FORMAT(a.fld_enddate, '%d-%m-%Y') AS enddaterotmission
                                            FROM itc_class_rotation_mission_mastertemp AS a LEFT JOIN itc_class_rotation_mission_student_mappingtemp AS b 
                                                    ON b.fld_schedule_id=a.fld_id
                                            WHERE a.fld_class_id='".$classid."' AND a.fld_delstatus='0' AND a.fld_flag='1' AND b.fld_flag='1' 
                                            GROUP BY scheduleid");
                                        
                                            if($qryschedulerotmission1->num_rows>0)
                                           {
                                               $studentoverallrotmission='';
                                               while($rowqryschedulerotmission1 = $qryschedulerotmission1->fetch_assoc())
                                               {
                                                   extract($rowqryschedulerotmission1);
                                                   
                                                    $enddaterotmission = date('d-m-Y', strtotime("+1 day", strtotime($enddaterotmission)));
                                                    $datetimerotmission1 = new DateTime($startdaterotmission);
                                                    $datetimerotmission2 = new DateTime($enddaterotmission);
                                                    $intervalrotmission = $datetimerotmission1->diff($datetimerotmission2);
                                                    $durationrotmission = $intervalrotmission->format('%a');

                                                    if($durationrotmission==0)
                                                    {
                                                        $durationrotmission=1;
                                                    }
                                                    
                                                    foreach($schedulepercentagerotmission as $key=>$schvalrotmission)
                                                    {
                                                        $schvalrotmission1=explode("~",$schvalrotmission);
                                                        if($schvalrotmission1[0]==$scheduleid)
                                                        {
                                                            $schpercentage=$schvalrotmission1[1];
                                                        }
                                                    }
                                                   
                                               ?>
                                                   {id: '<?php echo $scheduleid."@".$classid;?>', text:"<?php echo $schedulename." / ".'Schedule';?>", start_date:"<?php echo $startdaterotmission;?>", duration:<?php echo $durationrotmission; ?>, order:10,
                                                    progress:<?php echo $schpercentage; ?>, parent:<?php echo $classid; ?>}, 

                                               <?php

                                                   $totrots=$totrot+1;
                                                   for($rot=2;$rot<=$totrots;$rot++)
                                                   {
                                                       $rotdet=$ObjDB->QueryObject("SELECT DATE_FORMAT(fld_startdate, '%d-%m-%Y') AS startdaterot, DATE_FORMAT(fld_enddate, '%d-%m-%Y') AS enddaterot FROM itc_class_rotation_missionscheduledate where fld_schedule_id='".$scheduleid."' and fld_rotation='".$rot."' and fld_flag='1'");
                                                       
                                                       if($rotdet->num_rows>0)
                                                       {
                                                           $rowrotdet=$rotdet->fetch_assoc();
                                                           extract($rowrotdet);
                                                       }
                                                       
                                                    $enddaterot = date('d-m-Y', strtotime("+1 day", strtotime($enddaterot)));
                                                    $datetimerot1 = new DateTime($startdaterot);
                                                    $datetimerot2 = new DateTime($enddaterot);
                                                    $intervalrot = $datetimerot1->diff($datetimerot2);
                                                    $durationrot = $intervalrot->format('%a');

                                                    if($durationrot==0)
                                                    {
                                                        $durationrot=1;
                                                    }
                                                    
                                                    foreach($missionrotationpercentagearray as $key=>$schvalrot)
                                                    {
                                                        $schvalrot1=explode("~",$schvalrot);
                                                        if($schvalrot1[0]==$rot AND $schvalrot1[1]==$scheduleid)
                                                        {
                                                            $rotpercentage=$schvalrot1[2];
                                                        }
                                                    }
                                                    
                                                    $rotation=$rot-1;
                                                   
                                                  ?>
                                                      {id: '<?php echo $rot."@".$scheduleid;?>', text:"<?php echo "Rotation ".$rotation." / ".'Rotation';?>", start_date:"<?php echo $startdaterot;?>", duration:<?php echo $durationrot; ?>, order:10,
                                                           progress:<?php echo $rotpercentage; ?>, parent:'<?php echo $scheduleid."@".$classid; ?>'}, 

                                                  <?php
                                                       
                                                              
                                                       
                                                     
                                                           $qrystddetailsrotmis1=$ObjDB->QueryObject("SELECT a.fld_id AS stdid, CONCAT(a.fld_lname,' ', a.fld_fname) AS stdname FROM itc_user_master AS a
                                                                              LEFT JOIN itc_class_student_mapping AS b ON a.fld_id=b.fld_student_id 
                                                                              LEFT JOIN itc_class_rotation_mission_student_mappingtemp AS c ON c.fld_student_id=a.fld_id
                                                                              WHERE b.fld_class_id='".$classid."' AND a.fld_delstatus='0' AND b.fld_flag='1' AND c.fld_flag='1' AND c.fld_schedule_id='".$scheduleid."' order by a.fld_lname ASC");

                                                           
                                                           if($qrystddetailsrotmis1->num_rows>0)
                                                           {
                                                               
                                                               while($rowqrystddetailsrotmis1 = $qrystddetailsrotmis1->fetch_assoc())
                                                               {
                                                                       extract($rowqrystddetailsrotmis1);

                                                                       foreach($stdpercentagerotmission as $key=>$schvalrot)
                                                                       {
                                                                            $schvalrot1=explode("~",$schvalrot);
                                                                            if($schvalrot1[0]==$rot AND $schvalrot1[1]==$stdid AND $schvalrot1[2]==$scheduleid)
                                                                            {
                                                                                $stdpercentagemisrot=$schvalrot1[3];
                                                                                $missionid=$schvalrot1[4];
                                                                            }
                                                                       }
                                                                       
                                                                       ?>
                                                                            {id: '<?php echo $scheduleid."~".$stdid."~".$classid."~23~".$missionid."~".$rot;?>', text:"<?php echo $stdname." / ".'Student';?>", start_date:"<?php echo $startdaterot;?>", duration:<?php echo $durationrot;?>, order:10,
                                                                            progress:<?php echo $stdpercentagemisrot; ?>,parent:'<?php echo $rot."@".$scheduleid; ?>'},
                                                                     <?php

                                                               } // student while loop end
                                                            } // student if loop end
                                                       } // rotation for loop end 
                                                } //schedule while loop end
                                           } // schedule end
                            /***************Rotational mission schedule end here *************************/       
                           
                        }//class while end
                    }
                ?>
            ],
            links:[
                { id:1, source:1, target:2, type:"1"},
                { id:2, source:2, target:3, type:"0"},
                { id:3, source:3, target:4, type:"0"},
                { id:4, source:2, target:5, type:"2"},
            ]
        };
		gantt.init("gantt_here");
		gantt.parse(tasks);
	</script>
</body>