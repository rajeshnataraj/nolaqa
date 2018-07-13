<?php 
/*------
		Page - library-units
		Description:
		Show the units list
		
		Actions Performed:
			New unit - Create a new unit
		
		History:
				
------*/
	
	@include("sessioncheck.php");
	$sid = isset($method['sid']) ? $method['sid'] : '0';
	$sqry='';
	if($sid!=0){
		$sid = explode(',',$sid);
		for($i=0;$i<sizeof($sid);$i++){
			$id = explode('_',$sid[$i]);
			if($id[1]=='unit'){
				$sqry.= " and c.fld_id =".$id[0];
			}		
			else{				
				$itemqry = $ObjDB->QueryObject("select fld_item_id from itc_main_tag_mapping where fld_tag_id='".$sid[$i]."' and fld_access='1' and fld_tag_type='4'");
				$sqry = "and (";
				$j=1;
				while($itemres = $itemqry->fetch_assoc()){
					extract($itemres);
					if($j==$itemqry->num_rows){
						$sqry.=" c.fld_id=".$fld_item_id.")";
					}
					else{
						$sqry.=" c.fld_id=".$fld_item_id." or";
					}
					$j++;
				}
			}
		}		
	}
?>

<section data-type='2home' id='library-units'> 
  <script type="text/javascript" charset="utf-8">		
		$.getScript("library/units/library-units.js");	
		$(function(){				
        	var t4 = new $.TextboxList('#form_tags_units', {
                	startEditableBit: false,
                	inBetweenEditableBits: false,
                	plugins: {
                    	autocomplete: {
                       		onlyFromValues:true,
                        	queryRemote: true,
                        	remote: { url: 'autocomplete.php', extraParams: "oper=search&tag_type=4&subject=1&course=1&unit=1" },
                        	placeholder: ''
                    	}
                	},
                	bitsOptions:{editable:{addKeys: [188]}}													
            	});																	
            
            	t4.addEvent('bitAdd',function(bit) {
                	fn_loadunit();
            	});
            
            	t4.addEvent('bitRemove',function(bit) {
                	fn_loadunit();
            	});					
        });	
            
        function fn_loadunit(){
            var sid = $('#form_tags_units').val();
            $("#unitlist").load("library/units/library-units.php #unitlist > *",{'sid':sid});
            removesections('#library-units');
        }
		
		
    </script>
  <div class='container'>
    <div class='row'>
      <div class='twelve columns'>
        <p class="darkTitle">Units</p>
        <p class="darkSubTitle"></p>
      </div>
    </div>
    <div class='row'>
      <div class='twelve columns'>
        <!--<p class="filterLightTitle">To filter this list, search by
          <?php if($sessmasterprfid==2 || $sessmasterprfid==3){?>
          Tag Name and
          <?php }?>
          Unit Name.</p>-->
          <p class="filterLightTitle">Search by Unit name, Tag name, or browse through the complete list of Units below. 
          </p>
        <div class="tag_well">
          <input type="text" name="test3" value="" id="form_tags_units" />
        </div>
      </div>
    </div>
    <div class='row buttons rowspacer' id="unitlist">
      <?php if($sessmasterprfid == 2 || $sessmasterprfid == 3) { ?>
      <a class='skip btn mainBtn' href='#library-units' id='btnlibrary-units-newunits'>
      <div class='icon-synergy-add-dark'></div>
      <div class='onBtn'>New Unit</div>
      </a>
      <?php 
			}
			if($sessmasterprfid == 2 || $sessmasterprfid == 3)
			{
				$qry = "SELECT c.fld_id AS unitid, c.fld_unit_name AS unitname, fn_shortname (c.fld_unit_name, 1) AS shortname, 
							   c.fld_unit_icon AS uniticon 
						FROM itc_unit_master AS c 
						WHERE c.fld_delstatus = '0' ".$sqry." 
						GROUP BY c.fld_id ";
			}	
			else {
					$qry ="SELECT a.fld_unit_id AS unitid, c.fld_unit_name AS unitname, fn_shortname (c.fld_unit_name, 1) AS shortname, 
								  c.fld_unit_icon AS uniticon 
							FROM  itc_license_cul_mapping AS a 
								  LEFT JOIN itc_license_track AS b 
								  ON a.fld_license_id = b.fld_license_id 
								  RIGHT JOIN itc_unit_master AS c 
								  ON a.fld_unit_id = c.fld_id 
							WHERE b.fld_district_id = '".$districtid."' AND b.fld_school_id = '".$schoolid."' 
								  AND b.fld_user_id = '".$indid."'  AND b.fld_delstatus = '0' 
								  AND '".date("Y-m-d")."' BETWEEN b.fld_start_date 
								  AND b.fld_end_date   AND a.fld_active = '1' 
								  AND c.fld_delstatus = '0' ".$sqry." 
							GROUP BY a.fld_unit_id ";
			}
			
			$qrytogetallunits = $ObjDB->QueryObject($qry);
			
			if($qrytogetallunits->num_rows>0)
			{
				while($res=$qrytogetallunits->fetch_assoc()){
					extract($res);
			?>
      <a class='skip btn mainBtn' href='#library-units' id='btnlibrary-units-actions' name='<?php echo $unitid.",".$unitname;?>'>
      <div class="icon-synergy-units"> <img class="thumbimg" src="thumb.php?src=<?php echo __CNTUNITICONPATH__.$uniticon; ?>&w=40&h=40&q=100" /> </div>
      <div class='onBtn tooltip' title="<?php echo $unitname;?>"><?php echo $shortname; ?></div>
      </a>
      <?php
				}
			}
          	?>
    </div>
  </div>
</section>
<?php
	@include("footer.php");
