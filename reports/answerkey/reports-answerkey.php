<?php
@include("sessioncheck.php");
$date=date("Y-m-d");
$sqry='';
?>
<section data-type='#reports-answerkey' id='reports-answerkey'>
	<script type="text/javascript" charset="utf-8">	
		$.getScript('reports/answerkey/reports-answerkey.js');
	</script>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Answer Key</p>
				<!--<p class="dialogSubTitleLight">Customize your report below, then press View Report.</p>-->
                                <p class="dialogSubTitleLight">Select the specific assessment you wish to view, then click "View Report".</p>
            </div>
        </div>
        
        <div class='row formBase rowspacer'>
            <div class='eleven columns centered insideForm'>
            	<?php
                //if($sessmasterprfid!=2 and $sessmasterprfid!=3 and $sessmasterprfid!=6) { ?>
            	<div class="row">
                    <div class='six columns'>   
                        Assessment
                        <dl class='field row'>
                            <div class="selectbox">
                                <input type="hidden" name="assid" id="assid" value="">
                                <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                                    <span class="selectbox-option input-medium" data-option="" style="width:97%">Select Assessment</span>
                                    <b class="caret1"></b>
                                </a>
                                <div class="selectbox-options">
                                    <input type="text" class="selectbox-filter" placeholder="Search Assessment">
                                    <ul role="options" style="width:100%">
                                        <?php 
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
                                                <li><a tabindex="-1" href="#" data-option="<?php echo $testid;?>" onClick="$('#viewreport').show();"><?php echo $testname; ?></a></li>
                                                <?php
                                            }
                                        }?>      
                                    </ul>
                                </div>
                            </div> 
                        </dl>
                    </div>
                    <?php //}?>
                    
                </div>
                
                <div class='row rowspacer' id="viewreport" style="display:none">
                    <input class="darkButton" type="button" id="btnstep" style="width:200px; height:42px; float:right;" value="View Report" onClick="fn_viewreport(2);" />
                </div>
            </div>
        </div>
    </div>
</section>
<?php
	@include("footer.php");