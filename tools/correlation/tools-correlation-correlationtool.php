<?php

@include("sessioncheck.php");
?>

<section data-type='2home' id='tools-correlation-correlationtool'>
    <script language="javascript">
        $.getScript("tools/correlation/tools-correlation-correlationtool.js");

    </script> 
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
                <p class="dialogTitle">Correlation Tools</p>
                <p class="dialogSubTitleLight">&nbsp;</p>
            </div>
        </div>
        <div class='row buttons'>
            <a class='skip btn mainBtn' href='#tools-correlation' id='btntools-correlation-correlationtoolassignment' name=''>
                <div class='icon-synergy-repository'></div>
                <div class='onBtn'>Alignment</div>
            </a>
            <a class='skip btn mainBtn ' href='#tools-correlation' id='btntools-correlation-correlationtoolasset' name='tools-correlation-correlationtoolassetnew'>
                <div class='icon-synergy-repository'></div>
                <div class='onBtn'>Assets</div>
            </a>
        </div>
    </div>
</section>

<?php

@include("footer.php");
