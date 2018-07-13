<?php
@include("sessioncheck.php");

$menuid= isset($_REQUEST['id']) ? $_REQUEST['id'] : '0';
$iplcount=0;
$modulecount=0;

?>
<section data-type='2home' id='library'>
    <script>
        $.getScript("js/checkContent.js");
    </script>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="lightTitle">Library</p>
                <p class="lightSubTitle">&nbsp;</p>
            </div>
        </div>
        <div class='row buttons'>
        <?php
            $qrymenuname=$ObjDB->QueryObject("SELECT a.fld_id ,a.fld_menu_name, a.fld_class, a.fld_href, a.fld_hrefid, a.fld_divclass FROM itc_main_menu AS a RIGHT JOIN itc_menu_privileges AS b ON a.fld_id=b.fld_menu_id WHERE b.fld_profile_id='".$sessprofileid."' AND b.fld_access='1' and a.fld_main_menu_id='".$menuid."' and a.fld_delstatus='0' and b.fld_delstatus='0' order by a.fld_position ASC");
            while($rowmenuname=$qrymenuname->fetch_object())
            {
                $iplcount = 0;
                $modulecount = 0;
                $menuname=$rowmenuname->fld_menu_name;
                $menuid=$rowmenuname->fld_id;
                $class=$rowmenuname->fld_class;
                $href=$rowmenuname->fld_href;
                $id=$rowmenuname->fld_hrefid;
                $divclass=$rowmenuname->fld_divclass;

                if($menuid == 7) //IPL
                    $iplcount = $ObjDB->SelectSingleValueInt("SELECT count(a.fld_lesson_id) FROM itc_license_cul_mapping AS a LEFT JOIN itc_license_track AS b ON a.fld_license_id=b.fld_license_id WHERE b.fld_school_id='".$schoolid."' AND b.fld_district_id='".$districtid."' AND b.fld_user_id='".$indid."' AND b.fld_delstatus='0' AND a.fld_active='1'");

                if($menuid == 9) // MOdule
                    $modcount = $ObjDB->SelectSingleValueInt("SELECT count(a.fld_module_id) FROM itc_license_mod_mapping AS a LEFT JOIN itc_license_track AS b ON a.fld_license_id=b.fld_license_id WHERE b.fld_school_id='".$schoolid."' AND b.fld_district_id='".$districtid."' AND b.fld_user_id='".$indid."' AND b.fld_delstatus='0' AND a.fld_active='1' AND  a.fld_type='1'");

                if($menuid == 44) //Math Module
                    $modcount = $ObjDB->SelectSingleValueInt("SELECT count(a.fld_module_id) FROM itc_license_mod_mapping AS a LEFT JOIN itc_license_track AS b ON a.fld_license_id=b.fld_license_id WHERE b.fld_school_id='".$schoolid."' AND b.fld_district_id='".$districtid."' AND b.fld_user_id='".$indid."' AND b.fld_delstatus='0' AND a.fld_active='1' AND a.fld_type='2'");

                if($menuid == 48) //Quest
                    $modcount = $ObjDB->SelectSingleValueInt("SELECT count(a.fld_module_id) FROM itc_license_mod_mapping AS a LEFT JOIN itc_license_track AS b ON a.fld_license_id=b.fld_license_id WHERE b.fld_school_id='".$schoolid."' AND b.fld_district_id='".$districtid."' AND b.fld_user_id='".$indid."' AND b.fld_delstatus='0' AND a.fld_active='1' AND a.fld_type='7'");

                if($menuid == 50) //Expedition
                    $expcount = $ObjDB->SelectSingleValueInt("SELECT count(a.fld_exp_id) FROM itc_license_exp_mapping AS a LEFT JOIN itc_license_track AS b ON a.fld_license_id=b.fld_license_id WHERE b.fld_school_id='".$schoolid."' AND b.fld_district_id='".$districtid."' AND b.fld_user_id='".$indid."' AND b.fld_delstatus='0' AND a.fld_flag='1'");

                if($menuid == 57) //PD
                    $pdcount = $ObjDB->SelectSingleValueInt("SELECT count(a.fld_pd_id) FROM itc_license_pd_mapping AS a 
                                                                    LEFT JOIN itc_license_track AS b ON a.fld_license_id=b.fld_license_id
                                                                    LEFT JOIN itc_user_master AS c ON c.fld_school_id = b.fld_school_id
                                                                    WHERE b.fld_school_id='".$schoolid."' AND b.fld_district_id='".$districtid."' AND b.fld_user_id='".$indid."' 
                                                                    AND b.fld_delstatus='0' AND a.fld_active='1' AND c.fld_id='".$uid."'");

                if($menuid == 68) //Mission
                {
                    $miscount = $ObjDB->SelectSingleValueInt("SELECT count(a.fld_mis_id) FROM itc_license_mission_mapping AS a LEFT JOIN itc_license_track AS b ON a.fld_license_id=b.fld_license_id WHERE b.fld_school_id='".$schoolid."' AND b.fld_district_id='".$districtid."' AND b.fld_user_id='".$indid."' AND b.fld_delstatus='0' AND a.fld_flag='1'");
                }

                if($menuid == 56) //Grading Rubric
                {
                    $rubriccount=$ObjDB->SelectSingleValueInt("SELECT SUM(w.emcount) FROM (SELECT count(a.fld_exp_id) AS emcount FROM itc_license_exp_mapping AS a 
                                              LEFT JOIN itc_license_track AS b ON a.fld_license_id=b.fld_license_id 
                                              WHERE b.fld_school_id='".$schoolid."' AND b.fld_district_id='".$districtid."' 
                                              AND b.fld_user_id='".$indid."' AND b.fld_delstatus='0' AND a.fld_flag='1'
                                              UNION ALL
                                              SELECT count(a.fld_mis_id)  AS emcount FROM itc_license_mission_mapping AS a 
                                              LEFT JOIN itc_license_track AS b ON a.fld_license_id=b.fld_license_id 
                                              WHERE b.fld_school_id='".$schoolid."' AND b.fld_district_id='".$districtid."' 
                                              AND b.fld_user_id='".$indid."' AND b.fld_delstatus='0' AND a.fld_flag='1' ) AS w ");             
                }

				if($menuid!=57 AND $sessmasterprfid!='10' AND $menuid != 68)
                {
                    if($iplcount!=0 or $modcount!=0 or $menuid==27 or $menuid==49 or $expcount!=0 or $miscount!=0 or $rubriccount!=0 or $sessmasterprfid==2 or $sessmasterprfid==3)
                    {
                        if($sessmasterprfid!='10' or ($menuid!=44 and $menuid!=9) )	
                        {
							?>
							<a class='<?php echo $class; if($trialuser == '1' and ($menuid == '44' or $menuid == '9' or $menuid == '48' or $menuid == '49')){ echo ' dim'; }?>' href='<?php echo $href;?>' id='<?php echo $id;?>' name='<?php echo $menuid;?>'>
									<div class='<?php echo $divclass;?>'></div>
									<div class='onBtn'><?php echo ucfirst($menuname);?></div>
							</a>
							<?php
                        }
                    }
                }
				if($menuid == 68 AND $sessmasterprfid!='10') //Mission
                {
					if($miscount!=0 or $sessmasterprfid=='2')
					{
						?>
						<a class='<?php echo $class; if($trialuser == '1' and ($menuid == '44' or $menuid == '9' or $menuid == '48' or $menuid == '49')){ echo ' dim'; }?>' href='<?php echo $href;?>' id='<?php echo $id;?>' name='<?php echo $menuid;?>'>
								<div class='<?php echo $divclass;?>'></div>
								<div class='onBtn'><?php echo ucfirst($menuname);?></div>
						</a>
						<?php
						
					}
					
				}
				if($menuid==57  AND $sessmasterprfid!='10') //PD
                {
                    if($sessmasterprfid!='8' and $sessmasterprfid!='9' and $sessmasterprfid!='10' and $pdcount!=0 or $sessmasterprfid==2 or $sessmasterprfid==3)
                    {
                        ?>

                                    <a class='<?php echo $class;?>' href='<?php echo $href;?>' id='<?php echo $id;?>' name='<?php echo $menuid;?>'>
                                            <div class='<?php echo $divclass;?>'></div>
                                            <div class='onBtn'><?php echo ucfirst($menuname);?></div>
                                    </a>
                        <?php
                    }
                    else
                    {
                        $flag=$ObjDB->SelectSingleValueInt("SELECT fld_flag FROM itc_user_master WHERE fld_id='".$uid."'");

                        if($pdcount!=0 AND ($sessmasterprfid=='8' OR $sessmasterprfid=='9'))
                        {
                            ?>

                                   <a class='<?php echo $class;?>' href='<?php echo $href;?>' id='<?php echo $id;?>' name='<?php echo $menuid;?>'>
                                            <div class='<?php echo $divclass;?>'></div>
                                            <div class='onBtn'><?php echo ucfirst($menuname);?></div>
                                    </a>
                            <?php
                        }
                    }
                }
				if($menuid == 7){
					$iplcnt = $ObjDB->SelectSingleValueInt("SELECT b.fld_id AS lessonid 
											FROM itc_class_sigmath_student_mapping AS a 
											LEFT JOIN itc_class_sigmath_lesson_mapping AS c ON a.fld_sigmath_id=c.fld_sigmath_id 
											LEFT JOIN itc_ipl_master AS b ON b.fld_id=c.fld_lesson_id 
											LEFT JOIN itc_class_sigmath_master AS d ON d.fld_id=c.fld_sigmath_id 
											LEFT JOIN itc_class_master AS e ON e.fld_id=d.fld_class_id 
											WHERE a.fld_student_id='".$uid."' AND a.fld_flag='1' AND c.fld_flag='1' AND b.fld_delstatus='0' AND b.fld_access='1' AND d.fld_delstatus='0' AND e.fld_delstatus='0' AND c.fld_license_id IN (SELECT fld_license_id FROM itc_license_track WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."') 
											GROUP BY b.fld_id");

					if($sessmasterprfid == '10' AND $iplcnt > 0){

							?>
							<a class='<?php echo $class; ?>' href='<?php echo $href;?>' id='<?php echo $id;?>' name='<?php echo $menuid;?>'>
									<div class='<?php echo $divclass;?>'></div>
									<div class='onBtn'><?php echo ucfirst($menuname);?></div>
							</a>
							<?php
					}
				} //IPL 
			}
			$modqry = $ObjDB->QueryObject("SELECT w.* FROM (SELECT b.fld_module_id FROM itc_class_rotation_schedule_mastertemp AS a LEFT JOIN itc_class_rotation_schedulegriddet AS b ON a.fld_id=b.fld_schedule_id LEFT JOIN itc_class_rotation_scheduledate as g on b.fld_schedule_id=g.fld_schedule_id and b.fld_rotation=g.fld_rotation LEFT JOIN itc_module_master AS c ON b.fld_module_id=c.fld_id LEFT JOIN itc_class_master AS d on d.fld_id=a.fld_class_id WHERE a.fld_delstatus='0' AND b.fld_type='1' AND d.fld_delstatus='0' AND d.fld_lock='0' AND a.fld_moduletype='1' AND b.fld_student_id='".$uid."' AND a.fld_moduletype='1' AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track WHERE fld_school_id='".$schoolid."') AND b.fld_flag='1' 
													UNION ALL   SELECT b.fld_module_id FROM itc_class_rotation_schedule_mastertemp AS a LEFT JOIN itc_class_rotation_schedulegriddet AS b ON a.fld_id=b.fld_schedule_id LEFT JOIN itc_class_rotation_scheduledate as g on b.fld_schedule_id=g.fld_schedule_id and b.fld_rotation=g.fld_rotation LEFT JOIN itc_mathmodule_master AS c ON b.fld_module_id=c.fld_id LEFT JOIN itc_class_master AS d on d.fld_id=a.fld_class_id WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND b.fld_type='2' AND d.fld_lock='0' AND a.fld_moduletype='2' AND b.fld_student_id='".$uid."' AND a.fld_moduletype='2' AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."') AND b.fld_flag='1' 
													UNION ALL   SELECT a.fld_module_id FROM itc_class_indassesment_master AS a LEFT JOIN itc_class_indassesment_student_mapping AS b ON a.fld_id=b.fld_schedule_id LEFT JOIN itc_module_master AS c ON a.fld_module_id=c.fld_id LEFT JOIN itc_class_master AS d on d.fld_id=a.fld_class_id WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND d.fld_lock='0' AND a.fld_moduletype='1' AND b.fld_student_id='".$uid."' AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."') AND b.fld_flag='1'
													UNION ALL   SELECT a.fld_module_id FROM itc_class_indassesment_master AS a LEFT JOIN itc_class_indassesment_student_mapping AS b ON a.fld_id=b.fld_schedule_id LEFT JOIN itc_mathmodule_master AS c ON a.fld_module_id=c.fld_id LEFT JOIN itc_class_master AS d on d.fld_id=a.fld_class_id WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND d.fld_lock='0' AND a.fld_moduletype='2' AND b.fld_student_id='".$uid."' AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."') AND b.fld_flag='1'
													UNION ALL   SELECT b.fld_module_id FROM itc_class_rotation_modexpschedule_mastertemp AS a LEFT JOIN itc_class_rotation_modexpschedulegriddet AS b ON a.fld_id=b.fld_schedule_id LEFT JOIN itc_class_rotation_modexpscheduledate as g on b.fld_schedule_id=g.fld_schedule_id and b.fld_rotation=g.fld_rotation LEFT JOIN itc_module_master AS c ON b.fld_module_id=c.fld_id LEFT JOIN itc_class_master AS d on d.fld_id=a.fld_class_id WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND d.fld_lock='0' AND b.fld_student_id='".$uid."' AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."') AND b.fld_flag='1' AND b.fld_type='1'
													UNION ALL   SELECT b.fld_module_id FROM `itc_class_dyad_schedulemaster` AS a LEFT JOIN `itc_class_dyad_schedulegriddet` AS b ON a.fld_id=b.fld_schedule_id LEFT JOIN itc_module_master AS c ON b.fld_module_id=c.fld_id LEFT JOIN itc_class_master AS d on d.fld_id=a.fld_class_id LEFT JOIN itc_class_dyad_schedule_studentmapping AS e ON (e.fld_schedule_id=b.fld_schedule_id AND e.fld_student_id='".$uid."') WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND d.fld_lock='0' AND (b.fld_student_id='".$uid."' OR b.fld_rotation='0') AND e.fld_flag='1' AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."') AND b.fld_flag='1'		
													UNION ALL   SELECT a.fld_module_id FROM `itc_class_indassesment_master` AS a LEFT JOIN `itc_class_indassesment_student_mapping` AS b ON a.fld_id=b.fld_schedule_id LEFT JOIN itc_module_master AS c ON a.fld_module_id=c.fld_id LEFT JOIN itc_class_master AS d on d.fld_id=a.fld_class_id WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND d.fld_lock='0' AND a.fld_moduletype='7' AND b.fld_student_id='".$uid."' AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_start_date<='".date("Y-m-d")."')
													UNION ALL   SELECT b.fld_module_id FROM `itc_class_triad_schedulemaster` AS a LEFT JOIN `itc_class_triad_schedulegriddet` AS b ON a.fld_id=b.fld_schedule_id LEFT JOIN itc_module_master AS c ON b.fld_module_id=c.fld_id LEFT JOIN itc_class_master AS d on d.fld_id=a.fld_class_id LEFT JOIN itc_class_triad_schedule_studentmapping AS e ON (e.fld_schedule_id=b.fld_schedule_id AND e.fld_student_id='".$uid."') WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND d.fld_lock='0' AND (b.fld_student_id='".$uid."' OR b.fld_rotation='0') AND e.fld_flag='1' AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' ) AND b.fld_flag='1') AS w
                                                    UNION ALL   SELECT a.fld_id FROM itc_class_indasexpedition_master AS a LEFT JOIN itc_class_student_mapping as b on b.fld_class_id=a.fld_class_id WHERE b.fld_student_id='".$uid."' AND b.fld_flag = 1 AND a.fld_lock = 0 AND a.fld_delstatus = 0 AND a.fld_flag = 1");
			$allmodcount = $modqry->num_rows;
			if($allmodcount!=0 and $sessmasterprfid=='10')
			{
				?>
				<a class='skip btn mainBtnLarge' style="width:210px;" href='#library-modules' onclick="window.open('http://robo-review.pitsco.com');">
					<div class='icon-synergy-add-dark'></div>
					<div class='onBtn'><img src="img/robo_review-link.png" width="210" style="height: 98px;margin-left: -2px;margin-top: -52px;" /></div>
				</a>
				<?php
			}
            ?>
        </div>
    </div>
</section>

<?php
	@include("footer.php");
