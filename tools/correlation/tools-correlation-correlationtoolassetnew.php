<?php
@include("sessioncheck.php");

error_reporting(E_ALL);
ini_set('display_errors', '1');
/*
  Created By - MOhan. M
 */

$id = isset($method['id']) ? $method['id'] : '0';
$id = explode(",", $id);
if ($id[0] != '0') {
    $qrydetails = $ObjDB->QueryObject("SELECT fld_id as prdid ,fld_prd_type as ptype,fld_prd_name as pname,fld_prd_version as pversion,fld_prd_asset_name as assetname,fld_asset_id as assetid FROM itc_correlation_productdetails WHERE fld_id='" . $id[1] . "' AND  fld_delstatus='0' ");
    $row = $qrydetails->fetch_assoc();
    extract($row);
}
//
//$id[1] = 0 - Edit/Create Type

$sheetid = $id[0];
?>
<section data-type='2home' id='tools-correlation-correlationtoolassetnew'>
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

            $("#list3").sortable({/*------- Modules Left Box ------*/
                connectWith: ".droptrue",
                dropOnEmpty: true,
                items: "div[class='draglinkleft']",
                receive: function(event, ui) {
                    $("div[class='draglinkright']").each(function() {
                        if ($(this).parent().attr('id') == 'list3') {
                            fn_movealllistitems('list3', 'list4', 1, $(this).children(":first").attr('id'));
                        }
                    });
                }
            });

            $("#list4").sortable({/*------- Modules Right Box ------*/
                connectWith: ".droptrue",
                dropOnEmpty: true,
                receive: function(event, ui) {
                    $("div[class='draglinkleft']").each(function() {
                        if ($(this).parent().attr('id') == 'list4') {
                            fn_movealllistitems('list3', 'list4', 1, $(this).children(":first").attr('id'));
                        }
                    });
                }
            });

            $("#list5").sortable({/*------- Units Left Box ------*/
                connectWith: ".droptrue",
                dropOnEmpty: true,
                items: "div[class='draglinkleft']",
                receive: function(event, ui) {
                    $("div[class='draglinkright']").each(function() {
                        if ($(this).parent().attr('id') == 'list5') {
                            fn_movealllistitems('list5', 'list6', 1, $(this).children(":first").attr('id'));
                        }
                    });
                }
            });

            $("#list6").sortable({/*------- Units Right Box ------*/
                connectWith: ".droptrue",
                dropOnEmpty: true,
                receive: function(event, ui) {
                    $("div[class='draglinkleft']").each(function() {
                        if ($(this).parent().attr('id') == 'list6') {
                            fn_movealllistitems('list5', 'list6', 1, $(this).children(":first").attr('id'));
                        }
                    });
                }
            });


            $("#list13").sortable({/*------- expedition left Box ------*/
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

            $("#list14").sortable({/*------- expedition right Box ------*/
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
    <div class='container'>
        <!--Load the Module Name / New module-->
        <div class='row'>
            <div class='twelve columns'>
                <p class="dialogTitle"><?php
					if ($id[2] == '2')
					{
				 		echo "Update Product";
					} 
                    elseif ($id[2] == '3') 
					{
                        echo "Update Version";
                    } 
					else 
					{
                        echo "New Product";
                    }
                    ?></p>
                <p class="dialogSubTitle">&nbsp;</p>
            </div>
        </div>

        <!--Load the Module Form-->

        <div class='row formBase'>
            <div class='eleven columns centered insideForm'>
                <form name="assetsforms" id="assetsforms">
                    <div class='row'>
                        <div class='twelve columns'>

                            Select Title<span class="fldreq">*</span>

                            <strong>
                                <span  id="btnstep"  style="cursor:pointer;" onclick="fn_newclass(0);"  class="right">+Add Product Title</span>
                            </strong>

                            <div class='field row' id="classnameload">
                                <dt class='dropdown'>
                                <div class="selectbox">
                                    <input type="hidden" name="selectui" class="required" id="selectui" value="<?php echo $ptype; ?>" >
                                    <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                        <?php
                                        $ptitle = $ObjDB->SelectSingleValue("SELECT fld_title_name
                                                                                            FROM itc_correlation_producttitles
                                                                                            WHERE fld_title_type='" . $id[0] . "' AND  fld_delstatus='0'");

                                        if ($id[0] != '0') {
                                            ?>

                                            <span class="selectbox-option input-medium" data-option="" style="width:97%;"><?php echo $ptitle; ?></span>
                                            <?php
                                        } else {
                                            ?>
                                            <span class="selectbox-option input-medium" data-option="" style="width:97%;">Select</span>
                                            <?php
                                        }
                                        ?>
                                        <b class="caret1"></b>
                                    </a>
                                    <div class="selectbox-options" style="min-width: 100%;">
                                        <input type="text" class="selectbox-filter" placeholder="Search class" style="min-width: 97%;" >
                                        <ul role="options" >
                                            <?php
                                            $stateqry = $ObjDB->QueryObject("SELECT fld_title_name AS ptitle,fld_id AS titleid,fld_title_type AS titletype FROM itc_correlation_producttitles
                                                                                                                                                WHERE fld_delstatus='0'
                                                                                                                                                ORDER BY fld_title_name ASC");
                                            while ($rowstate = $stateqry->fetch_assoc()) {
                                                extract($rowstate);
                                                ?>
                                                <li><a href="#" id="producttitle" data-option="<?php echo $titletype; ?>"><?php echo $ptitle; ?></a></li>

                                            <?php }
                                            ?>
                                        </ul>
                                    </div>
                                </div>
                                </dt>
                            </div>
                        </div>

                    </div>
                    <div class='row'>
                        <div class='six columns' >
                            Product Name<span class="fldreq">*</span>
                            <dl class='field row'>
                                <dt class='text'>
                                <?php
                                if ($id[0] != '0') {
                                    ?>

                                    <input type='text' id="productname" name="productname" value="<?php echo $pname; ?>" />
                                    <?php
                                } else {
                                    ?>
                                    <input placeholder='Product Name' type='text' id="productname" name="productname" />
                                    <?php
                                }
                                ?>

                                </dt>
                            </dl>

                        </div>
                        <div class='six columns'>
                            Version<span class="fldreq">*</span>
                            <dl class='field row'>
                                <dt class='text'>
                                <?php
                                if ($id[0] != '0') {
                                    ?>
                                    <input  type='text' id="productversion" name="productversion" value="<?php echo $pversion; ?>" />
                                    <?php
                                } else {
                                    ?>
                                    <input placeholder='product version' type='text' id="productversion" name="productversion" />
                                    <?php
                                }
                                ?>
                                </dt>
                            </dl>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='six columns'>
                            Asset Name<span class="fldreq">*</span>
                            <dl class='field row'>
                                <dt class='text'>
                                <?php
                                if ($id[0] != '0') {
                                    ?>
                                    <input type='text' id="assetname" name="assetname" value="<?php echo $assetname; ?>" />
                                    <?php
                                } else {
                                    ?>
                                    <input placeholder='Asset Name' type='text' id="assetname" name="assetname" />
                                    <?php
                                }
                                ?>
                                </dt>
                            </dl>
                        </div>
                        <div class='six columns'>
                            Asset ID<span class="fldreq">*</span>
                            <dl class='field row'>
                                <dt class='text'>
                                <?php
                                if ($id[0] != '0') {
                                    ?>
                                    <input  type='text' name="assetid" id="assetid" value="<?php echo $assetid; ?>" />
                                    <?php
                                } else {
                                    ?>
                                    <input placeholder='AssetID' type='text' id="assetid" name="assetid" />
                                    <?php
                                }
                                ?>
                                </dt>
                            </dl>
                        </div>
                    </div>
                    <div class='row rowspacer' id="units"> <!-- Subject-->
                        <div class='six columns'>
                            <div class="dragndropcol">
                                <?php
                                $qrysub = $ObjDB->QueryObject("SELECT a.fld_id as subid, a.fld_subject_name as subname from itc_correlation_productsubject as a
                                                                                        WHERE a.fld_id NOT IN(SELECT fld_subject_id FROM itc_correlation_productmapping WHERE fld_product_id='" . $id[1] . "'
                                                                                         AND fld_delstatus='0')
                                                                                        AND a.fld_delstatus='0'
                                                                                        ORDER BY subname ASC");
                                ?>
                                <div class="dragtitle">Subject available (<span id="leftsub"><?php echo $qrysub->num_rows; ?></span>)</div>
                                <div class="draglinkleftSearch" id="s_list5" >

                                    <dl class='field row'>
                                        <dt class='text'>
                                        <input placeholder='Search' type='text' id="list_5_search" name="list_5_search" onKeyUp="search_list(this, '#list5');" />
                                        </dt>
                                    </dl>
                                </div>
                                <div class="dragWell" id="testrailvisible5" >
                                    <div id="list5" class="dragleftinner droptrue">
                                        <?php
                                        if ($qrysub->num_rows > 0) {
                                            while ($resunit = $qrysub->fetch_assoc()) {
                                                extract($resunit);
                                                ?>
                                                <div class="draglinkleft" id="list5_<?php echo $subid; ?>" >
                                                    <div class="dragItemLable" id="<?php echo $subid; ?>"><?php echo $subname; ?></div>
                                                    <div class="clickable" id="clck_<?php echo $subid; ?>" onclick="fn_movealllistitems('list5', 'list6', 1,<?php echo $subid; ?>);"></div>
                                                </div>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="dragAllLink"  onclick="fn_movealllistitems('list5', 'list6', 0, 0);" style="cursor: pointer;cursor:hand;width:  123px;float: right;margin-top:5px; ">Add all Subject.</div>
                            </div>
                        </div>
                        <div class='six columns'>
                            <div class="dragndropcol">
                                <?php
                                $qrysubselect = $ObjDB->QueryObject("SELECT a.fld_id as subid, a.fld_subject_name as subname from itc_correlation_productsubject as a
                                                                                        WHERE a.fld_id IN(SELECT fld_subject_id FROM itc_correlation_productmapping WHERE fld_product_id='" . $id[1] . "'
                                                                                         AND fld_delstatus='0')
                                                                                        AND a.fld_delstatus='0'
                                                                                        ORDER BY subname ASC");
                                ?>
                                <div class="dragtitle">Subject selected (<span id="rightsubject"><?php echo $qrysubselect->num_rows; ?></span>)</div>
                                <div class="draglinkleftSearch" id="s_list6" >
                                    <dl class='field row'>
                                        <dt class='text'>
                                        <input placeholder='Search' type='text' id="list_6_search" name="list_6_search" onKeyUp="search_list(this, '#list6');" />
                                        </dt>
                                    </dl>
                                </div>
                                <div class="dragWell" id="testrailvisible6">
                                    <div id="list6" class="dragleftinner droptrue">
                                        <?php
                                        if ($qrysubselect->num_rows > 0) {
                                            while ($resassignedexp = $qrysubselect->fetch_assoc()) {
                                                extract($resassignedexp);
                                                ?>
                                                <div class="draglinkright" id="list6_<?php echo $subid; ?>">
                                                    <div class="dragItemLable" id="<?php echo $subid; ?>"><?php echo $subname; ?></div>
                                                    <div class="clickable" id="clck_<?php echo $subid; ?>" onclick="fn_movealllistitems('list5', 'list6', 1,<?php echo $subid; ?>);"></div>
                                                </div>
                                                <?php
                                            }
                                        }
                                        ?>

                                    </div>
                                </div>
                                <div class="dragAllLink" onclick="fn_movealllistitems('list6', 'list5', 0, 0);" style="cursor: pointer;cursor:hand;width:  150px;float: right;margin-top:5px; ">Remove all Subject.</div>
                            </div>
                        </div>
                    </div><!-- End subject-->
                    <div class='row rowspacer'> <!-- Shows Grade list to select-->
                        <div class='six columns'>
                            <div class="dragndropcol">
                                <?php
//$qrygrade = $ObjDB->QueryObject("SELECT fld_id as gradeid, fld_grade_name as gradename  FROM itc_correlation_grades where fld_gradeout_flag=0");

                                $qrygrade = $ObjDB->QueryObject("SELECT a.fld_id as gradeid, a.fld_grade_name as gradename  FROM itc_correlation_productgrade as a
                                                                                        WHERE a.fld_id NOT IN(SELECT fld_grade_id FROM itc_correlation_productmapping WHERE fld_product_id='" . $id[1] . "'
                                                                                         AND fld_delstatus='0')
                                                                                        AND a.fld_delstatus='0'
                                                                                        ORDER BY gradeid ASC");
                                ?>
                                <div class="dragtitle">Grade available(<span id="leftgrade"><?php echo $qrygrade->num_rows; ?></span>)</div>
                                <div class="draglinkleftSearch" id="s_list3" >
                                    <dl class='field row'>
                                        <dt class='text'>
                                        <input placeholder='Search' type='text' id="list_3_search" name="list_3_search" onKeyUp="search_list(this, '#list3');" />
                                        </dt>
                                    </dl>
                                </div>
                                <div class="dragWell" id="testrailvisible3" >
                                    <div id="list3" class="dragleftinner droptrue">
                                        <?php
                                        if ($qrygrade->num_rows > 0) {
                                            while ($resmod = $qrygrade->fetch_assoc()) {
                                                extract($resmod);
                                                ?>

                                                <div class="draglinkleft" id="list3_<?php echo $gradeid; ?>" name="<?php echo $gradeid; ?>">
                                                    <div class="dragItemLable tooltip" id="<?php echo $gradeid; ?>" title="<?php echo $gradename; ?>"><?php echo $gradename; ?></div>
                                                    <div class="clickable" id="clck_<?php echo $gradeid; ?>" onclick="fn_movealllistitems('list3', 'list4', 1, '<?php echo $gradeid; ?>');"></div>
                                                </div>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="dragAllLink"  onclick="fn_movealllistitems('list3', 'list4', 0, 0);" style="cursor: pointer;cursor:hand;width: 120px;float: right;margin-top:5px; ">Add all Grades.</div>
                            </div>
                        </div>
                        <div class='six columns'>
                            <div class="dragndropcol">
                                <?php
                                $qrygradeselect = $ObjDB->QueryObject("SELECT a.fld_id as gradeid, a.fld_grade_name as gradename  FROM itc_correlation_productgrade as a
                                                                                        WHERE a.fld_id IN(SELECT fld_grade_id FROM itc_correlation_productmapping WHERE fld_product_id='" . $id[1] . "'
                                                                                         AND fld_delstatus='0')
                                                                                        AND a.fld_delstatus='0'
                                                                                        ORDER BY gradeid ASC");
                                ?>
                                <div class="dragtitle">Grade Selected(<span id="rightgrade"><?php echo $qrygradeselect->num_rows; ?></span>)</div>
                                <div class="draglinkleftSearch" id="s_list4" >
                                    <dl class='field row'>
                                        <dt class='text'>
                                        <input placeholder='Search' type='text' id="list_4_search" name="list_4_search" onKeyUp="search_list(this, '#list4');" />
                                        </dt>
                                    </dl>
                                </div>
                                <div class="dragWell" id="testrailvisible4">
                                    <div id="list4" class="dragleftinner droptrue">
                                        <?php
                                        if ($qrygradeselect->num_rows > 0) {
                                            while ($resassignedmod = $qrygradeselect->fetch_assoc()) {
                                                extract($resassignedmod);
                                                ?>
                                                <div class="draglinkright<?php //if(empty($dimmodule)) { echo ' dim'; }  ?>" id="list4_<?php echo $gradeid; ?>" name="<?php echo $gradeid; ?>">
                                                    <div class="dragItemLable tooltip" id="<?php echo $gradeid; ?>" title="<?php echo $gradename; ?>"><?php echo $gradename; ?></div>
                                                    <div class="clickable" id="clck_<?php echo $gradeid; ?>" onclick="fn_movealllistitems('list3', 'list4', 1, '<?php echo $gradeid; ?>');"></div>
                                                </div>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="dragAllLink" onclick="fn_movealllistitems('list4', 'list3', 0, 0);" style="cursor: pointer;cursor:hand;width:  153px;float: right;margin-top:5px; ">Remove all Grades.</div>
                            </div>
                        </div>
                    </div>
                    <div class='row rowspacer' id='productsetnameload'> <!-- Shows Productset list to select-->
                        <div class='six columns'>
                            <div class="dragndropcol">
                                <?php
                                $qryprdset = $ObjDB->QueryObject("SELECT a.fld_id as prdsetid, a.fld_productset_name as prdsetname  FROM itc_correlation_productset as a
                                                                                        WHERE a.fld_id NOT IN(SELECT fld_productset_id FROM itc_correlation_productmapping WHERE fld_product_id='" . $id[1] . "'
                                                                                         AND fld_delstatus='0')
                                                                                        AND a.fld_delstatus='0'
                                                                                        ORDER BY prdsetname ASC");
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
                                <div class="dragAllLink"  onclick="fn_movealllistitems('list13', 'list14', 0, 0);" style="cursor: pointer;cursor:hand;width:  153px;float: right;margin-top:5px; ">Add all Productsets.</div>
                            </div>
                        </div>
                        <div class='six columns'>
                            <div class="dragndropcol">
                                <?php
                                $qryproductsetselect = $ObjDB->QueryObject("SELECT a.fld_id as prdsetid, a.fld_productset_name as prdsetname  FROM itc_correlation_productset as a
                                                                                        WHERE a.fld_id IN(SELECT fld_productset_id FROM itc_correlation_productmapping WHERE fld_product_id='" . $id[1] . "'
                                                                                         AND fld_delstatus='0')
                                                                                        AND a.fld_delstatus='0'
                                                                                        ORDER BY prdsetname ASC");
                                ?>

                                <div class="dragtitle">Productsets Selected(<span id="rightprdset"><?php echo $qryproductsetselect->num_rows; ?></span>)</div>
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
                                        if ($qryproductsetselect->num_rows > 0) {
                                            while ($resassignedexp = $qryproductsetselect->fetch_assoc()) {
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
                                <div class="dragAllLink" onclick="fn_movealllistitems('list14', 'list13', 0, 0);" style="cursor: pointer;cursor:hand;width:  172px;float: right;margin-top:5px; ">Remove all Productset.</div>
                            </div>
                        </div>
                    </div>
					<!--<div class='row rowspacer'>--> <!-- Tag Well -->
                    	<!--<div class='twelve columns'>
                        	To create new tag, type a name and press Enter.
                            <div class="tag_well">
                                <input type="text" name="form_tags_license" value="" id="form_tags_license" />
                            </div>
                        </div>
                    </div> -->
                    <div class='row rowspacer'>
                        <div class='twelve columns'>
                            <?php
                            if ($id[2] == '2')
                                {
                                ?>
                                <input class="darkButton" type="button" id="btnstep2" style="float: right; height: 34px;  width: 170px;" value="Update" onClick="fn_saveproduct(1,<?php echo $id[1]; ?>);" />
                                <?php
                                } 
                            else {
                                ?>
                                <input class="darkButton" type="button" id="btnstep2" style="float: right; height: 34px;  width: 170px;" value="<?php
                                       if ($id[2] == '3') {
                                           echo "Create Version";
                                       } else {
                                           echo "Save";
                                       }
                                       ?>" onClick="fn_saveproduct(0,<?php echo $id[1]; ?>);" />
                            <?php } ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</section>
 <script type="text/javascript" language="javascript">
$(function(){
                        $("#assetsforms").validate({
                            ignore: "",
                            errorElement: "dd",
							errorPlacement: function(error, element) {
								$(element).parents('dl').addClass('error');
								error.appendTo($(element).parents('dl'));
								error.addClass('msg');
								//window.scroll(0,($('dd').offset().top)-50);
								
							},
                            rules: { 
                                                                
                                                                
																productname:{ required: true},
																productversion: { required: true },
																assetname:{ required: true},
																assetid: { required: true }
																
												 },
                            messages: { 
                                                                
                                                                productname: { required: "Please type Product Name"},
																productversion: { required: "Please type Product Version" }, 
                                                                assetname: { required: "Please type Asset Name"},
																assetid: { required: "Please type Asset ID" } 
                                                                
                            },
                             highlight: function(element, errorClass, validClass) {
                                $(element).parents('dl').addClass(errorClass);
                                $(element).addClass(errorClass).removeClass(validClass);
                            },
                            unhighlight: function(element, errorClass, validClass) {
                                if($(element).attr('class') == 'error'){
                                    $(element).parents('dl').removeClass(errorClass);
                                    $(element).removeClass(errorClass).addClass(validClass);
                                }
                            },
                            onkeyup: false,
                            onblur: true
                        });
                    });
	 </script>
<?php
@include("footer.php");
