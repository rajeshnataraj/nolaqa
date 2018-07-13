<?php
@include("sessioncheck.php");
 error_reporting(E_ALL);
  ini_set('display_errors', '1');
?>
<script language="JavaScript">
$('body').css({'overflow': ''}); 
	$('body').removeAttr("style");
	$('.remarkContainer').remove();
</script>
<?php

$menuid= isset($method['id']) ? $method['id'] : '';	
$sid = isset($method['sid']) ? $method['sid'] : '0';
$date=date("Y-m-d");
$sqry='';
if($sid!=0){
	$sid = explode(',',$sid);
	for($i=0;$i<sizeof($sid);$i++){
		$ids = explode('_',$sid[$i]);
		if($ids[1]=='test'){
			$sqry.= " and a.fld_id =".$ids[0];
		}
		else{
			$itemqry = $ObjDB->QueryObject("SELECT fld_item_id FROM itc_main_tag_mapping 
			                                WHERE fld_tag_id='".$sid[$i]."' AND fld_access='1' 
											AND fld_tag_type='20'");
			$sqry.= " AND (";
			$j=1;
			while($itemres = $itemqry->fetch_assoc()){
				extract($itemres);
				if($j==$itemqry->num_rows){
					$sqry.=" a.fld_id=".$fld_item_id.")";
				}
				else{
					$sqry.=" a.fld_id=".$fld_item_id." or ";
				}
				$j++;
			} // while ends
		} // nested if ends 
	}// for ends
}// if ends
	
?>

<section data-type='2home' id='test'>
	<script type="text/javascript" charset="utf-8">		
		$.getScript("test/testassign/test-testassign-quscreation.js");	
		$.getScript("test/testassign/test-testassign-addquestion.js");	
		$.getScript("test/testassign/test-testassign-addstudents.js");
		$.getScript("test/testassign/test-testassign-addtest.js");
		$.getScript("test/testassign/test-testassign-newtest.js");	
		
		$(function(){				
			var t4 = new $.TextboxList('#form_tags_tests', {
				startEditableBit: false,
				inBetweenEditableBits: false,
				plugins: {
					autocomplete: {
						onlyFromValues:true,
						queryRemote: true,
						remote: { url: 'autocomplete.php', extraParams: "oper=search&tag_type=20&test=1" },
						placeholder: ''
					}
				},
				bitsOptions:{editable:{addKeys: [188]}}													
			});																	
			
			t4.addEvent('bitAdd',function(bit) {
				fn_loadtests();
			});
			
			t4.addEvent('bitRemove',function(bit) {
				fn_loadtests();
			});					
				
		});	
		function fn_loadtests(){
			var sid = $('#form_tags_tests').val();
			$("#testtags").load("test/test.php #testtags > *",{'sid':sid});
			removesections('#test');
		}
	</script>

    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="darkTitle">Assessment</p>
                <p class="darkSubTitle">&nbsp;</p>
            </div>
        </div>
        
        <div class='row'>
            <div class='twelve columns'>
	            <!--<p class="filterLightTitle">To filter this list, search by Tag Name and Assessment Name.</p>-->
                    <p class="filterLightTitle">To search for a specific assessment, search by name, Tag name, or browse through the list of assessments below.</p>
                <div class="tag_well">
                    <input type="text" name="test3" value="" id="form_tags_tests" />
                </div>
            </div>
    	</div>
        
        <div id="testtags" class='rowspacer'>
            <div class='row buttons'>
                <a class='skip btn mainBtn' href='#test-testassign' id='btntest-testassign-steps' name='0,1'>
                    <div class="icon-synergy-add-dark"></div>
                    <div class='onBtn'>New Assessment</div>
                </a>
                
                <a class='skip btn mainBtn' href='#test-testassign' id='btntest-testassign-testenginequestion' name='0,1'>
                    <div class="icon-synergy-questions"></div>
                    <div class='onBtn'>Question <br/> Bank</div>
                </a>
				
				<!-- New Import excel sheet code created by chandra start line -->
				<?php
				if($sessmasterprfid == 2)
				{?>
					<a class='skip btn mainBtn' href='#test-testassign' id='btntest-testassign-importexcel' name='<?php echo $id[0];?>'>
						<div class='icon-synergy-add-user'></div>
						<div class='onBtn'>Import Questions</div>
					</a>
				<?php
				}?>
				<!-- New Import excel sheet code end line -->
				
                <?php 
                /* teacher level assessment assign code Starts */
                
                if($sessmasterprfid == 9 OR $sessmasterprfid == 8)
                {
                ?>
                    <a class='skip btn mainBtn' href='#test-testassign' id='btntest-testassign-testteacher' name='0,1'>
                        <div class="icon-synergy-add-dark"></div>
                        <div class='onBtn tooltip' original-title="Add an Assessment from other" >Add an Assessment...</div>
                    </a>
                    
                <?php
                }
                        if($sessmasterprfid == 5 OR $sessmasterprfid == 7 OR $sessmasterprfid == 8 || $sessmasterprfid == 9)
                        {
							
                    $openresponse=$ObjDB->SelectSingleValue("SELECT count(a.fld_open_flag) FROM itc_test_student_answer_track as a
                                                                    LEFT JOIN itc_test_questionassign as b on b.fld_question_id=a.fld_question_id
                                                                    where a.fld_answer_type_id='15' AND a.fld_open_flag='0' AND a.fld_delstatus='0' AND b.fld_delstatus='0'");  
                       ?>
                    <a class='skip btn mainBtn <?php if($openresponse>0){ ?>response<?php } ?>' href='#test-testassign' id='btntest-testassign-testopenquestion' name='0,1'><!-- response -->
                    <div class="icon-synergy-openquestions"></div>
                    <div class='onBtn'>Open <br/> Response</div>
                </a>
                
                <?php
                        }
                        
				if($sessmasterprfid == 2 or $sessmasterprfid == 3){ 
                                        $qry = "SELECT a.fld_test_name AS testname, fn_shortname(a.fld_test_name,1) AS shortname,a.fld_otherteach_profile_id AS otherteachid, a.fld_created_by AS createbyid,
					               a.fld_id AS testid, a.fld_step_id AS stepid, a.fld_flag AS flag FROM itc_test_master AS a 
								   LEFT JOIN itc_user_master AS b ON a.fld_created_by = b.fld_id WHERE b.fld_profile_id ='2' 
								   AND a.fld_delstatus='0' ".$sqry." GROUP BY a.fld_id  
								   ORDER BY testname";
				}
				else if($sessmasterprfid == 5){
                                
					$qry = "SELECT DISTINCT(a.fld_id) AS testid, fn_shortname(a.fld_test_name,1) AS shortname,a.fld_otherteach_profile_id AS otherteachid, a.fld_test_name AS testname,
					                      a.fld_created_by AS createbyid, a.fld_profile_id AS profileid, a.fld_step_id AS stepid ,a.fld_flag AS flag 
							FROM `itc_test_master` AS a 
					        LEFT JOIN `itc_license_assessment_mapping`AS b ON a.fld_id=b.fld_assessment_id 
							LEFT JOIN `itc_license_track` AS c ON b.fld_license_id=c.fld_license_id WHERE c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."' AND a.fld_delstatus='0' AND c.fld_delstatus='0' AND c.fld_user_id='".$indid."'  and b.fld_access='1' ".$sqry." 
							UNION ALL SELECT a.fld_id AS testid,fn_shortname(a.fld_test_name,1) AS shortname,a.fld_otherteach_profile_id AS otherteachid,a.fld_test_name AS testname, 
										   a.fld_created_by AS createbyid, a.fld_profile_id AS profileid, a.fld_step_id AS stepid,a.fld_flag AS flag FROM `itc_test_master` AS a 
							WHERE a.fld_created_by='".$uid."' and a.fld_delstatus='0' ".$sqry."ORDER BY profileid , testname";
				}
				else if($sessmasterprfid == 6){
                                    
                                  
					$qry = "SELECT DISTINCT(a.fld_id) AS testid, fn_shortname(a.fld_test_name,1) AS shortname,a.fld_otherteach_profile_id AS otherteachid,
					               a.fld_test_name AS testname, a.fld_created_by AS createbyid, a.fld_profile_id AS profileid, a.fld_step_id AS stepid ,a.fld_flag AS flag 
							FROM `itc_test_master` AS a 
							LEFT JOIN `itc_license_assessment_mapping`AS b ON a.fld_id=b.fld_assessment_id 
							LEFT JOIN `itc_license_track` AS c ON b.fld_license_id=c.fld_license_id 
							WHERE c.fld_district_id='".$sendistid."' AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."' AND a.fld_delstatus='0' AND c.fld_delstatus='0' AND c.fld_user_id='".$indid."'  and b.fld_access='1' ".$sqry." 
							UNION ALL SELECT a.fld_id AS testid,fn_shortname(a.fld_test_name,1) AS shortname,a.fld_otherteach_profile_id AS otherteachid,a.fld_test_name AS testname, 
							                 a.fld_created_by AS createbyid, a.fld_profile_id AS profileid, a.fld_step_id AS stepid,a.fld_flag AS flag FROM `itc_test_master` AS a 
							WHERE a.fld_created_by='".$uid."' and a.fld_delstatus='0' ".$sqry." ORDER BY profileid , testname";
				}
				else if($sessmasterprfid == 7 and $sendistid !='0'){
                                    
                                    
                                        $districtid=$ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_created_by) FROM itc_user_master WHERE fld_id='".$uid."'
                                                                                AND fld_school_id = '".$senshlid."'  AND fld_district_id='".$sendistid."' AND fld_delstatus = '0'  AND fld_profile_id = '7'");
                                         
					$qry = "SELECT DISTINCT(a.fld_id) AS testid,fn_shortname(a.fld_test_name,1) AS shortname,a.fld_otherteach_profile_id AS otherteachid, a.fld_test_name AS testname, a.fld_created_by AS createbyid, a.fld_profile_id AS profileid,
                                                        a.fld_step_id AS stepid, a.fld_flag AS flag FROM itc_test_master AS a 
                                                        LEFT JOIN `itc_license_assessment_mapping`AS b ON a.fld_id=b.fld_assessment_id 
							LEFT JOIN `itc_license_track` AS c ON b.fld_license_id=c.fld_license_id WHERE c.fld_school_id='".$senshlid."' AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."' AND a.fld_delstatus='0' AND c.fld_delstatus='0' AND c.fld_user_id='".$indid."'  and b.fld_access='1' ".$sqry." 
                                                        
                                                        UNION ALL
                                                        
                                                SELECT DISTINCT(a.fld_id) AS testid, fn_shortname(a.fld_test_name, 1) AS shortname,a.fld_otherteach_profile_id AS otherteachid, a.fld_test_name AS testname,
                                                        a.fld_created_by AS createbyid, a.fld_profile_id AS profileid, a.fld_step_id AS stepid, a.fld_flag AS flag FROM `itc_test_master` AS a
                                                        LEFT JOIN `itc_test_school_mapping` AS b ON a.fld_id = b.fld_test_id
                                                        LEFT JOIN itc_school_master AS c ON b.fld_school_id=c.fld_id 
                                                        WHERE b.fld_school_id = '".$senshlid."' AND b.fld_flag = '1' ".$sqry."
                                                            
                                                        UNION ALL

                                                SELECT a.fld_id AS testid,fn_shortname(a.fld_test_name,1) AS shortname,a.fld_otherteach_profile_id AS otherteachid,a.fld_test_name AS testname, 
							a.fld_created_by AS createbyid, a.fld_profile_id AS profileid, a.fld_step_id AS stepid,a.fld_flag AS flag FROM `itc_test_master` AS a
                                                        WHERE a.fld_created_by='".$uid."' and a.fld_delstatus='0' ".$sqry." 
                                        
                                                        UNION ALL
                                                        
                                                SELECT a.fld_id AS testid,fn_shortname(a.fld_test_name,1) AS shortname,a.fld_otherteach_profile_id AS otherteachid,a.fld_test_name AS testname, 
                                                        a.fld_created_by AS createbyid, a.fld_profile_id AS profileid, a.fld_step_id AS stepid,a.fld_flag AS flag FROM `itc_test_master` AS a 
                                                        WHERE a.fld_school_id IN ('".$senshlid."', 0) AND a.fld_delstatus = '0'  AND a.fld_created_by IN (".$districtid.")   AND a.fld_delstatus = '0' ".$sqry."
                                                        ORDER BY profileid , testname";
				}
				else if($sessmasterprfid == 7 and $sendistid =='0'){
					$qry = "SELECT DISTINCT(a.fld_id) AS testid,fn_shortname(a.fld_test_name,1) AS shortname,a.fld_otherteach_profile_id AS otherteachid, a.fld_test_name AS testname, a.fld_created_by AS createbyid, a.fld_profile_id AS profileid,
							a.fld_step_id AS stepid, a.fld_flag AS flag FROM itc_test_master AS a 
							LEFT JOIN `itc_license_assessment_mapping`AS b ON a.fld_id=b.fld_assessment_id 
							LEFT JOIN `itc_license_track` AS c ON b.fld_license_id=c.fld_license_id WHERE c.fld_school_id='".$senshlid."' AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."' AND a.fld_delstatus='0' AND c.fld_delstatus='0' AND c.fld_user_id='".$indid."'  and b.fld_access='1' ".$sqry."
							
							UNION ALL 

							SELECT a.fld_id AS testid,fn_shortname(a.fld_test_name,1) AS shortname,a.fld_otherteach_profile_id AS otherteachid,a.fld_test_name AS testname, 
							a.fld_created_by AS createbyid, a.fld_profile_id AS profileid, a.fld_step_id AS stepid,a.fld_flag AS flag FROM `itc_test_master` AS a 
							WHERE a.fld_created_by='".$uid."' and a.fld_delstatus='0' ".$sqry." ORDER BY profileid , testname";
                                        
                                        
				}
				else if($sessmasterprfid == 9 and $sendistid =='0' and $senshlid =='0')
				{                                   
					$qry = "SELECT DISTINCT(a.fld_id) AS testid,fn_shortname(a.fld_test_name,1) AS shortname,a.fld_otherteach_profile_id AS otherteachid, a.fld_test_name AS testname, a.fld_created_by AS createbyid, a.fld_profile_id AS profileid,
                                                        a.fld_step_id AS stepid, a.fld_flag AS flag FROM itc_test_master AS a 
                                                        LEFT JOIN `itc_license_assessment_mapping`AS b ON a.fld_id=b.fld_assessment_id 
							LEFT JOIN `itc_license_track` AS c ON b.fld_license_id=c.fld_license_id 
							WHERE AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."' AND a.fld_delstatus='0' AND c.fld_delstatus='0' AND c.fld_user_id='".$indid."'  and b.fld_access='1' ".$sqry."
                                                            
                                                        UNION ALL 
                                                        
							SELECT a.fld_id AS testid,fn_shortname(a.fld_test_name,1) AS shortname,a.fld_otherteach_profile_id AS otherteachid,a.fld_test_name AS testname, 
							a.fld_created_by AS createbyid, a.fld_profile_id AS profileid, a.fld_step_id AS stepid,a.fld_flag AS flag FROM `itc_test_master` AS a 
							WHERE a.fld_created_by='".$uid."' and a.fld_delstatus='0' ".$sqry." ORDER BY profileid , testname";
				}
				else {
				   $districtid=$ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_created_by) FROM itc_user_master WHERE fld_school_id = '".$senshlid."'  AND  fld_district_id='".$sendistid."' AND fld_delstatus = '0'  AND fld_profile_id = '7'");//fld_created_by NOT IN(2) AND
                                   if($districtid == '')
                                       $districtid=0;
				  // error_reporting(E_ALL);
				 //  ini_set('display_errors', '1');

					if($districtid=='2')
					{

							$qry = "SELECT DISTINCT(a.fld_id) AS testid,fn_shortname(a.fld_test_name,1) AS shortname,a.fld_otherteach_profile_id AS otherteachid, a.fld_test_name AS testname, a.fld_created_by AS createbyid, a.fld_profile_id AS profileid,
																			a.fld_step_id AS stepid, a.fld_flag AS flag FROM itc_test_master AS a 
																		   LEFT JOIN `itc_license_assessment_mapping` AS b ON a.fld_id=b.fld_assessment_id 
																		   LEFT JOIN `itc_license_track` AS c ON b.fld_license_id=c.fld_license_id WHERE c.fld_school_id='".$senshlid."' AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."' AND a.fld_delstatus='0' AND c.fld_delstatus='0' AND c.fld_user_id='".$indid."' and b.fld_access='1' ".$sqry."

										UNION ALL

								SELECT a.fld_id AS testid,fn_shortname(a.fld_test_name,1) AS shortname,a.fld_otherteach_profile_id AS otherteachid,a.fld_test_name AS testname, 
										a.fld_created_by AS createbyid, a.fld_profile_id AS profileid, a.fld_step_id AS stepid,a.fld_flag AS flag FROM `itc_test_master` AS a 
										WHERE a.fld_created_by='".$uid."' and a.fld_delstatus='0' 
										".$sqry."  ORDER BY profileid , testname";

					}
					else
					{

					$qry = "SELECT DISTINCT(a.fld_id) AS testid,fn_shortname(a.fld_test_name,1) AS shortname,a.fld_otherteach_profile_id AS otherteachid, a.fld_test_name AS testname, a.fld_created_by AS createbyid, a.fld_profile_id AS profileid,
																			a.fld_step_id AS stepid, a.fld_flag AS flag FROM itc_test_master AS a 
																		   LEFT JOIN `itc_license_assessment_mapping` AS b ON a.fld_id=b.fld_assessment_id 
																		   LEFT JOIN `itc_license_track` AS c ON b.fld_license_id=c.fld_license_id WHERE c.fld_school_id='".$senshlid."' AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."' AND a.fld_delstatus='0' AND c.fld_delstatus='0' AND c.fld_user_id='".$indid."'  and b.fld_access='1' ".$sqry."

									UNION ALL 

							SELECT a.fld_id AS testid,fn_shortname(a.fld_test_name,1) AS shortname,a.fld_otherteach_profile_id AS otherteachid,a.fld_test_name AS testname, 
									a.fld_created_by AS createbyid, a.fld_profile_id AS profileid, a.fld_step_id AS stepid,a.fld_flag AS flag FROM `itc_test_master` AS a 
									WHERE a.fld_school_id IN ('".$senshlid."', 0) AND a.fld_delstatus = '0'  AND a.fld_created_by IN (".$districtid.")   AND a.fld_delstatus = '0' ".$sqry."

									UNION ALL

							SELECT a.fld_id AS testid,fn_shortname(a.fld_test_name,1) AS shortname,a.fld_otherteach_profile_id AS otherteachid,a.fld_test_name AS testname, 
									a.fld_created_by AS createbyid, a.fld_profile_id AS profileid, a.fld_step_id AS stepid,a.fld_flag AS flag FROM `itc_test_master` AS a 
									WHERE a.fld_created_by='".$uid."' and a.fld_delstatus='0' 
									".$sqry."  ORDER BY profileid , testname";
					}
				}
				$qrytest = $ObjDB->QueryObject($qry);
                if($qrytest->num_rows>0){
                    while($rowtest = $qrytest->fetch_assoc())
                    {
                        extract($rowtest);                    
                        if($flag==1)
                            $stepid=1;
                        ?>
                         <?php $checkcreatedby=$ObjDB->SelectSingleValue("SELECT fld_profile_id FROM itc_user_master WHERE fld_id='".$createbyid."'");?>
                        
                        <a class='skip btn mainBtn <?php if(($checkcreatedby==2 || $checkcreatedby==3) && $sessmasterprfid!=2){?>pit<?php } if($sessmasterprfid == 7 AND $sendistid !='0' AND $checkcreatedby==6){?>dis<?php } if($sessmasterprfid == 9 and $sendistid !='0' and $senshlid !='0' AND $checkcreatedby==6){?>dis<?php } if($otherteachid=='2'){?>sch<?php } if($otherteachid=='3'){?>teacher<?php }?>' href='#test-testassign' id='btntest-testassign-actions' name="<?php echo $testid.",".$stepid.",".$testname.",".$flag.",".$createbyid;?>">
                            <div class="icon-synergy-tests"></div>
                            <div class='onBtn tooltip' original-title="<?php echo $testname; ?>"><?php echo $shortname;?></div>
                        </a>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
</section>
<?php
	@include("footer.php");