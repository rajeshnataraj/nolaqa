<?php 
@include("sessioncheck.php");

$id = isset($method['id']) ? $method['id'] : '';

$id=explode(",",$id);

	$productqry = $ObjDB->NonQuery("SELECT fld_product_name AS productname,fld_product_key as productkey FROM itc_sim_product WHERE fld_id='".$id[0]."' ANd fld_delstatus='0'");
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
		<a class='skip btn mainBtn' href='#sim-product-view' id='btnsim-product-view' name='<?php echo $id[0];?>'>
			<div class='icon-synergy-view'></div>
			<div class='onBtn'>View</div>
		</a>
		<?php if($sessmasterprfid == 2) 
		{ ?>
			<a class='skip btn mainBtn' href='#sim-product-newproduct' id='btnsim-product-newproduct' name='<?php echo $id[0].",".$id[2];?>'>
				<div class="icon-synergy-edit"></div>
				<div class='onBtn'>Edit</div>
			</a>
			<a class='skip btn main' href='#sim-product' onclick="fn_deleteproduct(<?php echo $id[0].",".$id[2];?>)">
                <div class='icon-synergy-trash'></div>
                <div class='onBtn'>Delete</div>
            </a>
		<?php 
		} ?>
		<a class='skip btn mainBtn' href='#sim-items-items' id='btnsim-items-items' name='<?php echo $id[0].",".$id[1].",".$id[2];?>'>
			<div class='icon-synergy-courses'></div>
			<div class='onBtn'>Fields</div>
		</a>
     
    </div>
  </div>
</section>
<?php
	@include("footer.php");