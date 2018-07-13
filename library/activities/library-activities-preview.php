<?php
	@include("sessioncheck.php");	
	
    $filename = isset($method['filename']) ? $method['filename'] : '0';	
	$fileformat = isset($method['fileformat']) ? $method['fileformat'] : '0';	
	$imagearray=array('jpg','jpeg','gif','png');
	$videoarrayarray=array('mp3','wav','wma','swf','avi','flv','mp4','mpg','wmv','divx','mpeg');
	if(in_array($fileformat,$imagearray))
	{
		$type=1;
		
	}
	else if(in_array($fileformat,$videoarrayarray))
	{
		$type=2;
	}
?>
<section data-type='library-activities' id='library-activities-preview' title="<?php echo $filename; ?>">
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
                <p class="darkTitle">Preview Activity</p>
                <p class="darkSubTitle">&nbsp;</p>
            </div>       
        </div>     
        
        <div class='row buttons rowspacer'> 
        	<?php if($type=='2'){ ?>  	
                <iframe src="<?php echo _CONTENTURL_; ?>activity/<?php echo $filename;?>" width="99%" height="610px;" style="border:none;margin:0 auto;"></iframe>
            <?php }
			
				if($type=='1'){
				
				$data = getimagesize(__FULLCNTACTIVITYPATH__.$filename);
			   $width = $data[0];
			   $height = $data[1];
			   if($width>600 or $height>900)
			   {
				   $src="thumb.php?src=".__FULLCNTACTIVITYPATH__.$filename."&w=900&h=600&q=100";
			   }
			   else
			   {
				   $src=__FULLCNTACTIVITYPATH__.$filename;
			   }
	                 
					 
					?>
					<img id="imgload" src="<?php echo $src;?>" style="border:none;margin:0 auto;max-height:600;max-width:900px;visibility:hidden">
                      <script type="text/javascript" language="javascript">
                   $('#imgload').attr('src', '<?php echo $src;?>').load(function() {  
					  $(this).css('visibility','visible')
					});
                   </script> 
                    <?php } ?>
        </div>
    </div>
</section>
<?php
	@include("footer.php");