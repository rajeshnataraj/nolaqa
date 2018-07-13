<?php
	@include("sessioncheck.php");
 
$id = isset($method['id']) ? $method['id'] : 0;

$id=explode(",",$id);
$urlname=$id[0];
$uploadimg = $id[2];


?>
<section data-type='assignment-expedition-materiallist' id='assignment-expedition-viewmaterialfortask'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">View</p>
                <p class="dialogSubTitle">&nbsp;</p>
            </div>
        </div>
       <div class='row formBase'> 
            <div class="row rowspacer" style="margin:235px;">
               
                <img src="<?php if($urlname != '') { echo $urlname; } else { echo __CNTMATERIALICONPATH__.$uploadimg; } ?>" height="50%" width="50%">
              
            </div>
            <div class='row rowspacer' id="unitbtn">
                       <div class='row'>
                            <div class='four columns btn primary push_two noYes' style="margin-left:35%;">
                                <a onclick="fn_cancel('assignment-expedition-viewmaterialfortask')" tabindex="4">Close</a>
                            </div>
                            
                        </div>
                   	</div>
          
        </div>
        
   
    </div>
</section>
<?php
	@include("footer.php");