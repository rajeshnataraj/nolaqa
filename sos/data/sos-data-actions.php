<?php 
@include("sessioncheck.php");

/*
	Created By - Mohan. M
	
*/
	
$id = isset($method['id']) ? $method['id'] : '';
$id=explode(",",$id);
$id[1]= $ObjDB->SelectSingleValue("SELECT fld_data_sheetname FROM itc_sos_datasheet_master  WHERE fld_id='".$id[0]."' AND fld_delstatus='0'");
?>
<section data-type='#sos-data' id='sos-data-actions'>
    <script type="text/javascript" charset="utf-8">	
        $.getScript('sos/data/sos-data.js');
    </script>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle"><?php echo $id[1];?></p>
                <p class="dialogSubTitle">&nbsp;</p>
            </div>
        </div>
        
        <div class='row buttons'>
            <a class='skip btn mainBtn' href='#sos-data' id='btnsos-data-view' name='<?php echo $id[0].",1";?>'>
                <div class='icon-synergy-view'></div>
                <div class='onBtn'>View</div>
            </a>
         
            <a class='skip btn mainBtn' href='#sos-data' id='btnsos-data-newdata' name='<?php echo $id[0].",0";?>'>
                <div class='icon-synergy-edit'></div>
                <div class='onBtn'>Edit</div>
            </a>
            <a class='skip btn main' href='#sos-data' onclick="fn_deletedatasheet(<?php echo $id[0];?>)">
                <div class='icon-synergy-trash'></div>
                <div class='onBtn'>Delete</div>
            </a>
      
        </div>
    </div>
</section>
<?php
	@include("footer.php");