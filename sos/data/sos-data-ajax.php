<?php
/*
	Created By - MOhan. M
	
	History:
*/
@include("sessioncheck.php");
	
$date = date("Y-m-d H:i:s");
$oper = isset($method['oper']) ? $method['oper'] : '';



if($oper=='newclassnameform' and  $oper!='')
{

        ?>
    <div class="four columns">
        <div class="row rowspacer" style="min-width:400px;">  
           <form name="classnameextendforms" id="classnameextendforms" >
           <div class="eleven columns" style="float: left; font-weight: bold; font-size: 15px;margin-left:15px" >
               <span style="color:red" >*</span>New Class Name: &nbsp;&nbsp;&nbsp;
               <dl class="field row">
                  <dt class="text">
                     <input type="text" onblur="$(this).valid();" value="<?php echo $extendtxt; ?>" name="txtclassname" id="txtclassname" placeholder="New Class Name" />
                  </dt> 
               </dl>
           </div>
           </form>
        </div>     
        <div class="row rowspacer" style="min-width:400px;">
            <div style="margin-left:220px;margin-right:10px;" >
                <input style="margin-right:10px;width:90px" onclick="fn_saveclassform();" type="button" class="module-extend-button" value="Save"   /> 
                <input type="button" style="width:90px" onclick="fn_cancelclassform();" class="module-extend-button" value="Cancel"  /> 
            </div>
        </div>
    </div>
    <script type="text/javascript" language="javascript" >
        $('#txtclassname').keypress(function(event) {
            if (event.which == 13) {
                    event.preventDefault();
                    fn_saveextendform();
            }
        });
        $("#classnameextendforms").validate({
            ignore: "",
            errorElement: "dd",
            errorPlacement: function(error, element) {
                    $(element).parents('dl').addClass('error');
                    error.appendTo($(element).parents('dl'));
                    error.addClass('msg');
                    window.scroll(0,($('dd').offset().top)-50);
            },
            rules: { 
                txtclassname: { required: true }
            }, 
            messages: { 
                txtclassname: { required: "please type Class name" }, //, remote: "Module Name already exists"
            },
             highlight: function(element, errorClass, validClass) {
                $(element).parents('dl').addClass(errorClass);
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
    </script>
	<?php
}

/*****this opertion can perform to save the form details of getting extend text in database****/
if($oper=='saveclasstxt' and  $oper!='')
{
	try {
            
            $classnametxt= isset($method['classnametxt']) ? ($method['classnametxt']) : '';
            
            $extendid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_sos_class_master
                                           (fld_sos_class_name,fld_created_by,fld_created_date)
                                                                               VALUES('".$classnametxt."','".$uid."','".$date."')");

            echo "success";
		
	  
	}
	catch(Exception  $e)
	{
		 echo "fail";
	}
	
}

if($oper=="loadclassname" and $oper != " " )
{ ?>
  
        Class Name<span class="fldreq">*</span> 
        <dl class='field row'>
            <dt class='dropdown'>
                <div class="selectbox">
                    <input type="hidden" name="classname" id="classname" value="<?php echo $classname;?>" onchange="$(this).valid();">
                    <a class="selectbox-toggle" tabindex="1" role="button" data-toggle="selectbox" href="#">
                        <span class="selectbox-option input-medium" data-option="<?php if($sheetid==0){ echo "0";} else { echo $classname;}?>"><?php if($sheetid == 0){ echo "Select class";} else {echo $classname;}?></span><b class="caret1"></b>
                    </a>
                    <?php if($sheetid == 0){?>
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
                    <?php } ?>
                </div>
            </dt>
        </dl>
                    
        <?php
}

/*--- Delete the Data ---*/
if($oper=="deletedatasheet" and $oper != " " )
{
	try
	{
	
                $datasheetid = isset($method['dataid']) ? $method['dataid'] : ''; 
	
		$sheetcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_sos_datasheet_master 
		                                          WHERE fld_id='".$datasheetid."' AND fld_delstatus='0'");
		if($sheetcount!=0)
		{
			 $ObjDB->NonQuery("UPDATE itc_sos_datasheet_master 
                                            SET fld_delstatus='1', fld_deleted_date = '".$date."' ,fld_deleted_by = '".$uid."'		
                                            WHERE fld_id='".$datasheetid."'");
		
			
			echo "success";
		}
		else
		{
			echo "exists";
		}
	
  }
  catch(Exception $e)
  {
	  echo "fail";
	  
  }
}

/*--- Save and Update the Data ---*/
if($oper=="savedatasheet" and $oper != " " )
{
    try{
            
	$sheetid = isset($method['sheetid']) ? $method['sheetid'] : '0';
        $datasheetname=isset($method['datasheetname']) ? $method['datasheetname'] : '';
        $classid = isset($method['classname']) ? ($method['classname']) : ''; 
	$term = isset($method['term']) ? ($method['term']) : '0'; 
	$state = isset($method['state']) ? $method['state'] : '0'; 
        $year = isset($method['year']) ? $method['year'] : '0000';
        $tracklen = isset($method['tracklen']) ? ($method['tracklen']) : '0'; 
	$co2cart = isset($method['co2cart']) ? $method['co2cart'] : '0'; 
        $tracksuface = isset($method['txttracksuface']) ? ($method['txttracksuface']) : ''; 
	
	
        $sheetdetail = isset($method['detail']) ? $method['detail'] : ''; 
        $sheetdetailtemp = explode('^',$sheetdetail);
        
        $headercount = isset($method['headercount']) ? $method['headercount'] : '0'; 
        
        $newstucount = isset($method['newstucount']) ? $method['newstucount'] : '0'; 
        
        $dashid = isset($method['dashid']) ? $method['dashid'] : '0'; 
        
        
        $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_sos_datasheet_master 
                                                            WHERE fld_id='".$dashid."' AND fld_delstatus='0'");
        if($cnt==0)
        {
            $maxid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_sos_datasheet_master 
                                                (fld_data_sheetname, fld_sosclass_id, fld_term, fld_state, 
                                                    fld_year, fld_created_date, fld_created_by, fld_student_count, fld_track_length, fld_co2cartridge,fld_track_surface) 
                                                VALUES ('".$datasheetname."','".$classid."', '".$term."', 
                                                       '".$state."', '".$year."', '".$date."', '".$uid."', '".$studentcount."', '".$tracklen."', '".$co2cart."', '".$tracksuface."')");

        }
        else
        {
            $ObjDB->NonQuery("UPDATE itc_sos_datasheet_master 
                           SET fld_data_sheetname='".$datasheetname."', fld_sosclass_id = '".$classid."' ,
                           fld_term='".$term."', fld_state = '".$state."', fld_year = '".$year."' ,fld_updated_by = '".$uid."', 
                           fld_updated_date = '".$date."', fld_student_count='".$studentcount."', fld_track_length = '".$tracklen."', fld_track_surface = '".$tracksuface."',
                           fld_co2cartridge='".$co2cart."' WHERE fld_id='".$cnt."' AND fld_delstatus='0'");
            $maxid=$cnt;
        }


        
        if($newstucount=='0' || $newstucount=='')
        {
            
            $studentcount=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_sos_student_master 
                                                                (fld_datasheet_id, fld_created_date, fld_created_by) 
                                                                VALUES ('".$maxid."','".$date."', '".$uid."')");
        }
        else
        {
            
            $studentcount=$newstucount;
          
        }
        


        for($i=0;$i<(sizeof($sheetdetailtemp)-1);$i++) 
        {
            $sheetdetailtemp[$i] = ltrim($sheetdetailtemp[$i],",");
            $sdetails = explode(',',$sheetdetailtemp[$i]);
            for($j=0;$j<(sizeof($sdetails));$j++) 
            {
                $cellid="txt_".($j+1);     
                $viewcellid='txt_'.($j+1).'_'.$studentcount;
                
                $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_sos_datasheet_records 
                                                            WHERE fld_cell_id='".$cellid."' AND fld_stu_id='".$studentcount."'  AND fld_datasheet_id='".$maxid."' AND fld_delstatus='0'");
                if($cnt==0)
                {
                         $ObjDB->NonQuery("INSERT INTO itc_sos_datasheet_records
                                                            (fld_datasheet_id, fld_datasheet_recordname, fld_cell_id, fld_stu_id, fld_created_date, fld_created_by, fld_view_cellid)	
                                                                    VALUES('".$maxid."', '".$sdetails[$j]."', '".$cellid."', '".$studentcount."', '".$date."', '".$uid."', '".$viewcellid."')");
                }
                else
                {
                        $ObjDB->NonQuery("UPDATE itc_sos_datasheet_records 
                                                    SET fld_datasheet_recordname='".$sdetails[$j]."', fld_cell_id='".$cellid."', fld_updated_date = '".$date."' ,fld_updated_by = '".$uid."',fld_stu_id='".$studentcount."',fld_view_cellid='".$viewcellid."'	
                                                    WHERE fld_datasheet_id='".$maxid."' AND fld_id='$cnt' AND fld_stu_id='".$studentcount."' AND fld_delstatus='0'");
                }

            }
        }
        
        $precnt = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_sos_datasheet_records WHERE fld_stu_id<'".$studentcount."' AND fld_datasheet_id='".$maxid."' AND fld_delstatus='0' limit 0,16");
        $nxtcnt = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_sos_datasheet_records WHERE fld_stu_id>'".$studentcount."' AND fld_datasheet_id='".$maxid."' AND fld_delstatus='0' limit 0,16");
            
        echo "success~".$maxid."~".$studentcount."~".$precnt."~".$nxtcnt;
        
      
	
    }
    catch(Exception $e)
    {
        echo "fail";
    }
}

/*********Previous Student Code Start Here***************/
if($oper == "prestudent" and $oper != '') 
{
    $stcount = isset($method['stcount']) ? $method['stcount'] : '0';
    $currentstuid=isset($method['currentstuid']) ? $method['currentstuid'] : '0';
    $dashid = isset($method['dashid']) ? $method['dashid'] : '0'; 
    
    $datasheetval=array();
    
    $prestuval= $ObjDB->SelectSingleValueInt("SELECT fld_stu_id AS prestudentid FROM itc_sos_datasheet_records WHERE fld_stu_id<'".$currentstuid."' AND fld_datasheet_id='".$dashid."' AND fld_delstatus='0' ORDER BY fld_stu_id DESC LIMIT 0,16");
    
    $qry = $ObjDB->QueryObject("SELECT fld_datasheet_recordname AS dataval FROM itc_sos_datasheet_records WHERE fld_stu_id='".$prestuval."' AND fld_datasheet_id='".$dashid."' AND fld_delstatus='0' LIMIT 0,16");
    if($qry->num_rows>0)
    {												
        while($rowqryclassmap = $qry->fetch_assoc())
        {
            extract($rowqryclassmap);
            $datasheetval[]=$dataval;            
        }
     }
     $cnt = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_sos_datasheet_records WHERE fld_stu_id<'".$prestuval."' AND fld_datasheet_id='".$dashid."' AND fld_delstatus='0' limit 0,16");
     $nxtcnt = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_sos_datasheet_records WHERE fld_stu_id>'".$prestuval."' AND fld_datasheet_id='".$dashid."' AND fld_delstatus='0' limit 0,16");
            
     echo json_encode($datasheetval)."~".$cnt."~".$prestuval."~".$nxtcnt;
}
/*********Previous Student Code End Here***************/

/*********Next Student Code Start Here***************/
if($oper == "nextstudent" and $oper != '') 
{
    $stcount = isset($method['stcount']) ? $method['stcount'] : '0';
    $currentstuid=isset($method['currentstuid']) ? $method['currentstuid'] : '0';
    $dashid = isset($method['dashid']) ? $method['dashid'] : '0'; 
    
    $datasheetval=array();
    $qry = $ObjDB->QueryObject("SELECT fld_datasheet_recordname AS dataval,fld_stu_id AS nextstudentid FROM itc_sos_datasheet_records WHERE fld_stu_id>'".$currentstuid."' AND fld_datasheet_id='".$dashid."' AND fld_delstatus='0' limit 0,16");
    if($qry->num_rows>0)
    {												
        while($rowqryclassmap = $qry->fetch_assoc())
        {
            extract($rowqryclassmap);
            $datasheetval[]=$dataval;
            $nxtstuval=$nextstudentid;
        }
     }
     
     $cnt = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_sos_datasheet_records WHERE fld_stu_id>'".$nxtstuval."' AND fld_datasheet_id='".$dashid."' AND fld_delstatus='0' limit 0,16");
     echo json_encode($datasheetval)."~".$cnt."~".$nxtstuval;
}
/*********Next Student Code End Here***************/

/*********Delete Student Code start Here***************/
if($oper=="deletestudent" and $oper != " " )
{
    try
    {
        $datasheetid = isset($method['dataid']) ? $method['dataid'] : ''; 
        $currentstuid = isset($method['currentstuid']) ? $method['currentstuid'] : ''; 

        $sheetcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_sos_datasheet_master 
                                                  WHERE fld_id='".$datasheetid."' AND fld_delstatus='0'");
        if($sheetcount!=0)
        {
            
            $ObjDB->NonQuery("UPDATE itc_sos_datasheet_records
                               SET fld_delstatus='1', fld_deleted_date = '".$date."' ,fld_deleted_by = '".$uid."'		
                               WHERE fld_datasheet_id='".$datasheetid."' AND fld_stu_id='".$currentstuid."'");
            
            $ObjDB->NonQuery("UPDATE itc_sos_student_master
                               SET fld_delstatus='1', fld_deleted_date = '".$date."' ,fld_deleted_by = '".$uid."'		
                               WHERE fld_datasheet_id='".$datasheetid."' AND fld_id='".$currentstuid."'");
            

            $precnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_sos_student_master WHERE fld_id<'".$currentstuid."' AND fld_datasheet_id='".$datasheetid."' AND fld_delstatus='0' limit 0,1");
            $nxtcnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_sos_student_master WHERE fld_id>'".$currentstuid."' AND fld_datasheet_id='".$datasheetid."' AND fld_delstatus='0' limit 0,1");
            
            if($nxtcnt!='0' || $nxtcnt!='')
            {
                $datasheetval=array();
                $qry = $ObjDB->QueryObject("SELECT fld_datasheet_recordname AS dataval,fld_stu_id AS nextstudentid FROM itc_sos_datasheet_records WHERE fld_stu_id>'".$currentstuid."' AND fld_datasheet_id='".$datasheetid."' AND fld_delstatus='0' limit 0,16");
                if($qry->num_rows>0)
                {												
                    while($rowqryclassmap = $qry->fetch_assoc())
                    {
                        extract($rowqryclassmap);
                        $datasheetval[]=$dataval;
                        $nxtstuval=$nextstudentid;
                    }
                }
                $pcnt = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_sos_datasheet_records WHERE fld_stu_id<'".$nxtstuval."' AND fld_datasheet_id='".$datasheetid."' AND fld_delstatus='0' ORDER BY fld_stu_id DESC limit 0,16");
                $cnt = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_sos_datasheet_records WHERE fld_stu_id>'".$nxtstuval."' AND fld_datasheet_id='".$datasheetid."' AND fld_delstatus='0' limit 0,16");
                echo json_encode($datasheetval)."~".$cnt."~".$nxtstuval."~".$pcnt."~2";
                
            }
            else if($precnt!='0' || $precnt!='')
            {
                $datasheetval=array();
                $prestuval = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_sos_student_master WHERE fld_id<'".$currentstuid."' AND fld_datasheet_id='".$datasheetid."' AND fld_delstatus='0' ORDER BY fld_id DESC limit 0,1");               

                $qry = $ObjDB->QueryObject("SELECT fld_datasheet_recordname AS dataval FROM itc_sos_datasheet_records WHERE fld_stu_id='".$prestuval."' AND fld_datasheet_id='".$datasheetid."' AND fld_delstatus='0' LIMIT 0,16");
                if($qry->num_rows>0)
                {												
                    while($rowqryclassmap = $qry->fetch_assoc())
                    {
                        extract($rowqryclassmap);
                        $datasheetval[]=$dataval;                     
                    }
                 }
                 $cnt = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_sos_datasheet_records WHERE fld_stu_id<'".$prestuval."' AND fld_datasheet_id='".$datasheetid."' AND fld_delstatus='0' ORDER BY fld_id DESC limit 0,16");
                 $nxtcnt = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_sos_datasheet_records WHERE fld_stu_id>'".$prestuval."' AND fld_datasheet_id='".$datasheetid."' AND fld_delstatus='0' limit 0,16");

                 echo json_encode($datasheetval)."~".$cnt."~".$prestuval."~".$nxtcnt."~1";
            }
            else
            {
                echo "success~".$precnt."~".$nxtcnt."~".$nxtcnt."~0";
            }            
        }
        else
        {
            echo "exists";
        }
    }
    catch(Exception $e)
    {
            echo "fail";
    }
}
/*********Delete Student Code End Here***************/

/*********Import Student Code Start Here***************/
if($oper == "importstudents" and $oper != '') 
{
    error_reporting(E_ALL);
ini_set('display_errors', '1');
    
	$temp=0;
	
	$j=0;
	
	$a=0;

	$k=0;
	
	$b=0;
	
	$duplicateid='';

	$path =(isset( $method['path'])) ?  $method['path'] : '';
	$flag =(isset( $method['flagg'])) ?  $method['flagg'] : '';
        
        $datasheetname=isset($method['datasheetname']) ? $method['datasheetname'] : '';
        $classid = isset($method['classname']) ? ($method['classname']) : ''; 
	$term = isset($method['term']) ? ($method['term']) : '0'; 
	$state = isset($method['state']) ? $method['state'] : '0'; 
        $year = isset($method['year']) ? $method['year'] : '0000';
        $tracklen = isset($method['tracklen']) ? ($method['tracklen']) : '0'; 
	$co2cart = isset($method['co2cart']) ? $method['co2cart'] : '0'; 
        $tracksuface = isset($method['txttracksuface']) ? ($method['txttracksuface']) : ''; 
        
         $dashid = isset($method['dashid']) ? $method['dashid'] : '0'; 
	
	
	@include(__EXACTPATH__.'PHPExcel/IOFactory.php');
	require_once __EXACTPATH__.'PHPExcel/Writer/CSV.php'; 
 	$inputFileName = '../../uploaddir/importstudents/'.$path;
	
	$data=array(); // 
	$vals=array(); //
	$val=array(); // 
	$cell=array(); //
	$arr=array(); //
	$pathinfo = pathinfo($inputFileName);
	$extensionType = NULL;	
	
	$FileType = PHPExcel_IOFactory::identify($inputFileName);
	if($pathinfo['extension']=='csv')
	{
	  $FileType='CSV';	
	}
	$objReader = PHPExcel_IOFactory::createReader( $FileType);
	$objPHPExcel = $objReader->load($inputFileName);	
	
	$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
        PHPExcel_Settings::setCacheStorageMethod($cacheMethod);
		
	$worksheet = $objPHPExcel->getActiveSheet();
	$highestRow         = $worksheet->getHighestRow(); // e.g. 10
	$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
	$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

	$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
	
        $stuname=array();
	
	$unwanteddatarow=array();
        if(fnEscapeCheck($sheetData[1]['A'])==fnEscapeCheck('First Name') and fnEscapeCheck($sheetData[1]['B'])==fnEscapeCheck('Last Name'))
        { 
            
            $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_sos_datasheet_master 
                                                                WHERE fld_id='".$dashid."' AND fld_delstatus='0'");
            if($cnt==0)
            {
                $maxid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_sos_datasheet_master 
                                                (fld_data_sheetname, fld_sosclass_id, fld_term, fld_state, 
                                                    fld_year, fld_created_date, fld_created_by, fld_student_count, fld_track_length, fld_co2cartridge,fld_track_surface) 
                                                VALUES ('".$datasheetname."','".$classid."', '".$term."', 
                                                       '".$state."', '".$year."', '".$date."', '".$uid."', '0', '".$tracklen."', '".$co2cart."', '".$tracksuface."')");

            }
            else
            {
                $ObjDB->NonQuery("UPDATE itc_sos_datasheet_master 
                               SET fld_data_sheetname='".$datasheetname."', fld_sosclass_id = '".$classid."' ,
                               fld_term='".$term."', fld_state = '".$state."', fld_year = '".$year."' ,fld_updated_by = '".$uid."', 
                               fld_updated_date = '".$date."', fld_student_count='0', fld_track_length = '".$tracklen."', fld_track_surface = '".$tracksuface."',
                               fld_co2cartridge='".$co2cart."' WHERE fld_id='".$cnt."' AND fld_delstatus='0'");
                $maxid=$cnt;
            }
            
            for($i=2;$i<=sizeof($sheetData);$i++)
            {
                $data=$sheetData[$i];
                $fname=addslashes($data['A']);
                $lname=addslashes($data['B']);

                if($fname != "" and $lname != "")
                {                   
                    $studentname=$fname." ".$lname;
                   
    

                    $studentcount=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_sos_student_master 
                                                                (fld_datasheet_id, fld_student_name, fld_created_date, fld_created_by) 
                                                                VALUES ('".$maxid."', '".$studentname."', '".$date."', '".$uid."')");
                    for($s=1;$s<=16;$s++){
                         $cellid='txt_'.$s;
                         $viewcellid='txt_'.$s.'_'.$studentcount;
                        if($s=='1'){
                             $ObjDB->NonQuery("INSERT INTO itc_sos_datasheet_records
                                                            (fld_datasheet_id, fld_datasheet_recordname, fld_cell_id, fld_stu_id, fld_created_date, fld_created_by, fld_view_cellid)	
                                                                    VALUES('".$maxid."', '".$studentname."', '".$cellid."', '".$studentcount."', '".$date."', '".$uid."', '".$viewcellid."')");
                        }
                        else{
                            $stutname='';
                             $ObjDB->NonQuery("INSERT INTO itc_sos_datasheet_records
                                                            (fld_datasheet_id, fld_datasheet_recordname, fld_cell_id, fld_stu_id, fld_created_date, fld_created_by, fld_view_cellid)	
                                                                    VALUES('".$maxid."', '".$stutname."', '".$cellid."', '".$studentcount."', '".$date."', '".$uid."', '".$viewcellid."')");
                        }
                        
                    }
           
                   
                    
                }
                else
                {
                     if($fname !="" or $lname !="" or $username !=""  ){
                            $unwanteddatarow[]=array("rowno"=>$i,"fname"=>$fname,"lname"=>$lname,"reason"=>"Required Field is empty");
                     }
                }
            }          
            
            $studentcount = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_sos_student_master where fld_datasheet_id='".$maxid."' ANd fld_delstatus='0'");
            $nextstucount = $ObjDB->SelectSingleValueInt("SELECT fld_stu_id FROM itc_sos_datasheet_records where fld_datasheet_id='".$maxid."' group by fld_stu_id");
            
            $precnt = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_sos_datasheet_records WHERE fld_stu_id<'".$nextstucount."' AND fld_datasheet_id='".$maxid."' AND fld_delstatus='0' limit 0,16"); // ORDER BY fld_stu_id DESC 
            $nxtcnt = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_sos_datasheet_records WHERE fld_stu_id>'".$nextstucount."' AND fld_datasheet_id='".$maxid."' AND fld_delstatus='0' limit 0,16");
            
            echo "success~".$maxid."~".$studentcount."~".$nextstucount."~".$precnt."~".$nxtcnt;
            
        }
        else
        {   ?>
            <div class="title-info" style="font-weight:bold">file not have vaild format</div>   
            <?php 	
        }
}
/*********Import Student Code End Here***************/

/*********Show Import Student Code Start Here***************/
if($oper == "showimportstudent" and $oper != '') 
{
    $sheetid = isset($method['sheetid']) ? $method['sheetid'] : '0';
?>

        <div>       	                       
            <div class="gridtableouter" style="width:850px; height:650px;">
                <table class="fancyTable" id="myTable0" cellpadding="0" cellspacing="0">
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
                       <tr  id="tr_<?php echo $i;?>" class="<?php echo $detatilid;?>" >
                           <td id="detail_<?php echo $i;?>" class="tooltip" title="<?php echo $detailname; ?>"><?php echo $det; ?> </td>
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
                    <?php
                        $qrycelldet=$ObjDB->QueryObject("SELECT fld_datasheet_id AS dsid, fld_datasheet_recordname AS recordname, fld_cell_id AS cellid FROM itc_sos_datasheet_records WHERE fld_datasheet_id='".$sheetid."' AND fld_delstatus='0' limit 0,16");


                        while($rowcelldet=$qrycelldet->fetch_assoc())
                        {
                             extract($rowcelldet);
                        ?>
                           <script>
                                $('#<?php echo $cellid;?>').val('<?php echo $recordname;?>');
                                alert("hai");
                           </script>
                        <?php
                        }
                    ?>
            </div>
        </div>
<?php    
}
/*********Show Import Student Code End Here***************/