<?php
	@include("sessioncheck.php");
?>

<script type='text/javascript'>
	$.getScript("tools/message/tools-message-message.js");
</script>

<section data-type='#teach-studentlock' id='teach-studentlock'>
    <div class='container'>
        	<div class='row'>
                <div class="span10">
                    <p class="dialogTitle">Locked Students</p>
                    <p class="dialogSubTitleLight">Below is a list of students who are currently locked.</p>
                </div>
        	</div>
        
        <div class='row rowspacer'>
            <div class='span10 offset1' > 
                <table class='table table-hover table-striped table-bordered'>
                    <thead class='tableHeadText'>
                        <tr style="cursor:default;">
                            <th style="padding-left:15px;">Student Name</th>
	                        <th style="padding-left:15px;">Schedule Name</th>
                            <th style="padding-left:15px;">Lesson Name</th>
                             <th style="padding-left:15px;">Unlock</th>
                        </tr>
                    </thead>
                	<tbody>
                    <div id="studentdiv"> 
                    <?php
					$qry = $ObjDB->QueryObject("SELECT a.fld_id AS maxid, a.fld_student_id AS studentid, a.fld_lesson_id AS lessonid, a.fld_test_type AS stype,
												CONCAT(e.fld_fname,' ',e.fld_lname) AS studentname, f.fld_ipl_name AS lessonname, a.fld_schedule_id
												FROM itc_assignment_sigmath_master AS a
												LEFT JOIN itc_class_teacher_mapping AS b ON a.fld_class_id=b.fld_class_id
												LEFT JOIN itc_class_master AS d ON d.fld_id=a.fld_class_id
												LEFT JOIN itc_user_master AS e ON e.fld_id=a.fld_student_id
												LEFT JOIN itc_ipl_master AS f ON f.fld_id=a.fld_lesson_id
												WHERE a.fld_type=5 AND b.fld_teacher_id='".$uid."' AND b.fld_flag='1' AND d.fld_delstatus='0'
												AND e.fld_delstatus='0' AND f.fld_delstatus='0' AND a.fld_status='0' AND a.fld_test_type<>2 AND a.fld_test_type<>5");
					if($qry->num_rows>0){
					while($res = $qry->fetch_assoc()){
					extract($res);
					
					if($stype==1)
						$tablename = "itc_class_sigmath_master";
					if($stype==2)
						$tablename = "itc_class_rotation_schedule_mastertemp";
					if($stype==5)
						$tablename = "itc_class_indassesment_master";
					
					$sname = $ObjDB->SelectSingleValue("SELECT fld_schedule_name FROM ".$tablename." WHERE fld_id='".$fld_schedule_id."'");
					?>                               
                       <tr >
                           <td style="padding-left:15px; cursor:default;"><?php echo $studentname; ?></td>
                           <td style="padding-left:15px; cursor:default;"><?php echo $sname; ?></td>
                           <td style="padding-left:15px; cursor:default;"><?php echo $lessonname; ?></td>
                           <td style="padding-left:35px; " class="icon-synergy-locked " onClick="fn_unlock(<?php echo $maxid; ?>);"></td>
                           
                       </tr>
                     </div>  
                    <?php 
									} // while ends
                                } // if ends
								else
								{?>
									<tr><td>No Student To Unlock <script>$('#loka').hide();</script></td></tr>
						 <?php }
                            ?>
                  	</tbody>
            	</table>
                <script language="javascript" type="text/javascript">
                                function fn_unlock(id){					
                                    var dataparam = "oper=unlock&maxid="+id;	 
                                    $.ajax({
                                        type: "POST",
                                        url: 'assignment/sigmath/assignment-sigmath-test-ajax.php',
                                        data: dataparam,
                                        beforeSend:function(){			
                                            showloadingalert("Loading, please wait.");
                                        },
                                        success: function(data){			
                                            $('#lockstudent_'+id).hide();
                                            closeloadingalert();
											showloadingalert("Student has been Unlocked Successfully");
											closeloadingalert();
											setTimeout('removesections("#home");',500);
											setTimeout('showpages("studentlock","studentlock.php")',1000);
                                    	}
                                    });	
                                }
                            </script>
            </div>
        </div>
    </div>
</section>
<?php
	@include("footer.php");