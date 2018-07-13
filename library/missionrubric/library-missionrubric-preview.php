<?php
@include("sessioncheck.php");
 
$id  = isset($method['id']) ? $method['id'] : '0';
$id=explode(",",$id);
$missionid=$id[0];
$rubricnameid=$id[1];

$rubricname=$ObjDB->SelectSingleValue("SELECT fld_rub_name FROM itc_mis_rubric_name_master WHERE fld_mis_id='".$missionid."' AND fld_id='".$rubricnameid."' ");//AND fld_created_by='".$uid."'


$exptitle=$ObjDB->SelectSingleValue("SELECT a.fld_mis_name FROM itc_mission_master AS a
								 LEFT JOIN itc_license_mission_mapping AS b ON a.fld_id=b.fld_mis_id 
								 WHERE a.fld_id='".$missionid."' AND b.fld_delstatus='0' AND a.fld_flag='1' AND a.fld_delstatus='0'
								 GROUP BY a.fld_id");


?>
<section data-type='#library-missionrubric' id='library-missionrubric-preview'>
<script type="text/javascript" charset="utf-8">		
	$.getScript("library/missionrubric/library-missionrubric.js");	
</script>
<div class='container'>
    <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle"><?php echo $rubricname; ?></p>
                <p class="dialogSubTitle">&nbsp;</p>
            </div>
			<div id="dist" style="" class="row rowspacer">
				<?php $rubricname=str_replace(' ', '_', $rubricname); ?>
                <input type="button" id='btnlibrary-missionrubric-preview' onClick="fn_downloadpdf(<?php echo $missionid; ?>,<?php echo $rubricnameid; ?>,'<?php echo $rubricname.'_'; ?>');" value="Download to Print / Save" style="height:42px; float:right;" id="btnstep" class="darkButton">
            </div>
    </div>
    
    <div class='row formBase rowspacer'>
        <div class='eleven columns centered insideForm'>
            <div class="row">
                <div class='rowspacer formBase'>  
                    <div id="expsetting" class='row rowspacer'>  
                        <div class='span10 offset1'>
                            <table class='table table-hover table-striped table-bordered setbordertopradius' id="mytable" >
                                <thead >
                                    <tr style="cursor:default;">
                                        <th width="19.5%">Category</th>
                                        <th width="14.7%" class='centerText'>4</th>
                                        <th width="14.7%" class='centerText'>3</th>
                                        <th width="13.5%" class='centerText'>2</th>
                                        <th width="13.5%" class='centerText'>1</th>
                                        <th width="13.3%" class='centerText'>0</th>
                                        <th width="7%" class='centerText'>Weight</th>
                                        <th width="7%" class='centerText'>Score</th>
                                            <th style='border-left: none;'></th>
                                    </tr>
                                </thead>
                              </table>
                          <div style="height:547px;overflow-y: auto;">
                                  <table class='table table-hover table-striped table-bordered setbordertopradius' id="mytable" >
                                <?php

                                  $qrydest=$ObjDB->QueryObject("SELECT a.fld_id as destid,a.fld_dest_name as destname 
								  									FROM itc_mis_rubric_dest_master as a
																	WHERE a.fld_mis_id='".$missionid."' AND a.fld_rubric_name_id='".$rubricnameid."' 
																	AND a.fld_delstatus='0'");
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
                           
                                        <tbody> 
                                            <style> .bcolor{background: #F1F1F3;} m{ text-decoration: overline;} mm{text-decoration: underline;}
                                                .table tr td:first-child {
                                                    padding-left: 20px;
                                                }
                                            </style>
                                          <tr class="bcolor">
                                                <td style="font:  bold 12px/30px Times New Roman;">Interval <?php echo $i+1;?></td>
                                            <td colspan="7" style="font:  bold 14px/30px Times New Roman;"><?php echo $dname[$i];?></td>
                                          </tr>
                                          <?php

                                            
                                            $qryviewexp_rubric=$ObjDB->QueryObject("SELECT c.fld_id as rubricid, a.fld_id as destid,a.fld_dest_name as destname, c.fld_category as category,c.fld_four as four,c.fld_three as three,
                                                                                        c.fld_two as two,c.fld_one as one, c.fld_zer as zer ,c.fld_weight as weight,c.fld_score as score FROM itc_mis_rubric_dest_master as a
                                                                                            LEFT JOIN itc_mis_rubric_master AS c ON c.fld_destination_id=a.fld_id 
                                                                                            WHERE c.fld_rubric_id='".$rubricnameid."' AND c.fld_mis_id='".$missionid."' AND c.fld_destination_id='".$did[$i]."' AND a.fld_delstatus='0' AND c.fld_delstatus='0' ");//AND c.fld_created_by='".$uid."' 
                                              
                                          
                                            if($qryviewexp_rubric->num_rows > 0) 
                                            {
                                                $cnt=1;
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
                                                    ?>
                                                    <tr class="Btn" id="exp-rubric-<?php echo $rubricid; ?>">
                                                      <td width="18%" id="rubrictxt-<?php echo $rubricid; ?>" ><?php echo $category ;?></td>
                                                      <td width="14%" id="rubrictxt-<?php echo $rubricid; ?>" ><?php echo $four ;?></td>
                                                      <td width="14%" id="rubrictxt-<?php echo $rubricid; ?>" ><?php echo $three ;?></td>
                                                      <td width="13%" id="rubrictxt-<?php echo $rubricid; ?>" ><?php echo $two ;?></td>
                                                      <td width="13%" id="rubrictxt-<?php echo $rubricid; ?>" ><?php echo $one ;?></td>
                                                      <td width="13%" id="rubrictxt-<?php echo $rubricid; ?>" ><?php echo $zer ;?></td>
                                                      <td width="7%" id="rubrictxt-<?php echo $rubricid; ?>" ><?php echo "X".$weight ;?></td>
                                                      <td width="7%" id="rubrictxt-<?php echo $rubricid; ?>" ><m><?php echo $score ;?></m></td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                            else{           ?>
                                                    <tr id="exp-rubric-0">
                                                        <td colspan="8" align="center">  </td>
                                                </tr> <?php
                                            }  ?>
                                      </tbody>
                                     
                                     <?php   
                                     }
                                    ?>
                           </table>
                             </div>
                        </div>
                    </div>
                    <?php 
                   
                    $totalscore==0;                    
                    for($w=0;$w<sizeof($scoreval);$w++){
                        $totalscore=$scoreval[$w]+$totalscore;
                        
                    }
                    
                    ?>
                    <input type="hidden" name="result" id="result" value="<?php echo $scoreposible; ?>">
                    
                    
                </div> <span style="margin-left: 735px;">Total Score&nbsp;&nbsp;&nbsp;<mm><?php echo $totalscore; ?></mm></span> 
                </div>
            </div> 
        </div>
    </div>
</section>