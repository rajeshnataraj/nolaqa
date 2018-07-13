<?php
	@include("sessioncheck.php");	
	
	$id = (isset($method['id'])) ? $method['id'] : 0;
	$id=explode(',',$id);
	
        $filename = $id[0];	
	$fileformat = $id[1];	
	
	$videoarrayarray=array('mp4');
	if(in_array($fileformat,$videoarrayarray)) $type=2;        
        


?>
<section data-type='#library-video' id='library-video-preview'>
    <div class='container'>
        <div class='row'>
            <div class="span10">
                <p class="dialogTitle">Preview SOS Video </p>
                <p class="dialogSubTitleLight">&nbsp;</p>
            </div>
        </div>     
        <div class='row buttons'> 
                    <?php if($fileformat=='mp4') { ?> 
                        <div class='row formBase'>
                            <div class='eleven columns centered insideForm'>
                                 
                                    
                                  <div id="loadImg" style="height:0px;"><img src="<?php echo __HOSTADDR__; ?>img/iframe-loader.gif"/></div>
       
                                 
                                
                                  <iframe src="<?= CLOUDFRONT_URL ?>/sosvideo/<?php echo $filename; ?>" id="ifr_pdf" onload="autoResize('ifr_pdf');$('#loadImg').remove();" width="855" height="484">
                                   <video id="example_video_1" class="video-js vjs-default-skin" controls preload="auto" autoplay="autoplay"  style="width:100%; height:100%" onload="$('#loadImg').remove();">
                                  <source src="<?= CLOUDFRONT_URL ?>/sosvideo/<?php echo $filename; ?>" type='video/mp4' />
                                    </video>  
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
