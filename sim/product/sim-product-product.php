<?php
/*------
	Page - sim
	Description:
		Showing the menus related with sim / accounts
		new page created by chandru 
	History:	
		
------*/
@include("sessioncheck.php");

//error_reporting(E_ALL);
//ini_set("display_errors","1");

$date=date("Y-m-d H:i:s");

$id= isset($method['id']) ? $method['id'] : '';
$id=explode(",",$id);
$catid = $id[0]; // 1 time catid and another time productid
$catname = $id[1];

$sid= isset($method['sid']) ? $method['sid'] : '0';

$sqry='';
if($sid!=0){
	$sid = explode(',',$sid);
	for($i=0;$i<sizeof($sid);$i++){	
		$id = explode('_',$sid[$i]);
		if($id[1]=='product'){				
			$sqry.= " AND fld_id =".$id[0];
		}
		else{	
			$itemqry = $ObjDB->QueryObject("SELECT fld_item_id FROM itc_main_tag_mapping 
										   WHERE fld_tag_id='".$sid[$i]."' 
										   AND fld_access='1' AND fld_tag_type='41'");
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
<section data-type='#sim-product-product' id='sim-product-product'>
	
	<script type="text/javascript" charset="utf-8">	
		$.getScript('sim/product/sim-product-product.js');
        $(function(){				
            var t4 = new $.TextboxList('#form_tags_product', {
                startEditableBit: false,
                inBetweenEditableBits: false,
                plugins: {
                    autocomplete: {
                        onlyFromValues:true,
                        queryRemote: true,
                        remote: { url: 'autocomplete.php', extraParams: "oper=search&tag_type=41&product=<?php echo '1~'.$catid; ?>" }, // this catid is product id
                        placeholder: ''
                    }
                },
                bitsOptions:{editable:{addKeys: [188]}}													
            });																	
            
            t4.addEvent('bitBoxAdd',function(bit) {
                fn_loadproduct();
            });
            
            t4.addEvent('bitRemove',function(bit) {
                fn_loadproduct();
            });					
                
        });	
		
        function fn_loadproduct(){
            var sid = $('#form_tags_product').val();
			var cid =$('#cid').val();
			$("#simproduct").load("sim/product/sim-product-product.php #simproduct > *",{'sid':sid,'id':cid});
			removesections('#sim-product-newproduct');
        }
    </script>
	
	<div class='container'>
		<div class='row'>
			<div class='twelve columns'>
				<p class="dialogTitle"><?php echo "Product - ".$catname;?></p>
				<p class="dialogSubTitleLight">&nbsp;</p>
			</div>
		</div>
		<div class="sdesc" id="Types6">
			<div class='row' style="padding-bottom:20px;"> 
				<div class='twelve columns'>
					<p class="filterLightTitle" style="color:#48708a;">To filter this list, search by Product Name.</p>
					<dl class='field row'>
						<dt>
							<div class="tag_well">
								<input type="text" name="form_tags_product" value="" id="form_tags_product" />
							</div>
						</dt>               
					</dl>
				</div>
			</div>
		</div>
		<div class='row buttons' id="simproduct">
			<?php if($sessmasterprfid == 2) 
			{ ?>
				<div id="large_icon_studentlist" style="float:left;">
					<a class='skip btn mainBtn' href='#sim-product-newproduct' id='btnsim-product-newproduct' name='<?php echo "0,".$catid;?>'>
						<div class="icon-synergy-add-dark"></div>
						<div class='onBtn'>New Product</div>
					</a>
					<a class='skip btn mainBtn' href='#sim-category' id='btnsim-category-newcategory' name='<?php echo $catid;?>'>
						<div class="icon-synergy-edit"></div>
						<div class='onBtn'>Edit Category</div>
					</a>
				</div>
			<?php 
			$product=$ObjDB->NonQuery("SELECT fld_id as proid,fn_shortname (fld_product_name,1) as proname, CONCAT(fld_product_name, ' ', fld_version_number) AS fullname,fld_product_code as productcode FROM itc_sim_product WHERE fld_cat_id='".$catid."' AND fld_delstatus='0' ".$sqry."  GROUP BY proid ORDER BY proname ASC ");
				
			}
			else 
			{ // Show Teacher Level
				$product=$ObjDB->NonQuery("SELECT a.fld_id as proid,fn_shortname (a.fld_product_name,1) as proname, CONCAT(a.fld_product_name, ' ',a.fld_version_number) AS fullname,a.fld_product_code as productcode
								FROM itc_sim_product AS a
								LEFT JOIN itc_license_simproduct_mapping AS b ON a.fld_id = b.fld_product_id
								LEFT JOIN itc_license_master AS c ON c.fld_id = b.fld_license_id
								LEFT JOIN itc_license_track AS d ON b.fld_license_id = d.fld_license_id
                                                            WHERE d.fld_district_id = '".$districtid."' AND d.fld_school_id = '".$schoolid."' AND d.fld_user_id = '".$indid."' AND a.fld_delstatus='0' AND b.fld_active='1' AND d.fld_start_date<='".$date."' AND d.fld_end_date>='".$date."' AND b.fld_delstatus='0' AND c.fld_delstatus='0' AND b.fld_cat_id='".$catid."'
								GROUP BY a.fld_id 
								ORDER BY a.fld_product_name ASC");
			}

			if($product->num_rows>0) 
			{	
				while($rowpro=$product->fetch_assoc())
				{
					extract($rowpro);
				?>
				<div id="large_icon_studentlist" style="float:left;">
					<a class='skip btn mainBtn' href='#<?php if($sessmasterprfid == 2) {?>sim-product-action<?php } else { ?>sim-document-document<?php }?>' id='<?php if($sessmasterprfid == 2) {?>btnsim-product-action<?php } else { ?>btnsim-document-document<?php }?>' name='<?php echo $proid.",". str_replace(",", "", str_replace("&","and",$fullname)).",".$catid;?>' >
						<div class="icon-synergy-tests"></div>
						<div class='onBtn' title="<?php echo $fullname; ?>" ><?php echo $proname; ?></div>
					</a>
				</div>
				<?php

				}
			}
				
			?>
		</div>
		<input type="hidden" name="proid" id="proid" value="<?php echo $proid;?>">
		<input type="hidden" name="cid" id="cid" value="<?php echo $catid;?>">
		
	</div>
</section>
<?php
	@include("footer.php");
