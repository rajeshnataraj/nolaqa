<?php
@include("sessioncheck.php");

$id = (isset($method['id'])) ? $method['id'] : 0;
$ids=explode('~',$id);


$yearid = $ids[0];
$stateid = $ids[1];
$tracklenval = $ids[2];

 if($tracklenval=='1'){
    $tracklength='65 Feet 7 inches';
}
else if($tracklenval=='2'){
     $tracklength='55 feet';
}
else if($tracklenval=='3'){
     $tracklength='45 feet';
}
else{
     $tracklength='Other';
}

?>

<section data-type='#sos-fastesttimestate-preview' id='sos-fastesttimestate-preview'>
    <div class='container'>
        <div class='row'>
            <p class="dialogTitle">Fastest Times by State</p>
            <p class="dialogSubTitleLight"></p>
        </div>
             <script language="javascript" type="text/javascript">
                   $.getScript('sos/fastesttimestate/sos-fastesttimestate.js');
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
                            $qry=$ObjDB->NonQuery("SELECT a.fld_id AS datasheetid,b.fld_sos_class_name AS classname, a.fld_term AS term, a.fld_year AS yeear, a.fld_state AS state,a.fld_student_count AS stucount,
                                                                a.fld_track_length AS tracklen FROM itc_sos_datasheet_master AS a  
                                                                LEFT JOIN itc_sos_class_master AS b ON a.fld_sosclass_id = b.fld_id
                                                                WHERE a.fld_state='".$stateid."' AND a.fld_year='".$yearid."' AND fld_track_length='".$tracklenval."' AND a.fld_delstatus='0' AND b.fld_delstatus='0' AND a.fld_created_by='".$uid."' order by classname");

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
                                                <p><?php echo ":  ".$yeear; ?></p>
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
                                        <br>
                                        <br>
                                        <table class='table table-hover table-striped table-bordered setbordertopradius' id="mytable" >
                                            <tr class="title">
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
                                                                                                    WHERE b.fld_datasheet_id='".$datasheetid."' AND b.fld_view_cellid='".$carameid."' AND b.fld_delstatus='0' AND a.fld_created_by='".$uid."'");

                                                        $tracksur = $ObjDB->SelectSingleValue("SELECT a.fld_track_surface
                                                                                                    FROM  itc_sos_datasheet_master AS a 
                                                                                                    WHERE a.fld_id='".$datasheetid."' AND a.fld_delstatus='0' AND a.fld_created_by='".$uid."'");

                                                        $racetime = $ObjDB->SelectSingleValue("SELECT b.fld_datasheet_recordname AS dsrecordname
                                                                                                    FROM  itc_sos_datasheet_master AS a 
                                                                                                    LEFT JOIN itc_sos_datasheet_records AS b ON a.fld_id=b.fld_datasheet_id 
                                                                                                    WHERE b.fld_datasheet_id='".$datasheetid."' AND  b.fld_view_cellid='".$racetimeid."' AND b.fld_delstatus='0' AND a.fld_created_by='".$uid."'");
                                                        $cartridge = $ObjDB->SelectSingleValueInt("SELECT a.fld_co2cartridge AS catridge
                                                                                                    FROM  itc_sos_datasheet_master AS a 
                                                                                                    WHERE a.fld_id='".$datasheetid."' AND  a.fld_state='".$stateid."' AND a.fld_year='".$yearid."' AND a.fld_track_length='".$tracklength."' AND a.fld_delstatus='0' AND a.fld_created_by='".$uid."'");
                                                        
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
                                                               <td style="cursor:pointer;"  onclick="fn_showdetails('<?php echo $datasheetid; ?>','<?php echo $finalres[2]; ?>');"><?php echo $finalres[0]; ?></td>
                                                               <td style="cursor: default;"><?php echo $tracklength ; ?></td>
                                                               <td style="cursor:default;"><?php echo  $co2cart; ?></td>
                                                               <td style="cursor:default;"><?php echo $tracksur; ?></td>
                                                               <td style="cursor:default;"><?php echo $finalres[1]; ?></td>
                                                           </tr>
                                                        <?php                                                    
                                                    }
                                                  ?>  </table><br><br> 
                                  <?php  }
                                } ?>
                                 </div>	
                            </div>	
                    </form>
                    <?php 
                    if($flag=='0')
                    { 
                        echo "No records";
                    }  
                    if($flag!='0'){ ?>     
                <div class='row rowspacer' id="viewreportdiv">
		    <input class="darkButton" type="button" id="btnstep" style="width:200px; height:42px; float:right;" value="Viewreport" onClick="fn_showfastestbystate(<?php echo $uid; ?>);" />
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
