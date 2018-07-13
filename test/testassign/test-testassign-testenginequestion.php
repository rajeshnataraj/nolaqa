<?php
@include("sessioncheck.php");

$id= isset($method['id']) ? $method['id'] : '';
$sid = isset($method['sid']) ? $method['sid'] : '0';
	$sqry='';
	if($sid!=0){
		$sid = explode(',',$sid);
		for($i=0;$i<sizeof($sid);$i++){
			$ids = explode('~',$sid[$i]);
			if(isset($ids[1]) and $ids[1]=='diagnostic'){
				$sqry.= " AND a.fld_question_type_id =".$ids[0];
			}
			else if(isset($ids[1]) and $ids[1]=='mastery1'){
				$sqry.= " AND a.fld_question_type_id =".$ids[0];
			}
			else if(isset($ids[1]) and $ids[1]=='mastery2'){
				$sqry.= " AND a.fld_question_type_id =".$ids[0];
			}
			else if(isset($ids[1]) and $ids[1]=='testengine'){
				$sqry.= " AND a.fld_question_type_id =".$ids[0];
				$sqry.= " AND (a.fld_created_by='".$uid."' OR a.fld_id IN(SELECT  h.fld_question_id FROM itc_license_assessment_mapping AS e 
				            LEFT JOIN itc_license_track AS g ON e.fld_license_id = g.fld_license_id 
							RIGHT JOIN itc_test_master AS f ON e.fld_assessment_id=f.fld_id 
							RIGHT JOIN itc_test_questionassign AS h ON h.fld_test_id=f.fld_id 
							WHERE g.fld_district_id='".$districtid."' AND g.fld_school_id='".$schoolid."' 
							AND g.fld_user_id='".$indid."' AND g.fld_delstatus='0' AND '".date("Y-m-d")."' 
							BETWEEN g.fld_start_date AND g.fld_end_date AND e.fld_access='1' AND f.fld_delstatus='0' 
							AND h.fld_delstatus='0' )) ";
			}
			else if(isset($ids[1]) and $ids[1]=='unit'){
				$sqry.= ' AND a.fld_id IN (SELECT fld_item_id FROM itc_main_tag_mapping WHERE fld_tag_type="4" AND fld_access="1" AND fld_tag_id='.$ids[0].')';
			}
			else if(isset($ids[1]) and $ids[1]=='lesson'){
				$sqry.= ' AND a.fld_id IN (SELECT fld_item_id FROM itc_main_tag_mapping WHERE fld_tag_type="1" AND fld_access="1" AND fld_tag_id='.$ids[0].')';
			}
                        else if($sid[$i]==61){	
                            $ObjDB->NonQuery("SET SESSION group_concat_max_len = 1000000");				
                            $invalue = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_item_id) FROM itc_main_tag_mapping WHERE fld_tag_id=61 AND fld_access='1'");
                            $sqry.= ' AND a.fld_id IN ('.$invalue.')';	
                        }
			else{
				if(isset($sid[$i]) and $sid[$i]==1)
				$sqry.= ' AND a.fld_id IN (SELECT fld_item_id FROM itc_main_tag_mapping WHERE fld_tag_id="1")';
				else{
					if($sessmasterprfid == 2)
						$itemqry = $ObjDB->QueryObject("SELECT a.fld_item_id FROM itc_main_tag_mapping AS a 
						                               LEFT JOIN itc_question_details AS b ON a.fld_item_id=b.fld_id 
													   LEFT JOIN itc_user_master AS c ON b.fld_created_by = c.fld_id                     
													   WHERE a.fld_tag_id='".$sid[$i]."' AND a.fld_access='1' AND a.fld_tag_type='19' AND c.fld_profile_id ='2' GROUP BY a.fld_item_id");  // c.fld_profile_id
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
													   WHERE a.fld_tag_id='".$sid[$i]."' AND a.fld_access='1' AND a.fld_tag_type='19' ".$tmpvar."");						
					}
					if($itemqry->num_rows>0){
						$sqry.= " and (";
						$j=1;
						while($itemres = $itemqry->fetch_assoc()){
							extract($itemres);
							if($j==$itemqry->num_rows){
								$sqry.=" a.fld_id=".$fld_item_id.")";
							}
							else{
								$sqry.=" a.fld_id=".$fld_item_id." or";
							}
							$j++;
						} // while ends
					}
				}
			} // nested if ends 
		}// for ends
	}// if ends
?>
<script type="text/javascript" charset="utf-8">		
	$.getScript("test/testassign/test-testassign-addquestion.js");
	
	$(function(){				
		var t4 = new $.TextboxList('#form_tags', {
			unique: true, 
			startEditableBit: false,
			inBetweenEditableBits: false,
			plugins: {
				autocomplete: {
					onlyFromValues:true,
					queryRemote: true,
					remote: { url: 'autocomplete.php', extraParams: "oper=search&tag_type=19&testquestion=1&lesson=1&unit=1" },
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
				else if($ids[1]=='lesson'){
					$tagname=$ObjDB->SelectSingleValue("SELECT fld_ipl_name FROM itc_ipl_master WHERE fld_id='".$sid[$i]."' AND fld_delstatus='0'");
				}
				else if($ids[1]=='unit'){
					$tagname=$ObjDB->SelectSingleValue("SELECT fld_unit_name FROM itc_unit_master WHERE fld_id='".$sid[$i]."' AND fld_delstatus='0'");
				}			
				else{
					$tagname=$ObjDB->SelectSingleValue("SELECT fld_tag_name FROM itc_main_tag_master WHERE MD5(fld_id)=MD5('".$sid[$i]."') AND fld_delstatus='0'");
				}
				if($tagname==''){
					$tagname = $sid[$i];
					$sid[$i] = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_main_tag_master WHERE fld_tag_name='".$sid[$i]."' AND fld_delstatus='0'");
				}
			?>	
			
			t4.add('<?php echo $ObjDB->EscapeStrAll($tagname); ?>','<?php echo $sid[$i]; ?>');	
			
			<?php
			}
			}?>
		t4.addEvent('bitAdd',function(bit) {
			fn_queslist();
		});
		
		t4.addEvent('bitRemove',function(bit) {
			fn_queslist();
		});						
			
	});		
	function fn_queslist(){
		var sid = $('#form_tags').val();
		$("#queslist").load("test/testassign/test-testassign-testenginequestion.php #queslist > *",{ 'sid':sid},function(){
			$('#tablecontents2').slimscroll({
				height:'auto',
				railVisible: false,
				allowPageScroll: false,
				railColor: '#F4F4F4',
				opacity: 9,
				color: '#88ABC2',
			});
		});
	}
        
        $('#tablecontents2').slimscroll({
            height:'auto',
            size: '3px',
            railVisible: false,
            allowPageScroll: false,
            railColor: '#F4F4F4',
            opacity: 9,
            color: '#88ABC2',
    });
</script>
<section data-type='home' id='test-testassign-testenginequestion'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Question bank</p>
            	<p class="dialogSubTitleLight">Create new question for the assessment.</p>
            </div>
        </div>
        
        <div class='row rowspacer' style="padding-bottom:20px;">
            <div class='twelve columns'>
            	<p class="filterDarkTitle">To filter this list, search by Tag Name, Lesson Name, Unit Name, MAEP and Assessment Question.</p>
                <div class="form_friends">
                    <input type="text" name="test3" value="" id="form_tags" />
                </div>
            </div>
        </div>
        
        <div class='row rowspacer'>
            <div class='span10 offset1' id="queslist">
                <table class='table table-hover table-striped table-bordered setbordertopradius'>
                    <thead class='tableHeadText'>
                        <tr>
                            <th width="10%">#</th>
                            <th width="65%" class='centerText'>Available Question</th>
                            <th width="25%" class='centerText'>Assessment Type</th>
                        </tr>
                    </thead>
                     <tbody>
                        <tr class="mainBtn" id="btntest-testassign-quscreation" name="0,qbank,new">
                            <td colspan="4" class="createnewtd"><span style="margin-left:20px;" class="icon-synergy-create small-icon"></span><span style="margin-left:40px;">Create New Question</span></td>               
                        </tr>
                    </tbody>
                </table>
				<?php 
                if($sqry!=''){
                    if($sessmasterprfid == 2){					
                        $qry = $ObjDB->QueryObject("SELECT DISTINCT(a.fld_id) AS qusid, a.fld_question AS qusname, a.fld_lesson_id AS lid, 
                                                  c.fld_question_type AS qustype, a.fld_created_by FROM `itc_question_details` AS a, `itc_ipl_master` AS b, 
                                                  `itc_question_type` AS c, itc_user_master as w 
                                                  WHERE a.fld_created_by=w.fld_id AND c.`fld_id`=a.`fld_question_type_id` 
                                                  AND a.`fld_question_type_id`='4' AND w.fld_profile_id='2' AND a.fld_delstatus='0' AND b.fld_delstatus='0' ".$sqry."");
                    }
                    else{
                        $qry = $ObjDB->QueryObject("SELECT DISTINCT(a.fld_id) AS qusid, a.fld_question AS qusname, a.fld_lesson_id AS lid, 
                                                  c.fld_question_type AS qustype, a.fld_created_by FROM `itc_question_details` AS a, 
                                                  `itc_ipl_master` AS b, `itc_question_type` AS c WHERE c.`fld_id`=a.`fld_question_type_id` 
                                                  AND a.`fld_question_type_id`='4' AND a.fld_delstatus='0' AND b.fld_delstatus='0' ".$sqry."");
                    }
                ?>
                <div style="max-height:400px;width:100%;" id="tablecontents2" >
                	<table style="margin-bottom:0px;" class='table table-hover table-striped table-bordered bordertopradiusremove'>
                    	<tbody>
                        	<?php
							if($qry->num_rows > 0){
								$i=1;
								while($row=$qry->fetch_assoc()){
								extract($row);
								if($fld_created_by==$uid OR $sessmasterprfid == '2')
								{
									$path = "btntest-testassign-qusactions"; 
									$pathname = $qusid.",qbank,edit";
								}
								else
								{
									$path = "btntest-testassign-review";
									$pathname = "0_".$qusid."_0";
								}
								?>	
								<tr class="mainBtn" id="<?php echo $path;?>" name="<?php echo $pathname;?>">
									<td width="10%" style="text-align:center;" id="que">
									  <?php echo $i; ?>
									</td>         	
									<td width="65%">
										<?php echo strip_tags($qusname);?>
									</td>
									<td width="25%" class='centerText'>
										<?php echo $qustype; ?>
									</td>                                                              
								</tr>
							 <?php
								 $i++;
								} // while ends
							} // if ends
						}
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
