<?php 
	@include("sessioncheck.php");
	
	$id = isset($method['id']) ? $method['id'] : '0';
	
	$id = explode(",",$id);
	
	$qrydetails = $ObjDB->QueryObject("SELECT a.fld_email AS email, CONCAT(a.fld_fname,' ',a.fld_lname) AS name1, b.fld_hubid AS hubid   
										FROM `itc_user_master` AS a , `itc_district_master` AS b 
										WHERE a.fld_id=b.`fld_district_admin_id` AND b.`fld_id`='".$id[0]."' AND a.`fld_delstatus`='0' AND b.fld_delstatus='0'");
		$row = $qrydetails->fetch_assoc();
		extract($row);
		
	
?>

<script language="javascript"> $.getScript("users/districts/users-districts-newdistrict.js"); </script>
<section data-type='#users-districts' id='users-districts-newdistrictview'>

	<div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">View the district details</p>
                <p class="dialogSubTitleLight">&nbsp;</p>
            </div>
        </div>
        
        <div class='row formBase'>
            <div class='eleven columns centered insideForm'>
            <div class="row">
            
                <div class="four columns">
                    <div class="wizardReportcols">
                        <div class="wizardReportDesc">Admin Name:</div>
                        	<div class="wizardReportData"><?php echo $name1;?></div>
                        <div class="wizardReportDesc">Email Address:</div>
                        	<div class="wizardReportData"><?php echo $email;?></div>
                        <div class="wizardReportDesc">HUB ID:</div>
                        <div class="wizardReportData"><?php echo $hubid;?></div>
                    </div>
                </div>
                
                <div class="four columns">
               		<div class="wizardReportcols">
                        <div class="wizardReportDesc">Plan Name:</div>
                        <div class="wizardReportData"><?php
							 $qryslicdetails = $ObjDB->QueryObject("SELECT  DISTINCT(a.`fld_id`) AS licid, a.`fld_license_name` AS licname 
							 										FROM `itc_license_master` AS a, `itc_license_track` AS b 
																	WHERE a.fld_id = b.fld_license_id AND b.fld_district_id='".$id[0]."' and a.fld_delstatus='0'"); 
							 
							 if($qryslicdetails->num_rows!=0){ 
								while($row=$qryslicdetails->fetch_assoc()){
								extract($row);
								if(strlen($licname)>15){ $templicname = substr($licname,0,15)."..."; } else { $templicname =$licname;}
								?>
                                	<div class="wizardReportData" style="cursor:pointer;" onclick="fn_distlicdet(<?php echo $licid;?>,'<?php echo $id[0];?>');"><?php echo $templicname;?></div>
                                <?php
								}
							 }
							 ?></div>
                    </div>
                </div>
                
                
                <div class="four columns">
               		<div class="wizardReportcols">
                        <div class="wizardReportDesc">Schools</div>
                        <div class="wizardReportData"><?php
							 $qryshldetails = $ObjDB->QueryObject("SELECT fld_id as shlid, fld_school_name AS shlname 
							 										FROM `itc_school_master` 
																	WHERE fld_district_id='".$id[0]."' AND fld_delstatus='0'"); 
							 
							 if($qryshldetails->num_rows!=0){ 
								while($row=$qryshldetails->fetch_assoc()){
								extract($row);
								if(strlen($shlname)>15){ $tempshlname = substr($shlname,0,15)."..."; } else { $tempshlname =$shlname;}
								?>
                                	<div class="wizardReportData" style="cursor:pointer;" onclick="fn_loadshldetails(<?php echo $shlid;?>);"><?php echo $tempshlname;?></div>
                                <?php
								}
							 }
							 ?></div>
                    </div>
                </div>
            </div>
            </div>
        </div>
        
        <div id="shldetails" style="display: none;" >
        </div>
        
        <div id="distlicdetails" style="display: none;" >
        </div>
    </div>
</section>
<?php
	@include("footer.php");