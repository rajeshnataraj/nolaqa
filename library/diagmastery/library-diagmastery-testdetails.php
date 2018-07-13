<?php
@include("sessioncheck.php");

/*
	Created By - Muthukumar. D
	Page - library-diagmastery-testdetails
	Description:
		Show the Subject, Course, Units, Lesson dropdowns & Lesson Weight textbox, Tag to Create/Edit the Diagnostic-Mastery Details
	Actions Performed:
		Subject - Loads all the Subject names.
		Course - Loads all the Course names under the Selected Subject.
		Units - Loads all the Unit names under the Selected Course.
		Lesson - Loads all the Lesson names under the Selected Unit.
		Lesson Weight - Type only the Numeric Values Greater than '0' & Less than or Equal to '100'.
		Tag - Creates a new tag to save the Diagmastery details.
	History:
*/

$id = (isset($method['id'])) ? $method['id'] : 0;
$id = explode(",",$id);

$lessonweight='';
//$id[0] - Diagmastery id
//$id[1] = Step id

$qrytag = $ObjDB->QueryObject("SELECT a.fld_id AS tagid,a.fld_tag_name AS tagname 
							FROM itc_main_tag_master AS a, itc_main_tag_mapping AS b 
							WHERE a.fld_id=b.fld_tag_id AND b.fld_tag_type='22' AND b.fld_access='1' 
								AND a.fld_created_by='".$uid."' AND a.fld_delstatus='0' AND b.fld_item_id='".$id[0]."'");
		
if($id[0]==0){ //Create New DiagnosticMastery
	$unitid = '';
	$lessonid = '';	
	$unitname = "Select Unit";
	$lessonname = "Select Lesson";
}
else{ //Edit Saved DiagnosticMastery 
	$qrydiagdetails = $ObjDB->QueryObject("SELECT a.fld_id AS diagid, a.fld_unit_id AS unitid, a.fld_lesson_id AS lessonid, a.fld_lesson_weight AS lessonweight, 
											d.fld_unit_name AS unitname, CONCAT(e.fld_ipl_name,' ',f.fld_version) AS lessonname 
										FROM itc_diag_question_mapping AS a 
										LEFT JOIN itc_unit_master AS d ON a.fld_unit_id=d.fld_id 
										LEFT JOIN itc_ipl_master AS e ON a.fld_lesson_id=e.fld_id
										LEFT JOIN itc_ipl_version_track AS f ON f.fld_ipl_id=e.fld_id 
										WHERE a.fld_id='".$id[0]."' AND a.fld_delstatus='0' AND d.fld_delstatus='0' 
											AND e.fld_delstatus='0' AND e.fld_access='1' AND d.fld_activestatus='0'
											AND f.fld_zip_type='1' AND f.fld_delstatus='0' ");
											
	if($qrydiagdetails->num_rows>0)
	{
		while($resdiagdetails = $qrydiagdetails->fetch_assoc()){			
			extract($resdiagdetails);			
		}
	}
}
?>
<section data-type='#library-diagmastery' id='library-diagmastery-testdetails'>
<!--Script to change the Step Styles-->
<script language="javascript" type="text/javascript">
	$('#testdetails').addClass("active-first");
	$('#mas1ques').removeClass("active-mid");
	$('#mas2ques').removeClass("active-mid");
	$('#review').removeClass("active-last");
	$('#diagques').removeClass("active-mid");
	
	$(function(){				
		var t4 = new $.TextboxList('#form_tags_diagmastery', 
		{
			unique: true, plugins: {autocomplete: {}},
			bitsOptions:{editable:{addKeys: [188]}}	
		});
		<?php 
		if($id[0] != 0){
			if($qrytag->num_rows > 0) {
				while($restag = $qrytag->fetch_assoc()){
					extract($restag);
					?>
					t4.add('<?php echo $ObjDB->EscapeStrAll($tagname); ?>','<?php echo $tagid; ?>');				
					<?php 		
				}
			}
		}
		?>				
		t4.getContainer().addClass('textboxlist-loading');				
		$.ajax({url: 'autocomplete.php', data: 'oper=new', dataType: 'json', success: function(r){
			t4.plugins['autocomplete'].setValues(r);
			t4.getContainer().removeClass('textboxlist-loading');					
		}});						
	});
</script>

    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">New Test Details</p>
                <p class="dialogSubTitle">&nbsp;</p>
            </div>
        </div>
        
        <!--Loads the form with DiagnosticMastery Details Like -> Subject, Course, Unit, Lesson, Lesson Weight-->
        <div class='row formBase rowspacer'>
            <div class='eleven columns centered insideForm'>
                <form id="diagmasform" name="diagmasform">
                    <!--Units & Lesson Dropdown in Edit-->
                    <div class='row rowspacer'>
                        <div class='six columns'>
                        Unit<span class="fldreq">*</span>
                            <dl class='field row' id='unid'>  
                                <dt class='dropdown'> 
                                    <div id="unit">
                                        <div class="selectbox">
                                            <input type="hidden" name="unitid" id="unitid" value="<?php echo $unitid;?>"  onchange="$(this).valid();" />
                                            <a class="selectbox-toggle" style="width:100%;" role="button" data-toggle="selectbox" href="#">
                                                <span class="selectbox-option input-medium" data-option="<?php echo $unitid;?>" id="clearcourse" style="width:97%;"><?php echo $unitname; ?></span>
                                                <b class="caret1"></b>
                                            </a>
                                            <div class="selectbox-options">
                                                <input type="text" class="selectbox-filter" placeholder="Search Unit">
                                                <ul role="options" style="width:100%;">
                                                    <?php 
                                                    $categoryqry = $ObjDB->QueryObject("SELECT fld_id, fld_unit_name 
																						FROM itc_unit_master 
																						WHERE fld_delstatus= '0' AND fld_activestatus='0' ORDER BY fld_unit_name");
                                                    if($categoryqry->num_rows > 0)
                                                    {
                                                        while($res = $categoryqry->fetch_assoc())
                                                        {
															extract($res);
                                                        ?>
                                                            <li><a tabindex="-1" href="#" data-option="<?php echo $fld_id;?>" onclick="fn_showlesson(<?php echo $fld_id;?>)"><?php echo $fld_unit_name; ?></a></li>
                                                        <?php
                                                        }
                                                    }
                                                    ?>       
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </dt>
                            </dl>
                        </div>
                    
                        <div class='six columns'>
                        Lesson<span class="fldreq">*</span>
                            <dl class='field row' id='iplid'>  
                                <dt class='dropdown'> 
                                    <div id="lesson">
                                        <div class="selectbox">
                                            <input type="hidden" name="lessonid" id="lessonid" value="<?php echo $lessonid;?>" onchange="$(this).valid(); $('#btnstep').addClass('btn');	" />
                                            <a class="selectbox-toggle" style="width:100%;" role="button" data-toggle="selectbox" href="#">
                                                <span class="selectbox-option input-medium" data-option="<?php echo $lessonid;?>" id="clearcourse" style="width:97%;"><?php echo $lessonname; ?></span>
                                                <b class="caret1"></b>
                                            </a>
                                        </div>
                                    </div>
                                </dt>
                            </dl>                                      
                        </div>
                    </div>
                    
                    <!--Lesson Weight Textbox-->
                    <div class='row rowspacer'>
                        <div class='six columns'>
                        Lesson Weight<span class="fldreq">*</span>
                            <dl class='field row'>
                                <dt class='text'>
                                    <input placeholder='Lesson Weight' type='text' id="lessonweight" name="lessonweight" onkeyup="ChkValidChar(this.id);" value="<?php echo $lessonweight ;?>"/>
                                </dt>                                        
                            </dl>                                       
                        </div>
                    </div>
                    
                    <div class='row rowspacer'> <!-- Tag Well -->
                    	<div class='twelve columns'>
                        	To create a new tag, type a name and press Enter.
                            <div class="tag_well">
                                <input type="text" name="form_tags_diagmastery" value="" id="form_tags_diagmastery" />
                            </div>
                        </div>
                    </div>
                    
                    <!--Next Step Button-->
                    <div class='row rowspacer' id="ipls">
                         <input type="button" class="darkButton" value="Next Step" style="width:200px; height:40px; float:right" onClick="fn_savedetails(<?php echo $id[0];?>);" />
                    </div>
                </form>
                
                <script type="text/javascript" language="javascript">
                    //Function to set the max & min values for the textbox
					String.prototype.startsWith = function (str) {
						return (this.indexOf(str) === 0);
					}
					function ChkValidChar(id) {
						var txtbx = document.getElementById(id).value;
						//alert(txtbx);
						if ((txtbx.startsWith("0")) || (txtbx > 100)) // true
						{
							document.getElementById(id).value = "";
							//alert("you can not insert dot and zero as first character");
						}
					}
					
                    //Function to enter only numbers in textbox
                    $("#lessonweight").keypress(function (e) {
						if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
							return false;
						}
					});
                    
                    //Function to validate the form
                    $(function(){
                        $("#diagmasform").validate({
                            ignore: "",
                            errorElement: "dd",
                            errorPlacement: function(error, element) {
								$(element).parents('dl').addClass('error');
								error.appendTo($(element).parents('dl'));
								error.addClass('msg');
							},
                            rules: {                                 
                                unitid: { required: true },
                                lessonid: { required: true },
                                lessonweight: { required: true, digits: true }										
                            }, 
                            messages: {
                                unitid: {  required: "Please Select Unit" },
                                lessonid:{ required: "Please Select Lesson" },		   						  
                                lessonweight:{ required: "Please Type Lesson Weight", digits: "Please Enter Only Digits" }
                            },
                            highlight: function(element, errorClass, validClass) {
                                $(element).parents('dl').addClass(errorClass);
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
        </div>
    </div>
</section>
<?php
	@include("footer.php");
