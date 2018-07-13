<?php
@include("sessioncheck.php");

$date = date("Y-m-d H:i:s");
$oper = isset($method['oper']) ? $method['oper'] : '';


if ($oper == "showstate" and $oper != " ") {
    ?>
    Select State<span class="fldreq">*</span>
    <dl class='field row'>   
        <div class="selectbox">
            <input type="hidden" name="stateid" id="stateid" value="<?php echo $stdbid; ?>">
            <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                <span class="selectbox-option input-medium" data-option="1">Select State</span><b class="caret1"></b>
            </a>
            <div class="selectbox-options">
                <input type="text" class="selectbox-filter" placeholder="Search State" />
                <ul role="options">
    <?php
    $qry = $ObjDB->QueryObject("SELECT fld_id AS stdbid, fld_name AS stdbname from itc_standards_bodies ORDER BY stdbname");
    while ($res = $qry->fetch_assoc()) {
        extract($res);
        ?>
                        <li><a tabindex="-1" href="#" data-option="<?php echo $stdbid; ?>" onclick="fn_showdocuments(<?php echo $stdbid; ?>);"><?php echo $stdbname; ?></a></li>
                        <?php }
                    ?>
                </ul>
            </div>
        </div>
    </dl>  


    <?php
}



/* --- Load document dropdown --- */
if ($oper == "showdocuments" and $oper != " ") {
    $stid = isset($method['stid']) ? $method['stid'] : '';
    ?>
    Select Documents<span class="fldreq">*</span>
    <div class="selectbox">
        <input type="hidden" name="documentsubid" id="documentsubid" value="<?php echo $subjid; ?>">
        <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
            <span class="selectbox-option input-medium" data-option="" style="width:97%">Select Documents</span>
            <b class="caret1"></b>
        </a>
        <div class="selectbox-options">
            <input type="text" class="selectbox-filter" placeholder="Search Documents">
            <ul role="options" style="width:100%">
    <?php
    $qry = $ObjDB->QueryObject("SELECT a.fld_doc_title AS documenttitle,fn_shortname(a.fld_doc_title,2) as shortname, a.fld_doc_guid AS docguid, 
                                                            b.fld_id as subjid, b.fld_sub_title AS subjectname,fn_shortname(b.fld_sub_title,1) as shortsubjname,
                                                            b.fld_sub_year AS year, b.fld_sub_guid AS guid
                                                            FROM itc_correlation_documents a
                                                            LEFT JOIN itc_correlation_doc_subject b ON a.fld_id=b.fld_doc_id
                                                            WHERE  a.fld_authority_id='" . $stid . "'");
    if ($qry->num_rows > 0) {
        while ($row = $qry->fetch_assoc()) {
            extract($row);
            $stddocs = $documenttitle . " | " . $subjectname . " (" . $year . ")";
            ?>
                        <li><a tabindex="-1" href="#" data-option="<?php echo $subjid; ?>" onclick="fn_showgrades('<?php echo $subjid; ?>')"><?php echo $shortname . " | " . $shortsubjname . " (" . $year . ")"; ?></a></li>
                        <?php
                    }
                }
                ?>      
            </ul>
        </div>
    </div>


                <?php
            }

            /* --- Load document dropdown --- */
            if ($oper == "showgrades" and $oper != " ") {
                $subjid = isset($method['subid']) ? $method['subid'] : '';
                ?>
    Select Grades<span class="fldreq">*</span>
    <div class="selectbox">
        <input type="hidden" name="grades" id="grades" value="<?php echo $gguid; ?>">
        <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
            <span class="selectbox-option input-medium" data-option="" style="width:97%">Select Grades</span>
            <b class="caret1"></b>
        </a>
        <div class="selectbox-options">
            <input type="text" class="selectbox-filter" placeholder="Search Grades">
            <ul role="options" style="width:100%">
    <?php
    $qry = $ObjDB->QueryObject("SELECT fld_grade_guid AS gguid, fld_grade_name AS gradename,fld_id as gradeid,
                                                            fn_shortname(fld_grade_name,1) as shortgrdname
                                                            FROM itc_correlation_grades 
                                                            WHERE fld_sub_id='" . $subjid . "'");
    if ($qry->num_rows > 0) {
        while ($row = $qry->fetch_assoc()) {
            extract($row);
            ?>
                        <li><a tabindex="-1" href="#" data-option="<?php echo $gguid; ?>" onclick="fn_showinnerstandards('<?php echo $gradeid; ?>')"><?php echo $gradename; ?></a></li>
                        <?php
                    }
                }
                ?>      
            </ul>
        </div>
    </div>


                <?php
            }
            if ($oper == "showstandrads" and $oper != " ") {
                $gradeidss = isset($method['gradeids']) ? $method['gradeids'] : '';
                ?>
    Select Standard<span class="fldreq">*</span>
    <div class="selectbox">
        <input type="hidden" name="standradids" id="standradids" value="<?php echo $standardid; ?>">
        <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
            <span class="selectbox-option input-medium" data-option="" style="width:97%">Select Standard</span>
            <b class="caret1"></b>
        </a>
        <div class="selectbox-options">
            <input type="text" class="selectbox-filter" placeholder="Search Standard">
            <ul role="options" style="width:100%">
    <?php
    $qry = $ObjDB->QueryObject("SELECT fld_standard_guid AS stdgguid, fld_standard_name AS standardname,fld_id as standardid,
                                                            fld_standardname_id as nameid
                                                            FROM itc_correlation_standards 
                                                            WHERE fld_grade_id='" . $gradeidss . "'");
    if ($qry->num_rows > 0) {
        while ($row = $qry->fetch_assoc()) {
            extract($row);
            ?>
                        <li><a tabindex="-1" href="#" data-option="<?php echo $stdgguid; ?>" onclick="fn_showinnerstandards('<?php echo $standardid; ?>')">
            <?php echo $standardname . "(" . $nameid . ")"; ?></a></li>
            <?php
        }
    }
    ?>      
            </ul>
        </div>
    </div>


                <?php
            }

            if ($oper == "showinnerstandrads" and $oper != "") {

                $gradeids = isset($method['gradeids']) ? $method['gradeids'] : '';
                ?>
    <ul>
        <li><input type="checkbox" id="selecctall" /> Select All</li>
    </ul>
    <br>

    <?php
    $qrystandard = $ObjDB->QueryObject("SELECT fld_standard_guid AS stdgguid, fld_standard_name AS standardname,fld_id as standardids,
                                                            fld_standardname_id as nameid
                                                            FROM itc_correlation_standards 
                                                            WHERE fld_grade_id='" . $gradeids . "'");
    if ($qrystandard->num_rows > 0) {
        while ($rowstandard = $qrystandard->fetch_assoc()) {
            extract($rowstandard);
            ?>
            <font color="blue">Big Idea <?php echo $nameid; ?></font>
            <br>
            <div class="selectbox">
            <?php
            $qry = $ObjDB->QueryObject("SELECT fld_innerstandard_guid AS innerstdgguid, fld_innerstandard_name AS innerstandardname,fld_id as innerstandardid,
                                                            fld_innerstandardname_id as innernameid
                                                            FROM itc_correlation_innerstandards 
                                                            WHERE fld_standard_id='" . $standardids . "'");
            if ($qry->num_rows > 0) {
                while ($row = $qry->fetch_assoc()) {
                    extract($row);
                    ?>
                        <ul style="list-style: none; ">
                            <li>
                                <input class="checkbox-class" type="checkbox" name="deepinnerid" id="deepinnerid_<?php echo $innerstandardid; ?>" value="<?php echo $innerstdgguid; ?>"  > 
                    <?php echo $innernameid . " " . $innerstandardname; ?>
                            </li>
                        </ul>
                    <?php
                    $deepqry = $ObjDB->QueryObject("SELECT fld_deepinnerstandard_guid AS deepinnerstdgguid, fld_deepinnerstandard_name AS deepinnerstandardname,fld_id as deepinnerstandardid,
                                                            fld_deepinnerstandardname_id as deepinnernameid
                                                            FROM itc_correlation_deepinnerstandards 
                                                            WHERE fld_innerstandard_id='" . $innerstandardid . "'");

                    if ($deepqry->num_rows > 0) {
                        while ($deeprow = $deepqry->fetch_assoc()) {
                            extract($deeprow);
                            ?>
                                <ul style="list-style: none; text-indent: 20px; ">

                                    <li>
                                        <input class="checkbox-class"  type="checkbox" name="deepinnerid" id="deepids_<?php echo $deepinnerstandardid; ?>" value="<?php echo $deepinnerstdgguid; ?>" >
                                <?php echo $deepinnernameid . " " . $deepinnerstandardname; ?>
                                    </li>
                                </ul>
                            <?php
                            $subdeepqry = $ObjDB->QueryObject("SELECT fld_subdeepinnerstandard_guid AS subdeepinnerstdgguid, fld_subdeepinnerstandard_name AS subdeepinnerstandardname,fld_id as subdeepinnerstandardid,
                                                            fld_subdeepinnerstandardname_id as subdeepinnernameid
                                                            FROM itc_correlation_subdeepinnerstandards 
                                                            WHERE fld_deepinnerstandard_id='" . $deepinnerstandardid . "'");

                            if ($subdeepqry->num_rows > 0) {
                                while ($subdeeprow = $subdeepqry->fetch_assoc()) {
                                    extract($subdeeprow);
                                    ?>
                                        <ul style="list-style: none; text-indent: 20px; ">

                                            <li>
                                                <input class="checkbox-class"  type="checkbox" name="subdeepinnerid" id="subdeepids_<?php echo $subdeepinnerstandardid; ?>" value="<?php echo $subdeepinnerstdgguid; ?>" >
                                        <?php echo $subdeepinnernameid . " " . $subdeepinnerstandardname; ?>
                                            </li>
                                        </ul>
                                        <?php
                                    }
                                }
                            }
                        }
                    } // while ends
                } // if ends 
                ?>
            </div>
            <br><br>
                <?php
            } // if ends of standard
        } // while ends of standard
        ?><script>
                                        $(document).ready(function() {
                                            $('#selecctall').click(function(event) {  //on click
                                                if (this.checked) { // check select status
                                                    $('.checkbox-class').each(function() { //loop through each checkbox
                                                        this.checked = true;  //select all checkboxes with class "checkbox1"              
                                                    });
                                                } else {
                                                    $('.checkbox-class').each(function() { //loop through each checkbox
                                                        this.checked = false; //deselect all checkboxes with class "checkbox1"                      
                                                    });
                                                }
                                            });

                                        });
    </script>

        <?php
    }


    if ($oper == "showproducts" and $oper != " ") {
        $type = isset($method['type']) ? $method['type'] : 0;
        $typeids = explode(",", $type);
        ?>
    <script type="text/javascript" language="javascript">
        $(function() {
            $('#testrailvisible13').slimscroll({
                width: '410px',
                height: '366px',
                size: '7px',
                railVisible: true,
                allowPageScroll: false,
                railColor: '#F4F4F4',
                opacity: 1,
                color: '#d9d9d9',
                wheelStep: 1,
            });

            $('#testrailvisible14').slimscroll({
                width: '410px',
                height: '370px',
                size: '7px',
                railVisible: true,
                allowPageScroll: false,
                railColor: '#F4F4F4',
                opacity: 1,
                color: '#d9d9d9',
                wheelStep: 1,
            });
            $("#list7").sortable({
                connectWith: ".droptrue3",
                dropOnEmpty: true,
                items: "div[class='draglinkleft']",
                receive: function(event, ui) {
                    $("div[class=draglinkright]").each(function() {
                        if ($(this).parent().attr('id') == 'list7') {

                            fn_movealllistitems('list7', 'list8', 1, $(this).children(":first").attr('id'));

                        }
                    });
                }
            });
            $("#list8").sortable({
                connectWith: ".droptrue3",
                dropOnEmpty: true,
                receive: function(event, ui) {
                    $("div[class=draglinkleft]").each(function() {
                        //alert();
                        if ($(this).parent().attr('id') == 'list8') {
                            //alert($(this).children(":first").attr('id'));
                            fn_movealllistitems('list7', 'list8', 1, $(this).children(":first").attr('id'));

                        }
                    });
                }
            });
        });
    </script>
    <?php
    if ($sessmasterprfid == 2) {



//	 $qryunits="SELECT a.fld_id AS id, a.fld_unit_name AS nam,fn_shortname(a.fld_unit_name,2) AS shortname, 
//				2 AS typ, a.fld_asset_id as assetid
//	  			FROM itc_unit_master as a
//				WHERE a.fld_delstatus='0'";
//	  
//
//	  $qrymodules="SELECT a.fld_id AS id, CONCAT(a.fld_module_name,' ',b.fld_version) AS nam,fn_shortname(a.fld_module_name,2) AS shortname,
// 					3 AS typ, a.fld_asset_id AS assetid
//					FROM itc_module_master AS a 
//					LEFT JOIN itc_module_version_track AS b ON b.fld_mod_id=a.fld_id
//					WHERE a.fld_delstatus='0' AND b.fld_delstatus='0'";
//	  
//	  $qrymathmodules="SELECT a.fld_id AS id, CONCAT(a.fld_mathmodule_name,' ',b.fld_version) AS nam,fn_shortname(a.fld_mathmodule_name,2) AS shortname, 4 AS typ, a.fld_asset_id AS assetid 
//						FROM itc_mathmodule_master AS a 
//						LEFT JOIN itc_module_version_track AS b ON b.fld_mod_id=a.fld_module_id
//						WHERE a.fld_delstatus='0' AND b.fld_delstatus='0'";
//          /**Expedition Report Start here**/
//          $qryexpedition="SELECT a.fld_id AS id, CONCAT(a.fld_exp_name, ' ', b.fld_version) AS nam, 
//                        fn_shortname (CONCAT(a.fld_exp_name, ' ', b.fld_version), 2) AS shortname,5 AS typ, a.fld_asset_id as assetid
//							  FROM itc_exp_master AS a 
//							  LEFT JOIN itc_exp_version_track AS b ON b.fld_exp_id = a.fld_id 
//							  WHERE a.fld_delstatus = '0' AND b.fld_delstatus = '0'
//							  ORDER BY a.fld_exp_name ASC ";
        /*         * Expedition Report End here* */
    }
    ?>
    <div class='six columns'>
        <div class="dragndropcol">
            <div class="dragtitle">Products</div>
            <div class="dragWell" id="testrailvisible13" >
                <div id="list7" class="dragleftinner droptrue3">
                    <div class="draglinkleftSearch" id="s_list7" >
                        <dl class='field row'>
                            <dt class='text'>
                            <input placeholder='Search' type='text' id="list_7_search" name="list_7_search" onKeyUp="search_list(this, '#list7');" />
                            </dt>
                        </dl>
                    </div>
    <?php
    for ($t = 0; $t < sizeof($typeids); $t++) {


        $qry = "SELECT fld_id AS id, CONCAT(fld_prd_name,' ',fld_prd_version) AS nam,fld_prd_version as pversion,fn_shortname(fld_prd_name,4) AS shortname,fld_prd_type as typ ,
                                                      fld_asset_id AS assetid
                                                      FROM itc_correlation_productdetails 
                                                      WHERE  fld_delstatus='0' AND fld_prd_type='" . $typeids[$t] . "' ORDER BY nam";

        $productqry = $ObjDB->QueryObject($qry);
        if ($productqry->num_rows > 0) {
            while ($productqryrow = $productqry->fetch_assoc()) {
                extract($productqryrow);
                ?>

                                <div class="draglinkleft" id="list7_<?php echo $id . "_" . $typ; ?>" >
                                    <div class="dragItemLable tooltip" id="<?php echo $id . "_" . $typ; ?>" title="<?php echo $nam; ?>"><?php echo $shortname." ".$pversion; ?></div>
                                    <div class="clickable" id="clck_<?php echo $id . "_" . $typ; ?>" onclick="fn_movealllistitems('list7', 'list8', 1, '<?php echo $id . "_" . $typ; ?>');"></div>
                                </div>
                <?php
            } // while ends
        } // if ends
    } // for ends					
    ?>
                </div>
            </div>
            <div class="dragAllLink" onclick="fn_movealllistitems('list7', 'list8', 0);" style="cursor:pointer;cursor:hand;">add all Products</div>
        </div>
    </div>
    <div class='six columns'>
        <div class="dragndropcol">
            <div class="dragtitle">Selected Products<span class="fldreq">*</span></div>
            <div class="dragWell" id="testrailvisible14">
                <div id="list8" class="dragleftinner droptrue3">
                    <div class="draglinkleftSearch" id="s_list8" >
                        <dl class='field row'>   
                            <dt class='text'>
                            <input placeholder='Search' type='text' id="list_8_search" name="list_8_search" onKeyUp="search_list(this, '#list8');" />
                            </dt>
                        </dl>
                    </div>
                    <?php
                    if ($productqry->num_rows > 0) {
                        while ($productqryrow = $productqry->fetch_assoc()) {
                            extract($productqryrow);
                            ?>

                            <div class="draglinkleft" id="list8_<?php echo $id . "_" . $typ; ?>" >
                                <div class="dragItemLable tooltip" id="<?php echo $id . "_" . $typ; ?>" title="<?php echo $nam; ?>"><?php echo $shortname; ?></div>
                                <div class="clickable" id="clck_<?php echo $id . "_" . $typ; ?>" onclick="fn_movealllistitems('list7', 'list8', 1, '<?php echo $id . "_" . $typ; ?>');"></div>
                            </div>
                            <?php
                        } // while ends
                    } // if ends
                    ?>
                </div>	
            </div>
            <div class="dragAllLink" onclick="fn_movealllistitems('list8', 'list7', 0);"  style="cursor: pointer;cursor:hand;width: 160px;float: right; ">remove all Products</div>
        </div>
    </div>

    <?php
}



                /*
                 * starts for selecting production by tag types
                 */
                if ($oper == "makecorrelation" and $oper != " ") {
                    $prdids = isset($method['productid']) ? $method['productid'] : '';
                    $ptypeid = isset($method['ptype']) ? $method['ptype'] : '';
                    $resids = isset($method['resid']) ? $method['resid'] : '';
                    $standardguids = isset($method['guids']) ? $method['guids'] : '';
                    $finalstandard = explode(",", $standardguids);
                    $finalptypeid = explode(",", $ptypeid);
                    $finalprdids = explode(",", $prdids);
                    $finalresids = explode(",", $resids);

                    $resultofstandards = array();

                    for ($i = 0; $i < sizeof($finalstandard); $i++) {


                        for ($a = 0; $a < sizeof($finalprdids); $a++) {
                            $finalprdtypeids = explode("_", $finalprdids[$a]);

                           /* if ($finalprdtypeids[1] != 5) {
                                $prdguid = $ObjDB->SelectSingleValue("SELECT fld_asset_id
                                            FROM itc_correlation_productdetails 
                                            WHERE fld_prd_type='" . $finalprdtypeids[1] . "' 
                                            AND fld_id='" . $finalprdtypeids[0] . "' AND fld_exp_type='0'");
                            } else if ($finalprdtypeids[1] == 5) {*/
							
                                $prdguid = $ObjDB->SelectSingleValue("SELECT fld_prd_asset_id
                                            FROM itc_correlation_productdetails 
                                            WHERE fld_prd_type='" . $finalprdtypeids[1] . "' 
                                            AND fld_id='" . $finalprdtypeids[0] . "' ");//AND fld_exp_type='3'
							
                           // }

                            $resultofstandards[] = $finalstandard[$i] . "~" . $prdguid . "~" . $finalprdtypeids[1] . "~" . $finalprdtypeids[0];
                        } // for ends products
                    } // for ends standards
                    echo json_encode($resultofstandards);
                }

                if ($oper == "correlationsignature" and $oper != " ") {
                    $passetguid = isset($method['passetguid']) ? $method['passetguid'] : '';
                    $standguids = isset($method['standguids']) ? $method['standguids'] : '';

                    $icnt = isset($method['icnt']) ? $method['icnt'] : '';
                    $ptype = isset($method['ptype']) ? $method['ptype'] : '';
                    $prdid = isset($method['prdid']) ? $method['prdid'] : '';

                    $partnerID = 'pitsco';
                    $partnerKey = 'q044Qjav7i8dzgCJ6riUWA';
                    $authExpires = time() + 86400;
                    $userID = '';
                    $url = 'http://api.academicbenchmarks.com/rest/v3/standards/' . $standguids . '/assets/' . $passetguid . '?';
                    $url .= 'partner.id=' . $partnerID;
                    $message = $authExpires . "\n" . $userID;
                    $sig = urlencode(base64_encode(hash_hmac('sha256', $message, $partnerKey, true)));

                    $url .= '&auth.signature=' . $sig;
                    $url .= '&auth.expires=' . $authExpires;

                    $url .= '&user.id=' . $userID;

                    echo $icnt . "~" . $url . "~" . $ptype . "~" . $prdid . "~" . $standguids . "~" . $passetguid;
                }

                if ($oper == "savealignment" and $oper != " ") {
                    $prdids = isset($method['prdid']) ? $method['prdid'] : '';
                    $ptypeid = isset($method['ptype']) ? $method['ptype'] : '';
                    $stateid = isset($method['stateid']) ? $method['stateid'] : '';
                    $documentid = isset($method['documentid']) ? $method['documentid'] : '';
                    $grades = isset($method['grades']) ? $method['grades'] : '';
                    $standardguids = isset($method['guid']) ? $method['guid'] : '';
                    $alignmenttypeids = isset($method['alignmenttype']) ? $method['alignmenttype'] : '';
                    $prdassetidguids = isset($method['prdassetid']) ? $method['prdassetid'] : '';
                    $finalstandard = explode(",", $standardguids);

                    for ($i = 0; $i < sizeof($finalstandard); $i++) {
                        $gradid = $ObjDB->SelectSingleValue("SELECT fld_id
                                            FROM itc_correlation_grades 
                                            WHERE fld_grade_guid='" . $grades . "'");

                        $qrystandard = $ObjDB->QueryObject("SELECT fld_standard_guid AS stdgguid, fld_standard_name AS standardname,fld_id as standardids,
                                                            fld_standardname_id as nameid
                                                            FROM itc_correlation_standards 
                                                            WHERE fld_grade_id='" . $gradid . "'");
                        if ($qrystandard->num_rows > 0) {
                            while ($rowstandard = $qrystandard->fetch_assoc()) {
                                extract($rowstandard);

                                $totstd[] = $standardids;

                                $qry = $ObjDB->QueryObject("SELECT fld_innerstandard_guid AS innerstdgguid, fld_innerstandard_name AS innerstandardname,fld_id as innerstandardid,
                                                      fld_innerstandardname_id as innernameid
                                                      FROM itc_correlation_innerstandards 
                                                      WHERE fld_standard_id='" . $standardids . "'");
                                if ($qry->num_rows > 0) {
                                    while ($row = $qry->fetch_assoc()) {
                                        extract($row);

                                        $totstd[] = $innerstandardid;

                                        $deepqry = $ObjDB->QueryObject("SELECT fld_deepinnerstandard_guid AS deepinnerstdgguid, fld_deepinnerstandard_name AS deepinnerstandardname,fld_id as deepinnerstandardid,
                                                               fld_deepinnerstandardname_id as deepinnernameid
                                                               FROM itc_correlation_deepinnerstandards 
                                                               WHERE fld_innerstandard_id='" . $innerstandardid . "'");

                                        if ($deepqry->num_rows > 0) {
                                            while ($deeprow = $deepqry->fetch_assoc()) {
                                                extract($deeprow);

                                                $totstd[] = $deepinnerstandardid;

                                                $subdeepqry = $ObjDB->QueryObject("SELECT fld_id as subdeepinnerstandardid
                                                                               FROM itc_correlation_subdeepinnerstandards 
                                                                               WHERE fld_deepinnerstandard_id='" . $deepinnerstandardid . "'");

                                                if ($subdeepqry->num_rows > 0) {
                                                    while ($subdeeprow = $subdeepqry->fetch_assoc()) {
                                                        extract($subdeeprow);

                                                        $totstd[] = $subdeepinnerstandardid;
                                                    }
                                                }
                                            }
                                        }
                                    } // while ends
                                } // if ends 
                            } // if ends of standard
                        } // while ends of standard

                       // if ($ptypeid != 5) {
                            $cunt = $ObjDB->SelectSingleValue("SELECT fld_id
                                            FROM itc_correlation_alignment 
                                            WHERE fld_prdid='" . $prdids . "' AND fld_ptype='" . $ptypeid . "' 
                                            AND fld_deepinnerstandard='" . $finalstandard[$i] . "' AND fld_resoid='0' AND fld_delstatus='0'");


                            if ($cunt == 0) {
                                if ($alignmenttypeids == 2) {
                                    $ObjDB->NonQuery("INSERT INTO itc_correlation_alignment(fld_state_id, fld_documentsubid, fld_gradeid, fld_deepinnerstandard, fld_ptype, fld_prdid, fld_resoid, fld_createddate, fld_createdby,fld_delstatus) 
                                                    VALUES ('" . $stateid . "','" . $documentid . "', '" . $grades . "','" . $finalstandard[$i] . "','" . $ptypeid . "','" . $prdids . "','0','" . date('Y-m-d H:i:s') . "','" . $uid . "','1')");
                                } else {
                                    $ObjDB->NonQuery("INSERT INTO itc_correlation_alignment(fld_state_id, fld_documentsubid, fld_gradeid, fld_deepinnerstandard, fld_ptype, fld_prdid, fld_resoid, fld_createddate, fld_createdby,fld_delstatus) 
                                                    VALUES ('" . $stateid . "','" . $documentid . "', '" . $grades . "','" . $finalstandard[$i] . "','" . $ptypeid . "','" . $prdids . "','0','" . date('Y-m-d H:i:s') . "','" . $uid . "','0')");
                                }
                            } else {
                                if ($alignmenttypeids == 2) {
                                    $ObjDB->NonQuery("UPDATE itc_correlation_alignment set fld_delstatus='1',fld_updateddate='" . date('Y-m-d H:i:s') . "',fld_updatedby='" . $uid . "' 
                                            WHERE fld_state_id='" . $stateid . "' AND fld_documentsubid='" . $documentid . "' AND fld_gradeid='" . $grades . "' 
                                            AND fld_deepinnerstandard='" . $finalstandard[$i] . "' AND fld_ptype='" . $ptypeid . "' AND fld_prdid='" . $prdids . "' AND fld_resoid='0'");


                                    $stndcunt = $ObjDB->SelectSingleValue("SELECT COUNT(fld_id)
                                                            FROM itc_correlation_alignment 
                                                            WHERE fld_gradeid='" . $grades . "' AND fld_prdid='" . $prdids . "' AND fld_ptype='" . $ptypeid . "' 
                                                            AND fld_resoid='0' AND fld_delstatus='1'");


                                    if ($stndcunt == sizeof($totstd)) {
                                        $prdasset = $ObjDB->SelectSingleValue("SELECT fld_prd_asset_id
                                FROM itc_correlation_products 
                                WHERE fld_prd_sys_id='" . $prdids . "' AND fld_prd_type='" . $ptypeid . "' 
                                AND fld_exp_type='0'");

                                        $ObjDB->NonQuery("UPDATE itc_correlation_productsgradeout set fld_flag='1',fld_updated_date='" . date('Y-m-d H:i:s') . "' WHERE fld_standardguid='" . $grades . "' AND fld_productid='" . $prdasset . "'");
                                    }
                                }
                            //}
                        } 
						/*else if ($ptypeid == 5) {
                            $cunt = $ObjDB->SelectSingleValue("SELECT fld_id
                                            FROM itc_correlation_alignment 
                                            WHERE fld_prdid='" . $prdids . "' AND fld_ptype='" . $ptypeid . "' 
                                            AND fld_deepinnerstandard='" . $finalstandard[$i] . "' AND fld_resoid='" . $prdids . "' AND fld_delstatus='0'");
                            if ($cunt == 0) {

                                $ObjDB->NonQuery("INSERT INTO itc_correlation_alignment(fld_state_id, fld_documentsubid, fld_gradeid, fld_innerstandard, fld_deepinnerstandard, fld_ptype, fld_prdid, fld_resoid, fld_createddate, fld_createdby,fld_delstatus) 
                                                    VALUES ('" . $stateid . "','" . $documentid . "', '" . $grades . "','" . $standradids . "','" . $finalstandard[$i] . "','" . $ptypeid . "','" . $prdids . "','" . $prdids . "','" . date('Y-m-d H:i:s') . "','" . $uid . "','0')");
                            } else {
                                if ($alignmenttypeids == 2) {
                                    $ObjDB->NonQuery("UPDATE itc_correlation_alignment set fld_delstatus='1',fld_updateddate='" . date('Y-m-d H:i:s') . "',fld_updatedby='" . $uid . "' 
                                            WHERE fld_state_id='" . $stateid . "' AND fld_documentsubid='" . $documentid . "' AND fld_gradeid='" . $grades . "' 
                                            AND fld_deepinnerstandard='" . $finalstandard[$i] . "' AND fld_ptype='" . $ptypeid . "' AND fld_prdid='" . $prdids . "' AND fld_resoid='" . $prdids . "'");

                                    $stndcunt = $ObjDB->SelectSingleValue("SELECT COUNT(fld_id)
                                                            FROM itc_correlation_alignment 
                                                            WHERE fld_gradeid='" . $grades . "' AND fld_prdid='" . $prdids . "' AND fld_ptype='" . $ptypeid . "' 
                                                            AND fld_resoid='" . $prdids . "' AND fld_delstatus='1'");


                                    if ($stndcunt == sizeof($totstd)) {
                                        $prdasset = $ObjDB->SelectSingleValue("SELECT fld_prd_asset_id
                                                                FROM itc_correlation_products 
                                                                WHERE fld_prd_sys_id='" . $prdids . "' AND fld_prd_type='" . $ptypeid . "' 
                                                                AND fld_exp_type='3'");

                                        $ObjDB->NonQuery("UPDATE itc_correlation_productsgradeout set fld_flag='1',fld_updated_date='" . date('Y-m-d H:i:s') . "' WHERE fld_standardguid='" . $grades . "' AND fld_productid='" . $prdasset . "'");
                                    }
                                }
                            }
                        }*/
                    }
                }

                if ($oper == 'newclassnameform' and $oper != '') {
                    $tid = isset($method['titleid']) ? $method['titleid'] : '0';
                    $titletype = isset($method['titletype']) ? $method['titletype'] : '0';
                    $protitle = $ObjDB->SelectSingleValue("SELECT fld_title_name  FROM itc_correlation_producttitles 
											  WHERE fld_id='" . $tid . "' 
											  AND fld_delstatus='0'");
                    ?>


    <?php
    if ($tid != '0') {
        ?>
        <div class="four columns">
            <div class="row rowspacer" style="min-width:400px;">  
                <form name="classnameextendforms" id="classnameextendforms" >
                    <div class="eleven columns" style="float: left; font-weight: bold; font-size: 15px;margin-left:15px" >
                        <span style="color:red" >*</span>Update Product Title: &nbsp;&nbsp;&nbsp;
                        <dl class="field row">
                            <dt class="text">
                            <input type="text" onblur="$(this).valid();" value="<?php echo $protitle; ?>" name="txtclassname" id="txtclassname" placeholder="Update Product Title" />
                            </dt> 
                        </dl>
                    </div>
                </form>
            </div>     
            <div class="row rowspacer" style="min-width:400px;">
                <div style="margin-left:220px;margin-right:10px;" >
                    <input style="margin-right:10px;width:90px" onclick="fn_saveclassform(<?php echo $tid; ?>);" type="button" class="module-extend-button" value="Update"   /> 
                    <input type="button" style="width:90px" onclick="fn_cancelclassform();" class="module-extend-button" value="Cancel"  /> 
                </div>
            </div>
        </div>
    <?php
    } else {
        ?>
        <div class="four columns">
            <div class="row rowspacer" style="min-width:400px;">  
                <form name="classnameextendforms" id="classnameextendforms" >
                    <div class="eleven columns" style="float: left; font-weight: bold; font-size: 15px;margin-left:15px" >
                        <span style="color:red" >*</span>New Product Title: &nbsp;&nbsp;&nbsp;
                        <dl class="field row">
                            <dt class="text">
                            <input type="text" onblur="$(this).valid();" value="" name="txtclassname" id="txtclassname" placeholder="New Product Title" />
                            </dt> 
                        </dl>
                    </div>
                </form>
            </div>     
            <div class="row rowspacer" style="min-width:400px;">
                <div style="margin-left:220px;margin-right:10px;" >
                    <input style="margin-right:10px;width:90px" onclick="fn_saveclassform(0);" type="button" class="module-extend-button" value="Save"   /> 
                    <input type="button" style="width:90px" onclick="fn_cancelclassform();" class="module-extend-button" value="Cancel"  /> 
                </div>
            </div>
        </div>
    <?php } ?>

    <script type="text/javascript" language="javascript" >
        $('#txtclassname').keypress(function(event) {
            if (event.which == 13) {
                event.preventDefault();
                fn_saveextendform();
            }
        });
        $("#classnameextendforms").validate({
            ignore: "",
            errorElement: "dd",
            errorPlacement: function(error, element) {
                $(element).parents('dl').addClass('error');
                error.appendTo($(element).parents('dl'));
                error.addClass('msg');
                window.scroll(0, ($('dd').offset().top) - 50);
            },
            rules: {
                txtclassname: {required: true}
            },
            messages: {
                txtclassname: {required: "please type Product Title"}, //, remote: "Module Name already exists"
            },
            highlight: function(element, errorClass, validClass) {
                $(element).parents('dl').addClass(errorClass);
                $(element).addClass(errorClass).removeClass(validClass);
            },
            unhighlight: function(element, errorClass, validClass) {
                if ($(element).attr('class') == 'error') {
                    $(element).parents('dl').removeClass(errorClass);
                    $(element).removeClass(errorClass).addClass(validClass);
                }
            },
            onkeyup: false,
            onblur: true
        });
    </script>
    <?php
}

/* * ***this opertion can perform to save the form details of getting extend text in database*** */
if ($oper == 'saveclasstxt' and $oper != '') {

    try {
        $tid = isset($method['tid']) ? $method['tid'] : '0';


        $classnametxt = isset($method['classnametxt']) ? ($method['classnametxt']) : '';

        if ($tid != '0') {
            $ObjDB->NonQuery("UPDATE itc_correlation_producttitles set fld_title_name='" . $classnametxt . "' WHERE fld_id='" . $tid . "'");
            echo "update";
        } else {
            $ctype = $ObjDB->SelectSingleValueInt("SELECT count(fld_title_type) 
                                                           FROM itc_correlation_producttitles");

            $ctype = $ctype + 1;
            //echo $ctype;
            $ObjDB->NonQuery("INSERT INTO itc_correlation_producttitles (fld_title_name, fld_created_date, fld_title_type)VALUES('" . $classnametxt . "','" . $date . "','" . $ctype . "')");
            echo "success";
        }
    } catch (Exception $e) {
        echo "fail";
    }
}

if ($oper == "loadclassname" and $oper != " ") {
    ?>


    <dl class='field row' id="classnameload">
        <dt class='dropdown'>
        <div class="selectbox">

            <input type="hidden" name="selectui" class="required" id="selectui" >
            <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                <span class="selectbox-option input-medium" data-option="" style="width:95%;">Select</span>
                <b class="caret1"></b>
            </a>
            <div class="selectbox-options">
                <input type="text" class="selectbox-filter" placeholder="Search class" >
                <ul role="options">
    <?php
    $stateqry = $ObjDB->QueryObject("SELECT fld_title_name AS name,fld_id AS titleid
                                                                            FROM itc_correlation_producttitles 
                                                                            WHERE fld_delstatus='0' order by  fld_title_name;");
    if ($stateqry->num_rows > 0) {
        while ($rowstateqry = $stateqry->fetch_assoc()) {
            extract($rowstateqry);
            ?>
                            <li><a href="#" data-option="<?php echo $titleid; ?>"><?php echo $name; ?></a></li>
            <?php
        }
    }
    ?>       
                </ul>
            </div>

        </div>
        </dt>
    </dl>

    <?php
}
/* -------Productset-------- */

if ($oper == 'newproductset' and $oper != '') {
    ?>
    <div class="four columns">
        <div class="row rowspacer" style="min-width:400px;">  
            <form name="classnameextendforms" id="classnameextendforms" >
                <div class="eleven columns" style="float: left; font-weight: bold; font-size: 15px;margin-left:15px" >
                    <span style="color:red" >*</span>New Productset: &nbsp;&nbsp;&nbsp;
                    <dl class="field row">
                        <dt class="text">
                        <input type="text" onblur="$(this).valid();" value="<?php echo $extendtxt; ?>" name="txtclassname" id="txtclassname" placeholder="New Productset" />
                        </dt> 
                    </dl>
                </div>
            </form>
        </div>     
        <div class="row rowspacer" style="min-width:400px;">
            <div style="margin-left:220px;margin-right:10px;" >
                <input style="margin-right:10px;width:90px" onclick="fn_saveproductsetform();" type="button" class="module-extend-button" value="Save"   /> 
                <input type="button" style="width:90px" onclick="fn_cancelproductsetform();" class="module-extend-button" value="Cancel"  /> 
            </div>
        </div>
    </div>
    <script type="text/javascript" language="javascript" >
        $('#txtclassname').keypress(function(event) {
            if (event.which == 13) {
                event.preventDefault();
                fn_saveextendform();
            }
        });
        $("#classnameextendforms").validate({
            ignore: "",
            errorElement: "dd",
            errorPlacement: function(error, element) {
                $(element).parents('dl').addClass('error');
                error.appendTo($(element).parents('dl'));
                error.addClass('msg');
                window.scroll(0, ($('dd').offset().top) - 50);
            },
            rules: {
                txtclassname: {required: true}
            },
            messages: {
                txtclassname: {required: "please type Productset"}, //, remote: "Module Name already exists"
            },
            highlight: function(element, errorClass, validClass) {
                $(element).parents('dl').addClass(errorClass);
                $(element).addClass(errorClass).removeClass(validClass);
            },
            unhighlight: function(element, errorClass, validClass) {
                if ($(element).attr('class') == 'error') {
                    $(element).parents('dl').removeClass(errorClass);
                    $(element).removeClass(errorClass).addClass(validClass);
                }
            },
            onkeyup: false,
            onblur: true
        });
    </script>
    <?php
}
if ($oper == 'saveproductsettxt' and $oper != '') {
    try {
        $classproductsettxt = isset($method['classproductsettxt']) ? ($method['classproductsettxt']) : '';
        $ObjDB->NonQuery("INSERT INTO itc_correlation_productset(fld_productset_name, fld_created_date, fld_created_by)VALUES('" . $classproductsettxt . "','" . $date . "','" . $uid . "')");
        echo "success";
    } catch (Exception $e) {
        echo "fail";
    }
}

if ($oper == "loadproductsetname" and $oper != " ") {
    ?>


    <script type="text/javascript">
        $(function() {

            $('div[id^="testrailvisible"]').each(function(index, element) {
                $(this).slimscroll({/*------- Scroll for Modules Left Box ------*/
                    width: '410px',
                    height: '366px',
                    railVisible: true,
                    allowPageScroll: false,
                    railColor: '#F4F4F4',
                    opacity: 1,
                    color: '#d9d9d9',
                    size: '7px',
                    alwaysVisible: true,
                    wheelstep: 1
                });
            });




            $("#list13").sortable({/*------- Productset left Box ------*/
                connectWith: ".droptrue",
                dropOnEmpty: true,
                items: "div[class='draglinkleft']",
                receive: function(event, ui) {
                    $("div[class='draglinkright']").each(function() {
                        if ($(this).parent().attr('id') == 'list13') {
                            fn_movealllistitems('list13', 'list14', 1, $(this).children(":first").attr('id'));
                        }
                    });
                }
            });

            $("#list14").sortable({/*------- Productset right Box ------*/
                connectWith: ".droptrue",
                dropOnEmpty: true,
                receive: function(event, ui) {
                    $("div[class='draglinkleft']").each(function() {
                        if ($(this).parent().attr('id') == 'list14') {
                            fn_movealllistitems('list13', 'list14', 1, $(this).children(":first").attr('id'));
                        }
                    });
                }
            });

        });

    </script>
    <div class='row rowspacer' id="productsetnameload"> <!-- Shows Productset list to select-->
        <div class='six columns'>
            <div class="dragndropcol">
    <?php
    $qryprdset = $ObjDB->QueryObject("SELECT fld_id as prdsetid, fld_productset_name as prdsetname FROM itc_correlation_productset where fld_delstatus=0");
    ?>
                <div class="dragtitle">Productsets available (<span id="leftprdset"><?php echo $qryprdset->num_rows; ?></span>)<strong>
                        <span  id="btnstep"  style="cursor:pointer;" onclick="fn_newproductset();"  class="right">+Add Productset </span>
                    </strong></div>

                <div class="draglinkleftSearch" id="s_list13" > <!-- search for left box of expedition -->
                    <dl class='field row'>
                        <dt class='text'>
                        <input placeholder='Search' type='text' id="list_13_search" name="list_13_search" onKeyUp="search_list(this, '#list13');" />
                        </dt>
                    </dl>
                </div>
                <div class="dragWell" id="testrailvisible13" >
                    <div id="list13" class="dragleftinner droptrue">
    <?php
    if ($qryprdset->num_rows > 0) {
        while ($resmod = $qryprdset->fetch_assoc()) {
            extract($resmod);
            ?>
                                <div class="draglinkleft" id="list13_<?php echo $prdsetid; ?>" name="<?php echo $prdsetid; ?>">
                                    <div class="dragItemLable tooltip" id="<?php echo $prdsetid; ?>" title="<?php echo $prdsetname; ?>"><?php echo $prdsetname; ?></div>
                                    <div class="clickable" id="clck_<?php echo $prdsetid; ?>" onclick="fn_movealllistitems('list13', 'list14', 1, '<?php echo $prdsetid; ?>');"></div>
                                </div> 
            <?php
        }
    }
    ?>    
                    </div>
                </div>
                <div class="dragAllLink"  onclick="fn_movealllistitems('list13', 'list14', 0, 0);" style="cursor: pointer;cursor:hand;width:  153px;float: right; ">Add all Productsets.</div>
            </div>
        </div>
        <div class='six columns'>
            <div class="dragndropcol">
                <div class="dragtitle">Productsets Selected(<span id="rightprdset">0</span>)</div>
                <div class="draglinkleftSearch" id="s_list14" ><!-- search for right box of expedition -->
                    <dl class='field row'>
                        <dt class='text'>
                        <input placeholder='Search' type='text' id="list_14_search" name="list_14_search" onKeyUp="search_list(this, '#list14');" />
                        </dt>
                    </dl>
                </div>
                <div class="dragWell" id="testrailvisible14">
                    <div id="list14" class="dragleftinner droptrue">
    <?php
    if ($qryprdset->num_rows > 0) {
        while ($resassignedexp = $qryprdset->fetch_assoc()) {
            extract($resassignedexp);
            ?>
                                <div class="draglinkright" id="list14_<?php echo $prdsetid; ?>" name="<?php echo $prdsetid; ?>">                                                           
                                    <div class="dragItemLable tooltip" id="<?php echo $prdsetid; ?>" title="<?php echo $prdsetname; ?>"><?php echo $prdsetname; ?></div>
                                    <div class="clickable" id="clck_<?php echo $prdsetid; ?>" onclick="fn_movealllistitems('list13', 'list14', 1, '<?php echo $prdsetid; ?>');"></div>
                                </div>
            <?php
        }
    }
    ?>	
                    </div>
                </div>
                <div class="dragAllLink" onclick="fn_movealllistitems('list14', 'list13', 0, 0);" style="cursor: pointer;cursor:hand;width:  172px;float: right; ">Remove all Productset.</div>
            </div>
        </div>
    </div>
    <?php
}
if ($oper == "saveproduct" and $oper != " ") {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    $id = isset($method['id']) ? $method['id'] : 0;
    $pid = isset($method['pid']) ? $method['pid'] : 0;
    $proname = isset($method['proname']) ? $method['proname'] : '';
    $protiletype = isset($method['protiletype']) ? $method['protiletype'] : '0';
    $proversion = isset($method['proversion']) ? $method['proversion'] : '0';
    $assetname = isset($method['assetname']) ? $method['assetname'] : '';
    $assetid = isset($method['assetid']) ? $method['assetid'] : '';
    $list14 = isset($method['list14']) ? $method['list14'] : '';
    $list4 = isset($method['list4']) ? $method['list4'] : '';
    $list6 = isset($method['list6']) ? $method['list6'] : '';

    $product = explode(",", $list14);
    $grade = explode(",", $list4);
    $sub = explode(",", $list6);

    if ($id == '0') {

        $maxid = $ObjDB->NonQueryWithMaxValue("INSERT INTO itc_correlation_productdetails(fld_prd_type,fld_prd_name,fld_prd_version,fld_prd_asset_name,fld_asset_id,fld_created_date) 
					 VALUES('" . $protiletype . "', '" . $proname . "','" . $proversion . "','" . $assetname . "','" . $assetid . "','" . $date . "')");


        if ($sub != '') {
            for ($i = 0; $i < sizeof($sub); $i++) {

                $cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
														FROM itc_correlation_productmapping 
														WHERE fld_product_id='" . $maxid . "'  AND fld_subject_id='" . $sub[$i] . "'");
                if ($cnt == 0) {
                    $ObjDB->NonQuery("INSERT INTO itc_correlation_productmapping (fld_product_id,fld_subject_id, fld_created_by, fld_created_date)
											VALUES('" . $maxid . "','" . $sub[$i] . "','2','" . $date . "')");
                } else {
                    $ObjDB->NonQuery("UPDATE itc_correlation_productmapping 
											SET fld_delstatus='1',fld_updated_date = '" . $date . "' , fld_updated_by = '" . $uid . "' 
											WHERE fld_product_id='" . $maxid . "' AND fld_subject_id='" . $sub[$i] . "'");
                }
            }
        }
        if ($grade != '') {
            for ($i = 0; $i < sizeof($grade); $i++) {

                $cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
														FROM itc_correlation_productmapping 
														WHERE fld_product_id='" . $maxid . "'  AND fld_grade_id='" . $grade[$i] . "'");
                if ($cnt == 0) {
                    $ObjDB->NonQuery("INSERT INTO itc_correlation_productmapping (fld_product_id,fld_grade_id, fld_created_by, fld_created_date)
											VALUES('" . $maxid . "','" . $grade[$i] . "','2','" . $date . "')");
                } else {
                    $ObjDB->NonQuery("UPDATE itc_correlation_productmapping 
											SET  fld_delstatus='1',fld_updated_date = '" . $date . "' , fld_updated_by = '" . $uid . "' 
											WHERE fld_product_id='" . $maxid . "' AND fld_grade_id='" . $grade[$i] . "'");
                }
            }
        }
        if ($product != '') {
            for ($i = 0; $i < sizeof($product); $i++) {
                $cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) WHERE fld_product_id='" . $maxid . "'  AND fld_productset_id='" . $product[$i] . "'");
                if ($cnt == 0) {
                    $ObjDB->NonQuery("INSERT INTO itc_correlation_productmapping (fld_product_id,fld_productset_id, fld_created_by, fld_created_date)
											VALUES('" . $maxid . "','" . $product[$i] . "','2','" . $date . "')");
                } else {
                    $ObjDB->NonQuery("UPDATE itc_correlation_productmapping 
											SET fld_delstatus='1',fld_updated_date = '" . $date . "' , fld_updated_by = '" . $uid . "' 
											WHERE fld_product_id='" . $maxid . "' AND fld_productset_id='" . $product[$i] . "'");
                }
            }
        }
    } else {

//                    $maxid = $ObjDB->NonQueryWithMaxValue("INSERT INTO itc_correlation_productdetails(fld_prd_id,fld_prd_type,fld_prd_name,fld_prd_version,fld_prd_asset_name,fld_prd_asset_id,fld_created_date) 
//			echo		 VALUES('".$protiletype."','".$protiletype."', '".$proname."','".$proversion."','".$assetname."','".$assetid."','".$date."')");



        $ObjDB->NonQuery("UPDATE itc_correlation_productdetails
                                        SET fld_updated_date = '" . $date . "' ,  fld_prd_type = '" . $protiletype . "', fld_prd_name = '" . $proname . "', fld_prd_version = '" . $proversion . "' , fld_prd_asset_name = '" . $assetname . "', fld_asset_id = '" . $assetid . "'
                                        WHERE fld_id='" . $pid . "' ");
        $ObjDB->NonQuery("UPDATE itc_correlation_productmapping
                                        SET fld_delstatus='1' WHERE fld_product_id='" . $pid . "' ");
        if ($sub != '') {

            for ($i = 0; $i < sizeof($sub); $i++) {


                $cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
														FROM itc_correlation_productmapping 
														WHERE fld_product_id='" . $pid . "'  AND fld_subject_id='" . $sub[$i] . "'");
                if ($cnt == 0) {
                    $ObjDB->NonQuery("INSERT INTO itc_correlation_productmapping (fld_product_id,fld_subject_id, fld_created_by, fld_created_date)
											VALUES('" . $pid . "','" . $sub[$i] . "','2','" . $date . "')");
                } else {
                    $ObjDB->NonQuery("UPDATE itc_correlation_productmapping 
											SET fld_delstatus='0',fld_updated_date = '" . $date . "' , fld_updated_by = '" . $uid . "' 
											WHERE fld_product_id='" . $pid . "' AND fld_subject_id='" . $sub[$i] . "'");
                }
            }
        }
        if ($grade != '') {
            for ($i = 0; $i < sizeof($grade); $i++) {
                $cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
														FROM itc_correlation_productmapping 
														WHERE fld_product_id='" . $pid . "'  AND fld_grade_id='" . $grade[$i] . "'");
                if ($cnt == 0) {
                    $ObjDB->NonQuery("INSERT INTO itc_correlation_productmapping (fld_product_id,fld_grade_id, fld_created_by, fld_created_date)
											VALUES('" . $pid . "','" . $grade[$i] . "','2','" . $date . "')");
                } else {
                    $ObjDB->NonQuery("UPDATE itc_correlation_productmapping 
											SET fld_delstatus='0',fld_updated_date = '" . $date . "' , fld_updated_by = '" . $uid . "' 
											WHERE fld_product_id='" . $pid . "' AND fld_grade_id='" . $grade[$i] . "'");
                }
            }
        }
        if ($product != '') {
            for ($i = 0; $i < sizeof($product); $i++) {


                $cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
														FROM itc_correlation_productmapping 
														WHERE fld_product_id='" . $pid . "'  AND fld_productset_id='" . $product[$i] . "'");
                if ($cnt == 0) {
                    $ObjDB->NonQuery("INSERT INTO itc_correlation_productmapping (fld_product_id,fld_productset_id, fld_created_by, fld_created_date)
											VALUES('" . $pid . "','" . $product[$i] . "','2','" . $date . "')");
                } else {
                    $ObjDB->NonQuery("UPDATE itc_correlation_productmapping 
											SET fld_delstatus='0',fld_updated_date = '" . $date . "' , fld_updated_by = '" . $uid . "' 
											WHERE fld_product_id='" . $pid . "' AND fld_productset_id='" . $product[$i] . "'");
                }
            }
        }
    }
}
/* --- Load Student Dropdown --- */
if ($oper == "showproductdetail" and $oper != " ") {
    $prdtitletype = isset($method['titletype']) ? $method['titletype'] : '';
    //$id = isset($method['id']) ? $method['id'] : '';
    ?>
    <script>
        setTimeout('$("#mytable").treetable({ expandable: true, clickableNodeNames:true })', 3000);
    </script>

    <div class="row" id="showproduct">
        <div class='span10 offset1' id="tablecontents3">
            <table class='table table-hover table-striped table-bordered setbordertopradius' >
                <thead class='tableHeadText' >
                    <tr style="cursor:default;">
                        <th width="30%" class="span8">Product name</th>

                        <th class='centerText span2'>actions</th>
                    </tr>
                </thead>
                 <tbody>

                        <tr class="mainBtn" id="btntools-correlation-correlationtoolassetnew" name="0,0,0">
                            <td colspan="3" class="createnewtd"><span class="icon-synergy-create small-icon"></span>&nbsp;&nbsp;&nbsp;Create new product</td>               
                        </tr>
                    </tbody>
                </table>
            </div>
            <div>
				<table class='table table-hover table-striped table-bordered setbordertopradius' id="mytable" >
                    <tbody>

    <?php
    if ($prdtitletype != '0') {
        $qrytitle = $ObjDB->QueryObject("SELECT fld_id as tid,fld_title_name as protitle, fld_title_type as titletype FROM itc_correlation_producttitles WHERE
                                                                            fld_delstatus='0' AND fld_title_type='$prdtitletype'");
    } else {
        $qrytitle = $ObjDB->QueryObject("SELECT fld_id as tid,fld_title_name as protitle, fld_title_type as titletype FROM itc_correlation_producttitles WHERE
                                                                            fld_delstatus='0'");
    }

    if ($qrytitle->num_rows > 0) {
        $m = 0;
        while ($rowtitle = $qrytitle->fetch_assoc()) {
            $m++;
            extract($rowtitle);
            ?>
                            <tr data-tt-id="<?php echo $m; ?>">
                                    <td  width="30%" class="span8" ><?php echo $protitle; ?></td>
                                    <td class='centerText span2'>
										<div class="icon-synergy-edit  tooltip" id="btnstep" title="Edit"  style="float:left; font-size:18px;padding-right: 10px;" onclick="fn_newclass(<?php echo $tid; ?>,<?php echo $titletype; ?>)"></div>
                                        <div class="icon-synergy-trash tooltip" title="Delete" style="float:left; font-size:18px;padding-right: 10px;" onclick="fn_deleteproduct(<?php echo $tid; ?>,<?php echo $titletype; ?>)"></div>                               
                                    </td>
							</tr>
            <?php
            //echo ("SELECT fld_prd_name as proname, fld_prd_type as prdtype , fld_prd_version as prdversion
            //FROM itc_correlation_productdetails WHERE fld_prd_type='$titletype' AND fld_delstatus='0'");
            $qryprd = $ObjDB->QueryObject("SELECT fld_id as pid,fld_prd_name as proname, fld_prd_type as prdtype , fld_prd_version as prdversion
											FROM itc_correlation_productdetails WHERE fld_prd_type='$titletype' AND fld_delstatus='0' ");//limit 0,10
            if ($qryprd->num_rows > 0) {
                $n = 0;
                while ($rowprd = $qryprd->fetch_assoc()) {
                    $n++;
                    extract($rowprd);
                    ?>
                                  <tr data-tt-parent-id="<?php echo $m; ?>" data-tt-id="<?php echo $m . "." . $n; ?>" >
                                            <td width="30%" class="span8" ><?php echo $proname . " " . $prdversion; ?></td>
                                            <td class='centerText span2'>                                
                                                <div class="icon-synergy-view mainBtn tooltip" style="float:left; font-size:21px;padding-right: 10px;" id="btntools-correlation-viewproductdetail"  name="<?php echo $prdtype . "," . $pid; ?>" title="View"></div> 
                                                <div class="icon-synergy-edit mainBtn tooltip" id="btntools-correlation-correlationtoolassetnew" title="Edit" name="<?php echo $prdtype . "," . $pid . "," . 2; ?>" style="float:left; font-size:18px;padding-right: 10px;"></div>
                                                <div class="synbtn-promote mainBtn tooltip" id="btntools-correlation-correlationtoolassetnew" title="updateversion" name="<?php echo $prdtype . "," . $pid . "," . 3; ?>" style="float: left; padding-right: 10px; font-size: 142px; padding-top: 20px;"></div>
                                                <div class="icon-synergy-trash tooltip" title="Delete" style="float:left; font-size:18px;padding-right: 10px;" onclick="fn_deleteproduct(<?php echo $pid; ?>)"></div>                               
                                            </td>
								</tr>
                    <?php
                }
            }
            ?>

            <?php
        }
    }
    ?>


                </tbody>

            </table>


        </div>
    </div>
    <?php
}

/* --- Load Student Dropdown --- */

if ($oper == "deleteproduct" and $oper != " ") {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    try {

        $pid = isset($method['productid']) ? $method['productid'] : '';
        $tid = isset($method['titleid']) ? $method['titleid'] : '';
        $titletype = isset($method['titletype']) ? $method['titletype'] : '';

        $tcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_correlation_producttitles 
											  WHERE fld_id='" . $tid . "' 
											  AND fld_delstatus='0'");

        $pcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_correlation_productdetails 
											  WHERE fld_id='" . $pid . "' 
											  AND fld_delstatus='0'");

        if ($tcount == '1') {

            $ObjDB->NonQuery("UPDATE itc_correlation_producttitles 
							 SET fld_delstatus='1', fld_deleted_by='" . $uid . "', fld_deleted_date='" . date("Y-m-d H:i:s") . "'
							 WHERE fld_id='" . $tid . "'");
            $qryprd = $ObjDB->QueryObject("SELECT fld_id as pid FROM itc_correlation_productdetails WHERE fld_prd_type='$titletype' AND fld_delstatus='0' limit 0,10");
            if ($qryprd->num_rows > 0) {

                while ($rowprd = $qryprd->fetch_assoc()) {

                    extract($rowprd);

                    $ObjDB->NonQuery("UPDATE itc_correlation_productdetails 
							 SET fld_delstatus='1', fld_deleted_by='" . $uid . "', fld_deleted_date='" . date("Y-m-d H:i:s") . "'
							 WHERE fld_id='" . $pid . "'");

                    $ObjDB->NonQuery("UPDATE itc_correlation_productmapping 
							 SET fld_delstatus='1', fld_deleted_by='" . $uid . "', fld_deleted_date='" . date("Y-m-d H:i:s") . "'
							 WHERE fld_product_id='" . $pid . "'");
                }
            }
            echo "success";
        } elseif ($pcount = '1') {
            $ObjDB->NonQuery("UPDATE itc_correlation_productdetails 
							 SET fld_delstatus='1', fld_deleted_by='" . $uid . "', fld_deleted_date='" . date("Y-m-d H:i:s") . "'
							 WHERE fld_id='" . $pid . "'");

            $ObjDB->NonQuery("UPDATE itc_correlation_productmapping 
							 SET fld_delstatus='1', fld_deleted_by='" . $uid . "', fld_deleted_date='" . date("Y-m-d H:i:s") . "'
							 WHERE fld_product_id='" . $pid . "'");
            echo "success";
        } else {
            echo "exists";
        }
    } catch (Exception $e) {
        echo "fail";
    }
}

/* * **********Expedition code start here************* */

/*if ($oper == "showdestination" and $oper != " ") {
    $expeid = isset($method['expid']) ? $method['expid'] : 0;

    $expids = explode('_', $expeid);
    ?>
    <script type="text/javascript" language="javascript">
        $(function() {
            $('#testrailvisible0').slimscroll({
                width: '410px',
                height: '366px',
                size: '3px',
                railVisible: true,
                allowPageScroll: false,
                railColor: '#F4F4F4',
                opacity: 1,
                color: '#d9d9d9',
                wheelStep: 1,
            });
            $('#testrailvisible1').slimscroll({
                width: '410px',
                height: '366px',
                size: '3px',
                railVisible: true,
                allowPageScroll: false,
                railColor: '#F4F4F4',
                opacity: 1,
                color: '#d9d9d9',
                wheelStep: 1,
            });
            $("#list9").sortable({
                connectWith: ".droptrue1",
                dropOnEmpty: true,
                items: "div[class='draglinkleft']",
                receive: function(event, ui) {
                    $("div[class=draglinkright]").each(function() {
                        if ($(this).parent().attr('id') == 'list9') {
                            fn_movealllistitems('list9', 'list10', $(this).children(":first").attr('id'));
                        }
                    });
                }
            });

            $("#list10").sortable({
                connectWith: ".droptrue1",
                dropOnEmpty: true,
                receive: function(event, ui) {
                    $("div[class=draglinkleft]").each(function() {
                        if ($(this).parent().attr('id') == 'list10') {
                            fn_movealllistitems('list9', 'list10', $(this).children(":first").attr('id'));
                        }
                    });
                }
            });

            //$( "#list9,#list10" ).disableSelection();
        });
    </script>  

    <div class="row rowspacer" id="studentlist">
        <div class='six columns'>
            <div class="dragndropcol">
    <?php
    $qrydest = $ObjDB->QueryObject("SELECT fld_id AS destid, fld_dest_name AS destname, fn_shortname (CONCAT(fld_dest_name), 2)AS shortname FROM itc_exp_destination_master WHERE fld_exp_id='" . $expids[0] . "' AND fld_delstatus='0' GROUP BY destid ORDER BY fld_order");
    ?>
                <div class="dragtitle">Destinations available</div>
                <div class="draglinkleftSearch" id="s_list9" >
                    <dl class='field row'>   
                        <dt class='text'>
                        <input placeholder='Search' type='text' id="list_9_search" name="list_9_search" onKeyUp="search_list(this, '#list9');" />
                        </dt>
                    </dl>
                </div>
                <div class="dragWell" id="testrailvisible0" >
                    <div id="list9" class="dragleftinner droptrue1">
    <?php
    if ($qrydest->num_rows > 0) {
        while ($rowsdest = $qrydest->fetch_assoc()) {
            extract($rowsdest);
            ?>
                                <div class="draglinkleft" id="list9_<?php echo $destid; ?>" >
                                    <div class="dragItemLable tooltip" id="<?php echo $destid; ?>" title="<?php echo $destname; ?>"><?php echo $shortname; ?></div>
                                    <div class="clickable" id="clck_<?php echo $destid; ?>" onclick="fn_movealllistitems('list9', 'list10',<?php echo $expids[0]; ?>,<?php echo $destid; ?>);"></div>
                                </div>
            <?php
        }
    }
    ?>
                    </div>
                </div>
                <div class="dragAllLink"  onclick="fn_movealllistitems('list9', 'list10', 0, 0);" style="cursor: pointer;cursor:hand;width:  137px;float: right;">add all destinations</div>
            </div>
        </div>
        <div class='six columns'>
            <div class="dragndropcol">
                <div class="dragtitle">Selected Destinations</div>
                <div class="draglinkleftSearch" id="s_list10" >
                    <dl class='field row'>
                        <dt class='text'>
                        <input placeholder='Search' type='text' id="list_10_search" name="list_10_search" onKeyUp="search_list(this, '#list10');" />
                        </dt>
                    </dl>  
                </div>
                <div class="dragWell" id="testrailvisible1" >
                    <div id="list10" class="dragleftinner droptrue1">
    <?php
    if ($qrystudent->num_rows > 0) {
        while ($rowsstudent = $qrystudent->fetch_assoc()) {
            extract($rowsstudent);
            ?>
                                <div class="draglinkright" id="list10_<?php echo $destid; ?>" >
                                    <div class="dragItemLable tooltip" id="<?php echo $destid; ?>" title="<?php echo $destname; ?>"><?php echo $shortname; ?></div>
                                    <div class="clickable" id="clck_<?php echo $destid; ?>" onclick="fn_movealllistitems('list9', 'list10',<?php echo $expids[0]; ?>,<?php echo $destid; ?>);"></div>
                                </div>
            <?php
        }
    }
    ?>
                    </div>
                </div>
                <div class="dragAllLink"  onclick="fn_movealllistitems('list10', 'list9', 0, 0);" style="cursor: pointer;cursor:hand;width:  172px;float: right;">remove all destinations</div>

            </div>
        </div>
    </div>  


    <?php
}



if ($oper == "showtasks" and $oper != " ") {
    $destidsall = isset($method['destids']) ? $method['destids'] : '';

    $destid = explode(',', $destidsall);
    ?>
    <script type="text/javascript" language="javascript">
        $(function() {
            $('#testrailvisible2').slimscroll({
                width: '410px',
                height: '366px',
                size: '3px',
                railVisible: true,
                allowPageScroll: false,
                railColor: '#F4F4F4',
                opacity: 1,
                color: '#d9d9d9',
                wheelStep: 1,
            });
            $('#testrailvisible3').slimscroll({
                width: '410px',
                height: '366px',
                size: '3px',
                railVisible: true,
                allowPageScroll: false,
                railColor: '#F4F4F4',
                opacity: 1,
                color: '#d9d9d9',
                wheelStep: 1,
            });
            $("#list11").sortable({
                connectWith: ".droptrue2",
                dropOnEmpty: true,
                items: "div[class='draglinkleft']",
                receive: function(event, ui) {
                    $("div[class=draglinkright]").each(function() {
                        if ($(this).parent().attr('id') == 'list11') {
                            fn_movealllistitems('list11', 'list12', $(this).children(":first").attr('id'));
                        }
                    });
                }
            });

            $("#list12").sortable({
                connectWith: ".droptrue2",
                dropOnEmpty: true,
                receive: function(event, ui) {
                    $("div[class=draglinkleft]").each(function() {
                        if ($(this).parent().attr('id') == 'list12') {
                            fn_movealllistitems('list11', 'list12', $(this).children(":first").attr('id'));
                        }
                    });
                }
            });

            //$( "#list9,#list10" ).disableSelection();
        });
    </script>  
    <div class='six columns'>
        <div class="dragndropcol">
            <div class="dragtitle">Tasks available</div>
            <div class="draglinkleftSearch" id="s_list11" >
                <dl class='field row'>   
                    <dt class='text'>
                    <input placeholder='Search' type='text' id="list_11_search" name="list_11_search" onKeyUp="search_list(this, '#list11');" />
                    </dt>
                </dl>
            </div>
            <div class="dragWell" id="testrailvisible2" >
                <div id="list11" class="dragleftinner droptrue2">
    <?php
    for ($i = 0; $i < sizeof($destid); $i++) {
        $qrystudent = $ObjDB->QueryObject("SELECT fld_id AS taskid, fld_task_name AS taskname,fn_shortname (CONCAT(fld_task_name), 2) 
                                                               AS shortname
                                                               FROM itc_exp_task_master
                                                               WHERE fld_dest_id='" . $destid[$i] . "' AND fld_flag='1' AND fld_delstatus='0' ORDER BY fld_order");
        if ($qrystudent->num_rows > 0) {
            while ($rowsstudent = $qrystudent->fetch_assoc()) {
                extract($rowsstudent);
                ?>
                                <div class="draglinkleft" id="list11_<?php echo $taskid; ?>" >
                                    <div class="dragItemLable tooltip" id="<?php echo $taskid; ?>" title="<?php echo $taskname; ?>"><?php echo $shortname; ?></div>
                                    <div class="clickable" id="clck_<?php echo $taskid; ?>" onclick="fn_movealllistitems('list11', 'list12',<?php echo $destid[$i]; ?>,<?php echo $taskid; ?>);"></div>
                                </div>
                <?php
            }
        }
    }
    ?>
                </div>
            </div>
            <div class="dragAllLink"  onclick="fn_movealllistitems('list11', 'list12', 0, 0);" style="cursor: pointer;cursor:hand;width:  137px;float: right;">add all Tasks</div>
        </div>
    </div>
    <div class='six columns'>
        <div class="dragndropcol">
            <div class="dragtitle">Selected Tasks</div>
            <div class="draglinkleftSearch" id="s_list12" >
                <dl class='field row'>
                    <dt class='text'>
                    <input placeholder='Search' type='text' id="list_12_search" name="list_12_search" onKeyUp="search_list(this, '#list12');" />
                    </dt>
                </dl>  
            </div>
            <div class="dragWell" id="testrailvisible3" >
                <div id="list12" class="dragleftinner droptrue2">
    <?php
    if ($qrystudent->num_rows > 0) {
        while ($rowsstudent = $qrystudent->fetch_assoc()) {
            extract($rowsstudent);
            ?>
                            <div class="draglinkright" id="list12_<?php echo $taskid; ?>" >
                                <div class="dragItemLable tooltip" id="<?php echo $taskid; ?>" title="<?php echo $taskname; ?>"><?php echo $shortname; ?></div>
                                <div class="clickable" id="clck_<?php echo $taskid; ?>" onclick="fn_movealllistitems('list11', 'list12',<?php echo $destid[$i]; ?>,<?php echo $taskid; ?>);"></div>
                            </div>
            <?php
        }
    }
    ?>
                </div>
            </div>
            <div class="dragAllLink"  onclick="fn_movealllistitems('list12', 'list11', 0, 0);" style="cursor: pointer;cursor:hand;width:  172px;float: right;">remove all Tasks</div>

        </div>
    </div>

    <?php
}



if ($oper == "showresource" and $oper != " ") {
    $taskidall = isset($method['taskids']) ? $method['taskids'] : '';
    $taskid = explode(',', $taskidall);
    ?>
    <script type="text/javascript" language="javascript">
        $(function() {
            $('#testrailvisible4').slimscroll({
                width: '410px',
                height: '366px',
                size: '3px',
                railVisible: true,
                allowPageScroll: false,
                railColor: '#F4F4F4',
                opacity: 1,
                color: '#d9d9d9',
                wheelStep: 1,
            });
            $('#testrailvisible5').slimscroll({
                width: '410px',
                height: '366px',
                size: '3px',
                railVisible: true,
                allowPageScroll: false,
                railColor: '#F4F4F4',
                opacity: 1,
                color: '#d9d9d9',
                wheelStep: 1,
            });
            $("#list13").sortable({
                connectWith: ".droptrue3",
                dropOnEmpty: true,
                items: "div[class='draglinkleft']",
                receive: function(event, ui) {
                    $("div[class=draglinkright]").each(function() {
                        if ($(this).parent().attr('id') == 'list13') {
                            fn_movealllistitems('list13', 'list14', $(this).children(":first").attr('id'));
                        }
                    });
                }
            });

            $("#list14").sortable({
                connectWith: ".droptrue3",
                dropOnEmpty: true,
                receive: function(event, ui) {
                    $("div[class=draglinkleft]").each(function() {
                        if ($(this).parent().attr('id') == 'list14') {
                            fn_movealllistitems('list14', 'list13', $(this).children(":first").attr('id'));
                        }
                    });
                }
            });

            //$( "#list9,#list10" ).disableSelection();
        });
    </script>  

    <div class='six columns'>
        <div class="dragndropcol">
            <div class="dragtitle">Resources available</div>
            <div class="draglinkleftSearch" id="s_list13" >
                <dl class='field row'>   
                    <dt class='text'>
                    <input placeholder='Search' type='text' id="list_13_search" name="list_13_search" onKeyUp="search_list(this, '#list13');" />
                    </dt>
                </dl>
            </div>
            <div class="dragWell" id="testrailvisible4" >
                <div id="list13" class="dragleftinner droptrue3">
    <?php
    for ($j = 0; $j < sizeof($taskid); $j++) {
        $qrycount = $ObjDB->SelectSingleValue("SELECT COUNT(a.fld_id) FROM itc_exp_resource_master AS a 
                                                                            LEFT JOIN itc_exp_res_status AS b on a.fld_id = b.fld_res_id                                                      
                                                                            WHERE a.fld_task_id='" . $taskid[$j] . "' AND a.fld_flag='1' AND a.fld_delstatus='0' AND b.fld_school_id = '" . $schoolid . "' AND b.fld_created_by='" . $uid . "' ORDER BY a.fld_order");

        if ($qrycount != 0) {

            $qrystudent = $ObjDB->QueryObject("SELECT a.fld_id AS resoid, a.fld_res_name AS resoname, fn_shortname (CONCAT(a.fld_res_name), 2) AS shortname FROM itc_exp_resource_master AS a 
                                                                           LEFT JOIN itc_exp_res_status AS b on a.fld_id = b.fld_res_id                                                      
                                                                           WHERE a.fld_task_id='" . $taskid[$j] . "' AND a.fld_flag='1' AND a.fld_delstatus='0' AND b.fld_status IN (1,2) AND b.fld_school_id = '" . $schoolid . "' AND b.fld_created_by='" . $uid . "' ORDER BY a.fld_order");
        } else {

            $qrystudent = $ObjDB->QueryObject("SELECT a.fld_id AS resoid, a.fld_res_name AS resoname, fn_shortname (CONCAT(a.fld_res_name), 2) AS shortname FROM itc_exp_resource_master AS a 
                                                                           LEFT JOIN itc_exp_res_status AS b on a.fld_id = b.fld_res_id                                                      
                                                                           WHERE a.fld_task_id='" . $taskid[$j] . "' AND a.fld_flag='1' AND a.fld_delstatus='0' AND b.fld_status IN (1,2) AND b.fld_school_id = '0' ORDER BY a.fld_order");
        }

        if ($qrystudent->num_rows > 0) {
            while ($rowsstudent = $qrystudent->fetch_assoc()) {
                extract($rowsstudent);
                ?>
                                <div class="draglinkleft" id="list13_<?php echo $resoid; ?>" >
                                    <div class="dragItemLable tooltip" id="<?php echo $resoid; ?>" title="<?php echo $resoname; ?>"><?php echo $shortname; ?></div>
                                    <div class="clickable" id="clck_<?php echo $resoid; ?>" onclick="fn_movealllistitems('list13', 'list14',<?php echo $taskid[$j]; ?>,<?php echo $resoid; ?>);"></div>
                                </div>
                <?php
            }
        }
    }
    ?>
                </div>
            </div>
            <div class="dragAllLink"  onclick="fn_movealllistitems('list13', 'list14', 0, 0);" style="cursor: pointer;cursor:hand;width:  137px;float: right;">add all Resources</div>
        </div>
    </div>
    <div class='six columns'>
        <div class="dragndropcol">
            <div class="dragtitle">Selected Resources</div>
            <div class="draglinkleftSearch" id="s_list14" >
                <dl class='field row'>
                    <dt class='text'>
                    <input placeholder='Search' type='text' id="list_14_search" name="list_12_search" onKeyUp="search_list(this, '#list14');" />
                    </dt>
                </dl>  
            </div>
            <div class="dragWell" id="testrailvisible5" >
                <div id="list14" class="dragleftinner droptrue3">
    <?php
    if ($qrystudent->num_rows > 0) {
        while ($rowsstudent = $qrystudent->fetch_assoc()) {
            extract($rowsstudent);
            ?>
                            <div class="draglinkright" id="list14_<?php echo $resoid; ?>" >
                                <div class="dragItemLable tooltip" id="<?php echo $resoid; ?>" title="<?php echo $resoname; ?>"><?php echo $shortname; ?></div>
                                <div class="clickable" id="clck_<?php echo $resoid; ?>" onclick="fn_movealllistitems('list13', 'list14',<?php echo $taskid[$j]; ?>,<?php echo $resoid; ?>);"></div>
                            </div>
            <?php
        }
    }
    ?>
                </div>
            </div>
            <div class="dragAllLink"  onclick="fn_movealllistitems('list14', 'list13', 0, 0);" style="cursor: pointer;cursor:hand;width:  172px;float: right;">remove all Resources</div>

        </div>
    </div>


                    <?php
                }*/



                /*                 * **********Expedition code start here************* */
