<?php 
/*------
		Page - library-sosunits
		Description:
		Show the science of speed units list
		
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
			if($id[1]=='phase'){
				$sqry.= " and c.fld_id =".$id[0];
			}		
			else{				
				$itemqry = $ObjDB->QueryObject("select fld_item_id from itc_main_tag_mapping where fld_tag_id='".$sid[$i]."' and fld_access='1' and fld_tag_type='36'");
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

<section data-type='#library-phase' id='library-phase'>
  <script type="text/javascript" charset="utf-8">
      $.getScript("library/phase/library-phase.js");
		$(function(){				
        	var t4 = new $.TextboxList('#form_tags_phases', {
                	startEditableBit: false,
                	inBetweenEditableBits: false,
                	plugins: {
                    	autocomplete: {
                       		onlyFromValues:true,
                        	queryRemote: true,
                        	remote: { url: 'autocomplete.php', extraParams: "oper=searchsosphase&tag_type=36&subject=1&course=1&unit=1" },
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
            var sid = $('#form_tags_phases').val();
            $("#phaselist").load("library/phase/library-phase.php #phaselist > *",{'sid':sid});
            removesections('#library-phase');
        }
		
		
    </script>
  <div class='container'>
    <div class='row'>
      <div class='twelve columns'>
        <p class="darkTitle">Phases</p>
        <p class="darkSubTitle"></p>
      </div>
    </div>
    <div class='row'>
      <div class='twelve columns'>
        <!--<p class="filterLightTitle">To filter this list, search by
          <?php if($sessmasterprfid==2){?>
          Tag Name and
          <?php }?>
          Phase Name.</p>-->
          <p class="filterLightTitle">To see the list of Phases available, search by Phase name, 
          <?php if($sessmasterprfid==2){?>
          Tag name, 
          <?php }?>
          or browse through the options below.</p>
        <div class="tag_well">
          <input type="text" name="test3" value="" id="form_tags_phases" />
        </div>
      </div>
    </div>
    <div class='row buttons rowspacer' id="phaselist">
      <?php if($sessmasterprfid == 2) { ?>
      <a class='skip btn mainBtn' href='#library-phase' id='btnlibrary-phase-newphases'>
      <div class='icon-synergy-add-dark'></div>
      <div class='onBtn'>New Phase</div>
      </a>
      <?php 
			}
			if($sessmasterprfid == 2)
			{
                            
				$qry = "SELECT c.fld_id AS phaseid, c.fld_unit_id AS content_id, c.fld_phase_name AS phasename, fn_shortname (c.fld_phase_name, 1) AS shortname, 
							   c.fld_phase_icon AS phaseicon 
						FROM itc_sosphase_master AS c 
						WHERE c.fld_delstatus = '0' ".$sqry." 
						GROUP BY c.fld_id ";
			}	
			
			
			$qrytogetallunits = $ObjDB->QueryObject($qry);
			
			if($qrytogetallunits->num_rows>0)
			{
				while($res=$qrytogetallunits->fetch_assoc()){
					extract($res);

                    $contentManager = new contentManager($content_id, 'sos');
                    if($contentManager->disabled) $btn="btnOff";
                    else $btn="mainBtn";
			?>
      <a class='skip btn <?=$btn;?>' onclick="checkContent(this)" data-category="sos" data-content-id="<?=$content_id;?>" href='#library-phase' id='btnlibrary-phase-actions' name="<?php echo $phaseid.','.$phasename;?>">
      <div class="icon-synergy-courses"> <img class="thumbimg" src="thumb.php?src=<?php echo __CNTUNITICONPATH__.$phaseicon; ?>&w=40&h=40&q=100" /> </div>
      <div class='onBtn tooltip' title="<?php echo $phasename;?>"><?php echo $shortname; ?></div>
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
