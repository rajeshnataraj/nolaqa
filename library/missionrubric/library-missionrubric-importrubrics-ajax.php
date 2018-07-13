<?php

@include("sessioncheck.php");

$oper = isset($method['oper']) ? $method['oper'] : '';
$date = date("y-m-d H:i:s");
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
$editid = isset($method['id']) ? $method['id'] : '';

if($oper=="showimportrubric" )
{
$rubricname='';
$misid = isset($method['expid']) ? $method['expid'] : '';
?>
<script language="javascript" type="text/javascript">
$.getScript("library/missionrubric/library-missionrubric-importrubrics.js");
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
    'buttonText' : 'Select File',
    'removeCompleted' : true,
    'fileTypeExts' : '*.xls; *.xlsx; *.csv;',
    'onUploadSuccess' : function(file, data, response) {
            fn_importstudents(data);
     },
     'onUploadProgress' : function(file, bytesUploaded, bytesTotal, totalBytesUploaded, totalBytesTotal) {
       $('#userphoto').addClass('dim');   
    }

});

</script>

 <div class="row">  
    <div class='six columns'>
           Rubric Name<span class="fldreq">*</span>
           <dl class='field row'>
               <dt class='text'>
                   <input placeholder='Rubric Name' type='text' id="txtrubricname" name="txtrubricname" value="<?php echo $rubricname;?>" onBlur="$(this).valid();" />
               </dt>
           </dl>
    </div>   
 </div> 

<div class="row rowspacer" id="fileupload">
    <div class="row "> Import New Rubric: </div>
    
    <div class="three columns" >
        <div><a id="file_upload"> </a></div>
        <br />(File type: .xls, .xlsx, .csv) 
    </div>
    
    <div class="six" style="float:left"> Please <a href="import_rubric.xls" style="font-weight:bold">click here to download sample file</a> to import the rubric. The fields Destination Name, Category and 4 and 3 and 2 and 1 and 0,Weight are the required. </div>
</div>

 <script type="text/javascript" language="javascript">
    //Function to validate the form
    $("#form1").validate({
            ignore: "",
            errorElement: "dd",
            errorPlacement: function(error, element) {
                    $(element).parents('dl').addClass('error');
                    error.appendTo($(element).parents('dl'));	
                    error.addClass('msg');
            },
            rules: {
                    txtrubricname: { required: true, lettersonly: true,
                    remote:{ 
                                url: "library/missionrubric/library-missionrubric-importrubrics-ajax.php", 
                                type:"POST", 
                                data: {  
                                                id: function() {
                                                return '<?php echo $editid;?>';},
                                                oper: function() {
                                                return 'checkrubricname';}														  
                                },
                                async:false 
                    }},
            }, 
            messages: { 
                    txtrubricname: { required: "Please type Rubric Name", remote: "Rubric Name already exists" }

            },
            highlight: function(element, errorClass, validClass) {
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


if($oper == "importstudents" and $oper != '') 
{
     $rubname = isset($method['rubname']) ? $method['rubname'] : ''; //rubric
	$temp=0;
	
	$j=0;
	
	$a=0;

	$k=0;
	
	$b=0;
	
	$duplicateid='';

	$path =(isset( $method['path'])) ?  $method['path'] : '';
        $misid= isset($method['exp']) ? $method['exp'] : '';
	
	
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
	
	$unwanteddatarow=array();
        if(fnEscapeCheck($sheetData[1]['A'])==fnEscapeCheck('Destination Name') and fnEscapeCheck($sheetData[1]['B'])==fnEscapeCheck('Category') and fnEscapeCheck($sheetData[1]['C'])==fnEscapeCheck('4') and  fnEscapeCheck($sheetData[1]['D'])==fnEscapeCheck('3') and fnEscapeCheck($sheetData[1]['E'])==fnEscapeCheck('2') and fnEscapeCheck($sheetData[1]['F'])==fnEscapeCheck('1') and fnEscapeCheck($sheetData[1]['G'])==fnEscapeCheck('0') and fnEscapeCheck($sheetData[1]['H'])==fnEscapeCheck('Weight'))/* and fnEscapeCheck($sheetData[1]['E'])==fnEscapeCheck('Grade') and fnEscapeCheck($sheetData[1]['F'])==fnEscapeCheck('Custodian First Name') and fnEscapeCheck($sheetData[1]['G'])==fnEscapeCheck('Custodian Last Name') and fnEscapeCheck($sheetData[1]['H'])==fnEscapeCheck('Custodian Email') and fnEscapeCheck($sheetData[1]['I'])==fnEscapeCheck('Phone Number'))*/
        {                     
                    
           for($i=2;$i<=sizeof($sheetData);$i++)
           {
               $data=$sheetData[$i];                     	   
               //placeholders array
                $placeholders = array('&', '>', '!', '<');
                //replace values array
                $replace = array('and', 'greater than', 'ex', 'less than');
               
               
               $dname=addslashes($data['A']);
               
               $categoryname=addslashes($data['B']);
               $category = str_replace($placeholders, $replace, $categoryname);
               
               $fourtxt=addslashes($data['C']);
               $four = str_replace($placeholders, $replace, $fourtxt);
               
               $threetxt=addslashes($data['D']);
               $three = str_replace($placeholders, $replace, $threetxt);
               
               $twotxt=addslashes($data['E']);
               $two = str_replace($placeholders, $replace, $twotxt);
               
               $onetxt=addslashes($data['F']);
               $one = str_replace($placeholders, $replace, $onetxt);
               
               $zerotxt=addslashes($data['G']);
               $zero = str_replace($placeholders, $replace, $zerotxt);
               
               $weight=addslashes($data['H']);               
             
               
               if($dname != "" and $category != "" and $four != "" and $three != "" and $two != "" and $one != "" and $zero != "" and $weight != "") //and $destidcount == 1
               {
                    $txtdestscore=$weight*4;
                  

                    $newid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_mis_rubric_master ORDER BY fld_id DESC LIMIT 1");
                    $newid=$newid+1;
                    $temp =1;

                    
                    
                    //new Line Start
                    $countrub = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_mis_rubric_name_master 
                                                      WHERE fld_rub_name='".$rubname."' AND fld_mis_id='".$misid."' and fld_delstatus='0' AND fld_created_by='".$uid."'");
                    if($countrub == 0)
                    { 
                        $rubrid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_mis_rubric_name_master (fld_rub_name, fld_mis_id, fld_created_by, fld_created_date, fld_district_id, fld_school_id, fld_user_id,fld_profile_id) 
                                                    VALUES ('".$rubname."', '".$misid."', '".$uid."', '".$date."','".$sendistid."','".$schoolid."','".$indid."','".$sessmasterprfid."')");
                    }
                    else
                    { 
                        $ObjDB->NonQuery("UPDATE itc_mis_rubric_name_master SET fld_rub_name='".$rubname."', fld_updated_by='".$uid."', 
                                                                         fld_updated_date='".$date."' WHERE fld_mis_id='".$misid."' AND fld_created_by='".$uid."' AND fld_id='".$countrub."' AND fld_delstatus='0'");
                        $rubrid = $countrub;
                    } 
                    //new Line End  
                    
				    $destidcount=$ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_mis_rubric_dest_master WHERE fld_mis_id='".$misid."' 
												AND fld_dest_name='".$dname."' AND fld_rubric_name_id='".$rubrid."' AND fld_delstatus='0'" );
               
				   if($destidcount=='' || $destidcount=='0' )
				   {
                        
                   		$maxid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_mis_rubric_dest_master (fld_rubric_name_id, fld_mis_id, fld_dest_name, fld_created_by, fld_created_date) 
                                                            VALUES ('".$rubrid."', '".$misid."', '".$dname."', '".$uid."','".$date."')");						

				   }
				   else
				   {
					   $maxid=$destidcount;
				   }
				   
                    
                   $ObjDB->NonQuery("INSERT INTO itc_mis_rubric_master (`fld_rubric_id`,`fld_mis_id`, `fld_destination_id`, `fld_category`, `fld_four`, `fld_three`, `fld_two`, `fld_one`, `fld_zer`, `fld_weight`, `fld_score`, `fld_created_by`, `fld_created_date`,`newid`) 
                                                VALUES ('".$rubrid."', '".$misid."', '".$maxid."', '".$category."', '".$four."', '".$three."', '".$two."', '".$one."', '".$zero."', '".$weight."', '".$txtdestscore."', '".$uid."', '".date("Y-m-d H:i:s")."', '".$newid."')");//insert if not exist
                
                   
                }
                else
                {
                    if($dname != "" or $category != "" or $four != "" or $three != "" or $two != "" or $one != "" or $zero != "" or $weight != "")//or $destidcount == 1
                    {
                         $unwanteddatarow[]=array("rowno"=>$i,"dname"=>$dname,"category"=>$category,"four"=>$four,"three"=>$three,"two"=>$two,"one"=>$one,"zero"=>$zero,"weight"=>$weight,"reason"=>"Whether Destination name is wrong or Required Field is empty");
                    }
                }
           }
           ?>
            <?php if(!empty($unwanteddatarow))
            { ?>
               <div class="title-info">Some rubric statement's are not added:</div>   
               <table class='table table-hover table-striped table-bordered'>
                   <thead class='tableHeadText'>
                       <tr>	
                           <th class='centerText'>Row No</th>
                           <th class='centerText'>Destination Name</th>
                           <th class='centerText'>Category</th>
                           <th class='centerText'>4</th>
                           <th class='centerText'>3</th>
                           <th class='centerText'>2</th>
                           <th class='centerText'>1</th>
                           <th class='centerText'>0</th>
                           <th class='centerText'>Weight</th>
                           <th class='centerText'>reason</th>
                       </tr>
                   </thead>
                   <tbody>
                       <?php 
                       if(!empty($unwanteddatarow))
                       {
                           for($g=0;$g<sizeof($unwanteddatarow);$g++)
                           {
                                ?>
                            <tr class="noMouse" style="cursor:default;">
                                <td class="centerText"><?php echo $unwanteddatarow[$g]['rowno'];?></td>
                                <td class="centerText"><?php echo $unwanteddatarow[$g]['dname'];?></td>
                                <td class="centerText"><?php echo $unwanteddatarow[$g]['category'];?></td>
                                <td class="centerText"><?php echo $unwanteddatarow[$g]['four'];?></td> 
                                <td class="centerText"><?php echo $unwanteddatarow[$g]['three'];?></td> 
                                <td class="centerText"><?php echo $unwanteddatarow[$g]['two'];?></td>
                                <td class="centerText"><?php echo $unwanteddatarow[$g]['one'];?></td>
                                <td class="centerText"><?php echo $unwanteddatarow[$g]['zero'];?></td> 
                                <td class="centerText"><?php echo $unwanteddatarow[$g]['weight'];?></td>
                                <td class="centerText"><?php echo $unwanteddatarow[$g]['reason'];?></td>      
                            </tr>
                                 <?php
                           }
                       }
                       else if(empty($unwanteddatarow)) { ?>
                           <tr>
                              <td class="noMouse" style="cursor:default;" colspan="10">No Records</td>               
                           </tr>
                           <?php 
                        } 
                        ?>
                   </tbody>
               </table><?php 
            } else { 
           if($temp == 1) { ?> 
               <div class="title-info" style="font-weight:bold">Rubric statements are added successfully</div>
           <?php } else {?>  
               <div class="title-info" style="font-weight:bold">No Records</div> 
           <?php } }?>
               <?php 
        }
        else
	{   ?>
            <div class="title-info" style="font-weight:bold">File does not have a valid format</div>   
            <?php 	
        } 	
}


/*--- Check Rubric Name ---*/
if($oper=="checkrubricname") //rubric
{
	
            $misid = isset($method['id']) ? $method['id'] : '0'; 
            $txtrubricname = (isset($method['txtrubricname']) ?  fnEscapeCheck($method['txtrubricname']) : '');
            $count = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_mis_rubric_name_master 
		                                      WHERE MD5(LCASE(REPLACE(fld_rub_name,' ','')))='".$txtrubricname."' 
											   AND fld_delstatus='0' AND fld_mis_id<>'".$misid."'");
            if($count == 0){ echo "true"; }	else { echo "false"; }
        
}



@include("footer.php");     
