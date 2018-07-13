<?php 
@include("sessioncheck.php");

$date = date("Y-m-d H:i:s");
$oper = isset($method['oper']) ? $method['oper'] : '';



if($oper=="showstudent" and $oper != " " )
{
$clsid = isset($method['clsid']) ? $method['clsid'] : '';
$expid = isset($method['expid']) ? $method['expid'] : '';
$rubid = isset($method['rubid']) ? $method['rubid'] : '0';

$rubricid = isset($method['rubricid']) ? $method['rubricid'] : '0';
?> 
    <script type="text/javascript" language="javascript">
        $(function() {
                $('#testrailvisible0').slimscroll({
                        width: '410px',
                        height:'366px',
                        size: '3px',
                        railVisible: true,
                        allowPageScroll: false,
                        railColor: '#F4F4F4',
                        opacity: 1,
                        color: '#d9d9d9',
                        wheelStep: 1,

                });
                $('#testrailvisible1').slimscroll({
                        width: '410px',
                        height:'366px',
                        size: '3px',
                        railVisible: true,
                        allowPageScroll: false,
                        railColor: '#F4F4F4',
                        opacity: 1,
                        color: '#d9d9d9',
                        wheelStep: 1,
                });
                $("#list9").sortable({
                        connectWith: ".droptrue1",
                        dropOnEmpty: true,
                        items: "div[class='draglinkleft']",
                        receive: function(event, ui) { 
                                $("div[class=draglinkright]").each(function(){ 
                                        if($(this).parent().attr('id')=='list9'){
                                                fn_movealllistitems('list9','list10',$(this).children(":first").attr('id'));
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
                                        }
                                });								
                        }
                });
             
        });																	
    </script>  
                            
    <div class="row rowspacer" id="studentlist">
      <div class='six columns'>
          <div class="dragndropcol">
          <?php
             $qrystudent= $ObjDB->QueryObject("SELECT b.fld_student_id AS studentid,c.fld_fname AS firstname,c.fld_lname AS lastname ,c.fld_username as username FROM itc_class_indasexpedition_master AS a
                                                  LEFT JOIN itc_class_exp_student_mapping AS b ON a.fld_id=b.fld_schedule_id
                                                  LEFT JOIN itc_user_master AS c ON c.fld_id=b.fld_student_id
                                                  WHERE a.fld_class_id='".$clsid."' AND a.fld_exp_id='".$expid."'  AND a.fld_flag='1' AND a.fld_delstatus='0' AND b.fld_flag='1' 
                                                      AND c.fld_profile_id = '10'  AND c.fld_activestatus = '1'  AND c.fld_delstatus = '0'  group by studentid ORDER BY fld_fname");//b.fld_student_id NOT IN(SELECT fld_student_id FROM itc_exp_rubric_rpt_stu_mapping where fld_rubric_rpt_id='".$rubricid."' AND fld_delstatus='0') AND
             ?>
              <div class="dragtitle">Students available</div>
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
                         if($qrystudent->num_rows > 0){													
                              while($rowsstudent = $qrystudent->fetch_assoc()){
                                  extract($rowsstudent);
                                  ?>
                              <div class="draglinkleft" id="list9_<?php echo $studentid; ?>" >
                                  <div class="dragItemLable tooltip" id="<?php echo $studentid; ?>" title="<?php echo $username;?>"><?php echo $firstname." ".$lastname; ?></div>
                                  <div class="clickable" id="clck_<?php echo $studentid;?>" onclick="fn_movealllistitems('list9','list10',<?php echo $studentid;?>,<?php echo $expid; ?>);"></div>
                              </div>
                      <?php 
                              }
                          }
                      ?>
                      </div>
                  </div>
          </div>
      </div>
      <div class='six columns'>
              <div class="dragndropcol">
                  <div class="dragtitle">Students in your Rubric</div>
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
                              $qrystudent= $ObjDB->QueryObject("SELECT  a.fld_student_id AS studentid, c.fld_fname AS firstname,c.fld_lname AS lastname ,c.fld_username as username FROM itc_exp_rubric_rpt_stu_mapping AS a
                                                                    LEFT JOIN itc_exp_rubric_rpt AS b ON a.fld_rubric_rpt_id=b.fld_id
                                                                    LEFT JOIN itc_user_master AS c ON c.fld_id=a.fld_student_id
                                                                    WHERE a.fld_student_id b.fld_class_id='".$clsid."' AND b.fld_exp_id='".$expid."' AND a.fld_delstatus='0' AND b.fld_delstatus='0' 
                                                                    AND c.fld_profile_id = '10'  AND c.fld_activestatus = '1'  AND c.fld_delstatus = '0' group by studentid ORDER BY fld_fname");//IN(SELECT fld_student_id FROM itc_exp_rubric_rpt_stu_mapping where fld_rubric_rpt_id='".$rubricid."' AND fld_delstatus='0')    AND

                             if($qrystudent->num_rows > 0){													
                                 while($rowsstudent = $qrystudent->fetch_assoc()){
                                     extract($rowsstudent);
                                         ?>
                                     <div class="draglinkright" id="list10_<?php echo $studentid; ?>">
                                         <div class="dragItemLable tooltip" id="<?php echo $studentid; ?>" title="<?php echo $username;?>"><?php echo $firstname." ".$lastname; ?></div>
                                         <div class="clickable" id="clck_<?php echo $studentid; ?>" onclick="fn_movealllistitems('list9','list10',<?php echo $studentid; ?>,<?php echo $expid; ?>);"></div>
                                     </div>
                              <?php }
                             }
                          ?>
                      </div>
                  </div>
          </div>
      </div>
    </div>  
<input type="hidden" name="expid" id="expid" value="<?php echo $expid; ?>">
<input type="hidden" name="classid" id="classid" value="<?php echo $clsid; ?>">
<input type="hidden" name="rubricid" id="rubricid" value="<?php echo $rubricid; ?>">
<input type="hidden" name="rubid" id="rubid" value="<?php echo $rubid; ?>">

<?php
}


if($oper=="showrubric" and $oper != " " )
{
   
$expeditionid = isset($method['expid']) ? $method['expid'] : '0';
$rubriid = isset($method['rubricid']) ? $method['rubricid'] : '0';
$rubid = isset($method['rubid']) ? $method['rubid'] : '0';
  $createbtn = "Save";

?>
	<div class='formBase rowspacer'>
            <div class='rowspacer formBase'>  
                    <div id="expsetting" class='row rowspacer'>  
                        <div class='span10 offset1'>
                            <table class='table table-hover table-striped table-bordered setbordertopradius' id="mytable" >
                                <thead >
                                    <tr style="cursor:default;">
                                        <th width="18%">Category</th>
                                        <th width="14%" class='centerText'>4</th>
                                        <th width="14%" class='centerText'>3</th>
                                        <th width="13%" class='centerText'>2</th>
                                        <th width="13%" class='centerText'>1</th>
                                        <th width="13%" class='centerText'>0</th>
                                        <th width="7%" class='centerText'>Weight</th>
                                        <th width="7%" class='centerText'>Score</th>
                                    </tr>
                                </thead>
                                <?php

                                  $qrydest=$ObjDB->QueryObject("SELECT a.fld_id as destid,a.fld_dest_name as destname FROM itc_exp_destination_master as a
                                                                            LEFT JOIN itc_exp_master AS b ON b.fld_id=a.fld_exp_id 
                                                                            WHERE a.fld_exp_id='".$expeditionid."' AND a.fld_flag='1' AND a.fld_delstatus='0'");
                                    if($qrydest->num_rows > 0){
                                         while($row=$qrydest->fetch_assoc()){
                                             extract($row);
                                             $dname[]=$destname;
                                             $did[]=$destid;
                                         }
                                    }
                                    for($i=0;$i<sizeof($did);$i++) 
                                    {
                                        
                                      ?>
                                <tbody> <style> .bcolor{background: #F1F1F3;} m{ text-decoration: overline;}
                                .table tr td:first-child {
                                                padding-left: 20px;
                                            }
                                </style>
                                          <tr class="bcolor">
                                             <td style="font:  bold 12px/30px Times New Roman;">DESTINATION <?php echo $i+1;?></td>
                                            <td colspan="7" style="font:  bold 14px/30px Times New Roman;"><?php echo $dname[$i];?></td>
                                          </tr>
                                          <?php

                                              
                                               $qryviewexp_rubric=$ObjDB->QueryObject("SELECT c.fld_id as rubricid, a.fld_id as destid,a.fld_dest_name as destname, c.fld_category as category,c.fld_four as four,c.fld_three as three,
                                                                                        c.fld_two as two,c.fld_one as one, c.fld_zer as zer ,c.fld_weight as weight,c.fld_score as score FROM itc_exp_destination_master as a
                                                                                            LEFT JOIN itc_exp_master AS b ON b.fld_id=a.fld_exp_id 
                                                                                            LEFT JOIN itc_exp_rubric_master AS c ON c.fld_dest_id=a.fld_id 
                                                                                            WHERE c.fld_rubric_id='".$rubid."' AND c.fld_exp_id='".$expeditionid."' AND c.fld_dest_id='".$did[$i]."' AND a.fld_flag='1' AND a.fld_delstatus='0' AND c.fld_delstatus='0' ");//AND c.fld_created_by='".$uid1."'
                                              

                                            if($qryviewexp_rubric->num_rows > 0) 
                                            {
                                                $cnt=1;
                                                while($row=$qryviewexp_rubric->fetch_assoc())
                                                {
                                                 extract($row);
                                                 //placeholders array
                                                $placeholders = array('&', '>', '!', '<');
                                                //replace values array
                                                $replace = array('and', 'greater than', 'ex', 'less than');
                                                 
                                                      
                                                 $category = str_replace($placeholders, $replace, $category);
                                                 $category = str_replace(',', '', $category);
                                                 $four = str_replace($placeholders, $replace, $four);
                                                 $three = str_replace($placeholders, $replace, $three);
                                                 $two = str_replace($placeholders, $replace, $two);
                                                 $one = str_replace($placeholders, $replace, $one);
                                                 $zer = str_replace($placeholders, $replace, $zer);
                                                    ?>
                                                    <tr class="Btn" id="exp-rubric-<?php echo $rubricid; ?>" >
                                                        <td colswidth="18%" id="rubrictxt-<?php echo $rubricid; ?>" ><?php echo $category ;?></td>
                                                        <td width="14%" id="rubrictxt-<?php echo $rubricid; ?>" class='centerText' onclick="fn_showdeststmt('4','<?php echo $weight ;?>','<?php echo $rubricid; ?>','<?php echo $destid; ?>','<?php echo $rubid; ?>');this.disabled='disabled';"  ><?php echo $four ;?></td>
                                                        <td width="14%" id="rubrictxt-<?php echo $rubricid; ?>" class='centerText' onclick="fn_showdeststmt('3','<?php echo $weight ;?>','<?php echo $rubricid; ?>','<?php echo $destid; ?>','<?php echo $rubid; ?>');this.disabled='disabled';"  ><?php echo $three ;?></td>
                                                        <td width="13%" id="rubrictxt-<?php echo $rubricid; ?>" class='centerText' onclick="fn_showdeststmt('2','<?php echo $weight ;?>','<?php echo $rubricid; ?>','<?php echo $destid; ?>','<?php echo $rubid; ?>');this.disabled='disabled';"  ><?php echo $two ;?></td>
                                                        <td width="13%" id="rubrictxt-<?php echo $rubricid; ?>" class='centerText' onclick="fn_showdeststmt('1','<?php echo $weight ;?>','<?php echo $rubricid; ?>','<?php echo $destid; ?>','<?php echo $rubid; ?>');this.disabled='disabled';"  ><?php echo $one ;?></td>
                                                        <td width="13%" id="rubrictxt-<?php echo $rubricid; ?>" class='centerText' onclick="fn_showdeststmt('0','<?php echo $weight ;?>','<?php echo $rubricid; ?>','<?php echo $destid; ?>','<?php echo $rubid; ?>');this.disabled='disabled';"  ><?php echo $zer ;?></td>
                                                        <td width="7%" id="rubrictxt-<?php echo $rubricid; ?>" class='centerText'><?php echo "X".$weight ;?></td>
                                                        <td width="7%" id="rubrictxt-<?php echo $rubricid; ?>" class='centerText'>
                                                            <?php                                                            
                                                            if($score=='0')
                                                            {
                                                                $score='';
                                                            }
                                                            ?>                                                            
                                                            <input  type='text' id="txtscore-<?php echo $rubricid; ?>" readonly="" name="txtscore" maxlength="3" min="0" max="<?php //echo $scoree ;?>" value="<?php //echo $score ;?>" onkeypress="return isNumber(event)"  style="width:15px; border: 0px none;"><?php echo "/ ".$score ;?>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                   
                                                }
                                            }
                                            else{           ?>
                                                    <tr id="exp-rubric-0">
                                                        <td colspan="8" align="center">  </td>
                                                    </tr> 
                                                    <?php }   ?>
                                      </tbody>
                                     <?php   
                                     }
                                    ?>
                            </table>
                        </div>
                    </div>
                </div>
           
            </div> 
 <div class='row rowspacer' style="display:none" id="viewreportdiv">
    <input class="darkButton" type="button" id="btnstep2" style="width:210px; height:42px; float:right;" value="<?php echo $createbtn;?>" onClick="fn_saverubric('<?php echo $rubid;?>','<?php echo $expeditionid;?>');" />
</div>
<script type="text/javascript" language="javascript">
    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
           return false;
        }
        return true;
    }
</script>
    
    <?php    
}




/*--- Save and Update the Rubric ---*/
if($oper=="saverubric")
{
    try{
        
        $rubid = isset($method['rubnameid']) ? $method['rubnameid'] : '0'; 
        $expid = isset($method['expid']) ? $method['expid'] : '0'; 
        $classid = isset($method['classid']) ? $method['classid'] : '0';
        
        $studentid = isset($method['list10']) ? ($method['list10']) : '0'; 
        $score = isset($method['txtscore']) ? $method['txtscore'] : '0'; 
        $ruborderid = isset($method['ruborderid']) ? $method['ruborderid'] : '0'; 
        $destid = isset($method['destid']) ? $method['destid'] : '0'; 
        
        $stuid=explode(",",$studentid);
        $sco=explode(",",$score);
       
        
        //save class Name
        $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_exp_rubric_rpt WHERE fld_exp_id='".$expid."' AND fld_class_id='".$classid."' 
                                                    AND fld_rubric_nameid='".$rubid."' AND fld_created_by='".$uid."' AND fld_delstatus = '0'");
        if($cnt==0)
        {
           $maxid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_exp_rubric_rpt (fld_class_id, fld_exp_id, fld_rubric_nameid, fld_created_by, fld_created_date, fld_district_id, fld_school_id, fld_user_id,fld_profile_id) 
                                                    VALUES ('".$classid."', '".$expid."', '".$rubid."', '".$uid."', '".$date."','".$sendistid."','".$schoolid."','".$indid."','".$sessmasterprfid."')");
        }
        else
        {
           $ObjDB->NonQuery("UPDATE itc_exp_rubric_rpt SET fld_class_id='".$classid."', fld_exp_id='".$expid."', fld_updated_by='".$uid."', 
                                              fld_updated_date='".$date."' WHERE fld_rubric_nameid='".$rubid."'  AND fld_class_id='".$classid."'  AND fld_delstatus = '0' ");
           $maxid=$cnt;
        }
        
       /*rubric stmt*/

        for($i=0;$i<sizeof($stuid);$i++)
        {
            $cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_rubric_rpt_statement 
                                                    WHERE fld_dest_id='".$destid."' AND fld_rubric_id='".$ruborderid."' AND fld_rubric_rpt_id='".$maxid."'
                                                    AND fld_exp_id='".$expid."' AND fld_rubric_nameid='".$rubid."' AND fld_student_id='".$stuid[$i]."'");
            if($cnt==0)
            {
                    $ObjDB->NonQuery("INSERT INTO itc_exp_rubric_rpt_statement (fld_rubric_rpt_id, fld_dest_id, fld_rubric_id, fld_score, fld_created_by, fld_created_date, fld_rubric_nameid, fld_exp_id, fld_student_id) 
                                                                 VALUES ('".$maxid."', '".$destid."', '".$ruborderid."', '".$score."', '".$uid."', '".$date."', '".$rubid."', '".$expid."', '".$stuid[$i]."')");
            }
            else
            {
                   $ObjDB->NonQuery("UPDATE itc_exp_rubric_rpt_statement 
                                        SET fld_score='".$score."', fld_delstatus='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
                                        WHERE fld_dest_id='".$destid."' AND fld_rubric_id='".$ruborderid."' AND fld_rubric_rpt_id='".$maxid."'
                                        AND fld_exp_id='".$expid."' AND fld_rubric_nameid='".$rubid."' AND fld_student_id='".$stuid[$i]."'");
            }
        }
        echo "success";
    }
    catch(Exception $e)
    {
        echo "fail";
    }
}

