<?php
	@include("sessioncheck.php");

	error_reporting(E_ALL);
	ini_set("display_errors","1");
	$id = isset($method['id']) ? $method['id'] : '0';
	$id=explode(",",$id);

	$pid=$id[0];
	$catid=$id[1];
	if($pid==0)
	{
		$productname='';	
		$productcode='';
		$vernumber='';
	}
	else
	{
		
		$productname = $ObjDB->SelectSingleValue("SELECT fld_product_name FROM itc_nondigicontent_product WHERE fld_id='".$pid."' AND fld_delstatus='0'");
		$productcode = $ObjDB->SelectSingleValue("SELECT fld_asset_id FROM itc_nondigicontent_product WHERE fld_id='".$pid."' AND fld_delstatus='0'");
		$vernumber = $ObjDB->SelectSingleValue("SELECT fld_version_number FROM itc_nondigicontent_product WHERE fld_id='".$pid."' AND fld_delstatus='0'");

	}
	$catname = $ObjDB->SelectSingleValue("SELECT fld_category_name FROM itc_nondigicontent_category WHERE fld_id='".$catid."' AND fld_delstatus='0'");
?>
<!-- Autocomplete script start -->

<script type="text/javascript" charset="utf-8">	
	$(function(){				
		var t4 = new $.TextboxList('#form_tags_newproduct', 
		{
			unique: true, plugins: {autocomplete: {}},
			bitsOptions:{editable:{addKeys: [188]}}	});
		<?php 

			if($pid != '' and $pid!='undefined'){
				$qrytag = $ObjDB->QueryObject("SELECT a.fld_id AS tagid,a.fld_tag_name AS tagname 
				                              FROM itc_main_tag_master AS a, itc_main_tag_mapping AS b 
											  WHERE a.fld_id=b.fld_tag_id AND b.fld_tag_type='41' 
											        AND b.fld_access='1' AND a.fld_created_by='".$uid."' 
													AND a.fld_delstatus='0' AND b.fld_item_id='".$pid."'");
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
<input type="hidden" id="insertid">
<script language="javascript" type="text/javascript">
$.getScript("library/nondigicontent/library-nondigicontent-product.js");
		
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
<section data-type='#library-nondigicontent-newproduct' id='library-nondigicontent-newproduct'>
	<div class='container'>
    	<div class='row'>
            <div class='twelve columns'>
            <p class="dialogTitle"><?php if($pid == 0){ echo "Define New Product";} else { echo "Edit ".$productname;} ?></p>
                <p class="dialogSubTitleLight">&nbsp;</p>
                <h1></h1>
            </div>
    	</div>
    
        <div class='row formBase'>
            <div class='eleven columns centered insideForm'>
                <form method='post' name="validate" id="validate">
                    <div class="row">
                        <div class="six columns">
                            Product Name<span class="fldreq">*</span> 
                            <dl class='field row'>
                                <dt class='text'>
                                    <input id="productname" name="productname"  placeholder='Product Name' tabindex="1" type='text' value="<?php echo $productname; ?>">
                                </dt>
                            </dl> 
                        </div>
                        <div class='six columns'>
                                Product Code (ASSET ID)<span class="fldreq">*</span>
                                <dl class='field row'>
                                    <dt class='text'>
                                        <input id="productcode" name="productcode"  placeholder='Product Code' tabindex="1" type='text' value="<?php echo $productcode; ?>">
                                    </dt>
                                </dl> 
                        </div>
                        <div class="row">
                            <div class="six columns">
                                Product Version Number<span class="fldreq">*</span> 
                                <dl class='field row'>
                                    <dt class='text'>
                                        <input id="vnumber" name="vnumber"  placeholder='Product Version Number' tabindex="1" type='text' value="<?php echo $vernumber; ?>">
                                    </dt>
                                </dl> 
                            </div>
                        </div>
                    </div>
                    <!--start of new filter-->
        
                    <div class='row rowspacer'>
                        <div class='twelve columns'>
                        To create a new tag, type a name and press Enter.
                        <div class="tag_well">
                            <input type="text" name="test3" value="" id="form_tags_newproduct" />
                        </div>
                        </div>
                    </div>
            
                    <!--end of new filter-->
					
                    <div class="row rowspacer">
                        <div class="six columns">
                            <p class='btn primary twelve columns'>
                                <a tabindex="24" onclick="fn_cancel('<?php echo $pid; ?>','<?php echo $catid; ?>','<?php echo $catname; ?>')">Cancel</a>
                            </p>
                        </div>
                        <div class="six columns" id="userphoto">
                            <p class='btn secondary twelve columns'>
                                <?php if($pid==0) {?>
                                <a tabindex="24" onclick="fn_createproduct('<?php echo $pid; ?>','<?php echo $catid; ?>')">Finish</a>
                                <?php }
                                else {?>
                                <a tabindex="24" onclick="fn_editproduct('<?php echo $pid; ?>','<?php echo $catid; ?>')">Finish</a>
                                <?php } ?>
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
                                //ddlgrade : { required: true },					  	
                                
                            },
                            messages: {
                                catname: { required: "Please enter the first name" },
                                catcode: { required: "Please enter the last name" },
                                dfield: { required: "Please enter the User name" },
                                txtpassword: { required: "please enter paswword" },
                                //ddlgrade : { required: "Please select grade" },
                                
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