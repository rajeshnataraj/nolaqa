<?php 
@include("sessioncheck.php");

$date = date("Y-m-d H:i:s");
$oper = isset($method['oper']) ? $method['oper'] : '';

/************District Admin****************/

if($oper=="showclass" and $oper != " " )
{
   $scholid = isset($method['schoolid']) ? $method['schoolid'] : '0';
    ?>
    Class
    <div class="selectbox">
        <input type="hidden" name="classid" id="classid" value="" onchange="fn_showsch(this.value);" />
        <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
           <span class="selectbox-option input-medium" data-option="" style="width:97%">Select Class</span> 
           <b class="caret1"></b>
       </a>
       <div class="selectbox-options">
           <input type="text" class="selectbox-filter" placeholder="Search Class">
           <ul role="options" style="width:100%">
               <?php 
               if($sessmasterprfid == 6)
                { //For District Admin
                     $qry = $ObjDB->QueryObject("SELECT w.* FROM ((SELECT b.fld_class_id AS classid, a.fld_class_name AS classname,15 AS exptype
                                                        FROM itc_class_master AS a 
                                                        LEFT JOIN itc_class_indasmission_master AS b ON a.fld_id=b.fld_class_id  
                                                        WHERE b.fld_delstatus='0' AND a.fld_district_id='".$sendistid."' 
                                                        AND a.fld_school_id='".$scholid."' AND b.fld_flag='1' 
                                                        AND a.fld_delstatus = '0'  AND a.fld_flag = '1' )
                                                           UNION ALL
                                                        (SELECT b.fld_class_id AS classid, a.fld_class_name AS classname, 19 AS exptype
                                                        FROM itc_class_master AS a 
                                                        LEFT JOIN itc_class_rotation_mission_mastertemp AS b ON b.fld_class_id=a.fld_id   
                                                        WHERE b.fld_delstatus='0' AND b.fld_flag='1' AND a.fld_district_id='".$sendistid."' 
                                                        AND a.fld_school_id='".$scholid."'
                                                        AND a.fld_delstatus = '0'  AND a.fld_flag = '1'))AS w 
                                                        group by w.classid ORDER BY w.classname");
                     
                }
                if($qry->num_rows>0){
                while($row = $qry->fetch_assoc())
                       {
                               extract($row);
                               
                               ?>
                               <li><a tabindex="-1" href="#" data-option="<?php echo $classid;?>"><?php echo $classname; ?></a></li>
                               <?php
                       }
                   }
                   else{ ?>
                          <li style="margin-left: 14px;"><?php echo "No Class"; ?></li>
                   <?php }
                 ?>      
           </ul>
       </div>
    </div>
    <?php
}

/************District Admin****************/


if($oper=="showschedule" and $oper != " " )
{
        $clsid = isset($method['clsid']) ? $method['clsid'] : '';
        
        ?> 
       Schedule
            <dl class='field row'>
                <div class="selectbox">
                    <input type="hidden" name="schid" id="schid" value="" onchange="fn_showexp(this.value);" />
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
                                                            WHERE a.fld_class_id='".$clsid."' AND  a.fld_delstatus='0' AND a.fld_flag='1' 
                                                            AND b.fld_delstatus = '0'  AND b.fld_flag = '1') 
                                                    UNION ALL
                                                            (SELECT a.fld_id AS sch, a.fld_schedule_name AS schedulename,19 AS exptype,
                                                            a.fld_id AS assingnmentid FROM itc_class_rotation_mission_mastertemp AS a
                                                            LEFT JOIN itc_class_master AS b ON b.fld_id=a.fld_class_id
                                                            WHERE a.fld_class_id='".$clsid."' AND  a.fld_delstatus='0' AND a.fld_flag='1'
                                                             AND b.fld_delstatus = '0'  AND b.fld_flag = '1'))AS w group by w.assingnmentid ORDER BY w.schedulename
                                                ");
                            if($qry->num_rows>0)
                            {
                                while($row = $qry->fetch_assoc())
                                {
                                    extract($row);
                                    ?>
                                        <li><a tabindex="-1" href="#" data-option="<?php echo $schid."_".$exptype;?>"><?php echo $schedulename; ?></a></li>
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
    $schid = isset($method['schid']) ? $method['schid'] : '';
    $classid = isset($method['clsid']) ? $method['clsid'] : '';

    $schid =explode("_",$schid );
    $scheduleid=$schid[0];
    $scheduletype=$schid[1];   
    ?>
        Mission
            <dl class='field row'>
                <div class="selectbox">
                     <input type="hidden" name="expid" id="expid" value="" onchange="fn_showrubric(this.value);" />
                    <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                        <span class="selectbox-option input-medium" data-option="" style="width:97%">Select Mission</span> <b class="caret1"></b> </a>
                    <div class="selectbox-options">
                        <input type="text" class="selectbox-filter" placeholder="Search Mission">
                        <ul role="options" style="width:100%">
                            <?php 
                            
        if($scheduletype=='18')
        {
           $qry = $ObjDB->QueryObject("SELECT a.fld_mis_id AS expid, b.fld_mis_name AS expname FROM itc_class_indasmission_master AS a
                                            LEFT JOIN itc_mission_master AS b ON b.fld_id=a.fld_mis_id
                                            WHERE a.fld_class_id='".$classid."' AND a.fld_id='".$scheduleid."' AND a.fld_delstatus='0' AND a.fld_flag='1' AND b.fld_delstatus='0' 
                                            AND b.fld_flag='1'  group by expid ORDER BY expname");
        }
        else
        {
             $qry = $ObjDB->QueryObject("SELECT a.fld_mission_id AS expid, b.fld_mis_name AS expname FROM itc_class_rotation_mission_schedulegriddet AS a
                                            LEFT JOIN itc_mission_master AS b ON b.fld_id=a.fld_mission_id
                                            LEFT JOIN itc_class_rotation_mission_mastertemp AS c ON a.fld_schedule_id=c.fld_id 
                                            WHERE a.fld_class_id='".$classid."' AND a.fld_schedule_id='".$scheduleid."' AND a.fld_flag='1' AND b.fld_delstatus='0' AND c.fld_delstatus='0' 
                                            AND b.fld_flag='1'  group by expid ORDER BY expname");
                
        }
                            
                            if($qry->num_rows>0){
                                while($row = $qry->fetch_assoc())
                                {
                                        extract($row);
                                        ?>
                                        <li><a tabindex="-1" href="#" data-option="<?php echo $expid;?>"><?php echo $expname; ?></a></li>
                                        <?php
                                }
                            }else{ ?>
                                <li style="margin-left: 14px;"><?php echo "No Mission"; ?></li>
                            <?php }?>
                        </ul>
                    </div>
                </div>
            </dl>
        
<?php
}

/*--- Load Student Dropdown ---*/
if($oper=="showrubric" and $oper != " " )
{
    $expid = isset($method['expid']) ? $method['expid'] : '';
    $schid = isset($method['schid']) ? $method['schid'] : '';
    $classid = isset($method['clsid']) ? $method['clsid'] : '';

    $schid =explode("_",$schid );
    $scheduleid=$schid[0];
    $scheduletype=$schid[1];
    ?>
   
	Rubric
	<dl class='field row'>
                <div class="selectbox">
                    <input type="hidden" name="rubid" id="rubid" value="" onchange="fn_showstudent(this.value);" />
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
                                else
                                { ?>
                                        <li><?php echo "No Rubric"; ?></li>
                                <?php
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
        
        
                $classid = isset($method['clsid']) ? $method['clsid'] : '';
                $schid = isset($method['schid']) ? $method['schid'] : '';

                $schid =explode("_",$schid );
                $scheduleid=$schid[0];
                $scheduletype=$schid[1];
       
       
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
     
<?php
}
