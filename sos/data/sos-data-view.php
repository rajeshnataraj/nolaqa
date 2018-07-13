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
        $studentcount = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_sos_student_master where fld_datasheet_id='".$sheetid."' ANd fld_delstatus='0'");
        
        $noofstudents=$studentcount;

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
$studentids=array();

$stuqry = $ObjDB->QueryObject("SELECT fld_id AS stuid FROM itc_sos_student_master where fld_datasheet_id='".$sheetid."' AND fld_delstatus='0' ORDER BY fld_id ASC");
while($rowstu=$stuqry->fetch_assoc())
{
    extract($rowstu);
    $studentids[]=$stuid;
    
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
                
                
               <div class='row rowspacer'>       	                       
                    <div class="double-scroll" style="width:850px; height:600px; overflow-x: auto; overflow-y: auto;">
                        <table class="table" id="myTable05" cellpadding="0" cellspacing="0">
                            <thead>
                                    <tr>
                                    <th ><div style="width: 230px;box-shadow: 1px 0 0 #dddddd  !important; font-weight:bold;font-size:16px;">Data</div></th>
                                            <?php								    
                                            for($n=1;$n<=($noofstudents);$n++)
                                            {											
                                                ?>
                                               <th align="center"><span style="font-weight:bold;font-size:16px;vertical-align:top; margin-left:30px;">
                                                      <?php if($n==1){ ?>
                                                      Student <?php } ?></span>
                                                    </th>
                                                <?php
                                            }
                                            ?>
                                    </tr>
                            </thead>
                              <tbody>
                                    <?php
                                    $i=1;
                                    $qrymodule=$ObjDB->NonQuery("SELECT fld_id AS detatilid,fld_detail_name AS detailname,fld_start_range AS startrange,fld_end_range AS endrange
                                                                            FROM itc_sos_details WHERE fld_delstatus='0'");
                                    while($rowmodule = $qrymodule->fetch_assoc()) // show the module based on number of copies
                                    {
                                        extract($rowmodule);
                                                ?>
                           
                                        <tr  id="tr_<?php echo $i;?>" class="<?php echo $detatilid;?>" >
                                            <th style="background: #F6F6F6;" id="detail_<?php echo $i;?>"><div style="width: 230px; box-shadow: 1px 0 0 #dddddd  !important;  "><?php echo $detailname; ?></div> </th>
                                              <?php								    
                                              for($r=0;$r<sizeof($studentids);$r++)
                                              {											
                                                  if($i=='1' || $i=='2'){ ?>
                                            <td id="txt_<?php echo $i."_".$studentids[$r];?>" style="box-shadow: 1px 0 0 #dddddd  !important; text-align:center;"><span class="dragdrop">&nbsp;</span></td>
                                                      <?php
                                                  }
                                                  else{ ?>
                                                        <td id="txt_<?php echo $i."_".$studentids[$r];?>" style="box-shadow: 1px 0 0 #dddddd  !important; text-align:center;" ><span class="dragdrop">&nbsp;</span></td>
                                                      <?php
                                                  }
                                              }
                                              ?>
                                        </tr>
                                            <?php
                                        $i++;
                                    } // while loop ends
                                    ?>
                              </tbody>
                                </table>


                                <?php
                                $qrycelldet=$ObjDB->QueryObject("SELECT fld_datasheet_id AS dsid, fld_datasheet_recordname AS recordname, fld_view_cellid AS cellid FROM itc_sos_datasheet_records WHERE fld_datasheet_id='".$sheetid."' AND fld_delstatus='0'");

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
<script language="javascript" type="text/javascript">
    $('#myTable05').fixedHeaderTable({ fixedColumns: 1 });
    $('div.fht-fixed-column').children().last().css('margin-top','-29px');
</script>
</section>
<?php
	@include("footer.php");