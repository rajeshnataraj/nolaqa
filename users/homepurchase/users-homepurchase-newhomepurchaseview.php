<?php
/*------
	Page - homepurchase-newhomepurchaseview
	Description:
		Form to view the Home details.
	History:	
------*/  
	@include("sessioncheck.php");
	
	$id = isset($method['id']) ? $method['id'] : '0';
	$id = explode(",",$id);

	$qrydetails = $ObjDB->QueryObject("SELECT a.fld_email AS email, CONCAT(a.fld_fname,' ',a.fld_lname) AS name1 
									FROM `itc_user_master` AS a 
									WHERE a.`fld_id`='".$id[0]."' AND a.`fld_delstatus`='0'");
		$row = $qrydetails->fetch_assoc();
		extract($row);
		
	
?>
<script language="javascript"> $.getScript("users/homepurchase/users-homepurchase-newhomepurchase.js"); </script>
<section data-type='#users-districts' id='users-homepurchase-newhomepurchaseview'>

	<div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">View the home purchase details</p>
                <p class="dialogSubTitleLight">&nbsp;</p>
            </div>
        </div>
        
        <div class='row formBase'>
            <div class='eleven columns centered insideForm'>
                <div class="row">
                
                    <div class="six columns">
                        <div class="wizardReportcols">
                            <div class="wizardReportDesc">Admin Name:</div>
                                <div class="wizardReportData"><?php echo $name1;?></div>
                            <div class="wizardReportDesc">Email Address:</div>
                                <div class="wizardReportData"><?php echo $email;?></div>
                        </div>
                    </div>
                    
                    <div class="six columns">
                        <div class="wizardReportcols">
                            <div class="wizardReportDesc">Plan Name:</div>
                            <div class="wizardReportData"><?php
                                 $qryslicdetails = $ObjDB->QueryObject("SELECT  DISTINCT(a.`fld_id`) AS licid, a.`fld_license_name` AS licname 
                                                                    FROM `itc_license_master` AS a, `itc_license_track` AS b 
                                                                    WHERE a.fld_id = b.fld_license_id AND b.fld_district_id='0' 
                                                                        and b.fld_school_id='0' and fld_user_id='".$id[0]."' 
                                                                        and a.fld_delstatus='0'"); 
                                 
                                 if($qryslicdetails->num_rows!=0){ 
                                    while($row=$qryslicdetails->fetch_assoc()){
                                    extract($row);
                                    if(strlen($licname)>15){ $templicname = substr($licname,0,15)."..."; } else { $templicname =$licname;}
                                    ?>
                                        <div class="wizardReportData" style="cursor:pointer;" onclick="fn_homelicdet(<?php echo $licid;?>,'<?php echo $id[0];?>');"><?php echo $templicname;?></div>
                                    <?php
                                    }
                                 }
                                 ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
               
        <div id="homelicdetails" style="display: none;" >
        </div>
    </div>
</section>
<?php
	@include("footer.php");