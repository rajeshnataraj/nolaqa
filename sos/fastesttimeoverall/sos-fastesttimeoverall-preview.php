<?php
@include("sessioncheck.php");

$id = (isset($method['id'])) ? $method['id'] : 0;
$ids=explode('~',$id);

$tracklen = $ids[0];

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
?>

<section data-type='#sos-fastesttimeoverall-preview' id='sos-fastesttimeoverall-preview'>
    <div class='container'>
        <div class='row'>
            <p class="dialogTitle">Fastest Times by Overall</p>
            <p class="dialogSubTitleLight"></p>
        </div>
             <script language="javascript" type="text/javascript">
                   $.getScript('sos/fastesttimeoverall/sos-fastesttimeoverall.js');
            </script>
    <div class='row rowspacer'>
        <div class='twelve columns formBase'>
            <div class='row'>
                <div class='eleven columns centered insideForm'>
                    <form  id="createlicense" name="createlicense" method='post'>
                        <div class='row'>  
                            <div class='span10 offset1'>   
                                 
                            <?php 
                             $flag=0;
                            $qry=$ObjDB->NonQuery("SELECT a.fld_track_length AS tracklen, a.fld_id AS datasheetid,b.fld_sos_class_name AS classname, a.fld_term AS term, a.fld_year AS yeear, a.fld_state AS state,a.fld_student_count AS stucount
                                                                FROM itc_sos_datasheet_master AS a  
                                                                LEFT JOIN itc_sos_class_master AS b ON a.fld_sosclass_id = b.fld_id
                                                                WHERE a.fld_track_length='".$tracklen."' AND a.fld_delstatus='0' AND b.fld_delstatus='0' order by classname");

                            if($qry->num_rows > 0)
                            {
                                while($rowsqry = $qry->fetch_assoc())
                                {   
                                    extract($rowsqry);
                                    $statename = $ObjDB->SelectSingleValue("SELECT DISTINCT(fld_statename) FROM itc_state_city WHERE fld_statevalue='".$state."'");
                                    $flag++;
                                    
                                    $details=array();
                                    $detailracetime=array();
                                    $result=array();
                                    
                                    $studentids=array();

                                    $stuqry = $ObjDB->QueryObject("SELECT fld_id AS stuid FROM itc_sos_student_master where fld_datasheet_id='".$datasheetid."' AND fld_delstatus='0' ORDER BY fld_id ASC");
                                    while($rowstu=$stuqry->fetch_assoc())
                                    {
                                        extract($rowstu);
                                        $studentids[]=$stuid;

                                    }

                                    
                                    ?>
                                    <table class='table table-hover table-striped table-bordered setbordertopradius' id="mytable" >
                                        <tr class="title">
                                              <th>State</th>
                                              <th>Year</th>
                                              <th>Car Name</th>
                                              <th>Track Length</th>
                                              <th>CO2 Cartridge</th>
                                              <th>Track Surface</th>
                                              <th>Race Time</th>
                                        </tr>
                                    <?php
                                    for($j=0;$j<=sizeof($studentids);$j++)
                                    {
                                        $carameid='txt_2_'.$studentids[$j];                                      
                                        $racetimeid='txt_3_'.$studentids[$j]; 

                                        $carname = $ObjDB->SelectSingleValue("SELECT b.fld_datasheet_recordname AS dsrecordname
                                                                                           FROM  itc_sos_datasheet_master AS a 
                                                                                           LEFT JOIN itc_sos_datasheet_records AS b ON a.fld_id=b.fld_datasheet_id 
                                                                                           WHERE b.fld_datasheet_id='".$datasheetid."' AND b.fld_view_cellid='".$carameid."' AND b.fld_delstatus='0'");

                                         $tracksur = $ObjDB->SelectSingleValue("SELECT a.fld_track_surface
                                                                                           FROM  itc_sos_datasheet_master AS a 
                                                                                                    WHERE a.fld_id='".$datasheetid."' AND a.fld_delstatus='0' AND a.fld_created_by='".$uid."'");
                                         
                                        $racetime = $ObjDB->SelectSingleValue("SELECT b.fld_datasheet_recordname AS dsrecordname
                                                                                           FROM  itc_sos_datasheet_master AS a 
                                                                                           LEFT JOIN itc_sos_datasheet_records AS b ON a.fld_id=b.fld_datasheet_id 
                                                                                           WHERE b.fld_datasheet_id='".$datasheetid."' AND  b.fld_view_cellid='".$racetimeid."' AND b.fld_delstatus='0'");

                                       $cartridge = $ObjDB->SelectSingleValueInt("SELECT a.fld_co2cartridge AS catridge
                                                                                           FROM  itc_sos_datasheet_master AS a 
                                                                                           WHERE a.fld_id='".$datasheetid."' AND a.fld_track_length='".$tracklength."' AND a.fld_delstatus='0'");
                                                        
                                        if($cartridge='1'){
                                            $co2cart='8 gram';
                                        }
                                        else{
                                            $co2cart='4 gram';
                                        }
                                        
                                        $details[]=$carname."~".$racetime."~".$studentids[$j]; 
                                        $detailracetime[]=$racetime;
                                    }
                                    
                                    asort($detailracetime);
                                    $maxs = array_keys($detailracetime);
                                    
                                    if(sizeof($maxs)>=25){
                                        $cunt=25;                                       
                                    }
                                    else{
                                        $cunt=sizeof($maxs);
                                    }
                                    for($e=0;$e<$cunt;$e++){
                                       $result[]=$details[$maxs[$e]];                                     
                                    }

                                    for($j=1;$j<sizeof($result);$j++)
                                    {
                                        $finalres=explode("~",$result[$j]);
                                       ?>
                                            <tr >
                                                <td style="cursor: default;"><?php echo $statename; ?></td>
                                                <td style="cursor: default;"><?php echo $yeear ; ?></td>
                                                <td style="cursor:pointer;"  onclick="fn_showdetails('<?php echo $datasheetid; ?>','<?php echo $finalres[2]; ?>');"><?php echo $finalres[0]; ?></td>
                                                <td style="cursor: default;"><?php echo $tracklength ; ?></td>
                                                <td style="cursor:default;"><?php echo $co2cart; ?></td>
                                                <td style="cursor:default;"><?php echo $tracksur; ?></td>
                                                <td style="cursor:default;"><?php echo $finalres[1]; ?></td>
                                           </tr>
                                        <?php                                                    
                                    } ?>  </table><br><br> 
                              <?php  }
                            } ?>  
                              
                             </div>	
                        </div>
                         <input type="hidden" id="hidfilename" name="hidfilename" value="<?php echo "fastesttimeoverall_"; ?>" />
                    </form>
                      <?php 
                    if($flag=='0')
                    { 
                        echo "No records";
                    }  
                    if($flag!='0'){ ?>  
                    <div class='row rowspacer' id="viewreportdiv">
                        <input class="darkButton" type="button" id="btnstep" style="width:200px; height:42px; float:right;" value="Viewreport" onClick="fn_fastesttimeoverall(<?php echo $uid; ?>);" />
                    </div> 
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>	
                <!--View Report Button-->	
                            
</div>                            
</section>
<?php
	@include("footer.php");
