<?php
@include("sessioncheck.php");
ob_start();

$content = ob_get_clean();
/*
	Created By - Sathya
	
*/
?>
<style>
    #ui-datepicker-div{
        z-index:101 !important;
    }   
</style>
<section data-type='2home' id='reports-licenserenewal'>
    <script language="javascript">
    	$.getScript("reports/licenserenewal/reports-licenserenewal.js");
    </script>
   
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">License Renewal Reports</p>
            </div>
        </div>
        
        <div class='row formBase rowspacer' id="minheightstyle">
            <div class='eleven columns centered insideForm'>
                <form id="licenseform" name="sform">
                    <div class="row">
                        <div class='six columns'>   
                            Category
                            <dl class='field row'>
                                <div class="selectbox">
                                    <input type="hidden" name="categoryid" id="categoryid" value="Select Category">
                                    <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                                        <span class="selectbox-option input-medium" data-option="" style="width:97%">Select Category</span>
                                        <b class="caret1"></b>
                                    </a>
                                    <div class="selectbox-options">	
                                                <input type="text" class="selectbox-filter" placeholder="Search Category">		    
                                            <ul role="options" style="width:100%;">
                                                <li>
                                                    <a tabindex="-1" href="#" data-option="1" onclick="fn_schoolpurchase(1);">School Purchase</a>
                                                </li>
                                                <li>
                                                    <a tabindex="-1" href="#" data-option="2" onclick="fn_homepurchase(2);">Home Purchase</a>
                                                </li>
                                                <li>
                                                    <a tabindex="-1" href="#" data-option="3" onclick="fn_distpurchase(3);">District</a>
                                                </li>
                                            </ul>
                                    </div>
                                </div> 
                            </dl>
                        </div>
                       
                    </div>

                    <div class="row rowspacer">
                        <div class='twelve columns'> 
                            <div id="schoolsdiv" style="">

                            </div>
                        </div>
                     </div>
                    <div class="row rowspacer">
                         <div class='three columns'> 
                            <div id="expirationdate" style="display:none;">
                                Expiration Date<span class="fldreq">*</span> 
                                 <dl class='field row'>
                                        <dt class='text'>
                                              <input  id="startdate" readonly name="startdate" placeholder='Expiration Date' type='text' value="<?php echo $startdate; ?>" >
                                        </dt>                                        
                                </dl>
                            </div>
                        </div>
                     </div>

                    <!--View Report Button-->
                    <div class='row rowspacer' style="display:none" id="viewreportdiv">
                        <div class="six columns" style="float:right;">
                            <input class="darkButton" type="button" id="btnstep" style="width:200px; height:42px; margin-right:10px;" value="Export" onClick="fn_exportlicreport();" />
                            <input class="darkButton" type="button" id="btnstep" style="width:200px; height:42px;" value="View Report" onClick="fn_licenserenewalreport();" />
                        </div>
                            <input type="hidden" id="hidselectedstudentids" name="hidselectedstudentids" value="" />
                            <input type="hidden" id="hidfilename" name="hidfilename" value="<?php echo "licenserenewal_"; ?>" />
                    </div>
                    <!--View Report Button-->
                </form>
            </div>
        </div>
    </div>
</section>
<style>
    .ui-datepicker ui-widget ui-widget-content ui-helper-clearfix ui-corner-all{
      position: absolute; bottom: 1191.8px; left: 372.5px; display: block; z-index: 4;
    }
    #ui-datepicker-div .ui-datepicker ui-widget ui-widget-content ui-helper-clearfix ui-corner-all{
      position: absolute; bottom: 1191.8px; left: 372.5px; display: block; z-index: 4;
    }
</style>
<script>
    $( "#startdate" ).datepicker( {
        minDate: '-currentdate'
    });
</script>

<?php
@include("footer.php");