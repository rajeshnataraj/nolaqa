<?php
@include("sessioncheck.php");
?>
<section data-type='#reports-classroom' id='reports-classroom-stageschedule'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Stage Schedule report</p>
				<!--<p class="dialogSubTitleLight">Customize your report below, then press View Report.</p>-->
                                <p class="dialogSubTitleLight">Select the specific class you wish to view, then click "View Report".</p>
            </div>
        </div>
        
        <div class='row formBase rowspacer'>
            <div class='eleven columns centered insideForm'>
            	<?php if($sessmasterprfid==6) { ?>
                <div class="row">
                    <div class='six columns'>
                        School
                        <div class="selectbox">
                            <input type="hidden" name="districtid" id="districtid" value="<?php echo $districtid; ?>">
                            <input type="hidden" name="schoolid" id="schoolid" value="">
                            <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                                <span class="selectbox-option input-medium" data-option="" style="width:97%">Select School</span>
                                <b class="caret1"></b>
                            </a>
                            <div class="selectbox-options">
                                <input type="text" class="selectbox-filter" placeholder="Search School">
                                <ul role="options" style="width:100%">
                                    <?php 
                                    $qry = $ObjDB->QueryObject("SELECT fld_id AS schoolid, fld_school_name AS schoolname 
																FROM itc_school_master 
																WHERE fld_delstatus='0' AND fld_district_id='".$districtid."'
																ORDER BY fld_school_name");
                                    if($qry->num_rows>0){
                                        while($row = $qry->fetch_assoc())
                                        {
                                            extract($row);
                                            ?>
                                            <li><a tabindex="-1" href="#" data-option="<?php echo $schoolid;?>" onclick="fn_showteachers(<?php echo $schoolid;?>,0,3)"><?php echo $schoolname; ?></a></li>
                                            <?php
                                        }
                                    }?>      
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class='six columns' id="teachersdiv">   
                    
                    </div>
                </div>
                
                <div class="row rowspacer">
                    <div class='six columns' id="classdiv">   
                    
                    </div>
				<?php 
                }
				if($sessmasterprfid!=2 and $sessmasterprfid!=3 and $sessmasterprfid!=6) { ?>
            	<!--Shows Class & Student Dropdown-->
            	<div class="row">
                    <div class='six columns'>   
                    	Class
                        <dl class='field row'>
                            <div class="selectbox">
                                <input type="hidden" name="classid" id="classid" value="">
                                <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                                    <span class="selectbox-option input-medium" data-option="" style="width:97%">Select Class</span>
                                    <b class="caret1"></b>
                                </a>
                                <div class="selectbox-options">
                                    <input type="text" class="selectbox-filter" placeholder="Search Class">
                                    <ul role="options" style="width:100%">
                                        <?php 
                                        $qry = $ObjDB->QueryObject("SELECT fld_id AS classid, fld_class_name AS classname 
																	FROM itc_class_master 
																	WHERE fld_delstatus='0' AND fld_archive_class='0' AND (fld_created_by='".$uid."' 
																	OR fld_id IN (SELECT fld_class_id 
																					FROM itc_class_teacher_mapping 
																					WHERE fld_teacher_id='".$uid."' AND fld_flag='1')) 
																	ORDER BY fld_class_name");
                                        if($qry->num_rows>0){
                                            while($row = $qry->fetch_assoc())
                                            {
                                                extract($row);
                                                ?>
                                                <li><a tabindex="-1" href="#" data-option="<?php echo $classid;?>" onClick="$('#sctypediv').show();"><?php echo $classname; ?></a></li>
                                                <?php
                                            }
                                        }?>      
                                    </ul>
                                </div>
                            </div> 
                        </dl>
                    </div>
                    <?php }?>
                    <div class='six columns'>   
                        <dl class='field row'>
                        	<div id="sctypediv" style="display:none">
                            	Type
                                <div class="selectbox">
                                    <input type="hidden" name="sciencetypeid" id="sciencetypeid" value="">
                                    <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                                        <span class="selectbox-option input-medium" data-option="" style="width:97%">Select Type</span>
                                        <b class="caret1"></b>
                                    </a>
                                    <div class="selectbox-options">
                                    <ul role="options" style="width:100%">
                                        <li><a tabindex="-1" href="#" data-option="1" onClick="$('#stagediv').show();">Dyad Schedule</a></li>
                                        <li><a tabindex="-1" href="#" data-option="2" onClick="$('#stagediv').show();">Triad Schedule</a></li>
                                    </ul>
                                </div>
                                </div> 
                            </div>
                        </dl>
                    </div>
                </div>
                
                <div class="row rowspacer">
                    <div class='six columns'>   
                        <dl class='field row'>
                        	<div id="stagediv" style="display:none">
                            	Stage
                                <div class="selectbox">
                                    <input type="hidden" name="stageid" id="stageid" value="">
                                    <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                                        <span class="selectbox-option input-medium" data-option="" style="width:97%">Select Stage</span>
                                        <b class="caret1"></b>
                                    </a>
                                    <div class="selectbox-options">
                                        <ul role="options" style="width:100%">
                                            <li><a tabindex="-1" href="#" data-option="2" onClick="$('#viewreportdiv').show();">Stage 2</a></li>
                                            <li><a tabindex="-1" href="#" data-option="3" onClick="$('#viewreportdiv').show();">Stage 3</a></li>
                                            <li><a tabindex="-1" href="#" data-option="4" onClick="$('#viewreportdiv').show();">Stage 4</a></li>
                                            <li><a tabindex="-1" href="#" data-option="5" onClick="$('#viewreportdiv').show();">Stage 5</a></li>
                                        </ul>
                                    </div>
                                </div> 
                            </div>
                        </dl>
                    </div>
                </div>
                
                <input type="hidden" id="hidfilename" name="hidfilename" value="<?php echo "stageschedulereport_"; ?>" />
                
                <!--View Report Button-->
                <div class='row rowspacer' style="display:none" id="viewreportdiv">
                    <input class="darkButton" type="button" id="btnstep" style="width:200px; height:42px; float:right;" value="View Report" onClick="fn_showpassreport(4);" />
                </div>
            </div>
        </div>
    </div>
</section>
<?php
	@include("footer.php");