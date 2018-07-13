<?php
@include("sessioncheck.php");

?>
<section data-type='#sos-fastesttimestate' id='sos-fastesttimestate'>
 <script type="text/javascript" charset="utf-8">
       $.getScript('sos/fastesttimestate/sos-fastesttimestate.js');
   </script>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Fastest Times by State</p>
                <p class="dialogSubTitleLight">Customize your report below, then press View Report.</p>
            </div>
        </div>
        
       
        <div class='row formBase rowspacer'>
            <div class='eleven columns centered insideForm'>
                <!--Shows Class & Student Dropdown-->
            	<div class="row">
                    <div class='six columns'>   
                    	State<span class="fldreq">*</span> 
                            <div class="selectbox">
                                <input type="hidden" name="stateid" id="stateid" value="">
                                <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                                    <span class="selectbox-option input-medium" data-option="" style="width:97%">Select State</span>
                                    <b class="caret1"></b>
                                </a>
                                <div class="selectbox-options" id="searchstateid">
                                    <input type="text" class="selectbox-filter" placeholder="Search state">
                                    <ul role="options" style="width:100%">
                                        <?php 
                                       	$qry = $ObjDB->QueryObject("SELECT DISTINCT(fld_statevalue) AS statevalue, fld_statename AS statename 
                                                                                                FROM itc_state_city 
                                                                                                WHERE fld_delstatus=0 
                                                                                                ORDER BY fld_statename ASC");
                                        if($qry->num_rows>0){
                                            while($row = $qry->fetch_assoc())
                                            {
                                                extract($row);
						
                                                ?>
                                                <li><a tabindex="-1" href="#" data-option="<?php echo $statevalue;?>" ><?php echo $statename; ?></a></li>
                                                <?php
                                            }
                                        }?>     
                                    </ul>
                                </div>
                            </div> 
                    </div>

                      <div class='six columns'> 
                        Year<span class="fldreq">*</span> 
                        <div class="selectbox">
                            <input type="hidden" name="yearid" id="yearid" value="">
                            <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                                    <span class="selectbox-option input-medium" data-option="" style="width:97%">Select Year</span>
                                    <b class="caret1"></b>
                            </a>
                            <div class="selectbox-options">
                                <input type="text" class="selectbox-filter" placeholder="Search Year">
                                <ul role="options" style="width:100%">
                                    <?php 
                                    $startyear = date('Y');
                                    $endyear = $startyear-15;
                                    for($i=$startyear;$i>=$endyear;$i--){
                                             ?>
                                                 <li><a href="#" data-option="<?php echo $i;?>"><?php echo $i;?></a></li>
                                             <?php 
                                    } ?>         
                                </ul>
                    </div>
		</div>
                    </div>
		</div>
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
                    
                              <input type="hidden" id="hidfilename" name="hidfilename" value="<?php echo "fastesttimestate_"; ?>" />
                
                <!--View Report Button-->
                <div class='row rowspacer'id="viewreportdiv">
                    <input class="darkButton" type="button" id="btnstep" style="width:200px; height:42px; float:right;" value="View" onClick="fn_showfastestbystate_view();" />
                </div>
            </div>
        </div>
    </div>
</section>
<?php
	@include("footer.php");
