<?php
	@include("sessioncheck.php");
	$id = isset($method['id']) ? $method['id'] : '0';
	$id=explode(",",$id);

	$pid=$id[0];
	$catid=$id[1];
	if($pid==0)
	{
		$productname='';	
		$productcode='';
	}
	else
	{
		$productname = $ObjDB->SelectSingleValue("SELECT fld_product_name FROM itc_sim_product WHERE fld_id='".$pid."' AND fld_delstatus='0'");
		$productcode = $ObjDB->SelectSingleValue("SELECT fld_product_key FROM itc_sim_product WHERE fld_id='".$pid."' AND fld_delstatus='0'");
		$assetid = $ObjDB->SelectSingleValue("SELECT fld_asset_id FROM itc_sim_product WHERE fld_id='".$pid."' AND fld_delstatus='0'");

	}

	$cattype = $ObjDB->SelectSingleValueInt("SELECT fld_category_type FROM itc_sim_category WHERE fld_id='".$catid."' AND fld_delstatus='0'");

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
$.getScript("sim/product/sim-product-product.js");
		
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
<section data-type='#sim-product-newproduct' id='sim-product-newproduct'>
	<div class='container'>
    	<div class='row'>
      		<div class='twelve columns'>
            	<p class="dialogTitle"><?php if($pid == 0){ echo "Define New Product";} else { echo $productname;} ?></p>
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
												
						<!-- product key on IPL asset id  start line -->
						<div class='six columns'>
						Product Code (ASSET ID)<span class="fldreq">*</span>
							<div class="selectbox">
								<input type="hidden" name="productkey" id="productkey" value="<?php echo $productcode."~".$assetid; ?>" >
								<a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
									<span class="selectbox-option input-medium" data-option=""><?php if($pid == 0){ echo "Select Product Code";} else { echo $productcode;} ?></span>
									<b class="caret1"></b>
								</a>
								<div class="selectbox-options">
									<input type="text" class="selectbox-filter" placeholder="Search Product Code">
									<ul role="options" style="width:97%">
										<?php 
										if($catid == '1') // IPL
										{
											$qry = $ObjDB->QueryObject("SELECT fld_asset_id AS assetid,fld_id as iplid FROM itc_ipl_master WHERE fld_lesson_type='1'");
											if($qry->num_rows>0){
												while($row = $qry->fetch_assoc())
												{
													extract($row);
													?>
													<li><a tabindex="-1" href="#" data-option="<?php echo $assetid."~".$iplid;?>" onclick="$('#dist').show();"><?php echo $assetid; ?></a></li>
													<?php
												}
											}
										}
										else if($catid == '2') // Modules
										{
											$qry = $ObjDB->QueryObject("SELECT fld_asset_id AS assetid,fld_id as modid FROM itc_module_master WHERE fld_module_type='1' AND fld_delstatus='0'");
											if($qry->num_rows>0){
												while($row = $qry->fetch_assoc())
												{
													extract($row);
													?>
													<li><a tabindex="-1" href="#" data-option="<?php echo $assetid."~".$modid;?>" onclick="$('#dist').show();"><?php echo $assetid; ?></a></li>
													<?php
												}
											}
										}
										else if($catid == '3') // Math Modules
										{
											$qry = $ObjDB->QueryObject("SELECT fld_asset_id AS assetid,fld_id as mathid FROM itc_mathmodule_master WHERE fld_delstatus='0'");
											if($qry->num_rows>0){
												while($row = $qry->fetch_assoc())
												{
													extract($row);
													?>
													<li><a tabindex="-1" href="#" data-option="<?php echo $assetid."~".$mathid;?>" onclick="$('#dist').show();"><?php echo $assetid; ?></a></li>
													<?php
												}
											}
										}
										else if($catid == '5') // Quest (fld_module_type = 7 is quests assetid)
										{
											$qry = $ObjDB->QueryObject("SELECT fld_asset_id AS assetid,fld_id as questid FROM itc_module_master WHERE fld_module_type='7' AND fld_delstatus='0'");
											if($qry->num_rows>0){
												while($row = $qry->fetch_assoc())
												{
													extract($row);
													?>
													<li><a tabindex="-1" href="#" data-option="<?php echo $assetid."~".$questid;?>" onclick="$('#dist').show();"><?php echo $assetid; ?></a></li>
													<?php
												}
											}
										}
										else if($catid == '6') // Expedition
										{
											$qry = $ObjDB->QueryObject("SELECT fld_asset_id AS assetid,fld_id as expid FROM itc_exp_master WHERE fld_delstatus='0'");
											if($qry->num_rows>0){
												while($row = $qry->fetch_assoc())
												{
													extract($row);
													?>
													<li><a tabindex="-1" href="#" data-option="<?php echo $assetid."~".$expid;?>" onclick="$('#dist').show();"><?php echo $assetid; ?></a></li>
													<?php
												}
											}
										}
										else if($catid == '8') // PD
										{
											$qry = $ObjDB->QueryObject("SELECT fld_asset_id AS assetid,fld_id as pdid FROM itc_pd_master WHERE fld_lesson_type='1' AND fld_delstatus='0'");
											if($qry->num_rows>0){
												while($row = $qry->fetch_assoc())
												{
													extract($row);
													?>
													<li><a tabindex="-1" href="#" data-option="<?php echo $assetid."~".$pdid;?>" onclick="$('#dist').show();"><?php echo $assetid; ?></a></li>
													<?php
												}
											}
										}
										else if($catid == '10') // Missions
										{
											$qry = $ObjDB->QueryObject("SELECT fld_asset_id AS assetid,fld_id as misid FROM itc_mission_master WHERE fld_mistype='0' AND fld_delstatus='0'");
											if($qry->num_rows>0){
												while($row = $qry->fetch_assoc())
												{
													extract($row);
													?>
													<li><a tabindex="-1" href="#" data-option="<?php echo $assetid."~".$misid;?>" onclick="$('#dist').show();"><?php echo $assetid; ?></a></li>
													<?php
												}
											}
										}

										?>      
									</ul>
								</div>
							</div>
						</div>     
                  		<!-- product key on IPL asset id end line -->
      
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
								<a tabindex="24" onclick="fn_cancel('<?php echo $pid; ?>','<?php echo $catid; ?>')">Cancel</a>
                            </p>
                        </div>
                        <div class="six columns" id="userphoto">
                            <p class='btn secondary twelve columns'>
								<a tabindex="24" onclick="fn_createproduct('<?php echo $pid; ?>','<?php echo $catid; ?>')">Finish</a>
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