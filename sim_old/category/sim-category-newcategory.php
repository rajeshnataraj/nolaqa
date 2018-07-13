<?php
@include("sessioncheck.php");
$cid = isset($method['id']) ? $method['id'] : '0';

if($cid==0)
{
	$categoryname='';	
 	$categorycode='';
}
else
{
 	$categoryname = $ObjDB->SelectSingleValue("SELECT fld_category_name FROM itc_sim_category WHERE fld_id='".$cid."' ANd fld_delstatus='0'");
 	$categorycode = $ObjDB->SelectSingleValueInt("SELECT fld_category_code FROM itc_sim_category WHERE fld_id='".$cid."' ANd fld_delstatus='0'");
	$definefieldscount = $ObjDB->SelectSingleValueInt("SELECT fld_field_id FROM itc_sim_destination WHERE fld_cat_id='".$cid."' ANd fld_delstatus='0'");
	$definefieldecount = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_sim_destination WHERE fld_cat_id='".$cid."' ANd fld_delstatus='0'");
 	$fieldsname = $ObjDB->SelectSingleValue("SELECT fld_define_field FROM itc_sim_destination WHERE fld_cat_id='".$cid."' AND fld_field_id='".$definefieldscount."' AND fld_delstatus='0'");
	?>
	<script language="javascript" type="text/javascript">
		$(document).ready(function()
		{
			$('#dfield').val('<?php echo $fieldsname; ?>');
		});
	</script>
	<?php
	$extval="LIMIT ".$definefieldscount.",".$definefieldecount;
	
	$definefield=$ObjDB->QueryObject("SELECT fld_field_id AS fieldval FROM itc_sim_destination WHERE fld_cat_id='".$cid."' ANd fld_delstatus='0' ".$extval."");
  	if($definefield->num_rows > 0)
	{													
		while($rowdefinefield = $definefield->fetch_assoc())
		{
			extract($rowdefinefield);
			
		 	$fieldname = $ObjDB->SelectSingleValue("SELECT fld_define_field FROM itc_sim_destination WHERE fld_cat_id='".$cid."' AND fld_field_id='".$fieldval."' AND fld_delstatus='0'");
			?>
				<script language="javascript" type="text/javascript">
				$(document).ready(function()
				{
					$('#field_wrapper').append('<div><input type="text" class="text1" placeholder="Defined Field" id="'+'field_'+<?php echo $fieldval; ?>+'" name="dfield" value="<?php echo $fieldname; ?>"/><a href="javascript:void(0);" class="remove_button" title="Remove field"><img src="img/uploadify-cancel.png"/></div>')
					$('#hidtxtid').val(<?php echo $fieldval; ?>);
				});
				</script>
			<?php
			}
	  }
}


?>
<input type="hidden" id="insertid">
<script language="javascript" type="text/javascript">
	$.getScript("sim/category/sim-category.js");
	/* Add & Remove Field code start line */
	$(document).ready(function()
  	{
		var a=1;
		var id = $('#hidtxtid').val();
		id=parseInt(id)+parseInt(a);
		var maxField = 6; 
		// Add Field
		$( ".add_button" ).click(function() {
			if(id < maxField){ 
				$('#field_wrapper').append('<div><input type="text" class="text1" placeholder="Defined Field" id="'+'field_'+id+'" name="dfield" value=""/><a href="javascript:void(0);" class="remove_button" title="Remove field"><img src="img/uploadify-cancel.png"/></div>')
				id++;
			}
			$('#hidtxtid').val(id);
		});
		//Remove Field
		$('#field_wrapper').on('click', '.remove_button', function(e){ //Once remove button is clicked
			e.preventDefault();
			$(this).parent('div').remove(); //Remove field html
			x--; //Decrement field counter
		});
		
	});
	/* Add & Remove Field code end line */	
</script>
<style>
.text1 {
	background: #fff none repeat scroll 0 0;
	border: 1px solid #b7b7b7;
	border-radius: 4px;
	box-shadow: 0 2px 3px #ccc inset, 0 1px 0 #f4fff6;
	font-size: 14px;
	outline: medium none !important;
	padding: 8px 10px;
	position: relative;
	margin-top:8px;
}
</style>
<section data-type='#sim' id='sim-category-newcategory'>
	<div class='container'>
    	<div class='row'>
      		<div class='twelve columns'>
            	<p class="dialogTitle"><?php if($editid == 0){ echo "New Category";} else { echo $fname." ".$lname." "."Student";} ?></p>
				<p class="dialogSubTitleLight">&nbsp;</p>
        		<h1></h1>
      		</div>
    	</div>
    
        <div class='row formBase'>
            <div class='eleven columns centered insideForm'>
                <form method='post' name="validate" id="validate">
                    <div class="row">
                        <div class="six columns">
                            Category Name<span class="fldreq">*</span> 
                            <dl class='field row'>
                                <dt class='text'>
                                    <input id="catname" name="catname"  placeholder='category name' tabindex="1" type='text' value="<?php echo $categoryname; ?>">
                                </dt>
                            </dl> 
                        </div>
                        <div class="six columns">
                            Category Code
                            <dl class='field row'>
                                <dt class='text'>
                                    <input id="catcode" name="catcode" placeholder='Category Code' tabindex="11" type='text' value="<?php echo $categorycode; ?>">
                                </dt>
                            </dl>
                        </div>
                    </div>
                    
                    <div class="row rowspacer">
                        <div class="six columns">
                        Define Field<span class="fldreq">*</span> 
                            <dl class='field row' id="field_wrapper">
                                <dt class='text'>
                                    <input id="dfield" name="dfield" placeholder='Define Field' tabindex="2" type='text' value="">
                                </dt>
                            </dl> 
                        </div>
                        <div class="six columns">
                            <dl class='field row'>
                                <dt style="padding:8px 10px;margin:23px 0px 0px -13px;">
                                    <a href="javascript:void(0);" class="add_button" title="Add field"><input type="button" value="+Add Field"/></a>
                                </dt>
                            </dl>
                        </div>
                    </div>
                    <script language="javascript" type="text/javascript">
						
							
                    </script>
                    
                    <div class="row rowspacer">
                        <div class="six columns">
							<p class='btn primary twelve columns'>
								<a tabindex="24" onclick="fn_cancel()">Cancel</a>
                            </p>
                        </div>
                        <div class="six columns" id="userphoto">
                            <p class='btn secondary twelve columns'>
								<a tabindex="24" onclick="fn_createcategory(<?php echo $cid;?>)">Finish</a>
                            </p>
                        </div>
                    </div>
                </form>
            	<input id="hidtxtid" name="hidtxtid" type='hidden' value="0">
                <script type="text/javascript" language="javascript">
                     $(function(){
                        $("#validate").validate({
                            ignore: "",
                            errorElement: "dd",
                            errorPlacement: function(error, element) {
                                $(element).parents('dl').addClass('error');
                                error.appendTo($(element).parents('dl'));
                                error.addClass('msg');
                            },
                            rules: {
                                catname: { required: true, lettersonly: true },
                                catcode: { required: true },
                                dfield: { required: true },
								txtconfirmpassword: { required: true, equalTo: "#txtpassword" },                               				  	
                                
                            },
                            messages: {
                                catname: { required: "Please enter the first name" },
                                catcode: { required: "Please enter the last name" },
                                dfield: { required: "Please enter the User name" },
                                txtpassword: { required: "please enter paswword" },                                
                                
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