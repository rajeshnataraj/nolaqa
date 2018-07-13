<?php 
@include("sessioncheck.php");

$id = isset($method['id']) ? $method['id'] : '';

$id=explode(",",$id);

?>
<script>
	$.getScript("sim/category/sim-category.js");
</script>
<section data-type='#sim-category-action' id='sim-category-action'>
  <div class='container'>
    <div class='row'>
      <div class='twelve columns'>
      	<p class="dialogTitle"><?php echo $id[1];?></p>
        <p class="dialogSubTitleLight">&nbsp;</p>
      </div>
    </div>
     
    <div class='row buttons'>
		
		<!--<a class='skip btn mainBtn' href='#sim-category' id='btnsim-category-view' name='<?php //echo $id[0];?>'>
			<div class='icon-synergy-view'></div>
			<div class='onBtn'>View</div>
		</a>
		<a class='skip btn mainBtn' href='#sim-category' id='btnsim-category-newcategory' name='<?php //echo $id[0];?>'>
			<div class="icon-synergy-edit"></div>
			<div class='onBtn'>Edit</div>
		</a>-->
		<a class='skip btn mainBtn' href='#sim-product-product' id='btnsim-product-product' name='<?php echo $id[0];?>'>
			<div class='icon-synergy-courses'></div>
			<div class='onBtn'>Products</div>
		</a>
     
    </div>
  </div>
</section>
<?php
	@include("footer.php");