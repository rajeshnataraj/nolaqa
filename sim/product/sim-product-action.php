<?php 
@include("sessioncheck.php");

$id = isset($method['id']) ? $method['id'] : '';

$id=explode(",",$id);

	$productqry = $ObjDB->NonQuery("SELECT fld_cat_id as catid,CONCAT(fld_product_name, ' ',fld_version_number) AS productname,fld_product_code as productcode FROM itc_sim_product WHERE fld_id='".$id[0]."' ANd fld_delstatus='0'");
	$row = $productqry->fetch_assoc();
	extract($row);

?>
<script language="javascript" type="text/javascript">
	$.getScript("sim/product/sim-product-product.js");
</script>
<section data-type='#sim-product-action' id='sim-product-action'>
  <div class='container'>
    <div class='row'>
      <div class='twelve columns'>
      	<p class="dialogTitle"><?php echo $productname;?></p>
        <p class="dialogSubTitleLight">&nbsp;</p>
      </div>
    </div>
     
    <div class='row buttons'>
		<!--<a class='skip btn mainBtn' href='#sim-product-view' id='btnsim-product-view' name='<?php echo $id[0];?>'>
			<div class='icon-synergy-view'></div>
			<div class='onBtn'>View</div>
		</a>-->
		
		<?php if($sessmasterprfid == 2) 
		{ ?>
			<a class='skip btn mainBtn' href='#sim-product-newproduct' id='btnsim-product-newproduct' name='<?php echo $id[0].",".$id[2];?>'>
				<div class="icon-synergy-edit"></div>
				<div class='onBtn'>Edit Product</div>
			</a>
			<a class='skip btn mainBtn' href='#sim-product-copyproduct' id='btnsim-product-copyproduct' name='<?php echo $id[0].",".$id[2];?>'>
				<div class='icon-synergy-edit'></div>
				<div class='onBtn'>Copy Product</div>
			</a>
			<a class='skip btn main' href='#sim-product' onclick="fn_deleteproduct(<?php echo $id[0].",".$id[2];?>)">
                <div class='icon-synergy-trash'></div>
                <div class='onBtn'>Delete Product</div>
            </a>
		<?php 
		} ?>
		<a class='skip btn mainBtn' href='#sim-document-document' id='btnsim-document-document' name='<?php echo $id[0].",".$id[1].",".$catid;?>'>
			<div class='icon-synergy-courses'></div>
			<div class='onBtn'>Documents</div>
		</a>
     
    </div>
  </div>
</section>
<?php
	@include("footer.php");