<?php
	@include("sessioncheck.php");	
	
	$id = (isset($method['id'])) ? $method['id'] : 0;
	
        $docfilename = $ObjDB->SelectSingleValue("SELECT fld_docfile_name FROM itc_sosdocument_master 
		                                      WHERE fld_id='".$id."' AND fld_delstatus='0'");
		
	$content_url = CONTENT_URL;
  	
	

?>
<section data-type='#library-documents' id='library-documents-preview'>
    <div class='container'>
        <div class='row'>
            <div class="span10">
                <p class="dialogTitle">Preview SOS Document </p>
                <p class="dialogSubTitleLight">&nbsp;</p>
            </div>
        </div>     
        <div class='row buttons'> 
                   
                        <div class='row formBase'>
                            <div class='twelve columns centered insideForm'>
                                   <iframe src="<?= CONTENT_URL ?>/sosdocuments/<?php echo $docfilename; ?>" id="ifr_pdf" onload="autoResize('ifr_pdf');$('#loadImg').remove();" width="100%" height="1000">
                                   </iframe>
                                <div class='row formBase'></div>
                                    
                            </div>
                        </div>
                   
                           
        </div>
    </div>
</section>
<?php
	@include("footer.php");
