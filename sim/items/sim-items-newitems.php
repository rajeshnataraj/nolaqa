<?php
	@include("sessioncheck.php");
	
	$itemids= isset($method['id']) ? $method['id'] : '';
	$itemid = explode(',',$itemids);
	$definefieldid=$itemid[0];
	$catid=$itemid[1];
	$proid=$itemid[2];
	if($definefieldid==0)
	{
		$definefieldname='';	
	}
	else
	{
		$definefieldname = $ObjDB->SelectSingleValue("SELECT fld_define_field FROM itc_sim_items WHERE fld_id='".$definefieldid."' ANd fld_delstatus='0'");
	}


?>

<script language="javascript" type="text/javascript">
	$.getScript("sim/items/sim-items-items.js");
		
</script>
<!-- Autocomplete script start -->

<script type="text/javascript" charset="utf-8">	
	$(function(){				
		var t4 = new $.TextboxList('#form_tags_newitems', 
		{
			unique: true, plugins: {autocomplete: {}},
			bitsOptions:{editable:{addKeys: [188]}}	});
		<?php 
			if($definefieldid != '' and $definefieldid!='undefined'){
				$qrytag = $ObjDB->QueryObject("SELECT a.fld_id AS tagid,a.fld_tag_name AS tagname 
				                              FROM itc_main_tag_master AS a, itc_main_tag_mapping AS b 
											  WHERE a.fld_id=b.fld_tag_id AND b.fld_tag_type='42' 
											        AND b.fld_access='1' AND a.fld_created_by='".$uid."' 
													AND a.fld_delstatus='0' AND b.fld_item_id='".$definefieldid."'");
				if($qrytag->num_rows > 0) {
					
					while($restag = $qrytag->fetch_assoc()){
						
						extract($restag); ?>
            			t4.add('<?php echo $ObjDB->EscapeStrAll($tagname); ?>','<?php echo $tagid; ?>');				
			      <?php }
				}
			}
		?>				
		t4.getContainer().addClass('textboxlist-loading');				
		$.ajax({url: 'autocomplete.php', data: 'oper=new', type:"POST", dataType: 'json', success: function(r){
			t4.plugins['autocomplete'].setValues(r);
			t4.getContainer().removeClass('textboxlist-loading');					
		}});						
	});
</script>

<!-- Autocomplete script end -->
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
<section data-type='#sim-product-newitems' id='sim-product-newitems'>
	<div class='container'>
    	<div class='row'>
      		<div class='twelve columns'>
            	<p class="dialogTitle"><?php if($editid == 0){ echo "Define New Field";} else { echo $definefieldname;} ?></p>
				<p class="dialogSubTitleLight">&nbsp;</p>
        		<h1></h1>
      		</div>
    	</div>
    
        <div class='row formBase'>
            <div class='eleven columns centered insideForm'>
                <form method='post' name="validate" id="validate">
                    <div class="row">
                        <div class="six columns">
                            Field Name<span class="fldreq">*</span> 
                            <dl class='field row'>
                                <dt class='text'>
                                    <input id="fieldname" name="fieldname"  placeholder='fieldname' tabindex="1" type='text' value="<?php echo $definefieldname; ?>">
                                </dt>
                            </dl> 
                        </div>
                    </div>
					<!--start of new filter-->
        
                    <div class='row rowspacer'>
                        <div class='twelve columns'>
                        To create a new tag, type a name and press Enter.
                        <div class="tag_well">
                            <input type="text" name="test3" value="" id="form_tags_newitems" />
                        </div>
                        </div>
                    </div>
            
                    <!--end of new filter-->
                    
                    <div class="row rowspacer">
                        <div class="six columns">
							<p class='btn primary twelve columns'>
								<a tabindex="24" onclick="fn_cancel('<?php echo $definefieldid; ?>','<?php echo $catid; ?>','<?php echo $proid; ?>')">Cancel</a>
                            </p>
                        </div>
                        <div class="six columns" id="userphoto">
                            <p class='btn secondary twelve columns'>
								<a tabindex="24" onclick="fn_createfield('<?php echo $definefieldid; ?>','<?php echo $catid; ?>','<?php echo $proid; ?>')">Finish</a>
                            </p>
                        </div>
                    </div>
                </form>
            	<input id="hidtxtid" name="hidtxtid" type='hidden' value="">
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