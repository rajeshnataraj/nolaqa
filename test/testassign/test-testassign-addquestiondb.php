<?php
@include("sessioncheck.php");
$date = date("Y-m-d H:i:s");
$oper = isset($_REQUEST['oper']) ? $_REQUEST['oper'] : '';
/*--- Add question to the test ---*/
if($oper == "addquestion" and $oper != '') 
{
		$testid = (isset($_REQUEST['testid'])) ? $_REQUEST['testid'] : 0;
		$list =(isset( $_REQUEST['list'])) ?  $_REQUEST['list'] : '';
		
		$list=explode(",",$list);
		
		$order = $ObjDB->SelectSingleValueInt("SELECT MAX(fld_order_by) FROM 
		                                      itc_test_questionassign WHERE fld_test_id='".$testid."'");
		$orderby=0;
		for($i=0;$i<sizeof($list);$i++)
		{
			if($order==0){
				$orderby=$i+1;
			}
			else{
				$order++;
				$orderby=$order;
			}
			$count=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_test_questionassign 
			                                     WHERE fld_test_id='".$testid."' AND fld_question_id='".$list[$i]."' AND fld_delstatus='0'");
			if($count == 0){
				$chkcount=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_test_questionassign 
				                                        WHERE fld_test_id='".$testid."' AND fld_question_id='".$list[$i]."' AND fld_delstatus='1'");
			if($chkcount == 1){
				$ObjDB->NonQuery("UPDATE `itc_test_questionassign` SET fld_delstatus='0', fld_order_by ='".$orderby."', 
									fld_updated_by='".$uid."',fld_updated_date='".$date."' 
				                 WHERE fld_question_id='".$list[$i]."' AND fld_test_id='".$testid."' AND fld_delstatus='1'");
			}
			else{
					$ObjDB->NonQuery("INSERT INTO itc_test_questionassign(fld_test_id, fld_question_id, fld_order_by, fld_created_by,fld_created_date)
					                  VALUES('".$testid."','".$list[$i]."','".$orderby."','".$uid."','".date('Y-m-d H:i:s')."')");
				}
			}
		}
		echo $testid;
	}	
/*--- Save/Update a test Step ---*/
if($oper == "savequestion" and $oper != '')
{		
	$testid = isset($_REQUEST['testid']) ? $_REQUEST['testid'] : '0';
	$list =(isset( $_REQUEST['list'])) ?  $_REQUEST['list'] : '';
	$totalq =(isset( $_REQUEST['totalq'])) ?  $_REQUEST['totalq'] : '';
	$list=explode(",",$list);
	
	for($i=0;$i<sizeof($list);$i++)
		{	
			$ObjDB->NonQuery("UPDATE `itc_test_questionassign` SET fld_order_by='".$i."', fld_updated_date='".date("Y-m-d H:i:s")."', 
			                  fld_updated_by='".$uid."'  
							  WHERE fld_question_id='".$list[$i]."' AND fld_test_id='".$testid."' AND fld_delstatus='0'");
		}
		$ObjDB->NonQuery("UPDATE itc_test_master SET fld_step_id='2', fld_total_question='".$totalq."', fld_updated_by='".$uid."', 
		                fld_updated_date='".$date."' WHERE fld_id='".$testid."' AND fld_delstatus='0'");
	
}

/*--- Save/Update a test Final Step ---*/
if($oper == "savereview" and $oper != '')
{		
	$testid = isset($_REQUEST['testid']) ? $_REQUEST['testid'] : '0';
	
	$ObjDB->NonQuery("UPDATE itc_test_master SET fld_step_id='1', fld_flag='1', fld_updated_by='".$uid."', 
	                 fld_updated_date='".$date."' WHERE fld_id='".$testid."' AND fld_delstatus='0'");

        $testqry=$ObjDB->QueryObject("SELECT fld_ass_type as asstype,fld_content_id as contentid,fld_product_id as productid,fld_expt as expid,fld_mist as misid
										FROM itc_test_master 
										WHERE fld_id='".$testid."' AND fld_delstatus='0'");
        
                $row = $testqry->fetch_assoc();
		extract($row);
                
                               if($asstype == 1 AND $sessmasterprfid == 2)
                               {
                                    $explicenseqry=$ObjDB->QueryObject("SELECT fld_license_id as licenseid FROM itc_license_exp_mapping where fld_exp_id='".$expid."' and fld_flag='1' group by fld_license_id");

                                    if($explicenseqry->num_rows>0)
                                    {
                                        while($rowexplicense=$explicenseqry->fetch_assoc())
                                        {
                                            extract($rowexplicense);

                                              $cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
                                                                                 FROM itc_license_assessment_mapping 
                                                                                 WHERE fld_license_id='".$licenseid."'  AND fld_assessment_id='".$testid."'");
                                              if($cnt==0)
                                              {
                                                       $ObjDB->NonQuery("INSERT INTO itc_license_assessment_mapping (fld_license_id,fld_assessment_id,fld_access, fld_created_by, fld_created_date)
                                                                                              VALUES('".$licenseid."','".$testid."','1', '".$uid."', '".$date."')");
}
                                              else
                                              {
                                                      $ObjDB->NonQuery("UPDATE itc_license_assessment_mapping 
                                                                                              SET fld_access='1', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
                                                                                              WHERE fld_license_id='".$licenseid."' AND fld_assessment_id='".$testid."'");
                                              }
                                        }
                                    }
                                }
                                
                                
                                if($asstype == 2 AND $sessmasterprfid == 2)
                               {
                                    $explicenseqry=$ObjDB->QueryObject("SELECT fld_license_id as licenseid FROM itc_license_mission_mapping where fld_mis_id='".$misid."' and fld_flag='1' group by fld_license_id");

                                    if($explicenseqry->num_rows>0)
                                    {
                                        while($rowexplicense=$explicenseqry->fetch_assoc())
                                        {
                                            extract($rowexplicense);

                                              $cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
                                                                                 FROM itc_license_assessment_mapping 
                                                                                 WHERE fld_license_id='".$licenseid."'  AND fld_assessment_id='".$testid."'");
                                              if($cnt==0)
                                              {
                                                       $ObjDB->NonQuery("INSERT INTO itc_license_assessment_mapping (fld_license_id,fld_assessment_id,fld_access, fld_created_by, fld_created_date)
                                                                                              VALUES('".$licenseid."','".$testid."','1', '".$uid."', '".$date."')");
}
                                              else
                                              {
                                                      $ObjDB->NonQuery("UPDATE itc_license_assessment_mapping 
                                                                                              SET fld_access='1', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
                                                                                              WHERE fld_license_id='".$licenseid."' AND fld_assessment_id='".$testid."'");
                                              }
                                        }
                                    }
                                }
                                
                                
        
                              if($asstype == 0 AND $contentid!='0' AND $sessmasterprfid == 2)
                              {
                                  if($contentid==1)
                                  {
                                      $explicenseqry=$ObjDB->QueryObject("SELECT fld_license_id as licenseid FROM itc_license_exp_mapping where fld_exp_id='".$productid."' and fld_flag='1' group by fld_license_id");
                                      
                                      if($explicenseqry->num_rows>0)
                                      {
                                          while($rowexplicense=$explicenseqry->fetch_assoc())
                                          {
                                              extract($rowexplicense);
                                              
                                                $cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
										   FROM itc_license_assessment_mapping 
										   WHERE fld_license_id='".$licenseid."'  AND fld_assessment_id='".$testid."'");
                                                if($cnt==0)
                                                {
                                                         $ObjDB->NonQuery("INSERT INTO itc_license_assessment_mapping (fld_license_id,fld_assessment_id,fld_access, fld_created_by, fld_created_date)
                                                                                                VALUES('".$licenseid."','".$testid."','1', '".$uid."', '".$date."')");
}
                                                else
                                                {
                                                        $ObjDB->NonQuery("UPDATE itc_license_assessment_mapping 
                                                                                                SET fld_access='1', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
                                                                                                WHERE fld_license_id='".$licenseid."' AND fld_assessment_id='".$testid."'");
                                                }
                                          }
                                      }
                                      
                                  }
                                  else if($contentid==2)
                                  {
                                      $ipllicenseqry=$ObjDB->QueryObject("SELECT fld_license_id as licenseid FROM itc_license_cul_mapping where fld_lesson_id='".$productid."' and fld_active='1' group by fld_license_id");
                                      
                                      if($ipllicenseqry->num_rows>0)
                                      {
                                          while($rowipllicense=$ipllicenseqry->fetch_assoc())
                                          {
                                              extract($rowipllicense);
                                              
                                                $cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
										   FROM itc_license_assessment_mapping 
										   WHERE fld_license_id='".$licenseid."'  AND fld_assessment_id='".$testid."'");
                                                if($cnt==0)
                                                {
                                                         $ObjDB->NonQuery("INSERT INTO itc_license_assessment_mapping (fld_license_id,fld_assessment_id,fld_access, fld_created_by, fld_created_date)
                                                                                                VALUES('".$licenseid."','".$testid."','1', '".$uid."', '".$date."')");
                                                }
                                                else
                                                {
                                                        $ObjDB->NonQuery("UPDATE itc_license_assessment_mapping 
                                                                                                SET fld_access='1', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
                                                                                                WHERE fld_license_id='".$licenseid."' AND fld_assessment_id='".$testid."'");
                                                }
                                          }
                                      }
                                  }
                                  else if($contentid==3 OR $contentid==4 OR $contentid==7)
                                  {
                                      if($contentid==3)
                                      {
                                         $modlicenseqry=$ObjDB->QueryObject("SELECT fld_license_id as licenseid FROM itc_license_mod_mapping where fld_module_id='".$productid."' and fld_active='1' and fld_type='1' group by fld_license_id");
                                      }
                                      else if($contentid==4)
                                      {
                                          $modlicenseqry=$ObjDB->QueryObject("SELECT fld_license_id as licenseid FROM itc_license_mod_mapping where fld_module_id='".$productid."' and fld_active='1' and fld_type='2' group by fld_license_id");
                                      }
                                      else
                                      {
                                          $modlicenseqry=$ObjDB->QueryObject("SELECT fld_license_id as licenseid FROM itc_license_mod_mapping where fld_module_id='".$productid."' and fld_active='1' and fld_type='7' group by fld_license_id");
                                      }
                                      
                                      if($modlicenseqry->num_rows>0)
                                      {
                                          while($rowmodlicense=$modlicenseqry->fetch_assoc())
                                          {
                                              extract($rowmodlicense);
                                              
                                                $cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
										   FROM itc_license_assessment_mapping 
										   WHERE fld_license_id='".$licenseid."'  AND fld_assessment_id='".$testid."'");
                                                if($cnt==0)
                                                {
                                                         $ObjDB->NonQuery("INSERT INTO itc_license_assessment_mapping (fld_license_id,fld_assessment_id,fld_access, fld_created_by, fld_created_date)
                                                                                                VALUES('".$licenseid."','".$testid."','1', '".$uid."', '".$date."')");
                                                }
                                                else
                                                {
                                                        $ObjDB->NonQuery("UPDATE itc_license_assessment_mapping 
                                                                                                SET fld_access='1', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
                                                                                                WHERE fld_license_id='".$licenseid."' AND fld_assessment_id='".$testid."'");
                                                }
                                          }
                                      }
                                  }
                                  else if($contentid==5)
                                  {
                                      $explicenseqry=$ObjDB->QueryObject("SELECT fld_license_id as licenseid FROM itc_license_mission_mapping where fld_mis_id='".$productid."' and fld_flag='1' group by fld_license_id");
                                      
                                      if($explicenseqry->num_rows>0)
                                      {
                                          while($rowexplicense=$explicenseqry->fetch_assoc())
                                          {
                                              extract($rowexplicense);
                                              
                                                $cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
										   FROM itc_license_assessment_mapping 
										   WHERE fld_license_id='".$licenseid."'  AND fld_assessment_id='".$testid."'");
                                                if($cnt==0)
                                                {
                                                         $ObjDB->NonQuery("INSERT INTO itc_license_assessment_mapping (fld_license_id,fld_assessment_id,fld_access, fld_created_by, fld_created_date)
                                                                                                VALUES('".$licenseid."','".$testid."','1', '".$uid."', '".$date."')");
                                                }
                                                else
                                                {
                                                        $ObjDB->NonQuery("UPDATE itc_license_assessment_mapping 
                                                                                                SET fld_access='1', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
                                                                                                WHERE fld_license_id='".$licenseid."' AND fld_assessment_id='".$testid."'");
                                                }
                                          }
                                      }
                                      
                                  }
                                  else if($contentid==6)
                                  {
                                      $explicenseqry=$ObjDB->QueryObject("SELECT fld_license_id as licenseid FROM itc_license_pd_mapping where fld_pd_id='".$productid."' and fld_flag='1' group by fld_license_id");
                                      
                                      if($explicenseqry->num_rows>0)
                                      {
                                          while($rowexplicense=$explicenseqry->fetch_assoc())
                                          {
                                              extract($rowexplicense);
                                              
                                                $cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
										   FROM itc_license_assessment_mapping 
										   WHERE fld_license_id='".$licenseid."'  AND fld_assessment_id='".$testid."'");
                                                if($cnt==0)
                                                {
                                                         $ObjDB->NonQuery("INSERT INTO itc_license_assessment_mapping (fld_license_id,fld_assessment_id,fld_access, fld_created_by, fld_created_date)
                                                                                                VALUES('".$licenseid."','".$testid."','1', '".$uid."', '".$date."')");
                                                }
                                                else
                                                {
                                                        $ObjDB->NonQuery("UPDATE itc_license_assessment_mapping 
                                                                                                SET fld_access='1', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
                                                                                                WHERE fld_license_id='".$licenseid."' AND fld_assessment_id='".$testid."'");
                                                }
                                          }
                                      }
                                      
                                  }
                              }

}
/*--- Delet Question from test ---*/
if($oper == "delequa" and $oper != '')
	{		
		$testid = isset($_REQUEST['testid']) ? $_REQUEST['testid'] : '0';
		$qid = isset($_REQUEST['qid']) ? $_REQUEST['qid'] : '0';
		
		$ObjDB->NonQuery("UPDATE itc_test_questionassign SET fld_delstatus='1', fld_deleted_date='".$date."', fld_deleted_by='".$uid."' 
		                 WHERE fld_test_id='".$testid."' AND fld_question_id = '".$qid."'");
		
		$questioncount=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_test_questionassign WHERE fld_test_id='".$testid."' AND fld_delstatus='0'");
		if($questioncount == 0){
			$ObjDB->NonQuery("UPDATE itc_test_master SET fld_flag='0', fld_updated_by='".$uid."', fld_updated_date='".$date."' 
			                 WHERE fld_id='".$testid."' AND fld_delstatus='0'");
		}
		
		echo "success"."~".$questioncount;
	}
	

if($oper == "deleteques" and $oper != '')
{		
	$quesid = isset($_REQUEST['quesid']) ? $_REQUEST['quesid'] : '0';
	
	$questioncount = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_questionassign  AS a
                                                                LEFT JOIN itc_test_master AS b on b.fld_id=a.fld_test_id
                                                                WHERE a.fld_question_id='".$quesid."' AND b.fld_delstatus='0'");
	if($questioncount == 0){
		$ObjDB->NonQuery("UPDATE itc_question_details SET fld_delstatus='1', fld_deleted_by='".$uid."', fld_deleted_date='".$date."' 
		                 WHERE fld_id='".$quesid."'");
		
		echo "success";
	}
}
/*--- Select the type manual or random ---*/
if($oper == "testtype" and $oper != '') 
{
    $typeid = isset($_REQUEST['typeid']) ? $_REQUEST['typeid'] : '0';
    $testid = isset($_REQUEST['testid']) ? $_REQUEST['testid'] : '0';
    
    $ObjDB->NonQuery("UPDATE itc_test_master SET fld_question_type='".$typeid."', fld_updated_by='".$uid."', fld_updated_date='".$date."' 
                    WHERE fld_id='".$testid."' AND fld_delstatus='0'");
    
}

if($oper == "checktags" and $oper != '')
{		
	$id = isset($_REQUEST['rtestid']) ? $_REQUEST['rtestid'] : '0';
        $sid = isset($_REQUEST['tagid']) ? $_REQUEST['tagid'] : '0';
              
        $checktagscount= $ObjDB->SelectSingleValue("SELECT count(fld_id) FROM itc_test_random_questionassign WHERE fld_rtest_id='".$id."' and fld_tag_id='".$sid."' and fld_delstatus ='0'");
        echo $checktagscount;
}
/*--- Question count for Random Selection ---*/
if($oper == "questioncountsec" and $oper != '')
{		
	$id = isset($_REQUEST['rtestid']) ? $_REQUEST['rtestid'] : '0';
        $sid = isset($_REQUEST['tagid']) ? $_REQUEST['tagid'] : '0';
      	$tagname = isset($_REQUEST['tagname']) ? $_REQUEST['tagname'] : '0';
        $editsectionid = isset($_REQUEST['editsectionid']) ? $_REQUEST['editsectionid'] : '0';
	$totalavlqust=0;
        $qcount =0;
        $finalcount="";
        
        $alreadytags= $ObjDB->QueryObject("SELECT fld_tag_id,fld_avl_questions FROM itc_test_random_questionassign where fld_rtest_id ='".$id."' and fld_delstatus='0' and fld_id <> '".$editsectionid."'");
        if($alreadytags->num_rows>0){
            while($row= $alreadytags->fetch_assoc()){
                extract($row);
                $tagsid = explode(',',$fld_tag_id);
                for($i=0;$i<sizeof($tagsid);$i++){
                   if($sid == $tagsid[$i]){
                       $totalavlqust = $totalavlqust+ $fld_avl_questions;
                   } 
                }
           }
        }
	$sqry='';
	if($sid!=0){
		$sid = explode(',',$sid);
		for($i=0;$i<sizeof($sid);$i++){
			$ids = explode('_',$sid[$i]);
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
													  LEFT JOIN itc_user_master AS c ON b.fld_created_by = c.fld_profile_id 
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
        
        if($sqry != '') {
             if($editsectionid !=0){
                if($sessmasterprfid == 2){
                
                    $qry = $ObjDB->QueryObject("SELECT DISTINCT(a.fld_id) AS qusid, a.fld_question AS qusname, a.fld_lesson_id AS lid, 
                                              c.fld_question_type AS qustype, a.fld_created_by FROM `itc_question_details` AS a, `itc_ipl_master` AS b, 
                                              `itc_question_type` AS c, itc_user_master as w WHERE a.fld_created_by=w.fld_id
                                              AND c.`fld_id`=a.`fld_question_type_id` AND w.fld_profile_id='2' AND a.fld_question !=''  AND a.fld_delstatus='0' 
                                              AND b.fld_delstatus='0' AND c.fld_delstatus='0' ".$sqry."");
                }
                else{
                    $qry = $ObjDB->QueryObject("SELECT DISTINCT(a.fld_id) AS qusid, a.fld_question AS qusname, a.fld_lesson_id AS lid, 
                                               c.fld_question_type AS qustype, a.fld_created_by FROM `itc_question_details` AS a, `itc_ipl_master` AS b, 
                                               `itc_question_type` AS c WHERE c.`fld_id`=a.`fld_question_type_id` AND a.fld_delstatus='0'
                                                AND b.fld_delstatus='0' AND a.fld_question !='' AND c.fld_delstatus='0' ".$sqry."");                    
                }
                
            }
            else{
      				
                if($sessmasterprfid == 2){
                
                    $qry = $ObjDB->QueryObject("SELECT DISTINCT(a.fld_id) AS qusid, a.fld_question AS qusname, a.fld_lesson_id AS lid, 
                                              c.fld_question_type AS qustype, a.fld_created_by FROM `itc_question_details` AS a, `itc_ipl_master` AS b, 
                                              `itc_question_type` AS c, itc_user_master as w WHERE a.fld_created_by=w.fld_id
                                              AND a.fld_id NOT IN (SELECT fld_question_id FROM `itc_test_questionassign` WHERE fld_test_id='".$id."' AND fld_delstatus='0')
                                              AND c.`fld_id`=a.`fld_question_type_id` AND w.fld_profile_id='2' AND a.fld_question !=''  AND a.fld_delstatus='0' 
                                              AND b.fld_delstatus='0' AND c.fld_delstatus='0' ".$sqry."");
                }
                else{
                    $qry = $ObjDB->QueryObject("SELECT DISTINCT(a.fld_id) AS qusid, a.fld_question AS qusname, a.fld_lesson_id AS lid, 
                                               c.fld_question_type AS qustype, a.fld_created_by FROM `itc_question_details` AS a, `itc_ipl_master` AS b, 
                                               `itc_question_type` AS c WHERE c.`fld_id`=a.`fld_question_type_id` AND a.fld_delstatus='0'
                                               AND a.fld_id NOT IN (SELECT fld_question_id FROM `itc_test_questionassign` WHERE fld_test_id='".$id."' AND fld_delstatus='0')
                                               AND b.fld_delstatus='0' AND a.fld_question !='' AND c.fld_delstatus='0' ".$sqry."");                    
                }
            }
            }
            $qcount=$qry->num_rows;
            $finalcount = $qcount - $totalavlqust;
            if($finalcount < 0){
                echo "0";
            }
            else{
                echo $finalcount;
            }

}
/*--- Save/Update a random random section - Submit ---*/
if($oper == "randomqndetails" and $oper != '')
{
            $sectionid = isset($_REQUEST['sectionid']) ? $_REQUEST['sectionid'] : '0';
            $testid = isset($_REQUEST['testid']) ? $_REQUEST['testid'] : '0'; 
            $tagids =(isset( $_REQUEST['tagids'])) ?  $_REQUEST['tagids'] : '';
            $qusass =(isset( $_REQUEST['qusass'])) ?  $_REQUEST['qusass'] : '';
            $pect =(isset( $_REQUEST['pect'])) ?  $_REQUEST['pect'] : '';
            $qncounts =(isset( $_REQUEST['qncounts'])) ?  $_REQUEST['qncounts'] : '';
            
            $orderby= $ObjDB->SelectSingleValue("SELECT max(fld_order_by) FROM itc_test_random_questionassign WHERE fld_rtest_id='".$testid."' and fld_delstatus ='0'");
            if($sectionid !=0){
                $ObjDB->NonQuery("UPDATE itc_test_random_questionassign SET fld_tag_id='".$tagids."',fld_avl_questions='".$qncounts."',fld_qn_assign='".$qusass."',fld_pct_section='".$pect."', fld_updated_by='".$uid."', fld_updated_date='".$date."' 
                    WHERE fld_rtest_id='".$testid."' AND fld_id='".$sectionid."' AND fld_delstatus='0'");
                
            } 
            else{
                $orderby = $orderby+1;
                $ObjDB->NonQuery("INSERT INTO itc_test_random_questionassign (fld_rtest_id, fld_tag_id, fld_avl_questions,
                                        fld_qn_assign,fld_pct_section,fld_order_by,fld_created_by, fld_created_date)
                                         VALUES ('".$testid."','".$tagids."','".$qncounts."','".$qusass."','".$pect."','".$orderby."',
                                               '".$uid."','".$date."')");
            }
            
     
}
/*--- Save/Update a random random section whole - NextStep ---*/
if($oper == "randomqsection" and $oper != '')
{
            $fflag = isset($_REQUEST['fflag']) ? $_REQUEST['fflag'] : '0';
       
            $testid = isset($_REQUEST['testid']) ? $_REQUEST['testid'] : '0';
            $fsectionid = isset($_REQUEST['fsectionid']) ? $_REQUEST['fsectionid'] : '0';
            $ftags =(isset( $_REQUEST['ftags'])) ?  $_REQUEST['ftags'] : '';
            $fqnassig =(isset( $_REQUEST['fqnassig'])) ?  $_REQUEST['fqnassig'] : '';
            $fpct =(isset( $_REQUEST['fpct'])) ?  $_REQUEST['fpct'] : '';
            $fquscount =(isset( $_REQUEST['fquscount'])) ?  $_REQUEST['fquscount'] : '';
          
            $fsectionid = explode('~',$fsectionid);
            $ftags = explode('~',$ftags);
            $fquscount = explode('~',$fquscount);
            $fqnassig = explode('~',$fqnassig);
            $fpct = explode('~',$fpct);
            
            for($i=0;$i<sizeof($fsectionid);$i++){
               $ObjDB->NonQuery("UPDATE itc_test_random_questionassign SET fld_tag_id='".$ftags[$i]."',fld_avl_questions='".$fquscount[$i]."',fld_qn_assign='".$fqnassig[$i]."',fld_pct_section='".$fpct[$i]."',fld_order_by='".$i."', fld_updated_by='".$uid."', fld_updated_date='".$date."' 
                    WHERE fld_rtest_id='".$testid."' AND fld_id='".$fsectionid[$i]."' AND fld_delstatus='0'");
            }
              
	     
}
/*--- Save/Update a random random section question from itc_test_questionassign ---*/
if($oper == "saverandomquestion" and $oper != '') 
{  
            $id = isset($_REQUEST['testid']) ? $_REQUEST['testid'] : '0';
            $fsectionid = isset($_REQUEST['fsectionid']) ? $_REQUEST['fsectionid'] : '0';
            $ftags =(isset( $_REQUEST['ftags'])) ?  $_REQUEST['ftags'] : '';
            $fqnassig =(isset( $_REQUEST['fqnassig'])) ?  $_REQUEST['fqnassig'] : '';
            $fpct =(isset( $_REQUEST['fpct'])) ?  $_REQUEST['fpct'] : '';
            $fquscount =(isset( $_REQUEST['fquscount'])) ?  $_REQUEST['fquscount'] : '';
                      
            $fsectionid = explode('~',$fsectionid);
            $ftags = explode('~',$ftags);
            $fquscount = explode('~',$fquscount);
            $fqnassig = explode('~',$fqnassig);
            $fpct = explode('~',$fpct);
            
            $ObjDB->NonQuery("delete from itc_test_questionassign where fld_test_id='".$id."'");
            $ObjDB->NonQuery("UPDATE itc_test_master SET fld_nextflag='1' WHERE fld_id='".$id."'");
          
            for($k=0;$k<sizeof($fsectionid)-1;$k++){
             
            $sqry='';            
                    $sid = explode(',',$ftags[$k]);
                  
                    for($i=0;$i<sizeof($sid);$i++){
                            $ids = explode('_',$sid[$i]);
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
                                            if($sessmasterprfid == 2){
                                                    $itemqry = $ObjDB->QueryObject("SELECT a.fld_item_id FROM itc_main_tag_mapping AS a 
                                                                                  LEFT JOIN itc_question_details AS b ON a.fld_item_id=b.fld_id 
                                                                                                              LEFT JOIN itc_user_master AS c ON b.fld_created_by = c.fld_profile_id 
                                                                                                              WHERE a.fld_tag_id='".$sid[$i]."' AND a.fld_access='1' AND a.fld_tag_type='19' 
                                                                                                              AND c.fld_profile_id ='2' GROUP BY a.fld_item_id");
                                            }
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
           
            if($sqry != '') {

                    if($sessmasterprfid == 2){

                        $qry = $ObjDB->QueryObject("SELECT DISTINCT(a.fld_id) AS qusid, a.fld_question AS qusname, a.fld_lesson_id AS lid, 
                                                  c.fld_question_type AS qustype, a.fld_created_by FROM `itc_question_details` AS a, `itc_ipl_master` AS b, 
                                                  `itc_question_type` AS c, itc_user_master as w WHERE a.fld_created_by=w.fld_id 
                                                  AND c.`fld_id`=a.`fld_question_type_id` AND w.fld_profile_id='2' AND a.fld_delstatus='0'
                                                  AND a.fld_id NOT IN (SELECT fld_question_id FROM `itc_test_questionassign` WHERE fld_test_id='".$id."' AND fld_delstatus='0')
                                                  AND b.fld_delstatus='0' AND a.fld_question !='' AND c.fld_delstatus='0' ".$sqry." ORDER BY RAND() LIMIT 0,".$fqnassig[$k]."");
                    }
                    else{
                        $qry = $ObjDB->QueryObject("SELECT DISTINCT(a.fld_id) AS qusid, a.fld_question AS qusname, a.fld_lesson_id AS lid, 
                                                   c.fld_question_type AS qustype, a.fld_created_by FROM `itc_question_details` AS a, `itc_ipl_master` AS b, 
                                                   `itc_question_type` AS c WHERE c.`fld_id`=a.`fld_question_type_id` AND a.fld_delstatus='0'
                                                   AND a.fld_id NOT IN (SELECT fld_question_id FROM `itc_test_questionassign` WHERE fld_test_id='".$id."' AND fld_delstatus='0')
                                                    AND b.fld_delstatus='0' AND a.fld_question !='' AND c.fld_delstatus='0' ".$sqry." ORDER BY RAND() LIMIT 0,".$fqnassig[$k]."");
                    }
                }
                 if($qry->num_rows>0){
                    while($qcount1 = $qry->fetch_assoc())  {
                    extract($qcount1); 
                         $ObjDB->NonQuery("INSERT INTO itc_test_questionassign(fld_test_id, fld_question_id,fld_section_id,fld_tag_id, fld_order_by,fld_order_bytags,fld_rflag, fld_created_by,fld_created_date)
					                  VALUES('".$id."','".$qusid."','".$fsectionid[$k]."','".$fsectionid[$k]."','1','".$k."','1','".$uid."','".date('Y-m-d H:i:s')."')");
                    
                    }
                }
                
            } //for ends-
           
}
/*--- Check the available question for particular section ---*/
if($oper=="chkqnassig" and $oper != " " )
{
        $testid = isset($method['testid']) ? $method['testid'] : '0';
        $sectionid = isset($method['sectionid']) ? $method['sectionid'] : '';
        $assq = isset($method['assq']) ? $method['assq'] : '';

        $count = $ObjDB->SelectSingleValueInt("SELECT fld_avl_questions FROM itc_test_random_questionassign WHERE fld_id='".$sectionid."' AND fld_rtest_id='".$testid."' AND fld_delstatus='0'");

        if($count >= $assq){ echo "true"; }	else { echo "false"; }
}

/*--- Delet random Question and section  from test ---*/
if($oper == "delequarandom" and $oper != '')
{		
        $testid = isset($_REQUEST['testid']) ? $_REQUEST['testid'] : '0';
        $rsct = isset($_REQUEST['rsct']) ? $_REQUEST['rsct'] : '0';

        $ObjDB->NonQuery("UPDATE itc_test_questionassign SET fld_delstatus='1', fld_deleted_date='".$date."', fld_deleted_by='".$uid."' 
                         WHERE fld_test_id='".$testid."' AND fld_section_id = '".$rsct."'");
        $ObjDB->NonQuery("UPDATE itc_test_random_questionassign SET fld_delstatus='1', fld_deleted_date='".$date."', fld_deleted_by='".$uid."' 
                         WHERE fld_rtest_id='".$testid."' AND fld_id = '".$rsct."'");

        $questioncount=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_test_questionassign WHERE fld_test_id='".$testid."' AND fld_delstatus='0'");
        if($questioncount == 0){
                $ObjDB->NonQuery("UPDATE itc_test_master SET fld_flag='0', fld_updated_by='".$uid."', fld_updated_date='".$date."' 
                                 WHERE fld_id='".$testid."' AND fld_delstatus='0'");
        }

        echo "success"."~".$questioncount;
}
	@include("footer.php");