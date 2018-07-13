<?php
@include("sessioncheck.php");

error_reporting(E_ALL);
ini_set('display_errors', '1');
/*
	Created By - MOhan. M
*/

$id = isset($method['id']) ? $method['id'] : '0';
$id = explode(",",$id);
//$id[1] = 0 - Edit/Create Type

$sheetid = $id[0];

if($sheetid==0){
    $createbtn = "Save Sheet";
    $cancelbtn = "Cancel";
    $datasheetname = "";
    $sosclassid = "";
    $term = "";
    $year="";
    $state ="";

    $tracklen="";
    $co2="";
    $tracklength="";
    $co2cart="";
    $tracksurface="";

    $studentcount='0';
    $nextstucount='0';
    $prestucount='0';
    
    $msg = "New Data Sheet";
    $cancelclick="fn_cancel('sos-modules')";
    
    $flagg='1'; ?>

<?php
       
}
else{
    $createbtn = "Update Sheet";
    $cancelbtn = "Cancel";
    $moduleqry = $ObjDB->QueryObject("SELECT fld_data_sheetname as datasheetname, fld_sosclass_id as clsid, fld_term as term, fld_state as state,fld_year AS yeer, fld_student_count as noofstu, fld_track_length AS tracklength, fld_co2cartridge AS co2, fld_track_surface AS tracksurface
                                                    FROM itc_sos_datasheet_master WHERE fld_id='".$sheetid."' AND fld_delstatus='0';");

    while($rowmodule=$moduleqry->fetch_assoc())
    {
            extract($rowmodule);
            $datasheetname = $datasheetname;
            $sosclassid = $clsid;
            $term = $term;
            $year = $yeer;
            $state = $state;      
            $tracklen=$tracklength;
            $co2=$co2;
            $tracksurface=$tracksurface;

            if($tracklen=='1'){
                $tracklength='65 Feet 7 inches';
            }
            else if($tracklen=='2'){
                 $tracklength='55 feet';
            }
            else if($tracklen=='3'){
                 $tracklength='45 feet';
            }
            else{
                 $tracklength='Other';
            }

            if($co2='1'){
                $co2cart='8 gram';
            }
            else{
                $co2cart='4 gram';
            }

            $sossclassname = $ObjDB->SelectSingleValue("SELECT fld_sos_class_name AS sosclassname 
                                                           FROM itc_sos_class_master 
                                                           WHERE  fld_id='".$sosclassid."' AND fld_delstatus='0' 
                                                           ");

            $statename = $ObjDB->SelectSingleValue("SELECT DISTINCT fld_statename AS statename 
                                                           FROM itc_state_city 
                                                           WHERE  fld_statevalue='".$state."' AND fld_delstatus='0'
                                                           ORDER BY fld_statename ASC");

            $flagg='2';
            $studentcount = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_sos_student_master where fld_datasheet_id='".$sheetid."' AND fld_delstatus='0'");
            $nextstucount = $ObjDB->SelectSingleValueInt("SELECT fld_stu_id FROM itc_sos_datasheet_records where fld_datasheet_id='".$sheetid."' AND fld_delstatus='0' group by fld_stu_id");
            $prestucount='0';
            ?>
            <script>
                $('#datasheetstusetting').show();
                $('#datasheet').show(); 
                $('#btndiv').show();
                <?php if($studentcount!='0' AND $studentcount!='1') { ?>
                $('#btnnext').removeClass('dim');
                <?php } ?>
                 <?php if($studentcount!='0') { ?>   
                $('#btndel').removeClass('dim');
                 <?php } ?>         

        $('html, body').animate({
            scrollTop: 1300
        }, 1000);   
            </script>
            <?php
    }
    $msg = "Edit ".$datasheetname;
    $cancelclick="fn_cancel('sos-data-actions')";			
}
?>
<section data-type='2home' id='sos-data-newdata'>
     <script type="text/javascript" charset="utf-8">	
        $.getScript('sos/data/sos-data.js');       
        
        <?php $timestamp = time();?>
	$('#file_upload').uploadify({
                'formData'     : {
                        'timestamp' : '<?php echo $timestamp;?>',
                        'token'     : '<?php echo md5('nanonino' . $timestamp);?>',
                        'oper'      : 'importstudents' 
                },
                 'height': 40,
                 'width':185,
                'fileSizeLimit' : '2MB',
                'swf'      : 'uploadify/uploadify.swf',
                'uploader' : 'uploadify/uploadify_user.php',
                'multi':false,
                'buttonText' : 'Import Student',
                'removeCompleted' : true,
                'fileTypeExts' : '*.xls; *.xlsx; *.csv;',
                'onUploadSuccess' : function(file, data, response) {
                    $('#datasheet').hide();
                    fn_importstudents(data,<?php echo $sheetid; ?>);
                   
                 },
                 'onUploadProgress' : function(file, bytesUploaded, bytesTotal, totalBytesUploaded, totalBytesTotal) {                  
                }
        });        
    </script>
    <div class='container'>
    	<!--Load the Module Name / New module-->
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle"><?php echo $msg; ?></p>
                <p class="dialogSubTitle">&nbsp;</p>
            </div>
        </div>
        
        <!--Load the Module Form-->
        
        <div class='row formBase'>
            <div class='eleven columns centered insideForm'>
                <form name="dataforms" id="dataforms">
                    <div class="title-info">General Information</div>
                    <div class='row'>
                        <div class='six columns'>
                            Data Sheet Name<span class="fldreq">*</span>
                            <dl class='field row'>
                                <dt class='text'>
                                <input placeholder='Data Sheet Name' type='text' id="txtdatasheetname" name="txtdatasheetname" value="<?php echo $datasheetname;?>" onBlur="$(this).valid(); "/>
                                </dt>
                            </dl>
                        </div>
                        <div class='three columns'>
                           Term<span class="fldreq">*</span>
                           <dl class='field row'>
                               <dt class='text'>
                                   <input placeholder='Term' type='text' id="term" name="term" value="<?php echo $term ;?>" onkeypress="return isNumberchar(event)">
                               </dt>                                        
                           </dl>
                        </div>
                    </div>
                    <div class='row '>
                        <div class='six columns'>
                            Select state<span class="fldreq">*</span> 
                            <dl class='field row'>
                                <dt class='dropdown'>
                                    <div class="selectbox">
                                        <input type="hidden" name="ddlstate" id="ddlstate" value="<?php echo $state;?>" onchange="$(this).valid();">
                                        <a class="selectbox-toggle" tabindex="1" role="button" data-toggle="selectbox" href="#">
                                            <span class="selectbox-option input-medium" data-option="<?php if($sheetid==0){ echo "0";} else { echo $state;}?>"><?php if($sheetid == 0){ echo "Select state";} else {echo $statename;}?></span><b class="caret1"></b>
                                        </a>
                                      
                                        <div class="selectbox-options">
                                            <input type="text" class="selectbox-filter" placeholder="Search state" >
                                            <ul role="options">
                                                <?php 
                                                    $stateqry = $ObjDB->QueryObject("SELECT DISTINCT(fld_statevalue) AS statevalue, fld_statename AS statename 
                                                                                                FROM itc_state_city 
                                                                                                WHERE fld_delstatus=0 
                                                                                                ORDER BY fld_statename ASC");
                                                    while($rowstate = $stateqry->fetch_assoc()){ 
                                                        extract($rowstate);
                                                            ?>
                                                                <li><a href="#" data-option="<?php echo $statevalue;?>"><?php echo $statename;?></a></li>
                                                            <?php 
                                                    }?>       
                                            </ul>
                                        </div>
                                      
                                    </div>
                                </dt>
                            </dl> 
                        </div>
                        <div class='six columns'>
                            Year<span class="fldreq">*</span> 
                            <dl class='field row'>
                                <dt class='dropdown'>
                                    <div class="selectbox">
                                        <input type="hidden" name="year" id="year" value="<?php echo $year;?>" onchange="$(this).valid();">
                                        <a class="selectbox-toggle" tabindex="1" role="button" data-toggle="selectbox" href="#">
                                            <span class="selectbox-option input-medium" data-option="<?php if($sheetid==0){ echo "0";} else { echo $year;}?>"><?php if($sheetid == 0){ echo "Select Year";} else {echo $year;}?></span><b class="caret1"></b>
                                        </a>
                                    
                                        <div class="selectbox-options">
                                            <input type="text" class="selectbox-filter" placeholder="Search Year" >
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
                                </dt>
                            </dl>
                        </div>
                    </div>
                    <div class='row rowspacer'> 
                        <div class='six columns' id='classnameload'>
                            Class Name<span class="fldreq">*</span> 
                            <dl class='field row'>
                                <dt class='dropdown'>
                                    <div class="selectbox">
                                        <input type="hidden" name="classname" id="classname" value="<?php echo $sosclassid;?>" onchange="$(this).valid();">
                                        <a class="selectbox-toggle" tabindex="1" role="button" data-toggle="selectbox" href="#">
                                            <span class="selectbox-option input-medium" data-option="<?php if($sheetid==0){ echo "0";} else { echo $sosclassid;}?>"><?php if($sheetid == 0){ echo "Select class";} else {echo $sossclassname;}?></span><b class="caret1"></b>
                                        </a>
                                   
                                        <div class="selectbox-options">
                                            <input type="text" class="selectbox-filter" placeholder="Search class" >
                                            <ul role="options">
                                                <?php 
                                                    $stateqry = $ObjDB->QueryObject("SELECT fld_sos_class_name AS sossclassname,fld_id AS sosclassid
                                                                                                FROM itc_sos_class_master 
                                                                                                WHERE fld_delstatus='0' AND fld_created_by='".$uid."'
                                                                                                ORDER BY sossclassname ASC");
                                                    while($rowstate = $stateqry->fetch_assoc()){ 
                                                        extract($rowstate);
                                                            ?>
                                                                <li><a href="#" data-option="<?php echo $sosclassid;?>"><?php echo $sossclassname;?></a></li>
                                                            <?php 
                                                    }?>       
                                            </ul>
                                        </div>
                                     
                                    </div>
                                </dt>
                            </dl>
                        </div>
                        <div class='three columns'>
                          <div class='row rowspacer' title="Create New Class" style="cursor:pointer; float:left;" onclick="fn_newclass();">
                              <strong>
                                  <input type="button" id="btnstep" class="darkButton" style="width:200px; height:42px;" value="Create New Class"> 
                              </strong>
                          </div>
                      </div> 
                  </div>
                    <div class='row rowspacer'>
                        <div class='six columns'>
                            Track Length<span class="fldreq">*</span> 
                            <dl class='field row'>
                                <dt class='dropdown'>
                                    <div class="selectbox">
                                        <input type="hidden" name="tracklen" id="tracklen" value="<?php echo $tracklen;?>" onchange="$(this).valid();">
                                        <a class="selectbox-toggle" tabindex="1" role="button" data-toggle="selectbox" href="#">
                                            <span class="selectbox-option input-medium" data-option="<?php if($sheetid==0){ echo "0";} else { echo $tracklen;}?>"><?php if($sheetid == 0){ echo "Select Track Length";} else {echo $tracklength;}?></span><b class="caret1"></b>
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
                        <div class='six columns'>
                            Track Surface<span class="fldreq">*</span>
                            <dl class='field row'>
                                <dt class='text'>
                                <input placeholder='Track surface' type='text' id="txttracksuface" name="txttracksuface" value="<?php echo $tracksurface;?>" onkeypress="return isNumberchar(event)" onBlur="$(this).valid(); "/>
                                </dt>
                            </dl>
                        </div>
                    </div>
                    
                    <div class='row rowspacer'>
                       <div class='six columns'>
                            CO2 Cartridge<span class="fldreq">*</span> 
                            <dl class='field row'>
                                <dt class='dropdown'>
                                    <div class="selectbox">
                                        <input type="hidden" name="co2" id="co2" value="<?php echo $co2;?>" onchange="$(this).valid(); fn_showdsstusetting();">
                                        <a class="selectbox-toggle" tabindex="1" role="button" data-toggle="selectbox" href="#">
                                            <span class="selectbox-option input-medium" data-option="<?php if($sheetid==0){ echo "0";} else { echo $co2;}?>"><?php if($sheetid == 0){ echo "Select CO2 Cartridge";} else {echo $co2cart;}?></span><b class="caret1"></b>
                                        </a>
                                    
                                        <div class="selectbox-options">                                                    
                                            <ul role="options" style="width:400px;">
                                                <li><a tabindex="-1" href="#" data-option="1">8 gram</a></li>
                                                <li><a tabindex="-1" href="#" data-option="2">4 gram</a></li>
                                            </ul>
                                        </div>
                                      
                                    </div>
                                </dt>
                            </dl>
                        </div>
                        <div class='six columns'>
                           
                        </div>
                    </div>
                    
                <div id='datasheetstusetting' >
                    <div class='row'>
                        <div class='six columns'>
                            
                        </div>
                        <div class='three columns'>
                            <div class='row rowspacer' title="Add Student" style="cursor:pointer; float:right;" onclick="fn_addstudent();">
                                <strong>
                                    <input type="button" id="btnstep" class="darkButton<?php if($sheetid != 0){ echo ' dim'; } ?>" style="width:200px; height:42px;" value="Add Student"> 
                                           
                                </strong>
                            </div>
                        </div> 
                        <div class='three columns'>
                            <div class='row' title="Import Student" style="cursor:pointer; float:left;" id="importstu">
                                <strong>
                                      <input type="hidden" name="import" id="import" value="<?php echo $flagg;?>">
                                      <div><a id="file_upload"> </a></div>
                                </strong>
                            </div>
                        </div> 
                    </div>
                    <div class='row'>
                       <div class='six columns'>
                        </div>
                        <div class='six columns'>
                             <div class="" style="margin-top:-15px; margin-left:0px;">
                            <div><a id="file_upload"> </a></div>
                            <br />(File type: .xls, .xlsx, .csv) 
                        </div>
                        
                    	<div class="six" style="float:left"> Please <a href="import_sos_student.xls" style="font-weight:bold">click here to download sample file</a> to import the students. The fields First Name and Last Name are required. </div>
                        </div>
                        
                    </div>
                   
                    <div class='row rowspacer' id="datasheet" style="display: none;">       	                       
                            
                                    <?php if($sheetid == 0){ ?>
                             <div class="gridtableouter" style="width:850px; height:650px;">
                                        <table class="fancyTable" id="myTable0" cellpadding="0" cellspacing="0" style="width:750px; height:645px; margin-left: 44px;">
                                              <thead>
                                                  <tr class='trclass'>
                                                        <th  width='60%'>Data</th>
                                                        <th  width='40%'>Student</th>
                                                  </tr>
                                              </thead>
                                              <?php
                                              $i=1;
                                              $qrymodule=$ObjDB->NonQuery("SELECT fld_id AS detatilid,fld_detail_name AS detailname,fld_start_range AS startrange,fld_end_range AS endrange,fld_accept_range as acceptrange
                                                                                      FROM itc_sos_details WHERE fld_delstatus='0'");
                                              while($rowmodule = $qrymodule->fetch_assoc()) // show the module based on number of copies
                                              {
                                                  extract($rowmodule);
                                                  if(strlen($detailname)>20){                                                   
                                                      $det=$detailname;
                                                  }
                                                  else{
                                                      $det=$detailname;
                                                  }
                                                    ?>
                                                  <tr id="tr_<?php echo $i;?>" class="<?php echo $detatilid;?>" >
                                                        <td width='60%' id="detail_<?php echo $i;?>" class="tooltip" title="<?php echo $detailname; ?>" style="font-size: 13px; font-weight:bold;"><?php echo $det; ?> </td>
                                                      <?php if($i=='1'){ ?>
                                                            <td id="detail_<?php echo $i;?>"><p align="center"><input  id="txt_<?php echo $i;?>" type='text'style='' ></p></td>
                                                            <?php
                                                        }
                                                        elseif ($i=='2') { ?>
                                                            <td id="detail_<?php echo $i;?>"><p align="center"><input  id="txt_<?php echo $i;?>" type='text' onchange='acceptablecarname(<?php echo $i;?>)' style=''></p></td>
                                                            <?php
                                                        }
                                                        elseif ($i=='3') { ?>
                                                            <td id="detail_<?php echo $i;?>"><p align="center"><input  id="txt_<?php echo $i;?>" type='text' onkeypress="return isNumberdecimal(event)" onchange='acceptablecarname(<?php echo $i;?>)' style=''></p></td>
                                                            <?php
                                                        }
                                                        else{ ?>
                                                            <td id="detail_<?php echo $i;?>"><p align="center"><input  maxlength="3" class="tooltip" title="<?php echo $acceptrange; ?>"  onkeypress="return isNumber(event)" onchange='acceptablerange(<?php echo $i;?>)'  id="txt_<?php echo $i;?>" type='text'style='' ></p> </td>
                                                            <?php
                                                        } ?>
                                                  </tr>
                                                      <?php
                                                  $i++;
                                              } // while loop ends
                                              ?>

                                      </table>
                                  </div>
                                    <?php } 
                                    if($sheetid != 0)
                                    { ?>
                                        <div>
                                            <table class="fancyTable" id="myTable0" cellpadding="0" cellspacing="0" style=" width:798px; margin-left: 44px;">
                                           <thead>
                                              <tr class='trclass'>
                                                            <th  width='62.5%'>Data</th>
                                                        <th  width='40%'>Student</th>
                                                </tr>
                                            </thead>
                                            </table>
                               
                                        <div class="scroll" style="height:370px; overflow-x: auto; overflow-y: auto;">
                                            <table class="fancyTable" id="myTable0" cellpadding="0" cellspacing="0" style=" width:798px;margin-left: 44px;">
                                          <?php
                                          $i=1;
                                          $qrymodule=$ObjDB->NonQuery("SELECT fld_id AS detatilid,fld_detail_name AS detailname,fld_start_range AS startrange,fld_end_range AS endrange,fld_accept_range as acceptrange
                                                                                  FROM itc_sos_details WHERE fld_delstatus='0'");
                                          while($rowmodule = $qrymodule->fetch_assoc()) // show the module based on number of copies
                                          {
                                              extract($rowmodule);
                                              
                                               if(strlen($detailname)>20){
                                                       $det=$detailname;                                                    
                                                      
                                                  }
                                                  else{
                                                      $det=$detailname;
                                                  }                                              
                                                      ?>
                                              <tr  id="tr_<?php echo $i;?>" class="<?php echo $detatilid;?>">
                                                  <td width='62.5%' id="detail_<?php echo $i;?>" class="tooltip" title="<?php echo $detailname; ?>" style="font-size: 13px; font-weight:bold;"><?php echo $det; ?></td>
                                                    <?php								    
                                                   											
                                                        if($i=='1'){ ?>
                                                            <td id="detail_<?php echo $i;?>"><p align="center"><input  id="txt_<?php echo $i;?>" type='text' ></p></td>
                                                            <?php
                                                        }
                                                        elseif ($i=='2') { ?>
                                                            <td id="detail_<?php echo $i;?>"><p align="center"><input  id="txt_<?php echo $i;?>" type='text' onchange='acceptablecarname(<?php echo $i;?>)' ></p></td>
                                                            <?php
                                                        }
                                                        elseif ($i=='3') { ?>
                                                            <td id="detail_<?php echo $i;?>"><p align="center"><input  id="txt_<?php echo $i;?>" type='text' onkeypress="return isNumberdecimal(event)" onchange='acceptablecarname(<?php echo $i;?>)' ></p></td>
                                                            <?php
                                                        }
                                                        else{ ?>
                                                            <td id="detail_<?php echo $i;?>"><p align="center"><input  maxlength="3"  class="tooltip" title="<?php echo $acceptrange; ?>"  onkeypress="return isNumber(event)" onchange='acceptablerange(<?php echo $i;?>)'  id="txt_<?php echo $i;?>" type='text' ></p> </td>
                                                            <?php
                                                        }                                                    
                                                    ?>
                                              </tr>
                                                  <?php
                                              $i++;
                                          } // while loop ends
                                          ?>
                                        </table>
                                            </div>
                                             </div>
                                       <?php
                                        $qrycelldet=$ObjDB->QueryObject("SELECT fld_datasheet_id AS dsid, fld_datasheet_recordname AS recordname, fld_cell_id AS cellid FROM itc_sos_datasheet_records WHERE fld_datasheet_id='".$sheetid."' AND fld_delstatus='0' limit 0,16");


                                        while($rowcelldet=$qrycelldet->fetch_assoc())
                                        {
                                                extract($rowcelldet);
                                        ?>
                                              <script>
                                              $('#<?php echo $cellid;?>').val('<?php echo $recordname;?>');
                                              </script>
                                        <?php
                                        }
                                    } ?>                           
                            </div>                    
                </div>
                <!----Import Student code start here --->
                <div class='row rowspacer' id="importdatasheet" style="display: none;">       	                       

                </div>
                <!----Import Student code End here --->                  
                    <input type="hidden" name="dashid" id="dashid" value="<?php echo $sheetid; ?>">
                    <input type="hidden" name="currentstuid" id="currentstuid" value="<?php echo $nextstucount; ?>">
                    <input type="hidden" name="prestuid" id="prestuid" value="<?php echo $nextstucount; ?>">
                    <input type="hidden" name="nextstuid" id="nextstuid" value="<?php echo $nextstucount; ?>">
                    <input type="hidden" name="studentcount" id="studentcount" value="<?php echo $studentcount; ?>"> 
                    <input type="hidden" name="newstuid" id="newstuid" value="<?php echo $nextstucount; ?>">   
                
                <div class='row rowspacer' id="btndiv" style="display: none;">
                        <div class='twelve columns'>
                            <div style='margin-left: 129px; ' >
                                <input type="button" id="btnnewstu" class="darkButton" style="width:140px; height:42px;margin-top:10px;" value="New Student" onClick="fn_newstudent(<?php echo $sheetid; ?>);" />&nbsp;&nbsp;
                               <input type="button" id="btnnext" class="darkButton dim" style="width:100px; height:42px; margin-top:10px;" value="Next" onClick="fn_nextstudent(<?php echo $sheetid.",0"; ?>);" />&nbsp;&nbsp;
                               <input type="button" id="btnpre" class="darkButton dim" style="width:100px; height:42px; margin-top:10px;" value="Previous" onClick="fn_prestudent(<?php echo $sheetid.",0"; ?>);" />&nbsp;&nbsp;
                               <input type="button" id="btndel" class="darkButton dim" style="width:100px; height:42px; margin-top:10px;" value="Delete" onClick="fn_delstudent(<?php echo $sheetid; ?>);" />&nbsp;&nbsp;
                               <input type="button" id="btnsavesheet" class="darkButton" style="width:140px; height:42px;margin-top:10px;" value="Save Sheet" onClick="fn_savesheet(<?php echo $sheetid.",0"; ?>);" />&nbsp;&nbsp;
                               <input type="button" id="btnclose" class="darkButton" style="width:100px; height:42px; margin-top:10px;" value="Close" onClick="fn_closesheet(<?php echo $sheetid.",0"; ?>);" />&nbsp;
                            </div>
                        </div>
                    </div>
                </form>
                <script type="text/javascript" language="javascript"> 
                    function isNumber(evt) {
                        evt = (evt) ? evt : window.event;
                        var charCode = (evt.which) ? evt.which : evt.keyCode;
                        if (charCode > 31 && (charCode < 48 || charCode > 57) ) {
                           return false;
                        }
                        return true;
                    }
                    
                    function isNumberchar(evt) {
                        evt = (evt) ? evt : window.event;
                        var charCode = (evt.which) ? evt.which : evt.keyCode;
                        if (charCode > 32 && (charCode < 48 || charCode > 57)  && (charCode < 65 || charCode > 90)  && (charCode < 97 || charCode > 122)) {
                           return false;
                        }
                        return true;
                    }
                    
                    function isNumberdecimal(evt) {
                        var charCode = (evt.which) ? evt.which : event.keyCode;
                        if (charCode == 46 && evt.target.value.split('.').length>1) {
                            return false;
                        }
                        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
                            return false;
                        return true;
                    }
                    
                    $(function(){
                        $("#dataforms").validate({
                            ignore: "",
                                    errorElement: "dd",
                                    errorPlacement: function(error, element) {
                                            $(element).parents('dl').addClass('error');
                                            error.appendTo($(element).parents('dl'));
                                            error.addClass('msg'); 	
                            },
                            rules: { 
                                    txtdatasheetname: { required: true, lettersonly: true, placeholder: true}, 
                                    term: { required: true },
                                    txttracksuface: { required: true },
                            }, 
                            messages: { 
                                    txtdatasheetname:{  required:  "Please enter Data Sheet Name"},            
                                    term: { required: "Please enter Term" },
                                    txttracksuface: { required: "Please enter Track Surface" },
                            },
                            highlight: function(element, errorClass, validClass) {
                                    $(element).parent('dl').addClass(errorClass);
                                    $(element).addClass(errorClass).removeClass(validClass);
                            },
                            unhighlight: function(element, errorClass, validClass) {
                                    if($(element).attr('class') == 'error'){
                                                    $(element).parents('dl').removeClass(errorClass);
                                                    $(element).removeClass(errorClass).addClass(validClass);
                                    }
                            },
                            onkeyup: false,
                            onblur: true
                        });
                    });
              </script>
            </div>
        </div>        
    </div>
</section>
<?php
	@include("footer.php");