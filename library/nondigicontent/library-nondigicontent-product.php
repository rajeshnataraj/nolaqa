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
		if($id[1]=='nondigital'){				
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
<section data-type='#2home' id='library-nondigicontent-newcategory'>
	
	<script type="text/javascript" charset="utf-8">	
		$.getScript('library/nondigicontent/library-nondigicontent-product.js');
        $(function(){				
            var t4 = new $.TextboxList('#form_tags_nondigital', {
                startEditableBit: false,
                inBetweenEditableBits: false,
                plugins: {
                    autocomplete: {
                        onlyFromValues:true,
                        queryRemote: true,
                        remote: { url: 'autocomplete.php', extraParams: "oper=search&tag_type=43&nondigital=<?php echo '1~'.$catid; ?>" }, // this catid is product id
                        placeholder: ''
                    }
                },
                bitsOptions:{editable:{addKeys: [188]}}													
            });																	
            
            t4.addEvent('bitBoxAdd',function(bit) {
                fn_loadnondigital();
            });
            
            t4.addEvent('bitRemove',function(bit) {
                fn_loadnondigital();
            });					
                
        });	
		
        function fn_loadnondigital(){
            var sid = $('#form_tags_nondigital').val();
			var cid =$('#cid').val();
			$("#nondigitalproduct").load("library/nondigicontent/library-nondigicontent-product.php #nondigitalproduct > *",{'sid':sid,'id':cid});
			removesections('#library-nondigicontent-product');
        }
        
    </script>
	
	<div class='container'>
		<div class='row'>
			<div class='twelve columns'>
				<p class="dialogTitle"><?php echo $catname;?></p>
				<p class="dialogSubTitleLight">&nbsp;</p>
			</div>
		</div>
		<div class="sdesc" id="Types6">
			<div class='row' style="padding-bottom:20px;"> 
				<div class='twelve columns'>
					<p class="filterLightTitle" style="color:#48708a;">To filter this list, search by Student Name.</p>
					<dl class='field row'>
						<dt>
							<div class="tag_well">
								<input type="text" name="form_tags_nondigital" value="" id="form_tags_nondigital" />
							</div>
						</dt>               
					</dl>
				</div>
			</div>
		</div>
		<div class='row buttons' id="nondigitalproduct">
			<?php if($sessmasterprfid == 2) 
			{ ?>
				<div id="large_icon_studentlist" style="float:left;">
					<a class='skip btn mainBtn' href='#library-nondigicontent-newproduct' id='btnlibrary-nondigicontent-newproduct' name='<?php echo "0,".$catid;?>'>
						<div class="icon-synergy-add-dark"></div>
						<div class='onBtn'>New Product</div>
					</a>
					<a class='skip btn mainBtn' href='#library-nondigicontent-newcategory' id='btnlibrary-nondigicontent-newcategory' name='<?php echo $catid;?>'>
						<div class="icon-synergy-edit"></div>
						<div class='onBtn'>Edit Category</div>
					</a>
				</div>
			<?php 
			 	
			$product=$ObjDB->NonQuery("SELECT fld_id as proid,fn_shortname (fld_product_name,1) as proname, fld_product_name AS fullname FROM itc_nondigicontent_product WHERE fld_nondigicat_id='".$catid."' AND fld_delstatus='0' ".$sqry." ");
				
			}
			else 
			{
				
				$product=$ObjDB->NonQuery("SELECT a.fld_id as proid,fn_shortname (a.fld_product_name,1) as proname, a.fld_product_name AS fullname,a.fld_product_code as productcode
										FROM itc_nondigicontent_product AS a
										LEFT JOIN itc_license_nondigicontentproduct_mapping AS b ON a.fld_id = b.fld_product_id
										WHERE a.fld_delstatus='0' ".$sqry." 
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
					<a class='skip btn mainBtn' href='#library-nondigicontent-productaction' id='btnlibrary-nondigicontent-productaction' name='<?php echo $proid.",".$proname.",".$catid;?>' >
						<div class="icon-synergy-tests"></div>
						<div class='onBtn'title="<?php echo $fullname; ?>" ><?php echo $proname; ?></div>
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
