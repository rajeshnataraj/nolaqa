<?php
/*------
		Page - library-customcontent-newcustomcontent
		Description:
		Create or update the customcontent
		
		Actions Performed:
		Create - Create a new customcontent
		Update - Update the existing customcontent
		Cancel - Redirect to library-customcontent.php page ( show customcontent list )
		
		
		History:

------*/
	
	@include("sessioncheck.php");
	
	$customcontentid = isset($method['id']) ? $method['id'] : '0';	
	

	if($customcontentid != '' and $customcontentid!='undefined' and $customcontentid!='0'){
		$pageTitle = "Edit Custom content";
		$btnclick = "fn_cancel('library-customcontent-actions')";
		$btnvalue = "Update content";
		$btncancel = "Cancel";
	}
	else{
		$pageTitle = "New Custom content";
		$btnclick = "fn_cancel('library-customcontent')";
		$btnvalue = "Create content";
		$btncancel = "Cancel";
	}
	
	$qry_customcontentdetails = $ObjDB->QueryObject("SELECT fld_id AS id, fld_contentname AS name, fld_pointspossible AS points FROM itc_customcontent_master WHERE fld_id='".$customcontentid."' AND fld_delstatus='0'");
	
	$name="";
	$points="";
	if($qry_customcontentdetails->num_rows>0)
	{
		$contentdetails = $qry_customcontentdetails->fetch_assoc();		
		extract($contentdetails);	
	}
?>

<!-- Autocomplete script start -->

<script type="text/javascript" charset="utf-8">	
	$(function(){				
		var t4 = new $.TextboxList('#form_tags_newcustomcontent', 
		{
			unique: true, plugins: {autocomplete: {}},
			bitsOptions:{editable:{addKeys: [188]}}	});
		<?php 
			if($customcontentid != '' and $customcontentid!='undefined'){
				$qrytag = $ObjDB->QueryObject("SELECT a.fld_id AS tagid,a.fld_tag_name AS tagname FROM itc_main_tag_master AS a, itc_main_tag_mapping AS b WHERE a.fld_id=b.fld_tag_id AND b.fld_tag_type='25' AND b.fld_access='1' AND a.fld_created_by='".$uid."' AND a.fld_delstatus='0' AND b.fld_item_id='".$customcontentid."'");
				if($qrytag->num_rows > 0) {
					while($restag = $qrytag->fetch_assoc()){
						extract($restag);
			?>
					t4.add('<?php echo $ObjDB->EscapeStrAll($tagname); ?>','<?php echo $tagid; ?>');				
			<?php 	}
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

<!-- Autocomplete script end -->

<section data-type='2home' id='library-customcontent-newcustomcontent'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="darkTitle"><?php echo $pageTitle;?></p>
                <p class="darkSubTitle">&nbsp;</p>
            </div>
        </div>    
        
        <div class='row formBase rowspacer'>
            <div class='eleven columns centered insideForm'>
				<form name="customcontentforms" id="customcontentforms"> 
                     <!-- Customcontent name text box start -->            
                    <div class='row rowspacer'>
                        <div class='six columns'> 
                         	 Name of the custom content<span class="fldreq">*</span> 
                            <dl class='field row'>
                                <dt class='text'>
                                	<input placeholder='Name of the custom content' type='text' id="contentname" name="contentname" value="<?php echo $name; ?>" onBlur="$(this).valid();">
                                </dt>
                            </dl>
                        </div>
                        
                        <div class='six columns'>  
                         	Grade<span class="fldreq">*</span> 
                            <dl class='field row'>
                                <dt class='text'>
                                	<input placeholder='Points Possible' type='text' id="txtpp" name="txtpp" value="<?php echo $points; ?>"  onBlur="$(this).valid();" onkeypress="return isNumber(event)" >
                                </dt>
                            </dl>
                        </div>
                    </div> 
					<!-- Customcontent text box End -->
                    
                     
					<script type="text/javascript" language="javascript">
                
                        $(function(){
                            $("#customcontentforms").validate({
                                ignore: "",
                                errorElement: "dd",
                                errorPlacement: function(error, element) {
                                $(element).parents('dl').addClass('error');
                                error.appendTo($(element).parents('dl'));
                                error.addClass('msg');
                                },

                                rules: {                                    
                                    contentname: { required: true, lettersonly: true, 
									remote:
									{ 
										url: "library/customcontent/library-customcontent-ajax.php",
										type:"POST",  
										data: {  
												id: function() {
												return '<?php echo $customcontentid;?>';},
												oper: function() {
												return 'checkcustomcontentname';}
														  
											},	
										 async:false } 
                                },
								
									txtpp:{ required: true }
                                },
                            
                                messages: {                                   
                                    contentname: { required: "Please Type Custom Content Name", remote: "Custom Content Name Already Exists" },
									txtpp: { required: "Please Type Points Possible" }
                                } ,
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
                    
                   
                                    
                    <!--start of new filter-->
        
                    <div class='row rowspacer'>
                        <div class='twelve columns'>
                        To create a new tag, type a name and press Enter.
                        <div class="tag_well">
                            <input type="text" name="test3" value="" id="form_tags_newcustomcontent"/>
                        </div>
                        </div>
                    </div>
            
                    <!--end of new filter-->
                                    
                    <div class='row rowspacer' id="customcontentbtn">
                       <div class='row'>
                            <div class='four columns btn primary push_two noYes'>
                                <a onclick="<?php echo $btnclick;?>" tabindex="4"><?php echo $btncancel;?></a>
                            </div>
                            <div class='four columns btn secondary yesNo'>
                                <a onclick="fn_createcustomcontent(<?php echo $customcontentid;?>)"><?php echo $btnvalue;?></a>
                            </div>
                        </div>
                   	</div>
				</form>
        	</div>              
        </div> 
	</div>
</section>
<?php
	@include("footer.php");

