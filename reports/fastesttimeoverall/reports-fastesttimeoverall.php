<?php
@include("sessioncheck.php");

?>
<section data-type='#reports-fastesttimeoverall' id='reports-fastesttimeoverall'>
 <script type="text/javascript" charset="utf-8">
       $.getScript('reports/fastesttimeoverall/reports-fastesttimeoverall.js');
   </script>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Fastest Times by Overall</p>
                <p class="dialogSubTitleLight">Customize your report below, then press View Report.</p>
            </div>
        </div>
        
       
        <div class='row formBase rowspacer'>
            <div class='eleven columns centered insideForm'>
                <!--Shows Class & Student Dropdown-->
            	 <div class="row rowspacer">
                        <div class='six columns'>
                            Track Length<span class="fldreq">*</span> 
                            <dl class='field row'>
                                <dt class='dropdown'>
                                    <div class="selectbox">
                                        <input type="hidden" name="tracklen" id="tracklen" value="<?php echo $tracklen;?>" >
                                        <a class="selectbox-toggle" tabindex="1" role="button" data-toggle="selectbox" href="#">
                                            <span class="selectbox-option input-medium" data-option=""> <?php echo "Select Track Length"; ?></span><b class="caret1"></b>
                                        </a>
                                         <div class="selectbox-options">                                                    
                                            <ul role="options" style="width:400px;">
                                                <li><a tabindex="-1" href="#" data-option="1">65 Feet 7 inches</a></li>
                                                <li><a tabindex="-1" href="#" data-option="2">55 feet</a></li>
                                                <li><a tabindex="-1" href="#" data-option="3">45 feet</a></li>
                                                <li><a tabindex="-1" href="#" data-option="4">Other</a></li>
                                            </ul>
                                        </div>

                                    </div>
                                </dt>
                            </dl> 
                        </div>
                    </div>    
                     <!--Shows state list-->
                              <input type="hidden" id="hidfilename" name="hidfilename" value="<?php echo "fastesttimeoverall_"; ?>" />
                <!--View Report Button-->
                <div class='row rowspacer'id="viewreportdiv" >
                    <input class="darkButton" type="button" id="btnstep" style="width:200px; height:42px; float:right;" value="View" onClick="fastesttimeoverall_view();" />
                </div>
            </div>
        </div>
    </div>
</section>
<?php
	@include("footer.php");
