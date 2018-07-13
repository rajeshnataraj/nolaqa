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
<section data-type='2home' id='reports-password'>
	<script language="javascript">
    	$.getScript("reports/password/reports-password.js");
    </script>
    
    <style>
		.myclass .ZebraDialog_Body { background-image: url('img/down.png') }
    </style>
    
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Password Reports</p>				
            </div>
        </div>
        
        <div class='row formBase rowspacer' id="minheightstyle">
            <div class='eleven columns centered insideForm'>
            	<!--Shows Select Type & Class/Student Dropdown-->
            	<div class="row">
                	<?php if($sessmasterprfid==2 || $sessmasterprfid==3) { ?>
                	<div class='six columns'>   
                    	District
                        <dl class='field row'>
                            <div class="selectbox">
                                <input type="hidden" name="districtid" id="districtid" value="Select District">
                                <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                                    <span class="selectbox-option input-medium" data-option="" style="width:97%">Select District</span>
                                    <b class="caret1"></b>
                                </a>
                                <div class="selectbox-options">	
                                	<input type="text" class="selectbox-filter" placeholder="Search District">		    
                                    <ul role="options" style="width:100%;">
									<li>
<a tabindex="-1" href="#" data-option="school" onclick="$('#dist').show();fn_load_school_purcahse();$('#btnstep').addClass('dim');">School Purchase</a>
                                    </li>
                             <li>
<a tabindex="-1" href="#" data-option="home" onclick="$('#dist').show();fn_load_home_purcahse();$('#btnstep').addClass('dim');">Home Purchase</a>
                              </li>
                         
									<?php 
                                        $qry = $ObjDB->QueryObject("SELECT fld_id AS districtid, fld_district_name AS districtname 
																	FROM itc_district_master 
																	WHERE fld_delstatus='0'");
                                        if($qry->num_rows>0){
                                            while($row = $qry->fetch_assoc())
                                            {
                                                extract($row);
                                                ?>
                                                <li><a tabindex="-1" href="#" data-option="<?php echo $districtid;?>" onclick="$('#dist').hide(); fn_school(<?php echo $districtid; ?>);$('#btnstep').removeClass('dim');"><?php echo $districtname; ?></a></li>
                                                <?php
                                            }
                                        }?>
                                    </ul>
                                </div>
                            </div> 
                        </dl>
                    </div>
                    
                    <div class='six columns' id="schools">   
                        
                    </div>
                    <?php } else if($sessmasterprfid==6) { ?>
                    School
                    <div class="selectbox">
                        <input type="hidden" name="districtid" id="districtid" value="<?php echo $districtid; ?>">
                        <input type="hidden" name="schoolid" id="schoolid" value="">
                        <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                            <span class="selectbox-option input-medium" data-option="" style="width:97%">Select School</span>
                            <b class="caret1"></b>
                        </a>
                        <div class="selectbox-options">
                            <input type="text" class="selectbox-filter" placeholder="Search School">
                            <ul role="options" style="width:100%">
                                <?php 
                                $qry = $ObjDB->QueryObject("SELECT fld_id AS schoolid, fld_school_name AS schoolname 
															FROM itc_school_master 
															WHERE fld_delstatus='0' AND fld_district_id='".$districtid."'");
                                if($qry->num_rows>0){
                                    while($row = $qry->fetch_assoc())
                                    {
                                        extract($row);
                                        ?>
                                        <li><a tabindex="-1" href="#" data-option="<?php echo $schoolid;?>" onclick="$('#dist').show();"><?php echo $schoolname; ?></a></li>
                                        <?php
                                    }
                                }?>      
                            </ul>
                        </div>
                    </div>
                    <?php }?>
                </div>
                
                <input type="hidden" id="hidpassname" name="hidpassname" value="<?php echo "userpasswordreport_"; ?>" />
                
                <!--View Report Button-->
                <div class='row rowspacer' style="display:none" id="dist">
                    <input class="darkButton" type="button" id="btnstep" style="width:200px; height:42px; float:right;" value="Download Report" onClick="fn_showpassreport(<?php echo $sessmasterprfid;?>);" />
                </div>
            </div>
        </div>
    </div>
</section>
<?php
	@include("footer.php");