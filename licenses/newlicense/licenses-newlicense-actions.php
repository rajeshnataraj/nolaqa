<?php 
@include("sessioncheck.php");
$licenseid = isset($method['id']) ? $method['id'] : '0';
$count=0;
//get the license holders for the license
$qry = $ObjDB->QueryObject("SELECT fld_id, fld_district_id, fld_school_id, fld_user_id 
							FROM itc_license_track 
							WHERE fld_license_id='".$licenseid."' AND fld_end_date>'".date("Y-m-d")."' AND fld_delstatus='0'");
if($qry->num_rows>0){
	while($res=$qry->fetch_assoc()){
		extract($res);
		if($fld_district_id!=0){
			//get the count of district users who is using this license
			$count = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
												  FROM itc_district_master 
												  WHERE fld_id='".$fld_district_id."' AND fld_delstatus='0'");
		}
		else if($fld_school_id!=0){
			//get the count of school users who is using this license
			$count = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
												  FROM itc_school_master 
												  WHERE fld_id='".$fld_school_id."' AND fld_delstatus='0'");
		}
		else if($fld_user_id!=0){
			//get the count of Individual users who is using this license
			$count = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
												  FROM itc_user_master 
												  WHERE fld_id='".$fld_user_id."' AND fld_delstatus='0'");
		}
		if($count>0){
			break;
		}
	}		
}
?>
<script language="javascript" type="text/javascript">
	$.getScript("licenses/newlicense/licenses-newlicense.js");	
</script>
<section data-type='#library-modules' id='licenses-newlicense-actions'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
                <p class="dialogTitle">
					<?php echo $ObjDB->SelectSingleValue("SELECT fld_license_name 
														 FROM itc_license_master 
														 WHERE fld_id='".$licenseid."'");?>
                </p>
                <p class="dialogSubTitle">&nbsp;</p>
            </div>
        </div>
        <div class='row buttons'>
            <a class='skip btn mainBtn' href='#licenses-newlicenses' id='btnlicenses-newlicense-viewlicense' name='<?php echo $licenseid;?>'>
                <div class='icon-synergy-view'></div>
                <div class='onBtn'>View<br />content</div>
            </a>
            <a class='skip btn mainBtn' href='#licenses-newlicenses' id='btnlicenses-newlicense-viewlicenseholders' name='<?php echo $licenseid;?>'>
                <div class='icon-synergy-users'></div>
                <div class='onBtn'>View license<br />holders</div>
            </a>              
            <a class='skip btn mainBtn' href='#licenses-newlicenses' id='btnlicenses-newlicense' name='<?php echo $licenseid;?>'>
                <div class='icon-synergy-edit'></div>
                <div class='onBtn'>Edit this<br />license</div>
            </a>
            <a class='skip btn main <?php if($count>0){ echo "dim"; }?>' href='#licenses-newlicenses' onClick="fn_deletelicense(<?php echo $licenseid;?>)" name='<?php echo $licenseid;?>'>
                <div class='icon-synergy-trash'></div>
                <div class='onBtn'>Delete this<br />license</div>
            </a>
        </div>
    </div>
</section>
<?php
	@include("footer.php");