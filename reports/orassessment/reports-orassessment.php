<?php 
/*
Created by: Vijayalakshmi PHP Programmer
Created on: 14/12/2014
Details: Open Response Assessment Report by question and strudents order
Updated By MOhan M 8-6-2015
*/

@include("sessioncheck.php");
?>

<section data-type='2home' id='reports-orassessment'>
    <div class='container'>
          <div class='row'>
              <div class='twelve columns'>
                  <p class="dialogTitle">Open Response Assessment Report</p>
                  <p class="dialogSubTitleLight">Choose a reporting category to view its individual reports.</p>
                   <div class="row rowspacer"></div>
              </div>
          </div>    
          <div class='row buttons rowspacer'>
              <a class='skip btn mainBtn' href='#reports' id='btnreports-orassessment-byquestion' name='1'>
                     <div class='icon-synergy-reports'></div>
                     <div class='onBtn tooltip' original-title='Student Mastery'>By<br />Question</div>
                 </a>
                 <a class='skip btn mainBtn' href='#reports' id='btnreports-orassessment-byquestion' name='2'>
                     <div class='icon-synergy-reports'></div>
                     <div class='onBtn tooltip' original-title='class mastery'>By<br />Students</div>
                 </a>

                <a class='skip btn mainBtn' href='#reports' id='btnreports-orassessment-byquestion' name='3'>
                    <div class='icon-synergy-reports'></div>
                    <div class='onBtn tooltip' original-title='class mastery'>By<br />Standard</div>
                </a>

          </div>
    </div>  
</section>     
<?php
    @include("footer.php");
