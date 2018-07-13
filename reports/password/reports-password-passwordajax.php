<?php 
/*
	Created By - Muthukumar. D
	Page - reports-classroom-classroomajax.php
	History:
*/
	@include("sessioncheck.php");
	$date = date("Y-m-d H:i:s");
	$oper = isset($method['oper']) ? $method['oper'] : '';
	
	/*--- Load Student Dropdown ---*/
	if($oper=="showschool" and $oper != " " )
	{
		$districtid = isset($method['districtid']) ? $method['districtid'] : '';
		?>
        School
        <div class="selectbox">
            <input type="hidden" name="schoolid" id="schoolid" value="">
            <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                <span class="selectbox-option input-medium" data-option="" style="width:97%">Select School</span>
                <b class="caret1"></b>
            </a>
            <div class="selectbox-options">
                <input type="text" class="selectbox-filter" placeholder="Search School">
                <ul role="options" style="width:100%">
                    <?php 
                    $qry = $ObjDB->QueryObject("SELECT fld_id AS schoolid, fld_school_name AS schoolname 
												FROM itc_school_master 
												WHERE fld_delstatus='0' AND fld_district_id='".$districtid."'");
                    if($qry->num_rows>0){
                        while($row = $qry->fetch_assoc())
                        {
                            extract($row);
                            ?>
                            <li><a tabindex="-1" href="#" data-option="<?php echo $schoolid;?>"><?php echo $schoolname; ?></a></li>
                            <?php
                        }
                    }?>      
                </ul>
            </div>
        </div>
		<?php
	}
	
		/*--- Load Student Dropdown ---*/
	if($oper=="showschoolpurchase" and $oper != " " )
	{		
		?>
        School
        <div class="selectbox">
            <input type="hidden" name="schoolid" id="schoolid" value="">
            <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                <span class="selectbox-option input-medium" data-option="" style="width:97%">Select School</span>
                <b class="caret1"></b>
            </a>
            <div class="selectbox-options">
                <input type="text" class="selectbox-filter" placeholder="Search School">
                <ul role="options" style="width:100%">
                    <?php 
                    $qry = $ObjDB->QueryObject("SELECT fld_id AS schoolid, fld_school_name AS schoolname 
												FROM itc_school_master 
												WHERE fld_delstatus='0' AND fld_district_id='0'");
                    if($qry->num_rows>0){
                        while($row = $qry->fetch_assoc())
                        {
                            extract($row);
                            ?>
                            <li><a tabindex="-1" onClick="$('#btnstep').removeClass('dim');" href="#" data-option="<?php echo $schoolid;?>"><?php echo $schoolname; ?></a></li>
                            <?php
                        }
                    }?>      
                </ul>
            </div>
        </div>
		<?php
	}
		/*--- Load Student Dropdown ---*/
	if($oper=="showhomepurchase" and $oper != " " )
	{		
		?>
        Individuals
        <div class="selectbox">
            <input type="hidden" name="schoolid" id="schoolid" value="">
            <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                <span class="selectbox-option input-medium" data-option="" style="width:97%">Select Individuals</span>
                <b class="caret1"></b>
            </a>
            <div class="selectbox-options">
                <input type="text" class="selectbox-filter" placeholder="Search individuals">
                <ul role="options" style="width:100%">
                    <?php 
                    $qry = $ObjDB->QueryObject("SELECT  CONCAT(`fld_fname`,' ',`fld_lname` ) AS fullname ,fld_id 
											FROM `itc_user_master`  
											WHERE fld_district_id='0' AND fld_school_id='0' AND fld_profile_id='5' AND fld_user_id<>''");
                    if($qry->num_rows>0){
                        while($row = $qry->fetch_assoc())
                        {
                            extract($row);
                            ?>
                            <li><a tabindex="-1" onClick="$('#btnstep').removeClass('dim');" href="#" data-option="<?php echo $fld_id;?>"><?php echo $fullname; ?></a></li>
                            <?php
                        }
                    }?>      
                </ul>
            </div>
        </div>
		<?php
	}
	
	@include("footer.php");