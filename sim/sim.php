<?php

@include("sessioncheck.php");
$sid= isset($method['sid']) ? $method['sid'] : '';

$date=date("Y-m-d H:i:s");

$servername = "myrdsdbinstance.cj5abbh2tkav.us-west-2.rds.amazonaws.com";
$username = "itcrdsuser";
$password = "wrebeqEq5w";
$dbname = "synergyitc";

// Create connection
$dbcon = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($dbcon->connect_error) {
    die("Connection failed: " . $dbcon->connect_error);
}

$sql2 = "SELECT lt.fld_license_id as l_id 
					FROM itc_license_track as lt
					LEFT JOIN itc_license_master AS lm ON lt.fld_license_id = lm.fld_id 
					WHERE lt.fld_school_id='$schoolid' AND lt.fld_start_date<='".$date."' AND lt.fld_end_date>='".$date."'   
					GROUP BY lm.fld_id";
$result2 = $dbcon->query($sql2);

if ($result2->num_rows > 0) {
    // echo $result2->num_rows;
    while($row2 = $result2->fetch_assoc()) {
        // echo " ".$row2["l_id"].' ';
        $sqldelete = "DELETE FROM itc_license_simproduct_mapping WHERE fld_license_id='".$row2["l_id"]."';";
        $resultdelete = $dbcon->query($sqldelete);
    }
}

//Expeditions
$catid = 5;
$lictype = 4;

$sql = "SELECT p.fld_id as p_id, p.fld_asset_id as p_asset_id, p.fld_product_name as p_product_name, p.fld_version_number as p_version_number, p.fld_product_code as p_product_code, e.fld_id as e_id, em.fld_license_id as l_id 
				FROM itc_sim_product as p 
				LEFT JOIN itc_exp_master as e ON e.fld_asset_id=p.fld_product_code
				LEFT JOIN itc_license_exp_mapping as em ON em.fld_exp_id=e.fld_id 
				LEFT JOIN itc_license_master AS lm ON em.fld_license_id = lm.fld_id 
				LEFT JOIN itc_license_track AS lt ON em.fld_license_id = lt.fld_license_id
				WHERE em.fld_flag='1' AND p.fld_cat_id='$catid' AND lt.fld_school_id='$schoolid' AND lt.fld_start_date<='".$date."' AND lt.fld_end_date>='".$date."' AND lm.fld_delstatus='0'  AND p.fld_delstatus='0' 
				GROUP BY p.fld_product_code";
$result = $dbcon->query($sql);

if ($result->num_rows > 0) {
    //echo $result->num_rows;
    // output data of each row
    // while($row = $result->fetch_assoc()) {
    // $query3 = "INSERT INTO itc_license_simproduct_mapping (fld_license_id,fld_cat_id,fld_asset_id, fld_type, fld_product_id, fld_created_by, fld_created_date)
    // VALUES('".$row["l_id"]."', '".$catid."', '".$row["p_asset_id"]."', '$lictype', '".$row["p_id"]."', '2', '".$date."')";
    // $dbcon->query($query3);
    // }

    // output data of each row
    while($row = $result->fetch_assoc()) {
        $sql2 = "SELECT fld_id 
						FROM itc_license_simproduct_mapping as lsm WHERE fld_license_id = '". $row["l_id"] ."' AND fld_asset_id = '". $row["p_asset_id"] ."';";
        $result2 = $dbcon->query($sql2);
        // if ($result2->num_rows > 0) {
        // $sqldelete = "DELETE FROM itc_license_simproduct_mapping WHERE fld_license_id='".$row["l_id"]."' AND fld_cat_id = '$catid';";
        // $resultdelete = $dbcon->query($sqldelete);
        // }
        $query3 = "INSERT INTO itc_license_simproduct_mapping (fld_license_id,fld_cat_id,fld_asset_id, fld_type, fld_product_id, fld_created_by, fld_created_date)
								VALUES('".$row["l_id"]."', '".$catid."', '".$row["p_asset_id"]."', '$lictype', '".$row["p_id"]."', '2', '".$date."')";
        $dbcon->query($query3);
        // echo "a";
        //	echo "Missing ".$row["p_product_name"].' '.$row["l_id"].'<br>';
        //echo "INSERT INTO itc_license_simproduct_mapping(fld_license_id,fld_cat_id,fld_asset_id, fld_type, fld_product_id, fld_created_by, fld_created_date)
        //		VALUES(".$row["l_id"].",".$catid.",".$row["p_asset_id"].",$lictype,".$row["p_id"].", 2, '".$date."')";
    }

}

// Modules
$catid = 1;
$lictype = 1;
$sql = "SELECT p.fld_id as p_id, p.fld_asset_id as p_asset_id, p.fld_product_name as p_product_name, p.fld_version_number as p_version_number, p.fld_product_code as p_product_code, e.fld_id as e_id, em.fld_license_id as l_id 
				FROM itc_sim_product as p 
				LEFT JOIN itc_module_master as e ON e.fld_asset_id=p.fld_product_code
				LEFT JOIN itc_license_mod_mapping as em ON e.fld_id=em.fld_module_id
				LEFT JOIN itc_license_track AS lt ON em.fld_license_id = lt.fld_license_id
				LEFT JOIN itc_license_master AS lm ON em.fld_license_id = lm.fld_id 
				WHERE em.fld_type='1' AND p.fld_cat_id='1' AND lt.fld_school_id='$schoolid' AND lt.fld_start_date<='".$date."' AND lt.fld_end_date>='".$date."' AND lm.fld_delstatus='0' AND em.fld_active = '1'   AND p.fld_delstatus='0' 
GROUP BY p.fld_product_code 
ORDER BY p.fld_product_name ASC";
// echo "SELECT p.fld_id as p_id, p.fld_asset_id as p_asset_id, p.fld_product_name as p_product_name, p.fld_version_number as p_version_number, p.fld_product_code as p_product_code, e.fld_id as e_id, em.fld_license_id as l_id
// FROM itc_sim_product as p
// LEFT JOIN itc_license_mod_mapping as em ON p.fld_asset_id=em.fld_module_id
// LEFT JOIN itc_module_master as e ON e.fld_id=em.fld_module_id
// LEFT JOIN itc_license_track AS lt ON em.fld_license_id = lt.fld_license_id
// LEFT JOIN itc_license_master AS lm ON em.fld_license_id = lm.fld_id
// WHERE em.fld_type='1' AND p.fld_cat_id='1' AND lt.fld_school_id='$schoolid' AND lt.fld_start_date<='".$date."' AND lt.fld_end_date>='".$date."' AND lm.fld_delstatus='0'
// GROUP BY p.fld_product_code
// ORDER BY p.fld_product_name ASC";


$result = $dbcon->query($sql);
if ($result->num_rows > 0) {
    // echo $result->num_rows;
    // $row = $result->fetch_assoc();
    // $sql2 = "SELECT fld_id
    // FROM itc_license_simproduct_mapping as lsm WHERE fld_license_id = '". $row["l_id"] ."' AND fld_cat_id = '". $catid ."';";
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $query3 = "INSERT INTO itc_license_simproduct_mapping (fld_license_id,fld_cat_id,fld_asset_id, fld_type, fld_product_id, fld_created_by, fld_created_date)
								VALUES('".$row["l_id"]."', '".$catid."', '".$row["p_asset_id"]."', '$lictype', '".$row["p_id"]."', '2', '".$date."')";
        $dbcon->query($query3);
    }

}


// Missions
$catid = 8;
$lictype = 6;
$sql = "SELECT p.fld_id as p_id, p.fld_asset_id as p_asset_id, p.fld_product_name as p_product_name, p.fld_version_number as p_version_number, p.fld_product_code as p_product_code, e.fld_id as e_id, em.fld_license_id as l_id 
				FROM itc_sim_product as p 
				LEFT JOIN itc_mission_master as e ON e.fld_asset_id=p.fld_product_code
				LEFT JOIN itc_license_mission_mapping as em ON em.fld_mis_id=e.fld_id 
				LEFT JOIN itc_license_track AS lt ON em.fld_license_id = lt.fld_license_id
				LEFT JOIN itc_license_master AS lm ON em.fld_license_id = lm.fld_id 
				WHERE em.fld_flag='1' AND p.fld_cat_id='8' AND lt.fld_school_id='$schoolid' AND lt.fld_start_date<='".$date."' AND lt.fld_end_date>='".$date."' AND lm.fld_delstatus='0'   AND p.fld_delstatus='0' 
GROUP BY p.fld_product_code 
ORDER BY p.fld_product_name ASC";
// echo "SELECT p.fld_id as p_id, p.fld_asset_id as p_asset_id, p.fld_product_name as p_product_name, p.fld_version_number as p_version_number, p.fld_product_code as p_product_code, e.fld_id as e_id, em.fld_license_id as l_id
// FROM itc_sim_product as p
// LEFT JOIN itc_license_mod_mapping as em ON p.fld_asset_id=em.fld_module_id
// LEFT JOIN itc_module_master as e ON e.fld_id=em.fld_module_id
// LEFT JOIN itc_license_track AS lt ON em.fld_license_id = lt.fld_license_id
// LEFT JOIN itc_license_master AS lm ON em.fld_license_id = lm.fld_id
// WHERE em.fld_type='1' AND p.fld_cat_id='1' AND lt.fld_school_id='$schoolid' AND lt.fld_start_date<='".$date."' AND lt.fld_end_date>='".$date."' AND lm.fld_delstatus='0'
// GROUP BY p.fld_product_code
// ORDER BY p.fld_product_name ASC";


$result = $dbcon->query($sql);
if ($result->num_rows > 0) {
    // echo $result->num_rows;
    // $row = $result->fetch_assoc();
    // $sql2 = "SELECT fld_id
    // FROM itc_license_simproduct_mapping as lsm WHERE fld_license_id = '". $row["l_id"] ."' AND fld_cat_id = '". $catid ."';";
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $query3 = "INSERT INTO itc_license_simproduct_mapping (fld_license_id,fld_cat_id,fld_asset_id, fld_type, fld_product_id, fld_created_by, fld_created_date)
								VALUES('".$row["l_id"]."', '".$catid."', '".$row["p_asset_id"]."', '$lictype', '".$row["p_id"]."', '6', '".$date."')";
        $dbcon->query($query3);
    }

}


// Math Modules
$catid = 1;
$lictype = 1;
$sql = "SELECT p.fld_id as p_id, p.fld_asset_id as p_asset_id, p.fld_product_name as p_product_name, p.fld_version_number as p_version_number, p.fld_product_code as p_product_code, e.fld_id as e_id, em.fld_license_id as l_id 
				FROM itc_sim_product as p 
				LEFT JOIN itc_mathmodule_master as e ON p.fld_product_code=e.fld_asset_id
				LEFT JOIN itc_license_mod_mapping as em ON e.fld_id=em.fld_module_id
				LEFT JOIN itc_license_track AS lt ON em.fld_license_id = lt.fld_license_id
				LEFT JOIN itc_license_master AS lm ON em.fld_license_id = lm.fld_id 
				WHERE em.fld_type='2' AND p.fld_cat_id='1' AND lt.fld_school_id='$schoolid' AND lt.fld_start_date<='".$date."' AND lt.fld_end_date>='".$date."' AND lm.fld_delstatus='0' AND em.fld_active='1'   AND p.fld_delstatus='0' 
GROUP BY p.fld_product_code 
ORDER BY p.fld_product_name ASC";
// echo "SELECT p.fld_id as p_id, p.fld_asset_id as p_asset_id, p.fld_product_name as p_product_name, p.fld_version_number as p_version_number, p.fld_product_code as p_product_code, e.fld_id as e_id, em.fld_license_id as l_id
// FROM itc_sim_product as p
// LEFT JOIN itc_license_mod_mapping as em ON p.fld_asset_id=em.fld_module_id
// LEFT JOIN itc_module_master as e ON e.fld_id=em.fld_module_id
// LEFT JOIN itc_license_track AS lt ON em.fld_license_id = lt.fld_license_id
// LEFT JOIN itc_license_master AS lm ON em.fld_license_id = lm.fld_id
// WHERE em.fld_type='1' AND p.fld_cat_id='1' AND lt.fld_school_id='$schoolid' AND lt.fld_start_date<='".$date."' AND lt.fld_end_date>='".$date."' AND lm.fld_delstatus='0'
// GROUP BY p.fld_product_code
// ORDER BY p.fld_product_name ASC";


$result = $dbcon->query($sql);
if ($result->num_rows > 0) {
    // echo $result->num_rows;
    // $row = $result->fetch_assoc();
    // $sql2 = "SELECT fld_id
    // FROM itc_license_simproduct_mapping as lsm WHERE fld_license_id = '". $row["l_id"] ."' AND fld_cat_id = '". $catid ."';";
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $query3 = "INSERT INTO itc_license_simproduct_mapping (fld_license_id,fld_cat_id,fld_asset_id, fld_type, fld_product_id, fld_created_by, fld_created_date)
								VALUES('".$row["l_id"]."', '".$catid."', '".$row["p_asset_id"]."', '$lictype', '".$row["p_id"]."', '2', '".$date."')";
        $dbcon->query($query3);
    }

}


// Math Connections
$catid = 3;
$lictype = 3;
$sql = "SELECT p.fld_id as p_id, p.fld_asset_id as p_asset_id, p.fld_product_name as p_product_name, p.fld_version_number as p_version_number, p.fld_product_code as p_product_code, e.fld_id as e_id, em.fld_license_id as l_id 
				FROM itc_sim_product as p 
				LEFT JOIN itc_nondigicontent_product as e ON p.fld_product_code=e.fld_asset_id
				LEFT JOIN itc_license_nondigitalcontent_mapping as em ON e.fld_id=em.fld_product_id
				LEFT JOIN itc_license_track AS lt ON em.fld_license_id = lt.fld_license_id
				LEFT JOIN itc_license_master AS lm ON em.fld_license_id = lm.fld_id 
				WHERE e.fld_nondigicat_id='2' AND p.fld_cat_id='3' AND em.fld_access='1' AND lt.fld_school_id='$schoolid' AND lt.fld_start_date<='".$date."' AND lt.fld_end_date>='".$date."' AND lm.fld_delstatus='0' AND em.fld_deleted_by='0'  AND p.fld_delstatus='0' 
GROUP BY p.fld_product_code 
ORDER BY p.fld_product_name ASC";

$result = $dbcon->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $query3 = "INSERT INTO itc_license_simproduct_mapping (fld_license_id,fld_cat_id,fld_asset_id, fld_type, fld_product_id, fld_created_by, fld_created_date)
								VALUES('".$row["l_id"]."', '".$catid."', '".$row["p_asset_id"]."', '".$lictype."', '".$row["p_id"]."', '2', '".$date."')";
        $dbcon->query($query3);
    }
}

// PITSCO Science
$catid = 4;
$lictype = 4;
$sql = "SELECT p.fld_id as p_id, p.fld_asset_id as p_asset_id, p.fld_product_name as p_product_name, p.fld_version_number as p_version_number, p.fld_product_code as p_product_code, e.fld_id as e_id, em.fld_license_id as l_id 
				FROM itc_sim_product as p 
				LEFT JOIN itc_nondigicontent_product as e ON p.fld_product_code=e.fld_asset_id
				LEFT JOIN itc_license_nondigitalcontent_mapping as em ON e.fld_id=em.fld_product_id
				LEFT JOIN itc_license_track AS lt ON em.fld_license_id = lt.fld_license_id
				LEFT JOIN itc_license_master AS lm ON em.fld_license_id = lm.fld_id 
				WHERE e.fld_nondigicat_id='1' AND p.fld_cat_id='4' AND em.fld_access='1' AND lt.fld_school_id='$schoolid' AND lt.fld_start_date<='".$date."' AND lt.fld_end_date>='".$date."' AND lm.fld_delstatus='0' AND em.fld_deleted_by='0' AND p.fld_delstatus='0'
GROUP BY p.fld_product_code 
ORDER BY p.fld_product_name ASC";

$result = $dbcon->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $query3 = "INSERT INTO itc_license_simproduct_mapping (fld_license_id,fld_cat_id,fld_asset_id, fld_type, fld_product_id, fld_created_by, fld_created_date)
								VALUES('".$row["l_id"]."', '".$catid."', '".$row["p_asset_id"]."', '".$lictype."', '".$row["p_id"]."', '2', '".$date."')";
        $dbcon->query($query3);
    }
}

// STEM Challenges
$catid = 6;
$lictype = 6;
$sql = "SELECT p.fld_id as p_id, p.fld_asset_id as p_asset_id, p.fld_product_name as p_product_name, p.fld_version_number as p_version_number, p.fld_product_code as p_product_code, e.fld_id as e_id, em.fld_license_id as l_id 
				FROM itc_sim_product as p 
				LEFT JOIN itc_nondigicontent_product as e ON p.fld_product_code=e.fld_asset_id
				LEFT JOIN itc_license_nondigitalcontent_mapping as em ON e.fld_id=em.fld_product_id
				LEFT JOIN itc_license_track AS lt ON em.fld_license_id = lt.fld_license_id 
				LEFT JOIN itc_license_master AS lm ON em.fld_license_id = lm.fld_id 
				WHERE e.fld_nondigicat_id='3' AND p.fld_cat_id='6' AND em.fld_access='1' AND lt.fld_school_id='$schoolid' AND lt.fld_start_date<='".$date."' AND lt.fld_end_date>='".$date."' AND lm.fld_delstatus='0' AND em.fld_deleted_by='0' AND p.fld_delstatus='0'
GROUP BY p.fld_product_code 
ORDER BY p.fld_product_name ASC";

$result = $dbcon->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $query3 = "INSERT INTO itc_license_simproduct_mapping (fld_license_id,fld_cat_id,fld_asset_id, fld_type, fld_product_id, fld_created_by, fld_created_date)
								VALUES('".$row["l_id"]."', '".$catid."', '".$row["p_asset_id"]."', '".$lictype."', '".$row["p_id"]."', '2', '".$date."')";
        $dbcon->query($query3);
    }
}

// Quests
$catid = 2;
$lictype = 2;
$sql = "SELECT p.fld_id as p_id, p.fld_asset_id as p_asset_id, p.fld_product_name as p_product_name, p.fld_version_number as p_version_number, p.fld_product_code as p_product_code, e.fld_id as e_id, em.fld_license_id as l_id 
				FROM itc_sim_product as p 
				LEFT JOIN itc_nondigicontent_product as e ON p.fld_product_code=e.fld_asset_id
				LEFT JOIN itc_license_nondigitalcontent_mapping as em ON e.fld_id=em.fld_product_id
				LEFT JOIN itc_license_track AS lt ON em.fld_license_id = lt.fld_license_id
				LEFT JOIN itc_license_master AS lm ON em.fld_license_id = lm.fld_id 
				WHERE e.fld_nondigicat_id='4' AND p.fld_cat_id='2' AND em.fld_access='1' AND lt.fld_school_id='$schoolid' AND lt.fld_start_date<='".$date."' AND lt.fld_end_date>='".$date."' AND lm.fld_delstatus='0' AND em.fld_deleted_by='0' AND p.fld_delstatus='0'
GROUP BY p.fld_product_code 
ORDER BY p.fld_product_name ASC";

$result = $dbcon->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $query3 = "INSERT INTO itc_license_simproduct_mapping (fld_license_id,fld_cat_id,fld_asset_id, fld_type, fld_product_id, fld_created_by, fld_created_date)
								VALUES('".$row["l_id"]."', '".$catid."', '".$row["p_asset_id"]."', '".$lictype."', '".$row["p_id"]."', '2', '".$date."')";
        $dbcon->query($query3);
    }
}

?>
    <section data-type='2home' id='sim'>
        <div class='container'>
            <div class='row'>
                <div class='twelve columns'>
                    <p class="dialogTitle">Category</p>
                    <p class="dialogSubTitleLight">&nbsp;</p>
                </div>
            </div>

            <div class='row buttons' id="simcategory">
                <?php if($sessmasterprfid == 2)
                { ?>
                    <div id="large_icon_studentlist" style="float:left;">
                        <a class='skip btn mainBtn' href='#sim-category-newcategory' id='btnsim-category-newcategory' name='0'>
                            <div class="icon-synergy-add-dark"></div>
                            <div class='onBtn'>New Category</div>
                        </a>
                    </div>

                    <?php
                }

                if($sessmasterprfid == 2 || $sessmasterprfid == 3)
                {
                    $category = $ObjDB->QueryObject("SELECT c.fld_id AS id, c.fld_category_name AS categorys, fn_shortname (c.fld_category_name, 1) AS shortname
								    
							FROM itc_sim_category AS c 
							WHERE c.fld_delstatus = '0' ".$sqry." 
							GROUP BY c.fld_id ORDER BY categorys ASC");
                }
                else {



                    $category =$ObjDB->QueryObject("SELECT a.fld_cat_id AS id, c.fld_category_name AS categorys, fn_shortname (c.fld_category_name, 1) AS shortname
								  
							FROM  itc_license_simproduct_mapping AS a 
								  LEFT JOIN itc_license_track AS b 
								  ON a.fld_license_id = b.fld_license_id 
								  RIGHT JOIN itc_sim_category AS c 
								  ON a.fld_cat_id = c.fld_id 
							WHERE b.fld_district_id = '".$districtid."' AND b.fld_school_id = '".$schoolid."' 
								  AND b.fld_user_id = '".$indid."'  AND b.fld_delstatus = '0' 
								  AND '".date("Y-m-d")."' BETWEEN b.fld_start_date 
								  AND b.fld_end_date   AND a.fld_active = '1' 
								  AND c.fld_delstatus = '0' ".$sqry." 
							GROUP BY a.fld_cat_id ORDER BY categorys ");

                }


                if($category->num_rows>0)
                {
                    while($rowcat=$category->fetch_assoc())
                    {
                        extract($rowcat);
                        ?>
                        <div id="large_icon_studentlist" style="float:left;">
                            <a class='skip btn mainBtn' href='#sim-product-product' id='btnsim-product-product' name='<?php echo $id.",".$categorys;?>'>
                                <div class="icon-synergy-tests"></div>
                                <div class='onBtn'><?php echo $categorys; ?></div>
                            </a>
                        </div>

                        <?php

                    }
                }

                ?>


            </div>

        </div>
    </section>
<?php
@include("footer.php");
