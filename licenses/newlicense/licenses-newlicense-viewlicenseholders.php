<?php
/*
	Page - licenses-newlicense-viewlicenseholders
	Description:
	This is used for show the license holder(district,school,individual) for particular licenses
	
	Actions Performed:
	
	
	History:
*/
	@include("sessioncheck.php");
	$licenseid = isset($method['id']) ? $method['id'] : '0';
	$licensename = 	$ObjDB->SelectSingleValue("SELECT fld_license_name 
											  FROM itc_license_master 
											  WHERE fld_id='".$licenseid."'");
?>
<section data-type='2home' id='licenses-newlicense-viewlicenseholders'>
    <div class='container'>
        <div class='row'>
            <div class="span10">
            	<p class="dialogTitle"><?php echo $licensename;?></p>
                <p class="dialogSubTitleLight">To view, or edit a License Holder, click anywhere on it's row.</p>
            </div>
        </div>             
        <div class='row rowspacer'>
            <div class='span10 offset1'>
                <table class='table table-hover table-striped table-bordered'>
                    <thead class='tableHeadText'>
                        <tr>
                            <th>
                              License holder                              
                            </th>
                            <th class='centerText'>
                              #of users                              
                            </th>
                            <th class='centerText'>
                              Start Date                              
                            </th>    
                            <th class='centerText'>
                              End Date                              
                            </th> 
                        </tr>
                    </thead>
                    <tbody>
					<?php 
						//get the details of district which is using this license
                        $qry_district = $ObjDB->QueryObject("SELECT d.* 
															FROM (SELECT c.fld_license_type AS licensetype, b.fld_id AS did, b.fld_district_name AS sname, 
																CONCAT((a.fld_no_of_users-a.fld_remain_users),' / ',a.fld_no_of_users) AS users, a.fld_start_date AS sdate, 
																a.fld_end_date AS edate FROM itc_license_track AS a 
																LEFT JOIN itc_district_master AS b ON a.fld_district_id=b.fld_id
																LEFT JOIN itc_license_master AS c ON a.fld_license_id=c.fld_id
																WHERE a.fld_school_id=0 AND a.fld_user_id=0 AND 
																a.fld_delstatus='0' AND a.fld_license_id='".$licenseid."' AND b.fld_delstatus='0' ORDER BY a.fld_id DESC) AS d 
															GROUP BY d.did");
						
						//get the details of schools which is using this license
                        $qry_school = $ObjDB->QueryObject("SELECT d.* 
														  FROM (SELECT c.fld_license_type AS licensetype,b.fld_id AS sid, b.fld_district_id AS did, b.fld_school_name AS sname,
															CONCAT((a.fld_no_of_users-a.fld_remain_users),' / ',a.fld_no_of_users) AS users, a.fld_start_date AS sdate, a.fld_end_date AS
															edate FROM itc_license_track AS a LEFT JOIN itc_school_master AS b ON a.fld_school_id=b.fld_id 
															LEFT JOIN itc_license_master AS c ON a.fld_license_id=c.fld_id
															WHERE a.fld_user_id=0 AND a.fld_delstatus='0' AND a.fld_license_id='".$licenseid."' AND
															b.fld_delstatus='0' ORDER BY a.fld_id DESC) AS d 
														  GROUP BY d.sid");
						
						//get the details of Individuals which is using this license
                        $qry_individual = $ObjDB->QueryObject("SELECT d.* 
															  FROM (SELECT c.fld_license_type AS tlicensetype,b.fld_id AS iid, b.fld_fname AS sname, 
																  CONCAT((a.fld_no_of_users-a.fld_remain_users),' / ',a.fld_no_of_users) AS users, a.fld_start_date AS sdate, 
																  a.fld_end_date AS edate FROM itc_license_track AS a 
																  LEFT JOIN itc_user_master AS b ON a.fld_user_id=b.fld_id
																  LEFT JOIN itc_license_master AS c ON a.fld_license_id=c.fld_id
																  WHERE a.fld_school_id=0 AND a.fld_district_id=0 AND 
																  a.fld_delstatus='0' AND a.fld_license_id='".$licenseid."' AND b.fld_delstatus='0' ORDER BY a.fld_id DESC) AS d 
															  GROUP BY d.iid");
                        if($qry_district->num_rows==0 && $qry_school->num_rows==0 && $qry_individual->num_rows==0){ ?>
                        <tr>
                        	<td colspan="4" style="text-align:center; font-weight:bold">No Records Found</td>
                        </tr>  <?php
                        } 
                        else{ ?>
                        <tr>
                            <td colspan="4" style="text-align:center; font-weight:bold">District</td>
                        </tr> <?php
                            /*----------for districts--------------*/
                            while($res_district=$qry_district->fetch_assoc()){
                            extract($res_district);
							//get the start and end date for this license for distict
							$distdetails = $ObjDB->QueryObject("SELECT fld_start_date as sdate, fld_end_date as edate 
															   FROM itc_license_track 
															   WHERE fld_district_id='".$did."' AND fld_school_id=0 AND fld_license_id='".$licenseid."' 
															   AND fld_delstatus='0' AND '".date("Y-m-d")."' BETWEEN fld_start_date AND fld_end_date LIMIT 0,1");
							if($distdetails->num_rows>0){
								extract($distdetails->fetch_assoc());
							}
                        ?>                         
                        <tr class="mainBtn" id="btnlicenses-newlicense-viewlicensedistrict" name="<?php echo $did.",".$licenseid; ?>">                        
                            <td>
                              <?php echo $sname; ?>
                            </td>
                            <td class='centerText'>
                             <?php echo $users; ?>
                            </td>
                            <td class='centerText'>
                              <?php echo date("Y-m-d",strtotime($sdate)); ?>
                            </td>    
                             <td class='centerText'>
                              <?php echo date("Y-m-d",strtotime($edate)); ?>
                            </td>              
                        </tr>  
                        <?php } ?>                            
                        <tr>
                        	<td colspan="4" style="text-align:center; font-weight:bold">School</td>
                        </tr>
						<?php /*----------for schools--------------*/
                        while($res_school=$qry_school->fetch_assoc()){
                        extract($res_school);
						//get the start and end date for this license for School
						$schooldetails = $ObjDB->QueryObject("SELECT fld_start_date as sdate, fld_end_date as edate 
															 FROM itc_license_track 
															 WHERE fld_school_id='".$sid."' AND fld_license_id='".$licenseid."' AND fld_delstatus='0' AND '".date("Y-m-d")."' 
															 BETWEEN fld_start_date AND fld_end_date LIMIT 0,1");
							if($schooldetails->num_rows>0){
								extract($schooldetails->fetch_assoc());
							}
                        ?>                        
                        <tr class="mainBtn" <?php if($did!=0){?>id="btnlicenses-newlicense-viewlicenseschool"<?php }else{?>id="btnlicenses-newlicense-viewlicensesp"<?php }?> name="<?php echo $sid.",".$licenseid; ?>">
                            <td>
                            	<?php echo $sname; ?>
                            </td>
                            <td class='centerText'>
                            	<?php echo $users; ?>
                            </td>
                            <td class='centerText'>
                           		<?php echo date("Y-m-d",strtotime($sdate)); ?>
                            </td>    
                            <td class='centerText'>
                            	<?php echo date("Y-m-d",strtotime($edate)); ?>
                            </td>              
                        </tr>  
                        <?php } ?>
                        <tr>
                        	<td colspan="4" style="text-align:center; font-weight:bold">Individual</td>
                        </tr>
                        <?php
                        /*----------for individual--------------*/
                        while($res_individual=$qry_individual->fetch_assoc()){
                        extract($res_individual);
						//get the start and end date for this license for Individual
						$newqry = $ObjDB->QueryObject("SELECT fld_start_date as sdate, fld_end_date as edate 
													  FROM itc_license_track 
													  WHERE fld_user_id='".$iid."' AND fld_license_id='".$licenseid."' AND fld_delstatus='0' AND '".date("Y-m-d")."' 
													  	BETWEEN fld_start_date AND fld_end_date LIMIT 0,1");
							if($newqry->num_rows>0){
								extract($newqry->fetch_assoc());
							}
                        ?>
                        
                        <tr <?php if($tlicensetype==1){?>class="mainBtn" <?php }?> id="btnlicenses-newlicense-viewlicenseind" name="<?php echo $iid.",".$licenseid; ?>">
                            <td>
                            	<?php echo $sname; ?>
                            </td>
                            <td class='centerText'>
                            	<?php echo $users; ?>
                            </td>
                            <td class='centerText'>
                            	<?php echo date("Y-m-d",strtotime($sdate)); ?>
                            </td>    
                            <td class='centerText'>
                            	<?php echo date("Y-m-d",strtotime($edate)); ?>
                            </td>              
                        </tr>  
                        <?php }
                        }?>                                                    
                    </tbody>
                </table>
            </div>
        </div>        
    </div>
</section>
<?php
	@include("footer.php");