<?php 
@include("sessioncheck.php");

$date = date("Y-m-d H:i:s");
$oper = isset($method['oper']) ? $method['oper'] : '';

/*--- Load Student Dropdown ---*/
if($oper=="showschedule" and $oper != " " )
{
	$classid = isset($method['classid']) ? $method['classid'] : '';	
	?>
	Schedule
	<div class="selectbox">
        <input type="hidden" name="scheduleid" id="scheduleid" value="">
        <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
            <span class="selectbox-option input-medium" data-option="" style="width:97%">Select Schedule</span>
            <b class="caret1"></b>
        </a>
        <div class="selectbox-options">
            <input type="text" class="selectbox-filter" placeholder="Search Schedule">
            <ul role="options" style="width:100%">
                <?php 
                $qry = $ObjDB->QueryObject("SELECT w.* FROM (
											SELECT fld_id AS schid, fld_schedule_name AS schname, 0 AS schtype 
											FROM itc_class_sigmath_master 
											WHERE fld_class_id='".$classid."' AND fld_delstatus='0' AND fld_flag='1' 		
												UNION ALL		
											SELECT fld_id AS schid, fld_schedule_name AS schname, (CASE WHEN fld_moduletype='1' THEN '1' 
											WHEN fld_moduletype='2' THEN '4' END) AS schtype 
											FROM itc_class_rotation_schedule_mastertemp 
											WHERE fld_class_id='".$classid."' AND fld_delstatus='0' AND fld_flag='1'		
												UNION ALL		
											SELECT fld_id AS schid, fld_schedule_name AS schname, 2 AS schtype 
											FROM itc_class_dyad_schedulemaster 
											WHERE fld_class_id='".$classid."' AND fld_delstatus='0' AND fld_flag='1' 		
												UNION ALL	
											SELECT fld_id AS schid, fld_schedule_name AS schname, 3 AS schtype 
											FROM itc_class_triad_schedulemaster 
											WHERE fld_class_id='".$classid."' AND fld_delstatus='0' AND fld_flag='1' 		
												UNION ALL	
											SELECT fld_id AS schid, fld_schedule_name AS schname, (CASE WHEN fld_moduletype='1' THEN '5' 
											WHEN fld_moduletype='2' THEN '6' WHEN fld_moduletype='7' THEN '7' END) AS schtype 
											FROM itc_class_indassesment_master 
											WHERE fld_class_id='".$classid."' AND fld_delstatus='0' AND fld_flag='1'
												UNION ALL	
											SELECT fld_id AS schid, fld_schedule_name AS schname, fld_scheduletype AS schtype 
											FROM itc_class_indasexpedition_master 
											WHERE fld_class_id='".$classid."' AND fld_delstatus='0' AND fld_flag='1'
											UNION ALL		
											SELECT fld_id AS schid, fld_schedule_name AS schname, 19 AS schtype 
											FROM itc_class_rotation_expschedule_mastertemp
											WHERE fld_class_id='".$classid."' AND fld_delstatus='0' AND fld_flag='1'
												UNION ALL		
											SELECT fld_id AS schid, fld_schedule_name AS schname, 20 AS schtype 
											FROM itc_class_rotation_modexpschedule_mastertemp
											WHERE fld_class_id='".$classid."' AND fld_delstatus='0' AND fld_flag='1'	
											
										) AS w 
										ORDER BY w.schtype, w.schname");
                if($qry->num_rows>0){
					while($row = $qry->fetch_assoc())
					{
						extract($row);
						?>
						<li><a tabindex="-1" href="#" data-option="<?php echo $schid.','.$schtype;?>" onclick="fn_showstate()"><?php echo $schname; ?></a></li>
						<?php
					}
                }?>      
            </ul>
        </div>
	</div>
	<?php 
} 

if($oper=="showstates" and $oper != " " )
{
    
?>
    State
	<div class="selectbox">
        <input type="hidden" name="hidstateid" id="hidstateid" value=""/>
        <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
            <span class="selectbox-option input-medium" data-option="" style="width:97%">Select State</span>
            <b class="caret1"></b>
        </a>
        <div class="selectbox-options">
            <input type="text" class="selectbox-filter" placeholder="Search State">
            <ul role="options" style="width:100%">
                <?php
             $qry = $ObjDB->QueryObject("SELECT fld_id AS stdbid, fld_name AS stdbname from itc_standards_bodies ORDER BY stdbname");
             while($res=$qry->fetch_assoc())
                {
                    extract($res);
                    ?>
                <li><a tabindex="-1" href="#" data-option="<?php echo $stdbid;?>" onclick="fn_showdocuments(<?php echo $stdbid;?>)"><?php echo $stdbname; ?></a></li>
                    <?php  }   ?>   
            </ul>
        </div>
	</div>
<?php    
}
/*--- Load document dropdown ---*/
if($oper=="showdocuments" and $oper != " " )
{
 $stid = isset($method['stid']) ? $method['stid'] : '';
 
 $docqry = $ObjDB->QueryObject("SELECT a.fld_doc_title AS documenttitle, a.fld_doc_guid AS docguid, b.fld_sub_title AS subjectname, b.fld_sub_year AS year, b.fld_sub_guid AS guid
										FROM itc_correlation_documents a
										LEFT JOIN itc_correlation_doc_subject b ON a.fld_id=b.fld_doc_id
										WHERE  a.fld_authority_id='".$stid."'");
		
		$stddocs = array();
		if($docqry->num_rows > 0){ 
			while($docrow = $docqry->fetch_assoc()){
				extract($docrow);
				$stddocs[$guid] = $documenttitle." | ". $subjectname." (".$year.")";	
			}
		}
                
                ?>
     Document
        <div class="selectbox">
            <input type="hidden" name="seldocument" id="seldocument" value=""/>
            <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                <span id="standards" class="selectbox-option input-medium" data-option="" style="width:97%">Select Document</span>
                <b class="caret1"></b>
            </a>
            <div class="selectbox-options">
                <input type="text" class="selectbox-filter" placeholder="Search document">
                <ul role="options" style="width:100%">
                    <?php 
                    foreach ($stddocs as $key => $val) {
					?>
                    	<li><a tabindex="-1" href="#" class="tooltip" title="<?php echo $val;?>" data-option="<?php echo $key;?>" onclick="fn_showgrades('<?php echo $key;?>')"><?php echo $val; ?></a></li> 	
                    <?php
					}
					?>    
                </ul>
            </div>
        </div>
		<?php
}

 if($oper=="showgrades" and $oper != " " )
{
    $stdid = isset($method['stdid']) ? $method['stdid'] : '';

    $sub_id = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_correlation_doc_subject WHERE fld_sub_guid='".$stdid."'");
    ?>
     Grade
        <div class="selectbox">
        <input type="hidden" name="selgrade" id="selgrade" value="" >
        <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
            <span id="standards" class="selectbox-option input-medium" data-option="" style="width:97%">Select Grade</span>
            <b class="caret1"></b>
        </a>
        <div class="selectbox-options">
            <input type="text" class="selectbox-filter" placeholder="Search document">
            <ul role="options" style="width:100%">
                <?php
                $grdqry = $ObjDB->QueryObject("SELECT fld_grade_guid AS gguid, fld_grade_name AS gradename
                FROM itc_correlation_grades
                WHERE fld_sub_id='".$sub_id."'");
                
                if($grdqry->num_rows>0){
                    while($row = $grdqry->fetch_assoc())
                    {
                        extract($row);
                        ?>
                            <li><a tabindex="-1" href="#" data-option="<?php echo $gguid;?>" onclick="$('#viewreportdiv').show();"><?php echo $gradename; ?></a></li>
                        <?php
                    }
                }?>
            </ul>
        </div>
        </div>
    <?php
}



	@include("footer.php");