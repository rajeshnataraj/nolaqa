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
$proid = $itemid[0];
$catid = $itemid[2];

if($itemid[1]!='')
{
	$productname = $itemid[1];
}
else
{
	$productname = '';
}

$sid= isset($method['sid']) ? $method['sid'] : '0';
$catids = isset($method['catids']) ? $method['catids'] : '0';

$sqry='';
if($sid!=0){
	$sid = explode(',',$sid);
	for($i=0;$i<sizeof($sid);$i++){	
		$id = explode('_',$sid[$i]);
		if($id[1]=='items'){				
			$sqry.= " AND fld_id =".$id[0];
		}
		else{	
			$itemqry = $ObjDB->QueryObject("SELECT fld_item_id FROM itc_main_tag_mapping 
										   WHERE fld_tag_id='".$sid[$i]."' 
										   AND fld_access='1' AND fld_tag_type='42'");
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
<section data-type='#sim-items-items' id='sim-items-items'>
	<script type="text/javascript" charset="utf-8">	
		$.getScript('sim/items/sim-items-items.js');
        $(function(){				
            var t4 = new $.TextboxList('#form_tags_items', {
                startEditableBit: false,
                inBetweenEditableBits: false,
                plugins: {
                    autocomplete: {
                        onlyFromValues:true,
                        queryRemote: true,
                        remote: { url: 'autocomplete.php', extraParams: "oper=search&tag_type=42&items=<?php echo '2~'.$catid; ?>" },
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
           
			var sid = $('#form_tags_items').val();
			var catid =$('#catid').val();
			$("#simitems").load("sim/items/sim-items-items.php #simitems > *",{'sid':sid,'catids':catid});
			removesections('#sim-items-newitems');
        }
    </script>
	
	
	<div class='container'>
		<div class='row'>
			<div class='twelve columns'>
				<p class="dialogTitle">Fields - <?php echo $productname; ?></p>
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
								<input type="text" name="form_tags_items" value="" id="form_tags_items" />
							</div>
						</dt>               
					</dl>
				</div>
			</div>
		</div>
		<div class='row buttons' id="simitems">
			<?php if($sessmasterprfid == 2) 
			{ ?>
				<div id="large_icon_studentlist" style="float:left;">
					<a class='skip btn mainBtn' href='#sim-items-newitems' id='btnsim-items-newitems' name='0,<?php echo $catid.",".$proid ?>'>
						<div class="icon-synergy-add-dark"></div>
						<div class='onBtn'>New Field</div>
					</a>
				</div>
			<?php 
			}
				if($sid!='0')
				{
					$product=$ObjDB->NonQuery("SELECT fld_id as id,fld_define_field as proname FROM itc_sim_items WHERE fld_cat_id='".$catids."' AND fld_pro_id='".$proid."' AND fld_delstatus='0' ".$sqry." ");
				}
				else
				{
					/* old query */
					$product=$ObjDB->NonQuery("SELECT fld_id as id,fld_define_field as proname FROM itc_sim_items WHERE fld_cat_id='".$catid."' AND fld_pro_id='".$proid."' AND fld_delstatus='0'");
					
					/**** New Query ****/					
				}

				if($product->num_rows>0) 
				{	
					while($rowpro=$product->fetch_assoc())
					{
						extract($rowpro);
					?>
					<div id="large_icon_studentlist" style="float:left;">
						<a class='skip btn mainBtn' href='#sim-items-action' id='btnsim-items-action' name='<?php echo $catid.",".$proname.",".$proid.",".$id;?>'>
							<div class="icon-synergy-modules"></div>
							<div class='onBtn'><?php echo $proname; ?></div>
						</a>
					</div>
					<?php
						
					}
				}
				
			?>
		</div>
		<input type="hidden" name="catid" id="catid" value="<?php echo $catid;?>">
		
		
	</div>
</section>
<?php
	@include("footer.php");
