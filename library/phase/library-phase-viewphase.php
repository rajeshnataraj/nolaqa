<?php
	@include("sessioncheck.php");
	
	$phaseid = isset($method['id']) ? $method['id'] : '0';	
	$phasename=$ObjDB->SelectSingleValue("SELECT fld_phase_name from itc_sosphase_master 
	                                     WHERE fld_id='".$phaseid."' AND fld_delstatus='0'"); // get the unit name using unit id
	
?>
<section data-type='2home' id='library-phase-viewphase'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="darkTitle"><?php echo $phasename;?></p>
                <p class="darkSubTitle">&nbsp;</p>
            </div>
        </div>    
        
        <div class='row rowspacer'>
            <div class='twelve columns formBase'>
                <div class='row'>
                    <div class='eleven columns centered insideForm'>
                        <div class='row'>
                            <div class='eight columns'>              
                                 <span class="wizardReportDesc">PHASE:</span>
                                 <?php 
								if($sessmasterprfid==2){
									$qry="SELECT fld_phase_name AS lessonname 
                                                                                FROM itc_sosphase_master 
                                                                                WHERE fld_delstatus='0' AND fld_id='".$phaseid."'";
								}
								
								$qry_lesson = $ObjDB->QueryObject($qry);
								
								if($qry_lesson->num_rows > 0){
									
									while($res_lesson=$qry_lesson->fetch_assoc()){ 
									   
									   extract($res_lesson);
								?>
							         <div class='wizardReportData' title="<?php echo $lessonname;?>"><?php echo $lessonname;?></div>
								<?php 
									}
									
								}else{
								?>
									<div class='wizardReportData'>No Phase(s)</div>
								<?php
								}
								?>
                            </div>              
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
	@include("footer.php");