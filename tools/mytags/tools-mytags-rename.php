<?php 
	@include("sessioncheck.php");
	$id = isset($method['id']) ? $method['id'] : '0';	
	$tags=explode(',',$id);	
?> 
<section data-type='2home' id='tools-mytags-rename'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Rename Items</p>
            	<p class="dialogSubTitleLight">Change your tag name.</p>
            </div>
        </div>
        
        <div class='row formBase rowspacer'>
            <div class='eleven columns centered insideForm'>
                <form name="tagform" id="tagform">
                	 <?php 
						for($i=0;$i<sizeof($tags);$i++){
								$qry = $ObjDB->QueryObject("select fld_id, fld_tag_name from itc_main_tag_master where fld_id='".$tags[$i]."'");
								$restag = $qry->fetch_assoc();
								extract($restag);?>
                                <div class='row'>                    
                                    <div class='six columns'> 
                                        <dl class='field row'>
                                            <dt class='text'>
                                                <input placeholder='Tag Name' type='text' name="txttagname" id="txttagname_<?php echo $fld_id; ?>" value="<?php echo $fld_tag_name; ?>" onblur="fn_checktagname(<?php echo $fld_id;?>)" class="quantity"/>
                                            </dt>
                                        </dl>
                                    </div>
                                    <div class='three columns'> 
                                        <p class='btn twelve columns' id="remove">
                                            <a onclick="fn_rename(<?php echo $fld_id; ?>);"> Rename </a>
                                        </p>
                                    </div>                    
                                </div>
                      <?php	}?> 
                </form>
                <input type="hidden" id="hiderror" value="0" />
            </div>
        </div>
        <script type="text/javascript" language="javascript">
			/***addd license validate****/
			$(function(){
				$("#tagform").validate({
					ignore: "",
					errorElement: "dd",
					errorPlacement: function(error, element) {						
						if($(element).attr("class") == "quantity error"){								
								$(element).parents('dl').addClass('error');
								error.appendTo($(element).parents('dl'));									
								error.addClass('msg');
								error.html('Please type tagname');																			
						}
						else{
							$(element).parents('dl').addClass('error');
							error.appendTo($(element).parents('dl'));
							error.addClass('msg');								
						}
					},	
					highlight: function(element, errorClass, validClass) {
						$(element).parents('dl').addClass(errorClass);
						$(element).addClass(errorClass).removeClass(validClass);
					},
					unhighlight: function(element, errorClass, validClass) {
						if($(element).attr('class') == 'error' || $(element).attr('class') == 'quantity error'){
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
</section>
<?php
	@include("footer.php");
