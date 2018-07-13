<?php 
@include("sessioncheck.php");

/*
	Created By - SenthilNathan. S
		
*/

$date = date("Y-m-d H:i:s");
$oper = isset($method['oper']) ? $method['oper'] : '';



if($oper=="showassessment" and $oper != " " )
{
	$classid = isset($method['classid']) ? $method['classid'] : '';
	?>
    <script type="text/javascript" language="javascript">
        $(function() {
                $('#testrailvisible4').slimscroll({
                        width: '410px',
                        height:'366px',
                        size: '7px',
                        alwaysVisible: true,
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
                        size: '7px',
                        alwaysVisible: true,
                        railVisible: true,
                        allowPageScroll: false,
                        railColor: '#F4F4F4',
                        opacity: 1,
                        color: '#d9d9d9',
                        wheelStep: 1,
                });
                $("#list5").sortable({
                        connectWith: ".droptrue5",
                        dropOnEmpty: true,
                        items: "div[class='draglinkleft']",
                        receive: function(event, ui) { 
                                $("div[class=draglinkright]").each(function(){ 
                                        if($(this).parent().attr('id')=='list5'){
                                                fn_movealllistitems('list5','list6',$(this).children(":first").attr('id'));
                                        }
                                });											
                        }
                });

                $( "#list6" ).sortable({
                        connectWith: ".droptrue5",
                        dropOnEmpty: true,
                        receive: function(event, ui) { 
                                $("div[class=draglinkleft]").each(function(){ 
                                        if($(this).parent().attr('id')=='list6'){
                                                fn_movealllistitems('list5','list6',$(this).children(":first").attr('id'));
                                        }
                                });								
                        }
                });

             
        });																	
    </script>  
              
   <div class="row rowspacer" id="assessmentdiv">
      <div class='six columns'>
          <div class="dragndropcol">
            <?php 
            
             $qry = $ObjDB->QueryObject("SELECT f.testid,f.testname,f.testtype FROM (SELECT a.fld_test_id AS testid,b.fld_test_name AS testname,b.fld_ass_type AS testtype FROM itc_test_student_mapping AS a
                                                    LEFT JOIN itc_test_master AS b ON b.fld_id=a.fld_test_id
                                                    WHERE a.fld_class_id='".$classid."' AND a.fld_flag='1' AND a.fld_created_by='".$uid."' AND b.fld_delstatus='0'
                                                    UNION ALL
                                                    SELECT a.fld_test_id AS testid,b.fld_test_name AS testname,b.fld_ass_type AS testtype FROM itc_test_inline_student_mapping AS a
                                                    LEFT JOIN itc_test_master AS b ON b.fld_id=a.fld_test_id
                                                    WHERE a.fld_class_id='".$classid."' AND a.fld_flag='1' AND a.fld_created_by='".$uid."' AND b.fld_delstatus='0'
                                                    ) AS f
                                                    GROUP BY testid ORDER BY testname");  

              
    
           ?>
        <div class="dragtitle">Assessments available</div>
            <div class="draglinkleftSearch" id="s_list5" >
                <dl class='field row'>
                     <dt class='text'>
                         <input placeholder='Search' type='text' id="list_5_search" name="list_5_search" onKeyUp="search_list(this,'#list5');" />
                     </dt>
                </dl>
            </div>
            <div class="dragWell" id="testrailvisible4" >
                <div id="list5" class="dragleftinner droptrue1">
                 <?php 		
                   if($qry->num_rows>0){												
                        while($sturows = $qry->fetch_assoc()){
                            extract($sturows);
                            ?>

                        <div class="draglinkleft" id="list5_<?php echo $testid; ?>" name="<?php echo $testid; ?>-<?php echo $testtype; ?>" >

                            <div class="dragItemLable tooltip" id="<?php echo $testid; ?>" title="<?php echo $testname;?>"><?php echo $testname; ?></div>
                            <div class="clickable" id="clck_<?php echo $testid;?>" onclick="fn_movealllistitems('list5','list6',<?php echo $testid;?>,<?php echo $testid; ?>);"></div>
                        </div>
                <?php 
                        }
                    }
                ?>
                </div>
            </div>
        <div class="dragAllLink"  onclick="fn_movealllistitems('list5','list6',0,0);" style="cursor: pointer;cursor:hand;width:  410px;">add all Assessments</div>
          </div>
      </div>
      <div class='six columns'>
        <div class="dragndropcol">
                  <div class="dragtitle">Selected Assessments</div>
                  <div class="draglinkleftSearch" id="s_list6" >
                     <dl class='field row'>
                          <dt class='text'>
                              <input placeholder='Search' type='text' id="list_6_search" name="list_6_search" onKeyUp="search_list(this,'#list6');" />
                          </dt>
                      </dl>
                  </div>
                  <div class="dragWell" id="testrailvisible5" >
                      <div id="list6" class="dragleftinner droptrue1">
                       <?php 		

                         if($qry->num_rows>0){			
                        while($sturows = $qry->fetch_assoc()){
                            extract($sturows);
                                  ?>
                              <div class="draglinkright" id="list6_<?php echo $testid; ?>" name="<?php echo $testid; ?>-<?php echo $testtype; ?>" >

                                  <div class="dragItemLable tooltip" id="<?php echo $testid; ?>" title="<?php echo $testname;?>"><?php echo $testname; ?></div>
                                  <div class="clickable" id="clck_<?php echo $testid;?>" onclick="fn_movealllistitems('list5','list6',<?php echo $testid;?>,<?php echo $testid; ?>);"></div>
                              </div>
                      <?php 
                              }
                          }
                      ?>
                      </div>
                  </div>
                <div class="dragAllLink"  onclick="fn_movealllistitems('list6','list5',0,0);" style="cursor: pointer;cursor:hand;width:  180px;float: right;">remove all Assessments</div>
         
          </div>
      </div>
    </div>  

<?php
}




/*--- Load Student list ---*/


if($oper=="showstudent" and $oper != " " )
{
	$classid = isset($method['classid']) ? $method['classid'] : '';

	$test_id = isset($method['testid']) ? $method['testid'] : ''; 

        $testid = explode(",",$test_id);
	?>
    <script type="text/javascript" language="javascript">
        $(function() {
                $('#testrailvisible6').slimscroll({
                        width: '410px',
                        height:'366px',
                        size: '7px',
                        alwaysVisible: true,
                        railVisible: true,
                        allowPageScroll: false,
                        railColor: '#F4F4F4',
                        opacity: 1,
                        color: '#d9d9d9',
                        wheelStep: 1,
        
                });
                $('#testrailvisible7').slimscroll({
                        width: '410px',
                        height:'366px',
                        size: '7px',
                        alwaysVisible: true,
                        railVisible: true,
                        allowPageScroll: false,
                        railColor: '#F4F4F4',
                        opacity: 1,
                        color: '#d9d9d9',
                        wheelStep: 1,
                });
                $("#list7").sortable({
                        connectWith: ".droptrue5",
                        dropOnEmpty: true,
                        items: "div[class='draglinkleft']",
                        receive: function(event, ui) { 
                                $("div[class=draglinkright]").each(function(){ 
                                        if($(this).parent().attr('id')=='list7'){
                                                fn_movealllistitems('list7','list8',$(this).children(":first").attr('id'));
                                        }
                                });											
                        }
                });

                $( "#list8" ).sortable({
                        connectWith: ".droptrue5",
                        dropOnEmpty: true,
                        receive: function(event, ui) { 
                                $("div[class=draglinkleft]").each(function(){ 
                                        if($(this).parent().attr('id')=='list8'){
                                                fn_movealllistitems('list7','list8',$(this).children(":first").attr('id'));
                                        }
                                });								
                        }
                });
                
        });																	
    </script>  
              
   <div class="row rowspacer" id="studentlist">
      <div class='six columns'>
          <div class="dragndropcol">
            
        <div class="dragtitle">Students available</div>
            <div class="draglinkleftSearch" id="s_list7" >
                <dl class='field row'>
                     <dt class='text'>
                         <input placeholder='Search' type='text' id="list_7_search" name="list_7_search" onKeyUp="search_list(this,'#list7');" />
                     </dt>
                </dl>
            </div>
            <div class="dragWell" id="testrailvisible6" >
                <div id="list7" class="dragleftinner droptrue1">
                 <?php 

                    $qry = $ObjDB->QueryObject("SELECT f.studentname,f.studentid FROM (SELECT CONCAT(a.fld_fname,' ',a.fld_lname) AS studentname, a.fld_id AS studentid FROM itc_user_master AS a 
                                                                LEFT JOIN itc_test_student_mapping AS b ON a.fld_id=b.fld_student_id 
                                                                WHERE a.fld_activestatus='1' AND a.fld_delstatus='0' 
                                                                AND b.fld_class_id='".$classid."' AND b.fld_flag='1' 
                                                                AND b.fld_test_id IN (".$test_id.")
                                                                UNION ALL
                                                                SELECT CONCAT(a.fld_fname,' ',a.fld_lname) AS studentname, a.fld_id AS studentid FROM itc_user_master AS a 
                                                                LEFT JOIN itc_test_inline_student_mapping AS b ON a.fld_id=b.fld_student_id 
                                                                WHERE a.fld_activestatus='1' AND a.fld_delstatus='0' 
                                                                AND b.fld_class_id='".$classid."' AND b.fld_flag='1' 
                                                                AND b.fld_test_id IN (".$test_id.")
                                                                ) AS f
                                                                GROUP BY studentid ORDER BY studentname");

				if($qry->num_rows>0){
                            while($sturows = $qry->fetch_assoc()){
                                 extract($sturows);
                                    ?>
                        <div class="draglinkleft" id="list7_<?php echo $studentid; ?>" >
                            <div class="dragItemLable tooltip" id="<?php echo $studentid; ?>" title="<?php echo $studentname;?>"><?php echo $studentname; ?></div>
                            <div class="clickable" id="clck_<?php echo $studentid;?>" onclick="fn_movealllistitems('list7','list8',<?php echo $studentid;?>,<?php echo $studentid; ?>);"></div>
                        </div>
                                    <?php 
                                         }
                       }
						?>
                </div>
            </div>
        <div class="dragAllLink"  onclick="fn_movealllistitems('list7','list8',0,0);" style="cursor: pointer;cursor:hand;width:  145px;float: right;">add all Students</div>
          </div>
      </div>
      <div class='six columns'>
        <div class="dragndropcol">
                  <div class="dragtitle">Selected Students</div>
                  <div class="draglinkleftSearch" id="s_list8" >
                     <dl class='field row'>
                          <dt class='text'>
                              <input placeholder='Search' type='text' id="list_8_search" name="list_8_search" onKeyUp="search_list(this,'#list8');" />
                          </dt>
                      </dl>
                  </div>
                  <div class="dragWell" id="testrailvisible7" >
                      <div id="list8" class="dragleftinner droptrue1">
						<?php
                         if($qry->num_rows>0){												
                        while($sturows = $qry->fetch_assoc()){
                            extract($sturows);
                                  ?>
                              <div class="draglinkright" id="list8_<?php echo $studentid; ?>" >
                                  <div class="dragItemLable tooltip" id="<?php echo $studentid; ?>" title="<?php echo $studentname;?>"><?php echo $studentname; ?></div>
                                  <div class="clickable" id="clck_<?php echo $studentid;?>" onclick="fn_movealllistitems('list7','list8',<?php echo $studentid;?>,<?php echo $studentid; ?>);"></div>
                              </div>
                      <?php 
					}
                          }
                      ?>
		</div>
	</div>
                <div class="dragAllLink"  onclick="fn_movealllistitems('list8','list7',0,0);" style="cursor: pointer;cursor:hand;width:  180px;float: right;">remove all Students</div>
         
          </div>
      </div>
    </div>  

	<?php
}





@include("footer.php");