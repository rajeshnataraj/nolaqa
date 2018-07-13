<?php
@include("sessioncheck.php");

$id = (isset($method['id'])) ? $method['id'] : 0;
$id = explode(",",$id);
$answertype = 0;
$question='';
$testtypeid='';
$qrytagcnt=0;
$qrylessoncnt=0;
$qrycnt=0;
$timestamp = time();
$filename='';

if($id[0] != 0){
	$qry = $ObjDB->QueryObject("SELECT a.fld_question_type_id AS testtypeid, a.fld_answer_type AS answertype, a.fld_question AS question, a.fld_file_name AS filename, b.fld_answer_types                                       AS anstypename FROM itc_question_details AS a 
	                          LEFT JOIN itc_question_answer_types AS b ON a.fld_answer_type=b.fld_id 
	                          WHERE a.fld_id='".$id[0]."' AND a.fld_delstatus='0'");
	
	$qrylesson = $ObjDB->QueryObject("SELECT (CASE WHEN a.fld_tag_type = 1 THEN CONCAT(a.fld_tag_id,'_lesson') WHEN a.fld_tag_type = 4 
	                                 THEN CONCAT(a.fld_tag_id,'_unit') END) AS tagid, (CASE WHEN a.fld_tag_type = 1 
									 THEN (SELECT fld_ipl_name FROM itc_ipl_master WHERE fld_id=a.fld_tag_id AND fld_delstatus='0') 
									 WHEN a.fld_tag_type = 4 THEN (SELECT fld_unit_name FROM itc_unit_master 
									 WHERE fld_id=a.fld_tag_id AND fld_delstatus='0') END) 
									AS tagname FROM itc_main_tag_mapping AS a 
									WHERE a.fld_item_id='".$id[0]."' AND a.fld_lesson_flag='1' AND a.fld_access='1'");
	
	$qrytag = $ObjDB->QueryObject("SELECT a.fld_id AS tagid,a.fld_tag_name AS tagname FROM itc_main_tag_master AS a, itc_main_tag_mapping AS b 
	                              WHERE a.fld_id=b.fld_tag_id AND b.fld_tag_type='19' AND b.fld_access='1' AND (a.fld_created_by='".$uid."' or a.fld_profile_id='2')
								  AND a.fld_delstatus='0' AND b.fld_item_id='".$id[0]."'");
	$qrytagcnt=$qrytag->num_rows;
	$qrylessoncnt=$qrylesson->num_rows;
	$qrycnt=$qry->num_rows;
	
	if($qry->num_rows>0)
	{
		while($rowdetails = $qry->fetch_assoc()){			
			extract($rowdetails);			
		}
	}
}
?>
<section data-type='2home' id='test-testassign-quscreation'>
    <script type="text/javascript">
	
		$(function(){				
			var t4 = new $.TextboxList('#form_tags_questions1', 
			{
				unique: true, plugins: {autocomplete: {}},
				bitsOptions:{editable:{addKeys: [188]}}	});
				<?php 
					$chk = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_main_tag_mapping 
					                                    WHERE fld_item_id='".$id[0]."' AND fld_access='1' 
														AND fld_tag_id=61");
					if($chk!=0){?>
						t4.add('MAEP','61');
					<?php }
					if($qrytagcnt > 0){
						while($restag = $qrytag->fetch_assoc()){
							extract($restag);?>
							t4.add('<?php echo $ObjDB->EscapeStrAll($tagname); ?>','<?php echo $tagid; ?>');				
				<?php }
					}
					if($qrylessoncnt > 0){
						while($resqrylesson = $qrylesson->fetch_assoc()){
							extract($resqrylesson);?>
							t4.add('<?php echo $ObjDB->EscapeStrAll($tagname); ?>','<?php echo $tagid; ?>');				
				<?php }
					}
				?>					
			t4.getContainer().addClass('textboxlist-loading');				
			$.ajax({url: 'autocomplete.php?oper=new&assessment=1', dataType: 'json', success: function(r){
				t4.plugins['autocomplete'].setValues(r);
				t4.getContainer().removeClass('textboxlist-loading');					
			}});						
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
            	<p class="dialogTitle">Question</p>
				<p class="dialogSubTitleLight"></p>
            </div>
        </div>
        
        <div class='row rowspacer'>
            <div class='twelve columns formBase'>
                <div class='row'>
                    <div class='eleven columns centered insideForm'>
                    	<form name="questionforms" id="questionforms">
	                        <div class='row'> 
                                <div class='eight columns'>                                    
                                    Enter Question<span class="fldreq">*</span>
                                    <dl class='field row'>
                                        <dt class='textarea' style="height:280px;">
                                        	<textarea id="txtquestioneditor" name="txtquestioneditor" rows="17" style="height:283px; width:546px; border-color:#FFF;"><?php echo htmlentities($question);?></textarea>
                                        </dt>
                                    </dl>
                                </div> 
                                
                                <div class="four columns questionTools" id="questionTools">
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
										$('#questionUploader').on("click",function(){
                                            $('#clearname').remove();
                                        });
                                  
                                    </script>
                                    <input type="hidden" id="imghid" value="" />
                                    <input id="imageUploader" name="imageUploader" type="file" />	<!-- Web IPL Upload Button -->
                                  <div class="divequation-symbols">
                                        <?php
                                        $symbolsqry = $ObjDB->QueryObject("SELECT fld_id, fld_img_src, TRIM(fld_equations) AS fld_equations FROM itc_equation_editor");
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
								                                
							<div class='row rowspacer'>	
								<div class='eight columns'>
									<span>Answer</span>
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
                                                $licqry = $ObjDB->QueryObject("SELECT fld_id, fld_answer_types FROM itc_question_answer_types");
                                                while($res=$licqry->fetch_object()){?>
                                                	<li><a tabindex="-1" href="#" data-option="<?php echo $res->fld_id;?>"><?php echo $res->fld_answer_types; ?></a></li>
                                                <?php }	?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class='row rowspacer' id="divloadanswer">		    
                                
                            </div>
                               
                            <div class='row rowspacer' id="divpreviewmatrices" style="display:none;">		    
                                
                            </div>
                               
                            <div class='row rowspacer' id="showstep" style="display:none;">		    
                            	<div class='twelve columns' style="float:right">
                                	<div class="tRight">
                                    	<?php if($id[1] == "qbank") {?>
                                        		<input type="button" id="btnstep" class="darkButton" style="width:162px; height:42px;float:right;" value="<?php if($id[2] == "new"){ echo "Save Question"; } else { echo "Update Question";} ?>" onclick="fn_step2(<?php echo $id[0];?>,'<?php echo $id[1];?>')" />
                                        		
                                        <?php } else { ?>
                                        	<input type="button" id="btnstep" class="darkButton" style="width:162px; height:42px;float:right;" value="Save Question" onclick="fn_step2(<?php echo $id[0];?>,<?php echo $id[1];?>)" />
                                        	
                                        <?php } ?>
                                    
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="swfhid" value="<?php echo $filename;?>" />
                            <input type="hidden" id="previewclick" value="<?php echo $answertype;?>" />
                       	</form>
	                </div>
    	        </div>
        	 </div>
     	</div>
	</div>
</section>
<?php
	@include("footer.php");