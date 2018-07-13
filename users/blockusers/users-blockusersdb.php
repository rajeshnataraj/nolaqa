<?php 
	@include("sessioncheck.php");
	
	$oper = isset($method['oper']) ? $method['oper'] : '';
        
        
        if($oper == "changeschool" and $oper != ""){
            $usertype = isset($method['usertype']) ? $method['usertype'] : '0';
            ?>
               <div class="row">
                <div class='six columns'>
                    <?php if($usertype == 6){ echo "Districts";} else {echo "Schools";} ?>
                    <dl class='field row'>
                        <div class="selectbox">
                            <input type="hidden" name="ddlshl" id="ddlshl" value="" onchange="fn_selectusers1(this.value,<?php echo $usertype;?>);" >
                            <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                                <span class="selectbox-option input-medium" data-option="" style="width:97%"><?php if($usertype == 6){ echo "All Districts";} else {echo "All Schools";} ?></span>
                                <b class="caret1"></b>
                            </a>
                            <div class="selectbox-options">	
                                <input type="text" class="selectbox-filter" placeholder="Search School">		    
                                <ul role="options">
                                    <li><a tabindex="-1" href="#" data-option="0"><?php if($usertype == 6){ echo "All Districts";} else {echo "All Schools";} ?></a></li>
                                    <?php
                                        if($usertype == 6){
                                            $temp = 0;
                                            $shlqry = $ObjDB->QueryObject("SELECT fld_id, fld_district_name as schoolname
                                                                            FROM itc_district_master 
                                                                            WHERE fld_delstatus='0'
                                                                            ORDER BY fld_district_name ASC");
                                        }
                                        else if($usertype == 9 or $usertype == 10){
                                            $shlqry = $ObjDB->QueryObject("SELECT 
                                                                            fld_id, fld_school_name AS schoolname, '1' as temp
                                                                        FROM
                                                                            itc_school_master
                                                                        WHERE
                                                                            fld_delstatus = '0' 
                                                                        union all SELECT 
                                                                            fld_id AS id,
                                                                            CONCAT(fld_fname, ' ', fld_lname) AS schoolname,
                                                                            '2' as temp
                                                                        FROM
                                                                            itc_user_master
                                                                        WHERE
                                                                            fld_delstatus = '0'
                                                                                AND fld_profile_id = '5'
                                                                        ORDER BY temp , schoolname ASC");
                                        }
                                        else{
                                            $temp = "0";
                                            $shlqry = $ObjDB->QueryObject("SELECT fld_id, fld_school_name AS schoolname 
                                                                                                    FROM itc_school_master 
                                                                                                    WHERE fld_delstatus='0' 
                                                                                                    ORDER BY fld_school_name ASC");
                                        }
                                        while($rowshl = $shlqry->fetch_assoc()){ 
                                        extract($rowshl);
                                        ?>
                                                <li><a href="#" data-option="<?php echo $fld_id."~".$temp;?>"><?php echo $schoolname;?></a></li>
                                        <?php 
                                        }?>       
                                </ul>
                            </div>
                        </div> 
                    </dl>
                </div>
            </div>
        <?php
        }
        
        if($oper=="selectusers" and $oper != " " )
	{
		$usertype = isset($method['usertype']) ? $method['usertype'] : '0';
                $shlid1 = isset($method['shlid']) ? $method['shlid'] : '0';
                $shlid = explode("~",$shlid1);
                ?>
                <script type="text/javascript" charset="utf-8">	
                    $('#tablecontents').slimscroll({
                            height:'auto',
                            railVisible: false,
                            size:'3',
                            allowPageScroll: true,
                            railColor: '#F4F4F4',
                            opacity: 9,
                            color: '#88ABC2',
                            wheelStep: 1
                    });		
            </script>
                <div class='row rowspacer'>
                    <div class='span10 offset1' id="userslist"> 
                        <table id="test" class='table table-hover table-striped table-bordered setbordertopradius'>
                                <thead class='tableHeadText'>
                                <tr>

                                    <th width="60%">Users List</th>
                                    <th width="20%">User Type</th>
                                    <th width="20%">Type of Mode</th>

                                </tr>
                            </thead> 
                         </table>
                        <div style="max-height:400px;width:100%" id="tablecontents"  >
                            <table style="margin-bottom:0px;" class='table table-hover table-striped table-bordered bordertopradiusremove'>                  
                                <tbody>
                                    <?php 
                                    //All users
                                    if($usertype == -1){
                                        $qryuser = $ObjDB->QueryObject("SELECT 
                                                                            fld_id AS id,
                                                                            CONCAT(fld_fname, ' ', fld_lname) AS fullname,
                                                                            fn_shortname(CONCAT(fld_fname, ' ', fld_lname), 1) AS shortname,
                                                                            fld_block_status,fld_profile_id as pid
                                                                        FROM
                                                                            itc_user_master
                                                                        WHERE
                                                                            fld_delstatus = '0' AND fld_profile_id NOT IN(1,2,3,4) 
                                                                        ORDER BY fld_fname ASC");
                                    }
                                    //District
                                     else if($usertype == -2){
                                        $qryuser = $ObjDB->QueryObject("SELECT fld_id as id, fld_district_name as fullname,fn_shortname(fld_district_name, 1) AS shortname,fld_block_status
                                                                        FROM itc_district_master 
                                                                        WHERE fld_delstatus='0'
                                                                        ORDER BY fld_district_name ASC");
                                    }
                                    //School
                                    else if($usertype == -3){
                                        $qryuser = $ObjDB->QueryObject("SELECT fld_id as id, fld_school_name as fullname,fn_shortname(fld_school_name, 1) AS shortname,fld_block_status
                                                                        FROM itc_school_master 
                                                                        WHERE fld_delstatus='0' AND fld_district_id NOT IN(0)
                                                                        ORDER BY fld_school_name ASC");
                                    }
                                    //School Purchase
                                    else if($usertype == -4){
                                        $qryuser = $ObjDB->QueryObject("SELECT fld_id as id, fld_school_name as fullname,fn_shortname(fld_school_name, 1) AS shortname
                                                                        FROM itc_school_master 
                                                                        WHERE fld_delstatus='0' AND fld_district_id ='0'
                                                                        ORDER BY fld_school_name ASC");
                                    }
                                    
                                    else{
                                        //Home purchase
                                        if($usertype == 5){
                                            $sqry = "AND fld_profile_id ='5'";
                                        }
                                        // District Admin
                                        else if($usertype == 6 and $shlid[0] == 0){
                                            $sqry = "AND fld_profile_id ='6'";
                                        }
                                        else if($usertype == 6 and $shlid[0] != 0){
                                            $sqry = "AND fld_profile_id ='6' AND fld_district_id='".$shlid[0]."'";
                                        }
                                        // School Admin
                                        else if($usertype == 7 and $shlid[0] == 0){
                                            $sqry = "AND fld_profile_id ='7'";
                                        }
                                        else if($usertype == 7 and $shlid[0] != 0){
                                            $sqry = "AND fld_profile_id ='7' AND fld_school_id='".$shlid[0]."'";
                                        }
                                        //Teacher Admin
                                        else if($usertype == 8 and $shlid[0] == 0){
                                            $sqry = "AND fld_profile_id ='8'";
                                        }
                                        else if($usertype == 8 and $shlid[0] != 0){
                                            $sqry = "AND fld_profile_id ='8' AND fld_school_id='".$shlid[0]."'";
                                        }
                                        //Teacher
                                        else if($usertype == 9 and $shlid[0] == 0){
                                            $sqry = "AND fld_profile_id ='9'";
                                        }
                                         else if($usertype == 9 and $shlid[1] == 2){
                                            $sqry = "AND fld_profile_id ='9' AND fld_school_id='0' AND fld_district_id='0' AND fld_user_id='".$shlid[0]."'";
                                        }
                                        else if($usertype == 9 and $shlid[1] == 1){
                                            $sqry = "AND fld_profile_id ='9' AND fld_school_id='".$shlid[0]."'";
                                        }
                                        //student
                                        else if($usertype == 10 and $shlid[0] == 0){
                                            $sqry = "AND fld_profile_id ='10'";
                                        }
                                         else if($usertype == 10 and $shlid[1] == 2){
                                            $sqry = "AND fld_profile_id ='10' AND fld_school_id='0' AND fld_district_id='0' AND fld_user_id='".$shlid[0]."'";
                                        }
                                        else if($usertype == 10 and $shlid[1] == 1){
                                            $sqry = "AND fld_profile_id ='10' AND fld_school_id='".$shlid[0]."'";
                                        }
                                       
                                        
                                        
                                        $qryuser = $ObjDB->QueryObject("SELECT 
                                                                            fld_id AS id,
                                                                            CONCAT(fld_fname, ' ', fld_lname) AS fullname,
                                                                            fn_shortname(CONCAT(fld_fname, ' ', fld_lname), 1) AS shortname,
                                                                            fld_block_status,fld_profile_id as pid
                                                                        FROM
                                                                            itc_user_master
                                                                        WHERE
                                                                            fld_delstatus = '0' ".$sqry."  
                                                                        ORDER BY fld_fname ASC");
                                        
                                    }
                                    
                                        
                                        if($qryuser->num_rows>0){
                                            while($resuser=$qryuser->fetch_assoc()){
                                                extract($resuser);
                                                if($pid == 5){
                                                    $pname="Teacher Individual";
                                                }
                                                else if($pid == 6){
                                                    $pname="School District Admin";
                                                }
                                                else if($pid == 7){
                                                    $pname="School Admin";
                                                }
                                                else if($pid == 8){
                                                    $pname="Teacher Admin";
                                                }
                                                else if($pid == 9){
                                                    $pname="Teacher";
                                                }
                                                else if($pid == 10){
                                                    $pname="Student";
                                                }
                                                
                                                
                                        ?>
                                                <tr>


                                                    <td width="60%"><?php echo $fullname; ?></td>
                                                    <td width="20%"><?php echo $pname; ?></td>
                                                    <td width="20%">  
                                                        <input name="radio<?php echo $id; ?>" id="radio1_<?php echo $id; ?>" value="0" type="radio" <?php if($fld_block_status==0) echo 'checked="checked"'; ?> onclick="fn_changestatus(<?php echo $id; ?>,0,<?php echo $usertype;?>)" >                                                
                                                            <label class="radio <?php if($fld_block_status==0) echo "checked"; ?>" for="radio1_<?php echo $id; ?>" >
                                                                <span></span> Active
                                                            </label>
                                                        <input name="radio<?php echo $id; ?>" id="radio2_<?php echo $id; ?>" value="1" type="radio" <?php if($fld_block_status==1) echo 'checked="checked"'; ?>  onclick="fn_changestatus(<?php echo $id; ?>,1,<?php echo $usertype;?>)">
                                                            <label class="radio <?php if($fld_block_status==1) echo "checked"; ?>" for="radio2_<?php echo $id; ?>">
                                                                <span></span> Block
                                                            </label>                                                
                                                    </td>



                                                </tr>
                                    <?php }
                                        }
                                    else{?>
                                    <tr>
                                        <td colspan="3">No Records Found</td>
                                    </tr>
                                    <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> 
                <?php
                
	}
        
        if($oper=="changestatus" and $oper != " " )
	{
		$id = isset($method['id']) ? $method['id'] : '0';
		$type = isset($method['type']) ? $method['type'] : '0';
                $usertype = isset($method['usertype']) ? $method['usertype'] : '0';
                
                //All Users and Teacher Admins and teacher and student and School Admin and District admin
                if($usertype == -1 or $usertype == 8 or $usertype == 9 or $usertype == 10 or $usertype == 7 or $usertype == 6){
                    if($type == 1){
                        $ObjDB->NonQuery("UPDATE itc_user_master SET fld_block_status='".$type."',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' WHERE fld_id='".$id."' AND fld_delstatus='0'"); 
                    }
                    else{
                        $ObjDB->NonQuery("UPDATE itc_user_master SET fld_block_status='".$type."',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' WHERE fld_id='".$id."' AND fld_delstatus='0'"); 
                    }
                }
                
                //District
                if($usertype == -2){
                    if($type == 1){
                        $ObjDB->NonQuery("UPDATE itc_district_master SET fld_block_status='".$type."',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' WHERE fld_id='".$id."' AND fld_delstatus='0'"); 
                        $ObjDB->NonQuery("UPDATE itc_school_master SET fld_block_status='".$type."',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' WHERE fld_district_id='".$id."' AND fld_delstatus='0'"); 
                        $ObjDB->NonQuery("UPDATE itc_user_master SET fld_block_status='".$type."', fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' WHERE fld_district_id='".$id."' AND fld_delstatus='0'"); 
                    }
                    else{
                        $ObjDB->NonQuery("UPDATE itc_district_master SET fld_block_status='".$type."',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' WHERE fld_id='".$id."' AND fld_delstatus='0'");
                        $ObjDB->NonQuery("UPDATE itc_school_master SET fld_block_status='".$type."',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' WHERE fld_district_id='".$id."' AND fld_delstatus='0'"); 
                        $ObjDB->NonQuery("UPDATE itc_user_master SET fld_block_status='".$type."',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' WHERE fld_district_id='".$id."' AND fld_delstatus='0'");
                    }
                }
                //School and school purchase
                if($usertype == -3 or $usertype == -4){
                    if($type == 1){
                        $ObjDB->NonQuery("UPDATE itc_school_master SET fld_block_status='".$type."',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' WHERE fld_id='".$id."' AND fld_delstatus='0'"); 
                        $ObjDB->NonQuery("UPDATE itc_user_master SET fld_block_status='".$type."', fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' WHERE fld_school_id='".$id."' AND fld_delstatus='0'"); 
                    }
                    else{
                        $ObjDB->NonQuery("UPDATE itc_school_master SET fld_block_status='".$type."',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' WHERE fld_id='".$id."' AND fld_delstatus='0'"); 
                        $ObjDB->NonQuery("UPDATE itc_user_master SET fld_block_status='".$type."',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' WHERE fld_school_id='".$id."' AND fld_delstatus='0'");
                    }
                }
                //Home Purchase
                if($usertype == 5){
                    if($type == 1){
                        $ObjDB->NonQuery("UPDATE itc_user_master SET fld_block_status='".$type."',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' WHERE fld_user_id='".$id."' AND fld_delstatus='0'"); 
                    }
                    else{
                        $ObjDB->NonQuery("UPDATE itc_user_master SET fld_block_status='".$type."',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' WHERE fld_user_id='".$id."' AND fld_delstatus='0'"); 
                    }
                }
        }
        
   	@include("footer.php");