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

$moduleqry = $ObjDB->QueryObject("SELECT fld_data_sheetname as datasheetname, fld_sosclass_id as clsid, fld_term as term, fld_state as state,fld_year AS yeer, fld_student_count as noofstu, fld_track_length AS tracklength, fld_co2cartridge AS co2
                                                        FROM itc_sos_datasheet_master WHERE fld_id='".$sheetid."' AND fld_delstatus='0';");

while($rowmodule=$moduleqry->fetch_assoc())
{
        extract($rowmodule);
        $datasheetname = $datasheetname;
        $sosclassid = $clsid;
        $term = $term;
        $year = $yeer;
        $state = $state;
        $noofstudents=$noofstu;

        $tracklen=$tracklength;
        $co2=$co2;

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




}
$msg = "View ".$datasheetname;
?>
<section data-type='2home' id='sos-data-view'>
     <script type="text/javascript" charset="utf-8">	
        $.getScript('sos/data/sos-data.js');
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
                <div class='row'>
                    <div class='two columns'>
                        <p style="font-weight:bold;">Data Sheet Name</p>
                    </div>
                    <div class='six columns'>
                        <p><?php echo ":  ".$datasheetname; ?></p>
                    </div>
                </div>


                <div class='row'>  
                   <div class='two columns'>
                        <p style="font-weight:bold;">Class Name</p>
                  </div>
                  <div class='six columns'>
                        <p><?php echo ":  ".$sossclassname; ?></p>
                  </div>
                </div>
                <div class='row'>  
                    <div class='two columns'>
                        <p style="font-weight:bold;">Term</p>
                    </div>
                    <div class='six columns'>
                        <p><?php echo ":  ".$term; ?></p>
                    </div>
                </div>
                <div class='row'>  
                    <div class='two columns'>
                        <p style="font-weight:bold;">State</p>
                    </div>
                    <div class='six columns'>
                        <p><?php echo ":  ".$statename; ?></p>
                    </div>
                </div>
                <div class='row'>  
                    <div class='two columns'>
                        <p style="font-weight:bold;">Year</p>
                    </div>
                    <div class='six columns'>
                        <p><?php echo ":  ".$year; ?></p>
                    </div>
                </div>
                
                <div class='row'>  
                    <div class='two columns'>
                        <p style="font-weight:bold;">Track Length</p>
                    </div>
                    <div class='six columns'>
                        <p><?php echo ":  ".$tracklength; ?></p>
                    </div>
                </div>
                <div class='row'>  
                    <div class='two columns'>
                        <p style="font-weight:bold;">CO2 Cartridge</p>
                    </div>
                    <div class='six columns'>
                        <p><?php echo ":  ".$co2cart; ?></p>
                    </div>
                </div>
                
                    <style>
                    
                    .table {
                      table-layout: fixed; 
                      width: 100%;                    
                    }
                   
                  
                </style>
                       <script type="text/javascript">
            $(document).ready(function(){
               $('.double-scroll').doubleScroll();
            });
        </script>
               <div class='row rowspacer'>       	                       
                        <div class="outer">
                     <div class="double-scroll" style='width:850px;'>
                                <table  class='table' style='border: 1px solid #dddddd;' id="myTable0" cellpadding="0" cellspacing="0">
                                    <tr>
                                                  <th style='width:300px; cursor:default;font-weight:bold; box-shadow: 1px 0 0 #dddddd  !important;'  class='thclass'>Data</th>
                                            <?php								    
                                            for($n=1;$n<=($noofstudents-1);$n++)
                                            {											
                                                ?>
                                                    <th style='width:160px; cursor:default;'  class='thclass' ><span style="font-weight:bold;font-size:14px;vertical-align:top;">
                                                      <?php if($n==1){ ?>
                                                      Student Name <?php } ?></span>
                                                    </th>
                                                <?php
                                            }
                                            ?>
                                    </tr>
                                    <?php
                                    $i=1;
                                    $qrymodule=$ObjDB->NonQuery("SELECT fld_id AS detatilid,fld_detail_name AS detailname,fld_start_range AS startrange,fld_end_range AS endrange
                                                                            FROM itc_sos_details WHERE fld_delstatus='0'");
                                    while($rowmodule = $qrymodule->fetch_assoc()) // show the module based on number of copies
                                    {
                                        extract($rowmodule);
                                                ?>
                                        <tr  id="tr_<?php echo $i;?>" class="<?php echo $detatilid;?>" >
                                            <td style='font-weight:lighter; cursor:default; box-shadow: 1px 0 0 #dddddd  !important'  id="detail_<?php echo $i;?>"><?php echo $detailname; ?> </td>
                                              <?php								    
                                              for($r=2;$r<=$noofstudents;$r++)
                                              {											
                                                  if($i=='1' || $i=='2'){ ?>
                                                        <td style='width:150px;  cursor:default; box-shadow: 1px 0 0 #dddddd  !important'   id="txt_<?php echo $i."_".$r;?>" ></td>
                                                      <?php
                                                  }
                                                  else{ ?>
                                                        <td  style='width:150px; cursor:default; box-shadow: 1px 0 0 #dddddd  !important'  id="txt_<?php echo $i."_".$r;?>" ></td>
                                                      <?php
                                                  }
                                              }
                                              ?>
                                        </tr>
                                            <?php
                                        $i++;
                                    } // while loop ends
                                    ?>
                                </table>


                                <?php
                                $qrycelldet=$ObjDB->QueryObject("SELECT fld_datasheet_id AS dsid, fld_datasheet_recordname AS recordname, fld_cell_id AS cellid FROM itc_sos_datasheet_records WHERE fld_datasheet_id='".$sheetid."' AND fld_delstatus='0'");


                                while($rowcelldet=$qrycelldet->fetch_assoc())
                                {
                                    extract($rowcelldet);
                                    ?>
                                        <script>
                                            $('#<?php echo $cellid;?>').html('<?php echo $recordname;?>');
                                        </script>
                                    <?php
                                }
                                ?>
                            </div>
                       </div>
                  </div>
             </div>
        </div>
    </div>
    </div>
</section>
<?php
	@include("footer.php");