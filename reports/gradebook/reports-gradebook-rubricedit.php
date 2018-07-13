<?php

error_reporting(0);
@include("sessioncheck.php");


//error_reporting(E_ALL);
//ini_set('display_errors', '1');

$id = isset($method['id']) ? $method['id'] : '0';
$id = explode(",",$id);

$type=$id[0];
$classid=$id[1];
$studentid=$id[2];
$expeditionid=$id[3];
$rubid=$id[4];
$rubricid=$id[4];

$schid=$id[5];
if($type == "20" || $type == '19'){
    $type = '18';
}
$scheduletype = $type;
$schedid = $schid;
$clasid = $classid;
$createbtn = "Save";
?>
    <style>
        table td.grcategory, table td.desctd, table th.grcategory, table th.thdesctd, table td.scoretd, table th.scoretd{
            overflow-x: hidden;
            word-break: normal;
            word-wrap: break-word;
            letter-spacing: -0.1px;
            word-spacing: -0.1px;
            padding:6px 3px !important;
            text-align: center;
            vertical-align: middle !important;
        }
        table td.grcategory, table td.desctd, table td.scoretd{
            font-size: 13px;
            font-size: 12.5px;
            line-height: 15px;
        }
        table th.grcategory, table th.thdesctd{
            font-size:  14px;
            word-spacing:-0.2px;
            line-height: 20px;
            font-weight:bold;
            text-transform: capitalize;
        }
        table td.desctd{
            width:11%;
            width:10.9%;
            max-width:75px;
        }
        table td.scoretd{
            width:7%;
            width:6.9%;
            max-width:65px;
            text-align: center;
            font-weight:bold;
            font-size: 14px;
            margin-left:-2px;
        }
        table td.grcategory{
            width:15%;
            width:14.9%;
            max-width:75px;
            font-weight:bold;
        }
        .standardsbtn{
            font-size:  12px; letter-spacing:-0.1px; word-spacing:-0.2px;  text-align:center;   word-wrap: break-word;     line-height: 16px; font-weight:bold;
            display:inline-block; padding:6px; border-radius:6px;
            background: #cbcbcb;
            background-image: url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zd…AiIHk9IjAiIHdpZHRoPSIxIiBoZWlnaHQ9IjEiIGZpbGw9InVybCgjZzIyOSkiIC8+PC9zdmc+);
            background: -moz-linear-gradient(50% 0% -90deg, #ebf7f8 0%, #cdcece 93%, #cbcbcb 100%);
            background: -o-linear-gradient(-90deg, #ebf7f8 0%, #cdcece 93%, #cbcbcb 100%);
            background: -webkit-gradient(linear, 50% 0%, 50% 100%, color-stop(0, #ebf7f8), color-stop(0.93, #cdcece), color-stop(1, #cbcbcb));
            background: -webkit-linear-gradient(-90deg, #ebf7f8 0%, #cdcece 93%, #cbcbcb 100%);
            background: linear-gradient(180deg, #ebf7f8 0%, #cdcece 93%, #cbcbcb 100%);
            border: 1px solid #999;
            -webkit-box-shadow: 0 2px 3px rgba(0,0,0,.3), inset 0 0 2px rgba(255,255,255,.65), inset 0 -1px 2px rgba(0,0,0,.3);
            box-shadow: 0 2px 3px rgba(0,0,0,.3), inset 0 0 2px rgba(255,255,255,.65), inset 0 -1px 2px rgba(0,0,0,.3);
            color: #24475f;
        }
        .standardsbtn:hover{
            -webkit-box-shadow: inset 0 1px 1px #fff, 0 1px 2px rgba(0,0,0,0.31);
            box-shadow: inset 0 1px 1px #fff, 0 1px 2px rgba(0,0,0,0.31);
            background: #ccc;
            background: -moz-linear-gradient(top, #fff 0%, #ddd 100%);
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#fff), color-stop(100%,#ddd));
            background: -webkit-linear-gradient(top, #fff 0%,#ddd 100%);
            background: -o-linear-gradient(top, #fff 0%,#ddd 100%);
            background: -ms-linear-gradient(top, #fff 0%,#ddd 100%);
            background: linear-gradient(top, #fff 0%,#ddd 100%);
            filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#dddddd',GradientType=0 );
            color: #143344;
        }
        .standardslist{
            font-size:13px;
            color:#fff;
            letter-spacing:+0.2px;
            word-spacing:+0.2px;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-weight:bold;
            line-height:16px;
            text-align:left;
            display:block;
            padding:12px 24px;
        }

    </style>
<section data-type='2home' id='reports-gradebook-rubricedit'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Student Name: <?php echo $studentname = $ObjDB->SelectSingleValue("SELECT CONCAT(fld_fname,' ',fld_lname) 
                                                                                                                            FROM itc_user_master 
                                                                                                                            WHERE fld_id='".$studentid."' 
                                                                                                                            ORDER BY fld_lname"); ?></p>
            </div>
        </div>
         
      <div class='row formBase rowspacer'>
        <div class='eleven columns centered insideForm'>
            <form name="rubricforms" id="rubricforms">
            <div class='row'>
                <div class='twelve columns'>
                    <div class='six columns'>
                        <?php if($type=='16' || $type=='21' || $type=='25' ){ ?>
                       Rubric Name: <?php echo $rubricname=$ObjDB->SelectSingleValue("SELECT fld_rub_name FROM itc_exp_rubric_name_master WHERE fld_exp_id='".$expeditionid."' AND fld_id='".$rubid."' AND fld_delstatus='0'");?>
                        <?php 
                              $totscore=$ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_exp_rubric_master WHERE fld_exp_id='".$expeditionid."' AND fld_rubric_id='".$rubid."' AND fld_delstatus='0'");               
                                              
                         }else{ ?>
                         Rubric Name: <?php echo $rubricname=$ObjDB->SelectSingleValue("SELECT fld_rub_name FROM itc_mis_rubric_name_master WHERE fld_mis_id='".$expeditionid."' AND fld_id='".$rubid."' AND fld_delstatus='0'");?>
                        <?php 
                             $totscore=$ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_mis_rubric_master WHERE fld_mis_id='".$expeditionid."' AND fld_rubric_id='".$rubid."' AND fld_delstatus='0'");                     
                          }?>
                        

                    </div> 
                     <div class='six columns'>
                         <div class="row" style="margin-left:84px;">
                             <input type="hidden" id="rubschedid" value="<?php echo $schedid; ?>">
                             <input type="hidden" id="classid" value="<?php echo $classid; ?>">
                                <input type="hidden" name="totalscore" id="totalscore" value="">
                             <table>
                                 <tr>
                                   <td> Final Score: &nbsp;</td> <td id='studentscore' class='studentscore'> </td>
                                     <td> &nbsp;<?php echo " / ".$totscore; ?></td>
                                     <td>&nbsp; &nbsp;
                                         <input class="darkButton" type="button" id="btnstep2" style="float: none; height: 34px; width: 76px; margin-right:8px;" value="View" onClick="fn_digitalrubricstudent('<?php echo $rubid;?>','<?php echo $classid;?>','<?php echo $studentid;?>','<?php echo $expeditionid;?>','<?php echo $schid;?>','<?php echo $type;?>');" />
                                     <?php
                                        if($type=='16' || $type=='21' || $type=='25')
                                        {   ?>
                                             &nbsp;&nbsp;&nbsp;<input class="darkButton" name="0,<?php echo $classid; ?>, 1" type="button" id="btnstep2" style="float: none; height: 34px; width: 76px;" value="Save" onClick="fn_saverubric('<?php echo $rubid;?>','<?php echo $expeditionid;?>','<?php echo $scheduletype;?>');" />
                                            <?php
                                        }
                                        else
                                        {   ?>
                                               &nbsp;&nbsp;&nbsp;<input class="darkButton" name="0,<?php echo $classid; ?>, 1" type="button" id="btnstep2" style="float: none; height: 34px; width: 76px;" value="Save" onClick="fn_saverubric('<?php echo $rubid;?>','<?php echo $expeditionid;?>','<?php echo $scheduletype;?>');" />
                                            <?php 
                                        }   ?>

                                    </td>

                                 </tr>
                             </table>
                            </div> 
                        
                     </div> 
                </div>
            </div>
                <div class="row">
                    <div class='rowspacer formBase'>
                        <div id="expsetting" class='row rowspacer'>
                            <div class='span10 offset1'>
                                <table class='table table-hover table-striped table-bordered setbordertopradius' id="mytable" >
                                    <thead>
                                    <tr style="cursor:default;">
                                        <th class="grcategory" width="15%">Category</th>
                                        <th width="11%" class='centerText thdesctd'>4</th>
                                        <th  width="11%" class='centerText thdesctd'>3</th>
                                        <th  width="11%" class='centerText thdesctd'>2</th>
                                        <th  width="11%" class='centerText thdesctd'>1</th>
                                        <th  width="11%" class='centerText thdesctd'>0</th>
                                        <th width="7%" class='centerText scoretd'>Weight</th>
                                        <th width="7%" class='centerText scoretd'>Score</th>
                                        <!-- Code by barney related to #23153-->
                                        <th width="15%" class='centerText'>Comment</th>
                                    </tr>
                                    </thead>
                                </table>
                                <div style="min-height:500px; max-height:90vh; overflow-y: auto;" class="rubrictable">
                                    <table class='table table-hover table-striped table-bordered setbordertopradius' id="mytable" >
                                        <?php

                                        if($type=='18')
                                        {
                                            $qrydest=$ObjDB->QueryObject("SELECT a.fld_id as destid,a.fld_dest_name as destname FROM itc_mis_rubric_dest_master as a 
                                      LEFT JOIN itc_mission_master AS b ON b.fld_id=a.fld_mis_id  
                                      WHERE a.fld_mis_id='".$expeditionid."'  
                                      AND a.fld_rubric_name_id='".$rubid."'  AND a.fld_delstatus='0'");

                                            /*$qrydest=$ObjDB->QueryObject("SELECT a.fld_id as destid,a.fld_dest_name as destname FROM itc_exp_destination_master as a
                                                                                LEFT JOIN itc_exp_master AS b ON b.fld_id=a.fld_exp_id
                                                                                WHERE a.fld_exp_id='".$expeditionid."' AND a.fld_flag='1' AND a.fld_delstatus='0'");*/
                                        }
                                        else
                                        {
                                            $qrydest=$ObjDB->QueryObject("SELECT a.fld_id as destid,a.fld_dest_name as destname FROM itc_exp_rubric_dest_master as a 
                                    WHERE a.fld_exp_id='".$expeditionid."' AND a.fld_rubric_name_id='".$rubid."'   
                                    AND a.fld_delstatus='0'");

                                            /*$qrydest=$ObjDB->QueryObject("SELECT a.fld_id as destid,a.fld_dest_name as destname FROM itc_mis_destination_master as a
                                                                                  LEFT JOIN itc_mission_master AS b ON b.fld_id=a.fld_mis_id
                                                                                  WHERE a.fld_mis_id='".$expeditionid."' AND a.fld_flag='1' AND a.fld_delstatus='0'");*/

                                        }

                                        if($qrydest->num_rows > 0){
                                            while($row=$qrydest->fetch_assoc()){
                                                extract($row);
                                                $dname[]=$destname;
                                                $did[]=$destid;
                                            }
                                        }

                                        $stuscoreval=array();
                                        for($i=0;$i<sizeof($did);$i++)
                                        { ?>
                                            <tbody>
                                            <style> .bcolor{background: #F1F1F3;} m{ text-decoration: overline;}
                                                .table tr td:first-child {
                                                    padding-left: 20px;
                                                }
                                                .td_select{    background-color:#99ccff !important; font-weight: bold;  /*border:solid #0066ff; */}
                                            </style>
                                            <tr class="bcolor">
                                                <td style="font-size:  14px; letter-spacing:-0.1px; word-spacing:-0.2px;     word-wrap: break-word;     line-height: 20px; font-weight:bold;">
                                                    <?php
                                                    if($type=='16' || $type=='21' || $type=='25')
                                                    {
                                                    ?>   DESTINATION <?php echo $i+1;?></td><?php
                                                }
                                                else
                                                {
                                                    ?>   INTERVAL <?php echo $i+1;?></td><?php
                                                }
                                                ?>

                                                <td colspan="8" style="font-size:  14px; letter-spacing:-0.1px; word-spacing:-0.2px;     word-wrap: break-word;     line-height: 20px; font-weight:bold;"><?php echo $dname[$i];?></td>
                                            </tr>
                                            <?php
                                            if($type=='18')
                                            {
                                                $qryviewexp_rubric=$ObjDB->QueryObject("SELECT c.fld_id as rubricid, a.fld_id as destid,a.fld_dest_name as destname, c.fld_category as category,c.fld_four as four,c.fld_three as three, 
                                            c.fld_two as two,c.fld_one as one, c.fld_zer as zer ,c.fld_weight as weight,c.fld_score as scoree FROM itc_mis_rubric_dest_master as a 
                                                LEFT JOIN itc_mission_master AS b ON b.fld_id=a.fld_mis_id  
                                                LEFT JOIN itc_mis_rubric_master AS c ON c.fld_destination_id=a.fld_id  
                                                WHERE c.fld_rubric_id='".$rubid."' AND c.fld_mis_id='".$expeditionid."'  
                                                AND c.fld_destination_id='".$did[$i]."' AND a.fld_delstatus='0'  
                                                AND c.fld_delstatus='0' ");//AND c.fld_created_by='".$uid."'
                                                /*
                                             $qryviewexp_rubric=$ObjDB->QueryObject("SELECT c.fld_id as rubricid, a.fld_id as destid,a.fld_dest_name as destname, c.fld_category as category,c.fld_four as four,c.fld_three as three,
                                                                                        c.fld_two as two,c.fld_one as one, c.fld_zer as zer ,c.fld_weight as weight,c.fld_score as scoree FROM itc_exp_destination_master as a
                                                                                            LEFT JOIN itc_exp_master AS b ON b.fld_id=a.fld_exp_id
                                                                                            LEFT JOIN itc_exp_rubric_master AS c ON c.fld_dest_id=a.fld_id
                                                                                            WHERE c.fld_rubric_id='".$rubid."' AND c.fld_exp_id='".$expeditionid."' AND c.fld_dest_id='".$did[$i]."' AND a.fld_flag='1' AND a.fld_delstatus='0' AND c.fld_delstatus='0' ");//AND c.fld_created_by='".$uid1."'
                                            */
                                            }
                                            else
                                            {
                                                $qryviewexp_rubric=$ObjDB->QueryObject("SELECT c.fld_id as rubricid, a.fld_id as destid,a.fld_dest_name as destname, c.fld_category as category,c.fld_four as four,c.fld_three as three, 
                                            c.fld_two as two,c.fld_one as one, c.fld_zer as zer ,c.fld_weight as weight,c.fld_score as scoree FROM itc_exp_rubric_dest_master as a 
                                            LEFT JOIN itc_exp_rubric_master AS c ON c.fld_destination_id=a.fld_id  
                                            WHERE c.fld_rubric_id='".$rubid."' AND c.fld_exp_id='".$expeditionid."' AND c.fld_destination_id='".$did[$i]."'  AND a.fld_delstatus='0' AND c.fld_delstatus='0' ");//AND c.fld_created_by='".$uid."'



                                                /*
                                               $qryviewexp_rubric=$ObjDB->QueryObject("SELECT c.fld_id as rubricid, a.fld_id as destid,a.fld_dest_name as destname, c.fld_category as category,c.fld_four as four,c.fld_three as three,
                                                                                        c.fld_two as two,c.fld_one as one, c.fld_zer as zer ,c.fld_weight as weight,c.fld_score as scoree FROM itc_mis_destination_master as a
                                                                                            LEFT JOIN itc_mission_master AS b ON b.fld_id=a.fld_mis_id
                                                                                            LEFT JOIN itc_mis_rubric_master AS c ON c.fld_dest_id=a.fld_id
                                                                                            WHERE c.fld_rubric_id='".$rubid."' AND c.fld_mis_id='".$expeditionid."' AND c.fld_dest_id='".$did[$i]."' AND a.fld_flag='1' AND a.fld_delstatus='0' AND c.fld_delstatus='0' ");//AND c.fld_created_by='".$uid1."'
                                                                                            */
                                            }


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

                                                if($type=='18')
                                                {


                                                    $rubricrptid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_mis_rubric_rpt WHERE fld_mis_id='".$expeditionid."'   
                                                                                                                     AND fld_rubric_nameid ='".$rubid."' AND fld_class_id='".$classid."'  
                                                                                                                     AND fld_schedule_id='".$schid."' AND fld_delstatus='0' ");

                                                    $studentscore = $ObjDB->SelectSingleValue("SELECT fld_score FROM itc_mis_rubric_rpt_statement WHERE fld_mis_id='".$expeditionid."'  
                                                                                                                    AND fld_dest_id='".$did[$i]."' AND fld_rubric_id ='".$rubricid."' AND fld_student_id='".$studentid."'  
                                                                                                                    AND fld_delstatus='0' AND fld_rubric_rpt_id='".$rubricrptid."'");

                                                    $highlightcell = $ObjDB->SelectSingleValue("SELECT fld_hightlight_cell FROM itc_mis_rubric_rpt_statement WHERE fld_mis_id='".$expeditionid."'  
                                                                                                                    AND fld_dest_id='".$did[$i]."' AND fld_rubric_id ='".$rubricid."' AND fld_student_id='".$studentid."'  
                                                                                                                    AND fld_delstatus='0' AND fld_rubric_rpt_id='".$rubricrptid."'");

                                                    $studentcomment = $ObjDB->SelectSingleValue("SELECT fld_comment FROM itc_mis_rubric_rpt_statement WHERE fld_mis_id='".$expeditionid."'  
                                                                                                                    AND fld_dest_id='".$did[$i]."' AND fld_rubric_id ='".$rubricid."' AND fld_student_id='".$studentid."'  
                                                                                                                    AND fld_delstatus='0' AND fld_rubric_rpt_id='".$rubricrptid."'");

                                                }
                                                else
                                                {

                                                    $rubricrptid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_exp_rubric_rpt WHERE fld_exp_id='".$expeditionid."'   
                                                                                                                     AND fld_rubric_nameid ='".$rubid."' AND fld_class_id='".$classid."' AND fld_schedule_id='".$schid."' 
                                                                                                                     AND fld_delstatus='0' ");

                                                    $studentscore = $ObjDB->SelectSingleValue("SELECT fld_score FROM itc_exp_rubric_rpt_statement WHERE fld_exp_id='".$expeditionid."' AND fld_dest_id='".$did[$i]."'  
                                                                                                                AND fld_rubric_id ='".$rubricid."' AND fld_student_id='".$studentid."'  
                                                                                                                AND fld_delstatus='0' AND fld_rubric_rpt_id='".$rubricrptid."'");

                                                    $highlightcell = $ObjDB->SelectSingleValue("SELECT fld_hightlight_cell FROM itc_exp_rubric_rpt_statement WHERE fld_exp_id='".$expeditionid."' AND fld_dest_id='".$did[$i]."'  
                                                                                                                AND fld_rubric_id ='".$rubricid."' AND fld_student_id='".$studentid."'  
                                                                                                                AND fld_delstatus='0' AND fld_rubric_rpt_id='".$rubricrptid."'");

                                                    $studentcomment = $ObjDB->SelectSingleValue("SELECT fld_comment FROM itc_exp_rubric_rpt_statement WHERE fld_exp_id='".$expeditionid."' AND fld_dest_id='".$did[$i]."'  
                                                                                                                AND fld_rubric_id ='".$rubricid."' AND fld_student_id='".$studentid."'  
                                                                                                                AND fld_delstatus='0' AND fld_rubric_rpt_id='".$rubricrptid."'");
                                                }
                                                if($studentscore=='NULL')
                                                {
                                                    $studentscore='';
                                                }
                                                if($studentcomment=='NULL')
                                                {
                                                    $studentcomment='';
                                                }
                                                ?>
                                                <script>
                                                    fn_highlight('<?php echo $highlightcell; ?>','<?php echo $rubricid; ?>');
                                                </script>

                                                <tr class="Btn destrubrow" id="exp-rubric-<?php echo $rubricid; ?>" >
                                                    <td class="grcategory"  width="15%" id="rubrictxt-<?php echo $rubricid; ?>" ><b class="cattitle"><?php echo $category ;?></b><br><br>
                                                        <a class="standardsbtn"  onclick="fn_viewstandards('<?php echo $scheduletype; ?>', '<?php echo $rubricid; ?>', this);">View Standards</a>
                                                    </td>
                                                    <td  class="centerText desctd"  id="rubrictxt-<?php echo $rubricid; ?>-4" onclick="fn_highlight('4','<?php echo $rubricid; ?>');fn_showdeststmt('4','<?php echo $weight ;?>','<?php echo $rubricid; ?>','<?php echo $destid; ?>','<?php echo $rubid; ?>','<?php echo $type; ?>');this.disabled='disabled';"  ><?php echo $four ;?></td>
                                                    <td  class="centerText desctd"  id="rubrictxt-<?php echo $rubricid; ?>-3" onclick="fn_highlight('3','<?php echo $rubricid; ?>');fn_showdeststmt('3','<?php echo $weight ;?>','<?php echo $rubricid; ?>','<?php echo $destid; ?>','<?php echo $rubid; ?>','<?php echo $type; ?>');this.disabled='disabled';"  ><?php echo $three ;?></td>
                                                    <td  class="centerText desctd"  id="rubrictxt-<?php echo $rubricid; ?>-2" onclick="fn_highlight('2','<?php echo $rubricid; ?>');fn_showdeststmt('2','<?php echo $weight ;?>','<?php echo $rubricid; ?>','<?php echo $destid; ?>','<?php echo $rubid; ?>','<?php echo $type; ?>');this.disabled='disabled';"  ><?php echo $two ;?></td>
                                                    <td  class="centerText desctd"  id="rubrictxt-<?php echo $rubricid; ?>-1" onclick="fn_highlight('1','<?php echo $rubricid; ?>');fn_showdeststmt('1','<?php echo $weight ;?>','<?php echo $rubricid; ?>','<?php echo $destid; ?>','<?php echo $rubid; ?>','<?php echo $type; ?>');this.disabled='disabled';"  ><?php echo $one ;?></td>
                                                    <td  class="centerText desctd"  id="rubrictxt-<?php echo $rubricid; ?>-0" onclick="fn_highlight('0','<?php echo $rubricid; ?>');fn_showdeststmt('0','<?php echo $weight ;?>','<?php echo $rubricid; ?>','<?php echo $destid; ?>','<?php echo $rubid; ?>','<?php echo $type; ?>');this.disabled='disabled';"  ><?php echo $zer ;?></td>
                                                    <td class="scoretd" id="rubrictxt-<?php echo $rubricid; ?>" ><?php echo "X".$weight ;?></td>
                                                    <td class="scoretd" id="rubrictxt-<?php echo $rubricid; ?>" >

                                                        <input type="hidden" name="expid" id="expid" value="<?php echo $expeditionid; ?>">
                                                        <input type="hidden" name="studentid" id="studentid" value="<?php echo $studentid; ?>">
                                                        <input type="hidden" name="classid" id="classid" value="<?php echo $classid; ?>">
                                                        <input type="hidden" name="scheduleid" id="scheduleid" value="<?php echo $schid; ?>">
                                                        <input type="hidden" name="rubrictxtoldval_<?php echo $rubricid; ?>" id="rubrictxtoldval_<?php echo $rubricid; ?>" value="<?php echo $studentscore ;?>">
                                                        <input  type='text' id="txtscore-<?php echo $rubricid; ?>" readonly="" name="txtscore" maxlength="3" min="0" max="<?php echo $scoree ;?>" value="<?php echo $studentscore ;?>" onkeypress="return isNumber(event)"  style="min-width:12px; max-width:16px; text-align:right; border: none; background: transparent;  padding-right:4px; font-size:13px; font-size:14px;   margin-top: -1px; font-weight: bold; color:#369;"><?php echo "/ ".$scoree ;?>
                                                        <input type="hidden" id="ids_<?php echo $rubricid."~".$destid."~".$weight."~".$type; ?>" name="ids" value="">
                                                    </td>

                                                    <!-- Code by barney related to #23153-->
                                                    <td width="15%" id="rubrictxt-<?php echo $rubricid; ?>" style="padding: 0; border: 1px solid #DDD; border-right: none; margin: 0; line-height: 0; background: #fff; vertical-align: top !important;">
                                                        <textarea class="commentbox" spellcheck="true" maxlength="140" style="font-size: 11.5px;  line-height: 1.4em;       height: inherit;  min-height: 140px;    width: calc(100% - 14px); color:#333; padding:10px 7px;" id="txtcomment-<?php echo $rubricid; ?>" name="txtcomment" placeholder="Enter New Grading Rubric Comment"><?php echo $studentcomment; ?></textarea>
                                                    </td>
                                                </tr>
                                            <?php

                                            if($type=='18')
                                            {
                                                $qryviewexp_rubricstuscore=$ObjDB->QueryObject("SELECT fld_score FROM itc_mis_rubric_rpt_statement WHERE fld_mis_id='".$expeditionid."' AND fld_dest_id='".$did[$i]."' AND fld_rubric_id ='".$rubricid."' AND fld_student_id='".$studentid."' AND fld_delstatus='0'  AND fld_rubric_rpt_id='".$rubricrptid."' ");

                                            }
                                            else
                                            {
                                                $qryviewexp_rubricstuscore=$ObjDB->QueryObject("SELECT fld_score FROM itc_exp_rubric_rpt_statement WHERE fld_exp_id='".$expeditionid."' AND fld_dest_id='".$did[$i]."' AND fld_rubric_id ='".$rubricid."' AND fld_student_id='".$studentid."' AND fld_delstatus='0'  AND fld_rubric_rpt_id='".$rubricrptid."' ");
                                            }
                                            if($qryviewexp_rubricstuscore->num_rows > 0)
                                            {
                                                $cnt=1;
                                                while($row=$qryviewexp_rubricstuscore->fetch_assoc())
                                                {
                                                    extract($row);
                                                    $stuscoreval[]=$fld_score;
                                                }
                                            }
                                            }
                                            }
                                            else{           ?>
                                                <tr id="exp-rubric-0">
                                                    <td colspan="8" align="center">  </td>
                                                </tr>
                                                <?php
                                            }   ?>
                                            </tbody>
                                            <?php
                                        }

                                        /****student total score*/
                                        $totalscore1==0;
                                        //print_r($scoreval);
                                        for($w=0;$w<sizeof($stuscoreval);$w++){
                                            $totalscore1=$stuscoreval[$w]+$totalscore1;
                                        }
                                        ?>
                                        <input type="hidden" name="result" id="result" value="<?php echo $totalscore1; ?>">
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
           
            </form> 
             <div class='row rowspacer' id="viewreportdiv">
    
</div>
        </div>
    </div>
        

        
 </div>


    <script type='text/javascript'>

        var studentscore = $("#result").val();
        console.log(studentscore);
        $("#studentscore").text(studentscore);
        $("#totalscore").val(studentscore);

        function fn_digitalrubricstudent(rubid,clsid,stuid,expid,schduleid,stype)
        {
            $("#reports-pdfviewer").remove();

            if(stuid=='')
            {
                showloadingalert("please select any student.");
                setTimeout('closeloadingalert()',2000);
                return false;
            }

            var schid=schduleid + '_' + stype;
            var val = expid+"~"+clsid+"~"+rubid+"~"+stuid+"~"+schid;

            setTimeout('removesections("#reports-digitalrubric");',500);
            oper="digitalrubricreport";
            filename='digitalrubricreport' + new Date().getTime();
            console.log('bbb("reports-pdfviewer","reports/reports-pdfviewer.php","id='+val+'&oper='+oper+'&filename='+filename+'");');


            ajaxloadingalert('Loading, please wait.');
            setTimeout('showpageswithpostmethod("reports-pdfviewer","reports/reports-pdfviewer.php","id='+val+'&oper='+oper+'&filename='+filename+'");',500);
        }
//        var schid2=<?php //echo $schedid; ?>// + '_' + <?php //echo $scheduletype; ?>//;
//        var dataparam = "oper=showrubricstmt&expid=<?php //echo $expeditionid; ?>//&list10=<?php //echo $studentid; ?>//&rubid=<?php //echo $rubid; ?>//&type=<?php //echo $scheduletype; ?>//&schid="+schid2;
//        console.log(dataparam);
//        $.ajax({
//            type: 'post',
//            url: 'library/rubric/library-rubric-gradestudentrubricajax.php',
//            data: dataparam,
//            beforeSend: function(){
//            },
//            success:function(data) {
//                $('#rubricmain').html(data);//Used to load the student details in the dropdown
//                $("#wholeclassbuttons").html('').hide();
//                $('#rubricmain').show();
//                $('#viewreportdiv').show();
//            }
//        });

        function fn_saverubric(id,expid)
        {
            var ids = [];
            var score=[];
            var comments=[];

            if($("#rubricforms").validate().form()) //Validates the Rubric Form
            {
                var list10 = ["<?php echo $studentid; ?>"];
                if(list10!='')
                {
                    if(id!='undefined' && id!=0 && id!=''){ //Works in Editing module
                        actionmsg = "Saving";
                        alertmsg = "Rubric has been Saved Successfully";
                    }
                    else { //Works in Creating a New Module
                        actionmsg = "Saving";
                        alertmsg = "Rubric has been Saved Successfully";
                    }
                }
                else
                {
                    $.Zebra_Dialog("Please select the student for Grade Student", { 'type': 'information', 'buttons':  false, 'auto_close': 2000  });
                    return false;
                }

                $('input[id^=rubrictxtoldval_]').each(function()
                {
                    score.push($(this).val());
                });

                // Code by barney related to #23153
                $('.commentbox').each(function() {
                    comments.push($(this).val());
                });

                $("input[id^=ids_]").each(function()
                {
                    ids.push($(this).attr('id').replace('ids_',''));
                });
                var schid=$("#rubschedid").val();
                var classid=$("#classid").val();

                // Code by barney related to #23153
                console.log(comments);
                var dataparam = "oper=saverubricval&list10="+list10+"&expid="+expid+"&rubid="+id+"&ids="+ids+"&classid="+classid+"&schid="+schid+"&score="+score+"&comments="+comments;
                console.log(dataparam);
                var datas = "id=" + '0,'+classid+',1';

                $.ajax({
                    url: 'library/rubric/library-rubric-gradestudentrubricajax.php',
                    data: dataparam,
                    type: "POST",
                    beforeSend: function(){
                        showloadingalert("Saving, please wait...");
                    },
                    success: function (data)
                    {
                        if(data=="success") //Works if the data saved in db
                        {
                            console.log(datas);
                            setTimeout('closeloadingalert()',500);
                            $("#reports-gradebook").remove();
                            $("#reports-gradebook-showinnertable").remove();
                            $("#reports-gradebook-edit").remove();
                            $("#reports-gradebook-rubricedit").remove();
                            showpageswithpostmethod('class-newclass', 'reports/gradebook/reports-gradebook.php', datas);
                            }else{
                            setTimeout('closeloadingalert()',500);
                            console.log(datas);
                            $("#reports-gradebook").remove();
                            $("#reports-gradebook-showinnertable").remove();
                            $("#reports-gradebook-edit").remove();
                            $("#reports-gradebook-rubricedit").remove();
                            showpageswithpostmethod('class-newclass', 'reports/gradebook/reports-gradebook.php', datas);
                        }
                    },
                    error: function (data) {
                        alert(data);

                    }
                });
            }
        }


        $('.rubrictable').slimscroll({
            height:'auto',
            size: '5px',
            railVisible: true,
            allowPageScroll: true,
            railColor: '#F4F4F4',
            opacity: 9,
            color: '#88ABC2'
        });


        function fn_viewstandards(stype, rubid, standardsbtn){
            var rubcategory = $(standardsbtn).parent().find(".cattitle").text() + " Academic Standards";
            var val = 'schedtype=' + stype + "&rubid=" + rubid;
            console.log(val);

            $.ajax({
                type: 'POST',
                url: 'reports/rubric/reports-rubric-standards.php',
                data: val,
                success: function (data) {
                    $.Zebra_Dialog(data,
                        {
                            'type':     'information',
                            'buttons':  [
                                {caption: 'Close', callback: function() {
                                    console.log("a");
                                }}
                            ],
                            'title': rubcategory,
                            width: 560
                        });
                }
            });
        }
    </script>
</section>
<?php 

@include("footer.php");