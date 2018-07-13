<?php
@include("sessioncheck.php");

$tempid = isset($method['id']) ? $method['id'] : '0';
$tempid=explode(',',$tempid);
$id=$tempid[0];

$value="Save Changes";

?>


<section data-type='#class-newclass' id='class-newclass-newarchive'>
    <div class='container'>
        <div class='row'>
            <p class="dialogTitle"></p>
            <p class="dialogSubTitleLight"></p>
        </div>
        
        <div class='row rowspacer'>
            <div class='twelve columns formBase'>
                <div class='row'>
                	<div class='eleven columns centered insideForm'>
                            <form  id="createlicense" name="createlicense" method='post'>
                            <script language="javascript" type="text/javascript">
                                $.getScript('class/newclass/class-newclass-archive.js');
                                    $(function() {
                                            $('div[id^="testrailvisible"]').each(function(index, element) {
                                                    $(this).slimscroll({ /*------- Scroll for Modules Left Box ------*/
                                                            width: '410px',
                                                            height:'366px',
                                                            size: '7px',
                                                            railVisible: true,
                                                            alwaysVisible: true,
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
                                    	<div class="dragtitle">Archive Class</div>
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
                                                $qryteacher= $ObjDB->QueryObject("SELECT fld_class_name AS classname, fn_shortname(fld_class_name,1) AS shortname, fld_id AS classid, fld_lab AS classtypeid, 
													fld_step_id AS stepid, fld_flag AS flag 
												FROM itc_class_master 
												WHERE fld_archive_class='0' and fld_delstatus='0' AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' 
													AND (fld_created_by='".$uid."' OR fld_id IN(SELECT fld_class_id FROM itc_class_teacher_mapping WHERE fld_teacher_id='".$uid."' 
													AND fld_flag='1'))");
                                                if($qryteacher->num_rows > 0){
                                                    while($rowsqry = $qryteacher->fetch_assoc()){
                                                            extract($rowsqry);
                                                            ?>
                                                            <div class="draglinkleft" id="list1_<?php echo $classid; ?>" >
                                                                    <div class="dragItemLable" id="<?php echo $classid; ?>"><?php echo $classname; ?></div>
                                                                    <div class="clickable" id="clck_<?php echo $classid; ?>" onclick="fn_movealllistitems('list1','list2',<?php echo $classid; ?>);"></div>
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
                                        <div class="dragtitle">Classes Archived </div>
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
                                             $qryteacher= $ObjDB->QueryObject("SELECT fld_class_name AS classname, fn_shortname(fld_class_name,1) AS shortname, fld_id AS classid, fld_lab AS classtypeid, 
													fld_step_id AS stepid, fld_flag AS flag 
												FROM itc_class_master 
												WHERE fld_archive_class='1' and fld_delstatus='0' AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' 
													AND (fld_created_by='".$uid."' OR fld_id IN(SELECT fld_class_id FROM itc_class_teacher_mapping WHERE fld_teacher_id='".$uid."' 
													AND fld_flag='1'))");
                                               if($qryteacher->num_rows > 0){
                                                    while($rowsqry = $qryteacher->fetch_assoc()){
                                                            extract($rowsqry);
                                                    ?>
                                                            <div class="draglinkright" id="list2_<?php echo $classid; ?>">
                                                                <div class="dragItemLable tooltip" id="<?php echo $classid; ?>" title="<?php echo $classname;?>"><?php echo $classname; ?></div>
                                                                <div class="clickable" id="clck_<?php echo $classid; ?>" onclick="fn_movealllistitems('list1','list2',<?php echo $classid; ?>);"></div>
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
                            <div class="row rowspacer">
                                <div class="tRight">
                                     <input type="button" id="btnstep" class="darkButton" style="width:200px; height:42px;float:right;" value="<?php echo $value;?>" onClick="fn_savearchiveclass();" />
                                </div>
                              
                            </div>
                            
                            
    </section>
<?php
	@include("footer.php");