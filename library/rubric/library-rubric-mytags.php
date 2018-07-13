<?php
@include("sessioncheck.php");

$id = isset($method['id']) ? $method['id'] : '';
$id=explode(",",$id);
$expeditionid = $id[0];
$rubricid = $id[1];

$expeditionname = $ObjDB->SelectSingleValue("SELECT CONCAT(a.fld_exp_name,' ',b.fld_version)
												FROM itc_exp_master AS a 
												LEFT JOIN itc_exp_version_track AS b ON a.fld_id = b.fld_exp_id 
												WHERE a.fld_id='".$expeditionid."' AND a.fld_delstatus='0' AND b.fld_delstatus='0'");

$rubricname=$ObjDB->SelectSingleValue("SELECT fn_shortname (CONCAT(fld_rub_name), 1) FROM itc_exp_rubric_name_master WHERE fld_exp_id='".$expeditionid."' AND fld_id='".$rubricid."' AND fld_delstatus='0'");

 
			
?>


<section data-type='2home' id='library-rubric-mytags'>
    <div class='container'>
    	<!--Load the Expedition Name-->
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle"><?php echo "My Tags"."/ ".$expeditionname." / ".$rubricname; ?></p>
                <p class="dialogSubTitle">&nbsp;</p>
            </div>
        </div>
        
        <script type="text/javascript" charset="utf-8">
            $('#tablecontents15').slimscroll({
                height:'auto',
                size: '3px',
                railVisible: false,
                allowPageScroll: false,
                railColor: '#F4F4F4',
                opacity: 9,
                color: '#88ABC2',
                wheelStep: 1,
             });
             
           $.getScript("library/rubric/library-rubric-mytags.js");	
        </script> 
        <!--Load the Expedition Form-->
        <div class='row '>
            <div class='twelve columns centered insideForm'>
                <form name="exporderforms" id="exporderforms">
						<?php 
						$qrydest=$ObjDB->QueryObject("SELECT a.fld_id as destid,a.fld_dest_name as destname FROM itc_exp_rubric_dest_master as a
                                                                            WHERE a.fld_exp_id='".$expeditionid."' AND a.fld_rubric_name_id='".$rubricid."' AND a.fld_delstatus='0'");							
                        
                        if($qrydest->num_rows > 0){
                             while($row=$qrydest->fetch_assoc()){
                                 extract($row);

                                 $did[]=$destid;
                             }
                        }
                        ?>
                        <table class='table table-hover table-striped table-bordered setbordertopradius' id="expordertable" width="100%">
                            <thead class='tableHeadText'>
                                <tr>
                                    <th class='centerText' style="width: 40%">Statement</th>
                                    <th class='centerText' style="width: 20%">Destination</th>
                                    <th class='centerText' style="width: 40%">Tags</th>
                                </tr>
                            </thead>
                        </table>
                        
                        <div style="max-height:500px;width:100%" id="tablecontents15"  >
                        <table style="margin-bottom:0px;" class='table table-hover table-striped table-bordered bordertopradiusremove'>
                        <tbody>
                        <?php
                        for($i=0;$i<sizeof($did);$i++) 
                        {
							$qryviewexp_rubric=$ObjDB->QueryObject("SELECT c.fld_id as rowid, c.fld_destination_id as destid, c.fld_category as category 
																FROM itc_exp_rubric_master AS c 
																WHERE c.fld_rubric_id='".$rubricid."' AND c.fld_exp_id='".$expeditionid."'
																AND c.fld_destination_id='".$did[$i]."' AND c.fld_delstatus='0' ");                           
                            
                         
                            if($qryviewexp_rubric->num_rows > 0) 
                            {
                                $cnt=1;
                                while($row=$qryviewexp_rubric->fetch_assoc())
                                {
                                    extract($row);

                                    //placeholders array
                                    $placeholders = array('&', '>', '!', '<');
                                    //replace values array
                                    $replace = array('and', 'greater than', 'ex', 'less than');

                                    $category = str_replace($placeholders, $replace, $category);
                                    $category = str_replace(',', '', $category);
                            
                                
                            ?>
                            <input type="hidden" name="category_<?php echo $rowid;?>" id="category_<?php echo $i;?>" value="<?php echo $rowid;?>">
                            <tr>
                                <td style="width: 40%">
                                    <div><?php echo $category; ?></div>
                                </td>
                                <td style="width: 20%" class='centerText'>
                                  <?php echo $i+1; ?>
                                </td>
                                <td style="width: 40%" class='centerText'> 
                                     <input style="width:50%" type="text" name="mytagdest" value="" class="destinat" id="form_mytags_category_<?php echo $rowid;?>" />
                                     
                                </td>
                            </tr>
                            
                    <!-- Autocomplete script start -->

                            <script type="text/javascript" charset="utf-8">	
                                $(function(){				
                                    var t5 = new $.TextboxList('#form_mytags_category_<?php echo $rowid;?>', 
                                    {

                                        unique: true, plugins: {autocomplete: {}},
                                        bitsOptions:{editable:{addKeys: [188]}}	});

                                    <?php 
                                           
                                        $qrytag = $ObjDB->QueryObject("SELECT a.fld_id AS tagid,a.fld_tag_name AS tagname 
                                                                      FROM itc_main_tag_master AS a, itc_main_tag_mapping AS b 
                                                                                                  WHERE a.fld_id=b.fld_tag_id AND b.fld_tag_type='34' 
                                                                                                        AND b.fld_access='1' 
                                                                                                                AND a.fld_delstatus='0' AND b.fld_item_id='".$rowid."'");//AND a.fld_created_by='".$uid."' 
                                        if($qrytag->num_rows > 0) {
                                            while($restag = $qrytag->fetch_assoc()){
                                                extract($restag); ?>
                                                t5.add('<?php echo $ObjDB->EscapeStrAll($tagname); ?>','<?php echo $tagid; ?>');				
                                                <?php 
                                            }
                                        }
                                         
                                    ?>	
                                    var expid='<?php echo $expeditionid; ?>';
                                    var destid='<?php echo $rowid; ?>';
                                    t5.getContainer().addClass('textboxlist-loading');				
                                    $.ajax({url: 'autocomplete.php', data: 'oper=search&tag_type=34&expid='+expid+'&rowid='+destid, type:"POST", dataType: 'json', success: function(r){
                                            t5.plugins['autocomplete'].setValues(r);
                                            t5.getContainer().removeClass('textboxlist-loading');
                                            $('.textboxlist-autocomplete').css({"position":"absolute","z-index":" 1000","text-align":"left","width":"38%"});

                                    }});						
                                });
                            </script>

                        <!-- Autocomplete script end -->
                                <?php
                                    }
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        
            <div class="row rowspacer" style="margin-top:20px;">
                <div class="tLeft" style="color:#F00;"></div>
                <div class="tRight">
                    <input type="button" class="darkButton" style="width:200px; height:42px;float:right;margin-bottom:20px;margin-right:20px;" value="Save Status" onClick="fn_savecontenttagstatus(<?php echo $expeditionid; ?>,<?php echo $rubricid; ?>);" />
                </div>
            </div>
        </div>

    </div>
</section>

<?php
@include("footer.php");
