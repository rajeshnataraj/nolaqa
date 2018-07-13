<?php
@include("sessioncheck.php");

/*
	Created By - Narendrakumar. D
	Page - reports-gradereports-Assesment Report
	Description:
		Show the Student mastery & Class mastery Reports buttons.

	Actions Performed:
		Student mastery -  Redirect to studentmastery form - reports-gradereports-studentmastery.php
		Class Mastery - Redirect to class Mastery form - reports-gradereports-classmastery.php
	
	History:


*/
?>
<section data-type='2home' id='reports-assesmentreports'>
    <script language="javascript">
   		$.getScript("reports/assesmentreports/reports-assesmentreport.js");
    </script>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Assessment Reports</p>
				<p class="dialogSubTitleLight">Choose a reporting category to view its individual reports.</p>
            </div>
        </div>
        
        <div class='row buttons rowspacer'>
            <a class='skip btn mainBtn' href='#reports' id='btnreports-assesmentreports-studentmastery'>
                <div class='icon-synergy-reports'></div>
                <div class='onBtn tooltip' original-title='Student Mastery'>Student<br />Mastery</div>
            </a>
            <a class='skip btn mainBtn' href='#reports' id='btnreports-assesmentreports-classmastery'>
                <div class='icon-synergy-reports'></div>
                <div class='onBtn tooltip' original-title='class mastery'>Class <br />Mastery</div>
            </a>
       </div>
    </div>
</section>
<?php
	@include("footer.php");