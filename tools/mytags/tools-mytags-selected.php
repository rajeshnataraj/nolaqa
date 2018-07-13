<?php 
	@include("sessioncheck.php");
	$id = isset($method['id']) ? $method['id'] : '0';
	$count = isset($method['count']) ? $method['count'] : '0';
	$sqry='';
	$cond='';
	$sid = explode(',',$id);
	if(sizeof($sid)>1){
		for($i=0;$i<sizeof($sid);$i++){	
			$j = $i+1;
			if($i==0){
				$sqry.="(SELECT fld_id,fld_item_id,fld_tag_type FROM itc_main_tag_mapping WHERE fld_access='1' AND fld_tag_id=".$sid[$i].") AS a".$i." ";
				$cond.=	"ON a".$i.".fld_tag_type=a".$j.".fld_tag_type AND a".$i.".fld_item_id=a".$j.".fld_item_id ";
				$pre = $j;				
			}
			else{
				$sqry.="join (SELECT fld_id,fld_item_id,fld_tag_type FROM itc_main_tag_mapping WHERE fld_access='1' AND fld_tag_id=".$sid[$i].") AS a".$i." ";
				$cond.="AND a".$i.".fld_item_id=a".($pre-1).".fld_item_id ";
				$pre = $j;
			}
		}
	}
?>
<script type="text/javascript" charset="utf-8">			
	$(function(){				
		var t4 = new $.TextboxList('#form_tags_manage', 
		{
			unique: true, plugins: {autocomplete: {}},
			bitsOptions:{editable:{addKeys: [188]}}	});
		<?php for($i=0;$i<sizeof($sid);$i++){
				$qry = $ObjDB->QueryObject("SELECT fld_id, fld_tag_name FROM itc_main_tag_master WHERE fld_id='".$sid[$i]."'");
				$restag = $qry->fetch_assoc();
				extract($restag);?>
				t4.add('<?php echo $ObjDB->EscapeStr($fld_tag_name); ?>','<?php echo $fld_id; ?>');
		<?php }?>			
		t4.getContainer().addClass('textboxlist-loading');				
		$.ajax({url: 'autocomplete.php', data: 'oper=new', dataType: 'json', success: function(r){
			t4.plugins['autocomplete'].setValues(r);
			t4.getContainer().removeClass('textboxlist-loading');					
		}});
		t4.addEvent('bitBoxAdd',function(bit) {
			fn_managelist(bit.getValue(),0);
		});
		
		t4.addEvent('bitRemove',function(bit) {					
			fn_managelist(bit.getValue(),1);
		});							
	});	
	
	$('#tablecontentsselect').slimscroll({
		height:'auto',
		size: '3px',
		railVisible: false,
		allowPageScroll: false,
		railColor: '#F4F4F4',
		opacity: 9,
		color: '#88ABC2',
	});		
</script> 
<section data-type='2home' id='tools-mytags-selected'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Items tagged "My Favorite Content"</p>
                <p class="dialogSubTitleLight">Below is a list of all items tagged using the tag name above.</p>
            </div>
        </div> 
        <div class='row rowspacer'>
            <div class='twelve columns'>                
                <div class="tag_well">
                	<input type="text" name="test3" value="" id="form_tags_manage" />
                </div>
            </div>
        </div>
        <div class='row rowspacer'>
            <div class='span10 offset1' id="taglist"> 
            	<table class='table table-hover table-striped table-bordered setbordertopradius'>
                	<thead class='tableHeadText'>
                        <tr>
                        	<th width="70%">"My Favorite Content"</th>
                            <th width="20%" class='centerText' width="20%">Content Type</th>
                            <th width="10%" class='centerText'>Untag this item</th>                                                                  
                        </tr>
                    </thead>	
                </table>
                    	<?php 							
							if(sizeof($sid)==1){									
								$qrytags = $ObjDB->QueryObject("SELECT p.* FROM (SELECT a.fld_id, b.fld_type_name,a.fld_tag_type, c.fld_id AS tagid,(CASE 
										WHEN a.fld_tag_type = 1 THEN (SELECT CONCAT(e.fld_ipl_name,' ',it.fld_version) FROM itc_ipl_master AS e LEFT JOIN itc_ipl_version_track AS it 
										ON it.fld_ipl_id=e.fld_id WHERE e.fld_id=a.fld_item_id AND e.fld_delstatus='0' AND it.fld_zip_type='1' AND it.fld_delstatus='0') 
										WHEN a.fld_tag_type = 2 THEN (SELECT fld_activity_name FROM itc_activity_master WHERE fld_id=a.fld_item_id AND fld_delstatus='0') 
										WHEN a.fld_tag_type = 3 THEN (SELECT CONCAT(e.fld_module_name,' ',mo.fld_version) FROM itc_module_master AS e LEFT JOIN itc_module_version_track 
										AS mo ON mo.fld_mod_id=e.fld_id WHERE e.fld_id=a.fld_item_id AND e.fld_delstatus='0' AND mo.fld_delstatus='0') 
										WHEN a.fld_tag_type = 4 THEN (SELECT fld_unit_name FROM itc_unit_master WHERE fld_id=a.fld_item_id AND fld_delstatus='0') 
										WHEN a.fld_tag_type BETWEEN 7 AND 13 THEN (SELECT CONCAT(fld_fname,' ',fld_lname) FROM itc_user_master WHERE fld_id=a.fld_item_id 
										AND fld_delstatus='0') 
										WHEN (a.fld_tag_type = 14 OR a.fld_tag_type=17) THEN (SELECT fld_school_name FROM itc_school_master WHERE fld_id=a.fld_item_id 
										AND fld_delstatus='0')
										WHEN a.fld_tag_type = 15 THEN (SELECT fld_district_name FROM itc_district_master WHERE fld_id=a.fld_item_id AND fld_delstatus='0')
										WHEN a.fld_tag_type = 16 THEN (SELECT CONCAT(fld_fname,' ',fld_lname) FROM itc_user_master WHERE fld_id=a.fld_item_id AND fld_delstatus='0')
										WHEN a.fld_tag_type = 18 THEN (SELECT fld_license_name FROM itc_license_master WHERE fld_id=a.fld_item_id AND fld_delstatus='0')
										WHEN a.fld_tag_type = 19 THEN (SELECT fld_question FROM itc_question_details WHERE fld_id=a.fld_item_id AND fld_delstatus='0')
										WHEN a.fld_tag_type = 20 THEN (SELECT fld_test_name FROM itc_test_master WHERE fld_id=a.fld_item_id AND fld_delstatus='0')
										WHEN a.fld_tag_type = 21 THEN (SELECT fld_class_name FROM itc_class_master WHERE fld_id=a.fld_item_id AND fld_delstatus='0')
										WHEN a.fld_tag_type = 22 THEN (SELECT CONCAT(i.fld_ipl_name,' ',ivt.fld_version) FROM itc_diag_question_mapping AS dm LEFT JOIN itc_ipl_master 
										AS i ON dm.fld_lesson_id=i.fld_id LEFT JOIN itc_ipl_version_track AS ivt ON ivt.fld_ipl_id=i.fld_id WHERE dm.fld_id=a.fld_item_id AND 
										dm.fld_delstatus='0' AND ivt.fld_zip_type='1' AND ivt.fld_delstatus='0')
										WHEN a.fld_tag_type = 23 THEN (SELECT CONCAT(mm.fld_mathmodule_name,' ',mv.fld_version) FROM itc_mathmodule_master AS mm LEFT JOIN 
										itc_module_version_track AS mv ON mv.fld_mod_id=mm.fld_module_id WHERE mm.fld_id=a.fld_item_id AND mm.fld_delstatus='0' AND mv.fld_delstatus='0')
										WHEN a.fld_tag_type = 24 THEN (SELECT fld_project_name FROM itc_reports_contentmanagement_sns WHERE fld_id=a.fld_item_id AND fld_delstatus='0')
										END) AS itemname FROM itc_main_tag_mapping AS a, itc_main_tag_type_master AS b, itc_main_tag_master AS c WHERE 
										a.fld_tag_type=b.fld_id AND c.fld_id=a.fld_tag_id AND a.fld_access='1' AND c.fld_delstatus='0' AND a.fld_tag_id='".$sid[0]."') AS p 
										WHERE p.itemname<>''");
							}
							else{																	
								$qrytags = $ObjDB->QueryObject("SELECT p.* FROM (SELECT k.fld_id,m.fld_type_name,k.fld_item_id,k.fld_tag_type, (CASE 
										WHEN k.fld_tag_type = 1 THEN (SELECT CONCAT(e.fld_ipl_name,' ',it.fld_version) FROM itc_ipl_master AS e LEFT JOIN itc_ipl_version_track AS it 
										ON it.fld_ipl_id=e.fld_id WHERE e.fld_id=k.fld_item_id AND e.fld_delstatus='0' AND it.fld_zip_type='1' AND it.fld_delstatus='0') 
										WHEN k.fld_tag_type = 2 THEN (SELECT fld_activity_name FROM itc_activity_master WHERE fld_id=k.fld_item_id AND fld_delstatus='0') 
										WHEN k.fld_tag_type = 3 THEN (SELECT CONCAT(e.fld_module_name,' ',mo.fld_version) FROM itc_module_master AS e LEFT JOIN itc_module_version_track
										 AS mo ON mo.fld_mod_id=e.fld_id WHERE e.fld_id=k.fld_item_id AND e.fld_delstatus='0' AND mo.fld_delstatus='0') 
										WHEN k.fld_tag_type = 4 THEN (SELECT fld_unit_name FROM itc_unit_master WHERE fld_id=k.fld_item_id AND fld_delstatus='0') 
										WHEN k.fld_tag_type BETWEEN 7 AND 13 THEN (SELECT CONCAT(fld_fname,' ',fld_lname) FROM itc_user_master WHERE fld_id=k.fld_item_id 
										AND fld_delstatus='0') 
										WHEN (k.fld_tag_type = 14 OR k.fld_tag_type=17) THEN (SELECT fld_school_name FROM itc_school_master WHERE fld_id=k.fld_item_id 
										AND fld_delstatus='0')
										WHEN k.fld_tag_type = 15 THEN (SELECT fld_district_name FROM itc_district_master WHERE fld_id=k.fld_item_id AND fld_delstatus='0')
										WHEN k.fld_tag_type = 16 THEN (SELECT CONCAT(fld_fname,' ',fld_lname) FROM itc_user_master WHERE fld_id=k.fld_item_id AND fld_delstatus='0')
										WHEN k.fld_tag_type = 18 THEN (SELECT fld_license_name FROM itc_license_master WHERE fld_id=k.fld_item_id AND fld_delstatus='0')
										WHEN k.fld_tag_type = 19 THEN (SELECT fld_question FROM itc_question_details WHERE fld_id=k.fld_item_id AND fld_delstatus='0')
										WHEN k.fld_tag_type = 20 THEN (SELECT fld_test_name FROM itc_test_master WHERE fld_id=k.fld_item_id AND fld_delstatus='0')
										WHEN k.fld_tag_type = 21 THEN (SELECT fld_class_name FROM itc_class_master WHERE fld_id=k.fld_item_id AND fld_delstatus='0')
										WHEN k.fld_tag_type = 22 THEN (SELECT CONCAT(i.fld_ipl_name,' ',ivt.fld_version) FROM itc_diag_question_mapping AS dm LEFT JOIN itc_ipl_master 
										AS i ON dm.fld_lesson_id=i.fld_id LEFT JOIN itc_ipl_version_track AS ivt ON ivt.fld_ipl_id=i.fld_id WHERE dm.fld_id=k.fld_item_id AND 
										dm.fld_delstatus='0' AND ivt.fld_zip_type='1' AND ivt.fld_delstatus='0')
										WHEN k.fld_tag_type = 23 THEN (SELECT CONCAT(mm.fld_mathmodule_name,' ',mv.fld_version) FROM itc_mathmodule_master AS mm LEFT JOIN 
										itc_module_version_track AS mv ON mv.fld_mod_id=mm.fld_module_id WHERE mm.fld_id=k.fld_item_id AND mm.fld_delstatus='0' AND mv.fld_delstatus='0')
										WHEN k.fld_tag_type = 24 THEN (SELECT fld_project_name FROM itc_reports_contentmanagement_sns WHERE fld_id=k.fld_item_id AND fld_delstatus='0')
										END) AS itemname 
										FROM(SELECT a0.fld_id,a0.fld_item_id, a0.fld_tag_type
											FROM ".$sqry."
											".$cond.") AS k
										JOIN itc_main_tag_type_master AS m
										ON m.fld_id = k.fld_tag_type) AS p 
										WHERE p.itemname<>''");											
							}
				?>
				<div style="max-height:400px;width:100%" id="tablecontentsselect"  >
                    <table style="margin-bottom:0px;" class='table table-hover table-striped table-bordered bordertopradiusremove'>                  
                        <tbody>
                            <?php
							
							if($qrytags->num_rows>0){
								while($restag=$qrytags->fetch_assoc()){
									extract($restag);
							?>
                                    <tr id="items_<?php echo $fld_id; ?>" name="<?php echo $fld_id; ?>">
                                        <td width="70%">
                                           <?php echo $itemname; ?> 
                                        </td>
                                        <td width="20%" class='centerText'>
                                            <?php echo $fld_type_name; ?>
                                        </td>
                                        <td width="10%">
                                            <div class="icon-synergy-delete-dark" onclick="fn_deleteitem(<?php echo $fld_id; ?>)"></div>
                                        </td>
                                    </tr>
                        <?php }
							}
						else{?>
                        <tr>
                        	<td colspan="3">No Items Found</td>
                        </tr>
                        <?php }?>
                    	</tbody>
                	</table>
                </div>
                <input type="hidden" id="hidtagids" value="<?php echo $id; ?>" />
            </div>
		</div>   
    </div>   
</section>
<?php
	@include("footer.php");