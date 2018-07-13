<?php
	@include("sessioncheck.php");	
	
	$id = (isset($method['id'])) ? $method['id'] : 0;
	$id=explode(',',$id);
	
        $filename = $id[0];	
	$fileformat = $id[1];	
	
	$docarrayarray=array('pdf');
	if(in_array($fileformat,$docarrayarray)) $type=2;

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
                    <?php if($fileformat=='pdf') { ?> 
                        <div class='row formBase'>
                            <div class='twelve columns centered insideForm'>
                                   <iframe src="<?= CONTENT_URL ?>/sosdocuments/<?php echo $filename; ?>" id="ifr_pdf" onload="autoResize('ifr_pdf');$('#loadImg').remove();" width="100%" height="1000">
                                   </iframe>
                                <div class='row formBase'></div>
                                    
                            </div>
                        </div>
                    <?php }
                        ?>
                           
        </div>
    </div>
</section>
<?php
	@include("footer.php");
