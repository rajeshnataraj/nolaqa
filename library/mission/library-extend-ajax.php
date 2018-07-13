<?php 
/*
	Created By - Vijayalakshmi PHP Programmer
	Page - library-extend-ajax
	Description:
	   This page can work on the depends oper which means opertions here few opertions going to do it . like saving extend material details 
	   
	History:
	 no - update

*/
@include("sessioncheck.php"); 
$date = date("Y-m-d H:i:s");
$oper= isset($method['oper']) ? $method['oper'] : '';

/*****this opertion  performs to show the form for getting extend text(insert the extend name ****/

if($oper=='extendtxtform' and  $oper!='')
{
    
	$materialid= isset($method['materialid']) ? $method['materialid'] : '';
	$extendid= isset($method['extid']) ? $method['extid'] : '0';
	$type= isset($method['type']) ? $method['type'] : '';
	$extendtxt='';
       	
	$qryforgetexpdetails= $ObjDB->QueryObject("SELECT fld_mis_name as expname,fld_id as decryptexpid 
	                                             FROM itc_mission_master 
	                                             WHERE md5(fld_id)='".$materialid."'");
										   
	$rowqry_getexp_details = $qryforgetexpdetails->fetch_assoc();
	extract($rowqry_getexp_details);
	
	if($type=='rename' or $type=='copy')
	{
		$extendtxt= $ObjDB->SelectSingleValue("SELECT fld_extend_text 
		                                       FROM itc_mis_extendmaterials_master WHERE fld_id='".$extendid."'");
	}
	switch($type)
	{
	 case 'new':
       $typename='';
        break;
    case 'rename':
        $typename='Renaming';
        break;
    case 'copy':
        $typename='Copying';
        break;
	}
	?>
    <div class="four columns">
         <div class="row rowspacer" style="min-width:400px;">
            <center><span style="font-size:24px;" class="darkTitle"><?php echo $typename; ?> <?php echo $expname; ?></span></center>
         </div>
         <div class="row rowspacer" style="min-width:400px;">  
            <form name="expextendforms" id="expextendforms" >
            <div class="eleven columns" style="float: left; font-weight: bold; font-size: 15px;margin-left:15px" >
                <span style="color:red" >*</span>Material Name: &nbsp;&nbsp;&nbsp;
                <dl class="field row">
                   <dt class="text">
                      <input type="text" onblur="$(this).valid();" value="<?php echo $extendtxt; ?>" name="txtextensionname" id="txtextensionname" placeholder="Material Name" />
                   </dt> 
                </dl>
            </div>
            </form>
         </div>     
         <div class="row rowspacer" style="min-width:400px;">
			   <div style="margin-left:220px;margin-right:10px;" >
                <input style="margin-right:10px;width:90px" onclick="fn_saveextendexpform('<?php echo $decryptexpid; ?>','<?php echo $extendid; ?>','<?php echo $type; ?>');" type="button" class="module-extend-button" id="saveextend_btn" value="Save" /> 
           <input type="button" style="width:90px" onclick="fn_cancelextendform();" class="module-extend-button"   value="Cancel"   /> 
            </div>
        </div>
    </div>
       <script type="text/javascript" language="javascript" >
	   $('#txtextensionname').keypress(function(event) {
			if (event.which == 13) {
				event.preventDefault();
				fn_saveextendexpform('<?php echo $decryptexpid; ?>','<?php echo $extendid; ?>','<?php echo $type; ?>');
			}
		});
       $("#expextendforms").validate({
                            ignore: "",
                            errorElement: "dd",
							errorPlacement: function(error, element) {
								$(element).parents('dl').addClass('error');
								error.appendTo($(element).parents('dl'));
								error.addClass('msg');
								window.scroll(0,($('dd').offset().top)-50);
							},
                            rules: { 
                                txtextensionname: { required: true,
                                remote:{ 
                                        url: "library/expedition/library-expedition-extendremotecheck.php", 
                                        type:"POST",  
                                        data: {  
                                                extid: function() {
                                                return '<?php echo $extendid;?>';},
                                                expid: function() {
                                                return '<?php echo $decryptexpid; ?>;'},
                                                        oper: function() {
                                                        return 'checkextendname';}

                           }, 
                                         async:false 
                               }},
                           }, 
                            messages: { 
                                txtextensionname: { required: "please type extension name" ,  remote: "Material Extend Name already exists" }
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

if($oper=='saveextendtxt' and  $oper!='')
{
	try {
         
	$materialid= isset($method['materialid']) ? $method['materialid'] : '';
	$extendtext= isset($method['extendtxt']) ? ($method['extendtxt']) : '';
	$extendid= isset($method['extid']) ? $method['extid'] : '0';
	$type= isset($method['type']) ? $method['type'] : '';
       
	/**validation for the parameters and these below functions are validate to return true or false***/
	$validate_materialid=true;
	$validate_extendid=true;
	
	if($materialid!=0)  $validate_materialid=validate_datatype($materialid,'int');
	if($extendid!=0)  $validate_extendid=validate_datatype($extendid,'int');
	
		/**for purpose remove unwanted scripts****/
		$extendtext = $ObjDB->EscapeStrAll($extendtext);
		
	if($validate_materialid AND  $validate_extendid)
	{
         
		if($type=='new' or $type=='copy')
		{
	    
		 $created_name=$ObjDB->SelectSingleValue("SELECT CONCAT(fld_fname,' ',fld_lname) AS createdname 
		                                         FROM itc_user_master 
		                                         WHERE fld_id='".$uid."' AND fld_delstatus='0' ");
                 if($type=='copy')  {
		
                    $qryselectcopy_map =$ObjDB->QueryObject("SELECT M.fld_extend_id as mextendid,M.fld_mission as mexpid,M.fld_destination as mdestn,M.fld_task as mtask,M.fld_material as mmaterial FROM itc_mis_extendmaterials_mapping AS M
                                                               WHERE M.fld_extend_id='".$extendid."' AND M.fld_mission='".$materialid."' AND M.fld_created_by='".$uid."' AND M.fld_delstatus='0'");
                     
                    $extendid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_mis_extendmaterials_master
                                                                    (fld_mis_id,fld_extend_text,fld_school_id,fld_user_id,fld_created_by,fld_created_date,fld_created_name)
											   VALUES('".$materialid."','".$extendtext."','".$schoolid."','".$indid."','".$uid."','".$date."','".$created_name."')");
	
                       if($qryselectcopy_map->num_rows > 0)   {
                            while($row=$qryselectcopy_map->fetch_assoc())
                            {
                                    extract($row);
                                 $expmatlistid = $ObjDB->NonQueryWithMaxValue("INSERT INTO itc_mis_extendmaterials_mapping(fld_extend_id, fld_mission, fld_destination, fld_task, fld_material, fld_created_date, fld_created_by)
                                                  VALUES ('".$extendid."', '".$mexpid."', '".$mdestn."', '".$mtask."', '".$mmaterial."','".date("Y-m-d H:i:s")."','".$uid."')");
                            }
                       }
                 }
                 else
                 {
		
                    $extendid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_mis_extendmaterials_master
                                                                (fld_mis_id,fld_extend_text,fld_school_id,fld_user_id,fld_created_by,fld_created_date,fld_created_name)
											   VALUES('".$materialid."','".$extendtext."','".$schoolid."','".$indid."','".$uid."','".$date."','".$created_name."')");
                 }
                $access=true;
                echo "sucess~".$extendid."~".$created_name."~".$uid."~".md5($materialid)."~".$materialid."~".$uid."~".$access;
		}
		else
		{
			$ObjDB->NonQuery("UPDATE itc_mis_extendmaterials_master 
			                 SET fld_extend_text='".$extendtext."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' 
							 WHERE fld_id='".$extendid."'");
			echo "sucess";
		}
	}
	else
	{
		echo "fail";
	}
	  
	}
	catch(Exception  $e)
	{
		 echo "fail";
	}
}

/*****this opertion can perform to delete the existing extend text name in database****/

if($oper=='checkextendcontent' and  $oper!='')
{
	$id= isset($method['ex_id']) ? $method['ex_id'] : '0';
	
	try{

	 $chk = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM `itc_class_indasmission_extcontent_mapping` 
                                                 WHERE fld_mis_id='".$id."' AND fld_active='1'");
	
		if($chk==0)
		{
			echo "success";
		}
		else
		{
			echo "fail";
		}
	  
	}
	catch(Exception $e)
	{
		echo "fail"; 
	}
}


if($oper=='deleteextend' and  $oper!='')
{
	$extendid= isset($method['ex_id']) ? $method['ex_id'] : '';
	$schflag= isset($method['schflag']) ? $method['schflag'] : '';	
	$validate_extendid=true;
	
	if($extendid!=0)  $validate_extendid=validate_datatype($extendid,'int');
	
	try {
		if($validate_extendid)
		{
                
		$ObjDB->NonQuery("UPDATE itc_mis_extendmaterials_master SET fld_delstatus='1',fld_deleted_by='".$uid."',fld_deleted_date='".date("Y-m-d H:i:s")."'  
                                    WHERE fld_id='".$extendid."'");
								   
		$ObjDB->NonQuery("UPDATE itc_mis_extendmaterials_mapping SET fld_delstatus='1',fld_deleted_by='".$uid."',fld_deleted_date='".date("Y-m-d H:i:s")."' 
						 WHERE fld_extend_id='".$extendid."'  ");
						 
                if($schflag==1)
                {
                       $ObjDB->NonQuery("UPDATE itc_class_indasmission_extcontent_mapping SET fld_active='0', fld_updatedby='".$uid."', fld_updateddate='".date("Y-m-d H:i:s")."' where fld_mis_id='".$extendid."'"); 
                }
	  echo "sucess";
		}
		else
		{
			echo "fail";
		}
	}
	catch(Exception  $e)
	{
		 echo "fail";
	}
}

if($oper=='savetasklist' and  $oper!='')
{
	$destnid= isset($method['destnid']) ? $method['destnid'] : '';
        $extendid= isset($method['extendid']) ? $method['extendid'] : '';
        ?>
       <dl class='field row'>   
        <dt class='dropdown'>   
            <div class="selectbox materialbox" style="width:200px;">
                <input type="hidden" name="taskname" id="taskname"/>
                <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                    <span class="selectbox-option input-medium" data-option="" id="task_name" style="float:left;">Select Task</span>
                    <b class="caret1"></b>
                </a>
            <div class="selectbox-options" style="width:210px;">
             <input type="text" class="selectbox-filter" placeholder="Search Task" style="width:180px;">
                 <ul role="options">
                     <?php
                        $qrymaterial =  $ObjDB->QueryObject("SELECT fld_id as taskid, fld_task_name as taskname, fn_shortname (CONCAT(fld_task_name), 1) AS shortname, fld_task_desc, fld_mis_id 
                                                                    FROM itc_mis_task_master WHERE fld_dest_id='".$destnid."' AND fld_flag='1' AND fld_delstatus='0'"); 
                                             if($qrymaterial->num_rows > 0){
                                                     while($rowsqry = $qrymaterial->fetch_assoc()){
                                                             extract($rowsqry);
                                                                             ?>
             <li style="float:left;padding:3px 20px;"><a tabindex="-1" href="#" data-option="<?php echo $taskid;?>" class="tooltip" title="<?php echo $taskname;?>" id="option1<?php echo $taskid;?>" onclick="fn_loadtmaterialbox(<?php echo $taskid;?>,<?php echo $destnid; ?>,<?php echo $extendid;?>)"><?php echo $taskname;?></a></li>

                                         <?php   }
                                                     }
                                         ?>
                 </ul>
            </div>
            </div>
         </dt>                                         
        </dl>
                           
        <?php
}

if($oper=='savemateriallist' and  $oper!='')
{
	$taskid= isset($method['taskid']) ? $method['taskid'] : '';
        $destnid= isset($method['destnid']) ? $method['destnid'] : '';
        $extndid= isset($method['extndid']) ? $method['extndid'] : '';
      
        ?>
     <dl class='field row'>   
        <dt class='dropdown'>   
            <div class="selectbox materialbox" style="width:200px;">
                <input type="hidden" name="materialname" id="materialname"/>
                <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                    <span class="selectbox-option input-medium" data-option="" id="material_name" style="float:left;">Select Material</span>
                    <b class="caret1"></b>
                </a>
                  <div class="selectbox-options" style="width:210px;">
                        <input type="text" class="selectbox-filter" placeholder="Search Material" style="width:180px;">
                        <ul role="options">
                           <?php
                          
                               $qrymaterial =  $ObjDB->QueryObject("SELECT fld_id as materialid, fld_materials as material, fn_shortname (CONCAT(fld_materials), 1) AS shortname FROM itc_mis_materials_master
                                                                                                        WHERE fld_delstatus='0' AND fld_created_by='".$uid."' AND fld_id NOT IN(SELECT fld_material FROM itc_mis_extendmaterials_mapping where fld_destination='".$destnid."' AND fld_task='".$taskid."' AND fld_extend_id='".$extndid."')");
                                    if($qrymaterial->num_rows > 0){
                                            while($rowsqry = $qrymaterial->fetch_assoc()){
                                                    extract($rowsqry);     ?>
                        <li style="float:left;padding:3px 20px;"><a tabindex="-1" href="#" data-option="<?php echo $materialid;?>" class="tooltip" title="<?php echo $material;?>" id="option1<?php echo $materialid;?>" onclick=""><?php echo $material;?></a></li>

                            <?php     }
                                             }
                             ?>

                        </ul>
                    </div>

            </div>
        </dt>                                         
    </dl> 
<?php }

if($oper=='saveexpmaterials' and  $oper!='')
{
    $destnid = isset($_POST['destnname']) ? $_POST['destnname'] : '';
    $taskid = isset($_POST['taskname']) ? $_POST['taskname'] : '';
    $materialid = isset($_POST['material']) ? $_POST['material'] : '';
    $extendid = isset($_POST['extendid']) ? $_POST['extendid'] : '';
    $expnid = isset($_POST['expednname']) ? $_POST['expednname'] : '';
    $expnmaterialid = isset($_POST['expnmaterialid']) ? $_POST['expnmaterialid'] : '';
      
    try  
    {
        if($expnmaterialid == 0)
        {
            $expmatlistid = $ObjDB->NonQueryWithMaxValue("INSERT INTO itc_mis_extendmaterials_mapping(fld_extend_id, fld_mission, fld_destination, fld_task, fld_material, fld_created_date, fld_created_by)
                                                  VALUES ('".$extendid."', '".$expnid."', '".$destnid."', '".$taskid."', '".$materialid."','".date("Y-m-d H:i:s")."','".$uid."')");
          echo "sucess";
        }
        else {
            $ObjDB->NonQuery("UPDATE itc_mis_extendmaterials_mapping SET fld_extend_id='".$extendid."', fld_mission='".$expnid."', fld_destination='".$destnid."', fld_task='".$taskid."', fld_material='".$materialid."', 
                                fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$expnmaterialid."'");
			echo "sucess";
        }
            
    } catch (Exception $ex) {
        echo "fail".$e;
    }
}

if($oper=='showdefineexpmateril' and  $oper!='')
{
   $expmatid = isset($method['expmatid']) ? $method['expmatid'] : '0';
   
  $qry_expdetailslist= $ObjDB->QueryObject("SELECT S2.fld_dest_name as destname,S3.fld_task_name as taskname,S4.fld_materials as materialname,S1.fld_id as expextmatid,S1.fld_destination as destnid,S1.fld_task as taskid, S1.fld_material as materialid
                                                FROM itc_mis_extendmaterials_mapping AS S1 
                                              INNER JOIN itc_mis_destination_master AS S2 ON S2.fld_id = S1.fld_destination
                                              INNER JOIN itc_mis_task_master AS S3 ON S3.fld_id = S1.fld_task
                                              INNER JOIN itc_mis_materials_master AS S4 ON S4.fld_id = S1.fld_material where S1.fld_id='".$expmatid."'");
										   
	$rowqry_exp_detailslist = $qry_expdetailslist->fetch_assoc();
	extract($rowqry_exp_detailslist);
        echo $destname."~".$destnid."~".$taskname."~".$taskid."~".$materialname."~".$materialid."~".$expextmatid;
   
}
/* delete define expedition materil list */
if($oper == "deletedefineexpmaterial" and $oper != '')
{		
        $expmatid = isset($method['expmatid']) ? $method['expmatid'] : '0';       
        $ObjDB->NonQuery("UPDATE itc_mis_extendmaterials_mapping SET fld_delstatus='1',fld_deleted_date='".date("Y-m-d H:i:s")."',fld_deleted_by='".$uid."' WHERE fld_id='".$expmatid."'");
        
}

@include("footer.php");
