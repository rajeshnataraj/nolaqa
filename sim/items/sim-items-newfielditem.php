<?php
/*------
	Page - sim
	Description:
		Showing the menus related with sim / accounts
		new page created by chandru 
	History:	
		
------*/
@include("sessioncheck.php");
$itemids= isset($method['id']) ? $method['id'] : '';
$itemid = explode(',',$itemids);

$catid = $itemid[0];
$proid = $itemid[2];
$desname = $itemid[1];
$desid = $itemid[3];
$ditemid = $itemid[4];

$sid= isset($method['sid']) ? $method['sid'] : '0';
//tag name fillter using code
$catids= isset($method['catids']) ? $method['catids'] : '0';
$proids = isset($method['proids']) ? $method['proids'] : '0';
$itemids = isset($method['itemids']) ? $method['itemids'] : '0';

$sqry='';
if($sid!=0){
	$sid = explode(',',$sid);
	for($i=0;$i<sizeof($sid);$i++){	
		$id = explode('_',$sid[$i]);
		if($id[1]=='additems'){				
			$sqry.= " AND fld_id =".$id[0];
		}
		else{	
			$itemqry = $ObjDB->QueryObject("SELECT fld_item_id FROM itc_main_tag_mapping 
										   WHERE fld_tag_id='".$sid[$i]."' 
										   AND fld_access='1' AND fld_tag_type='43'");
			$sqry = "AND (";
			$j=1;
			while($itemres = $itemqry->fetch_assoc()){
				extract($itemres);
				if($j==$itemqry->num_rows){
					$sqry.=" fld_id=".$fld_item_id.")";
				}
				else{
					$sqry.=" fld_id=".$fld_item_id." or";
				}
				$j++;
			}
		}
	}		
}


?>
<section data-type='#sim-items-newfielditem' id='sim-items-newfielditem'>
	<script type="text/javascript" charset="utf-8">	
	   $.getScript('sim/items/sim-items-items.js');
       $(function(){				
            var t4 = new $.TextboxList('#form_tags_additems', {
                startEditableBit: false,
                inBetweenEditableBits: false,
                plugins: {
                    autocomplete: {
                        onlyFromValues:true,
                        queryRemote: true,
                        remote: { url: 'autocomplete.php', extraParams: "oper=search&tag_type=43&additems=<?php echo '3~'.$catid; ?>" },
                        placeholder: ''
                    }
                },
                bitsOptions:{editable:{addKeys: [188]}}													
            });																	
            
            t4.addEvent('bitBoxAdd',function(bit) {
                fn_loadmodule();
            });
            
            t4.addEvent('bitRemove',function(bit) {
                fn_loadmodule();
            });					
                
        });	
		
        function fn_loadmodule(){
          	
			var sid = $('#form_tags_additems').val();
			var catid =$('#catid').val();
			var proid = $('#proid').val();
		  	var itemid =$('#itemid').val();
						  
			$("#simadditems").load("sim/items/sim-items-newfielditem.php #simadditems > *",{'sid':sid,'catids':catid,'proids':proid,'itemids':itemid});
			removesections('#sim-items-addnewitem');
        }
    </script>
	
	
	<div class='container'>
		<div class='row'>
			<div class='twelve columns'>
				<p class="dialogTitle">Items</p>
				<p class="dialogSubTitleLight">&nbsp;</p>
			</div>
		</div>
		<div class="sdesc" id="Types6">
			<div class='row' style="padding-bottom:20px;">
				<div class='twelve columns'>
					<p class="filterLightTitle">To filter this list, search by Items.</p>
					<dl class='field row'>
						<dt>
							<div class="tag_well">
								<input type="text" name="form_tags_items" value="" id="form_tags_additems" />
							</div>
						</dt>               
					</dl>
				</div>
			</div>
		</div>
		<div class='row buttons' id="simadditems">
			<?php if($sessmasterprfid == 2) 
			{ ?>
				<div id="large_icon_studentlist" style="float:left;">
					<a class='skip btn mainBtn' href='#sim-items-addnewitem' id='btnsim-items-addnewitem' name='<?php echo $itemid[0].",".$itemid[1].",".$itemid[2].",".$itemid[3];?>'>
						<div class="icon-synergy-add-dark"></div>
						<div class='onBtn'>New Items</div>
					</a>
				</div>
			<?php
			}
				if($sid!='0')
				{
					$product =$ObjDB->NonQuery("SELECT fld_id as id,fld_item_name as itemname FROM itc_sim_desitem WHERE fld_cat_id='".$catids."' AND fld_pro_id='".$proids."' AND fld_des_id='".$itemids."' AND fld_delstatus='0' ".$sqry." ");
				}
				else
				{
					$product =$ObjDB->NonQuery("SELECT fld_id as id,fld_item_name as itemname FROM itc_sim_desitem WHERE fld_cat_id='".$catid."' AND fld_pro_id='".$proid."' AND fld_des_id='".$desid."' AND fld_delstatus='0' ".$sqry." " );
				}
				if($product->num_rows>0) 
				{	
					while($rowpro=$product->fetch_assoc())
					{
						extract($rowpro);
					?>
					<div id="large_icon_studentlist" style="float:left;">
						<a class='skip btn mainBtn' href='#sim-items-additemaction' id='btnsim-items-additemaction' name='<?php echo $catid.",".$itemname.",".$proid.",".$desid.",".$id;?>'>
							<div class="icon-synergy-modules"></div>
							<div class='onBtn'><?php echo $itemname; ?></div>
						</a>
					</div>
					<?php
						
					}
				}
				
			?>
		</div>
		<input type="hidden" name="catid" id="catid" value="<?php echo $catid;?>">
		<input type="hidden" name="proid" id="proid" value="<?php echo $proid;?>">
		<input type="hidden" name="itemid" id="itemid" value="<?php echo $desid;?>">
		<input type="hidden" name="ditemid" id="ditemid" value="<?php echo $ditemid;?>">
		
	</div>
</section>
<?php
	@include("footer.php");
