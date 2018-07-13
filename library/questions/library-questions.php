<?php
	@include("sessioncheck.php");
	$sid = isset($method['sid']) ? $method['sid'] : '0';
	$sqry = '';
	if($sid != 0){
		$sid = explode(',',$sid);
		for($i=0;$i<sizeof($sid);$i++){
			$ids = explode('~',$sid[$i]);
			if($ids[1]=='diagnostic'){
				$sqry.= " AND b.fld_question_type_id =".$ids[0];
			}
			else if($ids[1]=='mastery1'){
				$sqry.= " AND b.fld_question_type_id =".$ids[0];
			}
			else if($ids[1]=='mastery2'){
				$sqry.= " AND b.fld_question_type_id =".$ids[0];
			}
			else if($ids[1]=='testengine'){
				$sqry.= " AND b.fld_question_type_id =".$ids[0];
			}
			else if($ids[1] == 'unit'){ // check the conditional name and concatenate the field name according to it.
				$sqry.= " AND b.fld_unit_id =".$ids[0];
			}			
			else if($ids[1] == 'lesson'){
				$sqry.= " AND b.fld_lesson_id =".$ids[0];
			}
			else{
				$itemqry = $ObjDB->QueryObject("SELECT fld_item_id 
												FROM itc_main_tag_mapping 
												WHERE fld_tag_id='".$sid[$i]."' AND fld_access='1' AND fld_tag_type='19'");
				$sqry.= " AND (";
				$j=1;
				while($itemres = $itemqry->fetch_assoc()){
					extract($itemres);
					if($j==$itemqry->num_rows){
						$sqry.=" b.fld_id=".$fld_item_id.")";
					}
					else{
						$sqry.=" b.fld_id=".$fld_item_id." or";
					}
					$j++;
				} // while ends
			} // nested if ends 
		}// for ends
	}// if ends
?>
<script type="text/javascript" charset="utf-8">	
	$.getScript("library/questions/library-questions-creation.js");
			
	$(function(){				
		var t4 = new $.TextboxList('#form_tags_questions', 
		{
			startEditableBit: false,
			inBetweenEditableBits: false,
			plugins: {
				autocomplete: { 
					onlyFromValues:true, 
					queryRemote: true,
					remote: { url: 'autocomplete.php', extraParams: "oper=search&tag_type=19&diagtag=1&lesson=1" },
					placeholder: ''
				}
			},
			bitsOptions:{editable:{addKeys: [188]}}									
		});					
			<?php 
			if($sid != 0){
				for($i=0;$i<sizeof($sid);$i++)
				{	
					$tagname='';
					$ids = explode('_',$sid[$i]);
					if($ids[1]=='diagnostic'){
						$tagname= "Diagnostic Test";
					}
					else if($ids[1]=='mastery1'){
						$tagname= "Mastery1 Test";
					}
					else if($ids[1]=='mastery2'){
						$tagname= "Mastery2 Test";
					}
					else if($ids[1] == 'unit'){
						$tagname= $ObjDB->SelectSingleValue("SELECT fld_unit_name 
															FROM itc_unit_master 
															WHERE fld_id='".$ids[0]."'");
					}			
					else if($ids[1] == 'lesson'){
						$tagname= $ObjDB->SelectSingleValue("SELECT fld_ipl_name 
															FROM itc_ipl_master 
															WHERE fld_id='".$ids[0]."'");
					}				
					else{
						$tagname=$ObjDB->SelectSingleValue("SELECT fld_tag_name 
															FROM itc_main_tag_master 
															WHERE fld_id='".$sid[$i]."' AND fld_delstatus='0'");
					}
				?>	
					t4.add('<?php echo $ObjDB->EscapeStrAll($tagname); ?>','<?php echo $sid[$i]; ?>');	
				<?php
				}
			}
			?>
			
			t4.addEvent('bitAdd',function(bit) {
				fn_loadquestions();
			});
			
			t4.addEvent('bitRemove',function(bit) {
				fn_loadquestions();
			});					
	});	

	function fn_loadquestions(){
		var sid = $('#form_tags_questions').val();
		$("#quesdiv").load("library/questions/library-questions.php #quesdiv > *",{'sid':sid},function(){
			$('#tablecontents5').slimscroll({
				height:'auto',
				railVisible: false,
				allowPageScroll: false,
				railColor: '#F4F4F4',
				opacity: 9,
				color: '#88ABC2',
			});
		});
	}
	
	$('#tablecontents5').slimscroll({
		height:'auto',
		size: '3px',
		railVisible: false,
		allowPageScroll: false,
		railColor: '#F4F4F4',
		opacity: 9,
		color: '#88ABC2',
	});
</script>
<section data-type='2home' id='library-questions'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="darkTitle">Questions</p>
                <p class="darkSubTitle">&nbsp;</p>
            </div>
        </div>
        
        <div class='row'>
            <div class='twelve columns'>
                <p class="filterLightTitle">To filter this list, search by Tag Name, Diagnostic, Mastery 1, Mastery 2, and Lesson Name.</p>
                <div class="tag_well">
                	<input type="text" name="form_tags_questions" value="" id="form_tags_questions" />
               	</div>
            </div>
        </div>
        
        <div class='row rowspacer'>
            <div class='span10 offset1' id="quesdiv"> 
                <table class='table table-hover table-striped table-bordered setbordertopradius'>
                    <thead class='tableHeadText'>
                        <tr>
                            <th width="85%">Question&nbsp;</th>
                            <th class='centerText'>Type&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="mainBtn" id="btnlibrary-questions-steps" name="0">
                          <td colspan="2" class="createnewtd"><span class="icon-synergy-create small-icon"></span>&nbsp;&nbsp;&nbsp;Create a New Question</td>								 						</tr>
                    </tbody>
                </table>
				<?php
                if($sqry != '') {							
                    $qry = $ObjDB->QueryObject("SELECT b.fld_question AS question, b.fld_id AS quesid, 
                                                    c.fld_question_type AS questype 
                                                FROM itc_question_details AS b 
                                                LEFT JOIN itc_question_type AS c ON b.fld_question_type_id = c.fld_id 
                                                WHERE b.fld_delstatus='0' AND b.fld_question_type_id<>'4'  ".$sqry." 
                                                ORDER BY b.`fld_question_type_id` ASC"); //AND b.fld_access='1'
                ?>
                 <div style="max-height:400px;width:100%;" id="tablecontents5">
                    <table style="margin-bottom:0px;" class='table table-hover table-striped table-bordered bordertopradiusremove'>
                    	<tbody>
                            <?php			
							if($qry->num_rows>0){
								while($row = $qry->fetch_assoc())
								{	
									extract($row);
								?>
                                	<tr onclick="fn_showquestion(<?php echo $quesid;?>);">
                                        <td width="85%"><?php echo strip_tags($question); ?></td>
                                        <td class='centerText'><?php echo $questype; ?></td>
                                    </tr>
                                <?php	
								} // while ends
							}// if ends
						}// if ends
						?>
                  		</tbody>
            		</table>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
	@include("footer.php");