 <?php 
 error_reporting(0);
	@include("sessioncheck.php");

	$date = date("Y-m-d H:i:s");
	$oper = isset($method['oper']) ? $method['oper'] : '';
	
	/*--- Load document dropdown ---*/
	if($oper=="showdocuments" and $oper != " " )
	{
		
		
		$stid = isset($method['stid']) ? $method['stid'] : '';
		$rptid = isset($method['rptid']) ? $method['rptid'] : '';                      
                
		$guiddb = $ObjDB->SelectSingleValue("SELECT fld_doc_id FROM itc_bestfit_rpt_doc_mapping WHERE fld_best_id='".$rptid."'");
                
		$docqry = $ObjDB->QueryObject("SELECT a.fld_doc_title AS documenttitle,fn_shortname(a.fld_doc_title,1) as shortname, a.fld_doc_guid AS docguid, b.fld_sub_title AS subjectname,fn_shortname(b.fld_sub_title,1) as shortsubjname, b.fld_sub_year AS year, b.fld_sub_guid AS guid
										FROM itc_correlation_documents a
										LEFT JOIN itc_correlation_doc_subject b ON a.fld_id=b.fld_doc_id
										WHERE  a.fld_authority_id IN('".$stid."','52')
 AND b.fld_sub_guid NOT IN(SELECT fld_doc_id from itc_bestfit_rpt_doc_mapping where fld_best_id='".$rptid."' and fld_flag='1') ");
               	
                $docqrysec = $ObjDB->QueryObject("SELECT a.fld_doc_title AS documenttitle,fn_shortname(a.fld_doc_title,1) as shortname, a.fld_doc_guid AS docguid, b.fld_sub_title AS subjectname,fn_shortname(b.fld_sub_title,1) as shortsubjname, b.fld_sub_year AS year, b.fld_sub_guid AS guid
										FROM itc_correlation_documents a
										LEFT JOIN itc_correlation_doc_subject b ON a.fld_id=b.fld_doc_id
										WHERE  a.fld_authority_id IN('".$stid."','52')
 AND b.fld_sub_guid IN(SELECT fld_doc_id from itc_bestfit_rpt_doc_mapping where fld_best_id='".$rptid."' and fld_flag='1') ");
		
		
		
		
		
		?>

        <script type="text/javascript" language="javascript">
			$(function() {
				$('#testrailvisible11').slimscroll({
					width: '410px',
					height:'366px',
					size: '7px',
                                        alwaysVisible: true,
					railVisible: true,
					allowPageScroll: false,
					railColor: '#F4F4F4',
					opacity: 1,
					color: '#d9d9d9',
                                         wheelStep: 1,
				
				});
				
				$('#testrailvisible12').slimscroll({
					width: '410px',
					height:'366px',
					size: '7px',
                                        alwaysVisible: true,
					railVisible: true,
					allowPageScroll: false,
					railColor: '#F4F4F4',
					opacity: 1,
					color: '#d9d9d9',
                                         wheelStep: 1,
				});			
				$("#list5").sortable({
					connectWith: ".droptrue3",
					dropOnEmpty: true,
					items: "div[class='draglinkleft']",
					receive: function(event, ui) {
						$("div[class=draglinkright]").each(function(){ 
							if($(this).parent().attr('id')=='list5'){
								
								fn_movealllistitems('list5','list6',1,$(this).children(":first").attr('id'));
								
							}
						});
					}
				});			
				$("#list6" ).sortable({
					connectWith: ".droptrue3",
					dropOnEmpty: true,
					receive: function(event, ui) {
						$("div[class=draglinkleft]").each(function(){ 						
							if($(this).parent().attr('id')=='list6'){								
								fn_movealllistitems('list5','list6',1,$(this).children(":first").attr('id'));
								
							}
						});
					}
				});
			}); 
		</script>
              <div class='six columns'>
            <div class="dragndropcol">
                <div class="dragtitle">Documents</div>
                <div class="dragWell" id="testrailvisible11" >
                    <div id="list5" class="dragleftinner droptrue3">
                    	<div class="draglinkleftSearch" id="s_list5" >
                           <dl class='field row'>
                                <dt class='text'>
                                    <input placeholder='Search' type='text' id="list_5_search" name="list_5_search" onKeyUp="search_list(this,'#list5');" />
                                </dt>
                            </dl>
                        </div>
                        <?php
							if($docqry->num_rows > 0){ 
							while($docrow = $docqry->fetch_assoc()){
							extract($docrow);
							$stddocs = $documenttitle." | ". $subjectname." (".$year.")";	
								
                        ?>
                            <div class="draglinkleft" id="list5_<?php echo $guid; ?>" >
                                <div class="dragItemLable tooltip" title="<?php echo $stddocs;?>" id="<?php echo $guid; ?>"><?php echo $shortname." | ". $shortsubjname." (".$year.")";?></div>
                                <div class="clickable" id="clck_<?php echo $guid; ?>" onclick="fn_movealllistitems('list5','list6',1,'<?php echo $guid; ?>');"></div>
                            </div>
                      	<?php
								
							}
						}
                        ?>
                    </div>
                </div>
                <div class="dragAllLink" onclick="fn_movealllistitems('list5','list6',0);" style="cursor: pointer;cursor:hand;width: 110x;float: right;"  >add all documents</div>
            </div>
        </div> 
                <div class='six columns'>
            <div class="dragndropcol">
                <div class="dragtitle">Selected documents<span class="fldreq">*</span></div>
                <div class="dragWell" id="testrailvisible12">
                    <div id="list6" class="dragleftinner droptrue3">
                    	
                        <?php
                        	if($docqrysec->num_rows > 0){ 
							while($docrowsec = $docqrysec->fetch_assoc()){
							extract($docrowsec);
							$stddocs = $documenttitle." | ". $subjectname." (".$year.")";
								
                        ?>
                             <div class="draglinkright" id="list6_<?php echo $guid; ?>" >
                                <div class="dragItemLable tooltip" title="<?php echo $stddocs;?>" id="<?php echo $guid; ?>"><?php echo $shortname." | ". $shortsubjname." (".$year.")";?></div>
                                <div class="clickable" id="clck_<?php echo $guid; ?>" onclick="fn_movealllistitems('list5','list6',1,'<?php echo $guid; ?>');"></div>
                            </div>
                        <?php

							}
						}	
							
                        ?>
                    </div>	
                </div>
                <div class="dragAllLink" onclick="fn_movealllistitems('list6','list5',0);" style="cursor: pointer;cursor:hand;width: 160px;float: right; ">remove all documents</div>
            </div>
        </div>

       
		<?php
	}
	
	/*--- Load document dropdown ---*/
	if($oper=="showgrades" and $oper != " " )
	{
		$stid = isset($method['stid']) ? $method['stid'] : '';
		$stdid = isset($method['stdid']) ? $method['stdid'] : '';
		$rptid = isset($method['rptid']) ? $method['rptid'] : '';
                $dimflag='0';
		
		$stddocsdb = array();		
		
                $grdguidqry = $ObjDB->QueryObject("SELECT a.fld_guid AS grdguids, a.fld_grade AS grdnames,fn_shortname(a.fld_grade,1) as shortgrdname,fn_shortname(b.fld_sub_title,1) as shortsubjname,
										b.fld_sub_title AS subjectname,b.fld_sub_year AS year
										FROM itc_bestfit_rpt_std_grades AS a 
										LEFT JOIN itc_correlation_doc_subject AS b ON a.fld_sub_id=b.fld_id
										WHERE a.fld_rpt_data_id='".$rptid."' AND a.fld_delstatus='0'");
		if($grdguidqry->num_rows > 0){
			while($grdguidrow = $grdguidqry->fetch_assoc()){
				extract($grdguidrow);
				$stddocsdb[$grdguids] = $grdnames." | ". $subjectname." (".$year.")~".$shortgrdname." | ". $shortsubjname." (".$year.")";				
				
			}
		}
		
		
		$grdqry = $ObjDB->QueryObject("SELECT a.fld_grade_guid AS gguid, a.fld_grade_name AS gradename 
			,fn_shortname(a.fld_grade_name,1) as shortgrdname,fn_shortname(b.fld_sub_title,1) as shortsubjname,
                                                b.fld_sub_title AS subjectname,b.fld_sub_year AS year,a.fld_gradeout_flag as gradeoutflag
                                                FROM itc_correlation_grades as a 
                                                LEFT JOIN itc_correlation_doc_subject AS b ON a.fld_sub_id=b.fld_id
                                                WHERE b.fld_sub_guid IN (".$stdid.")");


		$stddocs = array();
		if($grdqry->num_rows > 0){
			while($grdrow = $grdqry->fetch_assoc()){
				extract($grdrow);
				$stddocs[$gguid] = $gradename." | ". $subjectname." (".$year.")~".$shortgrdname." | ". $shortsubjname." (".$year.")~".$gradeoutflag;
			}
		}
		
		?>
        <script type="text/javascript" language="javascript">
			$(function() {
				$('#testrailvisible13').slimscroll({
					width: '410px',
					height:'360px',
					size: '7px',
                                        alwaysVisible: true,
					railVisible: true,
					allowPageScroll: false,
					railColor: '#F4F4F4',
					opacity: 1,
					color: '#d9d9d9',
                                         wheelStep: 1,
				
				});
				
				$('#testrailvisible14').slimscroll({
					width: '410px',
					height:'360px',
					size: '7px',
                                        alwaysVisible: true,
					railVisible: true,
					allowPageScroll: false,
					railColor: '#F4F4F4',
					opacity: 1,
					color: '#d9d9d9',
                                         wheelStep: 1,
				});
				$("#list7").sortable({
					connectWith: ".droptrue3",
					dropOnEmpty: true,
					items: "div[class='draglinkleft']",
					receive: function(event, ui) {
						$("div[class=draglinkright]").each(function(){ 
							if($(this).parent().attr('id')=='list7'){
								
								fn_movealllistitems('list7','list8',1,$(this).children(":first").attr('id'));
								fn_validategrade();
							}
						});
					}
				});
				$("#list8" ).sortable({
					connectWith: ".droptrue3",
					dropOnEmpty: true,
					receive: function(event, ui) {
						$("div[class=draglinkleft]").each(function(){ 						
							if($(this).parent().attr('id')=='list8'){								
								fn_movealllistitems('list7','list8',1,$(this).children(":first").attr('id'));
								fn_validategrade();
							}
						});
					}
				});
			}); 
		</script>
        <div class='six columns'>
            <div class="dragndropcol">
                <div class="dragtitle">Grades</div>
                <div class="dragWell" id="testrailvisible13" >
                    <div id="list7" class="dragleftinner droptrue3">
                    	<div class="draglinkleftSearch" id="s_list7" >
                           <dl class='field row'>
                                <dt class='text'>
                                    <input placeholder='Search' type='text' id="list_7_search" name="list_7_search" onKeyUp="search_list(this,'#list7');" />
                                </dt>
                            </dl>
                        </div>
                        <?php
							foreach($stddocs as $key => $val) {
								if(!array_key_exists($key,$stddocsdb)) {		
                                                             $val=explode("~",$val);
                                                             ?>
                            <div class="draglinkleft<?php if($val[2]==1){ $dimflag='1'; echo " dim";}?>" id="list7_<?php echo $key; ?>" >
                                <div class="dragItemLable tooltip" id="<?php echo $key; ?>" title="<?php echo $val[0];?>"><?php echo $val[1];?></div>
                                <div class="clickable" id="clck_<?php echo $key; ?>" onclick="fn_movealllistitems('list7','list8',1,'<?php echo $key; ?>');fn_validategrade();"></div>
                            </div>
                      	<?php
								}
							}
                        ?>
                    </div>
                </div>
                <div class="dragAllLink<?php if($dimflag=='1'){ echo " dim";}?>" onclick="fn_movealllistitems('list7','list8',0);fn_validategrade();">add all grades</div>
            </div>
        </div>
        <div class='six columns'>
            <div class="dragndropcol">
                <div class="dragtitle">Selected Grades<span class="fldreq">*</span></div>
                <div class="dragWell" id="testrailvisible14">
                    <div id="list8" class="dragleftinner droptrue3">
                        <?php
                        	foreach($stddocs as $key => $val) {
								if(array_key_exists($key, $stddocsdb)) {
                                                                    $val=explode("~",$val);
                        ?>
                            <div class="draglinkright" id="list8_<?php echo $key; ?>" >
                                <div class="dragItemLable tooltip" id="<?php echo $key; ?>" title="<?php echo $val[0];?>"><?php echo $val[1];?></div>
                                <div class="clickable" id="clck_<?php echo $key; ?>" onclick="fn_movealllistitems('list7','list8',1,'<?php echo $key; ?>');fn_validategrade();"></div>
                            </div>
                        <?php
								}
							}
                        ?>
                    </div>	
                </div>
                <div class="dragAllLink" onclick="fn_movealllistitems('list8','list7',0);fn_validategrade();" style="cursor: pointer;cursor:hand;width: 135px;float: right;">remove all grades</div>
            </div>
        </div>
		<?php
	}
	
	/*--- Check Subject Name Duplication ---*/
	if($oper=="checkreportname" and $oper != " " )
	{
		$rptid = isset($method['rptid']) ? $method['rptid'] : '0';
		$rpttitle = isset($method['txtrpttitle']) ? fnEscapeCheck($method['txtrpttitle']) : '';
		
		$count = $ObjDB->SelectSingleValueInt("SELECT COUNT(*) 
                                                        FROM itc_bestfit_report_data 
                                                        WHERE MD5(LCASE(REPLACE(fld_title,' ','')))='".$rpttitle."' AND fld_delstatus=0 AND fld_id<>'".$rptid."' AND fld_created_by='".$uid."'");
		if($count == 0){ echo "true"; }	else { echo "false"; }
	}
	
	/*--- Save Step1 Correlation Basic and standard Info---*/
	if($oper=="savestep1" and $oper != " " )
	{
		$rptid = isset($method['rptid']) ? $method['rptid'] : 0;
		$rpttitle = isset($method['rpttitle']) ? $ObjDB->EscapeStrAll($method['rpttitle']) : '';
		$ownerid = isset($method['ownerid']) ? $method['ownerid'] : '';
		$prepfor = isset($method['prepfor']) ? $ObjDB->EscapeStrAll($method['prepfor']) : '';
		$prepon = isset($method['prepon']) ? $method['prepon'] : '';
                $state = isset($method['state']) ? $method['state'] : 0;
		$documentid = isset($method['documentid']) ? $method['documentid'] : 0;
		$gradeids = isset($method['gradeids']) ? $method['gradeids'] : 0;
		$gradename = isset($method['gradename']) ? $method['gradename'] : 0;
		$standardname = isset($method['standardname']) ? $method['standardname'] : 0;
		$rptsytle = isset($method['rptsytle']) ? $method['rptsytle'] : '';
		$sec = isset($method['sec']) ? $method['sec'] : '';
		$selectschool = isset($method['selectschool']) ? $method['selectschool'] : '';//changes	
                
                $gids = explode(",",$gradeids);
		$gnames = explode("~",$gradename);
		$secsep = explode(",",$sec);
                $documentid=explode(",",$documentid);
		
		$rptchk = $ObjDB->SelectSingleValueInt("SELECT COUNT(*) 
                                                        FROM itc_bestfit_report_data 
                                                        WHERE MD5(LCASE(REPLACE(fld_title,' ','')))='".fnEscapeCheck($rpttitle)."' AND fld_delstatus=0 AND fld_id<>'".$rptid."' AND fld_created_by='".$uid."'");
                
                $ObjDB->NonQuery("UPDATE itc_bestfit_rpt_doc_mapping
						 SET fld_flag=0,fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' 
						 WHERE fld_best_id='".$rptid ."'");                

		
		if($rptchk == 0) {	
			if($rptid == 0) {	
				$rptid =$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_bestfit_report_data (fld_title, fld_owner_id, fld_prepared_for, fld_prepared_on,fld_report_style, 
									fld_sec_std_add_summary, fld_sec_bench_add_summary, fld_sec_corr_by_std, fld_sec_corr_by_title, fld_sec_std_not_add,fld_sec_prod_description,
                                                                        fld_created_by, fld_created_date,fld_schoolid,fld_step_id) 
                                                                        VALUES ('".$rpttitle."','".$ownerid."','".$prepfor."','".$prepon."','".$rptsytle."','".$secsep[0]."','".$secsep[1]."','".$secsep[2]."',
									'".$secsep[3]."','".$secsep[4]."','".$secsep[5]."','".$uid."','".date("Y-m-d H:i:s")."','".$selectschool."','1')");
			}
			else {
				$ObjDB->NonQuery("UPDATE itc_bestfit_report_data SET fld_title='".$rpttitle."', fld_owner_id='".$ownerid."', fld_prepared_for='".$prepfor."', 
						      fld_prepared_on='".$prepon."',fld_report_style='".$rptsytle."', fld_sec_std_add_summary='".$secsep[0]."', 
                                                      fld_sec_bench_add_summary='".$secsep[1]."', fld_sec_corr_by_std='".$secsep[2]."', fld_sec_corr_by_title='".$secsep[3]."', 
                                                      fld_sec_std_not_add='".$secsep[4]."',fld_sec_prod_description='".$secsep[5]."',fld_schoolid='".$selectschool."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."',fld_step_id='1' 
						      WHERE fld_id='".$rptid."'");
                               
			}
                
                for($i=0;$i<sizeof($documentid);$i++)
		{       
			
                       
			$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
                                                            FROM itc_bestfit_rpt_doc_mapping 
                                                            WHERE fld_best_id='".$rptid."' AND fld_doc_id=".$documentid[$i]."");


			if($cnt==0)
			{

				

				$docqry = $ObjDB->QueryObject("SELECT a.fld_doc_title AS documenttitle,b.fld_sub_title AS subjectname, b.fld_sub_year AS year
										FROM itc_correlation_documents a
										LEFT JOIN itc_correlation_doc_subject b ON a.fld_id=b.fld_doc_id
										WHERE  a.fld_authority_id='".$state."' AND b.fld_sub_guid=".$documentid[$i]."");

				if($docqry->num_rows > 0)
				{ 
					$stddocs='';
					$docrow = $docqry->fetch_assoc();
					extract($docrow);
					$stddocs = $documenttitle." | ". $subjectname." (".$year.")";	
				
				}


				$ObjDB->NonQuery("INSERT INTO itc_bestfit_rpt_doc_mapping(fld_best_id,fld_doc_name, fld_doc_id,fld_std_body, fld_flag,fld_created_date,fld_created_by) 
																VALUES ('".$rptid."','".$stddocs."', ".$documentid[$i].",'".$state."','1','".date('Y-m-d H:i:s')."','".$uid."')");
                                
                                
			}
			else
			{
				$docqry= $ObjDB->QueryObject("SELECT a.fld_doc_title AS documenttitle,b.fld_sub_title AS subjectname, b.fld_sub_year AS year
										FROM itc_correlation_documents a
										LEFT JOIN itc_correlation_doc_subject b ON a.fld_id=b.fld_doc_id
										WHERE  a.fld_authority_id='".$state."' AND b.fld_sub_guid=".$documentid[$i]."");

				if($docqry->num_rows > 0)
				{ 
					$stddocs='';
					$docrow = $docqry->fetch_assoc();
					extract($docrow);
					$stddocs = $documenttitle." | ". $subjectname." (".$year.")";	
				
				}

				$ObjDB->NonQuery("UPDATE itc_bestfit_rpt_doc_mapping 
								SET fld_flag='1',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."',fld_doc_name='".$stddocs ."',fld_std_body='".$state."' 
								WHERE fld_best_id='".$rptid."'  AND fld_doc_id=".$documentid[$i]." AND fld_id='".$cnt."'");
			}
		}        
                        
                $ObjDB->NonQuery("UPDATE itc_bestfit_rpt_std_grades 
						SET fld_delstatus=1 
						WHERE fld_rpt_data_id='".$rptid."'");
                
		for($i=0;$i<sizeof($gids);$i++){				
			
			
												
			$gnam = $ObjDB->SelectSingleValue("SELECT fld_grade_name as name FROM itc_correlation_grades
												WHERE fld_grade_guid='".$gids[$i]."'");
			
			$gchk = $ObjDB->SelectSingleValueInt("SELECT COUNT(*) 
                                                                FROM itc_bestfit_rpt_std_grades 
                                                                WHERE fld_rpt_data_id='".$rptid."' AND fld_guid='".$gids[$i]."'");
			if($gchk == 0) {
				$ObjDB->NonQuery("INSERT INTO itc_bestfit_rpt_std_grades (fld_rpt_data_id,fld_guid,fld_grade) 
								VALUES ('".$rptid."','".$gids[$i]."','".$gnam."')");
			}
			else {
				$ObjDB->NonQuery("UPDATE itc_bestfit_rpt_std_grades 
								SET fld_delstatus=0 
								WHERE fld_rpt_data_id='".$rptid."' AND fld_guid='".$gids[$i]."'");
			}
		}
                for($i=0;$i<sizeof($documentid);$i++)
		{       
      

                        $grdqry = $ObjDB->QueryObject("SELECT fld_sub_id as subid,fld_grade_guid AS gguid, fld_grade_name AS gradename 
										FROM itc_correlation_grades
										WHERE fld_sub_id IN (SELECT fld_id FROM itc_correlation_doc_subject WHERE fld_sub_guid=".$documentid[$i].")");

                        if($grdqry->num_rows > 0){
						while($grdrow = $grdqry->fetch_assoc()){
							extract($grdrow);
							
							$ObjDB->NonQuery("UPDATE itc_bestfit_rpt_std_grades 
								SET fld_sub_id='".$subid."'
								WHERE fld_rpt_data_id='".$rptid."' AND fld_guid='".$gguid."'");

                }

                }
                
                }
			
			echo $rptid;
		}
		else {
                    
			echo "invalid";	
		}
                
          
	}
	
if($oper=="showproducts" and $oper != " " )
{ 
    $type = isset($method['type']) ? $method['type'] : 0;
    $rptid = isset($method['rptid']) ? $method['rptid'] : 0;
    $selectproducts = isset($method['selectproducts']) ? $method['selectproducts'] : 0;
    $selectproducts = explode(',',$selectproducts);
    $a='0';
    if($sessmasterprfid==2 or $sessmasterprfid==3 )
    { 

	  $qryipls="SELECT a.fld_id AS id, CONCAT(a.fld_ipl_name,' ',b.fld_version) AS nam,fn_shortname	(a.fld_ipl_name,2) AS shortname, 
				1 AS typ, a.fld_asset_id AS assetid
				FROM itc_ipl_master AS a
                              LEFT JOIN itc_ipl_version_track AS b ON b.fld_ipl_id=a.fld_id
				WHERE a.fld_access='1' AND a.fld_delstatus='0' AND b.fld_delstatus='0' AND b.fld_zip_type='1'";

	 $qryunits="SELECT a.fld_id AS id, a.fld_unit_name AS nam,fn_shortname(a.fld_unit_name,2) AS shortname, 
				2 AS typ, a.fld_asset_id as assetid
	  			FROM itc_unit_master as a
				WHERE a.fld_delstatus='0'";


	  $qrymodules="SELECT a.fld_id AS id, CONCAT(a.fld_module_name,' ',b.fld_version) AS nam,fn_shortname(a.fld_module_name,2) AS shortname,
 					3 AS typ, a.fld_asset_id AS assetid
                                      FROM itc_module_master AS a 
                                      LEFT JOIN itc_module_version_track AS b ON b.fld_mod_id=a.fld_id
					WHERE a.fld_delstatus='0' AND b.fld_delstatus='0'";

	  $qrymathmodules="SELECT a.fld_id AS id, CONCAT(a.fld_mathmodule_name,' ',b.fld_version) AS nam,fn_shortname(a.fld_mathmodule_name,2) AS shortname, 4 AS typ, 					a.fld_asset_id AS assetid 
                                            FROM itc_mathmodule_master AS a 
                                            LEFT JOIN itc_module_version_track AS b ON b.fld_mod_id=a.fld_module_id
						WHERE a.fld_delstatus='0' AND b.fld_delstatus='0'";
    }

	else if($sessmasterprfid==6){	//Lessons listed based on available licenses for a distict
		$qryipls ="SELECT c.fld_id AS id, CONCAT(c.fld_ipl_name,' ',d.fld_version) AS nam,fn_shortname(c.fld_ipl_name,2) AS shortname, 1 AS typ ,c.fld_asset_id AS assetid 
                                    FROM itc_license_track AS a 
                                    LEFT JOIN itc_license_cul_mapping AS b ON a.fld_license_id=b.fld_license_id 
                                    LEFT JOIN itc_ipl_master AS c ON b.`fld_lesson_id`=c.fld_id
                                    LEFT JOIN itc_ipl_version_track AS d ON d.fld_ipl_id=c.fld_id 
                                    WHERE a.fld_delstatus='0' AND b.fld_active='1' AND a.fld_district_id='".$sendistid."' 
                                            AND a.fld_start_date<=NOW() AND a.fld_end_date>=NOW() AND c.fld_delstatus='0' AND c.fld_access='1'
					AND d.fld_zip_type='1' AND d.fld_delstatus='0'  
                                    GROUP BY c.fld_id";

		$qryunits ="SELECT c.fld_id AS id, c.fld_unit_name AS nam,fn_shortname(c.fld_unit_name,2) AS shortname, 2 AS typ, c.fld_asset_id as assetid 
                                    FROM itc_license_track AS a 
                                    LEFT JOIN itc_license_cul_mapping AS b ON a.fld_license_id=b.fld_license_id 
                                    LEFT JOIN itc_unit_master AS c ON b.`fld_unit_id`=c.fld_id 
                                    WHERE a.fld_delstatus='0' AND b.fld_active='1' AND a.fld_district_id='".$sendistid."' AND a.fld_start_date<=NOW() 
					AND a.fld_end_date>=NOW() AND c.fld_delstatus='0' 
                                    GROUP BY c.fld_id";

		$qrymodules ="SELECT b.fld_module_id AS id, CONCAT(a.fld_module_name,' ',d.fld_version) AS nam,fn_shortname(a.fld_module_name,2) AS shortname, 3 AS typ , a.fld_asset_id AS assetid 
                                            FROM itc_module_master AS a 
                                            LEFT JOIN itc_license_mod_mapping AS b ON a.fld_id=b.fld_module_id 
                                            LEFT JOIN itc_license_track AS c ON b.fld_license_id=c.fld_license_id 
                                            LEFT JOIN itc_module_version_track AS d ON d.fld_mod_id=b.fld_module_id
                                            WHERE a.fld_delstatus='0' AND c.fld_district_id='".$sendistid."' AND c.fld_school_id='0' AND c.fld_delstatus='0' 
						AND b.fld_active='1' AND c.fld_start_date<=NOW() AND c.fld_end_date>=NOW() AND b.fld_type=1 AND d.fld_delstatus='0' 
                                            GROUP BY b.fld_module_id";

		$qrymathmodules ="SELECT b.fld_module_id AS id, CONCAT(a.fld_mathmodule_name,' ',d.fld_version) AS nam,fn_shortname(a.fld_mathmodule_name,2) AS shortname, 4 AS typ , a.fld_asset_id AS assetid 
                                                    FROM itc_mathmodule_master AS a 
                                                    LEFT JOIN itc_license_mod_mapping AS b ON a.fld_id=b.fld_module_id 
                                                    LEFT JOIN itc_license_track AS c ON b.fld_license_id=c.fld_license_id
                                                    LEFT JOIN itc_module_version_track AS d ON d.fld_mod_id=b.fld_module_id 
                                                        WHERE a.fld_delstatus='0' AND c.fld_district_id='".$sendistid."' AND c.fld_school_id='0' 
							AND c.fld_delstatus='0' AND d.fld_delstatus='0'
							AND b.fld_active='1' AND c.fld_start_date<=NOW() AND c.fld_end_date>=NOW() AND b.fld_type=2
                                                    GROUP BY b.fld_module_id";					
    }
    else{	//Lessons listed based on available licenses for a school or an individual user
		$qryipls ="SELECT c.fld_id AS id, CONCAT(c.fld_ipl_name,' ',d.fld_version) AS nam,fn_shortname(c.fld_ipl_name,2) AS shortname, 1 AS typ , c.fld_asset_id AS assetid 
					FROM itc_license_track AS a 
					LEFT JOIN itc_license_cul_mapping AS b ON a.fld_license_id=b.fld_license_id 
					LEFT JOIN itc_ipl_master AS c ON b.`fld_lesson_id`=c.fld_id 
					LEFT JOIN itc_ipl_version_track AS d ON d.fld_ipl_id=c.fld_id
					WHERE a.fld_delstatus='0' AND b.fld_active='1' AND a.fld_school_id='".$schoolid."' AND a.fld_user_id='".$indid."' 
						AND a.fld_start_date<=NOW() AND a.fld_end_date>=NOW() AND c.fld_delstatus='0' AND c.fld_access='1'
					AND d.fld_zip_type='1' AND d.fld_delstatus='0' 
					GROUP BY c.fld_id";	
		
		$qryunits ="SELECT c.fld_id AS id, c.fld_unit_name AS nam,fn_shortname(c.fld_unit_name,2) AS shortname, 2 AS typ , c.fld_asset_id as assetid 
					FROM itc_license_track AS a 
					LEFT JOIN itc_license_cul_mapping AS b ON a.fld_license_id=b.fld_license_id 
					LEFT JOIN itc_unit_master AS c ON b.`fld_unit_id`=c.fld_id 
					WHERE a.fld_delstatus='0' AND b.fld_active='1' AND a.fld_school_id='".$schoolid."' AND a.fld_user_id='".$indid."' 
					AND a.fld_start_date<=NOW() AND a.fld_end_date>=NOW() AND c.fld_delstatus='0'  
					GROUP BY c.fld_id";
		
		$qrymodules ="SELECT b.fld_module_id AS id, 
						CONCAT(a.fld_module_name,' ',d.fld_version) AS nam,fn_shortname(a.fld_module_name,2) AS shortname, 3 AS typ , a.fld_asset_id AS assetid 
						FROM itc_module_master AS a 
						LEFT JOIN itc_license_mod_mapping AS b ON a.fld_id=b.fld_module_id 
						LEFT JOIN itc_license_track AS c ON b.fld_license_id=c.fld_license_id
						LEFT JOIN itc_module_version_track AS d ON d.fld_mod_id=b.fld_module_id
						WHERE a.fld_delstatus='0' AND c.fld_school_id='".$schoolid."' AND c.fld_user_id='".$indid."' AND c.fld_delstatus='0' 
						AND b.fld_active='1' AND c.fld_start_date<=NOW() AND c.fld_end_date>=NOW() AND b.fld_type=1 AND d.fld_delstatus='0' 
						GROUP BY b.fld_module_id";
						
		$qrymathmodules ="SELECT b.fld_module_id AS id, CONCAT(a.fld_mathmodule_name,' ',d.fld_version) AS nam,fn_shortname(a.fld_mathmodule_name,2) AS shortname, 4 AS typ , a.fld_asset_id AS assetid 
							FROM itc_mathmodule_master AS a 
							LEFT JOIN itc_license_mod_mapping AS b ON a.fld_id=b.fld_module_id 
							LEFT JOIN itc_license_track AS c ON b.fld_license_id=c.fld_license_id 
							LEFT JOIN itc_module_version_track AS d ON d.fld_mod_id=b.fld_module_id
							WHERE a.fld_delstatus='0' AND c.fld_school_id='".$schoolid."' AND c.fld_user_id='".$indid."' 
							AND c.fld_delstatus='0' AND b.fld_active='1' AND c.fld_start_date<=NOW() AND c.fld_end_date>=NOW() AND b.fld_type=2 AND d.fld_delstatus='0' 
							GROUP BY b.fld_module_id";
	}
				
    if($type==0)
    {
           $qry=$qryipls." union all ".$qryunits." union all ".$qrymodules." union all ".$qrymathmodules." ORDER BY nam";
    }
    else if($type==1)
    {
             $qry=$qryipls." ORDER BY nam";
    }
    else if($type==2)
    {
             $qry=$qryunits." ORDER BY nam";
    }  
    else if($type==3)
    {
             $qry=$qrymodules." ORDER BY nam";
    }  
    else if($type==4)
    {
             $qry=$qrymathmodules." ORDER BY nam";
    }   
	  
    $productdetails=array();
    $productqry = $ObjDB->QueryObject($qry);
    if($productqry->num_rows > 0){
        $i=0;
        while($productqryrow = $productqry->fetch_assoc()){
            extract($productqryrow);
            if($assetid=='')
            {
                $assetid='MO.ENVM.3.0.0a';
            }
            $productdetails[]=array("id"=>$id,"nam"=>$nam,"shortname"=>$shortname,"type"=>$typ,"productid"=>$assetid);
            $i++;
           }
    }
    ?>
    <script type="text/javascript" language="javascript">
        $(function() {
            $('#testrailvisible13').slimscroll({
                width: '410px',
                height:'366px',
                size: '7px',
                alwaysVisible: true,
                railVisible: true,
                allowPageScroll: false,
                railColor: '#F4F4F4',
                opacity: 1,
                color: '#d9d9d9',
                 wheelStep: 1,
            });

           
            

            $("#list7").sortable({
                connectWith: ".droptrue3",
                dropOnEmpty: true,
                items: "div[class='draglinkleft']",
                receive: function(event, ui) {
                    $("div[class=draglinkright]").each(function(){ 
                        if($(this).parent().attr('id')=='list7'){                           
                            fn_movealllistitems('list7','list8',1,$(this).attr('id').replace('list8_',''));
                            fn_saveselect();
                            fn_validategrade();
                        }
                    });
                }
            });

        }); 
    </script>	  	
   
    <div class="dragndropcol">
        <div class="dragtitle">Products</div>
            <div class="dragWell" id="testrailvisible13" >
                <div id="list7" class="dragleftinner droptrue3">
                    <div class="draglinkleftSearch" id="s_list7" >
                        <dl class='field row'>
                            <dt class='text'>
                                <input placeholder='Search' type='text' id="list_7_search" name="list_7_search" onKeyUp="search_list(this,'#list7');" />
                            </dt>
                        </dl>
                    </div>
                    <?php
			$gnam = $ObjDB->QueryObject("SELECT fld_guid as guid FROM itc_bestfit_rpt_std_grades
			       WHERE fld_rpt_data_id='".$rptid."' AND fld_delstatus='0'");
			if($gnam->num_rows > 0){
				$sguids='';
				while($grdrow = $gnam->fetch_assoc()){
					extract($grdrow);
                       

				if($sguids=='')
				{
				$sguids="'".$guid."'";
				}
				else
				{
				$sguids.=","."'".$guid."'";
				}
					
				}
			$sguidsqry=" in (".$sguids.")";
			}
                        
                        
                        
                                
                                
                                
                                $qrylessonunselect=$ObjDB->QueryObject("SELECT b.fld_prd_id as asstid FROM itc_correlation_productsgradeout as a
                                                                LEFT JOIN itc_correlation_products as b ON b.fld_prd_asset_id = a.fld_productid
                                                                WHERE a.fld_standardguid$sguidsqry GROUP BY a.fld_productid");
                    $filter_greyout=array(); 
                    while($lessonunselect=$qrylessonunselect->fetch_assoc()){
                    extract($lessonunselect);
                    array_push($filter_greyout,$asstid);
                    } 
                       
                    for($i=0;$i<sizeof($productdetails);$i++) {
                        $bool=true;
                        for($j=0;$j<sizeof($selectproducts);$j++)
                        {
                           
                            $selctp=explode('_',$selectproducts[$j]);
                            
                            if($selctp[0] == $productdetails[$i]['id'] and $selctp[1] ==$productdetails[$i]['type'] )
                            {
                                $bool=false;
                            }
                        }
                        if($bool)
                        {
				$dimproduct = array_diff(array($productdetails[$i]['productid']),$filter_greyout);
                            ?>
                            <div name="<?php echo $productdetails[$i]['type']; ?>" class="draglinkleft<?php if(!empty($dimproduct)) { echo ' dim'; $a=1;}?>" id="list7_<?php echo $productdetails[$i]['id']."_".$productdetails[$i]['type']; ?>" >
                               <div class="dragItemLable tooltip" id="<?php echo  $productdetails[$i]['id']."~".$productdetails[$i]['type']."~".$productdetails[$i]['productid']; ?>" title="<?php echo $productdetails[$i]['nam']; ?>"><?php echo $productdetails[$i]['shortname'];?></div>
                                <div class="clickable" id="clck_<?php echo $productdetails[$i]['productid']; ?>" onclick="fn_movealllistitemsproducts('list7','list8',1,'<?php echo $productdetails[$i]['id']."_".$productdetails[$i]['type']; ?>');fn_saveselect();"></div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
                <div class="dragAllLink<?php if($a==1){ echo " dim";}?>" onclick="fn_movealllistitemsproducts('list7','list8',0);fn_saveselect();fn_validateproducts();" style="cursor: pointer;cursor:hand;width: 115px;float: right;" >add all products</div>
    </div>    
<?php 
}
/*
 * starts for selecting production by tag types
 */
if($oper=="showtagproducts" and $oper != " " )
{
    $rptid = isset($method['rptid']) ? $method['rptid'] : 0;
    $selecttagproducts = isset($method['selecttagproducts']) ? $method['selecttagproducts'] : 0;
    $selecttagproducts = explode(',',$selecttagproducts);
    $a = 0;

    
    if($sessmasterprfid==2 or $sessmasterprfid==3 )
    {     

        for($i=0;$i<sizeof($selecttagproducts);$i++) {
           
           $ptag_type =$ObjDB->QueryObject("SELECT fld_item_id as titleid,fld_tag_type as tagtype FROM itc_main_tag_mapping where fld_tag_id='".$selecttagproducts[$i]."' AND (fld_tag_type = '1' OR fld_tag_type = '23' OR fld_tag_type = '3' OR fld_tag_type = '4') AND fld_access = '1'");
             if($ptag_type->num_rows > 0){
                 static $ipl_cnt = 0;
                 static $unit_cnt = 0;
                 static $module_cnt = 0;
                 static $mathmod_cnt = 0;
                 
                while($rowqry = $ptag_type->fetch_assoc())
                {
                extract($rowqry);
                    if($tagtype == '1')
                    {
                       
                         $qryipls="SELECT a.fld_id AS id, CONCAT(a.fld_ipl_name,' ',b.fld_version) AS nam,fn_shortname(a.fld_ipl_name,2) AS shortname, 1 AS typ, a.fld_asset_id AS assetid,'IPL' AS titlename
                                        FROM itc_ipl_master  AS a
                                        LEFT JOIN itc_ipl_version_track AS b ON b.fld_ipl_id=a.fld_id
                                        WHERE a.fld_access='1' AND a.fld_delstatus='0' AND b.fld_delstatus='0' AND b.fld_zip_type='1' AND a.fld_id = '".$titleid."'";
                         ++$ipl_cnt;
                        
                         if($ipl_cnt == 1) {
                             $group_qryipls = $qryipls;
                         }
                         elseif($ipl_cnt >1) {
                             $group_qryipls = $group_qryipls." UNION ".$qryipls;
                             
                         }
                    }
                    if($tagtype == '4')
                    {
                        $qryunits="SELECT fld_id AS id, fld_unit_name AS nam,fn_shortname(fld_unit_name,2) AS shortname, 2 AS typ, fld_asset_id as assetid,'Unit' AS titlename
	  			FROM itc_unit_master 
				WHERE fld_delstatus='0' AND fld_id = '".$titleid."'";
                        ++$unit_cnt;
                        
                         if($unit_cnt == 1) {
                             $group_qryunit = $qryunits;
                         }
                         elseif($unit_cnt >1) {
                             $group_qryunit = $group_qryunit." UNION ".$qryunits;
                             
                         }
                    }
                    if($tagtype == '3')
                    {
                        $qrymodules="SELECT a.fld_id AS id, CONCAT(a.fld_module_name,' ',b.fld_version) AS nam,fn_shortname(a.fld_module_name,2) AS shortname, 3 AS typ, a.fld_asset_id AS assetid,'Module' AS titlename 
					FROM itc_module_master AS a 
					LEFT JOIN itc_module_version_track AS b ON b.fld_mod_id=a.fld_id
					WHERE a.fld_delstatus='0' AND b.fld_delstatus='0' AND a.fld_id = '".$titleid."'";
                        ++$module_cnt;
                        
                         if($module_cnt == 1) {
                             $group_qrymodule = $qrymodules;
                         }
                         elseif($module_cnt >1) {
                             $group_qrymodule = $group_qrymodule." UNION ".$qrymodules;
                             
                         }
                    }
                    if($tagtype == '23')
                    {
                        $qrymathmodules="SELECT a.fld_id AS id, CONCAT(a.fld_mathmodule_name,' ',b.fld_version) AS nam,fn_shortname(a.fld_mathmodule_name,2) AS shortname, 4 AS typ, a.fld_asset_id AS assetid,'Math Module' AS titlename 
						FROM itc_mathmodule_master AS a 
						LEFT JOIN itc_module_version_track AS b ON b.fld_mod_id=a.fld_module_id
						WHERE a.fld_delstatus='0' AND b.fld_delstatus='0' AND a.fld_id = '".$titleid."'";
                        ++$mathmod_cnt;
                        
                         if($mathmod_cnt == 1) {
                             $group_qrymathmod = $qrymathmodules;
                         }
                         elseif($mathmod_cnt >1) {
                             $group_qrymathmod = $group_qrymathmod." UNION ".$qrymathmodules;
                             
                         }
                    }
                
                }
             }
        }       
         
    }
    else if($sessmasterprfid==6){	//Lessons listed based on available licenses for a distict
    	for($i=0;$i<sizeof($selecttagproducts);$i++) {
    		$ptag_type =$ObjDB->QueryObject("SELECT fld_item_id as titleid,fld_tag_type as tagtype FROM itc_main_tag_mapping where fld_tag_id='".$selecttagproducts[$i]."' AND (fld_tag_type = '1' OR fld_tag_type = '23' OR fld_tag_type = '3' OR fld_tag_type = '4') AND fld_access = '1'");
             if($ptag_type->num_rows > 0){
                 static $ipl_cnt = 0;
                 static $unit_cnt = 0;
                 static $module_cnt = 0;
                 static $mathmod_cnt = 0;
                 while($rowqry = $ptag_type->fetch_assoc())
                {
                	extract($rowqry);
                	if($tagtype == '1')
                    {
                    	$qryipls = "SELECT c.fld_id AS id, CONCAT(c.fld_ipl_name,' ',d.fld_version) AS nam,fn_shortname(c.fld_ipl_name,2) AS shortname, 1 AS typ ,c.fld_asset_id AS assetid,'IPL' AS titlename
					FROM itc_license_track AS a 
					LEFT JOIN itc_license_cul_mapping AS b ON a.fld_license_id=b.fld_license_id 
					LEFT JOIN itc_ipl_master AS c ON b.`fld_lesson_id`=c.fld_id
					LEFT JOIN itc_ipl_version_track AS d ON d.fld_ipl_id=c.fld_id 
					WHERE a.fld_delstatus='0' AND b.fld_active='1' AND a.fld_district_id='".$sendistid."' 
						AND a.fld_start_date<=NOW() AND a.fld_end_date>=NOW() AND c.fld_delstatus='0' AND c.fld_access='1'
						AND d.fld_zip_type='1' AND d.fld_delstatus='0' AND c.fld_id = '".$titleid."'
					GROUP BY c.fld_id";
					++$ipl_cnt;
                        
                         if($ipl_cnt == 1) {
                             $group_qryipls = $qryipls;
                         }
                         elseif($ipl_cnt >1) {
                             $group_qryipls = $group_qryipls." UNION ".$qryipls;
                    	 }
            		}
            		if($tagtype == '4')
                    {
                    	$qryunits = "SELECT c.fld_id AS id, c.fld_unit_name AS nam,fn_shortname(c.fld_unit_name,2) AS shortname, 2 AS typ, c.fld_asset_id as assetid,'Unit' AS titlename
					FROM itc_license_track AS a 
					LEFT JOIN itc_license_cul_mapping AS b ON a.fld_license_id=b.fld_license_id 
					LEFT JOIN itc_unit_master AS c ON b.`fld_unit_id`=c.fld_id 
					WHERE a.fld_delstatus='0' AND b.fld_active='1' AND a.fld_district_id='".$sendistid."' AND a.fld_start_date<=NOW() 
						AND a.fld_end_date>=NOW() AND c.fld_delstatus='0' AND c.fld_id = '".$titleid."'
					GROUP BY c.fld_id";
					++$unit_cnt;
                        
                         if($unit_cnt == 1) {
                             $group_qryunit = $qryunits;
                         }
                         elseif($unit_cnt >1) {
                             $group_qryunit = $group_qryunit." UNION ".$qryunits;
                             
                         }
                    }
                    if($tagtype == '3')
                    {
                    	$qrymodules = "SELECT b.fld_module_id AS id, CONCAT(a.fld_module_name,' ',d.fld_version) AS nam,fn_shortname(a.fld_module_name,2) AS shortname, 3 AS typ , a.fld_asset_id AS assetid,'Module' AS titlename  
						FROM itc_module_master AS a 
						LEFT JOIN itc_license_mod_mapping AS b ON a.fld_id=b.fld_module_id 
						LEFT JOIN itc_license_track AS c ON b.fld_license_id=c.fld_license_id 
						LEFT JOIN itc_module_version_track AS d ON d.fld_mod_id=b.fld_module_id
						WHERE a.fld_delstatus='0' AND c.fld_district_id='".$sendistid."' AND c.fld_school_id='0' AND c.fld_delstatus='0' 
							AND b.fld_active='1' AND c.fld_start_date<=NOW() AND c.fld_end_date>=NOW() AND b.fld_type=1 AND d.fld_delstatus='0' AND a.fld_id = '".$titleid."'
						GROUP BY b.fld_module_id";
						++$module_cnt;
                        
                         if($module_cnt == 1) {
                             $group_qrymodule = $qrymodules;
                         }
                         elseif($module_cnt >1) {
                             $group_qrymodule = $group_qrymodule." UNION ".$qrymodules;
                             
                         }
                    }
					if($tagtype == '23')
                    {
                    	$qrymathmodules = "SELECT b.fld_module_id AS id, CONCAT(a.fld_mathmodule_name,' ',d.fld_version) AS nam,fn_shortname(a.fld_mathmodule_name,2) AS shortname, 4 AS typ , a.fld_asset_id AS assetid,'Math Module' AS titlename
							FROM itc_mathmodule_master AS a 
							LEFT JOIN itc_license_mod_mapping AS b ON a.fld_id=b.fld_module_id 
							LEFT JOIN itc_license_track AS c ON b.fld_license_id=c.fld_license_id
							LEFT JOIN itc_module_version_track AS d ON d.fld_mod_id=b.fld_module_id 
							WHERE a.fld_delstatus='0' AND c.fld_district_id='".$sendistid."' AND c.fld_school_id='0' AND c.fld_delstatus='0' AND d.fld_delstatus='0'
								AND b.fld_active='1' AND c.fld_start_date<=NOW() AND c.fld_end_date>=NOW() AND b.fld_type=2 AND a.fld_id = '".$titleid."'
							GROUP BY b.fld_module_id";	
							++$mathmod_cnt;
                        
                         if($mathmod_cnt == 1) {
                             $group_qrymathmod = $qrymathmodules;
                         }
                         elseif($mathmod_cnt >1) {
                             $group_qrymathmod = $group_qrymathmod." UNION ".$qrymathmodules;
                    	}
                    }

            	}
             }
    	}
    }
    else
    {   //Lessons listed based on available licenses for a school or an individual user

    	for($i=0;$i<sizeof($selecttagproducts);$i++) {
    		$ptag_type =$ObjDB->QueryObject("SELECT fld_item_id as titleid,fld_tag_type as tagtype FROM itc_main_tag_mapping where fld_tag_id='".$selecttagproducts[$i]."' AND (fld_tag_type = '1' OR fld_tag_type = '23' OR fld_tag_type = '3' OR fld_tag_type = '4') AND fld_access = '1'");
             if($ptag_type->num_rows > 0){
                 static $ipl_cnt = 0;
                 static $unit_cnt = 0;
                 static $module_cnt = 0;
                 static $mathmod_cnt = 0;
                 while($rowqry = $ptag_type->fetch_assoc())
                {
                	extract($rowqry);
                	if($tagtype == '1')
                    {

					$qryipls = "SELECT c.fld_id AS id, CONCAT(c.fld_ipl_name,' ',d.fld_version) AS nam,fn_shortname(c.fld_ipl_name,2) AS shortname, 1 AS typ , c.fld_asset_id AS assetid,'IPL' AS titlename
					FROM itc_license_track AS a 
					LEFT JOIN itc_license_cul_mapping AS b ON a.fld_license_id=b.fld_license_id 
					LEFT JOIN itc_ipl_master AS c ON b.`fld_lesson_id`=c.fld_id 
					LEFT JOIN itc_ipl_version_track AS d ON d.fld_ipl_id=c.fld_id
					WHERE a.fld_delstatus='0' AND b.fld_active='1' AND a.fld_school_id='".$schoolid."' AND a.fld_user_id='".$indid."' 
						AND a.fld_start_date<=NOW() AND a.fld_end_date>=NOW() AND c.fld_delstatus='0' AND c.fld_access='1'
						AND d.fld_zip_type='1' AND d.fld_delstatus='0' AND c.fld_id = '".$titleid."'
					GROUP BY c.fld_id";	
					++$ipl_cnt;
                        
                         if($ipl_cnt == 1) {
                             $group_qryipls = $qryipls;
                         }
                         elseif($ipl_cnt >1) {
                             $group_qryipls = $group_qryipls." UNION ".$qryipls;
                    	 }
                    }
                    if($tagtype == '4')
                    {
                    	$qryunits = "SELECT c.fld_id AS id, c.fld_unit_name AS nam,fn_shortname(c.fld_unit_name,2) AS shortname, 2 AS typ , c.fld_asset_id as assetid,'Unit' AS titlename
					FROM itc_license_track AS a 
					LEFT JOIN itc_license_cul_mapping AS b ON a.fld_license_id=b.fld_license_id 
					LEFT JOIN itc_unit_master AS c ON b.`fld_unit_id`=c.fld_id 
					WHERE a.fld_delstatus='0' AND b.fld_active='1' AND a.fld_school_id='".$schoolid."' AND a.fld_user_id='".$indid."' 
				AND a.fld_start_date<=NOW() AND a.fld_end_date>=NOW() AND c.fld_delstatus='0' AND c.fld_id = '".$titleid."'
					GROUP BY c.fld_id";
					++$unit_cnt;
                        
                         if($unit_cnt == 1) {
                             $group_qryunit = $qryunits;
                         }
                         elseif($unit_cnt >1) {
                             $group_qryunit = $group_qryunit." UNION ".$qryunits;
                             
                         }
                    }
                    if($tagtype == '3')
                    {
                    	$qrymodules = "SELECT b.fld_module_id AS id, 
						CONCAT(a.fld_module_name,' ',d.fld_version) AS nam,fn_shortname(a.fld_module_name,2) AS shortname, 3 AS typ , a.fld_asset_id AS assetid,'Module' AS titlename  
						FROM itc_module_master AS a 
						LEFT JOIN itc_license_mod_mapping AS b ON a.fld_id=b.fld_module_id 
						LEFT JOIN itc_license_track AS c ON b.fld_license_id=c.fld_license_id
						LEFT JOIN itc_module_version_track AS d ON d.fld_mod_id=b.fld_module_id
						WHERE a.fld_delstatus='0' AND c.fld_school_id='".$schoolid."' AND c.fld_user_id='".$indid."' AND c.fld_delstatus='0' 
							AND b.fld_active='1' AND c.fld_start_date<=NOW() AND c.fld_end_date>=NOW() AND b.fld_type=1 AND d.fld_delstatus='0' AND a.fld_id = '".$titleid."'
						GROUP BY b.fld_module_id";
						++$module_cnt;
                        
                         if($module_cnt == 1) {
                             $group_qrymodule = $qrymodules;
                         }
                         elseif($module_cnt >1) {
                             $group_qrymodule = $group_qrymodule." UNION ".$qrymodules;
                             
                         }
                    }
					if($tagtype == '23')
                    {
                    	$qrymathmodules = "SELECT b.fld_module_id AS id, CONCAT(a.fld_mathmodule_name,' ',d.fld_version) AS nam,fn_shortname(a.fld_mathmodule_name,2) AS shortname, 4 AS typ , a.fld_asset_id AS assetid,'Math Module' AS titlename  
							FROM itc_mathmodule_master AS a 
							LEFT JOIN itc_license_mod_mapping AS b ON a.fld_id=b.fld_module_id 
							LEFT JOIN itc_license_track AS c ON b.fld_license_id=c.fld_license_id 
							LEFT JOIN itc_module_version_track AS d ON d.fld_mod_id=b.fld_module_id
							WHERE a.fld_delstatus='0' AND c.fld_school_id='".$schoolid."' AND c.fld_user_id='".$indid."' 
								AND c.fld_delstatus='0' AND b.fld_active='1' AND c.fld_start_date<=NOW() AND c.fld_end_date>=NOW() AND b.fld_type=2 AND d.fld_delstatus='0' AND a.fld_id = '".$titleid."'
							GROUP BY b.fld_module_id";	
							++$mathmod_cnt;
                        
                         if($mathmod_cnt == 1) {
                             $group_qrymathmod = $qrymathmodules;
                         }
                         elseif($mathmod_cnt >1) {
                             $group_qrymathmod = $group_qrymathmod." UNION ".$qrymathmodules;
                    	}
                    }



                }
            }
        }


    }   /* end of the else part */

    if($group_qryipls != '' && $group_qryunit == '' && $group_qrymodule == '' && $group_qrymathmod == '')
            $qry = $group_qryipls." ORDER BY nam";
    elseif ($group_qryipls == '' && $group_qryunit != '' && $group_qrymodule == '' && $group_qrymathmod == '')
        $qry = $group_qryunit." ORDER BY nam";
    elseif ($group_qryipls == '' && $group_qryunit == '' && $group_qrymodule != '' && $group_qrymathmod == '')
        $qry = $group_qrymodule." ORDER BY nam";
    elseif ($group_qryipls == '' && $group_qryunit == '' && $group_qrymodule == '' && $group_qrymathmod != '')
        $qry = $group_qrymathmod." ORDER BY nam";
    elseif ($group_qryipls != '' && $group_qryunit != '' && $group_qrymodule == '' && $group_qrymathmod == '')
        $qry = $group_qryipls." UNION ALL ".$group_qryunit." ORDER BY nam";
    elseif ($group_qryipls != '' && $group_qryunit == '' && $group_qrymodule != '' && $group_qrymathmod == '')
        $qry = $group_qryipls." UNION ALL ".$group_qrymodule." ORDER BY nam";
    elseif ($group_qryipls != '' && $group_qryunit == '' && $group_qrymodule == '' && $group_qrymathmod != '')
        $qry = $group_qryipls." UNION ALL ".$group_qrymathmod." ORDER BY nam";
    elseif ($group_qryipls == '' && $group_qryunit != '' && $group_qrymodule != '' && $group_qrymathmod == '')
        $qry = $group_qryunit." UNION ALL ".$group_qrymodule." ORDER BY nam";
    elseif ($group_qryipls == '' && $group_qryunit != '' && $group_qrymodule == '' && $group_qrymathmod != '')
        $qry = $group_qryunit." UNION ALL ".$group_qrymathmod." ORDER BY nam";
    elseif ($group_qryipls == '' && $group_qryunit == '' && $group_qrymodule != '' && $group_qrymathmod != '')
        $qry = $group_qrymodule." UNION ALL ".$group_qrymathmod." ORDER BY nam";
    elseif ($group_qryipls != '' && $group_qryunit != '' && $group_qrymodule != '' && $group_qrymathmod == '')
        $qry = $group_qryipls." UNION ALL ".$group_qryunit." UNION ALL ".$group_qrymodule." ORDER BY nam";
    elseif ($group_qryipls != '' && $group_qryunit != '' && $group_qrymodule == '' && $group_qrymathmod != '')
        $qry = $group_qryipls." UNION ALL ".$group_qryunit." UNION ALL ".$group_qrymathmod." ORDER BY nam";
    elseif ($group_qryipls == '' && $group_qryunit != '' && $group_qrymodule != '' && $group_qrymathmod != '')
        $qry = $group_qryunit." UNION ALL ".$group_qrymodule." UNION ALL ".$group_qrymathmod." ORDER BY nam";
    elseif ($group_qryipls != '' && $group_qryunit == '' && $group_qrymodule != '' && $group_qrymathmod != '')
        $qry = $group_qryipls." UNION ALL ".$group_qrymodule." UNION ALL ".$group_qrymathmod." ORDER BY nam";
    elseif ($group_qryipls != '' && $group_qryunit != '' && $group_qrymodule != '' && $group_qrymathmod != '')
        $qry = $group_qryipls." UNION ALL ".$group_qryunit." UNION ALL ".$group_qrymodule." UNION ALL ".$group_qrymathmod." ORDER BY nam";
    else 
           $qry = '';
    
   $selproductdetails=array();
     $productdetails=array();
      if($qry != '')  {
	  	$productqry = $ObjDB->QueryObject($qry);
		if($productqry->num_rows > 0){
			$i=0;
			while($productqryrow = $productqry->fetch_assoc()){
				extract($productqryrow);
				if($assetid=='')
				{
					$assetid='MO.ENVM.3.0.0a';
                                        
                                        
				}
				$productdetails[]=array("id"=>$id,"nam"=>$nam,"shortname"=>$shortname,"type"=>$typ,"productid"=>$assetid,"title_name"=>$titlename,"gradeoutflag"=>$gradeoutflag);
				$i++;
			}
		}
      }
   
         ?>
                <script type="text/javascript" language="javascript">
			$(function() {
				$('#testrailvisible17').slimscroll({
					width: '410px',
					height:'366px',
					size: '7px',
                                        alwaysVisible: true,
					railVisible: true,
					allowPageScroll: false,
					railColor: '#F4F4F4',
					opacity: 1,
					color: '#d9d9d9',
                                         wheelStep: 1,
				
				});
				
				$("#list11").sortable({
					connectWith: ".droptrue3",
					dropOnEmpty: true,
					items: "div[class='draglinkleft']",
					receive: function(event, ui) {
						$("div[class=draglinkright]").each(function(){ 
							if($(this).parent().attr('id')=='list11'){								
								fn_movealllistitems('list11','list12',1,$(this).attr('id').replace('list12_',''));
								fn_saveselecttag();
								fn_validateproductstag();
							}
						});
					}
				});
                                                                                              $("#list12").sortable({
					connectWith: ".droptrue3",
					dropOnEmpty: true,
					items: "div[class='draglinkleft']",
					receive: function(event, ui) {
						$("div[class=draglinkright]").each(function(){ 
							if($(this).parent().attr('id')=='list12'){								
								fn_movealllistitems('list11','list12',1,$(this).attr('id').replace('list11_',''));
								fn_saveselecttag();
								fn_validateproductstag();
							}
						});
					}
				});
			
				
			}); 
		</script>	
    	
           <div class="dragndropcol">
                <div class="dragtitle">Products</div>
                <div class="dragWell" id="testrailvisible17" >
                    <div id="list11" class="dragleftinner droptrue3">
                    	<div class="draglinkleftSearch" id="s_list11" >
                           <dl class='field row'>
                                <dt class='text'>
                                    <input placeholder='Search' type='text' id="list_11_search" name="list_11_search" onKeyUp="search_list(this,'#list11');" />
                                </dt>
                            </dl>
                        </div>
                          <?php
                         

			$gnam = $ObjDB->QueryObject("SELECT fld_guid as guid FROM itc_bestfit_rpt_std_grades
			       WHERE fld_rpt_data_id='".$rptid."' AND fld_delstatus='0'");
			if($gnam->num_rows > 0){
				$sguids='';
				while($grdrow = $gnam->fetch_assoc()){
					extract($grdrow);


				if($sguids=='')
				{
				$sguids="'".$guid."'";
				}
				else
				{
				$sguids.=","."'".$guid."'";
				}
					
				}
			$sguidsqry=" in (".$sguids.")";
			}
                        
                        $qrylessonunselect=$ObjDB->QueryObject("SELECT b.fld_prd_id as asstid FROM itc_correlation_productsgradeout as a
                                                                LEFT JOIN itc_correlation_products as b ON b.fld_prd_asset_id = a.fld_productid
                                                                WHERE a.fld_standardguid$sguidsqry GROUP BY a.fld_productid");
                    $filter_greyout=array(); 
                    while($lessonunselect=$qrylessonunselect->fetch_assoc()){
                    extract($lessonunselect);
                    array_push($filter_greyout,$asstid);
                    } 
                         
                        $qryforselectd_prods = $ObjDB->QueryObject("SELECT b.fld_prd_name,b.fld_prd_id,a.fld_type,
                                                                                                b.fld_prd_sys_id
                                                                                                FROM itc_bestfit_rpt_products as a 
                                                                                                LEFT JOIN itc_correlation_products as b on a.fld_prd_id=b.fld_id and a.fld_type=b.fld_prd_type
                                                                                                WHERE a.fld_rpt_data_id='".$rptid."' AND a.fld_delstatus='0' AND b.fld_prd_name<>''");

                        if($qryforselectd_prods->num_rows > 0){
							while($qryforselectd_prodsrow = $qryforselectd_prods->fetch_assoc()){
								extract($qryforselectd_prodsrow);								
								$selproductdetails[]=array("id"=>$fld_prd_sys_id,"nam"=>$fld_prd_name,"type"=>$fld_type,"productid"=>$fld_prd_id);
							}
						}


							for($i=0;$i<sizeof($productdetails);$i++) {
								 $bool=true;
								for($j=0;$j<sizeof($selproductdetails);$j++)
								{
									
									if($productdetails[$i]['id'] == $selproductdetails[$j]['id'] && $productdetails[$i]['type'] == $selproductdetails[$j]['type']) 
									{
										
											$bool=false;
										
									}						
								}
								if($bool)
								{
$dimproduct = array_diff(array($productdetails[$i]['productid']),$filter_greyout);
                        ?>
								<div name="<?php echo $productdetails[$i]['type']; ?>" class="draglinkleft<?php  if(!empty($dimproduct)) { echo ' dim'; $a=1;}?>" id="list11_<?php echo $productdetails[$i]['id']."_".$productdetails[$i]['type']; ?>" >
									<div class="dragItemLable tooltip" id="<?php echo  $productdetails[$i]['id']."~".$productdetails[$i]['type']."~".$productdetails[$i]['productid']; ?>" title="<?php echo $productdetails[$i]['nam']; ?>"><?php echo $productdetails[$i]['shortname']."/".$productdetails[$i]['title_name'];?></div>
									<div class="clickable" id="clck_<?php echo $productdetails[$i]['productid']; ?>" onclick="fn_movealllistitemsproducts('list11','list12',1,'<?php echo $productdetails[$i]['id']."_".$productdetails[$i]['type']; ?>');fn_saveselecttag();fn_validateproductstag();"></div>
								</div>
                      	<?php
								}
							}
                        ?>
                    </div>
                </div>
                <div class="dragAllLink<?php if($a==1){ echo ' dim';}?>" onclick="fn_movealllistitemsproducts('list11','list12',0);fn_saveselecttag();fn_validateproductstag();" style="cursor: pointer;cursor:hand;width: 145px;float: right;">add all products</div>
            </div>
 <?php  
    
}

if($oper=="showselectedpro" and $oper != " ")
{ 
    
    $selectproducts = isset($method['selectproducts']) ? $method['selectproducts'] : 0;
    $reqproducts = isset($method['reqproducts']) ? $method['reqproducts'] : 0;
    $rptid = isset($method['rptid']) ? $method['rptid'] : '';
    $selectproducts = explode(',',$selectproducts);
    $reqproducts = explode(',',$reqproducts);
    
    $req_productdetails=array();
    
   
    $selected_products=array();
    /*Query for required products */
	
   	
	
    $qryfor_req_productdetails=$ObjDB->QueryObject("SELECT b.fld_prd_name, b.fld_prd_id,fn_shortname(b.fld_prd_name,2) AS shortname, a.fld_type, b.fld_prd_sys_id 
                                                FROM itc_bestfit_rpt_reqproducts a 
                                                LEFT JOIN itc_correlation_products b ON b.fld_id=a.fld_prod_id 
                                                WHERE a.fld_rpt_data_id='".$rptid."' AND a.fld_delstatus='0' AND b.fld_prd_name=a.fld_req_product
                                                GROUP BY b.fld_prd_asset_id ORDER BY b.fld_prd_name ASC");
    
    if($qryfor_req_productdetails->num_rows > 0){
       
        while($qryfor_req_productdetailsrow = $qryfor_req_productdetails->fetch_assoc()){
        extract($qryfor_req_productdetailsrow);
        $req_productdetails[]=array("id"=>$fld_prd_sys_id,"nam"=>$fld_prd_name,"shortname"=>$shortname,"type"=>$fld_type,"productid"=>$fld_prd_id);
       
        ?>
        <script type="text/javascript" language="javascript">
            productid.push('<?php echo $fld_prd_sys_id.'_'.$fld_type;?>');
        </script>
        <?php 
        }
        
    }  
    
  /*Query for Selected products */
    for($w=0;$w<sizeof($selectproducts);$w++)
    {   
       
        $selct_prod_sep=explode('_',$selectproducts[$w]); 
												
        $select_listproduct=$ObjDB->QueryObject("SELECT y.fld_prd_sys_id as prodsysid,y.fld_prd_type as type,y.fld_prd_name as prodname,fn_shortname(y.fld_prd_name,2) AS shortname,y.fld_prd_id as prodid 
                                                FROM itc_correlation_products AS y
                                                LEFT JOIN itc_bestfit_rpt_products as z ON z.fld_type = y.fld_prd_type
                                                WHERE z.fld_rpt_data_id='".$rptid."' AND y.fld_prd_type ='".$selct_prod_sep[1]."' AND y.fld_prd_sys_id='".$selct_prod_sep[0]."' GROUP BY y.fld_prd_sys_id");
       if($select_listproduct->num_rows > 0){
       while($select_listproductrow = $select_listproduct->fetch_assoc()){
       extract($select_listproductrow);
                   
       $selected_products[]=array("id"=>$prodsysid,"pname"=>$prodname,"shortname"=>$shortname,"type"=>$type,"productid"=>$prodid);
          }
       }
       else {
            $qryforproductdetails=$ObjDB->QueryObject("SELECT fld_prd_name AS proname,fn_shortname(fld_prd_name,2) as shortname,fld_prd_id as prodid FROM itc_correlation_products 
                                                       WHERE fld_prd_sys_id='".$selct_prod_sep[0]."' AND fld_prd_type='".$selct_prod_sep[1]."'");

                                if($qryforproductdetails->num_rows > 0){
                                    while($qryforproductdetailsrow = $qryforproductdetails->fetch_assoc()){
                                        extract($qryforproductdetailsrow);
                                        $selected_products[]=array("id"=>$selct_prod_sep[0],"pname"=>$proname,"shortname"=>$shortname,"type"=>$selct_prod_sep[1],"productid"=>$prodid);
                                      }
                                 }
            }
            
     }
   $required_prodlists=array();
   $selected_prodlists=array();
   $list_pairset=array();
   $difflist=array();
   
     for($p=0;$p<sizeof($selected_products);$p++)
     {
         array_push($selected_prodlists,$selected_products[$p]['productid']);
     }
     for($q=0;$q<sizeof($req_productdetails); $q++)
         {
             array_push($required_prodlists,$req_productdetails[$q]['productid']);
         }
     
     $diff_arraylist = array_diff($selected_prodlists,$required_prodlists);

     foreach($diff_arraylist as $valued)
     {
        
       $difflist[]=$valued;
         
     }
     
     for($r=0;$r<sizeof($difflist);$r++)
     {
        
         $list_pairset[]=array_merge((array)$required_prodlists,(array)$difflist[$r]);
     }
    
    ?>
    <script type="text/javascript" language="javascript">
    var productid = [];
    </script>
    <script type="text/javascript" language="javascript">
        $(function() {
            $('#testrailvisible15').slimscroll({
                width: '410px',
                height:'366px',
                size: '7px',
                alwaysVisible: true,
                railVisible: true,
                allowPageScroll: false,
                railColor: '#F4F4F4',
                opacity: 1,
                color: '#d9d9d9',
                 wheelStep: 1,
            });

            $('#testrailvisible16').slimscroll({
                width: '410px',
                height:'370px',
                size: '7px',
                alwaysVisible: true,
                railVisible: true,
                allowPageScroll: false,
                railColor: '#F4F4F4',
                opacity: 1,
                color: '#d9d9d9',
                 wheelStep: 1,
            });


            $("#list9").sortable({
                connectWith: ".droptrue4",
                dropOnEmpty: true,
                items: "div[class='draglinkleft']",
                receive: function(event, ui) {
                    $("div[class=draglinkright]").each(function(){ 
                        if($(this).parent().attr('id')=='list9'){                            
                            fn_movealllistitems('list9','list10',1,$(this).attr('id').replace('list10_',''));
                            fn_saveselect();
                        }
                    });
                }
            });

            $("#list10" ).sortable({
                connectWith: ".droptrue4",
                dropOnEmpty: true,
                receive: function(event, ui) {
                    $("div[class=draglinkleft]").each(function(){ 
                        if($(this).parent().attr('id')=='list10'){                            
                            fn_movealllistitems('list9','list10',1,$(this).attr('id').replace('list9_',''));
                            fn_saveselect();
                        }
                    });
                }
            });
        }); 
     
                            
    </script>
   
    <div class="row rowspacer">
        <div id="requiredproducts">
            <div class="six columns">
                <div class="dragndropcol">
                    <div class="dragtitle"> Selected Products<span class="fldreq">*</span></div>
                    <div class="dragWell" id="testrailvisible15" >
                        <div id="list9" class="dragleftinner droptrue4">
                            <div class="draglinkleftSearch" id="s_list9" >
                                <dl class='field row'>
                                    <dt class='text'>
                                        <input placeholder='Search' type='text' id="list_9_search" name="list_9_search" onKeyUp="search_list(this,'#list9');" />                                        
                                    </dt>
                                </dl>
                            </div>                            
                            <?php
                             $selectproductsdetails =array();
                          
                            for($i=0;$i<sizeof($selected_products);$i++)
                            {
                               
                                 $bool=true;
                                 for($j=0;$j<sizeof($req_productdetails);$j++)
                                 {
                                    
                                      if($req_productdetails[$j]['id'] == $selected_products[$i]['id'] and $req_productdetails[$j]['type'] ==$selected_products[$i]['type'])
                                        {
                                            $bool=false;
                                           
                                        }
                                 }
                                if($bool)
                                {
                                  

                                    ?>
                                    <div name="<?php echo $selected_products[$i]['type']; ?>" class="draglinkleft" id="list9_<?php echo $selected_products[$i]['id']."_".$selected_products[$i]['type']; ?>" >
                                       <div class="dragItemLable tooltip" id="<?php echo  $selected_products[$i]['id']."~".$selected_products[$i]['type']."~".$selected_products[$i]['productid']."~".$selected_products[$i]['pname']; ?>" title="<?php echo $selected_products[$i]['pname'];?>"><?php echo $selected_products[$i]['shortname'];?></div>
                                        <div class="clickable" id="clck_<?php echo $selected_products[$i]['productid']; ?>" onclick="fn_movealllistitemsproducts('list9','list10',1,'<?php echo $selected_products[$i]['id']."_".$selected_products[$i]['type']; ?>');fn_saveselect();"></div>
                                    </div>
                               <?php
                                   
                                }
                           
                                }     
                            ?>
                        </div>
                    </div>
                    <div class="dragAllLink" onclick="fn_movealllistitemsproducts('list9','list10',0);fn_saveselect();" style="cursor: pointer;cursor:hand;width: 115px;float: right;" >add all products</div>
                </div>
            </div>

            <div class="six columns">
                <div class="dragndropcol">
                    <div class="dragtitle"> Required Products</div>
                    <div class="dragWell" id="testrailvisible16">
                        <div id="list10" class="dragleftinner droptrue4">
                             <?php
                                                
                                for($k=0;$k<sizeof($req_productdetails);$k++) {
                                    ?>
                                    <div name="<?php echo $req_productdetails[$k]['type']; ?>" class="draglinkright" id="list10_<?php  echo $req_productdetails[$k]['id']."_".$req_productdetails[$k]['type']; ?>" >
                                        <div class="dragItemLable tooltip" id="<?php echo  $req_productdetails[$k]['id']."~".$req_productdetails[$k]['type']."~".$req_productdetails[$k]['productid']; ?>" title="<?php echo $selected_products[$i]['pname'];?>"><?php echo $req_productdetails[$k]['shortname'];?></div>
                                        <div class="clickable" id="clck_<?php echo $req_productdetails[$k]['productid']; ?>" onclick="fn_movealllistitemsproducts('list9','list10',1,'<?php echo $req_productdetails[$k]['id']."_".$req_productdetails[$k]['type']; ?>');fn_validateproducts();fn_saveselect();"></div>
                                    </div>
                                    <?php
                                    
                                }
                                
                              ?>
                        
                        </div>
                    </div>
                    <div class="dragAllLink" onclick="fn_movealllistitemsproducts('list10','list9',0);fn_saveselect();" style="cursor: pointer;cursor:hand;width: 145px;float: right;" >remove all products</div>
                </div>
            </div>
           </div>
    </div>
    <input type="hidden" value="" id="hidlist10" name="hidlist10" />
    <input type="hidden" value="" id="list10rec" name="list10rec" />
    
   
    <?php   
  
}
if($oper=="showtagselectedpro" and $oper != " ")
{ 	

    $selectproducts = isset($method['selectproducts']) ? $method['selectproducts'] : 0;
    $reqproducts = isset($method['reqproducts']) ? $method['reqproducts'] : 0;
    $rptid = isset($method['rptid']) ? $method['rptid'] : '';
    $selectproducts = explode(',',$selectproducts);
    $reqproducts = explode(',',$reqproducts);
  
    $req_productdetails=array();
    
   
    $selected_products=array();
    /*Query for required products */
	
   	
	
    $qryfor_req_productdetails=$ObjDB->QueryObject("SELECT b.fld_prd_name, b.fld_prd_id,fn_shortname(b.fld_prd_name,2) AS shortname, a.fld_type, b.fld_prd_sys_id 
                                                FROM itc_bestfit_rpt_reqproducts a 
                                                LEFT JOIN itc_correlation_products b ON b.fld_id=a.fld_prod_id 
                                                WHERE a.fld_rpt_data_id='".$rptid."' AND a.fld_delstatus='0' AND b.fld_prd_name=a.fld_req_product
                                                GROUP BY b.fld_prd_asset_id ORDER BY b.fld_prd_name ASC");

    if($qryfor_req_productdetails->num_rows > 0){
       
        while($qryfor_req_productdetailsrow = $qryfor_req_productdetails->fetch_assoc()){
        extract($qryfor_req_productdetailsrow);
        $req_productdetails[]=array("id"=>$fld_prd_sys_id,"nam"=>$fld_prd_name,"shortname"=>$shortname,"type"=>$fld_type,"productid"=>$fld_prd_id);
       
        ?>
        <script type="text/javascript" language="javascript">
            productid.push('<?php echo $fld_prd_sys_id.'_'.$fld_type;?>');
        </script>
        <?php 
        }
        
    }  
    
  /*Query for Selected products */
    for($w=0;$w<sizeof($selectproducts);$w++)
    {   
      
        $selct_prod_sep=explode('~',$selectproducts[$w]); 
			
		
												
        $select_listproduct=$ObjDB->QueryObject("SELECT y.fld_prd_sys_id as prodsysid,y.fld_prd_type as type,y.fld_prd_name as prodname,fn_shortname(y.fld_prd_name,2) AS shortname,y.fld_prd_id as prodid 
                                                FROM itc_correlation_products AS y
                                                LEFT JOIN itc_bestfit_rpt_products as z ON z.fld_type = y.fld_prd_type
                                                WHERE z.fld_rpt_data_id='".$rptid."' AND y.fld_prd_type ='".$selct_prod_sep[1]."' AND y.fld_prd_sys_id='".$selct_prod_sep[0]."' GROUP BY y.fld_prd_sys_id");
       if($select_listproduct->num_rows > 0){
       while($select_listproductrow = $select_listproduct->fetch_assoc()){
       extract($select_listproductrow);
                   
       $selected_products[]=array("id"=>$prodsysid,"pname"=>$prodname,"shortname"=>$shortname,"type"=>$type,"productid"=>$prodid);
          }
       }
       else {
            $qryforproductdetails=$ObjDB->QueryObject("SELECT fld_prd_name AS proname,fn_shortname(fld_prd_name,2) as shortname,fld_prd_id as prodid FROM itc_correlation_products 
                                                       WHERE fld_prd_sys_id='".$selct_prod_sep[0]."' AND fld_prd_type='".$selct_prod_sep[1]."'");

                                if($qryforproductdetails->num_rows > 0){
                                    while($qryforproductdetailsrow = $qryforproductdetails->fetch_assoc()){
                                        extract($qryforproductdetailsrow);
                                        $selected_products[]=array("id"=>$selct_prod_sep[0],"pname"=>$proname,"shortname"=>$shortname,"type"=>$selct_prod_sep[1],"productid"=>$prodid);
                                      }
                                 }
            }
            
     }
   $required_prodlists=array();
   $selected_prodlists=array();
   $list_pairset=array();
   $difflist=array();
   
     for($p=0;$p<sizeof($selected_products);$p++)
     {
    	 array_push($selected_prodlists,$selected_products[$p]['productid']);
     }
     for($q=0;$q<sizeof($req_productdetails); $q++)
	 {
		 array_push($required_prodlists,$req_productdetails[$q]['productid']);
	 }
     
     $diff_arraylist = array_diff($selected_prodlists,$required_prodlists);

     foreach($diff_arraylist as $valued)
     {
        
       $difflist[]=$valued;
         
     }
     
     for($r=0;$r<sizeof($difflist);$r++)
     {
        
         $list_pairset[]=array_merge((array)$required_prodlists,(array)$difflist[$r]);
     }
     
    ?>
    <script type="text/javascript" language="javascript">
    var productid = [];
    </script>
    <script type="text/javascript" language="javascript">
        $(function() {
            $('#testrailvisible19').slimscroll({
                width: '410px',
                height:'366px',
                size: '7px',
                alwaysVisible: true,
                railVisible: true,
                allowPageScroll: false,
                railColor: '#F4F4F4',
                opacity: 1,
                color: '#d9d9d9',
                 wheelStep: 1,
            });

            $('#testrailvisible20').slimscroll({
                width: '410px',
                height:'366px',
                size: '7px',
                alwaysVisible: true,
                railVisible: true,
                allowPageScroll: false,
                railColor: '#F4F4F4',
                opacity: 1,
                color: '#d9d9d9',
                 wheelStep: 1,
            });


            $("#list13").sortable({
                connectWith: ".droptrue4",
                dropOnEmpty: true,
                items: "div[class='draglinkleft']",
                receive: function(event, ui) {
                    $("div[class=draglinkright]").each(function(){ 
                        if($(this).parent().attr('id')=='list13'){                            
                            fn_movealllistitems('list13','list14',1,$(this).attr('id').replace('list14_',''));
                            fn_saveselecttag();
                        }
                    });
                }
            });

            $("#list14" ).sortable({
                connectWith: ".droptrue4",
                dropOnEmpty: true,
                receive: function(event, ui) {
                    $("div[class=draglinkleft]").each(function(){ 
                        if($(this).parent().attr('id')=='list14'){                            
                            fn_movealllistitems('list13','list14',1,$(this).attr('id').replace('list13_',''));
                            fn_saveselecttag();
                        }
                    });
                }
            });
        }); 
     
                            
    </script>
   
    <div class="row rowspacer">
        <div id="tagrequiredproducts">
            <div class="six columns">
                <div class="dragndropcol">
                    <div class="dragtitle"> Selected Products<span class="fldreq">*</span></div>
                    <div class="dragWell" id="testrailvisible19" >
                        <div id="list13" class="dragleftinner droptrue4">
                            <div class="draglinkleftSearch" id="s_list13" >
                                <dl class='field row'>
                                    <dt class='text'>
                                        <input placeholder='Search' type='text' id="list_13_search" name="list_13_search" onKeyUp="search_list(this,'#list13');" />                                        
                                    </dt>
                                </dl>
                            </div>                            
                            <?php
                             $selectproductsdetails =array();
                          
                            for($i=0;$i<sizeof($selected_products);$i++)
                            {
                               
                                 $bool=true;
                                 for($j=0;$j<sizeof($req_productdetails);$j++)
                                 {
                                    
                                      if($req_productdetails[$j]['id'] == $selected_products[$i]['id'] and $req_productdetails[$j]['type'] ==$selected_products[$i]['type'])
                                        {
                                            $bool=false;
                                           
                                        }
                                 }
                                if($bool)
                                {
                                  

                                    ?>
                                    <div name="<?php echo $selected_products[$i]['type']; ?>" class="draglinkleft" id="list13_<?php echo $selected_products[$i]['id']."_".$selected_products[$i]['type']; ?>" >
                                       <div class="dragItemLable tooltip" id="<?php echo  $selected_products[$i]['id']."~".$selected_products[$i]['type']."~".$selected_products[$i]['productid']."~".$selected_products[$i]['pname']; ?>" title="<?php echo $selected_products[$i]['pname'];?>"><?php echo $selected_products[$i]['shortname'];?></div>
                                        <div class="clickable" id="clck_<?php echo $selected_products[$i]['productid']; ?>" onclick="fn_movealllistitemsproducts('list13','list14',1,'<?php echo $selected_products[$i]['id']."_".$selected_products[$i]['type']; ?>');fn_saveselecttag();"></div>
                                    </div>
                               <?php
                                   
                                }
                           
                                }     
                            ?>
                        </div>
                    </div>
                    <div class="dragAllLink" onclick="fn_movealllistitemsproducts('list13','list14',0);fn_saveselecttag();" style="cursor: pointer;cursor:hand;width: 145px;float: right;" >add all products</div>
                </div>
            </div>

            <div class="six columns">
                <div class="dragndropcol">
                    <div class="dragtitle"> Required Products</div>
                    <div class="dragWell" id="testrailvisible20">
                        <div id="list14" class="dragleftinner droptrue4">
                             <?php
                                                
                                for($k=0;$k<sizeof($req_productdetails);$k++) {
                                    ?>
                                    <div name="<?php echo $req_productdetails[$k]['type']; ?>" class="draglinkright" id="list14_<?php  echo $req_productdetails[$k]['id']."_".$req_productdetails[$k]['type']; ?>" >
                                        <div class="dragItemLable tooltip" id="<?php echo  $req_productdetails[$k]['id']."~".$req_productdetails[$k]['type']."~".$req_productdetails[$k]['productid']; ?>" title="<?php echo $selected_products[$i]['pname'];?>"><?php echo $req_productdetails[$k]['shortname'];?></div>
                                        <div class="clickable" id="clck_<?php echo $req_productdetails[$k]['productid']; ?>" onclick="fn_movealllistitemsproducts('list13','list14',1,'<?php echo $req_productdetails[$k]['id']."_".$req_productdetails[$k]['type']; ?>');fn_validateproducts();fn_saveselecttag();"></div>
                                    </div>
                                    <?php
                                    
                                }
                                
                              ?>
                        
                        </div>
                    </div>
                    <div class="dragAllLink" onclick="fn_movealllistitemsproducts('list14','list13',0);fn_saveselecttag();" style="cursor: pointer;cursor:hand;width: 145px;float: right;" >remove all products</div>
                </div>
            </div>
           </div>
    </div>
   
    <input type="hidden" value="" id="hidlist14" name="hidlist14" />
    <input type="hidden" value="" id="list14rec" name="list14rec" />
    
   
    <?php   
  
}
/*
 * ends for selecting production by tag types
 */         
if($oper=="removerightroducts" and $oper != " " )
{
    
      
    $rptid = isset($method['rptid']) ? $method['rptid'] : 0;
    $remtagproducts = isset($method['remtagproducts']) ? $method['remtagproducts'] : 0;
    $remtagproducts = explode(',',$remtagproducts);

    if($sessmasterprfid==2 or $sessmasterprfid==3 )
    { 

        for($i=0;$i<sizeof($remtagproducts);$i++) {
           
           $ptag_type =$ObjDB->QueryObject("SELECT fld_item_id as titleid,fld_tag_type as tagtype FROM itc_main_tag_mapping where fld_tag_id='".$remtagproducts[$i]."' AND (fld_tag_type = '1' OR fld_tag_type = '23' OR fld_tag_type = '3' OR fld_tag_type = '4') AND fld_access = '1'");
             if($ptag_type->num_rows > 0){
                 static $ipl_cnt = 0;
                 static $unit_cnt = 0;
                 static $module_cnt = 0;
                 static $mathmod_cnt = 0;
                 
                while($rowqry = $ptag_type->fetch_assoc())
                {
                extract($rowqry);
                    if($tagtype == '1')
                    {
                       
                         $qryipls="SELECT a.fld_id AS id, CONCAT(a.fld_ipl_name,' ',b.fld_version) AS nam,fn_shortname(a.fld_ipl_name,2) AS shortname, 1 AS typ, a.fld_asset_id AS assetid,'IPL' AS titlename
                                        FROM itc_ipl_master  AS a
                                        LEFT JOIN itc_ipl_version_track AS b ON b.fld_ipl_id=a.fld_id
                                        WHERE a.fld_access='1' AND a.fld_delstatus='0' AND b.fld_delstatus='0' AND b.fld_zip_type='1' AND a.fld_id = '".$titleid."'";
                         ++$ipl_cnt;
                        
                         if($ipl_cnt == 1) {
                             $group_qryipls = $qryipls;
                         }
                         elseif($ipl_cnt >1) {
                             $group_qryipls = $group_qryipls." UNION ".$qryipls;
                             
                         }
                    }
                    if($tagtype == '4')
                    {
                        $qryunits="SELECT fld_id AS id, fld_unit_name AS nam,fn_shortname(fld_unit_name,2) AS shortname, 2 AS typ, fld_asset_id as assetid,'Unit' AS titlename
	  			FROM itc_unit_master 
				WHERE fld_delstatus='0' AND fld_id = '".$titleid."'";
                        ++$unit_cnt;
                        
                         if($unit_cnt == 1) {
                             $group_qryunit = $qryunits;
                         }
                         elseif($unit_cnt >1) {
                             $group_qryunit = $group_qryunit." UNION ".$qryunits;
                             
                         }
                    }
                    if($tagtype == '3')
                    {
                        $qrymodules="SELECT a.fld_id AS id, CONCAT(a.fld_module_name,' ',b.fld_version) AS nam,fn_shortname(a.fld_module_name,2) AS shortname, 3 AS typ, a.fld_asset_id AS assetid,'Module' AS titlename 
					FROM itc_module_master AS a 
					LEFT JOIN itc_module_version_track AS b ON b.fld_mod_id=a.fld_id
					WHERE a.fld_delstatus='0' AND b.fld_delstatus='0' AND a.fld_id = '".$titleid."'";
                        ++$module_cnt;
                        
                         if($module_cnt == 1) {
                             $group_qrymodule = $qrymodules;
                         }
                         elseif($module_cnt >1) {
                             $group_qrymodule = $group_qrymodule." UNION ".$qrymodules;
                             
                         }
                    }
                    if($tagtype == '23')
                    {
                        $qrymathmodules="SELECT a.fld_id AS id, CONCAT(a.fld_mathmodule_name,' ',b.fld_version) AS nam,fn_shortname(a.fld_mathmodule_name,2) AS shortname, 4 AS typ, a.fld_asset_id AS assetid,'Math Module' AS titlename 
						FROM itc_mathmodule_master AS a 
						LEFT JOIN itc_module_version_track AS b ON b.fld_mod_id=a.fld_module_id
						WHERE a.fld_delstatus='0' AND b.fld_delstatus='0' AND a.fld_id = '".$titleid."'";
                        ++$mathmod_cnt;
                        
                         if($mathmod_cnt == 1) {
                             $group_qrymathmod = $qrymathmodules;
                         }
                         elseif($mathmod_cnt >1) {
                             $group_qrymathmod = $group_qrymathmod." UNION ".$qrymathmodules;
                             
                         }
                    }
                
                }  // end the while loop
             }
        }    // end the for loop         
         
    }    // end the if condition
	else if($sessmasterprfid==6){	//Lessons listed based on available licenses for a distict
    	for($i=0;$i<sizeof($remtagproducts);$i++) {
    		$ptag_type =$ObjDB->QueryObject("SELECT fld_item_id as titleid,fld_tag_type as tagtype FROM itc_main_tag_mapping where fld_tag_id='".$remtagproducts[$i]."' AND (fld_tag_type = '1' OR fld_tag_type = '23' OR fld_tag_type = '3' OR fld_tag_type = '4') AND fld_access = '1'");
             if($ptag_type->num_rows > 0){
                 static $ipl_cnt = 0;
                 static $unit_cnt = 0;
                 static $module_cnt = 0;
                 static $mathmod_cnt = 0;
                 while($rowqry = $ptag_type->fetch_assoc())
                {
                	extract($rowqry);
                	if($tagtype == '1')
                    {
                    	$qryipls = "SELECT c.fld_id AS id, CONCAT(c.fld_ipl_name,' ',d.fld_version) AS nam,fn_shortname(c.fld_ipl_name,2) AS shortname, 1 AS typ ,c.fld_asset_id AS assetid,'IPL' AS titlename 
					FROM itc_license_track AS a 
					LEFT JOIN itc_license_cul_mapping AS b ON a.fld_license_id=b.fld_license_id 
					LEFT JOIN itc_ipl_master AS c ON b.`fld_lesson_id`=c.fld_id
					LEFT JOIN itc_ipl_version_track AS d ON d.fld_ipl_id=c.fld_id 
					WHERE a.fld_delstatus='0' AND b.fld_active='1' AND a.fld_district_id='".$sendistid."' 
						AND a.fld_start_date<=NOW() AND a.fld_end_date>=NOW() AND c.fld_delstatus='0' AND c.fld_access='1'
						AND d.fld_zip_type='1' AND d.fld_delstatus='0' AND c.fld_id = '".$titleid."' 
					GROUP BY c.fld_id";
					++$ipl_cnt;
                        
                         if($ipl_cnt == 1) {
                             $group_qryipls = $qryipls;
                         }
                         elseif($ipl_cnt >1) {
                             $group_qryipls = $group_qryipls." UNION ".$qryipls;
                    	 }
            		}
            		if($tagtype == '4')
                    {
                    	$qryunits = "SELECT c.fld_id AS id, c.fld_unit_name AS nam,fn_shortname(c.fld_unit_name,2) AS shortname, 2 AS typ, c.fld_asset_id as assetid,'Unit' AS titlename
					FROM itc_license_track AS a 
					LEFT JOIN itc_license_cul_mapping AS b ON a.fld_license_id=b.fld_license_id 
					LEFT JOIN itc_unit_master AS c ON b.`fld_unit_id`=c.fld_id 
					WHERE a.fld_delstatus='0' AND b.fld_active='1' AND a.fld_district_id='".$sendistid."' AND a.fld_start_date<=NOW() 
						AND a.fld_end_date>=NOW() AND c.fld_delstatus='0' AND c.fld_id = '".$titleid."'
					GROUP BY c.fld_id";
					++$unit_cnt;
                        
                         if($unit_cnt == 1) {
                             $group_qryunit = $qryunits;
                         }
                         elseif($unit_cnt >1) {
                             $group_qryunit = $group_qryunit." UNION ".$qryunits;
                             
                         }
                    }
                    if($tagtype == '3')
                    {
                    	$qrymodules = "SELECT b.fld_module_id AS id, CONCAT(a.fld_module_name,' ',d.fld_version) AS nam,fn_shortname(a.fld_module_name,2) AS shortname, 3 AS typ , a.fld_asset_id AS assetid,'Module' AS titlename  
						FROM itc_module_master AS a 
						LEFT JOIN itc_license_mod_mapping AS b ON a.fld_id=b.fld_module_id 
						LEFT JOIN itc_license_track AS c ON b.fld_license_id=c.fld_license_id 
						LEFT JOIN itc_module_version_track AS d ON d.fld_mod_id=b.fld_module_id
						WHERE a.fld_delstatus='0' AND c.fld_district_id='".$sendistid."' AND c.fld_school_id='0' AND c.fld_delstatus='0' 
							AND b.fld_active='1' AND c.fld_start_date<=NOW() AND c.fld_end_date>=NOW() AND b.fld_type=1 AND d.fld_delstatus='0' AND a.fld_id = '".$titleid."'
						GROUP BY b.fld_module_id";
						++$module_cnt;
                        
                         if($module_cnt == 1) {
                             $group_qrymodule = $qrymodules;
                         }
                         elseif($module_cnt >1) {
                             $group_qrymodule = $group_qrymodule." UNION ".$qrymodules;
                             
                         }
                    }
					if($tagtype == '23')
                    {
                    	$qrymathmodules = "SELECT b.fld_module_id AS id, CONCAT(a.fld_mathmodule_name,' ',d.fld_version) AS nam,fn_shortname(a.fld_mathmodule_name,2) AS shortname, 4 AS typ , a.fld_asset_id AS assetid,'Math Module' AS titlename  
							FROM itc_mathmodule_master AS a 
							LEFT JOIN itc_license_mod_mapping AS b ON a.fld_id=b.fld_module_id 
							LEFT JOIN itc_license_track AS c ON b.fld_license_id=c.fld_license_id
							LEFT JOIN itc_module_version_track AS d ON d.fld_mod_id=b.fld_module_id 
							WHERE a.fld_delstatus='0' AND c.fld_district_id='".$sendistid."' AND c.fld_school_id='0' AND c.fld_delstatus='0' AND d.fld_delstatus='0'
								AND b.fld_active='1' AND c.fld_start_date<=NOW() AND c.fld_end_date>=NOW() AND b.fld_type=2 AND a.fld_id = '".$titleid."'
							GROUP BY b.fld_module_id";	
							++$mathmod_cnt;
                        
                         if($mathmod_cnt == 1) {
                             $group_qrymathmod = $qrymathmodules;
                         }
                         elseif($mathmod_cnt >1) {
                             $group_qrymathmod = $group_qrymathmod." UNION ".$qrymathmodules;
                    	}
                    }

            	}
             }
    	}
    }
    else
    {   //Lessons listed based on available licenses for a school or an individual user

    	for($i=0;$i<sizeof($remtagproducts);$i++) {
    		$ptag_type =$ObjDB->QueryObject("SELECT fld_item_id as titleid,fld_tag_type as tagtype FROM itc_main_tag_mapping where fld_tag_id='".$remtagproducts[$i]."' AND (fld_tag_type = '1' OR fld_tag_type = '23' OR fld_tag_type = '3' OR fld_tag_type = '4') AND fld_access = '1'");
             if($ptag_type->num_rows > 0){
                 static $ipl_cnt = 0;
                 static $unit_cnt = 0;
                 static $module_cnt = 0;
                 static $mathmod_cnt = 0;
                 while($rowqry = $ptag_type->fetch_assoc())
                {
                	extract($rowqry);
                	if($tagtype == '1')
                    {

					$qryipls = "SELECT c.fld_id AS id, CONCAT(c.fld_ipl_name,' ',d.fld_version) AS nam,fn_shortname(c.fld_ipl_name,2) AS shortname, 1 AS typ , c.fld_asset_id AS assetid,'IPL' AS titlename  
					FROM itc_license_track AS a 
					LEFT JOIN itc_license_cul_mapping AS b ON a.fld_license_id=b.fld_license_id 
					LEFT JOIN itc_ipl_master AS c ON b.`fld_lesson_id`=c.fld_id 
					LEFT JOIN itc_ipl_version_track AS d ON d.fld_ipl_id=c.fld_id
					WHERE a.fld_delstatus='0' AND b.fld_active='1' AND a.fld_school_id='".$schoolid."' AND a.fld_user_id='".$indid."' 
						AND a.fld_start_date<=NOW() AND a.fld_end_date>=NOW() AND c.fld_delstatus='0' AND c.fld_access='1'
						AND d.fld_zip_type='1' AND d.fld_delstatus='0' AND c.fld_id = '".$titleid."' 
					GROUP BY c.fld_id";	
					++$ipl_cnt;
                        
                         if($ipl_cnt == 1) {
                             $group_qryipls = $qryipls;
                         }
                         elseif($ipl_cnt >1) {
                             $group_qryipls = $group_qryipls." UNION ".$qryipls;
                    	 }
                    }
                    if($tagtype == '4')
                    {
                    	$qryunits = "SELECT c.fld_id AS id, c.fld_unit_name AS nam,fn_shortname(c.fld_unit_name,2) AS shortname, 2 AS typ , c.fld_asset_id as assetid,'Unit' AS titlename 
					FROM itc_license_track AS a 
					LEFT JOIN itc_license_cul_mapping AS b ON a.fld_license_id=b.fld_license_id 
					LEFT JOIN itc_unit_master AS c ON b.`fld_unit_id`=c.fld_id 
					WHERE a.fld_delstatus='0' AND b.fld_active='1' AND a.fld_school_id='".$schoolid."' AND a.fld_user_id='".$indid."' 
						AND a.fld_start_date<=NOW() AND a.fld_end_date>=NOW() AND c.fld_delstatus='0' AND c.fld_id = '".$titleid."' 
					GROUP BY c.fld_id";
					++$unit_cnt;
                        
                         if($unit_cnt == 1) {
                             $group_qryunit = $qryunits;
                         }
                         elseif($unit_cnt >1) {
                             $group_qryunit = $group_qryunit." UNION ".$qryunits;
                             
                         }
                    }
                    if($tagtype == '3')
                    {
                    	$qrymodules = "SELECT b.fld_module_id AS id, 
						CONCAT(a.fld_module_name,' ',d.fld_version) AS nam,fn_shortname(a.fld_module_name,2) AS shortname, 3 AS typ , a.fld_asset_id AS assetid,'Module' AS titlename  
						FROM itc_module_master AS a 
						LEFT JOIN itc_license_mod_mapping AS b ON a.fld_id=b.fld_module_id 
						LEFT JOIN itc_license_track AS c ON b.fld_license_id=c.fld_license_id
						LEFT JOIN itc_module_version_track AS d ON d.fld_mod_id=b.fld_module_id
						WHERE a.fld_delstatus='0' AND c.fld_school_id='".$schoolid."' AND c.fld_user_id='".$indid."' AND c.fld_delstatus='0' 
							AND b.fld_active='1' AND c.fld_start_date<=NOW() AND c.fld_end_date>=NOW() AND b.fld_type=1 AND d.fld_delstatus='0' AND a.fld_id = '".$titleid."'
						GROUP BY b.fld_module_id";
						++$module_cnt;
                        
                         if($module_cnt == 1) {
                             $group_qrymodule = $qrymodules;
                         }
                         elseif($module_cnt >1) {
                             $group_qrymodule = $group_qrymodule." UNION ".$qrymodules;
                             
                         }
                    }
					if($tagtype == '23')
                    {
                    	$qrymathmodules = "SELECT b.fld_module_id AS id, CONCAT(a.fld_mathmodule_name,' ',d.fld_version) AS nam,fn_shortname(a.fld_mathmodule_name,2) AS shortname, 4 AS typ , a.fld_asset_id AS assetid,'Math Module' AS titlename 
							FROM itc_mathmodule_master AS a 
							LEFT JOIN itc_license_mod_mapping AS b ON a.fld_id=b.fld_module_id 
							LEFT JOIN itc_license_track AS c ON b.fld_license_id=c.fld_license_id 
							LEFT JOIN itc_module_version_track AS d ON d.fld_mod_id=b.fld_module_id
							WHERE a.fld_delstatus='0' AND c.fld_school_id='".$schoolid."' AND c.fld_user_id='".$indid."' 
								AND c.fld_delstatus='0' AND b.fld_active='1' AND c.fld_start_date<=NOW() AND c.fld_end_date>=NOW() AND b.fld_type=2 AND d.fld_delstatus='0' AND a.fld_id = '".$titleid."'
							GROUP BY b.fld_module_id";	
							++$mathmod_cnt;
                        
                         if($mathmod_cnt == 1) {
                             $group_qrymathmod = $qrymathmodules;
                         }
                         elseif($mathmod_cnt >1) {
                             $group_qrymathmod = $group_qrymathmod." UNION ".$qrymathmodules;
                    	}
                    }



                }   /* end the while loop */
            }   /* end the if($ptag_type->num_rows > 0) */
        }   /* end the loop of selecttagproducts */


    }   /* end of the else part */


    if($group_qryipls != '' && $group_qryunit == '' && $group_qrymodule == '' && $group_qrymathmod == '')
            $qry = $group_qryipls." ORDER BY nam";
    elseif ($group_qryipls == '' && $group_qryunit != '' && $group_qrymodule == '' && $group_qrymathmod == '')
        $qry = $group_qryunit." ORDER BY nam";
    elseif ($group_qryipls == '' && $group_qryunit == '' && $group_qrymodule != '' && $group_qrymathmod == '')
        $qry = $group_qrymodule." ORDER BY nam";
    elseif ($group_qryipls == '' && $group_qryunit == '' && $group_qrymodule == '' && $group_qrymathmod != '')
        $qry = $group_qrymathmod." ORDER BY nam";
    elseif ($group_qryipls != '' && $group_qryunit != '' && $group_qrymodule == '' && $group_qrymathmod == '')
        $qry = $group_qryipls." UNION ALL ".$group_qryunit." ORDER BY nam";
    elseif ($group_qryipls != '' && $group_qryunit == '' && $group_qrymodule != '' && $group_qrymathmod == '')
        $qry = $group_qryipls." UNION ALL ".$group_qrymodule." ORDER BY nam";
    elseif ($group_qryipls != '' && $group_qryunit == '' && $group_qrymodule == '' && $group_qrymathmod != '')
        $qry = $group_qryipls." UNION ALL ".$group_qrymathmod." ORDER BY nam";
    elseif ($group_qryipls == '' && $group_qryunit != '' && $group_qrymodule != '' && $group_qrymathmod == '')
        $qry = $group_qryunit." UNION ALL ".$group_qrymodule." ORDER BY nam";
    elseif ($group_qryipls == '' && $group_qryunit != '' && $group_qrymodule == '' && $group_qrymathmod != '')
        $qry = $group_qryunit." UNION ALL ".$group_qrymathmod." ORDER BY nam";
    elseif ($group_qryipls == '' && $group_qryunit == '' && $group_qrymodule != '' && $group_qrymathmod != '')
        $qry = $group_qrymodule." UNION ALL ".$group_qrymathmod." ORDER BY nam";
    elseif ($group_qryipls != '' && $group_qryunit != '' && $group_qrymodule != '' && $group_qrymathmod == '')
        $qry = $group_qryipls." UNION ALL ".$group_qryunit." UNION ALL ".$group_qrymodule." ORDER BY nam";
    elseif ($group_qryipls != '' && $group_qryunit != '' && $group_qrymodule == '' && $group_qrymathmod != '')
        $qry = $group_qryipls." UNION ALL ".$group_qryunit." UNION ALL ".$group_qrymathmod." ORDER BY nam";
    elseif ($group_qryipls == '' && $group_qryunit != '' && $group_qrymodule != '' && $group_qrymathmod != '')
        $qry = $group_qryunit." UNION ALL ".$group_qrymodule." UNION ALL ".$group_qrymathmod." ORDER BY nam";
    elseif ($group_qryipls != '' && $group_qryunit == '' && $group_qrymodule != '' && $group_qrymathmod != '')
        $qry = $group_qryipls." UNION ALL ".$group_qrymodule." UNION ALL ".$group_qrymathmod." ORDER BY nam";
    elseif ($group_qryipls != '' && $group_qryunit != '' && $group_qrymodule != '' && $group_qrymathmod != '')
        $qry = $group_qryipls." UNION ALL ".$group_qryunit." UNION ALL ".$group_qrymodule." UNION ALL ".$group_qrymathmod." ORDER BY nam";
    else 
           $qry = '';
      
   
     $selproductdetails=array();
     $productdetails=array();
      if($qry != '')  {
	  	$productqry = $ObjDB->QueryObject($qry);
		if($productqry->num_rows > 0){
			$i=0;
			while($productqryrow = $productqry->fetch_assoc()){
				extract($productqryrow);
				if($assetid=='')
				{
					$assetid='MO.ENVM.3.0.0a';
                                        
                                        
				}
				$productdetails[]=array("id"=>$id,"nam"=>$nam,"type"=>$typ);
				$i++;
			}
		}
      }
 
       $remove_selectprod1 = array();
       $remove_selectprod2 = array();
       $remove_selectprod3 = array();


		for($m=0;$m<sizeof($productdetails);$m++)
		{
			
		  $mprd_id = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM  itc_correlation_products 
                                                                                                                     WHERE fld_prd_type ='".$productdetails[$m]['type']."' 
                                                                                                                    AND fld_prd_sys_id='".$productdetails[$m]['id']."'");
  
			array_push($remove_selectprod1, $mprd_id);
		}
			 $selected_correpqry = $ObjDB->QueryObject("SELECT fld_prd_id as prodid,fld_type as types FROM  itc_bestfit_rpt_products 
			 												WHERE fld_rpt_data_id='".$rptid."' AND fld_tagflag ='1' AND fld_delstatus = '0'");

		if($selected_correpqry->num_rows > 0){
			
			while($cor_productqryrow = $selected_correpqry->fetch_assoc()){
				extract($cor_productqryrow);

				array_push($remove_selectprod3, $prodid.'_'.$types);
				array_push($remove_selectprod2,$prodid);
				
			}
		}


		$remproduct_result = array_diff($remove_selectprod2, $remove_selectprod1);
		$remproduct_result = array_values($remproduct_result); 
		for($n=0;$n<sizeof($remproduct_result);$n++)
		{
			$ObjDB->NonQuery("UPDATE itc_bestfit_rpt_products SET fld_delstatus=1 
										       WHERE fld_rpt_data_id='".$rptid."' AND fld_tagflag ='1' AND fld_prd_id ='".$remproduct_result[$n]."'");
		}		
  
    echo json_encode($productdetails);
}
if($oper=="hidereprd" and $oper!="")
{
$ObjDB->NonQuery("UPDATE itc_bestfit_rpt_reqproducts SET fld_delstatus='1' WHERE fld_id='".$rptid."'");
echo "sucess";

}

	if($oper=="reqcombi" and $oper!= "" )
		  {
	$list_pairset = isset($method['list_pairset']) ? $method['list_pairset'] : 0;
			   
                                      }
           
	  	/*--- Save Step2 Correlation Standard Info---*/
	if($oper=="savestep2" and $oper != " " )
	{
           
		$rptid = isset($method['rptid']) ? $method['rptid'] : 0;
                $productid= isset($method['productid']) ? $method['productid'] : 0;
                $tagproductid= isset($method['tagproductid']) ? $method['tagproductid'] : 0;
                $reqproductid= isset($method['reqproductid']) ? $method['reqproductid'] : 0;
                $tagreqproductid= isset($method['tagreqproductid']) ? $method['tagreqproductid'] : 0;
                $notitrecomm= isset($method['notitrecomm']) ? $method['notitrecomm'] : 0;
                $tagpid= isset($method['tagpid']) ? $method['tagpid'] : 0;
                $maxrecom= isset($method['maxrecom']) ? $method['maxrecom'] : 0;
                $totcombi= isset($method['totcombi']) ? $method['totcombi'] : 0;
                $cntreq_prod= isset($method['cntreq_prod']) ? $method['cntreq_prod'] : 0;
		$titletype= isset($method['titletype']) ? $method['titletype'] : 0;
		$show_titletype= isset($method['show_titletype']) ? $method['show_titletype'] : 0;
               
                $truchk= isset($method['truchk']) ? $method['truchk'] : 0;
                $productid=explode(',',$productid);
                $tagproductid=explode(',',$tagproductid);
                $tagreqproductid=explode(',',$tagreqproductid);
               
                $reqproductid=explode(',',$reqproductid);
               
		$productid = array_filter($productid);
		$tagproductid = array_filter($tagproductid);
               
 	   	$ObjDB->NonQuery("UPDATE itc_bestfit_report_data SET fld_step_id='2',fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$rptid."'");
               
		$ObjDB->NonQuery("UPDATE itc_bestfit_rpt_products SET fld_delstatus='1' WHERE fld_rpt_data_id='".$rptid."'");
                $ObjDB->NonQuery("UPDATE itc_bestfit_rpt_reqproducts SET fld_delstatus='1' WHERE fld_rpt_data_id='".$rptid."'");
                $ObjDB->NonQuery("UPDATE itc_bestfit_report_data SET fld_flag='".$truchk."' WHERE fld_id='".$rptid."'");
                
              if(!empty($productid)) { 
                  
               for($i=0;$i<sizeof($productid);$i++)
		{
			$productdetails=explode('~',$productid[$i]);
			             
			            
                        $mprd_id = $ObjDB->SelectSingleValueInt("SELECT fld_id
                                                                    FROM itc_correlation_products 
                                                                    WHERE fld_prd_id='".$productdetails[2]."' AND fld_prd_type='".$productdetails[1]."'");
                       $productname = $ObjDB->SelectSingleValue("SELECT fld_prd_name
                                                                    FROM itc_correlation_products 
                                                                    WHERE fld_prd_sys_id='".$productdetails[0]."' AND fld_prd_type='".$productdetails[1]."'");
                        
                        $cnt=$ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id)
                                                                FROM  itc_bestfit_rpt_products 
                                                                WHERE fld_rpt_data_id='".$rptid."' AND fld_prd_id='".$mprd_id."'");
                        
                  if($cnt==0)
			{ 
                        if ($show_titletype  ==  '0') {
                            
                           $ObjDB->NonQuery("INSERT INTO itc_bestfit_rpt_products(fld_rpt_data_id,fld_type,fld_prd_id,fld_product_name,fld_product_id,fld_product_system_id,fld_notitle,fld_maxrecom,fld_totcombi,fld_flag) 
			 				VALUES ('".$rptid."','".$productdetails[1]."','".$mprd_id."','".$productname."','".$productdetails[2]."','".$productdetails[0]."','".$notitrecomm."','".$maxrecom."','".$totcombi."','".$truchk."')");
							
							$ObjDB->NonQuery("UPDATE itc_bestfit_report_data SET fld_show_alltype='10', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$rptid."'");
                        }
						  else{
						   $ObjDB->NonQuery("INSERT INTO itc_bestfit_rpt_products(fld_rpt_data_id,fld_type,fld_prd_id,fld_product_name,fld_product_id,fld_product_system_id,fld_notitle,fld_maxrecom,fld_totcombi,fld_flag) 
			 				VALUES ('".$rptid."','".$productdetails[1]."','".$mprd_id."','".$productname."','".$productdetails[2]."','".$productdetails[0]."','".$notitrecomm."','".$maxrecom."','".$totcombi."','".$truchk."')");
                        
						  }
						}
                        
		   else if($cnt!==0)
			{ 
			 if ($show_titletype  ==  '0') {
                            
                           $ObjDB->NonQuery("UPDATE itc_bestfit_rpt_products 
							SET fld_delstatus='0',fld_type='".$productdetails[1]."',fld_product_name='".$productname."',fld_product_id='".$productdetails[2]."', fld_notitle='".$notitrecomm."',fld_maxrecom='".$maxrecom."',fld_totcombi='".$totcombi."', fld_flag='".$truchk."'
							WHERE fld_rpt_data_id='".$rptid."' AND fld_prd_id = '".$mprd_id."'");
							
							$ObjDB->NonQuery("UPDATE itc_bestfit_report_data SET fld_show_alltype='10', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$rptid."'");
                         }
						 else{
						  $ObjDB->NonQuery("UPDATE itc_bestfit_rpt_products 
							SET fld_delstatus='0',fld_type='".$productdetails[1]."',fld_product_name='".$productname."',fld_product_id='".$productdetails[2]."', fld_notitle='".$notitrecomm."',fld_maxrecom='".$maxrecom."',fld_totcombi='".$totcombi."', fld_flag='".$truchk."'
							WHERE fld_rpt_data_id='".$rptid."' AND fld_prd_id = '".$mprd_id."'");
						 }
			}
                         
                      }   
                   }    
        
         if(!empty($tagproductid)) {
           
               for($i=0;$i<sizeof($tagproductid);$i++)
		{
                  
			$productdetails=explode('~',$tagproductid[$i]);
			         
                        $mprd_id = $ObjDB->SelectSingleValueInt("SELECT fld_id
                                                                    FROM itc_correlation_products 
                                                                    WHERE fld_prd_id='".$productdetails[2]."'");
                       $productname = $ObjDB->SelectSingleValue("SELECT fld_prd_name
                                                                    FROM itc_correlation_products 
                                                                    WHERE fld_prd_sys_id='".$productdetails[0]."' AND fld_prd_type='".$productdetails[1]."'");
                        
                        $cnt=$ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id)
                                                                FROM  itc_bestfit_rpt_products 
                                                                WHERE fld_rpt_data_id='".$rptid."' AND fld_prd_id='".$mprd_id."'");
                        
                  if($cnt==0)
			{ 
                            
                           $ObjDB->NonQuery("INSERT INTO itc_bestfit_rpt_products(fld_rpt_data_id,fld_type,fld_prd_id,fld_product_name,fld_product_id,fld_product_system_id,fld_notitle,fld_maxrecom,fld_totcombi,fld_flag,fld_tagflag) 
			 				VALUES ('".$rptid."','".$productdetails[1]."','".$mprd_id."','".$productname."','".$productdetails[2]."','".$productdetails[0]."','".$notitrecomm."','".$maxrecom."','".$totcombi."','".$truchk."','1')");
							
							  $ObjDB->NonQuery("UPDATE itc_bestfit_report_data SET fld_tagproduct_id='".$tagpid."',fld_updated_by='".$uid."',fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$rptid."' AND fld_delstatus='0'");
                        }
                        
		   else if($cnt!==0)
			{ 
                            
                           $ObjDB->NonQuery("UPDATE itc_bestfit_rpt_products 
                                                    SET fld_delstatus='0',fld_type='".$productdetails[1]."',fld_product_name='".$productname."',"
                                   . "fld_product_id='".$productdetails[2]."', fld_notitle='".$notitrecomm."',fld_maxrecom='".$maxrecom."',fld_totcombi='".$totcombi."', fld_flag='".$truchk."',fld_tagflag = '1'
                                                    WHERE fld_rpt_data_id='".$rptid."' AND fld_prd_id = '".$mprd_id."'");
													
						$ObjDB->NonQuery("UPDATE itc_bestfit_report_data SET fld_tagproduct_id='".$tagpid."',fld_updated_by='".$uid."',fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$rptid."' AND fld_delstatus='0'");
                         }
                         
                      }   
                   }    
                  if($cntreq_prod != 0 && $truchk!=0 )
                    {
                      $reqproductid = array_filter($reqproductid);
	$tagreqproductid = array_filter($tagreqproductid);
                     
                        if(!empty($reqproductid)) {
                     
                        for($j=0;$j<sizeof($reqproductid);$j++)
                        {
                            $reqproductids=explode('~',$reqproductid[$j]);
                           
                            $prd_id = $ObjDB->SelectSingleValueInt("SELECT fld_id 
                                                                    FROM itc_correlation_products 
                                                                    WHERE fld_prd_type ='".$reqproductids[1]."' 
                                                                    AND fld_prd_sys_id='".$reqproductids[0]."'");
                            $cnt1=$ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
                                                                FROM  itc_bestfit_rpt_reqproducts 
                                                                WHERE fld_rpt_data_id='".$rptid."' AND fld_prod_id='".$prd_id."'");
                            if($cnt1==0)
                            {
                            
                            $ObjDB->NonQuery("INSERT INTO itc_bestfit_rpt_reqproducts(fld_rpt_data_id,fld_type,fld_req_product,fld_product_id,fld_product_sys_id, fld_prod_id, fld_req_notitle,fld_req_maxrecom,fld_req_totcombi,fld_flag) 
                                                            VALUES ('".$rptid."','".$reqproductids[1]."','".$reqproductids[3]."','".$reqproductids[2]."','".$reqproductids[0]."','".$prd_id."','".$notitrecomm."','".$maxrecom."','".$totcombi."','".$truchk."')");
                            }
                            else
                              {
                               
                                $prd_name = $ObjDB->SelectSingleValue("SELECT fld_prd_name FROM itc_correlation_products 
                                                                    WHERE fld_prd_id ='".$reqproductids[2]."' ");
                                    
                                $ObjDB->NonQuery("UPDATE itc_bestfit_rpt_reqproducts 
                                                SET fld_delstatus='0',fld_req_product='".$prd_name."', fld_req_notitle='".$notitrecomm."',fld_req_maxrecom='".$maxrecom."',fld_req_totcombi='".$totcombi."',fld_flag='".$truchk."'
                                                WHERE fld_product_sys_id = '".$reqproductids[0]."' AND fld_prod_id='".$prd_id."'");
                               
                             }
                         }
                    }
               
                        if(!empty($tagreqproductid)) {
                   
                        for($j=0;$j<sizeof($tagreqproductid);$j++)
                        {
                            $tagreqproductids=explode('~',$tagreqproductid[$j]);
                          
                            $prd_id = $ObjDB->SelectSingleValueInt("SELECT fld_id 
                                                                    FROM itc_correlation_products 
                                                                    WHERE fld_prd_type ='".$tagreqproductids[1]."' 
                                                                    AND fld_prd_sys_id='".$tagreqproductids[0]."'");
                            
                          
                            $cnt1=$ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
                                                                FROM  itc_bestfit_rpt_reqproducts 
                                                                WHERE fld_rpt_data_id='".$rptid."' AND fld_prod_id='".$prd_id."'");
                            
                         
                            if($cnt1==0)
                            {
                           
                            $ObjDB->NonQuery("INSERT INTO itc_bestfit_rpt_reqproducts(fld_rpt_data_id,fld_type,fld_req_product,fld_product_id,fld_product_sys_id, fld_prod_id, fld_req_notitle,fld_req_maxrecom,fld_req_totcombi,fld_flag,fld_tagflag) 
                                                            VALUES ('".$rptid."','".$tagreqproductids[1]."','".$tagreqproductids[3]."','".$tagreqproductids[2]."','".$tagreqproductids[0]."','".$prd_id."','".$notitrecomm."','".$maxrecom."','".$totcombi."','".$truchk."','1')");
                            }
                            else
                              {
                               
                                $prd_name = $ObjDB->SelectSingleValue("SELECT fld_prd_name FROM itc_correlation_products 
                                                                    WHERE fld_prd_id ='".$tagreqproductids[2]."' ");
                                    
                                $ObjDB->NonQuery("UPDATE itc_bestfit_rpt_reqproducts 
                                                SET fld_delstatus='0',fld_req_product='".$prd_name."', fld_req_notitle='".$notitrecomm."',fld_req_maxrecom='".$maxrecom."',fld_req_totcombi='".$totcombi."',fld_flag='".$truchk."',fld_tagflag = '1'
                                                WHERE fld_product_sys_id = '".$tagreqproductids[0]."' AND fld_prod_id='".$prd_id."'");
                               
                             }
                         }
                        }
                        
                    }
               
             echo $rptid;
        }
	if($oper=="deletereport" and $oper != " " )
	{
		$rptid = isset($method['id']) ? $method['id'] : 0;
            
		$ObjDB->NonQuery("UPDATE itc_bestfit_report_data SET fld_delstatus='1', fld_deleted_by='".$uid."', fld_deleted_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$rptid."'");
	}
        ?>
    
       <?php
    @include("footer.php");