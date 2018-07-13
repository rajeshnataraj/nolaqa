<?php
@include("sessioncheck.php");

/*
    Created By - Mohan. M
    Created Date : 2-7-2015
*/
?>
<section data-type='#reports-assesmentreports' id='reports-assesmentreports-classmastery'>
    <script language="javascript">
      $.getScript("reports/assesmentreports/reports-assesmentreport-classmastery.js");
    </script>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Class Mastery Report</p>
                <p class="dialogSubTitleLight">Customize your report below, then press View Report.</p>
            </div>
        </div>
        
        <div class='row formBase rowspacer'>
            <div class='eleven columns centered insideForm'>
            	<div class='row rowspacer'>
		<?php if($sessmasterprfid==9 or $sessmasterprfid==8) { ?>
                    
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
                                

                                $qry = $ObjDB->QueryObject("SELECT f.classid,f.classname FROM (SELECT a.fld_id AS classid, a.fld_class_name AS classname
                                                                        FROM itc_class_master as a
                                                                        LEFT JOIN itc_test_student_mapping as b ON a.fld_id = b.fld_class_id 
                                                                        WHERE fld_archive_class='0' AND a.fld_delstatus = '0' AND b.fld_created_by='".$uid."' AND b.fld_flag='1'
                                                                        AND (a.fld_created_by = '".$uid."' OR a.fld_id IN (SELECT fld_class_id
                                                                        FROM itc_class_teacher_mapping WHERE fld_teacher_id = '".$uid."' AND fld_flag = '1'))
                                                                        UNION ALL
                                                                        SELECT b.fld_class_id AS classid,c.fld_class_name AS classname FROM itc_test_master AS a
                                                                        LEFT JOIN itc_class_indasexpedition_master AS b ON a.fld_expt=b.fld_exp_id
                                                                        LEFT JOIN itc_class_master AS c ON b.fld_class_id=c.fld_id

                                                                        WHERE a.fld_ass_type='1' AND a.fld_created_by='".$uid."' AND b.fld_createdby='".$uid."'
                                                                        AND c.fld_archive_class='0' AND c.fld_delstatus='0' AND a.fld_delstatus = '0' AND b.fld_delstatus='0' AND b.fld_flag='1' 
                                                                        UNION ALL

                                                                        SELECT d.fld_class_id AS classid,c.fld_class_name AS classname FROM itc_test_master AS a
                                                                        LEFT JOIN itc_class_indasmission_master AS d ON a.fld_mist=d.fld_mis_id
                                                                        LEFT JOIN itc_class_master AS c ON d.fld_class_id=c.fld_id

                                                                        WHERE a.fld_ass_type='2' AND a.fld_created_by='".$uid."' AND d.fld_createdby='".$uid."'
                                                                        AND c.fld_archive_class='0' AND c.fld_delstatus='0' AND a.fld_delstatus = '0' AND d.fld_delstatus='0' AND d.fld_flag='1') as f GROUP BY classid ORDER BY classname");


                                ?>
                               <div class="dragtitle">   &nbsp;&nbsp;&nbsp;Class available (<span id="nostudentleftdiv"> <?php echo $qry->num_rows;?></span>)</div>
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
                                            while($rowqryclassmap = $qry->fetch_assoc()){
                                                extract($rowqryclassmap);
                                                ?>
                                            <div class="draglinkleft" id="list3_<?php echo $classid; ?>" >
                                                <div class="dragItemLable tooltip" id="<?php echo $classid; ?>" title="<?php echo $classname;?>"><?php echo $classname; ?></div>
                                                <div class="clickable" id="clck_<?php echo $classid;?>" onclick="fn_movealllistitems('list3','list4',<?php echo $classid;?>,<?php echo $classid; ?>);"></div>
                                            </div>
                                    <?php 
                                            }
                                        }
                                    ?>
                                    </div>
                                </div>
                            <div class="dragAllLink"  onclick="fn_movealllistitems('list3','list4',0,0);" style="cursor: pointer;cursor:hand;width:  410px;">add all Class</div>
                              </div>
                          </div>
                          <div class='six columns'>
                            <div class="dragndropcol">
                                    <div class="dragtitle">Selected Class</div> 
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
                                                  <div class="draglinkright" id="list4_<?php echo $classid; ?>" >
                                                      <div class="dragItemLable tooltip" id="<?php echo $classid; ?>" title="<?php echo $classname;?>"><?php echo $classname; ?></div>
                                                      <div class="clickable" id="clck_<?php echo $classid;?>" onclick="fn_movealllistitems('list3','list4',<?php echo $classid;?>,<?php echo $classid; ?>);"></div>
                                                  </div>
                                          <?php 
                                                  }
                                              }
                                          ?>
                                          </div>
                                      </div>
                                    <div class="dragAllLink"  onclick="fn_movealllistitems('list4','list3',0,0);" style="cursor: pointer;cursor:hand;width:  180px;float: right;">remove all Class</div>

                              </div>
                          </div>
                    <?php } ?>
                </div>
                
                <div class="row rowspacer">
                    <div class='twelve columns'> 
                        <div id="assessmentdiv" style="">

                        </div>
                    </div>
                </div>
                
                 <!---Show Standards -->                
                    <div class="row rowspacer">
                        <div class='twelve columns'> 
                            <div id="standardsdiv" style="display:none">Show Standards
                                <input type="radio" id="tag" name="types" checked="checked" value="1" onclick="getradioval(1);" />Yes
                                <input type="radio" id="search" name="types" value="0" onclick="getradioval(0);" />No
                            </div>
                        </div>
                    </div>
                <!---Show Standards -->  
                
                <input type="hidden" id="hidfilename" name="hidfilename" value="<?php echo "classmasteryreport_"; ?>" />
                   <input type="hidden" id="hidradioval" name="hidradioval" value="1" />
                <!--View Report Button-->
                <div class='row rowspacer' style="display:none" id="viewreportdiv">
                	
                    <input class="darkButton" type="button" id="btnstep" style="width:200px; height:42px; float:right;" value="View Report" onClick="fn_viewclassmastery('<?php echo $uid; ?>');" />
                </div>
            </div>
        </div>
    </div>
</section>
<?php
	@include("footer.php");