<?php
@include("sessioncheck.php");

$menuid= isset($method['id']) ? $method['id'] : '0';
?>
<section data-type='2home' id='tools'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Tools</p>
                <p class="dialogSubTitleLight">&nbsp;</p>
            </div>
        </div>
        
        <div class='row buttons'>
			<?php
            $qrymenuname=$ObjDB->QueryObject("SELECT a.fld_id,a.fld_menu_name,a.fld_class,a.fld_href,a.fld_hrefid,a.fld_divclass 
			                                 FROM itc_main_menu AS a 
											 RIGHT JOIN itc_menu_privileges AS b ON a.fld_id=b.fld_menu_id 
											 WHERE b.fld_profile_id='".$sessprofileid."' AND b.fld_access='1' AND a.fld_main_menu_id='".$menuid."' 
											 AND a.fld_delstatus='0' AND b.fld_delstatus='0' ORDER BY  a.fld_position ASC");
            
            while($rowmenuname=$qrymenuname->fetch_object())
            {
				$menuname=$rowmenuname->fld_menu_name;
				$menuid=$rowmenuname->fld_id;
				$class=$rowmenuname->fld_class;
				$href=$rowmenuname->fld_href;
				$id=$rowmenuname->fld_hrefid;
				$divclass=$rowmenuname->fld_divclass;
                                
                                if($sessmasterprfid=='10'){
                                $expqry = $ObjDB->QueryObject("SELECT w.* FROM (SELECT a.fld_exp_id AS fld_module_id FROM `itc_class_indasexpedition_master` AS a LEFT JOIN `itc_class_exp_student_mapping` AS b ON a.fld_id=b.fld_schedule_id LEFT JOIN itc_exp_master AS c ON a.fld_exp_id=c.fld_id LEFT JOIN itc_class_master AS d on d.fld_id=a.fld_class_id WHERE a.fld_delstatus='0' AND b.fld_flag='1' AND d.fld_delstatus='0' AND c.fld_delstatus='0' AND d.fld_lock='0' AND b.fld_student_id='".$uid."' AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."')
                                                                    UNION ALL   SELECT b.fld_expedition_id FROM `itc_class_rotation_expschedule_mastertemp` AS a LEFT JOIN `itc_class_rotation_expschedulegriddet` AS b ON a.fld_id=b.fld_schedule_id LEFT JOIN itc_class_rotation_expscheduledate as g on b.fld_schedule_id=g.fld_schedule_id and b.fld_rotation=g.fld_rotation WHERE a.fld_delstatus='0' AND b.fld_student_id='".$uid."' AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' ) AND b.fld_flag='1'
                                                                    UNION ALL   SELECT b.fld_module_id FROM `itc_class_rotation_modexpschedule_mastertemp` AS a LEFT JOIN `itc_class_rotation_modexpschedulegriddet` AS b ON a.fld_id=b.fld_schedule_id LEFT JOIN itc_class_rotation_modexpscheduledate as g on b.fld_schedule_id=g.fld_schedule_id and b.fld_rotation=g.fld_rotation LEFT JOIN itc_exp_master AS c ON b.fld_module_id=c.fld_id LEFT JOIN itc_class_master AS d on d.fld_id=a.fld_class_id 
                                                                    WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND d.fld_lock='0' AND b.fld_student_id='".$uid."' AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."') AND b.fld_flag='1' AND b.fld_type='2') AS w"); 
                                $expcount = $expqry->num_rows;
                                if(($menuid == 54 OR $menuid == 55) AND $expcount > 0 OR $menuid!=54 AND $menuid!=55){
				?>
				<a class='<?php echo $class;?>' href='<?php echo $href;?>' id='<?php echo $id;?>' name='<?php echo $menuid;?>'>
                    <div class='<?php echo $divclass;?>'></div>
                                            <div  class='onBtn'><?php if($menuid==25 AND $sessmasterprfid==2){ echo "Tags";}else{ echo ucfirst($menuname); }?></div>
				</a>
				<?php
            }
                                }
                                else {
            ?>
                                        <a class='<?php echo $class;?>' href='<?php echo $href;?>' id='<?php echo $id;?>' name='<?php echo $menuid;?>'>
                                        <div class='<?php echo $divclass;?>'></div>
                                        <div  class='onBtn'><?php if($menuid==25 AND $sessmasterprfid==2){ echo "Tags";}else{ echo ucfirst($menuname); }?></div>
                                        </a>
				<?php
                                    
                                }
                                
            }
            ?>
        </div>
    </div>
</section>
<?php
	@include("footer.php");
