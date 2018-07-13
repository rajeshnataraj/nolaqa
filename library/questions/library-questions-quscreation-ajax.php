<?php
	@include("sessioncheck.php");
	$oper = isset($_POST['oper']) ? $_POST['oper'] : '';
	$date = date("Y-m-d H:i:s");
	$timestamp = time();
	
	/*--- Load Answer Choise ---*/
	if($oper == "loadanswerchoice" and $oper != "") 
	{
		$answertypeid = isset($_POST['answertypeid']) ? $_POST['answertypeid'] : '';
		$questionid = isset($_POST['questionid']) ? $_POST['questionid'] : '';
		$timestamp = time();
		?>
		<script type="text/javascript" language="javascript">
			$("input[type^=text]").keypress(function (e) {
				if (e.which == 34) {
					return false;
				}
			});
		</script>
        <?php
		/*--- Multiple Choise ---*/
		if($answertypeid == 1)
		{
			$qry = $ObjDB->QueryObject("SELECT GROUP_CONCAT(IF(fld_attr_id = '1', fld_answer, NULL) SEPARATOR '~') AS 'choice', 
											GROUP_CONCAT(IF(fld_attr_id = '2', fld_answer, NULL) SEPARATOR '~') AS 'correct' 
										FROM itc_question_answer_mapping 
										WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' AND fld_answer<>'' AND fld_flag='1'");
										
			$alphabet = array('A','B','C','D','E','F','G','H');
			$anscnt = 0;
			if($qry->num_rows > 0) {
				while($row = $qry->fetch_assoc())
				{
					extract($row);
					$anschoices = explode("~",$choice);
					$correctans = explode("~",$correct);
				}
			}
			
			$hcount = 2;
			for($hc=2;$hc<=7;$hc++){
				if(isset($anschoices[$hc]) and  $anschoices[$hc] != '')
					$hcount = $hc + 1;
			}
			 
			$newheight = $hcount * 200;
			
			for($i=1;$i<=8;$i++){
				if(in_array($i,$correctans)){
					${'choiceans'.$i} = '1';
				}
				else {
					${'choiceans'.$i} = '0';
				}
			}
			
		?>
			<script type="text/javascript" language="javascript">
				function fn_loadtexteditor(){
					tinyMCE.init
					({
						script_url : "<?php echo __TINYPATH__; ?>tiny_mce.js",
						browser:"msie,gecko,opera",
						plugins : "asciimath,asciisvg",
						theme : "advanced",
						verify_html : false,
						mode : "exact",
						elements : "txtanswereditor1,txtanswereditor2,txtanswereditor3,txtanswereditor4,txtanswereditor5,txtanswereditor6,txtanswereditor7,txtanswereditor8",						
						body_class : "my_class",						
						theme_advanced_toolbar_location :"hide",
						theme_advanced_toolbar_align : "left",
						theme_advanced_buttons1 :"bold,italic,underline,strikethrough,bullist,numlist,separator,"
						+ "justifyleft,justifycenter,justifyright,justifyfull,link,unlink,spellchecker,forecolor,pdw_toggle",
						theme_advanced_resizing : true,
						theme_advanced_buttons2 :"formatselect,fontselect,fontsizeselect,anchor,image,separator,undo,redo,cleanup,code,sub,cut ,copy,paste,forecolorpicker,backcolorpicker"+" sup,charmap,outdent,indent,hr",						
						AScgiloc : '<?php echo __TINYPATH__; ?>php/svgimg.php', //change me
						ASdloc : '<?php echo __TINYPATH__; ?>plugins/asciisvg/js/d.svg' //change me	
					});	
				}
				
				setTimeout("fn_loadtexteditor()",1000);
				$('.textarea').css('border','none');
				$('.textarea').css('box-shadow','none');
			</script>
			
			<div class='eight columns'>
				<div id='TextBoxesGroup'>
					<?php
						for($i=1;$i<=8;$i++){	
					?>
					<div id="TextBoxDiv<?php echo $i; ?>" <?php if($i > $hcount) {?>style="display:none"<?php }?> >
						<label class="label">Choice #<?php echo $i; ?>: </label>&nbsp;
						<div style="margin-top: -8%; margin-left: 55%;">
							<input type="button" onclick="fn_selectans('right1','<?php echo $alphabet[$i-1]; ?>');$('#ans<?php echo $i; ?>').val(1);" id="PAR1_<?php echo $alphabet[$i-1]; ?>" value="RIGHT" <?php if(${'choiceans'.$i} == 1){ echo 'class="green_dark right_wrong"'; } else { echo 'class="green_light right_wrong"'; } ?>/>
							<input type="button" onclick="fn_selectans('wrong1','<?php echo $alphabet[$i-1]; ?>');$('#ans<?php echo $i; ?>').val(0);" id="PAR2_<?php echo $alphabet[$i-1]; ?>" <?php if(${'choiceans'.$i} != 1){ echo 'class="red_dark right_wrong"'; } else { echo 'class="red_light right_wrong"'; } ?> value="WRONG" />
						</div>
						<dt class='textarea' style="height: 130px; margin-left: 10px; margin-right: 40px; width: 500px;">
							<textarea id="txtanswereditor<?php echo $i; ?>" name="txtanswereditor<?php echo $i; ?>" rows="17" style="height:100px;" onblur="tinymce.execCommand('mceFocus',false,'txtanswereditor<?php echo $i+1; ?>');"><?php if(isset($anschoices[$i-1])) echo htmlentities($anschoices[$i-1]);?></textarea>
						</dt>
						<div class="clear"></div>
					</div>
					<?php
						}
					?>
				</div>
				<div style="text-align:right; margin-right:50px">
					<input type="button" class="darkButton" value="+" name="addmulchoice" id="addmulchoice" onclick="addanochoice($('#hidchoicename').val(),0);"/>
					<input type="button" class="darkButton" value="-" name="removemulchoice" id="removemulchoice" onclick="addanochoice($('#hidchoicename').val(),1);" <?php if($hcount==2) {?>style="display:none"<?php }?>/>
				</div>
				<input type="hidden" id="ans1" value="<?php if($choiceans1==1){ echo "1"; } else{ echo "0"; }?>"/>
				<input type="hidden" id="ans2" value="<?php if($choiceans2==1){ echo "1"; } else{ echo "0"; }?>"/>
				<input type="hidden" id="ans3" value="<?php if($choiceans3==1){ echo "1"; } else{ echo "0"; }?>"/>
				<input type="hidden" id="ans4" value="<?php if($choiceans4==1){ echo "1"; } else{ echo "0"; }?>"/>
				<input type="hidden" id="ans5" value="<?php if($choiceans5==1){ echo "1"; } else{ echo "0"; }?>"/>
				<input type="hidden" id="ans6" value="<?php if($choiceans6==1){ echo "1"; } else{ echo "0"; }?>"/>
				<input type="hidden" id="ans7" value="<?php if($choiceans7==1){ echo "1"; } else{ echo "0"; }?>"/>
				<input type="hidden" id="ans8" value="<?php if($choiceans8==1){ echo "1"; } else{ echo "0"; }?>"/>
				<input type="hidden" value="" id="hidprogress" name="hidprogress" />
				<input type="hidden" value="" id="hidonfocus" name="hidonfocus" />
				<input type="hidden" value="<?php echo $hcount;?>" id="hidchoicename" name="hidchoicename" />
			</div>	
			
			<div class="four columns">
				<div id="questionTools1" class="questionTools" style="height:<?php echo $newheight;?>px;">
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
		<?php	
		} // Multiple Choice Ends
	
		/*--- Single Answer id=2 ---*/
		if($answertypeid == 2)
		{
			$qry = $ObjDB->QueryObject("SELECT GROUP_CONCAT(IF(fld_attr_id = '3', fld_answer, NULL)) AS `prefixans`, 
											GROUP_CONCAT(IF(fld_attr_id = '4', fld_answer, NULL)) AS `suffixans`, 
											GROUP_CONCAT(IF(fld_attr_id = '1', fld_answer, NULL)) AS `answerarray` 
										FROM itc_question_answer_mapping 
										WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' AND fld_flag='1'");
			if($qry->num_rows > 0) {
				while($row = $qry->fetch_assoc())
				{
					extract($row);
				}
			}
			?>
            <div class='eight columns'>
                Text Prefix: &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;<input type="text" class="mix-input qit-medium" id="pretext" placeholder="" value="<?php echo $prefixans; ?>" onkeyup="ChkValidChar(this.id);"/> <br/><br/>
                Correct answer: &nbsp;&nbsp;<input type="text" class="mix-input qit-big" id="txtsingleanswer" name="txtsingleanswer" value="<?php echo $answerarray; ?>" placeholder="Enter your answer here" onkeyup="ChkValidChar(this.id);"/><br/><br/>
                Text Suffix:&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;<input type="text" class="mix-input qit-medium" id="posttext"  placeholder="" value="<?php echo $suffixans; ?>" onkeyup="ChkValidChar(this.id);"/><br/>
          	</div>
            <script language="javascript" type="text/javascript">
				$('input').autoGrowInput({
					comfortZone: 50,
					maxWidth: 200
				});
			</script>
       	<?php	
		} // Single Answer Ends
		
		/*--- Match the following id=3 ---*/ 
		if($answertypeid == 3 )
		{
			$i='';
			$qry = $ObjDB->QueryObject("SELECT GROUP_CONCAT(IF(fld_attr_id = '3', fld_answer, NULL) SEPARATOR '~') AS `prefixans`, 
											GROUP_CONCAT(IF(fld_attr_id = '4', fld_answer, NULL) SEPARATOR '~') AS `suffixans` 
										FROM itc_question_answer_mapping
										WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' AND fld_flag='1'");
			$prefixarray = array();
			$suffixarray = array();
			if($qry->num_rows > 0){
				while($row = $qry->fetch_assoc())
				{
					extract($row);
					$prefixarray = explode("~",$prefixans);
					$suffixarray = explode("~",$suffixans);
					$i++;
				}
			}
			
			$count = sizeof($prefixarray);
			?>
            <div class='eight columns'>
              	<div class="row">
                	<div class='eight columns'>
                    	Correct Answer: &nbsp;
                        <div class="selectbox" style="width:15rem;">
                            <input type="hidden" name="selectcount" id="selectcount" value="<?php echo $count; ?>" onchange="fn_insertmathboxes(<?php echo $answertypeid;?>,<?php echo $questionid; ?>)">
                            <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                <span class="selectbox-option input-medium" style="width: 90%;" data-option="<?php echo $count; ?>" id="clearsubject"><?php if($count ==0){ echo "No.of Answers"; } else { echo $count; } ?></span>
                                <b class="caret1"></b>
                            </a>
                            <script language="javascript" type="text/javascript">
                                var a = <?php echo $answertypeid;?>;
                                if(a!=0)
                                    fn_insertmathboxes(<?php echo $answertypeid;?>,<?php echo $questionid; ?>)
                            </script>
                            <div class="selectbox-options">
                                <ul role="options">
                                    <?php for($i=2; $i<=10; $i++) { ?>
                                        <li><a tabindex="-1" href="#" data-option="<?php echo $i; ?>"><?php echo $i; ?></a></li>
                                    <?php }	?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row rowspacer">
                	<div class='eleven columns' id="mulboxes">
						<?php 
                        for($i=1;$i<=$count;$i++){ 
                            $j=$i; $j--;?>
                            <input type="text" class="mix-input qit-medium" id="mulbox<?php echo $i;?>" value="<?php echo $prefixarray[$j]; ?>" style="height:30px;" placeholder="Choices" onkeyup="ChkValidChar(this.id);"/>&nbsp; 
                            <input type="text" class="mix-input qit-medium" id="ans<?php echo $i;?>" style="width:50px;height:30px;" value="<?php echo $suffixarray[$j]; ?>" placeholder="Answer" onkeyup="ChkValidChar(this.id);"/><br /><br />
                        <?php 	
                        }
                        ?>
                    </div>
                </div>
			</div>  
        	<script language="javascript" type="text/javascript">
				$('input').autoGrowInput({
					comfortZone: 50,
					maxWidth: 300
				});
			</script>          
			<?php
		}// Match the following Ends
		
		/*--- Custom answer type id=4 ---*/
		if($answertypeid == 4)
		{	
			$qry = $ObjDB->QueryObject("SELECT GROUP_CONCAT(IF(fld_attr_id = '6', fld_answer, NULL)) AS `answerpattr`, 
											GROUP_CONCAT(IF(fld_attr_id = '9', fld_answer, NULL)) AS `ordertype`
										FROM itc_question_answer_mapping 
										WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' AND fld_flag='1'");
			$answer = array();
			if($qry->num_rows > 0) {
				while($row = $qry->fetch_assoc())
				{
					extract($row);
					$answer = explode(',',$answerpattr);	
				}
			}
		?>
			<script type="text/javascript" charset="utf-8">		
				$(function(){				
					var t4 = new $.TextboxList('#anspattern',{
						unique: false,
						startEditableBit: false,
						inBetweenEditableBits: false,
						plugins: {autocomplete: {onlyFromValues:true}},
						bitsOptions:{editable:{addKeys: [188]}}	
					});
					<?php 
						if($answer[0] != '') {
							for($i=0;$i<sizeof($answer);$i++){
								$patternname = $ObjDB->SelectSingleValue("select fld_symbol_name from itc_question_answer_pattern_master where fld_id='".$answer[$i]."'");?>
								t4.add('<?php echo $patternname; ?>','<?php echo $answer[$i];?>');				
					<?php 
							}
						}
					?>
					t4.getContainer().addClass('textboxlist-loading');				
					$.ajax({url: 'library/questions/autocomplete.php', dataType: 'json', success: function(r){
						t4.plugins['autocomplete'].setValues(r);
						t4.getContainer().removeClass('textboxlist-loading');					
					}});						
				});
				<?php if($answer[0] != ''){?>
						fn_preview(<?php echo $questionid; ?>);
				<?php }?>
			</script>
            <div class="row rowspacer" >
                <div class="twelve columns">
                    <div class='form_friends'>
                        <div class="row">         
                            <div class='two columns'>
                                <ul class="field row">
                                    <li>
                                        <label class="radio <?php if($ordertype!=""){?>checked<?php }?>" for="Order" >
                                        <input name="ordertype" type="radio" value="2" style="display:none;" <?php if($ordertype!=""){?>checked="checked"<?php }?> />
                                        <span></span> Order
                                        </label>
                                    </li> 
                                </ul>
                            </div>
                            <div class='two columns'>
                            	<ul class="field row">    
                               		<li>
                                    	<label class="radio <?php if($ordertype==""){?>checked<?php }?> " for="Unorder" >
                                        <input name="ordertype" type="radio"  value="1" style="display:none;" <?php if($ordertype==""){?>checked="checked"<?php }?> />
                                        	<span></span> Unorder
                                        </label>
                                 	</li>
                                </ul>             
                            </div>
                        </div>
                        <input type='text' name="anspattern" id="anspattern" />
                    </div>
                </div>
			</div>
            <div class="row rowspacer" >
                <div class="nine columns"><!-- Preview Custom Answer -->
                	<div id="preview"></div>
                </div>	
                <div class="one columns"></div>
                <div class="two columns">
                	<input type="button" value="Preview" class="btn" style="width:200px;height:40px;float:right;" onClick="fn_preview(<?php echo $questionid; ?>)" />
                </div>
            </div>
		<?php 
		}
		
		/*--- Answer choice id=5 ---*/
		if($answertypeid == 5)
		{
			$qry = $ObjDB->QueryObject("SELECT GROUP_CONCAT(fld_answer SEPARATOR '~') AS answer 
										FROM itc_question_answer_mapping 
										WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' AND fld_flag='1'");
			$answerarray=array();
			$i=0;
			if($qry->num_rows > 0) {
				while($row=$qry->fetch_assoc())
				{
					extract($row);
					$answerarray = explode("~",$answer);
					$i++;
				}
			}
			?>
            <div class="row rowspacer" >
            	<div class="eight columns">
                	<div class="ans1" align="left">
                        <input type="text" onkeyup="ChkValidChar(this.id);" name="pretext" id="pretext" value="<?php if(isset($answerarray[0])){ echo $answerarray[0]; } ?>" class="mix-input" />&nbsp;
                        <input type="button" onclick="fn_selectans('yes1');$('#yesno1').val('yes');$('#yesno2').val('no');" id="PAR9"<?php if($answerarray[2]=="1") {?> class="green_dark right_wrong" <?php } else {?>class="green_light right_wrong"<?php } ?> value="RIGHT" />
                        <input type="button" onclick="fn_selectans('no1');$('#yesno1').val('no');$('#yesno2').val('yes');" id="PAR10" <?php if($answerarray[2]=="1") {?> class="red_light right_wrong" <?php } else {?>class="red_dark right_wrong"<?php }?> value="WRONG" />
                    </div>
                </div>
            </div>
            <div class="row rowspacer" >
           		<div class="eight columns">  
                	<div class="ans2" align="left" style="margin-top:10px;">
                        <input name="posttext" onkeyup="ChkValidChar(this.id);" type="text" class="mix-input" id="posttext" value="<?php if(isset($answerarray[1])){ echo $answerarray[1]; }?>"/>&nbsp;
                        <input type="button" onclick="fn_selectans('yes2');$('#yesno2').val('yes');$('#yesno1').val('no');" id="PAR11"<?php if($answerarray[2]=="2") {?> class="green_dark right_wrong" <?php } else {?>class="green_light right_wrong"<?php }?> value="RIGHT" />
                        <input type="button" onclick="fn_selectans('no2');$('#yesno2').val('no');$('#yesno1').val('yes');" id="PAR12" <?php if($answerarray[2]=="2") {?> class="red_light right_wrong" <?php } else {?>class="red_dark right_wrong"<?php }?> value="WRONG" />
                    </div>	
               	</div>
          	</div>

            <input type="hidden" id="yesno1" value="<?php if($answerarray[2]=="1") { echo "yes";} else{echo "no";}?>"/></td>
            <input type="hidden" id="yesno2" value="<?php if($answerarray[2]=="2") { echo "yes";} else{echo "no";}?>"/></td>
		<?php
		}
		
		/*--- Menu & Extrems id=6 ---*/
		if($answertypeid == 6)
		{
			$qry = $ObjDB->QueryObject("SELECT GROUP_CONCAT(fld_answer SEPARATOR '~') AS answer 
										FROM itc_question_answer_mapping 
										WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' AND fld_flag='1'");
			$answerarray=array();
			$i='';
			if($qry->num_rows > 0) {
				while($row = $qry->fetch_assoc())
				{
					extract($row);
					$answerarray = explode("~",$answer);
					$i++;
				}
			}
			?>
			<div class="twelve columns">
                <div class="means" align="center" style="width:50%; float:left" >
                    <b>Means</b><br /><br />
                    <input type="text" name="mean1" id="mean1" value="<?php if(isset($answerarray[0])){ echo $answerarray[0]; }?>" onkeyup="ChkValidChar(this.id);" class="mix-input"/>&nbsp;
                    <input type="text" name="mean2" id="mean2" value="<?php if(isset($answerarray[1])){ echo $answerarray[1]; }?>" onkeyup="ChkValidChar(this.id);" class="mix-input" />
                </div>
                <div class="means" align="center" style="width:50%;float:left">
                    <b>Extremes</b> <br /><br />
                    <input name="ext1" type="text" class="mix-input" id="ext1" value="<?php if(isset($answerarray[2])){ echo $answerarray[2]; } ?>" onkeyup="ChkValidChar(this.id);"/>&nbsp;
                    <input type="text" name="ext2" id="ext2" value="<?php if(isset($answerarray[3])){ echo $answerarray[3]; }?>" onkeyup="ChkValidChar(this.id);" class="mix-input" />
                </div>
			</div>
			<?php	
		}
		
		/*--- Single Range id=7 ---*/
		if($answertypeid == 7 )
		{
			$qry = $ObjDB->QueryObject("SELECT GROUP_CONCAT(IF(fld_attr_id = '3', fld_answer, NULL)) AS `prefixans`, 
											GROUP_CONCAT(IF(fld_attr_id = '4', fld_answer, NULL)) AS `suffixans`, 
											GROUP_CONCAT(IF(fld_attr_id = '1', fld_answer, NULL) SEPARATOR '~') AS `answer` 
										FROM itc_question_answer_mapping 
										WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' AND fld_flag='1'");
			if($qry->num_rows > 0) {
				while($row = $qry->fetch_assoc())
				{
					extract($row);
					$answerarray = explode("~",$answer);
				}
			}
			?>
            	<div class="row rowspacer">
                	<div class="five columns">
            			Text Prefix: &nbsp;<input type="text" id="pretext" class="mix-input" placeholder="Text Prefix" value="<?php echo $prefixans;?>" onkeyup="ChkValidChar(this.id);"/>        
                    </div>
                    <div class="five columns">
            			Text Suffix: &nbsp;<input type="text" id="posttext" class="mix-input"  placeholder="Text sufix" value="<?php echo $suffixans;?>" onkeyup="ChkValidChar(this.id);"/>      
                    </div>
                </div>	
				<div class="row rowspacer">
                	<div class="five columns">
            			From: &nbsp;<input type="text" id="loweranswer" name="loweranswer" class="mix-input qit-medium" placeholder="Range"  value="<?php if(isset($answerarray[0])){ echo $answerarray[0]; }?>" onkeyup="ChkValidChar(this.id);"/>   
                    </div>
                    <div class="five columns">
            			To &nbsp;<input type="text" id="upperanswer" name="upperanswer" placeholder="Range" class="mix-input qit-medium" value="<?php if(isset($answerarray[1])){ echo $answerarray[1]; }?>" onkeyup="ChkValidChar(this.id);"/>      
                    </div>
                </div>
            	<script language="javascript" type="text/javascript">
					$("#upperanswer").ForceNumericOnly();
					$("#loweranswer").ForceNumericOnly();
				</script>
			<?php	
		}
		
		/*--- Multiple Choice Images id=8 ---*/
		if($answertypeid==8)
		{
			$qry = $ObjDB->QueryObject("SELECT GROUP_CONCAT(IF(fld_attr_id = '1', fld_answer, NULL) SEPARATOR '~') AS 'choice', 
											GROUP_CONCAT(IF(fld_attr_id = '2', fld_answer, NULL) SEPARATOR '~') AS 'correctans' 
										FROM itc_question_answer_mapping 
										WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' AND fld_answer<>'' AND fld_flag='1'");
										
			$alphabet = array('A','B','C','D','E','F','G','H');
			$answerarray = array('','','','','','','','');
			$correct = array('','','','','','','','');
			$anscnt = 0;
			if($qry->num_rows > 0) {
				while($row = $qry->fetch_assoc())
				{
					extract($row);
					$answerarray = explode("~",$choice);
					$correct = explode("~",$correctans);
				}
			}
			
			$hcount = 2;
			for($hc=2;$hc<=7;$hc++){
				if(isset($answerarray[$hc]) and ($answerarray[$hc]!='') and ($answerarray[$hc]!='no-image.png'))
					$hcount = $hc + 1;
			}
			
			$newheight = $hcount * 200;
			
			for($i=1;$i<=8;$i++){
				if(in_array($i,$correct)){
					${'choiceans'.$i} = '1';
				}
				else {
					${'choiceans'.$i} = '0';
				}
			}
			
			$width1 = 93;
			$height1 = 100;
			
			for($i=1;$i<=8;$i++){
				${'image'.$i} = (isset($answerarray[$i-1]) and $answerarray[$i-1] != '') ? "thumb.php?src=".__CNTANSIMGPATH__.$answerarray[$i-1]."&w=200&h=200&zc=3" : '';
			}
			
			?>
			<div class="twelve columns">
            <?php
				for($i=1;$i<=8;$i++){
			?>		
                <div id="img<?php echo $i; ?>" <?php if($i>$hcount) {?>style="display:none"<?php }?>>
                    Image <?php echo $i; ?>:
                    <div class="row rowspacer">
                        <div class="three columns">
                            <input id="imageUploaderquestions<?php echo $i; ?>" name="imageUploaderquestions<?php echo $i; ?>" type="file" />
                            <?php if($i==1){ ?><div id="queueimg" style="display:none;"></div><?php } ?>
                        </div>
                        <div class="eight columns">
                            <div class="row">
                                <div class="five columns">
                                    <input type="button" onclick="fn_selectans('right1','<?php echo $alphabet[$i-1]; ?>');$('#ans<?php echo $i; ?>').val(1);" id="PAR1_<?php echo $alphabet[$i-1]; ?>" value="RIGHT" <?php if(${'choiceans'.$i}==1){ echo 'class="green_dark right_wrong"'; } else { echo 'class="green_light right_wrong"'; } ?>/>
                                    <input type="button" onclick="fn_selectans('wrong1','<?php echo $alphabet[$i-1]; ?>');$('#ans<?php echo $i; ?>').val(0);" id="PAR2_<?php echo $alphabet[$i-1]; ?>" <?php if(${'choiceans'.$i} != 1){ echo 'class="red_dark right_wrong"'; } else { echo 'class="red_light right_wrong"'; } ?> value="WRONG" />
                                </div>
                                <div class="six columns">
                                    <img name="txtimageans<?php echo $i; ?>" id="txtimageans<?php echo $i; ?>" src="<?php echo ${'image'.$i}; ?>" />  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
         	<?php
				}
			?>      
            	<script language="javascript" type="text/javascript">					
					$('input[id^="imageUploaderquestions"]').each(function(index, element) {
						$(this).uploadify({
							'formData'     : {
								'timestamp' : '<?php echo $timestamp; ?>',
								'token'     : '<?php echo md5('nanonino' . $timestamp);?>',
								'oper' : 'question/ansimg'
							},
							'swf'      : 'uploadify/uploadify.swf',
							'uploader' : '<?php echo _CONTENTURL_; ?>uploadify.php',
							'buttonClass' : 'btn',
							'buttonText' : 'Insert Image',
							'fileTypeExts' : '*.jpg;*.png;',
							'queueID'  : 'queueimg',
							'queueSizeLimit' : 1,
							'width' : 150,
							'onUploadSuccess' : function(file, data, response) {							
								$("#txtimageans"+(index+1)).attr('src', 'thumb.php?src=<?php echo __CNTANSIMGPATH__;?>'+data+'&w=200&h=200&zc=3'); 
								$("#image"+(index+1)).val(data); 
							}
						});    
					});
	
				</script> 
                <div>
                	<input type="button" class="darkButton" value="+" name="addmulimg" id="addmulimg" onclick="addanoimg($('#hidimgchname').val(),0);"/>
                    <input type="button" class="darkButton" value="-" name="removemulimg" id="removemulimg" onclick="addanoimg($('#hidimgchname').val(),1);" <?php if(isset($answerarray[2])) {?>style="display:none"<?php }?>/>
                </div>
            </div>
        	<input type="hidden" id="ans1" value="<?php if($choiceans1==1){echo "1";} else{echo "0";}?>"/>
            <input type="hidden" id="ans2" value="<?php if($choiceans2==1){echo "1";} else{echo "0";}?>"/>
            <input type="hidden" id="ans3" value="<?php if($choiceans3==1){echo "1";} else{echo "0";}?>"/>
            <input type="hidden" id="ans4" value="<?php if($choiceans4==1){echo "1";} else{echo "0";}?>"/>
            <input type="hidden" id="ans5" value="<?php if($choiceans5==1){echo "1";} else{echo "0";}?>"/>
            <input type="hidden" id="ans6" value="<?php if($choiceans6==1){echo "1";} else{echo "0";}?>"/>
            <input type="hidden" id="ans7" value="<?php if($choiceans7==1){echo "1";} else{echo "0";}?>"/>
            <input type="hidden" id="ans8" value="<?php if($choiceans8==1){echo "1";} else{echo "0";}?>"/>
            <input type="hidden" id="image1" value="<?php echo $answerarray[0]; ?>"/>
            <input type="hidden" id="image2" value="<?php echo $answerarray[1]; ?>"/>
            <input type="hidden" id="image3" value="<?php echo $answerarray[2]; ?>"/>
            <input type="hidden" id="image4" value="<?php echo $answerarray[3]; ?>"/>
            <input type="hidden" id="image5" value="<?php echo $answerarray[4]; ?>"/>
            <input type="hidden" id="image6" value="<?php echo $answerarray[5]; ?>"/>
            <input type="hidden" id="image7" value="<?php echo $answerarray[6]; ?>"/>
            <input type="hidden" id="image8" value="<?php echo $answerarray[7]; ?>"/>
            <input type="hidden" value="<?php echo $hcount;?>" id="hidimgchname" name="hidimgchname" />
			<?php 
		}
	
		/*--- Single Multiple id=9 ---*/	
		if($answertypeid == 9)
		{
			$qry = $ObjDB->QueryObject("SELECT SUM(IF(fld_attr_id = '1', 1, 0)) AS `count`,
											GROUP_CONCAT(IF(fld_attr_id = '1', fld_answer, NULL) SEPARATOR '~') AS `answer`, 
											GROUP_CONCAT(IF(fld_attr_id = '3', fld_answer, NULL) SEPARATOR '~') AS `prefixans`, 
											GROUP_CONCAT(IF(fld_attr_id = '4', fld_answer, NULL) SEPARATOR '~') AS `suffixans` 
										FROM itc_question_answer_mapping 
										WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' AND fld_flag='1'");
			$answerarray=array();							
			if($qry->num_rows > 0) {
				while($row = $qry->fetch_assoc())
				{
					extract($row);
					$answerarray = explode("~",$answer);
				}
			}
			?>	
            <div class='eight columns'>
              	<div class="row">
                	<div class='eight columns'>
                    	Correct Answer: &nbsp;
                        <div class="selectbox" style="width:15rem;">
                            <input type="hidden" name="selectcount" id="selectcount" value="<?php echo $count; ?>" onchange="fn_insertmathboxes(<?php echo $answertypeid;?>,<?php echo $questionid; ?>)">
                            <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                <span class="selectbox-option input-medium" style="width: 90%;" data-option="<?php echo $count; ?>" id="clearsubject"><?php if($count == 0){ echo "No.of Answers"; } else { echo $count; } ?></span>
                                <b class="caret1"></b>
                            </a>
                            <script>
                                var a = <?php echo $answertypeid;?>;
                                if(a!=0)
                                    fn_insertmathboxes(<?php echo $answertypeid;?>,<?php echo $questionid; ?>)
                            </script>
                            <div class="selectbox-options">
                                <ul role="options">
                                    <?php for($i=2; $i<=10; $i++) { ?>
                                        <li><a tabindex="-1" href="#" data-option="<?php echo $i; ?>"><?php echo $i; ?></a></li>
                                    <?php }	?>
                                </ul>
                            </div>
                        </div>
                        <div class="row rowspacer">
                           <div class="four columns">
                            	Text Prefix: &nbsp;<input type="text" id="pretext" class="mix-input" style="width:145px" placeholder="Text Prefix" onkeyup="ChkValidChar(this.id);" value="<?php echo $prefixans;?>" />        
                            </div>
                            <div class="four columns" style="margin-left:55px;">
                            	Text Suffix: &nbsp;<input type="text" id="posttext" class="mix-input" style="width:145px; " placeholder="Text sufix" onkeyup="ChkValidChar(this.id);" value="<?php echo $suffixans;?>" />      
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row rowspacer">
                	<div class='eight columns' id="mulboxes">
						<?php 
                        for($i=1;$i<=$count;$i++)
						{	$j=$i; $j--; ?>
							<input type="text" class="mix-input" id="mulbox<?php echo $i;?>" value="<?php echo $answerarray[$j]; ?>" onkeyup="ChkValidChar(this.id);" placeholder="Choices" />&nbsp; 
							<?php 	
						}
                        ?>
                    </div>
                </div>
			</div> 
		<?php 
		}
		
		/*--- Drag & Drop id=10 ---*/	
		if($answertypeid == 10 )
		{
			$boxcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_answer) FROM itc_question_answer_mapping WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."'AND fld_attr_id='2' AND fld_flag='1'");
 			$ansopt = $ObjDB->QueryObject("SELECT fld_answer AS answer FROM itc_question_answer_mapping WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."'AND fld_attr_id='10' AND fld_flag='1'");
			
			$qrysuf = $ObjDB->QueryObject("SELECT fld_answer AS answer FROM itc_question_answer_mapping WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' AND fld_attr_id='2' AND fld_flag='1'");
			$suffixarray=array();
			$l=0;
			while($row=$qrysuf->fetch_assoc())
			{
				extract($row);
				$suffixarray[$l]=$answer;
				$l++;
			}
			
			$k=0;
			while($opt=$ansopt->fetch_assoc())
			{
				extract($opt);
				$optionarray[$k]=$answer;				
				$k++;
			}
			?>
        	<div class="row rowspacer">
                <div class="twelve columns">
                    <input type="text" id="optionlist" onkeyup="ChkValidChar(this.id);" name="optionlist" class="mix-input" placeholder="Enter your option" style="width:245px;" value="" />
                    <input type="button" value="+" class="darkButton" onclick="fn_addoption()" />
                    <div class="row" >
                        <dl class="field row">
                            <dt class="textarea eight columns" style="margin-top:5px;">
                                <div class=" messagesBody" id="optiondiv" style="height:100px;overflow-y:scroll;word-wrap: break-word;">
                                <?php $j=0; for($i=0;$i<$k;$i++){ $j++;?>
                                	<div id="opdiv_<?php echo $j;?>" class="row"><span id="opt_<?php echo $j;?>"><?php echo $optionarray[$i]; ?></span><span style="float:right"><input type="button" class="darkButton" value="-" onclick="fn_removeopt(<?php echo $j;?>)" /></span></div>
                                <?php  }?> 
                                </div>
                            </dt>
                        </dl>
                    </div>
                </div>
            </div>
            
            <div id="optlists">
            	<?php
				if($k!=0)
				{
					$j=0; 
					for($i=0;$i<$k;$i++)
					{ 
						$j++;?>
                        <span id="option_<?php echo $j;?>" class="drag" style="margin:5px; cursor:pointer" title="Drag the Element"><?php echo $optionarray[$i]; ?></span>
                        <script>
                        $("#option_<?php echo $j;?>").draggable({
							containment: 'document',
							revert: true,
							start: function() {
								dragvalue = $(this).html();
							}
						});
						$('#hidoptions').val(<?php echo $j;?>);
						</script>
                    	<?php  
					}
				}
				?>
            </div>
            
			<div class='row rowspacer eight columns'>
              	<div class="row">
                	<div class='eight columns'>
                    	No.of Boxes: &nbsp;
                        <div class="selectbox" style="width:15rem;">
                            <input type="hidden" name="selectcount" id="selectcount" value="<?php echo $boxcount; ?>" onchange="fn_insertmathboxes(<?php echo $answertypeid;?>,<?php echo $questionid; ?>)">
                            <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                <span class="selectbox-option input-medium" style="width: 90%;" data-option="<?php echo $boxcount; ?>" id="clearsubject"><?php if($boxcount ==0){ echo "No.of Answers"; } else { echo $boxcount; } ?></span>
                                <b class="caret1"></b>
                            </a>
                            <script>
                                var a = <?php echo $answertypeid;?>;
                                if(a!=0)
                                    fn_insertmathboxes(<?php echo $answertypeid;?>,<?php echo $questionid; ?>)
                            </script>
                            <div class="selectbox-options">
                                <ul role="options">
                                    <?php for($i=2; $i<=10; $i++) { ?>
                                        <li><a tabindex="-1" href="#" data-option="<?php echo $i; ?>"><?php echo $i; ?></a></li>
                                    <?php }	?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row rowspacer">
                	<div id="mulboxes">
						<?php 
						$j=0;
                        for($i=0;$i<$boxcount;$i++){ 
                            $j++;?>
                            <input type="text" class="mix-input qit-medium" id="ans<?php echo $j;?>" style="width:30px;height:30px;" value="<?php echo $suffixarray[$i]; ?>" placeholder="Answer" readonly />&nbsp; 
                            <script>
							$("#ans<?php echo $j;?>").droppable({
								accept: '.drag',
								drop: function()
								{
									$('#ans<?php echo $j;?>').val(dragvalue);
								}
							});
							</script>
                        <?php 	
                        }
                        ?>
                    </div>
                </div>
			</div>
            <input type="hidden" id="hidoptions" name="hidoptions" value="0" />
            <script>
			 	function fn_addoption(){
					var opt = $('#optionlist').val();
					if(opt!='')
					{
						var inc = $('#hidoptions').val();
						inc++;
						$('#optiondiv').append('<div id="opdiv_'+inc+'" style="width:100%" class="row"><span id="opt_'+inc+'">'+ opt+'</span><span style="float:right" ><input type="button" class="darkButton" value="-" onclick="fn_removeopt('+inc+')" /></span></div>');
						$('#optlists').append('<span id="option_'+inc+'" class="drag" style="margin:5px; cursor:pointer" title="Drag the Element">'+ opt+'</span>');
						$("#option_"+inc).draggable({
							containment: 'document',
							revert: true,
							start: function() {
								dragvalue = $(this).html();
							}
						});
						$('#optionlist').val('');
						$('#hidoptions').val(inc);
					}
				}
				
				function fn_removeopt(id,type){		
					var removeval = $('#opt_'+id).html();						
					$('#opdiv_'+id).remove();
					$('#optlists').html('');
					$("span[id^=opt_]").each(function(){		
					   var optid = ($(this).attr('id').replace('opt_',''));	
					   var optval = $(this).html();						
					   $('#optlists').append('<span id="option_'+optid+'" class="drag" style="margin:5px; cursor:pointer" title="Drag the Element">'+ optval+'</span>');	
					   $("#option_"+optid).draggable({
							containment: 'document',
							revert: true,
							start: function() {
								dragvalue = $(this).html();
							}
						});
					});
					
					$("input[id^=ans]").each(function(){		
					   var optid = ($(this).attr('id').replace('ans',''));	
					   if($(this).val() == removeval)
					   		$(this).val('');
					});
				}
			</script>
            <?php
		}
		
		if($answertypeid == 11 )
		{
			$qry = $ObjDB->QueryObject("SELECT GROUP_CONCAT(IF(fld_attr_id = '1', fld_answer, NULL) SEPARATOR '~') AS 'answer', 
											GROUP_CONCAT(IF(fld_attr_id = '2', fld_answer, NULL) SEPARATOR '~') AS 'pullans',
											GROUP_CONCAT(IF(fld_attr_id = '2', fld_boxid, NULL) SEPARATOR '~') AS 'boxid',
											GROUP_CONCAT(IF(fld_attr_id = '10', fld_answer, NULL) SEPARATOR '~') AS 'ansopt' 
										FROM itc_question_answer_mapping 
										WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' AND fld_answer<>'' AND fld_flag='1'");
										
			$alphabet = array('A','B','C','D','E','F','G','H');
			$answerarray = array('','','','','','','','');
			$questionarray = array('','','','','','','','');
			$boxarray = array('','','','','','','','');
			$optionarray = array('','','','','','','','');
			if($qry->num_rows > 0) {
				while($row = $qry->fetch_assoc())
				{
					extract($row);
					$questionarray = array_values(array_filter(explode("~", $answer)));
					$answerarray = array_values(array_filter(explode("~",$pullans)));
					$boxarray = array_values(array_filter(explode("~",$boxid)));
					$optionarray = array_values(array_filter(explode("~",$ansopt)));
				}
				
				$k = sizeof($optionarray);
			  	
			}
						
			$hcount = 1;
			for($hc=1;$hc<=9;$hc++){
				if(isset($questionarray[$hc]) and ($questionarray[$hc]!=''))
					$hcount = $hc + 1;
			}
			
		
		 ?>
            <div class="row rowspacer">
                <div class="twelve columns">
                    <input type="text" id="optionlist" onkeyup="ChkValidChar(this.id);" name="optionlist" class="mix-input" placeholder="Enter your option here" style="width:245px;"  value="" />
                    <input type="button" value="+" class="darkButton" onclick="fn_addoption()" />
                    <div class="row" >
                        <dl class="field row">
                            <dt class="textarea eight columns" style="margin-top:5px;">
                            	<div class=" messagesBody" id="optiondiv" style="height:200px;overflow-y:scroll;word-wrap: break-word;">
								<?php for($o=0;$o<$k;$o++){?>
							 		<div id="opdiv_<?php echo $o+1;?>" class="row"><span id="opt_"><?php echo $optionarray[$o]; ?></span><span style="float:right"><input type="button" class="darkButton" value="-" onclick="fn_removeopt(<?php echo $o+1;?>)" /></span></div>
									<?php  }	?> 
                        		</div>
                            </dt>
                        </dl>
                    </div>
                </div>
            </div>    
			<div class="row rowspacer">
            	<?php
					for($i=0;$i<=9;$i++){
				?>
                       <div class="row"  id="TextQusBox<?php echo $i; ?>" style=" margin-top:2%; <?php if(!isset($questionarray[$i]) or $questionarray[$i]=='') { echo "display:none;"; }?>" >
                        <div class="eight columns">
                          <input type="text" onkeyup="ChkValidChar(this.id);" id="pullqus<?php echo $i; ?>" name="pullqus<?php echo $i; ?>" class="mix-input" placeholder="Enter your question here" style="width:545px;"  value="<?php if(isset($questionarray[$i])) { echo $questionarray[$i]; } ?>" />
                        </div>
                        <div class="three columns">
                          <div class="selectbox" >
                            <input type="hidden" name="pullans<?php echo $i; ?>" id="pullans<?php echo $i; ?>" value="<?php if(isset($boxarray[$i])) { echo $boxarray[$i]; } ?>" >
                            <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#"><span class="selectbox-option input-medium" style="width: 90%;" data-option="<?php if(isset($answerarray[$i])) { echo $answerarray[$i]; } ?>" id="clearsubject">
                            <?php if(!isset($answerarray[$i]) or $answerarray[$i] == ''){ echo "Select Answers"; } else { echo $answerarray[$i]; } ?>
                            </span> <b class="caret1"></b> </a> 
                            <div class="selectbox-options">
                                <ul role="options" id="option<?php echo $i; ?>"> 
                                <?php $m = 1; 
									for($o=0;$o<sizeof($optionarray);$o++){
										if(isset($optionarray[$o]) or $optionarray[$o] =='') {
								?>
                                     <li><a tabindex="-1" href="#" data-option="<?php echo $m; ?>"><?php echo $optionarray[$o]; ?></a></li>
                                 
                                <?php 
										}
										$m++; 
									}	
								?>                                    
                                </ul>
                            </div>
                          </div>
                        </div>
                      </div>
              	<?php
					}
				?>
              <!--end of 5more option-->
              <input type="hidden" value="" id="hidonfocus" name="hidonfocus" />
              <input type="hidden" value="<?php echo $hcount;?>" id="hidchoicename" name="hidchoicename" />
              <input type="hidden" value="<?php echo $optionvalues;?>" id="hidchoicelist" name="hidchoicename" />
              <input type="hidden" value="<?php echo $k; ?>" id="hidoptions" name="hidoptions" />
       		</div>
            <div style="text-align:left; margin-top:5px">
                <input type="button" class="darkButton" value="+" name="addmulqus" id="addmulqus" onclick="addanoqus($('#hidchoicename').val(),0);"/>
                <input type="button" class="darkButton" value="-" name="removemulqus" id="removemulqus" onclick="addanoqus($('#hidchoicename').val(),1);" <?php if(isset($questionarray[1]) and $questionarray[1] == '') {?>style="display:none"<?php }?>/>
            </div>
                
			<script language="javascript" type="text/javascript"> 
                function fn_addoption(){
                    var opt = $('#optionlist').val();
                    
                    if(opt != '')
                    {
                        var inc = $('#hidoptions').val();
                        inc++;
                        $('#optiondiv').append('<div id="opdiv_'+inc+'" style="width:100%" class="row"><span id="opt_'+inc+'">'+ opt+'</span><span style="float:right" ><input type="button" class="darkButton" value="-" onclick="fn_removeopt('+inc+')" /></span></div>');
                        $('#optionlist').val('');
                        $('#hidoptions').val(inc);
                        var j=1;
                        for(j=1;j<=10;j++)
                        {
                            var i=1;
                            $('#option'+j).html('');
                            $("span[id^=opt_]").each(function(){								
                               $('#option'+j).append('<li><a tabindex="-1" href="#" data-option="'+i+'">'+$(this).html()+'</a></li>');	
                               i++;
                            });				
                        }
						
                    }
                }
                
                function fn_removeopt(id,type){					
                        $('#opdiv_'+id).remove();
                        var j=1;
                        for (j=1;j<=10;j++)
                        {
                            var i=1;
                            $('#option'+j).html('');
                            $("span[id^=opt]").each(function(){								
                               $('#option'+j).append('<li><a tabindex="-1" href="#" data-option="'+i+'">'+$(this).html()+'</a></li>');	
                               i++;
                            });
                            var hidvalue = $('#pullans'+j).val();
                            if(hidvalue==id){								
                                $("div#TextQusBox"+j+" span:first-child").html('Select Answers');
                                $('#pullans'+j).val('');
                            }
                            else{
                                if(hidvalue>id){								                                  
                                    hidvalue--;
                                    $('#pullans'+j).val(hidvalue);
                                }
                            }
                        }
                }
            </script>
        	<?php 
		}
		if($answertypeid == 12)
		{
			$ansopt = $ObjDB->QueryObject("SELECT fld_ball_color, fld_inner_ball, fld_outer_ball, fld_correct, fld_ano_correct 
										FROM itc_question_drag_drop 
										WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' AND fld_flag='1'");
			$ballcolor=array();
			$innerball=array();
			$outerball=array();
			$correctball=array();
			$anocorrectball=array();
			$l=0;
			while($row=$ansopt->fetch_assoc())
			{
				extract($row);
				$ballcolor[$l]=$fld_ball_color;
				$innerball[$l]=$fld_inner_ball;
				$outerball[$l]=$fld_outer_ball;
				$correctball[$l]=$fld_correct;
				$anocorrectball[$l]=$fld_ano_correct;
				$l++;
			}
			
			$m=1;
			$ball ='';
			$inner ='';
			$outer ='';
			$hcount = 1;
			for($k=0;$k<sizeof($ballcolor);$k++)
			{
				$hcount = $m;
				if($ball=='')
					$ball = $ballcolor[$k];
				else
					$ball = $ball."~".$ballcolor[$k];
				
				if($inner=='')
					$inner = $innerball[$k];
				else
					$inner = $inner."~".$innerball[$k];
				
				if($outer=='')
					$outer = $outerball[$k];
				else
					$outer = $outer."~".$outerball[$k];
				$m++;
			}
			
			?>
	
			<script language="javascript" type="text/javascript">
				(function(d){d.fn.shuffle=function(c){c=[];return this.each(function(){c.push(d(this).clone(true))}).each(function(a,b){d(b).replaceWith(c[a=Math.floor(Math.random()*c.length)]);c.splice(a,1)})};d.shuffle=function(a){return d(a).shuffle()}})(jQuery);
				$(function() {
					
					$(".ballcontainer").sortable({
						connectWith: ".ballsplitted",
						revert: true,
						over: function( event, ui ) {
						},
						receive: function(event, ui) {
							var clsitem= ui.item.attr('class');
							var itemstyle=ui.item.attr('style');
							var backgroun=ui.item.css('backgroundColor');
							$('.empty').remove();
							ui.item.removeAttr('style');
							ui.item.remove();
							$('div.ballcontainer :first-child').before('<div id="<?php echo $uniqu= uniqid(); ?>" style="'+itemstyle+'" class="'+clsitem+'"></div>');
							rearrangeballs();
							$('#<?php echo $uniqu; ?>').animate({ backgroundColor: "yellow" }, '100');
							$('#<?php echo $uniqu; ?>').animate({ backgroundColor: backgroun }, '200');
						}
					});
					$(".ballsplitted").sortable({connectWith: ".ballcontainer",revert: true,
						receive: function(event, ui) {
							var clsitem= ui.item.removeAttr('id');
							rearrangeballs();
						}
					});	
				});
				
				$('#colorSelector1,#colorSelector2,#colorSelector3,#colorSelector4,#colorSelector5').ColorPicker({
					onSubmit: function(hsb, hex, rgb, el) {
						$(el).val(hex);
						$(el).ColorPickerHide();
						var inboxid=$(el).attr('id').replace('colorSelector','');
						if($('#insidered'+inboxid).val()=='')
						{
							$('#insidered'+inboxid).focus()
						}
						else if($('#outsidered'+inboxid).val()=='')
						{
							$('#outsidered'+inboxid).focus()
						}
					}
				});
				$('.colorpicker').css('z-index','10000');
				
				
				$("#insidered1,#outsidered1,#insidered2,#outsidered2,#insidered3,#outsidered3,#insidered4,#outsidered4,#insidered5,#outsidered5").keypress(function (e) {
					if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
						return false;
					}
				});
			</script>
			<table class='table table-hover table-striped table-bordered' style="width: 66%;" >
				<thead class='tableHeadText'>
					<tr style="text-align:center">
						<th>ball color</th>
						<th>inside container balls count</th>
						<th>outside balls count</th>
					</tr>
				</thead>
				<tbody>
                	<?php
						for($i=1;$i<=5;$i++){
					?>
					<tr id="trow<?php echo $i; ?>" style="text-align:center;<?php if($hcount<$i) {?>display:none<?php }?>" >
						<td><input class="mix-input qit-medium" maxlength="10" type="text" id="colorSelector<?php echo $i; ?>" style="width:75px;height:20px;" name="outsidered" readonly value="<?php if(isset($ballcolor[$i-1])) { echo $ballcolor[$i-1]; }?>"/></td>
						<td><input class="mix-input qit-medium" maxlength="2" type="text" id="insidered<?php echo $i; ?>" style="width:75px;height:20px;" name="outsidered1" value="<?php if(isset($innerball[$i-1])) { echo $innerball[$i-1]; } ?>"/></td>
						<td><input class="mix-input qit-medium" maxlength="2" type="text" id="outsidered<?php echo $i; ?>" style="width:75px;height:20px;" name="outsidered1" value="<?php if(isset($outerball[$i-1])) { echo $outerball[$i-1]; } ?>"/></td>
					</tr>
					<?php
						}
					?>
				</tbody>
			</table>
			<input type="button" value="+" id="add-btn" class="btn" style="" onClick="fn_ballsrowview(1);" />  <span>add balls</span>
			<input type="button" value="-" id="remove-btn" class="btn <?php if($hcount<2) {?>dim<?php }?>" style="" onClick="fn_ballsrowview(2)" /> <span>remove balls</span>
			<input type="button" value="Preview" class="btn" style="float: right;height: 40px;margin-top: -59px;width: 167px;" onClick="fn_previewballs()" />
			<input type="hidden" id="hidtextrowcnt" name="hidtextrowcnt" value="<?php echo $hcount;?>" />
			<input type="hidden" id="hidballcolor" name="hidballcolor" value="<?php echo $ball;?>" />
			<input type="hidden" id="hidinsideball" name="hidinsideball" value="<?php echo $inner;?>" />
			<input type="hidden" id="hidoutsideball" name="hidoutsideball" value="<?php echo $outer;?>" />
            
			<script language="javascript" type="text/javascript">
				$('#insidered').each(function(){
					$(this).ForceNumericOnly();
				});
				$('#outsidered').each(function(){
					$(this).ForceNumericOnly();
				});
				
				var a = <?php echo $l; ?>;
				if(a!=0){
					fn_previewballs();
					$('.colorpicker').each(function(){
						$(this).css('z-index','1000000');
					});
					<?php 
						for($i=1;$i<=sizeof($ballcolor);$i++)
						{    
							$k=$i; $k--;
							if($outerball[$k]!='0')
							{
						?>
								$('#correct<?php echo $i ?>').val(<?php echo $correctball[$k] ?>);
								$('#anocorrect<?php echo $i ?>').val(<?php echo $anocorrectball[$k] ?>);
						<?php  
							}
						}
					?>
				}
			</script>
			<div id="wrapper" style="display:none; height:280px;">
				<div class="ballcontainer"></div>
				<div class="ballsplitted"></div>
			</div>
			<br />
			<table id="correctans" class='table table-hover table-striped table-bordered' style="display:none; width: 66%;" >
				<thead class='tableHeadText'>
					<tr style="text-align:center">
						<th>colors</th>
						<th>answer count</th>
						<th>alternative answer count</th>
					</tr>
				</thead>
				<tbody>
					<tr id="tanswerrow1" style="text-align:center;display:none"></tr>
					<tr id="tanswerrow2" style="text-align:center;display:none"></tr>
					<tr id="tanswerrow3" style="text-align:center;display:none"></tr>
					<tr id="tanswerrow4" style="text-align:center;display:none"></tr>
					<tr id="tanswerrow5" style="text-align:center;display:none"></tr>
				</tbody>
			</table>
			<?php 
		}
		
		if($answertypeid == 13 )
		{	
			?>
			<script language="javascript" type="text/javascript">
				function fn_points(answertypeid,questionid)
				{
					var id = $('#selectcount').val();
					for(i=1;i<=5;i++)
					{
						if(i<=id)
							$('#balldraggable'+i).show();
						else
							$('#balldraggable'+i).hide();
					}
				}
			</script>
			<?php
			
			$qry = $ObjDB->QueryObject("SELECT GROUP_CONCAT(IF(fld_attr_id = '2', fld_answer, NULL) SEPARATOR '~') AS `answer`, 
											GROUP_CONCAT(IF(fld_attr_id = '1', fld_answer, NULL)) AS `ansimage`
										FROM itc_question_answer_mapping 
										WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' AND fld_answer<>'' AND fld_flag='1'");
			$imagepos=array();	
			$imageballposition = array();						
			$l = 0;
			if($qry->num_rows > 0) {
				while($row = $qry->fetch_assoc())
				{
					extract($row);
					if($answer != '') {
						$imagepos = explode("~",$answer);
					}
				}
				
				$l = sizeof($imagepos);
			}
			
			if(isset($imagepos) and $l!=0) {
			
				for($k=0;$k<$l;$k++)
				{
					$imageballposition[$k] = explode(',',$imagepos[$k]);
				}
				$ansimage1 = ($ansimage != '') ? "thumb.php?src=".__CNTANSIMGPATH__.$ansimage."&w=800&h=200&zc=3" : '';
			}
			else {
				$i = 5;
				for($k=0;$k<5;$k++)
				{
					$imageballposition[$k] = array(0,$i);
					$i = $i + 5;
				}
			}
						
			?>
			<div class='twelve columns rowspacer'>
				<div class='row'>
					<input id="imgphoto" name="imgphoto" type="file" />
                    <div id="queueimg" style="display:none;"></div>
					
					<div class='seven columns'>
						No.of Points: &nbsp;
						<div class="selectbox" style="width:15rem;">
							<input type="hidden" name="selectcount" id="selectcount" value="<?php echo $l; ?>" onchange="fn_points(<?php echo $answertypeid;?>,<?php echo $questionid; ?>)">
							<a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
								<span class="selectbox-option input-medium" style="width: 90%;" data-option="<?php echo $l; ?>" id="clearsubject"><?php if($l ==0){ echo "No.of Points"; } else { echo $l; } ?></span>
								<b class="caret1"></b>
							</a>
							<script>
								var a = <?php echo $answertypeid;?>;
								if(a!=0)
									fn_points(<?php echo $answertypeid;?>,<?php echo $questionid; ?>)
							</script>
							<div class="selectbox-options">
								<ul role="options">
									<?php for($i=1; $i<=5; $i++) { ?>
										<li><a tabindex="-1" href="#" data-option="<?php echo $i; ?>"><?php echo $i; ?></a></li>
									<?php }	?>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="rowspacer">&nbsp;</div>
			<div class="twelve columns rowspacer" >
                            <div class="twelve columns" id="droppable" style="width:100%">
					<div id="balldraggable1" class="rowspacer drag1" title="Drag this Point" <?php if(isset($imagepos[0])) ?>style="display:none"></div>
					<div id="balldraggable2" class="rowspacer drag1" title="Drag this Point" <?php if(isset($imagepos[1])) ?>style="display:none"></div>
					<div id="balldraggable3" class="rowspacer drag1" title="Drag this Point" <?php if(isset($imagepos[2])) ?>style="display:none"></div>
					<div id="balldraggable4" class="rowspacer drag1" title="Drag this Point" <?php if(isset($imagepos[3])) ?>style="display:none"></div>
					<div id="balldraggable5" class="rowspacer drag1" title="Drag this Point" <?php if(isset($imagepos[4])) ?>style="display:none"></div>
					
                    <div id="showimg" <?php if($ansimage1=='') {?>style="display:none"<?php }?>>
                    	<img name="txtimage" id="txtimage" src="<?php echo $ansimage1; ?>"/>  
                    </div>
				</div>
			</div>
		   
			<script language="javascript" type="text/javascript">
				$('#imgphoto').uploadify({
					'formData'     : {
						'timestamp' : '<?php echo $timestamp; ?>',
						'token'     : '<?php echo md5('nanonino' . $timestamp);?>',
						'oper' : 'question/ansimg'
					},
					'swf'      : 'uploadify/uploadify.swf',
					'uploader' : '<?php echo _CONTENTURL_; ?>uploadify.php',
					'buttonClass' : 'btn',
					'buttonText' : 'Select a file',
					'fileTypeExts' : '*.jpg;*.png',
					'queueID'  : 'queueimg',
					'queueSizeLimit' : 1,
					'width' : 300,
					'onUploadSuccess' : function(file, data, response) {
						$("#showimg").show();
						$("#txtimage").attr('src','thumb.php?src=<?php echo __CNTANSIMGPATH__; ?>'+data+'&w=800&h=200&zc=3'); 
						$("#hideimagename").val(data);
					}
				});
				
				/******This is for dragable script******/
				$('#balldraggable1,#balldraggable2,#balldraggable3,#balldraggable4,#balldraggable5').draggable({
					containment: '#txtimage',
					create: function( event, ui ) {
						<?php if(isset($imageballposition[0])) { ?> $('#balldraggable1').css({'top':'<?php echo $imageballposition[0][0];?>','left':'<?php echo $imageballposition[0][1];?>' }); <?php } ?>
						<?php if(isset($imageballposition[1])) { ?> $('#balldraggable2').css({'top':'<?php echo $imageballposition[1][0];?>','left':'<?php echo $imageballposition[1][1];?>' }); <?php } ?>
						<?php if(isset($imageballposition[2])) { ?> $('#balldraggable3').css({'top':'<?php echo $imageballposition[2][0];?>','left':'<?php echo $imageballposition[2][1];?>' }); <?php } ?>
						<?php if(isset($imageballposition[3])) { ?> $('#balldraggable4').css({'top':'<?php echo $imageballposition[3][0];?>','left':'<?php echo $imageballposition[3][1];?>' }); <?php } ?>
						<?php if(isset($imageballposition[4])) { ?> $('#balldraggable5').css({'top':'<?php echo $imageballposition[4][0];?>','left':'<?php echo $imageballposition[4][1];?>' }); <?php } ?>
					}
				});
				$('#droppable').droppable({
					accept: '.drag1',
					start: function(event, ui) { $(this).removeAttr("style"); },
					drop: function(event, ui) {
						ballpost=('' + ui.draggable.css('top') + ',' + ui.draggable.css('left'));
						var id = ui.draggable.attr('id').replace('balldraggable','');
						$('#hideimagedragpos'+id).val(ballpost);						
					}
				});
			</script>
			  
			<input type="hidden" id="hideimagename" name="hideimagename" value="<?php echo $ansimage; ?>" />
			<input type="hidden" id="hideimagedragpos1" name="hideimagedragpos1" value="<?php if(isset($imagepos[0])) echo $imagepos[0]; ?>" />
			<input type="hidden" id="hideimagedragpos2" name="hideimagedragpos2" value="<?php if(isset($imagepos[1])) echo $imagepos[1]; ?>" />
			<input type="hidden" id="hideimagedragpos3" name="hideimagedragpos3" value="<?php if(isset($imagepos[2])) echo $imagepos[2]; ?>" />
			<input type="hidden" id="hideimagedragpos4" name="hideimagedragpos4" value="<?php if(isset($imagepos[3])) echo $imagepos[3]; ?>" />
			<input type="hidden" id="hideimagedragpos5" name="hideimagedragpos5" value="<?php if(isset($imagepos[4])) echo $imagepos[4]; ?>" />
			<?php 
		}
		
		if($answertypeid == 14)
		{
			$qry = $ObjDB->QueryObject("SELECT GROUP_CONCAT(IF(fld_attr_id = '1', fld_answer, NULL)) AS `ansimage`, 
											GROUP_CONCAT(IF(fld_attr_id = '2', fld_answer, NULL)) AS `imagepos`
										FROM itc_question_answer_mapping 
										WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' AND fld_flag='1'");
			$imagepos='';							
			if($qry->num_rows > 0) {
				while($row = $qry->fetch_assoc())
				{
					extract($row);					
				}
			}
			?>
			<div class="row">
             	<div class="six columns" >
            		<input id="imgphoto" name="imgphoto" type="file" />
                    <div id="queueimg" style="display:none;"></div>
           		</div>
          	</div>
			<script type="text/javascript" language="javascript">
				$('#imgphoto').uploadify({
					'formData'     : {
						'timestamp' : '<?php echo $timestamp; ?>',
						'token'     : '<?php echo md5('nanonino' . $timestamp);?>',
						'oper' : 'question/ansimg'
					},
					'swf'      : 'uploadify/uploadify.swf',
					'uploader' : '<?php echo _CONTENTURL_; ?>uploadify.php',
					'buttonClass' : 'btn',
					'buttonText' : 'Select a file',
					'fileTypeExts' : '*.jpg;*.png',
					'queueID'  : 'queueimg',
					'queueSizeLimit' : 1,
					'width' : 300,
					'onUploadSuccess' : function(file, data, response) {					
						$("#hideimagename").val(data); 
						$('#iframegraphline').attr('src','<?php echo $domainame; ?>library/questions/line.php?img='+data+'&val=0');
						$("#drawing").css('background','url("<?php echo $domainame; ?>thumb.php?src=<?php echo __CNTANSIMGPATH__; ?>'+data+'&w=700&h=700&zc=3") no-repeat scroll 0 0 transparent');
					}
				});
			</script>
            <div class="row rowspacer">
				<div class="six columns" id="droppable" style="height:800px;">
					<iframe id="iframegraphline" src="" height="800" width="800"></iframe>
					<script>
						<?php if($imagepos!='') {?>
							$('#iframegraphline').attr('src','<?php echo $domainame; ?>library/questions/line.php?img=<?php echo $ansimage;?>&val=<?php echo $imagepos;?>');
							$("#hideimagename").val('<?php echo $ansimage;?>'); 
						<?php }?>
					</script>
				</div>
				<div id="debug" ></div>
			</div>
			<input type="hidden" id="hideimagename" name="hideimagename" value="<?php echo $ansimage; ?>" />
			<?php 
		}
	}// End of answerchoices
		
	/*--- Load Text Boxes ---*/
	if($oper == "insertmathboxes" and $oper!= "")
	{
		$count=isset($_POST['count']) ? $_POST['count'] : 0;
		$answertypeid=isset($_POST['answertypeid']) ? $_POST['answertypeid'] : 0;
		$questionid = isset($_POST['qid']) ? $_POST['qid'] : 0;
		
		$suffix = '';
		$prefix = '';
		$prefixarr = array('','','','','','','','','','');
		$suffixarr = array('','','','','','','','','','');
		
		if($answertypeid == 3)
		{
			$qry = $ObjDB->QueryObject("SELECT GROUP_CONCAT(IF(fld_attr_id = '3', fld_answer, NULL) SEPARATOR '~') AS 'prefix', 
										GROUP_CONCAT(IF(fld_attr_id = '4', fld_answer, NULL) SEPARATOR '~') AS 'suffix' 
									FROM itc_question_answer_mapping 
									WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' AND fld_answer<>'' AND fld_flag='1'");
		}
		else if($answertypeid == 10)
		{
			$qry = $ObjDB->QueryObject("SELECT GROUP_CONCAT(IF(fld_attr_id = '2', fld_answer, NULL) SEPARATOR '~') AS 'suffix' FROM itc_question_answer_mapping WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' AND fld_answer<>'' AND fld_flag='1'");
		}
		else {
			$qry = $ObjDB->QueryObject("SELECT GROUP_CONCAT(IF(fld_attr_id = '1', fld_answer, NULL) SEPARATOR '~') AS 'prefix' 
										FROM itc_question_answer_mapping 
										WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' AND fld_answer<>'' AND fld_flag='1'");
		}
		
		if($qry->num_rows > 0) {
		
			while($row = $qry->fetch_assoc())
			{
				extract($row);
				$prefixarr = explode('~', $prefix);
				$suffixarr = explode('~', $suffix);
			}
		}
		
		for($i=0;$i<$count;$i++)
		{ 
			if($answertypeid == 3)
			{
			?>
				<input type="text" class="mix-input" id="mulbox<?php echo $i+1;?>" value="<?php echo (isset($prefixarr[$i]))? $prefixarr[$i] : ''; ?>" placeholder="Choices" onkeyup="ChkValidChar(this.id);"/>&nbsp;
				<input type="text" class="mix-input" id="ans<?php echo $i+1;?>" style="width:55px;height:15px;" value="<?php echo (isset($suffixarr[$i]))? $suffixarr[$i] : ''; ?>" placeholder="Answer" onkeyup="ChkValidChar(this.id);"/><br /><br />
			<?php 
			}
			else if($answertypeid == 10)
			{ 
			?>
				<input type="text" class="mix-input qit-medium" id="ans<?php echo $i+1;?>" style="width:30px;height:30px;" value="<?php echo $suffixarr[$i]; ?>" placeholder="Answer" readonly />&nbsp; 
                <script language="javascript" type="text/javascript">
					$("#ans<?php echo $i+1;?>").droppable({
						accept: '.drag',
						drop: function()
						{							
							$('#ans<?php echo $i+1;?>').val(dragvalue);
						}
					});
				</script>
			<?php    
			}
			else 
			{ 
			?>
				<input type="text" class="mix-input" id="mulbox<?php echo $i+1;?>" value="<?php echo (isset($prefixarr[$i]))? $prefixarr[$i] : ''; ?>" style="margin-top:10px;" placeholder="Choices" onkeyup="ChkValidChar(this.id);"/>&nbsp;
			<?php    
			}
		}
		?>
		<script language="javascript" type="text/javascript">
            $('input').autoGrowInput({
                comfortZone: 50,
                maxWidth: 200
            });
			
			$("input[type^=text]").keypress(function (e) {
				if (e.which == 34) {
					return false;
				}
			});
        </script>    
        <?php
	}
		
	/*--- Load answer boxes according to the user preview---*/
	if($oper == "preview" and $oper != '')
	{		
		$anspattern = isset($_POST['anspattern']) ? $_POST['anspattern'] : '';
		$questionid = isset($_POST['quesid']) ? $_POST['quesid'] : '0';
		$answerdespat = explode(',',$anspattern);
		$answer = $ObjDB->SelectSingleValue("SELECT fld_answer 
											FROM itc_question_answer_mapping 
											WHERE fld_quesid='".$questionid."' AND fld_attr_id='7' AND fld_flag='1'");
		for($i=0;$i<sizeof($answerdespat);$i++)
		{
			$design=$ObjDB->QueryObject("SELECT fld_html_code as htmldesign 
										FROM itc_question_answer_pattern_master 
										WHERE fld_id='".$answerdespat[$i]."'");
			while($ansdesptn=$design->fetch_object())
			{
				echo $ansdesptn->htmldesign;
			}
		}?>
        <input type="hidden" id="hidanswer" value="<?php echo $answer; ?>" />
        
        <script language="javascript" type="text/javascript">
			$('input').autoGrowInput({
				comfortZone: 10,
				maxWidth: 200
			});
		</script>
		<?php
	}
	
	/*--- Save the Step1 ---*/
	if($oper == "savestep1" and $oper != '')
	{
		$editid = isset($_POST['editid']) ? $_POST['editid'] : '0';
		$testid = isset($_POST['testid']) ? $_POST['testid'] : '';
		$unitid = isset($_POST['unitid']) ? $_POST['unitid'] : '';
		$lessonid = isset($_POST['lessonid']) ? $_POST['lessonid'] : '';
		$tags = isset($_POST['tags']) ? $_POST['tags'] : '';	
		
		$testvalidopt = array('options' => array('min_range' => 0, 'max_range' => 4));
		$options = array();
		
		$testvalid = fn_validate($testid, FILTER_VALIDATE_INT, $testvalidopt);
		$editidvalid = fn_validate($editid, FILTER_VALIDATE_INT, $options);
		$unitvalid = fn_validate($unitid, FILTER_VALIDATE_INT, $options);
		$lessonvalid = fn_validate($lessonid, FILTER_VALIDATE_INT, $options);
		$date= date("Y-m-d H:i:s");
		
		if($testvalid and $editidvalid) { // validating test type id and question id 
			if($testid<=3){
				if(!($unitvalid and $lessonvalid)){
					echo "invalid~0";
					die;
				}
			}			
		}
		else {
			echo "invalid~0";
			die;	
		}
		
		if($editid != 0)
		{				 
			if($testid<=3){
					$ObjDB->NonQuery("UPDATE itc_question_details 
									SET fld_unit_id='".$unitid."', fld_lesson_id='".$lessonid."', 
										fld_question_type_id='".$testid."', fld_updated_by='".$uid."', fld_updated_date='".$date."' 
									WHERE fld_id='".$editid."' AND fld_delstatus='0'");
			}
		}
		else
		{
			if($testid <= 3){

				$editid = $ObjDB->NonQueryWithMaxValue("INSERT INTO itc_question_details (fld_unit_id, fld_lesson_id, fld_question_type_id, fld_created_by, fld_created_date) 
													 VALUES ('".$unitid."', '".$lessonid."', '".$testid."', '".$uid."', '".$date."')");
				
			}
		}
	
		echo "success~".$editid;
	}
	
	/*--- Save the Step2 ---*/
	if($oper == "savequestionbank" and $oper!= "")
	{
		$questionid = isset($_POST['questionid']) ? addslashes($_POST['questionid']) : '';
		$question = isset($_POST['question']) ? addslashes($_POST['question']) : '';
		$answertype = isset($_POST['answertype']) ? $_POST['answertype'] : '';
		$answer = isset($_POST['answer']) ? $ObjDB->EscapeStr($_POST['answer']) : '';
		$prefix = isset($_POST['prefix']) ? $ObjDB->EscapeStr($_POST['prefix']) : '';
		$suffix = isset($_POST['suffix']) ? $ObjDB->EscapeStr($_POST['suffix']) : '';
		$correct = isset($_POST['correct']) ? $_POST['correct'] : '';
		$pattern = isset($_POST['pattern']) ? $_POST['pattern'] : '';
		$patternanswer = isset($_POST['patternanswer']) ? $_POST['patternanswer'] : '';
		$remfile = isset($_POST['remfile']) ? addslashes($_POST['remfile']) : '';
		$tags = isset($_POST['tags']) ? $ObjDB->EscapeStr($_POST['tags']) : '';	
		$ordertype = isset($_POST['ordertype']) ? $_POST['ordertype'] : '';
		
		$listoptions = isset($_POST['listoptions']) ? $_POST['listoptions'] : '';
		$ballcolor = isset($_POST['ballcolor']) ? $_POST['ballcolor'] : '';
		$insideball = isset($_POST['insideball']) ? $_POST['insideball'] : '';
		$outsideball = isset($_POST['outsideball']) ? $_POST['outsideball'] : '';
		$dragcorrect = isset($_POST['dragcorrect']) ? $_POST['dragcorrect'] : '';
		$draganocorrect = isset($_POST['draganocorrect']) ? $_POST['draganocorrect'] : '';
		$anscolor = isset($_POST['anscolor']) ? $_POST['anscolor'] : '';
		$correctpulldown = isset($_POST['correctpulldown']) ? $_POST['correctpulldown'] : '';
		$ObjDB->NonQuery("UPDATE itc_question_details 
						SET fld_question='".$question."', fld_answer_type='".$answertype."', fld_file_name='".$remfile."', fld_step_id='2', 
							fld_updated_by='".$uid."', fld_updated_date='".$date."' 
						WHERE fld_id='".$questionid."' AND fld_delstatus='0'");
		
		/***************Tags Codings******Updateing**************************/
		$ObjDB->NonQuery("UPDATE itc_main_tag_mapping 
						SET fld_access='0', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."'  
						WHERE fld_tag_type='19' AND fld_item_id='".$questionid."' AND fld_tag_id 
						IN(SELECT fld_id FROM itc_main_tag_master WHERE fld_created_by='".$uid."' AND fld_delstatus='0')");			
		
		if($tags!='')
		{
			fn_tagupdate($tags,19,$questionid,$uid);
		}
		/*********************************/
		
		$ObjDB->NonQuery("UPDATE itc_question_answer_mapping 
						SET fld_flag='0', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."'  
						WHERE fld_quesid='".$questionid."' ");
		
		if($answer!='')
		{
			$answer = explode('~',$answer);
			for($i=0;$i<sizeof($answer);$i++)
			{
				$boxid=$i;
				$boxid++;
				$count = $ObjDB->SelectSingleValueInt("SELECT fld_id 
													FROM itc_question_answer_mapping 
													WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertype."' AND fld_attr_id='1' AND fld_flag='0'");
				if($count != 0)
					$ObjDB->NonQuery("UPDATE itc_question_answer_mapping 
									SET fld_flag='1', fld_answer='".$answer[$i]."', fld_boxid='".$boxid."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."'  
									WHERE fld_id='".$count."' ");
				else
					$ObjDB->NonQuery("INSERT INTO itc_question_answer_mapping (fld_quesid, fld_ans_type, fld_attr_id, fld_answer, fld_boxid, fld_created_by, fld_created_date) 
									VALUES ('".$questionid."', '".$answertype."', '1', '".$answer[$i]."', '".$boxid."', '".$uid."', '".date("Y-m-d H:i:s")."')");
			}
		}
		
		if($prefix!='')
		{
			$prefix = explode('~',$prefix);
			for($i=0;$i<sizeof($prefix);$i++)
			{
				$boxid=$i;
				$boxid++;
				$count = $ObjDB->SelectSingleValueInt("SELECT fld_id 
													FROM itc_question_answer_mapping 
													WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertype."' AND fld_attr_id='3' AND fld_flag='0'");
				if($count != 0)
					$ObjDB->NonQuery("UPDATE itc_question_answer_mapping 
									SET fld_flag='1', fld_answer='".$prefix[$i]."', fld_boxid='".$boxid."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' 
									WHERE fld_id='".$count."' ");
				else
					$ObjDB->NonQuery("INSERT INTO itc_question_answer_mapping (fld_quesid, fld_ans_type, fld_attr_id, fld_answer, fld_boxid, fld_created_by, fld_created_date) 
									VALUES ('".$questionid."', '".$answertype."', '3', '".$prefix[$i]."', '".$boxid."', '".$uid."', '".date("Y-m-d H:i:s")."')");
			}
		}
		
		if($suffix!='')
		{
			$suffix = explode('~',$suffix);
			for($i=0;$i<sizeof($suffix);$i++)
			{
				$boxid=$i;
				$boxid++;
				$count = $ObjDB->SelectSingleValueInt("SELECT fld_id 
													FROM itc_question_answer_mapping 
													WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertype."' AND fld_attr_id='4' AND fld_flag='0'");
				if($count != 0)
					$ObjDB->NonQuery("UPDATE itc_question_answer_mapping 
									SET fld_flag='1', fld_answer='".$suffix[$i]."', fld_boxid='".$boxid."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' 
									WHERE fld_id='".$count."' ");
				else
					$ObjDB->NonQuery("INSERT INTO itc_question_answer_mapping (fld_quesid, fld_ans_type, fld_attr_id, fld_answer, fld_boxid, fld_created_by, fld_created_date) 
									VALUES ('".$questionid."', '".$answertype."', '4', '".$suffix[$i]."', '".$boxid."', '".$uid."', '".date("Y-m-d H:i:s")."')");
			}
		}
		
		if($pattern!='')
		{
			$pattern = explode('~',$pattern);
			for($i=0;$i<sizeof($pattern);$i++)
			{
				$count = $ObjDB->SelectSingleValueInt("SELECT fld_id 
													FROM itc_question_answer_mapping
													 WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertype."' AND fld_attr_id='6' AND fld_flag='0'");
				if($count != 0)
					$ObjDB->NonQuery("UPDATE itc_question_answer_mapping 
									SET fld_flag='1', fld_answer='".$pattern[$i]."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' 
									WHERE fld_id='".$count."' ");
				else
					$ObjDB->NonQuery("INSERT INTO itc_question_answer_mapping (fld_quesid, fld_ans_type, fld_attr_id, fld_answer, fld_created_by, fld_created_date) 
									VALUES ('".$questionid."', '".$answertype."', '6', '".$pattern[$i]."', '".$uid."', '".date("Y-m-d H:i:s")."')");
			}
		}
		
		if($patternanswer != '')
		{
			$patternanswer = explode('~',$patternanswer);
			for($i=0;$i<sizeof($patternanswer);$i++)
			{
				$count = $ObjDB->SelectSingleValueInt("SELECT fld_id 
													FROM itc_question_answer_mapping 
													WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertype."' AND fld_attr_id='7' AND fld_flag='0'");
				if($count != 0){
                                        $patternanswer[$i] = $ObjDB->EscapeStrAll($patternanswer[$i]);
					$ObjDB->NonQuery("UPDATE itc_question_answer_mapping 
									SET fld_flag='1', fld_answer='".$patternanswer[$i]."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' 
									WHERE fld_id='".$count."' ");
                                }
				else {
                                        $patternanswer[$i] = $ObjDB->EscapeStrAll($patternanswer[$i]);
					$ObjDB->NonQuery("INSERT INTO itc_question_answer_mapping (fld_quesid, fld_ans_type, fld_attr_id, fld_answer, fld_created_by, fld_created_date) 
									VALUES ('".$questionid."', '".$answertype."', '7', '".$patternanswer[$i]."', '".$uid."', '".date("Y-m-d H:i:s")."')");
                                }
			}
		}	
		if($correct!='')
		{
			$correct = explode('~',$correct);
			for($i=0;$i<sizeof($correct);$i++)
			{
				$count = $ObjDB->SelectSingleValueInt("SELECT fld_id 
														FROM itc_question_answer_mapping 
														WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertype."' AND fld_attr_id='2' AND fld_flag='0'");
				if($count != 0)
					$ObjDB->NonQuery("UPDATE itc_question_answer_mapping 
									SET fld_flag='1', fld_answer='".$correct[$i]."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' 
									WHERE fld_id='".$count."' ");
				else
					$ObjDB->NonQuery("INSERT INTO itc_question_answer_mapping (fld_quesid, fld_ans_type, fld_attr_id, fld_answer, fld_created_by, fld_created_date) 
									VALUES ('".$questionid."', '".$answertype."', '2', '".$correct[$i]."', '".$uid."', '".date("Y-m-d H:i:s")."')");
			}
		}
		
		if($ordertype == 2)
		{
			$count = $ObjDB->SelectSingleValueInt("SELECT fld_id 
												FROM itc_question_answer_mapping 
												WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertype."' AND fld_attr_id='9' AND fld_flag='0'");
			if($count!=0)
					$ObjDB->NonQuery("UPDATE itc_question_answer_mapping 
									SET fld_flag='1', fld_attr_id='9', fld_answer='1', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' 
									WHERE fld_id='".$count."' ");
					
				else
					$ObjDB->NonQuery("INSERT INTO itc_question_answer_mapping (fld_quesid, fld_ans_type, fld_attr_id, fld_answer, fld_created_by, fld_created_date) 
									VALUES ('".$questionid."', '".$answertype."', '9', '1', '".$uid."', '".date("Y-m-d H:i:s")."')");
		}
		else
		{
			$ObjDB->NonQuery("UPDATE itc_question_answer_mapping 
							SET fld_flag='0', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' 
							WHERE fld_quesid='".$questionid."' and fld_attr_id='9' and  fld_answer='1'");
		}
		
		
		if($answertype==10)
		{
			$listoptions = explode('~',$listoptions);
			if($correct!='')
			{
				$correct = explode('~',$correct);
				$j=0;
				for($i=0;$i<sizeof($correct);$i++)
				{
					$j++;
					$count = $ObjDB->SelectSingleValueInt("SELECT fld_id 
														FROM itc_question_answer_mapping 
														WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertype."' AND fld_attr_id='2' AND fld_flag='0'");
					if($count!=0)
						$ObjDB->NonQuery("UPDATE itc_question_answer_mapping 
										SET fld_flag='1', fld_answer='".$correct[$i]."', fld_boxid='".$j."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."'  
										WHERE fld_id='".$count."' ");
					else
						$ObjDB->NonQuery("INSERT INTO itc_question_answer_mapping (fld_quesid, fld_ans_type, fld_attr_id, fld_answer,fld_boxid, fld_created_by, fld_created_date) 
										VALUES ('".$questionid."', '".$answertype."', '2', '".$correct[$i]."','".$j."', '".$uid."', '".date("Y-m-d H:i:s")."')");
				}
			}
			if($listoptions!='')
			{
				$j=0;
				for($i=0;$i<sizeof($listoptions);$i++)
				{	
					$j++;
					$count = $ObjDB->SelectSingleValueInt("SELECT fld_id 
														FROM itc_question_answer_mapping 
														WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertype."' AND fld_attr_id='10' AND fld_flag='0'");
					if($count!=0)
						$ObjDB->NonQuery("UPDATE itc_question_answer_mapping 
										SET fld_flag='1', fld_answer='".$listoptions[$i]."',fld_boxid='".$j."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."'  
										WHERE fld_id='".$count."' ");
					else
						$ObjDB->NonQuery("INSERT INTO itc_question_answer_mapping (fld_quesid, fld_ans_type, fld_attr_id, fld_answer, fld_flag, fld_boxid, fld_created_by, fld_created_date) 
										VALUES ('".$questionid."', '".$answertype."', '10', '".$listoptions[$i]."','1', '".$j."', '".$uid."', '".date("Y-m-d H:i:s")."')");
				}
			}
		}
		
		if($answertype==11)
		{
			$listoptions = explode('~',$listoptions);
			if($correctpulldown!='')
			{
				$correctpull = explode('~',$correctpulldown);
				for($i=0;$i<sizeof($correctpull);$i++)
				{
					$count = $ObjDB->SelectSingleValueInt("SELECT fld_id 
															FROM itc_question_answer_mapping 
															WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertype."' AND fld_attr_id='2' AND fld_flag='0'");
					if($count!=0)
						$ObjDB->NonQuery("UPDATE itc_question_answer_mapping 
										SET fld_flag='1', fld_answer='".$listoptions[$correctpull[$i]-1]."', fld_boxid='".$correctpull[$i]."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' 
										WHERE fld_id='".$count."' ");
					else
						$ObjDB->NonQuery("INSERT INTO itc_question_answer_mapping (fld_quesid, fld_ans_type, fld_attr_id, fld_answer,fld_boxid, fld_created_by, fld_created_date) 
										VALUES ('".$questionid."', '".$answertype."', '2', '".$listoptions[$correctpull[$i]-1]."','".$correctpull[$i]."', '".$uid."', '".date("Y-m-d H:i:s")."')");
				}
			}
			if($listoptions!='')
			{
				
				for($i=0;$i<sizeof($listoptions);$i++)
				{	
					$count = $ObjDB->SelectSingleValueInt("SELECT fld_id 
														FROM itc_question_answer_mapping 
														WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertype."' AND fld_attr_id='10' AND fld_flag='0'");
					if($count!=0)
						$ObjDB->NonQuery("UPDATE itc_question_answer_mapping 
										SET fld_flag='1', fld_answer='".$listoptions[$i]."',fld_boxid='".$i."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' 
										WHERE fld_id='".$count."' ");
					else
						$ObjDB->NonQuery("INSERT INTO itc_question_answer_mapping (fld_quesid, fld_ans_type, fld_attr_id, fld_answer, fld_flag, fld_boxid, fld_created_by, fld_created_date) 
										VALUES ('".$questionid."', '".$answertype."', '10', '".$listoptions[$i]."','1', '".$i."', '".$uid."', '".date("Y-m-d H:i:s")."')");
				}
			}
		}
		
		if($answertype==12)
		{
			$ballcolor = explode('~',$ballcolor);
			$insideball = explode('~',$insideball);
			$outsideball = explode('~',$outsideball);
			$dragcorrect = explode('~',$dragcorrect);
			$draganocorrect = explode('~',$draganocorrect);
			$anscolor = explode('~',$anscolor);
			if($ballcolor[0]!='')
			{
				for($i=0;$i<sizeof($ballcolor);$i++)
				{
					$count = $ObjDB->SelectSingleValueInt("SELECT fld_id 
														FROM itc_question_drag_drop 
														WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertype."' AND fld_ball_color='".$ballcolor[$i]."' 
															AND fld_flag='1'");
					
					if($count!=0)
						$ObjDB->NonQuery("UPDATE itc_question_drag_drop 
										SET fld_flag='1', fld_inner_ball='".$insideball[$i]."', fld_outer_ball='".$outsideball[$i]."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' 
										WHERE fld_id='".$count."' ");
					else
						$ObjDB->NonQuery("INSERT INTO itc_question_drag_drop (fld_quesid, fld_ans_type, fld_ball_color, fld_inner_ball, fld_outer_ball, fld_flag, fld_created_by, fld_created_date) 
										VALUES ('".$questionid."', '".$answertype."', '".$ballcolor[$i]."', '".$insideball[$i]."', '".$outsideball[$i]."','1', '".$uid."', '".date("Y-m-d H:i:s")."')");
				}
			}
			if($anscolor[0]!='')
			{
				for($i=0;$i<sizeof($anscolor);$i++)
				{	
					if($dragcorrect[$i]=='-')
						$cor = 0;
					else
						$cor = $dragcorrect[$i];
					
					if($draganocorrect[$i]=='-')
						$anocor = 0;
					else
						$anocor = $draganocorrect[$i];
					
					$newanscolor = str_replace("#",'',$anscolor[$i]);
					
					$ObjDB->NonQuery("UPDATE itc_question_drag_drop 
									SET fld_flag='1', fld_correct='".$cor."', fld_ano_correct='".$anocor."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' 
									WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertype."' AND fld_ball_color='".$newanscolor."'");
				}
			}
		}
	}
	
	/*--- Load Lesson  ---*/
	if($oper=="showlesson" and $oper != " " )
	{
		$unitid = isset($_POST['unitid']) ? $_POST['unitid'] : '';
		?>
        <div class="selectbox">
            <input type="hidden" name="lessonid" id="lessonid" value=""  onchange="$(this).valid();" />
            <a class="selectbox-toggle" style="width:410px;" role="button" data-toggle="selectbox" href="#">
                <span class="selectbox-option input-medium" data-option=" " id="clearlesson" style="width:400px;">Select Lesson</span>
                <b class="caret1"></b>
            </a>
            <div class="selectbox-options">
                <input type="text" class="selectbox-filter" placeholder="Search Lesson">
                <ul role="options" style="width:410px;">
                    <?php 
                    $qryipl = $ObjDB->QueryObject("SELECT a.`fld_id` AS iplid, CONCAT(a.`fld_ipl_name`,' ', b.`fld_version`) AS ipl_name 
													FROM `itc_ipl_master` a
													LEFT JOIN `itc_ipl_version_track` b ON a.`fld_id`=b.`fld_ipl_id`
													WHERE b.`fld_zip_type`='1' AND a.`fld_delstatus`='0' AND a.`fld_unit_id`='".$unitid."' AND a.`fld_access`='1'");
                    if($qryipl->num_rows > 0)
                    {
                        while($rowipl = $qryipl->fetch_assoc())
                        {
							extract($rowipl);
                        ?>
                            <li><a tabindex="-1" href="#" data-option="<?php echo $iplid;?>"><?php echo $ipl_name; ?></a></li>
                        <?php
                        }
                    }
                    ?>       
                </ul>
            </div>
        </div>
		<?php
	}	
	
	/*--- Save/Update a Review  ---*/
	if($oper == "savereview" and $oper != '')
	{
		$questionid = isset($_POST['questionid']) ? $_POST['questionid'] : '';
		$sid = isset($_POST['sid']) ? $_POST['sid'] : '';
		if($sid==''){
			$qry = $ObjDB->QueryObject("SELECT fld_question_type_id,fld_lesson_id FROM itc_question_details WHERE fld_id='".$questionid."'");
			extract($qry->fetch_assoc());
			if($fld_question_type_id==1)
				$type = '1_diagnostic';
			if($fld_question_type_id==2)
				$type = '2_mastery1';
			if($fld_question_type_id==3)
				$type = '3_mastery2';
			$lesson = $fld_lesson_id.'_lesson';
			
		}
		
		$ObjDB->NonQuery("UPDATE itc_question_details 
						SET fld_access='1', fld_updated_by='".$uid."', fld_updated_date='".$date."', fld_step_id='1' 
						WHERE fld_id='".$questionid."' AND fld_delstatus='0'");
		
		echo $type.','.$lesson;
	}
			
	/*----- Load Equation Symbols -----*/	
	if($oper == "loadequationsymbols" and $oper != "") 
	{
		$anschoice = isset($_POST['anschoice']) ? $_POST['anschoice'] : '';
		?>
    	<div style="width:450px;height:250px;">
		<?php
            $symbolsqry = $ObjDB->QueryObject("SELECT fld_id, fld_img_src, TRIM(fld_equations) AS fld_equations FROM itc_equation_editor");
            while($rowsymbol =$symbolsqry->fetch_object())
            {
        	?>
            <div class="divsymbols">
                <img src="<?php echo __IMGPATH__; ?>equation-img/<?php echo $rowsymbol->fld_img_src.".png";?>" alt="<?php echo $rowsymbol->fld_equations;?>" id="q_<?php echo $rowsymbol->fld_id;?>" border="0" onclick="fn_addtoquestion(this,<?php echo $anschoice; ?>);" />
            </div>                   
        	<?php
            }
		?>
        </div>
    <?php
	}
	
	/*--- Delete a Question  ---*/
	if($oper == "deleteques" and $oper != '')
	{
		$questionid = isset($_POST['qid']) ? $_POST['qid'] : '';
		
		$count = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
											FROM itc_diag_question_mapping 
											WHERE (fld_diag_ques1a='".$questionid."' OR fld_diag_ques1b='".$questionid."' OR fld_diag_ques2a='".$questionid."' 
												OR fld_diag_ques2b='".$questionid."' OR fld_diag_ques3a='".$questionid."' OR fld_diag_ques3b='".$questionid."' 
												OR fld_mast1_ques1a='".$questionid."' OR fld_mast1_ques1b='".$questionid."' OR fld_mast1_ques2a='".$questionid."' 
												OR fld_mast1_ques2b='".$questionid."' OR fld_mast1_ques3a='".$questionid."' OR fld_mast1_ques3b='".$questionid."' 
												OR fld_mast2_ques1a='".$questionid."' OR fld_mast2_ques1b='".$questionid."' OR fld_mast2_ques2a='".$questionid."' 
												OR fld_mast2_ques2b='".$questionid."' OR fld_mast2_ques3a='".$questionid."' OR fld_mast2_ques3b='".$questionid."') 
												AND fld_delstatus='0'");
		
		if($count==0)
		{
			$ObjDB->NonQuery("UPDATE itc_question_details 
							SET fld_delstatus='1', fld_deleted_date='".date("Y-m-d H:i:s")."', fld_deleted_by='".$uid."' 
							WHERE fld_id='".$questionid."'");
			echo "success";
		}
		else
		{
			echo "exists";
		}
		
	}

	@include("footer.php");		