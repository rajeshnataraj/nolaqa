<?php
@include("sessioncheck.php");

$menuid= isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
?>
<section data-type='2home' id='sos-report'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="darkTitle">Reports</p>
                <p class="darkSubTitle">&nbsp;</p>
            </div>
        </div>
        <div class='row buttons'>
	<?php
		if($sessmasterprfid == 9){ //For Pitsco & Content Admin
                        ?>
                       <a class='skip btn mainBtn' href='#sos-report-fastesttimeclass' id='btnsos-fastesttimeclass' name='0'>
                            <div class='icon-synergy-lessons'></div>
                            <div class='onBtn'>Fastest Times by Class</div>
                       </a>
			<a class='skip btn mainBtn' href='#sos-report-fastesttimestate' id='btnsos-fastesttimestate' name='0'>
                            <div class='icon-synergy-lessons'></div>
                            <div class='onBtn'>Fastest Times by State</div>
                       </a>
			<a class='skip btn mainBtn' href='#sos-report-fastesttimeoverall' id='btnsos-fastesttimeoverall' name='0'>
                            <div class='icon-synergy-lessons'></div>
                            <div class='onBtn'>Fastest Times Overall</div>
                       </a>

                        <?php
                        }
			?>			
					
        </div>
    </div>
</section>
<?php
	@include("footer.php");
