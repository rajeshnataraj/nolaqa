<?php
@include("sessioncheck.php");

/*
	Created By - Mohan. M
	*/

?>
<section data-type='2home' id='reports-completionreport'>
 <?php if($sessmasterprfid==9 or $sessmasterprfid==8) { ?>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Student Completion Report</p>
				<!--<p class="dialogSubTitleLight">Select the type to view the report.</p>-->
                                <p class="dialogSubTitleLight">Select the type of report you wish to view, and then click "View Report".</p>
            </div>
        </div>
        
        <div class='row buttons rowspacer'>
            <a class='skip btn mainBtn' href='#reports' id='btnreports-completionreport-byclass'>
                <div class='icon-synergy-reports'></div>
                <div class='onBtn tooltip' original-title='By Class'>Class</div>
            </a>
            <a class='skip btn mainBtn' href='#reports' id='btnreports-completionreport-bystudent'>
                <div class='icon-synergy-reports'></div>
                <div class='onBtn tooltip' original-title='By Student'>Student</div>
            </a>
            
            
        </div>
    </div>
<?php } if($sessmasterprfid==2){?>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Completion Report</p>
				<p class="dialogSubTitleLight">Select the type to view the report.</p>
            </div>
        </div>
        
        <div class='row buttons rowspacer'>
            <a class='skip btn mainBtn' href='#reports' id='btnreports-completionreport-byschool'>
                <div class='icon-synergy-reports'></div>
                <div class='onBtn tooltip' original-title='By School'>School</div>
            </a>
            <a class='skip btn mainBtn' href='#reports' id='btnreports-completionreport-byclass'>
                <div class='icon-synergy-reports'></div>
                <div class='onBtn tooltip' original-title='By Class'>Class</div>
            </a>
            <a class='skip btn mainBtn' href='#reports' id='btnreports-completionreport-bystudent'>
                <div class='icon-synergy-reports'></div>
                <div class='onBtn tooltip' original-title='By Student'>Student</div>
            </a>
            
        </div>
    </div>
      <?php }?>
</section>