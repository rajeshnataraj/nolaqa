<?php 
/*
Created by: Vijayalakshmi PHP Programmer
Created on: 15/12/2014

*/
@include("sessioncheck.php");

$date = date("Y-m-d H:i:s");
$oper = isset($method['oper']) ? $method['oper'] : '';


if($oper=="saveselect_assessment" and $oper != " " )
{ 
      $studentid = isset($method['studentid']) ? $method['studentid'] : '';
      $assessid = isset($method['assessmentid']) ? $method['assessmentid'] : '';
      echo $studentid."~".$assessid;

}
/* show the selected open resonse answers   ***/
	if($oper == "view_ind_answer" and $oper != '')
	{

	$studid = isset($method['studid']) ? $method['studid'] : '0';
	$quesid = isset($method['questionid']) ? $method['questionid'] : '0';
	$answerid = isset($method['answerid']) ? $method['answerid'] : '0';

	$chk_ansid= $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_test_teacher_comment WHERE fld_answer_id='".$answerid."' AND fld_student_id='".$uid."' AND fld_delstatus='0'");

	if($chk_ansid > 0)
	{
		echo "success~".$answerid;
	}
	else
	{
		echo "fail~0";
	}

	}
//Show the assessment list
if($oper == "showassessmentlist" and $oper != '')
{
 $studentid = isset($method['studid']) ? $method['studid'] : '';
 $startdate = isset($method['startdt']) ? $method['startdt'] : '';
 $enddate = isset($method['enddt']) ? $method['enddt'] : '';

 $st_date = date("Y-m-d", strtotime($startdate)); 
 $en_date = date("Y-m-d", strtotime($enddate)); 

	$sqry = "AND ('".$st_date."' BETWEEN f.fld_start_date AND f.fld_end_date OR '".$en_date."' BETWEEN f.fld_start_date AND f.fld_end_date OR f.fld_start_date BETWEEN '".$st_date."' AND '".$en_date."' OR f.fld_end_date BETWEEN '".$st_date."' AND '".$en_date."')";

?>
<script type="text/javascript" language="javascript">

	$(function() {

			$('#testrailvisible0').slimscroll({
				width: '410px',
				height:'366px',
				size: '7px',
                                alwaysVisible: true,
				railVisible: true,
				allowPageScroll: false,
				railColor: '#F4F4F4',
				opacity: 1,
				color: '#d9d9d9',
				
			});
			$('#testrailvisible1').slimscroll({
				width: '410px',
				height:'366px',
				size: '7px',
                                alwaysVisible: true,
				railVisible: true,
				allowPageScroll: false,
				railColor: '#F4F4F4',
				opacity: 1,
				color: '#d9d9d9',
			});
			$("#list9").sortable({
				connectWith: ".droptrue1",
				dropOnEmpty: true,
				items: "div[class='draglinkleft']",
				receive: function(event, ui) { 
					$("div[class=draglinkright]").each(function(){ 
						if($(this).parent().attr('id')=='list9'){
							fn_movealllistitems('list9','list10',$(this).children(":first").attr('id'));
							fn_validateassessments();
						}
					});											
				}
			});
		
			$( "#list10" ).sortable({
				connectWith: ".droptrue1",
				dropOnEmpty: true,
				receive: function(event, ui) { 
					$("div[class=draglinkleft]").each(function(){ 
						if($(this).parent().attr('id')=='list10'){
							fn_movealllistitems('list9','list10',$(this).children(":first").attr('id'));

							fn_validateassessments();
						}
					});								
				}
			});
		
		
	});										
</script> 
                                <div class='six columns'>
                                    <div class="dragndropcol">
                                    <?php
                                    
                                       $qry_assessment_openresp=$ObjDB->QueryObject("SELECT b.fld_question_id as quesid,b.fld_id as answerid,d.fld_id as assesid,d.fld_test_name as assesname
                            ,fn_shortname(d.fld_test_name,2) as shortname,b.fld_answer_type_id as type, 
                    f.fld_start_date,f.fld_end_date FROM itc_question_details as a 
                                    LEFT JOIN itc_test_student_answer_track as b on a.fld_id=b.fld_question_id
                    LEFT JOIN itc_test_master as d ON d.fld_id = b.fld_test_id
                                    LEFT JOIN itc_test_questionassign as c on a.fld_id=c.fld_question_id
                     LEFT JOIN itc_test_student_mapping as f ON d.fld_id = f.fld_test_id
                                    WHERE  a.fld_delstatus='0' AND b.fld_student_id='".$uid."'
                                    AND b.fld_answer_type_id='15' AND b.fld_delstatus='0'  AND c.fld_delstatus='0' AND d.fld_flag='1' AND d.fld_delstatus = '0' ".$sqry." GROUP BY d.fld_id ORDER BY assesname");
                                    ?>
                                        <div class="dragtitle">Assessments</div>
                                        <div class="draglinkleftSearch" id="s_list9" >
                                           <dl class='field row'>
                                                <dt class='text'>
                                                    <input placeholder='Search' type='text' id="list_9_search" name="list_9_search" onKeyUp="search_list(this,'#list9');" />
                                                </dt>
                                            </dl>
                                        </div>
                                        <div class="dragWell" id="testrailvisible0" >
                                            <div id="list9" class="dragleftinner droptrue1">
                                             <?php      
                                               if($qry_assessment_openresp->num_rows > 0){
$i=0;
    while($qry_openrespass_details = $qry_assessment_openresp->fetch_assoc()){
            extract($qry_openrespass_details);
                                                        ?>
                                                    <div class="draglinkleft" id="list9_<?php echo $assesid; ?>" >
                                                        <div class="dragItemLable tooltip" id="<?php echo $assesid; ?>" title="<?php echo $assesname;?>"><?php echo $shortname; ?></div>
                                                        <div class="clickable" id="clck_<?php echo $assesid; ?>" onclick="fn_movealllistitems('list9','list10',<?php echo $assesid; ?>);fn_validateassessments();"></div>
                                                    </div> 
                                            <?php 
                                                    }
                                                }
                                            ?>
                                            </div>
                                        </div>
                                        <div class="dragAllLink"  onclick="fn_movealllistitems('list9','list10',0,0);" style="cursor: pointer;cursor:hand;width: 152px;float: right;">add all assessments</div>
                                    </div>
                                </div>
                            <div class='six columns'>
                                    <div class="dragndropcol">
                                        <div class="dragtitle">Selected Assessments</div>
                                        <div class="draglinkleftSearch" id="s_list10" >
                                           <dl class='field row'>
                                                <dt class='text'>
                                                    <input placeholder='Search' type='text' id="list_10_search" name="list_10_search" onKeyUp="search_list(this,'#list10');" />
                                                </dt>
                                            </dl>
                                        </div>
                                         <div class="dragWell" id="testrailvisible1">
                                            <div id="list10" class="dragleftinner droptrue1">
                                             <?php 
                                          if($qry_assessment_openresp->num_rows > 0){
$i=0;
    while($qry_openrespass_details = $qry_assessment_openresp->fetch_assoc()){
            extract($qry_openrespass_details);
                                                        
                                                    ?>
                                                            <div class="draglinkright" id="list10_<?php echo $assesid; ?>">
                                                                <div class="dragItemLable tooltip" id="<?php echo $assesid; ?>" title="<?php echo $assesname;?>"><?php echo $shortname; ?></div>
                                                                <div class="clickable" id="clck_<?php echo $assesid; ?>" onclick="fn_movealllistitems('list9','list10',<?php echo $assesid; ?>);fn_validateassessments();"></div>
                                                            </div>
                                            <?php   }
                                                }
                                             
                                            ?>
                                            </div>
                                        </div>
                                        <div class="dragAllLink" onclick="fn_movealllistitems('list10','list9',0,0);"  style="cursor: pointer;cursor:hand;width: 180px;float: right;">remove all assessments</div>
                                    </div>
                                </div>                          

<?php
  	
}

	  
	@include("footer.php");
