<?php
	@include("sessioncheck.php");
	
	$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
	$id=explode(",",$id);
	$contenttype=1;
	$startdate='';
	$enddate='';
	$activityassignqry='';
	$activityassignqryrowcnt=0;
	if($id[1]!=0)
	{
		$activityassignqry=$ObjDB->QueryObject("SELECT a.fld_class_id AS assignclassid, a.fld_start_date AS startdate, a.fld_end_date AS enddate, 
		                                       b.fld_class_name  AS classname 
											   FROM itc_activity_student_mapping AS a
											   LEFT JOIN itc_class_master AS b ON b.fld_id=a.fld_class_id
											   WHERE a.fld_activity_id='".$id[0]."' AND a.fld_class_id='".$id[1]."' AND a.fld_start_date='".$id[2]."' 
											   AND a.fld_flag='1' AND a.fld_created_by='".$uid."'");
		$activityassignqryrowcnt=$activityassignqry->num_rows;
		if($activityassignqryrowcnt>0){
			$row = $activityassignqry->fetch_assoc();
			extract($row);
		}
	}
?>
<section data-type='#library-activities' id='library-activities-addstudents'>
	<script>
		$('#contenttype li').click(function () {
			if($(this).val()==1)
			{
				$('#classasin').show();
				$('#studentasin').hide();
				
			}
			else if($(this).val()==2)
			{
				$('#classasin').hide();
				$('#studentasin').show();
			}
		});
		<?php if($activityassignqryrowcnt>0) { ?>
				fn_showstudentlist(<?php echo $id[1];?>,<?php echo $id[0]; ?>,'<?php echo $id[2]; ?>');
		<?php } ?>
	</script>
    <div class="container">
        <div class="row">
            <div class="twelve columns">
                <p class="dialogTitle">Assign this Activity</p>
                <p class="dialogSubTitleLight">Assign this activity to class or students below.</p>
                <div class="row rowspacer"></div>
            </div>
        </div>   
         
        <div class="row rowspacer">
            <div class="twelve columns formBase">
                <div class="row">
                    <div class="eleven columns centered insideForm">
                        <form name="frmselectstandard" id="frmselectstandard">
                        	<input type="hidden" id="predate" name="predate" value="<?php if($startdate!='' && $startdate!='0000-00-00') echo date("m/d/Y",strtotime($startdate)); ?>" />
                            <div class="row rowspacer">
                            	<div class='three columns'>
                                	Start date<span class="fldreq">*</span>
                                    <dl class='field row'>
                                        <dt class='text'>
                                             <input id="sdate1" readonly name="sdate1" placeholder='Start Date' type='text' value="<?php if($startdate!='' && $startdate!='0000-00-00') echo date("m/d/Y",strtotime($startdate)); ?>" >
                                        </dt>                                        
                                    </dl>
                                </div>
                                <div class='three columns'>
                                	End date<span class="fldreq">*</span>
                                    <dl class='field row'>
                                        <dt class='text'>
                                             <input id="edate1" readonly name="edate1" placeholder='End Date' type='text' value="<?php if($enddate!='' && $enddate!='0000-00-00') echo date("m/d/Y",strtotime($enddate)); ?>" >
                                        </dt>                                        
                                    </dl>
                                </div>
                                <div class="six columns">
                                	Class<span class="fldreq">*</span>
                                    <dl class="field row">   
                                        <dt id="dpdocuments" class="dropdown">   
                                            <div class="selectbox">
                                                <input type="hidden" name="classids" id="classids" onchange="$('#classid').val(this.value)" value="" >
                                                <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                                                <span class="selectbox-option input-medium" data-option="<?php if($activityassignqryrowcnt>0) { echo $assignclassid; }?>" style="width:97%"><?php if($activityassignqryrowcnt>0) { echo $classname; } else { echo "Select class" ;}?></span>
                                                <b class="caret1"></b>
                                                </a>
                                                <div class="selectbox-options">
                                                    <input type="text" class="selectbox-filter" placeholder="Search Class">
                                                    <ul role="options" style="width:100%">
														<?php 
                                                        $qry = $ObjDB->QueryObject("SELECT fld_id AS classid, fld_class_name AS classname 
														                           FROM itc_class_master 
														                            WHERE fld_school_id='".$schoolid."' 
																					AND fld_created_by='".$uid."' AND fld_delstatus='0'
																					UNION ALL
																					SELECT a.fld_id AS classid, a.fld_class_name AS classname 
																					FROM itc_class_master AS a,itc_class_teacher_mapping AS b 
																					WHERE a.fld_id=b.fld_class_id AND b.fld_teacher_id='".$uid."' AND b.fld_flag='1'");
                                                        if($qry->num_rows>0){
															while($row = $qry->fetch_assoc())
															{
																extract($row);
																?>
																<li><a tabindex="-1" href="#" onclick="fn_showstudentlist(<?php echo $classid;?>,<?php echo $id[0]; ?>);" data-option="<?php echo $classid;?>" ><?php echo $classname; ?></a></li>
																<?php
															}
                                                        }?>      
                                                    </ul>
                                                </div>
                                            </div>
                                        </dt>
                                	</dl>
                                </div>
                            </div>
                            
                            <script type="text/javascript" language="javascript">
								$("#sdate1").datepicker( {
									onSelect: function(dateText,inst){
										$(this).parents().parents().removeClass('error');
									},
									beforeShow: function() {
										setTimeout(function(){
											$('.ui-datepicker').css('z-index', 2);
										}, 0);
									}
								});
								$("#edate1").datepicker( {
									onSelect: function(dateText,inst){
										$(this).parents().parents().removeClass('error');
									},
									beforeShow: function() {
										setTimeout(function(){
											$('.ui-datepicker').css('z-index', 2);
										}, 0);
									}
								});
								
								$(function(){
									$("#frmselectstandard").validate({
										ignore: "",
											errorElement: "dd",
											errorPlacement: function(error, element) {
												$(element).parents('dl').addClass('error');
												error.appendTo($(element).parents('dl'));
												error.addClass('msg'); 	
										},
										rules: { 
											sdate1: { required: true },
											edate1: { required: true, greaterThan: "#sdate1" }
										}, 
										messages: { 
											sdate1:{  required: "Select the start date" },
											edate1:{  required: "Select the end date", greaterThan: "Must be greater than Start date."}								
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
                        
                            <div class="row rowspacer">
                                <div id="studentlist" class="twelve columns">  
                                </div>
                            </div>
                            
                            <div class="row rowspacer">
                                <div class="tRight">
                                	<input type="button" id="btnstep" class="darkButton <?php if($activityassignqryrowcnt==0) { ?>dim<?php } ?>" style="width:195px; height:42px;float:right;" value="Assign Activity" onClick="fn_studentassign(<?php echo $id[0];?>,<?php echo $id[1];?>);" />
                                </div>
                            </div>
                        </form>
                        <input type="hidden" id="testnames" name="testnames" value="<?php echo $classname; ?> " />
                        <input type="hidden" id="classid" name="classid" value="<?php echo $assignclassid; ?> " />
                    </div>
                </div>
            </div>
        </div>
    </div>   
</section>
<?php
	@include("footer.php");