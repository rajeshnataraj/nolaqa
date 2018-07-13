<?php 
@include("table.class.php");
@include("comm_func.php");
$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '0';
$id = explode("~",$id);

$sessmasterprfid = isset($_REQUEST['sessmasterprfid']) ? $_REQUEST['sessmasterprfid'] : '0';
$schoolid = isset($_REQUEST['schoolid']) ? $_REQUEST['schoolid'] : '0';

$extraqry='';
$exqry='';

if($sessmasterprfid==2 || $sessmasterprfid==3)
{
	if($id[0] != '' and $id[0] != 'school' and  $id[0] != 'home' and $id[1] == ''){ // for all district user reports 
		$distqry = $ObjDB->QueryObject("SELECT fld_id AS districtid, fld_district_name AS districtname FROM itc_district_master WHERE fld_id='".$id[0]."'");
		if($distqry->num_rows > 0){
			while($rowdist = $distqry->fetch_assoc()){
				extract($rowdist);
				$filename = $districtname." - DistrictReport";
			}
		}
	}

	else if($id[0] != '' and $id[0] != 'school' and  $id[0] != 'home' and $id[1] !='') // for all school user reports 
	{
		$distqry = $ObjDB->QueryObject("SELECT fld_id AS rptschoolid, fld_district_id AS districtid, fld_school_name AS districtname 
										FROM itc_school_master 
										WHERE fld_id ='".$id[1]."'");
		if($distqry->num_rows > 0){
			while($rowdist = $distqry->fetch_assoc()){
				extract($rowdist);
				$filename = $districtname." - SchoolReport";
				$extraqry = "AND fld_id='".$rptschoolid."'";
			}
		}
	}
	
	else if($id[0] == 'school' and $id[1] !='') // for all school purchase reports 
	{
		$distqry = $ObjDB->QueryObject("SELECT fld_id AS rptschoolid, fld_district_id AS districtid, fld_school_name AS districtname 
										FROM itc_school_master 
										WHERE fld_id ='".$id[1]."' and  fld_district_id='0' ");
		if($distqry->num_rows > 0){
			while($rowdist = $distqry->fetch_assoc()){
				extract($rowdist);
				$filename = $districtname." - School purchase Report";
				$extraqry = "AND fld_id='".$rptschoolid."'";
			}
		}
	}
	
	else if($id[0] == 'home' and $id[1] !='') // for all home purchase reports 
	{
		$username=$ObjDB->SelectSingleValue("SELECT  CONCAT(`fld_fname`,' ',`fld_lname` ) AS fullname ,fld_id 
											FROM `itc_user_master`  
											WHERE fld_district_id='0' AND fld_school_id='0' AND fld_profile_id='5' 
												AND fld_user_id='".$id[1]."' ");
		$districtid = 0;
		$districtname = $username."- home purchase reports";
		$filename = $districtname;
		$extraqry = "";
		$exqry="and a.fld_user_id='".$id[1]."'";  
	}
}

if($sessmasterprfid==6)
{
	$distqry = $ObjDB->QueryObject("SELECT fld_id AS rptschoolid, fld_district_id AS districtid, fld_school_name AS districtname 
									FROM itc_school_master 
									WHERE fld_id ='".$id[1]."'");
	if($distqry->num_rows > 0){
		while($rowdist = $distqry->fetch_assoc()){
			extract($rowdist);
		}
	}
	$filename = $districtname." SchoolReport";
	$extraqry = "AND fld_id='".$rptschoolid."'";
}

if($sessmasterprfid==7)
{
	$distqry = $ObjDB->QueryObject("SELECT fld_id AS rptschoolid, fld_district_id AS districtid, fld_school_name AS districtname 
									FROM itc_school_master 
									WHERE fld_id ='".$schoolid."'");
	if($distqry->num_rows > 0){ 
		while($rowdist = $distqry->fetch_assoc()){
			extract($rowdist);
		}
	}
	$filename = $districtname." SchoolReport";
	$extraqry = "AND fld_id='".$rptschoolid."'";
}
?>
<style>
	.title
	{
		font-size: 50px; color:#808080; font-family:Arial;
	}
	.trgray
	{
		font-size:30px; background-color:#CCCCCC; font-weight:normal; 
	}
	.trclass
	{
		font-size:30px; background-color:#FFFFFF; font-weight:normal;
	}
	.tdleft{
		border-top:1px solid #b4b4b4; border-left:1px solid #b4b4b4; border-bottom:1px solid #b4b4b4;
	}
	
	.tdmiddle{
		border-top:1px solid #b4b4b4; border-bottom:1px solid #b4b4b4;
	}
	.tdright{
		border-top:1px solid #b4b4b4; border-right:1px solid #b4b4b4; border-bottom:1px solid #b4b4b4;
	}
	.tryellow{
		background-color:#FF0;
	}
</style>
<table cellpadding="2" cellspacing="0" >
	<tr style="font-size:35px; font-weight:bold">
        <th class='centerText'>School Name</th>
        <th class='centerText'>User Type</th>
        <th class='centerText'>Full Name</th>
        <th class='centerText'>Email</th>
        <th class='centerText'>User Name</th>
        <th class='centerText'>Password</th>
    </tr>
	
    <tbody>
		<?php     
        if($id[0] != 'home')
        {
            $schqry = $ObjDB->QueryObject("SELECT fld_id, fld_school_name 
											FROM itc_school_master 
											WHERE fld_district_id='".$districtid."' ".$extraqry." and fld_delstatus='0'");
		
			$rowid = 6;
			if($schqry->num_rows > 0){
				while($rowsch = $schqry->fetch_object()){
					$schcnt=0;
					$schid = $rowsch->fld_id;
					$schname = $rowsch->fld_school_name;
					$schuserqry = $ObjDB->QueryObject("SELECT a.fld_id, a.fld_email, c.fld_prf_main_id, c.fld_profile_name, 
															CONCAT(TRIM(a.fld_fname),' ',TRIM(a.fld_lname)) AS fullname, 
															a.fld_username, a.fld_password 
														FROM itc_user_master AS a 
														LEFT JOIN itc_school_master AS b ON a.fld_school_id=b.fld_id 
														LEFT JOIN itc_profile_master AS c ON a.fld_profile_id=c.fld_id 
														WHERE a.fld_district_id=".$districtid." AND a.fld_school_id=".$schid." 
															AND c.fld_prf_main_id<>11 AND a.fld_delstatus='0' ".$exqry." 
														ORDER BY c.fld_prf_main_id");
					if($schuserqry->num_rows > 0)
					{
						while($rowschqry = $schuserqry->fetch_object())
						{
							$password = fnDecrypt($rowschqry->fld_password, $encryptkey);
							if($rowschqry->fld_username=='')
								$password='';
							?>
							<tr class="trclass <?php if($rowschqry->fld_prf_main_id==10) { ?>tryellow<?php }?>">
								<?php if($schcnt==0) { ?>
                                <td class="trclass tdleft tdright" rowspan="<?php echo $schuserqry->num_rows; ?>" align="center"><?php echo $schname; ?></td>
                                <?php  } ?> 
                                <td class="tdleft"><?php echo  $rowschqry->fld_profile_name; ?></td>
                                <td class="tdmiddle"><?php echo  $rowschqry->fullname; ?></td>
                                <td class="tdmiddle"><?php echo  $rowschqry->fld_email; ?></td>
                                <td class="tdmiddle"><?php echo  $rowschqry->fld_username; ?></td>
                                <td class="tdright"><?php echo  $password; ?></td>
							</tr>   
							<?php	
							$schcnt++;
						}
					}
				}
			}
		} 
		
		if($id[0] == 'home')
		{
			$schqry = $ObjDB->QueryObject("SELECT  CONCAT(`fld_fname`,' ',`fld_lname` ) AS fullname ,fld_id 
											FROM `itc_user_master` 
											WHERE fld_district_id='0' AND fld_school_id='0' AND fld_profile_id='5' 
												AND fld_user_id='".$id[1]."' ");
			
			$rowid = 6;
			if($schqry->num_rows > 0){
				while($rowsch = $schqry->fetch_object()){
					$schcnt=0;
					$schid = $rowsch->fld_id;
					$schname = $rowsch->fullname;
					$schuserqry = $ObjDB->QueryObject("SELECT a.fld_id, a.fld_email, c.fld_prf_main_id, c.fld_profile_name, 
														 CONCAT(TRIM(a.fld_fname),' ',TRIM(a.fld_lname)) AS fullname, 
														 a.fld_username, a.fld_password 
														FROM itc_user_master AS a 
														LEFT JOIN itc_school_master AS b ON a.fld_school_id=b.fld_id 
														LEFT JOIN itc_profile_master AS c ON a.fld_profile_id=c.fld_id 
														WHERE a.fld_district_id=".$districtid." AND a.fld_school_id=0 
															AND c.fld_prf_main_id<>11 AND a.fld_delstatus='0' ".$exqry." 
														ORDER BY c.fld_prf_main_id, a.fld_lname");
					if($schuserqry->num_rows > 0){
						while($rowschqry = $schuserqry->fetch_object()){
							$password = fnDecrypt($rowschqry->fld_password, $encryptkey);
							if($rowschqry->fld_username=='')
								$password='';
							?>
                            <tr class="trclass">
								<?php if($schcnt==0) { ?>
                                <td class="trclass tdleft tdright" rowspan="<?php echo $schuserqry->num_rows; ?>" valign="middle"  align="center" ><?php echo $schname; ?></td>
                                <?php  } ?> 
                                <td class="tdleft"><?php echo  $rowschqry->fld_profile_name; ?></td>
                                <td class="tdmiddle"><?php echo  $rowschqry->fullname; ?></td>
                                <td class="tdmiddle"><?php echo  $rowschqry->fld_email; ?></td>
                                <td class="tdmiddle"><?php echo  $rowschqry->fld_username; ?></td>
                                <td class="tdright"><?php echo  $password; ?></td>
							</tr>   
							<?php	
							$schcnt++;
						}
					}
				}
			}
		} ?>
    </tbody>
</table>
