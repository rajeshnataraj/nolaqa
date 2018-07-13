<?php
@include("sessioncheck.php");

$id= isset($method['id']) ? $method['id'] : '';
$sid = isset($method['sid']) ? $method['sid'] : '0';
$flag=0;
	$sqry='';
	if($sid!=0){
		$sid = explode(',',$sid);
		for($i=0;$i<sizeof($sid);$i++){
			$ids = explode('~',$sid[$i]);
			if($ids[1]=='diagnostic'){
				$sqry.= " and a.fld_question_type_id =".$ids[0];
			}
			else if($ids[1]=='mastery1'){
				$sqry.= " and a.fld_question_type_id =".$ids[0];
			}
			else if($ids[1]=='mastery2'){
				$sqry.= " and a.fld_question_type_id =".$ids[0];
			}
			else if($ids[1]=='testengine'){
				$sqry.= " and a.fld_question_type_id =".$ids[0];
				$sqry.= " AND (a.fld_created_by='".$uid."' OR a.fld_id IN 
				          (SELECT  h.fld_question_id FROM itc_license_assessment_mapping AS e LEFT JOIN itc_license_track AS g ON e.fld_license_id = g.fld_license_id 
						   RIGHT JOIN itc_test_master AS f ON e.fld_assessment_id=f.fld_id
						   RIGHT JOIN itc_test_questionassign AS h ON h.fld_test_id=f.fld_id WHERE g.fld_district_id='".$districtid."' 
						   AND g.fld_school_id='".$schoolid."' AND g.fld_user_id='".$indid."' AND g.fld_delstatus='0' AND '".date("Y-m-d")."' 
						   BETWEEN g.fld_start_date AND g.fld_end_date AND e.fld_access='1' AND f.fld_delstatus='0' AND h.fld_delstatus='0' )) ";
			}
			else if($ids[1] == 'unit'){ // check the conditional name and concatenate the field name according to it.
				$chkqry = $ObjDB->SelectSingleValue("SELECT  GROUP_CONCAT(fld_item_id SEPARATOR ',') AS 'fld_item_id' 
				                                    FROM itc_main_tag_mapping WHERE fld_tag_type='4' AND fld_lesson_flag='1' 
													AND fld_access='1' AND fld_tag_id='".$ids[0]."'");
				if($chkqry!='')
					$sqry.= " AND (a.fld_unit_id =".$ids[0]." OR a.fld_id in (".$chkqry."))";
				else
					$sqry.= " AND (a.fld_unit_id =".$ids[0].")";
				
			}
			else if($ids[1] == 'course'){
				$sqry.= " AND a.fld_course_id =".$ids[0];
			}
			else if($ids[1] == 'subject'){
				$sqry.= " AND a.fld_subject_id =".$ids[0];
			}
			else if($ids[1] == 'lesson'){
				$chkqry = $ObjDB->SelectSingleValue("SELECT  GROUP_CONCAT(fld_item_id SEPARATOR ',') AS 'fld_item_id' 
				                                    FROM itc_main_tag_mapping WHERE fld_tag_type='1' AND fld_lesson_flag='1' 
													AND fld_access='1' AND fld_tag_id='".$ids[0]."'");
				if($chkqry!='')
					$sqry.= " AND (a.fld_lesson_id =".$ids[0]." OR a.fld_id in (".$chkqry."))";
				else
					$sqry.= " AND (a.fld_lesson_id =".$ids[0].")";
			}
			else{
				if($sid[$i]==61){	
						$ObjDB->NonQuery("SET SESSION group_concat_max_len = 1000000");				
						$invalue = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_item_id) FROM itc_main_tag_mapping WHERE fld_tag_id=61 AND fld_access='1'");
						$sqry.= ' AND a.fld_id IN ('.$invalue.')';	
				}
				else{					
					if($sessmasterprfid == 2)
						$itemqry = $ObjDB->QueryObject("SELECT a.fld_item_id FROM itc_main_tag_mapping AS a 
						                              LEFT JOIN itc_question_details AS b ON a.fld_item_id=b.fld_id 
													  LEFT JOIN itc_user_master AS c ON b.fld_created_by = c.fld_id 
													  WHERE a.fld_tag_id='".$sid[$i]."' AND a.fld_access='1' AND a.fld_tag_type='19' 
													  AND c.fld_profile_id ='2' GROUP BY a.fld_item_id");
					else{
						$tag_type = $ObjDB->SelectSingleValueInt("SELECT fld_tag_type FROM itc_main_tag_master WHERE fld_id='".$sid[$i]."'");
						if($tag_type==0)
							$tmpvar = " AND b.fld_created_by='".$uid."'";
						else{
							$tmpvar.= " AND a.fld_item_id IN(SELECT  h.fld_question_id FROM itc_license_assessment_mapping AS e 
				            LEFT JOIN itc_license_track AS g ON e.fld_license_id = g.fld_license_id 
							RIGHT JOIN itc_test_master AS f ON e.fld_assessment_id=f.fld_id 
							RIGHT JOIN itc_test_questionassign AS h ON h.fld_test_id=f.fld_id 
							WHERE g.fld_district_id='".$districtid."' AND g.fld_school_id='".$schoolid."' 
							AND g.fld_user_id='".$indid."' AND g.fld_delstatus='0' AND '".date("Y-m-d")."' 
							BETWEEN g.fld_start_date AND g.fld_end_date AND e.fld_access='1' AND f.fld_delstatus='0' 
							AND h.fld_delstatus='0' )";							
						}
							$itemqry = $ObjDB->QueryObject("SELECT a.fld_item_id FROM itc_main_tag_mapping AS a 
						                               LEFT JOIN itc_question_details AS b ON a.fld_item_id=b.fld_id 
													   WHERE a.fld_tag_id='".$sid[$i]."' AND a.fld_access='1' AND a.fld_tag_type='19'  
													   ".$tmpvar." GROUP BY a.fld_item_id");						
					}
					
					if($itemqry->num_rows>0){
						$j=1;
						$sqry.= " and (";
						while($itemres = $itemqry->fetch_assoc()){
							extract($itemres);					
							
							if($j==$itemqry->num_rows){						
								$sqry.=" a.fld_id=".$fld_item_id.")";
							}
							else{
								$sqry.=" a.fld_id=".$fld_item_id." or";
							}
							$j++;
						}
					}
				}
			}
		}
	}
?>
<section data-type='home' id='test-testassign-addquestion'>
	<script type="text/javascript" charset="utf-8">	
		$(function(){				
			var t4 = new $.TextboxList('#form_tags', {
				unique: true, 
				startEditableBit: false,
				inBetweenEditableBits: false,
				plugins: {
					autocomplete: {
						onlyFromValues:true,
						queryRemote: true,
						remote: { url: 'autocomplete.php', extraParams: "oper=search&tag_type=19&diagtag=1&lesson=1&testquestion=1" },
						placeholder: ''
					}
				},
				bitsOptions:{editable:{addKeys: [188]}}													
			});																	
			<?php if($sid!=0){
			
			
			for($i=0;$i<sizeof($sid);$i++)
			{
				$ids = explode('_',$sid[$i]);	
				if($ids[1]=='testengine'){
						$tagname= "Assessment questions";
						
					}
								
					else{
				$tagname=$ObjDB->SelectSingleValue("SELECT fld_tag_name FROM itc_main_tag_master 
				                                    WHERE fld_id='".$sid[$i]."' AND fld_delstatus='0'");
					}
			?>	
			
			t4.add('<?php echo $ObjDB->EscapeStrAll($tagname); ?>','<?php echo $sid[$i]; ?>');	
			
			<?php
			}
			}?>
			t4.addEvent('bitAdd',function(bit) {
				fn_queslist();
				removesections('#test-testassign-addquestion');
			});
			
			t4.addEvent('bitRemove',function(bit) {
				fn_queslist();
				removesections('#test-testassign-addquestion');
			});						
				
		});	
		
		$('#tablecontents1').slimscroll({
			height:'auto',
			railVisible: false,
			allowPageScroll: false,
			railColor: '#F4F4F4',
			opacity: 9,
			color: '#88ABC2',
		});
		
		function fn_queslist(){
			var sid = $('#form_tags').val();
			$("#licenselist").load("test/testassign/test-testassign-addquestion.php #licenselist > *",{
				"sid":sid,
				"id":"<?php echo $id;?>"
				},function(){
				$('#tablecontents1').slimscroll({
					height:'auto',
					railVisible: false,
					allowPageScroll: false,
					railColor: '#F4F4F4',
					opacity: 9,
					color: '#88ABC2',
				});
			});
		}
	</script>
       <link rel="stylesheet" href="../css/styleminus.css">
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Add a Question to Your Assessment</p>
            	<p class="dialogSubTitleLight">Select from available questions or create a new question using tags below. Click a row to view details.</p>
            </div>
        </div>
        
        <div class='row rowspacer'>
            <div class='twelve columns'>
            	<p id="filters">To filter this list, search by Tag Name, Lesson Name, Diagnostic, Mastery1, Mastery2, MAEP and Assessment Questions.</p>
                <div class="tag_well">
                    <input type="text" name="form_tags" value="" id="form_tags" />
                </div>
            </div>
        </div>		
        <div class='row rowspacer'>
            <div class='span10 offset1' id="licenselist">
                 <table class='table table-hover table-striped table-bordered setbordertopradius'>
                    <thead class='tableHeadText'>
                        <tr>
                            <th width="10%">Add</th>
                            <th width="45%" class='centerText'>Available Question</th>
                            <th width="20%" class='centerText'>Lesson</th>
                            <th width="25%" class='centerText'>Assessment Type</th>
                        </tr>
                    </thead>
                     <tbody>
                        <tr class="mainBtn" id="btntest-testassign-quscreation" name="0,<?php echo $id;?>">
                            <td colspan="4" class="createnewtd"><span style="margin-left:20px;" class="icon-synergy-create small-icon"></span><span style="margin-left:40px;">Create New Question</span><span style="margin-left:475px;"></span>Total Questions:<span id='qcount' style="margin-left:20px;"></span></td>               
                        </tr>
                    </tbody>
                </table>
				<?php 
                if($sqry != '') {
                    $quesids = 0;										
                    if($sessmasterprfid == 2){
                        $qry = $ObjDB->QueryObject("SELECT DISTINCT(a.fld_id) AS qusid, a.fld_question AS qusname, a.fld_lesson_id AS lid, 
                                                  c.fld_question_type AS qustype, a.fld_created_by FROM `itc_question_details` AS a, `itc_ipl_master` AS b, 
                                                  `itc_question_type` AS c, itc_user_master as w WHERE a.fld_created_by=w.fld_id 
                                                  AND c.`fld_id`=a.`fld_question_type_id` AND w.fld_profile_id='2' AND a.fld_delstatus='0' 
                                                  AND a.fld_id NOT IN (SELECT fld_question_id FROM `itc_test_questionassign` 
                                                  WHERE fld_test_id='".$id."' AND fld_delstatus='0') AND b.fld_delstatus='0' AND c.fld_delstatus='0' ".$sqry."");
                    }
                    else{
                        $qry = $ObjDB->QueryObject("SELECT DISTINCT(a.fld_id) AS qusid, a.fld_question AS qusname, a.fld_lesson_id AS lid, 
                                                   c.fld_question_type AS qustype, a.fld_created_by FROM `itc_question_details` AS a, `itc_ipl_master` AS b, 
                                                   `itc_question_type` AS c WHERE c.`fld_id`=a.`fld_question_type_id` AND a.fld_delstatus='0'
                                                    AND a.fld_id NOT IN (SELECT fld_question_id FROM `itc_test_questionassign` 
                                                    WHERE fld_test_id='".$id."' AND fld_delstatus='0') AND b.fld_delstatus='0' AND c.fld_delstatus='0' ".$sqry."");
                    }
				?>
                <div style="max-height:400px;width:100%;" id="tablecontents1" >
                    <table style="margin-bottom:0px;" class='table table-hover table-striped table-bordered bordertopradiusremove'>
                    	<tbody>
                			<?php
							if($qry->num_rows > 0){
								while($row=$qry->fetch_assoc()){
								extract($row);
							?>	
								<tr id="tr_<?php echo $qusid; ?>"  name="<?php echo $qusid; ?>"  >	
									<td width="10%" id="<?php echo $qusid; ?>" onclick="fn_rowclick(this.id)" name="<?php echo $qusid; ?>">
										 <span id="span_<?php echo $qusid; ?>" style="margin-left:20px;" class="icon-synergy-add-small"></span>
									</td>								
	
									<td width="45%" class="mainBtn" id="btntest-testassign-review" name="create_<?php echo $qusid;?>_<?php echo $id;?>">
										<?php echo strip_tags($qusname);?>
									</td>
									
									<td width="20%" class='centerText'>
										<?php
											$lessontit = $ObjDB->SelectSingleValue("SELECT CONCAT(a.fld_ipl_name,' ', b.fld_version) 
																					FROM itc_ipl_master  AS a 
																					LEFT JOIN itc_ipl_version_track AS b ON a.fld_id=b.fld_ipl_id
																					WHERE a.fld_id='".$lid."' AND a.fld_delstatus='0' 
																					AND b.fld_zip_type='1' AND b.fld_delstatus='0'");
											echo $lessontit; 
										 ?>
									 </td>
									  
									<td width="25%" class='centerText'>
										<?php echo $qustype; ?>
									</td>                                                              
								</tr>
						<?php
								}
							}
							else{?>
								<tr><td colspan="4">No Records Found</td></tr>                                       
						<?php 
							}
						}
						?>
                		</tbody>
            		</table>
                </div>
            </div>
        </div>
        
        <div class='row rowspacer'>
            <div class='six columns'></div>
            <div class='twelve columns'>
                <div class='row' id="submit" style="display:none;">
                	<div class="tRight">
                        <input type="button" id="btnstep" class="darkButton" style="width:140px; height:42px;float:right;" value="Submit" onClick="fn_submitlist(<?php echo $id;?>);" />
                    </div>
                </div>
            </div>
        </div>
    </div>  
    <script>		
		if($('#test-testassign-testquestion').hasClass('blueWindow1'))
			$('#filters').addClass('filterDarkTitle');
		else if($('#test-testassign-testquestion').hasClass('blueWindow2'))
			$('#filters').addClass('filterLightTitle');
	</script> 
</section>
<?php
	@include("footer.php");