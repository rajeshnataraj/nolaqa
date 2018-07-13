<?php
/*----
    Created BY : MOhan M PHP Programmer.(28/10/2015)	
----*/

@include("sessioncheck.php");
$menuid= isset($method['id']) ? $method['id'] : '';
$sid = isset($method['sid']) ? $method['sid'] : '0';
?>

<section data-type='#tools-widgets' id='tools-widgets'>
    <div class='container'>
        	<div class='row'>
                <div class="span10">
                    <p class="darkTitle">Turn off Widgets per Content</p>
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
                                        $("#list6").sortable({
                                                connectWith: ".droptrue",
                                                dropOnEmpty: true,
                                                items: "div[class='draglinkleft']",
                                                receive: function(event, ui) {
                                                        $("div[class=draglinkright]").each(function(){ 
                                                                if($(this).parent().attr('id')=='list6'){
                                                                        fn_movealllistitems('list6','list7',$(this).children(":first").attr('id'));
                                                                }
                                                        });
                                                }
                                        });
                                        /* drag and sort for the first right box - Teachers */	
                                        $( "#list7" ).sortable({
                                                connectWith: ".droptrue",
                                                dropOnEmpty: true,
                                                receive: function(event, ui) {
                                                        $("div[class=draglinkleft]").each(function(){ 
                                                                if($(this).parent().attr('id')=='list7'){
                                                                        fn_movealllistitems('list6','list7',$(this).children(":first").attr('id'));
                                                                }
                                                        });
                                                }
                                        });
                                    });
                            </script> 
                               
                            <div class='row'>
                                <div class='six columns'>
                                    <div class="dragndropcol">
                                    	<div class="dragtitle">Content Available</div>
                                        <div class="draglinkleftSearch" id="s_list6" >
                                            <dl class='field row'>
                                                <dt class='text'>
                                                    <input placeholder='Search' type='text' id="list_1_search" name="list_1_search" onKeyUp="search_list(this,'#list6');" />
                                                </dt>
                                            </dl>
                                        </div>
                                        <div class="dragWell" id="testrailvisible1" >
                                            <div id="list6" class="dragleftinner droptrue">
						<?php 
                                                $qry = $ObjDB->QueryObject("SELECT fld_id AS contentid,fld_menu_name AS contentname
                                                                               FROM itc_widgets_schedule_menu WHERE fld_id NOT IN (SELECT fld_content_id FROM widgets_turnoff_content WHERE fld_flag='1' AND fld_created_by = '".$uid."') AND fld_delstatus='0'");
                                                if($qry->num_rows > 0)
                                                {
                                                    while($rowsqry = $qry->fetch_assoc())
                                                    {
                                                        extract($rowsqry);
                                                        ?>
                                                        <div class="draglinkleft" id="list6_<?php echo $contentid; ?>" >
                                                                <div class="dragItemLable" id="<?php echo $contentid; ?>"><?php echo $contentname; ?></div>
                                                                <div class="clickable" id="clck_<?php echo $contentid; ?>" onclick="fn_movealllistitems('list6','list7',<?php echo $contentid; ?>);"></div>
                                                        </div> 
                                                        <?php
                                                    }
                                                }   ?>    
                                            </div>
                                        </div>
                                    	<div class="dragAllLink"  onclick="fn_movealllistitems('list6','list7',0);">add all content</div>
                                    </div>
                                </div>
                                
                                <div class='six columns'>
                                      <div class="dragndropcol">
                                          <div class="dragtitle">Content Selected </div>
                                          <div class="draglinkleftSearch" id="s_list7" >
                                             <dl class='field row'>
                                                  <dt class='text'>
                                                      <input placeholder='Search' type='text' id="list_2_search" name="list_2_search" onKeyUp="search_list(this,'#list7');" />
                                                  </dt>
                                              </dl>
                                          </div>
                                           <div class="dragWell" id="testrailvisible1">
                                              <div id="list7" class="dragleftinner droptrue">
                                               <?php 
                                               $qry = $ObjDB->QueryObject("SELECT fld_id AS contentid,fld_menu_name AS contentname
                                                                               FROM itc_widgets_schedule_menu WHERE fld_id IN (SELECT fld_content_id FROM widgets_turnoff_content WHERE fld_flag='1' AND fld_created_by = '".$uid."') AND fld_delstatus='0'");
                                                 if($qry->num_rows > 0){
                                                      while($rowsqry = $qry->fetch_assoc())
                                                      {
                                                              extract($rowsqry);
                                                              ?>
                                                              <div class="draglinkright" id="list7_<?php echo $contentid; ?>">
                                                                  <div class="dragItemLable tooltip" id="<?php echo $contentid; ?>" title="<?php echo $contentname;?>"><?php echo $contentname; ?></div>
                                                                  <div class="clickable" id="clck_<?php echo $contentid; ?>" onclick="fn_movealllistitems('list6','list7',<?php echo $contentid; ?>);"></div>
                                                              </div>
                                                          <?php 	
                                                      }
                                                  }

                                              ?>
                                           </div>
                                           </div>
                                          <div class="dragAllLink" onclick="fn_movealllistitems('list7','list6',0,0);">remove all content</div>
                                      </div>
                                  </div>
                            </div>   
                            
                            <div class='row rowspacer' style="display:none" id="savereportdiv">
                                <input class="darkButton" type="button" id="btnstep" style="width:200px; height:42px; float:right;" value="Save" onClick="fn_savecont(2);" />
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
