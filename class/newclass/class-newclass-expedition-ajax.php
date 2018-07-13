<?php 
	@include("sessioncheck.php");
	
	$date = date("Y-m-d H:i:s");
	$oper = isset($method['oper']) ? $method['oper'] : '';
        
// Exp Sch Inline Ass starts - Karthi       
if($oper=="showschinlineass" and $oper != " ")
{
    $expids= isset($method['expids']) ? $method['expids'] : '0';
    $scheduleid= isset($method['scheduleid']) ? $method['scheduleid'] : '0';
    $schlicenseid = isset($method['schlicenseid']) ? $method['schlicenseid'] : '';

    if($expids!=''){


     ?>
     <div class="row rowspacer"> Select Assessment new

          <style>
          h2.acc_trigger {
              padding: 0;
              margin: 0 0 5px 0;
              width: 100%;
              font-size: 20px;
              font-weight: normal;
              float: left;
              margin-bottom:0;
          }
          h2.acc_trigger a {
              text-decoration: none;
              display: block;
              padding: 0 0 0 15px;
          }
          </style>
          <script type="text/javascript" language="javascript">
              jQuery(document).ready(function ($) {
              //Set default open/close settings
                var divs=$('.accordion>div').hide(); //Hide/close all containers	
                $(".accordion>div:first").show();
                $(".accordion>h2>a>input:first").addClass('removeButton').removeClass('addButton');
                $(".accordion>h2>a>input:first").val('-');
                   var h2s = $(".accordion>h2").click(function () {
                if($(this).children().children('input').hasClass('addButton'))
                {
                    $(".accordion>h2>a>input").addClass('addButton').removeClass('removeButton');
                    $(".accordion>h2>a>input").val('+');
                    $(this).children().children('input').addClass('removeButton').removeClass('addButton');
                    $(this).children().children('input').val('-');
                }
                else
                {	
                    $(".accordion>h2>a>input").addClass('addButton').removeClass('removeButton');
                    $(".accordion>h2>a>input").val('+');
                    $(this).children().children('input').addClass('addButton').removeClass('removeButton');
                    $(this).children().children('input').val('+');	
                }
                    h2s.not(this).removeClass('active')
                    $(this).toggleClass('active')
                    divs.not($(this).next()).slideUp()
                    //var spans=$('.accordion>span').hide(); 
                    //spans.not($(this).next()).slideUp()
                    $(this).next().slideToggle()
                    return false; //Prevent the browser jump to the link anchor

                  });
              });
          </script>

          <div class="accordion">
          <?php
                $distadminid=$ObjDB->SelectSingleValue("SELECT fld_id FROM itc_user_master WHERE fld_school_id = '0'  AND fld_district_id='".$sendistid."' 
                                                            AND fld_delstatus = '0' AND fld_user_id='0' AND fld_profile_id = '6'");
                    
                $schladminid=$ObjDB->SelectSingleValue("SELECT fld_id FROM itc_user_master WHERE fld_school_id = '$senshlid' 
                                                            AND fld_delstatus = '0' AND fld_user_id='0' AND fld_profile_id = '7'");
                  $qrytexp = $ObjDB->QueryObject("SELECT fld_exp_name as expname, fld_id as expid FROM itc_exp_master WHERE fld_id IN (".$expids.") and 
                                                                      fld_delstatus='0' order by expname asc");

                  if ($qrytexp->num_rows > 0) {
                      $a = 0;
                      $x=0;
                      $y=0;
                      $z=0;
                      while ($rowtexp = $qrytexp->fetch_assoc()) {

                          extract($rowtexp);
                          ?>
              <h2 class="acc_trigger"><a href="#"><input type="button" class="addButton" value="+" style="margin-right: 10px;"><?php echo $expname;?></a></h2>
                          <?php

                              if ($qrytexp->num_rows > 0) {
                                  ?>
                                  <div class="acc_container">
                                  <?php
                                  // Expedition Test starts
                                      $checkexpcount1 = $ObjDB->QueryObject("select fld_id
                                                                          from itc_test_master where fld_destid = '0' and fld_taskid=0 and fld_resid=0
                                                                            and (fld_prepostid = '2' or fld_prepostid = '1') and fld_ass_type='1' and  fld_created_by IN('".$uid."','".$distadminid."','".$schladminid."') 
                                                                            and fld_expt='".$expid."' and fld_delstatus = '0' 
                                                                                UNION ALL
                                                                            SELECT a.fld_id 
                                                                                FROM itc_test_master AS a
                                                                            LEFT JOIN
                                                                            `itc_license_assessment_mapping` AS b ON a.fld_id = b.fld_assessment_id
                                                                            LEFT JOIN
                                                                            `itc_license_track` AS c ON b.fld_license_id = c.fld_license_id
                                                                            WHERE c.fld_school_id='".$senshlid."' AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."'
                                                                                    AND a.fld_delstatus = '0' AND c.fld_delstatus = '0' AND c.fld_user_id = '".$indid."' and b.fld_access = '1'
                                                                                    and (a.fld_prepostid = '2' or a.fld_prepostid = '1') and a.fld_ass_type='1' and a.fld_destid = '0' and b.fld_license_id='".$schlicenseid."'
                                                                                    and a.fld_taskid=0 and a.fld_resid=0 and fld_profile_id='2' and a.fld_expt='".$expid."' and a.fld_delstatus = '0'");

                                          ?>
                                      <div class='row rowspacer' style="margin-top:0px;">
                                      <div class='six columns' class="block" name="" id="" style="text-indent: 20px; margin-top:0px;" > <?php echo $expname;?></div>

                                          <?php 
                                            $checkexpcount = $checkexpcount1->num_rows>0;
                                            if($checkexpcount>0){
                                                $checkexptestprecount1 = $ObjDB->QueryObject("select fld_id
                                                                          from itc_test_master where fld_destid = '0' and fld_taskid=0 and fld_resid=0
                                                                            and  fld_prepostid = '1' and fld_ass_type='1' and  fld_created_by IN('".$uid."','".$distadminid."','".$schladminid."') 
                                                                            and fld_expt='".$expid."' and fld_delstatus = '0' 
                                                                                UNION ALL
                                                                            SELECT a.fld_id 
                                                                                FROM itc_test_master AS a
                                                                            LEFT JOIN
                                                                            `itc_license_assessment_mapping` AS b ON a.fld_id = b.fld_assessment_id
                                                                            LEFT JOIN
                                                                            `itc_license_track` AS c ON b.fld_license_id = c.fld_license_id
                                                                            WHERE c.fld_school_id='".$senshlid."' AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."'
                                                                                    AND a.fld_delstatus = '0' AND c.fld_delstatus = '0' AND c.fld_user_id = '".$indid."' and b.fld_access = '1'
                                                                                    and a.fld_prepostid = '1' and a.fld_ass_type='1' and a.fld_destid = '0' and b.fld_license_id='".$schlicenseid."'
                                                                                    and a.fld_taskid=0 and a.fld_resid=0 and fld_profile_id='2' and a.fld_expt='".$expid."' and a.fld_delstatus = '0'");
                                                $checkexptestprecount = $checkexptestprecount1->num_rows>0;
                                                if($checkexptestprecount>0){
                                                  
                                                      $expretestname= "Select Pretest";
                                                      $expretestid=0;
                                                 

                                              ?>
                                              <div class='three columns'> 
                                                  <?php
                                                      if($scheduleid!=0){                                                                
                                                          $exppretestdetail= $ObjDB->QueryObject("select a.fld_pretest as expretestid,b.fld_test_name as expretestname
                                                                                                      from itc_exp_ass as a left join itc_test_master as b on a.fld_pretest=b.fld_id where fld_tdestid='0' and fld_ttaskid='0' and fld_tresid=0 
                                                                                                      and fld_texpid ='".$expid."' and fld_sch_id= '".$scheduleid."' and fld_schtype_id='19' and b.fld_delstatus='0'"); 
                                                          $exppretestcount = $exppretestdetail->num_rows>0; 
                                                          if($exppretestcount !=0)
                                                          {
                                                             $rowexppretest=$exppretestdetail->fetch_assoc();
                                                             extract($rowexppretest);                                                                     
                                                          }
                                                          if($expretestid==0 or $expretestid==''){
                                                              $expretestname="None";
                                                              $expretestid=0;
                                                          }
                                                          $exppreplaycnt = $ObjDB->SelectSingleValueInt("select count(fld_id)
                                                                                                  from itc_exp_testplay_track 
                                                                                                  where fld_exp_id='".$expid."' and fld_exp_test_id='".$expretestid."' and fld_schedule_id= '".$scheduleid."' and fld_schedule_type='19'");
                                                      }
                                                  ?>
                                                  <dl class='field row <?php if($exppreplaycnt!=0){echo "dim";}?>'>   
                                                      <dt class='dropdown'>   
                                                          <div class="selectbox" style="width:200px;font-size: 14px; height: 20px; line-height: 20px;">
                                                              <input type="hidden" name="exppre_<?php echo $expid."_0_0_0";?>" id="" value="<?php echo $expretestid;?>"/>
                                                              <a class="selectbox-toggle" role="button" data-toggle="selectbox" style="font-size: 14px; height: 20px; line-height: 20px;">
                                                                  <span class="selectbox-option input-medium" data-option="<?php echo $expretestid;?>" id="clearsubject"><?php  echo $expretestname; ?></span>
                                                                  <b class="caret1"></b>
                                                              </a>                       
                                                              <div class="selectbox-options" style="width:210px;" >
                                                                  <input type="text" class="selectbox-filter" placeholder="Search Pretest">
                                                                  <ul role="options">
                                                                          <li><a tabindex="-1" href="#" data-option="0"><?php echo "None";?></a></li>
                                                                          <?php

                                                                              $qrypre1= $ObjDB->QueryObject("SELECT a.fld_id AS expretestid,a.fld_test_name AS expretestname
                                                                                                                FROM itc_test_master AS a
                                                                                                                LEFT JOIN
                                                                                                                `itc_license_assessment_mapping` AS b ON a.fld_id = b.fld_assessment_id
                                                                                                                LEFT JOIN
                                                                                                                `itc_license_track` AS c ON b.fld_license_id = c.fld_license_id
                                                                                                                WHERE c.fld_school_id='".$senshlid."' AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."'
                                                                                                                AND a.fld_delstatus = '0' AND c.fld_delstatus = '0' AND c.fld_user_id = '".$indid."' and b.fld_access = '1'
                                                                                                                and a.fld_prepostid = '1' and a.fld_ass_type='1' and a.fld_destid = '0' and b.fld_license_id='".$schlicenseid."'  
                                                                                                                and a.fld_taskid=0 and a.fld_resid=0 and fld_profile_id='2' and a.fld_expt='".$expid."' and a.fld_delstatus = '0' 
                                                                                                                UNION ALL
                                                                                                                select fld_id AS expretestid,fld_test_name AS expretestname
                                                                                                      from itc_test_master where fld_destid = '0' and fld_taskid=0 and fld_resid=0
                                                                                                                and  fld_prepostid = '1' and fld_ass_type='1' and  fld_created_by IN('".$uid."','".$distadminid."','".$schladminid."') 
                                                                                                                and fld_expt='".$expid."' and fld_delstatus = '0' ORDER BY expretestname");

                                                                              if($qrypre1->num_rows>0)
                                                                              {
                                                                                  while($rowpre1=$qrypre1->fetch_assoc())
                                                                                  {
                                                                                      extract($rowpre1);
                                                                          ?>
                                                                               <li><a tabindex="-1" href="#" data-option="<?php echo $expretestid;?>"><?php echo $expretestname;?></a></li>

                                                                          <?php
                                                                                  }
                                                                              }
                                                                          ?>
                                                                  </ul>
                                                              </div>
                                                          </div>
                                                      </dt>                                       
                                                  </dl>  
                                              </div>
                                              <?php } else{ ?> <div class='three columns'> <dl class='field row'><input type="hidden" name="exppre_<?php echo $expid."_0_0_0";?>" id="" value="0"/>  </dl></div> <?php } ?>



                                              <?php 
                                                $checkexptestpostcount1 = $ObjDB->QueryObject("select fld_id
                                                                          from itc_test_master where fld_destid = '0' and fld_taskid=0 and fld_resid=0
                                                                            and  fld_prepostid = '2' and fld_ass_type='1' and  fld_created_by IN('".$uid."','".$distadminid."','".$schladminid."') 
                                                                            and fld_expt='".$expid."' and fld_delstatus = '0' 
                                                                                UNION ALL
                                                                            SELECT a.fld_id 
                                                                                FROM itc_test_master AS a
                                                                            LEFT JOIN
                                                                            `itc_license_assessment_mapping` AS b ON a.fld_id = b.fld_assessment_id
                                                                            LEFT JOIN
                                                                            `itc_license_track` AS c ON b.fld_license_id = c.fld_license_id
                                                                            WHERE c.fld_school_id='".$senshlid."' AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."'
                                                                                    AND a.fld_delstatus = '0' AND c.fld_delstatus = '0' AND c.fld_user_id = '".$indid."' and b.fld_access = '1'
                                                                                    and a.fld_prepostid = '2' and a.fld_ass_type='1' and a.fld_destid = '0' and b.fld_license_id='".$schlicenseid."'
                                                                                    and a.fld_taskid=0 and a.fld_resid=0 and fld_profile_id='2' and a.fld_expt='".$expid."' and a.fld_delstatus = '0'");
                                                $checkexptestpostcount = $checkexptestpostcount1->num_rows>0;
                                                if($checkexptestpostcount>0){
                                                 
                                                      $expposttestname="Select Posttest";
                                                      $expposttestid=0;
                                                  
                                              ?>
                                              <div class='three columns'> 
                                                  <?php
                                                      if($scheduleid!=0){                                                                
                                                          $expposttestdetail= $ObjDB->QueryObject("select a.fld_posttest as expposttestid,b.fld_test_name as expposttestname
                                                                                                      from itc_exp_ass as a left join itc_test_master as b on a.fld_posttest=b.fld_id where fld_tdestid='0' and fld_ttaskid='0' and fld_tresid=0 
                                                                                                      and fld_texpid ='".$expid."' and fld_sch_id= '".$scheduleid."' and fld_schtype_id='19' and b.fld_delstatus='0'"); 
                                                          $expposttestcount = $expposttestdetail->num_rows>0; 
                                                          if($expposttestcount !=0)
                                                          {
                                                             $rowexpposttest=$expposttestdetail->fetch_assoc();
                                                             extract($rowexpposttest);                                                                     
                                                          }
                                                          if($expposttestid==0 or $expposttestid==''){
                                                              $expposttestname="None";
                                                              $expposttestid=0;
                                                          }
                                                          $exppostplaycnt = $ObjDB->SelectSingleValueInt("select count(fld_id)
                                                                                                  from itc_exp_testplay_track 
                                                                                                  where fld_exp_id='".$expid."' and fld_exp_test_id='".$expposttestid."' and fld_schedule_id= '".$scheduleid."' and fld_schedule_type='19'");
                                                      }                                                         
                                                  ?>
                                                  <dl class='field row <?php if($exppostplaycnt!=0){echo "dim";}?>'>   
                                                      <dt class='dropdown'>   
                                                          <div class="selectbox" style="width:200px;font-size: 14px; height: 20px; line-height: 20px;">
                                                              <input type="hidden" name="" id="exppost_<?php echo $a;?>" value="<?php echo $expposttestid;?>"/>
                                                              <a class="selectbox-toggle" role="button" data-toggle="selectbox" style="font-size: 14px; height: 20px; line-height: 20px;">
                                                                  <span class="selectbox-option input-medium" data-option="<?php echo $expposttestid;?>" id="clearsubject"><?php  echo $expposttestname;?></span>
                                                                  <b class="caret1"></b>
                                                              </a>                       
                                                              <div class="selectbox-options" style="width:210px;">
                                                                  <input type="text" class="selectbox-filter" placeholder="Search Posttest">
                                                                  <ul role="options">
                                                                      <li><a tabindex="-1" href="#" data-option="0"><?php echo "None";?></a></li>
                                                                          <?php

                                                                              $qrpost1= $ObjDB->QueryObject("SELECT a.fld_id AS expposttestid,a.fld_test_name AS expposttestname
                                                                                                                FROM itc_test_master AS a
                                                                                                                LEFT JOIN
                                                                                                                `itc_license_assessment_mapping` AS b ON a.fld_id = b.fld_assessment_id
                                                                                                                LEFT JOIN
                                                                                                                `itc_license_track` AS c ON b.fld_license_id = c.fld_license_id
                                                                                                                WHERE c.fld_school_id='".$senshlid."' AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."'
                                                                                                                AND a.fld_delstatus = '0' AND c.fld_delstatus = '0' AND c.fld_user_id = '".$indid."' and b.fld_access = '1'
                                                                                                                and a.fld_prepostid = '2' and a.fld_ass_type='1' and a.fld_destid = '0' and b.fld_license_id='".$schlicenseid."' 
                                                                                                                and a.fld_taskid=0 and a.fld_resid=0 and fld_profile_id='2' and a.fld_expt='".$expid."' and a.fld_delstatus = '0' 
                                                                                                                UNION ALL
                                                                                                                select fld_id AS expposttestid,fld_test_name AS expposttestname
                                                                              from itc_test_master where fld_destid = '0' and fld_taskid=0 and fld_resid=0
                                                                                                                and  fld_prepostid = '2' and fld_ass_type='1' and  fld_created_by IN('".$uid."','".$distadminid."','".$schladminid."') 
                                                                                                                and fld_expt='".$expid."' and fld_delstatus = '0' ORDER BY expposttestname");

                                                                              if($qrpost1->num_rows>0)
                                                                              {
                                                                                  while($rowpost1=$qrpost1->fetch_assoc())
                                                                                  {
                                                                                      extract($rowpost1);
                                                                          ?>
                                                                               <li><a tabindex="-1" href="#" data-option="<?php echo $expposttestid;?>"><?php echo $expposttestname;?></a></li>
                                                                          <?php
                                                                                  }
                                                                              }
                                                                          ?>
                                                                  </ul>
                                                              </div>
                                                          </div>
                                                      </dt>                                       
                                                  </dl>
                                              </div>
                                               <?php } else{ ?> <div class='three columns'> <dl class='field row'>  </dl></div> <?php } 
                                          }   
                                          ?> </div><?php
                                  // Expedition Test ends
                                  //Destination Test starts

                                  $qrytdest = $ObjDB->QueryObject("SELECT fld_dest_name as destname, fld_id as destid
                                                                   FROM itc_exp_destination_master WHERE fld_exp_id='".$expid."' AND fld_delstatus='0'");//limit 0,10
                                  if ($qrytdest->num_rows > 0) {
                                          $b=1;
                                          while ($rowtdest = $qrytdest->fetch_assoc()) {

                                              extract($rowtdest);
                                              $checkdestcount1 = $ObjDB->QueryObject("SELECT a.fld_id 
                                                                                    FROM itc_test_master AS a
                                                                                    LEFT JOIN
                                                                                    `itc_license_assessment_mapping` AS b ON a.fld_id = b.fld_assessment_id
                                                                                    LEFT JOIN
                                                                                    `itc_license_track` AS c ON b.fld_license_id = c.fld_license_id
                                                                                    WHERE c.fld_school_id='".$senshlid."' AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."'
                                                                                    AND a.fld_delstatus = '0' AND c.fld_delstatus = '0' AND c.fld_user_id = '".$indid."' and b.fld_access = '1'
                                                                                    and (a.fld_prepostid = '2' or a.fld_prepostid = '1') and a.fld_ass_type='1' and fld_destid != '0' and fld_destid='".$destid."' 
                                                                                    and a.fld_taskid=0 and a.fld_resid=0 and fld_profile_id='2' and a.fld_expt='".$expid."' and b.fld_license_id='".$schlicenseid."' and a.fld_delstatus = '0'
                                                                                    UNION ALL
                                                                                    select fld_id
                                                                                    from itc_test_master where fld_destid !='0' and fld_destid='".$destid."' and fld_taskid=0 and fld_resid=0
                                                                                    and (fld_prepostid = '2' or fld_prepostid = '1') and fld_ass_type='1' and  fld_created_by IN('".$uid."','".$distadminid."','".$schladminid."') 
                                                                                    and fld_expt='".$expid."' and fld_delstatus = '0'");

                                          ?>
                                      <div class='row rowspacer' style="margin-top:0px;">
                                              <div class='six columns' class="block" style="text-indent: 25px; margin-top:0px;" > <?php echo "D"."$b".".".$destname;?></div>

                                          <?php 
                                            $checkdestcount = $checkdestcount1->num_rows>0;
                                            if($checkdestcount>0){
                                              $checkdesttestprecount1 = $ObjDB->QueryObject("SELECT a.fld_id 
                                                                                                FROM itc_test_master AS a
                                                                                                LEFT JOIN
                                                                                                `itc_license_assessment_mapping` AS b ON a.fld_id = b.fld_assessment_id
                                                                                                LEFT JOIN
                                                                                                `itc_license_track` AS c ON b.fld_license_id = c.fld_license_id
                                                                                                WHERE c.fld_school_id='".$senshlid."' AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."'
                                                                                                AND a.fld_delstatus = '0' AND c.fld_delstatus = '0' AND c.fld_user_id = '".$indid."' and b.fld_access = '1'
                                                                                                and a.fld_prepostid = '1' and a.fld_ass_type='1' and fld_destid != '0' and fld_destid='".$destid."' and b.fld_license_id='".$schlicenseid."'
                                                                                                and a.fld_taskid=0 and a.fld_resid=0 and fld_profile_id='2' and a.fld_expt='".$expid."' and a.fld_delstatus = '0'
                                                                                                UNION ALL
                                                                                                select fld_id
                                                                                                from itc_test_master where fld_destid != '0' and fld_destid='".$destid."' and fld_taskid=0 and fld_resid=0
                                                                                                and fld_prepostid = '1' and fld_ass_type='1' and  fld_created_by IN('".$uid."','".$distadminid."','".$schladminid."') 
                                                                                                and fld_expt='".$expid."' and fld_delstatus = '0'");
                                                $checkdesttestprecount = $checkdesttestprecount1->num_rows>0;
                                                if($checkdesttestprecount>0){
                                                  
                                                      $destpretestname ="Select Pretest";
                                                      $destpretestid=0;
                                                  
                                              ?>
                                              <div class='three columns'> 
                                                  <?php
                                                      if($scheduleid!=0){   
                                                          $destpretestdetail= $ObjDB->QueryObject("select a.fld_pretest as destpretestid,b.fld_test_name as destpretestname
                                                                                                      from itc_exp_ass as a left join itc_test_master as b on a.fld_pretest=b.fld_id where fld_tdestid='".$destid."' and fld_ttaskid='0' and fld_tresid=0 
                                                                                                      and fld_texpid ='".$expid."' and fld_sch_id= '".$scheduleid."' and fld_schtype_id='19' and b.fld_delstatus='0'"); 
                                                          $destpretestcount = $destpretestdetail->num_rows>0; 
                                                          if($destpretestcount !=0)
                                                          {
                                                             $rowdetpretest=$destpretestdetail->fetch_assoc();
                                                             extract($rowdetpretest);                                                                     
                                                          }
                                                          if($destpretestid==0 or $destpretestid==''){
                                                              $destpretestname="None";
                                                              $destpretestid=0;
                                                          }
                                                          $destpreplaycnt = $ObjDB->SelectSingleValueInt("select count(fld_id)
                                                                                                  from itc_exp_dest_testplay_track 
                                                                                                  where fld_exp_id='".$expid."' and fld_dest_id='".$destid."' and fld_dest_test_id='".$destpretestid."' and fld_schedule_id= '".$scheduleid."' and fld_schedule_type='19'");
                                                      }                                                         
                                                  ?>
                                                  <dl class='field row <?php if($destpreplaycnt!=0){echo "dim";}?>'>   
                                                      <dt class='dropdown'>   
                                                          <div class="selectbox" style="width:200px;font-size: 14px; height: 20px; line-height: 20px;">
                                                              <input type="hidden" name="destpre_<?php echo $expid."_".$destid."_0_0";?>" id="" value="<?php echo $destpretestid;?>"/>
                                                              <a class="selectbox-toggle" role="button" data-toggle="selectbox" style="font-size: 14px; height: 20px; line-height: 20px;">
                                                                  <span class="selectbox-option input-medium" data-option="<?php echo $destpretestid;?>" id="clearsubject"><?php  echo $destpretestname; ?></span>
                                                                  <b class="caret1"></b>
                                                              </a>                       
                                                              <div class="selectbox-options" style="width:210px;">
                                                                  <input type="text" class="selectbox-filter" placeholder="Search Pretest">
                                                                  <ul role="options">
                                                                      <li><a tabindex="-1" href="#" data-option="0"><?php echo "None";?></a></li>
                                                                          <?php

                                                                              $qrypre2 = $ObjDB->QueryObject("SELECT a.fld_id AS destpretestid, a.fld_test_name AS destpretestname
                                                                                                                FROM itc_test_master AS a
                                                                                                                LEFT JOIN
                                                                                                                `itc_license_assessment_mapping` AS b ON a.fld_id = b.fld_assessment_id
                                                                                                                LEFT JOIN
                                                                                                                `itc_license_track` AS c ON b.fld_license_id = c.fld_license_id
                                                                                                                WHERE c.fld_school_id='".$senshlid."' AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."'
                                                                                                                AND a.fld_delstatus = '0' AND c.fld_delstatus = '0' AND c.fld_user_id = '".$indid."' and b.fld_access = '1'
                                                                                                                and a.fld_prepostid = '1' and a.fld_ass_type='1' and fld_destid != '0' and fld_destid='".$destid."' and b.fld_license_id='".$schlicenseid."'
                                                                                                                and a.fld_taskid=0 and a.fld_resid=0 and fld_profile_id='2' and a.fld_expt='".$expid."' and a.fld_delstatus = '0'
                                                                                                                UNION ALL
                                                                                                                select fld_id AS destpretestid,fld_test_name AS destpretestname
                                                                                                                from itc_test_master where fld_destid != '0' and fld_destid='".$destid."' and fld_taskid=0 and fld_resid=0
                                                                                                                and fld_prepostid = '1' and fld_ass_type='1' and  fld_created_by IN('".$uid."','".$distadminid."','".$schladminid."') 
                                                                                                                and fld_expt='".$expid."' and fld_delstatus = '0' ORDER BY destpretestname");

                                                                              if($qrypre2->num_rows>0)
                                                                              {
                                                                                  while($rowpre2=$qrypre2->fetch_assoc())
                                                                                  {
                                                                                      extract($rowpre2);
                                                                          ?>
                                                                               <li><a tabindex="-1" href="#" data-option="<?php echo $destpretestid;?>"><?php echo $destpretestname;?></a></li>
                                                                          <?php
                                                                                  }
                                                                              }
                                                                          ?>
                                                                  </ul>
                                                              </div>
                                                          </div>
                                                      </dt>                                       
                                                  </dl>  
                                              </div>
                                              <?php } else{ ?> <div class='three columns'><input type="hidden" name="destpre_<?php echo $expid."_".$destid."_0_0";?>" id="" value="0"/> <dl class='field row'>  </dl></div> <?php } ?>



                                              <?php 
                                              $checkdesttestpostcount1 = $ObjDB->QueryObject("SELECT a.fld_id 
                                                                                                FROM itc_test_master AS a
                                                                                                LEFT JOIN
                                                                                                `itc_license_assessment_mapping` AS b ON a.fld_id = b.fld_assessment_id
                                                                                                LEFT JOIN
                                                                                                `itc_license_track` AS c ON b.fld_license_id = c.fld_license_id
                                                                                                WHERE c.fld_school_id='".$senshlid."' AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."'
                                                                                                AND a.fld_delstatus = '0' AND c.fld_delstatus = '0' AND c.fld_user_id = '".$indid."' and b.fld_access = '1'
                                                                                                and a.fld_prepostid = '2' and a.fld_ass_type='1' and fld_destid != '0' and fld_destid='".$destid."' and b.fld_license_id='".$schlicenseid."'
                                                                                                and a.fld_taskid=0 and a.fld_resid=0 and fld_profile_id='2' and a.fld_expt='".$expid."' and a.fld_delstatus = '0'
                                                                                                UNION ALL
                                                                                                select fld_id
                                                                                                from itc_test_master where fld_destid != '0' and fld_destid='".$destid."' and fld_taskid=0 and fld_resid=0
                                                                                                and fld_prepostid = '2' and fld_ass_type='1' and  fld_created_by IN('".$uid."','".$distadminid."','".$schladminid."') 
                                                                                                and fld_expt='".$expid."' and fld_delstatus = '0'");
                                                $checkdesttestpostcount = $checkdesttestpostcount1->num_rows>0;
                                                if($checkdesttestpostcount>0){
                                                  
                                                       $destposttestname="Select Posttest";
                                                       $destposttestid=0;
                                                  
                                              ?>
                                              <div class='three columns'> 
                                                  <?php
                                                      if($scheduleid!=0){                                                                
                                                          $destposttestdetail= $ObjDB->QueryObject("select a.fld_posttest as destposttestid,b.fld_test_name as destposttestname
                                                                                                      from itc_exp_ass as a left join itc_test_master as b on a.fld_posttest=b.fld_id where fld_tdestid='".$destid."' and fld_ttaskid='0' and fld_tresid=0 
                                                                                                      and fld_texpid ='".$expid."' and fld_sch_id= '".$scheduleid."' and fld_schtype_id='19' and b.fld_delstatus='0'"); 
                                                          $destposttestcount = $destposttestdetail->num_rows>0; 
                                                          if($destposttestcount !=0)
                                                          {
                                                             $rowdestposttest=$destposttestdetail->fetch_assoc();
                                                             extract($rowdestposttest);                                                                     
                                                          }
                                                          if($destposttestid==0 or $destposttestid==0){
                                                              $destposttestname="None";
                                                              $destposttestid=0;
                                                          }
                                                          $destpostplaycnt = $ObjDB->SelectSingleValueInt("select count(fld_id)
                                                                                                  from itc_exp_dest_testplay_track 
                                                                                                  where fld_exp_id='".$expid."' and fld_dest_id='".$destid."' and fld_dest_test_id='".$destposttestid."' and fld_schedule_id= '".$scheduleid."' and fld_schedule_type='19'");
                                                      }                                                         
                                                  ?>
                                                  <dl class='field row <?php if($destpostplaycnt!=0){echo "dim";}?>'>   
                                                      <dt class='dropdown'>   
                                                          <div class="selectbox" style="width:200px;font-size: 14px; height: 20px; line-height: 20px;">
                                                              <input type="hidden" name="" id="destpost_<?php echo $x; ?>" value="<?php echo $destposttestid;?>"/>
                                                              <a class="selectbox-toggle" role="button" data-toggle="selectbox" style="font-size: 14px; height: 20px; line-height: 20px;">
                                                                  <span class="selectbox-option input-medium" data-option="<?php echo $destposttestid;?>" id="clearsubject"><?php  echo $destposttestname;?></span>
                                                                  <b class="caret1"></b>
                                                              </a>                       
                                                              <div class="selectbox-options" style="width:210px;">
                                                                  <input type="text" class="selectbox-filter" placeholder="Search Posttest">
                                                                  <ul role="options">
                                                                      <li><a tabindex="-1" href="#" data-option="0"><?php echo "None";?></a></li>
                                                                          <?php

                                                                              $qrpost2 = $ObjDB->QueryObject("SELECT a.fld_id AS destposttestid, a.fld_test_name AS destposttestname
                                                                                                                FROM itc_test_master AS a
                                                                                                                LEFT JOIN
                                                                                                                `itc_license_assessment_mapping` AS b ON a.fld_id = b.fld_assessment_id
                                                                                                                LEFT JOIN
                                                                                                                `itc_license_track` AS c ON b.fld_license_id = c.fld_license_id
                                                                                                                WHERE c.fld_school_id='".$senshlid."' AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."'
                                                                                                                AND a.fld_delstatus = '0' AND c.fld_delstatus = '0' AND c.fld_user_id = '".$indid."' and b.fld_access = '1'
                                                                                                                and a.fld_prepostid = '2' and a.fld_ass_type='1' and fld_destid != '0' and fld_destid='".$destid."' and b.fld_license_id='".$schlicenseid."'
                                                                                                                and a.fld_taskid=0 and a.fld_resid=0 and fld_profile_id='2' and a.fld_expt='".$expid."' and a.fld_delstatus = '0'
                                                                                                                UNION ALL
                                                                                                                select fld_id AS destposttestid,fld_test_name AS destposttestname
                                                                                                                from itc_test_master where fld_destid != '0' and fld_destid='".$destid."' and fld_taskid=0 and fld_resid=0
                                                                                                                and fld_prepostid = '2' and fld_ass_type='1' and  fld_created_by IN('".$uid."','".$distadminid."','".$schladminid."') 
                                                                                                                and fld_expt='".$expid."' and fld_delstatus = '0' ORDER BY destposttestname");

                                                                              if($qrpost2->num_rows>0)
                                                                              {
                                                                                  while($rowpost2=$qrpost2->fetch_assoc()) 
                                                                                  {
                                                                                      extract($rowpost2);
                                                                          ?>
                                                                               <li><a tabindex="-1" href="#" data-option="<?php echo $destposttestid;?>"><?php echo $destposttestname;?></a></li>
                                                                          <?php
                                                                                  }
                                                                              }
                                                                          ?>
                                                                  </ul>
                                                              </div>
                                                          </div>
                                                      </dt>                                       
                                                  </dl>
                                              </div>
                                              <?php } else{ ?> <div class='three columns'> <dl class='field row'>  </dl></div> <?php } 


                                              $x++;
                                          }
                                          ?></div> <?php

                                  //Destination Test Ends 
                                  //Task Test Starts 
                                  $qryttask = $ObjDB->QueryObject("SELECT fld_task_name as taskname, fld_id as taskid
                                                                              FROM itc_exp_task_master WHERE fld_dest_id='".$destid."' AND fld_delstatus='0'");//limit 0,10
                                  if ($qryttask->num_rows > 0) {
                                      $d=1;
                                      while ($rowttask = $qryttask->fetch_assoc()) {
                                      extract($rowttask);
                                          $checktaskcount1 = $ObjDB->QueryObject("SELECT a.fld_id 
                                                                                    FROM itc_test_master AS a
                                                                                    LEFT JOIN
                                                                                    `itc_license_assessment_mapping` AS b ON a.fld_id = b.fld_assessment_id
                                                                                    LEFT JOIN
                                                                                    `itc_license_track` AS c ON b.fld_license_id = c.fld_license_id
                                                                                    WHERE c.fld_school_id='".$senshlid."' AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."'
                                                                                    AND a.fld_delstatus = '0' AND c.fld_delstatus = '0' AND c.fld_user_id = '".$indid."' and b.fld_access = '1' and b.fld_license_id='".$schlicenseid."'
                                                                                    and (a.fld_prepostid = '2' or a.fld_prepostid = '1') and a.fld_ass_type='1' and a.fld_destid != '0' and a.fld_destid='".$destid."' 
                                                                                    and a.fld_taskid !='0' and a.fld_taskid='".$taskid."' and a.fld_resid=0 and fld_profile_id='2' and a.fld_expt='".$expid."' and a.fld_delstatus = '0'
                                                                                    UNION ALL
                                                                                    select fld_id
                                                                                    from itc_test_master where fld_destid != '0' and fld_destid='".$destid."' and fld_taskid !='0' and fld_taskid='".$taskid."' and fld_resid=0
                                                                                    and (fld_prepostid = '2' or fld_prepostid = '1') and fld_ass_type='1' and  fld_created_by IN('".$uid."','".$distadminid."','".$schladminid."') 
                                                                                    and fld_expt='".$expid."' and fld_delstatus = '0'");
                                            $checktaskcount = $checktaskcount1->num_rows>0;

                                          ?>
                                        <div class='row rowspacer' style="margin-top:0px;">
                                              <div class='six columns' class="block" style="text-indent: 30px; margin-top:0px;"  > <?php echo "T"."$d".".".$taskname;?></div>

                                          <?php 
                                          if($checktaskcount>0){
                                                $checktasktestprecount1 = $ObjDB->QueryObject("SELECT a.fld_id 
                                                                                            FROM itc_test_master AS a
                                                                                            LEFT JOIN
                                                                                            `itc_license_assessment_mapping` AS b ON a.fld_id = b.fld_assessment_id
                                                                                            LEFT JOIN
                                                                                            `itc_license_track` AS c ON b.fld_license_id = c.fld_license_id
                                                                                            WHERE c.fld_school_id='".$senshlid."' AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."'
                                                                                            AND a.fld_delstatus = '0' AND c.fld_delstatus = '0' AND c.fld_user_id = '".$indid."' and b.fld_access = '1'
                                                                                            and a.fld_prepostid = '1' and a.fld_ass_type='1' and a.fld_destid != '0' and a.fld_destid='".$destid."' and b.fld_license_id='".$schlicenseid."'
                                                                                            and a.fld_taskid !='0' and a.fld_taskid='".$taskid."' and a.fld_resid=0 and fld_profile_id='2' and a.fld_expt='".$expid."' and a.fld_delstatus = '0'
                                                                                            UNION ALL
                                                                                            select fld_id
                                                                                            from itc_test_master where fld_destid != '0' and fld_destid='".$destid."' and fld_taskid !='0' and fld_taskid='".$taskid."' and fld_resid=0
                                                                                            and fld_prepostid = '1' and fld_ass_type='1' and  fld_created_by IN('".$uid."','".$distadminid."','".$schladminid."') 
                                                                                            and fld_expt='".$expid."' and fld_delstatus = '0'");
                                                $checktasktestprecount = $checktasktestprecount1->num_rows>0; 
                                                  
                                                if($checktasktestprecount>0){
                                                  
                                                       $taskpretestname="Select Pretest";
                                                       $taskpretestid=0;
                                                  
                                              ?>
                                              <div class='three columns'> 
                                                  <?php
                                                      if($scheduleid!=0){                                                                
                                                          $taskpretestdetail= $ObjDB->QueryObject("select a.fld_pretest as taskpretestid,b.fld_test_name as taskpretestname
                                                                                                      from itc_exp_ass as a left join itc_test_master as b on a.fld_pretest=b.fld_id where fld_tdestid='".$destid."' and fld_ttaskid='".$taskid."' and fld_tresid=0 
                                                                                                      and fld_texpid ='".$expid."' and fld_sch_id= '".$scheduleid."' and fld_schtype_id='19' and b.fld_delstatus='0'"); 
                                                          $taskpretestcount = $taskpretestdetail->num_rows>0; 
                                                          if($taskpretestcount !=0)
                                                          {
                                                             $rowtaskpretest=$taskpretestdetail->fetch_assoc();
                                                             extract($rowtaskpretest);                                                                     
                                                          }
                                                          if($taskpretestid==0 or $taskpretestid==''){
                                                              $taskpretestname="None";
                                                              $taskpretestid=0;
                                                          }
                                                          $taskppreplaycnt = $ObjDB->SelectSingleValueInt("select count(fld_id)
                                                                                                  from itc_exp_task_testplay_track 
                                                                                                  where fld_exp_id='".$expid."' and fld_dest_id='".$destid."' and fld_task_id='".$taskid."' and fld_task_test_id='".$taskpretestid."' and fld_schedule_id= '".$scheduleid."' and fld_schedule_type='19'");
                                                      }                                                         
                                                  ?>
                                                  <dl class='field row <?php if($taskppreplaycnt!=0){echo "dim";}?>'>   
                                                      <dt class='dropdown'>   
                                                          <div class="selectbox" style="width:200px;font-size: 14px; height: 20px; line-height: 20px;">
                                                              <input type="hidden" name="taskpre_<?php echo $expid."_".$destid."_".$taskid."_0";?>" id="" value="<?php echo $taskpretestid;?>"/>
                                                              <a class="selectbox-toggle" role="button" data-toggle="selectbox" style="font-size: 14px; height: 20px; line-height: 20px;">
                                                                  <span class="selectbox-option input-medium" data-option="<?php echo $taskpretestid;?>" id="clearsubject"><?php echo $taskpretestname;?></span>
                                                                  <b class="caret1"></b>
                                                              </a>                       
                                                              <div class="selectbox-options" style="width:210px;">
                                                                  <input type="text" class="selectbox-filter" placeholder="Search Pretest">
                                                                  <ul role="options">
                                                                      <li><a tabindex="-1" href="#" data-option="0"><?php echo "None";?></a></li>
                                                                          <?php

                                                                              $qrypre3 = $ObjDB->QueryObject("SELECT a.fld_id AS taskpretestid,a.fld_test_name AS taskpretestname 
                                                                                            FROM itc_test_master AS a
                                                                                            LEFT JOIN
                                                                                            `itc_license_assessment_mapping` AS b ON a.fld_id = b.fld_assessment_id
                                                                                            LEFT JOIN
                                                                                            `itc_license_track` AS c ON b.fld_license_id = c.fld_license_id
                                                                                            WHERE c.fld_school_id='".$senshlid."' AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."'
                                                                                            AND a.fld_delstatus = '0' AND c.fld_delstatus = '0' AND c.fld_user_id = '".$indid."' and b.fld_access = '1'
                                                                                            and a.fld_prepostid = '1' and a.fld_ass_type='1' and a.fld_destid != '0' and a.fld_destid='".$destid."' and b.fld_license_id='".$schlicenseid."'
                                                                                            and a.fld_taskid !='0' and a.fld_taskid='".$taskid."' and a.fld_resid=0 and fld_profile_id='2' and a.fld_expt='".$expid."' and a.fld_delstatus = '0'
                                                                                            UNION ALL
                                                                                            select fld_id AS taskpretestid,fld_test_name AS taskpretestname
                                                                                            from itc_test_master where fld_destid != '0' and fld_destid='".$destid."' and fld_taskid !='0' and fld_taskid='".$taskid."' and fld_resid=0
                                                                                            and fld_prepostid = '1' and fld_ass_type='1' and  fld_created_by IN('".$uid."','".$distadminid."','".$schladminid."') 
                                                                                            and fld_expt='".$expid."' and fld_delstatus = '0' ORDER BY taskpretestname");

                                                                              if($qrypre3->num_rows>0)
                                                                              {
                                                                                  while($rowpre3=$qrypre3->fetch_assoc())
                                                                                  {
                                                                                      extract($rowpre3);
                                                                          ?>
                                                                               <li><a tabindex="-1" href="#" data-option="<?php echo $taskpretestid;?>"><?php echo $taskpretestname;?></a></li>
                                                                          <?php
                                                                                  }
                                                                              }
                                                                          ?>
                                                                  </ul>
                                                              </div>
                                                          </div>
                                                      </dt>                                       
                                                  </dl>  
                                              </div>
                                              <?php } else{ ?> <div class='three columns'> <dl class='field row'><input type="hidden" name="taskpre_<?php echo $expid."_".$destid."_".$taskid."_0";?>" id="" value="0"/>  </dl></div> <?php } ?>


                                              <?php 
                                              $checktasktestpostcount1 = $ObjDB->QueryObject("SELECT a.fld_id 
                                                                                            FROM itc_test_master AS a
                                                                                            LEFT JOIN
                                                                                            `itc_license_assessment_mapping` AS b ON a.fld_id = b.fld_assessment_id
                                                                                            LEFT JOIN
                                                                                            `itc_license_track` AS c ON b.fld_license_id = c.fld_license_id
                                                                                            WHERE c.fld_school_id='".$senshlid."' AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."'
                                                                                            AND a.fld_delstatus = '0' AND c.fld_delstatus = '0' AND c.fld_user_id = '".$indid."' and b.fld_access = '1'
                                                                                            and a.fld_prepostid = '2' and a.fld_ass_type='1' and a.fld_destid != '0' and a.fld_destid='".$destid."' and b.fld_license_id='".$schlicenseid."'
                                                                                            and a.fld_taskid !='0' and a.fld_taskid='".$taskid."' and a.fld_resid=0 and fld_profile_id='2' and a.fld_expt='".$expid."' and a.fld_delstatus = '0'
                                                                                            UNION ALL
                                                                                            select fld_id
                                                                                            from itc_test_master where fld_destid != '0' and fld_destid='".$destid."' and fld_taskid !='0' and fld_taskid='".$taskid."' and fld_resid=0
                                                                                            and fld_prepostid = '2' and fld_ass_type='1' and  fld_created_by IN('".$uid."','".$distadminid."','".$schladminid."') 
                                                                                            and fld_expt='".$expid."' and fld_delstatus = '0'");
                                                $checktasktestpostcount = $checktasktestpostcount1->num_rows>0;
                                                  
                                                if($checktasktestpostcount>0){
                                                  
                                                       $taskposttestname="Select Posttest";
                                                       $taskposttestid=0;
                                                  
                                              ?>
                                              <div class='three columns'>
                                                  <?php
                                                      if($scheduleid!=0){                                                                
                                                          $taskposttestdetail= $ObjDB->QueryObject("select a.fld_posttest as taskposttestid,b.fld_test_name as taskposttestname
                                                                                                      from itc_exp_ass as a left join itc_test_master as b on a.fld_posttest=b.fld_id where fld_tdestid='".$destid."' and fld_ttaskid='".$taskid."' and fld_tresid=0 
                                                                                                      and fld_texpid ='".$expid."' and fld_sch_id= '".$scheduleid."' and fld_schtype_id='19' and b.fld_delstatus='0'"); 
                                                          $taskposttestcount = $taskposttestdetail->num_rows>0; 
                                                          if($taskposttestcount !=0)
                                                          {
                                                             $rowtaskposttest=$taskposttestdetail->fetch_assoc();
                                                             extract($rowtaskposttest);                                                                     
                                                          }
                                                          if($taskposttestid==0 or $taskposttestid==''){
                                                              $taskposttestname="None";
                                                              $taskposttestid=0;
                                                          }
                                                          $taskpostplaycnt = $ObjDB->SelectSingleValueInt("select count(fld_id)
                                                                                                  from itc_exp_task_testplay_track 
                                                                                                  where fld_exp_id='".$expid."' and fld_dest_id='".$destid."' and fld_task_id='".$taskid."' and fld_task_test_id='".$taskposttestid."' and fld_schedule_id= '".$scheduleid."' and fld_schedule_type='19'");
                                                      }                                                         
                                                  ?>
                                                  <dl class='field row <?php if($taskpostplaycnt!=0){echo "dim";}?>'>   
                                                      <dt class='dropdown'>   
                                                          <div class="selectbox" style="width:200px;font-size: 14px; height: 20px; line-height: 20px;">
                                                              <input type="hidden" name="" id="taskpost_<?php echo $y; ?>" value="<?php echo $taskposttestid;?>"/>
                                                              <a class="selectbox-toggle" role="button" data-toggle="selectbox" style="font-size: 14px; height: 20px; line-height: 20px;">
                                                                  <span class="selectbox-option input-medium" data-option="<?php echo $taskposttestid;?>" id="clearsubject"><?php  echo $taskposttestname; ?></span>
                                                                  <b class="caret1"></b>
                                                              </a>                       
                                                              <div class="selectbox-options" style="width:210px;">
                                                                  <input type="text" class="selectbox-filter" placeholder="Search Posttest">
                                                                  <ul role="options">
                                                                      <li><a tabindex="-1" href="#" data-option="0"><?php echo "None";?></a></li>
                                                                          <?php

                                                                              $qrpost3 = $ObjDB->QueryObject("SELECT a.fld_id AS taskposttestid,a.fld_test_name AS taskposttestname
                                                                                            FROM itc_test_master AS a
                                                                                            LEFT JOIN
                                                                                            `itc_license_assessment_mapping` AS b ON a.fld_id = b.fld_assessment_id
                                                                                            LEFT JOIN
                                                                                            `itc_license_track` AS c ON b.fld_license_id = c.fld_license_id
                                                                                            WHERE c.fld_school_id='".$senshlid."' AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."'
                                                                                            AND a.fld_delstatus = '0' AND c.fld_delstatus = '0' AND c.fld_user_id = '".$indid."' and b.fld_access = '1'
                                                                                            and a.fld_prepostid = '2' and a.fld_ass_type='1' and a.fld_destid != '0' and a.fld_destid='".$destid."' and b.fld_license_id='".$schlicenseid."'
                                                                                            and a.fld_taskid !='0' and a.fld_taskid='".$taskid."' and a.fld_resid=0 and fld_profile_id='2' and a.fld_expt='".$expid."' and a.fld_delstatus = '0'
                                                                                            UNION ALL
                                                                                            select fld_id AS taskposttestid,fld_test_name AS taskposttestname
                                                                                            from itc_test_master where fld_destid != '0' and fld_destid='".$destid."' and fld_taskid !='0' and fld_taskid='".$taskid."' and fld_resid=0
                                                                                            and fld_prepostid = '2' and fld_ass_type='1' and  fld_created_by IN('".$uid."','".$distadminid."','".$schladminid."') 
                                                                                            and fld_expt='".$expid."' and fld_delstatus = '0' ORDER BY taskposttestname");

                                                                              if($qrpost3->num_rows>0)
                                                                              {
                                                                                  while($rowpost3=$qrpost3->fetch_assoc()) 
                                                                                  {
                                                                                      extract($rowpost3);
                                                                          ?>
                                                                               <li><a tabindex="-1" href="#" data-option="<?php echo $taskposttestid;?>"><?php echo $taskposttestname;?></a></li>
                                                                          <?php
                                                                                  }
                                                                              }
                                                                          ?>
                                                                  </ul>
                                                              </div>
                                                          </div>
                                                      </dt>                                       
                                                  </dl>
                                              </div>
                                              <?php } else{ ?> <div class='three columns'> <dl class='field row'>  </dl></div> 

                                                  <?php } 
                                                  $y++;
                                          }
                                          ?>  </div> <?php
                                          //Task test ends
                                                  //Res test starts
                                                      $qrytres = $ObjDB->QueryObject("SELECT fld_res_name as resname, fld_id as resid
                                                                                                  FROM itc_exp_resource_master WHERE fld_task_id='".$taskid."' AND fld_delstatus='0'");//limit 0,10
                                                      if ($qrytres->num_rows > 0) {
                                                          $e=1;
                                                          while ($rowtres = $qrytres->fetch_assoc()) {

                                                              extract($rowtres);
                                                              $checkrescount1 = $ObjDB->QueryObject("SELECT a.fld_id 
                                                                                                                FROM itc_test_master AS a
                                                                                                                LEFT JOIN
                                                                                                                `itc_license_assessment_mapping` AS b ON a.fld_id = b.fld_assessment_id
                                                                                                                LEFT JOIN
                                                                                                                `itc_license_track` AS c ON b.fld_license_id = c.fld_license_id
                                                                                                                WHERE c.fld_school_id='".$senshlid."' AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."'
                                                                                                                AND a.fld_delstatus = '0' AND c.fld_delstatus = '0' AND c.fld_user_id = '".$indid."' and b.fld_access = '1'
                                                                                                                and (a.fld_prepostid = '2' or a.fld_prepostid = '1') and a.fld_ass_type='1' and a.fld_destid != '0' 
                                                                                                                and a.fld_destid='".$destid."' and a.fld_taskid !='0' and a.fld_taskid='".$taskid."' and b.fld_license_id='".$schlicenseid."' 
                                                                                                                and a.fld_resid !='0' and a.fld_resid='".$resid."' and fld_profile_id='2' and a.fld_expt='".$expid."' and a.fld_delstatus = '0'
                                                                                                                UNION ALL
                                                                                                                select fld_id
                                                                                                                from itc_test_master where fld_destid != '0' and fld_destid='".$destid."' and fld_taskid !='0' and fld_taskid='".$taskid."' 
                                                                                                                and fld_resid !='0' and fld_resid='".$resid."' and (fld_prepostid = '2' or fld_prepostid = '1') and fld_ass_type='1' 
                                                                                                                and  fld_created_by IN('".$uid."','".$distadminid."','".$schladminid."') 
                                                                                                                and fld_expt='".$expid."' and fld_delstatus = '0'");
                                                                $checkrescount=$checkrescount1->num_rows>0;
                                                              
                                                              ?>
                                                              <div class='row rowspacer' style="margin-top:0px;">   
                                                                  <div class='six columns' class="block" style="text-indent:35px; margin-top:0px;" > <?php echo "R"."$e".".".$resname;?></div>

                                                              <?php
                                                              if($checkrescount>0){
                                                                    $checkrestestprecount1 = $ObjDB->QueryObject("SELECT a.fld_id 
                                                                                                                FROM itc_test_master AS a
                                                                                                                LEFT JOIN
                                                                                                                `itc_license_assessment_mapping` AS b ON a.fld_id = b.fld_assessment_id
                                                                                                                LEFT JOIN
                                                                                                                `itc_license_track` AS c ON b.fld_license_id = c.fld_license_id
                                                                                                                WHERE c.fld_school_id='".$senshlid."' AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."'
                                                                                                                AND a.fld_delstatus = '0' AND c.fld_delstatus = '0' AND c.fld_user_id = '".$indid."' and b.fld_access = '1'
                                                                                                                and a.fld_prepostid = '1' and a.fld_ass_type='1' and a.fld_destid != '0' and b.fld_license_id='".$schlicenseid."'
                                                                                                                and a.fld_destid='".$destid."' and a.fld_taskid !='0' and a.fld_taskid='".$taskid."' 
                                                                                                                and a.fld_resid !='0' and a.fld_resid='".$resid."' and fld_profile_id='2' and a.fld_expt='".$expid."' and a.fld_delstatus = '0'
                                                                                                                UNION ALL
                                                                                                                select fld_id
                                                                                                                from itc_test_master where fld_destid != '0' and fld_destid='".$destid."' and fld_taskid !='0' and fld_taskid='".$taskid."' 
                                                                                                                and fld_resid !='0' and fld_resid='".$resid."' and fld_prepostid = '1' and fld_ass_type='1' 
                                                                                                                and  fld_created_by IN('".$uid."','".$distadminid."','".$schladminid."') 
                                                                                                                and fld_expt='".$expid."' and fld_delstatus = '0'");
                                                                    $checkrestestprecount=$checkrestestprecount1->num_rows>0;
                                                                  
                                                                    if($checkrestestprecount>0){
                                                                 
                                                                          $respretestname="Select Pretest";
                                                                          $respretestid=0;
                                                                      
                                                                  ?>
                                                                  <div class='three columns'> 
                                                                      <?php
                                                                          if($scheduleid!=0){                                                                
                                                                              $respretestdetail= $ObjDB->QueryObject("select a.fld_pretest as respretestid,b.fld_test_name as respretestname
                                                                                                                          from itc_exp_ass as a left join itc_test_master as b on a.fld_pretest=b.fld_id where fld_tdestid='".$destid."' and fld_ttaskid='".$taskid."' and fld_tresid='".$resid."' 
                                                                                                                          and fld_texpid ='".$expid."' and fld_sch_id= '".$scheduleid."' and fld_schtype_id='19' and b.fld_delstatus='0'"); 
                                                                              $respretestcount = $respretestdetail->num_rows>0; 
                                                                              if($respretestcount !=0)
                                                                              {
                                                                                 $rowrespretest=$respretestdetail->fetch_assoc();
                                                                                 extract($rowrespretest);                                                                     
                                                                              }
                                                                              if($respretestid==0 or $respretestid==''){
                                                                                  $respretestname="None";
                                                                                  $respretestid=0;
                                                                              }
                                                                              $respreplaycnt = $ObjDB->SelectSingleValueInt("select count(fld_id)
                                                                                                  from itc_exp_res_testplay_track 
                                                                                                  where fld_exp_id='".$expid."' and fld_dest_id='".$destid."' and fld_task_id='".$taskid."' and fld_res_id='".$resid."' and fld_res_test_id='".$respretestid."' and fld_schedule_id= '".$scheduleid."' and fld_schedule_type='19'");
                                                                          }                                                         
                                                                      ?>
                                                                      <dl class='field row <?php if($respreplaycnt!=0){echo "dim";}?>'>   
                                                                          <dt class='dropdown'>   
                                                                              <div class="selectbox" style="width:200px;font-size: 14px; height: 20px; line-height: 20px;">
                                                                                  <input type="hidden" name="respre_<?php echo $expid."_".$destid."_".$taskid."_".$resid;?>" id="" value="<?php echo $respretestid;?>"/>
                                                                                  <a class="selectbox-toggle" role="button" data-toggle="selectbox" style="font-size: 14px; height: 20px; line-height: 20px;">
                                                                                      <span class="selectbox-option input-medium" data-option="<?php echo $respretestid;?>" id="clearsubject"><?php echo $respretestname;?></span>
                                                                                      <b class="caret1"></b>
                                                                                  </a>                       
                                                                                  <div class="selectbox-options" style="width:210px;">
                                                                                      <input type="text" class="selectbox-filter" placeholder="Search Pretest">
                                                                                      <ul role="options">
                                                                                          <li><a tabindex="-1" href="#" data-option="0"><?php echo "None";?></a></li>
                                                                                              <?php

                                                                                                  $qrypre4 = $ObjDB->QueryObject("SELECT a.fld_id AS respretestid,a.fld_test_name AS respretestname
                                                                                                                FROM itc_test_master AS a
                                                                                                                LEFT JOIN
                                                                                                                `itc_license_assessment_mapping` AS b ON a.fld_id = b.fld_assessment_id
                                                                                                                LEFT JOIN
                                                                                                                `itc_license_track` AS c ON b.fld_license_id = c.fld_license_id
                                                                                                                WHERE c.fld_school_id='".$senshlid."' AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."'
                                                                                                                AND a.fld_delstatus = '0' AND c.fld_delstatus = '0' AND c.fld_user_id = '".$indid."' and b.fld_access = '1'
                                                                                                                and a.fld_prepostid = '1' and a.fld_ass_type='1' and a.fld_destid != '0' and b.fld_license_id='".$schlicenseid."'
                                                                                                                and a.fld_destid='".$destid."' and a.fld_taskid !='0' and a.fld_taskid='".$taskid."' 
                                                                                                                and a.fld_resid !='0' and a.fld_resid='".$resid."' and fld_profile_id='2' and a.fld_expt='".$expid."' and a.fld_delstatus = '0'
                                                                                                                UNION ALL
                                                                                                                select fld_id AS respretestid,fld_test_name AS respretestname
                                                                                                                from itc_test_master where fld_destid != '0' and fld_destid='".$destid."' and fld_taskid !='0' and fld_taskid='".$taskid."' 
                                                                                                                and fld_resid !='0' and fld_resid='".$resid."' and fld_prepostid = '1' and fld_ass_type='1' 
                                                                                                                and  fld_created_by IN('".$uid."','".$distadminid."','".$schladminid."') 
                                                                                                                and fld_expt='".$expid."' and fld_delstatus = '0' order by respretestname");

                                                                                                  if($qrypre4->num_rows>0)
                                                                                                  {
                                                                                                      while($rowpre4=$qrypre4->fetch_assoc())
                                                                                                      {
                                                                                                          extract($rowpre4);
                                                                                              ?>
                                                                                                   <li><a tabindex="-1" href="#" data-option="<?php echo $respretestid;?>"><?php echo $respretestname;?></a></li> 
                                                                                              <?php
                                                                                                      }
                                                                                                  }
                                                                                              ?>
                                                                                      </ul>
                                                                                  </div>
                                                                              </div>
                                                                          </dt>                                       
                                                                      </dl>  
                                                                  </div>
                                                                  <?php } else{ ?> <div class='three columns'> <dl class='field row'><input type="hidden" name="respre_<?php echo $expid."_".$destid."_".$taskid."_".$resid;?>" id="" value="0"/>  </dl></div> <?php } ?>


                                                                  <?php                                                                        
                                                                  $checkrestestpostcount1 = $ObjDB->QueryObject("SELECT a.fld_id 
                                                                                                                FROM itc_test_master AS a
                                                                                                                LEFT JOIN
                                                                                                                `itc_license_assessment_mapping` AS b ON a.fld_id = b.fld_assessment_id
                                                                                                                LEFT JOIN
                                                                                                                `itc_license_track` AS c ON b.fld_license_id = c.fld_license_id
                                                                                                                WHERE c.fld_school_id='".$senshlid."' AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."'
                                                                                                                AND a.fld_delstatus = '0' AND c.fld_delstatus = '0' AND c.fld_user_id = '".$indid."' and b.fld_access = '1'
                                                                                                                and a.fld_prepostid = '2' and a.fld_ass_type='1' and a.fld_destid != '0' and b.fld_license_id='".$schlicenseid."'
                                                                                                                and a.fld_destid='".$destid."' and a.fld_taskid !='0' and a.fld_taskid='".$taskid."' 
                                                                                                                and a.fld_resid !='0' and a.fld_resid='".$resid."' and fld_profile_id='2' and a.fld_expt='".$expid."' and a.fld_delstatus = '0'
                                                                                                                UNION ALL
                                                                                                                select fld_id
                                                                                                                from itc_test_master where fld_destid != '0' and fld_destid='".$destid."' and fld_taskid !='0' and fld_taskid='".$taskid."' 
                                                                                                                and fld_resid !='0' and fld_resid='".$resid."' and fld_prepostid = '2' and fld_ass_type='1' 
                                                                                                                and  fld_created_by IN('".$uid."','".$distadminid."','".$schladminid."') 
                                                                                                                and fld_expt='".$expid."' and fld_delstatus = '0'");
                                                                    $checkrestestpostcount=$checkrestestpostcount1->num_rows>0;
                                                                      
                                                                    if($checkrestestpostcount>0){
                                                                      
                                                                          $resposttestname="Select Posttest";
                                                                          $resposttestid=0;
                                                                     
                                                                  ?>
                                                                  <div class='three columns'>
                                                                      <?php
                                                                          if($scheduleid!=0){                                                                                      
                                                                              $resposttestdetail= $ObjDB->QueryObject("select a.fld_posttest as resposttestid,b.fld_test_name as resposttestname
                                                                                                                          from itc_exp_ass as a left join itc_test_master as b on a.fld_posttest=b.fld_id where fld_tdestid='".$destid."' and fld_ttaskid='".$taskid."' and fld_tresid='".$resid."' 
                                                                                                                          and fld_texpid ='".$expid."' and fld_sch_id= '".$scheduleid."' and fld_schtype_id='19' and b.fld_delstatus='0'"); 
                                                                              $resposttestcount = $resposttestdetail->num_rows>0; 
                                                                              if($resposttestcount !=0)
                                                                              {
                                                                                 $rowresposttest=$resposttestdetail->fetch_assoc();
                                                                                 extract($rowresposttest);                                                                     
                                                                              }
                                                                              if($resposttestid==0 or $resposttestid==''){
                                                                                  $resposttestname="None";
                                                                                  $resposttestid=0;
                                                                              }
                                                                              $respostplaycnt = $ObjDB->SelectSingleValueInt("select count(fld_id)
                                                                                                  from itc_exp_res_testplay_track 
                                                                                                  where fld_exp_id='".$expid."' and fld_dest_id='".$destid."' and fld_task_id='".$taskid."' and fld_res_id='".$resid."' and fld_res_test_id='".$resposttestid."' and fld_schedule_id= '".$scheduleid."' and fld_schedule_type='19'");
                                                                          }                                                         
                                                                      ?>
                                                                      <dl class='field row <?php if($respostplaycnt!=0){echo "dim";}?>'>   
                                                                          <dt class='dropdown'>   
                                                                              <div class="selectbox" style="width:200px;font-size: 14px; height: 20px; line-height: 20px;">
                                                                                  <input type="hidden" name="" id="respost_<?php echo $z; ?>" value="<?php echo $resposttestid;?>"/>
                                                                                  <a class="selectbox-toggle" role="button" data-toggle="selectbox" style="font-size: 14px; height: 20px; line-height: 20px;">
                                                                                      <span class="selectbox-option input-medium" data-option="<?php echo $resposttestid;?>" id="clearsubject"><?php  echo $resposttestname;?></span>
                                                                                      <b class="caret1"></b>
                                                                                  </a>                       
                                                                                  <div class="selectbox-options" style="width:210px;">
                                                                                      <input type="text" class="selectbox-filter" placeholder="Search Posttest">
                                                                                      <ul role="options">
                                                                                          <li><a tabindex="-1" href="#" data-option="0"><?php echo "None";?></a></li>
                                                                                              <?php

                                                                                                  $qrpost4 = $ObjDB->QueryObject("SELECT a.fld_id AS resposttestid,a.fld_test_name AS resposttestname
                                                                                                                FROM itc_test_master AS a
                                                                                                                LEFT JOIN
                                                                                                                `itc_license_assessment_mapping` AS b ON a.fld_id = b.fld_assessment_id
                                                                                                                LEFT JOIN
                                                                                                                `itc_license_track` AS c ON b.fld_license_id = c.fld_license_id
                                                                                                                WHERE c.fld_school_id='".$senshlid."' AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."'
                                                                                                                AND a.fld_delstatus = '0' AND c.fld_delstatus = '0' AND c.fld_user_id = '".$indid."' and b.fld_access = '1'
                                                                                                                and a.fld_prepostid = '2' and a.fld_ass_type='1' and a.fld_destid != '0' and b.fld_license_id='".$schlicenseid."'
                                                                                                                and a.fld_destid='".$destid."' and a.fld_taskid !='0' and a.fld_taskid='".$taskid."' 
                                                                                                                and a.fld_resid !='0' and a.fld_resid='".$resid."' and fld_profile_id='2' and a.fld_expt='".$expid."' and a.fld_delstatus = '0'
                                                                                                                UNION ALL
                                                                                                                select fld_id AS resposttestid,fld_test_name AS resposttestname
                                                                                                                from itc_test_master where fld_destid != '0' and fld_destid='".$destid."' and fld_taskid !='0' and fld_taskid='".$taskid."' 
                                                                                                                and fld_resid !='0' and fld_resid='".$resid."' and fld_prepostid = '2' and fld_ass_type='1' 
                                                                                                                and  fld_created_by IN('".$uid."','".$distadminid."','".$schladminid."') 
                                                                                                                and fld_expt='".$expid."' and fld_delstatus = '0' order by resposttestname");

                                                                                                  if($qrpost4->num_rows>0)
                                                                                                  {
                                                                                                      while($rowpost4=$qrpost4->fetch_assoc()) 
                                                                                                      {
                                                                                                          extract($rowpost4);
                                                                                              ?>
                                                                                                   <li><a tabindex="-1" href="#" data-option="<?php echo $resposttestid;?>"><?php echo $resposttestname;?></a></li>
                                                                                              <?php
                                                                                                      }
                                                                                                  }
                                                                                              ?>
                                                                                      </ul>
                                                                                  </div>
                                                                              </div>
                                                                          </dt>                                       
                                                                      </dl>
                                                                  </div>
                                                                  <?php } else{ ?> <div class='three columns'> <dl class='field row'>  </dl></div> <?php } 

                                                                  $z++;
                                                              }
                                                              ?> </div><?php
                                                          $e++;
                                                          }// res while
                                                      }// res if
                                                      $d++;
                                                  } //task While
                                              }//task if
                                              $b++;
                                          } // Dest while

                                      }// Dest if
                                ?>
                                  </div>
                                      <?php
                                  //$b++;
                              }
                               $a++;
              }

          }?>
          </div>
  <?php
    }

}
// Exp Sch Inline Ass Ends - Karthi         
        
        
        
        if($oper=="exploadcontent" and $oper != " ")
	{
		
		$sid = isset($method['sid']) ? $method['sid'] : '0';		
		$licenseid = isset($method['lid']) ? $method['lid'] : '0';
		$classid = isset($method['classid']) ? $method['classid'] : '';
                $mtype = isset($method['type']) ? $method['type'] : '0';
		$flag=0;
// Checking to module template type
		if($mtype == 0) {
		      $mtype=1;
		      $assigntype = 0;
		    }
		    else
		    {
		      $assigntype = 1;
		    }
		
	
		$qryschdet=$ObjDB->NonQuery("SELECT COUNT(b.fld_id) AS countschedulestumap,a.fld_flag as flag,a.fld_schedule_name AS schedulename,a.fld_startdate AS startdate,a.fld_numberofcopies AS numberofcopies,a.fld_numberofrotations AS numberofrotations,a.fld_rotationlength AS rotationlength
                                    FROM itc_class_rotation_expschedule_mastertemp AS a
                                    LEFT JOIN itc_class_rotation_expschedule_student_mappingtemp AS b ON b.fld_schedule_id=a.fld_id
                                    WHERE a.fld_id='".$sid."' AND b.fld_flag='1'");
									
		if($qryschdet->num_rows>0)
		{
		$row=$qryschdet->fetch_assoc();
		extract($row);
			if($flag==0)
			{
				$count=0;
			}
			else
			{
				$count=1;
			}
		}
		else
		{
			$count=0;
			$countschedulestumap=0;
		}
		
		if($count==0)
		{
			$type="create";
		}
		else
		{
			$type="update";
		}
		
	if($countschedulestumap==0)
	{
		$countstudent=$ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_class_student_mapping WHERE fld_class_id='".$classid."' AND fld_flag=1");
	}
	else
	{
		$countstudent=$countschedulestumap;
	}
	
	
	if($sid==0 or $flag==0)
	{
		$value="Next";
	}
	else
	{
		if($assigntype == 0)
		   $value="Next";
		else
		$value="View Schedule";
	}

		if($sid!=0)
		{
			$expids='';
			$qrygetexpeditionclasssch=$ObjDB->QueryObject("SELECT fld_expedition_id AS expid FROM itc_class_rotation_schedule_expedition_mappingtemp
													WHERE fld_schedule_id='".$sid."' AND fld_flag='1'"); //a.fld_schedule_name AS schedulename
		
			if($qrygetexpeditionclasssch->num_rows>0)
			{
				$m=1;
				while($rowclasssch=$qrygetexpeditionclasssch->fetch_assoc())
				{
					extract($rowclasssch);
					
					if($m=='1'){
						$expids=$expid;
					}
					else
					{
						$expids=$expids.",".$expid;
					}
					$m++;
				}
			}
		
			$sqry=" AND fld_exp_id IN (".$expids.")";
			
		}
		else
		{
			$sqry='';
		}
?>
				<form id="sform">
					<div class='row'>
							<div class='four columns'>
                            	Number of copies<span class="fldreq">*</span>
                                    <dl class='field row'>
                                        <dt class='text'>
                                              <input placeholder='number of copies' required='' type='text' id="numberofcopies" name="numberofcopies" value="<?php if($numberofcopies!=''){ echo $numberofcopies; }else { echo "1";}?>" <?php if($count==1){?> readonly title="Read only" <?php }?> onkeypress="return isNumberKey(event);">
                                        </dt>                                        
                                    </dl>
                                </div>
                                <div class='four columns'>
                                	 Number of rotations<span class="fldreq">*</span>
                                    <dl class='field row'>
                                        <dt class='text'>
                                          <input placeholder='number of rotations' required='' type='text' id="numberofrotations" name="numberofrotations" value="<?php if($numberofrotations>0){ echo $numberofrotations ;} else { echo "1";} ?>" onkeypress="return isNumberKey(event);" <?php if($count==1){?> readonly title="Read only" <?php }?>>
                                        </dt>                                        
                                    </dl>
                                </div>
                                <div class='four columns'>
                                	Rotation  length<span class="fldreq">*</span>
                                    <dl class='field row'>
                                        <dt class='text'>
                                            <input placeholder='rotation length' required='' type='text' id="rotationlength" name="rotationlength" value="<?php if($rotationlength!=''){echo $rotationlength;}else { echo "7";}?>" onkeypress="return isNumberKey(event);" <?php if($count==1){?> readonly title="Read only" <?php }?>>
                                        </dt>                                        
                                    </dl>
                                </div>
                            </div>
                         </form>
					
							
                            <script>fn_loadexpedition(<?php echo $sid.",".$mtype.",".$assigntype; ?>);</script>
                           
                            <div id="expeditions"> 
                                                       
                            </div> 
                            
                            <div><!----Mohan M -->
                            <input type="checkbox" id='chkblock'>Select Block Expeditions 
                            <script>
                                    $(document).ready(function(){
                                
                                         $('#chkblock').click(function(){

                                                 if($('input[type="checkbox"]').prop("checked") == true){
                                                           
                                                            $('#blockexpeditions').show();
                                                            $('#blockstudent').show();
                                                            $('#expstublock').show();
                                                            
                                                            
                                                            
                                                         }
            
                                                 else if($('input[type="checkbox"]').prop("checked") == false){
                                                                
                                                             $('#blockexpeditions').hide();
                                                             $('#blockstudent').hide();
                                                             $('#expstublock').hide();
                                                             
                                                             
                                                          }

                                                        });

                                                     });
                            </script>
            
                            </div>
                            
                            <div id="blockexpeditions" style="display:none;">
                                
                            </div>
                            
                            <div id="blockstudent" style="display:none;">
                                
                            </div>
                            
                           
                             <div class='row rowspacer'>
                                    <div id="expstublock" style="float:right;display:none;">
                                        <input type="button" id="modstublock" class="darkButton" value="Save Block Expedition" onclick="fn_blockexpstudent();" /> 
                                    </div>
                    		</div>
<!-- Mohan M-->
<div class="row " id="rubriccontent" style="">
	<div class='twelve columns'>
		<?php
                $pitscoadmins=$ObjDB->SelectSingleValue("SELECT group_concat(fld_id) FROM itc_user_master WHERE fld_profile_id='2' AND fld_delstatus='0' AND fld_activestatus='1'");	
       
                
		if($sessmasterprfid == 5)
		{ 	//For Teacher inv
                           
			 $qry = "SELECT fld_rub_name, fld_id, fld_exp_id, fld_created_by,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname  FROM itc_exp_rubric_name_master WHERE fld_delstatus = '0' AND fld_created_by IN (".$pitscoadmins.", ".$uid.")  ".$sqry."
						UNION SELECT fld_rub_name, fld_id, fld_exp_id, fld_created_by,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname FROM itc_exp_rubric_name_master WHERE fld_delstatus = '0' and fld_district_id = '0' ".$sqry." and fld_school_id = '0' and fld_user_id='".$indid."'";


		}
		else if($sessmasterprfid == 7)
		{ 	//For School Admin

			$qry = "SELECT fld_rub_name, fld_id, fld_exp_id, fld_created_by, fld_profile_id ,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname FROM itc_exp_rubric_name_master WHERE fld_delstatus = '0' AND fld_created_by IN (".$pitscoadmins.", ".$uid.") ".$sqry."
						UNION 
						SELECT fld_rub_name, fld_id, fld_exp_id, fld_created_by, fld_profile_id,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname  FROM itc_exp_rubric_name_master WHERE fld_delstatus = '0' and fld_district_id = '".$sendistid."' ".$sqry." and fld_school_id = '0' order by fld_profile_id ASC";

		}
		else
		{ 	//For Teacher

			$qry="SELECT fld_rub_name, fld_id, fld_exp_id, fld_created_by, fld_profile_id ,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname FROM itc_exp_rubric_name_master WHERE fld_delstatus = '0' AND fld_created_by IN(".$pitscoadmins.") ".$sqry."
						UNION SELECT fld_rub_name, fld_id, fld_exp_id, fld_created_by, fld_profile_id,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname  FROM itc_exp_rubric_name_master WHERE fld_delstatus = '0'  ".$sqry."
					and fld_district_id = '".$sendistid."' and fld_school_id = '0'
						UNION  SELECT fld_rub_name, fld_id, fld_exp_id, fld_created_by, fld_profile_id,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname  FROM itc_exp_rubric_name_master WHERE fld_delstatus = '0'  ".$sqry."
					and fld_district_id = '".$sendistid."' and fld_school_id='".$schoolid."' and fld_profile_id='7'
						UNION  SELECT fld_rub_name, fld_id, fld_exp_id, fld_created_by, fld_profile_id,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname  FROM itc_exp_rubric_name_master WHERE fld_delstatus = '0' ".$sqry."
					and fld_district_id = '".$sendistid."' and fld_school_id='".$schoolid."' AND fld_created_by ='".$uid."' order by fld_profile_id ASC";

		}

		?>
Select Grading Rubric
		 <div > 
			<dl class='field row' >  
				<?php
				$rubricvalues=array();
				$qry_for_get_all_expedition = $ObjDB->QueryObject($qry);
				if($qry_for_get_all_expedition->num_rows>0)
				{
						$i=1; 
						?><table cellpadding="19px" cellspacing="19px" > <tr><?php
					while($row=$qry_for_get_all_expedition->fetch_assoc())
					{
						extract($row); 

						$chkval = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_class_expmis_rubricmaster 
																WHERE fld_rubric_id='".$fld_id."' AND fld_schedule_id='".$sid."' 
																AND fld_delstatus='0' AND fld_schedule_type='17'");

						?>
						<td>
							<dt>       
								<input id="chkboxrubric_<?php echo $fld_id; ?>" type="checkbox" value="<?php echo $fld_exp_id; ?>" name="chkbox" <?php if($chkval=='1' || $sid=='0' ){echo "checked"; } ?>  >
								<span></span>
								<?php echo $fld_rub_name; ?>
							</dt>
						</td>
						<?php
						if($i%3==0)
						{
							echo "</tr><tr>";
						}
						$i++;

					}
					?> </tr></table> <?php
				}
				?>
			</dl>
		</div>
	</div>	
</div>
 <!-- Mohan M-->                      

 
                    <div id="showschinlineass" class='row rowspacer'></div>
                    
                             <div class='row rowspacer'>
                                    <div id="extenddiv" style="float:left;"> <!-- extend content -->
                                       Materials List of the Expeditions in your class
                                    </div>
                                    <div style="float:right;">
                                        <input type="button" id="extendbtn" class="darkButton" value="Materials List" onclick="fn_rotloadexpextendcontent(<?php echo $sid.",".$licenseid.",";?>'exc');" /> 
                                    </div>
                    		</div>
							<!-- Extend Content added start line created by chandru  -->   
							<div class='row rowspacer'>
                                    <div id="extenddiv1" style="float:left;"> <!-- extend content -->
                                       Extend Content List in your assignment
                                    </div>
                                    <div id="extendbtn"   style="float:right;">
                                        <input type="button" id="extendbtn" class="darkButton" value="Extend Content" onclick="fn_rotloadexpextendcontent1(<?php echo $sid.",".$licenseid.",";?>'exc');" /> 
                                    </div>
                    		</div>
							<!-- Extend Content added start line -->
                            
                            
                            <div id="expextendcontent" class='row rowspacer'>
                            </div>                                                           
                                       
							<div class="row rowspacer" style="margin-top:20px;">
                                <div class="tLeft" style="color:#F00;">
                                </div>
                                <div class="tRight" id="modnxtstep" style="display:none;">
                                    <input type="button" class="darkButton" id="btnstep" style="width:200px; height:42px;float:right;" value="<?php echo $value;?>" onClick="fn_saveexpeditionalschedule(0);" />
                                </div>
                            </div>
                            <?php
		                    if($assigntype == 0) {
		                      $sid = 0;
		                      $type="create";
		                    }
                            ?>
                             <input type="hidden" id="scount" value="<?php echo $countstudent; ?>"/>
                             <input type="hidden" id="rotationtype" value="<?php echo $type; ?>"/>
                             <input type="hidden" id="scheduleid" value="<?php echo $sid; ?>"/>
                             
				  

 <script type="text/javascript" language="javascript">
       
		$(function(){
            $("#sform").validate({
                	ignore: "",
					errorElement: "dd",
					errorPlacement: function(error, element) {
						$(element).parents('dl').addClass('error');
						error.appendTo($(element).parents('dl'));
						error.addClass('msg'); 		
				},
                rules: { 
					numberofcopies: { required: true },
					numberofrotations: { required: true },
					rotationlength: { required: true }
				}, 
                messages: { 	  
					numberofcopies:{ required:  "please enter number of copies" },
					numberofrotations: {   required: "Enter number of rotations" },
					rotationlength: {   required: "please enter rotation length" }
					
					
					
                },
                highlight: function(element, errorClass, validClass) {
					$(element).parent('dl').addClass(errorClass);
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
                });	
				</script>	
<?php
	}
        
/********Mohan M***********/
if($oper=="showrubric" and $oper!='')
{
	$expids= isset($method['list4']) ? $method['list4'] : '0';
        $pitscoadmins=$ObjDB->SelectSingleValue("SELECT group_concat(fld_id) FROM itc_user_master WHERE fld_profile_id='2' AND fld_delstatus='0' AND fld_activestatus='1'");
        
	if($expids!='')
	{
		if($sessmasterprfid == 5)
		{ 	//For Teacher inv
        
			 $qry = "SELECT fld_rub_name, fld_id, fld_exp_id, fld_created_by,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname  FROM itc_exp_rubric_name_master WHERE fld_exp_id IN (".$expids.") and fld_delstatus = '0' AND fld_created_by IN (".$pitscoadmins." , ".$uid.") 
						UNION SELECT fld_rub_name, fld_id, fld_exp_id, fld_created_by,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname FROM itc_exp_rubric_name_master WHERE fld_exp_id IN (".$expids.") and fld_delstatus = '0' and fld_district_id = '0' and fld_school_id = '0' and fld_user_id='".$indid."'";
        

		}
		else if($sessmasterprfid == 7)
		{ 	//For School Admin

			$qry = "SELECT fld_rub_name, fld_id, fld_exp_id, fld_created_by, fld_profile_id ,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname FROM itc_exp_rubric_name_master WHERE fld_exp_id IN (".$expids.") and fld_delstatus = '0' AND fld_created_by IN (".$pitscoadmins." , ".$uid.") 
						UNION 
						SELECT fld_rub_name, fld_id, fld_exp_id, fld_created_by, fld_profile_id,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname  FROM itc_exp_rubric_name_master WHERE fld_exp_id IN (".$expids.") and fld_delstatus = '0' and fld_district_id = '".$sendistid."' and fld_school_id = '0' order by fld_profile_id ASC";

		}
		else
		{ 	//For Teacher

			$qry="SELECT fld_rub_name, fld_id, fld_exp_id, fld_created_by, fld_profile_id ,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname FROM itc_exp_rubric_name_master WHERE fld_exp_id IN (".$expids.") and fld_delstatus = '0' AND fld_created_by IN(".$pitscoadmins.")
					UNION SELECT fld_rub_name, fld_id, fld_exp_id, fld_created_by, fld_profile_id,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname  FROM itc_exp_rubric_name_master WHERE fld_exp_id IN (".$expids.") and fld_delstatus = '0' 
					and fld_district_id = '".$sendistid."' and fld_school_id = '0'
					UNION  SELECT fld_rub_name, fld_id, fld_exp_id, fld_created_by, fld_profile_id,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname  FROM itc_exp_rubric_name_master WHERE fld_exp_id IN (".$expids.") and fld_delstatus = '0' 
					and fld_district_id = '".$sendistid."' and fld_school_id='".$schoolid."' and fld_profile_id='7'
					UNION  SELECT fld_rub_name, fld_id, fld_exp_id, fld_created_by, fld_profile_id,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname  FROM itc_exp_rubric_name_master WHERE fld_exp_id IN (".$expids.") and fld_delstatus = '0' 
					and fld_district_id = '".$sendistid."' and fld_school_id='".$schoolid."' AND fld_created_by ='".$uid."' order by fld_profile_id ASC";

		}

		$rubricvalues=array();
		$qry_for_get_all_expedition = $ObjDB->QueryObject($qry);
		if($qry_for_get_all_expedition->num_rows>0)
		{ ?>
			<div class="row" > 
				<div class='twelve columns'>Select Grading Rubric
					<div >
						<dl class='field row' > 
							<?php
			  				$i=1; 
							?><table cellpadding="19px" cellspacing="19px" > <tr><?php
							while($row=$qry_for_get_all_expedition->fetch_assoc())
							{
								extract($row); 

								$chkval = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_class_expmis_rubricmaster 
																		WHERE fld_rubric_id='".$fld_id."' AND fld_schedule_id='".$scheduleid."' 
																		AND fld_delstatus='0' AND fld_schedule_type='17'");

								?>
								<td>
								<dt>       
									<input id="chkboxrubric_<?php echo $fld_id; ?>" type="checkbox" value="<?php echo $fld_exp_id; ?>" name="chkbox" <?php if($chkval=='0'){echo "checked"; } ?>  >
									<span></span>
									<?php echo $fld_rub_name; ?>
								</dt>
								</td>
								
											
								<?php
								if($i%3==0)
								{
									echo "</tr><tr>";
									
								}
								$i++;
							}
			 					?> </tr></table> 
							
						</dl>
					</div>
				</div>	
				
			</div>
		<?php
		}
		else
		{
			echo "fail";
		}
	}
}
/********Mohan M***********/
        
        
        /*--- load expeditions  ---*/
	if($oper=="loadexpedition" and $oper!='')
	{
		$licenseid = isset($method['licenseid']) ? $method['licenseid'] : '0';
		$scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '0';
         	$assigntype = isset($method['assigntype']) ? $method['assigntype'] : '';
		
		$count=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_class_rotation_expschedule_mastertemp WHERE fld_flag=1 and fld_id='".$scheduleid."'");
		
		if($assigntype == 0)
                {
                  $count = 0;
                }
		
	       $countmodulemap=$ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_class_rotation_schedule_expedition_mappingtemp WHERE fld_schedule_id='".$scheduleid."' AND fld_flag=1");
			
			if($countmodulemap==0)
			{
	?>
    		<script>
				fn_movealllistitems('list3','list4',0);
            </script>
    <?php
			}
			?>
    	 <script language="javascript" type="text/javascript">
    					$(function() {
							$('#testrailvisible15').slimscroll({
								width: '410px',
								height:'366px',
								size: '7px',
                                                                alwaysVisible: true,
                                                                wheelStep: 1,
								railVisible: true,
                                                                allowPageScroll: false,
								railColor: '#F4F4F4',
								opacity: 1,
								color: '#d9d9d9'
								
							});
							$('#testrailvisible16').slimscroll({
								width: '410px',
								height:'366px',
								size: '7px',
								railVisible: true,
                                                                alwaysVisible: true,
								allowPageScroll: false,
								railColor: '#F4F4F4',
								opacity: 1,
								color: '#d9d9d9',
                                                                wheelStep: 1
							});
							
							$("#list3").sortable({
								connectWith: ".droptrue1",
								dropOnEmpty: true,
								items: "div[class='draglinkleft']",
								receive: function(event, ui) {
									$("div[class=draglinkright]").each(function(){ 
										if($(this).parent().attr('id')=='list3'){
											fn_movealllistitems('list3','list4',$(this).children(":first").attr('id'),'rotational');
										}
									});
								}
							});
                        
							$( "#list4" ).sortable({
								connectWith: ".droptrue1",
								dropOnEmpty: true,
								receive: function(event, ui) {
									$("div[class=draglinkleft]").each(function(){ 
										if($(this).parent().attr('id')=='list4'){
											fn_movealllistitems('list3','list4',$(this).children(":first").attr('id'),'rotational');
										}
									});
								}
							});
                        });
                    
      		 </script>
            <div class='row rowspacer <?php if($count==1){echo "dim";}?>' >
                <div class='six columns'>
                    <div class="dragndropcol">
                        <?php
                               
																					
                                        $qryexpedition= $ObjDB->QueryObject("SELECT 
                                                                            a.fld_id as expid,
                                                                            fn_shortname(CONCAT(a.fld_exp_name, ' ', b.fld_version),
                                                                                    1) AS shortname,
                                                                            CONCAT(a.fld_exp_name, ' ', b.fld_version) as expname

                                                                            FROM
                                                                            itc_exp_master AS a
                                                                                LEFT JOIN
                                                                            itc_exp_version_track AS b ON b.fld_exp_id = a.fld_id
                                                                                LEFT JOIN
                                                                            itc_license_exp_mapping AS c ON a.fld_id = c.fld_exp_id
                                                                            WHERE
                                                                            a.fld_id NOT IN (SELECT 
                                                                                    fld_expedition_id
                                                                                FROM
                                                                                    itc_class_rotation_schedule_expedition_mappingtemp
                                                                                WHERE
                                                                                    fld_schedule_id = '".$scheduleid."'
                                                                                        AND fld_flag = '1')
                                                                                AND c.fld_license_id = '".$licenseid."'
                                                                                AND c.fld_flag = '1'
                                                                                AND a.fld_delstatus = '0'
                                                                                AND b.fld_delstatus = '0' group by a.fld_id
                                                                            ORDER BY expname");
										
                        ?>
                        <div class="dragtitle">Expeditions (<span id="leftmoddiv"><?php echo $qryexpedition->num_rows;?></span>)</div>
                        <div class="dragWell" id="testrailvisible15" >
                        <div class="draglinkleftSearch" id="s_list3" >
                                                   <dl class='field row'>
                                                        <dt class='text'>
                                                            <input placeholder='Search' type='text' id="list_3_search" name="list_3_search" onKeyUp="search_list(this,'#list3');" />
                                                        </dt>
                                                    </dl>
                                                </div>
                            <div id="list3" class="dragleftinner droptrue1">
									<?php 
                                       
											if($qryexpedition->num_rows > 0){
												while($rowsqry = $qryexpedition->fetch_assoc()){
													extract($rowsqry);
													
                                                ?>
                                            <div class="draglinkleft" id="list3_<?php echo $expid; ?>" title="<?php echo $expname; ?>">
                                                <div class="dragItemLable" id="<?php echo $expid; ?>"><?php echo $expname; ?></div>
                                                <div class="clickable" id="clck_<?php echo $expid; ?>" onclick="fn_movealllistitems('list3','list4','<?php echo $expid; ?>','exprotational');"></div>
                                            </div> 
                                        <?php }
                                            }?>
                            </div>
                        </div>
                        <div class="dragAllLink"  onclick="fn_movealllistitems('list3','list4',0,'exprotational');fn_rotloadexpextendcontent(<?php echo $scheduleid.",".$licenseid.",";?>'mod');">add all expeditions</div>
                    </div>
                </div>
                <div class='six columns'>
                    <div class="dragndropcol">
                        <?php
                              
                                     $qryexpmap=$ObjDB->QueryObject("SELECT 
                                                                            a.fld_id as expid,
                                                                            fn_shortname(CONCAT(a.fld_exp_name, ' ', b.fld_version),
                                                                                    1) AS shortname,
                                                                            CONCAT(a.fld_exp_name, ' ', b.fld_version) as expname

                                                                            FROM
                                                                            itc_exp_master AS a
                                                                                LEFT JOIN
                                                                            itc_exp_version_track AS b ON b.fld_exp_id = a.fld_id
                                                                                LEFT JOIN 
                                                                            itc_class_rotation_schedule_expedition_mappingtemp AS c ON a.fld_id=c.fld_expedition_id 
									    WHERE c.fld_schedule_id='".$scheduleid."' AND c.fld_flag=1 AND a.fld_delstatus='0' AND b.fld_delstatus='0' order by expname");
											       
									
                        ?>
                        <div class="dragtitle">Expedition in your class (<span id="rightmoddiv"><?php echo $qryexpmap->num_rows;?></span>)</div>
                        <div class="dragWell" id="testrailvisible16">
                            <div id="list4" class="dragleftinner droptrue1">
                                <?php 
                                    
											if($qryexpmap->num_rows > 0){
												while($rowmodulemap = $qryexpmap->fetch_assoc()){
													extract($rowmodulemap);
                                                ?>
                                                <div class="draglinkright" id="list4_<?php echo $expid; ?>" title="<?php echo $expname; ?>">
                                                    <div class="dragItemLable" id="<?php echo $expid; ?>"><?php echo $expname;?></div>
                                                    <div class="clickable" id="clck_<?php echo $expid; ?>" onclick="fn_movealllistitems('list3','list4','<?php echo $expid; ?>','exprotational');"></div>
                                                </div>
                                         <?php }
                                            }?>   
                            </div>
                        </div>
                        <div class="dragAllLink" onclick="fn_movealllistitems('list4','list3',0,'exprotational');fn_rotloadexpextendcontent(<?php echo $scheduleid.",".$licenseid.",";?>'mod')">remove all expeditions</div>
                    </div>
                </div>
            </div>
                         
                            
                                      
    <?php
	}
        
        
        
        
        /*--- Blocked students  ---*/
        if($oper=="blockstudents" and $oper!='')
        {
                $licenseid = isset($method['licenseid']) ? $method['licenseid'] : '0';
		$scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '0';
		$studenttype = isset($method['studenttype']) ? $method['studenttype'] : '0';
                $students = isset($method['students']) ? $method['students'] : '0';
                $classid = isset($method['classid']) ? $method['classid'] : '0';
                $blockexpedition = isset($method['blockexpedition']) ? $method['blockexpedition'] : '0';
            ?>
                  <script language="javascript" type="text/javascript">
								$(function() {
									$('div[id^="testrailvisible"]').each(function(index, element) {
										$(this).slimscroll({ /*------- Scroll for Modules Left Box ------*/
											width: '410px',
											height:'366px',
											size: '7px',
											railVisible: true,
                                                                                        alwaysVisible: true,
											allowPageScroll: false,
											railColor: '#F4F4F4',
											opacity: 1,
											color: '#d9d9d9',
                                                                                         wheelStep: 1
										});
									});
									
									/* drag and sort for the first left box - Teachers */	
									
									
									$("#list25").sortable({
										connectWith: ".droptrue1",
										dropOnEmpty: true,
										items: "div[class='draglinkleft']",
										receive: function(event, ui) {
											$("div[class=draglinkright]").each(function(){ 
												if($(this).parent().attr('id')=='list25'){
													fn_movealllistitems('list25','list26',$(this).children(":first").attr('id'));
												}
											});
										}
									});
								
									$( "#list26" ).sortable({
										connectWith: ".droptrue1",
										dropOnEmpty: true,
										receive: function(event, ui) {
											$("div[class=draglinkleft]").each(function(){ 
												if($(this).parent().attr('id')=='list26'){
													fn_movealllistitems('list25','list26',$(this).children(":first").attr('id'));
												}
											});
										}
									});
								});
                            </script> 
                               
                            
                            
                            <div class='row rowspacer'>
                                <div class='six columns'>
                                    <div class="dragndropcol">
                                    <?php
                                                                    if($students=='' or $studenttype==1)
                                                                    {
                                                                        $cond = "SELECT fld_studentid 
                                                                                    FROM itc_class_rotation_expblockstudent 
                                                                                    WHERE fld_scheduleid='".$scheduleid."' AND fld_expeditionid='".$blockexpedition."' AND fld_flag='1'";
                                                                        
                                                                       
                                                                        
                                                                        $qrystudent= $ObjDB->QueryObject("SELECT a.fld_id AS studentid, CONCAT(a.fld_fname,' ',a.fld_lname) AS sname,a.fld_username as username
												 								  FROM itc_class_student_mapping AS b LEFT JOIN itc_user_master AS a  ON a.fld_id=b.fld_student_id  
																				  WHERE b.fld_class_id='".$classid."' AND b.fld_flag='1' AND b.fld_student_id NOT IN(".$cond.") 
																				  ORDER BY a.fld_lname");
                                                                    }
                                                                    else
                                                                    {
                                                                        $cond = "SELECT fld_studentid 
                                                                                    FROM itc_class_rotation_expblockstudent 
                                                                                    WHERE fld_scheduleid='".$scheduleid."' AND fld_expeditionid='".$blockexpedition."' AND fld_flag='1'";
                                                                        
                                                                        $qrystudent= $ObjDB->QueryObject("SELECT fld_id AS studentid, CONCAT(fld_fname,' ',fld_lname) AS sname,fld_username as username
												 								  FROM itc_user_master  
																				  WHERE fld_id IN(".$students.") AND fld_id NOT IN(".$cond.") 
																				  ORDER BY fld_lname");
                                                                    }
									?>
                                    	<div class="dragtitle">   &nbsp;&nbsp;&nbsp;Students available (<span id="blockstudentleftdiv"><?php echo $qrystudent->num_rows;?></span>)</div>
                                        <div class="draglinkleftSearch" id="s_list25" >
                                            <dl class='field row'>
                                                <dt class='text'>
                                                    <input placeholder='Search' type='text' id="list_25_search" name="list_25_search" onKeyUp="search_list(this,'#list25');" />
                                                </dt>
                                            </dl>
                                        </div>
                                        <div class="dragWell" id="testrailvisible25" >
                                            <div id="list25" class="dragleftinner droptrue1">
												<?php 
                                                if($qrystudent->num_rows > 0){
													while($rowqryclassstudentmap = $qrystudent->fetch_assoc()){
														extract($rowqryclassstudentmap);
														?>
														<div class="draglinkleft" id="list25_<?php echo $studentid; ?>" >
                                                            <div class="dragItemLable tooltip" id="<?php echo $studentid; ?>" title="<?php echo $username;?>"><?php echo $sname; ?></div>
                                                            <div class="clickable" id="clck_<?php echo $studentid;?>" onclick="fn_movealllistitems('list25','list26',<?php echo $studentid;?>);"></div>
														</div> 
													<?php }
                                                }?>    
                                            </div>
                                        </div>
                                   		<div class="dragAllLink"  onclick="fn_movealllistitems('list25','list26',0);">add all Students</div>
                                    </div>
                                </div>
                                
                                <div class='six columns'>
                                    <div class="dragndropcol">
                                    <?php                                   if($students=='' or $studenttype==1)
                                                                            {
										$qryclassstudentmap=$ObjDB->QueryObject("SELECT a.fld_id AS studentid, CONCAT(a.fld_fname,' ',a.fld_lname) AS sname,a.fld_username as username
                                                                                                                            FROM itc_user_master AS a LEFT JOIN itc_class_rotation_expblockstudent AS b ON a.fld_id=b.fld_studentid 
																					WHERE b.fld_classid='".$classid."' AND b.fld_scheduleid='".$scheduleid."' AND b.fld_expeditionid='".$blockexpedition."'  AND b.fld_flag=1 AND a.fld_activestatus='1' AND a.fld_delstatus='0'");
                                                                            }
                                                                            else
                                                                            {
                                                                                $qryclassstudentmap=$ObjDB->QueryObject("SELECT a.fld_id AS studentid, CONCAT(a.fld_fname,' ',a.fld_lname) AS sname,a.fld_username as username
                                                                                                                            FROM itc_user_master AS a LEFT JOIN itc_class_rotation_expblockstudent AS b ON a.fld_id=b.fld_studentid 
																					WHERE b.fld_classid='".$classid."' AND b.fld_scheduleid='".$scheduleid."' AND b.fld_expeditionid='".$blockexpedition."' AND b.fld_studentid IN(".$students.") AND b.fld_flag=1 AND a.fld_activestatus='1' AND a.fld_delstatus='0'");
                                                                            }
									?>
                                    	<div class="dragtitle">Blocked Students  (<span id="blockstudentrightdiv"><?php echo $qryclassstudentmap->num_rows;?></span>)</div> 
                                        <div class="draglinkleftSearch" id="s_list26" >
                                            <dl class='field row'>
                                                <dt class='text'>
                                                    <input placeholder='Search' type='text' id="list_26_search" name="list_26_search" onKeyUp="search_list(this,'#list26');" />
                                                </dt>
                                            </dl>
                                        </div>
                                        <div class="dragWell" id="testrailvisible26">
                                            <div id="list26" class="dragleftinner droptrue1">
                                            <?php 
                                            if($qryclassstudentmap->num_rows > 0){
												while($rowsqry = $qryclassstudentmap->fetch_assoc()){
													extract($rowsqry);
													?> 
													<div class="draglinkright" id="list26_<?php echo $studentid; ?>">
                                                        <div class="dragItemLable tooltip" id="<?php echo $studentid; ?>" title="<?php echo $username;?>"><?php echo $sname;?></div>
                                                        <div class="clickable" id="clck_<?php echo $studentid; ?>" onclick="fn_movealllistitems('list25','list26',<?php echo $studentid; ?>);">
                                                        </div>
													</div>
												<?php }
                                            }?>
                                            </div>
                                        </div>
                                    	<div class="dragAllLink" onclick="fn_movealllistitems('list26','list25',0);">remove all Students</div>
            <?php
        }
        
        
        /*--- Blocked Expeditions  ---*/
	if($oper=="blockexpeditions" and $oper!='')
	{
               
                $licenseid = isset($method['licenseid']) ? $method['licenseid'] : '0';
		$scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '0';
                $expeditionlist = isset($method['expeditions']) ? $method['expeditions'] : '0';
                $blockmodid=array();
                if($scheduleid!='0')
                {
                   $qry = $ObjDB->QueryObject("SELECT a.fld_expeditionid AS sexpid
								FROM itc_class_rotation_expblockstudent AS a 
								WHERE a.fld_scheduleid='".$scheduleid."' AND a.fld_flag='1' group by a.fld_expeditionid");			
	
                        if($qry->num_rows>0)
                        {
                            while($row=$qry->fetch_assoc())
                            {
                                extract($row); 
                                $blockmodid[]=$sexpid;
                        }
                }
                }
                
        ?>
            <div class='row rowspacer'>
            <div class='six columns'>
               Select Block Expeditions
            <dl class='field row'> 
            <dt class="dropdown" style="width:300px;">     
            <div class="selectbox">
                <input type="hidden" name="selectblockexpedition" id="selectblockexpedition" value="" onchange="fn_expblockstudent();">
                <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                    <span class="selectbox-option input-medium" data-option=" ">Select Expedition</span>
                    <b class="caret1"></b>
                </a>
                <div class="selectbox-options">
                    <input type="text" class="selectbox-filter" placeholder="Search Module" value="">
                    <ul role="options">
                        <?php 
                      if($expeditionlist!='')
                      {
                         
                             $qryexpedition=$ObjDB->QueryObject("SELECT a.fld_id as id, CONCAT(a.fld_exp_name,' ',b.fld_version) as expname
							                          FROM itc_exp_master AS a
							                               LEFT JOIN itc_exp_version_track AS b ON b.fld_exp_id=a.fld_id
							                               LEFT JOIN itc_license_exp_mapping AS c ON a.fld_id=c.fld_exp_id 
													  WHERE c.fld_license_id='".$licenseid."'  AND a.fld_delstatus='0' AND c.fld_flag='1' AND b.fld_delstatus='0' AND a.fld_id IN(".$expeditionlist.") 
													  GROUP BY a.fld_id ORDER BY expname");
                        
                        
                         
                        if($qryexpedition->num_rows > 0)
                        {
                          
                          while($rowsqry = $qryexpedition->fetch_assoc())
                          {
                              extract($rowsqry);
                               
                              if(in_array($id,$blockmodid))
                              {
                                  $block="true";
                              }
                              else
                              {
                                  $block="false";
                              }
                              
                              ?>
                           
                            <li><a tabindex="-1" href="#" data-option="<?php echo $id;?>"  title="<?php echo $expname;?>" class="tooltip"><?php echo $expname; if($block=="true"){ echo " / Block";}?> </a></li>
                        <?php 
                        }
                        }
                       
                      }
                       else
                       {
                          echo "No Records";
                       }
                        ?>       
                    </ul>
                </div>
            </div>
            </dt>
           </dl>
           </div>
           </div>
                 
        <?php
        }
        
    /* Expedition Extend content */
        
    if($oper == "loadextendcontent" and $oper != "")
    {
        $list4 = isset($method['list4']) ? $method['list4'] : '';
        $licenseid = isset($method['licenseid']) ? $method['licenseid'] : '';
        $sid = isset($method['scheduleid']) ? $method['scheduleid'] : '0';
    ?>
    <div class='span10 offset1'>
        <table class='table table-hover table-striped table-bordered'>
            <thead class='tableHeadText'>
                <tr>
                    <th>Expedition Name</th>
                    <th class='centerText'>Materials List</th>                    
                </tr>
            </thead>
            <tbody>
                <?php 
                    $exptype=15;
                    if($list4[0]!='')
                    {
                            $count=0;
                    for($i=0;$i<sizeof($list4);$i++)
		    {
                        
                        $expid=$list4[$i];
			$expednname = $ObjDB->SelectSingleValue("SELECT fld_exp_name FROM itc_exp_master WHERE fld_id='".$expid."'");
			$texname = "Select Extend Content";
			$tablename="itc_exp_extendmaterials_master";
                        

		$selectext=$ObjDB->QueryObject("SELECT b.fld_ext_id AS texid,a.fld_extend_text as texname FROM ".$tablename." AS a 
                                                    LEFT JOIN itc_class_expedition_extcontent_mapping AS b ON a.fld_id=b.fld_ext_id 
                                                    WHERE b.fld_schedule_id='".$sid."' AND b.fld_exp_id IN(".$expid.") AND b.fld_active='1' AND a.fld_delstatus='0'");
									 
			$getcontent = $ObjDB->QueryObject("SELECT fld_id AS exid,fld_extend_text AS extname FROM ".$tablename." 
                                                                WHERE fld_exp_id='".$expid."' AND fld_created_by='".$uid."' AND fld_delstatus='0'
                                                                UNION ALL
                                                                SELECT a.fld_id AS exid,a.fld_extend_text AS exname FROM ".$tablename." AS a 
                                                                LEFT JOIN itc_license_extcontent_mapping AS b ON a.fld_id=b.fld_ext_id WHERE 
                                                                b.fld_license_id='".$licenseid."' AND b.fld_module_id IN(".$expid.") AND b.fld_type='".$exptype."' 
                                                                AND b.fld_active='1' AND a.fld_delstatus='0'"); 
					
						
			if($selectext->num_rows>0){
                                $res = $selectext->fetch_assoc();
                                extract($res);
                        }											 
						
					if($getcontent->num_rows>0)
					{
						$count++;
					?>
				<tr>
					<td><?php echo $expednname; ?></td>
					<td>									
						<div id="clspass">   
							<dl class='field row'>
								<div class="selectbox">
									<input type="hidden" name="exid" id="exid_<?php echo $texid;?>" value="<?php echo $texid."~17~".$expid;?>">
									<a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
										<span class="selectbox-option input-medium" data-option="" style="width:97%"><?php echo $texname;?></span>
										<b class="caret1"></b>
									</a>
									<div class="selectbox-options">
										<input type="text" class="selectbox-filter" placeholder="Search Materials">
										<ul role="options" style="width:100%">
										   <?php 
												while($res = $getcontent->fetch_assoc()){
													extract($res);?>
													<li><a tabindex="-1" href="#" data-option="<?php echo $exid."~17~".$expid;?>"><?php echo $extname; ?></a></li>
													<?php
												}?>      
										</ul>
									</div>
								</div> 
							</dl>
						</div>
					</td>
				</tr>
				<?php 
				}
                        }
                    }
                        
			if($count==0)
		        {
			?>
			<tr>
				<td colspan="2">
					No records found
				</td>
			</tr>
		 <?php
			}
			?>
                               
            </tbody>
        </table>
    </div>
 <?php   
}


/* New Extend content of the expeditions created by chandru start line created date 05-01-2016 */
if($oper == "loadexpextendcontent1" and $oper != ""){		
	
	$list4 = isset($method['list4']) ? $method['list4'] : '';
	$licenseid = isset($method['licenseid']) ? $method['licenseid'] : '';
	$sid = isset($method['scheduleid']) ? $method['scheduleid'] : '0';
	$list4=explode(",",$list4);
	$flag=0;	
	
	?>
    <div class='span10 offset1 <?php if($flag==1){?>dim<?php } ?>'>
        <table id="rotextendcontent" class='table table-hover table-striped table-bordered'>
            <thead class='tableHeadText'>
                <tr>
                    <th>Expedition Name</th>
                    <th class='centerText'>Extend Content List</th>                    
                </tr>
            </thead>
            <tbody>
                <?php 
				   if($list4[0] != '') {
					   $count=0;
						for($i=0;$i<sizeof($list4);$i++)
						{
							$templist = explode('-',$list4[$i]);
							$type=$templist[1];							
							
							$expid = $templist[0];
													
							$expednname = $ObjDB->SelectSingleValue("SELECT fld_exp_name FROM itc_exp_master WHERE fld_id='".$expid."'");
							
							$texname = "Select Extend Content";
							$extcount='0';
							$moduletype=17;								
								
							$tablename="itc_exp_extendtext_master";
								
								$selectext=$ObjDB->QueryObject("SELECT b.fld_exp_id AS texid,a.fld_extend_text as textname,b.fld_select_all AS selectall FROM ".$tablename." AS a 
                                                            LEFT JOIN itc_class_indasexpeditionschedule_expextcontent_mapping AS b ON a.fld_id=b.fld_ext_id 
                                                            WHERE b.fld_schedule_id='".$sid."' AND b.fld_exp_id='".$expid."' AND b.fld_active='1' AND a.fld_delstatus='0'");
														
								$getcontent = $ObjDB->QueryObject("SELECT fld_id AS exid,fld_extend_text AS extname FROM ".$tablename." 
															WHERE fld_exp_id='".$expid."' AND fld_created_by='".$uid."' AND fld_delstatus='0'
															UNION ALL
															SELECT a.fld_id AS exid,a.fld_extend_text AS exname FROM ".$tablename." AS a 
															LEFT JOIN itc_license_extcontent_mapping AS b ON a.fld_id=b.fld_ext_id WHERE 
															b.fld_license_id='".$licenseid."' AND b.fld_module_id='".$expid."' AND b.fld_type='".$moduletype."' 
															AND b.fld_active='1' AND a.fld_delstatus='0'"); 
																  
																  
								if($selectext->num_rows>0){
                                                                    $cut=0;
                                                                    while($res = $selectext->fetch_assoc())
                                                                    {
									extract($res);
                                                                        if($selectall=='0')
                                                                        {
                                                                            if($cut=='0'){
                                                                                $texname=$textname;
								}
                                                                            else if($cut>='3'){
                                                                                 $texname=$texname."...";
                                                                            }
                                                                            else{
                                                                                $texname=$texname.",".$textname;
                                                                            }
                                                                            $cut++;
                                                                        }
                                                                        else{
                                                                            $texname='Select all';
                                                                        }
																	 
                                                                    }
								}
									
                                                                
																	 
									
								if($getcontent->num_rows>0)
								{
									$count++;
								?>
							<tr id="exc<?php echo $expid;?>">
                            	<td><?php echo $expednname; ?></td>
                                <td>									
                                    <div id="clspass">   
                                        <dl class='field row'>
                                            <div class="selectbox">
                                                <input type="hidden" name="exid_<?php echo $expid;?>" id="exid_<?php echo $expid;?>" value="<?php echo $texid."~".$templist[1]."~".$expid;?>">
                                                <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                                                    <span class="selectbox-option input-medium" data-option="" style="width:97%" ><div id="expname_<?php echo $expid;?>"><?php echo $texname;?></div></span>
                                                    <b class="caret1"></b>
                                                </a>
                                                <div class="selectbox-options">
                                                    <input type="text" class="selectbox-filter" placeholder="Search Class">
                                                    <ul role="options" style="width:100%">
												   			  <li><span onclick="fn_selectallexpsc(<?php echo $expid; ?>);">Select All</span></li>
															   <?php 
                                                                while($res = $getcontent->fetch_assoc())
                                                                {
                                                                    extract($res);
																	                                                                  
                                                                        $extcount = $ObjDB->SelectSingleValue("SELECT count(b.fld_exp_id) FROM ".$tablename." AS a 
																												LEFT JOIN itc_class_indasexpeditionschedule_expextcontent_mapping AS b ON a.fld_id=b.fld_ext_id 
																												WHERE b.fld_schedule_id='".$sid."' AND b.fld_exp_id='".$expid."' AND b.fld_active='1' AND a.fld_delstatus='0'");
                                                                        
                                                                        ?>
                                                                    <li><input type="checkbox" <?php if($extcount!='0' && $selectall!='1'){ ?> checked="checked"<?php } ?> name="mod_<?php echo $exid."_".$expid;?>" class="ads_Checkbox_<?php echo $expid;?>" value="<?php echo $exid."_".$expid."_".$extname;?>" id="mod_<?php echo $exid."_".$expid;?>" onclick="fn_fillnameforexpsc(<?php echo $i; ?>,<?php echo $expid; ?>);">&nbsp;<?php echo $extname; ?></li>
                                                                        <?php  
                                                                   
                                                                } ?>      
                                                    </ul>
                                                </div>
                                               
                                                
                                            </div> 
                                        </dl>
                                    </div>
                                    <input type="hidden" name="selectallexp_<?php echo $expid.",".$licenseid; ?>" id="selectallexp_<?php echo $expid; ?>" value="1" /> 
                                    <input type="hidden" name="excflag" id="excflag" value="1" />
								</td>
                            </tr>
							<?php 
							}
						}
				   }
					
					if($count==0)
					{
				 ?>
                 	<tr>
                    	<td colspan="2">
                        	No records found
                        </td>
                    </tr>
                 <?php
					}
					?>
                               
            </tbody>
        </table>
    </div>
    <?php 
}

/* New Extend content of the expeditions created by chandru end line */


if($oper=="blockmodstudents" and $oper!='')
{

        $scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '0';
        $classid = isset($method['classid']) ? $method['classid'] : '0';
        $blockmodule = isset($method['blockmodule']) ? $method['blockmodule'] : '0';
        $blockstudents = isset($method['students']) ? $method['students'] : '0';
        if($blockmodule=='')
        {
            $blockmodule='0';
        }

        $blockstudents = explode(',',$blockstudents);

        /* Block student mapping start */

                $ObjDB->NonQuery("UPDATE itc_class_rotation_expblockstudent SET fld_flag='0',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' WHERE fld_scheduleid='".$scheduleid."' AND fld_expeditionid='".$blockmodule."'");

                if($blockstudents[0]>0)
                {
                    for($i=0;$i<sizeof($blockstudents);$i++)
                    {

                            $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_class_rotation_expblockstudent WHERE fld_scheduleid='".$scheduleid."' AND  fld_expeditionid='".$blockmodule."'  AND fld_studentid='".$blockstudents[$i]."'");
                            if($cnt==0)
                            {

                                    $ObjDB->NonQuery("INSERT INTO itc_class_rotation_expblockstudent(fld_classid,fld_scheduleid,fld_expeditionid,fld_studentid,fld_flag,fld_createddate,fld_createdby) VALUES ('".$classid."','".$scheduleid."','".$blockmodule."','".$blockstudents[$i]."','1','".date('Y-m-d H:i:s')."','".$uid."')");
                            }
                            else
                            {
                                    $ObjDB->NonQuery("UPDATE itc_class_rotation_expblockstudent SET fld_flag='1',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' WHERE fld_scheduleid='".$scheduleid."' AND fld_studentid='".$blockstudents[$i]."' AND fld_id='".$cnt."'");
                            }
                    }
                }


                /* Block student mapping end */
}


        if($oper == "saveschedule" and $oper != '')
	{
            
		try
		{
                    
                   
		$classid = isset($method['classid']) ? $method['classid'] : '0';
		$sid = isset($method['sid']) ? $method['sid'] : '0';
		$sname = isset($method['sname']) ? $method['sname'] : '0';
		$startdate = isset($method['startdate']) ? $method['startdate'] : '0';
		$enddate = isset($method['enddate']) ? $method['enddate'] : '0';
		$scheduletype = isset($method['scheduletype']) ? $method['scheduletype'] : '0';
		$students = isset($method['students']) ? $method['students'] : '0';
		$unstudents = isset($method['unstudents']) ? $method['unstudents'] : '0';
		$studenttype = isset($method['studenttype']) ? $method['studenttype'] : '0';
		$numberofcopies = isset($method['numberofcopies']) ? $method['numberofcopies'] : '0';
		$numberofrotations = isset($method['numberofrotations']) ? $method['numberofrotations'] : '0';
		$rotationlength = isset($method['rotationlength']) ? $method['rotationlength'] : '0';
		$licenseid = isset($method['licenseid']) ? $method['licenseid'] : '0';
		$expeditions = isset($method['expeditions']) ? $method['expeditions'] : '0';
		$extid = isset($method['extids']) ? $method['extids'] : '0';
            $schflag = isset($method['schflag']) ? $method['schflag'] : '0';
			
		/***********Chandru Updated by one or more Extend Content option code start here*********/
		$expids = isset($method['expids']) ? $method['expids'] : '0';
		$selectallexpids = isset($method['selectallexpids']) ? $method['selectallexpids'] : '0';
			
		/***********Chandru Updated by one or more Extend Content option code end here*********/	
			
		$selectchkboxids = isset($method['selectchkboxids']) ? $method['selectchkboxids'] : '0';     //Mohan M     
		$selectchkboxids = explode(',',$selectchkboxids);  //Mohan M   
		
			
                $blockmodule = isset($method['blockmodule']) ? $method['blockmodule'] : '0';
                $blockstudents = isset($method['blockstudents']) ? $method['blockstudents'] : '0';
		

           
                $exptest = isset($_REQUEST['exptest']) ? $_REQUEST['exptest'] : '0';
                $desttest = isset($_REQUEST['desttest']) ? $_REQUEST['desttest'] : '0';
                $tasktest = isset($_REQUEST['tasktest']) ? $_REQUEST['tasktest'] : '0';
                $restest = isset($_REQUEST['restest']) ? $_REQUEST['restest'] : '0';

           

                if($blockmodule=='')
                {
                    $blockmodule='0';
                }
		
		$students = explode(',',$students);
		$expeditions = explode(',',$expeditions);
		$unstudents = explode(',',$unstudents);
		$extid = explode(',',$extid);
                $blockstudents = explode(',',$blockstudents);
		
		/* chandru updated code start line */	
		$expids = explode(',',$expids);
		$selectallexpids = explode(',',$selectallexpids);
		/* chandru updated code end line */
			
	   $validate_schname=true;
	   $validate_schid=true;
	   $validate_date=true;
	
		if($sid!=0)
		{
			$validate_schid=validate_datatype($sid,'int');
			$validate_schname=validate_datas($sname,'lettersonly'); 
			$validate_date=validate_datas($startdate,'dateformat'); 
		}
		
		if($validate_schid and $validate_schname and $validate_date)
		{
                    
		
		$remainusers = $ObjDB->SelectSingleValueInt("SELECT fld_remain_users FROM itc_license_track WHERE fld_school_id='".$schoolid."' and fld_license_id='".$licenseid."' and fld_delstatus='0' and fld_user_id='".$indid."' AND '".date("Y-m-d")."' BETWEEN fld_start_date AND fld_end_date");
		
		
		
		if($studenttype==1){
			/*---------checking the license for student----------------------*/				
			$count=0;
			$qry = $ObjDB->QueryObject("SELECT fld_student_id FROM itc_class_student_mapping WHERE fld_class_id='".$classid."' and fld_flag='1'");
			if($qry->num_rows>0){
				$students=array();
				while($res=$qry->fetch_assoc())
				{
					extract($res);
					$students[]=$fld_student_id;
					$check = $ObjDB->SelectSingleValueInt("SELECT COUNT(*) FROM itc_license_assign_student AS a 
					         LEFT JOIN itc_user_master AS b ON a.fld_student_id=b.fld_id 
							 WHERE a.fld_license_id='".$licenseid."' AND a.fld_student_id='".$fld_student_id."' AND a.fld_flag='1' AND b.fld_delstatus='0'");
					
					if($check==0)
					{
						$count++;
					}
				}
			}
		}
		else{
			$count=0;
			$add=0;			
			for($i=0;$i<sizeof($students);$i++)
			{
				$check = $ObjDB->SelectSingleValueInt("SELECT COUNT(*) FROM itc_license_assign_student AS a 
				         LEFT JOIN itc_user_master AS b ON a.fld_student_id=b.fld_id 
						 WHERE a.fld_license_id='".$licenseid."' AND a.fld_student_id='".$students[$i]."' AND a.fld_flag='1' AND b.fld_delstatus='0'");
				
				if($check==0)
				{
					$count++;
				}
			}
			
			$remainusers = $ObjDB->SelectSingleValueInt("SELECT fld_remain_users FROM itc_license_track WHERE fld_school_id='".$schoolid."' and fld_license_id='".$licenseid."' and fld_delstatus='0' and fld_user_id='".$indid."' AND '".date("Y-m-d")."' BETWEEN fld_start_date AND fld_end_date");
			  
                        

		
			for($i=0;$i<sizeof($unstudents);$i++)
			{
				$check = $ObjDB->SelectSingleValueInt("SELECT count(*) FROM itc_license_assign_student WHERE fld_license_id='".$licenseid."' and fld_student_id='".$unstudents[$i]."' and fld_flag='1'");
				
				if($check>0)
				{
                                    
				$studentcount = $ObjDB->SelectSingleValueInt("SELECT SUM(o.cnt) FROM (SELECT COUNT(a.fld_id) AS cnt FROM itc_class_sigmath_student_mapping AS a                                 LEFT JOIN itc_class_sigmath_master AS b ON a.fld_sigmath_id=b.fld_id 
				                WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_schedule_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_schedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
					   WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                               
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_expschedule_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_expschedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
					   WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."' AND a.fld_schedule_id<>'".$sid."'
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_modexpschedule_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_modexpschedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
					   WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."' AND b.fld_license_id='".$licenseid."'
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_mission_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_mission_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
					   WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."' AND b.fld_license_id='".$licenseid."'
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_dyad_schedule_studentmapping AS a 
								LEFT JOIN itc_class_dyad_schedulemaster AS b ON a.fld_schedule_id=b.fld_id 
								WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_triad_schedule_studentmapping AS a 
								LEFT JOIN itc_class_triad_schedulemaster AS b ON a.fld_schedule_id=b.fld_id 
								WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_indassesment_student_mapping AS a 
								LEFT JOIN itc_class_indassesment_master AS b ON a.fld_schedule_id=b.fld_id 
								WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_exp_student_mapping AS a 
								LEFT JOIN itc_class_indasexpedition_master AS b ON a.fld_schedule_id=b.fld_id 
								WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_pdschedule_student_mapping AS a 
								LEFT JOIN itc_class_pdschedule_master AS b ON a.fld_pdschedule_id=b.fld_id 
								WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                        
                                     ) AS o");
					
					$ObjDB->NonQuery("UPDATE itc_class_rotation_expschedule_student_mappingtemp SET fld_flag='0',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$unstudents[$i]."'");
					
					$ObjDB->NonQuery("UPDATE itc_class_rotation_expschedulegriddet SET fld_flag='0',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$unstudents[$i]."'");
					
					if($studentcount==0){
						$add++;
						$ObjDB->NonQuery("UPDATE itc_license_assign_student SET fld_flag='0',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' WHERE fld_license_id='".$licenseid."' AND fld_student_id='".$unstudents[$i]."' ");
					}
				}
			}
		}
                
               
		
		$assignedstudents = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_license_assign_student WHERE fld_license_id='".$licenseid."' AND fld_flag='1' AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."'");
		
		$totalusers = $ObjDB->SelectSingleValueInt("SELECT fld_no_of_users FROM itc_license_track WHERE fld_school_id='".$schoolid."' and fld_license_id='".$licenseid."' and fld_delstatus='0' and fld_user_id='".$indid."' AND '".date("Y-m-d")."' BETWEEN fld_start_date AND fld_end_date");
		
		$totalremain = $remainusers-$count;
		if($totalusers>=($assignedstudents+$count)){
			$flag=1;
		}		
		else{	
			$flag=0;
		}
                
              
		if($flag==1) //if student user availale for license
		{ 
                    
			if($sid!=0)
			{
				$ObjDB->NonQuery("UPDATE itc_class_rotation_expschedule_mastertemp SET fld_schedule_name='".$ObjDB->EscapeStrAll($sname)."',fld_student_type='".$studenttype."',fld_startdate='".date("Y-m-d",strtotime($startdate))."',fld_rotationlength='".$rotationlength."',fld_numberofcopies='".$numberofcopies."',fld_numberofrotations='".$numberofrotations."',fld_updatedby='".$uid."',fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$sid."'");
			}
			else
			{
                            
                           
				
				$ObjDB->NonQuery("insert into itc_class_rotation_expschedule_mastertemp (fld_class_id,fld_license_id,fld_schedule_name,fld_scheduletype,fld_student_type,fld_startdate,fld_numberofcopies,fld_numberofrotations,fld_rotationlength,fld_created_date,fld_createdby) values('".$classid."','".$licenseid."','".$ObjDB->EscapeStrAll($sname)."','".$scheduletype."','".$studenttype."','".date("Y-m-d",strtotime($startdate))."','".$numberofcopies."','".$numberofrotations."','".$rotationlength."','".date("Y-m-d H:i:s")."','".$uid."')");
				
				$sid=$ObjDB->SelectSingleValueInt("SELECT MAX(fld_id) FROM itc_class_rotation_expschedule_mastertemp");
                                
                                
			}
                        
                        /***********Chandru Updated by one or more Extend Content option code start here*********/
			if($scheduletype==17)
			{
				
				 $ObjDB->NonQuery("UPDATE itc_class_indasexpeditionschedule_expextcontent_mapping 
											 SET fld_active='0' 
											 WHERE fld_schedule_id='".$sid."'");

				if($expids[0] != '')
				{					
					for($i=0;$i<sizeof($expids);$i++)
					{
						
						$templistmod = explode('_',$expids[$i]);												
						
						if($templistmod[0]!='' and $templistmod[0]!=0)
						{
							
							$cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_class_indasexpeditionschedule_expextcontent_mapping WHERE fld_schedule_id = '".$sid."'
																		AND fld_ext_id = '".$templistmod[0]."' AND fld_exp_id = '".$templistmod[1]."'");
							if($cnt==0)
							{
								
						 		$ObjDB->NonQuery("INSERT INTO itc_class_indasexpeditionschedule_expextcontent_mapping (fld_schedule_id,fld_ext_id,fld_active,fld_exp_id,fld_createdby,fld_createddate)
														VALUES('".$sid."','".$templistmod[0]."','1','".$templistmod[1]."','".$uid."','".date("Y-m-d H:i:s")."')");
							
							}
							else
							{
																
								$ObjDB->NonQuery("UPDATE itc_class_indasexpeditionschedule_expextcontent_mapping 
														SET fld_active='1',fld_updatedby='".$uid."',fld_updateddate='".date("Y-m-d H:i:s")."' 
														WHERE fld_schedule_id='".$sid."' AND fld_ext_id='".$templistmod[0]."' AND fld_exp_id='".$templistmod[1]."'");
							}
						}					
					}
				}				
			  

				if($selectallexpids[0] != '')
				{ /******Select All Extend Content******/
					
				   for($i=0;$i<(sizeof($selectallexpids)-1);$i++)
					{
					 	
						$selectallexpids[$i] = ltrim($selectallexpids[$i],",");
						$templistmod = explode(',',$selectallexpids[$i]);
					 
						$getcontent = $ObjDB->QueryObject("SELECT fld_id AS exid,fld_extend_text AS exname FROM itc_exp_extendtext_master
																WHERE fld_exp_id='".$templistmod[0]."' AND fld_school_id='".$schoolid."' AND fld_delstatus='0'
																UNION ALL
																SELECT a.fld_id AS exid,a.fld_extend_text AS exname FROM itc_exp_extendtext_master AS a 
																LEFT JOIN itc_license_extcontent_mapping AS b ON a.fld_id=b.fld_ext_id WHERE 
																b.fld_license_id='".$templistmod[1]."' AND b.fld_module_id='".$templistmod[0]."' AND b.fld_type='1' 
																AND b.fld_active='1' AND a.fld_delstatus='0'");
						if($getcontent->num_rows>0)
						{
							while($res = $getcontent->fetch_assoc())
							{
								extract($res);
								
								
								$cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_class_indasexpeditionschedule_expextcontent_mapping 
																			WHERE fld_schedule_id='".$sid."'  AND fld_ext_id='".$exid."'  
																			AND fld_exp_id='".$templistmod[0]."'");
								if($cnt==0)
								{
									
									$ObjDB->NonQuery("INSERT INTO itc_class_indasexpeditionschedule_expextcontent_mapping (fld_schedule_id,fld_ext_id,fld_active,fld_exp_id,fld_createdby,fld_createddate,fld_select_all)
															VALUES('".$sid."','".$exid."','1','".$templistmod[0]."','".$uid."','".date("Y-m-d H:i:s")."','1')");
								
								}
								else
								{
									$ObjDB->NonQuery("UPDATE itc_class_indasexpeditionschedule_expextcontent_mapping 
															SET fld_active='1',fld_updatedby='".$uid."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_select_all='1'
															WHERE fld_schedule_id='".$sid."' AND fld_ext_id='$exid' AND fld_exp_id='".$templistmod[0]."'");
								}

							}
						}
					}
				} /******Select All Extend Content******/
				
				
				
                                /****************Mohan M  Feb 20 2016******************/	
                                if($selectchkboxids[0] != '')
                                {
                                        $ObjDB->NonQuery("UPDATE itc_class_expmis_rubricmaster
                                                                                 SET fld_delstatus='1',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' 
                                                                                        WHERE fld_schedule_id='".$sid."'");


                                        for($m=0;$m<sizeof($selectchkboxids);$m++)
                                        {
                                                $templistrubric = explode('~',$selectchkboxids[$m]);

                                                if($templistrubric[0]!='' and $templistrubric[1]!=0)
                                                {

                                                        $classname = $ObjDB->SelectSingleValue("SELECT fld_class_name FROM itc_class_master WHERE fld_id='".$classid."' AND fld_delstatus='0'");
                                                        $schedulename = $ObjDB->SelectSingleValue("SELECT fld_schedule_name FROM itc_class_rotation_expschedule_mastertemp WHERE fld_id='".$sid."' AND fld_scheduletype='17' AND fld_delstatus='0'");
                                                        $expname = $ObjDB->SelectSingleValue("SELECT fld_exp_name FROM itc_exp_master WHERE fld_id='".$templistrubric[1]."' AND fld_delstatus='0'");
                                                        $rubricname = $ObjDB->SelectSingleValue("SELECT fld_rub_name FROM itc_exp_rubric_name_master WHERE fld_id='".$templistrubric[0]."' AND fld_delstatus='0'");

                                                        $cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_class_expmis_rubricmaster WHERE fld_schedule_id = '".$sid."'
                                                                                                        AND fld_rubric_id = '".$templistrubric[0]."' AND fld_expmisid = '".$templistrubric[1]."' AND fld_schedule_type='17'");

                                                        if($cnt==0)
                                                        {
                                                                $ObjDB->NonQuery("INSERT INTO itc_class_expmis_rubricmaster (fld_class_id, fld_schedule_id, fld_schedule_type, fld_expmisid, fld_rubric_id, fld_created_by, fld_created_date,fld_class_name,fld_schedule_name,fld_expmisname,fld_rubric_name)
                                                                                                VALUES('".$classid."','".$sid."','17','".$templistrubric[1]."','".$templistrubric[0]."','".$uid."','".date("Y-m-d H:i:s")."', '".$ObjDB->EscapeStrAll($classname)."', '".$ObjDB->EscapeStrAll($schedulename)."','".$ObjDB->EscapeStrAll($expname)."','".$ObjDB->EscapeStrAll($rubricname)."')");
                                                   }
                                                        else
                                                        {
                                                                $ObjDB->NonQuery("UPDATE itc_class_expmis_rubricmaster 
                                                                                        SET fld_delstatus='0',fld_updated_by='".$uid."',fld_updated_date='".date("Y-m-d H:i:s")."',  
                                                                                        fld_class_name='".$ObjDB->EscapeStrAll($classname)."',fld_schedule_name='".$ObjDB->EscapeStrAll($schedulename)."',fld_expmisname='".$ObjDB->EscapeStrAll($expname)."',fld_rubric_name='".$ObjDB->EscapeStrAll($rubricname)."'
                                                                                        WHERE fld_schedule_id='".$sid."' AND fld_rubric_id='".$templistrubric[0]."' AND fld_expmisid='".$templistrubric[1]."'");
                                                        }
                                                }					
                                        }
                                }
                                else
                                {
                                        $ObjDB->NonQuery("UPDATE itc_class_expmis_rubricmaster
                                                                                 SET fld_delstatus='1',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' 
                                                                                        WHERE fld_schedule_id='".$sid."'");

                                }
/****************Mohan M  Feb 20 2016******************/
				
				
				
		   }
			
		   /***********Chandru Updated by one or more Extend Content option code End here*********/
                        
                        
                        
                    /***********Inline test Starts - Karthi*********/
                    $expval = explode("~",$exptest);
                        
                    if($exptest !=''){
                        if($scheduletype=='17'){
                            $scheduletype=19;
                        }
                         for($i=0;$i<sizeof($expval);$i++)
                         {
                             $expval1 = explode("_",$expval[$i]);
                             $texpid = $expval1[0];
                             $tdestid = $expval1[1];
                             $ttaskid = $expval1[2];
                             $tresid = $expval1[3];
                             $tpreid = $expval1[4];
                             $tpostid = $expval1[5];
                             if($tpreid=="undefined" or $tpreid==""){ $tpreid=0;}
                             if($tpostid=="undefined" or $tpostid==""){$tpostid=0;}
                        
                             $exptestcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(*) from itc_exp_ass where fld_class_id='".$classid."' and fld_sch_id='".$sid."' and fld_schtype_id='".$scheduletype."' 
                                                                      and fld_texpid='".$texpid."' and fld_tdestid='0' and fld_ttaskid='0' and fld_tresid='0'");
                             if($exptestcount == 0)
                             {
                                  $expqry = $ObjDB->NonQuery("INSERT into itc_exp_ass (fld_class_id,fld_sch_id,fld_schtype_id,fld_texpid,fld_tdestid,fld_ttaskid,fld_tresid,fld_pretest,fld_posttest,fld_created_date,fld_created_by,fld_school_id,fld_user_id) 
                                                                                                                              VALUES('".$classid."','".$sid."','".$scheduletype."','".$texpid."','".$tdestid."','".$ttaskid."','".$tresid."','".$tpreid."','".$tpostid."','".date("Y-m-d H:i:s")."','".$uid."','".$schoolid."','".$indid."')");
			
                             }
                              else {
                                  $ObjDB->NonQuery("UPDATE itc_exp_ass set fld_pretest='".$tpreid."', fld_posttest='".$tpostid."', fld_updated_date='".date("Y-m-d H:i:s")."', fld_updated_by='".$uid."', fld_school_id='".$schoolid."', fld_user_id='".$indid."'
                                                    where fld_class_id='".$classid."' and fld_sch_id='".$sid."' and fld_schtype_id='".$scheduletype."' and fld_texpid='".$texpid."' and 
                                                    fld_tdestid='0' and fld_ttaskid='0' and fld_tresid='0'");                       
                              }
                         }
                    }
			
                    if($desttest !=''){
                        if($scheduletype=='17'){
                            $scheduletype=19;
                        }
                        $destval = explode("~",$desttest);
                         for($i=0;$i<sizeof($destval);$i++)
                         {
                             $destval1 = explode("_",$destval[$i]);
                             $texpid = $destval1[0];
                             $tdestid = $destval1[1];
                             $ttaskid = $destval1[2];
                             $tresid = $destval1[3];
                             $tpreid = $destval1[4];
                             $tpostid = $destval1[5];
                             if($tpreid=="undefined" or $tpreid==""){ $tpreid=0;}
                             if($tpostid=="undefined" or $tpostid==""){ $tpostid=0;}
                             $desttestcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(*) from itc_exp_ass where fld_class_id='".$classid."' and fld_sch_id='".$sid."' and fld_schtype_id='".$scheduletype."' 
                                                                      and fld_texpid='".$texpid."' and fld_tdestid='".$tdestid."' and fld_ttaskid='0' and fld_tresid='0'");
                             if($desttestcount == 0)
                             {
                                  $destqry = $ObjDB->NonQuery("INSERT into itc_exp_ass (fld_class_id,fld_sch_id,fld_schtype_id,fld_texpid,fld_tdestid,fld_ttaskid,fld_tresid,fld_pretest,fld_posttest,fld_created_date,fld_created_by,fld_school_id,fld_user_id) 
                                                                                                                              VALUES('".$classid."','".$sid."','".$scheduletype."','".$texpid."','".$tdestid."','".$ttaskid."','".$tresid."','".$tpreid."','".$tpostid."','".date("Y-m-d H:i:s")."','".$uid."','".$schoolid."','".$indid."')");
                             }
                             else
                             {
                        
                                 $ObjDB->NonQuery("UPDATE itc_exp_ass set fld_pretest='".$tpreid."', fld_posttest='".$tpostid."', fld_updated_date='".date("Y-m-d H:i:s")."', fld_updated_by='".$uid."', fld_school_id='".$schoolid."', fld_user_id='".$indid."'
                                                    where fld_class_id='".$classid."' and fld_sch_id='".$sid."' and fld_schtype_id='".$scheduletype."' and fld_texpid='".$texpid."' and 
                                                    fld_tdestid='".$tdestid."' and fld_ttaskid='0' and fld_tresid='0'");
                             }
                         }
                    }

                    if($tasktest!=''){
                        if($scheduletype=='17'){
                            $scheduletype=19;
                        }
                        $taskval = explode("~",$tasktest);
                         for($i=0;$i<sizeof($taskval);$i++)
                         {
                             $taskval1 = explode("_",$taskval[$i]);
                             $texpid = $taskval1[0];
                             $tdestid = $taskval1[1];
                             $ttaskid = $taskval1[2];
                             $tresid = $taskval1[3];
                             $tpreid = $taskval1[4];
                             $tpostid = $taskval1[5];

                             if($tpreid=="undefined" or $tpreid==""){ $tpreid=0;}
                             if($tpostid=="undefined" or $tpostid==""){$tpostid=0;}
                             $tasktestcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(*) from itc_exp_ass where fld_class_id='".$classid."' and fld_sch_id='".$sid."' and fld_schtype_id='".$scheduletype."' 
                                                                      and fld_texpid='".$texpid."' and fld_tdestid='".$tdestid."' and fld_ttaskid='".$ttaskid."' and fld_tresid='0'");
                             if($tasktestcount == 0)
                             {
                                  $taskqry = $ObjDB->NonQuery("INSERT into itc_exp_ass (fld_class_id,fld_sch_id,fld_schtype_id,fld_texpid,fld_tdestid,fld_ttaskid,fld_tresid,fld_pretest,fld_posttest,fld_created_date,fld_created_by,fld_school_id,fld_user_id) 
                                                                                                                              VALUES('".$classid."','".$sid."','".$scheduletype."','".$texpid."','".$tdestid."','".$ttaskid."','".$tresid."','".$tpreid."','".$tpostid."','".date("Y-m-d H:i:s")."','".$uid."','".$schoolid."','".$indid."')");
                             }
                             else
                             {
                                 $ObjDB->NonQuery("UPDATE itc_exp_ass set fld_pretest='".$tpreid."', fld_posttest='".$tpostid."', fld_updated_date='".date("Y-m-d H:i:s")."', fld_updated_by='".$uid."', fld_school_id='".$schoolid."', fld_user_id='".$indid."'
                                                    where fld_class_id='".$classid."' and fld_sch_id='".$sid."' and fld_schtype_id='".$scheduletype."' and fld_texpid='".$texpid."' and 
                                                    fld_tdestid='".$tdestid."' and fld_ttaskid='".$ttaskid."' and fld_tresid='0'");
                             }
                         }
                     }
                     if($restest){
                        if($scheduletype=='17'){
                            $scheduletype=19;
                        }
                         $resval = explode("~",$restest);
                         for($i=0;$i<sizeof($resval);$i++)
                         {
                             $resval1 = explode("_",$resval[$i]);
                             $texpid = $resval1[0];
                             $tdestid = $resval1[1];
                             $ttaskid = $resval1[2];
                             $tresid = $resval1[3];
                             $tpreid = $resval1[4];
                             $tpostid = $resval1[5];
                             if($tpreid=="undefined" or $tpreid==""){ $tpreid=0;}
                             if($tpostid=="undefined" or $tpostid==""){$tpostid=0;}
                             $restestcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(*) from itc_exp_ass where fld_class_id='".$classid."' and fld_sch_id='".$sid."' and fld_schtype_id='".$scheduletype."' 
                                                                      and fld_texpid='".$texpid."' and fld_tdestid='".$tdestid."' and fld_ttaskid='".$ttaskid."' and fld_tresid='".$tresid."'");
                             if($restestcount == 0)
                             {
                                  $resqry = $ObjDB->NonQuery("INSERT into itc_exp_ass (fld_class_id,fld_sch_id,fld_schtype_id,fld_texpid,fld_tdestid,fld_ttaskid,fld_tresid,fld_pretest,fld_posttest,fld_created_date,fld_created_by,fld_school_id,fld_user_id) 
                                                                                                                              VALUES('".$classid."','".$sid."','".$scheduletype."','".$texpid."','".$tdestid."','".$ttaskid."','".$tresid."','".$tpreid."','".$tpostid."','".date("Y-m-d H:i:s")."','".$uid."','".$schoolid."','".$indid."')");
                             }
                              else {
                                  $ObjDB->NonQuery("UPDATE itc_exp_ass set fld_pretest='".$tpreid."', fld_posttest='".$tpostid."', fld_updated_date='".date("Y-m-d H:i:s")."', fld_updated_by='".$uid."', fld_school_id='".$schoolid."', fld_user_id='".$indid."'
                                                    where fld_class_id='".$classid."' and fld_sch_id='".$sid."' and fld_schtype_id='".$scheduletype."' and fld_texpid='".$texpid."' and 
                                                    fld_tdestid='".$tdestid."' and fld_ttaskid='".$ttaskid."' and fld_tresid='".$tresid."'");
                              }
                         }
                     }
                     /***********Inline test Ends - Karthi*********/



                     /* Block student mapping start */
                        
                        $ObjDB->NonQuery("UPDATE itc_class_rotation_expblockstudent SET fld_flag='0',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' WHERE fld_scheduleid='".$sid."' AND fld_expeditionid='".$blockmodule."'");
                            
                        if($blockstudents[0]>0)
                            {
                            for($i=0;$i<sizeof($blockstudents);$i++)
                            {
                                
                                    $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_class_rotation_expblockstudent WHERE fld_scheduleid='".$sid."' AND  fld_expeditionid='".$blockmodule."'  AND fld_studentid='".$blockstudents[$i]."'");
				if($cnt==0)
				{
                                   
                                            $ObjDB->NonQuery("INSERT INTO itc_class_rotation_expblockstudent(fld_classid,fld_scheduleid,fld_expeditionid,fld_studentid,fld_flag,fld_createddate,fld_createdby) VALUES ('".$classid."','".$sid."','".$blockmodule."','".$blockstudents[$i]."','1','".date('Y-m-d H:i:s')."','".$uid."')");
				}
				else
				{
                                            $ObjDB->NonQuery("UPDATE itc_class_rotation_expblockstudent SET fld_flag='1',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' WHERE fld_scheduleid='".$sid."' AND fld_studentid='".$blockstudents[$i]."' AND fld_id='".$cnt."'");
				}
                        }
                        }
				
				
			/* Block student mapping end */
			
			/* Schedule Module Mapping */
			
                    if($schflag!=1)
                    {
			$ObjDB->NonQuery("UPDATE itc_class_rotation_schedule_expedition_mappingtemp SET fld_flag='0',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."'");
                    }
			
			for($i=0;$i<sizeof($expeditions);$i++)
			{
				
				
				$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_class_rotation_schedule_expedition_mappingtemp WHERE fld_schedule_id='".$sid."' AND fld_expedition_id='".$expeditions[$i]."'");
				if($cnt==0)
				{
					$ObjDB->NonQuery("INSERT INTO itc_class_rotation_schedule_expedition_mappingtemp(fld_schedule_id,fld_expedition_id,fld_flag,fld_createddate,fld_createdby) VALUES ('".$sid."', '".$expeditions[$i]."','1','".date('Y-m-d H:i:s')."','".$uid."')");
				}
				else
				{
					$ObjDB->NonQuery("UPDATE itc_class_rotation_schedule_expedition_mappingtemp SET fld_flag='1',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."' AND fld_expedition_id='".$expeditions[$i]."'  AND fld_id='".$cnt."'");
				}
			}
			
			/* Schedule Module Mapping End */
			
			/* Schedule Student Mapping */
			
			$ObjDB->NonQuery("UPDATE itc_class_rotation_expschedule_student_mappingtemp SET fld_flag='0',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."'");
			
			for($i=0;$i<sizeof($students);$i++)
			{
				$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_class_rotation_expschedule_student_mappingtemp WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$students[$i]."'");
				if($cnt==0)
				{
					$ObjDB->NonQuery("INSERT INTO itc_class_rotation_expschedule_student_mappingtemp(fld_schedule_id, fld_student_id,fld_flag,fld_createddate,fld_createdby) VALUES ('".$sid."', '".$students[$i]."','1','".date('Y-m-d H:i:s')."','".$uid."')");
				}
				else
				{
					$ObjDB->NonQuery("UPDATE itc_class_rotation_expschedule_student_mappingtemp SET fld_flag='1',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$students[$i]."' AND fld_id='".$cnt."'");
				}
				
				/* Schedule Student Mapping End */
				
				//tracing student
				$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_license_assign_student WHERE fld_license_id='".$licenseid."' AND fld_student_id='".$students[$i]."'");
				if($cnt==0)
				{
					$ObjDB->NonQuery("INSERT INTO itc_license_assign_student(fld_school_id, fld_license_id, fld_student_id, fld_flag,fld_created_date,fld_created_by) VALUES ('".$schoolid."', '".$licenseid."', '".$students[$i]."', '1','".date('Y-m-d H:i:s')."','".$uid."')");
				}
				else
				{
					$ObjDB->NonQuery("UPDATE itc_license_assign_student SET fld_flag='1',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' WHERE fld_license_id='".$licenseid."' AND fld_student_id='".$students[$i]."' AND fld_id='".$cnt."'");
				}
			}
			
			//extend insert/update
			$ObjDB->NonQuery("UPDATE itc_class_expedition_extcontent_mapping 
							 SET fld_active='0' 
							 WHERE fld_schedule_id='".$sid."'");
			if($extid[0] != '') {
				for($i=0;$i<sizeof($extid);$i++)
				{
					$templist = explode('~',$extid[$i]);
					if($templist[0]!='' and $templist[0]!=0){
						$cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
															FROM itc_class_expedition_extcontent_mapping 
															WHERE fld_schedule_id='".$sid."'  AND fld_ext_id='".$templist[0]."' AND fld_schedule_type='".$templist[1]."' 
															AND fld_exp_id='".$templist[2]."'");
						if($cnt==0)
						{
							 $ObjDB->NonQuery("INSERT INTO itc_class_expedition_extcontent_mapping (fld_schedule_id,fld_ext_id,fld_active,fld_schedule_type,fld_exp_id,fld_createdby,fld_createddate)
												VALUES('".$sid."','".$templist[0]."','1','".$templist[1]."','".$templist[2]."','".$uid."','".date("Y-m-d H:i:s")."')");
						}
						else
						{
							$ObjDB->NonQuery("UPDATE itc_class_expedition_extcontent_mapping 
												SET fld_active='1',fld_updatedby='".$uid."',fld_updateddate='".date("Y-m-d H:i:s")."' 
												WHERE fld_schedule_id='".$sid."' AND fld_ext_id='".$templist[0]."' AND fld_schedule_type='".$templist[1]."' AND fld_exp_id='".$templist[2]."'");
						}
					}					
				}
			}
			
			
			
			$ObjDB->NonQuery("UPDATE itc_class_rotation_expschedule_mastertemp SET fld_updated_date='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."',fld_license_id='".$licenseid."' WHERE fld_id='".$sid."'");
			
			echo "success~".$sid;
			
			send_notification($licenseid,$schoolid,$indid);
			
		}		
                else
                {
                    echo "exceed";
		}
		}
		else
		{
			echo "fail";		
		}
		}
		catch(Exception $e)
		{
			 echo "failcatch";
		}
}



if($oper=="autoblock" and $oper!='')
{
        $scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '0';
        $expid = isset($method['expid']) ? $method['expid'] : '0';
        $stumodid=array();
        $stupoints=array();

        $moduleexplode=explode(",",$expid);
        
        for($i=0;$i<sizeof($moduleexplode);$i++)
        {
            $explodehypen=explode("-",$moduleexplode[$i]);
            if($modid=='')
            {
                $modid=$explodehypen[0];
            }
            else
            {
                $modid.=",".$explodehypen[0];
            }
        }
        
        $qryschedulestudentmap=$ObjDB->QueryObject("SELECT a.fld_id FROM itc_user_master AS a LEFT JOIN itc_class_rotation_expschedule_student_mappingtemp AS b ON a.fld_id=b.fld_student_id WHERE b.fld_schedule_id='".$scheduleid."' AND b.fld_flag=1 AND a.fld_activestatus='1' AND a.fld_delstatus='0' ");
        
        while($rowstudent=$qryschedulestudentmap->fetch_assoc())
    	{
	       extract($rowstudent);
               if($stu=='')
               {
                    $stu=$fld_id;
               }
               else
               {
                   $stu.=",".$fld_id;
               }
                        
        }
        
       
        
        $qryautoblock=$ObjDB->QueryObject("SELECT fld_exp_id as expid,fld_student_id as stuid,fld_schedule_id as sid,fld_schedule_type as schtype from itc_exp_dest_play_track where fld_exp_id in(".$expid.") and fld_student_id in (".$stu.") and fld_delstatus='0' group by fld_exp_id,fld_student_id");
        
        
        if($qryautoblock->num_rows>0)
        {
            while($rowstudent=$qryautoblock->fetch_assoc())
            {
                extract($rowstudent);
                $count=0;
                $autoid=0;
                
                    if($schtype==15)
                    {
                        $autoid=$ObjDB->SelectSingleValueInt("SELECT b.fld_id FROM itc_class_indasexpedition_master as a left join itc_class_master as b on b.fld_id=a.fld_class_id WHERE a.fld_id='".$sid."' and a.fld_delstatus='0' and b.fld_delstatus='0'");
                    }
                    else if($schtype==19)
                    {
                        $autoid=$ObjDB->SelectSingleValueInt("SELECT b.fld_id FROM itc_class_rotation_expschedule_mastertemp as a left join itc_class_master as b on b.fld_id=a.fld_class_id WHERE a.fld_id='".$sid."' and a.fld_delstatus='0' and b.fld_delstatus='0'");
                    }
                    else if($schtype==20)
                    {
                        $autoid=$ObjDB->SelectSingleValueInt("SELECT b.fld_id FROM itc_class_rotation_modexpschedule_mastertemp as a left join itc_class_master as b on b.fld_id=a.fld_class_id WHERE a.fld_id='".$sid."' and a.fld_delstatus='0' and b.fld_delstatus='0'");
                    }
                    
                    if($autoid>0)
                    {
                        $stumodid[]=$expid."-".$stuid;
                    }
                
            }
        }
            
       
        echo json_encode($stumodid);
         
         
    }
    
    
    
    /*--- save rotational table cell details  ---*/
	if($oper == "saverotation" and $oper != '')
	{
                error_reporting(E_ALL);
            ini_set('display_errors', '1');
		$classid = (isset($method['classid'])) ? $method['classid'] : 0;
		$scheduleid = (isset($method['scheduleid'])) ? $method['scheduleid'] : 0;
		$moduledet = (isset($method['moduledet'])) ? $method['moduledet'] : 0;	
		$numberofrotation = (isset($method['numberofrotation'])) ? $method['numberofrotation'] : 0;	
		$celldet = (isset($method['celldet'])) ? $method['celldet'] : 0;
		$autoblock = (isset($method['autoblock'])) ? $method['autoblock'] : 0;
		$startdate = (isset($method['startdate'])) ? $method['startdate'] : 0;
                $generatetype = (isset($method['generatetype'])) ? $method['generatetype'] : 0;
                $blockmodule = isset($method['blockmodule']) ? $method['blockmodule'] : '0';
                $blockstudents = isset($method['blockstudents']) ? $method['blockstudents'] : '0';

		$rotlength = (isset($method['rotlength'])) ? $method['rotlength'] : 0;
		$rotlength=$rotlength-1;
		$count=0;
		$moduledet=explode(",",$moduledet);
		$celldet=explode(",",$celldet);
                $blockstudents=explode(",",$blockstudents);

		$schflag=$ObjDB->SelectSingleValueInt("SELECT fld_flag FROM itc_class_rotation_expschedule_mastertemp WHERE fld_id='".$scheduleid."'");
		
		$ObjDB->NonQuery("UPDATE itc_class_rotation_expmoduledet SET fld_flag='0' WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."'");
		
		$j=2;
                $ObjDB->NonQuery("UPDATE itc_class_rotation_schedule_expedition_mappingtemp SET fld_flag='0',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$scheduleid."'");
                
		for($i=0;$i<sizeof($moduledet);$i++)
		{
                    if($moduledet[$i]!="undefined")
                    {
                        $getmoduledet=explode("-",$moduledet[$i]);

                        $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_class_rotation_schedule_expedition_mappingtemp WHERE fld_schedule_id='".$scheduleid."' AND fld_expedition_id='".$getmoduledet[0]."'");
                        if($cnt==0)
                        {
                            $ObjDB->NonQuery("INSERT INTO itc_class_rotation_schedule_expedition_mappingtemp(fld_schedule_id,fld_expedition_id,fld_flag,fld_createddate,fld_createdby) VALUES ('".$scheduleid."', '".$getmoduledet[0]."','1','".date('Y-m-d H:i:s')."','".$uid."')");
                        }
                        else
                        {
                            $ObjDB->NonQuery("UPDATE itc_class_rotation_schedule_expedition_mappingtemp SET fld_flag='1',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$scheduleid."' AND fld_expedition_id='".$getmoduledet[0]."'  AND fld_id='".$cnt."'");
                        }

                        $count=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) 
                                                                FROM itc_class_rotation_expmoduledet
                                                                        WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_exp_id='".$getmoduledet[0]."' 
                                                                            AND fld_row_id='".$j."'");

                        if($count==0)
                        {
                            $ObjDB->NonQuery("INSERT INTO itc_class_rotation_expmoduledet(fld_class_id,fld_schedule_id,fld_exp_id,fld_numberofrotation,fld_row_id)
                                                                    values('".$classid."','".$scheduleid."','".$getmoduledet[0]."','".$numberofrotation."','".$j."')");
                        }
                        else
                        {
                            $ObjDB->NonQuery("UPDATE itc_class_rotation_expmoduledet SET fld_flag='1',fld_numberofrotation='".$numberofrotation."' 
                                                WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_exp_id='".$getmoduledet[0]."'  and fld_row_id='".$j."'");
                        }
                    }
                    $j++;
		}
		
                /* Block student mapping start */
		
                        $ObjDB->NonQuery("UPDATE itc_class_rotation_expblockstudent SET fld_flag='0',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' WHERE fld_scheduleid='".$scheduleid."' AND fld_expeditionid='".$blockmodule."'");
			
                        if($blockstudents[0]>0)
                        {
                            for($i=0;$i<sizeof($blockstudents);$i++)
                            {
                                
                                    $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_class_rotation_expblockstudent WHERE fld_scheduleid='".$scheduleid."' AND  fld_expeditionid='".$blockmodule."'  AND fld_studentid='".$blockstudents[$i]."'");
                                    if($cnt==0)
                                    {

                                            $ObjDB->NonQuery("INSERT INTO itc_class_rotation_expblockstudent(fld_classid,fld_scheduleid,fld_expeditionid,fld_studentid,fld_flag,fld_createddate,fld_createdby) VALUES ('".$classid."','".$scheduleid."','".$blockmodule."','".$blockstudents[$i]."','1','".date('Y-m-d H:i:s')."','".$uid."')");
                                    }
                                    else
                                    {
                                            $ObjDB->NonQuery("UPDATE itc_class_rotation_expblockstudent SET fld_flag='1',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' WHERE fld_scheduleid='".$scheduleid."' AND fld_studentid='".$blockstudents[$i]."' AND fld_id='".$cnt."'");
                                    }
                            }
                        }
                        
				
				
	       /* Block student mapping end */
		
		
		$ObjDB->NonQuery("UPDATE itc_class_rotation_expschedulegriddet SET fld_flag='0',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."'");
		
		for($i=0;$i<sizeof($celldet);$i++)
		{
			$getcelldet=explode("~",$celldet[$i]);
			$getrowid=explode("_",$getcelldet[2]);
			$getmoduledet=explode("-",$getcelldet[0]);
			
			if($getcelldet[3]!="undefined")
			{
				$count=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_class_rotation_expschedulegriddet WHERE fld_class_id='".$classid."' AND  fld_schedule_id='".$scheduleid."' AND fld_expedition_id='".$getmoduledet[0]."'  and fld_row_id='".$getrowid[1]."' AND fld_cell_id='".$getcelldet[2]."'");
			
			  if($count==0)
			  {
                                    $cnt=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_class_rotation_expblockstudent WHERE fld_classid='".$classid."' AND fld_scheduleid='".$scheduleid."' AND fld_expeditionid='".$getmoduledet[0]."' AND fld_studentid='".$getcelldet[3]."' ANd fld_flag='1'");
                                    
                                    if($cnt=='0')
                                    {
	                                $ObjDB->NonQuery("INSERT INTO  itc_class_rotation_expschedulegriddet(fld_class_id,fld_schedule_id,fld_expedition_id,fld_rotation,fld_cell_id,fld_student_id,fld_row_id,fld_createddate,fld_createdby)values('".$classid."','".$scheduleid."','".$getmoduledet[0]."','".$getcelldet[1]."','".$getcelldet[2]."','".$getcelldet[3]."','".$getrowid[1]."','".date("Y-m-d H:i:s")."','".$uid."')");
			  }
			  }
			  else
			  {
                                   $cnt=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_class_rotation_expblockstudent WHERE fld_classid='".$classid."' AND fld_scheduleid='".$scheduleid."' AND fld_expeditionid='".$getmoduledet[0]."' AND fld_studentid='".$getcelldet[3]."' ANd fld_flag='1'");
                                    
                                    if($cnt=='0')
                                    {
					$ObjDB->NonQuery("UPDATE itc_class_rotation_expschedulegriddet SET fld_flag='1',fld_student_id='".$getcelldet[3]."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_expedition_id='".$getmoduledet[0]."' AND fld_row_id='".$getrowid[1]."' AND fld_cell_id='".$getcelldet[2]."'");
			  }
			}
			}
			
		}
		
	if($schflag==0)
	{
		$sdate='';
		$edate='';
		for($i=2;$i<=$numberofrotation+1;$i++)
		{
			if($i==2)
			{
				$sdate=$i."~".$startdate;
				$enddate=date("Y-m-d",strtotime($startdate. "+".$rotlength." weekdays"));

				$ObjDB->NonQuery("INSERT INTO itc_class_rotation_expscheduledate(fld_class_id,fld_schedule_id,fld_rotation,fld_startdate,fld_enddate,fld_createddate,fld_createdby)VALUES('".$classid."','".$scheduleid."','".$i."','".$startdate."','".$enddate."','".date("Y-m-d H:i:s")."','".$uid."')");
				
			}
			else
			{
				$startdate=date("Y-m-d",strtotime($enddate. "+1 weekdays"));
				$enddate=date("Y-m-d",strtotime($startdate. "+".$rotlength." weekdays"));

				$ObjDB->NonQuery("INSERT INTO itc_class_rotation_expscheduledate(fld_class_id,fld_schedule_id,fld_rotation,fld_startdate,fld_enddate,fld_createddate,fld_createdby)VALUES('".$classid."','".$scheduleid."','".$i."','".$startdate."','".$enddate."','".date("Y-m-d H:i:s")."','".$uid."')");
				
			}
			
		}
		
		
		
		$rotenddate=$ObjDB->SelectSingleValue("SELECT fld_enddate FROM itc_class_rotation_expscheduledate WHERE fld_schedule_id='".$scheduleid."' and fld_flag='1' AND fld_rotation IN(SELECT MAX(fld_rotation) FROM itc_class_rotation_expscheduledate WHERE fld_schedule_id='".$scheduleid."' AND fld_flag='1') LIMIT 0,1 ");
		
		$ObjDB->NonQuery("UPDATE itc_class_rotation_expschedule_mastertemp SET fld_enddate='".$rotenddate."',fld_gridupdatedby='".$uid."',fld_gridupdateddate='".date("Y-m-d H:i:s")."' WHERE fld_id='".$scheduleid."'");
	}
	else
	{
		$ObjDB->NonQuery("UPDATE itc_class_rotation_expscheduledate SET fld_flag=0 WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."'");

		$sdate='';
		$edate='';
		for($i=2;$i<=$numberofrotation+1;$i++)
		{
			$rotcount=$ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_class_rotation_expscheduledate WHERE fld_rotation='".$i."' AND fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."'");

			if($rotcount==1)
			{
				$ObjDB->NonQuery("UPDATE itc_class_rotation_expscheduledate SET fld_flag=1,fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_rotation='".$i."' AND fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."'");
			}
			else
			{
				$rotation=$i-1;
				$rotenddate=$ObjDB->SelectSingleValue("SELECT fld_enddate FROM itc_class_rotation_expscheduledate WHERE fld_rotation='".$rotation."' AND fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."'");

				$startdate=date("Y-m-d",strtotime($rotenddate. "+1 weekdays"));
				$enddate=date("Y-m-d",strtotime($startdate. "+".$rotlength." weekdays"));

				$ObjDB->NonQuery("INSERT INTO itc_class_rotation_expscheduledate(fld_class_id,fld_schedule_id,fld_rotation,fld_startdate,fld_enddate,fld_createddate,fld_createdby)VALUES('".$classid."','".$scheduleid."','".$i."','".$startdate."','".$enddate."','".date("Y-m-d H:i:s")."','".$uid."')");


			}
			
		}

		$rotenddate=$ObjDB->SelectSingleValue("SELECT fld_enddate FROM itc_class_rotation_expscheduledate WHERE fld_schedule_id='".$scheduleid."' and fld_flag='1' AND fld_rotation IN(SELECT MAX(fld_rotation) FROM itc_class_rotation_expscheduledate WHERE fld_schedule_id='".$scheduleid."' AND fld_flag='1') LIMIT 0,1 ");
		
		$ObjDB->NonQuery("UPDATE itc_class_rotation_expschedule_mastertemp SET fld_enddate='".$rotenddate."',fld_gridupdatedby='".$uid."',fld_gridupdateddate='".date("Y-m-d H:i:s")."' WHERE fld_id='".$scheduleid."'");
	}


		$ObjDB->NonQuery("UPDATE itc_class_rotation_expschedule_mastertemp SET fld_step_id=1,fld_flag=1,fld_autoblock='".$autoblock."',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."',fld_generatetype='".$generatetype."' WHERE fld_id='".$scheduleid."'");
		
		
		
		
	}
        
        
        
        /*--- Show expeditions  ---*/
	if($oper == "showexpedition" and $oper != '')
	{	
		$scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '0';
                $licenseid=isset($method['licenseid']) ? $method['licenseid'] : '0';
		
	?>
		<dl class='field row'> 
            <dt class="dropdown" style="width:300px;">     
            <div class="selectbox">
                <input type="hidden" name="selectmodule" id="selectmodule" value=" ">
                <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                    <span class="selectbox-option input-medium" data-option=" ">Select Expedition</span>
                    <b class="caret1"></b>
                </a>
                <div class="selectbox-options">
                    <input type="text" class="selectbox-filter" placeholder="Search Expedition" value="">
                    <ul role="options">
                        <?php 
                         
                         
                             $qryexp=$ObjDB->QueryObject("SELECT a.fld_id as id, CONCAT(a.fld_exp_name,' ',b.fld_version) as expname,'17' as type 
							                          FROM itc_exp_master AS a
							                               LEFT JOIN itc_exp_version_track AS b ON b.fld_exp_id=a.fld_id
							                               LEFT JOIN itc_license_exp_mapping AS c ON a.fld_id=c.fld_exp_id 
													  WHERE c.fld_license_id='".$licenseid."' AND a.fld_delstatus='0' AND c.fld_flag='1' AND b.fld_delstatus='0' group by id order by expname");
                                                 
                         
                         
                        if($qryexp->num_rows > 0)
                        {
                          while($rowsqry = $qryexp->fetch_assoc())
                          {
                              extract($rowsqry);
                              ?>
                           
                            <li><a tabindex="-1" href="#" data-option="<?php echo $id;?>"  onclick="fn_addexpedition(<?php echo $id;?>,<?php echo $scheduleid;?>,<?php echo $type;?>);" title="<?php echo $expname;?>" class="tooltip"><?php echo $expname;?> </a></li>
                        <?php 
                        }
                        }
                        else
                        {
                            echo "No Records";
                        }
                        ?>       
                    </ul>
                </div>
            </div>
            </dt>
        </dl>      
	<?php
	}
        
        
        /*--- add modules  ---*/
	if($oper == "addexpedition" and $oper != '')
	{	
		$expid = isset($method['expid']) ? $method['expid'] : '0';
		$scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '0';
		$scheduletype = isset($method['scheduletype']) ? $method['scheduletype'] : '0';
		$thlength = isset($method['thlength']) ? $method['thlength'] : '0';
		$trlength = isset($method['trlength']) ? $method['trlength'] : '0';
		$type = isset($method['type']) ? $method['type'] : '0';
                $classid = isset($method['classid']) ? $method['classid'] : '0';
                $numberofrotation = (isset($method['numberofrotation'])) ? $method['numberofrotation'] : 0;	
		$trlength=explode("_",$trlength);
		
                $rowid=$trlength[1]+1;
                
               
                $expname=$ObjDB->SelectSingleValue("SELECT CONCAT(a.fld_exp_name,' ',b.fld_version) 
                                                                                FROM itc_exp_master AS a 
                                                                                        LEFT JOIN itc_exp_version_track AS b ON b.fld_exp_id=a.fld_id
                                                                                WHERE a.fld_id='".$expid."' AND b.fld_delstatus='0' AND a.fld_delstatus='0'");


                $count=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) 
                            FROM itc_class_rotation_expmoduledet
						    WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_exp_id='".$expid."' 
                                    AND fld_row_id='".$rowid."'");

                if($count==0)
                {
                        $ObjDB->NonQuery("INSERT INTO itc_class_rotation_expmoduledet(fld_class_id,fld_schedule_id,fld_exp_id,fld_numberofrotation,fld_row_id)
                                                             values('".$classid."','".$scheduleid."','".$expid."','".$numberofrotation."','".$rowid."')");
                }
                else
                {
                        $ObjDB->NonQuery("UPDATE itc_class_rotation_expmoduledet SET fld_flag='1',fld_numberofrotation='".$numberofrotation."' 
                                                WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_exp_id='".$expid."' and fld_row_id='".$rowid."'");
                }
                                
                                
			
		
		
	?>
    
    <tr id="tr_<?php echo $trlength[1]+1; ?>" class="<?php echo $expid."-".$type; ?>">
        <td id="module_<?php echo $trlength[1]+1;?>" onmouseover="fn_checkcellvalue(<?php echo $trlength[1]+1;?>)" onmouseout="fn_checkcellvalueout(this.id)"><?php echo $expname; ?></td>
            <?php
				$k=2;
				$z=$trlength[1]+1;
				for($i=1;$i<$thlength;$i++)
				{
				?>
                <td id="stu_<?php echo $z.$k;?>" style="background: #FFFFFF;">
                
                	<div class="rowspanone clk row<?php echo $k;?>" id="seg1_<?php echo $z;?>_<?php echo $k;?>">&nbsp;</div>
					<div class="imagetop" id="imagetop_<?php echo $z;?>_<?php echo $k;?>" title="Delete"></div>
					<div class="rowspantwo clk row<?php echo $k;?>" id="seg2_<?php echo $z;?>_<?php echo $k;?>">&nbsp;</div>
					<div class="imagebottom" id="imagebottom_<?php echo $z;?>_<?php echo $k;?>" title="Delete"  <?php $k++; ?>></div>
                    
                </td>
                <?php
				}
				?>
		</tr>
         <tr id="addmod">
        	<td>
                  <a style="cursor:pointer;" id="addmodinc" class="addmodinc dim" onclick="fn_showexpedition(<?php echo $scheduleid;?>);"> + Add Expedition </a>
            </td>
            <?php
				for($i=1;$i<$thlength;$i++)
				{
				?>
                <td></td>
                <?php
				}
				?>
         </tr>
    <?php
	}
        
        
        /* check the studentname if already assigned to any class */
	if($oper == "checkstudentmod" and $oper != '')
	{
		$celldet = (isset($method['celldet'])) ? $method['celldet'] : 0;
		$sid = (isset($method['scheduleid'])) ? $method['scheduleid'] : 0;
                $operation = (isset($method['operation'])) ? $method['operation'] : 0;
		$celldet=explode(",",$celldet);
		$studentname="";
		$nam=array();
		
		if($operation=="generate")
                {
			for($i=0;$i<sizeof($celldet);$i++)
			{
				$getcelldet=explode("~",$celldet[$i]);
				$getexpdet=explode("-",$getcelldet[0]);
				
				if($getcelldet[1]!="undefined")
				{
					$count=0;
                                        
                                        
                                        
                                        $qryanstrack=$ObjDB->QueryObject("SELECT fld_exp_id as expid,fld_student_id as stuid,fld_schedule_id as sid,fld_schedule_type as schtype from itc_exp_dest_play_track where fld_exp_id='".$getexpdet[0]."' and fld_student_id='".$getcelldet[1]."' and fld_delstatus='0' group by fld_exp_id,fld_student_id");
                                        
                                        if($qryanstrack->num_rows>0)
                                        {
                                            $row=$qryanstrack->fetch_assoc();
                                            extract($row);
                                            
                                            $autoid=0;
                
                                            if($schtype==15)
                                            {
                                                $autoid=$ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_class_indasexpedition_master as a left join itc_class_master as b on b.fld_id=a.fld_class_id WHERE a.fld_id='".$sid."' and a.fld_delstatus='0'  and b.fld_delstatus='0'");
                                            }
                                            else if($schtype==19)
                                            {
                                                $autoid=$ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_class_rotation_expschedule_mastertemp as a left join itc_class_master as b on b.fld_id=a.fld_class_id WHERE a.fld_id='".$sid."' and a.fld_delstatus='0'  and b.fld_delstatus='0'");
                                            }
                                            else if($schtype==20)
                                            {
                                                $autoid=$ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_class_rotation_modexpschedule_mastertemp as a left join itc_class_master as b on b.fld_id=a.fld_class_id WHERE a.fld_id='".$sid."' and a.fld_delstatus='0'  and b.fld_delstatus='0'");
                                            }

                                            if($autoid>0)
                                            {
                                                $name=$ObjDB->SelectSingleValue("SELECT CONCAT(fld_fname,'',fld_lname) FROM itc_user_master WHERE fld_id='".$getcelldet[1]."' AND fld_delstatus='0'");
                                                $nam[]=$name;
                                                break;
                                            }
						
					}
				
				}
			
			}
                        
                    
			
			if(sizeof($nam)>0)
			{
				echo "Synergy ITC is unable to generate a complete schedule with the options currently selected. This is likely due to lack of available content.
                                      You can try adding additional content, reducing the number of rotations, turning off Autoblocking or clicking on the Show Details button for additional information.";
			}
                }
                else
                {
                    ?>
                    <script language="javascript">
                        $('#tableshowdetails').slimscroll({
                                height:'auto',
                                railVisible: false,
                                allowPageScroll: false,
                                railColor: '#F4F4F4',
                                opacity: 9,
                                color: '#88ABC2',
                                 wheelStep: 1
                        });
                    </script>
                    <table class='table table-hover table-striped table-bordered setbordertopradius'  id="mytable" >
                    <thead class='tableHeadText' >
                        <tr>
                            <th width="50%" class='centerText'>Student Name</th>
                            <th width="50%" class='centerText'>Blocked Expedition</th>
                            
                        </tr>
                    </thead>
                    </table>
                    <div style="max-height:400px;width:100%;" id="tableshowdetails" >
                    <table style="margin-bottom:0px;color:#47708a;" class='table table-hover table-striped table-bordered bordertopradiusremove' id="mytable">
                        <tbody>
                            <?php
                              
			for($i=0;$i<sizeof($celldet);$i++)
			{
				$getcelldet=explode("~",$celldet[$i]);
				$getexpdet=explode("-",$getcelldet[0]);
				
				if($getcelldet[1]!="undefined")
				{
					$count=0;
                                        
                                        $qryanstrack=$ObjDB->QueryObject("SELECT fld_exp_id as expid,fld_student_id as stuid,fld_schedule_id as sid,fld_schedule_type as schtype from itc_exp_dest_play_track where fld_exp_id='".$getexpdet[0]."' and fld_student_id='".$getcelldet[1]."' and fld_delstatus='0' group by fld_exp_id,fld_student_id");
                                        
                                        if($qryanstrack->num_rows>0)
                                        {
                                            $row=$qryanstrack->fetch_assoc();
                                            extract($row);
                                            
                                            $autoid=0;
                
                                            if($schtype==15)
                                            {
                                                $autoid=$ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_class_indasexpedition_master as a left join itc_class_master as b on b.fld_id=a.fld_class_id WHERE a.fld_id='".$sid."' and a.fld_delstatus='0' and b.fld_delstatus='0'");
                                            }
                                            else if($schtype==19)
                                            {
                                                $autoid=$ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_class_rotation_expschedule_mastertemp as a left join itc_class_master as b on b.fld_id=a.fld_class_id WHERE a.fld_id='".$sid."' and a.fld_delstatus='0' and b.fld_delstatus='0'");
                                            }
                                            else if($schtype==20)
                                            {
                                                $autoid=$ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_class_rotation_modexpschedule_mastertemp as a left join itc_class_master as b on b.fld_id=a.fld_class_id WHERE a.fld_id='".$sid."' and a.fld_delstatus='0' and b.fld_delstatus='0'");
                                            }

                                            if($autoid>0)
                                            {
                                            
                                            $name=$ObjDB->SelectSingleValue("SELECT CONCAT(fld_fname,'',fld_lname) FROM itc_user_master WHERE fld_id='".$getcelldet[1]."' AND fld_delstatus='0'");
                                                
                                            
                                            $expname=$ObjDB->SelectSingleValue("SELECT  CONCAT(a.fld_exp_name, ' ', b.fld_version) AS expname
                                                          FROM itc_exp_master AS a 
							  LEFT JOIN itc_exp_version_track AS b ON b.fld_exp_id = a.fld_id 
							  WHERE a.fld_id='".$getexpdet[0]."' AND a.fld_delstatus = '0' AND b.fld_delstatus = '0'");
                                              
                                          ?>
                                                 <tr>									
                                                    <td width="50%" class='centerText'><?php echo $name; ?></td>
                                                    <td width="50%" class='centerText'><?php echo $expname; ?></td>
                                                 </tr>
                                          <?php
						
						
                                            }
						
					}
				
				}
			
                        }
                            ?>
                            
                        </tbody>
                    </table>
                    </div>
                              
                 <?php   
                }
		
    }
       
    if($oper=="removeexpedition" and $oper!='')
    {
        $exptype = isset($method['exptype']) ? $method['exptype'] : '0';
        $rowid = isset($method['rowid']) ? $method['rowid'] : '0';
        $scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '0';
        $classid = isset($method['classid']) ? $method['classid'] : '0';
    
        $exp=explode('-',$exptype);
        
       
         
         $ObjDB->NonQuery("UPDATE itc_class_rotation_expmoduledet SET fld_flag='0'
                                WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_exp_id='".$exp[0]."' and fld_row_id='".$rowid."'");
        
    }
        
        
        @include("footer.php");

?>