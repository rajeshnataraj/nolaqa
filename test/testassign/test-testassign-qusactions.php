<?php 
@include("sessioncheck.php");

$id = isset($method['id']) ? $method['id'] : '';

$id=explode(",",$id);
?>
<section data-type='#test-testassign' id='test-testassign-qusactions'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
                <p class="darkTitle">View Question</p>
                <p class="darkSubTitle">&nbsp;View the question details below.</p>
             </div>
        </div>
    	
        <div class='row rowspacer'>
            <a class='skip btn mainBtn' href='#test-testassign' id='btntest-testassign-quscreation' name='<?php echo $id[0].",".$id[1].",".$id[2];?>'>
               <div class="icon-synergy-edit"></div>
                <div class='onBtn'>Edit<br />Question</div>
            </a>
            <a class='skip btn main' href='#class-class' onclick="fn_deleteques(<?php echo $id[0];?>)">
                <div class="icon-synergy-trash"></div>
                <div class='onBtn'>Delete<br />Question</div>
            </a>
        </div>
    </div>
    
    <div class='row rowspacer'>
        <div class='twelve columns formBase'>
            <div class='row'>
                <div class='eleven columns centered insideForm' style="min-height:300px;">
                    <div id="loadImg"><img src="<?php echo __HOSTADDR__; ?>img/iframe-loader.gif" /></div>
                    <iframe src="test/testassign/test-testassign-reviewiframe.php?id=<?php echo $id[0]; ?>" width="100%" height="300px" style="border:#F00;" id="ifr_question3" onload="$('#loadImg').remove();autoResize('ifr_question3');"> </iframe>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="hidflag" name="hidflag" value="1" />
</section>
<?php
	@include("footer.php");

