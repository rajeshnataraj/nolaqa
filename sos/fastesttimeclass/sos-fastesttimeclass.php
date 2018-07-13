<?php
@include("sessioncheck.php");

$tempid = isset($method['id']) ? $method['id'] : '0';
$tempid=explode(',',$tempid);
$id=$tempid[0];
?>

<section data-type='#sos-fastesttimeclass' id='sos-fastesttimeclass'>
    <div class='container'>
        <div class='row'>
            <p class="dialogTitle">Fastest Times by Class</p>
            <p class="dialogSubTitleLight"></p>
        </div>
        
        <div class='row rowspacer'>
            <div class='twelve columns formBase'>
                <div class='row'>
                	<div class='eleven columns centered insideForm'>
                            <form  id="createlicense" name="createlicense" method='post'>
                            <script language="javascript" type="text/javascript">
                                $.getScript('sos/fastesttimeclass/sos-fastesttimeclass.js');
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
                                    	<div class="dragtitle">Classes Available</div>
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
                                              		$qry = $ObjDB->QueryObject("SELECT a.fld_id as classid, a.fld_sos_class_name AS classname
                                                                                        FROM itc_sos_class_master AS a
                                                                                        LEFT JOIN itc_sos_datasheet_master AS b ON a.fld_id=b.fld_sosclass_id
                                                                                        WHERE b.fld_delstatus='0' AND a.fld_created_by='".$uid."'  group by classid");
                                                if($qry->num_rows > 0){
                                                    while($rowsqry = $qry->fetch_assoc()){
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
                                        <div class="dragtitle">Class Selected </div>
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
                            <div class="row">
                                <div class='six columns'>
                                    Track Length<span class="fldreq">*</span> 
                                    <dl class='field row'>
                                        <dt class='dropdown'>
                                            <div class="selectbox">
                                                <input type="hidden" name="tracklen" id="tracklen" value="<?php echo $tracklen;?>" onchange="$(this).valid();">
                                                <a class="selectbox-toggle" tabindex="1" role="button" data-toggle="selectbox" href="#">
                                                    <span class="selectbox-option input-medium" data-option=""> <?php echo "Select Track Length"; ?></span><b class="caret1"></b>
                                                </a>
                                                 <div class="selectbox-options">                                                    
                                                    <ul role="options" style="width:400px;">
                                                        <li><a tabindex="-1" href="#" data-option="1">65 Feet 7 inches</a></li>
                                                        <li><a tabindex="-1" href="#" data-option="2">55 feet</a></li>
                                                        <li><a tabindex="-1" href="#" data-option="3">45 feet</a></li>
                                                        <li><a tabindex="-1" href="#" data-option="4">Other</a></li>
                                                    </ul>
                                                </div>

                                            </div>
                                        </dt>
                                    </dl> 
                                </div>
                            </div>      
			<div class="showstudentname" >
			<div class="row rowspacer" id="stupassdiv">
                    <div class='six columns showallst'>   
                       
                        <form id="frmrep" name="frmrep">
                            <div class="field">
                                <label class="checkbox" for="stuname" onclick="fn_checkstu()">
                                    <input name="stuname" id="stuname" value="1" type="checkbox" style="display:none;"/>
                                    <span></span>	Show Student Name
                                </label>
                            </div>
                        </form>
                        <input type="hidden" id="hidcheckstu" name="hidcheckstu" value="0" />
                
			</div>
                      </div>
                    </div>       
				<!--Shows Class list-->
                              <input type="hidden" id="hidfilename" name="hidfilename" value="<?php echo "fastesttimeclass_"; ?>" />
                
                <!--View Report Button-->
                <div class='row rowspacer' id="viewreportdiv">
                    <input class="darkButton" type="button" id="btnstep" style="width:200px; height:42px; float:right;" value="View" onClick=" fn_fastesttimeclass_view();" />
                </div>
        </div>        
      </div>    
     </div>                  
     </div>                     
    </section>
<?php
	@include("footer.php");
