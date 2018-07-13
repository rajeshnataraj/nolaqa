<?php
@include("sessioncheck.php");
$id= isset($method['id']) ? $method['id'] : '';		
?>
<section data-type='2home' id='assignment-activities'>
    <div class='container'>
        <div class='row'>
            <div class="span10">
                <p class="dialogTitle">Activities</p>
                <p class="dialogSubTitleLight">&nbsp;Select a Activity for View.</p>
            </div>           
        </div>      
               
        <div class='row buttons rowspacer' id="activitylist">
			<?php 
            $qry = $ObjDB->QueryObject("SELECT ac.fld_id AS activityid, ac.fld_activity_name AS activityname, b.fld_id AS lessonid, CONCAT(b.fld_ipl_name,' ',(SELECT fld_version FROM itc_ipl_version_track WHERE fld_ipl_id=b.fld_id AND fld_zip_type='1' AND fld_delstatus='0')) AS lessonname, b.fld_ipl_icon AS lessonicon, b.fld_unit_id AS unitid, un.fld_unit_name AS unitname FROM itc_class_sigmath_student_mapping AS a LEFT JOIN itc_class_sigmath_lesson_mapping AS c ON a.fld_sigmath_id=c.fld_sigmath_id LEFT JOIN itc_ipl_master AS b ON b.fld_id=c.fld_lesson_id LEFT JOIN itc_class_sigmath_master AS d ON d.fld_id=c.fld_sigmath_id LEFT JOIN itc_class_master AS e ON e.fld_id=d.fld_class_id LEFT JOIN itc_unit_master AS un ON un.fld_id=b.fld_unit_id LEFT JOIN itc_activity_master AS ac ON ac.fld_unit_id=b.fld_unit_id WHERE a.fld_student_id='".$uid."' AND ac.fld_delstatus='0' AND a.fld_flag='1' AND c.fld_flag='1' AND b.fld_delstatus='0' AND b.fld_access='1' AND d.fld_delstatus='0' AND e.fld_delstatus='0' AND c.fld_license_id IN (SELECT fld_license_id FROM itc_license_track WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_start_date<='".date("Y-m-d H:i:s")."' AND fld_end_date >='".date("Y-m-d H:i:s")."') AND (ac.fld_created_by='2' OR ac.fld_school_id='".$schoolid."' OR ac.fld_user_id='".$indid."')  GROUP BY ac.fld_id");
            if($qry->num_rows>0){
				while($res = $qry->fetch_assoc()){
					extract($res);
					?>
					<a class='skip btn mainBtn' href='#library-activities' name="<?php echo $activityid;?>" id='btnlibrary-activities-viewactivity'>
                        <div class='icon-synergy-activities'></div>
                        <div class='onBtn' title="<?php echo $activityname;?>"><?php echo $activityname;?></div>
					</a>      
				<?php }
            }?>
        </div>
    </div>
</section>
<?php
@include("footer.php");