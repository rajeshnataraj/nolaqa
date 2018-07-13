<?php 
@include("sessioncheck.php");

$id = isset($method['id']) ? $method['id'] : '';

$id=explode(",",$id);

$proid=$id[0];
$catid=$id[1];
$docid=$id[2];
$listicon=$id[3];

if($listicon == '')
{
	$listicon ='0';
}

$docqry = $ObjDB->NonQuery("SELECT fld_document_name AS docname FROM itc_sim_document WHERE fld_id='".$docid."' AND fld_delstatus='0'");
$row = $docqry->fetch_assoc();
extract($row);

?>
<script>
	$.getScript("sim/document/sim-document-document.js");
</script>
<section data-type='#sim-items-action' id='sim-items-action'>
  <div class='container'>
    <div class='row'>
      <div class='twelve columns'>
      	<p class="dialogTitle"><?php echo $docname;?></p>
        <p class="dialogSubTitleLight">&nbsp;</p>
      </div>
    </div>
     
    <div class='row buttons'>
		<?php if($sessmasterprfid == 2) 
		{ ?>
			<a class='skip btn main'  href='#sim-document'  onclick="fn_viewdocument('<?php echo $docid;?>');">
				<div class='icon-synergy-view'></div>
				<div class='onBtn'>View</div>
			</a>
			<a class='skip btn mainBtn' href='#sim-document-newdocument' id='btnsim-document-newdocument' name='<?php echo $docid.",".$catid.",".$proid.",".$listicon;?>'>
				<div class="icon-synergy-edit"></div>
				<div class='onBtn'>Edit</div>
			</a>
			<a class='skip btn main' href='#sim-document' onclick="fn_deletedocument(<?php echo $docid.",".$proid.",".$catid.",".$listicon;?>)">
                <div class='icon-synergy-trash'></div>
                <div class='onBtn'>Delete</div>
            </a>
		<?php 
		} ?>
		
    </div>
  </div>
</section>
<?php
	@include("footer.php");