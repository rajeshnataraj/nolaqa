<?php 
@include("sessioncheck.php");

$id = isset($method['id']) ? $method['id'] : '';

$id=explode(",",$id);


$definefieldname = $ObjDB->SelectSingleValue("SELECT fld_define_field FROM itc_sim_items WHERE fld_id='".$id[3]."' ANd fld_delstatus='0'");

?>
<script>
	$.getScript("sim/items/sim-items-items.js");
</script>
<section data-type='#sim-items-action' id='sim-items-action'>
  <div class='container'>
    <div class='row'>
      <div class='twelve columns'>
      	<p class="dialogTitle"><?php echo $definefieldname;?></p>
        <p class="dialogSubTitleLight">&nbsp;</p>
      </div>
    </div>
     
    <div class='row buttons'>
		
		<a class='skip btn mainBtn' href='#sim-items-view' id='btnsim-items-view' name='<?php echo $id[3];?>'>
			<div class='icon-synergy-view'></div>
			<div class='onBtn'>View</div>
		</a>
		<?php if($sessmasterprfid == 2) 
		{ ?>
			<a class='skip btn mainBtn' href='#sim-items-newitems' id='btnsim-items-newitems' name='<?php echo $id[3].",".$id[0].",".$id[2];?>'>
				<div class="icon-synergy-edit"></div>
				<div class='onBtn'>Edit</div>
			</a>
			<a class='skip btn main' href='#sim-items' onclick="fn_deletefields(<?php echo $id[3].",".$id[0].",".$id[2];?>)">
                <div class='icon-synergy-trash'></div>
                <div class='onBtn'>Delete</div>
            </a>
		<?php 
		} ?>
		<a class='skip btn mainBtn' href='#sim-items-newfielditem' id='btnsim-items-newfielditem' name='<?php echo $id[0].",".$id[1].",".$id[2].",".$id[3];?>'>
			<div class='icon-synergy-courses'></div>
			<div class='onBtn'>Item</div>
		</a>
     
    </div>
  </div>
</section>
<?php
	@include("footer.php");