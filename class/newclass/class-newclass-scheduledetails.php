<?php 
@include("sessioncheck.php");
$tempid = isset($method['id']) ? $method['id'] : '';
$tempid=explode(",",$tempid);
$id=$tempid[0];
$flag=$tempid[1];

?>

<section data-type='#class-newclass' id='class-newclass-scheduledetails'>
	<script language="javascript">
		$('#classdetails').removeClass("active-first");
		$('#review').removeClass("active-last");
		$('#people').removeClass("active-mid");
		$('#schedule').parents().removeClass("dim");
		$('#schedule').addClass("active-mid");
		
		$('#tablecontents3').slimscroll({
			height:'auto',
			railVisible: false,
			allowPageScroll: false,
                        size: '7px',
                        alwaysVisible: true,
			railColor: '#F4F4F4',
			opacity: 9,
			color: '#88ABC2',
                        wheelStep: 1
		});
	</script>
	<div class='container'>
        <div class='row'>
            <div class="span10">
                <p class="dialogTitle">Add Schedules</p>
                <p class="dialogSubTitleLight">&nbsp;</p>                
            </div>
        </div>  
        <div class="row">
            <div class='span10 offset1'>
                 <table class='table table-hover table-striped table-bordered setbordertopradius' id="mytable" >
                    <thead class='tableHeadText' >
                        <tr style="cursor:default;">
                            <th width="30%">Schedule name</th>
                            <th width="20%" class='centerText'>Type</th>
                            <th width="8%" class='centerText'>enrolled<br />students</th>
                            <th width="13%" class='centerText'>start<br />date</th>
                            <th width="13%" class='centerText'>end<br />date</th>
                            <th class='centerText'>actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="mainBtn" id="btnclass-newclass-newschedulestep" name="0,0,<?php echo $id; ?>">
                            <td colspan="6" class="createnewtd" style="border-left:none;"> <span class="icon-synergy-create small-icon"></span><span>&nbsp;&nbsp;&nbsp;Add a new schedule </span></td>
                        </tr>
                    </tbody>
                </table>
                        <?php
                        
						$qry = $ObjDB->QueryObject("SELECT a.fld_id AS sid, a.fld_schedule_name AS sname,1 AS stype,'Sigmath' AS typename,'' as wcalock, a.fld_class_id AS classid, 
														a.fld_start_date AS startdate, a.fld_end_date AS enddate, COUNT(a.fld_id) AS scount
													FROM itc_class_sigmath_master AS a LEFT JOIN itc_class_sigmath_student_mapping AS b ON b.fld_sigmath_id=a.fld_id 
													WHERE a.fld_class_id='".$id."' AND a.fld_delstatus='0' AND b.`fld_flag`='1' 
													GROUP BY sid
																										
													UNION ALL 													
													SELECT a.fld_id AS sid, a.fld_schedule_name AS sname,a.fld_scheduletype AS stype, (CASE WHEN a.fld_scheduletype=2 
														THEN 'Module Schedule' WHEN a.fld_scheduletype=6 THEN 'Math Module Schedule' END) AS typename,'' as wcalock, a.fld_class_id AS classid,
														a.fld_startdate AS startdate, a.fld_enddate AS enddate, COUNT(a.fld_id) AS scount 
													FROM itc_class_rotation_schedule_mastertemp AS a LEFT JOIN itc_class_rotation_schedule_student_mappingtemp AS b 
														ON b.fld_schedule_id=a.fld_id
													WHERE a.fld_class_id='".$id."' AND a.fld_delstatus='0' AND b.fld_flag='1' 
													GROUP BY sid													
                                                                                        
													UNION ALL
                                                                                        SELECT a.fld_id AS sid, a.fld_schedule_name AS sname,17 AS stype, 'Expedition Schedule' AS typename,'' as wcalock, a.fld_class_id AS classid,
                                                                                                a.fld_startdate AS startdate, a.fld_enddate AS enddate, COUNT(a.fld_id) AS scount 
                                                                                        FROM itc_class_rotation_expschedule_mastertemp AS a LEFT JOIN itc_class_rotation_expschedule_student_mappingtemp AS b 
                                                                                                ON b.fld_schedule_id=a.fld_id
                                                                                        WHERE a.fld_class_id='".$id."' AND a.fld_delstatus='0' AND b.fld_flag='1' 
                                                                                        GROUP BY sid		
                                                                                        UNION ALL
                                                                                        SELECT a.fld_id AS sid, a.fld_schedule_name AS sname,20 AS stype, 'Mission Schedule' AS typename,'' as wcalock, a.fld_class_id AS classid,
                                                                                                a.fld_startdate AS startdate, a.fld_enddate AS enddate, COUNT(a.fld_id) AS scount 
                                                                                        FROM itc_class_rotation_mission_mastertemp AS a LEFT JOIN itc_class_rotation_mission_student_mappingtemp AS b 
                                                                                                ON b.fld_schedule_id=a.fld_id
                                                                                        WHERE a.fld_class_id='".$id."' AND a.fld_delstatus='0' AND b.fld_flag='1' 
                                                                                        GROUP BY sid
                                                                                        UNION ALL
                                                                                        SELECT a.fld_id AS sid, a.fld_schedule_name AS sname,19 AS stype, 'Module/Expedition Schedule' AS typename,'' as wcalock, a.fld_class_id AS classid,
                                                                                                a.fld_startdate AS startdate, a.fld_enddate AS enddate, COUNT(a.fld_id) AS scount 
                                                                                        FROM itc_class_rotation_modexpschedule_mastertemp AS a LEFT JOIN itc_class_rotation_modexpschedule_student_mappingtemp AS b 
                                                                                                ON b.fld_schedule_id=a.fld_id
                                                                                        WHERE a.fld_class_id='".$id."' AND a.fld_delstatus='0' AND b.fld_flag='1' 
                                                                                        GROUP BY sid		

                                                                                        UNION ALL

													SELECT a.fld_id AS sid, a.fld_schedule_name AS sname,3 AS stype,'Dyad' AS typename,'' as wcalock, a.fld_class_id AS classid, 
														a.fld_startdate AS startdate, a.fld_enddate AS enddate,COUNT(a.fld_id) AS scount 
													FROM itc_class_dyad_schedulemaster AS a LEFT JOIN itc_class_dyad_schedule_studentmapping AS b ON b.fld_schedule_id=a.fld_id
													WHERE a.fld_class_id='".$id."' AND a.fld_delstatus='0' AND b.fld_flag='1' 
													GROUP BY sid													
													UNION ALL 
													
													SELECT a.fld_id AS sid, a.fld_schedule_name AS sname,4 AS stype,'Triad' AS typename,'' as wcalock,a.fld_class_id AS classid, 
														a.fld_startdate AS startdate, a.fld_enddate AS enddate, COUNT(a.fld_id) AS scount 
													FROM itc_class_triad_schedulemaster AS a LEFT JOIN itc_class_triad_schedule_studentmapping AS b ON b.fld_schedule_id=a.fld_id
													WHERE a.fld_class_id='".$id."' AND a.fld_delstatus='0' AND b.fld_flag='1' 
													GROUP BY sid
													UNION ALL 
													
													SELECT a.fld_id AS sid, a.fld_schedule_name AS sname,5 AS stype,'Whole Class Assignment' AS typename,'' as wcalock,a.fld_class_id AS classid, 
														a.fld_startdate AS startdate, a.fld_enddate AS enddate, COUNT(a.fld_id) AS scount 
													FROM itc_class_indassesment_master AS a LEFT JOIN itc_class_indassesment_student_mapping AS b ON b.fld_schedule_id=a.fld_id
													WHERE a.fld_class_id='".$id."' AND a.fld_delstatus='0' AND b.fld_flag='1' 
													GROUP BY sid
													
													UNION ALL 
													
													SELECT a.fld_id AS sid, a.fld_schedule_name AS sname,15 AS stype,'Whole Class Assignment - Expedition' AS typename, a.fld_lock as wcalock,  a.fld_class_id AS classid, 
														a.fld_startdate AS startdate, a.fld_enddate AS enddate, COUNT(a.fld_id) AS scount 
													FROM itc_class_indasexpedition_master AS a LEFT JOIN itc_class_exp_student_mapping AS b ON b.fld_schedule_id=a.fld_id
                                                                                        WHERE a.fld_class_id='".$id."'  AND a.fld_delstatus='0' AND b.fld_flag='1' 
                                                                                        GROUP BY sid
                                                                                        
                                                                                        UNION ALL 

                                                                                        SELECT a.fld_id AS sid,a.fld_schedule_name AS sname,18 AS stype,'Whole Class Assignment - Mission' AS typename,a.fld_lock as wcalock,a.fld_class_id AS classid,
                                                                                        a.fld_startdate AS startdate,a.fld_enddate AS enddate,COUNT(a.fld_id) AS scount
                                                                                        FROM itc_class_indasmission_master AS a LEFT JOIN itc_class_mission_student_mapping AS b ON b.fld_schedule_id = a.fld_id 
                                                                                        WHERE a.fld_class_id ='".$id."' AND a.fld_delstatus = '0' AND b.fld_flag = '1'
                                                                                        GROUP BY sid

                                                                                        

                                                                                        UNION ALL 

                                                                                        SELECT a.fld_id AS sid, a.fld_schedule_name AS sname,16 AS stype,'PD' AS typename,'' as wcalock, a.fld_class_id AS classid, 
                                                                                            a.fld_start_date AS startdate, a.fld_end_date AS enddate, COUNT(a.fld_id) AS scount
                                                                                        FROM itc_class_pdschedule_master AS a 
                                                                                        LEFT JOIN itc_class_pdschedule_student_mapping AS b ON b.fld_pdschedule_id=a.fld_id 
                                                                                        WHERE a.fld_class_id='".$id."' AND a.fld_delstatus='0' AND b.fld_flag='1' GROUP BY sid");
				?>
                <div style="max-height:400px;width:100%;" id="tablecontents3" >
                    <table style="margin-bottom:0px;" class='table table-hover table-striped table-bordered bordertopradiusremove' id="mytable">
                        <tbody>
                        <?php							
                        if($qry->num_rows!=0){                                           
                            while($row=$qry->fetch_assoc()){
								extract($row);
                            ?>
                            <tr>									
                                <td width="30%"><?php echo $sname; ?></td>
                                <td width="20%" class='centerText'><?php echo $typename; ?></td>
                                <td width="8%" class='centerText'><?php echo $scount; ?></td>
                                <td width="13%" class='centerText'><?php echo date("m/d/Y",strtotime($startdate)); ?></td>
                                <td width="13%" class='centerText'>
									<?php if($enddate!="0000-00-00" and $enddate!=""){echo date("m/d/Y",strtotime($enddate)); } ?>
                                </td>
                                <td class='centerText'>                                
                                	<div class="icon-synergy-view mainBtn tooltip" 
									<?php if($stype==1){?> 
                                    		id="btnclass-newclass-viewprogress" 
                                            name="<?php echo $sid.",".$classid;?>" 
									<?php } 	
										  else if($stype==2 or $stype==6) { ?> 
                                          	id="btnclass-newclass-viewschedule_edit" 
                                            name="<?php echo $sid.",".$stype.",".$classid.",viewrot";?>" 
									<?php }
                                                                        else if($stype==17) { ?> 
                                          	id="btnclass-newclass-viewschedule_editexp" 
                                            name="<?php echo $sid.",".$stype.",".$classid.",viewrot";?>" 
									<?php }
                                                                        else if($stype==19) { ?> 
                                          	id="btnclass-newclass-viewschedule_editmodexp" 
                                            name="<?php echo $sid.",".$stype.",".$classid.",viewrot";?>" 
									<?php }
                                                                        
                                                                         else if($stype==20) { ?> 
                                          	id="btnclass-newclass-viewschedule_editmission" 
                                            name="<?php echo $sid.",".$stype.",".$classid.",viewrot";?>" 
									<?php }
								     else if($stype==3) { ?> 
                                    		id="btnclass-newclass-viewdyadtable" 
                                            name="<?php echo $sid.",".$stype.",".$classid.",viewdyad";?>" 
									<?php } 
										  else if($stype==4) { ?> 
                                          	id="btnclass-newclass-viewtriadtable" 
                                            name="<?php echo $sid.",".$stype.",".$classid.",viewtriad";?>" 
									<?php } 
										  else if($stype==5){?> 
                                          	id="btnclass-newclass-viewindprogress" 
                                            name="<?php echo $sid.",".$stype.",".$classid;?>" 
									<?php } 
										else if($stype==15){?> 
                                          	id="btnclass-newclass-viewexpedition" 
                                            name="<?php echo $sid.",".$stype.",".$classid;?>" 
									<?php }
                                                                                else if($stype==18){?> 
                                          	id="btnclass-newclass-viewmission" 
                                            name="<?php echo $sid.",".$stype.",".$classid;?>" 
									<?php }                         
                                                else if($stype==16){ ?> 
                                                    id="btnclass-newclass-viewpdprogress" 
                                                    name="<?php echo $sid.",".$classid;?>" <?php 
                                                } 
                                                ?> style="float:left; font-size:21px;padding-right: 10px;" title="View"></div>
                                    <div class="icon-synergy-edit mainBtn tooltip" id="btnclass-newclass-newschedulestep" title="Edit" name="<?php echo $sid.",".$stype.",".$classid;?>" style="float:left; font-size:18px;padding-right: 10px;"></div>

                                    <?php if($stype==16){?>
                                    <div class="icon-synergy-trash tooltip" title="Delete" style="float:left; font-size:18px;padding-right: 10px;" onclick="fn_deletepdschedule(<?php echo $sid;?>,<?php echo $stype;?>,<?php echo $classid;?>)"></div>    
                                    <?php } else{?>
                                    <div class="icon-synergy-trash tooltip" title="Delete" style="float:left; font-size:18px;padding-right: 10px;" onclick="fn_deleterotationschedule(<?php echo $sid;?>,<?php echo $stype;?>,<?php echo $classid;?>)"></div>                               
                                    <?php } ?>
                                    <?php if($stype==15){ ?>
									<div>
									 <a href='#class-class' onclick="fn_wcaexplock(<?php echo $sid;?>)">
                                               <div class='<?php if($wcalock==1){ echo "icon-synergy-locked";} else{ echo "icon-synergy-unlocked";} ?>' style="float:center; font-size:18px;padding-right: 15px;" id="wcaexplock<?php echo $sid;?>">
                                                   <div id="wcaexpcontent<?php echo $sid;?>" style="float:right; font-size:10px;padding-right: 6px;"><?php if($wcalock==1){ echo "Lock";}  else{ echo "unlock";} ?></div>
									</div>
                                            </a>
                                        </div>
						<?php } ?>
                                     <?php if($stype==18){ ?>
                                        <div>
                                            <a href='#class-class' onclick="fn_wcamissionlock(<?php echo $sid;?>)">
                                               <div class='<?php if($wcalock==1){ echo "icon-synergy-locked";} else{ echo "icon-synergy-unlocked";} ?>' style="float:center; font-size:18px;padding-right: 15px;" id="wcamislock<?php echo $sid;?>">
                                                   <div id="wcamiscontent<?php echo $sid;?>" style="float:right; font-size:10px;padding-right: 6px;"><?php if($wcalock==1){ echo "Lock";}  else{ echo "unlock";} ?></div>
                                               </div> 
                                            </a>
                                        </div>
                                    <?php } ?>
                                    <?php if($stype!=18 && $stype!=19 && $stype!=20) {
                                        $get_class_schedules_query = "SELECT 
                                                                b.fld_schedule_id AS sid,
                                                                b.fld_expedition_id AS expid,
                                                                COUNT(b.fld_id) AS scount 
                                                            FROM 
                                                                itc_class_rotation_expschedulegriddet AS b 
                                                                LEFT JOIN itc_exp_master AS e ON e.fld_id=b.fld_expedition_id 
                                                            WHERE 
                                                                b.fld_schedule_id='$sid' AND b.fld_flag='1' 
                                                                AND e.fld_thinksp != '0' AND e.fld_thinksp IS NOT NULL 
                                                            GROUP BY sid
													    UNION ALL 
                                                            SELECT 
                                                                a.fld_id AS sid,
                                                                a.fld_exp_id AS expid,
                                                                COUNT(a.fld_id) AS scount 
                                                            FROM 
                                                                itc_class_indasexpedition_master AS a 
                                                                LEFT JOIN itc_exp_master AS e ON e.fld_id=a.fld_exp_id 
                                                            WHERE 
                                                                a.fld_id='$sid' AND a.fld_delstatus='0' 
                                                                AND e.fld_thinksp != '0' AND e.fld_thinksp IS NOT NULL 
                                                            GROUP BY sid";
                                        $query_object = $ObjDB->QueryObject($get_class_schedules_query);
                                        if ($query_object->num_rows > 0) {
                                            ?>
                                            <!--Digital Logbook-->
                                            <div class="icon-synergy-dashboard tooltip" title="Digital Logbook"
                                                 onclick="fn_logbook(<?php echo $sid; ?>,<?php echo $stype; ?>,<?php echo $classid; ?>)"
                                                 style="float:left; font-size:18px;padding-right: 10px;"></div>
                                            <?php
                                        }
                                    }
                                    ?>

                                </td>
                            </tr>
                            <?php }                                                        
                         }?>                                                 
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    
    <?php
		if($flag==0)
		{?>   
            <div class="row rowspacer">
                <div class="tRight">
                    <input type="button" id="btnstep" class="darkButton" style="width:200px; height:42px;float:right;" value="Next Step" onClick="fn_reviewclass(<?php echo $id;?>);" />
                </div>
            </div>
       <?php
		}
		?>
 	</div>
    <input type="hidden" id="rotshe" value="" />
    <script>
        function fn_logbook(sid, stype, classid) {
            dataparam="sid="+sid+"&stype="+stype+"&classid="+classid;
            window.open('library/thinkscapeTeacher.php?'+dataparam);
//            dataparam="sid="+sid+"&stype="+stype+"&classid="+classid;
//            $.ajax({
//                type: 'post',
//                url: 'library/thinkscapeTeacher.php',
//                data: dataparam,
//                beforeSend: function(){
//                    showloadingalert("Loading Digital Logbook, please wait.");
//                },
//                success:function(ajaxdata) {
//                    console.log(ajaxdata);
//                }
//            });
        }
    </script>
</section>
<?php
	@include("footer.php");