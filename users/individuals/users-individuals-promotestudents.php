<?php
@include("sessioncheck.php");

$date=date("Y-m-d H:i:s");

?>

<script language="javascript" type="text/javascript">
    $.getScript("users/individuals/users-individuals-promotestudents.js");
</script>
<section data-type='2home' id='users-individuals-promotestudents'>
 <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Promote Students</p>
                <p class="dialogSubTitle">&nbsp;</p>
            </div>
        </div>
        
        <div class='row formBase rowspacer'>
            <div class='eleven columns centered insideForm'> 
            <form name="rubricforms" id="rubricforms">
               
                <!--Show Grade-->
                    <div class="row rowspacer">
                        <div class="six columns">
                         Select grade<span class="fldreq">*</span>
                            <dl class='field row'>
                                <dt class='dropdown'>
                                    <div class="selectbox">
                                     <input type="hidden" name="ddlgrade" id="ddlgrade" value="" onchange="fn_showstudent();$('#ddlgrade').valid();">
                                    <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                    <span class="selectbox-option input-medium" data-option="">Select grade</span>
                                    <b class="caret1"></b>
                                    </a>
                                        <div class="selectbox-options" >
                                        <input type="text" class="selectbox-filter" placeholder="Search grade" >
                                            <ul role="options">
                                                <?php 
                                                   for($j=1;$j<=12;$j++){ ?>
                                                            <li><a tabindex="1" href="#" data-option="<?php echo $j;?>"><?php echo $j;?></a></li>
                                                    <?php 
                                                    }?>       
                                            </ul>
                                        </div>
                                    </div>
                                </dt>
                            </dl> 
                        </div>
                         <div class='six columns'> 

                        </div>
                    </div>
                <!--Show Grade ends-->
                
                <!--Shows Student -->
                    <div class="row rowspacer">
                        <div class='twelve columns'> 
                            <div id="studentdiv" style="display:none">

                            </div>
                        </div>
                    </div>
                <!--Shows Student -->
                <!--Show Grade-->
                <div class="row rowspacer" id="selectgrade" style="display:none">
                        <div class="six columns">
                         Select grade<span class="fldreq">*</span>
                            <dl class='field row'>
                                <dt class='dropdown'>
                                    <div class="selectbox">
                                     <input type="hidden" name="ddlgrade1" id="ddlgrade1" value="" onchange="fn_showsavebtn();$('#ddlgrade1').valid();">
                                    <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                    <span class="selectbox-option input-medium" data-option="">Select grade</span>
                                    <b class="caret1"></b>
                                    </a>
                                        <div class="selectbox-options" >
                                        <input type="text" class="selectbox-filter" placeholder="Search grade" >
                                            <ul role="options">
                                                <?php 
                                                   for($j=1;$j<=12;$j++){ ?>
                                                            <li><a tabindex="1" href="#" data-option="<?php echo $j;?>"><?php echo $j;?></a></li>
                                                    <?php 
                                                    }?>       
                                            </ul>
                                        </div>
                                    </div>
                                </dt>
                            </dl> 
                        </div>
                         <div class='six columns'> 

                        </div>
                    </div>
                <!--Show Grade ends-->
                    <div class='row rowspacer' style="display:none" id="savediv">
                        <input class="darkButton" type="button" id="btnstep2" style="width:210px; height:42px; float:right;" value="Save" onClick="fn_savegrade();" />
                    </div>
       
                  </form>
            </div>
        </div>
    </div>
    
</section>
<?php
	@include("footer.php");