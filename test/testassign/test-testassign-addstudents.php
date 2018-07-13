<?php
	@include("sessioncheck.php");
	
	$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
	$id=explode(",",$id);
	$contenttype=1;
	$testassigncount=0;
	$testname = $ObjDB->SelectSingleValue("SELECT fld_test_name FROM `itc_test_master` WHERE fld_id='".$id[0]."'");
	$startdate='';
	$enddate='';
	
	
	if($id[1]!=0)
	{
		$testassignqry=$ObjDB->QueryObject("SELECT fld_class_id AS assignclassid, fld_start_date AS startdate, fld_end_date AS enddate, 
		                         (SELECT fld_class_name FROM `itc_class_master` WHERE fld_id=fld_class_id) AS classname FROM `itc_test_student_mapping` 
								 WHERE fld_test_id='".$id[0]."' AND fld_class_id='".$id[1]."' AND fld_start_date='".$id[2]."' AND fld_flag='1' AND fld_created_by='".$uid."'");
		$testassigncount=$testassignqry->num_rows;
		if($testassignqry->num_rows>0){
			$row = $testassignqry->fetch_assoc();
			extract($row);
		}
	}
?>

<section data-type='#test-testassign' id='test-testassign-addstudents'>
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
		<?php if($testassigncount>0) { ?>
				fn_showstudentlist(<?php echo $id[1];?>,<?php echo $id[0]; ?>,'<?php echo $id[2]; ?>');
		<?php } ?>
	</script>
    <div class="container">
        <div class="row">
            <div class="twelve columns">
                <p class="dialogTitle">Assign this Assessment</p>
                <p class="dialogSubTitleLight">Assign this assessment to class or students below.</p>
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
                            	<div class='four columns'>
                                	Start date<span class="fldreq">*</span>
                                    <dl class='field row'>
                                        <dt class='text'>
                                             <input id="sdate1" readonly name="sdate1" class="quantity" placeholder='Start Date' type='text' value="<?php if($startdate!='') echo date("m/d/Y",strtotime($startdate)); ?>" >
                                        </dt>                                        
                                    </dl>
                                </div>
                                
                                <div class='four columns'>
                                	End date<span class="fldreq">*</span>
                                    <dl class='field row'>
                                        <dt class='text'>
                                             <input id="edate1" readonly name="edate1" class="quantity" placeholder='End Date' type='text' value="<?php if($enddate!='') echo date("m/d/Y",strtotime($enddate)); ?>" >
                                        </dt>                                        
                                    </dl>
                                </div>
                                
                                <div class="four columns">
                                	Class<span class="fldreq">*</span>
                                    <dl class="field row<?php if($id[1]!=0) { echo "dim"; }?>">   
                                        <dt id="dpdocuments" class="dropdown">   
                                            <div class="selectbox">
                                                <input type="hidden" name="classids" id="classids" onchange="$('#classid').val(this.value)" value="" >
                                                <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                                                <span class="selectbox-option input-medium" data-option="<?php if($testassigncount>0) { echo $assignclassid; }?>" style="width:97%"><?php if($testassigncount>0) { echo $classname; } else { echo "Select class" ;}?></span>
                                                <b class="caret1"></b>
                                                </a>
                                                <div class="selectbox-options">
                                                    <input type="text" class="selectbox-filter" placeholder="Search Class">
                                                    <ul role="options" style="width:100%">
														<?php 
                                                        $qry = $ObjDB->QueryObject("SELECT fld_id as classid, fld_class_name as classname FROM itc_class_master 
														                        WHERE fld_id NOT IN (SELECT fld_class_id FROM itc_test_class_mapping WHERE fld_test_id='".$id[0]."' 
																				 AND fld_flag='1') AND fld_school_id='".$schoolid."' AND fld_created_by='".$uid."' 
																				 AND fld_delstatus='0' UNION  
																				 SELECT a.fld_id AS classid, a.fld_class_name AS classname 
																				 FROM itc_class_master AS a,`itc_class_teacher_mapping` AS b WHERE a.fld_id NOT IN 
																				 (SELECT fld_class_id FROM itc_test_class_mapping WHERE fld_test_id='".$id[0]."' AND fld_flag='1') 
																				 AND a.fld_id=b.fld_class_id AND b.fld_teacher_id='".$uid."' and b.fld_flag='1' AND a.fld_delstatus='0'" );
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
                                                                                $("#enddate1").val('');
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
                                                                                fn_checkdate1();
										$(this).parents().parents().removeClass('error');
									},
									beforeShow: function() {
										setTimeout(function(){
											$('.ui-datepicker').css('z-index', 2);
										}, 0);
									}
								});
                                                                
                                                                function fn_checkdate1() {
                                                                    var startDate = $("#sdate1").val();
                                                                    var endDate = $("#edate1").val();
                                                                    if (startDate != '' && endDate !='') {
                                                                        if (Date.parse(startDate) > Date.parse(endDate)) {
                                                                            $("#edate1").val('');
                                                                            $.Zebra_Dialog("Start date should not be greater than end date.", { 'type': 'information', 'buttons':  false, 'auto_close': 2000  });

                                                                        }
                                                                    }
                                                                    return false;
                                                                }
								
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
											edate1:{  required: "Select the end date", greaterThan: "Must be greater than Start date." }								
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
                                	<input type="button" id="btnstep" class="darkButton <?php if($testassignqry->num_rows==0) { ?>dim<?php } ?>" style="width:195px; height:42px;float:right;" value="Assign Assessment" onClick="fn_studentassign(<?php echo $id[0];?>);" />
                                </div>
                            </div>
                        </form>
                        <input type="hidden" id="testnames" name="testnames" value="<?php echo $testname; ?> " />
                        <input type="hidden" id="classid" name="classid" value="<?php echo $assignclassid; ?> " />
                    </div>
                </div>
            </div>
        </div>
    </div>   
</section>
<?php
	@include("footer.php");