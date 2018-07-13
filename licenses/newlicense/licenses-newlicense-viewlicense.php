<?php 
	@include("sessioncheck.php");
	$licenseid = isset($method['id']) ? $method['id'] : '0';	
	//get license name 
	$licensename = $ObjDB->SelectSingleValue("SELECT fld_license_name  
											 FROM itc_license_master 
											 WHERE fld_id='".$licenseid."'");
?>
<section data-type='#library-modules' id='licenses-newlicense-viewlicense'>
    <div class='container'>
    	<div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle"><?php echo $licensename; ?>
                </p>
                <p class="dialogSubTitle">&nbsp;</p>
            </div>
        </div>
        <div class="row">
        	<div class="twelve columns formBase">
            	<div class='row'>
                    <div class='eleven columns centered insideForm'>
                    	<div class='row'>
							<?php
                            $qry_unit = $ObjDB->QueryObject("SELECT b.fld_unit_name AS unitname 
                                                            FROM itc_license_cul_mapping AS a LEFT JOIN itc_unit_master AS b ON a.fld_unit_id=b.fld_id
                                                            WHERE a.fld_license_id='".$licenseid."' AND a.fld_active='1' AND b.fld_delstatus='0' 
                                                            GROUP BY a.fld_unit_id 
                                                            ORDER BY unitname");	
                            $unitids = '';	
                            if($qry_unit->num_rows > 0) {
                            ?>
                                <div class='six columns'>
                                    <span class="wizardReportDesc">Unit Name:</span>
                                    <?php 
                                    while($rowunit = $qry_unit->fetch_assoc()){
                                        extract($rowunit);
                                    ?>		
                                        <div class="wizardReportData"><?php echo $unitname; ?></div>
                                    <?php
                                    }
                                    ?>	
                                </div>
                                <?php
							}
							
							$qry_lesson = $ObjDB->QueryObject("  SELECT CONCAT(b.fld_ipl_name,' ',c.fld_version) AS lessonname 	
																FROM itc_license_cul_mapping AS a LEFT JOIN itc_ipl_master AS b ON a.fld_lesson_id=b.fld_id
																		LEFT JOIN itc_ipl_version_track AS c ON b.fld_id=c.fld_ipl_id
																WHERE a.fld_license_id='".$licenseid."' AND a.fld_active='1' AND b.fld_delstatus='0' AND c.fld_zip_type='1' 
																		AND c.fld_delstatus='0'
																GROUP BY a.fld_lesson_id 
																ORDER BY lessonname");				
							if($qry_lesson->num_rows > 0) {
							?>	
                            <div class='six columns'>
                            	<span class="wizardReportDesc">Lesson Name:</span>
                                <?php 
									//get IPLs from the license
									
										while($rowlesson = $qry_lesson->fetch_assoc()){
											extract($rowlesson);	
									?>		
                                    	<div class="wizardReportData"><?php echo $lessonname; ?></div>
                                    <?php
										}
									
								?>	
                            </div>
                            <?php
							}
							?>
                      	</div>                    	
                        <div class='row rowspacer'>
                        <?php
						$qry_module = $ObjDB->QueryObject("SELECT w.* 
																	  FROM ((SELECT 'Module' AS mtype, CONCAT(b.fld_module_name,' ',c.fld_version ) AS modulename 
																	  	FROM itc_license_mod_mapping AS a 
																		LEFT JOIN itc_module_master AS b ON a.fld_module_id=b.fld_id
																		LEFT JOIN itc_module_version_track AS c ON c.fld_mod_id=b.fld_id
																		WHERE a.fld_license_id='".$licenseid."' AND a.fld_active='1' AND a.fld_type='1' 
																		AND b.fld_delstatus='0' AND c.fld_delstatus='0'
																		GROUP BY a.fld_module_id) 		
																	  UNION 		
																		(SELECT 'Math Module' AS mtype, CONCAT(b.fld_mathmodule_name,' ',c.fld_version) AS modulename 
																		FROM itc_license_mod_mapping AS a
																		LEFT JOIN itc_mathmodule_master AS b ON a.fld_module_id=b.fld_id 
																		LEFT JOIN itc_module_version_track AS c ON c.fld_mod_id=b.fld_module_id
																		WHERE a.fld_license_id='".$licenseid."' AND c.fld_delstatus='0'
																		AND a.fld_active='1' AND a.fld_type='2' AND b.fld_delstatus='0' GROUP BY a.fld_module_id)) AS w 
																	  ORDER BY w.modulename");	
									if($qry_module->num_rows > 0) {
										?>
                            <div class='six columns'>
                            	<span class="wizardReportDesc">Modules Name:</span>
                                <?php 
									//get moduels and mathmodules from the license
									
										while($rowmodule = $qry_module->fetch_assoc()){
											extract($rowmodule);
									?>		
                                    	<div class="wizardReportData"><?php echo $modulename." / ".$mtype; ?></div>
                                    <?php
										}
									
								?>	
                            </div>
                            <?php
							}							

							?>
                        
                      	</div>  
                        <div class='row rowspacer'>
							<?php
                            $qry_quest = $ObjDB->QueryObject("SELECT w.* 
                                                                          FROM (SELECT 'Quest' AS mtype, CONCAT(b.fld_module_name,' ',c.fld_version ) AS modulename 
                                                                            FROM itc_license_mod_mapping AS a 
                                                                            LEFT JOIN itc_module_master AS b ON a.fld_module_id=b.fld_id
                                                                            LEFT JOIN itc_module_version_track AS c ON c.fld_mod_id=b.fld_id
                                                                            WHERE a.fld_license_id='".$licenseid."' AND a.fld_active='1' AND a.fld_type='7' 
                                                                            AND b.fld_delstatus='0' AND c.fld_delstatus='0'
                                                                            GROUP BY a.fld_module_id) AS w 
                                                                          ORDER BY w.modulename");	
							if($qry_quest->num_rows > 0) {
										?>
                            <div class='six columns'>
                            	<span class="wizardReportDesc">Quest Name:</span>
                                <?php 
									//get moduels and mathmodules from the license
									
										while($rowquest = $qry_quest->fetch_assoc()){
											extract($rowquest);
									?>		
                                    	<div class="wizardReportData"><?php echo $modulename." / ".$mtype; ?></div>
                                    <?php
										}
								?>	
                            </div>
                            <?php
							}?>		
                      	</div>  
                        
                    <!--PD -->
                        <div class='row rowspacer'> <?php
                            $qry_unit = $ObjDB->QueryObject("SELECT b.fld_course_name AS unitname FROM itc_license_pd_mapping AS a 
                                                            LEFT JOIN itc_course_master AS b ON a.fld_course_id=b.fld_id 
                                                            WHERE a.fld_license_id='".$licenseid."' AND a.fld_active='1' 
                                                            AND b.fld_delstatus='0' GROUP BY a.fld_course_id ORDER BY unitname");	
                                $unitids = '';	
                                if($qry_unit->num_rows > 0) {
                                ?>
                                    <div class='six columns'>
                                        <span class="wizardReportDesc">Course Name:</span>
                                        <?php 
                                        while($rowunit = $qry_unit->fetch_assoc()){
                                            extract($rowunit);
                                        ?>		
                                            <div class="wizardReportData"><?php echo $unitname; ?></div>
                                        <?php
                                        }
                                        ?>	
                                    </div>
                                    <?php
                                }

                            $qry_pd = $ObjDB->QueryObject("SELECT w.* 
                                                                FROM (SELECT 'pd' AS mtype, CONCAT(b.fld_pd_name,' ',c.fld_version ) AS pdname 
                                                                FROM itc_license_pd_mapping AS a 
                                                                LEFT JOIN itc_pd_master AS b ON a.fld_pd_id=b.fld_id
                                                                LEFT JOIN itc_pd_version_track AS c ON c.fld_pd_id=b.fld_id
                                                                WHERE a.fld_license_id='".$licenseid."' AND a.fld_active='1'  
                                                                AND b.fld_delstatus='0' AND c.fld_delstatus='0'
                                                                GROUP BY a.fld_pd_id) AS w 
                                                                ORDER BY w.pdname");	
                                if($qry_pd->num_rows > 0) { ?>
                                    <div class='six columns'>
                                        <span class="wizardReportDesc">PDLesson Name:</span>
                                        <?php 
                                        //get Quest from the license
                                        while($rowpd = $qry_pd->fetch_assoc()){
                                            extract($rowpd);
                                        ?>		
                                            <div class="wizardReportData"><?php echo $pdname." / ".$mtype; ?></div>
                                        <?php
                                        }
                                        ?>	
                                    </div>
                                    <?php
                                }   ?>		
                        </div> 
                    <!--PD -->
                        
                                   
                       <!--SOS -->
                        <div class='row rowspacer'> <?php
                            $qry_sosunit = $ObjDB->QueryObject("SELECT a.fld_id AS unitid, a.fld_unit_name AS unitname 
                                                                            FROM itc_sosunit_master AS a LEFT JOIN itc_license_sosunit_mapping AS b ON a.fld_id=b.fld_unit_id
                                                                            WHERE a.fld_delstatus='0' AND b.fld_license_id='".$licenseid."' AND b.fld_access='1' 
                                                                            GROUP BY a.fld_id 
                                                                            ORDER BY a.fld_unit_name");	
                                
                                if($qry_sosunit->num_rows > 0) {
                                ?>
                                    <div class='four columns'>
                                        <span class="wizardReportDesc">Unit Name:</span>
                                        <?php 
                                        while($rowunit = $qry_sosunit->fetch_assoc()){
                                            extract($rowunit);
                                        ?>		
                                            <div class="wizardReportData"><?php echo $unitname; ?></div>
                                        <?php
                                        }
                                        ?>	
                                    </div>
                                    <?php
                                }

                            $qry_sosphase = $ObjDB->QueryObject("SELECT  a.fld_phase_name AS phasename 
														  FROM itc_sosphase_master AS a LEFT JOIN itc_license_sosphase_mapping AS b ON a.fld_id=b.fld_phase_id 
														  WHERE  a.fld_delstatus='0' 
														  	 AND b.fld_license_id='".$licenseid."' 
														  	 AND b.fld_active='1' 
														  ORDER BY phasename");	
                                if($qry_sosphase->num_rows > 0) { ?>
                                    <div class='four columns'>
                                        <span class="wizardReportDesc">Phase Name:</span>
                                        <?php 
                                        //get phase from the license
                                        while($rowphase = $qry_sosphase->fetch_assoc()){
                                            extract($rowphase);
                                        ?>		
                                            <div class="wizardReportData"><?php echo $phasename; ?></div>
                                        <?php
                                        }
                                        ?>	
                                    </div>
                                    <?php
                                }   
                            
                            
                            $qry_sosvideo = $ObjDB->QueryObject("SELECT  a.fld_video_name AS videoname
														  FROM itc_sosvideo_master AS a LEFT JOIN itc_license_sosvideo_mapping AS b ON a.fld_id=b.fld_video_id 
														  WHERE  a.fld_delstatus='0' 
														  	 AND b.fld_license_id='".$licenseid."' 
														  	 AND b.fld_active='1' 
														  ORDER BY videoname");	
                                if($qry_sosvideo->num_rows > 0) { ?>
                                    <div class='four columns'>
                                        <span class="wizardReportDesc">Video Name:</span>
                                        <?php 
                                        //get video from the license
                                        while($rowvideo = $qry_sosvideo->fetch_assoc()){
                                            extract($rowvideo);
                                        ?>		
                                            <div class="wizardReportData"><?php echo $videoname; ?></div>
                                        <?php
                                        }
                                        ?>	
                                    </div>
                                    <?php
                                }   ?>		
                        </div> 
                    <!--SOS -->
                                   
                        <div class='row rowspacer'>
							<?php
                            $qry_dest = $ObjDB->QueryObject("SELECT w.* 
                                                                          FROM (SELECT 'Destination' AS mtype, b.fld_dest_name AS modulename 
                                                                            FROM itc_license_exp_mapping AS a 
                                                                            LEFT JOIN itc_exp_destination_master AS b ON a.fld_dest_id=b.fld_id
                                                                            WHERE a.fld_license_id='".$licenseid."' AND a.fld_flag='1'  
                                                                            AND b.fld_delstatus='0'
                                                                            GROUP BY a.fld_dest_id) AS w 
                                                                          ORDER BY w.modulename");	
							if($qry_dest->num_rows > 0) {
										?>
                            <div class='six columns'>
                            	<span class="wizardReportDesc">Destination Name:</span>
                                <?php 
									//get destination from the license
									
										while($rowquest = $qry_dest->fetch_assoc()){
											extract($rowquest);
									?>		
                                    	<div class="wizardReportData"><?php echo $modulename." / ".$mtype; ?></div>
                                    <?php
										}
								?>	
                            </div>
                            <?php
							}?>		
                      	</div>   
                    
                    
                    <!--                    Mission-->
                    
                     <div class='row rowspacer'>
                            <?php
                            $qry_misdest = $ObjDB->QueryObject("SELECT 'Destination' AS mtype, b.fld_dest_name AS modulename, b.fld_mis_id AS misid
                                                FROM itc_license_mission_mapping AS a 
                                                LEFT JOIN itc_mis_destination_master AS b ON a.fld_dest_id=b.fld_id
                                                WHERE a.fld_license_id='".$licenseid."' AND a.fld_flag='1'  
                                                AND b.fld_delstatus='0'
                                                GROUP BY a.fld_dest_id
                                              ORDER BY misid");	
                            if($qry_misdest->num_rows > 0) 
                            {
                                ?>
                                <div class='six columns'>
                                <span class="wizardReportDesc">Destination Name:</span>
                                <?php 
                                //get destination from the license

                                while($rowmisquest = $qry_misdest->fetch_assoc())
                                {
                                    extract($rowmisquest);
                                    $misname = $ObjDB->SelectSingleValue("SELECT fld_mis_name  
											 FROM itc_mission_master 
											 WHERE fld_id='".$misid."'");
                                    ?>		
                                    <div class="wizardReportData"><?php echo $misname." / ".$modulename; ?></div>
                                    <?php
                                }
                                ?>	
                                </div>
                                <?php
                            }   ?>		
                      	</div>  
		<!--                    Mission-->
						
						<!-- Sim product start line -->
						<div class='row rowspacer'>
							<?php
                            $qry_product = $ObjDB->QueryObject("SELECT b.fld_product_name AS productname,b.fld_cat_id AS type  
                                                            FROM itc_license_simproduct_mapping AS a LEFT JOIN itc_sim_product AS b ON a.fld_product_id = b.fld_id
                                                            WHERE a.fld_license_id='".$licenseid."' AND a.fld_active='1' AND b.fld_delstatus='0' 
                                                            GROUP BY b.fld_id 
                                                            ORDER BY productname");	
							if($qry_product->num_rows > 0) {
										?>
                            <div class='six columns'>
                            	<span class="wizardReportDesc">Sim Product Name:</span>
                                <?php 
									//get moduels and mathmodules from the license
									
										while($rowproduct = $qry_product->fetch_assoc()){
											extract($rowproduct);
											if($type == '1'){ $typename = 'IPL'; }
											else if($type == '2'){ $typename = 'Module'; }
											else if($type == '3'){ $typename = 'Math Module'; }
											else if($type == '5'){ $typename = 'Quest'; }
											else if($type == '6'){ $typename = 'Expeditions'; }
											else if($type == '8'){ $typename = 'PD'; }
											else if($type == '10'){ $typename = 'Missions'; }
											
									?>		
                                    	<div class="wizardReportData"><?php echo $productname." / ".$typename; ?></div>
                                    <?php
										}
								?>	
                            </div>
                            <?php
							}?>		
                      	</div>
                        <!-- Sim product end line -->
                    </div>
             	</div>
         	</div>
      	</div>
    </div>
</section>
<?php
	@include("footer.php");