<?php
	@include("sessioncheck.php");
	
	$id = isset($method['id']) ? $method['id'] : 0;
	$id=explode(",",$id);
	
?>
<section data-type='#test-testassign' id='test-testassign-addtest'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Add Assessment to School.</p>
				<p class="dialogSubTitleLight"></p>
            </div>
        </div>
        
        <div class='row rowspacer'>
            <div class='twelve columns formBase'>
                <div class='row'>
                	<div class='eleven columns centered insideForm'>
                    <form  id="addschool" name="addschool" method='post'>
						<script language="javascript" type="text/javascript">
                            $(function() {
                                /* scroll for the first left box - school */	
                                $('#testrailvisible3').slimscroll({
                                    width: '410px',
                                    height:'366px',
                                    size: '3px',
                                    railVisible: true,
                                    allowPageScroll: false,
                                    railColor: '#F4F4F4',
                                    opacity: 1,
                                    color: '#d9d9d9',
                                    
                                });
                                
                                /* scroll for the first right box - school */	
                                $('#testrailvisible4').slimscroll({
                                    width: '410px',
                                    height:'366px',
                                    size: '3px',
                                    railVisible: true,
                                    allowPageScroll: false,
                                    railColor: '#F4F4F4',
                                    opacity: 1,
                                    color: '#d9d9d9',
                                });
                                
                                $("#list3").sortable({
                                    connectWith: ".droptrue1",
                                    dropOnEmpty: true,
                                    receive: function(event, ui) {
                                        $("div[class=draglinkright]").each(function(){ 
                                            if($(this).parent().attr('id')=='list3'){
                                                fn_movealllistitems('list3','list4',$(this).children(":first").attr('id'));
                                            }
                                        });
                                    }
                                });
                            
                                $( "#list4" ).sortable({
                                    connectWith: ".droptrue1",
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
                            
                            <div class='row rowspacer'>
                                <div class='six columns'>
                                    <div class="dragndropcol">
                                        <div class="dragtitle">Schools Available</div>
                                        <div class="dragWell" id="testrailvisible3" >
                                            <div id="list3" class="dragleftinner droptrue1">
                                                 <?php 
                                            $qrytest= $ObjDB->QueryObject("SELECT fld_id as schoolid, fld_school_name as schoolname FROM `itc_school_master` 
											                              WHERE fld_id NOT IN (SELECT fld_school_id FROM itc_test_school_mapping 
																		  WHERE fld_test_id='".$id[0]."' AND fld_flag='1') AND fld_district_id='".$sendistid."' AND fld_delstatus='0'");
                                            if($qrytest->num_rows > 0){
                                                while($rowqrytest = $qrytest->fetch_assoc()){
                                                    extract($rowqrytest);
                                                ?>
                                                    <div class="draglinkleft" id="list3_<?php echo $schoolid; ?>" >
                                                        <div class="dragItemLable" id="<?php echo $schoolid; ?>"><?php echo $schoolname; ?></div>
                                                        <div class="clickable" id="clck_<?php echo $schoolid; ?>" onclick="fn_movealllistitems('list3','list4',<?php echo $schoolid; ?>);"></div>
                                                    </div> 
                                                 <?php }
                                            }?>    
                                            </div>
                                        </div>
                                        <div class="dragAllLink"  onclick="fn_movealllistitems('list3','list4',0);">Add all schools</div>
                                    </div>
                                </div>
                                <div class='six columns'>
                                    <div class="dragndropcol">
                                        <div class="dragtitle">Schools in Test</div> 
                                        <div class="dragWell" id="testrailvisible4">
                                            <div id="list4" class="dragleftinner droptrue1">
                                            <?php 
                                            	$qrytestschoolmap=$ObjDB->QueryObject(" SELECT a.fld_id as schoolid, a.fld_school_name as schoolname 
												                                       FROM `itc_school_master` AS a 
																					   LEFT JOIN `itc_test_school_mapping` AS b ON a.fld_id=b.fld_school_id 
																					   WHERE b.fld_test_id='".$id[0]."' AND b.fld_flag='1' AND b.fld_created_by='".$uid."'");
                                            if($qrytestschoolmap->num_rows > 0){
                                                while($rowsqry = $qrytestschoolmap->fetch_assoc()){
                                                    extract($rowsqry);
                                                ?> 
                                                    <div class="draglinkright" id="list4_<?php echo $schoolid; ?>">
                                                        <div class="dragItemLable" id="<?php echo $schoolid; ?>"><?php echo $schoolname;?></div>
                                                        <div class="clickable" id="clck_<?php echo $schoolid; ?>" onclick="fn_movealllistitems('list3','list4',<?php echo $schoolid; ?>);"></div>
                                                    </div>
                                                 <?php }
                                            }?>
                                            </div>
                                        </div>
                                        <div class="dragAllLink" onclick="fn_movealllistitems('list4','list3',0);">remove all Schools</div>
                                    </div>
                                </div>
                            </div>
                        
                            <div class='row rowspacer' style="padding-top:20px;">
                                <div class='row'>
                                	<div class="tRight">
                                        <input type="button" id="btnstep" class="darkButton" style="width:140px; height:42px;float:right;" value="Save Test" onClick="fn_schoolassign(<?php echo $id[0];?>);" />
                                    </div>
                                   
                                </div>
                            </div>
                        <input type="hidden" name="classid" id="classid" value="<?php echo $id[0];?>" />
                    </form>
                    </div>
                </div>
            </div>
        </div>   
    </div>
</section>
<?php
	@include("footer.php");