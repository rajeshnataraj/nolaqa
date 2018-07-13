<?php 
@include("sessioncheck.php");

/*
	Created By - Muthukumar. D
	Page - reports-classroom-classroomajax.php
		
	History:
	

*/

$date = date("Y-m-d H:i:s");
$oper = isset($method['oper']) ? $method['oper'] : '';

/*--- Load Student Dropdown ---*/
if($oper=="showstudent" and $oper != " " )
{
	$classid = isset($method['classid']) ? $method['classid'] : '';
	?>
	Student
	<div class="selectbox">
		<input type="hidden" name="studentid" id="studentid" value="">
		<a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
			<span class="selectbox-option input-medium" data-option="" style="width:97%">Select Student</span>
			<b class="caret1"></b>
		</a>
		<div class="selectbox-options">
			<input type="text" class="selectbox-filter" placeholder="Search Student">
			<ul role="options" style="width:100%">
				<?php 
				$qry = $ObjDB->QueryObject("SELECT CONCAT(a.fld_lname,' ',a.fld_fname) AS studentname, a.fld_id AS studentid 
											FROM itc_user_master AS a 
											LEFT JOIN itc_class_student_mapping AS b ON a.fld_id=b.fld_student_id 
											WHERE a.fld_activestatus='1' AND a.fld_delstatus='0' 
											AND b.fld_class_id='".$classid."' AND b.fld_flag='1' 
											ORDER BY studentname");
				if($qry->num_rows>0){
				?>
				<li><a tabindex="-1" href="#" data-option="0" onclick="fn_getallstudent();">All Students</a></li>
				<?php
					while($row = $qry->fetch_assoc())
					{
						extract($row);
						?>
						<li><a tabindex="-1" href="#" data-option="<?php echo $studentid;?>" onclick="fn_getselectedstudent();"><?php echo $studentname; ?></a></li>
						<?php
					}
				}?>      
			</ul>
		</div>
	</div>
	<?php
}


/*--- District/Pitsco ---*/
if($oper=="showteachers" and $oper != " " )
{
	$schoolid = isset($method['schoolid']) ? $method['schoolid'] : '';
	$individualid = isset($method['individualid']) ? $method['individualid'] : '';
	$val = isset($method['val']) ? $method['val'] : '';
	?>
	Teachers
	<div class="selectbox">
		<input type="hidden" name="teacherid" id="teacherid" value="">
		<a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
			<span class="selectbox-option input-medium" data-option="" style="width:97%">Select Teacher</span>
			<b class="caret1"></b>
		</a>
		<div class="selectbox-options">
			<input type="text" class="selectbox-filter" placeholder="Search Teacher">
			<ul role="options" style="width:100%">
				<?php 
				$qry = $ObjDB->QueryObject("SELECT CONCAT(fld_lname,' ',fld_fname) AS teachername, fld_id AS teacherid 
											FROM itc_user_master 
											WHERE fld_activestatus='1' AND fld_delstatus='0' AND fld_profile_id IN (7,8,9) 
											AND fld_school_id='".$schoolid."' AND fld_user_id='".$individualid."' 
											ORDER BY fld_lname");
				if($qry->num_rows>0){
					while($row = $qry->fetch_assoc())
					{
						extract($row);
						?>
						<li><a tabindex="-1" href="#" data-option="<?php echo $teacherid;?>" onclick="<?php if($val==1) { ?>$('#typesdiv').show(); <?php } else { ?>fn_showclass(<?php echo $teacherid;?>,<?php echo $val;?>);<?php }?>"><?php echo $teachername; ?></a></li>
						<?php
					}
				}?>      
			</ul>
		</div>
	</div>
	<?php
}

if($oper=="showclass" and $oper != " " )
{
	$teacherid = isset($method['teacherid']) ? $method['teacherid'] : '';
	$schoolid = isset($method['schoolid']) ? $method['schoolid'] : '0';
	$indid = isset($method['indid']) ? $method['indid'] : '0';
	$val = isset($method['val']) ? $method['val'] : '0';
	
	?>
	Class 
	<dl class='field row'>
		<div class="selectbox">
			<input type="hidden" name="classid" id="classid" value="">
			<a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
				<span class="selectbox-option input-medium" data-option="" style="width:97%">Select Class</span>
				<b class="caret1"></b>
			</a>
			<div class="selectbox-options">
				<input type="text" class="selectbox-filter" placeholder="Search Class">
				<ul role="options" style="width:100%">
					<?php 
					$qry = $ObjDB->QueryObject("SELECT fld_id AS classid, fld_class_name AS classname 
												FROM itc_class_master 
												WHERE fld_delstatus='0' AND (fld_created_by='".$teacherid."' 
												OR fld_id IN (SELECT fld_class_id 
																FROM itc_class_teacher_mapping 
																WHERE fld_teacher_id='".$teacherid."' AND fld_flag='1')) 
												ORDER BY fld_class_name");
					if($qry->num_rows>0){
						while($row = $qry->fetch_assoc())
						{
							extract($row);
							?>
							<li><a tabindex="-1" href="#" data-option="<?php echo $classid;?>" onclick="<?php if($val==2) {?>$('#studentdiv').show(); fn_showstudent(<?php echo $classid; ?>);<?php } else {?>$('#sctypediv').show();<?php }?>"><?php echo $classname;?></a></li>
							<?php
						}
					}?>      
				</ul>
			</div>
		</div> 
	</dl>
	<?php
}

if($oper=="showclassstu" and $oper != " " )
{
	$teacherid = isset($method['teacherid']) ? $method['teacherid'] : '';
	$type = isset($method['type']) ? $method['type'] : '';
	$schoolid = isset($method['schoolid']) ? $method['schoolid'] : '0';
	$indid = isset($method['indid']) ? $method['indid'] : '0';
	
	if($type==1)
	{
		?>
		Class 
		<dl class='field row'>
			<div class="selectbox">
				<input type="hidden" name="classid" id="classid" value="">
				<a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
					<span class="selectbox-option input-medium" data-option="" style="width:97%">Select Class</span>
					<b class="caret1"></b>
				</a>
				<div class="selectbox-options">
					<input type="text" class="selectbox-filter" placeholder="Search Class">
					<ul role="options" style="width:100%">
						<?php 
						$qry = $ObjDB->QueryObject("SELECT fld_id AS classid, fld_class_name AS classname 
													FROM itc_class_master 
													WHERE fld_delstatus='0' AND fld_archive_class='0' AND (fld_created_by='".$teacherid."' 
													OR fld_id IN (SELECT fld_class_id 
																	FROM itc_class_teacher_mapping 
																	WHERE fld_teacher_id='".$teacherid."' AND fld_flag='1')) 
													ORDER BY fld_class_name");
						if($qry->num_rows>0){
							while($row = $qry->fetch_assoc())
							{
								extract($row);
								?>
								<li><a tabindex="-1" href="#" data-option="<?php echo $classid;?>" onclick="$('#reports-pdfviewer').hide('fade').remove(); $('#viewreportdiv').show();"><?php echo $classname;?></a></li>
								<?php
							}
						}?>      
					</ul>
				</div>
			</div> 
		</dl>
		<?php
	}
	if($type==2)
	{
		?>
        Student
        <dl class='field row'>
            <div class="selectbox">
                <input type="hidden" name="studentid" id="studentid" value="">
                <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                    <span class="selectbox-option input-medium" data-option="" style="width:97%">Select Student</span>
                    <b class="caret1"></b>
                </a>
                <div class="selectbox-options">
                    <input type="text" class="selectbox-filter" placeholder="Search Student">
                    <ul role="options" style="width:100%">
                        <?php 
                        $qry = $ObjDB->QueryObject("SELECT fld_id AS studentid, CONCAT(fld_lname,' ',fld_fname) AS studentname 
													FROM itc_user_master 
													WHERE fld_delstatus='0' AND fld_activestatus='1' AND fld_profile_id='10' 
													AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' 
													ORDER BY studentname");
                        if($qry->num_rows>0){
							?>
                            <li><a tabindex="-1" href="#" data-option="0" onclick="$('#reports-pdfviewer').hide('fade').remove(); $('#viewreportdiv').show();">All Students</a></li>
                            <?php
                            while($row = $qry->fetch_assoc())
                            {
                                extract($row);
                                ?>
                                <li><a tabindex="-1" href="#" data-option="<?php echo $studentid;?>" onclick="$('#reports-pdfviewer').hide('fade').remove(); $('#viewreportdiv').show();"><?php echo $studentname; ?></a></li>
                                <?php
                            }
                        }?>      
                    </ul>
                </div>
            </div> 
        </dl>
        <?php
	}
}

@include("footer.php");