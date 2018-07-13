<?php
/*
 * created by - Vijayalakshmi PHP programmer
 * creating sub_icons for expeditions
 */
@include("sessioncheck.php");

$menuid= isset($_REQUEST['id']) ? $_REQUEST['id'] : '';

$expcount = $ObjDB->SelectSingleValueInt("SELECT count(a.fld_exp_id) FROM itc_license_exp_mapping AS a 
												LEFT JOIN itc_license_track AS b ON a.fld_license_id=b.fld_license_id 
												WHERE b.fld_school_id='".$schoolid."' AND b.fld_district_id='".$districtid."' 
												AND b.fld_user_id='".$indid."' AND b.fld_delstatus='0' AND a.fld_flag='1'");
                                
$miscount = $ObjDB->SelectSingleValueInt("SELECT count(a.fld_mis_id) FROM itc_license_mission_mapping AS a 
											LEFT JOIN itc_license_track AS b ON a.fld_license_id=b.fld_license_id 
											WHERE b.fld_school_id='".$schoolid."' AND b.fld_district_id='".$districtid."' 
											AND b.fld_user_id='".$indid."' AND b.fld_delstatus='0' AND a.fld_flag='1'");
                          
if($sessmasterprfid == 2)
{
	$expcount=1; $miscount=1;
}

?>
<section data-type='2home' id='library-rubrics'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="darkTitle">Grading Rubrics</p>
                <p class="darkSubTitle">&nbsp;</p>
            </div>
        </div>
        <!--Tag For Searching/Selecting-->
        <div class='row'>
            <div class='twelve columns'>            	
                <p class="<?php if($sessmasterprfid == '10'){ echo "filterLightTitle"; }else { echo "filterDarkTitle"; } ?>">To see Grading Rubrics for specific activities, click on "Missions" or "Expeditions". </p>
                <div class="tag_well">
              	</div>
            </div>
        </div>
        <div class='row buttons'>
			<?php
			if($expcount!=0)
			{ 	?>
				<a class='skip btn mainBtn' href='#library-rubric' id='btnlibrary-rubric' name='<?php echo "0";?>'>
					<div class='icon-synergy-courses'></div>
					<div class='onBtn'>Expeditions</div>
				</a>
				<?php 
			} 
			if($miscount!=0)
			{ 	?>
				<a class='skip btn mainBtn' href='#library-missionrubric' id='btnlibrary-missionrubric' name='<?php echo "0";?>'>
					<div class='icon-synergy-courses'></div>
					<div class='onBtn'>Missions</div>
				</a>
				<?php 
			} ?>
        </div>
    </div>
</section>
<?php
	@include("footer.php");
