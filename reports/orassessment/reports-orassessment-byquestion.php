<?php
@include("sessioncheck.php");

/*
    Created By - Mohan. M
    Created Date : 8-6-2015
*/

error_reporting(E_ALL);
ini_set('display_errors', '1');

$id = isset($method['id']) ? $method['id'] : '0';
$id = explode(",",$id);

if($id[0] =='1')
{
    $title="Open Response Assessment By Question";
}
else if($id[0] =='2')
{
    $title="Open Response Assessment By Student";
}
else
{
     $title="Open Response Assessment By Standard";
}  
?>



<section data-type='#reports-orassessment' id='reports-orassessment-byquestion'>
    <script language="javascript">
        $.getScript("reports/orassessment/reports-orassessment.js");
    </script>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle"><?php echo $title; ?></p>
                <p class="dialogSubTitleLight">Customize your report below, then click "View Report".</p>
            </div>
        </div>
        
        <div class='row formBase rowspacer'>
            <div class='eleven columns centered insideForm'>
            	<div class='row rowspacer'>
                        <script type="text/javascript" language="javascript">
                            $(function() {
                                    $('#testrailvisible1').slimscroll({
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
                                    $('#testrailvisible2').slimscroll({
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
                                    $("#list3").sortable({
                                            connectWith: ".droptrue5",
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
                                            connectWith: ".droptrue5",
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

                          <div class='six columns'>
                              <div class="dragndropcol">
                                <?php 
                                
                                $qry = $ObjDB->QueryObject("SELECT  b.fld_id AS testid ,b.fld_test_name AS testname FROM itc_test_questionassign AS a
                                                                    LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                    LEFT JOIN itc_question_details AS c ON a.fld_question_id = c.fld_id
                                                                    WHERE a.fld_delstatus='0' AND a.fld_created_by='".$uid."'  AND b.fld_delstatus='0' AND b.fld_created_by='".$uid."' 	AND "
                                        . "                         fld_answer_type='15'  AND c.fld_created_by='".$uid."' AND c.fld_delstatus='0' AND b.fld_school_id='".$schoolid."'
                                                                    GROUP BY testid ORDER BY testname");


                               ?>
                               <div class="dragtitle">   &nbsp;&nbsp;&nbsp;Assessments available (<span id="nostudentleftdiv"> <?php echo $qry->num_rows;?></span>)</div>
                                <div class="draglinkleftSearch" id="s_list3" >
                                    <dl class='field row'>
                                         <dt class='text'>
                                             <input placeholder='Search' type='text' id="list_5_search" name="list_5_search" onKeyUp="search_list(this,'#list3');" />
                                         </dt>
                                    </dl>
                                </div>
                                <div class="dragWell" id="testrailvisible1" >
                                    <div id="list3" class="dragleftinner droptrue1">
                                     <?php 		
                                       if($qry->num_rows>0){												
                                            while($rowqryass = $qry->fetch_assoc()){
                                                extract($rowqryass);
                                                ?>
                                            <div class="draglinkleft" id="list3_<?php echo $testid; ?>" >
                                                <div class="dragItemLable tooltip" id="<?php echo $testid; ?>" title="<?php echo $testname;?>"><?php echo $testname; ?></div>
                                                <div class="clickable" id="clck_<?php echo $testid;?>" onclick="fn_movealllistitems('list3','list4',<?php echo $testid;?>,<?php echo $testid; ?>);"></div>
                                            </div>
                                    <?php 
                                            }
                                        }
                                    ?>
                                    </div>
                                </div>
                            <div class="dragAllLink"  onclick="fn_movealllistitems('list3','list4',0,0);" style="cursor: pointer;cursor:hand;width:  410px;">add all Assessments</div>
                              </div>
                          </div>
                          <div class='six columns'>
                            <div class="dragndropcol">
                                    <div class="dragtitle">Assessments Selected</div> 
                                      <div class="draglinkleftSearch" id="s_list4" >
                                         <dl class='field row'>
                                              <dt class='text'>
                                                  <input placeholder='Search' type='text' id="list_6_search" name="list_6_search" onKeyUp="search_list(this,'#list4');" />
                                              </dt>
                                          </dl>
                                      </div>
                                      <div class="dragWell" id="testrailvisible2" >
                                          <div id="list4" class="dragleftinner droptrue1">
                                           <?php 		
                                             if($qry->num_rows>0){												
                                            while($sturows = $qry->fetch_assoc()){
                                                extract($sturows);
                                                      ?>
                                                  <div class="draglinkright" id="list4_<?php echo $testid; ?>" >
                                                      <div class="dragItemLable tooltip" id="<?php echo $testid; ?>" title="<?php echo $testname;?>"><?php echo $testname; ?></div>
                                                      <div class="clickable" id="clck_<?php echo $testid;?>" onclick="fn_movealllistitems('list3','list4',<?php echo $testid;?>,<?php echo $testid; ?>);"></div>
                                                  </div>
                                          <?php 
                                                  }
                                              }
                                          ?>
                                          </div>
                                      </div>
                                    <div class="dragAllLink"  onclick="fn_movealllistitems('list4','list3',0,0);" style="cursor: pointer;cursor:hand;width:  180px;float: right;">remove all Assessments</div>

                              </div>
                          </div>
                </div>
                
                <input type="hidden" id="hidfilename" name="hidstudent" value="<?php echo "bystudentsreport_"; ?>" />  
                <input type="hidden" id="hidquestn" name="hidquestn" value="<?php echo "byquestionreport_"; ?>" />  
                <input type="hidden" id="hidquestand" name="hidquestand" value="<?php echo "bystandardreport_"; ?>" />  
                
                <!--View Report Button-->
                <div class='row rowspacer' style="display:none" id="viewreportdiv">
                    <input class="darkButton" type="button" id="btnstep" style="width:200px; height:42px; float:right;" value="View Report" onClick="fn_viewreport('<?php echo $uid; ?>','<?php echo $id[0]; ?>');" />
                </div>
            </div>
        </div>
    </div>
</section>
<?php
	@include("footer.php");