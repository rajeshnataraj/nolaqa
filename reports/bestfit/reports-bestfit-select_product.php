<?php 
	error_reporting(0);	
@include("sessioncheck.php");

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
$rptid = isset($method['rptid']) ? $method['rptid'] : 0;
$productdetails=array();
$tagproductdetails=array();
?>
<script type="text/javascript" language="javascript">
    var productid = [];
var tagproductid = [];
</script>
<?php
	/* to get flag for selecting type */

	$tagcnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_tagflag) FROM  itc_bestfit_rpt_products 
												WHERE fld_rpt_data_id='".$id."' AND fld_delstatus ='0'");


		$titletype='0';
                /*
* For selecting Title type 
 */
if($tagcnt == 0) {   // starts if tagcnt == 0

$showtype = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_show_alltype) FROM  itc_bestfit_report_data
                                                WHERE fld_id='".$id."' AND fld_delstatus ='0'");
												
                $qryforproductdetails=$ObjDB->QueryObject("SELECT b.fld_prd_name, b.fld_prd_id, a.fld_type, b.fld_prd_sys_id,fn_shortname(b.fld_prd_name,2) as shortname
                                                FROM itc_bestfit_rpt_products a 
                                                LEFT JOIN itc_correlation_products b ON b.fld_id=a.fld_prd_id
                                                WHERE a.fld_rpt_data_id='".$id."' AND a.fld_delstatus='0'
                                                 ORDER BY b.fld_prd_name ASC;");
                
    if($qryforproductdetails->num_rows > 0){
        ?>
<script language="javascript" type="text/javascript">
        $('#tag').addClass('dim');
         $('#btnstep2').removeClass('dim');

    </script>
    <?php
        while($qryforproductdetailsrow = $qryforproductdetails->fetch_assoc()){
        extract($qryforproductdetailsrow);
      
	  if($showtype=='1'){
                $titletype='0';
            }
            else
            {
       $titletype=$fld_type;
            }
			$productdetails[]=array("id"=>$fld_prd_sys_id,"nam"=>$fld_prd_name,"type"=>$fld_type,"productid"=>$fld_prd_id,"shortname"=>$shortname);
       
 ?>
        <script type="text/javascript" language="javascript">
            productid.push('<?php echo $fld_prd_sys_id.'_'.$fld_type;?>');
        </script>
        
 <?php 
        }
    }  
	
	if($titletype=='0')
    {
        $titlename="Show All Titles";
    }
    else if($titletype=='1')
    {
       $titlename="IPLs"; 
    }
    else if($titletype=='2')
    {
       $titlename="Units"; 
    }
    else if($titletype=='3')
    {
       $titlename="Modules"; 
    }
    else if($titletype=='4')
    {
       $titlename="Math Modules"; 
    }

   $checkbox=$ObjDB->SelectSingleValueInt("select fld_flag
                                            FROM itc_bestfit_report_data
                                            WHERE fld_id='".$id."' AND fld_delstatus='0'");
}
else{
     // starts else part of $tag cnt
	 
   
	$select_tagpid = $ObjDB->SelectSingleValue("SELECT fld_tagproduct_id FROM itc_bestfit_report_data WHERE fld_id='".$id."' AND fld_delstatus='0'");
	
	$qryforproductdetails = $ObjDB->QueryObject("SELECT b.fld_prd_name,b.fld_prd_id,a.fld_type,
                                                                                                b.fld_prd_sys_id,fn_shortname(b.fld_prd_name,2) as shortname,c.fld_tag_id as tagid,
                                                                                                d.fld_tag_name as tagname
                                                                                                FROM itc_bestfit_rpt_products as a 
                                                                                                left join itc_correlation_products as b on a.fld_prd_id=b.fld_id and a.fld_type=b.fld_prd_type
                                                                                                left join itc_main_tag_mapping as c on c.fld_item_id=b.fld_prd_sys_id 
                                                                                                left join itc_main_tag_master as d on d.fld_id=c.fld_tag_id 
                                                                                                where a.fld_rpt_data_id='".$id."' AND c.fld_access='1' AND a.fld_delstatus='0' AND b.fld_prd_name<>'' AND c.fld_tag_id IN (".$select_tagpid.")");
	if($qryforproductdetails->num_rows > 0){
?>
    <script language="javascript" type="text/javascript">
        $('#title').addClass('dim');
    </script>
    <?php
		while($qryforproductdetailsrow = $qryforproductdetails->fetch_assoc()){
			extract($qryforproductdetailsrow);
        
			$tagproductdetails[]=array("id"=>$fld_prd_sys_id,"nam"=>$fld_prd_name,"type"=>$fld_type,"productid"=>$fld_prd_id,"shortname"=>$shortname,"tagid"=>$tagid,"tagname"=>$tagname);?>
			<script type="text/javascript" language="javascript">
				tagproductid.push('<?php echo $fld_prd_sys_id.'_'.$fld_type;?>');
			</script>
			<?php
		}
	}
?>
	<script language="javascript" type="text/javascript" charset="utf-8">
   
	$(function(){
		$("#tag").prop("checked", true);
		$("#title").prop("checked", false);
		$("div.sdesc").hide();
        $("#Types6").show();
        
	});
	</script>
	
	<?php
        $tagcheckbox=$ObjDB->SelectSingleValueInt("select fld_flag
                                            FROM itc_bestfit_report_data
                                            WHERE fld_id='".$id."' AND fld_delstatus='0'");
	
    
}
if($titletype=='0')
    {
        $titlename="Show All Titles";
    }
    else if($titletype=='1')
    {
       $titlename="IPLs"; 
    }
    else if($titletype=='2')
    {
       $titlename="Units"; 
    }
    else if($titletype=='3')
    {
       $titlename="Modules"; 
    }
    else if($titletype=='4')
    {
       $titlename="Math Modules"; 
    }


   
?>
        
<script language="javascript" type="text/javascript">
    $('#hidlist8').val(productid);
    $('#hidselectedtagproducts').val(tagproductid); 
	if(productid=='')
	{
            $('#btnstep2').addClass('dim');
	}
            else
    {
        $('#btnstep2').removeClass('dim');
       
    }
	if(tagproductid=='')
	{
		$('#tagbtnstep2').addClass('dim');
	}
    else
    {
        $('#tagbtnstep2').removeClass('dim');
       
    }
	$('#bbasicstandardinfo').removeClass("active-first");
	$('#bselectproduct').addClass("active-mid").parent().removeClass("dim");
	$('#bgenerate').removeClass("active-mid");
	$('#bviewreport').removeClass("active-last");
	fn_showproducts(<?php echo $titletype; ?>,<?php echo $id; ?>);
          /* for radio button options in title  */
    $(function() {
           $("input[name='types']").click(function() {  
               var test = $(this).val();
               //alert(test);
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
					onlyFromValues:true,
					queryRemote: true,
					remote: { url: 'autocomplete.php', extraParams: "oper=searchproduct" },
					placeholder: ''
				}
			},
			bitsOptions:{editable:{addKeys: [188]}}													
		});																	
		<?php  
         $comp_tagid = '';
		for($i=0;$i<sizeof($tagproductdetails);$i++) {
                   $real_tagid = $tagproductdetails[$i]['tagid'];
                   if($real_tagid != $comp_tagid) {
				?>
            		t4.add("<?php echo $tagproductdetails[$i]['tagname']; ?>","<?php echo $tagproductdetails[$i]['tagid']; ?>");
            		fn_loadproducts();				
		<?php 
			$comp_tagid=$tagproductdetails[$i]['tagid'];
	}else { $comp_tagid=$tagproductdetails[$i]['tagid']; } } ?>
		t4.addEvent('bitAdd',function(bit) {
			fn_loadproducts();
		});
		
		t4.addEvent('bitRemove',function(bit) {

			fn_loadproducts();
                        fn_removeselecttag(<?php echo $id; ?>);

		});			
        function fn_loadproducts(){
           	var pid = $('#form_tags_products').val();           
			    if(pid == '') {
				t4.getContainer().addClass('textboxlist-loading');
				}


            var rptid = <?php echo $id; ?>;
                
               
            var dataparam = "oper=showtagproducts&&rptid="+rptid+"&selecttagproducts="+pid;
            $.ajax({
                    type: 'post',
                    url: 'reports/bestfit/reports-bestfit-ajax.php',
                    data: dataparam,
                    success:function(data) {               
                       
                            $('#loadproductstag').html(data);//Used to load the products in the add all products list
 			    fn_remloadedprod();
                             
                    }
            }); 
                         	
		} 
       });
</script>



<section data-type='2home' id='reports-bestfit-select_product'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Step 2: Select Products</p>
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
                                                <input type="hidden" name="showtitle" id="showtitle" value="<?php echo $titletype;?>" onchange="fn_showproducts(this.value,<?php echo $id; ?>);$('#hidselecteddropdown').val(this.value);fn_changetype();" />
												
                                                <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                                    <span class="selectbox-option input-medium" data-option="1"><?php if($titlename == '') {?>Show All Titles<?php } else { echo $titlename; } ?></span>
                                                    <b class="caret1"></b>
                                                </a>
                                                
                                                <div class="selectbox-options">
                                                    <input type="text" class="selectbox-filter" placeholder="Search Owner" />
                                                    <ul role="options">
                                                        <li><a tabindex="-1" href="#" data-option="0">Show All Titles</a></li>
                                                        <li><a tabindex="-1" href="#" data-option="1">IPLs</a></li>
                                                        <li><a tabindex="-1" href="#" data-option="2">Units</a></li>
                                                        <li><a tabindex="-1" href="#" data-option="3">Modules</a></li>
                                                        <li><a tabindex="-1" href="#" data-option="4">Math Modules</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </dt>
                                    </dl>                            
                                </div>
                            </div>
                            
                            <div class="row rowspacer" >
                                <div class='six columns' id="loadproducts"> </div>                     
                                
                                
                                <div class='six columns'>
                                    <div class="dragndropcol">
                                        <div class="dragtitle">Selected Products</div>
                                        
                                        <div class="dragWell" id="testrailvisible14" style="overflow: hidden; width: 406px; height: 364px;">
                                            <div id="list8" class="dragleftinner droptrue3">
                                                <?php
                                                
                                                for($i=0;$i<sizeof($productdetails);$i++) {
                                                    ?>
                                                    <div name="<?php echo $productdetails[$i]['type']; ?>" class="draglinkright" id="list8_<?php  echo $productdetails[$i]['id']."_".$productdetails[$i]['type']; ?>" >
                                <div class="dragItemLable tooltip" title="<?php echo $productdetails[$i]['nam'];?>" id="<?php echo  $productdetails[$i]['id']."~".$productdetails[$i]['type']."~".$productdetails[$i]['productid']; ?>"><?php echo $productdetails[$i]['shortname'];?></div>
                                <div class="clickable" id="clck_<?php echo $productdetails[$i]['productid']; ?>" onclick="fn_movealllistitemsproducts('list7','list8',1,'<?php echo $productdetails[$i]['id']."_".$productdetails[$i]['type']; ?>'); fn_saveselect();"></div>
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                            </div>	
                                        </div>
                                        <div class="dragAllLink" onclick="fn_movealllistitemsproducts('list8','list7',0); fn_saveselect();">remove all products</div>
                                    </div>
                                </div>	
                            </div>
                           
                            <input type="hidden" value="" id="hidlist8" name="hidlist8" />
                            
                       <div class="row rowspacer">  
                            <div class="field" id="checkbox" >
                                <input name="check_1" id="check" type="checkbox" class="myCheckbox" onclick="fn_showselectedpro(<?php echo $id;?>,$('#title').val());" <?php if($checkbox==1){  echo 'checked="checked"'; } ?> />
                                <span></span>  Required Products
                            </div>
                        </div>
                           <?php  if($checkbox==1){?>
                        <script>
                             setTimeout("fn_showselectedpro('<?php echo $id; ?>','5');",1000);
                        </script>
    
                        <?php
                            }
                            ?>
                         <script type="text/javascript" language="javascript">
                                $(function() {
				$('#testrailvisible14').slimscroll({
                                        width: '410px',
                                        height:'366px',
                                        size:'3px',
                                        railVisible: true,
                                        allowPageScroll: false,
                                        railColor: '#F4F4F4',
                                        opacity: 1,
                                        color: '#d9d9d9',
                                         wheelStep: 1,
                                    });
                                    $('#testrailvisible15').slimscroll({
                                        width: '410px',
                                        height:'366px',
                                        size:'3px',
                                        railVisible: true,
                                        allowPageScroll: false,
                                        railColor: '#F4F4F4',
                                        opacity: 1,
                                        color: '#d9d9d9',
                                         wheelStep: 1,
                                    });

                                    $('#testrailvisible16').slimscroll({
                                        width: '410px',
                                        height:'366px',
                                        size:'3px',
                                        railVisible: true,
                                        allowPageScroll: false,
                                        railColor: '#F4F4F4',
                                        opacity: 1,
                                        color: '#d9d9d9',
                                         wheelStep: 1,
                                    });
                                       $('#testrailvisible18').slimscroll({
                                            width: '410px',
                                            height:'366px',
											size:'3px',
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
                                        items: "div[class='draglinkleft']",
                                        receive: function(event, ui) {
                                            $("div[class=draglinkright]").each(function(){ 
                                                if($(this).parent().attr('id')=='list8'){                                                   
                                                    fn_movealllistitems('list8','list17',1,$(this).attr('id').replace('list9_',''));
                                                    fn_saveselect();
                                                }
                                            });
                                        }
                                    });

                                    $("#list9").sortable({
                                        connectWith: ".droptrue4",
                                        dropOnEmpty: true,
                                        items: "div[class='draglinkleft']",
                                        receive: function(event, ui) {
                                            $("div[class=draglinkright]").each(function(){ 
                                                if($(this).parent().attr('id')=='list9'){
                                                    //alert($(this).children(":first").attr('id'));
                                                    fn_movealllistitems('list9','list10',1,$(this).attr('id').replace('list10_',''));
                                                    fn_saveselect();
                                                }
                                            });
                                        }
                                    });

                                    $("#list10" ).sortable({
                                        connectWith: ".droptrue4",
                                        dropOnEmpty: true,
                                        receive: function(event, ui) {
                                            $("div[class=draglinkleft]").each(function(){ 
                                                if($(this).parent().attr('id')=='list10'){                                                   
                                                    fn_movealllistitems('list9','list10',1,$(this).attr('id').replace('list9_',''));
                                                    fn_saveselect();
                                                }
                                            });
                                        }
                                    });
                                      /* for listing the selected products in sorting order */       
                                    $("#list12" ).sortable({
                                        connectWith: ".droptrue3",
                                        dropOnEmpty: true,
                                        receive: function(event, ui) {
                                            $("div[class=draglinkleft]").each(function(){ 
                                                if($(this).parent().attr('id')=='list12'){                                                   
                                                    fn_movealllistitems('list11','list12',1,$(this).attr('id').replace('list11_',''));
                                                    fn_saveselecttag();
                                                    fn_validategrade();
                                                }
                                }); 
                                        }
                                    });
                                }); 
                            </script>
    
                            <div id="requiredproducts" style="display: none">
                                
                            </div>
                            <?php 
                           
                            if($checkbox==0)
                            {
                                $qryfortextboxdetails=$ObjDB->QueryObject("SELECT fld_notitle as notitle, fld_maxrecom as maxrecom, fld_totcombi as totcombi 
                                                                           FROM itc_bestfit_rpt_products 
                                                                           WHERE fld_rpt_data_id='".$id."' AND fld_delstatus='0'");
                                if($qryfortextboxdetails->num_rows > 0){ 
                                while($result = $qryfortextboxdetails->fetch_assoc()){
				extract($result);
                                }
                                }
                            }
                            else if($checkbox==1)
                            {
                                $qryfortextboxdetails=$ObjDB->QueryObject("SELECT fld_req_notitle as notitle, fld_req_maxrecom as maxrecom, fld_req_totcombi as totcombi  
                                                                           FROM itc_bestfit_rpt_reqproducts 
                                                                           WHERE fld_rpt_data_id='".$id."' AND fld_delstatus='0'");
                                if($qryfortextboxdetails->num_rows > 0){ 
                                while($result = $qryfortextboxdetails->fetch_assoc()){
				extract($result);
                                }
                                }
                            }
                            ?>
                            
                    <div class="row rowspacer">   
                        <table>
                            <tr>
                                <td>No of Title in each recommendation&nbsp;</td> <!--                            newline-->
                                <td>&nbsp;<input type="text" id="trecomm" onkeyup="fn_check();fn_ChkValidChar(this.id);" onblur="fn_changecombi();" value="<?php if($checkbox==0){ echo $notitle;} else { echo $notitle; } ?>"></td>
                            </tr>
                            <tr>
                                <td>Max no of recommendation&nbsp;</td><!--                            newline-->
                                <td>&nbsp;<input type="text" id="maxrecomm" onkeyup="fn_check(); fn_ChkValidChar123(this.id);" value="<?php if($checkbox==0){ echo $maxrecom;} else { echo $maxrecom; } ?>" ></td>
                            </tr>
                            <tr>
                                <td>Total no of combinations&nbsp;</td>
                                <td>&nbsp;<input type="text" id="totcombi" value="<?php echo $totcombi; ?>" onkeyup="fn_ChkValidChar(this.id);" readonly="readonly"></td>
                            </tr>
                        </table>
                    </div>		
                       
							  
                            <div class="row rowspacer">
                        	<input class="btn" type="button" id="btnstep2"  style="width:200px; height:42px;float:right;" value="Next Step" onClick="fn_totcombival(<?php echo $id; ?>,2);" />
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
                                                <div class="dragWell" id="testrailvisible18">
                                                    <div id="list12" class="dragleftinner droptrue3">
					
                                                       <?php     for($i=0;$i<sizeof($tagproductdetails);$i++) {          ?>
                                                        <div name="<?php echo $tagproductdetails[$i]['type']; ?>" class="draglinkright" id="list12_<?php  echo $tagproductdetails[$i]['id']."_".$tagproductdetails[$i]['type']; ?>" >
                                                            <div class="dragItemLable tooltip" title="<?php echo $tagproductdetails[$i]['nam'];?>" id="<?php echo  $tagproductdetails[$i]['id']."~".$tagproductdetails[$i]['type']."~".$tagproductdetails[$i]['productid']; ?>"><?php echo $tagproductdetails[$i]['shortname']."/";
                                                                    if ($tagproductdetails[$i]['type'] == '1') 
                                                                         echo "IPL";
                                                                     elseif ($tagproductdetails[$i]['type'] == '2') 
                                                                        echo "Unit";
                                                                    elseif ($tagproductdetails[$i]['type'] == '3') 
                                                                        echo "Module";
                                                                    elseif ($tagproductdetails[$i]['type'] == '4') 
                                                                        echo "Math Module";
						 
                                                            ?></div>
                                                            <div class="clickable" id="clck_<?php echo $tagproductdetails[$i]['productid']; ?>" onclick="fn_movealllistitemsproducts('list11','list12',1,'<?php echo $tagproductdetails[$i]['id']."_".$tagproductdetails[$i]['type']; ?>'); fn_saveselecttag();"></div>
                                                        </div>
                                                        <?php    }        ?>
                                                   </div>	
                                                </div>
                                                <div class="dragAllLink" onclick="fn_movealllistitemsproducts('list12','list11',0);fn_saveselecttag();" style="cursor:pointer;cursor:hand;"
                    >remove all products</div>
                                         </div>
                                      </div>	
                                   </div>
                                    <div class="row rowspacer">  
                            <div class="field" id="checkbox2" >
                                <input name="check_2" id="check2" type="checkbox" class="myCheckbox" onclick="fn_showselectedpro(<?php echo $id;?>,$('#tag').val());" <?php if($tagcheckbox==1){  echo 'checked="checked"'; } ?> />
                                <span></span>  Required Products
                            </div>
                                        
                        </div>
                                     <div id="tagrequiredproducts">
                                     </div>
                                    <?php  if($tagcheckbox==1){                                     
                                          ?>
                        <script>
                             setTimeout("fn_showselectedpro('<?php echo $id; ?>','6');",1000);
                        </script>
    
                        <?php
                            } ?>
                         
                            <?php 
                           
                            if($tagcheckbox==0)
                            {
                                $qryfortextboxdetails=$ObjDB->QueryObject("SELECT fld_notitle as notitle, fld_maxrecom as maxrecom, fld_totcombi as totcombi 
                                                                           FROM itc_bestfit_rpt_products 
                                                                           WHERE fld_rpt_data_id='".$id."' AND fld_delstatus='0'");
                                if($qryfortextboxdetails->num_rows > 0){ 
                                while($result = $qryfortextboxdetails->fetch_assoc()){
								extract($result);
                                }
                                }
                            }
                            else if($tagcheckbox==1)
                            {
                                $qryfortextboxdetails=$ObjDB->QueryObject("SELECT fld_req_notitle as notitle, fld_req_maxrecom as maxrecom, fld_req_totcombi as totcombi  
                                                                           FROM itc_bestfit_rpt_reqproducts 
                                                                           WHERE fld_rpt_data_id='".$id."' AND fld_delstatus='0'");
                                if($qryfortextboxdetails->num_rows > 0){ 
                                while($result = $qryfortextboxdetails->fetch_assoc()){
								extract($result);
                                }
                                }
                            }
                            ?>
                            
                    <div class="row rowspacer">   
                        <table>
                            <tr>
                                <td>No of Title in each recommendation&nbsp;</td> 
                                <td>&nbsp;<input type="text" id="tagtrecomm" onkeyup="fn_tagcheck(); fn_tagChkValidChar(this.id);" onblur="fn_tagchangecombi();" value="<?php if($tagcheckbox==0){ echo $notitle;} else { echo $notitle; } ?>" onkeypress="return onlyAlphabets(event,this);"></td>
                            </tr>
                            <tr>
                                <td>Max no of recommendation&nbsp;</td>
                                <td>&nbsp;<input type="text" id="tagmaxrecomm" onkeyup="fn_tagChkValidChar123(this.id);" value="<?php if($tagcheckbox==0){ echo $maxrecom;} else { echo $maxrecom; } ?>" onkeypress="return onlyAlphabets(event,this);"></td>
                            </tr>
                            <tr>
                                <td>Total no of combinations&nbsp;</td>
                                <td>&nbsp;<input type="text" id="tagtotcombi" value="<?php echo $totcombi; ?>" onkeyup="fn_tagChkValidChar(this.id);" readonly="readonly"></td>
                            </tr>
                        </table>
                    </div>
                        <div class="row rowspacer">
                        	<input class="btn" type="button" id="tagbtnstep2"  style="width:200px; height:42px;float:right;" value="Next Step" onClick="fn_totcombival(<?php echo $id; ?>,2);" />
                        </div>
                                      
                            	</form>
                        	</div>
                   </div>  
                    <!-- ends  selecting tag formation -->
					
                        <script type="text/javascript" language="javascript">
			    function fn_check()
                            {
                               if($('#trecomm').val()=='')
                               {
                                   $('#maxrecomm').val('');
                                   $('#totcombi').val('');
                               }
                               
                               if($('#maxrecomm').val()=='0')//newline
                               {                               
                                    $('#maxrecomm').val('');
                            }
				if($('#trecomm').val()=='0')//newline
                               {
                                  
                                    $('#trecomm').val('');
                               }
                               
                               
                               
                            }
                            function fn_tagcheck()
                            {
                            
                                
                               if($('#tagtrecomm').val()=='')
                               {
                                   $('#tagmaxrecomm').val('');
                                   $('#tagtotcombi').val('');
                               }
                            }
                             
                           function fn_changetype()
                            {
                                $("div[id^=list8_]").each(function()
                                { 
                                    var pid = $(this).attr('name');

                                    if($('#hidselecteddropdown').val() != $(this).attr('name') && $('#hidselecteddropdown').val()!=0)
                                    {
                                        $(this).hide().addClass('dim');
                                    }
                                    else
                                    {
                                        $(this).show().removeClass('dim');
                                    }
                                });
                            }
                            
                            $("input[id^=trecomm],input[id^=maxrecomm],input[id^=totcombi]").keypress(function (e) {
                                if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {					
                                    return false;
                                }
                            });
                            
                            //Function to set the max & min values for the textbox
                            String.prototype.startsWith = function (str) {
                                return (this.indexOf(str) === 0);
                            }                            

				

                                 
                            
                            //Function to set the max & min values for the textbox                         
                   
                    function fn_tagChkValidChar123(id) {
							
                                var txtbx = document.getElementById(id).value;
                                                                    
                                                                    var maxrecommdn = document.getElementById("tagtotcombi").value;

                                                                    if(parseInt(txtbx) > parseInt(maxrecommdn))
                                                                    {                                 
                                                                            document.getElementById(id).value ='';

                                                                            $.Zebra_Dialog("Max no of recommendation value should not be greater than Total no of combinations.", 
                                                                            { 'type': 'information'});

                                                                            return false;

										}
                                
                                  }
								  
                                function fn_tagChkValidChar(id) {
								
                                                                            var txtbx = document.getElementById(id).value;
                                                                            var reqcnt = '';
                                                                            var procnt = '';
                                                                            var length = '';
                                                                            var maxrecomm = '';
                                                                            var totcombi='';
                                                                            
                                                                         
                                                                                
                                                                                 $("div[id^=list14_]").each(function()
                                                                                    {
                                                                                            if($(this).attr('class') != 'draglinkright dim')
                                                                                                    reqcnt++;
                                                                                     }); 

                                                                                    $("div[id^=list12_]").each(function()
                                                                                    {
                                                                                            if($(this).attr('class') != 'draglinkright dim')
                                                                                                    procnt++;
                                                                                    });                                                                          
                                                                                  
                                                                         
                                                                            if(reqcnt!='')                                                                           
                                                                                    length = reqcnt-1;
                                                                                
											if(parseInt(txtbx) <= length) // true
                                                                                {
                                                                                        document.getElementById(id).value = '';

													$.Zebra_Dialog("No of Title in each recommendation value should not be lesser than required products.", 
                                                                                        { 'type': 'information' });
																						return false;
                                                                                }
																				if(parseInt(txtbx) > procnt) // true
                                                                                {
                                                                                        document.getElementById(id).value = '';

                                                                                        $.Zebra_Dialog("No of Title in each recommendation value should not be greater than selected products.", 
                                                                                        { 'type': 'information' });
																						return false;
                                                                                }                                                                          
                                                                        
                                                                   }                                                     
                           
                
                 
                            function fn_ChkValidChar123(id) {
							
										var txtbx = document.getElementById(id).value;
                                var maxrecommdn = document.getElementById("totcombi").value;
                              
                                if(parseInt(txtbx) > parseInt(maxrecommdn))
                                {                                 
											document.getElementById(id).value ='';
											
                                    $.Zebra_Dialog("Max no of recommendation value should not be greater than Total no of combinations.", 
											{ 'type': 'information' });
											
											return false;
											
                                }
                                
                                  }
								  
                                function fn_ChkValidChar(id) {
								
                                var txtbx = document.getElementById(id).value;
                                var reqcnt = '';
                                var procnt = '';
                                var length = '';
                                var maxrecomm = '';
                                var totcombi='';
                                $("div[id^=list10_]").each(function()
                                {
                                    if($(this).attr('class') != 'draglinkright dim')
                                        reqcnt++;
                                 }); 

                                $("div[id^=list8_]").each(function()
                                {
                                    if($(this).attr('class') != 'draglinkright dim')
                                        procnt++;
                                }); 

                                if(reqcnt!=0)
                                    length = reqcnt-1;
                                
										if(parseInt(txtbx) <= length) // true
                                {
											document.getElementById(id).value = '';
                                    
											$.Zebra_Dialog("No of Title in each recommendation value should not be lesser than required products.", 
											{ 'type': 'information' });
											return false;
										}
										if(parseInt(txtbx) > procnt) // true
										{
											document.getElementById(id).value = '';
											
                                    $.Zebra_Dialog("No of Title in each recommendation value should not be greater than selected products.", 
											{ 'type': 'information' });
											return false;
                                }
                                


                                  }
                                                           							
         
                                 
                        </script>
                        <input type="hidden" id="hidselectedtagproducts" name="hidselectedtagproducts" value="" />
                    </div>
                </div>
            </div>
        </div> 
    </div>
</section>     

<?php
@include("footer.php");
