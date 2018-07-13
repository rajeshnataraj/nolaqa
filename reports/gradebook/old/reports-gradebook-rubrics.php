<?php
@include("sessioncheck.php");

/*
	Created By - Muthukumar. D
	Page - reports-classroom
	Description:
		Show the Student Password & Schedule Reports buttons.

	Actions Performed:
		Student Password - Redirect to studentpassword form - reports-classroom-stupassword.php
		Student Schedule - Redirect to studentschedule form - reports-classroom-stuschedule.php
	
	History:


*/
$id = isset($method['id']) ? $method['id'] : '0';
$id = explode(",",$id);

if($id[0] != '0'){
	$formTitle = "Edit Rubrics";
	$btnvalue = "Update Rubrics";
	$btncancel = "Delete Rubrics";
	$btnclick = "fn_cancel('reports-gradebook-edit')";
}
else{
	$formTitle = "New Rubrics";
	$btnvalue = "Create Rubrics";
	$btncancel = "Cancel";
	$btnclick = "fn_cancel('reports-gradebook-edit')";
}

if($id[0] != '0' and $id[0] != 'undefined') {
	$qryrubricsdetails = $ObjDB->QueryObject("SELECT fld_rubrics_name AS rubricsname, fld_points_possible as pointspossible 
												FROM itc_rubrics_master 
												WHERE fld_id='".$id[0]."'");
	$rubricsdetails = $qryrubricsdetails->fetch_assoc();
	extract($rubricsdetails);
}
?>
<section data-type='2home' id='reports-gradebook-rubrics'>
	<div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Student Name: <?php echo $ObjDB->SelectSingleValue("SELECT CONCAT(fld_fname,' ',fld_lname) 
																							FROM itc_user_master 
																							WHERE fld_id='".$id[2]."'"); ?></p>
            </div>
        </div>
        
        <div class='row formBase rowspacer'>
            <div class='eleven columns centered insideForm'>
                <form name="rubricsforms" id="rubricsforms">
                	Unit Name: <?php echo $ObjDB->SelectSingleValue("SELECT fld_unit_name 
																	FROM itc_unit_master 
																	WHERE fld_id='".$id[3]."'"); ?>
                	<div class='twelve columns rowspacer'>
                        <div class='six columns'>
                            <dl class='field row'>
                                <dt class='text'>
                                    <input placeholder='New Rubrics Name' type="text" name="txtrubricsname" id="txtrubricsname" value="<?php echo $rubricsname;?>" onBlur="$(this).valid();" tabindex="1"/>
                                </dt>
                            </dl>
                        </div>
                        
                        <div class='six columns'>
                            <dl class='field row'>
                                <dt class='text'>
                                    <input placeholder='Rubrics Points' type="text" maxlength="3" name="txtrubricspoints" id="txtrubricspoints" value="<?php echo $pointspossible; ?>" onkeyup="ChkValidChar(this.id);" onBlur="$(this).valid();" tabindex="2"/>
                                </dt>
                            </dl>
                        </div>
                    </div>
                </form>
                
                <div class='row rowspacer'>
                    <div class='four columns btn primary push_two noYes'>
                        <a onclick="<?php echo $btnclick;?>" tabindex="4">Cancel</a>
                    </div>
                    <div class='four columns btn secondary yesNo'>
                        <a onclick="fn_saverubrics(<?php echo $id[0];?>)" tabindex="3"><?php echo $btnvalue;?></a>
                    </div>
                </div>
            </div>
            <script type="text/javascript" language="javascript">
				$("input[id^=earned]").keypress(function (e) {
					if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
						return false;
					}
				});
				
				//Function to set the max & min values for the textbox
				String.prototype.startsWith = function (str) {
					return (this.indexOf(str) === 0);
				}
				function ChkValidChar(id) {
					var txtbx = document.getElementById(id).value;
					if ((txtbx.startsWith("0")) || (txtbx > 100)) // true
					{
						document.getElementById(id).value = "";
					}
				}
			
				/***addd category validate****/
				$(function(){
					$("#rubricsforms").validate({
						ignore: "",
						errorElement: "dd",
						errorPlacement: function(error, element) {
							$(element).parents('dl').addClass('error');
							error.appendTo($(element).parents('dl'));
							error.addClass('msg'); 
						},
						rules: {
							txtrubricsname: { required: true, lettersonly:true, remote:{ url: "reports/gradebook/reports-gradebook-gradebookajax.php?oper=checkrubricsname&rid=<?php echo $id[0];?>&sid=<?php echo $id[4];?>", type:"post" } },
							txtrubricspoints: { required: true }
						},
					
						messages: {
							txtrubricsname: { required: "please type rubrics name",lettersonly:"please enter letters and numbers only", remote: "rubrics name already exists" },
							txtrubricspoints: { required: "please type rubrics points" }
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
        </div>
        
        <input type="hidden" name="hidclassid" id="hidclassid" value="<?php echo $id[1];?>"  />
        <input type="hidden" name="hidscheduleid" id="hidscheduleid" value="<?php echo $id[4];?>"  />
        <input type="hidden" name="hidrubricsid" id="hidrubricsid" value="<?php echo $id[0];?>"  />
		<input type="hidden" name="hidstudentid" id="hidstudentid" value="<?php echo $id[2];?>"  />
        <input type="hidden" name="hidunitmodid" id="hidunitmodid" value="<?php echo $id[3];?>"  />
    </div>
</section>
<?php
	@include("footer.php");