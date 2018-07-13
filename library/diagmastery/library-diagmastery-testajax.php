<?php 
	@include("sessioncheck.php");
	/*
		Created By - Muthukumar. D
		Page - library-diagmastery-testajax.php
		History:
	*/

	$date = date("Y-m-d H:i:s");
	$oper = isset($method['oper']) ? $method['oper'] : '';
	
	/*--- Load Course Dropdown ---*/
	if($oper=="showcourse" and $oper != " " )
	{
		$subjectid = isset($method['subjectid']) ? $method['subjectid'] : '';
		?>
        <div class="selectbox">
            <input type="hidden" name="courseid" id="courseid" value=""  onchange="$(this).valid();" />
            <a class="selectbox-toggle" style="width:100%;" role="button" data-toggle="selectbox" href="#">
                <span class="selectbox-option input-medium" data-option=" " style="width:97%;">Select Course</span>
                <b class="caret1"></b>
            </a>
            <div class="selectbox-options">
                <input type="text" class="selectbox-filter" placeholder="Search Course" value="">
                <ul role="options" style="width:100%;">
                    <?php
                    $qry = $ObjDB->QueryObject("SELECT fld_id, fld_course_name FROM itc_course_master WHERE fld_delstatus='0' AND fld_subject_id='".$subjectid."'");
                    while($res=$qry->fetch_object()){?>
                        <li><a tabindex="-1" href="#" data-option="<?php echo $res->fld_id;?>" onclick="fn_showunit(<?php echo $res->fld_id;?>)"><?php echo $res->fld_course_name; ?></a></li>
                    <?php
                    }?>                        
                </ul>
            </div>
        </div>
		<?php
	}
	
	/*--- Load Lesson Dropdown ---*/
	if($oper=="showlesson" and $oper != " " )
	{
		$unitid = isset($method['unitid']) ? $method['unitid'] : '';
		?>
        <div class="selectbox">
            <input type="hidden" name="lessonid" id="lessonid" value=""  onchange="$(this).valid(); $('#btnstep').addClass('btn');	" />
            <?php 
			$categoryqry = $ObjDB->QueryObject("SELECT b.fld_id, CONCAT(b.fld_ipl_name,' ',c.fld_version) AS fld_ipl_name 
												FROM itc_ipl_master AS b 
												LEFT JOIN itc_ipl_version_track AS c ON c.fld_ipl_id=b.fld_id
												WHERE b.fld_delstatus= '0' AND b.fld_unit_id='".$unitid."' AND b.fld_access='1' 
													AND c.fld_zip_type='1' AND c.fld_delstatus='0' AND b.fld_id 
												NOT IN (SELECT fld_lesson_id FROM `itc_diag_question_mapping` 
												WHERE fld_delstatus='0') ORDER BY b.fld_ipl_name");
			?>
            <a class="selectbox-toggle" style="width:100%;" role="button" data-toggle="selectbox" href="#">
                <span class="selectbox-option input-medium" data-option=" " id="clearlesson" style="width:97%;"><?php if($categoryqry->num_rows > 0) { echo "Select Lesson"; ?><script>$('#ipls').show();</script><?php } else { echo "No More IPLs"; ?><script>$('#ipls').hide();</script><?php } ?></span>
                <b class="caret1"></b>
            </a>
            <?php
			if($categoryqry->num_rows > 0)
            {
			?>
            <div class="selectbox-options">
                <input type="text" class="selectbox-filter" placeholder="Search Lesson">
                <ul role="options" style="width:100%;">
                    <?php 
                    $categoryqry = $ObjDB->QueryObject("SELECT b.fld_id, CONCAT(b.fld_ipl_name,' ',c.fld_version) AS fld_ipl_name 
														FROM itc_ipl_master AS b
														LEFT JOIN itc_ipl_version_track AS c ON c.fld_ipl_id=b.fld_id 
														WHERE b.fld_delstatus= '0' AND b.fld_unit_id='".$unitid."' 
															AND b.fld_access='1' AND c.fld_zip_type='1' 
															AND c.fld_delstatus='0' AND b.fld_id 
														NOT IN (SELECT fld_lesson_id FROM `itc_diag_question_mapping` 
														WHERE fld_delstatus='0') ORDER BY b.fld_ipl_name");
					
                    
					while($rowcategory = $categoryqry->fetch_object())
					{
					?>
						<li><a tabindex="-1" href="#" data-option="<?php echo $rowcategory->fld_id;?>"><?php echo $rowcategory->fld_ipl_name; ?></a></li>
					<?php
					}
                    
                    ?>       
                </ul>
            </div>
            <?php
			}?>
        </div>
		<?php
	}
	
	/*--- Save/Update a Diag/Mastery (Step 1)---*/
	if($oper == "savediagmas" and $oper != '')
	{
		$editid = isset($method['editid']) ? $method['editid'] : '0'; //Diagmastery id		
		$unitid = isset($method['unitid']) ? $method['unitid'] : '';
		$lessonid = isset($method['lessonid']) ? $method['lessonid'] : '';
		$lessonweight = isset($method['lessonweight']) ? $method['lessonweight'] : '';
		$tags = isset($method['tags']) ? $method['tags'] : '';	
		
		if($editid!=0)
		{
			$ObjDB->NonQuery("UPDATE itc_diag_question_mapping 
							SET fld_unit_id='".$unitid."', fld_lesson_id='".$lessonid."', 
								fld_lesson_weight='".$lessonweight."', fld_updated_by='".$uid."', 
								fld_updated_date='".$date."' 
							WHERE fld_id='".$editid."' AND fld_delstatus='0'");
			
			/*---tags------*/
			$ObjDB->NonQuery("UPDATE itc_main_tag_mapping 
							SET fld_access='0', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' 
							WHERE fld_tag_type='22' AND fld_item_id='".$editid."' AND fld_tag_id 
							IN(SELECT fld_id FROM itc_main_tag_master WHERE fld_created_by='".$uid."' AND fld_delstatus='0')");
			
			fn_tagupdate($tags,22,$editid,$uid);
		}
		else
		{
			$ObjDB->NonQuery("INSERT INTO itc_diag_question_mapping (fld_unit_id, fld_lesson_id, fld_lesson_weight, 
								fld_created_by, fld_created_date) 
							VALUES ('".$unitid."', '".$lessonid."', '".$lessonweight."', '".$uid."', '".$date."')");
			
			$editid = $ObjDB->SelectSingleValueInt("SELECT MAX(fld_id) 
													FROM itc_diag_question_mapping 
													WHERE fld_delstatus='0'");
			
			fn_taginsert($tags,22,$editid,$uid);
		}
		
		echo "success~".$editid;
	}
	
	/*--- Save/Update a Diag/Mastery Questions ---*/
	if($oper == "savequestions" and $oper != '')
	{
		$diagmasteryid = isset($method['diagmasteryid']) ? $method['diagmasteryid'] : '';
		$type = isset($method['type']) ? $method['type'] : '';//Type = 1/2/3;
		$questionsid = isset($method['questionsid']) ? $method['questionsid'] : '';
		$questionsid = explode(',',$questionsid);
		
		if($type==1)//Diagnostic
			$ObjDB->NonQuery("UPDATE itc_diag_question_mapping SET fld_diag_ques1a='".$questionsid[0]."', 
								fld_diag_ques1b='".$questionsid[1]."', fld_diag_ques2a='".$questionsid[2]."', 
								fld_diag_ques2b='".$questionsid[3]."', fld_diag_ques3a='".$questionsid[4]."', 
								fld_diag_ques3b='".$questionsid[5]."', fld_updated_by='".$uid."', 
								fld_updated_date='".$date."', fld_step_id='2' 
							WHERE fld_id='".$diagmasteryid."' AND fld_delstatus='0'");
		
		if($type==2)//Mastery1
			$ObjDB->NonQuery("UPDATE itc_diag_question_mapping 
							SET fld_mast1_ques1a='".$questionsid[0]."', 
								fld_mast1_ques1b='".$questionsid[1]."', 
								fld_mast1_ques2a='".$questionsid[2]."', 
								fld_mast1_ques2b='".$questionsid[3]."', 
								fld_mast1_ques3a='".$questionsid[4]."', fld_mast1_ques3b='".$questionsid[5]."', 
								fld_updated_by='".$uid."', fld_updated_date='".$date."', fld_step_id='3'
							 WHERE fld_id='".$diagmasteryid."' AND fld_delstatus='0'");
		
		if($type==3)//Mastery2
			$ObjDB->NonQuery("UPDATE itc_diag_question_mapping 
							SET fld_mast2_ques1a='".$questionsid[0]."', fld_mast2_ques1b='".$questionsid[1]."', 
								fld_mast2_ques2a='".$questionsid[2]."', fld_mast2_ques2b='".$questionsid[3]."', 
								fld_mast2_ques3a='".$questionsid[4]."', fld_mast2_ques3b='".$questionsid[5]."', 
								fld_updated_by='".$uid."', fld_updated_date='".$date."', fld_step_id='4', fld_access='1' 
							WHERE fld_id='".$diagmasteryid."' AND fld_delstatus='0'");
		
		echo "success~".$diagmasteryid;
	}
	
	/*--- Save/Update a Review ---*/
	if($oper == "savereview" and $oper != '')
	{
		$diagmasteryid = isset($method['diagmasteryid']) ? $method['diagmasteryid'] : '';
		
		$ObjDB->NonQuery("UPDATE itc_diag_question_mapping 
						SET fld_access='1', fld_updated_by='".$uid."', fld_updated_date='".$date."', fld_step_id='1' 
						WHERE fld_id='".$diagmasteryid."' AND fld_delstatus='0'");
		
		echo "success~".$diagmasteryid;
	}
	
	/*--- Delete a Test  ---*/
	if($oper == "deletetest" and $oper != '')
	{
		$diagmasid = isset($method['diagmasid']) ? $method['diagmasid'] : '';
		
		$ObjDB->NonQuery("UPDATE itc_diag_question_mapping 
						SET fld_delstatus='0', fld_deleted_date='".date("Y-m-d H:i:s")."', fld_deleted_by='".$uid."' 
						WHERE fld_id='".$diagmasid."'");
		echo "exists";
	}

	@include("footer.php");