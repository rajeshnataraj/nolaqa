<?php
@include("sessioncheck.php");

$id  = isset($method['id']) ? $method['id'] : '0';
$id=explode(",",$id);
$expeditionid=$id[0];
$rubid=$id[1];

$checkprofileid=$ObjDB->SelectSingleValueInt("SELECT fld_profile_id FROM itc_user_master where fld_id='".$uid."' and fld_activestatus='1' and fld_delstatus='0'");
if($checkprofileid == 9){
    $tablename="itc_exp_rubric_teacher_master";
}
else {
    $tablename="itc_exp_rubric_master"; 
}

$exptitle=$ObjDB->SelectSingleValue("SELECT a.fld_exp_name FROM itc_exp_master AS a
								 LEFT JOIN itc_license_exp_mapping AS b ON a.fld_id=b.fld_exp_id 
								 WHERE a.fld_id='".$expeditionid."' AND b.fld_delstatus='0' AND a.fld_flag='1' AND a.fld_delstatus='0'
								 GROUP BY a.fld_id");

/// Insert New record,pitsco Admin will add a new record existing rubric statement 
  $createbtn = "Save as rubric";
  $rubricname=$ObjDB->SelectSingleValue("SELECT fld_rub_name FROM itc_exp_rubric_name_master WHERE fld_exp_id='".$expeditionid."' AND fld_id='".$rubid."'");
  
?>
<section data-type='#library-rubric' id='library-rubric-editgraderubric'>
<script type="text/javascript" charset="utf-8">		
	$.getScript("library/rubric/library-rubric.js");
        
</script>
<div class='container'>
    <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle"><?php echo $rubricname; ?></p>
                <p class="dialogSubTitle">&nbsp;</p>
            </div>
    </div>
    
    <div class='row formBase rowspacer'>
        <div class='eleven columns centered insideForm'>
            <form name="rubricforms" id="rubricforms">
            <div class='row  rowspacer'> 
                <div class='six columns'>
                    Rubric Name<span class="fldreq">*</span>
                    <dl class='field row'>
                        <dt class='text'>
                            <input placeholder='Rubric Name' type='text' id="txtrubricname" name="txtrubricname" value="<?php echo $rubricname;?>" onBlur="$(this).valid();" />
                        </dt>
                    </dl>
                </div>
                <div class='six columns'></div>
             </div>
            
            
          <div class="row">
                <div class='rowspacer formBase'>  
                    <div id="expsetting" class='row rowspacer'>  
                        <div class='span10 offset1'>
                            <table class='table table-hover table-striped table-bordered setbordertopradius' id="mytable" >
                                <thead >
                                    <tr style="cursor:default;">
                                        <th width="17%">Category</th>
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
                                    $scoreval=array();
                                    for($i=0;$i<sizeof($did);$i++) 
                                    {
                                      ?>
                                <tbody> <style> .bcolor{background: #F1F1F3;} m{ text-decoration: overline;} mm{text-decoration: underline;}</style>
                                          <tr class="bcolor">
                                              <td style="font:  bold 13px/30px Times New Roman;">DESTINATION <?php echo $i+1;?></td>
                                            <td colspan="7" style="font:  bold 14px/30px Times New Roman;"><?php echo $dname[$i];?></td>
                                          </tr>
                                          <?php
                                            $checkalreadyexistornot=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_exp_rubric_teacher_master where fld_exp_id = '".$expeditionid."' AND fld_dest_id = '".$did[$i]."' AND fld_delstatus = '0' AND fld_created_by = '".$uid."' AND fld_rubric_nameid='".$rubid."'");
                                            if($checkalreadyexistornot!=0){
                                              $tablename="itc_exp_rubric_teacher_master";
                                              $uid1=$uid;
                                              
                                               $qryviewexp_rubric=$ObjDB->QueryObject("SELECT c.fld_id as rubricid, a.fld_id as destid,a.fld_dest_name as destname, c.fld_category as category,c.fld_four as four,c.fld_three as three,
                                                                                        c.fld_two as two,c.fld_one as one, c.fld_zer as zer ,c.fld_weight as weight,c.fld_score as score FROM itc_exp_destination_master as a
                                                                                            LEFT JOIN itc_exp_master AS b ON b.fld_id=a.fld_exp_id 
                                                                                            LEFT JOIN $tablename AS c ON c.fld_dest_id=a.fld_id 
                                                                                            WHERE c.fld_exp_id='".$expeditionid."' AND c.fld_dest_id='".$did[$i]."' AND c.fld_rubric_nameid='".$rubid."' AND a.fld_flag='1' AND a.fld_delstatus='0' AND c.fld_delstatus='0' AND c.fld_created_by='".$uid1."'");
                                              
                                          }
                                          else
                                          {
                                            $tablename="itc_exp_rubric_master";
                                            $uid1='2';
                                            
                                            $qryviewexp_rubric=$ObjDB->QueryObject("SELECT c.fld_id as rubricid, a.fld_id as destid,a.fld_dest_name as destname, c.fld_category as category,c.fld_four as four,c.fld_three as three,
                                                                                        c.fld_two as two,c.fld_one as one, c.fld_zer as zer ,c.fld_weight as weight,c.fld_score as score FROM itc_exp_destination_master as a
                                                                                            LEFT JOIN itc_exp_master AS b ON b.fld_id=a.fld_exp_id 
                                                                                            LEFT JOIN $tablename AS c ON c.fld_dest_id=a.fld_id 
                                                                                            WHERE c.fld_exp_id='".$expeditionid."' AND c.fld_dest_id='".$did[$i]."' AND a.fld_flag='1' AND a.fld_delstatus='0' AND c.fld_delstatus='0' AND c.fld_created_by='".$uid1."'");
                                          }
                                          
                                          
                                          
                                          
                                            
                                            if($qryviewexp_rubric->num_rows > 0) 
                                            {
                                                $cnt=1;
                                                while($row=$qryviewexp_rubric->fetch_assoc())
                                                {
                                                 extract($row);
                                                 $scoreval[]=$score;
                                                    ?>
                                          <?php
                                          $checkpitscoorteacher=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_exp_rubric_name_master where fld_exp_id='".$expeditionid."' AND fld_rub_name='".$rubricname."' AND fld_delstatus = '0' AND fld_created_by = '".$uid."'");
                                          if($checkpitscoorteacher==0){?>
                                               <tr class="Btn" id="exp-rubric-<?php echo $rubricid; ?>" >
                                         <?php }
                                         else
                                         {?>
                                             <tr class="Btn" id="exp-rubric-<?php echo $rubricid; ?>" onclick="fn_showdeststmteditform('<?php echo ($expeditionid); ?>','<?php echo $did[$i]; ?>','<?php echo $rubricid; ?>','1','<?php echo $rubid; ?>');this.disabled='disabled';"  >
                                                      
                                       <?php  }
                                          ?>
                                                    <td width="17%" id="rubrictxt-<?php echo $rubricid; ?>" ><?php echo $category ;?></td>
                                                      <td width="14%" id="rubrictxt-<?php echo $rubricid; ?>" class='centerText'><?php echo $four ;?></td>
                                                      <td width="14%" id="rubrictxt-<?php echo $rubricid; ?>" class='centerText'><?php echo $three ;?></td>
                                                      <td width="13%" id="rubrictxt-<?php echo $rubricid; ?>" class='centerText'><?php echo $two ;?></td>
                                                      <td width="13%" id="rubrictxt-<?php echo $rubricid; ?>" class='centerText'><?php echo $one ;?></td>
                                                      <td width="13%" id="rubrictxt-<?php echo $rubricid; ?>" class='centerText'><?php echo $zer ;?></td>
                                                      <td width="7%" id="rubrictxt-<?php echo $rubricid; ?>" class='centerText'><?php echo "X".$weight ;?></td>
                                                      <td width="7%" id="rubrict-<?php echo $rubricid; ?>" class='centerText'><m><?php echo $score ;?></m></td>
                                                     
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                            else{           ?>
                                                    <tr id="exp-rubric-0">
                                                        <td colspan="8" align="center">  </td>
                                                    </tr> 
                                                    <?php }  if($sessprofileid == 2 || $sessprofileid == 3) { ?>  
                                                    
                                                    <tr>
                                                      <td colspan="8">
                                                       
                                                        <span onclick="fn_showdeststmtform('<?php echo ($expeditionid); ?>','<?php echo $did[$i]; ?>','0');this.disabled='disabled';" > <span class="icon-synergy-add-dark small-icon" style="padding-top:10px;"></span>Add a detail of the statements in this destination</span>
                                                      </td>
                                                    </tr>   
                                                     <?php }  ?>
                                      </tbody>
                                     <?php   
                                     }
                                    ?>
                            </table>
                           
                        </div>
                    </div>
                    <?php 
                   
                    $totalscore==0;                   
                    for($w=0;$w<sizeof($scoreval);$w++){
                        $totalscore=$scoreval[$w]+$totalscore;
                        
                    }
                    
                    ?>
                    
                   </div><span style="margin-left: 735px;">Total Score&nbsp;&nbsp;&nbsp;<mm><?php echo $totalscore; ?></mm></span> 
            </div>
                
                <input type="hidden" name="rubnamecount" id="rubnamecount" value="<?php echo $rubricname; ?>">
                <!--View Report Button-->
                  <?php if($sessmasterprfid == 2 || $sessmasterprfid == 3 ){?>
                            <div class='row rowspacer' id="viewreportdiv">
                                <input class="darkButton" type="button" id="btnstep2" style="width:200px; height:42px; float:right;" value="<?php echo $createbtn;?>" onClick="fn_saverubric(<?php echo $expeditionid;?>);"  />
                            </div><?php }
                    else {?>
                        <div class='row rowspacer' id="viewreportdiv">
                           <input class="darkButton" type="button" id="btnstep2" style="width:200px; height:42px; float:right;" value="<?php echo $createbtn;?>" onClick="fn_saverubrictea(<?php echo $expeditionid;?>,'<?php echo $rubid;?>','<?php echo $checkpitscoorteacher;?>');"  />
                        </div> <?php 
                    }
                  
                  ?>
            </form> 
        </div>
    </div>   
 </div>
    <script type="text/javascript" language="javascript">
    //Function to validate the form
    $("#rubricforms").validate({
            ignore: "",
            errorElement: "dd",
            errorPlacement: function(error, element) {
                    $(element).parents('dl').addClass('error');
                    error.appendTo($(element).parents('dl'));	
                    error.addClass('msg');
            },
            rules: {
                    txtrubricname: { required: true, lettersonly: true, 
                    remote:{ 
                       url: 'library/rubric/library-rubric-graderubricajax.php',
                        type:"post", 
                        data: {  
                                uid: function() {
                                return '<?php echo $expeditionid;?>';},
                                oper: function() {
                                return 'checkrubricname';}
                            },
                            async:false } 
                        }
            }, 
            messages: { 
                    txtrubricname: { required: "Please type Rubric Name", remote: "Rubric Name already exists" }

            },
            highlight: function(element, errorClass, validClass) {
                    $(element).addClass(errorClass).removeClass(validClass);
            },
            unhighlight: function(element, errorClass, validClass) {
                    if($(element).attr('class') == 'error'){
                            $(element).parents('dl').removeClass(errorClass);
                            $(element).removeClass(errorClass).addClass(validClass);
                    }
            },
            onkeyup: false,
            onblur: true
    });
    </script>
</section>