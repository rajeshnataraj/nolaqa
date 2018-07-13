<?php 
@include("sessioncheck.php");

$id = isset($method['id']) ? $method['id'] : '';

$qry_lessondetails = $ObjDB->QueryObject("SELECT fld_id AS repositoryid, fld_repository_name AS repositoryname, fld_file_name AS filename, fld_file_type AS filetypename, fld_created_by as createdby FROM itc_repository_master WHERE fld_id = '".$id."' and fld_delstatus='0'");

    while($res_lessondetails = $qry_lessondetails->fetch_assoc()){
            extract($res_lessondetails);
    }
    $noview=array('xlsx','xls','txt','ppt','pptx','aac','ac3','frg','flp','m4b','aa3','doc','docx');



?>
<script type='text/javascript'>
$.getScript("tools/repository/tools-repository-newrepository.js");
</script>
<section data-type='#tools-repository' id='tools-repository-actions'>
  <div class='container'>
    <div class='row'>
            <div class="span10">
                <p class="dialogTitle"><?php echo $repositoryname?></p>
                <p class="dialogSubTitleLight">&nbsp;</p>
            </div>
        </div>
    <div class='row buttons'>
     <?php if(!in_array(strtolower($filetypename),$noview)) {      ?>
          <a class='skip btn mainBtn' href='#tools-repository' id='btntools-repository-preview' name='<?php echo $filename.",".$filetypename;?>'>
            <div class='icon-synergy-view'></div>
            <div class='onBtn'>View</div>
          </a>
    <?php } ?> 
         <?php if($sessprofileid == 2 || $sessprofileid == 3) { if($createdby== $uid) {?> 
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
     
?>

