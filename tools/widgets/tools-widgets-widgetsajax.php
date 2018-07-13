<?php 

/*----
    Created BY : Senthilnathan.S PHP Programmer.(30/10/2015)	
----*/

@include("sessioncheck.php");
$date = date("Y-m-d H:i:s");
$oper = isset($method['oper']) ? $method['oper'] : '';

if($oper=="showassignment" and $oper != " " )
{
    $classid = isset($method['classid']) ? $method['classid'] : '';
?> 
 Assignments 
    <dl class='field row'>
            <div class="selectbox">
                    <input type="hidden" name="assignid" id="assignid" value="">
                    <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                        <?php
                                                    $schid=$ObjDB->SelectSingleValue("SELECT fld_schedule_id FROM widgets_turnoff_student WHERE fld_created_by='".$uid."' AND fld_class_id='".$classid."'");
                                                    $schtype=$ObjDB->SelectSingleValue("SELECT fld_schedule_type FROM widgets_turnoff_student WHERE fld_created_by='".$uid."' AND fld_class_id='".$classid."' AND fld_schedule_id='".$schid."'");
                                                    if($schtype == '0'){
                                                     $schedule_name = $ObjDB->SelectSingleValue("SELECT a.fld_schedule_name
                                                                            FROM itc_class_sigmath_master AS a 
                                                                            LEFT JOIN itc_class_sigmath_unit_mapping AS b ON a.fld_id = b.fld_sigmath_id 
                                                                            LEFT JOIN itc_unit_master AS c ON c.fld_id = b.fld_unit_id 
                                                                            WHERE a.fld_class_id = '".$classid."' AND a.fld_id='".$schid."' AND a.fld_flag = '1' AND a.fld_delstatus = '0' AND b.fld_flag = '1' AND c.fld_activestatus = '0' 
                                                                            AND c.fld_delstatus = '0'");
                                                    }
                                                    if($schtype == '1' || $schtype == '4'){
                                                     $schedule_name = $ObjDB->SelectSingleValue("SELECT c.fld_schedule_name
                                                                            FROM itc_class_rotation_schedulegriddet AS a 
                                                                            LEFT JOIN itc_class_rotation_moduledet AS b ON b.fld_schedule_id=a.fld_schedule_id 
                                                                            left join itc_class_rotation_schedule_mastertemp as c on a.fld_schedule_id=c.fld_id
                                                                            LEFT JOIN itc_class_rotation_scheduledate AS d ON a.fld_schedule_id=d.fld_schedule_id and a.fld_rotation=d.fld_rotation
                                                                            LEFT JOIN itc_class_rotation_schedule_student_mappingtemp as e ON a.fld_schedule_id=e.fld_schedule_id
                                                                            WHERE a.fld_class_id='".$classid."' AND a.fld_schedule_id='".$schid."' AND b.fld_flag='1' AND a.fld_flag='1' AND e.fld_flag='1' and c.fld_delstatus='0' AND d.fld_flag='1'");
                                                    }
                                                    if($schtype == '9'){
                                                     $schedule_name = $ObjDB->SelectSingleValue("SELECT b.fld_test_name AS testname
                                                                            FROM itc_test_student_mapping AS a 
                                                                            LEFT JOIN itc_test_master AS b ON a.fld_test_id=b.fld_id 
                                                                            WHERE a.fld_class_id='".$classid."' AND b.fld_id='".$schid."' AND a.fld_flag='1' AND b.fld_delstatus='0' AND b.fld_ass_type='0'");
                                                    }
                                                    if($schtype == '15'){
                                                     $schedule_name = $ObjDB->SelectSingleValue("SELECT a.fld_schedule_name AS testname
                                                                            FROM itc_class_indasexpedition_master AS a 
                                                                            LEFT JOIN itc_exp_master AS b ON a.fld_exp_id=b.fld_id
                                                                            WHERE a.fld_class_id='".$classid."' AND a.fld_id='".$schid."' AND a.fld_flag='1' AND a.fld_delstatus='0' 
                                                                            AND b.fld_delstatus='0'");
                                                    }
                                                    if($schtype == '5'){
                                                     $schedule_name = $ObjDB->SelectSingleValue("SELECT a.fld_id AS testid, 5 AS typeids, a.fld_schedule_name AS testname 
                                                                            FROM itc_class_indassesment_master AS a 
                                                                            LEFT JOIN itc_module_master AS b ON a.fld_module_id=b.fld_id 
                                                                            LEFT JOIN itc_class_indassesment_student_mapping as c ON a.fld_id=c.fld_schedule_id
                                                                            WHERE a.fld_class_id='".$classid."' AND a.fld_flag='1' AND a.fld_delstatus='0' 
                                                                            AND a.fld_moduletype='1' AND a.fld_id='".$schid."' AND b.fld_delstatus='0' AND c.fld_flag='1'");
                                                    }
                                                    if($schtype == '6'){
                                                     $schedule_name = $ObjDB->SelectSingleValue("SELECT a.fld_id AS testid, 6 AS typeids, a.fld_schedule_name AS testname
                                                                            FROM itc_class_indassesment_master AS a 
                                                                            LEFT JOIN itc_mathmodule_master AS b ON a.fld_module_id=b.fld_id
                                                                            WHERE a.fld_class_id='".$classid."' AND a.fld_id='".$schid."' AND a.fld_flag='1' AND a.fld_delstatus='0' 
                                                                            AND a.fld_moduletype='2' AND b.fld_delstatus='0'");
                                                    }
                                                    if($schtype == '7'){
                                                     $schedule_name = $ObjDB->SelectSingleValue("SELECT a.fld_id AS testid, 7 AS typeids, a.fld_schedule_name AS testname
                                                                            FROM itc_class_indassesment_master AS a 
                                                                            LEFT JOIN itc_module_master AS b ON a.fld_module_id=b.fld_id
                                                                            WHERE a.fld_class_id='".$classid."' AND a.fld_id='".$schid."' AND a.fld_flag='1' AND a.fld_delstatus='0' 
                                                                            AND a.fld_moduletype='7' AND b.fld_delstatus='0'");
                                                    }
                                                    if($schedule_name!=''){
                                                    ?>
                                                     <span class="selectbox-option input-medium" data-option="" style="width:97%"><?php echo $schedule_name; ?></span>
                                                    <?php 
                                                   }
                                                   else{
                                                    ?>
                                                     <span class="selectbox-option input-medium" data-option="" style="width:97%">Select Assignment</span>
                                                  <?php } ?>
                            
                            <b class="caret1"></b>
                    </a>
                    <div class="selectbox-options">
                            <input type="text" class="selectbox-filter" placeholder="Search Assignment">
                            <ul role="options" style="width:100%">
                                    <?php 
                                  
                                        $qry = $ObjDB->QueryObject("(SELECT a.fld_id AS testid, 0 AS typeids, a.fld_schedule_name AS testname
                                                                            FROM itc_class_sigmath_master AS a 
                                                                            LEFT JOIN itc_class_sigmath_unit_mapping AS b ON a.fld_id = b.fld_sigmath_id 
                                                                            LEFT JOIN itc_unit_master AS c ON c.fld_id = b.fld_unit_id 
                                                                            WHERE a.fld_class_id = '".$classid."' AND a.fld_flag = '1' AND a.fld_delstatus = '0' AND b.fld_flag = '1' AND c.fld_activestatus = '0' 
                                                                            AND c.fld_delstatus = '0') 	
                                                                            UNION ALL		
                                                                            (SELECT a.fld_schedule_id AS testid,(CASE WHEN b.fld_type='1' THEN '1' WHEN b.fld_type='2' THEN '4' END) AS typeids, c.fld_schedule_name AS testname
                                                                            FROM itc_class_rotation_schedulegriddet AS a 
                                                                            LEFT JOIN itc_class_rotation_moduledet AS b ON b.fld_schedule_id=a.fld_schedule_id 
                                                                            left join itc_class_rotation_schedule_mastertemp as c on a.fld_schedule_id=c.fld_id
                                                                            LEFT JOIN itc_class_rotation_scheduledate AS d ON a.fld_schedule_id=d.fld_schedule_id and a.fld_rotation=d.fld_rotation
                                                                            LEFT JOIN itc_class_rotation_schedule_student_mappingtemp as e ON a.fld_schedule_id=e.fld_schedule_id
                                                                            WHERE a.fld_class_id='".$classid."' AND b.fld_flag='1' AND a.fld_flag='1' AND e.fld_flag='1' and c.fld_delstatus='0' AND d.fld_flag='1' 
                                                                            GROUP BY a.fld_schedule_id ) 		
                                                                            UNION ALL	
                                                                            (SELECT a.fld_id AS testid, 15 AS typeids, a.fld_schedule_name AS testname
                                                                            FROM itc_class_indasexpedition_master AS a 
                                                                            LEFT JOIN itc_exp_master AS b ON a.fld_exp_id=b.fld_id
                                                                            WHERE a.fld_class_id='".$classid."' AND a.fld_flag='1' AND a.fld_delstatus='0' 
                                                                            AND b.fld_delstatus='0' 
                                                                            GROUP BY a.fld_id) 
                                                                            UNION ALL
                                                                            (SELECT a.fld_id AS testid, 5 AS typeids, a.fld_schedule_name AS testname 
                                                                            FROM itc_class_indassesment_master AS a 
                                                                            LEFT JOIN itc_module_master AS b ON a.fld_module_id=b.fld_id 
                                                                            LEFT JOIN itc_class_indassesment_student_mapping as c ON a.fld_id=c.fld_schedule_id
                                                                            WHERE a.fld_class_id='".$classid."' AND a.fld_flag='1' AND a.fld_delstatus='0' 
                                                                            AND a.fld_moduletype='1' AND b.fld_delstatus='0' AND c.fld_flag='1'
                                                                            GROUP BY a.fld_id )  		
                                                                            UNION ALL		
                                                                            (SELECT a.fld_id AS testid, 6 AS typeids, a.fld_schedule_name AS testname
                                                                            FROM itc_class_indassesment_master AS a 
                                                                            LEFT JOIN itc_mathmodule_master AS b ON a.fld_module_id=b.fld_id
                                                                            WHERE a.fld_class_id='".$classid."' AND a.fld_flag='1' AND a.fld_delstatus='0' 
                                                                            AND a.fld_moduletype='2' AND b.fld_delstatus='0'
                                                                            GROUP BY a.fld_id)
                                                                            UNION ALL	
                                                                            (SELECT a.fld_id AS testid, 7 AS typeids, a.fld_schedule_name AS testname
                                                                            FROM itc_class_indassesment_master AS a 
                                                                            LEFT JOIN itc_module_master AS b ON a.fld_module_id=b.fld_id
                                                                            WHERE a.fld_class_id='".$classid."' AND a.fld_flag='1' AND a.fld_delstatus='0' 
                                                                            AND a.fld_moduletype='7' AND b.fld_delstatus='0' 
                                                                            GROUP BY a.fld_id)
                                                                            UNION ALL 		
                                                                            (SELECT b.fld_id AS testid, 9 AS typeids, b.fld_test_name AS testname
                                                                            FROM itc_test_student_mapping AS a 
                                                                            LEFT JOIN itc_test_master AS b ON a.fld_test_id=b.fld_id 
                                                                            WHERE a.fld_class_id='".$classid."' AND a.fld_flag='1' AND b.fld_delstatus='0' AND b.fld_ass_type='0'
                                                                            GROUP BY b.fld_id)");


                                                if($qry->num_rows > 0){													
                                                  while($rows = $qry->fetch_assoc()){
                                                      extract($rows);
                                                ?>
                                                <li><a tabindex="-1" href="#" data-option="<?php echo $testid;?>" onclick="fn_showstudent(<?php echo $testid;?>,<?php echo $typeids;?>)"><?php echo $testname; ?></a></li>
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
    $assessid = isset($method['assessid']) ? $method['assessid'] : '';
    $assesstype = isset($method['schtype']) ? $method['schtype'] : '';
    $classid = isset($method['classid']) ? $method['classid'] : '';
   
?> 
    <script type="text/javascript" language="javascript">
        $(function() {
                $('#testrailvisible4').slimscroll({
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
                $('#testrailvisible5').slimscroll({
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
                $("#list3").sortable({
                        connectWith: ".droptrue2",
                        dropOnEmpty: true,
                        items: "div[class='draglinkleft']",
                        receive: function(event, ui) { 
                                $("div[class=draglinkright]").each(function(){ 
                                        if($(this).parent().attr('id')=='list3'){
                                                fn_movealllistitems('list3','list4',$(this).children(":first").attr('id'));
                                        }
                                });											
                        }
                });

                $( "#list4" ).sortable({
                        connectWith: ".droptrue2",
                        dropOnEmpty: true,
                        receive: function(event, ui) { 
                                $("div[class=draglinkleft]").each(function(){ 
                                        if($(this).parent().attr('id')=='list4'){
                                                fn_movealllistitems('list3','list4',$(this).children(":first").attr('id'));
                                        }
                                });								
                        }
                });

        });																	
    </script>  
                            
    <div class="row rowspacer" id="assignmentlist">
      <div class='six columns'>
          <div class="dragndropcol">
            <input type="hidden" name="schetype" id="schetype" value="<?php echo $assesstype; ?>">
              <div class="dragtitle">Students available</div>
                  <div class="draglinkleftSearch" id="s_list3" >
                     <dl class='field row'>
                          <dt class='text'>
                              <input placeholder='Search' type='text' id="list_3_search" name="list_3_search" onKeyUp="search_list(this,'#list3');" />
                          </dt>
                      </dl>
                  </div>
                  <div class="dragWell" id="testrailvisible4" >
                      <div id="list3" class="dragleftinner droptrue2">
                       <?php 	
                      
                        if($assesstype == '0'){
                         $qry = $ObjDB->QueryObject("SELECT a.fld_student_id AS stuid, CONCAT(b.fld_lname, ' ', b.fld_fname) AS studentname 
                                                              FROM itc_class_sigmath_student_mapping AS a 
                                                              LEFT JOIN itc_user_master AS b ON a.fld_student_id=b.fld_id 
                                                              WHERE a.fld_sigmath_id = '".$assessid."' AND a.fld_flag = '1' AND b.fld_activestatus = '1' 
                                                              AND b.fld_delstatus = '0' AND a.fld_student_id NOT IN 
                                                               (SELECT fld_student_id 
                                                                FROM widgets_turnoff_student 
                                                                WHERE fld_class_id = '".$classid."' AND fld_schedule_id = '".$assessid."' AND fld_schedule_type = '".$assesstype."' AND fld_flag = '1')
                                                              ORDER BY studentname");
                        }
                        if($assesstype == '1' || $assesstype == '4'){
                         
                         $qry = $ObjDB->QueryObject("SELECT a.fld_student_id AS stuid, CONCAT(b.fld_lname, ' ', b.fld_fname) AS studentname 
                                                              FROM itc_class_rotation_schedule_student_mappingtemp AS a 
                                                              LEFT JOIN itc_user_master AS b ON a.fld_student_id=b.fld_id 
                                                              WHERE a.fld_schedule_id = '".$assessid."' AND a.fld_flag = '1' AND b.fld_activestatus = '1' 
                                                              AND b.fld_delstatus = '0' AND a.fld_student_id NOT IN 
                                                               (SELECT fld_student_id 
                                                                FROM widgets_turnoff_student 
                                                                WHERE fld_class_id = '".$classid."' AND fld_schedule_id = '".$assessid."' AND fld_schedule_type = '".$assesstype."' AND fld_flag = '1')
                                                              ORDER BY studentname");
                        }
                        if($assesstype == '9'){
                         $qry = $ObjDB->QueryObject("SELECT a.fld_student_id AS stuid, CONCAT(b.fld_lname, ' ', b.fld_fname) AS studentname 
                                                              FROM itc_test_student_mapping AS a 
                                                              LEFT JOIN itc_user_master AS b ON a.fld_student_id=b.fld_id 
                                                              WHERE a.fld_test_id = '".$assessid."' AND a.fld_flag = '1' AND b.fld_activestatus = '1' 
                                                                AND b.fld_delstatus = '0'  AND a.fld_student_id NOT IN 
                                                               (SELECT fld_student_id 
                                                                FROM widgets_turnoff_student 
                                                                WHERE fld_class_id = '".$classid."' AND fld_schedule_id = '".$assessid."' AND fld_schedule_type = '".$assesstype."' AND fld_flag = '1')
                                                              ORDER BY studentname");
                        }
                        if($assesstype == '15'){
                         $qry = $ObjDB->QueryObject("SELECT a.fld_student_id AS stuid, CONCAT(b.fld_lname, ' ', b.fld_fname) AS studentname 
                                                              FROM itc_class_exp_student_mapping AS a 
                                                              LEFT JOIN itc_user_master AS b ON a.fld_student_id=b.fld_id 
                                                              WHERE a.fld_schedule_id = '".$assessid."' AND a.fld_flag = '1' AND b.fld_activestatus = '1' 
                                                                AND b.fld_delstatus = '0'  AND a.fld_student_id NOT IN 
                                                               (SELECT fld_student_id 
                                                                FROM widgets_turnoff_student 
                                                                WHERE fld_class_id = '".$classid."' AND fld_schedule_id = '".$assessid."' AND fld_schedule_type = '".$assesstype."' AND fld_flag = '1')
                                                              ORDER BY studentname");
                        }
                        if($assesstype == '5' || $assesstype == '6' || $assesstype == '7'){
                         $qry = $ObjDB->QueryObject("SELECT a.fld_student_id AS stuid, CONCAT(b.fld_lname, ' ', b.fld_fname) AS studentname 
                                                              FROM itc_class_indassesment_student_mapping AS a 
                                                              LEFT JOIN itc_user_master AS b ON a.fld_student_id=b.fld_id 
                                                              WHERE a.fld_schedule_id = '".$assessid."' AND a.fld_flag = '1' AND b.fld_activestatus = '1' 
                                                                AND b.fld_delstatus = '0'  AND a.fld_student_id NOT IN 
                                                               (SELECT fld_student_id 
                                                                FROM widgets_turnoff_student 
                                                                WHERE fld_class_id = '".$classid."' AND fld_schedule_id = '".$assessid."' AND fld_schedule_type = '".$assesstype."' AND fld_flag = '1')
                                                              ORDER BY studentname");
                        }
                        
                         
                            if($qry->num_rows > 0){													
                              while($rows = $qry->fetch_assoc()){
                                  extract($rows);
                                  ?>
                              <div class="draglinkleft" id="list3_<?php echo $stuid; ?>" name="<?php echo $stuid; ?>">
                                  <div class="dragItemLable tooltip" id="<?php echo $stuid; ?>" title="<?php echo $studentname;?>"><?php echo $studentname; ?></div>
                                  
                                  <div class="clickable" id="clck_<?php echo $stuid;?>" onclick="fn_movealllistitems('list3','list4',<?php echo $stuid;?>,0);"></div>
                              </div>
                      <?php 
                              }
                          }
                      
                      ?>
                      </div>
                  </div>
                <div class="dragAllLink"  onclick="fn_movealllistitems('list3','list4',0,0);" style="cursor: pointer;cursor:hand;width:  120px;float: right;">add all Students</div>
          </div>
      </div>
      <div class='six columns'>
        <div class="dragndropcol">
                  <div class="dragtitle">Students Selected</div>
                  <div class="draglinkleftSearch" id="s_list4" >
                     <dl class='field row'>
                          <dt class='text'>
                              <input placeholder='Search' type='text' id="list_4_search" name="list_4_search" onKeyUp="search_list(this,'#list4');" />
                          </dt>
                      </dl>
                  </div>
                  <div class="dragWell" id="testrailvisible5" >
                      <div id="list4" class="dragleftinner droptrue2">
                       <?php 		
                       if($assesstype == '0'){
                         $qry = $ObjDB->QueryObject("SELECT a.fld_student_id AS stuid, CONCAT(b.fld_lname, ' ', b.fld_fname) AS studentname 
                                                              FROM itc_class_sigmath_student_mapping AS a 
                                                              LEFT JOIN itc_user_master AS b ON a.fld_student_id=b.fld_id 
                                                              WHERE a.fld_sigmath_id = '".$assessid."' AND a.fld_flag = '1' AND b.fld_activestatus = '1' 
                                                              AND b.fld_delstatus = '0' AND a.fld_student_id IN 
                                                               (SELECT fld_student_id 
                                                                FROM widgets_turnoff_student 
                                                                WHERE fld_class_id = '".$classid."' AND fld_schedule_id = '".$assessid."' AND fld_schedule_type = '".$assesstype."' AND fld_flag = '1')
                                                              ORDER BY studentname");
                        }
                        if($assesstype == '1' || $assesstype == '4'){
                         
                         $qry = $ObjDB->QueryObject("SELECT a.fld_student_id AS stuid, CONCAT(b.fld_lname, ' ', b.fld_fname) AS studentname 
                                                              FROM itc_class_rotation_schedule_student_mappingtemp AS a 
                                                              LEFT JOIN itc_user_master AS b ON a.fld_student_id=b.fld_id 
                                                              WHERE a.fld_schedule_id = '".$assessid."' AND a.fld_flag = '1' AND b.fld_activestatus = '1' 
                                                              AND b.fld_delstatus = '0' AND a.fld_student_id IN 
                                                               (SELECT fld_student_id 
                                                                FROM widgets_turnoff_student 
                                                                WHERE fld_class_id = '".$classid."' AND fld_schedule_id = '".$assessid."' AND fld_schedule_type = '".$assesstype."' AND fld_flag = '1')
                                                              ORDER BY studentname");
                        }
                        if($assesstype == '9'){
                         $qry = $ObjDB->QueryObject("SELECT a.fld_student_id AS stuid, CONCAT(b.fld_lname, ' ', b.fld_fname) AS studentname 
                                                              FROM itc_test_student_mapping AS a 
                                                              LEFT JOIN itc_user_master AS b ON a.fld_student_id=b.fld_id 
                                                              WHERE a.fld_test_id = '".$assessid."' AND a.fld_flag = '1' AND b.fld_activestatus = '1' 
                                                                AND b.fld_delstatus = '0'  AND a.fld_student_id IN 
                                                               (SELECT fld_student_id 
                                                                FROM widgets_turnoff_student 
                                                                WHERE fld_class_id = '".$classid."' AND fld_schedule_id = '".$assessid."' AND fld_schedule_type = '".$assesstype."' AND fld_flag = '1')
                                                              ORDER BY studentname");
                        }
                        if($assesstype == '15'){
                         $qry = $ObjDB->QueryObject("SELECT a.fld_student_id AS stuid, CONCAT(b.fld_lname, ' ', b.fld_fname) AS studentname 
                                                              FROM itc_class_exp_student_mapping AS a 
                                                              LEFT JOIN itc_user_master AS b ON a.fld_student_id=b.fld_id 
                                                              WHERE a.fld_schedule_id = '".$assessid."' AND a.fld_flag = '1' AND b.fld_activestatus = '1' 
                                                                AND b.fld_delstatus = '0'  AND a.fld_student_id IN 
                                                               (SELECT fld_student_id 
                                                                FROM widgets_turnoff_student 
                                                                WHERE fld_class_id = '".$classid."' AND fld_schedule_id = '".$assessid."' AND fld_schedule_type = '".$assesstype."' AND fld_flag = '1')
                                                              ORDER BY studentname");
                        }
                        if($assesstype == '5' || $assesstype == '6' || $assesstype == '7'){
                         $qry = $ObjDB->QueryObject("SELECT a.fld_student_id AS stuid, CONCAT(b.fld_lname, ' ', b.fld_fname) AS studentname 
                                                              FROM itc_class_indassesment_student_mapping AS a 
                                                              LEFT JOIN itc_user_master AS b ON a.fld_student_id=b.fld_id 
                                                              WHERE a.fld_schedule_id = '".$assessid."' AND a.fld_flag = '1' AND b.fld_activestatus = '1' 
                                                                AND b.fld_delstatus = '0'  AND a.fld_student_id IN 
                                                               (SELECT fld_student_id 
                                                                FROM widgets_turnoff_student 
                                                                WHERE fld_class_id = '".$classid."' AND fld_schedule_id = '".$assessid."' AND fld_schedule_type = '".$assesstype."' AND fld_flag = '1')
                                                              ORDER BY studentname");
                        }
                        if($qry->num_rows > 0){													
                              while($rows= $qry->fetch_assoc()){
                                  extract($rows);
                                  ?>
                              <div class="draglinkright" id="list4_<?php echo $stuid; ?>" >
                                  <div class="dragItemLable tooltip" id="<?php echo $stuid; ?>" title="<?php echo $studentname;?>"><?php echo $studentname; ?></div>
                                  <div class="clickable" id="clck_<?php echo $stuid;?>" onclick="fn_movealllistitems('list3','list4',<?php echo $stuid;?>,0); "></div>
                              </div>
                      <?php 
                              }
                          }
                      ?>
                      </div>
                  </div>
                <div class="dragAllLink"  onclick="fn_movealllistitems('list4','list3',0,0);" style="cursor: pointer;cursor:hand;width:  144px;float: right;">remove all Students</div>
         
          </div>
      </div>
    </div>  


<?php
 
}


/***********Turn off Widgets Individually Code start here****************/
if($oper=="saveind" and $oper != " " )
{
    $widgetid = isset($method['widgetid']) ? $method['widgetid'] : '';

    $widget_id=explode(",",$widgetid);
    $ObjDB->NonQuery("DELETE FROM widgets_turnoff_individual WHERE fld_created_by='".$uid."'");   //update already exist    
    for($i=0;$i<sizeof($widget_id);$i++)
    {
            
            $count=$ObjDB->SelectSingleValueInt("SELECT fld_id FROM widgets_turnoff_individual WHERE fld_created_by='".$uid."' AND fld_widget_id='".$widget_id[$i]."'"); // check count
           
            if($count=='0' OR $count=='')
            { 
              
                $ObjDB->NonQuery("INSERT INTO widgets_turnoff_individual
                                                             (fld_widget_id, fld_created_by, fld_created_date, fld_flag)	
                                                                             VALUES('".$widget_id[$i]."', '".$uid."', '".$date."', '1')");
           
            }
            else
            {
              
                $ObjDB->NonQuery("UPDATE widgets_turnoff_content 
                                    SET fld_flag='1',fld_updated_by='".$uid."',fld_updated_date='".$date."'
                                    WHERE fld_id='".$count."' ");           //update already exist        
            }
            
    }
    
}
/***********Turn off Widgets Individually Code End here****************/

/*********Turn off Widgets per Content Code start here************/
if($oper=="savecont" and $oper != " " )
{
    $contentids= isset($method['contentids']) ? $method['contentids'] : '';
  
    $contentid=explode(",",$contentids);
   
    $ObjDB->NonQuery("UPDATE widgets_turnoff_content 
                                         SET fld_flag='0',fld_updated_by='".$uid."',fld_updated_date='".$date."'
                                                   WHERE fld_created_by='".$uid."'");    
    
    for($i=0;$i<sizeof($contentid);$i++)
    {
        $count=$ObjDB->SelectSingleValueInt("SELECT fld_id FROM widgets_turnoff_content WHERE fld_created_by='".$uid."' AND fld_content_id='".$contentid[$i]."'"); // check count

        if($count=='0' OR $count=='')
        { 

            $ObjDB->NonQuery("INSERT INTO widgets_turnoff_content
                                    (fld_content_id, fld_created_by, fld_created_date, fld_flag)	
                                VALUES('".$contentid[$i]."', '".$uid."', '".$date."', '1')");
            //insert if not exist

        }  
        else
        {
            $ObjDB->NonQuery("UPDATE widgets_turnoff_content 
                                SET fld_flag='1',fld_updated_by='".$uid."',fld_updated_date='".$date."'
                                WHERE fld_id='".$count."' ");           //update already exist        

        }
    }
}
/*********Turn off Widgets per Content Code End here************/

/********Turn Off Based On Student Code Start here***************/
if($oper=="savestu" and $oper != " " )
{
    $classid = isset($method['classid']) ? $method['classid'] : '';
    $schedulid = isset($method['assessid']) ? $method['assessid'] : '';
    $schtype = isset($method['schtype']) ? $method['schtype'] : '';
    $studids = isset($method['stuid']) ? $method['stuid'] : '';

  
    $studid=explode(",",$studids);
    
    $ObjDB->NonQuery("UPDATE widgets_turnoff_student 
                                         SET fld_flag='0',fld_updated_by='".$uid."',fld_updated_date='".$date."'
                                                   WHERE fld_class_id='".$classid."' AND fld_created_by='".$uid."'");    
    
    for($i=0;$i<sizeof($studid);$i++)
    {
        $count=$ObjDB->SelectSingleValueInt("SELECT fld_id FROM widgets_turnoff_student WHERE fld_created_by='".$uid."' AND fld_schedule_id='".$schedulid."' AND fld_class_id='".$classid."' AND fld_student_id='".$studid[$i]."'"); // check count

        if($count=='0' OR $count=='')
        { 
          
            $ObjDB->NonQuery("INSERT INTO widgets_turnoff_student
                                    (fld_class_id, fld_schedule_id, fld_schedule_type, fld_student_id, fld_created_by, fld_created_date, fld_flag)	
                                VALUES('".$classid."', '".$schedulid."', '".$schtype."', '".$studid[$i]."','".$uid."', '".$date."', '1')");
            //insert if not exist

        }  
        else
        {
            $ObjDB->NonQuery("UPDATE widgets_turnoff_student 
                                SET fld_flag='1',fld_updated_by='".$uid."',fld_updated_date='".$date."'
                                WHERE fld_id='".$count."' ");           //update already exist        

        }
    }
}
/********Turn Off Based On Student Code End here***************/




@include("footer.php");
