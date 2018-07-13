<?php
@include("sessioncheck.php");
?>

<section data-type='2home' id='tools-correlation-correlationtoolassignment'>
    <script language="javascript">
        $.getScript("tools/correlation/tools-correlation-correlationtool.js");

    </script> 
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
                <p class="dialogTitle">Select Standard</p>
                <p class="dialogSubTitleLight">Using the fields below, select your standard.</p>
                <div class="row rowspacer"></div>
            </div>
        </div>    
        <div class='row'>
            <div class='twelve columns formBase'>
                <div class='row'>
                    <div class='eleven columns centered insideForm'>
                        <div class="row rowspacer">

                            <script type="text/javascript" language="javascript">
                                $(function() {
                                    $('#testrailvisible11').slimscroll({
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

                                    $('#testrailvisible12').slimscroll({
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
                                    $("#list5").sortable({
                                        connectWith: ".droptrue3",
                                        dropOnEmpty: true,
                                        items: "div[class='draglinkleft']",
                                        receive: function(event, ui) {
                                            $("div[class=draglinkright]").each(function() {
                                                if ($(this).parent().attr('id') == 'list5') {

                                                    fn_movealllistitems('list5', 'list6', 1, $(this).children(":first").attr('id'));

                                                }
                                            });
                                        }
                                    });
                                    $("#list6").sortable({
                                        connectWith: ".droptrue3",
                                        dropOnEmpty: true,
                                        receive: function(event, ui) {
                                            $("div[class=draglinkleft]").each(function() {
                                                if ($(this).parent().attr('id') == 'list6') {
                                                    fn_movealllistitems('list5', 'list6', 1, $(this).children(":first").attr('id'));

                                                }
                                            });
                                        }
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
                                                if ($(this).parent().attr('id') == 'list8') {
                                                    fn_movealllistitems('list7', 'list8', 1, $(this).children(":first").attr('id'));

                                                }
                                            });
                                        }
                                    });
                                });
                            </script>
                            <div class="row rowspacer">
                                <div class='twelve columns'>
                                    <div class='six columns'>       
                                        <div class="dragndropcol">
                                            <div class="dragtitle">Select Titles</div>
                                            <div class="dragWell" id="testrailvisible11" >
                                                <div id="list5" class="dragleftinner droptrue3">
                                                    <div class="draglinkleftSearch" id="s_list5" >
                                                        <dl class='field row'>   
                                                            <dt class='text'>
                                                            <input placeholder='Search' type='text' id="list_5_search" name="list_5_search" onKeyUp="search_list(this, '#list5');" />
                                                            </dt>
                                                        </dl>
                                                    </div>
                                                    <?php
                                                    $titles = $ObjDB->QueryObject("SELECT fld_title_type as fld_prd_type,fld_title_name as titlename   FROM itc_correlation_producttitles where fld_delstatus='0'  GROUP BY fld_title_type");
                                                    if ($titles->num_rows > 0) {
                                                        while ($titlerow = $titles->fetch_assoc()) {
                                                            extract($titlerow);

                                                            ?>
                                                            <div class="draglinkleft" id="list5_<?php echo $fld_prd_type; ?>" >
                                                                <div class="dragItemLable tooltip" id="<?php echo $fld_prd_type; ?>"><?php echo $titlename; ?></div>
                                                                <div class="clickable" id="clck_<?php echo $fld_prd_type; ?>" onclick="fn_movealllistitems('list5', 'list6', 1, '<?php echo $fld_prd_type; ?>');"></div>
                                                            </div>
                                                            <?php
                                                        }
                                                    }
                                                    ?>    
                                                </div>
                                            </div>
                                            <div class="dragAllLink" onclick="fn_movealllistitems('list5', 'list6', 0);"  style="cursor: pointer;cursor:hand;width: 130px;float:right; ">add all Titles</div>
                                        </div>
                                    </div>
                                    <div class='six columns'> 
                                        <div class="dragndropcol">
                                            <div class="dragtitle">Selected Titles<span class="fldreq">*</span></div>
                                            <div class="dragWell" id="testrailvisible12">
                                                <div id="list6" class="dragleftinner droptrue3">
                                                    <div class="draglinkleftSearch" id="s_list6" >
                                                        <dl class='field row'>   
                                                            <dt class='text'>
                                                            <input placeholder='Search' type='text' id="list_6_search" name="list_6_search" onKeyUp="search_list(this, '#list6');" />
                                                            </dt>
                                                        </dl>
                                                    </div>

                                                    <?php
                                                    if ($titles->num_rows > 0) {
                                                        while ($titlerow = $titles->fetch_assoc()) {
                                                            extract($titlerow);
                                                            ?>
                                                            <div class="draglinkright" id="list6_<?php echo $fld_prd_type; ?>" >
                                                                <input type="hidden" name="seltype" id="seltype" value="<?php echo $fld_prd_type; ?>">
                                                                <div class="dragItemLable tooltip" id="<?php echo $fld_prd_type; ?>"><?php echo $titlename; ?></div>
                                                                <div class="clickable" id="clck_<?php echo $fld_prd_type; ?>" onclick="fn_movealllistitems('list5', 'list6', 1, '<?php echo $fld_prd_type; ?>');"></div>
                                                            </div>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="dragAllLink" onclick="fn_movealllistitems('list6', 'list5', 0);"  style="cursor: pointer;cursor:hand;width: 160px;float: right;">remove all Titles</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row rowspacer">
                                    <div class='twelve columns' id="loadproducts"> 

                                    </div>
                                </div>
                                <!--/************Expedition code start here**************/ -->
                                <div class="row rowspacer" >
                                    <div class='twelve columns' id="destinationdiv" style="display:none">  
                                    </div>
                                </div>
                                <div class="row rowspacer" >
                                    <div class='twelve columns' id="taskdiv" style="display:none">  
                                    </div>
                                </div>
                                <div class="row rowspacer" >
                                    <div class='twelve columns' id="loadresource" style="display:none">  
                                    </div>
                                </div>
                                <div class='row rowspacer'>
                                    <div class='six columns'>
                                        <div id="loadstate">

                                        </div>
                                    </div>
                                    <div class="six columns">
                                        <div id="dpdocuments" >
                                        </div>
                                    </div>
                                </div>
                                <div class="row rowspacer">
                                    <div class='six columns'> 
                                        <div id="divdocgrades" >

                                        </div>
                                    </div>                                    
                                </div>
                                <div class="row rowspacer">
                                    <div class='twelve columns' style="display: none;" id="innerstandard"> 
                                        <div id="loadinnerstandards" style="height: 350px; overflow-x:hidden;overflow-y:scroll;" >

                                        </div>
                                    </div>

                                </div>

                                <div class="row rowspacer">
                                    <input class="btn" type="button" id="btnstep2"  style="width:200px; height:42px;float:right;" value="Create Alignment" onClick="fn_saveselect(1);" />
                                </div>
                                <div class="row rowspacer">
                                    <input class="btn" type="button" id="btnstep2"  style="width:200px; height:42px;float:right;" value="Exclude Alignment" onClick="fn_saveselect(2);" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            </section>

            <?php
            @include("footer.php");
            