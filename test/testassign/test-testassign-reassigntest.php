<?php
	@include("sessioncheck.php");
	
	$id = isset($method['id']) ? $method['id'] : '0';
	$id = explode(',',$id);
	$testid = $id[0];
	$studentid = $id[1];
	$classid = $id[2];
	$startdate = $id[3];
	
	$testname = $ObjDB->SelectSingleValue("SELECT fld_test_name 
	                                       FROM `itc_test_master` WHERE fld_id='".$testid."'");
	$classname=$ObjDB->SelectSingleValue("Select fld_class_name 
	                                     FROM itc_class_master WHERE fld_id='".$classid."'");
 ?>

<section data-type='#test-testassign' id='test-testassign-reassigntest'>
    <div class="container">
        <div class="row">
            <div class="twelve columns">
                <p class="dialogTitle">Re-Assign this Assessment</p>
                <p class="dialogSubTitleLight">Re-Assign this assessment to students below.</p>
                <div class="row rowspacer"></div>
            </div>
        </div>   
         
        <div class="row rowspacer">
            <div class="twelve columns formBase">
                <div class="row">
                    <div class="eleven columns centered insideForm">
                        <form name="frmreassign" id="frmreassign">
                        	<input type="hidden" id="hidpredate" name="predate" value="<?php echo $startdate;?>" />
                            <div class="row rowspacer">
                            	<div class='four columns'>
                                	Start date<span class="fldreq">*</span>
                                    <dl class='field row'>
                                        <dt class='text'>
                                             <input id="startdate1" readonly name="startdate1" class="quantity" placeholder='Start Date' type='text' value="<?php if($startdate!='') echo date("m/d/Y",strtotime($startdate)); ?>" >
                                        </dt>                                        
                                    </dl>
                                </div>
                                
                                <div class='four columns'>
                                	End date<span class="fldreq">*</span>
                                    <dl class='field row'>
                                        <dt class='text'>
                                             <input id="enddate1" readonly name="enddate1" class="quantity" placeholder='End Date' type='text' value="<?php if($enddate!='') echo date("m/d/Y",strtotime($enddate)); ?>" >
                                        </dt>                                        
                                    </dl>
                                </div>
                                
                                <div class='four columns'>
                                	class name
                                    <dl class='field row'>
                                        <dt class='text'>
                                             <input readonly class="quantity" type='text' value="<?php echo $classname; ?>" >
                                        </dt>                                        
                                    </dl>
                                </div>
                             </div>
                            
                            <script type="text/javascript" language="javascript">
								$("#startdate1").datepicker( {
									onSelect: function(dateText,inst){
                                                                                $("#enddate1").val('');
										$(this).parents().parents().removeClass('error');
									}
								});
								
								$("#enddate1").datepicker( {
									onSelect: function(dateText,inst){
                                                                                fn_checkdate();
										$(this).parents().parents().removeClass('error');
									}
								});
                                                                
                                                                function fn_checkdate() {
                                                                        var startDate = $("#startdate1").val();
                                                                        var endDate = $("#enddate1").val();
                                                                        if (startDate != '' && endDate !='') {
                                                                            if (Date.parse(startDate) > Date.parse(endDate)) {
                                                                                $("#enddate1").val('');
                                                                                $.Zebra_Dialog("Start date should not be greater than end date.", { 'type': 'information', 'buttons':  false, 'auto_close': 2000  });
                                                                               
                                                                            }
                                                                        }
                                                                        return false;
                                                                    }
								
								$(function(){
									$("#frmreassign").validate({
										ignore: "",
											errorElement: "dd",
											errorPlacement: function(error, element) {
												$(element).parents('dl').addClass('error');
												error.appendTo($(element).parents('dl'));
												error.addClass('msg'); 	
										},
										rules: { 
											startdate1: { required: true },
											enddate1: { required: true, greaterThan: "#startdate1" }
										}, 
										messages: { 
											startdate1:{  required: "Select the start date" },
											enddate1:{  required: "Select the end date", greaterThan: "Must be greater than Start date." }								
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
                                <div class="tRight">
                                	<input type="button" id="btnstep" class="darkButton" style="width:220px; height:42px;float:right;" value="Re-Assign Assessment" onClick="fn_testreassign(<?php echo $testid;?>,<?php echo $studentid;?>,<?php echo $classid;?>,<?php echo $startdate; ?>);" />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>   
</section>
<?php
	@include("footer.php");