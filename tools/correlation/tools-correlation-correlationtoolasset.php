<?php
@include("sessioncheck.php");
//$sid have tag ids
$sid = isset($method['sid']) ? $method['sid'] : '0';
$lid = isset($method['lid']) ? $method['lid'] : '0';
$sqry = '';
if ($sid != 0) {
    $sid = explode(',', $sid);
    for ($i = 0; $i < sizeof($sid); $i++) {
        //getting license id from the tag which you have selected in this page, fld_item_id is license id	
        $itemqry = $ObjDB->QueryObject("SELECT fld_item_id 
											FROM itc_main_tag_mapping 
											WHERE fld_tag_id='" . $sid[$i] . "' AND fld_access='1' AND fld_tag_type='18'");
        $sqry = "and (";
        $j = 1;
        while ($itemres = $itemqry->fetch_assoc()) {
            extract($itemres);
            if ($j == $itemqry->num_rows) {
                $sqry.=" a.fld_id=" . $fld_item_id . ")";
            } else {
                $sqry.=" a.fld_id=" . $fld_item_id . " or";
            }
            $j++;
        }
    }
}

if ($lid != 0) {
    $lid = explode(',', $lid);
    for ($i = 0; $i < sizeof($lid); $i++) {
        $itemqry = $ObjDB->QueryObject("SELECT fld_id FROM itc_license_master 
								            WHERE fld_id='" . $lid[$i] . "' AND fld_delstatus='0'");
        $sqry = "and (";
        $j = 1;
        while ($itemres = $itemqry->fetch_assoc()) {
            extract($itemres);
            if ($j == $itemqry->num_rows) {
                $sqry.=" a.fld_id=" . $fld_id . ")";
            } else {
                $sqry.=" a.fld_id=" . $fld_id . " or";
            }
            $j++;
        }
    }
}
?>
<script type="text/javascript" charset="utf-8">
    $(function() {
        var t4 = new $.TextboxList('#form_tags_licenses', {
            unique: true,
            startEditableBit: false,
            inBetweenEditableBits: false,
            plugins: {
                autocomplete: {
                    onlyFromValues: true,
                    queryRemote: true,
                    remote: {url: 'autocomplete.php', extraParams: "oper=search&tag_type=18"},
                    placeholder: ''
                }
            },
            bitsOptions: {editable: {addKeys: [188]}}
        });
        // this is for when adding the tag to filters 
        t4.addEvent('bitAdd', function(bit) {
            fn_loadlicense();
        });

        // this is for removing the tag from filters 
        t4.addEvent('bitRemove', function(bit) {
            fn_loadlicense();
        });

    });

    function fn_loadlicense() {
        //sid is tag ids
        var sid = $('#form_tags_licenses').val();
        $("#listlicense").load("licenses/licenses.php #listlicense > *", {'sid': sid}, function() {
            $('#tablecontents').slimscroll({
                height: 'auto',
                railVisible: false,
                allowPageScroll: false,
                railColor: '#F4F4F4',
                opacity: 9,
                color: '#88ABC2',
            });
        });

        removesections("#licenses");
    }


    $(function() {
        var t4 = new $.TextboxList('#form_tags_licensesname', {
            unique: true,
            startEditableBit: false,
            inBetweenEditableBits: false,
            plugins: {
                autocomplete: {
                    onlyFromValues: true,
                    queryRemote: true,
                    remote: {url: 'autocomplete.php', extraParams: "oper=searchlicensename&tag_type=18"},
                    placeholder: ''
                }
            },
            bitsOptions: {editable: {addKeys: [188]}}
        });
        // this is for when adding the tag to filters 
        t4.addEvent('bitAdd', function(bit) {
            fn_loadlicensename();
        });

        // this is for removing the tag from filters 
        t4.addEvent('bitRemove', function(bit) {
            fn_loadlicensename();
        });

    });

    function fn_loadlicensename() {
        //sid is tag ids
        var sid = $('#form_tags_licensesname').val();
        $("#listlicense").load("licenses/licenses.php #listlicense > *", {'lid': sid}, function() {
            $('#tablecontents').slimscroll({
                height: 'auto',
                railVisible: false,
                allowPageScroll: false,
                railColor: '#F4F4F4',
                opacity: 9,
                color: '#88ABC2',
            });
        });

        removesections("#licenses");
    }



    $('#tablecontents3').slimscroll({
        height: 'auto',
        railVisible: false,
        allowPageScroll: false,
        size: '7px',
        alwaysVisible: true,
        railColor: '#F4F4F4',
        opacity: 9,
        color: '#88ABC2',
        wheelStep: 1
    });

    /* for radio button options in title  */
    $("input[name='types']").click(function() {
        var test = $(this).val();
        $("div.sdesc").hide();
        $("#types" + test).show();
    });

    //$('table').fixedHeaderTable({cloneHeadToFoot: false, fixedColumn: false });
</script>
<script>
    setTimeout('$("#mytable").treetable({ expandable: true, clickableNodeNames:true })', 3000);
</script>
<section data-type='2home' id='tools-correlation-correlationtoolasset'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
                <p class="dialogTitle">Assets</p>
                <p class="dialogSubTitle">&nbsp;</p>     
            </div>
        </div>
        <div class='row rowspacer'>
            <div class='three columns' >
                <span style="color:white;">Title</span>
                <div class='field row'>
                    <dt class='dropdown'>
                    <div class="selectbox">
                        <input type="hidden" name="titletype"  id="titletype" value="<?php echo $prouiid; ?>" >
                        <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                            <span class="selectbox-option input-medium" data-option="" style="width:95%;">Select</span>
                            <b class="caret1"></b>
                        </a>
                        <div class="selectbox-options" >	
                            <input type="text" class="selectbox-filter" placeholder="Search class" >
                            <ul role="options" >
                                <?php
                                $stateqry = $ObjDB->QueryObject("SELECT fld_title_name AS name,fld_id AS titleid,fld_title_type AS titletype FROM itc_correlation_producttitles 
                                                                                WHERE fld_delstatus='0' 
                                                                                ORDER BY fld_id ASC");
                                while ($rowstate = $stateqry->fetch_assoc()) {
                                    extract($rowstate);
                                    ?>
                                    <li><a href="#" id="producttitle" data-option="<?php echo $titletype; ?>" onclick="fn_loadproductdetail(<?php echo $titletype; ?>);"><?php echo $name; ?></a></li>
                                <?php }
                                ?> 
                                <li><a href="#" id="producttitle" data-option="<?php echo $titletype; ?>" onclick="fn_loadproductdetail(0);">All</a></li>
                            </ul>
                        </div>
                    </div>
                    </dt>   
                </div>

            </div></div>
        <?php
        if ($sessmasterprfid == 2) {
            ?>
            <div class="row rowspacer" id="RadioGroup">
                <div class='twelve columns'>
                    <font style="color:white">Sort/filter the list by: 
                    <input type="radio" id="tag" name="types"  value="5" />Tag
                    <input type="radio" id="search" name="types" checked="checked" value="6" />Name
                    </font>
                    &nbsp;&nbsp;
                </div>
            </div> 
            <?php
        }
        ?>

        <div class='sdesc row' style="padding-bottom:20px;display:none;" id="types5">
            <div class='twelve columns'>
                <p class="filterLightTitle">Filter this list by Tag Name.</p>
                <div class="tag_well">
                    <input type="text" name="form_tags_licenses" value="" id="form_tags_licenses" />
                </div>
            </div>
        </div>

        <div class='sdesc row' style="padding-bottom:20px;" id="types6">
            <div class='twelve columns'>
                <p class="filterLightTitle">Filter this list by Name.</p>
                <div class="tag_well">
                    <input type="text" name="form_tags_licensesname" value="" id="form_tags_licensesname" />
                </div>
            </div>
        </div>


        <div class="row" id="showproduct">
            <div class='span10 offset1' >
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
            <div >
                <table class='table table-hover table-striped table-bordered setbordertopradius' id="mytable" >
                    <tbody>
                        <?php
                        $qrytitle = $ObjDB->QueryObject("SELECT fld_id as tid,fld_title_name as protitle, fld_title_type as titletype FROM itc_correlation_producttitles WHERE
                                                                            fld_delstatus='0'");

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
    </div>
</section>
<?php
@include("footer.php");
