<?php 
/*
	Page - licenses-newlicense-viewlicenseind
	Description:
	This is used for show the license detatils for particular Home purchase and add another licnese
	
	Actions Performed:
	
	
	History:
*/

@include("sessioncheck.php");

$id = isset($method['id']) ? $method['id'] : '';
$id = explode(',',$id);//id[0]->individual id, id[1]->licenseid
$ind_id = $id[0];
$templicenseid = $id[1]; // comes from licenses-newlicense-actions.php

//get Individual user name, licensecount
$getbasicdetails = $ObjDB->QueryObject("SELECT (SELECT CONCAT(fld_fname,' ',fld_lname) FROM itc_user_master WHERE fld_id='".$ind_id."') AS indname, 
											  (SELECT count(fld_id) FROM itc_license_master  WHERE fld_delstatus='0' AND fld_license_type='1') AS totalhidlicense,
											  (SELECT COUNT(fld_id) FROM itc_license_track WHERE fld_user_id='".$ind_id."' AND fld_school_id=0 AND fld_delstatus='0') 
											    AS licensecount");
if($getbasicdetails->num_rows>0)
	extract($getbasicdetails->fetch_assoc());
?>
<script type="text/javascript" charset="utf-8">		
	$.getScript("users/homepurchase/users-homepurchase-newhomepurchase.js");
</script>
<section data-type='license' id='licenses-newlicense-viewlicenseind'>
	<div class='container'>
    	<div class='row'>
          <div class='twelve columns'>
            <p class="dialogTitle"><?php echo $indname; ?></p>
            <p class="dialogSubTitleLight">&nbsp;</p>
          </div>
        </div>
        <div class='row formBase'>
            <div class='eleven columns centered insideForm'>
                  <form method='post' name="distval" id="distval">                                       
                    <div class="row" id="addlicensehome"> 
                	<?php 
                            $count = 0;
							$individualqry = $ObjDB->QueryObject("SELECT a.fld_id AS trackid, a.fld_license_id AS licenseid, a.fld_renewal_count, b.fld_license_name AS licensename, 
																	fn_shortname(b.fld_license_name,2) AS licenseshortname, a.fld_no_of_users AS totusers, a.fld_start_date AS startdate, 
																	a.fld_end_date AS enddate, a.fld_upgrade AS upgradeflag, a.fld_ipl_count AS iplcount, 
																	a.fld_mod_count AS modcount, a.fld_auto_renewal AS renewal 
																FROM itc_license_track AS a LEFT JOIN itc_license_master AS b ON a.fld_license_id=b.fld_id
																WHERE a.fld_district_id='0' AND a.fld_school_id='0' AND a.fld_user_id='".$ind_id."' AND a.fld_delstatus='0'");						
                            while($res = $individualqry->fetch_assoc()){
                                extract($res);
                                $count++;
                            ?>                      
                            <div class="row" id="lic<?php echo $count; ?>">
                                <div class="row">
                                    <div class="four columns">
                                     Licenses<span class="fldreq">*</span> 
                                        <dl class='field row'>
                                            <dt class='dropdown'>
                                                <div class="selectbox">
                                                    <input type="hidden" name="ddllic<?php echo $count; ?>" id="ddllic<?php echo $count; ?>" value="<?php echo $licenseid; ?>,<?php echo $trackid; ?>" onchange="$(this).valid()" />
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
                                            <input  id="noofusers<?php echo $count; ?>" name="noofusers<?php echo $count; ?>" placeholder='users' tabindex="18" type='text' value="<?php echo $totusers; ?>" onblur="fn_chkusercountdist(<?php echo $count.",".$trackid; ?>)" />
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
                                    <div id="grace<?php echo $count; ?>"> 
                                    <?php                                       
                                         if($iplcount>0){
                                        ?>
                                        
                                        <div class="one columns" style="padding-left:15px;">
                                            IPl                       	
                                            <dl class='field row'>
                                                <dt class='text'>
                                                    <input  id="iplcount<?php echo $count; ?>" name="iplcount<?php echo $count; ?>" placeholder='IPL' tabindex="21" type='text' value="<?php echo $iplcount; ?>" maxlength="2" />
                                                </dt>
                                            </dl>
                                        </div>  
                                        <?php } if($modcount>0){?> 
                                        <div class="one columns" <?php if($iplcount==0){ ?>style="padding-left:15px;" <?php }?>>
                                            Modules                          	
                                            <dl class='field row'>
                                                <dt class='text'>
                                                    <input  id="modcount<?php echo $count; ?>" name="modcount<?php echo $count; ?>" placeholder='module' tabindex="22" type='text' value="<?php echo $modcount; ?>" maxlength="2" />
                                                </dt>
                                            </dl>
                                        </div> 
                                        <?php 
                                        }?>                    
                                    </div> 
                                    <?php if($count!=1 && date("Y-m-d", strtotime($startdate)) > date("Y-m-d")){ ?> 
                                        <div class='one columns' style=" <?php if($iplcount==0 && $modcount!=0){ ?> padding-left:8px; <?php } if($renewal==1){?>display:none;<?php } ?>">
                                            remove
                                            <p class='btn twelve columns'>
                                                <a onclick="if(confirm('Are you sure want to delete this license?')){fn_removehplicense(<?php echo $count;?>,1,<?php echo $trackid; ?>);}" id="rmove"> - </a>
                                            </p>     
                                        </div> 
                                    <?php } else if($upgradeflag==1 && date("Y-m-d", strtotime($startdate)) < date("Y-m-d")){?>
                                        <div class='one columns' id="upgrade_<?php echo $trackid; ?>" style=" <?php if($iplcount==0 && $modcount!=0){ ?> padding-left:8px; <?php } if($renewal==1){?>display:none;<?php } ?>">
                                            upgrade
                                            <p class='btn twelve columns'>
                                                <a onclick="fn_upgrade(<?php echo $licenseid.",".$trackid; ?>)" id="rmove"> ↑ </a>
                                            </p>     
                                        </div> 
    
                                    <?php }?>
                                </div>                                                          
                                <input type="hidden" id="currentlicense<?php echo $count; ?>" value="<?php echo $licenseid; ?>" />
                                <input type="hidden" id="errorcount<?php echo $count; ?>" value="0" />
                                <div class="row">
                                	<div class='two columns'>
                                        <ul class="field row" onclick="fn_clickrenewal(<?php echo $count; ?>,<?php echo $trackid; ?>);fn_renewalcount(<?php echo $count; ?>)">
                                            <li>
                                                <label class="checkbox <?php if($renewal==1) echo "checked";?>" for="checkbox<?php echo $count; ?>">
                                                <input type="checkbox" id="checkbox<?php echo $count; ?>" style="display:none;" value="0" <?php if($renewal==1){?>checked="checked"<?php }?> />
                                                <span></span> Auto renewal
                                                </label>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class='one columns' title="No of times for auto renewal" id="rcountdiv_<?php echo $count;?>" <?php if($renewal==0){?>style="display:none;"<?php }?>>
                                        <dl class='field row'>
                                            <dt class='text'>
                                               <input type="text" id="renewalcount_<?php echo $count; ?>" maxlength="2" value="<?php echo $fld_renewal_count; ?>" />
                                            </dt>
                                        </dl> 
                                        <script>
                                            $("#renewalcount_<?php echo $count; ?>").keypress(function (e) {
                                                if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                                                    return false;
                                                }
                                            });		
                                        </script>                               	
                                    </div>
                                </div> 
                            </div>
                            <?php if(date("Y-m-d", strtotime($startdate)) > date("Y-m-d")){?>
                                <script>							
                                    $( "#sdate"+<?php echo $count; ?>).datepicker({
                                        minDate: new Date,
                                        onSelect: function(dateText,inst){	
                                            fn_endate(<?php echo $count; ?>);
                                        }
                                    });
                                </script>
                            <?php }?>
                            <script>
							$("#noofusers<?php echo $count; ?>").keypress(function (e) {
								if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
									return false;
								}
							});		
						</script>
                        <?php 
                        }?>
                    </div>
                        
                    <div class="row rowspacer">
                        <div class="four columns">
                            <p class='btn medium <?php if($totalhidlicense==$licensecount or $ind_id==0) echo "dim";?>' id="add">
                                <a onclick="addlichome($('#hidaddlicense').val());">Add another License</a>
                            </p> 
                        </div>
                    </div>
                    <input type="hidden" id="hidaddlicense" value="<?php if($ind_id==0)echo '1'; else echo $count;?>" />
                    <input type="hidden" id="hiddistid" value="<?php echo $ind_id; ?>" />
                    <input type="hidden" id="hidtotallicense" value="<?php echo $totalhidlicense; ?>" /> 
                    <div class='row'>
                        <div class='six columns'>                            
                            <p class='btn primary twelve columns'>
                                <a onClick="fn_cancel('licenses-newlicense-viewlicenseholders')">Cancel.</a>
                            </p>                            
                        </div>
                        <div class='six columns'>                            
                            <p class='btn secondary twelve columns'>
                                <a onclick="fn_updatelicense(<?php echo $ind_id;?>,'individual',<?php echo $templicenseid;?>)">Update</a>
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