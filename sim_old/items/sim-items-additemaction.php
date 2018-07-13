<?php 
@include("sessioncheck.php");

$id = isset($method['id']) ? $method['id'] : '';

$additemids = explode(",",$id);

$catid = $additemids[0];
$proid = $$additemids[2];
$desid = $additemids[3];
$desitemid = $additemids[4];
$desitemname = $additemids[1];

$proids = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_sim_product WHERE fld_cat_id='".$catid."' ANd fld_delstatus='0'");

$qryitem = $ObjDB->QueryObject("SELECT fld_id as id,fld_item_name AS itemname FROM itc_sim_desitem WHERE fld_id='".$desitemid."' ANd fld_delstatus='0'");
$row = $qryitem->fetch_assoc();
	extract($row);
?>
<script>
	$.getScript("sim/items/sim-items-items.js");
</script>
<section data-type='#sim-items-additemaction' id='sim-items-additemaction'>
  <div class='container'>
    <div class='row'>
      <div class='twelve columns'>
      	<p class="dialogTitle"><?php echo $itemname;?></p>
        <p class="dialogSubTitleLight">&nbsp;</p>
      </div>
    </div>
     
    <div class='row buttons'>
		
		<a class='skip btn mainBtn' href='#sim-items-additemview' id='btnsim-items-additemview' name='<?php echo $desitemid;?>'>
			<div class='icon-synergy-view'></div>
			<div class='onBtn'>View</div>
		</a>
		<?php if($sessmasterprfid == 2) 
		{ ?>
			<a class='skip btn mainBtn' href='#sim-items-addnewitem' id='btnsim-items-addnewitem' name='<?php echo $catid.",".$desitemname.",".$proids.",".$desid.",".$desitemid;?>'>
				<div class="icon-synergy-edit"></div>
				<div class='onBtn'>Edit</div>
			</a>
			<a class='skip btn main' href='#sim-items' onclick="fn_deleteitems(<?php echo $catid.",".$proids.",".$desid.",".$desitemid;?>)">
                <div class='icon-synergy-trash'></div>
                <div class='onBtn'>Delete</div>
            </a>
     	<?php 
		}
		else if($sessmasterprfid != 2) 
		{
		?>
			<a class='skip btn mainBtn' href='#sim-items-fileupload' id='btnsim-items-fileupload' name='<?php echo $catid.",".$desitemname.",".$proids.",".$desid.",".$desitemid;?>'>
				<div class="icon-synergy-view"></div>
				<div class='onBtn'>File upload lists</div>
			</a>
		<?php 
		}?>
    </div>
  </div>
</section>
<?php
	@include("footer.php");