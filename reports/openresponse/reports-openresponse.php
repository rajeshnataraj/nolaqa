<?php 
/*
Created by: Vijayalakshmi PHP Programmer
Created on: 14/12/2014

*/
@include("sessioncheck.php");
?>

<section data-type='2home' id='reports-openresponse'>

  <div class='container'>
	<div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Open Response Report</p>
		<p class="dialogSubTitleLight">Customize your report below, then click "View Report".</p>
                 <div class="row rowspacer"></div>
            </div>
        </div>    
     <div class='row'>
	<div class='twelve columns formBase'>
	    <div class='row'>
                    <div class='eleven columns centered insideForm'>

			<div id="showperioddiv" class="row rowspacer">
		            <div class='six columns' id="showstart">
		                Start date
		                <dl class='field row'>
		                    <dt class='text'>
		                         <input id="startdate1" readonly name="startdate1" class="quantity" placeholder='Start Date' type='text' value="" >
		                    </dt>                                        
		                </dl>
		            </div>
                    
		            <div class='six columns' id="showend" style="display:none">
		                End date
		                <dl class='field row'>
		                    <dt class='text'>
		                         <input id="enddate1" readonly name="enddate1" class="quantity" placeholder='End Date' type='text' value="" >
		                    </dt>                                        
		                </dl>
		            </div>
               		</div>
	<script type="text/javascript" language="javascript">
		$.getScript("reports/openresponse/reports-assessmentopenresponse.js");
		$("#startdate1").datepicker( {
			onSelect: function(selectedDate){
			$("#enddate1").val('');
			$('#showend').show();
			$('#viewreportdiv').hide();
			$("#enddate1" ).datepicker( "option", "minDate", selectedDate );
			$("#reports-pdfviewer").hide("fade").remove();
			}
		});

		$("#enddate1").datepicker( {
			onSelect: function(dateText,inst){
			var stdate = $('#startdate1').val();
			var enddate = $('#enddate1').val();
			fn_viewselectedassessment(stdate,enddate,'<?php echo $uid; ?>');

			}
		});
	</script>  
 <div class="row rowspacer" id="loadassessmentlist" style="display:none;"></div>
	<!--View Report Button-->
                <div class='row rowspacer' id="viewreportdiv" style="display:none;">
                	
                    <input class="darkButton" type="button" id="btnstep" style="width:200px; height:42px; float:right;" value="View Report" onClick="fn_openresreport('<?php echo $uid; ?>');" />
<input type="hidden" id="hidselectedassessments" name="hidselectedassessments" value="" />
                </div>
		    </div>
	    </div>
	</div>
     </div>
  </div>    
</section>     
