<?php 
/*
	Page - licenses-newlicense-viewlicenseschool
	Description:
	This is used for show the license detatils for particular school and add another licnese
	
	Actions Performed:
	
	
	History:
*/

@include("sessioncheck.php");

$id = isset($method['id']) ? $method['id'] : '';
$id = explode(',',$id);//id[0]->school id, id[1]->licenseid
$school_id = $id[0];
$templicenseid = $id[1];

//get School name, districid, licensecount
$getbasicdetails = $ObjDB->QueryObject("SELECT (SELECT fld_school_name FROM itc_school_master WHERE fld_id='".$school_id."') AS sname,
											  (SELECT fld_district_id FROM itc_school_master WHERE fld_id='".$school_id."') AS distid,
											  (SELECT COUNT(DISTINCT(fld_license_id)) FROM itc_license_track WHERE fld_district_id=distid AND fld_school_id=0 AND fld_delstatus='0') 
											   AS totalhidlicense,
											  (SELECT COUNT(DISTINCT(fld_license_id)) FROM itc_license_track WHERE fld_district_id=distid AND fld_school_id='".$school_id."' 
											   AND fld_delstatus='0') AS licensecount");
if($getbasicdetails->num_rows>0)
	extract($getbasicdetails->fetch_assoc());
?>
<script type="text/javascript" charset="utf-8">		
	$.getScript("users/schools/users-schools-newschool.js");
</script>
<section data-type='license' id='licenses-newlicense-viewlicenseschool'>
	<div class='container'>
    	<div class='row'>
          <div class='twelve columns'>
            <p class="dialogTitle"><?php echo $sname; ?></p>
            <p class="dialogSubTitleLight">&nbsp;</p>
          </div>
        </div>
        <div class='row formBase'>
            <div class='eleven columns centered insideForm'>
                  <form method='post' name="distval" id="distval">                                       
                    <div class="row" id="addlicenseshl"> 
                        <?php 
							$count = 0;
                        					
                            $schoolqry = $ObjDB->QueryObject("SELECT a.fld_id AS trackid, a.fld_license_id AS licenseid, a.fld_distlictrack_id AS dtrackid, b.fld_license_name AS licensename,
																fn_shortname(b.fld_license_name,2) AS licenseshortname,a.fld_no_of_users AS totusers, a.fld_start_date AS startdate, 
																a.fld_end_date AS enddate, a.fld_upgrade AS upgradeflag, 
																a.fld_ipl_count AS iplcount, a.fld_mod_count AS modcount, a.fld_auto_renewal AS renewal 
															FROM itc_license_track AS a LEFT JOIN itc_license_master AS b ON b.fld_id=a.fld_license_id 
															WHERE a.fld_school_id='".$school_id."' AND a.fld_user_id='0' AND a.fld_delstatus='0'");						
						while($res = $schoolqry->fetch_assoc()){
							extract($res);
							$count++;
							//get district remainusers, distrenewal count, upgraded id
							$getdistdetails = $ObjDB->QueryObject("SELECT (SELECT fld_remain_users FROM itc_license_track WHERE fld_id='".$dtrackid."') AS availusers,
																  (SELECT fld_auto_renewal FROM itc_license_track WHERE fld_id='".$dtrackid."') AS drenewal,
																  (SELECT fld_id FROM itc_license_track WHERE fld_license_id='".$licenseid."' AND fld_district_id='".$distid."' AND
																  	fld_school_id=0 AND fld_delstatus='0' AND fld_start_date > '".date("Y-m-d",strtotime($enddate))."' 
																  	ORDER BY fld_id DESC LIMIT 0,1) AS upgradeid");
							if($getdistdetails->num_rows>0)
								extract($getdistdetails->fetch_assoc());
						?>                      
                    	<div class="row" id="lic<?php echo $count; ?>">
                            <div class="row">
                                <div class="four columns">                                 
                                 Licenses<span class="fldreq">*</span> &nbsp;&nbsp;Available student seats: <?php echo $availusers;?>
                                    <dl class='field row'>
                                        <dt class='dropdown'>
                                            <div class="selectbox">
                                                <input type="hidden" name="ddllic<?php echo $count; ?>" id="ddllic<?php echo $count; ?>" value="<?php echo $licenseid; ?>,<?php echo $trackid; ?>,<?php echo $dtrackid; ?>" onchange="$(this).valid()" />
                                                <a class="selectbox-toggle" tabindex="17" role="button" data-toggle="selectbox" href="#">
                                                     <span class="selectbox-option input-medium tooltip" data-option="" title="<?php echo $licensename; ?>"><?php echo $licenseshortname; ?></span><b class="caret1"></b>
                                                </a>                                                
                                            </div>
                                        </dt>
                                    </dl>
                                </div>
                                <div class="one columns">
                                    Seats<span class="fldreq">*</span> 
                                    <dl class='field row'>
                                        <dt class='text'>
                                        <input  id="noofusers<?php echo $count; ?>" name="noofusers<?php echo $count; ?>" placeholder='users' tabindex="18" type='text' value="<?php echo $totusers; ?>" onblur="fn_chkusercountshl(<?php echo $count.",".$trackid; ?>)" />
                                        </dt>
                                    </dl>
                                </div>
                                <div class="two columns">
                                    Start date<span class="fldreq">*</span> 
                                    <dl class='field row'>
                                        <dt class='text'>
                                            <input  id="sdate<?php echo $count; ?>" name="sdate<?php echo $count; ?>" placeholder='Start Date' tabindex="19" type='text' value="<?php echo date("m/d/Y", strtotime($startdate)); ?>" readonly />
                                        </dt>
                                    </dl>
                                </div>
                                <div class="two columns">
                                    End date<span class="fldreq">*</span> 
                                    <dl class='field row'>
                                        <dt class='text'>
                                            <input  id="edate<?php echo $count; ?>" name="edate<?php echo $count; ?>" placeholder='End Date' tabindex="20" type='text' value="<?php echo date("m/d/Y", strtotime($enddate)); ?>" readonly />
                                        </dt>
                                    </dl>
                                </div>                                 
                                <?php									  	
								if($count!=1 && date("Y-m-d", strtotime($startdate)) > date("Y-m-d")){ ?> 
                                    <div class='one columns'>
                                        remove
                                        <p class='btn twelve columns'>
                                            <a onclick="if(confirm('Are you sure want to delete this license?')){fn_removeshllicense(<?php echo $count;?>,1,<?php echo $trackid; ?>);}" id="rmove"> - </a>
                                        </p>     
                                    </div> 
                                <?php } else if($upgradeflag==1 && date("Y-m-d", strtotime($startdate)) < date("Y-m-d") && $drenewal==0 && $sessmasterprfid==2 && $upgradeid!=0){?>
                                    <div class='one columns' id="upgrade_<?php echo $upgradeid; ?>">
                                        upgrade
                                        <p class='btn twelve columns'>
                                        	<a onclick="fn_upgrade(<?php echo $licenseid.",".$upgradeid.",".$trackid; ?>)"> ↑ </a>
                                        </p>     
                                    </div> 

                                <?php }?>
                            </div>                                                          
                            <input type="hidden" id="currentlicense<?php echo $count; ?>" value="<?php echo $licenseid; ?>" />
                            <input type="hidden" id="errorcount<?php echo $count; ?>" value="0" />                           
                        </div>
						<script>
							$("#noofusers<?php echo $count; ?>").keypress(function (e) {
								if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
									return false;
								}
							});		
						</script>
                    <?php }                        
                    ?>
                    </div>
                        
                    <div class="row rowspacer">
                        <div class="four columns">
                            <p class='btn medium <?php if($totalhidlicense==$licensecount or $school_id==0) echo "dim";?>' id="add">
                                <a onclick="addlicshl(<?php echo $distid; ?>,$('#hidaddlicense').val());">Add another License</a>
                            </p> 
                        </div>
                    </div>
                    <input type="hidden" id="hidaddlicense" value="<?php if($school_id==0)echo '1'; else echo $count;?>" />
                    <input type="hidden" id="hidtotallicense" value="<?php echo $totalhidlicense;?>" />
                    <input type="hidden" id="hidschoolid" value="<?php echo $school_id; ?>" />
                    <input type="hidden" id="hiddistid" value="<?php echo $distid; ?>" />
                    <div class='row'>
                        <div class='six columns'>                            
                            <p class='btn primary twelve columns'>
                                <a onClick="fn_cancel('licenses-newlicense-viewlicenseholders')">Cancel</a>
                            </p>                            
                        </div>
                        <div class='six columns'>                            
                            <p class='btn secondary twelve columns'>
                                <a onclick="fn_updatelicense(<?php echo $school_id;?>,'school',<?php echo $templicenseid;?>)">Update</a>
                            </p>                           
                        </div>
                    </div>
                </form>
        	</div>
        </div>
    </div>
</section>
<?php
	@include("footer.php");