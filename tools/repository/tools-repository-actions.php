<?php 
@include("sessioncheck.php");

$id = isset($method['id']) ? $method['id'] : '';

$qry = $ObjDB->QueryObject("SELECT fld_id,fld_repository_name,fld_created_by FROM itc_repository_master WHERE fld_delstatus='0' and fld_id='".$id."'");
$resqry=$qry->fetch_object();?>
<script type='text/javascript'>
$.getScript("tools/repository/tools-repository-newrepository.js");
</script>
<section data-type='#tools-repository' id='tools-repository-actions'>
  <div class='container'>
    <div class='row'>
            <div class="span10">
                <p class="dialogTitle"><?php echo $resqry->fld_repository_name." "."Actions";?></p>
                <p class="dialogSubTitleLight">&nbsp;</p>
            </div>
        </div>
    <div class='row buttons'>
    
          <a class='skip btn mainBtn' href='#tools-repository' id='btntools-repository-viewrepository' name='<?php echo $id;?>'>
            <div class='icon-synergy-view'></div>
            <div class='onBtn'>View</div>
          </a>
        
         <?php if($sessprofileid == 2 || $sessprofileid == 3) { if($resqry->fld_created_by== $uid) { ?> 
          <a class='skip btn mainBtn' href='#tools-repository' id='btntools-repository-newrepository' name='<?php echo $id;?>'>
            <div class='icon-synergy-edit'></div>
            <div class='onBtn'>Edit</div>
          </a>
          <a class='skip btn main' href='#tools-repository' onclick="fn_delete(<?php echo $id;?>)">
            <div class='icon-synergy-trash'></div>
            <div class='onBtn'>Delete</div>
          </a>
     
         <?php } }?>
    </div>
  </div>
</section>
<?php
	@include("footer.php");
