<?php 
	@include("sessioncheck.php");
	ini_set('display_errors', 1);
	error_reporting(E_ALL ^ E_NOTICE);

	$date = date("Y-m-d H:i:s");
	$oper = isset($method['oper']) ? $method['oper'] : '';
	
	/*--- save Dyad table cell details  ---*/
	if($oper == "saverotation" and $oper != '')
	{
		$classid = (isset($method['classid'])) ? $method['classid'] : 0;
		$scheduleid = (isset($method['scheduleid'])) ? $method['scheduleid'] : 0;
		$moduledet = (isset($method['moduledet'])) ? $method['moduledet'] : 0;	
		$celldet = (isset($method['celldet'])) ? $method['celldet'] : 0;
		$startdate = (isset($method['startdate'])) ? $method['startdate'] : 0;
		$enddate = (isset($method['enddate'])) ? $method['enddate'] : 0;
		$numberofrotation = (isset($method['numberofrotation'])) ? $method['numberofrotation'] : 0;
		
		$moduledet=explode(",",$moduledet);
		$celldet=explode(",",$celldet);
		$startdate=explode(",",$startdate);
		$enddate=explode(",",$enddate);
		
		$ObjDB->NonQuery("UPDATE itc_class_dyad_schedulemaster SET fld_dyadtableflg=1,fld_updated_date='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_id='".$scheduleid."'");
		$ObjDB->NonQuery("UPDATE itc_class_dyad_moduledet SET fld_flag='0' WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."'");
		
		$j=1;
		for($i=0;$i<sizeof($moduledet);$i++)
		{
			if($moduledet[$i]!="undefined")
			{
				$count=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_class_dyad_moduledet WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_module_id='".$moduledet[$i]."' AND fld_row_id='".$j."'");
				
				if($count==0)
				{
					$ObjDB->NonQuery("INSERT INTO itc_class_dyad_moduledet(fld_class_id,fld_schedule_id,fld_module_id,fld_row_id,fld_numberofrotation)VALUES('".$classid."','".$scheduleid."','".$moduledet[$i]."','".$j."','".$numberofrotation."')");
				}
				else
				{
					$ObjDB->NonQuery("UPDATE itc_class_dyad_moduledet SET fld_flag='1' WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_module_id='".$moduledet[$i]."' AND fld_row_id='".$j."'");
				}
			}
			
			$j++;
		}
		
		$qryorientation=$ObjDB->NonQuery("SELECT fld_id,fld_startdate,fld_enddate FROM itc_class_dyad_schedule_insstagemap WHERE fld_schedule_id='".$scheduleid."' and fld_orientationmod='1' and fld_flag='1'");
		
		$ObjDB->NonQuery("UPDATE itc_class_dyad_schedulegriddet SET fld_flag='0',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."'");
		
		if($qryorientation->num_rows>0)
		{
			$roworientation=$qryorientation->fetch_assoc();
			extract($roworientation);
			
			$oricount=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_class_dyad_schedulegriddet WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_rotation=0");
			
			$orimodid=$ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_module_master where fld_module_type='2' and fld_delstatus='0'");
			
			if($oricount==0)
			{
				
				$ObjDB->NonQuery("INSERT INTO itc_class_dyad_schedulegriddet(fld_class_id,fld_schedule_id,fld_rotation,fld_startdate,fld_enddate,fld_module_id,fld_stageid,fld_createddate,fld_createdby)VALUES('".$classid."','".$scheduleid."',0,'".$fld_startdate."','".$fld_enddate."','".$orimodid."','".$fld_id."','".date("Y-m-d H:i:s")."','".$uid."')");
				
			}
			else
			{
				$ObjDB->NonQuery("UPDATE itc_class_dyad_schedulegriddet SET fld_startdate='".$fld_startdate."',fld_enddate='".$fld_enddate."',fld_stageid='".$fld_id."',fld_flag='1',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_module_id='".$orimodid."'");
			}
		}
		
		for($i=0;$i<sizeof($celldet);$i++)
		{
			$getcelldet=explode("~",$celldet[$i]);
			$getrowid=explode("_",$getcelldet[2]);
			
			if($getcelldet[3]!="undefined")
			{
				$count=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_class_dyad_schedulegriddet WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_module_id='".$getcelldet[0]."' AND fld_row_id='".$getrowid[1]."' AND fld_cell_id='".$getcelldet[2]."'");
			
				if($count==0)
				{
					$ObjDB->NonQuery("INSERT INTO itc_class_dyad_schedulegriddet(fld_class_id,fld_schedule_id,fld_module_id,fld_rotation,fld_cell_id,fld_student_id,fld_row_id,fld_startdate,fld_enddate,fld_createddate,fld_createdby)VALUES('".$classid."','".$scheduleid."','".$getcelldet[0]."','".$getcelldet[1]."','".$getcelldet[2]."','".$getcelldet[3]."','".$getrowid[1]."','".date("Y-m-d",strtotime($getcelldet[5]))."','".date("Y-m-d",strtotime($getcelldet[6]))."','".date("Y-m-d H:i:s")."','".$uid."')");
				}
				else
				{
					$ObjDB->NonQuery("UPDATE itc_class_dyad_schedulegriddet SET fld_flag='1',fld_student_id='".$getcelldet[3]."',fld_startdate='".date("Y-m-d",strtotime($getcelldet[5]))."',fld_enddate='".date("Y-m-d",strtotime($getcelldet[6]))."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_module_id='".$getcelldet[0]."' AND fld_row_id='".$getrowid[1]."' AND fld_cell_id='".$getcelldet[2]."'");
				}
			}
			
		}
		
		for($i=0;$i<sizeof($startdate);$i++)
		{
			$rotationsdate=explode("~",$startdate[$i]);
			
			$ObjDB->NonQuery("UPDATE itc_class_dyad_schedulegriddet SET fld_startdate='".$rotationsdate[1]."',fld_stageid='".$rotationsdate[2]."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_rotation='".$rotationsdate[0]."' AND fld_schedule_id='".$scheduleid."' AND fld_class_id='".$classid."'");
		}
		
		for($i=0;$i<sizeof($enddate);$i++)
		{
			$rotationedate=explode("~",$enddate[$i]);
			
			$ObjDB->NonQuery("UPDATE itc_class_dyad_schedulegriddet SET fld_enddate='".$rotationedate[1]."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_rotation='".$rotationedate[0]."' AND fld_schedule_id='".$scheduleid."' AND fld_class_id='".$classid."'");
		}		
		
		
		$dyadenddate=$ObjDB->SelectSingleValue("SELECT MAX(fld_enddate) FROM itc_class_dyad_schedule_insstagemap WHERE fld_schedule_id='".$scheduleid."' AND fld_flag='1'");
		
		$ObjDB->NonQuery("UPDATE itc_class_dyad_schedulemaster SET fld_dyadtableflg=1,fld_enddate='".$dyadenddate."',fld_gridupdateddate='".date("Y-m-d H:i:s")."' WHERE fld_id='".$scheduleid."'");
		
	}
	
	
	/*--- Check student score  ---*/
	if($oper=="checkstudentscore" and $oper!='')
	{
		$studentid = isset($method['studentid']) ? $method['studentid'] : '0';
		$classid = isset($method['classid']) ? $method['classid'] : '0';
		$scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '0';
		
		$count=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_assignment_sigmath_master WHERE fld_student_id='".$studentid."' AND fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_type<>0");
		
		if($count=='0')
		{
			echo "fail";
		}
		else
		{
			echo "exist";
		}
}

    /*load instructions based on schedule type*/
	if($oper == "dyadinstructions" and $oper != '')
	{		
		$sid = isset($method['sid']) ? $method['sid'] : '0';
		$licenseid = isset($method['licenseid']) ? $method['licenseid'] : '0';
		$flag = isset($method['flag']) ? $method['flag'] : '0';
		
		if($sid!=0)
		{
			$qry=$ObjDB->NonQuery("SELECT fld_id,fld_stagevalue,fld_stagetype,fld_stagename,fld_startdate,fld_enddate,fld_numberofrotation FROM itc_class_dyad_schedule_insstagemap WHERE fld_schedule_id='".$sid."' AND fld_flag='1'");
			
			$dyadtableflag=$ObjDB->SelectSingleValueInt("SELECT fld_dyadtableflg FROM itc_class_dyad_schedulemaster WHERE fld_id='".$sid."' and fld_delstatus='0'");
		}
		
		$qrydyad=$ObjDB->NonQuery("SELECT fld_id,fld_name FROM itc_class_definedyads WHERE fld_schedule_id='".$sid."' AND fld_delstatus='0' ORDER BY fld_id ASC");
		?>
        	<div class="row">
                <div class="span10">
                <p class="darkTitle">Define Dyads</p>
                <p class="darkSubTitle">Below are the Define Dyads of a Traditional Lab schedule. Click "Add a new dyad to this schedule" to add additional dyad. </p>
                </div>
            </div>
            
            <form id="dyadform">
            <div class="row rowspacer <?php if($flag==1){?> dim <?php } ?>">
        	<table class='table table-striped table-bordered' id="mytable">
                <thead class='tableHeadText'>
                    <tr>                        
                        <th class='centerText'>Dyad</th>
                        <th class='centerText'>Module 1</th>
                        <th class='centerText'>Module 2</th>
                        <th class='centerText'>move or delete</th>
                    </tr>
                </thead>
                <tbody> 
                	<input type="hidden" name="dtcount" id="dtcount" value="<?php echo $qrydyad->num_rows;?>" />
                	<?php
					    $rowcount=$qrydyad->num_rows;
						if($qrydyad->num_rows > 0)
						{
							$cnt=1;
							while($row=$qrydyad->fetch_assoc())
							{
								extract($row);
								
						?>                   
                    <tr class="rowd-<?php echo $cnt;?>">
                        <td  style="cursor:default; text-align:center;" class="<?php echo $fld_name;?>" id="definedyad_<?php echo $cnt;?>_1"><?php echo $fld_name;?></td>
                        
                        <?php
							 
							 $qrymodulemap=$ObjDB->QueryObject("SELECT a.fld_id as moduleid, fn_shortname(CONCAT(a.fld_module_name,' ',b.fld_version),1) AS                                           shortname, CONCAT(a.fld_module_name,' ',b.fld_version) as modulename 
							               FROM itc_module_master AS a 
							               LEFT JOIN itc_module_version_track AS b ON b.fld_mod_id=a.fld_id
							               LEFT JOIN itc_class_dyad_schedule_modulemapping AS c ON a.fld_id=c.fld_module_id 
							               WHERE c.fld_schedule_id='".$sid."' and c.fld_dyad_id='".$fld_id."' AND c.fld_flag=1 AND b.fld_delstatus='0' AND                                           a.fld_delstatus='0'");
										   
                                        if($qrymodulemap->num_rows > 0){
											$ddyad=2;
                                            while($rowmodulemap = $qrymodulemap->fetch_assoc()){
                                                extract($rowmodulemap);
                                            ?>
                                            <td  style="cursor:default; text-align:center;" class="<?php echo $moduleid;?>" id="definedyad_<?php echo $cnt.'_'.$ddyad;?>"><?php echo $modulename;?></td>
                                            <?php
											$ddyad++;
											}
										}
										?>
                       
                        <td class='centerText'> 
                       
                       
                        <div class="icon-synergy-trash" style="float:right; font-size:18px;padding-right: 60px;" onclick="fn_deletedefinedyad(<?php echo $fld_id.","."'rowd-".$cnt."',".$flag;?>)"></div>
                         <div class="icon-synergy-edit"  style="float:right; font-size:18px;padding-right: 10px;" onclick="fn_showdefinedyad(<?php echo $fld_id.",".$flag;?>)"></div>
                         
                        </td>                                                               
                    </tr>
                    <?php
							$cnt++;
							}
						}
						else
						{
						?>
                        	<tr>
                            	<td colspan="4" align="center"> No Records </td>
                            </tr> 
                        <?php
						}
						?>
                         <tr id="dyadformdet" style="display:none;">
                        	<td class='centerText' style="cursor:default;">
                                 <dl class='field row' style="width:150px">
                                    <dt class='text'> 
                                    <input placeholder='Dyad Name' required='' type='text' id="dyadname" name="dyadname" <?php if($flag==1){?> readonly <?php } ?>  maxlength="10">
                                    </dt>
                                 </dl>
                            </td>
                            <td class='centerText' style="cursor:default;">
                            	
                                    <dl class='field row'>   
                                        <dt class='dropdown'>   
                                            <div class="selectbox dyadddbox <?php if($flag==1){ ?> dim <?php } ?>" style="width:200px;">
                                                <input type="hidden" name="module1" id="module1"/>
                                                <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                                    <span class="selectbox-option input-medium" data-option="" id="mod1name" style="float:left;">Select Module</span>
                                                    <b class="caret1"></b>
                                                </a>
                                              
                                                    <div class="selectbox-options" style="width:210px;">
                                                        <input type="text" class="selectbox-filter" placeholder="Search Module" style="width:180px;">
                                                        <ul role="options">
                                                      			<?php
																
										$qrymodule= $ObjDB->QueryObject("SELECT a.fld_id as moduleid, fn_shortname(CONCAT(a.fld_module_name,'',b.fld_version),1)                                                     AS shortname, CONCAT(a.fld_module_name,' ',b.fld_version) as modulename
										FROM itc_module_master AS a 
										LEFT JOIN itc_module_version_track AS b ON b.fld_mod_id=a.fld_id
										LEFT JOIN itc_license_mod_mapping AS c ON a.fld_id=c.fld_module_id 
										WHERE a.fld_id NOT IN (SELECT fld_module_id FROM itc_class_dyad_schedule_modulemapping WHERE fld_schedule_id='".$sid."'                                        AND fld_flag='1') AND c.fld_license_id='".$licenseid."' and c.fld_type='1' AND b.fld_delstatus='0' AND a.fld_module_type='1' AND  a.fld_delstatus='0' ORDER BY a.fld_module_name ASC");
										
												if($qrymodule->num_rows > 0){
													while($rowsqry = $qrymodule->fetch_assoc()){
														extract($rowsqry);
																?>
                                                                <li style="float:left;"><a tabindex="-1" href="#" data-option="<?php echo $moduleid;?>"  id="option1<?php echo $moduleid;?>" onclick="fn_loadmodddbox(<?php echo $moduleid;?>,'left')"><?php echo $modulename;?></a></li>
                                                                
                                                             <?php
															}
														}
													?>
                                                           
                                                        </ul>
                                                    </div>
                                              
                                            </div>
                                        </dt>                                         
                                    </dl>
                           
                            </td>
                            <td class='centerText' style="cursor:default;">
                                    <dl class='field row'>   
                                        <dt class='dropdown'>   
                                            <div class="selectbox dyadddbox <?php if($flag==1){ ?> dim <?php } ?>" style="width:200px;">
                                                <input type="hidden" name="module2" id="module2"/>
                                                <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                                    <span class="selectbox-option input-medium" data-option="" id="mod2name" style="float:left;">Select Module</span>
                                                    <b class="caret1"></b>
                                                </a>
                                              
                                                    <div class="selectbox-options" style="width:210px;">
                                                        <input type="text" class="selectbox-filter" placeholder="Search Module" style="width:180px;">
                                                        <ul role="options">
                                                      			<?php
										$qrymodule= $ObjDB->QueryObject("SELECT a.fld_id as moduleid, fn_shortname(CONCAT(a.fld_module_name,'',b.fld_version),1)                                                     AS shortname, CONCAT(a.fld_module_name,' ',b.fld_version) as modulename
										FROM itc_module_master AS a 
										LEFT JOIN itc_module_version_track AS b ON b.fld_mod_id=a.fld_id
										LEFT JOIN itc_license_mod_mapping AS c ON a.fld_id=c.fld_module_id 
										WHERE a.fld_id NOT IN (SELECT fld_module_id FROM itc_class_dyad_schedule_modulemapping WHERE fld_schedule_id='".$sid."'                                        AND fld_flag='1') AND c.fld_license_id='".$licenseid."' and c.fld_type='1' AND b.fld_delstatus='0' AND a.fld_module_type='1' AND  a.fld_delstatus='0' ORDER BY a.fld_module_name ASC");
										
												if($qrymodule->num_rows > 0){
													while($rowsqry = $qrymodule->fetch_assoc()){
														extract($rowsqry);
																?>
                                                                <li style="float:left;"><a tabindex="-1" href="#" data-option="<?php echo $moduleid;?>" id="option2<?php echo $moduleid;?>" onclick="fn_loadmodddbox(<?php echo $moduleid;?>,'right')"><?php echo $modulename;?></a></li>
                                                                
                                                             <?php
															}
														}
													?>
                                                           
                                                        </ul>
                                                    </div>
                                              
                                            </div>
                                        </dt>                                         
                                    </dl>
                          
                            </td>
                            <td class='centerText' style="cursor:default;">
                            <div class="icon-synergy-close" style="float:right; font-size:18px;padding-right: 60px;margin-top:3px; cursor:pointer;" onclick="$('#dyadformdet').hide();"></div>
                         <div class="icon-synergy-create"  style="float:right; font-size:20px;padding-right: 10px;margin-top:3px; cursor:pointer;" onclick="fn_savedyadinsschedule('adddyad',<?php echo $flag;?>);"></div>
                            </td>
                         <tr>
                         <?php
						 	if($rowcount<8)
							{
							?>
                        <tr>
                    	<td colspan="4">
                        	<span class="icon-synergy-add-dark small-icon" style="padding-top:10px;"></span>
   								<span onclick="$('#dyadformdet').show();$('#dyadname').val('');$('#module1').val('');$('#mod1name').html('Select module');$('#module2').val('');$('#mod2name').html('Select module');$('#dyadid').val(0);$('.dyadddbox').removeClass('dim');$('#dyadname').removeAttr('readonly');">Add a dyad to this schedule</span>
                        </td>
                    </tr>
                    <?php
							}
							?>
				</tbody>
            </table>
            </div>
            <input type="hidden" name="dyadid" id="dyadid"/>
            <input type="hidden" name="templateflag" id="templateflag" value="<?php echo $flag;?>" />
            </form>
            
            <script type="text/javascript" language="javascript">
				
				$(function(){
									$("#dyadform").validate({
										ignore: "",
											errorElement: "dd",
											errorPlacement: function(error, element) {
												$(element).parents('dl').addClass('error');
												error.appendTo($(element).parents('dl'));
												error.addClass('msg'); 	
										},
										rules: { 
											dyadname: { required: true },
											module1: { required: true },	
											module2: { required: true }	
										}, 
										messages: { 
											dyadname: {  required: "Fill dyad name" },	
											module1: {  required: "Select anyone module" },
											module2: {  required: "Select anyone module" }								
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
            
		<?php
		if($sid==0)
		{
		?>
        	<div class="row">
                <div class="span10">
                <p class="darkTitle">Instruction Stages/Steps</p>
                <p class="darkSubTitle">Below are the instruction stages/steps of a Traditional Lab schedule. Click the pencil to view or edit instruction and seating<br />assignments.</p>
                </div>
            </div>
            
            <div class="row rowspacer">
        	<table class='table table-striped table-bordered' id="mytable">
                <thead class='tableHeadText'>
                    <tr>                        
                        <th class='centerText' style="cursor:default;">instruction name</th>
                        <th class='centerText' style="cursor:default;">stage/step type</th>
                        <th class='centerText' style="cursor:default;">start date</th>
                        <th class='centerText'>end date</th>
                        <th class='centerText'>move or delete</th>
                    </tr>
                </thead>
                <tbody>                    
                    <tr  class="row-1">
                        
                        <td style="cursor:default; text-align:center;" class="1" id="dyad_1_2">Stage 1 Activity</td> 
                        <td class='centerText' style="cursor:default;" id="dyad_1_3">Teacher Led</td> 
                        <td class='centerText' style="cursor:default;">--/--/--</td> 
                        <td class='centerText' style="cursor:default;">--/--/--</td> 
                        <td class='centerText'> 
 				
                <div class="icon-synergy-trash" style="float:right; font-size:18px;padding-right: 60px;" onclick="fn_deletedyadstage('row-1',0)"></div>
                <div class="icon-synergy-edit mainBtn" id="btnclass-newclass-instructionstage"  name="1,1,0,Stage 1 Activity" style="float:right; font-size:18px;padding-right: 10px;"></div>
                </td>                                                               
                    </tr>
                     <tr class="row-2">
                        
                        <td  style="cursor:default;text-align:center;" class="2" id="dyad_2_2">Orientation</td> 
                        <td class='centerText' style="cursor:default;" id="dyad_2_3">Orientation</td> 
                        <td class='centerText' style="cursor:default;">--/--/--</td> 
                        <td class='centerText' style="cursor:default;">--/--/--</td> 
                        <td class='centerText'> 
                 	<div class="icon-synergy-trash" style="float:right; font-size:18px;padding-right: 60px;" onclick="fn_deletedyadstage('row-2',0)"></div>
                     <div class="icon-synergy-edit mainBtn" id="btnclass-newclass-instructionstage"  name="2,2,0,Orientation" style="float:right; font-size:18px;padding-right: 10px;"></div>
                    </td>                                                               
                    </tr>
                     <tr class="row-3">
                        
                        <td  style="cursor:default;text-align:center;" class="2" id="dyad_3_2">Dyad Rotation 1</td> 
                        <td class='centerText' style="cursor:default;" id="dyad_3_3">Dyad Rotation</td> 
                        <td class='centerText' style="cursor:default;">--/--/--</td> 
                        <td class='centerText' style="cursor:default;">--/--/--</td> 
                        <td class='centerText'> 
					<div class="icon-synergy-trash" style="float:right; font-size:18px;padding-right: 60px;" onclick="fn_deletedyadstage('row-3',0)"></div>
                    <div class="icon-synergy-edit mainBtn" id="btnclass-newclass-instructionstage"  name="2,3,0,Dyad 1" style="float:right; font-size:18px;padding-right: 10px;"></div>
                    </td>                                                               
                    </tr>
                     <tr class="row-4">
                        
                        <td  style="cursor:default;text-align:center;" class="2" id="dyad_4_2">Stage 2 Activity</td> 
                        <td class='centerText' style="cursor:default;" id="dyad_4_3">Teacher Led</td> 
                        <td class='centerText' style="cursor:default;">--/--/--</td> 
                        <td class='centerText' style="cursor:default;">--/--/--</td> 
                        <td class='centerText'> 
					<div class="icon-synergy-trash" style="float:right; font-size:18px;padding-right: 60px;" onclick="fn_deletedyadstage('row-4',0)"></div>
                    <div class="icon-synergy-edit mainBtn" id="btnclass-newclass-instructionstage"  name="2,1,0,Stage 2 Activity" style="float:right; font-size:18px;padding-right: 10px;"></div>
                    </td>                                                               
                    </tr>
                     <tr class="row-5">
                        
                        <td  style="cursor:default;text-align:center;" class="3" id="dyad_5_2">Dyad Rotation 2</td> 
                        <td class='centerText' style="cursor:default;" id="dyad_5_3">Dyad Rotation</td> 
                        <td class='centerText' style="cursor:default;">--/--/--</td> 
                        <td class='centerText' style="cursor:default;">--/--/--</td> 
                        <td class='centerText'>
					<div class="icon-synergy-trash" style="float:right; font-size:18px;padding-right: 60px;" onclick="fn_deletedyadstage('row-5',0)"></div>
                     <div class="icon-synergy-edit mainBtn" id="btnclass-newclass-instructionstage"  name="3,3,0,Dyad 2" style="float:right; font-size:18px;padding-right: 10px;"></div>
                    </td>                                                               
                    </tr>
                    <tr class="row-6">
                        
                        <td  style="cursor:default;text-align:center;" class="3" id="dyad_6_2">Stage 3 Activity</td> 
                        <td class='centerText' style="cursor:default;" id="dyad_6_3">Teacher Led</td> 
                        <td class='centerText' style="cursor:default;">--/--/--</td> 
                        <td class='centerText' style="cursor:default;">--/--/--</td> 
                        <td class='centerText'>
					<div class="icon-synergy-trash" style="float:right; font-size:18px;padding-right: 60px;" onclick="fn_deletedyadstage('row-6',0)"></div>
                     <div class="icon-synergy-edit mainBtn" id="btnclass-newclass-instructionstage"  name="3,1,0,Stage 3 Activity" style="float:right; font-size:18px;padding-right: 10px;"></div>
                    </td>                                                               
                    </tr>
                     <tr class="row-7">
                        
                        <td  style="cursor:default;text-align:center;" class="4" id="dyad_7_2">Dyad Rotation 3</td> 
                        <td class='centerText' style="cursor:default;" id="dyad_7_3">Dyad Rotation</td> 
                        <td class='centerText' style="cursor:default;">--/--/--</td> 
                        <td class='centerText' style="cursor:default;">--/--/--</td> 
                        <td class='centerText'> 
   				<div class="icon-synergy-trash" style="float:right; font-size:18px;padding-right: 60px;" onclick="fn_deletedyadstage('row-7',0)"></div>
                 <div class="icon-synergy-edit mainBtn" id="btnclass-newclass-instructionstage"  name="4,3,0,Dyad 3" style="float:right; font-size:18px;padding-right: 10px;"></div>
                </td>                                                               
                    </tr>
                     <tr class="row-8">
                        
                        <td  style="cursor:default;text-align:center;" class="4" id="dyad_8_2">Stage 4 Activity</td> 
                        <td class='centerText' style="cursor:default;" id="dyad_8_3">Teacher Led</td> 
                        <td class='centerText' style="cursor:default;">--/--/--</td> 
                        <td class='centerText' style="cursor:default;">--/--/--</td> 
                        <td class='centerText'> 
   				<div class="icon-synergy-trash" style="float:right; font-size:18px;padding-right: 60px;" onclick="fn_deletedyadstage('row-8',0)"></div>
                 <div class="icon-synergy-edit mainBtn" id="btnclass-newclass-instructionstage"  name="4,1,0,Stage 4 Activity" style="float:right; font-size:18px;padding-right: 10px;"></div>
                </td>                                                               
                    </tr>
                     <tr class="row-9">
                        
                        <td  style="cursor:default;text-align:center;" class="5" id="dyad_9_2">Dyad Rotation 4</td> 
                        <td class='centerText' style="cursor:default;" id="dyad_9_3">Dyad Rotation</td> 
                        <td class='centerText' style="cursor:default;">--/--/--</td> 
                        <td class='centerText' style="cursor:default;">--/--/--</td> 
                        <td class='centerText'> 
  					<div class="icon-synergy-trash" style="float:right; font-size:18px;padding-right: 60px;" onclick="fn_deletedyadstage('row-9',0)"></div>
                    <div class="icon-synergy-edit mainBtn" id="btnclass-newclass-instructionstage"  name="5,3,0,Dyad 4" style="float:right; font-size:18px;padding-right: 10px;"></div>
                     </td>                                                               
                    </tr>
                     <tr class="row-10">
                        
                        <td  style="cursor:default;text-align:center;" class="5" id="dyad_10_2">Stage 5 Activity</td> 
                        <td class='centerText' style="cursor:default;" id="dyad_10_3">Teacher Led</td> 
                        <td class='centerText' style="cursor:default;">--/--/--</td> 
                        <td class='centerText' style="cursor:default;">--/--/--</td> 
                        <td class='centerText'> 
  					<div class="icon-synergy-trash" style="float:right; font-size:18px;padding-right: 60px;" onclick="fn_deletedyadstage('row-10',0)"></div>
                    <div class="icon-synergy-edit mainBtn" id="btnclass-newclass-instructionstage"  name="5,1,0,Stage 5 Activity" style="float:right; font-size:18px;padding-right: 10px;"></div>
                     </td>                                                               
                    </tr>
                    <tr id="btnclass-newclass-instructionstage" class="mainBtn addstage" style="display:none;">
                    	<td colspan="8">
                        	<span class="icon-synergy-add-dark small-icon" style="padding-top:10px;"></span>
   								Add a new stage to this schedule
                        </td>
                    </tr>
				 </tbody>
            </table>
            </div>
		 <?php
		}
		else
		{
		?>
        	<div class="row">
                <div class="span10">
                <p class="darkTitle">Instruction Stages/Steps</p>
                <p class="darkSubTitle">Below are the instruction stages/steps of a Traditional Lab schedule. Click the pencil to view or edit instruction and seating<br />assignments.</p>
                </div>
            </div>
            
            <div class="row rowspacer <?php if($flag==1){?> dim <?php } ?>">
            <script>
				setTimeout('$("#example-basic").treetable({ expandable: true, clickableNodeNames:true })',3000);
			</script>
        	<table class='table table-striped table-bordered' id="example-basic">
                <thead class='tableHeadText'>
                    <tr>                        
                        
                        <th class='centerText'>instruction name</th>
                        <th class='centerText'>stage/step type</th>
                        <th class='centerText'>start date</th>
                        <th class='centerText'>end date</th>
                        <th class='centerText'>move or delete</th>
                    </tr>
                </thead>
                <tbody> 
                	<?php
					    $stagecount=$qry->num_rows;
						if($qry->num_rows > 0)
						{
							$cnt=1;
							$inc=1;
							$m=0;
							while($row=$qry->fetch_assoc())
							{
								extract($row);
								$m++;
								if($fld_stagetype==1)
								{
									$stagetype="Teacher led";
								}
								else if($fld_stagetype==2)
								{
									$stagetype="Orientation";
								}
								else
								{
									$stagetype="Dyad Rotation";
								}
								
						?>                   
                    <tr class="row-<?php echo $cnt;?>" data-tt-id="<?php echo $m;?>">
                        <td style="cursor:default;" id="stage<?php echo $fld_id;?>-1">
                        				
                                        <?php 
										echo $fld_stagename;
										?>
                                       
                        </td> 
                        <td class='centerText' style="cursor:default;" id="stage<?php echo $fld_id;?>-2"><?php echo $stagetype;?></td> 
                        <td class='centerText' style="cursor:default;" id="stage<?php echo $fld_id;?>-3"><?php if($fld_startdate=='0000-00-00'){ echo "--/--/--"; } else { echo date("m/d/Y",strtotime($fld_startdate));}?></td> 
                        <td class='centerText' style="cursor:default;" id="stage<?php echo $fld_id;?>-4"><?php if($fld_enddate=='0000-00-00'){ echo "--/--/--"; } else { echo date("m/d/Y",strtotime($fld_enddate));}?></td> 
                        <td class='centerText' id="stage<?php echo $fld_id;?>-5"> 
                       
                       
                        <div class="icon-synergy-trash" style="float:right; font-size:18px;padding-right: 60px;" onclick="fn_deletedyadstage('row-<?php echo $cnt;?>',<?php echo $fld_id;?>)"></div>
                          <?php
							if($fld_stagetype==1 and $fld_startdate=='0000-00-00')
							{
						?>
                        			<div class="icon-synergy-edit" onclick="fn_showinstructionstage(<?php echo $fld_stagevalue.",".$fld_stagetype.",".$fld_id;?>);" style="float:right; font-size:18px;padding-right: 10px;"></div>
                        <?php
							}
							else if($fld_stagetype==1 and $fld_startdate!='0000-00-00')
							{
							?>
                            		<div class="icon-synergy-edit" onclick="fn_updatestagedatesdyad(<?php echo $fld_stagevalue.",".$fld_stagetype.",".$fld_id.",".$cnt;?>);" style="float:right; font-size:18px;padding-right: 10px;"></div>
                            <?php
							}
							
							if($fld_stagetype==2 and $fld_startdate=='0000-00-00')
							{
						?>
                        			<div class="icon-synergy-edit" onclick="fn_showinstructionstage(<?php echo $fld_stagevalue.",".$fld_stagetype.",".$fld_id;?>);" style="float:right; font-size:18px;padding-right: 10px;"></div>
                        <?php
							}
							else if($fld_stagetype==2 and $fld_startdate!='0000-00-00')
							{
							?>
                            		<div class="icon-synergy-edit" onclick="fn_updatestagedatesdyad(<?php echo $fld_stagevalue.",".$fld_stagetype.",".$fld_id;?>);" style="float:right; font-size:18px;padding-right: 10px;"></div>
                            <?php
							}
							
							if($fld_stagetype==3 and $fld_startdate=='0000-00-00')
							{
						?>
                        			<div class="icon-synergy-edit" onclick="fn_showinstructionstage(<?php echo $fld_stagevalue.",".$fld_stagetype.",".$fld_id.",".$cnt;?>);" style="float:right; font-size:18px;padding-right: 10px;"></div>
                        <?php
							}
							else if($fld_stagetype==3 and $fld_startdate!='0000-00-00')
							{
							?>
                           			 <div  onclick="fn_updatenumofrotationdyad(<?php echo $fld_id;?>);" style="float:right; font-size:18px;padding-right:3px;margin-top:10px;margin-right:5px;width:30px;height:20px; background-color: #666;-moz-border-radius: 15px;border-radius: 15px;text-align:center; color: #FFF;"><?php echo $fld_numberofrotation;?></div>
                            <?php
							}
							?>
                         
                        </td>                                                               
                    </tr>
                    <?php
							$getrot=$ObjDB->QueryObject("SELECT fld_id,fld_startdate,fld_enddate FROM itc_class_dyad_stagerotmapping WHERE fld_stageid='".$fld_id."' AND fld_active='1' order by fld_id ASC");
							if($getrot->num_rows>0)
							{
								while($rowrot=$getrot->fetch_assoc())
								{
									extract($rowrot);
								?>
                             <tr class="row-<?php $cnt++; echo $cnt;?>"  data-tt-parent-id="<?php echo $m; ?>" data-tt-id="<?php echo $m.".".$n;?>">
                       
                                    <td class='centerText' style="cursor:default;" id="rot<?php echo $fld_id;?>-1">
                                               
                                                    <?php 
                                                    echo "Rotation ".$inc; $inc++;
                                                    ?>
                                                  
                                               
                                    </td> 
                                    <td class='centerText' style="cursor:default;" id="rot<?php echo $fld_id;?>-2"><?php echo "rotation";?></td> 
                                    <td class='centerText' style="cursor:default;" id="rot<?php echo $fld_id;?>-3"><?php if($fld_startdate=='0000-00-00'){ echo "--/--/--"; } else { echo date("m/d/Y",strtotime($fld_startdate));}?></td> 
                                    <td class='centerText' style="cursor:default;" id="rot<?php echo $fld_id;?>-4"><?php if($fld_enddate=='0000-00-00'){ echo "--/--/--"; } else { echo date("m/d/Y",strtotime($fld_enddate));}?></td> 
                                    <td class='centerText' id="rot<?php echo $fld_id;?>-5"> 
                                   
									<div class="icon-synergy-edit"  style="font-size:18px;padding-right: 10px;" id="rot<?php echo $fld_id;?>-5.1" onclick="fn_editstagerotdatedyad(<?php echo $fld_id;?>);"></div>
                                     
                                    </td>                                                               
                                </tr>	
                                <?php
								}
							}
							$cnt++;
							}
						}
						if($stagecount<10)
						{
						?>
                         <tr id="btnclass-newclass-instructionstage" class="mainBtn">
                    	<td colspan="8">
                        	<span class="icon-synergy-add-dark small-icon" style="padding-top:10px;"></span>
   								Add a new stage to this schedule
                        </td>
                    </tr>
                    	<?php
						}
						?>
				</tbody>
            </table>
            </div>
        	
        <?php
		}
		?>
        	  <div class="row rowspacer" style="margin-top:20px;">
                                <div class="tRight" id="modnxtstep">
                                <?php 
									if($sid==0 or $flag==1)
									{
									?>
                                   <input type="button" class="darkButton" id="btnstep" style="width:200px; height:42px;float:right;" value="Save Schedule" onclick="fn_savedyadinsschedule('dyadsch',<?php echo $flag;?>,'save');"/><?php } ?>&nbsp;&nbsp; <input type="button" class="darkButton" id="btnstep" style="width:200px;margin-right:10px; height:42px;float:right;" value="View Schedule" onclick="fn_savedyadinsschedule('dyadsch',<?php echo $flag;?>,'view');"/>
                                </div>
                            </div>
                          
                            
                            <input type="hidden" name="insscheduleid" id="insscheduleid" value="<?php echo $sid;?>" />
                             <input type="hidden" name="dyadtableflag" id="dyadtableflag" value="<?php echo $dyadtableflag;?>" />
	<?php
	}
	
	/* load Dyad stage */
	if($oper == "loadstage" and $oper != '')
	{		
		$stageval= isset($method['stageval']) ? $method['stageval'] : '0';
		$sid= isset($method['sid']) ? $method['sid'] : '0';
		
		$count=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) from itc_class_dyad_schedule_insstagemap where fld_stagevalue='".$stageval."' and fld_stagetype='3' and fld_schedule_id='".$sid."' and fld_startdate<>'0000-00-00' and fld_enddate<>'0000-00-00'  and fld_flag='1'");
		
		$countled=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) from itc_class_dyad_schedule_insstagemap where fld_stagevalue='".$stageval."' and fld_stagetype='1' and fld_schedule_id='".$sid."' and fld_startdate<>'0000-00-00' and fld_enddate<>'0000-00-00'  and fld_flag='1'");
		
		$countorientation=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) from itc_class_dyad_schedule_insstagemap where fld_stagevalue='".$stageval."' and fld_stagetype='2' and fld_schedule_id='".$sid."' and fld_startdate<>'0000-00-00' and fld_enddate<>'0000-00-00'  and fld_flag='1'");
		
		if($stageval==1 && $countled==0)
		{
			$stagetypeid=1;
			$stagetypename="Teacher led";
		}
		
		if($stageval==1 && $countled!=0)
		{
	?>
    	 <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                                    <span class="selectbox-option input-medium" data-option="">Select Stage Type </span>
                                                    <b class="caret1"></b>
                                                </a><?php echo "~fail";?>
    <?php
		}
		else
		{
		?>
    	
        <input type="hidden" name="stagetype" id="stagetype" value="<?php echo $stagetypeid;?>"  onchange="fn_loaddefinedyad()" />
                                                <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                                    <span class="selectbox-option input-medium" data-option=""><?php if($stagetypeid==1){ echo $stagetypename;} else{?>Select Stage Type <?php }?></span>
                                                    <b class="caret1"></b>
                                                </a>
                                                <?php if($stagetypeid==0){?>
                                                    <div class="selectbox-options">
                                                        <input type="text" class="selectbox-filter" placeholder="Search Stage Type">
                                                        <ul role="options">
                                                      			<?php 
																	if($stageval!=1)
																	{
																		if($countled!=1)
																		{
																	?>
                                                                    <li><a tabindex="-1" href="#" data-option="1">Teacher led</a></li>
                                                                    <?php
																		}
																		if($stageval==2 and $countorientation!=1)
																		{
																		?>
                                                                    <li><a tabindex="-1" href="#" data-option="2">Orientation</a></li>
                                                                    	<?php
																		}
																		if($count!=1)
																		{
																		?>
                                                                    <li><a tabindex="-1" href="#" data-option="3">Dyad Rotation</a></li>
                                                                    	<?php
																		}
																		?>
                                                                   <?php
																	}
																	?>
                                                               
                                                           
                                                        </ul>
                                                    </div>
                                               <?php }} echo "~success";?>
                                               
    <?php
	}
	
	/* delete stage instructions */
	if($oper == "deleteinstructions" and $oper != '')
	{		
		$insid = isset($method['insid']) ? $method['insid'] : '0';
		$ObjDB->NonQuery("UPDATE itc_class_dyad_schedule_insstagemap SET fld_flag='0',fld_deleteddate='".date("Y-m-d H:i:s")."',fld_deletedby='".$uid."' WHERE fld_id='".$insid."'");
		
	}
	
	/* delete define dyads */
	if($oper == "deletedefinedyad" and $oper != '')
	{		
		$dyadid = isset($method['dyadid']) ? $method['dyadid'] : '0';
		$ObjDB->NonQuery("UPDATE itc_class_definedyads SET fld_delstatus='1',fld_deleteddate='".date("Y-m-d H:i:s")."',fld_deletedby='".$uid."' WHERE fld_id='".$dyadid."'");
		$ObjDB->NonQuery("UPDATE itc_class_dyad_schedule_modulemapping SET fld_flag='0' WHERE fld_dyad_id='".$dyadid."'");
	}
	
	/* Check stage if stage and dyad greater then zero then show dyad table */
	if($oper == "checkstage" and $oper != '')
	{		
		$sid = isset($method['sid']) ? $method['sid'] : '0';
		$count=0;
		$countdyad=0;
		
		$qrystage=$ObjDB->QueryObject("SELECT count(a.fld_id) as count,count(b.fld_id) as countdyad 
		       FROM itc_class_dyad_schedule_insstagemap AS a 
			   LEFT JOIN itc_class_definedyads AS b ON b.fld_schedule_id='".$sid."'
			   WHERE a.fld_stagetype='3' and a.fld_schedule_id='".$sid."' and a.fld_flag=1 and a.fld_startdate<>'0000-00-00' and a.fld_enddate<>'0000-00-00'               and b.fld_delstatus='0'");
			   
			   if($qrystage->num_rows>0)
			   {
				   $row=$qrystage->fetch_assoc();
				   extract($row);
			   }
			  
		
		
		
		echo $count."~".$countdyad;
	}
	
	if($oper == "checkstageins" and $oper != '')
	{		
		$sid = isset($method['sid']) ? $method['sid'] : '0';
		$stagevalue = isset($method['stagevalue']) ? $method['stagevalue'] : '0';
		$stagetype = isset($method['stagetype']) ? $method['stagetype'] : '0';
		$stageid = isset($method['stageid']) ? $method['stageid'] : '0';
		
		
			$count=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_class_dyad_schedule_insstagemap WHERE fld_stagetype='".$stagetype."' AND fld_schedule_id='".$sid."' AND fld_stagevalue='".$stagevalue."' AND fld_flag=1 and fld_startdate<>'0000-00-00' AND fld_enddate<>'0000-00-00'");
		
		
		echo $count;
	}
	
	if($oper == "showdefinedyad" and $oper != '')
	{		
			$dyadid = isset($method['dyadid']) ? $method['dyadid'] : '0';
			$scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '0';
			$data='';
			$dyadname=$ObjDB->SelectSingleValue("SELECT fld_name from itc_class_definedyads where fld_id='".$dyadid."' and fld_delstatus='0'");
			$data=$dyadname;
			
			$qrymod=$ObjDB->NonQuery("SELECT a.fld_id as moduleid,fn_shortname(CONCAT(a.fld_module_name,' ',b.fld_version),1) AS shortname, CONCAT(a.fld_module_name,' ',b.fld_version) as modulename 
							               FROM itc_module_master AS a
										   LEFT JOIN itc_module_version_track AS b ON b.fld_mod_id=a.fld_id 
										   LEFT JOIN itc_class_dyad_schedule_modulemapping AS c ON a.fld_id=c.fld_module_id 
										   WHERE c.fld_schedule_id='".$scheduleid."' AND  c.fld_dyad_id='".$dyadid."' AND c.fld_flag=1 AND b.fld_delstatus='0' AND                                            a.fld_delstatus='0'");
			
			while($row=$qrymod->fetch_assoc())
			{
				extract($row);
				$data.="~".$moduleid."~".$modulename;
				
			}
			
		echo $data;	
	}
	
	
	if($oper == "saveschedule" and $oper != '')
	{		
		
		$classid = isset($method['classid']) ? $method['classid'] : '0';
		$sid = isset($method['sid']) ? $method['sid'] : '0';
		$sname = isset($method['sname']) ? $method['sname'] : '0';
		$startdate = isset($method['startdate']) ? $method['startdate'] : '0';
		$scheduletype = isset($method['scheduletype']) ? $method['scheduletype'] : '0';
		$students = isset($method['students']) ? $method['students'] : '0';
		$unstudents = isset($method['unstudents']) ? $method['unstudents'] : '0';
		$studenttype = isset($method['studenttype']) ? $method['studenttype'] : '0';
		$licenseid = isset($method['licenseid']) ? $method['licenseid'] : '0';
		$modules = isset($method['modules']) ? $method['modules'] : '0';
		$stagevalue = isset($method['stagevalue']) ? $method['stagevalue'] : '0';
		$stagetype = isset($method['stagetype']) ? $method['stagetype'] : '0';
		$distartdate = isset($method['distartdate']) ? $method['distartdate'] : '0';
		$dienddate = isset($method['dienddate']) ? $method['dienddate'] : '0';
		$stagename = isset($method['stagename']) ? $method['stagename'] : '0';
		$orientationmod = isset($method['orientationmod']) ? $method['orientationmod'] : '0';
		$dyadflag = isset($method['flag']) ? $method['flag'] : '0';
		$stagedet = isset($method['stagedet']) ? $method['stagedet'] : '0';
		$instype = isset($method['instype']) ? $method['instype'] : '0';
		$insstageid = isset($method['insstageid']) ? $method['insstageid'] : '0';
		$dyadid = isset($method['dyadid']) ? $method['dyadid'] : '0';
		$dyadname = isset($method['dyadname']) ? $method['dyadname'] : '0';
		$rotation = isset($method['rotation']) ? $method['rotation'] : '0';
		$adjustflag=isset($method['adflag']) ? $method['adflag'] : '0';
		$ddyad=isset($method['ddyad']) ? $method['ddyad'] : '0';
		$tempflag=isset($method['tempflag']) ? $method['tempflag'] : '0';
		$tempid=isset($method['tempid']) ? $method['tempid'] : '0';
	
		
		$students = explode(',',$students);
		$modules = explode(',',$modules);
		$unstudents = explode(',',$unstudents);
		$stagedettemp = explode(',',$stagedet);
		$ddyad=explode(',',$ddyad);
	
		$remainusers = $ObjDB->SelectSingleValueInt("SELECT fld_remain_users FROM itc_license_track WHERE fld_school_id='".$schoolid."' AND fld_license_id='".$licenseid."' AND fld_delstatus='0' AND fld_user_id='".$indid."' AND '".date("Y-m-d")."' BETWEEN fld_start_date AND fld_end_date");
		
		if($studenttype==1){
			/*---------checing the license for student----------------------*/				
			$count=0;
			$qry = $ObjDB->QueryObject("SELECT fld_student_id FROM itc_class_student_mapping WHERE fld_class_id='".$classid."' AND fld_flag='1'");
			if($qry->num_rows>0){
				$students=array();
				while($res=$qry->fetch_assoc())
				{
					extract($res);
					$students[]=$fld_student_id;
					$check = $ObjDB->SelectSingleValueInt("SELECT COUNT(*) FROM itc_license_assign_student AS a 
					LEFT JOIN itc_user_master AS b ON a.fld_student_id=b.fld_id 
					WHERE a.fld_license_id='".$licenseid."' AND a.fld_student_id='".$fld_student_id."' AND a.fld_flag='1' AND b.fld_delstatus='0'");
					
					if($check==0)
					{
						$count++;
					}
				}
			}
		}
		else{
			$count=0;
			$add=0;			
			for($i=0;$i<sizeof($students);$i++)
			{
				$check = $ObjDB->SelectSingleValueInt("SELECT COUNT(*) FROM itc_license_assign_student AS a LEFT JOIN itc_user_master AS b ON a.fld_student_id=b.fld_id WHERE a.fld_license_id='".$licenseid."' AND a.fld_student_id='".$students[$i]."' AND a.fld_flag='1' AND b.fld_delstatus='0'");
				
				if($check==0)
				{
					$count++;
				}
			}
			
			$remainusers = $ObjDB->SelectSingleValueInt("SELECT fld_remain_users FROM itc_license_track WHERE fld_school_id='".$schoolid."' AND fld_license_id='".$licenseid."' AND fld_delstatus='0' AND fld_user_id='".$indid."' AND '".date("Y-m-d")."' BETWEEN fld_start_date AND fld_end_date");
			
			for($i=0;$i<sizeof($unstudents);$i++)
			{
				$check = $ObjDB->SelectSingleValueInt("SELECT count(*) FROM itc_license_assign_student WHERE fld_license_id='".$licenseid."' AND fld_student_id='".$unstudents[$i]."' AND fld_flag='1'");
				
				if($check>0)
				{
					
				$studentcount = $ObjDB->SelectSingleValueInt("SELECT SUM(o.cnt) FROM (SELECT COUNT(a.fld_id) AS cnt FROM itc_class_sigmath_student_mapping AS a                                 LEFT JOIN itc_class_sigmath_master AS b ON a.fld_sigmath_id=b.fld_id 
				                WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_schedule_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_schedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
								WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_dyad_schedule_studentmapping AS a 
								LEFT JOIN itc_class_dyad_schedulemaster AS b ON a.fld_schedule_id=b.fld_id 
						WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."' AND a.fld_schedule_id<>'".$sid."'
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_triad_schedule_studentmapping AS a 
								LEFT JOIN itc_class_triad_schedulemaster AS b ON a.fld_schedule_id=b.fld_id 
								WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                    UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_indassesment_student_mapping AS a 
								LEFT JOIN itc_class_indassesment_master AS b ON a.fld_schedule_id=b.fld_id 
								WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_exp_student_mapping AS a 
								LEFT JOIN itc_class_indasexpedition_master AS b ON a.fld_schedule_id=b.fld_id 
								WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_pdschedule_student_mapping AS a 
								LEFT JOIN itc_class_pdschedule_master AS b ON a.fld_pdschedule_id=b.fld_id 
								WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_mission_student_mapping AS a 
								LEFT JOIN itc_class_mission_schedule_master AS b ON a.fld_schedule_id=b.fld_id 
								WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."') AS o");
					
					$ObjDB->NonQuery("UPDATE itc_class_dyad_schedule_studentmapping SET fld_flag='0',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$unstudents[$i]."'");
					
					$ObjDB->NonQuery("UPDATE itc_class_dyad_schedulegriddet SET fld_flag='0',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$unstudents[$i]."'");
					
					if($studentcount==0){
						$add++;
						$ObjDB->NonQuery("UPDATE itc_license_assign_student SET fld_flag='0',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' WHERE fld_license_id='".$licenseid."' AND fld_student_id='".$unstudents[$i]."' ");
					}
				}
			}
		}
		
		$assignedstudents = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_license_assign_student WHERE fld_license_id='".$licenseid."' AND fld_flag='1' AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."'");
		
		$totalusers = $ObjDB->SelectSingleValueInt("SELECT fld_no_of_users FROM itc_license_track WHERE fld_school_id='".$schoolid."' AND fld_license_id='".$licenseid."' AND fld_delstatus='0' AND fld_user_id='".$indid."' AND '".date("Y-m-d")."' BETWEEN fld_start_date AND fld_end_date");
		
		$totalremain = $remainusers-$count;
		if($totalusers>=($assignedstudents+$count)){
			$flag=1;
		}		
		else{	
			$flag=0;
		}
		
		if($flag==1) //if student user availale for license
		{ 
			if($sid!=0)
			{
				$ObjDB->NonQuery("UPDATE itc_class_dyad_schedulemaster set fld_schedule_name='".$sname."',fld_student_type='".$studenttype."',fld_startdate='".date("Y-m-d",strtotime($startdate))."' where fld_id='".$sid."'");
				
				$ObjDB->NonQuery("UPDATE itc_class_dyad_schedulemaster SET fld_updated_date='".date("Y-m-d H:i:s")."', fld_license_id='".$licenseid."',fld_updatedby='".$uid."' WHERE fld_id='".$sid."'");
			
			}
			else
			{
				
				$sid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_class_dyad_schedulemaster (fld_class_id,fld_license_id,fld_schedule_name,fld_scheduletype,fld_student_type,fld_startdate,fld_created_date,fld_createdby)VALUES('".$classid."','".$licenseid."','".$sname."','".$scheduletype."','".$studenttype."','".date("Y-m-d",strtotime($startdate))."','".date("Y-m-d H:i:s")."','".$uid."')");
				
			}
			
			$ObjDB->NonQuery("UPDATE itc_class_dyad_schedule_studentmapping SET fld_flag='0',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."'");
			
			for($i=0;$i<sizeof($students);$i++)
			{
				$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_class_dyad_schedule_studentmapping WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$students[$i]."'");
				if($cnt==0)
				{
					$ObjDB->NonQuery("INSERT INTO itc_class_dyad_schedule_studentmapping(fld_schedule_id, fld_student_id,fld_flag,fld_createddate,fld_createdby) VALUES ('".$sid."', '".$students[$i]."','1','".date('Y-m-d H:i:s')."','".$uid."')");
				}
				else
				{
					$ObjDB->NonQuery("UPDATE itc_class_dyad_schedule_studentmapping SET fld_flag='1',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$students[$i]."' AND fld_id='".$cnt."'");
				}
				
				//tracing student
				$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_license_assign_student WHERE fld_license_id='".$licenseid."' AND fld_student_id='".$students[$i]."'");
				if($cnt==0)
				{
					$ObjDB->NonQuery("INSERT INTO itc_license_assign_student(fld_school_id, fld_license_id, fld_student_id, fld_flag,fld_created_date,fld_created_by) VALUES ('".$schoolid."', '".$licenseid."', '".$students[$i]."', '1','".date('Y-m-d H:i:s')."','".$uid."')");
				}
				else
				{
					$ObjDB->NonQuery("UPDATE itc_license_assign_student SET fld_flag='1',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' WHERE fld_license_id='".$licenseid."' AND fld_student_id='".$students[$i]."' AND fld_id='".$cnt."'");
				}
			}
			
			if($dyadflag=="adddyad")
			{
				
						if($dyadid==0)
						{
							$dyadid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_class_definedyads(fld_schedule_id,fld_name,fld_createddate,fld_createdby)values('".$sid."','".$dyadname."','".date("Y-m-d H:i:s")."','".$uid."')");
							
						}
						else
						{
							$ObjDB->NonQuery("UPDATE itc_class_definedyads set fld_name='".$dyadname."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' where fld_id='".$dyadid."'");
						}
						
						$ObjDB->NonQuery("UPDATE itc_class_dyad_schedule_modulemapping set fld_flag='0' where fld_schedule_id='".$sid."' and fld_dyad_id='".$dyadid."'");
					
					for($i=0;$i<sizeof($modules);$i++){
						$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_class_dyad_schedule_modulemapping WHERE fld_schedule_id='".$sid."' AND fld_dyad_id='".$dyadid."' and fld_module_id='".$modules[$i]."'");
						if($cnt==0)
						{
							$ObjDB->NonQuery("INSERT INTO itc_class_dyad_schedule_modulemapping(fld_dyad_id,fld_schedule_id,fld_module_id,fld_flag) VALUES ('".$dyadid."','".$sid."', '".$modules[$i]."','1')");
						}
						else
						{
							$ObjDB->NonQuery("UPDATE itc_class_dyad_schedule_modulemapping SET fld_flag='1' WHERE fld_schedule_id='".$sid."' AND fld_module_id='".$modules[$i]."' AND fld_id='".$cnt."'");
						}
				  }
			 }
		
			
			if(($dyadflag=="ins" or $dyadflag=="dyadsch" or $dyadflag=="adddyad") and $tempflag=='0')
			{
				$countins=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_class_dyad_schedule_insstagemap where fld_schedule_id='".$sid."'");
				
				$val="";
				if($countins==0)
				{
					for($i=0;$i<sizeof($stagedettemp);$i++)
					{
						$edet=explode('~',$stagedettemp[$i]);
						if($edet[2]=="Teacher Led")
						{
							$val=1;
						}
						else if($edet[2]=="Orientation")
						{
							$val=2;
						}
						else if($edet[2]=="Dyad Rotation")
						{
							$val=3;
						}
						
						if($edet[0]!='0' and $val!='')
						{
						$ObjDB->NonQuery("INSERT INTO itc_class_dyad_schedule_insstagemap(fld_schedule_id,fld_stagevalue,fld_stagetype,fld_stagename,fld_createddate,fld_createdby)values('".$sid."','".$edet[0]."','".$val."','".$edet[1]."','".date("Y-m-d H:i:s")."','".$uid."')");
						}
					}
				}
				
				
				if($instype=="create")
				{
					$insstageid=$ObjDB->SelectSingleValueInt("SELECT fld_id from itc_class_dyad_schedule_insstagemap where fld_schedule_id='".$sid."' and fld_stagevalue='".$stagevalue."' and fld_stagetype='".$stagetype."'");
						
						if($insstageid==0)
						{
					
						$insstageid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_class_dyad_schedule_insstagemap(fld_schedule_id,fld_stagevalue,fld_stagetype,fld_stagename,fld_startdate,fld_enddate,fld_orientationmod,fld_numberofrotation,fld_createddate,fld_createdby)values('".$sid."','".$stagevalue."','".$stagetype."','".$stagename."','".date("Y-m-d",strtotime($distartdate))."','".date("Y-m-d",strtotime($dienddate))."','".$orientationmod."','".$rotation."','".date("Y-m-d H:i:s")."','".$uid."')");
						
						if($stagetype==3)
						{ 
						$rotcount=$ObjDB->SelectSingleValueInt("SELECT sum(fld_numberofrotation) FROM itc_class_dyad_schedule_insstagemap WHERE fld_schedule_id='".$sid."' AND fld_id<'".$insstageid."' AND fld_flag='1' AND fld_numberofrotation<>'0'");
						 
						 if($rotcount=='')
						 {
							 $rotcount=0;
						 }
						 
						 $totcount=$rotcount+$rotation;
						 $count=$rotcount+1;
						 
						 for($i=$count;$i<=$totcount;$i++)
						 {
							if($i==$count)
							{
								$startdate=date("Y-m-d",strtotime($distartdate));
							}
							else
							{
								$startdate="";
								$startdate=date("Y-m-d",strtotime($tempenddate. "+1 weekdays"));
								$tempenddate="";
							}
							
							$enddate=date("Y-m-d",strtotime($startdate. "+6 weekdays"));
							$tempenddate=$enddate;
							
							
							 $ObjDB->NonQuery("INSERT INTO itc_class_dyad_stagerotmapping(fld_schedule_id,fld_stageid,fld_rotation,fld_startdate,fld_enddate,fld_createddate,fld_createdby)VALUES('".$sid."','".$insstageid."','".$i."','".$startdate."','".$tempenddate."','".date("Y-m-d H:i:s")."','".$uid."')");
						 }
					  }
					  
						}
						else
						{
							$ObjDB->NonQuery("UPDATE itc_class_dyad_schedule_insstagemap set fld_schedule_id='".$sid."',fld_stagevalue='".$stagevalue."',fld_stagetype='".$stagetype."',fld_stagename='".$stagename."',fld_startdate='".date("Y-m-d",strtotime($distartdate))."',fld_enddate='".date("Y-m-d",strtotime($dienddate))."',fld_orientationmod='".$orientationmod."',fld_numberofrotation='".$rotation."',fld_adjacentflag='".$adjustflag."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."',fld_flag='1' where fld_schedule_id='".$sid."' and fld_stagevalue='".$stagevalue."' and fld_stagetype='".$stagetype."'");
							
							if($stagetype==3)
					{
						 $ObjDB->NonQuery("UPDATE itc_class_dyad_stagerotmapping SET fld_active='0',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."' AND fld_stageid='".$insstageid."'");
						 
						 $rotcount=$ObjDB->SelectSingleValueInt("SELECT sum(fld_numberofrotation) FROM itc_class_dyad_schedule_insstagemap WHERE fld_schedule_id='".$sid."' AND fld_id<'".$insstageid."' AND fld_flag='1' AND fld_numberofrotation<>'0'");
						 
						 if($rotcount=='')
						 {
							 $rotcount=0;
						 }
						 
						 $totcount=$rotcount+$rotation;
						 $count=$rotcount+1;
						 
						 for($i=$count;$i<=$totcount;$i++)
						 {
							if($i==$count)
							{
								$startdate=date("Y-m-d",strtotime($distartdate));
							}
							else
							{
								$startdate="";
								$startdate=date("Y-m-d",strtotime($tempenddate. "+1 weekdays"));
								$tempenddate="";
							}
							
							$enddate=date("Y-m-d",strtotime($startdate. "+6 weekdays"));
							$tempenddate=$enddate;
							 
							 $checkrotcount= $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_class_dyad_stagerotmapping WHERE fld_schedule_id='".$sid."'  AND fld_rotation='".$i."' AND fld_stageid='".$insstageid."'");
							 
							 if($checkrotcount==0)
							 {                                      
								 $ObjDB->NonQuery("INSERT INTO itc_class_dyad_stagerotmapping(fld_schedule_id,fld_stageid,fld_rotation,fld_startdate,fld_enddate,fld_createddate,fld_createdby)VALUES('".$sid."','".$insstageid."','".$i."','".$startdate."','".$tempenddate."','".date("Y-m-d H:i:s")."','".$uid."')");
							 }
							 else
							 {
								$ObjDB->NonQuery("UPDATE itc_class_dyad_stagerotmapping SET fld_stageid='".$insstageid."',fld_startdate='".$startdate."',fld_enddate='".$tempenddate."',fld_active='1',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."'  AND fld_rotation='".$i."' AND fld_stageid='".$insstageid."'"); 
								
							 }
						 }
						 
						 $ObjDB->NonQuery("UPDATE itc_class_dyad_schedule_insstagemap SET fld_enddate='".$tempenddate."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_id='".$insstageid."'");
						 
						  $qrygetrot=$ObjDB->QueryObject("SELECT fld_id FROM itc_class_dyad_stagerotmapping WHERE fld_schedule_id='".$sid."' AND fld_stageid>'".$insstageid."' AND fld_active='1' ORDER BY fld_id ASC");
						 
						 if($qrygetrot->num_rows>0)
						 {
							 while($row=$qrygetrot->fetch_assoc())
							 {
								 $rotcount++;
								 extract($row);
								 
								 $ObjDB->NonQuery("UPDATE itc_class_dyad_stagerotmapping SET fld_rotation='".$rotcount."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_id='".$fld_id."'");
							 }
							 
						 }
					   }
						}
						
					
				}
				else
				{
					
					if($insstageid==0)
					{
						$insstageid=$ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_class_dyad_schedule_insstagemap where fld_schedule_id='".$sid."' AND fld_stagevalue='".$stagevalue."' AND fld_stagetype='".$stagetype."'");
						
					$ObjDB->NonQuery("UPDATE itc_class_dyad_schedule_insstagemap SET fld_schedule_id='".$sid."',fld_stagevalue='".$stagevalue."',fld_stagetype='".$stagetype."',fld_stagename='".$stagename."',fld_startdate='".date("Y-m-d",strtotime($distartdate))."',fld_enddate='".date("Y-m-d",strtotime($dienddate))."',fld_orientationmod='".$orientationmod."',fld_numberofrotation='".$rotation."',fld_adjacentflag='".$adjustflag."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."',fld_flag='1' WHERE fld_schedule_id='".$sid."' AND fld_stagevalue='".$stagevalue."' AND fld_stagetype='".$stagetype."'");
					
						if($stagetype==3)
					{
						 $ObjDB->NonQuery("UPDATE itc_class_dyad_stagerotmapping SET fld_active='0',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."' AND fld_stageid='".$insstageid."'");
						 
						 $rotcount=$ObjDB->SelectSingleValueInt("SELECT sum(fld_numberofrotation) FROM itc_class_dyad_schedule_insstagemap WHERE fld_schedule_id='".$sid."' AND fld_id<'".$insstageid."' AND fld_flag='1' AND fld_numberofrotation<>'0'");
						 
						 if($rotcount=='')
						 {
							 $rotcount=0;
						 }
						 
						 $totcount=$rotcount+$rotation;
						 $count=$rotcount+1;
						 
						 for($i=$count;$i<=$totcount;$i++)
						 {
							if($i==$count)
							{
								$startdate=date("Y-m-d",strtotime($distartdate));
							}
							else
							{
								$startdate="";
								$startdate=date("Y-m-d",strtotime($tempenddate. "+1 weekdays"));
								$tempenddate="";
							}
							
							$enddate=date("Y-m-d",strtotime($startdate. "+6 weekdays"));
							$tempenddate=$enddate;
							 
							 $checkrotcount= $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_class_dyad_stagerotmapping WHERE fld_schedule_id='".$sid."'  AND fld_rotation='".$i."' AND fld_stageid='".$insstageid."'");
							 
							 if($checkrotcount==0)
							 {                                      
								 $ObjDB->NonQuery("INSERT INTO itc_class_dyad_stagerotmapping(fld_schedule_id,fld_stageid,fld_rotation,fld_startdate,fld_enddate,fld_createddate,fld_createdby)VALUES('".$sid."','".$insstageid."','".$i."','".$startdate."','".$tempenddate."','".date("Y-m-d H:i:s")."','".$uid."')");
							 }
							 else
							 {
								$ObjDB->NonQuery("UPDATE itc_class_dyad_stagerotmapping SET fld_stageid='".$insstageid."',fld_startdate='".$startdate."',fld_enddate='".$tempenddate."',fld_active='1',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."'  AND fld_rotation='".$i."' AND fld_stageid='".$insstageid."'"); 
								
							 }
						 }
						 
						 $ObjDB->NonQuery("UPDATE itc_class_dyad_schedule_insstagemap SET fld_enddate='".$tempenddate."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_id='".$insstageid."'");
						 
						  $qrygetrot=$ObjDB->QueryObject("SELECT fld_id FROM itc_class_dyad_stagerotmapping WHERE fld_schedule_id='".$sid."' AND fld_stageid>'".$insstageid."' AND fld_active='1' ORDER BY fld_id ASC");
						 
						 if($qrygetrot->num_rows>0)
						 {
							 while($row=$qrygetrot->fetch_assoc())
							 {
								 $rotcount++;
								 extract($row);
								 
								 $ObjDB->NonQuery("UPDATE itc_class_dyad_stagerotmapping SET fld_rotation='".$rotcount."' WHERE fld_id='".$fld_id."'");
							 }
							 
						 }
					   }
					   
					}
					else
					{
						$ObjDB->NonQuery("UPDATE itc_class_dyad_schedule_insstagemap SET fld_schedule_id='".$sid."',fld_stagevalue='".$stagevalue."',fld_stagetype='".$stagetype."',fld_stagename='".$stagename."',fld_startdate='".date("Y-m-d",strtotime($distartdate))."',fld_enddate='".date("Y-m-d",strtotime($dienddate))."',fld_orientationmod='".$orientationmod."',fld_numberofrotation='".$rotation."',fld_adjacentflag='".$adjustflag."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."',fld_flag='1' WHERE fld_id='".$insstageid."'");
						
					if($stagetype==3)
					{
						 $ObjDB->NonQuery("UPDATE itc_class_dyad_stagerotmapping SET fld_active='0' WHERE fld_schedule_id='".$sid."' AND fld_stageid='".$insstageid."'");
						 
						 $rotcount=$ObjDB->SelectSingleValueInt("SELECT sum(fld_numberofrotation) FROM itc_class_dyad_schedule_insstagemap WHERE fld_schedule_id='".$sid."' AND fld_id<'".$insstageid."' AND fld_flag='1' AND fld_numberofrotation<>'0'");
						 
						 if($rotcount=='')
						 {
							 $rotcount=0;
						 }
						 
						 $totcount=$rotcount+$rotation;
						 $count=$rotcount+1;
						 
						 for($i=$count;$i<=$totcount;$i++)
						 {
							if($i==$count)
							{
								$startdate=date("Y-m-d",strtotime($distartdate));
							}
							else
							{
								$startdate="";
								$startdate=date("Y-m-d",strtotime($tempenddate. "+1 weekdays"));
								$tempenddate="";
							}
							
							$enddate=date("Y-m-d",strtotime($startdate. "+6 weekdays"));
							$tempenddate=$enddate;
							 
							 $checkrotcount= $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_class_dyad_stagerotmapping WHERE fld_schedule_id='".$sid."'  AND fld_rotation='".$i."' AND fld_stageid='".$insstageid."'");
							 
							 if($checkrotcount==0)
							 {                                      
								 $ObjDB->NonQuery("INSERT INTO itc_class_dyad_stagerotmapping(fld_schedule_id,fld_stageid,fld_rotation,fld_startdate,fld_enddate,fld_createddate,fld_createdby)VALUES('".$sid."','".$insstageid."','".$i."','".$startdate."','".$tempenddate."','".date("Y-m-d H:i:s")."','".$uid."')");
							 }
							 else
							 {
								$ObjDB->NonQuery("UPDATE itc_class_dyad_stagerotmapping SET fld_stageid='".$insstageid."',fld_startdate='".$startdate."',fld_enddate='".$tempenddate."',fld_active='1',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."'  AND fld_rotation='".$i."' AND fld_stageid='".$insstageid."'"); 
								
							 }
						 }
						 
						 $ObjDB->NonQuery("UPDATE itc_class_dyad_schedule_insstagemap SET fld_enddate='".$tempenddate."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_id='".$insstageid."'");
						 
						  $qrygetrot=$ObjDB->QueryObject("SELECT fld_id FROM itc_class_dyad_stagerotmapping WHERE fld_schedule_id='".$sid."' AND fld_stageid>'".$insstageid."' AND fld_active='1' ORDER BY fld_id ASC");
						 
						 if($qrygetrot->num_rows>0)
						 {
							 while($row=$qrygetrot->fetch_assoc())
							 {
								 $rotcount++;
								 extract($row);
								 
								 $ObjDB->NonQuery("UPDATE itc_class_dyad_stagerotmapping SET fld_rotation='".$rotcount."' WHERE fld_id='".$fld_id."'");
							 }
							 
						 }
					   }
					
					}
				}
				
				if($adjustflag==1)
						{
							$qrystage=$ObjDB->NonQuery("SELECT fld_id,fld_stagevalue,fld_stagetype,fld_numberofrotation FROM itc_class_dyad_schedule_insstagemap WHERE fld_id>'".$insstageid."' AND fld_schedule_id='".$sid."' AND fld_startdate<>'0000-00-00' AND fld_enddate<>'0000-00-00' AND fld_flag='1' order by fld_id ASC");
							
							if($qrystage->num_rows>0)
							{
							$z=0;
							while($rowstage=$qrystage->fetch_assoc())
							{
								extract($rowstage);
								
								if($z==0)
								{
									$startdate=date("Y-m-d",strtotime($dienddate. "+1 weekdays"));
								}
								else
								{
									$startdate="";
									$startdate=date("Y-m-d",strtotime($tempenddate. "+1 weekdays"));
									$tempenddate="";
								}
								
								if($fld_stagetype==1)
								{
									if($fld_stagevalue==1)
									{
										$enddate=date("Y-m-d",strtotime($startdate. "+4 weekdays"));
										$tempenddate=$enddate;
									}
									else if($fld_stagevalue==2)
									{
										$enddate=date("Y-m-d",strtotime($startdate. "+4 weekdays"));
										$tempenddate=$enddate;
									}
									else if($fld_stagevalue==3)
									{
										$enddate=date("Y-m-d",strtotime($startdate. "+6 weekdays"));
										$tempenddate=$enddate;
									}
									else if($fld_stagevalue==4)
									{
										$enddate=date("Y-m-d",strtotime($startdate. "+6 weekdays"));
										$tempenddate=$enddate;
									}
									else if($fld_stagevalue==5)
									{
										$enddate=date("Y-m-d",strtotime($startdate. "+9 weekdays"));
										$tempenddate=$enddate;
									}
								}
								else if($fld_stagetype==2)
								{
									$enddate=date("Y-m-d",strtotime($startdate. "+6 weekdays"));
									$tempenddate=$enddate;
								}
								else if($fld_stagetype==3)
								{
									$days=($fld_numberofrotation*7)-1;
									$enddate=date("Y-m-d",strtotime($startdate. "+".$days." weekdays"));
									$tempenddate=$enddate;
									
									$ObjDB->NonQuery("UPDATE itc_class_dyad_stagerotmapping SET fld_active='0' WHERE fld_schedule_id='".$sid."' AND fld_stageid='".$fld_id."'");
						 
									 $rotcount=$ObjDB->SelectSingleValueInt("SELECT sum(fld_numberofrotation) FROM itc_class_dyad_schedule_insstagemap WHERE fld_schedule_id='".$sid."' AND fld_id<'".$fld_id."' AND fld_flag='1' AND fld_numberofrotation<>'0'");
									 
									 if($rotcount=='')
									 {
										 $rotcount=0;
									 }
									 
									 $totcount=$rotcount+$fld_numberofrotation;
									 $count=$rotcount+1;
									 
									 for($i=$count;$i<=$totcount;$i++)
									 {
										if($i==$count)
										{
											$startdaterot=date("Y-m-d",strtotime($startdate));
										}
										else
										{
											$startdaterot="";
											$startdaterot=date("Y-m-d",strtotime($tempenddaterot. "+1 weekdays"));
											$tempenddaterot="";
										}
										
										$enddaterot=date("Y-m-d",strtotime($startdaterot. "+6 weekdays"));
										$tempenddaterot=$enddaterot;
										 
										 $checkrotcount= $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_class_dyad_stagerotmapping WHERE fld_schedule_id='".$sid."'  AND fld_rotation='".$i."' AND fld_stageid='".$fld_id."'");
										 
										 if($checkrotcount==0)
										 {                                      
											 $ObjDB->NonQuery("INSERT INTO itc_class_dyad_stagerotmapping(fld_schedule_id,fld_stageid,fld_rotation,fld_startdate,fld_enddate,fld_createddate,fld_createdby)VALUES('".$sid."','".$fld_id."','".$i."','".$startdaterot."','".$tempenddaterot."','".date("Y-m-d H:i:s")."','".$uid."')");
										 }
										 else
										 {
											$ObjDB->NonQuery("UPDATE itc_class_dyad_stagerotmapping SET fld_stageid='".$fld_id."',fld_startdate='".$startdaterot."',fld_enddate='".$tempenddaterot."',fld_active='1',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."'  AND fld_rotation='".$i."' AND fld_stageid='".$fld_id."'"); 
										 }
									 }
								
									$tempenddate=$tempenddaterot;
								}
								
								
								$ObjDB->NonQuery("UPDATE itc_class_dyad_schedule_insstagemap SET fld_startdate='".$startdate."',fld_enddate='".$tempenddate."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_id='".$fld_id."'");
							
							$z++;	
								
							}
						  }
						  
						  
						}
			}
			else if($tempflag=='1')
			{
					$exdyaddet=$ObjDB->QueryObject("SELECT a.fld_module_id as modid,b.fld_name as dyadname 
					          FROM itc_class_dyad_schedule_modulemapping AS a 
                              LEFT JOIN itc_class_definedyads AS b ON a.fld_dyad_id=b.fld_id 
							  WHERE b.fld_schedule_id='".$tempid."' AND b.fld_delstatus=0 AND a.fld_flag='1'");
							 
							 if($exdyaddet->num_rows>0)
							 {
								$inc=1;
								while($rowesdyad=$exdyaddet->fetch_assoc())
								{
									extract($rowesdyad);
									
									if($inc%2!=0)
									{
										$dyadid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_class_definedyads(fld_schedule_id,fld_name,fld_createddate,fld_createdby)values('".$sid."','".$dyadname."','".date("Y-m-d H:i:s")."','".$uid."')");
									}
									
									$ObjDB->NonQuery("INSERT INTO itc_class_dyad_schedule_modulemapping(fld_dyad_id,fld_schedule_id,fld_module_id,fld_flag) VALUES ('".$dyadid."','".$sid."', '".$modid."','1')");
									
									$inc++;
								}
							 }
							 
					 $exinsstage=$ObjDB->QueryObject("SELECT fld_id as stageid,fld_stagevalue as stagevalue,fld_stagetype as stagetype,fld_startdate as startdate,fld_enddate as enddate,fld_orientationmod as orientationmod,fld_stagename as stagename,fld_numberofrotation as rotation,fld_adjacentflag as adjflag 
					             FROM itc_class_dyad_schedule_insstagemap 
								 WHERE fld_schedule_id='".$tempid."' AND fld_flag='1'"); 
								 
							if($exinsstage->num_rows>0)
							 {
								while($rowexinsstage=$exinsstage->fetch_assoc())
								{
									extract($rowexinsstage);
									$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_class_dyad_schedule_insstagemap(fld_schedule_id,fld_stagevalue,fld_stagetype,fld_stagename,fld_startdate,fld_enddate,fld_orientationmod,fld_numberofrotation,fld_adjacentflag,fld_createddate,fld_createdby)values('".$sid."','".$stagevalue."','".$stagetype."','".$stagename."','".$startdate."','".$enddate."','".$orientationmod."','".$rotation."','".$adjflag."','".date("Y-m-d H:i:s")."','".$uid."')");
									
									$rot=$ObjDB->QueryObject("SELECT fld_rotation,fld_startdate,fld_enddate FROM itc_class_dyad_stagerotmapping WHERE fld_stageid='".$stageid."' AND fld_active=1 order by fld_id ASC");
									
									if($rot->num_rows>0)
									{
										while($row=$rot->fetch_assoc())
										{
											extract($row);
											
												$ObjDB->NonQuery("INSERT INTO itc_class_dyad_stagerotmapping(fld_schedule_id,fld_stageid,fld_startdate,fld_enddate,fld_rotation,fld_createddate,fld_createdby)VALUES('".$sid."','".$id."','".$fld_startdate."','".$fld_enddate."','".$fld_rotation."','".date("Y-m-d H:i:s")."','".$uid."')");
											
										}
									}
									
								}
						}
				}
			
			send_notification($licenseid,$schoolid,$indid);
			
			echo "success~".$sid;
			
			
			
		}
		else{
			echo "fail";
		}
	}
	
	
	if($oper == "setenddate" and $oper != '')
	{		
		
		$startdate = isset($method['startdate']) ? $method['startdate'] : '0';
		$stagevalue = isset($method['stageval']) ? $method['stageval'] : '0';
		$stagetype = isset($method['stagetype']) ? $method['stagetype'] : '0';
		$rotation = isset($method['rotation']) ? $method['rotation'] : '0';
		$enddate="";
		
		
		if($stagetype==1)
		{
			if($stagevalue==1)
			{
				$enddate=date("Y-m-d",strtotime($startdate. "+4 weekdays"));
			}
			else if($stagevalue==2)
			{
				$enddate=date("Y-m-d",strtotime($startdate. "+4 weekdays"));
			}
			else if($stagevalue==3)
			{
				$enddate=date("Y-m-d",strtotime($startdate. "+6 weekdays"));
			}
			else if($stagevalue==4)
			{
				$enddate=date("Y-m-d",strtotime($startdate. "+6 weekdays"));
			}
			else if($stagevalue==5)
			{
				$enddate=date("Y-m-d",strtotime($startdate. "+9 weekdays"));
			}
		}
		else if($stagetype==2)
		{
			$enddate=date("Y-m-d",strtotime($startdate. "+6 weekdays"));
		}
		else if($stagetype==3)
		{
			if($rotation!=0)
			{
				$days=($rotation*7)-1;
			}
			else
			{
				$days=6;
			}
			$enddate=date("Y-m-d",strtotime($startdate. "+".$days." weekdays"));
		}
		
		
		echo date("m/d/Y",strtotime($enddate));
	}
	
	if($oper == "updatestagerotdate" and $oper != '')
	{	
		try
		{
			$startdate = isset($method['startdate']) ? $method['startdate'] : '0';
			$enddate = isset($method['enddate']) ? $method['enddate'] : '0';
			$id = isset($method['id']) ? $method['id'] : '0';
			$adjustflag = isset($method['adjustflag']) ? $method['adjustflag'] : '0';
			
			$validate_id=true;
			if($id!=0)  $validate_id=validate_datatype($id,'int');
			
			if($validate_id)
			{
				$ObjDB->NonQuery("UPDATE itc_class_dyad_stagerotmapping SET fld_startdate='".date("Y-m-d",strtotime($startdate))."',fld_enddate='".date("Y-m-d",strtotime($enddate))."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_id='".$id."'");
				
				$qrygetstageid=$ObjDB->QueryObject("SELECT fld_schedule_id,fld_stageid,fld_rotation FROM itc_class_dyad_stagerotmapping WHERE fld_id='".$id."'");
				
				if($qrygetstageid->num_rows>0)
				{
					$row=$qrygetstageid->fetch_assoc();
					extract($row);
					$sid=$fld_schedule_id;
					$stageid=$fld_stageid;
				}
				
				$maxrotation=$ObjDB->SelectSingleValueInt("SELECT max(fld_rotation) FROM itc_class_dyad_stagerotmapping WHERE fld_stageid='".$stageid."' AND fld_active='1'");
				
				$minrotation=$ObjDB->SelectSingleValueInt("SELECT min(fld_rotation) FROM itc_class_dyad_stagerotmapping WHERE fld_stageid='".$stageid."' AND fld_active='1'");
				
				if($fld_rotation==$minrotation)
				{
					$ObjDB->NonQuery("UPDATE itc_class_dyad_schedule_insstagemap SET fld_startdate='".date("Y-m-d",strtotime($startdate))."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_id='".$fld_stageid."'");
				}
				
				if($fld_rotation==$maxrotation)
				{
					$ObjDB->NonQuery("UPDATE itc_class_dyad_schedule_insstagemap SET fld_enddate='".date("Y-m-d",strtotime($enddate))."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_id='".$fld_stageid."'");
				}
				
				if($adjustflag==1)
				{
					$count=$fld_rotation+1;
					
					for($i=$count;$i<=$maxrotation;$i++)
					{
							$startdate="";
							$startdate=date("Y-m-d",strtotime($enddate. "+1 weekdays"));
							$tempenddate="";
							
							
							$enddate=date("Y-m-d",strtotime($startdate. "+6 weekdays"));
							$tempenddate=$enddate;
							 
						
							$ObjDB->NonQuery("UPDATE itc_class_dyad_stagerotmapping SET fld_stageid='".$stageid."',fld_startdate='".$startdate."',fld_enddate='".$tempenddate."',fld_active='1',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."'  AND fld_rotation='".$i."' AND fld_stageid='".$stageid."'"); 
							if($i==$maxrotation)
							{
								$ObjDB->NonQuery("UPDATE itc_class_dyad_schedule_insstagemap SET fld_enddate='".date("Y-m-d",strtotime($tempenddate))."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_id='".$stageid."'");
							}	
							
					}
					
					$qrystage=$ObjDB->NonQuery("SELECT fld_id,fld_stagevalue,fld_stagetype,fld_numberofrotation FROM itc_class_dyad_schedule_insstagemap WHERE fld_id>'".$stageid."' AND fld_schedule_id='".$sid."' AND fld_startdate<>'0000-00-00' AND fld_enddate<>'0000-00-00' AND fld_flag='1' order by fld_id ASC");
							
							if($qrystage->num_rows>0)
							{
							$z=0;
							while($rowstage=$qrystage->fetch_assoc())
							{
								extract($rowstage);
								
								if($z==0)
								{
									$startdate=date("Y-m-d",strtotime($tempenddate. "+1 weekdays"));
								}
								else
								{
									$startdate="";
									$startdate=date("Y-m-d",strtotime($tempenddate. "+1 weekdays"));
									$tempenddate="";
								}
								
								if($fld_stagetype==1)
								{
									if($fld_stagevalue==1)
									{
										$enddate=date("Y-m-d",strtotime($startdate. "+4 weekdays"));
										$tempenddate=$enddate;
									}
									else if($fld_stagevalue==2)
									{
										$enddate=date("Y-m-d",strtotime($startdate. "+4 weekdays"));
										$tempenddate=$enddate;
									}
									else if($fld_stagevalue==3)
									{
										$enddate=date("Y-m-d",strtotime($startdate. "+6 weekdays"));
										$tempenddate=$enddate;
									}
									else if($fld_stagevalue==4)
									{
										$enddate=date("Y-m-d",strtotime($startdate. "+6 weekdays"));
										$tempenddate=$enddate;
									}
									else if($fld_stagevalue==5)
									{
										$enddate=date("Y-m-d",strtotime($startdate. "+9 weekdays"));
										$tempenddate=$enddate;
									}
								}
								else if($fld_stagetype==2)
								{
									$enddate=date("Y-m-d",strtotime($startdate. "+6 weekdays"));
									$tempenddate=$enddate;
								}
								else if($fld_stagetype==3)
								{
									$days=($fld_numberofrotation*7)-1;
									$enddate=date("Y-m-d",strtotime($startdate. "+".$days." weekdays"));
									$tempenddate=$enddate;
									
									$ObjDB->NonQuery("UPDATE itc_class_dyad_stagerotmapping SET fld_active='0' WHERE fld_schedule_id='".$sid."' AND fld_stageid='".$fld_id."'");
						 
									 $rotcount=$ObjDB->SelectSingleValueInt("SELECT sum(fld_numberofrotation) FROM itc_class_dyad_schedule_insstagemap WHERE fld_schedule_id='".$sid."' AND fld_id<'".$fld_id."' AND fld_flag='1' AND fld_numberofrotation<>'0'");
									 
									 if($rotcount=='')
									 {
										 $rotcount=0;
									 }
									 
									 $totcount=$rotcount+$fld_numberofrotation;
									 $count=$rotcount+1;
									 
									 for($i=$count;$i<=$totcount;$i++)
									 {
										if($i==$count)
										{
											$startdaterot=date("Y-m-d",strtotime($startdate));
										}
										else
										{
											$startdaterot="";
											$startdaterot=date("Y-m-d",strtotime($tempenddaterot. "+1 weekdays"));
											$tempenddaterot="";
										}
										
										$enddaterot=date("Y-m-d",strtotime($startdaterot. "+6 weekdays"));
										$tempenddaterot=$enddaterot;
										 
										 $checkrotcount= $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_class_dyad_stagerotmapping WHERE fld_schedule_id='".$sid."'  AND fld_rotation='".$i."' AND fld_stageid='".$fld_id."'");
										 
										 if($checkrotcount==0)
										 {                                      
											 $ObjDB->NonQuery("INSERT INTO itc_class_dyad_stagerotmapping(fld_schedule_id,fld_stageid,fld_rotation,fld_startdate,fld_enddate,fld_createddate,fld_createdby)VALUES('".$sid."','".$fld_id."','".$i."','".$startdaterot."','".$tempenddaterot."','".date("Y-m-d H:i:s")."','".$uid."')");
										 }
										 else
										 {
											$ObjDB->NonQuery("UPDATE itc_class_dyad_stagerotmapping SET fld_stageid='".$fld_id."',fld_startdate='".$startdaterot."',fld_enddate='".$tempenddaterot."',fld_active='1',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."'  AND fld_rotation='".$i."' AND fld_stageid='".$fld_id."'"); 
										 }
									 }
								
								$tempenddate=$tempenddaterot;
								
								}
								
								
								$ObjDB->NonQuery("UPDATE itc_class_dyad_schedule_insstagemap set fld_startdate='".$startdate."',fld_enddate='".$tempenddate."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_id='".$fld_id."'");
							
							$z++;	
								
							}
						  }
					
				}
				
				
			}
			
			
	    }
	    catch(Exception $e)
	    {
		  echo "false";
	    }
	}
	
	
	if($oper == "updatenumofrotation" and $oper != '')
	{	
		try
		{
			$stageid = isset($method['stageid']) ? $method['stageid'] : '0';
			$sid = isset($method['sid']) ? $method['sid'] : '0';
			$adjustflag = isset($method['adjustflag']) ? $method['adjustflag'] : '0';
			
			
			$validate_id=true;
			if($stageid!=0)  $validate_id=validate_datatype($stageid,'int');
			
			if($validate_id)
			{
				
					$numofrotation=$ObjDB->SelectSingleValueInt("SELECT fld_numberofrotation FROM itc_class_dyad_schedule_insstagemap WHERE fld_id='".$stageid."'");
					
					if($numofrotation==4)
					{
						$ObjDB->NonQuery("UPDATE itc_class_dyad_schedule_insstagemap SET fld_numberofrotation='2',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_id='".$stageid."'");
						
						$qrygetrot=$ObjDB->QueryObject("SELECT fld_id FROM itc_class_dyad_stagerotmapping WHERE fld_schedule_id='".$sid."' AND fld_stageid='".$stageid."' AND fld_active='1' ORDER BY fld_id ASC LIMIT 2,4");
						
						if($qrygetrot->num_rows>0)
						{
							while($row=$qrygetrot->fetch_assoc())
							{
								extract($row);
								$ObjDB->NonQuery("UPDATE itc_class_dyad_stagerotmapping SET fld_active='0' WHERE fld_id='".$fld_id."'");
							}
						}
						
						 $maxenddate= $ObjDB->NonQuery("SELECT MAX(fld_enddate) AS tempenddate,MAX(fld_rotation) AS rotcount FROM itc_class_dyad_stagerotmapping WHERE fld_schedule_id='".$sid."' AND fld_stageid='".$stageid."' AND fld_active='1'");
						 
						 if($maxenddate->num_rows>0)
						 {
							 $row=$maxenddate->fetch_assoc();
							 extract($row);
						 }
						 
						 $ObjDB->NonQuery("UPDATE itc_class_dyad_schedule_insstagemap SET fld_enddate='".$tempenddate."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_id='".$stageid."'");
						 
						 $qrygetrot=$ObjDB->QueryObject("SELECT fld_id FROM itc_class_dyad_stagerotmapping WHERE fld_schedule_id='".$sid."' AND fld_stageid>'".$stageid."' AND fld_active='1' ORDER BY fld_id ASC");
						 
						 if($qrygetrot->num_rows>0)
						 {
							 while($row=$qrygetrot->fetch_assoc())
							 {
								 $rotcount++;
								 extract($row);
								 
								 $ObjDB->NonQuery("UPDATE itc_class_dyad_stagerotmapping SET fld_rotation='".$rotcount."' WHERE fld_id='".$fld_id."'");
							 }
							 
						 }
					}
					else if($numofrotation==2)
					{
						$ObjDB->NonQuery("UPDATE itc_class_dyad_schedule_insstagemap SET fld_numberofrotation='4',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_id='".$stageid."'");
						
						$rotcount=$ObjDB->SelectSingleValueInt("SELECT sum(fld_numberofrotation) FROM itc_class_dyad_schedule_insstagemap WHERE fld_schedule_id='".$sid."' AND fld_id<='".$stageid."' AND fld_flag='1' AND fld_numberofrotation<>'0'");
						
						$tempenddate=$ObjDB->SelectSingleValue("SELECT max(fld_enddate) FROM itc_class_dyad_stagerotmapping WHERE fld_schedule_id='".$sid."' AND fld_stageid='".$stageid."' AND fld_active='1'");
						
						
						
						 if($rotcount=='')
						 {
							 $rotcount=0;
						 }
						 
						 $count=$rotcount-1;
						 
						 for($i=$count;$i<=$rotcount;$i++)
						 {
							
							$startdate="";
							$startdate=date("Y-m-d",strtotime($tempenddate. "+1 weekdays"));
							$tempenddate="";
							
							
							$enddate=date("Y-m-d",strtotime($startdate. "+6 weekdays"));
							$tempenddate=$enddate;
							 
							 $checkrotcount= $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_class_dyad_stagerotmapping WHERE fld_schedule_id='".$sid."'  AND fld_rotation='".$i."' AND fld_stageid='".$stageid."'");
							 
							 if($checkrotcount==0)
							 {                                      
								 $ObjDB->NonQuery("INSERT INTO itc_class_dyad_stagerotmapping(fld_schedule_id,fld_stageid,fld_rotation,fld_startdate,fld_enddate,fld_createddate,fld_createdby)VALUES('".$sid."','".$stageid."','".$i."','".$startdate."','".$tempenddate."','".date("Y-m-d H:i:s")."','".$uid."')");
							 }
							 else
							 {
								$ObjDB->NonQuery("UPDATE itc_class_dyad_stagerotmapping SET fld_stageid='".$stageid."',fld_startdate='".$startdate."',fld_enddate='".$tempenddate."',fld_active='1',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."'  AND fld_rotation='".$i."' AND fld_stageid='".$stageid."'"); 
								
							 }
						 }
						 
						 $ObjDB->NonQuery("UPDATE itc_class_dyad_schedule_insstagemap SET fld_enddate='".$tempenddate."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_id='".$stageid."'");
						 
						 $qrygetrot=$ObjDB->QueryObject("SELECT fld_id FROM itc_class_dyad_stagerotmapping WHERE fld_schedule_id='".$sid."' AND fld_stageid>'".$stageid."' AND fld_active='1' ORDER BY fld_id ASC");
						 
						 if($qrygetrot->num_rows>0)
						 {
							 while($row=$qrygetrot->fetch_assoc())
							 {
								 $rotcount++;
								 extract($row);
								 
								 $ObjDB->NonQuery("UPDATE itc_class_dyad_stagerotmapping SET fld_rotation='".$rotcount."' WHERE fld_id='".$fld_id."'");
							 }
							 
						 }
						
					}
				
					
					if($adjustflag==1) 
						{
							$qrystage=$ObjDB->NonQuery("SELECT fld_id,fld_stagevalue,fld_stagetype,fld_numberofrotation FROM itc_class_dyad_schedule_insstagemap WHERE fld_id>'".$stageid."' AND fld_schedule_id='".$sid."' AND fld_startdate<>'0000-00-00' AND fld_enddate<>'0000-00-00' AND fld_flag='1' order by fld_id ASC");
							
							if($qrystage->num_rows>0)
							{
							$z=0;
							while($rowstage=$qrystage->fetch_assoc())
							{
								extract($rowstage);
								
								if($z==0)
								{
									$startdate=date("Y-m-d",strtotime($tempenddate. "+1 weekdays"));
								}
								else
								{
									$startdate="";
									$startdate=date("Y-m-d",strtotime($tempenddate. "+1 weekdays"));
									$tempenddate="";
								}
								
								if($fld_stagetype==1)
								{
									if($fld_stagevalue==1)
									{
										$enddate=date("Y-m-d",strtotime($startdate. "+4 weekdays"));
										$tempenddate=$enddate;
									}
									else if($fld_stagevalue==2)
									{
										$enddate=date("Y-m-d",strtotime($startdate. "+4 weekdays"));
										$tempenddate=$enddate;
									}
									else if($fld_stagevalue==3)
									{
										$enddate=date("Y-m-d",strtotime($startdate. "+6 weekdays"));
										$tempenddate=$enddate;
									}
									else if($fld_stagevalue==4)
									{
										$enddate=date("Y-m-d",strtotime($startdate. "+6 weekdays"));
										$tempenddate=$enddate;
									}
									else if($fld_stagevalue==5)
									{
										$enddate=date("Y-m-d",strtotime($startdate. "+9 weekdays"));
										$tempenddate=$enddate;
									}
								}
								else if($fld_stagetype==2)
								{
									$enddate=date("Y-m-d",strtotime($startdate. "+6 weekdays"));
									$tempenddate=$enddate;
								}
								else if($fld_stagetype==3)
								{
									$days=($fld_numberofrotation*7)-1;
									$enddate=date("Y-m-d",strtotime($startdate. "+".$days." weekdays"));
									$tempenddate=$enddate;
									
									$ObjDB->NonQuery("UPDATE itc_class_dyad_stagerotmapping SET fld_active='0' WHERE fld_schedule_id='".$sid."' AND fld_stageid='".$fld_id."'");
						 
									 $rotcount=$ObjDB->SelectSingleValueInt("SELECT sum(fld_numberofrotation) FROM itc_class_dyad_schedule_insstagemap WHERE fld_schedule_id='".$sid."' AND fld_id<'".$fld_id."' AND fld_flag='1' AND fld_numberofrotation<>'0'");
									 
									 if($rotcount=='')
									 {
										 $rotcount=0;
									 }
									 
									 $totcount=$rotcount+$fld_numberofrotation;
									 $count=$rotcount+1;
									 
									 for($i=$count;$i<=$totcount;$i++)
									 {
										if($i==$count)
										{
											$startdaterot=date("Y-m-d",strtotime($startdate));
										}
										else
										{
											$startdaterot="";
											$startdaterot=date("Y-m-d",strtotime($tempenddaterot. "+1 weekdays"));
											$tempenddaterot="";
										}
										
										$enddaterot=date("Y-m-d",strtotime($startdaterot. "+6 weekdays"));
										$tempenddaterot=$enddaterot;
										 
										 $checkrotcount= $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_class_dyad_stagerotmapping WHERE fld_schedule_id='".$sid."'  AND fld_rotation='".$i."' AND fld_stageid='".$fld_id."'");
										 
										 if($checkrotcount==0)
										 {                                      
											 $ObjDB->NonQuery("INSERT INTO itc_class_dyad_stagerotmapping(fld_schedule_id,fld_stageid,fld_rotation,fld_startdate,fld_enddate,fld_createddate,fld_createdby)VALUES('".$sid."','".$fld_id."','".$i."','".$startdaterot."','".$tempenddaterot."','".date("Y-m-d H:i:s")."','".$uid."')");
										 }
										 else
										 {
											$ObjDB->NonQuery("UPDATE itc_class_dyad_stagerotmapping SET fld_stageid='".$fld_id."',fld_startdate='".$startdaterot."',fld_enddate='".$tempenddaterot."',fld_active='1',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."'  AND fld_rotation='".$i."' AND fld_stageid='".$fld_id."'"); 
										 }
									 }
								
								$tempenddate=$tempenddaterot;
								
								}
								
								
								$ObjDB->NonQuery("UPDATE itc_class_dyad_schedule_insstagemap set fld_startdate='".$startdate."',fld_enddate='".$tempenddate."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_id='".$fld_id."'");
							
							$z++;	
								
							}
						  }
						}
					
				
			}
			
			
	    }
	    catch(Exception $e)
	    {
		  echo "false";
	    }
	}
	
	
	if($oper == "updatestagedates" and $oper != '')
	{	
		try
		{
			$stageid = isset($method['stageid']) ? $method['stageid'] : '0';
			$sid = isset($method['sid']) ? $method['sid'] : '0';
			$adjustflag = isset($method['adjustflag']) ? $method['adjustflag'] : '0';
			$startdate = isset($method['startdate']) ? $method['startdate'] : '0';
			$enddate = isset($method['enddate']) ? $method['enddate'] : '0';
			
			
			$validate_id=true;
			if($stageid!=0)  $validate_id=validate_datatype($stageid,'int');
			
			if($validate_id)
			{
				$msg='success';
				
				$stagerot=$ObjDB->QueryObject("SELECT fld_stagevalue as stagevalue,fld_stagetype as stagetype FROM `itc_class_dyad_schedule_insstagemap` WHERE fld_id='".$stageid."' and fld_schedule_id='".$sid."' AND fld_flag='1'");
				
				if($stagerot->num_rows>0)
				{
					$row=$stagerot->fetch_assoc();
					extract($row);
				}
				
				$afstageid=$stageid+1;
				
				$belowstageidsdate=$ObjDB->SelectSingleValue("SELECT fld_startdate FROM itc_class_dyad_schedule_insstagemap WHERE fld_id='".$afstageid."' AND fld_schedule_id='".$sid."' AND fld_flag='1'");
				
				$startdate=date("Y-m-d",strtotime($startdate));
				
				if($belowstageidsdate!='0000-00-00' and $belowstageidsdate!='')
				{
					if($startdate > $belowstageidsdate)
					{
						if($stagevalue==1 and $stagetype==1)
						{
							$msg="Orientation can not begin before Teacher led activity";
						}
						else if($stagevalue==2 and $stagetype==2)
						{
							$msg="Dyad rotation can not begin before Orientation";
						}
						else if(($stagevalue==3 or $stagevalue==4 or $stagevalue==5) and $stagetype==3)
						{
							$msg="Dyad rotation can not begin before Teacher led activity";
						}
						
					}
				}
				
					if($adjustflag==1 or $msg=="success")
					{
						$ObjDB->NonQuery("UPDATE itc_class_dyad_schedule_insstagemap set fld_startdate='".date("Y-m-d",strtotime($startdate))."',fld_enddate='".date("Y-m-d",strtotime($enddate))."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_id='".$stageid."'");
					}
				
						if($adjustflag==1) 
						{
							$qrystage=$ObjDB->NonQuery("SELECT fld_id,fld_stagevalue,fld_stagetype,fld_numberofrotation FROM itc_class_dyad_schedule_insstagemap WHERE fld_id>'".$stageid."' AND fld_schedule_id='".$sid."' AND fld_startdate<>'0000-00-00' AND fld_enddate<>'0000-00-00' AND fld_flag='1' order by fld_id ASC");
							
							if($qrystage->num_rows>0)
							{
							$z=0;
							while($rowstage=$qrystage->fetch_assoc())
							{
								extract($rowstage);
								
								if($z==0)
								{
									$startdate=date("Y-m-d",strtotime($enddate. "+1 weekdays"));
								}
								else
								{
									$startdate="";
									$startdate=date("Y-m-d",strtotime($tempenddate. "+1 weekdays"));
									$tempenddate="";
								}
								
								if($fld_stagetype==1)
								{
									if($fld_stagevalue==1)
									{
										$enddate=date("Y-m-d",strtotime($startdate. "+4 weekdays"));
										$tempenddate=$enddate;
									}
									else if($fld_stagevalue==2)
									{
										$enddate=date("Y-m-d",strtotime($startdate. "+4 weekdays"));
										$tempenddate=$enddate;
									}
									else if($fld_stagevalue==3)
									{
										$enddate=date("Y-m-d",strtotime($startdate. "+6 weekdays"));
										$tempenddate=$enddate;
									}
									else if($fld_stagevalue==4)
									{
										$enddate=date("Y-m-d",strtotime($startdate. "+6 weekdays"));
										$tempenddate=$enddate;
									}
									else if($fld_stagevalue==5)
									{
										$enddate=date("Y-m-d",strtotime($startdate. "+9 weekdays"));
										$tempenddate=$enddate;
									}
								}
								else if($fld_stagetype==2)
								{
									$enddate=date("Y-m-d",strtotime($startdate. "+6 weekdays"));
									$tempenddate=$enddate;
								}
								else if($fld_stagetype==3)
								{
									$days=($fld_numberofrotation*7)-1;
									$enddate=date("Y-m-d",strtotime($startdate. "+".$days." weekdays"));
									$tempenddate=$enddate;
									
									$ObjDB->NonQuery("UPDATE itc_class_dyad_stagerotmapping SET fld_active='0' WHERE fld_schedule_id='".$sid."' AND fld_stageid='".$fld_id."'");
						 
									 $rotcount=$ObjDB->SelectSingleValueInt("SELECT sum(fld_numberofrotation) FROM itc_class_dyad_schedule_insstagemap WHERE fld_schedule_id='".$sid."' AND fld_id<'".$fld_id."' AND fld_flag='1' AND fld_numberofrotation<>'0'");
									 
									 if($rotcount=='')
									 {
										 $rotcount=0;
									 }
									 
									 $totcount=$rotcount+$fld_numberofrotation;
									 $count=$rotcount+1;
									 
									 for($i=$count;$i<=$totcount;$i++)
									 {
										if($i==$count)
										{
											$startdaterot=date("Y-m-d",strtotime($startdate));
										}
										else
										{
											$startdaterot="";
											$startdaterot=date("Y-m-d",strtotime($tempenddaterot. "+1 weekdays"));
											$tempenddaterot="";
										}
										
										$enddaterot=date("Y-m-d",strtotime($startdaterot. "+6 weekdays"));
										$tempenddaterot=$enddaterot;
										 
										 $checkrotcount= $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_class_dyad_stagerotmapping WHERE fld_schedule_id='".$sid."'  AND fld_rotation='".$i."' AND fld_stageid='".$fld_id."'");
										 
										 if($checkrotcount==0)
										 {                                      
											 $ObjDB->NonQuery("INSERT INTO itc_class_dyad_stagerotmapping(fld_schedule_id,fld_stageid,fld_rotation,fld_startdate,fld_enddate,fld_createddate,fld_createdby)VALUES('".$sid."','".$fld_id."','".$i."','".$startdaterot."','".$tempenddaterot."','".date("Y-m-d H:i:s")."','".$uid."')");
										 }
										 else
										 {
											$ObjDB->NonQuery("UPDATE itc_class_dyad_stagerotmapping SET fld_stageid='".$fld_id."',fld_startdate='".$startdaterot."',fld_enddate='".$tempenddaterot."',fld_active='1',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."'  AND fld_rotation='".$i."' AND fld_stageid='".$fld_id."'"); 
										 }
									 }
								
								$tempenddate=$tempenddaterot;
								
								}
								
								
								$ObjDB->NonQuery("UPDATE itc_class_dyad_schedule_insstagemap set fld_startdate='".$startdate."',fld_enddate='".$tempenddate."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_id='".$fld_id."'");
							
							$z++;	
								
							}
						  }
						}
			}
			
			if($adjustflag==1) 
			{
				echo "success";
			}
			else
			{
				echo $msg; 
			}
			
	    }
	    catch(Exception $e)
	    {
		  echo "false";
	    }
	}
	
	
	if($oper == "stagecancel" and $oper != '')
	{	
		try
		{
			$stageid = isset($method['stageid']) ? $method['stageid'] : '0';
			
			$validate_id=true;
			if($stageid!=0)  $validate_id=validate_datatype($stageid,'int');
			
			if($validate_id)
			{
				$qrygetstagedate=$ObjDB->QueryObject("SELECT fld_startdate,fld_enddate FROM itc_class_dyad_schedule_insstagemap WHERE fld_id='".$stageid."'");
				if($qrygetstagedate->num_rows>0)
				{
					$row=$qrygetstagedate->fetch_assoc();
					extract($row);
					echo date("m/d/Y",strtotime($fld_startdate))."~". date("m/d/Y",strtotime($fld_enddate));
				}
			}
	   	}
	    catch(Exception $e)
	    {
		  echo "false";
	    }
	}
	
	if($oper == "rotcancel" and $oper != '')
	{	
		try
		{
			$stageid = isset($method['stageid']) ? $method['stageid'] : '0';
			
			$validate_id=true;
			if($stageid!=0)  $validate_id=validate_datatype($stageid,'int');
			
			if($validate_id)
			{
				$qrygetstagedate=$ObjDB->QueryObject("SELECT fld_startdate,fld_enddate FROM itc_class_dyad_stagerotmapping WHERE fld_id='".$stageid."'");
				if($qrygetstagedate->num_rows>0)
				{
					$row=$qrygetstagedate->fetch_assoc();
					extract($row);
					echo date("m/d/Y",strtotime($fld_startdate))."~". date("m/d/Y",strtotime($fld_enddate));
				}
			}
	   	}
	    catch(Exception $e)
	    {
		  echo "false";
	    }
	}
	
	if($oper == "checkstagedate" and $oper != '')
	{	
		try
		{
			$stagetype = isset($method['stagetype']) ? $method['stagetype'] : '0';
			$startdate = isset($method['distartdate']) ? $method['distartdate'] : '0';
			$stageid = isset($method['insstageid']) ? $method['insstageid'] : '0';
			$stagevalue = isset($method['stagevalue']) ? $method['stagevalue'] : '0';
			$sid = isset($method['sid']) ? $method['sid'] : '0';
			
			$validate_id=true;
			if($stageid!=0)  $validate_id=validate_datatype($stageid,'int');
			
			if($validate_id)
			{
				$prestageid=$stageid-1;
				$afstageid=$stageid+1;
				$msg='success~';
				
				$abovestageidsdate=$ObjDB->SelectSingleValue("SELECT fld_startdate FROM itc_class_dyad_schedule_insstagemap WHERE fld_id='".$prestageid."' AND fld_schedule_id='".$sid."' AND fld_flag='1'");
				
				$belowstageidsdate=$ObjDB->SelectSingleValue("SELECT fld_startdate FROM itc_class_dyad_schedule_insstagemap WHERE fld_id='".$afstageid."' AND fld_schedule_id='".$sid."' AND fld_flag='1'");
				
				$startdate=date("Y-m-d",strtotime($startdate));
				
				
				if($abovestageidsdate!='0000-00-00')
				{
					if($startdate < $abovestageidsdate)
					{
						if($stagevalue==2 and $stagetype==2)
						{
							$msg="above~Orientation can not begin before Teacher led activity";
						}
						else if($stagevalue==2 and $stagetype==2)
						{
							$msg="above~Dyad rotation can not begin before Orientation";
						}
						else if(($stagevalue==3 or $stagevalue==4 or $stagevalue==5) and $stagetype==1)
						{
							$msg="above~Teacherled activity can not begin before Dyadrotation";
						}
						else if(($stagevalue==3 or $stagevalue==4 or $stagevalue==5) and $stagetype==3)
						{
							$msg="above~Dyad rotation can not begin before Teacherled activity";
						}
					}
				}
				
				if($belowstageidsdate!='0000-00-00')
				{
					if($startdate > $belowstageidsdate)
					{
						if($stagevalue==1 and $stagetype==2)
						{
							$msg="below~Orientation can not begin before Teacher led activity";
						}
						else if($stagevalue==2 and $stagetype==2)
						{
							$msg="below~Dyad rotation can not begin before Orientation";
						}
						else if(($stagevalue==3 or $stagevalue==4 or $stagevalue==5) and $stagetype==1)
						{
							$msg="below~Teacherled activity can not begin before Dyadrotation";
						}
						else if(($stagevalue==3 or $stagevalue==4 or $stagevalue==5) and $stagetype==3)
						{
							$msg="below~Dyad rotation can not begin before Teacherled activity";
						}
					}
				}
				
				echo $msg;
			}
	   	}
	    catch(Exception $e)
	    {
		  echo "false";
	    }
	}
	
	
	if($oper == "checkstageeditmode" and $oper != '')
	{	
		try
		{
			$startdate = isset($method['startdate']) ? $method['startdate'] : '0';
			$stageid = isset($method['stageid']) ? $method['stageid'] : '0';
			$sid = isset($method['sid']) ? $method['sid'] : '0';
			
			$validate_id=true;
			if($stageid!=0)  $validate_id=validate_datatype($stageid,'int');
			
			if($validate_id)
			{
				$prestageid=$stageid-1;
				
				$stagecount=$ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_class_dyad_schedule_insstagemap WHERE fld_id>'".$stageid."' AND fld_schedule_id='".$sid."' AND fld_flag='1' AND fld_startdate<>'0000-00-00' limit 0,1");
				
				$msg='success~'.$stagecount;
				
				$abovestageidsdate=$ObjDB->SelectSingleValue("SELECT fld_startdate FROM itc_class_dyad_schedule_insstagemap WHERE fld_id='".$prestageid."' AND fld_schedule_id='".$sid."' AND fld_flag='1'");
				
				$startdate=date("Y-m-d",strtotime($startdate));
				
				$stagefields=$ObjDB->QueryObject("SELECT fld_stagevalue as stagevalue,fld_stagetype as stagetype FROM itc_class_dyad_schedule_insstagemap WHERE fld_id='".$stageid."' AND fld_schedule_id='".$sid."' AND fld_flag='1'");
				
				if($stagefields->num_rows>0)
				{
					$row=$stagefields->fetch_assoc();
					extract($row);
				}
				
				if($abovestageidsdate!='0000-00-00' and $abovestageidsdate!='')
				{
					if($startdate < $abovestageidsdate)
					{
						if($stagevalue==2 and $stagetype==2)
						{
							$msg="Orientation can not begin before Teacher led activity~";
						}
						else if($stagevalue==2 and $stagetype==2)
						{
							$msg="dyad rotation can not begin before Orientation~";
						}
						else if(($stagevalue==3 or $stagevalue==4 or $stagevalue==5) and $stagetype==1)
						{
							$msg="Teacherled activity can not begin before dyadrotation~";
						}
						
					}
				}
				
				echo $msg;
			}
	   	}
	    catch(Exception $e)
	    {
		  echo "false";
	    }
	}
	
	
	if($oper == "checkstageroteditmode" and $oper != '')
	{	
		try
		{
			$startdate = isset($method['startdate']) ? $method['startdate'] : '0';
			$id = isset($method['id']) ? $method['id'] : '0';
			$sid = isset($method['sid']) ? $method['sid'] : '0';
			
			$validate_id=true;
			if($id!=0)  $validate_id=validate_datatype($id,'int');
			
			if($validate_id)
			{
				
				
				
				$stagerot=$ObjDB->QueryObject("SELECT fld_stageid as stageid,fld_rotation as rotation FROM `itc_class_dyad_stagerotmapping` WHERE fld_id='".$id."' AND fld_active='1'");
				
				if($stagerot->num_rows>0)
				{
					$row=$stagerot->fetch_assoc();
					extract($row);
				}
				
				$stagecount=$ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_class_dyad_schedule_insstagemap WHERE fld_id>'".$stageid."' AND fld_schedule_id='".$sid."' AND fld_flag='1' AND fld_startdate<>'0000-00-00' limit 0,1");
				
				$msg='success~'.$stagecount;
				
				$minrotation=$ObjDB->SelectSingleValue("SELECT min(fld_rotation) FROM `itc_class_dyad_stagerotmapping` where fld_stageid='".$stageid."' and fld_schedule_id='".$sid."' and fld_active='1'");
				
				$startdate=date("Y-m-d",strtotime($startdate));
				
				$stagedet=$ObjDB->QueryObject("SELECT fld_startdate,fld_stagevalue,fld_stagetype FROM itc_class_dyad_schedule_insstagemap WHERE fld_id<'".$stageid."' AND fld_schedule_id='".$sid."' AND fld_flag='1' AND fld_startdate<>'0000-00-00' order by fld_id desc limit 0,1");
				
				if($stagedet->num_rows>0)
				{
					$row=$stagedet->fetch_assoc();
					extract($row);
				}
				
				if($rotation==$minrotation)
				{
					if($fld_startdate!='0000-00-00' and $fld_startdate!='')
					{
						if($startdate < $fld_startdate)
						{
							if($fld_stagevalue==2 and $fld_stagetype==2)
							{
								$msg="Dyad rotation can not begin before Orientation~";
							}
							else
							{
								$msg="Dyad rotation can not begin before Teacherled activity~";
							}
						}
					}
				}
				else
				{
					$prot=$rotation-1;
					$prestartdate=$ObjDB->SelectSingleValue("SELECT fld_startdate FROM itc_class_dyad_stagerotmapping WHERE fld_stageid='".$stageid."' and fld_schedule_id='".$sid."' and fld_active='1' and fld_rotation='".$prot."'");
					
					if($prestartdate!='0000-00-00' and $prestartdate!='')
					{
						if($startdate < $prestartdate)
						{
							$msg="rotation ".$rotation." can not begin before rotation ".$prot."~";
						}
					}
					
				}
				
				echo $msg;
				
			}
	   	}
	    catch(Exception $e)
	    {
		  echo "false";
	    }
	}
	
	
	if($oper == "checkbelowstage" and $oper != '')
	{	
		try
		{
			$stageid = isset($method['stageid']) ? $method['stageid'] : '0';
			$sid = isset($method['sid']) ? $method['sid'] : '0';
			
			$validate_id=true;
			if($stageid!=0)  $validate_id=validate_datatype($stageid,'int');
			
			if($validate_id)
			{
				$stagecount=$ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_class_dyad_schedule_insstagemap WHERE fld_id>'".$stageid."' AND fld_schedule_id='".$sid."' AND fld_flag='1' AND fld_startdate<>'0000-00-00' limit 0,1");
				
				echo $stagecount;
			}
		}
	    catch(Exception $e)
	    {
		  echo "false";
	    }
	}

	@include("footer.php");

	@include("footer.php");