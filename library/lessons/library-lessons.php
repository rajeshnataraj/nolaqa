<?php 
/*------
	Page - library-lessons
	Description:
		List the lessons according to the tag well filter.
	
	Actions Performed:	
		Tag well - Shows the lesson in fullscreen
	
	History:	
		
------*/

	@include("sessioncheck.php");
	
	/*------
		sid = subjectid_subject or courseid_course or unitid_unit or id for custom tag	
	------*/
	$sid = isset($_POST['sid']) ? $_POST['sid'] : '0';
	
	$sqry = '';
	if($sid != 0){
		$sid = explode(',',$sid); // split the id's 
		for($i=0;$i<sizeof($sid);$i++){
			$id = explode('~',$sid[$i]); //split the id and conditional name
			
			if(isset($id[1])and $id[1] == 'unit'){	// check the conditional name and concatenate the field name according to it.			 
				$sqry.= " AND b.fld_unit_id =".$id[0];
			}
			else if(isset($id[1])and $id[1] == 'lesson'){
				$sqry.= " AND b.fld_id =".$id[0];
			}
			else{
				//get lessons for the custom tag
				$itemqry = $ObjDB->QueryObject("SELECT fld_item_id FROM itc_main_tag_mapping WHERE fld_tag_id='".$sid[$i]."' AND fld_access='1' AND fld_tag_type='1'");
				$sqry = "AND (";
				$j=1;
				while($itemres = $itemqry->fetch_assoc()){
					extract($itemres);
					if($j==$itemqry->num_rows){
						$sqry.=" b.fld_id=".$fld_item_id.")";
					}
					else{
						$sqry.=" b.fld_id=".$fld_item_id." OR";
					}
					$j++;
				}
			}
		}		
	}
?>
<script type="text/javascript" charset="utf-8">		
	$.getScript("library/lessons/library-lessons-newlesson.js");	
	$(function(){				
		var t4 = new $.TextboxList('#form_tags_lessons', {
			startEditableBit: false,
			inBetweenEditableBits: false,
			plugins: {
				autocomplete: {
					onlyFromValues:true,
					queryRemote: true,
					remote: { url: 'autocomplete.php', extraParams: "oper=search&tag_type=1&subject=1&course=1&unit=1&lesson=1" },
					placeholder: ''
				}
			},
			bitsOptions:{editable:{addKeys: [188]}}													
		});																	
		
		t4.addEvent('bitAdd',function(bit) {
			fn_loadlesson();
		});
		
		t4.addEvent('bitRemove',function(bit) {
			fn_loadlesson();
		});						
	});	

	function fn_loadlesson(){
		var sid = $('#form_tags_lessons').val();
		$("#lessonlist").load("library/lessons/library-lessons.php #lessonlist > *",{"sid":sid});
		removesections('#library-lessons');
	}
</script>
<section data-type='2home' id='library-lessons'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="darkTitle">Lessons</p>
                <p class="darkSubTitle">&nbsp;</p>
            </div>
        </div>
        <!--start of new filter-->
        <div class='row'>
            <div class='twelve columns'>
                <!--<p class="<?php if($sessmasterprfid == '10'){ echo "filterDarkTitle"; }else { echo "filterLightTitle"; } ?>">To filter this list, search by <?php if($sessmasterprfid==2 || $sessmasterprfid==3){?>Tag Name, <?php }?>Unit Name, and Lesson Name.</p>-->
                <p class="<?php if($sessmasterprfid == '10'){ echo "filterDarkTitle"; }else { echo "filterLightTitle"; } ?>">Search by Unit name, Lesson name, or Tag name in the search box to find a specific Lesson, or browse through the Lessons below.</p>
                <div class="tag_well">
                	<input type="text" name="form_tags_subjects" value="" id="form_tags_lessons" />
                </div>
            </div>
        </div>
        <!--end of new filter-->
        <div class='row buttons rowspacer' id="lessonlist">
        	<?php if($sessmasterprfid == 2 || $sessmasterprfid == 3) { ?>
            <a class='skip btn mainBtn' href='#library-newlesson' id='btnlibrary-lessons-newlesson'>
                <div class='icon-synergy-add-dark'></div>
                <div class='onBtn'>New<br />Lesson</div>
            </a>            
            <?php }
			if($sessmasterprfid == 2 || $sessmasterprfid == 3)  //Admin level users
			{	
				
											$qry = $ObjDB->QueryObject("SELECT b.`fld_id` AS lessonid, b.`fld_ipl_icon` AS lessonicon, CONCAT(b.`fld_ipl_name`,' ',a.`fld_version`) AS 
						lessonname, fn_shortname(CONCAT(b.`fld_ipl_name`,' ',a.`fld_version`),1) AS shortname,
						a.fld_zip_name AS zipname FROM `itc_ipl_master` AS b
					LEFT JOIN `itc_ipl_version_track` a ON b.`fld_id`=a.`fld_ipl_id` 
					WHERE a.`fld_delstatus` = '0' AND a.`fld_zip_type`='1' AND b.`fld_delstatus`='0' ".$sqry." 
					GROUP BY b.`fld_id`	ORDER BY b.`fld_ipl_name`");
			}
			else if($sessmasterprfid == 10){	//Student level users						
					if($trialuser==1){
						// for trial user student
						$qry = $ObjDB->QueryObject("SELECT b.fld_id AS lessonid, CONCAT(b.fld_ipl_name,' ', d.fld_version) AS lessonname, fn_shortname 
													(CONCAT(b.fld_ipl_name,' ',d.fld_version),1) AS shortname, 
													b.fld_ipl_icon AS lessonicon, d.fld_zip_name AS zipname FROM itc_license_cul_mapping AS a 
													LEFT JOIN itc_license_track AS c ON a.fld_license_id = c.fld_license_id 
													RIGHT JOIN itc_ipl_master AS b ON a.fld_lesson_id=b.fld_id 
													LEFT JOIN `itc_ipl_version_track` d ON b.`fld_id`=d.`fld_ipl_id`
													WHERE c.fld_district_id='".$districtid."' AND c.fld_school_id='".$schoolid."' 
														AND c.fld_user_id='".$indid."' AND c.fld_delstatus='0' AND '".date("Y-m-d")."' 
														BETWEEN c.fld_start_date AND c.fld_end_date AND a.fld_active='1' 
														AND b.fld_delstatus='0' AND d.`fld_zip_type`='1' AND d.`fld_delstatus`='0' ".$sqry." 
													GROUP BY b.fld_id ORDER BY b.fld_ipl_name");
					}
					else {	
						// Lesson listed based on the class assigned for the student and availability of the license time period
						$qry = $ObjDB->QueryObject("SELECT b.fld_id AS lessonid, CONCAT(b.`fld_ipl_name`,' ',f.`fld_version`) AS lessonname, 				
															fn_shortname(CONCAT(b.`fld_ipl_name`,' ',f.`fld_version`),1) AS shortname, 
															b.fld_ipl_icon AS lessonicon, f.fld_zip_name AS zipname
													FROM itc_class_sigmath_student_mapping AS a 
													LEFT JOIN itc_class_sigmath_lesson_mapping AS c ON a.fld_sigmath_id=c.fld_sigmath_id 
													LEFT JOIN itc_ipl_master AS b ON b.fld_id=c.fld_lesson_id 
													LEFT JOIN `itc_ipl_version_track` f ON b.`fld_id`= f.`fld_ipl_id`
													LEFT JOIN itc_class_sigmath_master AS d ON d.fld_id=c.fld_sigmath_id 
													LEFT JOIN itc_class_master AS e ON e.fld_id=d.fld_class_id 
													WHERE a.fld_student_id='".$uid."' AND a.fld_flag='1' AND c.fld_flag='1' AND b.fld_delstatus='0' 
														AND b.fld_access='1' AND d.fld_delstatus='0' AND e.fld_delstatus='0' AND f.`fld_zip_type`='1' 
														AND f.`fld_delstatus`='0' AND c.fld_license_id IN (SELECT fld_license_id FROM itc_license_track 
														WHERE fld_school_id='".$schoolid."' 
														AND fld_user_id='".$indid."' AND fld_start_date<='".date("Y-m-d")."' 
														AND fld_end_date >='".date("Y-m-d")."') ".$sqry." 
													GROUP BY b.fld_id ORDER BY b.fld_ipl_name");
					}
			}
			else     //other than student and admin level users
			{				
				$qry = $ObjDB->QueryObject("SELECT b.fld_id AS lessonid, CONCAT(b.`fld_ipl_name`,' ',d.`fld_version`) AS lessonname,
												fn_shortname(CONCAT(b.`fld_ipl_name`,' ',d.`fld_version`),1) AS shortname, b.fld_ipl_icon AS lessonicon,
												d.fld_zip_name AS zipname 
											FROM itc_license_cul_mapping AS a 
											LEFT JOIN itc_license_track AS c ON a.fld_license_id = c.fld_license_id 
											RIGHT JOIN itc_ipl_master AS b ON a.fld_lesson_id = b.fld_id 
											LEFT JOIN `itc_ipl_version_track` AS d ON b.`fld_id`= d.`fld_ipl_id` 
											WHERE c.fld_district_id='".$districtid."' AND c.fld_school_id='".$schoolid."' 
												AND c.fld_user_id='".$indid."' AND c.fld_delstatus='0' 
												AND '".date("Y-m-d")."' BETWEEN c.fld_start_date AND c.fld_end_date AND a.fld_active='1' 
												AND b.fld_delstatus='0' AND d.`fld_zip_type`='1' AND d.`fld_delstatus`='0' ".$sqry." 
											GROUP BY b.fld_id ORDER BY b.fld_ipl_name");					
			}
			// To display the lessons
			if($qry->num_rows>0){
				while($res=$qry->fetch_assoc()){
					extract($res);
			?>
					<a class='skip btn mainBtn' href='#Library-Lessons' name="<?php echo $lessonid.",lesson,".$zipname; ?>" id='btnlibrary-lessons-actions'>
						<div class='icon-synergy-lessons'><?php if($lessonicon!='' and $lessonicon!='no-image.png') {?><img class="thumbimg" src="<?= CONTENT_URL."/iplicon/".$lessonicon ?>" width="40" height="40" /><?php }?></div>
						<div class='tooltip onBtn' title="<?php echo $lessonname;?>"><?php echo $shortname; ?></div>
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