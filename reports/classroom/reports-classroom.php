<?php
@include("sessioncheck.php");

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
<section data-type='2home' id='reports-classroom'>
	<script language="javascript">
   		$.getScript("reports/classroom/reports-classroom.js");
    </script>
    
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Classroom Management reports</p>
				<p class="dialogSubTitleLight">Select a report to view or print.</p>
            </div>
        </div>
        
        <div class='row buttons rowspacer'>
            <a class='skip btn mainBtn' href='#reports' id='btnreports-classroom-stupassword' style="display: none;">
                <div class='icon-synergy-reports'></div>
                <div class='onBtn tooltip' original-title='Student Passwords'>Student<br />Passwords</div>
            </a>
            <a class='skip btn mainBtn' href='#reports' id='btnreports-classroom-stuschedule'>
                <div class='icon-synergy-reports'></div>
                <div class='onBtn tooltip' original-title='Individual Student Schedule'>Individual <br />Student..</div>
            </a>
            <a class='skip btn mainBtn' href='#reports' id='btnreports-classroom-scienceschedule'>
                <div class='icon-synergy-reports'></div>
                <div class='onBtn tooltip' original-title='Science Schedule'>Science <br />Schedule</div>
            </a>
        </div>
    </div>
</section>
<?php
	@include("footer.php");