<?php
@include("sessioncheck.php");

$id  = isset($method['id']) ? $method['id'] : '0';
$ids=explode(",",$id);

$expeditionid=$ids[0];
$rubricnameid=$ids[1];

if($sessprofileid == 2)
{
    $ownrubricorothers=1;
}
else 
{
    $ownrubricorothers=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_exp_rubric_name_master WHERE fld_id='$rubricnameid' AND fld_exp_id='$expeditionid' AND fld_delstatus='0' AND fld_created_by='".$uid."'");
}




$exptitle=$ObjDB->SelectSingleValue("SELECT a.fld_exp_name FROM itc_exp_master AS a
								 LEFT JOIN itc_license_exp_mapping AS b ON a.fld_id=b.fld_exp_id 
								 WHERE a.fld_id='".$expeditionid."' AND b.fld_delstatus='0' AND a.fld_flag='1' AND a.fld_delstatus='0'
								 GROUP BY a.fld_id");


  $createbtn = "Save as rubric";
  $rubricname=$ObjDB->SelectSingleValue("SELECT fld_rub_name FROM itc_exp_rubric_name_master WHERE fld_exp_id='".$expeditionid."' AND fld_delstatus = '0' AND fld_id='".$rubricnameid."'"); //new line  AND fld_created_by='".$uid."'
  
?>
<section data-type='#library-rubric' id='library-rubric-graderubric'>
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
                    Rubric Name :
                     <?php 
                    if($ownrubricorothers == 1 )
                    { ?>
                      <dl class='field row'>
                          <dt class='text'>
                              <input placeholder='Rubric Name' type='text' id="txtrubricname" name="txtrubricname" value="<?php echo $rubricname;?>" onBlur="$(this).valid();" />
                          </dt>
                      </dl>
                       <?php 
                    } 
                    else
                    {  
                      echo " ".$rubricname;
                    } 
                      ?>
                </div>
                <div class='six columns'>
                  <input class="darkButton" type="button" id="btnstep2" style="width:200px; height:42px; float:right;  margin-top: 25px;" value="Add New Section" onClick="fn_addnewsection(<?php echo $expeditionid;?>,<?php echo $rubricnameid;?>,'0','','1','0');"  />
              </div>
             </div>
            
            
            <div class="row">
                <div class='rowspacer formBase'>  
                    <div id="expsetting" class='row rowspacer'>  
                        <div class='span10 offset1'>
                            <table class='table table-hover table-striped table-bordered setbordertopradius' id="mytablemm" >
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
								 </table>
                                 <div style="height:547px;overflow-y: auto;">
                                  <table class='table table-hover table-striped table-bordered setbordertopradius' id="mytable" >
                                <?php
                                  $qrydest=$ObjDB->QueryObject("SELECT a.fld_id as destid,a.fld_dest_name as destname FROM itc_exp_rubric_dest_master as a
                                                                            WHERE a.fld_exp_id='".$expeditionid."' AND a.fld_rubric_name_id='".$rubricnameid."'  AND a.fld_delstatus='0'");
                                    if($qrydest->num_rows > 0)
									{
                                         while($row=$qrydest->fetch_assoc())
										 {
                                             extract($row);
                                             $dname[]=$destname;
                                             $did[]=$destid;
                                         }
                                    }
                                    $scoreval=array();
                                    $mohan=1; $headcount=0;
                                    for($i=0;$i<sizeof($did);$i++) 
                                    {
                                        ?>
                                        <style> .bcolor{background: #F1F1F3;} .m{ text-decoration: overline;} .mm{text-decoration: underline;} *
                                            .table tr td:first-child {     padding-left: 20px;     }
                                        </style>
                                        <thead id="head_<?php echo $did[$i]; ?>">
                                          <tr class="bcolor">
                                              <td style="font:  bold 12px/30px Times New Roman;">DESTINATION <?php echo $i+1;?></td>
                                              <td colspan="7" id="destnationtxt-<?php echo $did[$i];?>" style="font:  bold 14px/30px Times New Roman;" onclick="fn_addnewsection('<?php echo $expeditionid; ?>','<?php echo $rubricnameid; ?>','<?php echo $did[$i]; ?>','<?php echo $dname[$i];?>','0');">
                                                <?php echo $dname[$i];?>                                             
                                                </td>
                                          </tr>
                                        </thead>
                                        <tbody id="row_<?php echo $did[$i]; ?>">
                                          <?php
                                            $qryviewexp_rubric=$ObjDB->QueryObject("SELECT c.fld_id as rowid, a.fld_id as destid,a.fld_dest_name as destname, c.fld_category as category,c.fld_four as four,c.fld_three as three,
                                                                                        c.fld_two as two,c.fld_one as one, c.fld_zer as zer ,c.fld_weight as weight,c.fld_score as score FROM itc_exp_rubric_dest_master as a
                                                                                            LEFT JOIN itc_exp_rubric_master AS c ON c.fld_destination_id=a.fld_id 
                                                                                            WHERE c.fld_rubric_id='".$rubricnameid."' AND c.fld_exp_id='".$expeditionid."' AND c.fld_destination_id='".$did[$i]."'  AND a.fld_delstatus='0' AND c.fld_delstatus='0' ");//AND c.fld_created_by='".$uid."'
                                            if($qryviewexp_rubric->num_rows > 0) 
                                            {
                                                $cnt=1;  $rowval=array();
                                                while($row=$qryviewexp_rubric->fetch_assoc())
                                                {
                                                    extract($row);

                                                    //placeholders array
                                                     $placeholders = array('•', '♦', '◘','*');//'>', '<'
                                                    //replace values array
                                                    $replace = array('<br/>•', '<br/>♦', '<br/>◘','<br/>*');//'greater than', 'less than'
                                                  
                                                    $category = str_replace($placeholders, $replace, $category);
                                                    $category = str_replace(',', '', $category);
                                                    $four = str_replace($placeholders, $replace, $four);
                                                    $three = str_replace($placeholders, $replace, $three);
                                                    $two = str_replace($placeholders, $replace, $two);
                                                    $one = str_replace($placeholders, $replace, $one);
                                                    $zer = str_replace($placeholders, $replace, $zer);
                                                    $scoreval[]=$score;
                                                    $rowval[]=$rowid;
                                                  
                                                    ?>

                                                    <tr class="exp-rubric-<?php echo $did[$i]; ?>" id="exp-rubric-<?php echo $rowid; ?>" onclick="fn_showdeststmteditform('<?php echo ($expeditionid); ?>','<?php echo $did[$i]; ?>','<?php echo $rowid; ?>','1','<?php echo $rubricnameid; ?>','1','<?php echo $dname[$i];?>');this.disabled='disabled';"  >
                                                      <td width="18%" id="rubrictxt_<?php echo $rowid; ?>_1" ><?php echo $category ;?></td>
                                                      <td width="14%" id="rubrictxt_<?php echo $rowid; ?>_2" ><?php echo $four ;?></td>
                                                      <td width="14%" id="rubrictxt_<?php echo $rowid; ?>_3" ><?php echo $three ;?></td>
                                                      <td width="13%" id="rubrictxt_<?php echo $rowid; ?>_4" ><?php echo $two ;?></td>
                                                      <td width="13%" id="rubrictxt_<?php echo $rowid; ?>_5" ><?php echo $one ;?></td>
                                                      <td width="13%" id="rubrictxt_<?php echo $rowid; ?>_6" ><?php echo $zer ;?></td>
                                                      <td width="7%" id="rubrictxt_<?php echo $rowid; ?>_7" ><?php echo "X".$weight ;?></td>
                                                      <td width="7%" id="rubrictxt_<?php echo $rowid; ?>_8" class='centerText m'><?php echo $score ;?></td>
                                                    </tr>
                                                    <input type="hidden" name="hiddestid" id="hiddestid_<?php echo $rowid;?>_0" value="<?php echo $did[$i]; ?>">
                                                    <input type="hidden" name="hidrubrowid" id="hidrubrowid_<?php echo $rowid;?>_0" value="<?php echo $rowid; ?>">
                                                    <input type="hidden" name="hiddestname" id="hiddestname_<?php echo $did[$i]; ?>_0" value="<?php echo $dname[$i]; ?>">
                                                    <?php
                                                } 
                                            }
                                            else
                                            {    ?>
                                               <thead>
                                                  <tr id="exp-rubric-<?php echo $mohan;  ?>">
                                                      <td colspan="8" align="center"></td>
                                                    </tr> 
                                              </thead>
                                              <?php 
                                            } ?>                                                      
                                           
                                            <tr value="<?php echo $mohan;  ?>" id="countrow-<?php echo $mohan;  ?>">
                                                <td colspan="8">
                                                    <span onclick="fn_showdeststmtform('<?php echo ($expeditionid); ?>','<?php echo $did[$i]; ?>','0','<?php echo $rubricnameid; ?>','<?php echo $mohan; ?>','<?php echo $dname[$i];?>');this.disabled='disabled';" > <span class="icon-synergy-add-dark small-icon" style="padding-top:10px;"></span>Add additional category to this destination</span>
                                                </td>
                                            </tr>                                               
                                            <?php// } ?>
                                      </tbody>
                                      <?php   
                                      $mohan++; $headcount++;
                                   }
                                  ?>
                            </table>
                          <div id="bottomOfDiv">             <div>
                        </div>
                    </div>
                    </div>
                    <?php 
                   
                    $totalscore==0;                  
                    for($w=0;$w<sizeof($scoreval);$w++){
                        $totalscore=$scoreval[$w]+$totalscore;
                    }
                        
                    $countrubric1=array();
                    $countrubric=$ObjDB->QueryObject("SELECT fld_id as rubricrowid FROM itc_exp_rubric_master WHERE fld_rubric_id='$rubricnameid' AND fld_exp_id='$expeditionid' AND fld_delstatus='0'");
                    if($countrubric->num_rows > 0)
                    {
                        while($row=$countrubric->fetch_assoc())
                        {
                            extract($row);
                            $countrubric1[]=$rubricrowid;
                        }
                    }
                    
                    $dbrowcount=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_exp_rubric_master WHERE fld_rubric_id='$rubricnameid' AND fld_exp_id='$expeditionid' AND fld_delstatus='0'");
                    $destcount=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_exp_rubric_dest_master WHERE fld_exp_id='$expeditionid'AND fld_rubric_name_id='$rubricnameid' AND fld_delstatus='0'");
                    $lastrowid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_exp_rubric_dest_master ORDER BY fld_id DESC LIMIT 1");
                    $lastrowid=$lastrowid+1;
                    $empdestcount=$dbrowcount+$destcount
                    ?>
                    <input type="hidden" name="hidaddnewsec" id="hidaddnewsec" value="<?php echo $lastrowid; ?>" />
                    <input type="hidden" name="hidtrrubric" id="hidtrrubric" value=<?php echo $headcount;?> />
					          <input type="hidden" name="hidtrcrubric" id="hidtrcrubric" value=<?php echo $headcount;?> />
                    <input type="hidden" name="hidrubriccount" id="hidrubriccount" value=<?php echo json_encode($countrubric1);?> />
                    <input type="hidden" name="hiddbrubriccount" id="hiddbrubriccount" value=<?php echo $dbrowcount;?> />
                    <input type="hidden" name="hiddbrdestcount" id="hiddbrdestcount" value=<?php echo $empdestcount;?> />
                    <input type="hidden" name="hiddbtotscore" id="hiddbtotscore" value=<?php echo $totalscore;?> />
                    <input type="hidden" name="hidneworimport" id="hidneworimport" value="0" />
                    <input type="hidden" name="rowcount" id="rowcount" value="0" />
                    <div class='twelve columns'>
                      <div class='six columns'>
                          <input class="darkButton" type="button" id="btnstep2" style="width:200px; height:42px; float:left;  margin-top: 25px;  margin-left: -18px;" value="Add New Section" onClick="fn_addnewsection(<?php echo $expeditionid;?>,<?php echo $rubricnameid;?>,'0','','1','0');"  />
                      </div>
                      <div class='six columns'>
                         <span style="float: right; margin-top: 32px; margin-right: 18px; ">Total Score&nbsp;&nbsp;&nbsp;<mm id='totscore'><?php echo $totalscore; ?></mm></span> 
                      </div>
                 </div>
              </div>
            </div>
            <input type="hidden" name="rubnamecount" id="rubnamecount" value="<?php echo $rubricname; ?>">
               
                <?php
                    if($ownrubricorothers == 1 ){?>
                            <div class='row rowspacer' id="viewreportdiv">
                            <div class='three columns'>
                                <input class="darkButton" type="button" id="btnstep2" style="width:200px; height:42px; float:right;" value="Update" onClick="fn_saveasrubric(<?php echo $expeditionid;?>,<?php echo $rubricnameid;?>,'1');"  />
                            </div>
                            <div class='three columns'>
                            </div>                          
                            <div class='three columns' style="margin-top:-24px;">
                                Rubric Name<span class="fldreq">*</span>
                                <dl class='field row'>
                                    <dt class='text'>
                                    <input placeholder='Rubric Name' type='text' id="txtrubricnameforsaveas" name="txtrubricnameforsaveas" value="" onBlur="$(this).valid();" />
                                    </dt>
                                </dl>
                            </div>
                            <div class='three columns' style="float: right">
                                <input class="darkButton" type="button" id="btnstep2" style="width:200px; height:42px; float:right;" value="<?php echo $createbtn;?>" onClick="fn_saveasrubric(<?php echo $expeditionid;?>,<?php echo $rubricnameid;?>,'0');"  />
                            </div>
                        </div><?php
                    }
                    else {?>
                        <div class='row rowspacer' id="viewreportdiv">
                            <div class='three columns'>
                                </div>
                            <div class='three columns'>
                            </div>                          
                            <div class='three columns' style="margin-top:-24px;">
                                Rubric Name<span class="fldreq">*</span>
                                <dl class='field row'>
                                    <dt class='text'>
                                    <input placeholder='Rubric Name' type='text' id="txtrubricnameforsaveas" name="txtrubricnameforsaveas" value="" onBlur="$(this).valid();" />
                                    </dt>
                                </dl>
                            </div>
                            <div class='three columns' style="float: right">
                                <input class="darkButton" type="button" id="btnstep2" style="width:200px; height:42px; float:right;" value="<?php echo $createbtn;?>" onClick="fn_saveasrubric(<?php echo $expeditionid;?>,<?php echo $rubricnameid;?>,'0');"  />
                            </div>
                        </div>
                        <?php 
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
                            return '<?php echo $rubricnameid;?>';},
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