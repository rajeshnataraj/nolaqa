<?php
	@include("sessioncheck.php");	
        
        error_reporting(E_ALL);
ini_set('display_errors', '1');

	
	$id = (isset($method['id'])) ? $method['id'] : 0;
	
        $videofilename = $ObjDB->SelectSingleValue("SELECT fld_videofile_name FROM itc_sosvideo_master 
		                                      WHERE fld_id='".$id."' AND fld_delstatus='0'");		
	

        
        $fileformat=explode('.',$videofilename);
          $filename=explode('_',$fileformat[0]);
	
?>
<section data-type='#sos-library' id='sos-library-previewvideo'>
    <div class='container'>
        <div class='row'>
            <div class="span10">
                <p class="dialogTitle">SOS Video </p>
                <p class="dialogSubTitleLight">&nbsp;</p>
            </div>
        </div>     
        <div class='row buttons'> 
                    <?php if($fileformat[1]=='mp4') { ?> 
                        <div class='row formBase'>
                            <div class='eleven columns centered insideForm'>
                                
                               <div id="loadImg" style="height:0px;"><img src="<?php echo __HOSTADDR__; ?>img/iframe-loader.gif"/></div>
     
                                 
                                
                                  <iframe src="<?= CLOUDFRONT_URL ?>/sosvideo/<?php echo $videofilename; ?>" id="ifr_pdf" onload="autoResize('ifr_pdf');$('#loadImg').remove();" width="855" height="484">
                                   <video id="example_video_1" class="video-js vjs-default-skin" controls preload="auto" autoplay="autoplay"  style="width:100%; height:100%" onload="$('#loadImg').remove();">
		    <source src="http://<?= CLOUDFRONT_URL ?>/sosvideo/<?php echo $videofilename; ?>" type='video/mp4' />
		</video>
                                  </iframe>
                                <div class='row formBase'></div>

                            </div>
                        </div>
                    <?php } 
                    else if($fileformat[1]=='zip')
                    {
                    ?>
                    <script>
                    $.getScript("library/video/library-video.js");   
                    
                    setTimeout("showfullscreenlessonpdsos('<?php echo $fileformat[0]; ?>','<?php echo $filename[0]; ?>')",1000);
           
                  </script>
                    <?php
                    }
                   ?>
                           
        </div>
    </div>
</section>
<?php
	@include("footer.php");
