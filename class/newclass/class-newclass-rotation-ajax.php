<?php 
	@include("sessioncheck.php");
	
	$date = date("Y-m-d H:i:s");
	$oper = isset($method['oper']) ? $method['oper'] : '';
        
        
       function super_unique($array)
        {
                $result = array_map("unserialize", array_unique(array_map("serialize", $array)));

                foreach ($result as $key => $value)
                {
                        if ( is_array($value) )
                        {
                                $result[$key] = super_unique($value);
                        }
                }

                return array_values($result);
        }
        
        function getIndex($name, $array, $newkey)
        {
           foreach($array as $key => $value)
           {
               if(is_array($value) && $value[$newkey] == $name)
               return $key;
           }
               return null;
        }

        // if student exist from same modules this function return false [duplicate copy of modules]
        function checkdupmodval($arr1,$val,$out,$numrot)
        {
            $flagfirst="true";
            $flagsecond="true";

              for($i=1;$i<=$numrot;$i++)
              {
                  $array1='';

                     $array1=$arr1.",".$i;

                      if($out[$array1][0]==$val OR $out[$array1][1]==$val) // first array 1,2 get 1
                      {
                          $flagfirst="false";
                          break;
                      }
              }



               if($flagfirst=="false")
               {
                   return "false";
               }
               else 
               {
                   return "true";
               } 
        }

        // return key from array $dupmodper is an array, $val is an array values
        function getkey($dupmodper,$val)
        {
            foreach($dupmodper as $key => $product)
            {
              foreach($product as $keyd => $pro)
              {
                  if($pro==$val)
                  {
                    return $keyd;
                  }
              }
            }
        }

        // if student exist from rotation(column) or modules(row) this function return false 
        function checkarray($arr1,$arr2,$val,$out,$nummod,$startrot,$endrot)
        {
            $flagfirst="true";
            $flagsecond="true";

              for($i=$startrot;$i<=$endrot;$i++)
              {
                  $array1='';
                  $array2='';
                  if($i!=$arr2) // second array value
                  {
                     $array1=$arr1.",".$i;

                      if($out[$array1][0]==$val OR $out[$array1][1]==$val) // first array 1,2 get 1
                      {

                          $flagfirst="false";
                          break;
                      }
                  }

               }

                for($i=1;$i<=$nummod;$i++)
                {
                  $array1='';
                  $array2='';

                  $array2=$i.",".$arr2;
                      if($out[$array2][0]==$val OR $out[$array2][1]==$val) // first array 1,2 get 1
                      {
                          $flagsecond="false";
                          break;
                      }
                 }

               if($flagfirst=="false" OR $flagsecond=="false")
               {
                   return "false";
               }
               else 
               {
                   return "true";
               }
        }

        // if pair exist from table function return false
        function checkduppair($value,$sval,$out)
        {
            $topval=$out[$value][0];
            $bottomval=$sval;
            $duppairflag="true";

            foreach($out as $key => $val)
            {
                if(($val[0]==$topval and $val[1]==$bottomval) OR ($val[0]==$bottomval and $val[1]==$topval))
                {
                    $duppairflag="false";
                    break;
                }
            }

            if($duppairflag=="true")
            {
                return "true";
            }
            else
            {
                return "false";
            }
        }

        // move array values
        function moveValueByIndex( array $array, $from=null, $to=null )
        {
               if ( null === $from )
               {
                 $from = count( $array ) - 1;
               }

               if ( !isset( $array[$from] ) )
               {
                 throw new Exception( "Offset $from does not exist" );
               }

               if ( array_keys( $array ) != range( 0, count( $array ) - 1 ) )
               {
                 throw new Exception( "Invalid array keys" );
               }

               $value = $array[$from];
               unset( $array[$from] );

               if ( null === $to )
               {
                 array_push( $array, $value );
               } 
               else 
               {
                 $tail = array_splice( $array, $to );
                 array_push( $array, $value );
                 $array = array_merge( $array, $tail );
               }

               return $array;
         }
             
        if($oper=="generatedispersed" and $oper!='')
        {
            
            
              
              $startrot = isset($method['startrot']) ? $method['startrot'] : '0';
              $endrot = isset($method['endrot']) ? $method['endrot'] : '0';
              $module = isset($method['module']) ? $method['module'] : '0';
              $student = isset($method['student']) ? $method['student'] : '0';
              $moddupid = isset($method['moddupid']) ? $method['moddupid'] : '0';
              $scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '0';
              
                $to = 'pitsco@nanonino.in';

                $subject = 'Rotational Schedule';

                $headers = "From: pitsco@nanonino.in \r\n";
                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

                $message = '<html><body>';
                $message .= '<table rules="all" style="border-color: #666;border:2px solid;" cellpadding="10">';
                $message .= "<tr><td><strong>Modules :</strong> </td><td>".$module."</td></tr>";
                $message .= "<tr><td><strong>Rotations :</strong> </td><td>".$endrot."</td></tr>";
                $message .= "<tr><td><strong>Students :</strong> </td><td>".$student."</td></tr>";
                $message .= "<tr><td><strong>oper:</strong> </td><td> Dispersed</td></tr>";
                $message .= "<tr><td><strong>Hostname:</strong> </td><td>".$_SERVER['SERVER_NAME']."</td></tr>";
                $message .= "<tr><td><strong>Userid:</strong> </td><td>".$uid."</td></tr>";
                $message .= "<tr><td><strong>Scheduleid:</strong> </td><td>".$scheduleid."</td></tr>";
                $message .= "<tr><td><strong>DateTime:</strong> </td><td>".date("Y-m-d H:i:s")."</td></tr>";
                $message .= "</table>";
                $message .= "</body></html>";

                mail($to, $subject, $message, $headers);
           
                
                $html = file_get_contents(REPORT_SERVER_URL.'rotationalschedule/packed_dispersed.php?startrot='.$startrot.'&oper=generatedispersed&module='.$module.'&student='.$student.'&moddupid='.$moddupid.'&endrot='.$endrot.'');

                echo $html;
			
	}
		
             
	/* Genearte the schedule */
	if($oper=="generateschedule" and $oper != " " )
	{
		
		$numrotation = isset($method['rotation']) ? $method['rotation'] : '0';
		$nummodule = isset($method['module']) ? $method['module'] : '0';
		$numstudent = isset($method['student']) ? $method['student'] : '0';
		$moddupid = isset($method['moddupid']) ? $method['moddupid'] : '0';
                $scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '0';
                
                $to = 'pitsco@nanonino.in';

                $subject = 'Rotational Schedule';

                $headers = "From: pitsco@nanonino.in \r\n";
                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

                $message = '<html><body>';
                $message .= '<table rules="all" style="border-color: #666;border:2px solid;" cellpadding="10">';
                $message .= "<tr><td><strong>Modules :</strong> </td><td>".$nummodule."</td></tr>";
                $message .= "<tr><td><strong>Rotations :</strong> </td><td>".$numrotation."</td></tr>";
                $message .= "<tr><td><strong>Students :</strong> </td><td>".$numstudent."</td></tr>";
                $message .= "<tr><td><strong>oper:</strong> </td><td> Packed </td></tr>";
                $message .= "<tr><td><strong>Hostname:</strong> </td><td>".$_SERVER['SERVER_NAME']."</td></tr>";
                $message .= "<tr><td><strong>Userid:</strong> </td><td>".$uid."</td></tr>";
                $message .= "<tr><td><strong>Scheduleid:</strong> </td><td>".$scheduleid."</td></tr>";
                $message .= "<tr><td><strong>DateTime:</strong> </td><td>".date("Y-m-d H:i:s")."</td></tr>";
                $message .= "</table>";
                $message .= "</body></html>";

                mail($to, $subject, $message, $headers);
		
		
                $html = file_get_contents(REPORT_SERVER_URL.'rotationalschedule/packed_dispersed.php?rotation='.$numrotation.'&oper=generateschedule&module='.$nummodule.'&student='.$numstudent.'&moddupid='.$moddupid.'');

                echo $html;
			
		
	}
			
		
	/* check the studentname if already assigned to any class */
	if($oper == "checkstudentmod" and $oper != '')
	{
		$celldet = (isset($method['celldet'])) ? $method['celldet'] : 0;
		$sid = (isset($method['scheduleid'])) ? $method['scheduleid'] : 0;
                $operation = (isset($method['operation'])) ? $method['operation'] : 0;
                $moduletype = (isset($method['moduletype'])) ? $method['moduletype'] : 0;
		$celldet=explode(",",$celldet);
		$studentname="";
		$nam=array();
		
		if($operation=="generate")
                {
			for($i=0;$i<sizeof($celldet);$i++)
			{
				$getcelldet=explode("~",$celldet[$i]);
				$getmoduledet=explode("-",$getcelldet[0]);
				
				if($getcelldet[1]!="undefined")
				{
					$count=0;
					
					if($getmoduledet[1]==1 OR $getmoduledet[1]==2)
                                        {
					
                                        $qryanstrack=$ObjDB->QueryObject("SELECT fld_schedule_id as sid,fld_schedule_type as stype from itc_module_answer_track where fld_module_id='".$getmoduledet[0]."' and fld_tester_id='".$getcelldet[1]."' and fld_delstatus='0' group by fld_module_id,fld_tester_id");
                                        
                                        $qrypointsmaster=$ObjDB->QueryObject("SELECT fld_schedule_id as sid,fld_schedule_type as stype from itc_module_points_master where fld_module_id='".$getmoduledet[0]."' and fld_student_id='".$getcelldet[1]."' and fld_delstatus='0' group by fld_module_id,fld_student_id");
                                        
                                        if($qryanstrack->num_rows>0)
                                        {
                                            $row=$qryanstrack->fetch_assoc();
                                            extract($row);
                                            
                                            if($stype==5 or $stype==6 or $stype==7)
                                            {
                                                $tablename="itc_class_indassesment_master as a";
                                            }
                                            else if($stype==1 or $stype==4)
                                            {
                                                $tablename="itc_class_rotation_schedule_mastertemp as a";
                                            }
                                            else if($stype==2)
                                            {
                                                $tablename="itc_class_dyad_schedulemaster as a";
                                            }
                                            else if($stype==3)
                                            {
                                                $tablename="itc_class_triad_schedulemaster as a";
                                            }
                                            else if($stype==21)
                                            {
                                                $tablename="itc_class_rotation_modexpschedule_mastertemp as a";
                                            }
                                            
                                            if($stype!=0 AND $sid!=0)
                                            {
                                            $count=$ObjDB->SelectSingleValueInt("select a.fld_id from ".$tablename." left join itc_class_master as b on b.fld_id=a.fld_class_id where a.fld_id='".$sid."' AND a.fld_moduletype='".$moduletype."' and a.fld_delstatus='0' and b.fld_delstatus='0'");
                                            
                                            }
                                        }
                                        else if($qrypointsmaster->num_rows>0)
                                        {
                                            $rowpoints=$qrypointsmaster->fetch_assoc();
                                            extract($rowpoints);
                                        
                                            if($stype==5 or $stype==6 or $stype==7)
                                            {
                                                $tablename="itc_class_indassesment_master as a";
                                            }
                                            else if($stype==1 or $stype==4)
                                            {
                                                $tablename="itc_class_rotation_schedule_mastertemp as a";
                                            }
                                            else if($stype==2)
                                            {
                                                $tablename="itc_class_dyad_schedulemaster as a";
                                            }
                                            else if($stype==3)
                                            {
                                                $tablename="itc_class_triad_schedulemaster as a";
                                            }
                                            else if($stype==21)
                                            {
                                                $tablename="itc_class_rotation_modexpschedule_mastertemp as a";
                                            }
                                            
                                            if($stype!=0 AND $sid!=0)
                                            {
                                            $count=$ObjDB->SelectSingleValueInt("select a.fld_id from ".$tablename." left join itc_class_master as b on b.fld_id=a.fld_class_id where a.fld_id='".$sid."' AND a.fld_moduletype='".$moduletype."' and a.fld_delstatus='0' and b.fld_delstatus='0'");
                                            }
                                        }
                                        
					if($count>0)
					{
						$name=$ObjDB->SelectSingleValue("SELECT CONCAT(fld_fname,'',fld_lname) FROM itc_user_master WHERE fld_id='".$getcelldet[1]."' AND fld_delstatus='0'");
						
						
						$nam[]=$name;
						
                                                break;
						
					}
                                        }
				
                                        
                                        if($getmoduledet[1]==8)
                                        {
                                            $qrypointsmaster=$ObjDB->QueryObject("SELECT fld_schedule_id as sid,fld_schedule_type as stype from itc_module_points_master where fld_module_id='".$getmoduledet[0]."' and fld_student_id='".$getcelldet[1]."' and fld_schedule_type='22' OR fld_schedule_type='8' and fld_delstatus='0' group by fld_module_id,fld_student_id");

                                            if($qrypointsmaster->num_rows>0)
                                            {
                                                $row=$qrypointsmaster->fetch_assoc();
                                                extract($row);
                                                
                                                if($stype!=0 AND $sid!=0)
                                                {
                                                    if($stype==22)
                                                    {
                                                        $count=$ObjDB->SelectSingleValueInt("SELECT a.fld_id FROM itc_class_rotation_modexpschedule_mastertemp as a left join itc_class_master as b on b.fld_id=a.fld_class_id WHERE a.fld_id='".$sid."' and a.fld_delstatus='0'  and b.fld_delstatus='0'");
				}
                                                    else if($stype==8)
                                                    {
                                                        $count=$ObjDB->SelectSingleValueInt("SELECT a.fld_id FROM itc_class_rotation_schedule_mastertemp as a left join itc_class_master as b on b.fld_id=a.fld_class_id WHERE a.fld_id='".$sid."' and a.fld_delstatus='0' and b.fld_delstatus='0'");
                                                    }
                                                }
			
                                                if($count>0)
                                                {
                                                    $name=$ObjDB->SelectSingleValue("SELECT CONCAT(fld_fname,'',fld_lname) FROM itc_user_master WHERE fld_id='".$getcelldet[1]."' AND fld_delstatus='0'");
                                                    $nam[]=$name;
                                                    break;
			}

                                            }
                                        }

				}

			}



			if(sizeof($nam)>0)
			{
				echo "Synergy ITC is unable to generate a complete schedule with the options currently selected. This is likely due to lack of available content. You can try reducing the number of rotations, turning off Auto Block or clicking the Show Details button for additional information.";
			}
                }
                else
                {
                    ?>
                    <script language="javascript">
                        $('#tableshowdetails').slimscroll({
                                height:'auto',
                                railVisible: false,
                                allowPageScroll: false,
                                railColor: '#F4F4F4',
                                opacity: 9,
                                color: '#88ABC2',
                                 wheelStep: 1
                        });
                    </script>
                    <table class='table table-hover table-striped table-bordered setbordertopradius'  id="mytable" >
                    <thead class='tableHeadText' >
                        <tr>
                            <th width="50%" class='centerText'>Student Name</th>
                            <th width="50%" class='centerText'>Blocked Module</th>

                        </tr>
                    </thead>
                    </table>
                    <div style="max-height:400px;width:100%;" id="tableshowdetails" >
                    <table style="margin-bottom:0px;color:#47708a;" class='table table-hover table-striped table-bordered bordertopradiusremove' id="mytable">
                        <tbody>
                            <?php

			for($i=0;$i<sizeof($celldet);$i++)
			{
				$getcelldet=explode("~",$celldet[$i]);
				$getmoduledet=explode("-",$getcelldet[0]);
				
				if($getcelldet[1]!="undefined")
				{
					$count=0;
                                        if($getmoduledet[1]==1 OR $getmoduledet[1]==2)
                                        {
                                        
                                        $qryanstrack=$ObjDB->QueryObject("SELECT fld_schedule_id as sid,fld_schedule_type as stype from itc_module_answer_track where fld_module_id='".$getmoduledet[0]."' and fld_tester_id='".$getcelldet[1]."' and fld_delstatus='0' group by fld_module_id,fld_tester_id");
                                        
                                        $qrypointsmaster=$ObjDB->QueryObject("SELECT fld_schedule_id as sid,fld_schedule_type as stype from itc_module_points_master where fld_module_id='".$getmoduledet[0]."' and fld_student_id='".$getcelldet[1]."' and fld_delstatus='0' group by fld_module_id,fld_student_id");
                                        
                                        if($qryanstrack->num_rows>0)
                                        {
                                            $row=$qryanstrack->fetch_assoc();
                                            extract($row);
                                            
                                            if($stype==5 or $stype==6 or $stype==7)
                                            {
                                                $tablename="itc_class_indassesment_master as a";
				}
                                            else if($stype==1 or $stype==4)
				{
                                                $tablename="itc_class_rotation_schedule_mastertemp as a";
				}
                                            else if($stype==2)
                                            {
                                                $tablename="itc_class_dyad_schedulemaster as a";
			}
                                            else if($stype==3)
                                            {
                                                $tablename="itc_class_triad_schedulemaster as a";
                                            }
                                            else if($stype==21)
                                            {
                                                $tablename="itc_class_rotation_modexpschedule_mastertemp as a";
                                            }
                                            
                                            if($stype!=0 AND $sid!=0)
                                            {
                                            $count=$ObjDB->SelectSingleValueInt("SELECT a.fld_id FROM ".$tablename." left join itc_class_master as b on b.fld_id=a.fld_class_id WHERE a.fld_id='".$sid."' AND a.fld_moduletype='".$moduletype."' and a.fld_delstatus='0' and b.fld_delstatus='0'");
                                            }
                                        }
                                        else if($qrypointsmaster->num_rows>0)
                                        {
                                            $rowpoints=$qrypointsmaster->fetch_assoc();
                                            extract($rowpoints);
                                        
                                             if($stype==5 or $stype==6 or $stype==7)
                                            {
                                                $tablename="itc_class_indassesment_master as a";
                                            }
                                            else if($stype==1 or $stype==4)
                                            {
                                                $tablename="itc_class_rotation_schedule_mastertemp as a";
                                            }
                                            else if($stype==2)
                                            {
                                                $tablename="itc_class_dyad_schedulemaster as a";
                                            }
                                            else if($stype==3)
                                            {
                                                $tablename="itc_class_triad_schedulemaster as a";
                                            }
                                            else if($stype==21)
                                            {
                                                $tablename="itc_class_rotation_modexpschedule_mastertemp as a";
                                            }
                                            
                                            if($stype!=0 AND $sid!=0)
                                            {
                                            $count=$ObjDB->SelectSingleValueInt("SELECT a.fld_id FROM ".$tablename." left join itc_class_master as b on b.fld_id=a.fld_class_id WHERE a.fld_id='".$sid."' AND a.fld_moduletype='".$moduletype."' and a.fld_delstatus='0' and b.fld_delstatus='0'");
                                            }
                                        }
                                        
					if($count>0)
			{
						$name=$ObjDB->SelectSingleValue("SELECT CONCAT(fld_fname,'',fld_lname) FROM itc_user_master WHERE fld_id='".$getcelldet[1]."' AND fld_delstatus='0'");
                                                
                                                if($moduletype==1)
                                                {
                                                   $modulename=$ObjDB->SelectSingleValue("SELECT CONCAT(a.fld_module_name,' ',b.fld_version)
                                                                                  FROM itc_module_master AS a 
										  LEFT JOIN itc_module_version_track AS b ON b.fld_mod_id='".$getmoduledet[0]."'    
										  WHERE a.fld_id='".$getmoduledet[0]."' AND b.fld_delstatus='0' AND a.fld_delstatus='0'");
			}
                                                else
                                                {
                                                    $modulename=$ObjDB->SelectSingleValue("SELECT CONCAT(a.fld_mathmodule_name,' ',b.fld_version)
                                                                                        FROM itc_mathmodule_master AS a 
											LEFT JOIN itc_module_version_track AS b ON b.fld_mod_id=a.fld_module_id
											WHERE a.fld_id='".$getmoduledet[0]."' AND b.fld_delstatus='0' AND a.fld_delstatus='0'");
                                                }
                                          ?>
                                                 <tr>									
                                                    <td width="50%" class='centerText'><?php echo $name; ?></td>
                                                    <td width="50%" class='centerText'><?php echo $modulename; ?></td>
                                                 </tr>
                                          <?php
                                        }
		
                                    }
						
                                    if($getmoduledet[1]==8)
                                    {
                                        $qrypointsmaster=$ObjDB->QueryObject("SELECT fld_schedule_id as sid,fld_schedule_type as stype from itc_module_points_master where fld_module_id='".$getmoduledet[0]."' and fld_student_id='".$getcelldet[1]."' and fld_schedule_type='22' OR fld_schedule_type='8' and fld_delstatus='0' group by fld_module_id,fld_student_id");
						
                                        if($qrypointsmaster->num_rows>0)
                                        {
                                            $row=$qrypointsmaster->fetch_assoc();
                                            extract($row);
						
                                            if($stype!=0 AND $sid!=0)
                                            {
                                                   if($stype==22)
                                                    {
                                                        $count=$ObjDB->SelectSingleValueInt("SELECT a.fld_id FROM itc_class_rotation_modexpschedule_mastertemp as a left join itc_class_master as b on b.fld_id=a.fld_class_id WHERE a.fld_id='".$sid."' and a.fld_delstatus='0' and b.fld_delstatus='0'");
    }
                                                    else if($stype==8)
                                                    {
                                                        $count=$ObjDB->SelectSingleValueInt("SELECT a.fld_id FROM itc_class_rotation_schedule_mastertemp as a left join itc_class_master as b on b.fld_id=a.fld_class_id WHERE a.fld_id='".$sid."' and a.fld_delstatus='0' and b.fld_delstatus='0'");
                                                    }
                                            }

                                            if($count>0)
                                            {
                                                   $name=$ObjDB->SelectSingleValue("SELECT CONCAT(fld_fname,'',fld_lname) FROM itc_user_master WHERE fld_id='".$getcelldet[1]."' AND fld_delstatus='0'");
                                                   
                                                   $modulename=$ObjDB->SelectSingleValue("SELECT fld_contentname from itc_customcontent_master where fld_id='".$getmoduledet[0]."' and fld_delstatus='0'");


                                              ?>
                                                     <tr>									
                                                        <td width="50%" class='centerText'><?php echo $name; ?></td>
                                                        <td width="50%" class='centerText'><?php echo $modulename; ?></td>
                                                     </tr>
                                              <?php
				}
			
                                            

                        }
                                    }
                                
                                }
			
                        }
                            ?>
                            
                        </tbody>
                    </table>
                    </div>
                              
                 <?php   
                }
		
    }

	/*--- save rotational table cell details  ---*/
	if($oper == "saverotation" and $oper != '')
	{
		$classid = (isset($method['classid'])) ? $method['classid'] : 0;
		$scheduleid = (isset($method['scheduleid'])) ? $method['scheduleid'] : 0;
		$moduledet = (isset($method['moduledet'])) ? $method['moduledet'] : 0;	
		$numberofrotation = (isset($method['numberofrotation'])) ? $method['numberofrotation'] : 0;	
		$celldet = (isset($method['celldet'])) ? $method['celldet'] : 0;
		$autoblock = (isset($method['autoblock'])) ? $method['autoblock'] : 0;
		$moduletype = (isset($method['moduletype'])) ? $method['moduletype'] : 0;
		$testflag = (isset($method['testflag'])) ? $method['testflag'] : 0;
		$startdate = (isset($method['startdate'])) ? $method['startdate'] : 0;
                $generatetype = (isset($method['generatetype'])) ? $method['generatetype'] : 0;
                $blockmodule = isset($method['blockmodule']) ? $method['blockmodule'] : '0';
                $blockstudents = isset($method['blockstudents']) ? $method['blockstudents'] : '0';

		$rotlength = (isset($method['rotlength'])) ? $method['rotlength'] : 0;
		$rotlength=$rotlength-1;
		$count=0;
		$moduledet=explode(",",$moduledet);
		$celldet=explode(",",$celldet);
                $blockstudents=explode(",",$blockstudents);

		$schflag=$ObjDB->SelectSingleValueInt("SELECT fld_flag FROM itc_class_rotation_schedule_mastertemp WHERE fld_id='".$scheduleid."'");
		
		$ObjDB->NonQuery("UPDATE itc_class_rotation_moduledet SET fld_flag='0' WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."'");
		
		$j=2;
		for($i=0;$i<sizeof($moduledet);$i++)
		{
			if($moduledet[$i]!="undefined")
			{
				$getmoduledet=explode("-",$moduledet[$i]);
				
				$count=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) 
				            FROM itc_class_rotation_moduledet
						    WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_module_id='".$getmoduledet[0]."' 
							AND fld_type='".$getmoduledet[1]."' AND fld_row_id='".$j."'");
				
			if($count==0)
			{
				$ObjDB->NonQuery("INSERT INTO itc_class_rotation_moduledet(fld_class_id,fld_schedule_id,fld_module_id,fld_type,fld_numberofrotation,fld_row_id)
				                                     values('".$classid."','".$scheduleid."','".$getmoduledet[0]."','".$getmoduledet[1]."','".$numberofrotation."','".$j."')");
			}
			else
			{
				$ObjDB->NonQuery("UPDATE itc_class_rotation_moduledet SET fld_flag='1',fld_numberofrotation='".$numberofrotation."' 
		        WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_module_id='".$getmoduledet[0]."' AND fld_type='".$getmoduledet[1]."' and fld_row_id='".$j."'");
			}
		}
			
			$j++;
		}
		
                /* Block student mapping start */
		
			
			
                        if($blockstudents[0]>0)
                        {
                            $blockmod=explode('-',$blockmodule);
                
                            $blockmodule=$blockmod[0];
                            $modtype=$blockmod[1];
                            
                            $ObjDB->NonQuery("UPDATE itc_class_rotation_blockstudent SET fld_flag='0',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' WHERE fld_scheduleid='".$scheduleid."' AND fld_moduleid='".$blockmodule."' AND fld_moduletype='".$modtype."'");
                            
                            for($i=0;$i<sizeof($blockstudents);$i++)
                            {
                                
                                    $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_class_rotation_blockstudent WHERE fld_scheduleid='".$scheduleid."' AND  fld_moduleid='".$blockmodule."' AND fld_moduletype='".$modtype."'  AND fld_studentid='".$blockstudents[$i]."'");
                                    if($cnt==0)
                                    {

                                            $ObjDB->NonQuery("INSERT INTO itc_class_rotation_blockstudent(fld_classid,fld_scheduleid,fld_moduleid,fld_moduletype,fld_studentid,fld_flag,fld_createddate,fld_createdby) VALUES ('".$classid."','".$scheduleid."','".$blockmodule."','".$modtype."','".$blockstudents[$i]."','1','".date('Y-m-d H:i:s')."','".$uid."')");
                                    }
                                    else
                                    {
                                            $ObjDB->NonQuery("UPDATE itc_class_rotation_blockstudent SET fld_moduletype='".$modtype."',fld_flag='1',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' WHERE fld_scheduleid='".$scheduleid."' AND fld_studentid='".$blockstudents[$i]."' AND fld_id='".$cnt."'");
                                    }
                            }
                        }
                        
				
				
	       /* Block student mapping end */
		
		
		$ObjDB->NonQuery("UPDATE itc_class_rotation_schedulegriddet SET fld_flag='0',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."'");
		
		for($i=0;$i<sizeof($celldet);$i++)
		{
			$getcelldet=explode("~",$celldet[$i]);
			$getrowid=explode("_",$getcelldet[2]);
			$getmoduledet=explode("-",$getcelldet[0]);
			
			if($getcelldet[3]!="undefined")
			{
				$count=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_class_rotation_schedulegriddet WHERE fld_class_id='".$classid."' AND                 fld_schedule_id='".$scheduleid."' AND fld_module_id='".$getmoduledet[0]."' AND fld_type='".$getmoduledet[1]."' and fld_row_id='".$getrowid[1]."' AND fld_cell_id='".$getcelldet[2]."'");
			
			  if($count==0)
			  {
                                    $cnt=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_class_rotation_blockstudent WHERE fld_classid='".$classid."' AND fld_scheduleid='".$scheduleid."' AND fld_moduleid='".$getmoduledet[0]."' AND fld_studentid='".$getcelldet[3]."' ANd fld_flag='1'");
                                    
                                    if($cnt=='0')
                                    {
	                                $ObjDB->NonQuery("INSERT INTO  itc_class_rotation_schedulegriddet(fld_class_id,fld_schedule_id,fld_module_id,fld_type,fld_rotation,fld_cell_id,fld_student_id,fld_row_id,fld_createddate,fld_createdby)values('".$classid."','".$scheduleid."','".$getmoduledet[0]."','".$getmoduledet[1]."','".$getcelldet[1]."','".$getcelldet[2]."','".$getcelldet[3]."','".$getrowid[1]."','".date("Y-m-d H:i:s")."','".$uid."')");
			  }
			  }
			  else
			  {
                                   $cnt=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_class_rotation_blockstudent WHERE fld_classid='".$classid."' AND fld_scheduleid='".$scheduleid."' AND fld_moduleid='".$getmoduledet[0]."' AND fld_studentid='".$getcelldet[3]."' ANd fld_flag='1'");
                                    
                                    if($cnt=='0')
                                    {
					$ObjDB->NonQuery("UPDATE itc_class_rotation_schedulegriddet SET fld_flag='1',fld_student_id='".$getcelldet[3]."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_module_id='".$getmoduledet[0]."' AND fld_type='".$getmoduledet[1]."' AND fld_row_id='".$getrowid[1]."' AND fld_cell_id='".$getcelldet[2]."'");
			  }
			}
			}
			
		}
		
	if($schflag==0)
	{
		$sdate='';
		$edate='';
		for($i=2;$i<=$numberofrotation+1;$i++)
		{
			if($i==2)
			{
				$sdate=$i."~".$startdate;
				$enddate=date("Y-m-d",strtotime($startdate. "+".$rotlength." weekdays"));

				$ObjDB->NonQuery("INSERT INTO itc_class_rotation_scheduledate(fld_class_id,fld_schedule_id,fld_rotation,fld_startdate,fld_enddate,fld_createddate,fld_createdby)VALUES('".$classid."','".$scheduleid."','".$i."','".$startdate."','".$enddate."','".date("Y-m-d H:i:s")."','".$uid."')");
				
			}
			else
			{
				$startdate=date("Y-m-d",strtotime($enddate. "+1 weekdays"));
				$enddate=date("Y-m-d",strtotime($startdate. "+".$rotlength." weekdays"));

				$ObjDB->NonQuery("INSERT INTO itc_class_rotation_scheduledate(fld_class_id,fld_schedule_id,fld_rotation,fld_startdate,fld_enddate,fld_createddate,fld_createdby)VALUES('".$classid."','".$scheduleid."','".$i."','".$startdate."','".$enddate."','".date("Y-m-d H:i:s")."','".$uid."')");
				
			}
			
		}
		
		
		
		$rotenddate=$ObjDB->SelectSingleValue("SELECT fld_enddate FROM itc_class_rotation_scheduledate WHERE fld_schedule_id='".$scheduleid."' and fld_flag='1' AND fld_rotation IN(SELECT MAX(fld_rotation) FROM itc_class_rotation_scheduledate WHERE fld_schedule_id='".$scheduleid."' AND fld_flag='1') LIMIT 0,1 ");
		
		$ObjDB->NonQuery("UPDATE itc_class_rotation_schedule_mastertemp SET fld_enddate='".$rotenddate."',fld_gridupdatedby='".$uid."',fld_gridupdateddate='".date("Y-m-d H:i:s")."' WHERE fld_id='".$scheduleid."'");
	}
	else
	{
		$ObjDB->NonQuery("UPDATE itc_class_rotation_scheduledate SET fld_flag=0 WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."'");

		$sdate='';
		$edate='';
		for($i=2;$i<=$numberofrotation+1;$i++)
		{
			$rotcount=$ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_class_rotation_scheduledate WHERE fld_rotation='".$i."' AND fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."'");

			if($rotcount==1)
			{
				$ObjDB->NonQuery("UPDATE itc_class_rotation_scheduledate SET fld_flag=1,fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_rotation='".$i."' AND fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."'");
			}
			else
			{
				$rotation=$i-1;
				$rotenddate=$ObjDB->SelectSingleValue("SELECT fld_enddate FROM itc_class_rotation_scheduledate WHERE fld_rotation='".$rotation."' AND fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."'");

				$startdate=date("Y-m-d",strtotime($rotenddate. "+1 weekdays"));
				$enddate=date("Y-m-d",strtotime($startdate. "+".$rotlength." weekdays"));

				$ObjDB->NonQuery("INSERT INTO itc_class_rotation_scheduledate(fld_class_id,fld_schedule_id,fld_rotation,fld_startdate,fld_enddate,fld_createddate,fld_createdby)VALUES('".$classid."','".$scheduleid."','".$i."','".$startdate."','".$enddate."','".date("Y-m-d H:i:s")."','".$uid."')");


			}
			
		}

		$rotenddate=$ObjDB->SelectSingleValue("SELECT fld_enddate FROM itc_class_rotation_scheduledate WHERE fld_schedule_id='".$scheduleid."' and fld_flag='1' AND fld_rotation IN(SELECT MAX(fld_rotation) FROM itc_class_rotation_scheduledate WHERE fld_schedule_id='".$scheduleid."' AND fld_flag='1') LIMIT 0,1 ");
		
		$ObjDB->NonQuery("UPDATE itc_class_rotation_schedule_mastertemp SET fld_enddate='".$rotenddate."',fld_gridupdatedby='".$uid."',fld_gridupdateddate='".date("Y-m-d H:i:s")."' WHERE fld_id='".$scheduleid."'");
	}


		$ObjDB->NonQuery("UPDATE itc_class_rotation_schedule_mastertemp SET fld_step_id=1,fld_flag=1,fld_autoblock='".$autoblock."',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."',fld_generatetype='".$generatetype."' WHERE fld_id='".$scheduleid."'");
		
		
		if($moduletype==1 and $testflag==1)
		{
			
			$ObjDB->NonQuery("UPDATE itc_module_play_track SET fld_delstatus='1',fld_deleted_date='".date('Y-m-d H:i:s')."',fld_deleted_by='".$uid."' WHERE fld_schedule_id='".$scheduleid."' and fld_schedule_type='1'");
			
			$ObjDB->NonQuery("UPDATE itc_module_points_master SET fld_delstatus='1',fld_deleted_date='".date('Y-m-d H:i:s')."',fld_deleted_by='".$uid."' WHERE fld_schedule_id='".$scheduleid."' and fld_schedule_type='1'");
			
			$ObjDB->NonQuery("UPDATE itc_module_variable_track SET fld_delstatus='1',fld_deleted_date='".date('Y-m-d H:i:s')."',fld_deleted_by='".$uid."' WHERE fld_schedule_id='".$scheduleid."' and fld_schedule_type='1'");
			
			$ObjDB->NonQuery("UPDATE itc_module_answer_track SET fld_delstatus='1',fld_deleted_date='".date('Y-m-d H:i:s')."',fld_deleted_by='".$uid."' WHERE fld_schedule_id='".$scheduleid."' and fld_schedule_type='1'");
		}
		if($moduletype==2 and $testflag==1)
		{
			$ObjDB->NonQuery("UPDATE itc_module_play_track SET fld_delstatus='1',fld_deleted_date='".date('Y-m-d H:i:s')."',fld_deleted_by='".$uid."' WHERE fld_schedule_id='".$scheduleid."' and fld_schedule_type='1'");
			
			$ObjDB->NonQuery("UPDATE itc_module_points_master SET fld_delstatus='1',fld_deleted_date='".date('Y-m-d H:i:s')."',fld_deleted_by='".$uid."' WHERE fld_schedule_id='".$scheduleid."' and fld_schedule_type='1'");
			
			$ObjDB->NonQuery("UPDATE itc_module_variable_track SET fld_delstatus='1',fld_deleted_date='".date('Y-m-d H:i:s')."',fld_deleted_by='".$uid."' WHERE fld_schedule_id='".$scheduleid."' and fld_schedule_type='1'");
			
			$ObjDB->NonQuery("UPDATE itc_module_answer_track SET fld_delstatus='1',fld_deleted_date='".date('Y-m-d H:i:s')."',fld_deleted_by='".$uid."' WHERE fld_schedule_id='".$scheduleid."' and fld_schedule_type='1'");
			
			$qry=$ObjDB->NonQuery("Select fld_id FROM itc_assignment_sigmath_master WHERE fld_schedule_id='".$scheduleid."' and fld_test_type='2'");
			
			if($qry->num_rows>0)
			{
				while($row=$qry->fetch_assoc())
				{
					extract($row);
					
					$ObjDB->NonQuery("UPDATE itc_assignment_sigmath_answer_track SET fld_delstatus='1',fld_deleted_date='".date('Y-m-d H:i:s')."',fld_deleted_by='".$uid."' WHERE fld_track_id='".$fld_id."'");
				}
			}
			
			$ObjDB->NonQuery("UPDATE itc_assignment_sigmath_master SET fld_delstatus='1',fld_deleted_date='".date('Y-m-d H:i:s')."',fld_deleted_by='".$uid."' WHERE fld_schedule_id='".$scheduleid."' and fld_test_type='2'");
		}
		
	}
	
	/*--- Show modules  ---*/
	if($oper == "showmodule" and $oper != '')
	{	
		$scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '0';
		$moduleid = isset($method['moduleid']) ? $method['moduleid'] : '0';
	?>
		<dl class='field row'> 
            <dt class="dropdown" style="width:300px;">     
            <div class="selectbox">
                <input type="hidden" name="selectmodule" id="selectmodule" value=" ">
                <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                    <span class="selectbox-option input-medium" data-option=" ">Select Module</span>
                    <b class="caret1"></b>
                </a>
                <div class="selectbox-options">
                    <input type="text" class="selectbox-filter" placeholder="Search Module" value="">
                    <ul role="options">
                        <?php 
                         
                         $qry = $ObjDB->QueryObject("SELECT fld_license_id as licenseid,fld_scheduletype as scheduletype 
						                            FROM itc_class_rotation_schedule_mastertemp 
													WHERE fld_id='".$scheduleid."'");
													
                         $res = $qry->fetch_assoc();
                         extract($res);
						 
                         if($scheduletype==2)
						 {
                             $qrymodule=$ObjDB->QueryObject("SELECT a.fld_id as id, CONCAT(a.fld_module_name,' ',b.fld_version) as modulename,1 as type 
							                          FROM itc_module_master AS a
							                               LEFT JOIN itc_module_version_track AS b ON b.fld_mod_id=a.fld_id
							                               LEFT JOIN itc_license_mod_mapping AS c ON a.fld_id=c.fld_module_id 
													  WHERE c.fld_license_id='".$licenseid."' AND c.fld_type='1' AND a.fld_delstatus='0' AND                                                            c.fld_active='1' AND b.fld_delstatus='0' 
													  UNION ALL 
													  SELECT fld_id as moduleid,fld_contentname as modulename,8 as type 
													  FROM itc_customcontent_master 
													  WHERE fld_createdby='".$uid."' AND fld_delstatus='0' ORDER BY modulename");
                         }
                         else
						 {
                             $qrymodule=$ObjDB->QueryObject("SELECT a.fld_id as id, CONCAT(a.fld_mathmodule_name,' ',b.fld_version) as modulename,2 as type 
							                       FROM itc_mathmodule_master AS a 
												   		LEFT JOIN itc_module_version_track AS b ON b.fld_mod_id=a.fld_module_id
							                       		LEFT JOIN itc_license_mod_mapping AS c ON a.fld_id=c.fld_module_id 
												   WHERE c.fld_license_id='".$licenseid."' AND c.fld_type='2' AND a.fld_delstatus='0' AND c.fld_active='1'                                                    		AND b.fld_delstatus='0' 
												   UNION ALL 
												   SELECT fld_id as moduleid,fld_contentname as modulename,8 as type 
												   FROM itc_customcontent_master 
												   WHERE fld_createdby='".$uid."' AND fld_delstatus='0' ORDER BY modulename");
                         }
                         
                         
                        if($qrymodule->num_rows > 0)
                        {
                          while($rowsqry = $qrymodule->fetch_assoc())
                          {
                              extract($rowsqry);
                              ?>
                           
                            <li><a tabindex="-1" href="#" data-option="<?php echo $id;?>"  onclick="fn_addmodule(<?php echo $id;?>,<?php echo $scheduleid;?>,<?php echo $type;?>);" title="<?php echo $modulename;?>" class="tooltip"><?php echo $modulename;?> </a></li>
                        <?php 
                        }
                        }
                        else
                        {
                            echo "No Records";
                        }
                        ?>       
                    </ul>
                </div>
            </div>
            </dt>
        </dl>      
	<?php
	}
	
	/*--- add modules  ---*/
	if($oper == "addmodule" and $oper != '')
	{	
		$moduleid = isset($method['moduleid']) ? $method['moduleid'] : '0';
		$scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '0';
		$scheduletype = isset($method['scheduletype']) ? $method['scheduletype'] : '0';
		$thlength = isset($method['thlength']) ? $method['thlength'] : '0';
		$trlength = isset($method['trlength']) ? $method['trlength'] : '0';
		$type = isset($method['type']) ? $method['type'] : '0';
                $classid = isset($method['classid']) ? $method['classid'] : '0';
                $numberofrotation = (isset($method['numberofrotation'])) ? $method['numberofrotation'] : 0;	
		$trlength=explode("_",$trlength);
		
                $rowid=$trlength[1]+1;
                
		if($scheduletype==2)
		{
			if($type==1)
			{
				$modulename=$ObjDB->SelectSingleValue("SELECT CONCAT(a.fld_module_name,' ',b.fld_version) 
												FROM itc_module_master AS a 
													LEFT JOIN itc_module_version_track AS b ON b.fld_mod_id=a.fld_id
												WHERE a.fld_id='".$moduleid."' AND b.fld_delstatus='0' AND a.fld_delstatus='0'");
                       
                                
                                $count=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) 
				            FROM itc_class_rotation_moduledet
						    WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_module_id='".$moduleid."' 
							AND fld_type='".$type."' AND fld_row_id='".$rowid."'");
				
                                if($count==0)
                                {
                                        $ObjDB->NonQuery("INSERT INTO itc_class_rotation_moduledet(fld_class_id,fld_schedule_id,fld_module_id,fld_type,fld_numberofrotation,fld_row_id)
                                                                             values('".$classid."','".$scheduleid."','".$moduleid."','".$type."','".$numberofrotation."','".$rowid."')");
			}
			else
			{
                                        $ObjDB->NonQuery("UPDATE itc_class_rotation_moduledet SET fld_flag='1',fld_numberofrotation='".$numberofrotation."' 
                                WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_module_id='".$moduleid."' AND fld_type='".$type."' and fld_row_id='".$rowid."'");
                                }
                                
                                
			}
			else
			{
				$modulename=$ObjDB->SelectSingleValue("SELECT fld_contentname FROM itc_customcontent_master WHERE fld_id='".$moduleid."' AND fld_delstatus='0'");
                                
                                 $count=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) 
				            FROM itc_class_rotation_moduledet
						    WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_module_id='".$moduleid."' 
							AND fld_type='".$type."' AND fld_row_id='".$rowid."'");
				
                                if($count==0)
                                {
                                        $ObjDB->NonQuery("INSERT INTO itc_class_rotation_moduledet(fld_class_id,fld_schedule_id,fld_module_id,fld_type,fld_numberofrotation,fld_row_id)
                                                                             values('".$classid."','".$scheduleid."','".$moduleid."','".$type."','".$numberofrotation."','".$rowid."')");
			}
                                else
                                {
                                        $ObjDB->NonQuery("UPDATE itc_class_rotation_moduledet SET fld_flag='1',fld_numberofrotation='".$numberofrotation."' 
                                WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_module_id='".$moduleid."' AND fld_type='".$type."' and fld_row_id='".$rowid."'");
		}
			}
		}
		else
		{
			if($type==2)
			{
			$modulename=$ObjDB->SelectSingleValue("SELECT CONCAT(a.fld_mathmodule_name,' ',b.fld_version) 
			                                      FROM itc_mathmodule_master AS a 
												  LEFT JOIN itc_module_version_track AS b ON b.fld_mod_id=a.fld_module_id
												  WHERE a.fld_id='".$moduleid."' AND b.fld_delstatus='0' AND a.fld_delstatus='0'");
                        
                               
                                 $count=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) 
				            FROM itc_class_rotation_moduledet
						    WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_module_id='".$moduleid."' 
							AND fld_type='".$type."' AND fld_row_id='".$rowid."'");
				
                                if($count==0)
                                {
                                        $ObjDB->NonQuery("INSERT INTO itc_class_rotation_moduledet(fld_class_id,fld_schedule_id,fld_module_id,fld_type,fld_numberofrotation,fld_row_id)
                                                                             values('".$classid."','".$scheduleid."','".$moduleid."','".$type."','".$numberofrotation."','".$rowid."')");
			}
			else
			{
                                        $ObjDB->NonQuery("UPDATE itc_class_rotation_moduledet SET fld_flag='1',fld_numberofrotation='".$numberofrotation."' 
                                WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_module_id='".$moduleid."' AND fld_type='".$type."' and fld_row_id='".$rowid."'");
                                }
			}
			else
			{
				$modulename=$ObjDB->SelectSingleValue("SELECT fld_contentname FROM itc_customcontent_master WHERE fld_id='".$moduleid."' AND fld_delstatus='0'");
                                
                                
		
                                 $count=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) 
				            FROM itc_class_rotation_moduledet
						    WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_module_id='".$moduleid."' 
							AND fld_type='".$type."' AND fld_row_id='".$rowid."'");
				
                                if($count==0)
                                {
                                        $ObjDB->NonQuery("INSERT INTO itc_class_rotation_moduledet(fld_class_id,fld_schedule_id,fld_module_id,fld_type,fld_numberofrotation,fld_row_id)
                                                                             values('".$classid."','".$scheduleid."','".$moduleid."','".$type."','".$numberofrotation."','".$rowid."')");
			}
                                else
                                {
                                        $ObjDB->NonQuery("UPDATE itc_class_rotation_moduledet SET fld_flag='1',fld_numberofrotation='".$numberofrotation."' 
                                WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_module_id='".$moduleid."' AND fld_type='".$type."' and fld_row_id='".$rowid."'");
		}
			}
		}
		
		
	?>
    
    <tr id="tr_<?php echo $trlength[1]+1; ?>" class="<?php echo $moduleid."-".$type; ?>">
        <td id="module_<?php echo $trlength[1]+1;?>" onmouseover="fn_checkcellvalue(<?php echo $trlength[1]+1;?>)" onmouseout="fn_checkcellvalueout(this.id)"><?php echo $modulename; ?></td>
            <?php
				$k=2;
				$z=$trlength[1]+1;
				for($i=1;$i<$thlength;$i++)
				{
				?>
                <td id="stu_<?php echo $z.$k;?>" style="background: #FFFFFF;">
                
                	<div class="rowspanone clk row<?php echo $k;?>" id="seg1_<?php echo $z;?>_<?php echo $k;?>">&nbsp;</div>
					<div class="imagetop" id="imagetop_<?php echo $z;?>_<?php echo $k;?>" title="Delete"></div>
					<div class="rowspantwo clk row<?php echo $k;?>" id="seg2_<?php echo $z;?>_<?php echo $k;?>">&nbsp;</div>
					<div class="imagebottom" id="imagebottom_<?php echo $z;?>_<?php echo $k;?>" title="Delete"  <?php $k++; ?>></div>
                    
                </td>
                <?php
				}
				?>
		</tr>
         <tr id="addmod" style="display:none;">
        	<td style="display:none;">
                  
            </td>
            <?php
				for($i=1;$i<$thlength;$i++)
				{
				?>
                <td></td>
                <?php
				}
				?>
         </tr>
    <?php
	}
	
	/*--- load modules  ---*/
	if($oper=="loadmodules" and $oper!='')
	{
		$licenseid = isset($method['licenseid']) ? $method['licenseid'] : '0';
		$scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '0';
		$moduletype = isset($method['moduletype']) ? $method['moduletype'] : '0';
         	$assigntype = isset($method['assigntype']) ? $method['assigntype'] : '';
		
		$count=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_class_rotation_schedule_mastertemp WHERE fld_flag=1 and fld_id='".$scheduleid."'");
		
		if($assigntype == 0)
		    {
		      $count = 0;
		    }

	    $countmodulemap=$ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_class_rotation_schedule_module_mappingtemp WHERE fld_schedule_id='".$scheduleid."' AND fld_flag=1");
			
			if($countmodulemap==0)
			{
	?>
    		<script>
				fn_movealllistitems('list3','list4',0);
            </script>
    <?php
			}
			?>
    	 <script language="javascript" type="text/javascript">
    					$(function() {
							$('#testrailvisible15').slimscroll({
								width: '410px',
								height:'366px',
								size: '7px',
								railVisible: true,
                                                                alwaysVisible: true,
								allowPageScroll: false,
								railColor: '#F4F4F4',
								opacity: 1,
								color: '#d9d9d9',
								 wheelStep: 1
							});
							$('#testrailvisible16').slimscroll({
								width: '410px',
								height:'366px',
								size: '7px',
								railVisible: true,
                                                                alwaysVisible: true,
								allowPageScroll: false,
								railColor: '#F4F4F4',
								opacity: 1,
								color: '#d9d9d9',
                                                                 wheelStep: 1
							});
							
							$("#list3").sortable({
								connectWith: ".droptrue1",
								dropOnEmpty: true,
								items: "div[class='draglinkleft']",
								receive: function(event, ui) {
									$("div[class=draglinkright]").each(function(){ 
										if($(this).parent().attr('id')=='list3'){
											fn_movealllistitems('list3','list4',$(this).children(":first").attr('id'),'rotational');
										}
									});
								}
							});
                        
							$( "#list4" ).sortable({
								connectWith: ".droptrue1",
								dropOnEmpty: true,
								receive: function(event, ui) {
									$("div[class=draglinkleft]").each(function(){ 
										if($(this).parent().attr('id')=='list4'){
											fn_movealllistitems('list3','list4',$(this).children(":first").attr('id'),'rotational');
										}
									});
								}
							});
                        });
                    
      		 </script>
            <div class='row rowspacer <?php if($count==1){echo "dim";}?>' >
                <div class='six columns'>
                    <div class="dragndropcol">
                        <?php
                                    if($moduletype==1)
										{
																					
                                        $qrymodule= $ObjDB->QueryObject("SELECT a.fld_id as moduleid, fn_shortname(CONCAT(a.fld_module_name,' ',b.fld_version),1) AS shortname, CONCAT(a.fld_module_name,' ',b.fld_version) as modulename,1 as type 
										       FROM itc_module_master AS a 
										            LEFT JOIN itc_module_version_track AS b ON b.fld_mod_id=a.fld_id
										            LEFT JOIN itc_license_mod_mapping AS c ON a.fld_id=c.fld_module_id 
											  WHERE a.fld_id NOT IN (SELECT fld_module_id FROM itc_class_rotation_schedule_module_mappingtemp WHERE fld_schedule_id='".$scheduleid."' AND fld_type='1' AND fld_flag='1') AND c.fld_license_id='".$licenseid."' AND                                                    c.fld_type='1' AND c.fld_active='1' AND a.fld_delstatus='0' AND a.fld_module_type='1' AND                                                    b.fld_delstatus='0'
											        UNION ALL 
													SELECT fld_id as moduleid,'' as shortname,fld_contentname as modulename,8 as type 
													FROM itc_customcontent_master 
													WHERE fld_id NOT IN (SELECT fld_module_id FROM itc_class_rotation_schedule_module_mappingtemp WHERE fld_schedule_id='".$scheduleid."' AND fld_type='8' AND fld_flag='1') AND fld_createdby='".$uid."' and fld_delstatus='0' ORDER BY modulename");
										}
										else
										{
											$qrymodule= $ObjDB->QueryObject("SELECT a.fld_id as moduleid, fn_shortname(CONCAT(a.fld_mathmodule_name,' ',b.                                                        fld_version),1) AS shortname, CONCAT(a.fld_mathmodule_name,' ',b.fld_version) as modulename,2 as type 
											       FROM itc_mathmodule_master AS a 
												        LEFT JOIN itc_module_version_track AS b ON b.fld_mod_id=a.fld_module_id
											            LEFT JOIN itc_license_mod_mapping AS c ON a.fld_id=c.fld_module_id WHERE a.fld_id NOT IN (SELECT fld_module_id FROM itc_class_rotation_schedule_module_mappingtemp WHERE fld_type='2' AND fld_schedule_id='".$scheduleid.                                                       "' AND fld_flag='1') AND c.fld_license_id='".$licenseid."' AND c.fld_type='2' and c.fld_active='1' and                                                        a.fld_delstatus='0' AND b.fld_delstatus='0' 
														UNION ALL 
														SELECT fld_id as moduleid,'' as shortname,fld_contentname as modulename,8 as type 
														FROM itc_customcontent_master 
														WHERE fld_id NOT IN (SELECT fld_module_id FROM itc_class_rotation_schedule_module_mappingtemp WHERE fld_schedule_id='".$scheduleid."' AND fld_type='8' AND fld_flag='1') AND fld_createdby='".$uid."' and fld_delstatus='0' ORDER BY modulename");	
										}
                        ?>
                        <div class="dragtitle">Modules (<span id="leftmoddiv"><?php echo $qrymodule->num_rows;?></span>)</div>
                        <div class="dragWell" id="testrailvisible15" >
                        <div class="draglinkleftSearch" id="s_list3" >
                                                   <dl class='field row'>
                                                        <dt class='text'>
                                                            <input placeholder='Search' type='text' id="list_3_search" name="list_3_search" onKeyUp="search_list(this,'#list3');" />
                                                        </dt>
                                                    </dl>
                                                </div>
                            <div id="list3" class="dragleftinner droptrue1">
									<?php 
                                       
											if($qrymodule->num_rows > 0){
												while($rowsqry = $qrymodule->fetch_assoc()){
													extract($rowsqry);
													
                                                ?>
                                            <div class="draglinkleft" id="list3_<?php echo $moduleid."-".$type; ?>" title="<?php echo $modulename; ?>">
                                                <div class="dragItemLable" id="<?php echo $moduleid."-".$type; ?>"><?php echo $modulename; ?></div>
                                                <div class="clickable" id="clck_<?php echo $moduleid."-".$type; ?>" onclick="fn_movealllistitems('list3','list4','<?php echo $moduleid."-".$type; ?>','rotational');"></div>
                                            </div> 
                                        <?php }
                                            }?>
                            </div>
                        </div>
                        <div class="dragAllLink"  onclick="fn_movealllistitems('list3','list4',0,'rotational');fn_rotloadextendcontent(<?php echo $scheduleid.",".$licenseid.",";?>'mod');">add all modules</div>
                    </div>
                </div>
                <div class='six columns'>
                    <div class="dragndropcol">
                        <?php
                                if($moduletype==1)
									{
                                     $qrymodulemap=$ObjDB->QueryObject("SELECT a.fld_id as moduleid, fn_shortname(CONCAT(a.fld_module_name,' ',b.                                                    fld_version),1) AS shortname, CONCAT(a.fld_module_name,' ',b.fld_version) as modulename,1 as type 
									          FROM itc_module_master AS a 
											        LEFT JOIN itc_module_version_track AS b ON b.fld_mod_id=a.fld_id
											        LEFT JOIN itc_class_rotation_schedule_module_mappingtemp AS c ON a.fld_id=c.fld_module_id 
											  WHERE c.fld_schedule_id='".$scheduleid."' AND c.fld_flag=1 AND a.fld_delstatus='0' AND c.fld_type='1' AND b.fld_delstatus='0' 
											        UNION ALL 
											        SELECT a.fld_id as moduleid,'' as shortname,a.fld_contentname as modulename,8 as type 
													FROM itc_customcontent_master as a 
													LEFT JOIN itc_class_rotation_schedule_module_mappingtemp AS b ON b.fld_module_id=a.fld_id 
													WHERE b.fld_schedule_id='".$scheduleid."' AND b.fld_type='8' AND b.fld_flag=1 AND a.fld_delstatus='0' 
													ORDER BY modulename");
									}
									else
									{
										 $qrymodulemap=$ObjDB->QueryObject("SELECT a.fld_id as moduleid, fn_shortname(CONCAT(a.fld_mathmodule_name,' ',b.                                                        fld_version),1) AS shortname, CONCAT(a.fld_mathmodule_name,' ',b.fld_version) as modulename,2 as type  
										           FROM itc_mathmodule_master AS a 
												   LEFT JOIN itc_module_version_track AS b ON b.fld_mod_id=a.fld_module_id
												   LEFT JOIN itc_class_rotation_schedule_module_mappingtemp AS c ON a.fld_id=c.fld_module_id 
												   WHERE  c.fld_schedule_id='".$scheduleid."' AND c.fld_flag=1 AND c.fld_type='2' AND a.fld_delstatus='0' AND b.fld_delstatus='0' 
												   UNION ALL 
												   SELECT a.fld_id as moduleid,'' as shortname,a.fld_contentname as modulename,8 as type 
												   FROM itc_customcontent_master as a 
												   LEFT JOIN itc_class_rotation_schedule_module_mappingtemp AS b ON b.fld_module_id=a.fld_id
												   WHERE b.fld_schedule_id='".$scheduleid."' AND b.fld_type='8' AND b.fld_flag=1 AND a.fld_delstatus='0' 
												   ORDER BY modulename");
									}
                        ?>
                        <div class="dragtitle">Modules in your class (<span id="rightmoddiv"><?php echo $qrymodulemap->num_rows;?></span>)</div>
                        <div class="dragWell" id="testrailvisible16">
                            <div id="list4" class="dragleftinner droptrue1">
                                <?php 
                                    
											if($qrymodulemap->num_rows > 0){
												while($rowmodulemap = $qrymodulemap->fetch_assoc()){
													extract($rowmodulemap);
                                                ?>
                                                <div class="draglinkright" id="list4_<?php echo $moduleid."-".$type; ?>" title="<?php echo $modulename; ?>">
                                                    <div class="dragItemLable" id="<?php echo $moduleid."-".$type; ?>"><?php echo $modulename; ?></div>
                                                    <div class="clickable" id="clck_<?php echo $moduleid."-".$type; ?>" onclick="fn_movealllistitems('list3','list4','<?php echo $moduleid."-".$type; ?>','rotational');"></div>
                                                </div>
                                         <?php }
                                            }?>   
                            </div>
                        </div>
                        <div class="dragAllLink" onclick="fn_movealllistitems('list4','list3',0,'rotational');fn_rotloadextendcontent(<?php echo $scheduleid.",".$licenseid.",";?>'mod')">remove all modules</div>
                    </div>
                </div>
            </div>
                         
                            
                                      
    <?php
	}
	
        /*--- Block modules  ---*/
	if($oper=="blockmodules" and $oper!='')
	{
           
		$licenseid = isset($method['licenseid']) ? $method['licenseid'] : '0';
		$scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '0';
		$moduletype = isset($method['moduletype']) ? $method['moduletype'] : '0';
                $modulelist = isset($method['modules']) ? $method['modules'] : '0';
                $customlist = isset($method['modules']) ? $method['modules'] : '0';
                
                $customcontent=explode(',',$customlist);
                
                $customarray='';
                for($i=0;$i<sizeof($customcontent);$i++)
                {
                    $cus=explode('-',$customcontent[$i]);
                    
                    if($customarray=='' and $cus[1]==8)
                    {
                       $customarray=$cus[0];
                    }
                    else if($customarray!='' and $cus[1]==8)
                    {
                        $customarray=$customarray.",".$cus[0];
                    }
                        
                }
                
                $blockmodid=array();
                if($scheduleid!='0')
                {
                   $qry = $ObjDB->QueryObject("SELECT a.fld_moduleid AS smoduleid,(CASE WHEN a.fld_moduletype=1 THEN (SELECT CONCAT(fld_module_name,' ',
									(SELECT b.fld_version FROM itc_module_version_track AS b WHERE b.fld_mod_id=a.fld_moduleid AND b.fld_delstatus='0')) FROM itc_module_master 
									WHERE fld_id=a.fld_moduleid) WHEN a.fld_moduletype=2 THEN (SELECT CONCAT(fld_mathmodule_name,' ',(SELECT fld_version FROM itc_module_version_track 
									WHERE fld_mod_id=fld_moduleid AND fld_delstatus='0')) FROM itc_mathmodule_master WHERE fld_id=a.fld_moduleid) 
									 END) AS smodulename 
								FROM itc_class_rotation_blockstudent AS a 
								WHERE a.fld_scheduleid='".$scheduleid."' AND a.fld_flag='1' group by a.fld_moduleid");			
	
                        if($qry->num_rows>0)
                        {
                            while($row=$qry->fetch_assoc())
                            {
                                extract($row); 
                                $blockmodid[]=$smoduleid;
                        }
                }
                        
                        $qrycustom = $ObjDB->QueryObject("SELECT a.fld_moduleid AS cusid
								FROM itc_class_rotation_blockstudent AS a 
								WHERE a.fld_scheduleid='".$scheduleid."' AND a.fld_flag='1' AND a.fld_moduletype='8' group by a.fld_moduleid");			
	
                        if($qrycustom->num_rows>0)
                        {
                            while($rowcustom=$qrycustom->fetch_assoc())
                            {
                                extract($rowcustom); 
                                $blockcusid[]=$cusid;
                }
                        }
                }
                
                if($customarray=='')
                {
                    $customarray=0;
                }
                
        ?>
            <div class='row rowspacer'>
            <div class='six columns'>
               Select Block Module
            <dl class='field row'> 
            <dt class="dropdown" style="width:300px;">     
            <div class="selectbox">
                <input type="hidden" name="selectmodule" id="selectblockmodule" value="" onchange="fn_blockstudent();">
                <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                    <span class="selectbox-option input-medium" data-option=" ">Select module</span>
                    <b class="caret1"></b>
                </a>
                <div class="selectbox-options">
                    <input type="text" class="selectbox-filter" placeholder="Search Module" value="">
                    <ul role="options">
                        <?php 
                      if($modulelist!='')
                      {
                         if($moduletype==2)
			 {
                             $qrymodule=$ObjDB->QueryObject("SELECT a.fld_id as id, CONCAT(a.fld_module_name,' ',b.fld_version) as modulename,1 as type 
							                          FROM itc_module_master AS a
							                               LEFT JOIN itc_module_version_track AS b ON b.fld_mod_id=a.fld_id
							                               LEFT JOIN itc_license_mod_mapping AS c ON a.fld_id=c.fld_module_id 
													  WHERE c.fld_license_id='".$licenseid."' AND c.fld_type='1' AND a.fld_delstatus='0' AND c.fld_active='1' AND b.fld_delstatus='0' AND a.fld_id IN(".$modulelist.") 
													  ORDER BY modulename");
                         }
                         else
			{
                             
                             
                             $qrymodule=$ObjDB->QueryObject("SELECT a.fld_id as id, CONCAT(a.fld_mathmodule_name,' ',b.fld_version) as modulename,2 as type 
							                       FROM itc_mathmodule_master AS a 
												   		LEFT JOIN itc_module_version_track AS b ON b.fld_mod_id=a.fld_module_id
							                       		LEFT JOIN itc_license_mod_mapping AS c ON a.fld_id=c.fld_module_id 
												   WHERE c.fld_license_id='".$licenseid."' AND c.fld_type='2' AND a.fld_delstatus='0' AND c.fld_active='1' AND  a.fld_id IN(".$modulelist.")                                                    		AND b.fld_delstatus='0' 
												   ORDER BY modulename");
                         }
                         
                       $customcontent=$ObjDB->QueryObject("SELECT fld_id as id,fld_contentname as modulename,8 as type 
													FROM itc_customcontent_master 
													WHERE fld_id IN (".$customarray.") and fld_delstatus='0'");
                         
                        if($qrymodule->num_rows > 0)
                        {
                          
                          while($rowsqry = $qrymodule->fetch_assoc())
                          {
                              extract($rowsqry);
                               
                              if(in_array($id,$blockmodid))
                              {
                                  $block="true";
                              }
                              else
                              {
                                  $block="false";
                              }
                              
                              ?>
                           
                            <li><a tabindex="-1" href="#" data-option="<?php echo $id."-".$type;?>"  title="<?php echo $modulename;?>" class="tooltip"><?php echo $modulename." / MOD"; if($block=="true"){ echo " / Block";}?> </a></li>
                        <?php 
                        }
                        }
                       
                        if($customcontent->num_rows > 0)
                        {
                          
                          while($rowsqry = $customcontent->fetch_assoc())
                          {
                              extract($rowsqry);
                               
                              if(in_array($id,$blockcusid))
                              {
                                  $block="true";
                      }
                       else
                       {
                                  $block="false";
                              }
                              
                              ?>
                           
                            <li><a tabindex="-1" href="#" data-option="<?php echo $id."-".$type;?>"  title="<?php echo $modulename;?>" class="tooltip"><?php echo $modulename." / CUSTOM"; if($block=="true"){ echo " / Block";}?> </a></li>
                        <?php 
                        }
                        }
                       
                      }
                       else
                       {
                          echo "No Records";
                       }
                        ?>       
                    </ul>
                </div>
            </div>
            </dt>
           </dl>
           </div>
           </div>
                 
        <?php
        }
        
        
        if($oper=="blockmodstudents" and $oper!='')
	{
                
		$scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '0';
                $classid = isset($method['classid']) ? $method['classid'] : '0';
		$moduletype = isset($method['moduletype']) ? $method['moduletype'] : '0';
                $blockmodule = isset($method['blockmodule']) ? $method['blockmodule'] : '0';
                $blockstudents = isset($method['students']) ? $method['students'] : '0';
                if($blockmodule=='')
                {
                    $blockmodule='0';
                }
                
                $blockmod=explode("-",$blockmodule);
                
                $blockmodule=$blockmod[0];
                $moduletype=$blockmod[1];
                
                $blockstudents = explode(',',$blockstudents);
                
                /* Block student mapping start */
                        
                        $ObjDB->NonQuery("UPDATE itc_class_rotation_blockstudent SET fld_flag='0',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' WHERE fld_scheduleid='".$scheduleid."' AND fld_moduletype='".$moduletype."' AND fld_moduleid='".$blockmodule."'");
			
                        if($blockstudents[0]>0)
                        {
                            for($i=0;$i<sizeof($blockstudents);$i++)
                            {
                                
                                    $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_class_rotation_blockstudent WHERE fld_scheduleid='".$scheduleid."' AND  fld_moduleid='".$blockmodule."' AND fld_moduletype='".$moduletype."'  AND fld_studentid='".$blockstudents[$i]."'");
                                    if($cnt==0)
                                    {
                                            
                                            $ObjDB->NonQuery("INSERT INTO itc_class_rotation_blockstudent(fld_classid,fld_scheduleid,fld_moduleid,fld_moduletype,fld_studentid,fld_flag,fld_createddate,fld_createdby) VALUES ('".$classid."','".$scheduleid."','".$blockmodule."','".$moduletype."','".$blockstudents[$i]."','1','".date('Y-m-d H:i:s')."','".$uid."')");
                                    }
                                    else
                                    {
                                            $ObjDB->NonQuery("UPDATE itc_class_rotation_blockstudent SET fld_moduletype='".$moduletype."',fld_flag='1',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' WHERE fld_scheduleid='".$scheduleid."' AND fld_studentid='".$blockstudents[$i]."' AND fld_id='".$cnt."'");
                                    }
                            }
                        }
				
				
			/* Block student mapping end */
        }
        
        /*--- Block students  ---*/
        if($oper=="blockstudents" and $oper!='')
        {
                $licenseid = isset($method['licenseid']) ? $method['licenseid'] : '0';
		$scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '0';
		$studenttype = isset($method['studenttype']) ? $method['studenttype'] : '0';
                $students = isset($method['students']) ? $method['students'] : '0';
                $classid = isset($method['classid']) ? $method['classid'] : '0';
                $blockmodule = isset($method['blockmodule']) ? $method['blockmodule'] : '0';
                
                $blockmod=explode('-',$blockmodule);
                
                $blockmodule=$blockmod[0];
                $moduletype=$blockmod[1];
            ?>
                  <script language="javascript" type="text/javascript">
								$(function() {
									$('div[id^="testrailvisible"]').each(function(index, element) {
										$(this).slimscroll({ /*------- Scroll for Modules Left Box ------*/
											width: '410px',
											height:'366px',
											size: '7px',
											railVisible: true,
                                                                                        alwaysVisible: true,
											allowPageScroll: false,
											railColor: '#F4F4F4',
											opacity: 1,
											color: '#d9d9d9',
                                                                                         wheelStep: 1
										});
									});
									
									/* drag and sort for the first left box - Teachers */	
									
									
									$("#list25").sortable({
										connectWith: ".droptrue1",
										dropOnEmpty: true,
										items: "div[class='draglinkleft']",
										receive: function(event, ui) {
											$("div[class=draglinkright]").each(function(){ 
												if($(this).parent().attr('id')=='list25'){
													fn_movealllistitems('list25','list26',$(this).children(":first").attr('id'));
												}
											});
										}
									});
								
									$( "#list26" ).sortable({
										connectWith: ".droptrue1",
										dropOnEmpty: true,
										receive: function(event, ui) {
											$("div[class=draglinkleft]").each(function(){ 
												if($(this).parent().attr('id')=='list26'){
													fn_movealllistitems('list25','list26',$(this).children(":first").attr('id'));
												}
											});
										}
									});
								});
                            </script> 
                               
                            
                            
                            <div class='row rowspacer'>
                                <div class='six columns'>
                                    <div class="dragndropcol">
                                    <?php
                                                                    if($students=='' or $studenttype==1)
                                                                    {
                                                                        $cond = "SELECT fld_studentid 
                                                                                    FROM itc_class_rotation_blockstudent 
                                                                                    WHERE fld_scheduleid='".$scheduleid."' AND fld_moduleid='".$blockmodule."' AND fld_moduletype='".$moduletype."' AND fld_flag='1'";
                                                                        
                                                                       
                                                                        
                                                                        $qrystudent= $ObjDB->QueryObject("SELECT a.fld_id AS studentid, CONCAT(a.fld_fname,' ',a.fld_lname) AS sname,a.fld_username as username
												 								  FROM itc_class_student_mapping AS b LEFT JOIN itc_user_master AS a  ON a.fld_id=b.fld_student_id  
																				  WHERE b.fld_class_id='".$classid."' AND b.fld_flag='1' AND b.fld_student_id NOT IN(".$cond.") 
																				  ORDER BY a.fld_lname");
                                                                    }
                                                                    else
                                                                    {
                                                                        $cond = "SELECT fld_studentid 
                                                                                    FROM itc_class_rotation_blockstudent 
                                                                                    WHERE fld_scheduleid='".$scheduleid."' AND fld_moduleid='".$blockmodule."' AND fld_moduletype='".$moduletype."' AND fld_flag='1'";
                                                                        
                                                                        $qrystudent= $ObjDB->QueryObject("SELECT fld_id AS studentid, CONCAT(fld_fname,' ',fld_lname) AS sname,fld_username as username
												 								  FROM itc_user_master  
																				  WHERE fld_id IN(".$students.") AND fld_id NOT IN(".$cond.") 
																				  ORDER BY fld_lname");
                                                                    }
									?>
                                    	<div class="dragtitle">   &nbsp;&nbsp;&nbsp;Students available (<span id="blockstudentleftdiv"><?php echo $qrystudent->num_rows;?></span>)</div>
                                        <div class="draglinkleftSearch" id="s_list25" >
                                            <dl class='field row'>
                                                <dt class='text'>
                                                    <input placeholder='Search' type='text' id="list_25_search" name="list_25_search" onKeyUp="search_list(this,'#list25');" />
                                                </dt>
                                            </dl>
                                        </div>
                                        <div class="dragWell" id="testrailvisible25" >
                                            <div id="list25" class="dragleftinner droptrue1">
												<?php 
                                                if($qrystudent->num_rows > 0){
													while($rowqryclassstudentmap = $qrystudent->fetch_assoc()){
														extract($rowqryclassstudentmap);
														?>
														<div class="draglinkleft" id="list25_<?php echo $studentid; ?>" >
                                                            <div class="dragItemLable tooltip" id="<?php echo $studentid; ?>" title="<?php echo $username;?>"><?php echo $sname; ?></div>
                                                            <div class="clickable" id="clck_<?php echo $studentid;?>" onclick="fn_movealllistitems('list25','list26',<?php echo $studentid;?>);"></div>
														</div> 
													<?php }
                                                }?>    
                                            </div>
                                        </div>
                                   		<div class="dragAllLink"  onclick="fn_movealllistitems('list25','list26',0);">add all Students</div>
                                    </div>
                                </div>
                                
                                <div class='six columns'>
                                    <div class="dragndropcol">
                                    <?php                                   if($students=='' or $studenttype==1)
                                                                            {
										$qryclassstudentmap=$ObjDB->QueryObject("SELECT a.fld_id AS studentid, CONCAT(a.fld_fname,' ',a.fld_lname) AS sname,a.fld_username as username
                                                                                                                            FROM itc_user_master AS a LEFT JOIN itc_class_rotation_blockstudent AS b ON a.fld_id=b.fld_studentid 
																					WHERE b.fld_classid='".$classid."' AND b.fld_scheduleid='".$scheduleid."' AND b.fld_moduleid='".$blockmodule."' AND b.fld_moduletype='".$moduletype."'  AND b.fld_flag='1' AND a.fld_activestatus='1' AND a.fld_delstatus='0'");
                                                                            }
                                                                            else
                                                                            {
                                                                                $qryclassstudentmap=$ObjDB->QueryObject("SELECT a.fld_id AS studentid, CONCAT(a.fld_fname,' ',a.fld_lname) AS sname,a.fld_username as username
                                                                                                                            FROM itc_user_master AS a LEFT JOIN itc_class_rotation_blockstudent AS b ON a.fld_id=b.fld_studentid 
																					WHERE b.fld_classid='".$classid."' AND b.fld_scheduleid='".$scheduleid."' AND b.fld_moduleid='".$blockmodule."' AND b.fld_moduletype='".$moduletype."' AND b.fld_studentid IN(".$students.") AND b.fld_flag='1' AND a.fld_activestatus='1' AND a.fld_delstatus='0'");
                                                                            }
									?>
                                    	<div class="dragtitle">Blocked Students  (<span id="blockstudentrightdiv"><?php echo $qryclassstudentmap->num_rows;?></span>)</div> 
                                        <div class="draglinkleftSearch" id="s_list26" >
                                            <dl class='field row'>
                                                <dt class='text'>
                                                    <input placeholder='Search' type='text' id="list_26_search" name="list_26_search" onKeyUp="search_list(this,'#list26');" />
                                                </dt>
                                            </dl>
                                        </div>
                                        <div class="dragWell" id="testrailvisible26">
                                            <div id="list26" class="dragleftinner droptrue1">
                                            <?php 
                                            if($qryclassstudentmap->num_rows > 0){
												while($rowsqry = $qryclassstudentmap->fetch_assoc()){
													extract($rowsqry);
													?> 
													<div class="draglinkright" id="list26_<?php echo $studentid; ?>">
                                                        <div class="dragItemLable tooltip" id="<?php echo $studentid; ?>" title="<?php echo $username;?>"><?php echo $sname;?></div>
                                                        <div class="clickable" id="clck_<?php echo $studentid; ?>" onclick="fn_movealllistitems('list25','list26',<?php echo $studentid; ?>);">
                                                        </div>
													</div>
												<?php }
                                            }?>
                                            </div>
                                        </div>
                                    	<div class="dragAllLink" onclick="fn_movealllistitems('list26','list25',0);">remove all Students</div>
            <?php
        }
	
	/*--- Delete schedule  ---*/
	if($oper=="deleteschedule" and $oper!='')
	{
		$scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '0';
		$type = isset($method['type']) ? $method['type'] : '0';


		if($type==1)
		{
			$ObjDB->NonQuery("UPDATE itc_class_sigmath_master SET fld_delstatus='1',fld_deletedby='".$uid."',fld_deleted_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$scheduleid."'");
			$add=0;
			
			$licenseid = $ObjDB->SelectSingleValueInt("SELECT fld_license_id FROM itc_class_sigmath_master WHERE fld_id='".$scheduleid."'");
			
			$qry = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_student_id SEPARATOR',') FROM itc_class_sigmath_student_mapping 
			                                 WHERE fld_sigmath_id='".$scheduleid."' AND fld_flag='1'");
			$studentid = explode(',',$qry);
			
			for($i=0;$i<sizeof($studentid);$i++)
			{
			$studentcount = $ObjDB->SelectSingleValueInt("SELECT SUM(o.cnt) FROM (SELECT COUNT(a.fld_id) AS cnt FROM itc_class_sigmath_student_mapping AS a                            LEFT JOIN itc_class_sigmath_master AS b ON a.fld_sigmath_id=b.fld_id 
				   WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."' AND a.fld_sigmath_id<>'".$scheduleid."'
                          UNION ALL 
                          SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_schedule_student_mappingtemp AS a 
						  LEFT JOIN itc_class_rotation_schedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
						  WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."' 
                          UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_expschedule_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_expschedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
				WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                    
                           UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_mission_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_mission_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
				WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                          
                          UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_modexpschedule_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_modexpschedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
				WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."' AND b.fld_license_id='".$licenseid."' 
                          UNION ALL 
                          SELECT COUNT(a.fld_id) AS cnt FROM itc_class_dyad_schedule_studentmapping AS a 
						  LEFT JOIN itc_class_dyad_schedulemaster AS b ON a.fld_schedule_id=b.fld_id 
						  WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                          UNION ALL 
                          SELECT COUNT(a.fld_id) AS cnt FROM itc_class_triad_schedule_studentmapping AS a 
						  LEFT JOIN itc_class_triad_schedulemaster AS b ON a.fld_schedule_id=b.fld_id 
						  WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
			
                           UNION ALL 
                          SELECT COUNT(a.fld_id) AS cnt FROM itc_class_indassesment_student_mapping AS a 
							   LEFT JOIN itc_class_indassesment_master AS b ON a.fld_schedule_id=b.fld_id 
							   WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                           UNION ALL 
                               SELECT COUNT(a.fld_id) AS cnt FROM itc_class_mission_student_mapping AS a 
							   LEFT JOIN itc_class_indasmission_master AS b ON a.fld_schedule_id=b.fld_id 
							   WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'                                 

                           UNION ALL 
                               SELECT COUNT(a.fld_id) AS cnt FROM itc_class_exp_student_mapping AS a 
							   LEFT JOIN itc_class_indasexpedition_master AS b ON a.fld_schedule_id=b.fld_id 
							   WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                           UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_pdschedule_student_mapping AS a 
								LEFT JOIN itc_class_pdschedule_master AS b ON a.fld_pdschedule_id=b.fld_id 
								WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."') AS o");
					
					if($studentcount==0){
						$add++;
						$ObjDB->NonQuery("UPDATE itc_license_assign_student SET fld_flag='0',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' WHERE fld_license_id='".$licenseid."' AND fld_student_id='".$studentid[$i]."' ");
					}
				
			}
		}
		else if($type==2 or $type==6)
		{
			$ObjDB->NonQuery("UPDATE itc_class_rotation_schedule_mastertemp SET fld_delstatus='1',fld_deletedby='".$uid."',fld_deleted_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$scheduleid."'");
			
			$add=0;
			
			$licenseid = $ObjDB->SelectSingleValueInt("SELECT fld_license_id FROM itc_class_rotation_schedule_mastertemp WHERE fld_id='".$scheduleid."'");
			
			$qry = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_student_id SEPARATOR',') FROM itc_class_rotation_schedule_student_mappingtemp WHERE fld_schedule_id='".$scheduleid."' AND fld_flag='1'");
			
			$studentid = explode(',',$qry);
			
			for($i=0;$i<sizeof($studentid);$i++)
			{
				$studentcount = $ObjDB->SelectSingleValueInt("SELECT SUM(o.cnt) FROM (SELECT COUNT(a.fld_id) AS cnt FROM itc_class_sigmath_student_mapping AS a                                LEFT JOIN itc_class_sigmath_master AS b ON a.fld_sigmath_id=b.fld_id 
				                WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."' 
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_schedule_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_schedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
				WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."' AND a.fld_schedule_id<>'".$scheduleid."'
                               UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_expschedule_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_expschedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
				WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                 UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_mission_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_mission_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
				WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                               UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_modexpschedule_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_modexpschedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
				WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."' AND b.fld_license_id='".$licenseid."'
                               UNION ALL 
                               SELECT COUNT(a.fld_id) AS cnt FROM itc_class_dyad_schedule_studentmapping AS a 
							   LEFT JOIN itc_class_dyad_schedulemaster AS b ON a.fld_schedule_id=b.fld_id 
							   WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                               UNION ALL 
                               SELECT COUNT(a.fld_id) AS cnt FROM itc_class_triad_schedule_studentmapping AS a 
							   LEFT JOIN itc_class_triad_schedulemaster AS b ON a.fld_schedule_id=b.fld_id 
							   WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                               UNION ALL 
                          SELECT COUNT(a.fld_id) AS cnt FROM itc_class_indassesment_student_mapping AS a 
							   LEFT JOIN itc_class_indassesment_master AS b ON a.fld_schedule_id=b.fld_id 
							   WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                           UNION ALL 
                               SELECT COUNT(a.fld_id) AS cnt FROM itc_class_mission_student_mapping AS a 
							   LEFT JOIN itc_class_indasmission_master AS b ON a.fld_schedule_id=b.fld_id 
							   WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'                                  

                           UNION ALL 
                               SELECT COUNT(a.fld_id) AS cnt FROM itc_class_exp_student_mapping AS a 
							   LEFT JOIN itc_class_indasexpedition_master AS b ON a.fld_schedule_id=b.fld_id 
							   WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                           UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_pdschedule_student_mapping AS a 
								LEFT JOIN itc_class_pdschedule_master AS b ON a.fld_pdschedule_id=b.fld_id 
								WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."') AS o");	
					
					if($studentcount==0){
						$add++;
						$ObjDB->NonQuery("UPDATE itc_license_assign_student SET fld_flag='0',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' WHERE fld_license_id='".$licenseid."' AND fld_student_id='".$studentid[$i]."' ");
					}
				
			}
		}
                else if($type==17)
		{
			$ObjDB->NonQuery("UPDATE itc_class_rotation_expschedule_mastertemp SET fld_delstatus='1',fld_deletedby='".$uid."',fld_deleted_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$scheduleid."'");
			
			$add=0;
			
			$licenseid = $ObjDB->SelectSingleValueInt("SELECT fld_license_id FROM itc_class_rotation_expschedule_mastertemp WHERE fld_id='".$scheduleid."'");
			
			$qry = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_student_id SEPARATOR',') FROM itc_class_rotation_expschedule_student_mappingtemp WHERE fld_schedule_id='".$scheduleid."' AND fld_flag='1'");
			
			$studentid = explode(',',$qry);
			
			for($i=0;$i<sizeof($studentid);$i++)
			{
				$studentcount = $ObjDB->SelectSingleValueInt("SELECT SUM(o.cnt) FROM (SELECT COUNT(a.fld_id) AS cnt FROM itc_class_sigmath_student_mapping AS a                                LEFT JOIN itc_class_sigmath_master AS b ON a.fld_sigmath_id=b.fld_id 
				                WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."' 
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_schedule_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_schedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
				WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                    UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_expschedule_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_expschedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
				WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                    
                                 UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_mission_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_mission_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
				WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                    
                               UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_expschedule_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_expschedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
				WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."' AND a.fld_schedule_id<>'".$scheduleid."'
                               UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_modexpschedule_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_modexpschedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
				WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."' AND b.fld_license_id='".$licenseid."'
                               UNION ALL 
                               SELECT COUNT(a.fld_id) AS cnt FROM itc_class_dyad_schedule_studentmapping AS a 
							   LEFT JOIN itc_class_dyad_schedulemaster AS b ON a.fld_schedule_id=b.fld_id 
							   WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                               UNION ALL 
                               SELECT COUNT(a.fld_id) AS cnt FROM itc_class_triad_schedule_studentmapping AS a 
							   LEFT JOIN itc_class_triad_schedulemaster AS b ON a.fld_schedule_id=b.fld_id 
							   WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                               UNION ALL 
                          SELECT COUNT(a.fld_id) AS cnt FROM itc_class_indassesment_student_mapping AS a 
							   LEFT JOIN itc_class_indassesment_master AS b ON a.fld_schedule_id=b.fld_id 
							   WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                           UNION ALL 
                               SELECT COUNT(a.fld_id) AS cnt FROM itc_class_mission_student_mapping AS a 
							   LEFT JOIN itc_class_indasmission_master AS b ON a.fld_schedule_id=b.fld_id 
							   WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'                                  

                           UNION ALL 
                               SELECT COUNT(a.fld_id) AS cnt FROM itc_class_exp_student_mapping AS a 
							   LEFT JOIN itc_class_indasexpedition_master AS b ON a.fld_schedule_id=b.fld_id 
							   WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                           UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_pdschedule_student_mapping AS a 
								LEFT JOIN itc_class_pdschedule_master AS b ON a.fld_pdschedule_id=b.fld_id 
							   WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."') AS o");
					
					if($studentcount==0){
						$add++;
						$ObjDB->NonQuery("UPDATE itc_license_assign_student SET fld_flag='0',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' WHERE fld_license_id='".$licenseid."' AND fld_student_id='".$studentid[$i]."' ");
					}
				
			}
		}
                else if($type==19)
		{
			$ObjDB->NonQuery("UPDATE itc_class_rotation_modexpschedule_mastertemp SET fld_delstatus='1',fld_deletedby='".$uid."',fld_deleted_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$scheduleid."'");
			
			$add=0;
			
			$licenseid = $ObjDB->SelectSingleValueInt("SELECT fld_license_id FROM itc_class_rotation_modexpschedule_mastertemp WHERE fld_id='".$scheduleid."'");
			
			$qry = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_student_id SEPARATOR',') FROM itc_class_rotation_modexpschedule_student_mappingtemp WHERE fld_schedule_id='".$scheduleid."' AND fld_flag='1'");
			
			$studentid = explode(',',$qry);
			
			for($i=0;$i<sizeof($studentid);$i++)
			{
				$studentcount = $ObjDB->SelectSingleValueInt("SELECT SUM(o.cnt) FROM (SELECT COUNT(a.fld_id) AS cnt FROM itc_class_sigmath_student_mapping AS a                                LEFT JOIN itc_class_sigmath_master AS b ON a.fld_sigmath_id=b.fld_id 
				                WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."' 
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_schedule_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_schedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
				WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                    UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_expschedule_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_expschedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
				WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                    
                                 UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_mission_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_mission_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
				WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                    
                               UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_expschedule_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_expschedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
				WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."' AND b.fld_license_id='".$licenseid."' 
                               UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_modexpschedule_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_modexpschedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
				WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."' AND a.fld_schedule_id<>'".$scheduleid."'
                               UNION ALL 
                               SELECT COUNT(a.fld_id) AS cnt FROM itc_class_dyad_schedule_studentmapping AS a 
							   LEFT JOIN itc_class_dyad_schedulemaster AS b ON a.fld_schedule_id=b.fld_id 
							   WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                               UNION ALL 
                               SELECT COUNT(a.fld_id) AS cnt FROM itc_class_triad_schedule_studentmapping AS a 
							   LEFT JOIN itc_class_triad_schedulemaster AS b ON a.fld_schedule_id=b.fld_id 
							   WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                               UNION ALL 
                          SELECT COUNT(a.fld_id) AS cnt FROM itc_class_indassesment_student_mapping AS a 
							   LEFT JOIN itc_class_indassesment_master AS b ON a.fld_schedule_id=b.fld_id 
							   WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                           UNION ALL 
                               SELECT COUNT(a.fld_id) AS cnt FROM itc_class_mission_student_mapping AS a 
							   LEFT JOIN itc_class_indasmission_master AS b ON a.fld_schedule_id=b.fld_id 
							   WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'                                  

                           UNION ALL 
                               SELECT COUNT(a.fld_id) AS cnt FROM itc_class_exp_student_mapping AS a 
							   LEFT JOIN itc_class_indasexpedition_master AS b ON a.fld_schedule_id=b.fld_id 
							   WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                           UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_pdschedule_student_mapping AS a 
								LEFT JOIN itc_class_pdschedule_master AS b ON a.fld_pdschedule_id=b.fld_id 
							   WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."') AS o");
					
					if($studentcount==0){
						$add++;
						$ObjDB->NonQuery("UPDATE itc_license_assign_student SET fld_flag='0',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' WHERE fld_license_id='".$licenseid."' AND fld_student_id='".$studentid[$i]."' ");
					}
				
			}
		}
                else if($type==20)
		{
			$ObjDB->NonQuery("UPDATE itc_class_rotation_mission_mastertemp SET fld_delstatus='1',fld_deletedby='".$uid."',fld_deleted_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$scheduleid."'");
			
			$add=0;
			
			$licenseid = $ObjDB->SelectSingleValueInt("SELECT fld_license_id FROM itc_class_rotation_mission_mastertemp WHERE fld_id='".$scheduleid."'");
			
			$qry = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_student_id SEPARATOR',') FROM itc_class_rotation_mission_student_mappingtemp WHERE fld_schedule_id='".$scheduleid."' AND fld_flag='1'");
			
			$studentid = explode(',',$qry);
			
			for($i=0;$i<sizeof($studentid);$i++)
			{
				$studentcount = $ObjDB->SelectSingleValueInt("SELECT SUM(o.cnt) FROM (SELECT COUNT(a.fld_id) AS cnt FROM itc_class_sigmath_student_mapping AS a                                LEFT JOIN itc_class_sigmath_master AS b ON a.fld_sigmath_id=b.fld_id 
				                WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."' 
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_schedule_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_schedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
				WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                    UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_expschedule_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_expschedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
				WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                    
                                 UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_mission_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_mission_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
				WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND a.fld_schedule_id<>'".$scheduleid."'
                                    
                               UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_expschedule_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_expschedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
				WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."' AND b.fld_license_id='".$licenseid."' 
                               UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_modexpschedule_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_modexpschedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
				WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."' AND b.fld_license_id='".$licenseid."' 
                               UNION ALL 
                               SELECT COUNT(a.fld_id) AS cnt FROM itc_class_dyad_schedule_studentmapping AS a 
							   LEFT JOIN itc_class_dyad_schedulemaster AS b ON a.fld_schedule_id=b.fld_id 
							   WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                               UNION ALL 
                               SELECT COUNT(a.fld_id) AS cnt FROM itc_class_triad_schedule_studentmapping AS a 
							   LEFT JOIN itc_class_triad_schedulemaster AS b ON a.fld_schedule_id=b.fld_id 
							   WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                               UNION ALL 
                          SELECT COUNT(a.fld_id) AS cnt FROM itc_class_indassesment_student_mapping AS a 
							   LEFT JOIN itc_class_indassesment_master AS b ON a.fld_schedule_id=b.fld_id 
							   WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                           UNION ALL 
                               SELECT COUNT(a.fld_id) AS cnt FROM itc_class_mission_student_mapping AS a 
							   LEFT JOIN itc_class_indasmission_master AS b ON a.fld_schedule_id=b.fld_id 
							   WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'                                  

                           UNION ALL 
                               SELECT COUNT(a.fld_id) AS cnt FROM itc_class_exp_student_mapping AS a 
							   LEFT JOIN itc_class_indasexpedition_master AS b ON a.fld_schedule_id=b.fld_id 
							   WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                           UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_pdschedule_student_mapping AS a 
								LEFT JOIN itc_class_pdschedule_master AS b ON a.fld_pdschedule_id=b.fld_id 
							   WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."') AS o");
					
					if($studentcount==0){
						$add++;
						$ObjDB->NonQuery("UPDATE itc_license_assign_student SET fld_flag='0',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' WHERE fld_license_id='".$licenseid."' AND fld_student_id='".$studentid[$i]."' ");
					}
				
			}
		}
		else if($type==3)
		{
			$ObjDB->NonQuery("UPDATE itc_class_dyad_schedulemaster SET fld_delstatus='1',fld_deletedby='".$uid."',fld_deleted_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$scheduleid."'");
			
			$add=0;
			
			$licenseid = $ObjDB->SelectSingleValueInt("SELECT fld_license_id FROM itc_class_rotation_schedule_mastertemp WHERE fld_id='".$scheduleid."'");
			
			$qry = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_student_id SEPARATOR',') FROM itc_class_rotation_schedule_student_mappingtemp WHERE fld_schedule_id='".$scheduleid."' AND fld_flag='1'");
			
			$studentid = explode(',',$qry);
			
			for($i=0;$i<sizeof($studentid);$i++)
			{
				$studentcount = $ObjDB->SelectSingleValueInt("SELECT SUM(o.cnt) FROM (SELECT COUNT(a.fld_id) AS cnt FROM itc_class_sigmath_student_mapping AS a                                LEFT JOIN itc_class_sigmath_master AS b ON a.fld_sigmath_id=b.fld_id 
				               WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."' 
                               UNION ALL 
                               SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_schedule_student_mappingtemp AS a 
							   LEFT JOIN itc_class_rotation_schedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
							   WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                               UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_expschedule_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_expschedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
				WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                    
                                 UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_mission_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_mission_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
				WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                    
                               UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_modexpschedule_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_modexpschedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
				WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."' AND b.fld_license_id='".$licenseid."'
                               UNION ALL 
                               SELECT COUNT(a.fld_id) AS cnt FROM itc_class_dyad_schedule_studentmapping AS a 
							   LEFT JOIN itc_class_dyad_schedulemaster AS b ON a.fld_schedule_id=b.fld_id 
				WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'  AND a.fld_schedule_id<>'".$scheduleid."'
                               UNION ALL 
                               SELECT COUNT(a.fld_id) AS cnt FROM itc_class_triad_schedule_studentmapping AS a 
							   LEFT JOIN itc_class_triad_schedulemaster AS b ON a.fld_schedule_id=b.fld_id 
							   WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                                               
                               UNION ALL 
                          SELECT COUNT(a.fld_id) AS cnt FROM itc_class_indassesment_student_mapping AS a 
							   LEFT JOIN itc_class_indassesment_master AS b ON a.fld_schedule_id=b.fld_id 
							   WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                           UNION ALL 
                                    SELECT COUNT(a.fld_id) AS cnt FROM itc_class_mission_student_mapping AS a 
							   LEFT JOIN itc_class_indasmission_master AS b ON a.fld_schedule_id=b.fld_id 
							   WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'                               

                           UNION ALL 
                               SELECT COUNT(a.fld_id) AS cnt FROM itc_class_exp_student_mapping AS a 
							   LEFT JOIN itc_class_indasexpedition_master AS b ON a.fld_schedule_id=b.fld_id 
							   WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                           UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_pdschedule_student_mapping AS a 
								LEFT JOIN itc_class_pdschedule_master AS b ON a.fld_pdschedule_id=b.fld_id 
								WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."') AS o");
					
					if($studentcount==0){
						$add++;
						$ObjDB->NonQuery("UPDATE itc_license_assign_student SET fld_flag='0' WHERE fld_license_id='".$licenseid."' AND fld_student_id='".$studentid[$i]."' ");
					}
				
			}
			
		}
		else if($type==5)
		{
			$ObjDB->NonQuery("UPDATE itc_class_indassesment_master SET fld_delstatus='1',fld_deletedby='".$uid."',fld_deleted_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$scheduleid."'");
			
			$add=0;
			
			$licenseid = $ObjDB->SelectSingleValueInt("SELECT fld_license_id FROM itc_class_indassesment_master WHERE fld_id='".$scheduleid."'");
			
			$qry = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_student_id SEPARATOR',') FROM itc_class_indassesment_student_mapping WHERE fld_schedule_id='".$scheduleid."' AND fld_flag='1'");
			
			$studentid = explode(',',$qry);
			
			for($i=0;$i<sizeof($studentid);$i++)
			{
				$studentcount = $ObjDB->SelectSingleValueInt("SELECT SUM(o.cnt) FROM (SELECT COUNT(a.fld_id) AS cnt FROM itc_class_sigmath_student_mapping AS a                                 LEFT JOIN itc_class_sigmath_master AS b ON a.fld_sigmath_id=b.fld_id 
				                WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."' 
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_schedule_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_schedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
								WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."' 
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_expschedule_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_expschedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
				WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                    
                                 UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_mission_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_mission_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
				WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                    
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_modexpschedule_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_modexpschedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
				WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."' AND b.fld_license_id='".$licenseid."'
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_dyad_schedule_studentmapping AS a 
								LEFT JOIN itc_class_dyad_schedulemaster AS b ON a.fld_schedule_id=b.fld_id WHERE a.fld_student_id='".$studentid[$i]."' 
                                AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_triad_schedule_studentmapping AS a 
								LEFT JOIN itc_class_triad_schedulemaster AS b ON a.fld_schedule_id=b.fld_id 
								WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                               UNION ALL 
                          SELECT COUNT(a.fld_id) AS cnt FROM itc_class_indassesment_student_mapping AS a 
							   LEFT JOIN itc_class_indassesment_master AS b ON a.fld_schedule_id=b.fld_id 
							   WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                           UNION ALL 
                                 SELECT COUNT(a.fld_id) AS cnt FROM itc_class_mission_student_mapping AS a 
							   LEFT JOIN itc_class_indasmission_master AS b ON a.fld_schedule_id=b.fld_id 
							   WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'                               
                                                           
                                 UNION ALL 
                               SELECT COUNT(a.fld_id) AS cnt FROM itc_class_exp_student_mapping AS a 
							   LEFT JOIN itc_class_indasexpedition_master AS b ON a.fld_schedule_id=b.fld_id 
							   WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                              UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_pdschedule_student_mapping AS a 
								LEFT JOIN itc_class_pdschedule_master AS b ON a.fld_pdschedule_id=b.fld_id 
								WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."') AS o");	
					
					if($studentcount==0){
						$add++;
						$ObjDB->NonQuery("UPDATE itc_license_assign_student SET fld_flag='0',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' WHERE fld_license_id='".$licenseid."' AND fld_student_id='".$studentid[$i]."' ");
					}
				
			}

		}
                else if($type==15)
		{
			$ObjDB->NonQuery("UPDATE itc_class_indasexpedition_master SET fld_delstatus='1',fld_deletedby='".$uid."',fld_deleted_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$scheduleid."'");

			$add=0;

			
			$licenseid = $ObjDB->SelectSingleValueInt("SELECT fld_license_id FROM itc_class_indasexpedition_master WHERE fld_id='".$scheduleid."'");
			
			$qry = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_student_id SEPARATOR',') FROM itc_class_exp_student_mapping WHERE fld_schedule_id='".$scheduleid."' AND fld_flag='1'");
			
			$studentid = explode(',',$qry);
			
			for($i=0;$i<sizeof($studentid);$i++)
			{
				$studentcount = $ObjDB->SelectSingleValueInt("SELECT SUM(o.cnt) FROM (SELECT COUNT(a.fld_id) AS cnt FROM itc_class_sigmath_student_mapping AS a                                 LEFT JOIN itc_class_sigmath_master AS b ON a.fld_sigmath_id=b.fld_id 
				                WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."' 
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_schedule_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_schedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
								WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."' 
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_expschedule_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_expschedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
				WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                
                                 UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_mission_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_mission_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
				WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                    
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_modexpschedule_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_modexpschedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
				WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."' AND b.fld_license_id='".$licenseid."'
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_dyad_schedule_studentmapping AS a 
								LEFT JOIN itc_class_dyad_schedulemaster AS b ON a.fld_schedule_id=b.fld_id WHERE a.fld_student_id='".$studentid[$i]."' 
                                AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_triad_schedule_studentmapping AS a 
								LEFT JOIN itc_class_triad_schedulemaster AS b ON a.fld_schedule_id=b.fld_id 
								WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                UNION ALL 
                          SELECT COUNT(a.fld_id) AS cnt FROM itc_class_indassesment_student_mapping AS a 
							   LEFT JOIN itc_class_indassesment_master AS b ON a.fld_schedule_id=b.fld_id 
							   WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                           UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_mission_student_mapping AS a 
							   LEFT JOIN itc_class_indasmission_master AS b ON a.fld_schedule_id=b.fld_id 
							   WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'                           
                                UNION ALL 
                               SELECT COUNT(a.fld_id) AS cnt FROM itc_class_exp_student_mapping AS a 
							   LEFT JOIN itc_class_indasexpedition_master AS b ON a.fld_schedule_id=b.fld_id 
							   WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                            UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_pdschedule_student_mapping AS a 
								LEFT JOIN itc_class_pdschedule_master AS b ON a.fld_pdschedule_id=b.fld_id 
								WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."') AS o");
					
					if($studentcount==0){
						$add++;
						$ObjDB->NonQuery("UPDATE itc_license_assign_student SET fld_flag='0',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' WHERE fld_license_id='".$licenseid."' AND fld_student_id='".$studentid[$i]."' ");
					}
				
			}
        $ObjDB->NonQuery("UPDATE itc_class_expmis_rubricmaster SET fld_delstatus='1',fld_deleted_by='".$uid."',fld_deleted_date='".date("Y-m-d H:i:s")."' WHERE fld_schedule_id='".$scheduleid."'");
        $ObjDB->NonQuery("UPDATE itc_exp_rubric_rpt SET fld_delstatus='1',fld_deleted_by='".$uid."',fld_deleted_date='".date("Y-m-d H:i:s")."' WHERE fld_schedule_id='".$scheduleid."'");
        $ObjDB->NonQuery("UPDATE itc_expsch_rubric_rpt SET fld_delstatus='1',fld_deleted_by='".$uid."',fld_deleted_date='".date("Y-m-d H:i:s")."' WHERE fld_schedule_id='".$scheduleid."'");

		}
                else if($type==16)
		{
			$ObjDB->NonQuery("UPDATE itc_class_pdschedule_master SET fld_delstatus='1',fld_deletedby='".$uid."',fld_deleted_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$scheduleid."'");
			
			$add=0;
			
			$licenseid = $ObjDB->SelectSingleValueInt("SELECT fld_license_id FROM itc_class_pdschedule_master WHERE fld_id='".$scheduleid."'");
			
			$qry = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_student_id SEPARATOR',') FROM itc_class_pdschedule_student_mapping WHERE fld_schedule_id='".$scheduleid."' AND fld_flag='1'");
			
			$studentid = explode(',',$qry);
			
			for($i=0;$i<sizeof($studentid);$i++)
			{
				$studentcount = $ObjDB->SelectSingleValueInt("SELECT SUM(o.cnt) FROM (SELECT COUNT(a.fld_id) AS cnt FROM itc_class_sigmath_student_mapping AS a                                 LEFT JOIN itc_class_sigmath_master AS b ON a.fld_sigmath_id=b.fld_id 
				                WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."' 
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_schedule_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_schedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
								WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."' 
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_expschedule_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_expschedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
				WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                    
                                 UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_mission_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_mission_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
				WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                    
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_modexpschedule_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_modexpschedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
				WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."' AND b.fld_license_id='".$licenseid."'
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_dyad_schedule_studentmapping AS a 
								LEFT JOIN itc_class_dyad_schedulemaster AS b ON a.fld_schedule_id=b.fld_id WHERE a.fld_student_id='".$studentid[$i]."' 
                                AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_triad_schedule_studentmapping AS a 
								LEFT JOIN itc_class_triad_schedulemaster AS b ON a.fld_schedule_id=b.fld_id 
								WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                UNION ALL 
                          SELECT COUNT(a.fld_id) AS cnt FROM itc_class_indassesment_student_mapping AS a 
							   LEFT JOIN itc_class_indassesment_master AS b ON a.fld_schedule_id=b.fld_id 
							   WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                           UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_mission_student_mapping AS a 
							   LEFT JOIN itc_class_indasmission_master AS b ON a.fld_schedule_id=b.fld_id 
							   WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'                           
                                UNION ALL 
                               SELECT COUNT(a.fld_id) AS cnt FROM itc_class_exp_student_mapping AS a 
							   LEFT JOIN itc_class_indasexpedition_master AS b ON a.fld_schedule_id=b.fld_id 
							   WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                            UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_pdschedule_student_mapping AS a 
								LEFT JOIN itc_class_pdschedule_master AS b ON a.fld_pdschedule_id=b.fld_id 
								WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."') AS o");
					
					if($studentcount==0){
						$add++;
						$ObjDB->NonQuery("UPDATE itc_license_assign_student SET fld_flag='0',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' WHERE fld_license_id='".$licenseid."' AND fld_student_id='".$studentid[$i]."' ");
					}
				
			}
		}
                else if($type==18)
		{
			$ObjDB->NonQuery("UPDATE itc_class_indasmission_master SET fld_delstatus='1',fld_deletedby='".$uid."',fld_deleted_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$scheduleid."'");

			$add=0;
			
			$licenseid = $ObjDB->SelectSingleValueInt("SELECT fld_license_id FROM itc_class_indasmission_master WHERE fld_id='".$scheduleid."'");
			
			$qry = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_student_id SEPARATOR',') FROM itc_class_mission_student_mapping WHERE fld_schedule_id='".$scheduleid."' AND fld_flag='1'");
			
			$studentid = explode(',',$qry);
			
			for($i=0;$i<sizeof($studentid);$i++)
			{
				$studentcount = $ObjDB->SelectSingleValueInt("SELECT SUM(o.cnt) FROM (SELECT COUNT(a.fld_id) AS cnt FROM itc_class_sigmath_student_mapping AS a                            
                                    LEFT JOIN itc_class_sigmath_master AS b ON a.fld_sigmath_id=b.fld_id 
				                WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."' 
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_schedule_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_schedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
								WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."' 
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_expschedule_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_expschedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
				WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                    
                                 UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_mission_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_mission_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
				WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                    
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_modexpschedule_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_modexpschedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
				WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."' AND b.fld_license_id='".$licenseid."'
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_dyad_schedule_studentmapping AS a 
								LEFT JOIN itc_class_dyad_schedulemaster AS b ON a.fld_schedule_id=b.fld_id WHERE a.fld_student_id='".$studentid[$i]."' 
                                AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_triad_schedule_studentmapping AS a 
								LEFT JOIN itc_class_triad_schedulemaster AS b ON a.fld_schedule_id=b.fld_id 
								WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_indassesment_student_mapping AS a 
							   LEFT JOIN itc_class_indassesment_master AS b ON a.fld_schedule_id=b.fld_id 
							   WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                                               
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_mission_student_mapping AS a 
							   LEFT JOIN itc_class_indasmission_master AS b ON a.fld_schedule_id=b.fld_id 
							   WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'                           
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_exp_student_mapping AS a 
							   LEFT JOIN itc_class_indasexpedition_master AS b ON a.fld_schedule_id=b.fld_id 
							   WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_pdschedule_student_mapping AS a 
								LEFT JOIN itc_class_pdschedule_master AS b ON a.fld_pdschedule_id=b.fld_id 
								WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."') AS o");
					
					if($studentcount==0){
						$add++;
						$ObjDB->NonQuery("UPDATE itc_license_assign_student SET fld_flag='0',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' WHERE fld_license_id='".$licenseid."' AND fld_student_id='".$studentid[$i]."' ");
					}
				
			}
$ObjDB->NonQuery("UPDATE itc_class_expmis_rubricmaster SET fld_delstatus='1',fld_deleted_by='".$uid."',fld_deleted_date='".date("Y-m-d H:i:s")."' WHERE fld_schedule_id='".$scheduleid."'");
			        $ObjDB->NonQuery("UPDATE itc_mis_rubric_rpt SET fld_delstatus='1',fld_deleted_by='".$uid."',fld_deleted_date='".date("Y-m-d H:i:s")."' WHERE fld_schedule_id='".$scheduleid."'");
        $ObjDB->NonQuery("UPDATE itc_missch_rubric_rpt SET fld_delstatus='1',fld_deleted_by='".$uid."',fld_deleted_date='".date("Y-m-d H:i:s")."' WHERE fld_schedule_id='".$scheduleid."'");

		}
		else
		{
			$ObjDB->NonQuery("UPDATE itc_class_triad_schedulemaster SET fld_delstatus='1',fld_deletedby='".$uid."',fld_deleted_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$scheduleid."'");
			
			$add=0;
			
			$licenseid = $ObjDB->SelectSingleValueInt("SELECT fld_license_id FROM itc_class_rotation_schedule_mastertemp WHERE fld_id='".$scheduleid."'");
			
			$qry = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_student_id SEPARATOR',') FROM itc_class_rotation_schedule_student_mappingtemp WHERE fld_schedule_id='".$scheduleid."' AND fld_flag='1'");
			
			$studentid = explode(',',$qry);
			
			for($i=0;$i<sizeof($studentid);$i++)
			{
				$studentcount = $ObjDB->SelectSingleValueInt("SELECT SUM(o.cnt) FROM (SELECT COUNT(a.fld_id) AS cnt FROM itc_class_sigmath_student_mapping AS a                                LEFT JOIN itc_class_sigmath_master AS b ON a.fld_sigmath_id=b.fld_id 
				                WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."' 
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_schedule_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_schedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
								WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_expschedule_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_expschedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
				WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                
                                 UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_mission_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_mission_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
				WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                    
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_modexpschedule_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_modexpschedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
				WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."' AND b.fld_license_id='".$licenseid."'
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_dyad_schedule_studentmapping AS a 
								LEFT JOIN itc_class_dyad_schedulemaster AS b ON a.fld_schedule_id=b.fld_id 
								WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'  
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_triad_schedule_studentmapping AS a
								LEFT JOIN itc_class_triad_schedulemaster AS b ON a.fld_schedule_id=b.fld_id 
		  WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."' AND a.fld_schedule_id<>'".$scheduleid."'
                      
                                UNION ALL 
                          SELECT COUNT(a.fld_id) AS cnt FROM itc_class_indassesment_student_mapping AS a 
							   LEFT JOIN itc_class_indassesment_master AS b ON a.fld_schedule_id=b.fld_id 
							   WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                           UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_mission_student_mapping AS a 
							   LEFT JOIN itc_class_indasmission_master AS b ON a.fld_schedule_id=b.fld_id 
							   WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'                            
                                UNION ALL 
                               SELECT COUNT(a.fld_id) AS cnt FROM itc_class_exp_student_mapping AS a 
							   LEFT JOIN itc_class_indasexpedition_master AS b ON a.fld_schedule_id=b.fld_id 
							   WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                           UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_pdschedule_student_mapping AS a 
								LEFT JOIN itc_class_pdschedule_master AS b ON a.fld_pdschedule_id=b.fld_id 
								WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."') AS o");
					
					if($studentcount==0){
						$add++;
						$ObjDB->NonQuery("UPDATE itc_license_assign_student SET fld_flag='0',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' WHERE fld_license_id='".$licenseid."' AND fld_student_id='".$studentid[$i]."' ");
					}
				
			}
		}
		$ObjDB->NonQuery("UPDATE itc_class_expmis_rubricmaster SET fld_delstatus='1',fld_deleted_by='".$uid."',fld_deleted_date='".date("Y-m-d H:i:s")."' WHERE fld_schedule_id='".$scheduleid."'");
        $ObjDB->NonQuery("UPDATE itc_exp_rubric_rpt SET fld_delstatus='1',fld_deleted_by='".$uid."',fld_deleted_date='".date("Y-m-d H:i:s")."' WHERE fld_schedule_id='".$scheduleid."'");
        $ObjDB->NonQuery("UPDATE itc_expsch_rubric_rpt SET fld_delstatus='1',fld_deleted_by='".$uid."',fld_deleted_date='".date("Y-m-d H:i:s")."' WHERE fld_schedule_id='".$scheduleid."'");
        $ObjDB->NonQuery("UPDATE itc_mis_rubric_rpt SET fld_delstatus='1',fld_deleted_by='".$uid."',fld_deleted_date='".date("Y-m-d H:i:s")."' WHERE fld_schedule_id='".$scheduleid."'");
        $ObjDB->NonQuery("UPDATE itc_missch_rubric_rpt SET fld_delstatus='1',fld_deleted_by='".$uid."',fld_deleted_date='".date("Y-m-d H:i:s")."' WHERE fld_schedule_id='".$scheduleid."'");

	}
	
	
	
	/*--- Check student score  ---*/
	if($oper=="checkstudentscore" and $oper!='')
	{
		$studentid = isset($method['studentid']) ? $method['studentid'] : '0';
		$classid = isset($method['classid']) ? $method['classid'] : '0';
		$scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '0';
		$moduleid = isset($method['moduleid']) ? $method['moduleid'] : '0';
                $scheduletype=isset($method['scheduletype']) ? $method['scheduletype'] : '0';
                $modtype=isset($method['modtype']) ? $method['modtype'] : '0';
		
                if($scheduletype==2)
                {
                    $count=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_module_play_track WHERE fld_tester_id='".$studentid."'  and fld_schedule_id='".$scheduleid."' and fld_module_id='".$moduleid."' and fld_delstatus='0' and fld_schedule_type='1'");
                }
                else if($scheduletype==6)
                {
                    $count=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_module_play_track WHERE fld_tester_id='".$studentid."'  and fld_schedule_id='".$scheduleid."' and fld_module_id='".$moduleid."' and fld_delstatus='0' and fld_schedule_type='4'");
                }
                else if($scheduletype==19)
                {
                    if($modtype==2)
                    {
                         $count=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_module_play_track WHERE fld_tester_id='".$studentid."'  and fld_schedule_id='".$scheduleid."' and fld_module_id='".$moduleid."' and fld_delstatus='0' and fld_schedule_type='21'");
                    }
		
                    if($modtype==4)
                    {
                         $count=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_exp_dest_play_track WHERE fld_student_id='".$studentid."'  and fld_schedule_id='".$scheduleid."' and fld_exp_id='".$moduleid."' and fld_delstatus='0' and fld_schedule_type='20'");
                    }
                    
                }
                else if($scheduletype==17)
                {
                    $count=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_exp_dest_play_track WHERE fld_student_id='".$studentid."'  and fld_schedule_id='".$scheduleid."' and fld_exp_id='".$moduleid."' and fld_delstatus='0' and fld_schedule_type='19'");
                }
		else if($scheduletype==20)
                {
                    $count=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_mis_dest_play_track WHERE fld_student_id='".$studentid."'  and fld_schedule_id='".$scheduleid."' and fld_mis_id='".$moduleid."' and fld_delstatus='0' and fld_schedule_type='23'");
                }
                
                
                
		if($count=='0')
		{
			echo "fail";
		}
		else
		{
			echo "exist";
		}
	}

	if($oper=="rotloadcontent" and $oper != " ")
	{
		$sid = isset($method['sid']) ? $method['sid'] : '0';		
		$licenseid = isset($method['lid']) ? $method['lid'] : '0';
		$classid = isset($method['classid']) ? $method['classid'] : '';
		$mtype = isset($method['type']) ? $method['type'] : '0';
		$flag=0;
// Checking to module template type
		if($mtype == 0) {
		      $mtype=1;
		      $assigntype = 0;
		    }
		    else
		    {
		      $assigntype = 1;
		    }
		
		
	
		$qryschdet=$ObjDB->NonQuery("SELECT COUNT(b.fld_id) AS countschedulestumap,a.fld_flag as flag,a.fld_moduletype AS moduletype,a.fld_schedule_name                                    AS schedulename,a.fld_startdate AS startdate,a.fld_numberofcopies AS numberofcopies,a.fld_numberofrotations AS                                    numberofrotations,a.fld_rotationlength AS rotationlength
                                    FROM itc_class_rotation_schedule_mastertemp AS a
                                    LEFT JOIN itc_class_rotation_schedule_student_mappingtemp AS b ON b.fld_schedule_id=a.fld_id
                                    WHERE a.fld_id='".$sid."' AND b.fld_flag='1'");
									
		if($qryschdet->num_rows>0)
		{
		$row=$qryschdet->fetch_assoc();
		extract($row);
			if($flag==0)
			{
				$count=0;
			}
			else
			{
				$count=1;
			}
		}
		else
		{
			$count=0;
			$countschedulestumap=0;
		}
		
		if($count==0)
		{
			$type="create";
		}
		else
		{
			$type="update";
		}
		
	if($countschedulestumap==0)
	{
		$countstudent=$ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_class_student_mapping WHERE fld_class_id='".$classid."' AND fld_flag=1");
	}
	else
	{
		$countstudent=$countschedulestumap;
	}
	
	if($moduletype==1)
	{
		$modulename="Module";
	}
	else
	{
		$modulename="Math Module";
	}
	
	if($sid==0 or $flag==0)
	{
		$value="Next";
	}
	else
	{
		if($assigntype == 0)
		   $value="Next";
		else
		$value="View Schedule";
	}

		
?>
				<form id="sform">
					<div class='row'>
							<div class='four columns'>
                            	Number of copies<span class="fldreq">*</span>
                                    <dl class='field row'>
                                        <dt class='text'>
                                              <input placeholder='number of copies' required='' type='text' id="numberofcopies" name="numberofcopies" value="<?php if($numberofcopies!=''){ echo $numberofcopies; }else { echo "1";}?>" <?php if($count==1){?> readonly title="Read only" <?php }?> onkeypress="return isNumberKey(event);">
                                        </dt>                                        
                                    </dl>
                                </div>
                                <div class='four columns'>
                                	 Number of rotations<span class="fldreq">*</span>
                                    <dl class='field row'>
                                        <dt class='text'>
                                          <input placeholder='number of rotations' required='' type='text' id="numberofrotations" name="numberofrotations" value="<?php if($numberofrotations>0){ echo $numberofrotations ;} else { echo "1";} ?>" onkeypress="return isNumberKey(event);" <?php if($count==1){?> readonly title="Read only" <?php }?>>
                                        </dt>                                        
                                    </dl>
                                </div>
                                <div class='four columns'>
                                	Rotation  length<span class="fldreq">*</span>
                                    <dl class='field row'>
                                        <dt class='text'>
                                            <input placeholder='rotation length' required='' type='text' id="rotationlength" name="rotationlength" value="<?php if($rotationlength!=''){echo $rotationlength;}else {if($mtype==1){echo "7";}else{echo "9";}}?>" onkeypress="return isNumberKey(event);" <?php if($count==1){?> readonly title="Read only" <?php }?>>
                                        </dt>                                        
                                    </dl>
                                </div>
                            </div>
                         </form>
					
							
                            <script>fn_loadmodules(<?php echo $sid.",".$mtype.",".$assigntype; ?>);</script>
                           
                            <div id="modules"> 
                                                       
                            </div> 
                            
                            <div>
                            <input type="checkbox">Select Block Module 
                            <script>
                                    $(document).ready(function(){
                                
                                         $('input[type="checkbox"]').click(function(){

                                                 if($(this).prop("checked") == true){
                                                           
                                                            $('#blockmodule').show();
                                                            $('#blockstudent').show();
                                                            $('#modstublock').show();
                                                            $('#extenddiv').show();
                                                            
                                                            
                                                         }
            
                                                 else if($(this).prop("checked") == false){
                                                              
                                                             $('#blockmodule').hide();
                                                             $('#blockstudent').hide();
                                                             $('#modstublock').hide();
                                                             $('#extenddiv').hide();
                                                             
                                                          }

                                                        });

                                                     });
                            </script>
            
                            </div>
                            
                                
                            <div id="blockmodule" style="display:none;">
                            </div>
                            
                           
                            <div id="blockstudent" style="display:none" >
                                
                            </div>
                           
                           
                             <div class='row rowspacer'>
                                    <div  id="modstublock" style="float:right;display:none">
                                        <input type="button" id="modstublock" class="darkButton" value="Save Block Module" onclick="fn_blockmodstudent();" /> 
                                    </div>
                    		</div>
                           
                             <div class='row rowspacer'>
                                    <div id="extenddiv" style="float:left;"> <!-- extend content -->
                                       Extend content of the modules / math modules in your class
                                    </div>
                                    <div id="extendbtn"   style="float:right;">
                                        <input type="button" id="extendbtn" class="darkButton" value="Extend Content" onclick="fn_rotloadextendcontent(<?php echo $sid.",".$licenseid.",";?>'exc');" /> 
                                    </div>
                    		</div>
                            
                            <div id="extendcontent"  class='row rowspacer'>
                            </div>                                                           
                                       
							<div class="row rowspacer" style="margin-top:20px;">
                                <div class="tLeft" style="color:#F00;">
                                </div>
                                <div class="tRight" id="modnxtstep" style="display:none;">
                                    <input type="button" class="darkButton" id="btnstep" style="width:200px; height:42px;float:right;" value="<?php echo $value;?>" onClick="fn_saverotationalschedule(0);" />
                                </div>
                            </div>
                            <?php
		                    if($assigntype == 0) {
		                      $sid = 0;
		                      $type="create";
		                    }
                            ?>
                             <input type="hidden" id="scount" value="<?php echo $countstudent; ?>"/>
                             <input type="hidden" id="rotationtype" value="<?php echo $type; ?>"/>
                             <input type="hidden" id="scheduleid" value="<?php echo $sid; ?>"/>
                             
				  

 <script type="text/javascript" language="javascript">
       
		$(function(){
            $("#sform").validate({
                	ignore: "",
					errorElement: "dd",
					errorPlacement: function(error, element) {
						$(element).parents('dl').addClass('error');
						error.appendTo($(element).parents('dl'));
						error.addClass('msg'); 		
				},
                rules: { 
					numberofcopies: { required: true },
					numberofrotations: { required: true },
					rotationlength: { required: true }
				}, 
                messages: { 	  
					numberofcopies:{ required:  "please enter number of copies" },
					numberofrotations: {   required: "Enter number of rotations" },
					rotationlength: {   required: "please enter rotation length" }
					
					
					
                },
                highlight: function(element, errorClass, validClass) {
					$(element).parent('dl').addClass(errorClass);
					$(element).addClass(errorClass).removeClass(validClass);
				},
				unhighlight: function(element, errorClass, validClass) {
					if($(element).attr('class') == 'error'){
							$(element).parents('dl').removeClass(errorClass);
							$(element).removeClass(errorClass).addClass(validClass);
					}
				},
                onkeyup: false,
                onblur: true
                });
                });	
				</script>	
<?php
	}
		
	if($oper == "saveschedule" and $oper != '')
	{		
		try
		{
		$classid = isset($method['classid']) ? $method['classid'] : '0';
		$sid = isset($method['sid']) ? $method['sid'] : '0';
		$sname = isset($method['sname']) ? $method['sname'] : '0';
		$startdate = isset($method['startdate']) ? $method['startdate'] : '0';
		$enddate = isset($method['enddate']) ? $method['enddate'] : '0';
		$scheduletype = isset($method['scheduletype']) ? $method['scheduletype'] : '0';
		$students = isset($method['students']) ? $method['students'] : '0';
		$unstudents = isset($method['unstudents']) ? $method['unstudents'] : '0';
		$studenttype = isset($method['studenttype']) ? $method['studenttype'] : '0';
		$numberofcopies = isset($method['numberofcopies']) ? $method['numberofcopies'] : '0';
		$numberofrotations = isset($method['numberofrotations']) ? $method['numberofrotations'] : '0';
		$rotationlength = isset($method['rotationlength']) ? $method['rotationlength'] : '0';
		$licenseid = isset($method['licenseid']) ? $method['licenseid'] : '0';
		$modules = isset($method['modules']) ? $method['modules'] : '0';
		$moduletype = isset($method['moduletype']) ? $method['moduletype'] : '0';
		$extid = isset($method['extids']) ? $method['extids'] : '0';
                $blockmodule = isset($method['blockmodule']) ? $method['blockmodule'] : '0';
                $blockstudents = isset($method['blockstudents']) ? $method['blockstudents'] : '0';
		
                $modids = isset($method['modids']) ? $method['modids'] : '0'; /***********Mohan M Updated by one or more Extend Content option code start here*********/
		$selectallmodids = isset($method['selectallmodids']) ? $method['selectallmodids'] : '0'; /***********Mohan M Updated by one or more Extend Content option code start here*********/
                
                
                if($blockmodule=='')
                {
                    $blockmodule='0';
                }
		
		$students = explode(',',$students);
		$modules = explode(',',$modules);
		$unstudents = explode(',',$unstudents);
		$extid = explode(',',$extid);
                $blockstudents = explode(',',$blockstudents);
		
                $modids = explode(',',$modids); /***********Mohan M Updated by [11-8-2015] one or more Extend Content option code start here*********/
                $selectallmodids = explode('~',$selectallmodids); /***********Mohan M Updated by [11-8-2015] one or more Extend Content option code start here*********/
                
	   $validate_schname=true;
	   $validate_schid=true;
	   $validate_date=true;
	
		if($sid!=0)
		{
			$validate_schid=validate_datatype($sid,'int');
			$validate_schname=validate_datas($sname,'lettersonly'); 
			$validate_date=validate_datas($startdate,'dateformat'); 
		}
		
		if($validate_schid and $validate_schname and $validate_date)
		{
		
		$remainusers = $ObjDB->SelectSingleValueInt("SELECT fld_remain_users FROM itc_license_track WHERE fld_school_id='".$schoolid."' and fld_license_id='".$licenseid."' and fld_delstatus='0' and fld_user_id='".$indid."' AND '".date("Y-m-d")."' BETWEEN fld_start_date AND fld_end_date");
		
		
		
		if($studenttype==1){
			/*---------checking the license for student----------------------*/				
			$count=0;
			$qry = $ObjDB->QueryObject("SELECT fld_student_id FROM itc_class_student_mapping WHERE fld_class_id='".$classid."' and fld_flag='1'");
			if($qry->num_rows>0){
				$students=array();
				while($res=$qry->fetch_assoc())
				{
					extract($res);
					$students[]=$fld_student_id;
					$check = $ObjDB->SelectSingleValueInt("SELECT COUNT(*) FROM itc_license_assign_student AS a 
					         LEFT JOIN itc_user_master AS b ON a.fld_student_id=b.fld_id 
							 WHERE a.fld_license_id='".$licenseid."' AND a.fld_student_id='".$fld_student_id."' AND a.fld_flag='1' AND b.fld_delstatus='0'");
					
					if($check==0)
					{
						$count++;
					}
				}
			}
		}
		else{
			$count=0;
			$add=0;			
			for($i=0;$i<sizeof($students);$i++)
			{
				$check = $ObjDB->SelectSingleValueInt("SELECT COUNT(*) FROM itc_license_assign_student AS a 
				         LEFT JOIN itc_user_master AS b ON a.fld_student_id=b.fld_id 
						 WHERE a.fld_license_id='".$licenseid."' AND a.fld_student_id='".$students[$i]."' AND a.fld_flag='1' AND b.fld_delstatus='0'");
				
				if($check==0)
				{
					$count++;
				}
			}
			
			$remainusers = $ObjDB->SelectSingleValueInt("SELECT fld_remain_users FROM itc_license_track WHERE fld_school_id='".$schoolid."' and fld_license_id='".$licenseid."' and fld_delstatus='0' and fld_user_id='".$indid."' AND '".date("Y-m-d")."' BETWEEN fld_start_date AND fld_end_date");
			
			for($i=0;$i<sizeof($unstudents);$i++)
			{
				$check = $ObjDB->SelectSingleValueInt("SELECT count(*) FROM itc_license_assign_student WHERE fld_license_id='".$licenseid."' and fld_student_id='".$unstudents[$i]."' and fld_flag='1'");
				
				if($check>0)
				{
				$studentcount = $ObjDB->SelectSingleValueInt("SELECT SUM(o.cnt) FROM (SELECT COUNT(a.fld_id) AS cnt FROM itc_class_sigmath_student_mapping AS a                                 LEFT JOIN itc_class_sigmath_master AS b ON a.fld_sigmath_id=b.fld_id 
				                WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_schedule_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_schedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
					   WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."' AND a.fld_schedule_id<>'".$sid."'
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_dyad_schedule_studentmapping AS a 
								LEFT JOIN itc_class_dyad_schedulemaster AS b ON a.fld_schedule_id=b.fld_id 
								WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_expschedule_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_expschedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
								WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                 UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_mission_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_mission_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
								WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_modexpschedule_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_modexpschedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
								WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_triad_schedule_studentmapping AS a 
								LEFT JOIN itc_class_triad_schedulemaster AS b ON a.fld_schedule_id=b.fld_id 
								WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_indassesment_student_mapping AS a 
								LEFT JOIN itc_class_indassesment_master AS b ON a.fld_schedule_id=b.fld_id 
								WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_exp_student_mapping AS a 
								LEFT JOIN itc_class_indasexpedition_master AS b ON a.fld_schedule_id=b.fld_id 
								WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_pdschedule_student_mapping AS a 
								LEFT JOIN itc_class_pdschedule_master AS b ON a.fld_pdschedule_id=b.fld_id 
								WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_mission_student_mapping AS a 
								LEFT JOIN itc_class_mission_schedule_master AS b ON a.fld_schedule_id=b.fld_id 
								WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."') AS o");
					
					$ObjDB->NonQuery("UPDATE itc_class_rotation_schedule_student_mappingtemp SET fld_flag='0',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$unstudents[$i]."'");					
					
					
					if($studentcount==0){
						$add++;
						$ObjDB->NonQuery("UPDATE itc_license_assign_student SET fld_flag='0',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' WHERE fld_license_id='".$licenseid."' AND fld_student_id='".$unstudents[$i]."' ");
					}
				}
			}
		}
		
		$assignedstudents = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_license_assign_student WHERE fld_license_id='".$licenseid."' AND fld_flag='1' AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."'");
		
		$totalusers = $ObjDB->SelectSingleValueInt("SELECT fld_no_of_users FROM itc_license_track WHERE fld_school_id='".$schoolid."' and fld_license_id='".$licenseid."' and fld_delstatus='0' and fld_user_id='".$indid."' AND '".date("Y-m-d")."' BETWEEN fld_start_date AND fld_end_date");
		
		$totalremain = $remainusers-$count;
		if($totalusers>=($assignedstudents+$count)){
			$flag=1;
		}		
		else{	
			$flag=0;
		}
		
		if($flag==1) //if student user availale for license
		{ 
			if($sid!=0)
			{
				$ObjDB->NonQuery("UPDATE itc_class_rotation_schedule_mastertemp SET fld_schedule_name='".$sname."',fld_student_type='".$studenttype."',fld_startdate='".date("Y-m-d",strtotime($startdate))."',fld_rotationlength='".$rotationlength."',fld_numberofcopies='".$numberofcopies."',fld_numberofrotations='".$numberofrotations."',fld_updatedby='".$uid."',fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$sid."'");
			}
			else
			{
				
				$ObjDB->NonQuery("insert into itc_class_rotation_schedule_mastertemp (fld_class_id,fld_license_id,fld_schedule_name,fld_moduletype,fld_scheduletype,fld_student_type,fld_startdate,fld_numberofcopies,fld_numberofrotations,fld_rotationlength,fld_created_date,fld_createdby) values('".$classid."','".$licenseid."','".$sname."','".$moduletype."','".$scheduletype."','".$studenttype."','".date("Y-m-d",strtotime($startdate))."','".$numberofcopies."','".$numberofrotations."','".$rotationlength."','".date("Y-m-d H:i:s")."','".$uid."')");
				
				$sid=$ObjDB->SelectSingleValueInt("SELECT MAX(fld_id) FROM itc_class_rotation_schedule_mastertemp");
			}
			
			
                        
                     /* Block student mapping start */
                        
                            
                            
                        if($blockstudents[0]>0)
                            {
                            
                            $blockmod=explode('-',$blockmodule);
                
                            $blockmodule=$blockmod[0];
                            $modtype=$blockmod[1];
                            
                             $ObjDB->NonQuery("UPDATE itc_class_rotation_blockstudent SET fld_flag='0',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' WHERE fld_scheduleid='".$sid."' AND fld_moduletype='".$modtype."' AND fld_moduleid='".$blockmodule."'");
                             
                            for($i=0;$i<sizeof($blockstudents);$i++)
                            {
                                
                                    $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_class_rotation_blockstudent WHERE fld_scheduleid='".$sid."' AND  fld_moduleid='".$blockmodule."' AND fld_moduletype='".$modtype."'  AND fld_studentid='".$blockstudents[$i]."'");
				if($cnt==0)
				{
                                   
                                            $ObjDB->NonQuery("INSERT INTO itc_class_rotation_blockstudent(fld_classid,fld_scheduleid,fld_moduleid,fld_moduletype,fld_studentid,fld_flag,fld_createddate,fld_createdby) VALUES ('".$classid."','".$sid."','".$blockmodule."','".$modtype."','".$blockstudents[$i]."','1','".date('Y-m-d H:i:s')."','".$uid."')");
				}
				else
				{
                                            $ObjDB->NonQuery("UPDATE itc_class_rotation_blockstudent SET fld_moduletype='".$modtype."',fld_flag='1',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' WHERE fld_scheduleid='".$sid."' AND fld_studentid='".$blockstudents[$i]."' AND fld_id='".$cnt."'");
				}
                        }
                        }
				
				
			/* Block student mapping end */
			
			/* Schedule Module Mapping */
			
			$ObjDB->NonQuery("UPDATE itc_class_rotation_schedule_module_mappingtemp SET fld_flag='0',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."'");
			
			for($i=0;$i<sizeof($modules);$i++)
			{
				$permodules=explode("-",$modules[$i]);
				
				$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_class_rotation_schedule_module_mappingtemp WHERE fld_schedule_id='".$sid."' AND fld_module_id='".$permodules[0]."' and fld_type='".$permodules[1]."'");
				if($cnt==0)
				{
					$ObjDB->NonQuery("INSERT INTO itc_class_rotation_schedule_module_mappingtemp(fld_schedule_id,fld_module_id,fld_type,fld_flag,fld_createddate,fld_createdby) VALUES ('".$sid."', '".$permodules[0]."','".$permodules[1]."','1','".date('Y-m-d H:i:s')."','".$uid."')");
				}
				else
				{
					$ObjDB->NonQuery("UPDATE itc_class_rotation_schedule_module_mappingtemp SET fld_flag='1',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."' AND fld_module_id='".$permodules[0]."' AND fld_type='".$permodules[1]."' AND fld_id='".$cnt."'");
				}
			}
			
			/* Schedule Module Mapping End */
			
			/* Schedule Student Mapping */
			
			$ObjDB->NonQuery("UPDATE itc_class_rotation_schedule_student_mappingtemp SET fld_flag='0',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."'");
			
			for($i=0;$i<sizeof($students);$i++)
			{
				$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_class_rotation_schedule_student_mappingtemp WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$students[$i]."'");
				if($cnt==0)
				{
					$ObjDB->NonQuery("INSERT INTO itc_class_rotation_schedule_student_mappingtemp(fld_schedule_id, fld_student_id,fld_flag,fld_createddate,fld_createdby) VALUES ('".$sid."', '".$students[$i]."','1','".date('Y-m-d H:i:s')."','".$uid."')");
				}
				else
				{
					$ObjDB->NonQuery("UPDATE itc_class_rotation_schedule_student_mappingtemp SET fld_flag='1',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$students[$i]."' AND fld_id='".$cnt."'");
				}
				
				/* Schedule Student Mapping End */
				
				//tracing student
				$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_license_assign_student WHERE fld_license_id='".$licenseid."' AND fld_student_id='".$students[$i]."'");
				if($cnt==0)
				{
					$ObjDB->NonQuery("INSERT INTO itc_license_assign_student(fld_school_id, fld_license_id, fld_student_id, fld_flag,fld_created_date,fld_created_by) VALUES ('".$schoolid."', '".$licenseid."', '".$students[$i]."', '1','".date('Y-m-d H:i:s')."','".$uid."')");
				}
				else
				{
					$ObjDB->NonQuery("UPDATE itc_license_assign_student SET fld_flag='1',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' WHERE fld_license_id='".$licenseid."' AND fld_student_id='".$students[$i]."' AND fld_id='".$cnt."'");
				}
			}
			
			//extend insert/update
                        if($scheduletype!=2)
                        {
			$ObjDB->NonQuery("UPDATE itc_class_rotation_extcontent_mapping 
							 SET fld_active='0' 
							 WHERE fld_schedule_id='".$sid."'");
			if($extid[0] != '') {
				for($i=0;$i<sizeof($extid);$i++)
				{
					$templist = explode('~',$extid[$i]);
					if($templist[0]!='' and $templist[0]!=0){
						$cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
															FROM itc_class_rotation_extcontent_mapping 
															WHERE fld_schedule_id='".$sid."'  AND fld_ext_id='".$templist[0]."' AND fld_schedule_type='".$templist[1]."' 
															AND fld_module_id='".$templist[2]."'");
						if($cnt==0)
						{
							 $ObjDB->NonQuery("INSERT INTO itc_class_rotation_extcontent_mapping (fld_schedule_id,fld_ext_id,fld_active,fld_schedule_type,fld_module_id,fld_createdby,fld_createddate)
												VALUES('".$sid."','".$templist[0]."','1','".$templist[1]."','".$templist[2]."','".$uid."','".date("Y-m-d H:i:s")."')");
						}
						else
						{
							$ObjDB->NonQuery("UPDATE itc_class_rotation_extcontent_mapping 
												SET fld_active='1',fld_updatedby='".$uid."',fld_updateddate='".date("Y-m-d H:i:s")."' 
												WHERE fld_schedule_id='".$sid."' AND fld_ext_id='".$templist[0]."' AND fld_schedule_type='".$templist[1]."' AND fld_module_id='".$templist[2]."'");
						}
					}					
				}
			}
                        }
			
                         /***********Mohan M Updated by one or more Extend Content option code start here*********/
                        if($scheduletype==2)
                        {
                             $ObjDB->NonQuery("UPDATE itc_class_rotation_extcontent_mapping 
                                                         SET fld_active='0' 
                                                         WHERE fld_schedule_id='".$sid."'");
                            
                            if($modids[0] != '')
                            {
                                for($i=0;$i<sizeof($modids);$i++)
                                {
                                    $templistmod = explode('_',$modids[$i]);
                                   
                                    if($templistmod[0]!='' and $templistmod[0]!=0)
                                    {
                                        $cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
                                                                                        FROM itc_class_rotation_extcontent_mapping 
                                                                                        WHERE fld_schedule_id='".$sid."'  AND fld_ext_id='".$templistmod[0]."' AND fld_schedule_type='".$templistmod[1]."' 
                                                                                        AND fld_module_id='".$templistmod[2]."'");
                                        if($cnt==0)
                                        {
                                                 $ObjDB->NonQuery("INSERT INTO itc_class_rotation_extcontent_mapping (fld_schedule_id,fld_ext_id,fld_active,fld_schedule_type,fld_module_id,fld_createdby,fld_createddate)
                                                                                VALUES('".$sid."','".$templistmod[0]."','1','".$templistmod[1]."','".$templistmod[2]."','".$uid."','".date("Y-m-d H:i:s")."')");
                                        }
                                        else
                                        {
                                                $ObjDB->NonQuery("UPDATE itc_class_rotation_extcontent_mapping 
                                                                        SET fld_active='1',fld_updatedby='".$uid."',fld_updateddate='".date("Y-m-d H:i:s")."' ,fld_select_all='0'
                                                                        WHERE fld_schedule_id='".$sid."' AND fld_ext_id='".$templistmod[0]."' AND fld_schedule_type='".$templistmod[1]."' AND fld_module_id='".$templistmod[2]."'");
                                        }
                                    }					
                                }
                            }
                            
                          //  print_r($selectallmodids);
                           
                            if($selectallmodids[0] != '')
                            { /******Select All Extend Content******/
                               for($i=0;$i<(sizeof($selectallmodids)-1);$i++)
                                {
                                    $selectallmodids[$i] = ltrim($selectallmodids[$i],",");
                                    $templistmod = explode(',',$selectallmodids[$i]);
                                 

                                    $getcontent = $ObjDB->QueryObject("SELECT fld_id AS exid,fld_extend_text AS exname FROM itc_extendtext_master
                                                                            WHERE fld_module_id='".$templistmod[0]."' AND fld_school_id='".$schoolid."' AND fld_delstatus='0'
                                                                            UNION ALL
                                                                            SELECT a.fld_id AS exid,a.fld_extend_text AS exname FROM itc_extendtext_master AS a 
                                                                            LEFT JOIN itc_license_extcontent_mapping AS b ON a.fld_id=b.fld_ext_id WHERE 
                                                                            b.fld_license_id='".$templistmod[1]."' AND b.fld_module_id='".$templistmod[0]."' AND b.fld_type='1' 
                                                                            AND b.fld_active='1' AND a.fld_delstatus='0'");
                                    if($getcontent->num_rows>0)
                                    {
                                        while($res = $getcontent->fetch_assoc())
                                        {
                                            extract($res);

                                            $cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
                                                                                        FROM itc_class_rotation_extcontent_mapping 
                                                                                        WHERE fld_schedule_id='".$sid."'  AND fld_ext_id='".$exid."' AND fld_schedule_type='1' 
                                                                                        AND fld_module_id='".$templistmod[0]."'");
                                            if($cnt==0)
                                            {
                                                    $ObjDB->NonQuery("INSERT INTO itc_class_rotation_extcontent_mapping (fld_schedule_id,fld_ext_id,fld_active,fld_schedule_type,fld_module_id,fld_createdby,fld_createddate,fld_select_all)
                                                                                   VALUES('".$sid."','".$exid."','1','1','".$templistmod[0]."','".$uid."','".date("Y-m-d H:i:s")."','1')");
                                            }
                                            else
                                            {
                                                    $ObjDB->NonQuery("UPDATE itc_class_rotation_extcontent_mapping 
                                                                            SET fld_active='1',fld_updatedby='".$uid."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_select_all='1'
                                                                            WHERE fld_schedule_id='".$sid."' AND fld_ext_id='$exid' AND fld_schedule_type='1' AND fld_module_id='".$templistmod[0]."'");
                                            }

                                        }
                                    }
                                }
                            } /******Select All Extend Content******/
                       }
                        /***********Mohan M Updated by one or more Extend Content option code End here*********/
			
			$ObjDB->NonQuery("UPDATE itc_class_rotation_schedule_mastertemp SET fld_updated_date='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."',fld_license_id='".$licenseid."' WHERE fld_id='".$sid."'");
			
			echo "success~".$sid;
			
			send_notification($licenseid,$schoolid,$indid);
			
		}		
                else
                {
                    echo "exceed";
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

if($oper == "loadextendcontent" and $oper != ""){	

	$list4 = isset($method['list4']) ? $method['list4'] : '';
	$licenseid = isset($method['licenseid']) ? $method['licenseid'] : '';
	$sid = isset($method['scheduleid']) ? $method['scheduleid'] : '0';
	$list4=explode(",",$list4);
	$flag=0;	
	?>
    <div class='span10 offset1 <?php if($flag==1){?>dim<?php } ?>'>
        <table id="rotextendcontent" class='table table-hover table-striped table-bordered'>
            <thead class='tableHeadText'>
                <tr>
                    <th>Module name</th>
                    <th class='centerText'>Extend Content</th>                    
                </tr>
            </thead>
            <tbody>
                <?php 
				   if($list4[0] != '') {
					   $count=0;
						for($i=0;$i<sizeof($list4);$i++)
						{
							$templist = explode('-',$list4[$i]);
							$type=$templist[1];
							$moduleid = $templist[0];
							if($templist[1]==2){
								$moduleid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM `itc_mathmodule_master` WHERE fld_id='".$templist[0]."'");
								$modulename = $ObjDB->SelectSingleValue("SELECT fld_mathmodule_name FROM itc_mathmodule_master WHERE fld_id='".$moduleid."'");
							}
							else{								
								$modulename = $ObjDB->SelectSingleValue("SELECT fld_module_name FROM itc_module_master WHERE fld_id='".$moduleid."'");
							}
							
							$texname = "Select Extend Content";
							$extcount='0';
								if($type==1)
								{
									$tablename="itc_extendtext_master";
								}
								else 
								{
									$tablename="itc_extendtextmath_master";
								}
								
								$selectext=$ObjDB->QueryObject("SELECT b.fld_ext_id AS texid,a.fld_extend_text as textname,b.fld_select_all AS selectall FROM ".$tablename." AS a 
											 LEFT JOIN itc_class_rotation_extcontent_mapping AS b ON a.fld_id=b.fld_ext_id 
											 WHERE b.fld_schedule_id='".$sid."' AND b.fld_module_id='".$moduleid."' AND b.fld_schedule_type='".$type."' AND b.fld_active='1' AND a.fld_delstatus='0'");
											 
								$getcontent = $ObjDB->QueryObject("SELECT fld_id AS exid,fld_extend_text AS exname FROM ".$tablename." 
																  WHERE fld_module_id='".$moduleid."' AND fld_school_id='".$schoolid."' AND fld_delstatus='0'
																  UNION ALL
																  SELECT a.fld_id AS exid,a.fld_extend_text AS exname FROM ".$tablename." AS a 
																  LEFT JOIN itc_license_extcontent_mapping AS b ON a.fld_id=b.fld_ext_id WHERE 
																  b.fld_license_id='".$licenseid."' AND b.fld_module_id='".$moduleid."' AND b.fld_type='".$type."' 
																  AND b.fld_active='1' AND a.fld_delstatus='0'");
																  
																  
								if($selectext->num_rows>0){
                                                                    $cut=0;
                                                                    while($res = $selectext->fetch_assoc())
                                                                    {
									extract($res);
                                                                        if($selectall=='0')
                                                                        {
                                                                            if($cut=='0'){
                                                                                $texname=$textname;
								}
                                                                            else if($cut>='3'){
                                                                                 $texname=$texname."...";
                                                                            }
                                                                            else{
                                                                                $texname=$texname.",".$textname;
                                                                            }
                                                                            $cut++;
                                                                        }
                                                                        else{
                                                                            $texname='Select all';
                                                                        }
																	 
                                                                    }
								}
									
                                                                
																	 
									
								if($getcontent->num_rows>0)
								{
									$count++;
								?>
							<tr id="exc<?php echo $moduleid;?>">
                            	<td><?php echo $modulename; ?></td>
                                <td>									
                                    <div id="clspass">   
                                        <dl class='field row'>
                                            <div class="selectbox">
                                                <input type="hidden" name="exid_<?php echo $moduleid;?>" id="exid_<?php echo $moduleid;?>" value="<?php echo $texid."~".$templist[1]."~".$moduleid;?>">
                                                <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                                                    <span class="selectbox-option input-medium" data-option="" style="width:97%" ><div id="modname_<?php echo $moduleid;?>"><?php echo $texname;?></div></span>
                                                    <b class="caret1"></b>
                                                </a>
                                                <div class="selectbox-options">
                                                    <input type="text" class="selectbox-filter" placeholder="Search Class">
                                                    <ul role="options" style="width:100%">
                                                       <?php 
                                                                if($type==1)
                                                                { ?>                                                                   
                                                                <li><span onclick="fn_selectallmod(<?php echo $moduleid; ?>);">Select All</span>
                                                                    </li>
                                                                    <?php  
                                                                }
                                                                while($res = $getcontent->fetch_assoc())
                                                                {
                                                                    extract($res);
                                                                    if($type==1)
                                                                    { 
                                                                        $extcount = $ObjDB->SelectSingleValue("SELECT count(b.fld_ext_id) FROM itc_extendtext_master AS a 
											 LEFT JOIN itc_class_rotation_extcontent_mapping AS b ON a.fld_id=b.fld_ext_id 
											 WHERE b.fld_schedule_id='".$sid."' AND b.fld_module_id='".$moduleid."' AND b.fld_schedule_type='".$type."' AND fld_ext_id='".$exid."' AND b.fld_active='1' AND a.fld_delstatus='0'");
                                                                        
                                                                        ?>
                                                                    <li><input type="checkbox" <?php if($extcount!='0' && $selectall!='1'){ ?> checked="checked"<?php } ?> name="mod_<?php echo $exid."_".$templist[1]."_".$moduleid;?>" class="ads_Checkbox_<?php echo $moduleid;?>" value="<?php echo $exid."_".$templist[1]."_".$moduleid."_".$exname;?>" id="mod_<?php echo $exid."_".$templist[1]."_".$moduleid;?>" onclick="fn_fillnameformod(<?php echo $i; ?>,<?php echo $moduleid; ?>);">&nbsp;<?php echo $exname; ?></li>
                                                                        <?php  
                                                                    }
                                                                    else
                                                                    { ?>
																<li><a tabindex="-1" href="#" data-option="<?php echo $exid."~".$templist[1]."~".$moduleid;?>"><?php echo $exname; ?></a></li>
                                                                <?php
                                                                    }
                                                                } ?>      
                                                    </ul>
                                                </div>
                                               
                                                
                                            </div> 
                                        </dl>
                                    </div>
                                    <input type="hidden" name="selectallmod_<?php echo $moduleid.",".$licenseid; ?>" id="selectallmod_<?php echo $moduleid; ?>" value="1" /> 
                                    <input type="hidden" name="excflag" id="excflag" value="1" />
								</td>
                            </tr>
							<?php 
							}
						}
				   }
					
					if($count==0)
					{
				 ?>
                 	<tr>
                    	<td colspan="2">
                        	No records found
                        </td>
                    </tr>
                 <?php
					}
					?>
                               
            </tbody>
        </table>
    </div>
    <?php 
}

if($oper=="removemodule" and $oper!='')
    {
        $moduletype = isset($method['moduletype']) ? $method['moduletype'] : '0';
        $rowid = isset($method['rowid']) ? $method['rowid'] : '0';
        $scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '0';
        $classid = isset($method['classid']) ? $method['classid'] : '0';
        
        $module=explode('-',$moduletype);
        
       
         
         $ObjDB->NonQuery("UPDATE itc_class_rotation_moduledet SET fld_flag='0'
                                WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_module_id='".$module[0]."' AND fld_type='".$module[1]."' and fld_row_id='".$rowid."'");
        
    }

    if($oper=="autoblock" and $oper!='')
    {
        $scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '0';
        $moduleid = isset($method['moduleid']) ? $method['moduleid'] : '0';
        $moduletype = isset($method['moduletype']) ? $method['moduletype'] : '0';
        $stumodid=array();
        $stupoints=array();
        $stupointscustom=array();

        $moduleexplode=explode(",",$moduleid);
        
        for($i=0;$i<sizeof($moduleexplode);$i++)
        {
            $explodehypen=explode("-",$moduleexplode[$i]);
            
            if($explodehypen[1]==1 OR $explodehypen[1]==2)
            {
            if($modid=='')
            {
                $modid=$explodehypen[0];
            }
            else
            {
                $modid.=",".$explodehypen[0];
            }
        }
        
            if($explodehypen[1]==8)
            {
                if($cusid=='')
                {
                    $cusid=$explodehypen[0];
                }
                else
                {
                    $cusid.=",".$explodehypen[0];
                }
            }
        }
        
        $qryschedulestudentmap=$ObjDB->QueryObject("SELECT a.fld_id FROM itc_user_master AS a LEFT JOIN itc_class_rotation_schedule_student_mappingtemp AS b ON a.fld_id=b.fld_student_id WHERE b.fld_schedule_id='".$scheduleid."' AND b.fld_flag=1 AND a.fld_activestatus='1' AND a.fld_delstatus='0' ");
        
        while($rowstudent=$qryschedulestudentmap->fetch_assoc())
    	{
	       extract($rowstudent);
               if($stu=='')
               {
                    $stu=$fld_id;
               }
               else
               {
                   $stu.=",".$fld_id;
               }
                        
        }
        
       
       if($modid=='')
       {
           $modid=0;
       }
        
       if($cusid=='')
       {
           $cusid=0;
       }
        
       if($stu=='')
       {
           $stu=0;
       }

        
        $qryautoblock=$ObjDB->QueryObject("SELECT fld_module_id as modid,fld_tester_id as stuid,fld_schedule_id as sid,fld_schedule_type as stype from itc_module_answer_track where fld_module_id in(".$modid.") and fld_tester_id in (".$stu.") and fld_delstatus='0' group by fld_module_id,fld_tester_id");
        
        $qrypointsmaster=$ObjDB->QueryObject("SELECT fld_module_id as modid,fld_student_id as stuid,fld_schedule_id as sid,fld_schedule_type as stype from itc_module_points_master where fld_module_id in(".$modid.") and fld_student_id in (".$stu.") and fld_delstatus='0' group by fld_module_id,fld_student_id");
        
        if($qryautoblock->num_rows>0)
        {
            while($rowstudent=$qryautoblock->fetch_assoc())
            {
                extract($rowstudent);
                $count=0;
                
                if($stype==5 or $stype==6 or $stype==7)
                {
                    $tablename="itc_class_indassesment_master as a";
                }
                else if($stype==1 or $stype==4)
                {
                    $tablename="itc_class_rotation_schedule_mastertemp as a";
                }
                else if($stype==2)
                {
                    $tablename="itc_class_dyad_schedulemaster as a";
                }
                else if($stype==3)
                {
                    $tablename="itc_class_triad_schedulemaster as a";
                }
                else if($stype==21)
                {
                    $tablename="itc_class_rotation_modexpschedule_mastertemp as a";
                }
                
                if($stype!=0 AND $sid!=0)
                {
                $count=$ObjDB->SelectSingleValueInt("select a.fld_id from ".$tablename." left join itc_class_master as b on b.fld_id=a.fld_class_id where a.fld_id='".$sid."' AND a.fld_moduletype='".$moduletype."' and a.fld_delstatus='0' and b.fld_delstatus='0'");
                }
                
                if($count>0)
                {
                    $stumodid[]=$modid."-".$moduletype."-".$stuid;
                }
            }
        }
            
         if($qrypointsmaster->num_rows>0)
        {
            while($rowpoints=$qrypointsmaster->fetch_assoc())
            {
                extract($rowpoints);
                $count=0;
                
                if($stype==5 or $stype==6 or $stype==7)
                {
                    $tablename="itc_class_indassesment_master as a";
                }
                else if($stype==1 or $stype==4)
                {
                    $tablename="itc_class_rotation_schedule_mastertemp as a";
                }
                else if($stype==2)
                {
                    $tablename="itc_class_dyad_schedulemaster as a";
                }
                else if($stype==3)
                {
                    $tablename="itc_class_triad_schedulemaster as a";
                }
                else if($stype==21)
                {
                    $tablename="itc_class_rotation_modexpschedule_mastertemp as a";
                }
                
                if($stype!=0 AND $sid!=0)
                {
                $count=$ObjDB->SelectSingleValueInt("select a.fld_id from ".$tablename." left join itc_class_master as b on b.fld_id=a.fld_class_id where a.fld_id='".$sid."' AND a.fld_moduletype='".$moduletype."' and a.fld_delstatus='0' and b.fld_delstatus='0'");
                }

                if($count>0)
                {
                    $stupoints[]=$modid."-".$moduletype."-".$stuid;
                }
            }
            
         }
         
         $qrypointsmastercustom=$ObjDB->QueryObject("SELECT fld_module_id as modid,fld_student_id as stuid,fld_schedule_id as sid,fld_schedule_type as stype from itc_module_points_master where fld_module_id in(".$cusid.") and fld_student_id in (".$stu.") and fld_schedule_type='8' or fld_schedule_type='22' and fld_delstatus='0' group by fld_module_id,fld_student_id");
         
          if($qrypointsmastercustom->num_rows>0)
         {
            while($rowpoints=$qrypointsmastercustom->fetch_assoc())
            {
                extract($rowpoints);
                $count=0;
                
                if($stype!=0 AND $sid!=0)
                {
                     if($stype==22)
                    {
                        $count=$ObjDB->SelectSingleValueInt("SELECT a.fld_id FROM itc_class_rotation_modexpschedule_mastertemp as a left join itc_class_master as b on b.fld_id=a.fld_class_id WHERE a.fld_id='".$sid."' and a.fld_delstatus='0' and b.fld_delstatus='0'");
                    }
                    else if($stype==8)
                    {
                        $count=$ObjDB->SelectSingleValueInt("SELECT a.fld_id FROM itc_class_rotation_schedule_mastertemp as a left join itc_class_master as b on b.fld_id=a.fld_class_id WHERE a.fld_id='".$sid."' and a.fld_delstatus='0' and b.fld_delstatus='0'");
                    }
                }

                if($count>0)
                {
                    $stupointscustom[]=$modid."-8-".$stuid;
                }
                
            }
         }
         
         
         $result = array_merge($stumodid,$stupoints);
         
         $result1=array_merge($result,$stupointscustom);
         
         echo json_encode($result1);
         
         
    }


	@include("footer.php");
