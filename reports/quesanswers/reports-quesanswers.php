<?php
/*
	Created By - Muthukumar.
	Page - reports-classroom
	Description:
		Show the Student Password & Schedule Reports buttons.
	Actions Performed:
		Student Password - Redirect to studentpassword form - reports-classroom-stupassword.php
		Student Schedule - Redirect to studentschedule form - reports-classroom-stuschedule.php
	History:
*/
?>
<section data-type='2home' id='reports-quesanswers'>
	<script language="javascript">
   		$.getScript("reports/quesanswers/reports-quesanswers.js");
    </script>
    
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Question and Answer Reports</p>
				<p class="dialogSubTitleLight">Select a report to view or print.</p>
            </div>
        </div>
        
        <div class='row buttons rowspacer'>
            <a class='skip btn mainBtn' href='#reports' id='btnreports-quesanswers-indiplquestionreport'>
                <div class='icon-synergy-reports'></div>
                <div class='onBtn tooltip' original-title='Individual IPL Question'>Individual IPL<br />Question</div>
            </a>
            <a class='skip btn mainBtn' href='#reports' id='btnreports-quesanswers-iplanalyticsreport'>
                <div class='icon-synergy-reports'></div>
                <div class='onBtn tooltip' original-title='IPL Analytics'>IPL Analytics</div>
            </a>
            <a class='skip btn mainBtn' href='#reports' id='btnreports-quesanswers-iplsummaryreport'>
                <div class='icon-synergy-reports'></div>
                <div class='onBtn tooltip' original-title='IPL Progress Summary'>IPL Progress<br />Summary</div>
            </a>
            <a class='skip btn mainBtn' href='#reports' id='btnreports-quesanswers-surveyreport'>
                <div class='icon-synergy-reports'></div>
                <div class='onBtn tooltip' original-title='Student Response'>Student<br />Response</div>
            </a>
            <a class='skip btn mainBtn' href='#reports' id='btnreports-quesanswers-assessmentquestion'>
                <div class='icon-synergy-reports'></div>
                <div class='onBtn tooltip' original-title='Assessment Question'>Assessment<br />Question</div>
            </a>
        </div>
    </div>
</section>