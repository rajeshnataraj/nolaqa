<?php 
@include("sessioncheck.php");

$date = date("Y-m-d H:i:s");
$date1 = date("Y-m-d 00:00:00");
$oper = isset($method['oper']) ? $method['oper'] : '';


if($oper=="showschedule" and $oper != " " )
{
        $clsid = isset($method['clsid']) ? $method['clsid'] : '';
        $type = isset($method['type']) ? $method['type'] : '';
        
        ?> 
       Schedule
            <dl class='field row'>
                <div class="selectbox">
                    <input type="hidden" name="schid" id="schid" value="" onchange="fn_showexp(this.value,<?php echo $type; ?>);" />
                    <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                       <span class="selectbox-option input-medium" data-option="" style="width:97%">Select Schedule</span> 
                       <b class="caret1"></b>
                   </a>
                   <div class="selectbox-options">
                       <input type="text" class="selectbox-filter" placeholder="Search Schedule">
                       <ul role="options" style="width:100%">
                           <?php
        
                        $qry = $ObjDB->QueryObject("SELECT w.* FROM (
                                                                (SELECT a.fld_id AS schid, a.fld_schedule_name AS schedulename,18 AS exptype,
                                                                a.fld_id AS assingnmentid FROM itc_class_indasmission_master AS a
                                                                LEFT JOIN itc_class_master AS b ON b.fld_id=a.fld_class_id
                                                                WHERE a.fld_class_id='".$clsid."' AND  a.fld_delstatus='0' AND a.fld_flag='1' AND a.fld_createdby='".$uid."'
                                                                AND b.fld_delstatus = '0'  AND b.fld_flag = '1') 
                                                        UNION ALL
                                                                (SELECT a.fld_id AS sch, a.fld_schedule_name AS schedulename,19 AS exptype,
                                                                a.fld_id AS assingnmentid FROM itc_class_rotation_mission_mastertemp AS a
                                                                LEFT JOIN itc_class_master AS b ON b.fld_id=a.fld_class_id
                                                                WHERE a.fld_class_id='".$clsid."' AND  a.fld_delstatus='0' AND a.fld_flag='1' AND a.fld_createdby='".$uid."'
                                                                 AND b.fld_delstatus = '0'  AND b.fld_flag = '1'))AS w group by w.assingnmentid ORDER BY w.schedulename
                                                ");
                            if($qry->num_rows>0)
                            {
                                while($row = $qry->fetch_assoc())
                                {
                                    extract($row);
                                    ?>
                                        <li><a tabindex="-1" href="#" data-option="<?php echo $schid."~".$exptype;?>"><?php echo $schedulename; ?></a></li>
                                    <?php
                                }
                            }
                            ?>      
                       </ul>
                   </div>
                </div>
            </dl>
        

    <?php 
} 



if($oper=="showexpedition" and $oper != " " )
{
        $type = isset($method['type']) ? $method['type'] : '';
        if($type==1 || $type==2)
        {
                $schid = isset($method['schid']) ? $method['schid'] : '';
                $classid = isset($method['clsid']) ? $method['clsid'] : '';

                $schid =explode("~",$schid );
                $scheduleid=$schid[0];
                $scheduletype=$schid[1];   
        }
        else
        {
                $schid = isset($method['schid']) ? $method['schid'] : '';
                $classid = isset($method['clsid']) ? $method['clsid'] : '';

                $schid =explode("~",$schid );
                $classid=$schid[0];
                $scheduleid=$schid[1];
                $scheduletype=$schid[2];   
        }
?> 
        Mission
            <dl class='field row'>
                <div class="selectbox">
                    <input type="hidden" name="expid" id="expid" value="" onchange="fn_showrubric(this.value,<?php echo $type; ?>);" />
                    <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                       <span class="selectbox-option input-medium" data-option="" style="width:97%">Select Mission</span> 
                       <b class="caret1"></b>
                   </a>
                   <div class="selectbox-options">
                       <input type="text" class="selectbox-filter" placeholder="Search Mission">
                       <ul role="options" style="width:100%">
                           <?php
          
        if($scheduletype=='18')
        {
               $qry = $ObjDB->QueryObject("SELECT a.fld_mis_id AS expid, b.fld_mis_name AS expname FROM itc_class_indasmission_master AS a
                                            LEFT JOIN itc_mission_master AS b ON b.fld_id=a.fld_mis_id
                                            WHERE a.fld_class_id='".$classid."' AND a.fld_id='".$scheduleid."' AND a.fld_delstatus='0' AND a.fld_flag='1' AND b.fld_delstatus='0' 
                                            AND b.fld_flag='1' AND a.fld_createdby='".$uid."' group by expid ORDER BY expname");
        }
        else
        {
            
             $qry = $ObjDB->QueryObject("SELECT a.fld_mission_id AS expid, b.fld_mis_name AS expname FROM itc_class_rotation_mission_schedulegriddet AS a
                                            LEFT JOIN itc_mission_master AS b ON b.fld_id=a.fld_mission_id
                                            LEFT JOIN itc_class_rotation_mission_mastertemp AS c ON a.fld_schedule_id=c.fld_id 
                                            WHERE a.fld_class_id='".$classid."' AND a.fld_schedule_id='".$scheduleid."' AND a.fld_flag='1' AND b.fld_delstatus='0' AND c.fld_delstatus='0' 
                                            AND b.fld_flag='1' AND a.fld_createdby='".$uid."' group by expid ORDER BY expname");
                
        }
                            if($qry->num_rows>0){
                                while($row = $qry->fetch_assoc())
                                {
                                    extract($row);
                                            ?>
                                                <li><a tabindex="-1" href="#" data-option="<?php echo $expid;?>"><?php echo $expname; ?></a></li>
                                            <?php
                                }
                            }
                            ?>      
                       </ul>
                   </div>
                </div>
            </dl>
        

    <?php 
} 


if($oper=="showrubric" and $oper != " " )
{

        $type = isset($method['type']) ? $method['type'] : '';
        
        if($type==1 || $type==2)
        {
                $expid = isset($method['expid']) ? $method['expid'] : '';
                $schid = isset($method['schid']) ? $method['schid'] : '';
                $classid = isset($method['clsid']) ? $method['clsid'] : '';

                $schid =explode("~",$schid );
                $scheduleid=$schid[0];
                $scheduletype=$schid[1];
        }
        else
        {
                $clasid = isset($method['clsid']) ? $method['clsid'] : '';
                $expid = isset($method['expid']) ? $method['expid'] : '';
                $schid =explode("~",$clasid );
                $classid=$schid[0];
                $scheduleid=$schid[1];
                $scheduletype=$schid[2];   
        }
        
        ?> 
        Rubric
            <dl class='field row'>
                <div class="selectbox">
                    <input type="hidden" name="rubid" id="rubid" value="" onchange="fn_showstudent(this.value,<?php echo $type; ?>);" />
                    <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                       <span class="selectbox-option input-medium" data-option="" style="width:97%">Select Rubric</span> 
                       <b class="caret1"></b>
                   </a>
                   <div class="selectbox-options">
                       <input type="text" class="selectbox-filter" placeholder="Search Rubric">
                       <ul role="options" style="width:100%">
                           <?php
        if($scheduletype=='18')
        {
                 $qry = $ObjDB->QueryObject("SELECT a.fld_rubric_id AS rubricid, a.fld_rubric_name AS rubname 
                                                FROM itc_class_expmis_rubricmaster AS a
                                                         LEFT JOIN itc_class_indasmission_master AS b ON a.fld_schedule_id=b.fld_id 
                                                                WHERE a.fld_class_id='".$classid."' AND a.fld_schedule_id='".$scheduleid."' AND a.fld_expmisid='".$expid."' AND b.fld_delstatus='0'
                                                                AND a.fld_schedule_type='".$scheduletype."' AND a.fld_delstatus='0' 
                                                                GROUP BY rubricid");  
        }
        else
        {
             $qry = $ObjDB->QueryObject("SELECT a.fld_rubric_id AS rubricid, a.fld_rubric_name AS rubname 
                                                FROM itc_class_expmis_rubricmaster AS a
                                                         LEFT JOIN itc_class_rotation_mission_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
                                                                WHERE a.fld_class_id='".$classid."' AND a.fld_schedule_id='".$scheduleid."' AND a.fld_expmisid='".$expid."' AND b.fld_delstatus='0'
                                                                AND a.fld_schedule_type='".$scheduletype."' AND a.fld_delstatus='0' 
                                                                GROUP BY rubricid");   
        }
                           
                            if($qry->num_rows>0){
                                while($row = $qry->fetch_assoc())
                                {
                                        extract($row);
                                        ?>
                                        <li><a tabindex="-1" href="#" data-option="<?php echo $rubricid;?>"><?php echo $rubname; ?></a></li>
                                        <?php
                                }
                            }
                            ?>      
                       </ul>
                   </div>
                </div>
            </dl>
    <?php 
} 


if($oper=="showstudent" and $oper != " " )
{

        $type = isset($method['type']) ? $method['type'] : '';
        $expid = isset($method['expid']) ? $method['expid'] : '';
        $rubid = isset($method['rubid']) ? $method['rubid'] : '0';
        
        if($type==1 || $type==2)
        {
                $classid = isset($method['clsid']) ? $method['clsid'] : '';
                $schid = isset($method['schid']) ? $method['schid'] : '';

                $schid =explode("~",$schid );
                $scheduleid=$schid[0];
                $scheduletype=$schid[1];
        }
        else
        {
                $clasid = isset($method['clsid']) ? $method['clsid'] : '';
                $schid =explode("~",$clasid );
                $classid=$schid[0];
                $scheduleid=$schid[1];
                $scheduletype=$schid[2];      
        }
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
<?php if($type =='1' || $type =='3'){ ?>                   
    <div class="row rowspacer" id="studentlist">
      <div class='six columns'>
          <div class="dragndropcol">
          <?php
                
        if($scheduletype=='18')
        {
           $qrystudent= $ObjDB->QueryObject("SELECT b.fld_student_id AS studentid,c.fld_fname AS firstname,c.fld_lname AS lastname ,c.fld_username as username, CONCAT(c.fld_fname,' ',c.fld_lname) AS sname FROM itc_class_indasmission_master AS a
                                                  LEFT JOIN itc_class_mission_student_mapping AS b ON a.fld_id=b.fld_schedule_id
                                                  LEFT JOIN itc_user_master AS c ON c.fld_id=b.fld_student_id
                                                  WHERE a.fld_class_id='".$classid."' AND b.fld_schedule_id='".$scheduleid."' AND a.fld_mis_id='".$expid."'  AND a.fld_flag='1' AND a.fld_delstatus='0' AND b.fld_flag='1' 
                                                      AND c.fld_profile_id = '10'  AND c.fld_activestatus = '1'  AND c.fld_delstatus = '0'  group by studentid ORDER BY sname ASC");
        }
        else
        {
                $qrystudent= $ObjDB->QueryObject("SELECT b.fld_student_id AS studentid,c.fld_fname AS firstname,c.fld_lname AS lastname ,c.fld_username as username, 
						 CONCAT(c.fld_fname,' ',c.fld_lname) AS sname FROM itc_class_rotation_mission_schedulegriddet AS a
							LEFT JOIN itc_class_rotation_mission_student_mappingtemp AS b ON a.fld_student_id=b.fld_student_id
							LEFT JOIN itc_user_master AS c ON c.fld_id=b.fld_student_id
							WHERE a.fld_class_id='".$classid."' AND b.fld_schedule_id='".$scheduleid."' AND a.fld_mission_id='".$expid."'  AND a.fld_flag='1' AND b.fld_flag='1' 
							AND c.fld_profile_id = '10'  AND c.fld_activestatus = '1'  AND c.fld_delstatus = '0'  group by studentid ORDER BY sname ASC");
                
        }
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
                                  <div class="clickable" id="clck_<?php echo $studentid;?>" onclick="fn_movealllistitems('list9','list10',<?php echo $studentid;?>,<?php echo $expid; ?>,<?php echo $type; ?>);"></div>
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
                              if($qrystudent->num_rows > 0){													
                                 while($rowsstudent = $qrystudent->fetch_assoc()){
                                     extract($rowsstudent);
                                         ?>
                                       <div class="draglinkright" id="list10_<?php echo $studentid; ?>">
                                         <div class="dragItemLable tooltip" id="<?php echo $studentid; ?>" title="<?php echo $username;?>"><?php echo $firstname." ".$lastname; ?></div>
                                         <div class="clickable" id="clck_<?php echo $studentid; ?>" onclick="fn_movealllistitems('list9','list10',<?php echo $studentid; ?>,<?php echo $expid; ?>,<?php echo $type; ?>);"></div>
                                     </div>
                              <?php }
                             }
                          ?>
                      </div>
                  </div>
          </div>
      </div>
    </div> 
     <?php } else { ?>
    <div class="row rowspacer" id="studentlist">
      <div class='six columns'>
          <div class="dragndropcol">
          <?php
          /***********class students*********/
        if($scheduletype=='18')
        {
           $qrystudent= $ObjDB->QueryObject("SELECT b.fld_student_id AS studentid,c.fld_fname AS firstname,c.fld_lname AS lastname ,c.fld_username as username, CONCAT(c.fld_fname,' ',c.fld_lname) AS sname FROM itc_class_indasmission_master AS a
                                                  LEFT JOIN itc_class_mission_student_mapping AS b ON a.fld_id=b.fld_schedule_id
                                                  LEFT JOIN itc_user_master AS c ON c.fld_id=b.fld_student_id
                                                  WHERE a.fld_class_id='".$classid."' AND b.fld_schedule_id='".$scheduleid."' AND a.fld_mis_id='".$expid."'  AND a.fld_flag='1' AND a.fld_delstatus='0' AND b.fld_flag='1' 
                                                      AND c.fld_profile_id = '10'  AND c.fld_activestatus = '1'  AND c.fld_delstatus = '0'  group by studentid ORDER BY sname ASC");
        }
        else
        {
                $qrystudent= $ObjDB->QueryObject("SELECT b.fld_student_id AS studentid,c.fld_fname AS firstname,c.fld_lname AS lastname ,c.fld_username as username, 
						 CONCAT(c.fld_fname,' ',c.fld_lname) AS sname FROM itc_class_rotation_mission_schedulegriddet AS a
							LEFT JOIN itc_class_rotation_mission_student_mappingtemp AS b ON a.fld_student_id=b.fld_student_id
							LEFT JOIN itc_user_master AS c ON c.fld_id=b.fld_student_id
							WHERE a.fld_class_id='".$classid."' AND b.fld_schedule_id='".$scheduleid."' AND a.fld_mission_id='".$expid."'  AND a.fld_flag='1' AND b.fld_flag='1' 
							AND c.fld_profile_id = '10'  AND c.fld_activestatus = '1'  AND c.fld_delstatus = '0'  group by studentid ORDER BY sname ASC");
                
        } 
                
         if($qrystudent->num_rows > 0){													
            while($rowsstudent = $qrystudent->fetch_assoc()){
                extract($rowsstudent);
                $classstudents[]=$studentid;
            }
        }

        /***********class students*********/
             ?>
              <div class="dragtitle">Students Currently Logged in</div>
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
                    for($i=0;$i<sizeof($classstudents);$i++) 
                    {
                       $qryforcurrentstudent= $ObjDB->QueryObject("SELECT a.fld_user_id as studentid, c.fld_fname AS firstname, c.fld_lname AS lastname , c.fld_username as username  FROM itc_login_track_master as a 
                                                                    LEFT JOIN itc_user_master AS c ON c.fld_id=a.fld_user_id
                                                                    WHERE a.fld_login_datetime >='".$date1."'
                                                                    AND a.fld_logout_datetime='0000-00-00 00:00:00' AND a.fld_delstatus='0' AND a.fld_user_id='".$classstudents[$i]."'");	
                         if($qryforcurrentstudent->num_rows > 0){													
                              while($rowsstudent = $qryforcurrentstudent->fetch_assoc()){
                                  extract($rowsstudent);
                                  ?>
                              <div class="draglinkleft" id="list9_<?php echo $studentid; ?>" >
                                  <div class="dragItemLable tooltip" id="<?php echo $studentid; ?>" title="<?php echo $username;?>"><?php echo $firstname." ".$lastname; ?></div>
                                  <div class="clickable" id="clck_<?php echo $studentid;?>" onclick="fn_movealllistitems('list9','list10',<?php echo $studentid;?>,<?php echo $expid; ?>,<?php echo $type; ?>);"></div>
                              </div>
                      <?php 
                              }
                          }
                          else{                             
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
                   
                         if($qryforcurrentstudent->num_rows > 0){													
                              while($rowsstudent = $qryforcurrentstudent->fetch_assoc()){
                                  extract($rowsstudent);
                                         ?>
                                      <div class="draglinkright" id="list10_<?php echo $studentid; ?>">
                                         <div class="dragItemLable tooltip" id="<?php echo $studentid; ?>" title="<?php echo $username;?>"><?php echo $firstname." ".$lastname; ?></div>
                                         <div class="clickable" id="clck_<?php echo $studentid; ?>" onclick="fn_movealllistitems('list9','list10',<?php echo $studentid; ?>,<?php echo $expid; ?>,<?php echo $type; ?>);"></div>
                                     </div>
                              <?php }
                             }
                   
                          ?>
                      </div>
                  </div>
          </div>
      </div>
    </div> 
      <?php } ?>
<?php
}


if($oper=="showrubricstmt" and $oper != " " )
{
   
   
$expeditionid = isset($method['expid']) ? $method['expid'] : '0';
$rubid = isset($method['rubid']) ? $method['rubid'] : '0';
$type = isset($method['type']) ? $method['type'] : '';
        
$createbtn = "Save";
$resetbtn = "Reset";
$finishbtn = "Finish";	
$totscore=$ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_mis_rubric_master WHERE fld_mis_id='".$expeditionid."' AND fld_rubric_id='".$rubid."' AND fld_delstatus='0'");               
      
?>
        <div class='row rowspacer'>     
                <div class='twelve columns'>
                        <div class='six columns'>
                        </div>
                        <div class='six columns'>
                                <div class="row" style="margin-left:34px;">
                                        <input type="hidden" name="totalscore" id="totalscore" value="0">
                                     <table>
                                         <tr>
                                           <td> Final Score: &nbsp;</td> <td id='studentscore' class='studentscore'> </td>
                                             <td> &nbsp;<?php echo " / ".$totscore; ?></td>
											 <td> &nbsp;&nbsp;&nbsp;<input class="darkButton" type="button" id="btnstep2" style="float: right; height: 34px; margin-top: -6px; width: 76px;" value="<?php echo $resetbtn;?>" onClick="fn_resetrubric('<?php echo $rubid;?>','<?php echo $expeditionid;?>','<?php echo $type;?>');" /> 
                                             <td> &nbsp;&nbsp;&nbsp;<input class="darkButton" type="button" id="btnstep2" style="float: right; height: 34px; margin-top: -6px; width: 76px;" value="<?php echo $createbtn;?>" onClick="fn_saverubric('<?php echo $rubid;?>','<?php echo $expeditionid;?>','<?php echo $type;?>');" />
											 <td> &nbsp;&nbsp;&nbsp;<input class="darkButton" type="button" id="btnstep2" style="float: right; height: 34px; margin-top: -6px; width: 76px;" value="<?php echo $finishbtn;?>" onClick="fn_finishrubric('<?php echo $rubid;?>','<?php echo $expeditionid;?>','<?php echo $type;?>');" />												 
                                            </td>
                                         </tr>
                                     </table>
                            </div> 
                     </div> 
                </div>
         </div>
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
                                        <th width="6%" class='centerText'>Weight</th>
                                        <th width="9%" class='centerText'>Score</th>
                                    </tr>
                                </thead>
                            </table>
                            <div style="height:547px;overflow-y: auto;">
                                  <table class='table table-hover table-striped table-bordered setbordertopradius' id="mytable" >
                                <?php

                                  $qrydest=$ObjDB->QueryObject("SELECT a.fld_id as destid,a.fld_dest_name as destname FROM itc_mis_rubric_dest_master as a
                                                                            LEFT JOIN itc_mission_master AS b ON b.fld_id=a.fld_mis_id 
                                                                            WHERE a.fld_mis_id='".$expeditionid."' AND a.fld_rubric_name_id='".$rubid."' AND a.fld_delstatus='0'");
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
                                <tbody> 
                                <style> 
                                  .bcolor{background: #F1F1F3;} m{ text-decoration: overline;}
                                    .table tr td:first-child {   padding-left: 20px;    }
                                          .td_select{    background-color:#99ccff !important; font-weight: bold;  /*border:solid #0066ff; */} 
                                </style>
                                          <tr class="bcolor">
                                             <td style="font:  bold 12px/30px Times New Roman;">Interval <?php echo $i+1;?></td>
                                            <td colspan="7" style="font:  bold 14px/30px Times New Roman;"><?php echo $dname[$i];?></td>
                                          </tr>
                                          <?php

                                            $qryviewexp_rubric=$ObjDB->QueryObject("SELECT c.fld_id as rubricid, a.fld_id as destid,a.fld_dest_name as destname, c.fld_category as category,c.fld_four as four,c.fld_three as three,
                                                                                        c.fld_two as two,c.fld_one as one, c.fld_zer as zer ,c.fld_weight as weight,c.fld_score as score FROM itc_mis_rubric_dest_master as a
                                                                                            LEFT JOIN itc_mission_master AS b ON b.fld_id=a.fld_mis_id 
                                                                                            LEFT JOIN itc_mis_rubric_master AS c ON c.fld_destination_id=a.fld_id 
                                                                                            WHERE c.fld_rubric_id='".$rubid."' AND c.fld_mis_id='".$expeditionid."' AND c.fld_destination_id='".$did[$i]."'  AND a.fld_delstatus='0' AND c.fld_delstatus='0' ");//AND c.fld_created_by='".$uid1."'
                                              
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
                                                    ?>
                                                    <tr class="Btn" id="exp-rubric-<?php echo $rubricid; ?>" >
                                                        <td colswidth="18%" id="rubrictxt-<?php echo $rubricid; ?>" ><?php echo $category ;?></td>
                                                        <td width="14%" id="rubrictxt-<?php echo $rubricid; ?>-4"  onclick="fn_highlight('4','<?php echo $rubricid; ?>');fn_showdeststmt('4','<?php echo $weight ;?>','<?php echo $rubricid; ?>','<?php echo $destid; ?>','<?php echo $rubid; ?>','<?php echo $type; ?>');this.disabled='disabled';"  ><?php echo $four ;?></td>
                                                        <td width="14%" id="rubrictxt-<?php echo $rubricid; ?>-3"  onclick="fn_highlight('3','<?php echo $rubricid; ?>');fn_showdeststmt('3','<?php echo $weight ;?>','<?php echo $rubricid; ?>','<?php echo $destid; ?>','<?php echo $rubid; ?>','<?php echo $type; ?>');this.disabled='disabled';"  ><?php echo $three ;?></td>
                                                        <td width="13%" id="rubrictxt-<?php echo $rubricid; ?>-2"  onclick="fn_highlight('2','<?php echo $rubricid; ?>');fn_showdeststmt('2','<?php echo $weight ;?>','<?php echo $rubricid; ?>','<?php echo $destid; ?>','<?php echo $rubid; ?>','<?php echo $type; ?>');this.disabled='disabled';"  ><?php echo $two ;?></td>
                                                        <td width="13%" id="rubrictxt-<?php echo $rubricid; ?>-1"  onclick="fn_highlight('1','<?php echo $rubricid; ?>');fn_showdeststmt('1','<?php echo $weight ;?>','<?php echo $rubricid; ?>','<?php echo $destid; ?>','<?php echo $rubid; ?>','<?php echo $type; ?>');this.disabled='disabled';"  ><?php echo $one ;?></td>
                                                        <td width="13%" id="rubrictxt-<?php echo $rubricid; ?>-0"  onclick="fn_highlight('0','<?php echo $rubricid; ?>');fn_showdeststmt('0','<?php echo $weight ;?>','<?php echo $rubricid; ?>','<?php echo $destid; ?>','<?php echo $rubid; ?>','<?php echo $type; ?>');this.disabled='disabled';"  ><?php echo $zer ;?></td>
                                                        <td width="6%" id="rubrictxt-<?php echo $rubricid; ?>" ><?php echo "X".$weight ;?></td>
                                                        <td width="9%" id="rubrictxt-<?php echo $rubricid; ?>" >
                                                            
                                                           <input type="hidden" name="rubrictxtoldval_<?php echo $rubricid; ?>" id="rubrictxtoldval_<?php echo $rubricid; ?>" value="">
                                                            <input  type='text' id="txtscore-<?php echo $rubricid; ?>" readonly="" name="txtscore" maxlength="3" min="0" max="<?php //echo $scoree ;?>" value="<?php //echo $score ;?>" onkeypress="return isNumber(event)"  style="width:15px; border: 0px none;"><?php echo "/ ".$score ;?>
															<input type="hidden" id="ids_<?php echo $rubricid."~".$destid."~".$weight."~".$type; ?>" name="ids" value="">
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
           
            </div> 
 
<script type="text/javascript" language="javascript">
        function isNumber(evt) 
        {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if (charCode > 31 && (charCode < 48 || charCode > 57)) 
                {
                   return false;
                }
                return true;
        }
        
        function fn_highlight(cellid,rubid)
        {
                var otherval = []; 
                $('#rubrictxt-'+rubid+'-'+cellid).toggleClass("td_select");  
                
                for(a=0;a<=4;a++)
                {
                        if(parseInt(a)!=cellid)
                        {
                                otherval.push(a);  
                        }
                    
                }
                for(b=0;b<otherval.length;b++)
                {
                        $('#rubrictxt-'+rubid+'-'+otherval[b]).removeClass("td_select"); 
                }
        }
</script>
    
    <?php    
}




/*--- Save and Update the Rubric ---*/
if($oper=="saverubric")
{
    try{
        
       $type = isset($method['type']) ? $method['type'] : '';
        $rubid = isset($method['rubnameid']) ? $method['rubnameid'] : '0'; 
        $expid = isset($method['expid']) ? $method['expid'] : '0'; 
       

        $studentid = isset($method['list10']) ? ($method['list10']) : '0'; 
        $score = isset($method['txtscore']) ? $method['txtscore'] : '0'; 
        $ruborderid = isset($method['ruborderid']) ? $method['ruborderid'] : '0'; 
        $destid = isset($method['destid']) ? $method['destid'] : '0'; 


        $stuid=explode(",",$studentid);
        $sco=explode(",",$score); 
            
            
        if($type==1 || $type==2)
        {
                $clasid = isset($method['classid']) ? $method['classid'] : '0';
                $schid = isset($method['schid']) ? $method['schid'] : '';

                $schid =explode("~",$schid );
                $scheduleid=$schid[0];
                $scheduletype=$schid[1];
            
        }
        else
        {
                $classid = isset($method['classid']) ? $method['classid'] : '';
                $schid =explode("~",$classid);
                $clasid=$schid[0];
                $scheduleid=$schid[1];
                $scheduletype=$schid[2]; 
                
        }
        if($scheduletype=='18')
        {
              //save class Name
                $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_mis_rubric_rpt WHERE fld_mis_id='".$expid."' AND fld_class_id='".$clasid."' AND fld_schedule_id='".$scheduleid."'
                                                            AND fld_rubric_nameid='".$rubid."' AND fld_created_by='".$uid."' AND fld_delstatus = '0'");
                if($cnt==0)
                {
                   $maxid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_mis_rubric_rpt (fld_class_id, fld_mis_id, fld_rubric_nameid, fld_created_by, fld_created_date, fld_district_id, fld_school_id, fld_user_id,fld_profile_id,fld_schedule_id) 
                                                            VALUES ('".$clasid."', '".$expid."', '".$rubid."', '".$uid."', '".$date."','".$sendistid."','".$schoolid."','".$indid."','".$sessmasterprfid."','".$scheduleid."')");
                }
                else
                {
                   $ObjDB->NonQuery("UPDATE itc_mis_rubric_rpt SET fld_class_id='".$clasid."', fld_mis_id='".$expid."', fld_updated_by='".$uid."', 
                                                      fld_updated_date='".$date."' WHERE fld_rubric_nameid='".$rubid."'  AND fld_class_id='".$clasid."'  AND fld_schedule_id='".$scheduleid."'  AND fld_delstatus = '0' ");
                   $maxid=$cnt;
                }

               /*rubric stmt*/

                for($i=0;$i<sizeof($stuid);$i++)
                {
                    $cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_rubric_rpt_statement 
                                                            WHERE fld_dest_id='".$destid."' AND fld_rubric_id='".$ruborderid."' AND fld_rubric_rpt_id='".$maxid."'
                                                            AND fld_mis_id='".$expid."' AND fld_rubric_nameid='".$rubid."' AND fld_student_id='".$stuid[$i]."'");
                    if($cnt==0)
                    {
                            $ObjDB->NonQuery("INSERT INTO itc_mis_rubric_rpt_statement (fld_rubric_rpt_id, fld_dest_id, fld_rubric_id, fld_score, fld_created_by, fld_created_date, fld_rubric_nameid, fld_mis_id, fld_student_id) 
                                                                         VALUES ('".$maxid."', '".$destid."', '".$ruborderid."', '".$score."', '".$uid."', '".$date."', '".$rubid."', '".$expid."', '".$stuid[$i]."')");
                    }
                    else
                    {
                           $ObjDB->NonQuery("UPDATE itc_mis_rubric_rpt_statement 
                                                SET fld_score='".$score."', fld_delstatus='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
                                                WHERE fld_dest_id='".$destid."' AND fld_rubric_id='".$ruborderid."' AND fld_rubric_rpt_id='".$maxid."'
                                                AND fld_mis_id='".$expid."' AND fld_rubric_nameid='".$rubid."' AND fld_student_id='".$stuid[$i]."'");
                    }
                }   
        }
        else
        {
                $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_missch_rubric_rpt WHERE fld_mis_id='".$expid."' AND fld_class_id='".$clasid."' AND fld_schedule_id='".$scheduleid."'
                                                            AND fld_rubric_nameid='".$rubid."' AND fld_created_by='".$uid."' AND fld_delstatus = '0'");
                if($cnt==0)
                {
                   $maxid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_missch_rubric_rpt (fld_class_id, fld_mis_id, fld_rubric_nameid, fld_created_by, fld_created_date, fld_district_id, fld_school_id, fld_user_id,fld_profile_id,fld_schedule_id) 
                                                            VALUES ('".$clasid."', '".$expid."', '".$rubid."', '".$uid."', '".$date."','".$sendistid."','".$schoolid."','".$indid."','".$sessmasterprfid."','".$scheduleid."')");
                }
                else
                {
                   $ObjDB->NonQuery("UPDATE itc_missch_rubric_rpt SET fld_class_id='".$clasid."', fld_mis_id='".$expid."', fld_updated_by='".$uid."', 
                                                      fld_updated_date='".$date."' WHERE fld_rubric_nameid='".$rubid."'  AND fld_class_id='".$clasid."'  AND fld_schedule_id='".$scheduleid."'  AND fld_delstatus = '0' ");
                   $maxid=$cnt;
                }

               /*rubric stmt*/

                for($i=0;$i<sizeof($stuid);$i++)
                {
                    $cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_missch_rubric_rpt_statement 
                                                            WHERE fld_dest_id='".$destid."' AND fld_rubric_id='".$ruborderid."' AND fld_rubric_rpt_id='".$maxid."'
                                                            AND fld_mis_id='".$expid."' AND fld_rubric_nameid='".$rubid."' AND fld_student_id='".$stuid[$i]."'");
                    if($cnt==0)
                    {
                            $ObjDB->NonQuery("INSERT INTO itc_missch_rubric_rpt_statement (fld_rubric_rpt_id, fld_dest_id, fld_rubric_id, fld_score, fld_created_by, fld_created_date, fld_rubric_nameid, fld_mis_id, fld_student_id) 
                                                 VALUES ('".$maxid."', '".$destid."', '".$ruborderid."', '".$score."', '".$uid."', '".$date."', '".$rubid."', '".$expid."', '".$stuid[$i]."')");
                    }
                    else
                    {
                           $ObjDB->NonQuery("UPDATE itc_missch_rubric_rpt_statement 
                                                SET fld_score='".$score."', fld_delstatus='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
                                                WHERE fld_dest_id='".$destid."' AND fld_rubric_id='".$ruborderid."' AND fld_rubric_rpt_id='".$maxid."'
                                                AND fld_mis_id='".$expid."' AND fld_rubric_nameid='".$rubid."' AND fld_student_id='".$stuid[$i]."'");
                    }
                }   
        }
        echo "success";
    }
    catch(Exception $e)
    {
        echo "fail";
    }
}
/*** Save and update the Rubric new created by chandru ***/
if($oper=="saverubricval")
{
	try
	{
		$studentid = isset($method['list10']) ? ($method['list10']) : '0'; 
		$expid = isset($method['expid']) ? ($method['expid']) : '0'; 
		$rubid = isset($method['rubid']) ? ($method['rubid']) : '0';
		$ids = isset($method['ids']) ? ($method['ids']) : '0';
		$score = isset($method['score']) ? ($method['score']) : '0';

		$score=explode(",",$score);
		$stuid=explode(",",$studentid);
		$ids=explode(",",$ids);

		$id = '';
		$type = '';

		for($i=0;$i<sizeof($ids);$i++)
		{
			$idval  = explode('~',$ids[$i]); 
			if($id=='')
			{
				$type=$idval[3];
			}
			else
			{
				$type=$type.",".$idval[3];
			}
		}

		if($type==1 || $type==2)
		{
			$clasid = isset($method['classid']) ? $method['classid'] : '0';
			$schid = isset($method['schid']) ? $method['schid'] : '';

			$schid =explode("~",$schid );
			$scheduleid=$schid[0];
			$scheduletype=$schid[1];

		}
		else
		{
			$classid = isset($method['classid']) ? $method['classid'] : '';
			$schid =explode("~",$classid);
			$clasid=$schid[0];
			$scheduleid=$schid[1];
			$scheduletype=$schid[2]; 

		}

		if($scheduletype=='18')
		{
			//save class Name
			$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_mis_rubric_rpt WHERE fld_mis_id='".$expid."' AND fld_class_id='".$clasid."' AND fld_schedule_id='".$scheduleid."'
														AND fld_rubric_nameid='".$rubid."' AND fld_created_by='".$uid."' AND fld_delstatus = '0'");
			if($cnt==0)
			{

				$maxid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_mis_rubric_rpt (fld_class_id, fld_mis_id, fld_rubric_nameid, fld_created_by, fld_created_date, fld_district_id, fld_school_id, fld_user_id,fld_profile_id,fld_schedule_id) 
														VALUES ('".$clasid."', '".$expid."', '".$rubid."', '".$uid."', '".$date."','".$sendistid."','".$schoolid."','".$indid."','".$sessmasterprfid."','".$scheduleid."')");
			}
			else
			{
				$ObjDB->NonQuery("UPDATE itc_mis_rubric_rpt SET fld_class_id='".$clasid."', fld_mis_id='".$expid."', fld_updated_by='".$uid."', 
					  					fld_updated_date='".$date."' WHERE fld_rubric_nameid='".$rubid."'  AND fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."'  AND fld_delstatus = '0' ");
				$maxid=$cnt;
			}

			/*rubric stmt*/

			for($j=0;$j<sizeof($stuid);$j++)
			{
				for($i=0;$i<sizeof($ids);$i++)
				{
					$idval  = explode('~',$ids[$i]); 
					
					if($score[$i] == '')
					{
						$score[$i] = 'NULL';
					}
					
                                        $rubweight = $ObjDB->SelectSingleValueInt("SELECT fld_weight FROM itc_mis_rubric_master 
                                                                                        WHERE fld_id='".$idval[0]."' AND fld_destination_id='".$idval[1]."' 
                                                                                        AND fld_mis_id='".$expid."' AND fld_delstatus='0'");
                                
                                        if($score[$i]!='NULL')
                                        {
                                                if($score[$i]==0)
                                                {
                                                   $cellid=0;
                                                }
                                                else
                                                {
                                                   $cellid=($score[$i]/$rubweight);
                                                }

                                        }
                                        else
                                        {
                                           $cellid='NULL';     
                                        }
					
					$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_mis_rubric_rpt_statement 
															WHERE fld_dest_id='".$idval[1]."' AND fld_rubric_id='".$idval[0]."' AND fld_rubric_rpt_id='".$maxid."'
															AND fld_mis_id='".$expid."' AND fld_rubric_nameid='".$rubid."' AND fld_student_id='".$stuid[$j]."'");

					if($cnt==0)
					{
						$ObjDB->NonQuery("INSERT INTO itc_mis_rubric_rpt_statement (fld_rubric_rpt_id, fld_dest_id, fld_rubric_id, fld_score, fld_created_by, fld_created_date, fld_rubric_nameid, fld_mis_id, fld_student_id, fld_hightlight_cell) 
                                                                         VALUES ('".$maxid."', '".$idval[1]."', '".$idval[0]."', '".$score[$i]."', '".$uid."', '".$date."', '".$rubid."', '".$expid."', '".$stuid[$j]."', '".$cellid."')");
					}
					else
					{
						$ObjDB->NonQuery("UPDATE itc_mis_rubric_rpt_statement 
                                                                        SET fld_score='".$score[$i]."', fld_delstatus='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."', fld_hightlight_cell = '".$cellid."'
												WHERE fld_dest_id='".$idval[1]."' AND fld_rubric_id='".$idval[0]."' AND fld_rubric_rpt_id='".$maxid."'
												AND fld_mis_id='".$expid."' AND fld_rubric_nameid='".$rubid."' AND fld_student_id='".$stuid[$j]."'");
					}

				}
			}   
		}
		else
		{
			//save class Name
			$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_missch_rubric_rpt WHERE fld_mis_id='".$expid."' AND fld_class_id='".$clasid."' AND fld_schedule_id='".$scheduleid."'
														AND fld_rubric_nameid='".$rubid."' AND fld_created_by='".$uid."' AND fld_delstatus = '0'");
			if($cnt==0)
			{
			   $maxid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_missch_rubric_rpt (fld_class_id, fld_mis_id, fld_rubric_nameid, fld_created_by, fld_created_date, fld_district_id, fld_school_id, fld_user_id,fld_profile_id,fld_schedule_id) 
														VALUES ('".$clasid."', '".$expid."', '".$rubid."', '".$uid."', '".$date."','".$sendistid."','".$schoolid."','".$indid."','".$sessmasterprfid."','".$scheduleid."')");
			}
			else
			{
			   $ObjDB->NonQuery("UPDATE itc_missch_rubric_rpt SET fld_class_id='".$clasid."', fld_mis_id='".$expid."', fld_updated_by='".$uid."', 
												  fld_updated_date='".$date."' WHERE fld_rubric_nameid='".$rubid."'  AND fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_delstatus = '0' ");
			   $maxid=$cnt;
			}
			
			/*rubric stmt*/

			for($j=0;$j<sizeof($stuid);$j++)
			{
				for($i=0;$i<sizeof($ids);$i++)
				{
					$idval  = explode('~',$ids[$i]); 
					
					if($score[$i] == '')
					{
						$score[$i] = 'NULL';
					}
					
                                        $rubweight = $ObjDB->SelectSingleValueInt("SELECT fld_weight FROM itc_mis_rubric_master 
                                                                                        WHERE fld_id='".$idval[0]."' AND fld_destination_id='".$idval[1]."' 
                                                                                        AND fld_mis_id='".$expid."' AND fld_delstatus='0'");
                                
                                        if($score[$i]!='NULL')
                                        {
                                                if($score[$i]==0)
                                                {
                                                   $cellid=0;
                                                }
                                                else
                                                {
                                                   $cellid=($score[$i]/$rubweight);
                                                }

                                        }
                                        else
                                        {
                                           $cellid='NULL';     
                                        }
					
					$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_missch_rubric_rpt_statement 
															WHERE fld_dest_id='".$idval[1]."' AND fld_rubric_id='".$idval[0]."' AND fld_rubric_rpt_id='".$maxid."'
															AND fld_mis_id='".$expid."' AND fld_rubric_nameid='".$rubid."' AND fld_student_id='".$stuid[$j]."'");

					if($cnt==0)
					{
						$ObjDB->NonQuery("INSERT INTO itc_missch_rubric_rpt_statement (fld_rubric_rpt_id, fld_dest_id, fld_rubric_id, fld_score, fld_created_by, fld_created_date, fld_rubric_nameid, fld_mis_id, fld_student_id, fld_hightlight_cell) 
                                                                         VALUES ('".$maxid."', '".$idval[1]."', '".$idval[0]."', '".$score[$i]."', '".$uid."', '".$date."', '".$rubid."', '".$expid."', '".$stuid[$j]."', '".$cellid."')");
					}
					else
					{
						$ObjDB->NonQuery("UPDATE itc_missch_rubric_rpt_statement 
                                                                        SET fld_score='".$score[$i]."', fld_delstatus='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."', fld_hightlight_cell = '".$cellid."' 
												WHERE fld_dest_id='".$idval[1]."' AND fld_rubric_id='".$idval[0]."' AND fld_rubric_rpt_id='".$maxid."'
												AND fld_mis_id='".$expid."' AND fld_rubric_nameid='".$rubid."' AND fld_student_id='".$stuid[$j]."'");
					}

				}
			} 
		}
		echo "success";
	}
	catch(Exception $e)
	{
		echo "fail";
	}
	
}

