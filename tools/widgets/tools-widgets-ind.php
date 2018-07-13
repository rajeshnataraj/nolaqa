<?php
/*----
    Created BY : MOhan M PHP Programmer.(28/10/2015)	
----*/

@include("sessioncheck.php");
$menuid= isset($method['id']) ? $method['id'] : '';
$sid = isset($method['sid']) ? $method['sid'] : '0';
?>

<section data-type='#tools-widgets' id='tools-widgets-ind'>
    <div class='container'>
        <div class='row'>
            <div class="span10">
                <p class="darkTitle">Turn off Widgets Individually</p>
                <p class="dialogSubTitleLight"></p>
            </div>
        </div>
        <div class='row rowspacer'>
            <div class='twelve columns formBase'>
                <div class='row'>
                    <div class='eleven columns centered insideForm'>
                        <form  id="createlicense" name="createlicense" method='post'>
                            <script language="javascript" type="text/javascript">
                                $.getScript('tools/widgets/tools-widgets.js');
                                $(function()
                                {
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
                                    $("#list9").sortable({
                                            connectWith: ".droptrue",
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
                                    /* drag and sort for the first right box - Teachers */	
                                    $( "#list10" ).sortable({
                                            connectWith: ".droptrue",
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
                               
                            <div class='row'>
                                <div class='six columns'>
                                    <div class="dragndropcol">
                                    	<div class="dragtitle">Widgets Available</div>
                                        <div class="draglinkleftSearch" id="s_list9" >
                                            <dl class='field row'>
                                                <dt class='text'>
                                                    <input placeholder='Search' type='text' id="list_1_search" name="list_1_search" onKeyUp="search_list(this,'#list9');" />
                                                </dt>
                                            </dl>
                                        </div>
                                        <div class="dragWell" id="testrailvisible1" >
                                            <div id="list9" class="dragleftinner droptrue">
						<?php 
                                                $qry = $ObjDB->QueryObject("SELECT fld_menu_name AS menuname, fld_id AS menuid 
												FROM itc_widgets_menu 
												WHERE fld_id NOT IN (SELECT fld_widget_id FROM widgets_turnoff_individual WHERE fld_flag='1' AND fld_created_by = '".$uid."') AND fld_delstatus= '0'");
                                                if($qry->num_rows > 0){
                                                    while($rowsqry = $qry->fetch_assoc()){
                                                            extract($rowsqry);
                                                            ?>
                                                            <div class="draglinkleft" id="list9_<?php echo $menuid; ?>" >
                                                                    <div class="dragItemLable" id="<?php echo $menuid; ?>"><?php echo $menuname; ?></div>
                                                                    <div class="clickable" id="clck_<?php echo $menuid; ?>" onclick="fn_movealllistitems('list9','list10',<?php echo $menuid; ?>);"></div>
                                                            </div> 
                                                            <?php
                                                    }
                                                }?>    
                                            </div>
                                        </div>
                                    	<div class="dragAllLink"  onclick="fn_movealllistitems('list9','list10',0);">add all classes</div>
                                    </div>
                                </div>
                                
                                <div class='six columns'>
                                      <div class="dragndropcol">
                                          <div class="dragtitle">Widgets Selected </div>
                                          <div class="draglinkleftSearch" id="s_list10" >
                                             <dl class='field row'>
                                                  <dt class='text'>
                                                      <input placeholder='Search' type='text' id="list_2_search" name="list_2_search" onKeyUp="search_list(this,'#list10');" />
                                                  </dt>
                                              </dl>
                                          </div>
                                           <div class="dragWell" id="testrailvisible1">
                                              <div id="list10" class="dragleftinner droptrue">
                                               <?php 
                                               $qry = $ObjDB->QueryObject("SELECT fld_menu_name AS menuname, fld_id AS menuid 
												FROM itc_widgets_menu 
												WHERE fld_id IN (SELECT fld_widget_id FROM widgets_turnoff_individual WHERE fld_flag='1' AND fld_created_by = '".$uid."') AND fld_delstatus= '0'");
                                                 if($qry->num_rows > 0){
                                                      while($rowsqry = $qry->fetch_assoc()){
                                                              extract($rowsqry);
                                                      ?>
                                                              <div class="draglinkright" id="list10_<?php echo $menuid ?>">
                                                                  <div class="dragItemLable tooltip" id="<?php echo $menuid; ?>" title="<?php echo $classname;?>"><?php echo $menuname; ?></div>
                                                                  <div class="clickable" id="clck_<?php echo $menuid; ?>" onclick="fn_movealllistitems('list9','list10',<?php echo $menuid; ?>);"></div>
                                                              </div>
                                                  <?php 	}
                                                  }

                                              ?>
                                           </div>
                                           </div>
                                          <div class="dragAllLink" onclick="fn_movealllistitems('list10','list9',0,0);">remove all classes</div>
                                      </div>
                                  </div>
                             </div>   
                            
                            <div class='row'>
                                <div class='twelve columns'>  
                                    <div id="assignmentdiv" style="display:none">
                                    </div>
                                </div>        
                            </div>    
                            
                            <div class='row rowspacer' style="display:none" id="savereportdiv">
                                <input class="darkButton" type="button" id="btnstep" style="width:200px; height:42px; float:right;" value="Save" onClick="fn_saveind(1);" />
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
