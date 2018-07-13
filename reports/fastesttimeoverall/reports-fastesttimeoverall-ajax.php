<?php 
	@include("sessioncheck.php");	
	$oper = isset($method['oper']) ? $method['oper'] : '';
	$date=date("Y-m-d H:i:s");
if($oper=='showcardetails' and  $oper!='')
{
        $sheetid= isset($method['dsid']) ? $method['dsid'] : '0';
        $stuid= isset($method['rowid']) ? $method['rowid'] : '0';
          
        $i=2;
        //----- New Query next and previews button start page-----// 
        $stuqry = $ObjDB->QueryObject("SELECT fld_id AS studid FROM itc_sos_student_master where fld_datasheet_id='".$sheetid."' AND fld_delstatus='0' ORDER BY fld_id ASC");
        while($rowstu=$stuqry->fetch_assoc())
        {
            extract($rowstu);
            $studentids[]=$studid;

        }
        for($j=0;$j<=sizeof($studentids);$j++)
        {
            $racetimeid='txt_3_'.$studentids[$j];
            
            $racetime = $ObjDB->SelectSingleValue("SELECT b.fld_datasheet_recordname AS dsrecordname
                                                        FROM  itc_sos_datasheet_master AS a 
                                                        LEFT JOIN itc_sos_datasheet_records AS b ON a.fld_id=b.fld_datasheet_id 
                                                        WHERE  b.fld_datasheet_id='".$sheetid."' AND b.fld_view_cellid='".$racetimeid."' AND b.fld_delstatus='0'  ");
             $details[]=$studentids[$j];
             $detailracetime[]=$racetime;
        }

        asort($detailracetime);
        $maxs = array_keys($detailracetime);

        if(sizeof($maxs)>=25){
            $cunt=sizeof($maxs);
        }
        else{
            $cunt=sizeof($maxs);
        }
        for($e=0;$e<$cunt;$e++){
           $result[]=$details[$maxs[$e]];
        }
        $key = array_search($stuid, $result); // $key = 2;
        $next = $result[$key+1];
        $previews = $result[$key-1];
        
        $firstval=$result[1];
        $lastsize=sizeof($result)-1;
      
        $lastval=$result[$lastsize];
        //----- New Query next and previews button End page created by chandru-----// 
        ?>

        <style>
            #fancybox-content{
                border-width: 10px;
                width: 480px; 
                height: 600px;
            }
        </style>

	<div class="row " style="min-width:400px;">
    	<div style="margin-left:524px;margin-top:-5px;" >
			<a class="icon-synergy-close-dark tooltip" onclick="fn_cancelextendform()" title="Close" href="javascript:void(0);"></a>
      </div>
	</div>
           <table style=" border: 1px solid #dddddd; width:550px; font-size: 15px;  ">
                    <tr>
                        <th class='thclass' align="right" style=" font-weight: bold;">Data</th>
                          <th></th>
                    </tr>
        <?php
        $qrymodule=$ObjDB->NonQuery("SELECT fld_id AS detatilid,fld_detail_name AS detailname,fld_start_range AS startrange,fld_end_range AS endrange
                                                                            FROM itc_sos_details WHERE fld_delstatus='0' limit 1,16 ");
        while($rowmodule = $qrymodule->fetch_assoc()) // show the module based on number of copies
        {
            extract($rowmodule);
                ?>
                    <tr style=" border: 1px solid #dddddd;">
                       <td style=" border: 1px solid #dddddd; width:311px;"  id="detail_<?php echo $i;?>"><?php echo $detailname; ?> </td>
                       <td style=" border: 1px solid #dddddd;"  id="txt_<?php echo $i."_".$stuid;?>" ></td>
                    </tr>
                <?php
            $i++;
        } // while loop ends
        ?>
    </table>

 <?php
    $qrycelldet=$ObjDB->QueryObject("SELECT fld_datasheet_id AS dsid, fld_datasheet_recordname AS recordname, fld_view_cellid AS cellid FROM itc_sos_datasheet_records WHERE fld_datasheet_id='".$sheetid."' AND fld_delstatus='0' ");


    while($rowcelldet=$qrycelldet->fetch_assoc())
    {
        extract($rowcelldet);
        ?>
            <script>
                $('#<?php echo $cellid;?>').html('<?php echo "&nbsp;&nbsp;&nbsp;".$recordname;?>');
            </script>
        <?php
    }
    ?>
    <!--New Query for next and previews button Start page created by chandru -->
    <input type="hidden" name="teachclickstuid" id="teachclickstuid" value="<?php echo $stuid; ?>">
    <input type="hidden" name="currentstuid" id="currentstuid" value="<?php echo $stuid; ?>">
    <input type="hidden" name="nextstuid" id="nextstuid" value="<?php echo $stuid; ?>">
    <input type="hidden" name="previewsstuid" id="previewsstuid" value="<?php echo $stuid; ?>">
    <input type="hidden" name="firstid" id="firstid" value="<?php echo $firstval; ?>">
    <input type="hidden" name="lastid" id="lastid" value="<?php echo $lastval; ?>">

    <div style='margin-left: 155px; margin-top:22px; '>
        <input type="button" id="btnpre"  class="darkButton" style="width:100px; height:37px; margin-top:10px;" value="Previous" onClick="fn_nextstudent(<?php echo $sheetid.",2"; ?>);" />&nbsp;&nbsp;&nbsp;
        <input type="button" id="btnnext" class="darkButton" style="width:100px; height:37px; margin-top:10px;" value="Next" onClick="fn_nextstudent(<?php echo $sheetid.",1"; ?>);" />&nbsp;&nbsp;&nbsp;
    </div>
    <!--New Query for next and previews button Start page created by chandru -->

<?php
		
}
/********* New Query in next and previews Student Code Start Here***************/
if($oper == "nextstudent" and $oper != '') 
{
  
        $sheetid= isset($method['dsid']) ? $method['dsid'] : '0';
        $previousstuid= isset($method['previousstuid']) ? $method['previousstuid'] : '0';
        $currentstuid= isset($method['currentstuid']) ? $method['currentstuid'] : '0';
        $nextid= isset($method['nextid']) ? $method['nextid'] : '0';
        $status= isset($method['status']) ? $method['status'] : '0';
        
        $studentids=array();

        $stuqry = $ObjDB->QueryObject("SELECT fld_id AS studid FROM itc_sos_student_master where fld_datasheet_id='".$sheetid."' AND fld_delstatus='0' ORDER BY fld_id ASC");
        while($rowstu=$stuqry->fetch_assoc())
        {
            extract($rowstu);
            $studentids[]=$studid;

        }
        for($j=0;$j<=sizeof($studentids);$j++)
        {
            $racetimeid='txt_3_'.$studentids[$j];
            
            $racetime = $ObjDB->SelectSingleValue("SELECT b.fld_datasheet_recordname AS dsrecordname
                                                                                           FROM  itc_sos_datasheet_master AS a 
                                                                                           LEFT JOIN itc_sos_datasheet_records AS b ON a.fld_id=b.fld_datasheet_id 
                                                                                           WHERE b.fld_datasheet_id='".$sheetid."' AND  b.fld_view_cellid='".$racetimeid."' AND b.fld_delstatus='0'");
             $details[]=$studentids[$j];
             $detailracetime[]=$racetime;
        }

        asort($detailracetime);
        $maxs = array_keys($detailracetime);

        if(sizeof($maxs)>=25){
            $cunt=sizeof($maxs);
        }
        else{
            $cunt=sizeof($maxs);
        }
        for($e=0;$e<$cunt;$e++){
           $result[]=$details[$maxs[$e]];
        }
       
        if($status=='1')
        {
            $key = array_search($currentstuid, $result); // $key = 2;
            $next = $result[$key+1];
            $qry = $ObjDB->QueryObject("SELECT fld_datasheet_id AS dsid, fld_datasheet_recordname AS recordname, fld_stu_id AS studentid FROM itc_sos_datasheet_records
                                                            WHERE fld_datasheet_id='".$sheetid."' AND fld_stu_id='".$next."' AND fld_delstatus='0' limit 1,16");
        }
        else
        {
            $key = array_search($previousstuid, $result); // $key = 2;
            $previews = $result[$key-1];
            $qry = $ObjDB->QueryObject("SELECT fld_datasheet_id AS dsid, fld_datasheet_recordname AS recordname, fld_stu_id AS studentid FROM itc_sos_datasheet_records
                                                            WHERE fld_datasheet_id='".$sheetid."' AND fld_stu_id='".$previews."' AND fld_delstatus='0' limit 1,16");
        }
        if($qry->num_rows>0)
        {												
            while($rowqryclassmap = $qry->fetch_assoc())
            {
                extract($rowqryclassmap);
                $datasheetval[]=$recordname;
               
            }
        }
        $firstval=$result[1];
        $lastsize=sizeof($result)-1;
      
        $lastval=$result[$lastsize];
        echo json_encode($datasheetval)."~".$next."~".$previews."~".$firstval."~".$lastval."~".$currentstuid;
        
    }
/********* New Query in next and previews Student Code Start Here***************/
	@include("footer.php");
