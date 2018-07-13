<?php
	@include("sessioncheck.php");
     
        $menuid= isset($method['id']) ? $method['id'] : '';
        $sid = isset($method['sid']) ? $method['sid'] : '0';
?>

<script type="text/javascript">
    // test-testassign-testteacher.php
</script>

<section data-type='#test-testassign' id='test-testassign'>
    <div class='container'>
        	<div class='row'>
                <div class="span10">
                    <p class="darkTitle">Add an Assessment from other user</p>
                    <p class="dialogSubTitleLight"></p>
                </div>
        	</div>
        <div class='row rowspacer'>
            <div class='twelve columns formBase'>
                <div class='row'>
                	<div class='eleven columns centered insideForm'>
                            <form  id="createlicense" name="createlicense" method='post'>
                            <script language="javascript" type="text/javascript">
                                $.getScript('test/testassign/test-testassign-testteacher.js');
                                    $(function() {
                                            $('div[id^="testrailvisible"]').each(function(index, element) {
                                                    $(this).slimscroll({ /*------- Scroll for Modules Left Box ------*/
                                                            width: '410px',
                                                            height:'366px',
                                                            size: '3px',
                                                            railVisible: true,
                                                            allowPageScroll: false,
                                                            railColor: '#F4F4F4',
                                                            opacity: 1,
                                                            color: '#d9d9d9',   
                                                            wheelStep: 1
                                                    });
                                            });

                                            /* drag and sort for the first left box - Teachers */	
                                            $("#list1").sortable({
                                                    connectWith: ".droptrue",
                                                    dropOnEmpty: true,
                                                    items: "div[class='draglinkleft']",
                                                    receive: function(event, ui) {
                                                            $("div[class=draglinkright]").each(function(){ 
                                                                    if($(this).parent().attr('id')=='list1'){
                                                                            fn_movealllistitems('list1','list2',$(this).children(":first").attr('id'));
                                                                    }
                                                            });
                                                    }
                                            });
                                            /* drag and sort for the first right box - Teachers */	
                                            $( "#list2" ).sortable({
                                                    connectWith: ".droptrue",
                                                    dropOnEmpty: true,
                                                    receive: function(event, ui) {
                                                            $("div[class=draglinkleft]").each(function(){ 
                                                                    if($(this).parent().attr('id')=='list2'){
                                                                            fn_movealllistitems('list1','list2',$(this).children(":first").attr('id'));
                                                                    }
                                                            });
                                                    }
                                            });
                                    });
                            </script> 
                               
                            <div class='row'>
                                <div class='six columns'>
                                    <div class="dragndropcol">
                                    	<div class="dragtitle">School Assessments</div>
                                        <div class="draglinkleftSearch" id="s_list1" >
                                            <dl class='field row'>
                                                <dt class='text'>
                                                    <input placeholder='Search' type='text' id="list_1_search" name="list_1_search" onKeyUp="search_list(this,'#list1');" />
                                                </dt>
                                            </dl>
                                        </div>
                                        <div class="dragWell" id="testrailvisible1" >
                                            <div id="list1" class="dragleftinner droptrue">
						<?php 
                                                    $checkqryschoolcreate = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_id) FROM itc_user_master WHERE fld_school_id='".$schoolid."' AND fld_district_id='".$sendistid."' AND fld_profile_id='7' AND fld_delstatus='0' ");
                                                    
                                                    $checkqryschool = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_school_id) FROM itc_license_track WHERE fld_district_id='".$sendistid."' AND fld_school_id NOT IN(0)  AND fld_delstatus='0'");
                                                    
                                                    $otherteachid=$ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_id) FROM itc_user_master WHERE fld_school_id IN(".$checkqryschool.") AND fld_district_id='".$sendistid."' AND fld_profile_id='9'");
                                                    
                                                    $qry = $ObjDB->QueryObject("SELECT fld_id AS testid,fld_test_name AS testname,fld_created_by FROM itc_test_master 
                                                                                            WHERE fld_school_id IN(".$checkqryschool.") AND fld_delstatus='0' AND fld_created_by NOT IN(".$uid.",".$otherteachid.")  ORDER BY testname" ); //AND fld_created_by IN(".$checkqryschoolcreate.")  
                                                    
                                                    
                                                                
                                                             
                                                if($qry->num_rows > 0){
                                                    while($rowsqry = $qry->fetch_assoc()){
                                                            extract($rowsqry);
                                                            ?>
                                                            <div class="draglinkleft" id="list1_<?php echo $testid; ?>" >
                                                                    <div class="dragItemLable" id="<?php echo $testid; ?>"><?php echo $testname; ?></div>
                                                                    <div class="clickable" id="clck_<?php echo $testid; ?>" onclick="fn_movealllistitems('list1','list2',<?php echo $testid; ?>);"></div>
                                                            </div> 
                                                            <?php
                                                    }
                                                }?>    
                                            </div>
                                        </div>
                                    	<div class="dragAllLink"  onclick="fn_movealllistitems('list1','list2',0);">add all classes</div>
                                    </div>
                                </div>
                                
                              <div class='six columns'>
                                    <div class="dragndropcol">
                                        <div class="dragtitle">Assessments Selected </div>
                                        <div class="draglinkleftSearch" id="s_list2" >
                                           <dl class='field row'>
                                                <dt class='text'>
                                                    <input placeholder='Search' type='text' id="list_2_search" name="list_2_search" onKeyUp="search_list(this,'#list2');" />
                                                </dt>
                                            </dl>
                                        </div>
                                         <div class="dragWell" id="testrailvisible1">
                                            <div id="list2" class="dragleftinner droptrue">
                                             <?php 
                                            	 
                                               if($qry->num_rows > 0){
                                                    while($rowsqry = $qry->fetch_assoc()){
                                                            extract($rowsqry);
                                                    ?>
                                                            <div class="draglinkright" id="list2_<?php echo $testid; ?>">
                                                                <div class="dragItemLable tooltip" id="<?php echo $testid; ?>" title="<?php echo $testname;?>"><?php echo $testname; ?></div>
                                                                <div class="clickable" id="clck_<?php echo $testid; ?>" onclick="fn_movealllistitems('list1','list2',<?php echo $testid; ?>);"></div>
                                                            </div>
                                                <?php 	}
                                                }
                                             
                                            ?>
                                         </div>
                                         </div>
                                        <div class="dragAllLink" onclick="fn_movealllistitems('list2','list1',0,0);">remove all classes</div>
                                    </div>
                                </div>
                          </div>   
                            
       <script type="text/javascript" language="javascript">
            $.getScript('test/testassign/test-testassign-testteacher.js');
        $(function() {
                $('#testrailvisible2').slimscroll({
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
                $('#testrailvisible3').slimscroll({
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
                $("#list11").sortable({
                        connectWith: ".droptrue2",
                        dropOnEmpty: true,
                        items: "div[class='draglinkleft']",
                        receive: function(event, ui) { 
                                $("div[class=draglinkright]").each(function(){ 
                                        if($(this).parent().attr('id')=='list11'){
                                                fn_movealllistitems('list11','list12',$(this).children(":first").attr('id'));
                                        }
                                });											
                        }
                });

                $( "#list12" ).sortable({
                        connectWith: ".droptrue2",
                        dropOnEmpty: true,
                        receive: function(event, ui) { 
                                $("div[class=draglinkleft]").each(function(){ 
                                        if($(this).parent().attr('id')=='list12'){
                                                fn_movealllistitems('list11','list12',$(this).children(":first").attr('id'));
                                        }
                                });								
                        }
                });

        });																	
    </script>  
                            
    <div class="row rowspacer" id="assignmentlist">
      <div class='six columns'>
          <div class="dragndropcol">
                              
          <?php
         
         
         ?>
              <div class="dragtitle">Teacher Assignments </div>
                  <div class="draglinkleftSearch" id="s_list11" >
                     <dl class='field row'>
                          <dt class='text'>
                              <input placeholder='Search' type='text' id="list_11_search" name="list_11_search" onKeyUp="search_list(this,'#list11');" />
                          </dt>
                      </dl>
                  </div>
                  <div class="dragWell" id="testrailvisible2" >
                      <div id="list11" class="dragleftinner droptrue2">
                       <?php 	
                        $checkqryschool = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_school_id) FROM itc_license_track WHERE fld_district_id='".$sendistid."' AND fld_school_id NOT IN(0)  AND fld_delstatus='0'");
                        $otherteachid=$ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_id) FROM itc_user_master WHERE fld_school_id IN(".$checkqryschool.") AND fld_district_id='".$sendistid."' AND fld_profile_id='9'");
                                                    
                           $qryteacher = $ObjDB->QueryObject("SELECT fld_id AS tid,fld_test_name AS testnamee,fld_created_by FROM itc_test_master WHERE fld_created_by IN(".$otherteachid.")  AND fld_created_by NOT IN(".$uid.")  AND fld_delstatus='0' ORDER BY testnamee ");
                            
                            
                            if($qryteacher->num_rows > 0){													
                              while($rows = $qryteacher->fetch_assoc()){
                                  extract($rows);
                                  ?>
                              <div class="draglinkleft" id="list11_<?php echo $tid; ?>" >
                                  <div class="dragItemLable tooltip" id="<?php echo $tid; ?>" title="<?php echo $testnamee;?>"><?php echo $testnamee; ?></div>
                                  
                                  <div class="clickable" id="clck_<?php echo $tid;?>" onclick="fn_movealllistitems('list11','list12',<?php echo $tid;?>,0);"></div>
                              </div>
                      <?php 
                              }
                          }
                      
                      ?>
                      </div>
                  </div>
                <div class="dragAllLink"  onclick="fn_movealllistitems('list11','list12',0,0);" style="cursor: pointer;cursor:hand;width:  120px;float: right;">add all assignments</div>
          </div>
      </div>
      <div class='six columns'>
        <div class="dragndropcol">
                  <div class="dragtitle">Assignments Selected</div>
                  <div class="draglinkleftSearch" id="s_list12" >
                     <dl class='field row'>
                          <dt class='text'>
                              <input placeholder='Search' type='text' id="list_12_search" name="list_12_search" onKeyUp="search_list(this,'#list12');" />
                          </dt>
                      </dl>
                  </div>
                  <div class="dragWell" id="testrailvisible3" >
                      <div id="list12" class="dragleftinner droptrue2">
                       <?php 		
                       
                        if($qryteacher->num_rows > 0){													
                              while($rows= $qryteacher->fetch_assoc()){
                                  extract($rows);
                                  ?>
                              <div class="draglinkright" id="list12_<?php echo $tid; ?>" >
                                  <div class="dragItemLable tooltip" id="<?php echo $tid; ?>" title="<?php echo $testnamee;?>"><?php echo $testnamee; ?></div>
                                  <div class="clickable" id="clck_<?php echo $tid;?>" onclick="fn_movealllistitems('list11','list12',<?php echo $tid;?>,0); "></div>
                              </div>
                      <?php 
                              }
                          }
                      ?>
                      </div>
                  </div>
                <div class="dragAllLink"  onclick="fn_movealllistitems('list12','list11',0,0);" style="cursor: pointer;cursor:hand;width:  144px;float: right;">remove all assignments</div>
         
          </div>
      </div>
    </div>  
                            
    <div class='row rowspacer' id="selectassessment">
        <input class="darkButton" type="button" id="btnstep" style="width:200px; height:42px; float:right;" value="Select" onClick="fn_select();" />
        
    </div>
                 </form>           
                    </div>        
                </div>    
           </div>                  
       </div>    
    </div>
</section>
<?php
	@include("footer.php");
