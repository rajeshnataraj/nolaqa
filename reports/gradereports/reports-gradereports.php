<?php
/*
	Created By - Muthukumar. D
	Page - reports-classroom
	Description:
		Show the Student Password & Schedule Reports buttons.

	Actions Performed:
		Student Password - Redirect to studentpassword form - reports-classroom-stupassword.php
		Student Schedule - Redirect to studentschedule form - reports-classroom-stuschedule.php
	
	History:


*/
?>
<section data-type='2home' id='reports-gradereports'>
	<script language="javascript">
   		$.getScript("reports/gradereports/reports-gradereports.js");
    </script>
    
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Grade reports</p>
				<p class="dialogSubTitleLight">Select a report to view or print.</p>
            </div>
        </div>
        
        <div class='row buttons rowspacer'>
            <a class='skip btn mainBtn' href='#reports' id='btnreports-gradereports-classschedule'>
                <div class='icon-synergy-reports'></div>
                <div class='onBtn tooltip' original-title='Class Schedule Scores'>Class<br />Schedule..</div>
            </a>
            <a class='skip btn mainBtn' href='#reports' id='btnreports-gradereports-individualgrade'>
                <div class='icon-synergy-reports'></div>
                <div class='onBtn tooltip' original-title='Individual Grades'>Individual<br />Grades</div>
            </a>
            <a class='skip btn mainBtn' href='#reports' id='btnreports-gradereports-classreport'>
                <div class='icon-synergy-reports'></div>
                <div class='onBtn tooltip' original-title='Class Grades'>Class<br />Grades</div>
            </a>
            <a class='skip btn mainBtn' href='#reports' id='btnreports-gradereports-indvidualassignment'>
                <div class='icon-synergy-reports'></div>
                <div class='onBtn tooltip' original-title='Individual Assignment Points'>Individual<br />Assignment..</div>
            </a>
            <a class='skip btn mainBtn' href='#reports' id='btnreports-gradereports-assementengine'>
                <div class='icon-synergy-reports'></div>
                <div class='onBtn tooltip' original-title='Assessment Report'>Assessment<br />Report</div>
            </a>
            <a class='skip btn mainBtn' href='#reports' id='btnreports-gradereports-prepost'>
                <div class='icon-synergy-reports'></div>
                <div class='onBtn tooltip' original-title='Pre/Post Report'>Pre/Post<br />Report</div>
            </a>
            <a class='skip btn mainBtn' href='#reports' id='btnreports-gradereports-classexpedition'>
                <div class='icon-synergy-reports'></div>
                <div class='onBtn tooltip' original-title='Class Expedition'>Class <br />Expedition</div>
            </a>
             <a class='skip btn mainBtn' href='#reports' id='btnreports-gradereports-classmission'>
                <div class='icon-synergy-reports'></div>
                <div class='onBtn tooltip' original-title='Class Mission'>Class <br />Mission</div>
            </a>
     
        </div>
    </div>
</section>