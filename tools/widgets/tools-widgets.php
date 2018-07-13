<?php
	@include("sessioncheck.php");
     
        $menuid= isset($method['id']) ? $method['id'] : '';

?>

<script type='text/javascript'>
	$.getScript("tools/widgets/tools-widgets.js");
</script>
<section data-type='#tools-widgets' id='tools-widgets'>

    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Turn Off Widgets</p>
				<p class="dialogSubTitleLight">Choose a category to turn off widgets</p>
            </div>
        </div>
        
        <div class='row buttons rowspacer'>
            <a class='skip btn mainBtn' href='#tools-message' id='btntools-widgets-ind'>
                <div class='icon-synergy-tests'></div>
                <div class='onBtn tooltip' original-title='widgets individually'>Turn Off<br />Individually</div>
            </a>
            <a class='skip btn mainBtn' href='#tools-message' id='btntools-widgets-cont'>
                <div class='icon-synergy-tests'></div>
                <div class='onBtn tooltip' original-title='widgets per content'>Turn Off<br />Per Content</div>
            </a>
            <a class='skip btn mainBtn' href='#tools-message' id='btntools-widgets-stud'>
                <div class='icon-synergy-tests'></div>
                <div class='onBtn tooltip' original-title='widgets based on student'>Turn Off Based<br /> on Student</div>
            </a>
       </div>
    </div>
</section>
<?php
	@include("footer.php");
