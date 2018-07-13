<?php 
@include("sessioncheck.php");

/*
	Created By -  Mohan Kumar.V
	Page - reports-testreports-testreportsajax.php
		
	Shows the Assignment Drop down And  Multiple student selection 
        access type and view report type 
	

*/

$date = date("Y-m-d H:i:s");
$oper = isset($method['oper']) ? $method['oper'] : '';

/*--- Load access report Dropdown ---*/
if($oper=="loadwaystoviewreports" and $oper != " " )
{
    $schid = isset($method['schid']) ? $method['schid'] : '';
    ?>
    Ways to sort the report
                 <div class="selectbox">
                    <input type="hidden" name="viewtype" id="viewtype" value="" />
                    <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                        <span class="selectbox-option input-medium" data-option="" style="width:97%">Select View Type</span>
                        <b class="caret1"></b>
                    </a>
                    <div class="selectbox-options"  style="top: -133px;">
                        <input type="text" class="selectbox-filter" placeholder="Search Sort Type Report">
                        <ul role="options" style="width:100%;">
                            <li><a tabindex="-1" href="#" data-option="1" onclick="$('#sortdropdiv').show(); $('#viewdropdiv').hide(); fn_showsortreport(1,'<?php echo $schid;?>');">Assessment</a></li>
                            <li><a tabindex="-1" href="#" data-option="2" onclick="$('#sortdropdiv').show(); $('#viewdropdiv').hide(); fn_showsortreport(2,'<?php echo $schid;?>');">Standard</a></li>
                            <li><a tabindex="-1" href="#" data-option="3" onclick="$('#sortdropdiv').show(); $('#viewdropdiv').hide(); fn_showsortreport(3,'<?php echo $schid;?>');">Question</a></li>
                            
                        </ul>
                    </div>
                </div> 
    <?php
    
    
    
}

/*--- Load access report Dropdown ---*/
if($oper=="loadsortreports" and $oper != " " )
{
    $viewtype = isset($method['viewtype']) ? $method['viewtype'] : '';
     $schid = isset($method['schlid']) ? $method['schlid'] : '';
    ?>
    Ways to sort the report
                 <div class="selectbox">
                    <input type="hidden" name="sorttype" id="sorttype" value="" />
                    <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                        <span class="selectbox-option input-medium" data-option="" style="width:97%">Select Sort Type</span>
                        <b class="caret1"></b>
                    </a>
                    <div class="selectbox-options"  style="top: -133px;">
                        <input type="text" class="selectbox-filter" placeholder="Search Sort Type Report">
                        <ul role="options" style="width:100%;">
                            <li><a tabindex="-1" href="#" data-option="1" onclick="$('#viewdrop').show();$('#viewdropdiv').show();  fn_showviewreport(1,'<?php echo $schid;?>');">Class</a></li>
                            <li><a tabindex="-1" href="#" data-option="2" onclick="$('#attemptsdiv').show(); $('#viewdropdiv').show(); fn_showviewreport(2,'<?php echo $schid;?>');">Student</a></li>
                           
                            
                        </ul>
                    </div>
                </div> 
    <?php
    
    
    
}
if($oper=="loadattempts" and $oper!=""){

    $accesstyp = isset($method['sortaccesstype']) ? $method['sortaccesstype'] : '';
    $schid = isset($method['schlid']) ? $method['schlid'] : '';
    
    ?>
    Student Attempts
                 <div class="selectbox">
                    <input type="hidden" name="studentattemptid" id="studentattemptid" value="" />
                    <a class="selectbox-toggle" style="width:100%;" role="button" data-toggle="selectbox" href="#" >
                        <span class="selectbox-option input-medium" data-option="" style="width:97%">Select Student Attempt</span>
                        <b class="caret1"></b>
                    </a>
                    <div class="selectbox-options"  style="top: -133px;">
                        <input type="text" class="selectbox-filter" placeholder="Search Student Attempt">
                        <ul role="options" style="width:100%;">
                            <li><a tabindex="-1" href="#" data-option="1" onclick=" $('#viewreportdiv').show();">Last Attempt</a></li>
                            <li><a tabindex="-1" href="#" data-option="2" onclick="$('#viewreportdiv').show();">All Attempts</a></li>
                            
                        </ul>
                    </div>
                </div> 
    <?php
}

/*--- Load view report Dropdown ---*/
if($oper=="loadviewreports" and $oper != " " )
{
    $accesstyp = isset($method['accesstype']) ? $method['accesstype'] : '';
    $schid = isset($method['schlid']) ? $method['schlid'] : '';
    ?>
    View the report
                 <div class="selectbox">
                    <input type="hidden" name="viewthereportdropid" id="viewthereportdropid" value="" />
                    <a class="selectbox-toggle" style="width:100%;" role="button" data-toggle="selectbox" href="#" >
                        <span class="selectbox-option input-medium" data-option="" style="width:97%">Select View Report</span>
                        <b class="caret1"></b>
                    </a>
                    <div class="selectbox-options"  style="top: -133px;">
                        <input type="text" class="selectbox-filter" placeholder="Search View Report">
                        <ul role="options" style="width:100%;">
                            <li><a tabindex="-1" href="#" data-option="1" onclick="$('#attemptsdiv').show(); fn_showattempts(); ">Data View</a></li>
                            <li><a tabindex="-1" href="#" data-option="2" onclick="$('#attemptsdiv').show(); fn_showattempts(); ">Graph View</a></li>
                            
                        </ul>
                    </div>
                </div> 
    <?php
    
    
    
}

@include("footer.php");
