<?php
@include("sessioncheck.php");

$id = isset($method['id']) ? $method['id'] : 0;
$productdetails = array();
$prdsysid = array();
$tagproductdetails = array();
?>
<script type="text/javascript" language="javascript">
    var productid = [];
    var tagproductid = [];
</script>
<?php
/* to get flag for selecting type */
$tagcnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_tagflag) FROM  itc_correlation_rpt_products 
												WHERE fld_rpt_data_id='" . $id . "' AND fld_delstatus ='0'");
//$titletype='0';
/*
 * For selecting Title type 
 */
if ($tagcnt == 0) {   // starts if tagcnt == 0
    $showtype = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_show_alltype) FROM  itc_correlation_report_data
                                                WHERE fld_id='" . $id . "' AND fld_delstatus ='0'");

    $qryforproductdetails = $ObjDB->QueryObject("SELECT b.fld_prd_name, b.fld_asset_id, a.fld_type, b.fld_id,fn_shortname (CONCAT(fld_prd_name, ' ', fld_prd_version), 2) AS shortname
                                                        FROM itc_correlation_rpt_products a 
                                                        LEFT JOIN itc_correlation_productdetails b ON b.fld_id=a.fld_prd_id
                                                        WHERE a.fld_rpt_data_id='" . $id . "' AND  a.fld_delstatus='0' 
                                                        ORDER BY b.fld_prd_name ASC");
    if ($qryforproductdetails->num_rows > 0) {
        ?>
        <script language="javascript" type="text/javascript">
            $('#tag').addClass('dim');
            $('#btnstep3').removeClass('dim');

        </script>
        <?php
        while ($qryforproductdetailsrow = $qryforproductdetails->fetch_assoc()) {
            extract($qryforproductdetailsrow);
            if ($showtype == '1') {
                $titletype = '0';
            } else {
                $titletype = $fld_type;
            }
            $prdsysid[] = $fld_id;
            $productdetails[] = array("id" => $fld_id, "nam" => $fld_prd_name, "type" => $fld_type, "productid" => $fld_asset_id, "shortname" => $shortname);
            ?>

            <script type="text/javascript" language="javascript">
                productid.push('<?php echo $fld_id . '_' . $fld_type; ?>');
            </script>

            <?php
        }
    }
}   // ends 
else {   // starts else part of $tag cnt
    $select_tagpid = $ObjDB->SelectSingleValue("SELECT fld_tagproduct_id FROM itc_correlation_report_data WHERE fld_id='" . $id . "' AND fld_delstatus='0'");

    $qryforproductdetails = $ObjDB->QueryObject("SELECT b.fld_prd_name,b.fld_prd_id,a.fld_type,
													b.fld_prd_sys_id,fn_shortname(b.fld_prd_name,2) as shortname,c.fld_tag_id as tagid,
													d.fld_tag_name as tagname
													FROM itc_correlation_rpt_products as a 
													left join itc_correlation_productdetails as b on a.fld_prd_id=b.fld_id and a.fld_type=b.fld_prd_type
													left join itc_main_tag_mapping as c on c.fld_item_id=b.fld_prd_sys_id 
													left join itc_main_tag_master as d on d.fld_id=c.fld_tag_id 
													where a.fld_rpt_data_id='" . $id . "' AND c.fld_access='1' AND a.fld_delstatus='0' AND b.fld_prd_name<>'' AND c.fld_tag_id IN (" . $select_tagpid . ")");
    if ($qryforproductdetails->num_rows > 0) {
        ?>
        <script language="javascript" type="text/javascript">
            $('#title').addClass('dim');
        </script>
        <?php
        while ($qryforproductdetailsrow = $qryforproductdetails->fetch_assoc()) {
            extract($qryforproductdetailsrow);

            $tagproductdetails[] = array("id" => $fld_prd_sys_id, "nam" => $fld_prd_name, "type" => $fld_type, "productid" => $fld_prd_id, "shortname" => $shortname, "tagid" => $tagid, "tagname" => $tagname);
            ?>
            <script type="text/javascript" language="javascript">
                tagproductid.push('<?php echo $fld_prd_sys_id . '_' . $fld_type; ?>');
            </script>
            <?php
        }
    }
    ?>
    <script language="javascript" type="text/javascript" charset="utf-8">

        $(function () {
            $("#tag").prop("checked", true);
            $("#title").prop("checked", false);
            $("div.sdesc").hide();
            $("#Types6").show();

        });
    </script>

    <?php
}
?>
<script language="javascript" type="text/javascript">
    $('#hidselectedproducts').val(productid);


    $('#hidselectedtagproducts').val(tagproductid);

    if (productid == '')
    {
        $('#btnstep3').addClass('dim');
    } else
    {
        $('#btnstep3').removeClass('dim');

    }
    if (tagproductid == '')
    {
        $('#tagbtnstep3').addClass('dim');
    } else
    {
        $('#tagbtnstep3').removeClass('dim');

    }
    $('#cbasicinfo').removeClass("active-first");
    $('#cselectstandard').removeClass("active-mid");
    $('#cselectproduct').addClass("active-mid").parent().removeClass("dim");
    $('#cviewreport').removeClass("active-last");


    fn_showproducts('<?php echo $titletype; ?>',<?php echo $id; ?>);


</script>
<script type="text/javascript" language="javascript">
    $(function () {

        $('#testrailvisible14').slimscroll({
            width: '410px',
            height: '370px',
            size: '7px',
            alwaysVisible: true,
            railVisible: true,
            allowPageScroll: false,
            railColor: '#F4F4F4',
            opacity: 1,
            color: '#d9d9d9',
            wheelStep: 1
        });
        $('#testrailvisible16').slimscroll({
            width: '410px',
            height: '370px',
            size: '7px',
            alwaysVisible: true,
            railVisible: true,
            allowPageScroll: false,
            railColor: '#F4F4F4',
            opacity: 1,
            color: '#d9d9d9',
            wheelStep: 1
        });
        $('#testrailvisible1').slimscroll({
            width: '410px',
            height: '366px',
            size: '7px',
            alwaysVisible: true,
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
            size: '7px',
            alwaysVisible: true,
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
            size: '7px',
            alwaysVisible: true,
            railVisible: true,
            allowPageScroll: false,
            railColor: '#F4F4F4',
            opacity: 1,
            color: '#d9d9d9',
            wheelStep: 1,
        });


        $("#list8").sortable({
            connectWith: ".droptrue3",
            dropOnEmpty: true,
            receive: function (event, ui) {
                $("div[class=draglinkleft]").each(function () {
                    if ($(this).parent().attr('id') == 'list8') {
                        fn_movealllistitems('list7', 'list8', 1, $(this).attr('id').replace('list7_', ''));
                        fn_saveselect();
                        fn_validategrade();
                    }
                });
            }
        });
        $("#list22").sortable({
            connectWith: ".droptrue1",
            dropOnEmpty: true,
            receive: function (event, ui) {
                $("div[class=draglinkleft]").each(function () {
                    if ($(this).parent().attr('id') == 'list22') {
                        fn_movealllistitems('list21', 'list22', 1, $(this).children(":first").attr('id'));
                    }
                });
            }
        });

        $("#list24").sortable({
            connectWith: ".droptrue2",
            dropOnEmpty: true,
            receive: function (event, ui) {
                $("div[class=draglinkleft]").each(function () {
                    if ($(this).parent().attr('id') == 'list24') {
                        fn_movealllistitems('list23', 'list24', 1, $(this).children(":first").attr('id'));
                    }
                });
            }
        });

        $("#list26").sortable({
            connectWith: ".droptrue3",
            dropOnEmpty: true,
            receive: function (event, ui) {
                $("div[class=draglinkleft]").each(function () {
                    if ($(this).parent().attr('id') == 'list26') {
                        fn_movealllistitems('list25', 'list26', 1, $(this).children(":first").attr('id'));
                    }
                });
            }
        });

        /* for listing the selected products in sorting order */
        $("#list10").sortable({
            connectWith: ".droptrue3",
            dropOnEmpty: true,
            receive: function (event, ui) {
                $("div[class=draglinkleft]").each(function () {
                    if ($(this).parent().attr('id') == 'list10') {
                        fn_movealllistitems('list9', 'list10', 1, $(this).attr('id').replace('list9_', ''));
                        fn_saveselecttag();
                        fn_validategrade();
                    }
                });
            }
        });
        /* for radio button options in title  */

        $("input[name='types']").click(function () {
            var test = $(this).val();
            $("div.sdesc").hide();
            $("#Types" + test).show();
            fn_loadproducts();
        });
        /* for searching by tags in textbox list field */
        var t4 = new $.TextboxList('#form_tags_products', {
            startEditableBit: false,
            inBetweenEditableBits: false,
            unique: true,
            plugins: {
                autocomplete: {
                    onlyFromValues: true,
                    queryRemote: true,
                    remote: {url: 'autocomplete.php', extraParams: "oper=searchproduct"},
                    placeholder: ''
                }
            },
            bitsOptions: {editable: {addKeys: [188]}}
        });
<?php
$comp_tagid = '';
for ($i = 0; $i < sizeof($tagproductdetails); $i++) {
    $real_tagid = $tagproductdetails[$i]['tagid'];
    if ($real_tagid != $comp_tagid) {
        ?>
                t4.add("<?php echo $tagproductdetails[$i]['tagname']; ?>", "<?php echo $tagproductdetails[$i]['tagid']; ?>");
                fn_loadproducts();
        <?php
        $comp_tagid = $tagproductdetails[$i]['tagid'];
    } else {
        $comp_tagid = $tagproductdetails[$i]['tagid'];
    }
}
?>
        t4.addEvent('bitAdd', function (bit) {
            fn_loadproducts();
        });

        t4.addEvent('bitRemove', function (bit) {

            fn_loadproducts();
            fn_removeselecttag(<?php echo $id; ?>);

        });
        function fn_loadproducts() {
            var pid = $('#form_tags_products').val();
            if (pid == '') {
                t4.getContainer().addClass('textboxlist-loading');
                $('#title').removeClass('dim');//changes

            }
            var rptid = <?php echo $id; ?>;


            var dataparam = "oper=showtagproducts&&rptid=" + rptid + "&selecttagproducts=" + pid;
            $.ajax({
                type: 'post',
                url: 'reports/correlation/reports-correlation-ajax.php',
                data: dataparam,
                success: function (data) {

                    $('#loadproductstag').html(data);//Used to load the products in the add all products list
                    fn_remloadedprod();

                }
            });

        }
    });

</script>	  	
<section data-type='2home' id='reports-correlation-select_product'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
                <p class="dialogTitle">Step 3: Select Products</p>
                <p class="dialogSubTitleLight">Using the fields below, select your products. Press &ldquo;Next Step&rdquo; to continue.</p>
                <div class="row rowspacer"></div>
            </div>
        </div>    
        <div class='row'>
            <div class='twelve columns formBase'>
                <div class='row'>
                    <div class='eleven columns centered insideForm'>
                        <!-- Creating 2 radio button for titile selection  -->
                        <div class="row rowspacer" id="RadioGroup">
                            <div class='six columns'>
                                Select Type: 
                                <input type="radio" id="title" name="types" checked="checked" value="5" />Title
                                <input type="radio" id="tag" name="types" value="6" />Tag
                            </div>
                        </div>  
                        <div class="sdesc" id="Types5"> <!-- starts  used by selecting titles  --> 
                            <form id="frmselectstandard" name="frmselectstandard">

                                <div class="row rowspacer">
                                    <div class='six columns'>
                                        Select Title<span class="fldreq">*</span>
                                        <dl class='field row'>   
                                            <dt class='dropdown'>   
                                                <div class="selectbox">

                                                    <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                                        <span class="selectbox-option input-medium" data-option="1"><?php if ($titlename == '') { ?>Show All Titles<?php } else {
    echo $titlename;
} ?></span>
                                                        <b class="caret1"></b>
                                                    </a>
                                                    <div class="selectbox-options">
                                                        <input type="text" class="selectbox-filter" placeholder="Search Owner" />
                                                        <ul role="options">
                                                            <li><a tabindex="-1" href="#" data-option="0">Show All Titles</a></li>
                                                                    <?php
                                                                    //$selecttype = $ObjDB->SelectSingleValue("SELECT fld_type FROM itc_correlation_rpt_products WHERE fld_rpt_data_id='".$id."' AND fld_delstatus='0'");

                                                                    $stateqry = $ObjDB->QueryObject("SELECT fld_title_name AS name,fld_id AS titleid,fld_title_type AS titletype FROM itc_correlation_producttitles 
                                                                                                                                                   WHERE fld_delstatus='0' 
                                                                                                                                                   ORDER BY fld_id ASC");
                                                                    while ($rowstate = $stateqry->fetch_assoc()) {
                                                                    extract($rowstate);
                                                                    ?>
                                                                <li><a tabindex="-1" href="#" data-option="<?php echo $titletype; ?>"><?php echo $name; ?></a></li>
                                                            <?php }
                                                            ?> 
                                                        </ul>
                                                        <input type="hidden" name="showtitle" id="showtitle" value="<?php echo $titletype; ?>" onchange="fn_showproducts(this.value,<?php echo $id; ?>);
                                                        $('#hidselecteddropdown').val(this.value);
                                                        fn_changetype();" />
                                                    </div>
                                                </div>
                                            <dt>
                                        </dl>                            
                                    </div>
                                </div>
                                <div class="row rowspacer" >
                                    <div class='six columns' id="loadproducts"></div>
                                    <div class='six columns'>
                                        <div class="dragndropcol">
                                            <div class="dragtitle">Selected Products<span class="fldreq">*</span></div> 
                                            <div class="dragWell" id="testrailvisible14">
                                                <div id="list8" class="dragleftinner droptrue3">

<?php
for ($i = 0; $i < sizeof($productdetails); $i++) {
    ?>
                                                        <div name="<?php echo $productdetails[$i]['type']; ?>" class="draglinkright" id="list8_<?php echo $productdetails[$i]['id'] . "_" . $productdetails[$i]['type']; ?>" >
                                                            <div class="dragItemLable tooltip" title="<?php echo $productdetails[$i]['nam']; ?>" id="<?php echo $productdetails[$i]['id'] . "~" . $productdetails[$i]['type'] . "~" . $productdetails[$i]['productid']; ?>"><?php echo $productdetails[$i]['shortname']; ?></div>
                                                            <div class="clickable" id="clck_<?php echo $productdetails[$i]['productid']; ?>" onclick="fn_movealllistitemsproducts('list7', 'list8', 1, '<?php echo $productdetails[$i]['id'] . "_" . $productdetails[$i]['type']; ?>');"></div>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>


                                                </div>	
                                            </div>
                                            <div class="dragAllLink" onclick="fn_movealllistitemsproducts('list8', 'list7', 0);
                                    fn_saveselect();fn_validateproducts();" style="cursor: pointer;cursor:hand;width: 145px;float: right;" 
                                                 >remove all products</div>
                                            <!--changes-->                  </div>
                                    </div>
                                </div>	


                                <div class="row rowspacer">
                                    <input class="btn" type="button" id="btnstep3"  style="width:200px; height:42px;float:right;" value="Next Step" onClick="fn_movenextstep(<?php echo $id; ?>, 4);" />
                                </div>
                            </form>
                        </div>  <!-- ends  used by selecting titles  --> 
                        <!--  starts  selecting tag formation -->
                        <div class="sdesc" id="Types6" style="display: none;">
                            <div class='row' style="padding-bottom:20px;">  <!-- Tag Well -->
                                <div class='twelve columns'>
                                    <p class="">To filter this list, search by Tag Name.</p>
                                    <div class="tag_well">
                                        <input type="text" name="form_tags_products" value="" id="form_tags_products" />
                                    </div>
                                </div>
                            </div>
                            <div class='row buttons' id="productlist">
                                <form id="tagfrmselectstandard" name="tagfrmselectstandard">
                                    <div class="row rowspacer" >
                                        <div class='six columns' id="loadproductstag"></div>
                                        <div class='six columns'>
                                            <div class="dragndropcol">
                                                <!-- selected products using ajax page -->
                                                <div class="dragtitle">Selected Products<span class="fldreq">*</span></div> 
                                                <div class="dragWell" id="testrailvisible16">
                                                    <div id="list10" class="dragleftinner droptrue3">

<?php for ($i = 0; $i < sizeof($tagproductdetails); $i++) { ?>
                                                            <div name="<?php echo $tagproductdetails[$i]['type']; ?>" class="draglinkright" id="list10_<?php echo $tagproductdetails[$i]['id'] . "_" . $tagproductdetails[$i]['type']; ?>" >
                                                                <div class="dragItemLable tooltip" title="<?php echo $tagproductdetails[$i]['nam']; ?>" id="<?php echo $tagproductdetails[$i]['id'] . "~" . $tagproductdetails[$i]['type'] . "~" . $tagproductdetails[$i]['productid']; ?>"><?php
    echo $tagproductdetails[$i]['shortname'] . "/";
    if ($tagproductdetails[$i]['type'] == '1')
        echo "IPL";
    elseif ($tagproductdetails[$i]['type'] == '2')
        echo "Unit";
    elseif ($tagproductdetails[$i]['type'] == '3')
        echo "Module";
    elseif ($tagproductdetails[$i]['type'] == '4')
        echo "Math Module";
    elseif ($tagproductdetails[$i]['type'] == '5')
        echo "Expedition";
    ?></div>
                                                                <div class="clickable" id="clck_<?php echo $tagproductdetails[$i]['productid']; ?>" onclick="fn_movealllistitemsproducts('list9', 'list10', 1, '<?php echo $tagproductdetails[$i]['id'] . "_" . $tagproductdetails[$i]['type']; ?>');"></div>
                                                            </div>
                                                                <?php } ?>
                                                    </div>	
                                                </div>
                                                <div class="dragAllLink" onclick="fn_movealllistitemsproducts('list10', 'list9', 0);
                                                        fn_saveselecttag();fn_validateproductstag();"  style="cursor: pointer;cursor:hand;width:  140px;float: right;">remove all products</div><!--changes-->
                                            </div>
                                        </div>	
                                    </div>
                                    <div class="row rowspacer">
                                        <input class="btn" type="button" id="tagbtnstep3"  style="width:200px; height:42px;float:right;" value="Next Step" onClick="fn_movenextstep(<?php echo $id; ?>, 4);" />
                                    </div>

                                </form>
                            </div>
                        </div>  
                        <!-- ends  selecting tag formation -->

                        <script type="text/javascript" language="javascript">
                            function fn_changetype()
                            {
                                $("div[id^=list8_]").each(function ()
                                {
                                    var pid = $(this).attr('name');

                                    if ($('#hidselecteddropdown').val() != $(this).attr('name') && $('#hidselecteddropdown').val() != 0)
                                    {
                                        $(this).hide().addClass('dim');
                                    } else
                                    {
                                        $(this).show().removeClass('dim');
                                    }
                                });
                            }
                        </script>
                        <input type="hidden" id="hidselecteddropdown" name="hidselecteddropdown" value="1"  />
                        <input type="hidden" id="hidselectedtagproducts" name="hidselectedtagproducts" value="" />
                        <input type="hidden" id="hidrptid" name="hidrptid" value="<?php echo $id; ?>" />
                    </div>
               	</div>
            </div>
        </div>
    </div>
</section>     
<?php
@include("footer.php");
