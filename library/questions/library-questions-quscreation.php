<?php
@include("sessioncheck.php");

$id = (isset($_POST['id'])) ? $_POST['id'] : 0;
$id = explode(",",$id);
$timestamp = time();
$question='';
$testtypeid='';
$answertype=0;

$qry = $ObjDB->QueryObject("SELECT a.fld_question_type_id AS testtypeid, a.fld_answer_type AS answertype, a.fld_question AS question, 
								fn_shortname(a.fld_file_name,2) AS shortfilename, a.fld_file_name AS filename, b.fld_answer_types AS anstypename 
							FROM itc_question_details AS a 
							LEFT JOIN itc_question_answer_types AS b ON a.fld_answer_type=b.fld_id 
							WHERE a.fld_id='".$id[0]."' AND a.fld_delstatus='0'");
if($qry->num_rows>0)
{
	while($rowdetails = $qry->fetch_assoc()){			
		extract($rowdetails);			
	}
}
?>

<section data-type='2home' id='library-questions-quscreation'>
    <script type="text/javascript">
		$('#quesdetails').removeClass("active-first");
		$('#review').removeClass("active-last");
		$('#newques').parents().removeClass("dim");
		$('#newques').addClass("active-mid");
		$.getScript("js/colorpicker.js");
		
		$(function(){				
				var t4 = new $.TextboxList('#form_tags_questions1', {
						unique: true, plugins: {autocomplete: {}},
						bitsOptions:{editable:{addKeys: [188]}}	
				});
				t4.getContainer().addClass('textboxlist-loading');				
				$.ajax({url: 'autocomplete.php?oper=new', type:"POST", dataType: 'json', success: function(r){
					t4.plugins['autocomplete'].setValues(r);
					t4.getContainer().removeClass('textboxlist-loading');					
				}});
				<?php 
					$qrytag = $ObjDB->QueryObject("SELECT a.fld_id AS tagid,a.fld_tag_name AS tagname 
													FROM itc_main_tag_master AS a, itc_main_tag_mapping AS b 
													WHERE a.fld_id=b.fld_tag_id AND b.fld_tag_type='19' AND b.fld_access='1' AND a.fld_created_by='".$uid."' 
														AND a.fld_delstatus='0' AND b.fld_item_id='".$id[0]."'");
					
					if($qrytag->num_rows > 0){
						
						while($restag = $qrytag->fetch_assoc()){
							extract($restag);?>
							t4.add('<?php echo $ObjDB->EscapeStrAll($tagname); ?>','<?php echo $tagid; ?>');				
				<?php }
					}
				?>										
		});
		
		
		function fn_loadeditor(){
			tinyMCE.init({
				script_url : "tiny_mce/tiny_mce.js",
				plugins : "asciimath,asciisvg",
				theme : "advanced",
				verify_html : false,
				relative_urls : false,
				remove_script_host : false,
				convert_urls : true,
				mode : "exact",
				elements : "txtquestioneditor",
				theme_advanced_toolbar_location :"hide",
				theme_advanced_toolbar_align : "left",
				theme_advanced_buttons1 :"bold,italic,underline,strikethrough,bullist,numlist,separator,"
				+ "justifyleft,justifycenter,justifyright,justifyfull,link,unlink,spellchecker,forecolor,pdw_toggle",
				theme_advanced_resizing : true,
				theme_advanced_buttons2 :"formatselect,fontselect,fontsizeselect,anchor,image,separator,undo,redo,cleanup,code,sub,cut ,copy,paste,forecolorpicker,backcolorpicker"+" sup,charmap,outdent,indent,hr",
						
				AScgiloc : '<?php echo __TINYPATH__;?>php/svgimg.php', //change me
				ASdloc : '<?php echo __TINYPATH__;?>plugins/asciisvg/js/d.svg' //change me	
			});
		}
		
		setTimeout("fn_loadeditor()",2000);
		$('.textarea').css('border','none');
		$('.textarea').css('box-shadow','none');
	</script>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="darkTitle">Create Question</p>
                <p class="darkSubTitle">&nbsp;</p>
            </div>
        </div>
        
        <div class='row rowspacer'>
            <div class='twelve columns formBase'>
                <div class='row'>
                    <div class='eleven columns centered insideForm'>
                    	<form name="questionforms" id="questionforms">
	                        <div class='row'> 
                                <div class='eight columns'>
                                    Question<span class="fldreq">*</span>
                                    <dl class='field row'>
                                        <dt class='textarea' style="height:280px;">
                                        	<textarea id="txtquestioneditor" name="txtquestioneditor" rows="17" style="height:283px; width:546px; border-color:#FFF;"><?php echo htmlentities($question);?></textarea>
                                        </dt>
                                    </dl>
                                </div> 
                                <div class="four columns questionTools" id="questionTools">
                                    <input type="hidden" id="imghid" value="" />
                                    <input id="imageUploader" name="imageUploader" type="file" />	<!-- Web IPL Upload Button -->
                            		<div id="queueicon" style="display:none;"></div>
                                   	<script language="javascript" type="text/javascript">
                                        $('#imageUploader').uploadify({
											'formData'     : {
												'timestamp' : '<?php echo $timestamp; ?>',
												'token'     : '<?php echo md5('nanonino' . $timestamp);?>',
												'oper' : 'question/images'
											},
											'swf'      : 'uploadify/uploadify.swf',
											'uploader' : '<?php echo _CONTENTURL_; ?>uploadify.php',
											'buttonClass' : 'btn',
											'buttonText' : 'Insert Image',
											'fileTypeExts' : '*.png;*.jpg;*.gif;',
											'queueID'  : 'queueicon',
											'queueSizeLimit' : 1,
											'width' : 250,
											'onUploadSuccess' : function(file, data, response) {
												var editorcontent = tinyMCE.activeEditor.selection.getContent();
												var tempeq = '<img src="<?php echo __CNTQUEIMGPATH__; ?>'+data+'" />';
												tinyMCE.activeEditor.selection.setContent(editorcontent+"<span style='float:left'>"+tempeq+"</span>");
											}
										});
                                    </script>
                                    <div class="divequation-symbols">
                                        <?php
                                        $symbolsqry = $ObjDB->QueryObject("SELECT fld_id, fld_img_src, TRIM(fld_equations) AS fld_equations 
																			FROM itc_equation_editor");
                                        while($rowsymbol =$symbolsqry->fetch_object())
                                        {
                                            ?>
                                            <div class="divsymbols">
                                                <img src="<?php echo __HOSTADDR__; ?>img/equation-img/<?php echo $rowsymbol->fld_img_src.".png";?>" alt="<?php echo $rowsymbol->fld_equations;?>" id="q_<?php echo $rowsymbol->fld_id;?>" border="0" onclick="fn_addtoquestion(this,0);" />
                                            </div>                   
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                       		</div>        
                            
                            <div class='row rowspacer'>
                                <div class='twelve columns'>
                                    To create a new tag, type a name and press Enter.
                                    <div class="tag_well">
                                        <input type="text" name="test3" value="" id="form_tags_questions1" />
                                    </div>
                                </div>
                            </div>
								                                
							<?php
							
                            if($testtypeid == 2 || $testtypeid == 3)
                            {
                            ?>
                                <div class='row rowspacer'>
                                    <div class='six columns' id="questionfiletype" style="display:block">
                                        <p class='lableRight'>Remediation File: (Upload only SWF & Zip File)</p>
                                    </div>
                                    
                                    <div class='eight columns'>
                                        <div class='row'>
                                            <input id="questionUploader" name="questionUploader" type="file" />	<!-- Web IPL Upload Button -->
                                            <br /><br />
                            				<div id="queue" style="width:100%;" original-title='<?php echo $filename; ?>'>
                                            	<?php
													if($filename != '') {
														echo $filename;
													?>
															<input type="button" id="btnlibrary-questions-preview" value="Preview" class="mainBtn remediation" name="<?php echo $filename.",rem"?>" align="right" />
                                                    <?php
                                                    }
												?>
                                            </div>
                                    		<script language="javascript" type="text/javascript">
												$('#questionUploader').uploadify({
													'formData'     : {
														'timestamp' : '<?php echo $timestamp; ?>',
														'token'     : '<?php echo md5('nanonino' . $timestamp);?>',
														'oper' : 'question/remediations'
													},
													'swf'      : 'uploadify/uploadify.swf',
													'uploader' : '<?php echo _CONTENTURL_; ?>uploadify.php',
													'buttonClass' : 'btn',
													'buttonText' : 'Select Remediation File',
													'fileTypeExts' : '*.swf;*.zip;',
													'queueID'  : 'queue',
													'queueSizeLimit' : 1,
													'width' : 250,
													'onUploadSuccess' : function(file, data, response) {
														if(data == '' || data == undefined || data == 'invalid'){
															alert("Please upload a valid remediation zip");
															return false;
														}else{														
															$('#swfhid').val(data);
															$('#queue').html(data+' <input type="button" id="btnlibrary-questions-preview" value="Preview" class="mainBtn remediation" name="'+data+',rem" align="right" />');
														}
													}
												});
											</script>
                                        </div>
                                    </div>
                              	</div>
                            <?php 
                            }
							
                            ?>
                            <div class='row rowspacer'>	
								<div class='eight columns'>
                                   Answer Type<span class="fldreq">*</span>
                                    <div class="selectbox">
                                    	<input type="hidden" name="answertypeid" id="answertypeid" value="<?php echo $answertype; ?>" onChange="fn_loadanswerchoice(this.value,<?php echo $id[0];?>)">
                                        <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                            <span class="selectbox-option input-medium" data-option="<?php echo $answertype;?>" id="clearsubject"><?php if($answertype!=0) echo $anstypename; else echo "Answer type"; ?></span>
                                            <b class="caret1"></b>
                                        </a>
                                        <script>
											var a = <?php echo $answertype;?>;
											if(a!=0)
												fn_loadanswerchoice(<?php echo $answertype;?>,<?php echo $id[0];?>);

										</script>
                                        <div class="selectbox-options">
                                        	<input type="text" class="selectbox-filter" placeholder="Select answer type">
                                            <ul role="options">
												<?php 
                                                $licqry = $ObjDB->QueryObject("SELECT fld_id, fld_answer_types 
																				FROM itc_question_answer_types ORDER BY fld_answer_types");
                                                while($res=$licqry->fetch_object()){?>
                                                	<li><a tabindex="-1" href="#" data-option="<?php echo $res->fld_id;?>"><?php echo $res->fld_answer_types; ?></a></li>
                                                <?php }	?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class='row rowspacer' id="divloadanswer" style="min-height:200px;">		    
                                
                            </div>
                               
                            <div class='row rowspacer' id="showstep" style="display:none;">		    
                            	<div class='twelve columns' style="float:right">
                                    <input class="darkButton" type="button" id="btnstep"  style="width:200px; height:40px;float:right;" value="Next Step" onClick="fn_step2(<?php echo $id[0];?>,<?php echo $testtypeid;?>);" />
                                </div>
                            </div>
                            <input type="hidden" id="swfhid" value="<?php echo $filename;?>" />
                       	</form>
	                </div>
    	        </div> 
        	 </div>
     	</div>
	</div>
</section>
<?php
	@include("footer.php");