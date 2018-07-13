<?php
@include("sessioncheck.php");

$id = (isset($_POST['id'])) ? $_POST['id'] : 0;
$id = explode(",",$id);

if($id[0]==0){	
	$unitid = '';
	$lessonid = '';
	$testid = '';	
	$unitname = "Select Unit";
	$lessonname = "Select Lesson";
	$testtype = "Select Test Type";
}
else{
	$qrydiagdetails = $ObjDB->QueryObject("SELECT a.`fld_id` AS quesid, a.`fld_unit_id` AS unitid, a.`fld_lesson_id` AS lessonid, 
													a.`fld_question_type_id` AS testid, b.`fld_unit_name` AS unitname, 
													CONCAT(c.`fld_ipl_name`,' ',e.`fld_version`) AS lessonname, d.`fld_question_type` AS testtype 
											FROM `itc_question_details` AS a 
											LEFT JOIN `itc_question_type` AS d ON a.`fld_question_type_id` = d.`fld_id`
											LEFT JOIN `itc_unit_master` AS b ON a.`fld_unit_id` = b.`fld_id` 
											LEFT JOIN `itc_ipl_master` AS c  ON a.`fld_lesson_id` = c.`fld_id`
											LEFT JOIN `itc_ipl_version_track` AS e ON c.`fld_id` = e.`fld_ipl_id`  
											WHERE a.`fld_id` = '".$id[0]."' AND a.`fld_delstatus` = '0' AND b.`fld_delstatus` = '0' AND c.`fld_delstatus` = '0' 											  AND c.`fld_access` = '1' AND b.`fld_activestatus` = '0' AND d.`fld_delstatus` = '0' AND e.`fld_zip_type` = '1'
											  AND e.`fld_delstatus`='0'");
	if($qrydiagdetails->num_rows>0)
	{
		while($resdiagdetails = $qrydiagdetails->fetch_assoc()){			
			extract($resdiagdetails);			
		}
	}
}
?>
<script language="javascript" type="text/javascript">
	$('#quesdetails').addClass("active-first");
	$('#review').removeClass("active-last");
	$('#newques').removeClass("active-mid");
</script>
<section data-type='#library-questions' id='library-questions-questiondetails'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="darkTitle">New Question Details</p>
                <p class="darkSubTitle">&nbsp;</p>
            </div>
        </div>
        
        <div class='row rowspacer'>
            <div class='twelve columns formBase'>
                <div class='row'>
                    <div class='eleven columns centered insideForm'>
                        <form method='post' id="questiondetailsform" name="questiondetailsform">
                            <div class='row'>
                                <div class='six columns'>
                                	Test Type<span class="fldreq">*</span>
                                    <dl class='field row' id='testid'>  
                                        <dt class='dropdown'> 
                                            <div id="test">
                                                <div class="selectbox">	<!-- Test Type Drop Down -->
                                                    <input type="hidden" name="testtypeid" id="testtypeid" value="<?php echo $testid;?>" onchange="fn_toggleformdet(this.value);$(this).valid();"/>
                                                    <a class="selectbox-toggle" style="width:410px;" role="button" data-toggle="selectbox" href="#">
                                                        <span class="selectbox-option input-medium" data-option="<?php echo $testid;?>" id="clearcourse" style="width:400px;"><?php echo $testtype; ?></span>
                                                        <b class="caret1"></b>
                                                    </a>
                                                    <div class="selectbox-options">                                                        
                                                        <ul role="options" style="width:410px;">
															<?php 
															$qry = $ObjDB->QueryObject("SELECT fld_id as tid, fld_question_type as tname 
																						FROM itc_question_type 
																						WHERE fld_delstatus=0 AND fld_id<>'4' 
																						ORDER BY fld_id ASC");
															if($qry->num_rows>0){
																while($row = $qry->fetch_assoc())
																{
																	extract($row);
																	?>
																	<li><a tabindex="-1" href="#" data-option="<?php echo $tid;?>" ><?php echo $tname; ?></a></li>
																	<?php
																}
															}?>     
                                                        </ul>
                                                    </div>
                                                </div>	<!-- Test Type Drop Down -->
                                            </div>
                                        </dt>
                                    </dl>
                                </div>
                            </div>                            
                            <div class='row rowspacer' id="divdiagmastery">
                                <div class='six columns'>
                                	Unit<span class="fldreq">*</span>
                                    <dl class='field row' id='unid'>  
                                        <dt class='dropdown' id="unit"> 
                                            <div class="selectbox"> <!-- Unit Drop Down -->
                                                <input type="hidden" name="unitid" id="unitid" value="<?php echo $unitid;?>"  onchange="$(this).valid();" />
                                                <a class="selectbox-toggle" style="width:410px;" role="button" data-toggle="selectbox" href="#">
                                                    <span class="selectbox-option input-medium" data-option="<?php echo $unitid;?>" id="clearcourse" style="width:400px;"><?php echo $unitname; ?></span>
                                                    <b class="caret1"></b>
                                                </a>                                                
                                                <div class="selectbox-options">
                                                    <input type="text" class="selectbox-filter" placeholder="Search Unit">
                                                    <ul role="options" style="width:410px;">
                                                        <?php 
                                                        $qry = $ObjDB->QueryObject("SELECT fld_id AS uid, fld_unit_name AS uname 
																					FROM itc_unit_master 
																					WHERE fld_delstatus='0' AND fld_activestatus='0' ORDER BY fld_unit_name");
                                                        if($qry->num_rows>0){
                                                            while($row = $qry->fetch_assoc())
                                                            {
                                                                extract($row);
                                                                ?>
                                                                <li><a tabindex="-1" href="#" data-option="<?php echo $uid;?>" onclick="fn_showlesson(<?php echo $uid;?>)"><?php echo $uname; ?></a></li>
                                                                <?php
                                                            }
                                                        }?>     
                                                    </ul>
                                                </div>                                                
                                            </div>	<!-- Unit Drop Down -->
                                        </dt>
                                    </dl>
                                </div>
                                <div class='six columns'>
                                	Lesson<span class="fldreq">*</span>
                                    <dl class='field row' id='iplid'>  
                                        <dt class='dropdown' id="lesson"> 
                                            <div class="selectbox"> <!-- Lesson Drop Down -->
                                                <input type="hidden" name="lessonid" id="lessonid" value="<?php echo $lessonid;?>"  onchange="$(this).valid();" />
                                                <a class="selectbox-toggle" style="width:410px;" role="button" data-toggle="selectbox" href="#">
                                                    <span class="selectbox-option input-medium" data-option="<?php echo $lessonid;?>" id="clearcourse" style="width:400px;"><?php echo $lessonname; ?></span>
                                                    <b class="caret1"></b>
                                                </a>
                                                <?php if($id[0] != 0){?>
                                                    <div class="selectbox-options">
                                                        <input type="text" class="selectbox-filter" placeholder="Search Lesson">
                                                        <ul role="options" style="width:410px;">
                                                            <?php 
                                                            $qry = $ObjDB->QueryObject("SELECT a.`fld_id` AS lid, 
																						CONCAT(a.`fld_ipl_name`,' ', b.`fld_version`) AS lname 
                                                                                        FROM `itc_ipl_master` a
                                                                                        LEFT JOIN `itc_ipl_version_track` b ON a.`fld_id`=b.`fld_ipl_id`
                                                                                        WHERE b.`fld_zip_type`='1' AND a.`fld_delstatus`='0' 
																						AND a.`fld_access`='1' ORDER BY a.`fld_ipl_name`");
                                                            if($qry->num_rows>0){
                                                                while($row = $qry->fetch_assoc())
                                                                {
                                                                    extract($row);
                                                                    ?>
                                                                    <li><a tabindex="-1" href="#" data-option="<?php echo $lid;?>" ><?php echo $lname; ?></a></li>
                                                                    <?php
                                                                }
                                                            }?>    
                                                        </ul>
                                                    </div>
                                                <?php }?>
                                            </div>	<!-- Lesson Drop Down -->
                                        </dt>
                                    </dl>                                      
                                </div>
                            </div>
                            
                            <div class='row rowspacer' id="divassessment" style="display:none;">
                                <div class='six columns'></div>
                            </div>
                            
                            <div class='row rowspacer'>
                                <div class='six columns'>
                                    <div class='row'>                                        
                                    </div>
                                </div>
                                <div class='twelve columns'>
                                    <div class='row'>
                                    	<input <?php if($id[0] == 0){ echo "disabled class=\"darkButtonDisabled\""; } else { echo "class=\"darkButton\""; } ?> type="button" id="btnstep"  style="width:200px; height:42px;float:right;" value="Next Step" onClick="fn_savestep1(<?php echo $id[0];?>);" />
                                    </div>
                                </div>
                            </div>
                        </form>
						<script type="text/javascript" language="javascript">
							/****addd category validate*****/
							$(function(){
								$("#questiondetailsform").validate({
									ignore: "",
									errorElement: "dd",
									errorPlacement: function(error, element) {
										$(element).parents('dl').addClass('error');
										error.appendTo($(element).parents('dl'));
										error.append('<span class="caret"></span>');
										error.addClass('msg').css('width','420px');
									},
									rules: { 
										testtypeid: { required: true },										
										unitid: { required: function(element) {	return ($('#testtypeid').val() <= 3);  } },
										lessonid: { required: function(element) {	return ($('#testtypeid').val() <= 3);  } }									
									}, 
									messages: { 
										testtypeid:{ required:  "Please Select Test Type" },										
										unitid: {  required: "Please Select Unit" },
										lessonid:{ required: "Please Select Lesson" }
									},
									highlight: function(element, errorClass, validClass) {
										$(element).addClass(errorClass).removeClass(validClass);
										$(element).append('<span class="caret"></span>');
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
	@include("footer.php");