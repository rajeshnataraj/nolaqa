<?php 
	@include("sessioncheck.php");
	//$sid have tag ids
	$sid = isset($method['sid']) ? $method['sid'] : '0';
        $lid = isset($method['lid']) ? $method['lid'] : '0';
	$sqry='';
	if($sid!=0){
		$sid = explode(',',$sid);
		for($i=0;$i<sizeof($sid);$i++){	
			//getting license id from the tag which you have selected in this page, fld_item_id is license id	
			$itemqry = $ObjDB->QueryObject("SELECT fld_item_id 
											FROM itc_main_tag_mapping 
											WHERE fld_tag_id='".$sid[$i]."' AND fld_access='1' AND fld_tag_type='18'");
			$sqry = "and (";
			$j=1;
			while($itemres = $itemqry->fetch_assoc()){
				extract($itemres);
				if($j==$itemqry->num_rows){
					$sqry.=" a.fld_id=".$fld_item_id.")";
				}
				else{
					$sqry.=" a.fld_id=".$fld_item_id." or";
				}
				$j++;
			}
		}
	}
        
        if($lid!=0){
	$lid = explode(',',$lid);
		for($i=0;$i<sizeof($lid);$i++){		
			$itemqry = $ObjDB->QueryObject("SELECT fld_id FROM itc_license_master 
								            WHERE fld_id='".$lid[$i]."' AND fld_delstatus='0'");
			$sqry = "and (";
			$j=1;
			while($itemres = $itemqry->fetch_assoc()){
				extract($itemres);
				if($j==$itemqry->num_rows){
					$sqry.=" a.fld_id=".$fld_id.")";
				}
				else{
					$sqry.=" a.fld_id=".$fld_id." or";
				}
				$j++;
			}
		}
	}
?>
<script type="text/javascript" charset="utf-8">		
	$(function(){				
		var t4 = new $.TextboxList('#form_tags_licenses', {
			unique: true, 
			startEditableBit: false,
			inBetweenEditableBits: false,
			plugins: {
				autocomplete: {
					onlyFromValues:true,
					queryRemote: true,
					remote: { url: 'autocomplete.php', extraParams: "oper=search&tag_type=18" },
					placeholder: ''
				}
			},
			bitsOptions:{editable:{addKeys: [188]}}													
		});	
		// this is for when adding the tag to filters 
		t4.addEvent('bitAdd',function(bit) {
			fn_loadlicense();
		});
		
		// this is for removing the tag from filters 
		t4.addEvent('bitRemove',function(bit) {
			fn_loadlicense();
		});					
			
	});	

	function fn_loadlicense(){
		//sid is tag ids
		var sid = $('#form_tags_licenses').val();		
		$("#listlicense").load("licenses/licenses.php #listlicense > *",{'sid':sid},function(){
			 $('#tablecontents').slimscroll({
                    height:'auto',
                    railVisible: false,
                    allowPageScroll: false,
                    railColor: '#F4F4F4',
                    opacity: 9,
                    color: '#88ABC2',
                });
			});
			
		removesections("#licenses");
	}
	
        
        $(function(){				
		var t4 = new $.TextboxList('#form_tags_licensesname', {
			unique: true, 
			startEditableBit: false,
			inBetweenEditableBits: false,
			plugins: {
				autocomplete: {
					onlyFromValues:true,
					queryRemote: true,
					remote: { url: 'autocomplete.php', extraParams: "oper=searchlicensename&tag_type=18" },
					placeholder: ''
				}
			},
			bitsOptions:{editable:{addKeys: [188]}}													
		});	
		// this is for when adding the tag to filters 
		t4.addEvent('bitAdd',function(bit) {
			fn_loadlicensename();
		});
		
		// this is for removing the tag from filters 
		t4.addEvent('bitRemove',function(bit) {
			fn_loadlicensename();
		});					
			
	});	

	function fn_loadlicensename(){
		//sid is tag ids
		var sid = $('#form_tags_licensesname').val();		
		$("#listlicense").load("licenses/licenses.php #listlicense > *",{'lid':sid},function(){
	 $('#tablecontents').slimscroll({
                    height:'auto',
                    railVisible: false,
                    allowPageScroll: false,
                    railColor: '#F4F4F4',
                    opacity: 9,
                    color: '#88ABC2',
                });
			});
                
		removesections("#licenses");
	}
        
        
	
	 $('#tablecontents').slimscroll({
                    height:'auto',
                    railVisible: false,
                    allowPageScroll: false,
                    railColor: '#F4F4F4',
                    opacity: 9,
                    color: '#88ABC2',
                });
                
                /* for radio button options in title  */
        $("input[name='types']").click(function() {  
            var test = $(this).val();
            $("div.sdesc").hide();
            $("#types" + test).show(); 
        });
                
</script>
<section data-type='2home' id='licenses'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Licenses</p>
                <p class="dialogSubTitle">&nbsp;</p>     
            </div>
        </div>
        <?php 
          if($sessmasterprfid == 2) 
          { 
        ?>
            <div class="row" id="RadioGroup">
            <div class='twelve columns'>
                <font style="color:white">Sort/filter the license list using: 
                <input type="radio" id="tag" name="types"  value="5" />Tag
                <input type="radio" id="search" name="types" checked="checked" value="6" />License Name
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
            	<p class="filterLightTitle">Filter this list by License Name.</p>
                <div class="tag_well">
                	<input type="text" name="form_tags_licensesname" value="" id="form_tags_licensesname" />
              	</div>
            </div>
       </div>
        
        <div class='row'>
            
                <div class='span10 offset1' id="listlicense" >  
                <table id="test" class='table table-hover table-striped table-bordered setbordertopradius'>
                    <thead class='tableHeadText'>
                        <tr>
                            <th style="width:40%" >License type</th>
                            <th style="width:20%" >Type</th>
                            <th style="width:30%" class='centerText'>Number of license holders</th>
                            <th style="width:20%" class='centerText'>Duration</th>
                        </tr>
                        
                    </thead>
                    <tbody>
                        <tr class="mainBtn" id="btnlicenses-newlicense" name="0">
                            <td colspan="3" class="createnewtd"><span class="icon-synergy-create small-icon"></span>&nbsp;&nbsp;&nbsp;Create new license</td>               
                        </tr>
                        </tbody>
                    </table>
                    <div style="max-height:400px;width:100%" id="tablecontents"  >
                    <table class='table table-hover table-striped table-bordered bordertopradiusremove'>
                    <tbody>
                        <?php 
						
							//get the license details from the below query
                            $getlicenseqry ="SELECT a.fld_content_type as contenttype,a.fld_id AS licenseid, a.fld_license_name AS licensename,(CASE 
													WHEN (a.fld_duration_type = 1 AND a.fld_duration>1) THEN 'Months' WHEN (a.fld_duration_type = 1 AND
													a.fld_duration=1) THEN 'Month' WHEN (a.fld_duration_type = 2 AND a.fld_duration>1) THEN 'Years' WHEN 
													(a.fld_duration_type = 2 AND a.fld_duration=1) THEN 'Year' END) AS durationtype, a.fld_duration AS 
													duration,  COUNT(b.fld_id) AS cnt 
											FROM itc_license_master AS a 
											LEFT JOIN itc_license_track AS b ON a.fld_id=b.fld_license_id 
											WHERE a.fld_delstatus='0' ".$sqry." 
											GROUP BY a.fld_id 
											ORDER BY a.fld_license_name";
							$licensedetails = $ObjDB->QueryObject($getlicenseqry);
                            if($licensedetails->num_rows > 0){
                                while($res=$licensedetails->fetch_assoc()){
                                extract($res);
								//This query is used for get the number of license holders for each license. The users includes district,school,individual user
								$getlicenseholderqry = "SELECT SUM(a.cnt) AS coun 
														FROM( SELECT COUNT(DISTINCT(fld_district_id)) AS cnt 
																FROM itc_license_track 
																WHERE fld_license_id='".$licenseid."' AND fld_school_id=0 AND fld_user_id=0 
																AND fld_delstatus='0' AND fld_district_id IN(SELECT fld_id FROM itc_district_master 
																WHERE fld_delstatus='0') 
														UNION ALL SELECT COUNT(DISTINCT(fld_school_id)) AS cnt FROM itc_license_track WHERE 
																fld_license_id='".$licenseid."' AND fld_user_id=0 AND fld_delstatus='0' AND fld_school_id
																IN(SELECT fld_id FROM itc_school_master WHERE fld_delstatus='0') 
														UNION ALL SELECT COUNT(DISTINCT(fld_user_id)) AS cnt FROM itc_license_track 
																WHERE fld_license_id='".$licenseid."' AND fld_school_id=0 AND fld_delstatus='0' 
																AND fld_user_id IN(SELECT fld_id FROM itc_user_master WHERE fld_delstatus='0')) AS a";
                        ?>	
                                <tr class="mainBtn <?php echo $licenseid; ?>" id="btnlicenses-newlicense-actions" name="<?php echo $licenseid; ?>">
                                    <td style="width:40%" ><?php echo $licensename; ?></td>
                                    <td style="width:20%" ><?php if($contenttype==1){ echo "ITC"; }else{ echo "SOS"; } ?></td>
                                    <td style="width:30%" class='centerText'><?php echo $ObjDB->SelectSingleValueInt($getlicenseholderqry); ?></td>
                                    <td style="width:20%" class='centerText'><?php echo $duration." ".$durationtype; ?></td>
                                </tr>
                        <?php
                                }
                            }
                        ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
	@include("footer.php");