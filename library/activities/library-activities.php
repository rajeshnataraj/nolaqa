<?php 
	@include("sessioncheck.php");
	$createdby='';
	$sid = isset($method['sid']) ? $method['sid'] : '0';
	$sqry='';
	if($sid!=0){
		$sid = explode(',',$sid);
		for($i=0;$i<sizeof($sid);$i++){
			$id = explode('_',$sid[$i]);
			if($id[1]=='unit'){				
				$sqry.= " AND c.fld_unit_id =".$id[0];
			}
			else if($id[1]=='activity'){				
				$sqry.= " AND c.fld_id =".$id[0];
			}
			else{				
				$itemqry = $ObjDB->QueryObject("SELECT fld_item_id FROM itc_main_tag_mapping 
				                               WHERE fld_tag_id='".$sid[$i]."' AND fld_access='1' AND fld_tag_type='2'");
				$sqry = "AND (";
				$j=1;
				while($itemres = $itemqry->fetch_assoc()){
					extract($itemres);
					if($j==$itemqry->num_rows){
						$sqry.=" c.fld_id=".$fld_item_id.")";
					}
					else{
						$sqry.=" c.fld_id=".$fld_item_id." OR";
					}
					$j++;
				}
			}
		}		
	}
?>
<script type="text/javascript" charset="utf-8">	
	$.getScript("library/activities/library-activities-newactivity.js");	
	$(function(){				
		var t4 = new $.TextboxList('#form_tags_input_3', {
			startEditableBit: false,
			inBetweenEditableBits: false,
			plugins: {
				autocomplete: {
					onlyFromValues:true,
					queryRemote: true,
					remote: { url: 'autocomplete.php', extraParams: "oper=search&tag_type=2&unit=1&activity=1" },
					placeholder: ''
				}
			},
			bitsOptions:{editable:{addKeys: [188]}}													
		});																	
		
		t4.addEvent('bitAdd',function(bit) {
			fn_alert();
		});
		
		t4.addEvent('bitRemove',function(bit) {
			fn_alert();
		});					
	});	

	function fn_alert(){
		var sid = $('#form_tags_input_3').val();
		$("#activitylist").load("library/activities/library-activities.php #activitylist > *",{ 'sid':sid});
		removesections('#library-activities');
	}
</script>
<section data-type='2home' id='library-activities'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="darkTitle">Activities</p>
                <p class="darkSubTitle"></p>
            </div>
        </div>
        <div class='row'>
            <div class='twelve columns'>
                <!--<p class="filterDarkTitle">To filter this list, search by Activity Name, Unit Name, and Tag Name.</p>-->
                <p class="filterDarkTitle">Search by Activity name, Unit name, Tag name, or browse through the Activities below.</p>
                <div class="tag_well">
                    <input type="text" name="form_tags_subjects" value="" id="form_tags_input_3" />
                </div>
            </div>
        </div>
       
        <div class='row buttons rowspacer' id="activitylist">
        	<?php if($sessmasterprfid == 2 || $sessmasterprfid == 3 || $sessmasterprfid == 5 || $sessmasterprfid == 8 || $sessmasterprfid == 9 || $sessmasterprfid == 7) { ?>
            <a class='skip btn mainBtn' href='#library-activities' id='btnlibrary-activities-newactivity'>
                <div class='icon-synergy-add-dark'></div>
                <div class='onBtn'>New<br />Activity</div>
            </a>
            <?php }			
			
				if($sessmasterprfid == 2 || $sessmasterprfid == 3){
					
					$getactivityqry="SELECT c.fld_id AS activityid, c.fld_activity_name AS activityname, fn_shortname(c.fld_activity_name,1) AS shortname 
					                           FROM itc_activity_master AS c WHERE c.fld_delstatus='0' AND c.fld_created_by in (select fld_id from itc_user_master where fld_profile_id='2' and fld_delstatus='0') ".$sqry." 
											   GROUP BY activityid";
				}
				else{				
					$getactivityqry="SELECT c.fld_id AS activityid, c.fld_activity_name AS activityname, 
					                                  c.fld_created_by AS createdby,fn_shortname(c.fld_activity_name,1) AS shortname 
											  FROM itc_activity_master as c 
											  WHERE c.fld_delstatus='0' AND c.fld_created_by='".$uid."' ".$sqry." 
											  GROUP BY activityid 		
											  
											  UNION 		
											  
											  SELECT c.fld_id AS activityid, c.fld_activity_name AS activityname,
											         c.fld_created_by AS createdby,fn_shortname(c.fld_activity_name,1) AS shortname 
											  FROM itc_license_cul_mapping AS a 
											  LEFT JOIN itc_license_track AS b ON a.fld_license_id = b.fld_license_id 
											  RIGHT JOIN itc_unit_master AS d ON d.fld_id=a.fld_unit_id 
											  LEFT JOIN itc_activity_master AS c ON c.fld_unit_id=d.fld_id 
											  WHERE b.fld_district_id='".$districtid."' AND b.fld_school_id='".$schoolid."' 
											        AND b.fld_user_id='".$indid."' AND b.fld_delstatus='0' AND '".date("Y-m-d")."' 
											  BETWEEN b.fld_start_date AND b.fld_end_date AND a.fld_active='1' AND c.fld_delstatus='0' 
											  AND (SELECT fld_profile_id FROM itc_user_master WHERE fld_id=c.fld_created_by)=2  ".$sqry." 
											  GROUP BY activityid";
				}
				
				$qry = $ObjDB->QueryObject($getactivityqry);
				while($res=$qry->fetch_assoc()){
				 
				 extract($res);
                    $contentManager = new contentManager($activityid, 'activity');
                    if($contentManager->disabled) $btn="btnOff";
                    else $btn="mainBtn";
			 	 $checkcreatedby=$ObjDB->SelectSingleValue("SELECT fld_profile_id FROM itc_user_master 
				                                            WHERE fld_id='".$createdby."'");?>
                <a class='skip btn <?=$btn;?>  <?php if($checkcreatedby==2 || $checkcreatedby==3){?>pit<?php } ?>' onclick="checkContent(<?=$activityid;?>, 'activity')" href='#library-activities' name="<?php echo $activityid;?>" id='btnlibrary-activities-actions'>
                    <div class='icon-synergy-activities'></div>
                    <div class='onBtn tooltip' title="<?php echo $activityname;?>"><?php echo $shortname;?></div>
                </a>      
            <?php }?>
        </div>
    </div>
</section>
<?php
	@include("footer.php");