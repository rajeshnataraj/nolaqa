<?php 
@include("sessioncheck.php");

$date = date("Y-m-d H:i:s");
$date1 = date("Y-m-d 00:00:00");
$oper = isset($method['oper']) ? $method['oper'] : '';


if($oper=="showstudent" and $oper != " " )
{
$gid = isset($method['gid']) ? $method['gid'] : '';

?> 
    <script type="text/javascript" language="javascript">
        $(function() {
                $('#testrailvisible0').slimscroll({
                        width: '410px',
                        height:'366px',
                        size: '7px',
                        alwaysVisible: true,
                        wheelstep: 1,
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
                        size: '7px',
                        alwaysVisible: true,
                        wheelstep: 1,
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
             $qrystudent= $ObjDB->QueryObject("SELECT a.fld_id as studentid,CONCAT(a.fld_fname, ' ', a.fld_lname) AS sname,b.fld_field_id,b.fld_field_value
                                                FROM itc_user_master as a
                                                LEFT JOIN itc_user_add_info as b ON b.fld_user_id = a.fld_id
                                                WHERE a.fld_profile_id = '10' and a.fld_delstatus = '0' and a.fld_school_id = '".$schoolid."' and b.fld_field_id = '12' and b.fld_field_value = '".$gid."' ORDER BY sname");//b.fld_student_id NOT IN(SELECT fld_student_id FROM itc_exp_rubric_rpt_stu_mapping where fld_rubric_rpt_id='".$rubricid."' AND fld_delstatus='0') AND
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
                                  <div class="dragItemLable tooltip" id="<?php echo $studentid; ?>" title="<?php echo $username;?>"><?php echo $sname; ?></div>
                                  <div class="clickable" id="clck_<?php echo $studentid;?>" onclick="fn_movealllistitems('list9','list10',<?php echo $studentid;?>);"></div>
                              </div>
                      <?php 
                              }
                          }
                      ?>
                      </div>
                  </div>
                <div class="dragAllLink"  onclick="fn_movealllistitems('list9','list10',0,0);">add all students</div>
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
                          
                      </div>
                  </div>
                <div class="dragAllLink" onclick="fn_movealllistitems('list10','list9',0,0);">remove all students</div>
          </div>
      </div>
    </div> 
     
<?php
}


/*--- Save and Update the Rubric ---*/
if($oper=="savestdgrade")
{
    try{
        
        $upgid = isset($method['upgid']) ? $method['upgid'] : '0'; 
        $studentid = isset($method['list10']) ? ($method['list10']) : '0'; 
         
        
        $stuid=explode(",",$studentid);
        
        for($i=0;$i<sizeof($stuid);$i++)
        {
            $ObjDB->NonQuery("UPDATE itc_user_add_info 
                                 SET fld_field_value='".$upgid."' 
                                 WHERE fld_user_id='".$stuid[$i]."' AND fld_field_id = '12' AND fld_delstatus='0'");
           
        }
        echo "success";
    }
    catch(Exception $e)
    {
        echo "fail";
    }
}

