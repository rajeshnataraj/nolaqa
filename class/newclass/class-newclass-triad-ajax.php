<?php 
	@include("sessioncheck.php");
	
	$date = date("Y-m-d H:i:s");
	$oper = isset($method['oper']) ? $method['oper'] : '';
	
	/*--- save Triad table cell details  ---*/
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
		
		$ObjDB->NonQuery("UPDATE itc_class_triad_schedulemaster SET fld_flag=1 WHERE fld_id='".$scheduleid."'");
		$ObjDB->NonQuery("UPDATE itc_class_triad_moduledet SET fld_flag='0' WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."'");
		
		$j=1;
		for($i=0;$i<sizeof($moduledet);$i++)
		{
			if($moduledet[$i]!="undefined")
			{
				$count=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_class_triad_moduledet WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_module_id='".$moduledet[$i]."' AND fld_row_id='".$j."'");
				
				if($count==0)
				{
					$ObjDB->NonQuery("INSERT INTO itc_class_triad_moduledet(fld_class_id,fld_schedule_id,fld_module_id,fld_row_id,fld_numberofrotation)VALUES('".$classid."','".$scheduleid."','".$moduledet[$i]."','".$j."','".$numberofrotation."')");
				}
				else
				{
					$ObjDB->NonQuery("UPDATE itc_class_triad_moduledet SET fld_flag='1' WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_module_id='".$moduledet[$i]."' AND fld_row_id='".$j."'");
				}
			}
			
			$j++;
		}
		
		$qryorientation=$ObjDB->NonQuery("SELECT fld_id,fld_startdate,fld_enddate FROM itc_class_triad_schedule_insstagemap WHERE fld_schedule_id='".$scheduleid."' and fld_orientationmod='1' and fld_flag='1'");
		
		$ObjDB->NonQuery("UPDATE itc_class_triad_schedulegriddet SET fld_flag='0',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."'");
		
		if($qryorientation->num_rows>0)
		{
			$roworientation=$qryorientation->fetch_assoc();
			extract($roworientation);
			
			$oricount=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_class_triad_schedulegriddet WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_rotation=0");
			
			$orimodid=$ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_module_master where fld_module_type='2' and fld_delstatus='0'");
			
			if($oricount==0)
			{
				
				$ObjDB->NonQuery("INSERT INTO itc_class_triad_schedulegriddet(fld_class_id,fld_schedule_id,fld_rotation,fld_startdate,fld_enddate,fld_module_id,fld_stageid,fld_createddate,fld_createdby)VALUES('".$classid."','".$scheduleid."',0,'".$fld_startdate."','".$fld_enddate."','".$orimodid."','".$fld_id."','".date('Y-m-d H:i:s')."','".$uid."')");
				
			}
			else
			{
				$ObjDB->NonQuery("UPDATE itc_class_triad_schedulegriddet SET fld_startdate='".$fld_startdate."',fld_enddate='".$fld_enddate."',fld_stageid='".$fld_id."',fld_flag='1',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_module_id='".$orimodid."'");
			}
		}
		
		for($i=0;$i<sizeof($celldet);$i++)
		{
			$getcelldet=explode("~",$celldet[$i]);
			$getrowid=explode("_",$getcelldet[2]);
			
			if($getcelldet[3]!="undefined")
			{
				$count=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_class_triad_schedulegriddet WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_module_id='".$getcelldet[0]."' AND fld_row_id='".$getrowid[1]."' AND fld_cell_id='".$getcelldet[2]."'");
			
				if($count==0)
				{
					
					$ObjDB->NonQuery("INSERT INTO itc_class_triad_schedulegriddet(fld_class_id,fld_schedule_id,fld_module_id,fld_rotation,fld_cell_id,fld_student_id,fld_row_id,fld_startdate,fld_enddate,fld_createddate,fld_createdby)VALUES('".$classid."','".$scheduleid."','".$getcelldet[0]."','".$getcelldet[1]."','".$getcelldet[2]."','".$getcelldet[3]."','".$getrowid[1]."','".date("Y-m-d",strtotime($getcelldet[5]))."','".date("Y-m-d",strtotime($getcelldet[6]))."','".date("Y-m-d H:i:s")."','".$uid."')");
				}
				else
				{
					$ObjDB->NonQuery("UPDATE itc_class_triad_schedulegriddet SET fld_flag='1',fld_student_id='".$getcelldet[3]."',fld_startdate='".date("Y-m-d",strtotime($getcelldet[5]))."',fld_enddate='".date("Y-m-d",strtotime($getcelldet[6]))."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_module_id='".$getcelldet[0]."' AND fld_row_id='".$getrowid[1]."' AND fld_cell_id='".$getcelldet[2]."'");
				}
			}
			
		}
		
		for($i=0;$i<sizeof($startdate);$i++)
		{
			$rotationsdate=explode("~",$startdate[$i]);
		
			$ObjDB->NonQuery("UPDATE itc_class_triad_schedulegriddet SET fld_startdate='".$rotationsdate[1]."',fld_stageid='".$rotationsdate[2]."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_rotation='".$rotationsdate[0]."' AND fld_schedule_id='".$scheduleid."' AND fld_class_id='".$classid."'");
		}
		
		for($i=0;$i<sizeof($enddate);$i++)
		{
			$rotationedate=explode("~",$enddate[$i]);

			$ObjDB->NonQuery("UPDATE itc_class_triad_schedulegriddet SET fld_enddate='".$rotationedate[1]."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_rotation='".$rotationedate[0]."' AND fld_schedule_id='".$scheduleid."' AND fld_class_id='".$classid."'");
		}		
		
		
		$triadenddate=$ObjDB->SelectSingleValue("SELECT MAX(fld_enddate) FROM itc_class_triad_schedule_insstagemap WHERE fld_schedule_id='".$scheduleid."' AND fld_flag='1'");
		
		$ObjDB->NonQuery("UPDATE itc_class_triad_schedulemaster SET fld_triadtableflg=1,fld_enddate='".$triadenddate."',fld_gridupdateddate='".date("Y-m-d H:i:s")."' WHERE fld_id='".$scheduleid."'");
		
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
	if($oper == "triadinstructions" and $oper != '')
	{		
		$sid = isset($method['sid']) ? $method['sid'] : '0';
		$licenseid = isset($method['licenseid']) ? $method['licenseid'] : '0'; 
		$flag = isset($method['flag']) ? $method['flag'] : '0';
		
		if($sid!=0)
		{
			$qry=$ObjDB->NonQuery("SELECT fld_id,fld_stagevalue,fld_stagetype,fld_stagename,fld_startdate,fld_enddate,fld_numberofrotation FROM itc_class_triad_schedule_insstagemap WHERE fld_schedule_id='".$sid."' AND fld_flag='1'");
			
			$triadtableflag=$ObjDB->SelectSingleValueInt("SELECT fld_triadtableflg FROM itc_class_triad_schedulemaster WHERE fld_id='".$sid."' and fld_delstatus='0'");
			
		}
		 
		$qrytriad=$ObjDB->NonQuery("SELECT fld_id,fld_name FROM itc_class_definetriads WHERE fld_schedule_id='".$sid."' AND fld_delstatus='0' ORDER BY fld_id ASC");
		
		?>
        	<div class="row">
                <div class="span10">
                <p class="darkTitle">Define Triads</p>
                <p class="darkSubTitle">Below are the Define Triads of a Traditional Lab schedule. Click "Add a new triad to this schedule" to add additional triad. </p>
                </div>
            </div>
            
            <form id="triadform">
            <div class="row rowspacer <?php if($flag==1){?> dim <?php } ?>">
        	<table class='table table-striped table-bordered' id="mytable">
                <thead class='tableHeadText'>
                    <tr>                        
                        <th class='centerText' style="width:20%">Triad</th> 
                        <th class='centerText' style="width:20%">Module 1</th>
                        <th class='centerText' style="width:20%">Module 2</th>
                        <th class='centerText' style="width:20%">Module 3</th>
                        <th class='centerText' style="width:20%">move or delete</th>
                    </tr>
                </thead>
                <tbody> 
                <input type="hidden" name="ttcount" id="ttcount" value="<?php echo $qrytriad->num_rows;?>" />
                	<?php
					    $rowcount=$qrytriad->num_rows;
						if($qrytriad->num_rows > 0)
						{
							$cnt=1;
							while($row=$qrytriad->fetch_assoc())
							{
								extract($row);
								
						?>                   
                    <tr class="rowt-<?php echo $cnt;?>">
                        <td style="cursor:default;text-align:center;" class="<?php echo $fld_name;?>" id="definetriad_<?php echo $cnt;?>_1"><?php echo $fld_name;?></td>
                        
                        <?php
							 
							 $qrymodulemap=$ObjDB->QueryObject("SELECT a.fld_id as moduleid,fn_shortname(CONCAT(a.fld_module_name,' ',b.fld_version),1) AS                                           shortname, CONCAT(a.fld_module_name,' ',b.fld_version) as modulename 
							               FROM itc_module_master AS a
										   LEFT JOIN itc_module_version_track AS b ON b.fld_mod_id=a.fld_id 
										   LEFT JOIN itc_class_triad_schedule_modulemapping AS c ON a.fld_id=c.fld_module_id 
										   WHERE c.fld_schedule_id='".$sid."' AND c.fld_triad_id='".$fld_id."' AND c.fld_flag=1 AND b.fld_delstatus='0' AND                                            a.fld_delstatus='0'");
										   
                                        if($qrymodulemap->num_rows > 0){
											$ttriad=2;
                                            while($rowmodulemap = $qrymodulemap->fetch_assoc()){
                                                extract($rowmodulemap);
                                            ?>
                                             <td style="cursor:default; text-align:center;" class="<?php echo $moduleid;?>" id="definetriad_<?php echo $cnt.'_'.$ttriad;?>"><?php echo $modulename;?></td>
                                            <?php
											$ttriad++;
											}
										}
										?>
                       
                        <td class='centerText'> 
                       
                       
                        <div class="icon-synergy-trash" style="float:right; font-size:18px;padding-right: 60px;" onclick="fn_deletedefinetriad(<?php echo $fld_id.","."'rowt-".$cnt."',".$flag;?>)"></div>
                         <div class="icon-synergy-edit"  style="float:right; font-size:18px;padding-right: 10px;" onclick="fn_showdefinetriad(<?php echo $fld_id.",".$flag;?>)"></div>
                         
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
                            	<td colspan="5" align="center"> No Records </td>
                            </tr> 
                        <?php
						}
						?>
                         <tr id="triadformdet" style="display:none;">
                        	<td class='centerText' style="cursor:default;">
                                 <dl class='field row' style="width:150px">
                                    <dt class='text'> 
                                    <input placeholder='Triad Name' required='' type='text' id="triadname" name="triadname" <?php if($flag==1){?> readonly <?php } ?>  maxlength="10">
                                    </dt>
                                 </dl>
                            </td>
                            <td class='centerText' style="cursor:default;">
                            	
                                    <dl class='field row'>   
                                        <dt class='dropdown'>   
                                            <div class="selectbox triadddbox <?php if($flag==1){ ?> dim <?php } ?>" style="width:130px;">
                                                <input type="hidden" name="module1" id="module1"/>
                                                <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                                    <span class="selectbox-option input-medium" data-option="" id="mod1name" style="float:left;width:85%">Select Module</span>
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
													 WHERE a.fld_id NOT IN (SELECT fld_module_id FROM itc_class_triad_schedule_modulemapping WHERE                                                     fld_schedule_id='".$sid."' AND fld_flag='1') AND c.fld_license_id='".$licenseid."' AND b.fld_delstatus='0' AND a.fld_module_type='1' AND c.fld_type='1' AND a.fld_delstatus='0' ORDER BY a.fld_module_name ASC");
													 
												if($qrymodule->num_rows > 0){
													while($rowsqry = $qrymodule->fetch_assoc()){
														extract($rowsqry);
																?>
                                                                <li style="float:left;"><a tabindex="-1" href="#" data-option="<?php echo $moduleid;?>" id="toption1<?php echo $moduleid;?>" onclick="fn_tloadmodddbox(<?php echo $moduleid;?>,'mod1')"><?php echo $modulename;?></a></li>
                                                                
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
                                            <div class="selectbox triadddbox <?php if($flag==1){ ?> dim <?php } ?>" style="width:130px;">
                                                <input type="hidden" name="module2" id="module2"/>
                                                <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                                    <span class="selectbox-option input-medium" data-option="" id="mod2name" style="float:left;width:85%">Select Module</span>
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
													 WHERE a.fld_id NOT IN (SELECT fld_module_id FROM itc_class_triad_schedule_modulemapping WHERE                                                     fld_schedule_id='".$sid."' AND fld_flag='1') AND c.fld_license_id='".$licenseid."' AND b.fld_delstatus='0'  AND a.fld_module_type='1' AND c.fld_type='1' AND a.fld_delstatus='0' ORDER BY a.fld_module_name ASC");
												if($qrymodule->num_rows > 0){
													while($rowsqry = $qrymodule->fetch_assoc()){
														extract($rowsqry);
																?>
                                                                <li style="float:left;"><a tabindex="-1" href="#" data-option="<?php echo $moduleid;?>" id="toption2<?php echo $moduleid;?>" onclick="fn_tloadmodddbox(<?php echo $moduleid;?>,'mod2')"><?php echo $modulename;?></a></li>
                                                                
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
                                            <div class="selectbox triadddbox <?php if($flag==1){ ?> dim <?php } ?>" style="width:130px;">
                                                <input type="hidden" name="module3" id="module3"/>
                                                <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                                    <span class="selectbox-option input-medium" data-option="" id="mod3name" style="float:left;width:85%">Select Module</span>
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
													 WHERE a.fld_id NOT IN (SELECT fld_module_id FROM itc_class_triad_schedule_modulemapping WHERE                                                     fld_schedule_id='".$sid."' AND fld_flag='1') AND c.fld_license_id='".$licenseid."' AND b.fld_delstatus='0'  AND a.fld_module_type='1' AND c.fld_type='1' AND a.fld_delstatus='0' ORDER BY a.fld_module_name ASC");
													 
												if($qrymodule->num_rows > 0){
													while($rowsqry = $qrymodule->fetch_assoc()){
														extract($rowsqry);
																?>
                                                                <li style="float:left;"><a tabindex="-1" href="#" data-option="<?php echo $moduleid;?>" id="toption3<?php echo $moduleid;?>" onclick="fn_tloadmodddbox(<?php echo $moduleid;?>,'mod3')"><?php echo $modulename;?></a></li>
                                                                
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
                             <div class="icon-synergy-close" style="float:right; font-size:18px;padding-right: 60px;margin-top:3px; cursor:pointer;" onclick="$('#triadformdet').hide();"></div>
                         <div class="icon-synergy-create"  style="float:right; font-size:20px;padding-right: 10px;margin-top:3px; cursor:pointer;" onclick="fn_savetriadinsschedule('addtriad',<?php echo $flag;?>);"></div>
                            </td>
                         <tr>
                         <?php
						 	if($rowcount<8)
							{
							?>
                        <tr>
                    	<td colspan="5">
                        	<span class="icon-synergy-add-dark small-icon" style="padding-top:10px;"></span>
   								<span onclick="$('#triadformdet').show();$('#triadname').val('');$('#module1').val('');$('#mod1name').html('Select module');$('#module2').val('');$('#mod2name').html('Select module');$('#module3').val('');$('#mod3name').html('Select module');$('#triadid').val('');$('.triadddbox').removeClass('dim');$('#triadname').removeAttr('readonly');">Add a triad to this schedule</span>
                        </td>
                    </tr>
                    	<?php
							}
							?>
                            <input type="hidden" name="scrolltriad" id="scrolltriad" />
				</tbody>
            </table>
            </div>
            <input type="hidden" name="triadid" id="triadid"/>
             <input type="hidden" name="templateflag" id="templateflag" value="<?php echo $flag;?>" />
            </form>
            
            <script type="text/javascript" language="javascript">
				
				$(function(){
									$("#triadform").validate({
										ignore: "",
											errorElement: "dd",
											errorPlacement: function(error, element) {
												$(element).parents('dl').addClass('error');
												error.appendTo($(element).parents('dl'));
												error.addClass('msg'); 	
										},
										rules: { 
											triadname: { required: true },
											module1: { required: true },	
											module2: { required: true },
											module3: { required: true }	
										}, 
										messages: { 
											triadname: {  required: "Fill triad name" },	
											module1: {  required: "Select anyone module" },
											module2: {  required: "Select anyone module" },
											module3: {  required: "Select anyone module" }								
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
                        <th class='centerText' style="cursor:default;">end date</th>
                        <th class='centerText' style="cursor:default;">move or delete</th>
                    </tr>
                </thead>
                <tbody>                    
                    <tr  class="row-1">
                        <td style="cursor:default; text-align:center;" class="1" id="triad_1_2">Stage 1 Activity</td> 
                        <td class='centerText' id="triad_1_3" style="cursor:default;">Teacher Led</td> 
                        <td class='centerText' style="cursor:default;">--/--/--</td> 
                        <td class='centerText' style="cursor:default;">--/--/--</td> 
                        <td class='centerText'> 
 				
                <div class="icon-synergy-trash" style="float:right; font-size:18px;padding-right: 60px;" onclick="fn_deletetriadstage('row-1',0)"></div>
                <div class="icon-synergy-edit mainBtn" id="btnclass-newclass-instructionstagetriad"  name="1,1,0,Teacher led" style="float:right; font-size:18px;padding-right: 10px;"></div>
                </td>                                                               
                    </tr>
                     <tr class="row-2">
                        
                        <td style="cursor:default; text-align:center;" class="2" id="triad_2_2">Orientation</td> 
                        <td class='centerText' id="triad_2_3" style="cursor:default;">Orientation</td> 
                        <td class='centerText' style="cursor:default;">--/--/--</td> 
                        <td class='centerText' style="cursor:default;">--/--/--</td> 
                        <td class='centerText'> 
                 	<div class="icon-synergy-trash" style="float:right; font-size:18px;padding-right: 60px;" onclick="fn_deletetriadstage('row-2',0)"></div>
                     <div class="icon-synergy-edit mainBtn" id="btnclass-newclass-instructionstagetriad"  name="2,2,0,Orientation" style="float:right; font-size:18px;padding-right: 10px;"></div>
                    </td>                                                               
                    </tr>
                     <tr class="row-3">
                        
                        <td style="cursor:default; text-align:center;" class="2" id="triad_3_2">Triad Rotation 1</td> 
                        <td class='centerText' id="triad_3_3" style="cursor:default;">Triad Rotation</td> 
                        <td class='centerText' style="cursor:default;">--/--/--</td> 
                        <td class='centerText' style="cursor:default;">--/--/--</td> 
                        <td class='centerText'> 
					<div class="icon-synergy-trash" style="float:right; font-size:18px;padding-right: 60px;" onclick="fn_deletetriadstage('row-3',0)"></div>
                    <div class="icon-synergy-edit mainBtn" id="btnclass-newclass-instructionstagetriad"  name="2,3,0,Triad 1" style="float:right; font-size:18px;padding-right: 10px;"></div>
                    </td>                                                               
                    </tr>
                     <tr class="row-4">
                       
                        <td style="cursor:default; text-align:center;" class="2" id="triad_4_2">Stage 2 Activity</td>  
                        <td class='centerText' id="triad_4_3" style="cursor:default;">Teacher Led</td> 
                        <td class='centerText' style="cursor:default;">--/--/--</td> 
                        <td class='centerText' style="cursor:default;">--/--/--</td> 
                        <td class='centerText'> 
					<div class="icon-synergy-trash" style="float:right; font-size:18px;padding-right: 60px;" onclick="fn_deletetriadstage('row-4',0)"></div>
                    <div class="icon-synergy-edit mainBtn" id="btnclass-newclass-instructionstagetriad"  name="2,1,0,Stage 2 Activity" style="float:right; font-size:18px;padding-right: 10px;"></div>
                    </td>                                                               
                    </tr>
                     <tr class="row-5">
                       
                        <td style="cursor:default; text-align:center;" class="3" id="triad_5_2">Triad Rotation 2</td> 
                        <td class='centerText' id="triad_5_3" style="cursor:default;">Triad Rotation</td> 
                        <td class='centerText' style="cursor:default;">--/--/--</td> 
                        <td class='centerText' style="cursor:default;">--/--/--</td> 
                        <td class='centerText'>
					<div class="icon-synergy-trash" style="float:right; font-size:18px;padding-right: 60px;" onclick="fn_deletetriadstage('row-5',0)"></div>
                     <div class="icon-synergy-edit mainBtn" id="btnclass-newclass-instructionstagetriad"  name="3,3,0,Triad 2" style="float:right; font-size:18px;padding-right: 10px;"></div>
                    </td>                                                               
                    </tr>
                     <tr class="row-6">
                        
                        <td style="cursor:default; text-align:center;" class="3" id="triad_6_2">Stage 3 Activity</td> 
                        <td class='centerText' id="triad_6_3" style="cursor:default;">Teacher Led</td> 
                        <td class='centerText' style="cursor:default;">--/--/--</td> 
                        <td class='centerText' style="cursor:default;">--/--/--</td> 
                        <td class='centerText'>
					<div class="icon-synergy-trash" style="float:right; font-size:18px;padding-right: 60px;" onclick="fn_deletetriadstage('row-6',0)"></div>
                     <div class="icon-synergy-edit mainBtn" id="btnclass-newclass-instructionstagetriad"  name="3,1,0,Stage 3 Activity" style="float:right; font-size:18px;padding-right: 10px;"></div>
                    </td>                                                               
                    </tr>
                     <tr class="row-7">
                       
                        <td style="cursor:default; text-align:center;" class="4" id="triad_7_2">Triad Rotation 3</td> 
                        <td class='centerText' id="triad_7_3" style="cursor:default;">Triad Rotation</td> 
                        <td class='centerText' style="cursor:default;">--/--/--</td> 
                        <td class='centerText' style="cursor:default;">--/--/--</td> 
                        <td class='centerText'> 
   				<div class="icon-synergy-trash" style="float:right; font-size:18px;padding-right: 60px;" onclick="fn_deletetriadstage('row-7',0)"></div>
                 <div class="icon-synergy-edit mainBtn" id="btnclass-newclass-instructionstagetriad"  name="4,3,0,Triad 3" style="float:right; font-size:18px;padding-right: 10px;"></div>
                </td>                                                               
                    </tr>
                     <tr class="row-8">
                        
                        <td style="cursor:default; text-align:center;" class="4" id="triad_8_2">Stage 4 Activity</td> 
                        <td class='centerText' id="triad_8_3" style="cursor:default;">Teacher Led</td> 
                        <td class='centerText' style="cursor:default;">--/--/--</td> 
                        <td class='centerText' style="cursor:default;">--/--/--</td> 
                        <td class='centerText'> 
   				<div class="icon-synergy-trash" style="float:right; font-size:18px;padding-right: 60px;" onclick="fn_deletetriadstage('row-8',0)"></div>
                 <div class="icon-synergy-edit mainBtn" id="btnclass-newclass-instructionstagetriad"  name="4,1,0,Stage 4 Activity" style="float:right; font-size:18px;padding-right: 10px;"></div>
                </td>                                                               
                    </tr>
                     <tr class="row-9">
                       
                        <td style="cursor:default; text-align:center;" class="5" id="triad_9_2">Triad Rotation 4</td> 
                        <td class='centerText' id="triad_9_3" style="cursor:default;">Triad Rotation</td> 
                        <td class='centerText' style="cursor:default;">--/--/--</td> 
                        <td class='centerText' style="cursor:default;">--/--/--</td> 
                        <td class='centerText' style="cursor:default;"> 
  					<div class="icon-synergy-trash" style="float:right; font-size:18px;padding-right: 60px;" onclick="fn_deletetriadstage('row-9',0)"></div>
                    <div class="icon-synergy-edit mainBtn" id="btnclass-newclass-instructionstagetriad"  name="5,3,0,Triad 4" style="float:right; font-size:18px;padding-right: 10px;"></div>
                     </td>                                                               
                    </tr>
                    <tr class="row-10">
                        
                        <td style="cursor:default; text-align:center;" class="5" id="triad_10_2">Stage 5 Activity</td> 
                        <td class='centerText' id="triad_10_3" style="cursor:default;">Teacher Led</td> 
                        <td class='centerText' style="cursor:default;">--/--/--</td> 
                        <td class='centerText' style="cursor:default;">--/--/--</td> 
                        <td class='centerText' style="cursor:default;"> 
  					<div class="icon-synergy-trash" style="float:right; font-size:18px;padding-right: 60px;" onclick="fn_deletetriadstage('row-10',0)"></div>
                    <div class="icon-synergy-edit mainBtn" id="btnclass-newclass-instructionstagetriad"  name="5,3,0,Stage 5 Activity" style="float:right; font-size:18px;padding-right: 10px;"></div>
                     </td>                                                               
                    </tr>
                    <tr id="btnclass-newclass-instructionstagetriad" class="mainBtn addtriadstage" style="display:none;">
                    	<td colspan="8">
                        	<span class="icon-synergy-add-dark small-icon" style="padding-top:10px;"></span>
   								Add a stage to this schedule
                                <input type="hidden" name="scrollins" id="scrollins" />
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
                        
                        <th class='centerText' style="cursor:default;">instruction name</th>
                        <th class='centerText' style="cursor:default;">stage/step type</th>
                        <th class='centerText' style="cursor:default;">start date</th>
                        <th class='centerText' style="cursor:default;">end date</th>
                        <th class='centerText' style="cursor:default;">move or delete</th>
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
									$stagetype="Teacher Led";
								}
								else if($fld_stagetype==2)
								{
									$stagetype="Orientation";
								}
								else
								{
									$stagetype="Triad Rotation";
								}
								
						?>                   
                    <tr class="row-<?php echo $cnt;?>" data-tt-id="<?php echo $m;?>">
                       
                        <td  style="cursor:default;" id="stage<?php echo $fld_id;?>-1">
                        			
                                        <?php 
										echo $fld_stagename;
										?>
                                       
									
							
                        </td> 
                        <td class='centerText' style="cursor:default;" id="stage<?php echo $fld_id;?>-2"><?php echo $stagetype;?></td> 
                        <td class='centerText' style="cursor:default;" id="stage<?php echo $fld_id;?>-3"><?php if($fld_startdate=='0000-00-00'){ echo "--/--/--"; } else { echo date("m/d/Y",strtotime($fld_startdate));}?></td> 
                        <td class='centerText' style="cursor:default;" id="stage<?php echo $fld_id;?>-4"><?php if($fld_enddate=='0000-00-00'){ echo "--/--/--"; } else { echo date("m/d/Y",strtotime($fld_enddate));}?></td> 
                        <td class='centerText' id="stage<?php echo $fld_id;?>-5"> 
                       
                       
                        <div class="icon-synergy-trash" style="float:right; font-size:18px;padding-right: 60px;" onclick="fn_deletetriadstage('row-<?php echo $cnt;?>',<?php echo $fld_id;?>)"></div>
                        
                        <?php
							if($fld_stagetype==1 and $fld_startdate=='0000-00-00')
							{
						?>
                        			<div class="icon-synergy-edit" onclick="fn_showinstructionstagetriad(<?php echo $fld_stagevalue.",".$fld_stagetype.",".$fld_id;?>);" style="float:right; font-size:18px;padding-right: 10px;"></div>
                        <?php
							}
							else if($fld_stagetype==1 and $fld_startdate!='0000-00-00')
							{
							?>
                            		<div class="icon-synergy-edit" onclick="fn_updatestagedates(<?php echo $fld_stagevalue.",".$fld_stagetype.",".$fld_id.",".$cnt;?>);" style="float:right; font-size:18px;padding-right: 10px;"></div>
                            <?php
							}
							
							if($fld_stagetype==2 and $fld_startdate=='0000-00-00')
							{
						?>
                        			<div class="icon-synergy-edit" onclick="fn_showinstructionstagetriad(<?php echo $fld_stagevalue.",".$fld_stagetype.",".$fld_id;?>);" style="float:right; font-size:18px;padding-right: 10px;"></div>
                        <?php
							}
							else if($fld_stagetype==2 and $fld_startdate!='0000-00-00')
							{
							?>
                            		<div class="icon-synergy-edit" onclick="fn_updatestagedates(<?php echo $fld_stagevalue.",".$fld_stagetype.",".$fld_id;?>);" style="float:right; font-size:18px;padding-right: 10px;"></div>
                            <?php
							}
							
							if($fld_stagetype==3 and $fld_startdate=='0000-00-00')
							{
						?>
                        			<div class="icon-synergy-edit" onclick="fn_showinstructionstagetriad(<?php echo $fld_stagevalue.",".$fld_stagetype.",".$fld_id.",".$cnt;?>);" style="float:right; font-size:18px;padding-right: 10px;"></div>
                        <?php
							}
							else if($fld_stagetype==3 and $fld_startdate!='0000-00-00')
							{
							?>
                           			 <div  onclick="fn_updatenumofrotation(<?php echo $fld_id;?>);" style="float:right; font-size:18px;padding-right:3px;margin-top:10px;margin-right:5px;width:30px;height:20px; background-color: #666;-moz-border-radius: 15px;border-radius: 15px;text-align:center; color: #FFF;"><?php echo $fld_numberofrotation;?></div>
                            <?php
							}
							?>
                         
                         
                        </td>                                                               
                    </tr>
					<?php
							$getrot=$ObjDB->QueryObject("SELECT fld_id,fld_startdate,fld_enddate FROM itc_class_triad_stagerotmapping WHERE fld_stageid='".$fld_id."' AND fld_active='1' order by fld_id ASC");
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
                                   
									<div class="icon-synergy-edit"  style="font-size:18px;padding-right: 10px;" id="rot<?php echo $fld_id;?>-5.1" onclick="fn_editstagerotdate(<?php echo $fld_id;?>);"></div>
                                     
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
                         <tr id="btnclass-newclass-instructionstagetriad" class="mainBtn">
                    	<td colspan="8">
                        	<span class="icon-synergy-add-dark small-icon" style="padding-top:10px;"></span>
   								Add a stage to this schedule
                                <input type="hidden" name="scrollins" id="scrollins" />
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
                                   <input type="button" class="darkButton" id="btnstep" style="width:200px; height:42px;float:right;" value="Save Schedule" onclick="fn_savetriadinsschedule('triadsch',<?php echo $flag;?>,'save');"/> <?php } ?> &nbsp;&nbsp; <input type="button" class="darkButton" id="btnstep" style="width:200px;margin-right:10px; height:42px;float:right;" value="View Schedule" onclick="fn_savetriadinsschedule('triadsch',<?php echo $flag;?>,'view');"/>
                                </div>
                            </div>
                          
                            
                            <input type="hidden" name="insscheduleid" id="insscheduleid" value="<?php echo $sid;?>" />
                            <input type="hidden" name="triadtableflag" id="triadtableflag" value="<?php echo $triadtableflag;?>" />
                            
	<?php
	}

	/* Load stage */
	if($oper == "loadstage" and $oper != '')
	{		
		$stageval= isset($method['stageval']) ? $method['stageval'] : '0';
		$sid= isset($method['sid']) ? $method['sid'] : '0';
		
		$count=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_class_triad_schedule_insstagemap WHERE fld_stagevalue='".$stageval."' AND fld_stagetype='3' AND fld_schedule_id='".$sid."' and fld_startdate<>'0000-00-00' and fld_enddate<>'0000-00-00'  AND fld_flag='1'");
		
		$countled=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_class_triad_schedule_insstagemap WHERE fld_stagevalue='".$stageval."' AND fld_stagetype='1' AND fld_schedule_id='".$sid."' and fld_startdate<>'0000-00-00' and fld_enddate<>'0000-00-00'  AND fld_flag='1'");
		
		$countorientation=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_class_triad_schedule_insstagemap WHERE fld_stagevalue='".$stageval."' AND fld_stagetype='2' AND fld_schedule_id='".$sid."' and fld_startdate<>'0000-00-00' and fld_enddate<>'0000-00-00' AND fld_flag='1'");
		
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
                                                </a>~fail
    <?php
		}
		else
		{
		?>
    	
        <input type="hidden" name="stagetype" id="stagetype" value="<?php echo $stagetypeid;?>"  onchange="fn_loaddefinetriad()" />
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
                                                                    <li><a tabindex="-1" href="#" data-option="1">Teacher Led</a></li>
                                                                    <?php
																		}
																		if($stageval==2 AND $countorientation!=1)
																		{
																		?>
                                                                    <li><a tabindex="-1" href="#" data-option="2">Orientation</a></li>
                                                                    	<?php
																		}
																		if($count!=1)
																		{
																		?>
                                                                    <li><a tabindex="-1" href="#" data-option="3">Triad Rotation</a></li>
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
	
	/* delete triad instructions*/
	if($oper == "deleteinstructions" and $oper != '')
	{		
		$insid = isset($method['insid']) ? $method['insid'] : '0';
		$ObjDB->NonQuery("UPDATE itc_class_triad_schedule_insstagemap SET fld_flag='0',fld_deleteddate='".date("Y-m-d H:i:s")."',fld_deletedby='".$uid."' WHERE fld_id='".$insid."'");
	}
	
	/*delete define triads*/
	if($oper == "deletedefinetriad" and $oper != '')
	{		
		$triadid = isset($method['triadid']) ? $method['triadid'] : '0';
		$ObjDB->NonQuery("UPDATE itc_class_definetriads SET fld_delstatus='1',fld_deleteddate='".date("Y-m-d H:i:s")."',fld_deletedby='".$uid."'  WHERE fld_id='".$triadid."'");
		$ObjDB->NonQuery("UPDATE itc_class_triad_schedule_modulemapping SET fld_flag='0' WHERE fld_triad_id='".$triadid."'");
	}
	
	/*Check triad stage*/
	if($oper == "checkstage" and $oper != '')
	{		
		$sid = isset($method['sid']) ? $method['sid'] : '0';
		$count=0;
		$counttriad=0;
		
		$qrystage=$ObjDB->QueryObject("SELECT count(a.fld_id) as count,count(b.fld_id) as counttriad 
		       FROM itc_class_triad_schedule_insstagemap AS a 
			   LEFT JOIN itc_class_definetriads AS b ON b.fld_schedule_id='".$sid."'
			   WHERE a.fld_stagetype='3' and a.fld_schedule_id='".$sid."' AND a.fld_flag=1 AND a.fld_startdate<>'0000-00-00' AND a.fld_enddate<>'0000-00-00'               AND b.fld_delstatus='0'");
			   
			   if($qrystage->num_rows>0)
			   {
				   $row=$qrystage->fetch_assoc();
				   extract($row);
			   }
		
		echo $count."~".$counttriad;
	}
	
	if($oper == "checkstageins" AND $oper != '')
	{		
		$sid = isset($method['sid']) ? $method['sid'] : '0';
		$stagevalue = isset($method['stagevalue']) ? $method['stagevalue'] : '0';
		$stagetype = isset($method['stagetype']) ? $method['stagetype'] : '0';
		$stageid = isset($method['stageid']) ? $method['stageid'] : '0';
		
		
			$count=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_class_triad_schedule_insstagemap WHERE fld_stagetype='".$stagetype."' AND fld_schedule_id='".$sid."' AND fld_stagevalue='".$stagevalue."' AND fld_flag=1 AND fld_startdate<>'0000-00-00' AND fld_enddate<>'0000-00-00'");
		
		
		echo $count;
	}
	
	/* Fill the datas to define triad fields*/
	if($oper == "showdefinetriad" and $oper != '')
	{		
			$scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '0';
			$triadid = isset($method['triadid']) ? $method['triadid'] : '0';
			$data='';
			$triadname=$ObjDB->SelectSingleValue("SELECT fld_name FROM itc_class_definetriads WHERE fld_id='".$triadid."' AND fld_delstatus='0' order by fld_id ASC");
			$data=$triadname;
			
			$qrymod=$ObjDB->NonQuery("SELECT a.fld_id as moduleid,fn_shortname(CONCAT(a.fld_module_name,' ',b.fld_version),1) AS shortname, CONCAT(a.fld_module_name,' ',b.fld_version) as modulename 
							               FROM itc_module_master AS a
										   LEFT JOIN itc_module_version_track AS b ON b.fld_mod_id=a.fld_id 
										   LEFT JOIN itc_class_triad_schedule_modulemapping AS c ON a.fld_id=c.fld_module_id 
										   WHERE c.fld_schedule_id='".$scheduleid."' AND  c.fld_triad_id='".$triadid."' AND c.fld_flag=1 AND b.fld_delstatus='0' AND                                            a.fld_delstatus='0'");
			
			
			
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
		$enddate = isset($method['enddate']) ? $method['enddate'] : '0';
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
		$triadid = isset($method['triadid']) ? $method['triadid'] : '0';
		$triadname = isset($method['triadname']) ? $method['triadname'] : '0';
		$rotation = isset($method['rotation']) ? $method['rotation'] : '0';
		$adjustflag=isset($method['adflag']) ? $method['adflag'] : '0';
		$ttriad=isset($method['ttriad']) ? $method['ttriad'] : '0';
		$tempflag=isset($method['tempflag']) ? $method['tempflag'] : '0';
		$tempid=isset($method['tempid']) ? $method['tempid'] : '0';
	
		
		$students = explode(',',$students);
		$modules = explode(',',$modules);
		$unstudents = explode(',',$unstudents);
		$stagedettemp = explode(',',$stagedet);
		$ttriad=explode(',',$ttriad);
		
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
					$check = $ObjDB->SelectSingleValueInt("SELECT COUNT(*) FROM itc_license_assign_student AS a LEFT JOIN itc_user_master AS b ON a.fld_student_id=b.fld_id WHERE a.fld_license_id='".$licenseid."' AND a.fld_student_id='".$fld_student_id."' AND a.fld_flag='1' AND b.fld_delstatus='0'");
					
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
					
					$studentcount = $ObjDB->SelectSingleValueInt("SELECT SUM(o.cnt) FROM (SELECT COUNT(a.fld_id) AS cnt 
					                FROM itc_class_sigmath_student_mapping AS a 
									LEFT JOIN itc_class_sigmath_master AS b ON a.fld_sigmath_id=b.fld_id 
									WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                    UNION ALL 
                                    SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_schedule_student_mappingtemp AS a 
									LEFT JOIN itc_class_rotation_schedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
									WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                    UNION ALL 
                                    SELECT COUNT(a.fld_id) AS cnt FROM itc_class_dyad_schedule_studentmapping AS a 
									LEFT JOIN itc_class_dyad_schedulemaster AS b ON a.fld_schedule_id=b.fld_id 
									WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."' 
                                    UNION ALL 
                                    SELECT COUNT(a.fld_id) AS cnt FROM itc_class_triad_schedule_studentmapping AS a 
									LEFT JOIN itc_class_triad_schedulemaster AS b ON a.fld_schedule_id=b.fld_id 
									WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'  AND a.fld_schedule_id<>'".$sid."'
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
					
					$ObjDB->NonQuery("UPDATE itc_class_triad_schedule_studentmapping SET fld_flag='0',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$unstudents[$i]."'");
					
					$ObjDB->NonQuery("UPDATE itc_class_triad_schedulegriddet SET fld_flag='0',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$unstudents[$i]."'");
					
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
		
		if($flag==1){ //if student user availale for license
			if($sid!=0)
			{
				$ObjDB->NonQuery("UPDATE itc_class_triad_schedulemaster set fld_schedule_name='".$sname."',fld_student_type='".$studenttype."',fld_startdate='".date("Y-m-d",strtotime($startdate))."' WHERE fld_id='".$sid."'");
				
				$ObjDB->NonQuery("UPDATE itc_class_triad_schedulemaster SET fld_updated_date='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."', fld_license_id='".$licenseid."' WHERE fld_id='".$sid."'");
			
			}
			else
			{
				
				$sid=$ObjDB->NonQueryWithMaxValue("insert into itc_class_triad_schedulemaster (fld_class_id,fld_license_id,fld_schedule_name,fld_scheduletype,fld_student_type,fld_startdate,fld_created_date,fld_createdby) VALUES('".$classid."','".$licenseid."','".$sname."','".$scheduletype."','".$studenttype."','".date("Y-m-d",strtotime($startdate))."','".date("Y-m-d H:i:s")."','".$uid."')");
			
			}
			
			/* Student Mapping */
			
			$ObjDB->NonQuery("UPDATE itc_class_triad_schedule_studentmapping set fld_flag='0',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."'");
			
			for($i=0;$i<sizeof($students);$i++){
				$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_class_triad_schedule_studentmapping WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$students[$i]."'");
				if($cnt==0)
				{
					$ObjDB->NonQuery("INSERT INTO itc_class_triad_schedule_studentmapping(fld_schedule_id, fld_student_id,fld_flag,fld_createddate,fld_createdby) VALUES ('".$sid."', '".$students[$i]."','1','".date('Y-m-d H:i:s')."','".$uid."')");
				}
				else
				{
					$ObjDB->NonQuery("UPDATE itc_class_triad_schedule_studentmapping SET fld_flag='1',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$students[$i]."' AND fld_id='".$cnt."'");
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
			
			
			if($dyadflag=="addtriad")
			{
				
						if($triadid==0 or $triadid=="undefined")
						{
							$triadid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_class_definetriads(fld_schedule_id,fld_name,fld_createddate,fld_createdby)VALUES('".$sid."','".$triadname."','".date("Y-m-d H:i:s")."','".$uid."')");
						}
						else
						{
							$ObjDB->NonQuery("UPDATE itc_class_definetriads set fld_name='".$triadname."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_id='".$triadid."'");
						}
						
						$ObjDB->NonQuery("UPDATE itc_class_triad_schedule_modulemapping set fld_flag='0' WHERE fld_schedule_id='".$sid."' AND fld_triad_id='".$triadid."'");
					
					for($i=0;$i<sizeof($modules);$i++)
					{
						$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_class_triad_schedule_modulemapping WHERE fld_schedule_id='".$sid."' AND fld_triad_id='".$triadid."' AND fld_module_id='".$modules[$i]."'");
						
						if($cnt==0)
						{
							$ObjDB->NonQuery("INSERT INTO itc_class_triad_schedule_modulemapping(fld_triad_id,fld_schedule_id,fld_module_id,fld_flag) VALUES ('".$triadid."','".$sid."', '".$modules[$i]."','1')");
						}
						else
						{
							$ObjDB->NonQuery("UPDATE itc_class_triad_schedule_modulemapping SET fld_flag='1' WHERE fld_schedule_id='".$sid."' AND fld_module_id='".$modules[$i]."' AND fld_id='".$cnt."'");
						}
					}
			}
						
			
			
			if(($dyadflag=="ins" or $dyadflag=="triadsch" or $dyadflag=="addtriad") and $tempflag=='0')
			{
				$countins=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_class_triad_schedule_insstagemap WHERE fld_schedule_id='".$sid."'");
				
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
						else if($edet[2]=="Triad Rotation")
						{
							$val=3;
						}
						
						if($edet[0]!='0' and $val!='')
						{
						
						$ObjDB->NonQuery("INSERT INTO itc_class_triad_schedule_insstagemap(fld_schedule_id,fld_stagevalue,fld_stagetype,fld_stagename,fld_createddate,fld_createdby)VALUES('".$sid."','".$edet[0]."','".$val."','".$edet[1]."','".date("Y-m-d H:i:s")."','".$uid."')");
						}
					}
				}
				
				if($instype=="create")
				{
					$insstageid=$ObjDB->SelectSingleValueInt("SELECT fld_id from itc_class_triad_schedule_insstagemap where fld_schedule_id='".$sid."' and fld_stagevalue='".$stagevalue."' and fld_stagetype='".$stagetype."'");
					
					if($insstageid==0)
					{
						 $insstageid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_class_triad_schedule_insstagemap(fld_schedule_id,fld_stagevalue,fld_stagetype,fld_stagename,fld_startdate,fld_enddate,fld_orientationmod,fld_numberofrotation,fld_createddate,fld_createdby)VALUES('".$sid."','".$stagevalue."','".$stagetype."','".$stagename."','".date("Y-m-d",strtotime($distartdate))."','".date("Y-m-d",strtotime($dienddate))."','".$orientationmod."','".$rotation."','".date("Y-m-d H:i:s")."','".$uid."')");
						
						if($stagetype==3)
						{ 
						$rotcount=$ObjDB->SelectSingleValueInt("SELECT sum(fld_numberofrotation) FROM itc_class_triad_schedule_insstagemap WHERE fld_schedule_id='".$sid."' AND fld_id<'".$insstageid."' AND fld_flag='1' AND fld_numberofrotation<>'0'");
						 
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
							
							
							 $ObjDB->NonQuery("INSERT INTO itc_class_triad_stagerotmapping(fld_schedule_id,fld_stageid,fld_rotation,fld_startdate,fld_enddate,fld_createddate,fld_createdby)VALUES('".$sid."','".$insstageid."','".$i."','".$startdate."','".$tempenddate."','".date("Y-m-d H:i:s")."','".$uid."')");
						 }
					  }
					}
					else
					{
						$ObjDB->NonQuery("UPDATE itc_class_triad_schedule_insstagemap set fld_schedule_id='".$sid."',fld_stagevalue='".$stagevalue."',fld_stagetype='".$stagetype."',fld_stagename='".$stagename."',fld_startdate='".date("Y-m-d",strtotime($distartdate))."',fld_enddate='".date("Y-m-d",strtotime($dienddate))."',fld_orientationmod='".$orientationmod."',fld_numberofrotation='".$rotation."',fld_adjacentflag='".$adjustflag."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."',fld_flag='1' WHERE fld_schedule_id='".$sid."' AND fld_stagevalue='".$stagevalue."' AND fld_stagetype='".$stagetype."'");
						
						if($stagetype==3)
						{
						$ObjDB->NonQuery("UPDATE itc_class_triad_stagerotmapping SET fld_active='0' WHERE fld_schedule_id='".$sid."' AND fld_stageid='".$insstageid."'");
						 
						 $rotcount=$ObjDB->SelectSingleValueInt("SELECT sum(fld_numberofrotation) FROM itc_class_triad_schedule_insstagemap WHERE fld_schedule_id='".$sid."' AND fld_id<'".$insstageid."' AND fld_flag='1' AND fld_numberofrotation<>'0'");
						 
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
							 
							 $checkrotcount= $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_class_triad_stagerotmapping WHERE fld_schedule_id='".$sid."'  AND fld_rotation='".$i."' AND fld_stageid='".$insstageid."'");
							 
							 if($checkrotcount==0)
							 {                                      
								 $ObjDB->NonQuery("INSERT INTO itc_class_triad_stagerotmapping(fld_schedule_id,fld_stageid,fld_rotation,fld_startdate,fld_enddate,fld_createddate,fld_createdby)VALUES('".$sid."','".$insstageid."','".$i."','".$startdate."','".$tempenddate."','".date("Y-m-d H:i:s")."','".$uid."')");
							 }
							 else
							 {
								$ObjDB->NonQuery("UPDATE itc_class_triad_stagerotmapping SET fld_stageid='".$insstageid."',fld_startdate='".$startdate."',fld_enddate='".$tempenddate."',fld_active='1',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."'  AND fld_rotation='".$i."' AND fld_stageid='".$insstageid."'"); 
							 }
						 }
						 
						  $ObjDB->NonQuery("UPDATE itc_class_triad_schedule_insstagemap SET fld_enddate='".$tempenddate."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_id='".$insstageid."'");
						  
						  $qrygetrot=$ObjDB->QueryObject("SELECT fld_id FROM itc_class_triad_stagerotmapping WHERE fld_schedule_id='".$sid."' AND fld_stageid>'".$insstageid."' AND fld_active='1' ORDER BY fld_id ASC");
						 
						 if($qrygetrot->num_rows>0)
						 {
							 while($row=$qrygetrot->fetch_assoc())
							 {
								 $rotcount++;
								 extract($row);
								 
								 $ObjDB->NonQuery("UPDATE itc_class_triad_stagerotmapping SET fld_rotation='".$rotcount."' WHERE fld_id='".$fld_id."'");
							 }
							 
						 }
					   }
					}
						
					
				}
				else
				{
					
					if($insstageid==0)
					{
						
						$insstageid=$ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_class_triad_schedule_insstagemap WHERE fld_schedule_id='".$sid."' AND fld_stagevalue='".$stagevalue."' AND fld_stagetype='".$stagetype."'");
						
						
					$ObjDB->NonQuery("UPDATE itc_class_triad_schedule_insstagemap set fld_schedule_id='".$sid."',fld_stagevalue='".$stagevalue."',fld_stagetype='".$stagetype."',fld_stagename='".$stagename."',fld_startdate='".date("Y-m-d",strtotime($distartdate))."',fld_enddate='".date("Y-m-d",strtotime($dienddate))."',fld_orientationmod='".$orientationmod."',fld_numberofrotation='".$rotation."',fld_adjacentflag='".$adjustflag."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."',fld_flag='1' WHERE fld_schedule_id='".$sid."' AND fld_stagevalue='".$stagevalue."' AND fld_stagetype='".$stagetype."'");
					
					if($stagetype==3)
					{
						$ObjDB->NonQuery("UPDATE itc_class_triad_stagerotmapping SET fld_active='0' WHERE fld_schedule_id='".$sid."' AND fld_stageid='".$insstageid."'");
						 
						 $rotcount=$ObjDB->SelectSingleValueInt("SELECT sum(fld_numberofrotation) FROM itc_class_triad_schedule_insstagemap WHERE fld_schedule_id='".$sid."' AND fld_id<'".$insstageid."' AND fld_flag='1' AND fld_numberofrotation<>'0'");
						 
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
							 
							 $checkrotcount= $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_class_triad_stagerotmapping WHERE fld_schedule_id='".$sid."'  AND fld_rotation='".$i."' AND fld_stageid='".$insstageid."'");
							 
							 if($checkrotcount==0)
							 {                                      
								 $ObjDB->NonQuery("INSERT INTO itc_class_triad_stagerotmapping(fld_schedule_id,fld_stageid,fld_rotation,fld_startdate,fld_enddate,fld_createddate,fld_createdby)VALUES('".$sid."','".$insstageid."','".$i."','".$startdate."','".$tempenddate."','".date("Y-m-d H:i:s")."','".$uid."')");
							 }
							 else
							 {
								$ObjDB->NonQuery("UPDATE itc_class_triad_stagerotmapping SET fld_stageid='".$insstageid."',fld_startdate='".$startdate."',fld_enddate='".$tempenddate."',fld_active='1',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."'  AND fld_rotation='".$i."' AND fld_stageid='".$insstageid."'"); 
							 }
						 }
						 
						  $ObjDB->NonQuery("UPDATE itc_class_triad_schedule_insstagemap SET fld_enddate='".$tempenddate."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_id='".$insstageid."'");
						 
						  $qrygetrot=$ObjDB->QueryObject("SELECT fld_id FROM itc_class_triad_stagerotmapping WHERE fld_schedule_id='".$sid."' AND fld_stageid>'".$insstageid."' AND fld_active='1' ORDER BY fld_id ASC");
						 
						 if($qrygetrot->num_rows>0)
						 {
							 while($row=$qrygetrot->fetch_assoc())
							 {
								 $rotcount++;
								 extract($row);
								 
								 $ObjDB->NonQuery("UPDATE itc_class_triad_stagerotmapping SET fld_rotation='".$rotcount."' WHERE fld_id='".$fld_id."'");
							 }
							 
						 }
					  }
					}
					else
					{
						
						$ObjDB->NonQuery("UPDATE itc_class_triad_schedule_insstagemap set fld_schedule_id='".$sid."',fld_stagevalue='".$stagevalue."',fld_stagetype='".$stagetype."',fld_stagename='".$stagename."',fld_startdate='".date("Y-m-d",strtotime($distartdate))."',fld_enddate='".date("Y-m-d",strtotime($dienddate))."',fld_orientationmod='".$orientationmod."',fld_numberofrotation='".$rotation."',fld_adjacentflag='".$adjustflag."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_flag='1',fld_updatedby='".$uid."' WHERE fld_id='".$insstageid."'");
						
					if($stagetype==3)
					{
						 $ObjDB->NonQuery("UPDATE itc_class_triad_stagerotmapping SET fld_active='0' WHERE fld_schedule_id='".$sid."' AND fld_stageid='".$insstageid."'");
						 
						 $rotcount=$ObjDB->SelectSingleValueInt("SELECT sum(fld_numberofrotation) FROM itc_class_triad_schedule_insstagemap WHERE fld_schedule_id='".$sid."' AND fld_id<'".$insstageid."' AND fld_flag='1' AND fld_numberofrotation<>'0'");
						 
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
							 
							 $checkrotcount= $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_class_triad_stagerotmapping WHERE fld_schedule_id='".$sid."'  AND fld_rotation='".$i."' AND fld_stageid='".$fld_id."'");
							 
							 if($checkrotcount==0)
							 {                                      
								 $ObjDB->NonQuery("INSERT INTO itc_class_triad_stagerotmapping(fld_schedule_id,fld_stageid,fld_rotation,fld_startdate,fld_enddate,fld_createddate,fld_createdby)VALUES('".$sid."','".$insstageid."','".$i."','".$startdate."','".$tempenddate."','".date("Y-m-d H:i:s")."','".$uid."')");
							 }
							 else
							 {
								$ObjDB->NonQuery("UPDATE itc_class_triad_stagerotmapping SET fld_stageid='".$insstageid."',fld_startdate='".$startdate."',fld_enddate='".$tempenddate."',fld_active='1',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."'  AND fld_rotation='".$i."' AND fld_stageid='".$insstageid."'"); 
								
							 }
						 }
						 
						 $ObjDB->NonQuery("UPDATE itc_class_triad_schedule_insstagemap SET fld_enddate='".$tempenddate."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_id='".$insstageid."'");
						 
						  $qrygetrot=$ObjDB->QueryObject("SELECT fld_id FROM itc_class_triad_stagerotmapping WHERE fld_schedule_id='".$sid."' AND fld_stageid>'".$insstageid."' AND fld_active='1' ORDER BY fld_id ASC");
						 
						 if($qrygetrot->num_rows>0)
						 {
							 while($row=$qrygetrot->fetch_assoc())
							 {
								 $rotcount++;
								 extract($row);
								 
								 $ObjDB->NonQuery("UPDATE itc_class_triad_stagerotmapping SET fld_rotation='".$rotcount."' WHERE fld_id='".$fld_id."'");
							 }
							 
						 }
					   }
					}
				}
				
				if($adjustflag==1) 
						{
							$qrystage=$ObjDB->NonQuery("SELECT fld_id,fld_stagevalue,fld_stagetype,fld_numberofrotation FROM itc_class_triad_schedule_insstagemap WHERE fld_id>'".$insstageid."' AND fld_schedule_id='".$sid."' AND fld_startdate<>'0000-00-00' AND fld_enddate<>'0000-00-00' AND fld_flag='1' order by fld_id ASC");
							
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
									
									$ObjDB->NonQuery("UPDATE itc_class_triad_stagerotmapping SET fld_active='0' WHERE fld_schedule_id='".$sid."' AND fld_stageid='".$fld_id."'");
						 
									 $rotcount=$ObjDB->SelectSingleValueInt("SELECT sum(fld_numberofrotation) FROM itc_class_triad_schedule_insstagemap WHERE fld_schedule_id='".$sid."' AND fld_id<'".$fld_id."' AND fld_flag='1' AND fld_numberofrotation<>'0'");
									 
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
										 
										 $checkrotcount= $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_class_triad_stagerotmapping WHERE fld_schedule_id='".$sid."'  AND fld_rotation='".$i."' AND fld_stageid='".$fld_id."'");
										 
										 if($checkrotcount==0)
										 {                                      
											 $ObjDB->NonQuery("INSERT INTO itc_class_triad_stagerotmapping(fld_schedule_id,fld_stageid,fld_rotation,fld_startdate,fld_enddate,fld_createddate,fld_createdby)VALUES('".$sid."','".$fld_id."','".$i."','".$startdaterot."','".$tempenddaterot."','".date("Y-m-d H:i:s")."','".$uid."')");
										 }
										 else
										 {
											$ObjDB->NonQuery("UPDATE itc_class_triad_stagerotmapping SET fld_stageid='".$fld_id."',fld_startdate='".$startdaterot."',fld_enddate='".$tempenddaterot."',fld_active='1',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."'  AND fld_rotation='".$i."' AND fld_stageid='".$fld_id."'"); 
										 }
									 }
								
									$tempenddate=$tempenddaterot;
								
								}
								
								
								$ObjDB->NonQuery("UPDATE itc_class_triad_schedule_insstagemap set fld_startdate='".$startdate."',fld_enddate='".$tempenddate."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_id='".$fld_id."'");
							
							$z++;	
								
							}
						  }
						}
						
			}
			else if($tempflag=='1')
			{
				
					$exdyaddet=$ObjDB->QueryObject("SELECT a.fld_module_id as modid,b.fld_name as triadname 
					          FROM itc_class_triad_schedule_modulemapping AS a 
                              LEFT JOIN itc_class_definetriads AS b ON a.fld_triad_id=b.fld_id 
							  WHERE b.fld_schedule_id='".$tempid."' AND b.fld_delstatus=0 AND a.fld_flag='1'");
							 
							 if($exdyaddet->num_rows>0)
							 {
								$inc=1;
								while($rowesdyad=$exdyaddet->fetch_assoc())
								{
									extract($rowesdyad);
									
									if($inc==1 || ($inc%2==0 && $inc%3==1) || ($inc%2==1 && $inc%3==1))
									{
										$triadid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_class_definetriads(fld_schedule_id,fld_name,fld_createddate,fld_createdby)values('".$sid."','".$triadname."','".date("Y-m-d H:i:s")."','".$uid."')");
									}
									
									$ObjDB->NonQuery("INSERT INTO itc_class_triad_schedule_modulemapping(fld_triad_id,fld_schedule_id,fld_module_id,fld_flag) VALUES ('".$triadid."','".$sid."', '".$modid."','1')");
									
									$inc++;
								}
							 }
							 
					 $exinsstage=$ObjDB->QueryObject("SELECT fld_id as stageid,fld_stagevalue as stagevalue,fld_stagetype as stagetype,fld_startdate as startdate,fld_enddate as enddate,fld_orientationmod as orientationmod,fld_stagename as stagename,fld_numberofrotation as rotation,fld_adjacentflag as adjflag 
					             FROM itc_class_triad_schedule_insstagemap 
								 WHERE fld_schedule_id='".$tempid."' AND fld_flag='1'"); 
								 
							if($exinsstage->num_rows>0)
							 {
								while($rowexinsstage=$exinsstage->fetch_assoc())
								{
									extract($rowexinsstage);
									$id=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_class_triad_schedule_insstagemap(fld_schedule_id,fld_stagevalue,fld_stagetype,fld_stagename,fld_startdate,fld_enddate,fld_orientationmod,fld_numberofrotation,fld_adjacentflag,fld_createddate,fld_createdby)values('".$sid."','".$stagevalue."','".$stagetype."','".$stagename."','".$startdate."','".$enddate."','".$orientationmod."','".$rotation."','".$adjflag."','".date("Y-m-d H:i:s")."','".$uid."')");
									
									$rot=$ObjDB->QueryObject("SELECT fld_rotation,fld_startdate,fld_enddate FROM itc_class_triad_stagerotmapping WHERE fld_stageid='".$stageid."' AND fld_active=1 order by fld_id ASC");
									
									if($rot->num_rows>0)
									{
										while($row=$rot->fetch_assoc())
										{
											extract($row);
											
												$ObjDB->NonQuery("INSERT INTO itc_class_triad_stagerotmapping(fld_schedule_id,fld_stageid,fld_startdate,fld_enddate,fld_rotation,fld_createddate,fld_createdby)VALUES('".$sid."','".$id."','".$fld_startdate."','".$fld_enddate."','".$fld_rotation."','".date("Y-m-d H:i:s")."','".$uid."')");
											
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
	
	if($oper == "setenddate" AND $oper != '')
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
				$ObjDB->NonQuery("UPDATE itc_class_triad_stagerotmapping SET fld_startdate='".date("Y-m-d",strtotime($startdate))."',fld_enddate='".date("Y-m-d",strtotime($enddate))."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_id='".$id."'");
				
				$qrygetstageid=$ObjDB->QueryObject("SELECT fld_schedule_id,fld_stageid,fld_rotation FROM itc_class_triad_stagerotmapping WHERE fld_id='".$id."'");
				
				if($qrygetstageid->num_rows>0)
				{
					$row=$qrygetstageid->fetch_assoc();
					extract($row);
					$sid=$fld_schedule_id;
					$stageid=$fld_stageid;
				}
				
				$maxrotation=$ObjDB->SelectSingleValueInt("SELECT max(fld_rotation) FROM itc_class_triad_stagerotmapping WHERE fld_stageid='".$stageid."' AND fld_active='1'");
				
				$minrotation=$ObjDB->SelectSingleValueInt("SELECT min(fld_rotation) FROM itc_class_triad_stagerotmapping WHERE fld_stageid='".$stageid."' AND fld_active='1'");
				
				if($fld_rotation==$minrotation)
				{
					$ObjDB->NonQuery("UPDATE itc_class_triad_schedule_insstagemap SET fld_startdate='".date("Y-m-d",strtotime($startdate))."',fld_updateddate='".date("Y-m-d H:i:s")."' WHERE fld_id='".$fld_stageid."'");
				}
				
				if($fld_rotation==$maxrotation)
				{
					$ObjDB->NonQuery("UPDATE itc_class_triad_schedule_insstagemap SET fld_enddate='".date("Y-m-d",strtotime($enddate))."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_id='".$fld_stageid."'");
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
							 
						
							$ObjDB->NonQuery("UPDATE itc_class_triad_stagerotmapping SET fld_stageid='".$stageid."',fld_startdate='".$startdate."',fld_enddate='".$tempenddate."',fld_active='1',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."'  AND fld_rotation='".$i."' AND fld_stageid='".$stageid."'"); 
							if($i==$maxrotation)
							{
								$ObjDB->NonQuery("UPDATE itc_class_triad_schedule_insstagemap SET fld_enddate='".date("Y-m-d",strtotime($tempenddate))."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_id='".$stageid."'");
							}	
							
					}
					
					$qrystage=$ObjDB->NonQuery("SELECT fld_id,fld_stagevalue,fld_stagetype,fld_numberofrotation FROM itc_class_triad_schedule_insstagemap WHERE fld_id>'".$stageid."' AND fld_schedule_id='".$sid."' AND fld_startdate<>'0000-00-00' AND fld_enddate<>'0000-00-00' AND fld_flag='1' order by fld_id ASC");
							
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
									
									$ObjDB->NonQuery("UPDATE itc_class_triad_stagerotmapping SET fld_active='0' WHERE fld_schedule_id='".$sid."' AND fld_stageid='".$fld_id."'");
						 
									 $rotcount=$ObjDB->SelectSingleValueInt("SELECT sum(fld_numberofrotation) FROM itc_class_triad_schedule_insstagemap WHERE fld_schedule_id='".$sid."' AND fld_id<'".$fld_id."' AND fld_flag='1' AND fld_numberofrotation<>'0'");
									 
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
										 
										 $checkrotcount= $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_class_triad_stagerotmapping WHERE fld_schedule_id='".$sid."'  AND fld_rotation='".$i."' AND fld_stageid='".$fld_id."'");
										 
										 if($checkrotcount==0)
										 {                                      
											 $ObjDB->NonQuery("INSERT INTO itc_class_triad_stagerotmapping(fld_schedule_id,fld_stageid,fld_rotation,fld_startdate,fld_enddate,fld_createddate,fld_createdby)VALUES('".$sid."','".$fld_id."','".$i."','".$startdaterot."','".$tempenddaterot."','".date("Y-m-d H:i:s")."','".$uid."')");
										 }
										 else
										 {
											$ObjDB->NonQuery("UPDATE itc_class_triad_stagerotmapping SET fld_stageid='".$fld_id."',fld_startdate='".$startdaterot."',fld_enddate='".$tempenddaterot."',fld_active='1',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."'  AND fld_rotation='".$i."' AND fld_stageid='".$fld_id."'"); 
										 }
									 }
								
								$tempenddate=$tempenddaterot;
								
								}
								
								
								$ObjDB->NonQuery("UPDATE itc_class_triad_schedule_insstagemap set fld_startdate='".$startdate."',fld_enddate='".$tempenddate."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_id='".$fld_id."'");
							
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
				
					$numofrotation=$ObjDB->SelectSingleValueInt("SELECT fld_numberofrotation FROM itc_class_triad_schedule_insstagemap WHERE fld_id='".$stageid."'");
					
					if($numofrotation==6)
					{
						$ObjDB->NonQuery("UPDATE itc_class_triad_schedule_insstagemap SET fld_numberofrotation='3',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_id='".$stageid."'");
						
						$qrygetrot=$ObjDB->QueryObject("SELECT fld_id FROM itc_class_triad_stagerotmapping WHERE fld_schedule_id='".$sid."' AND fld_stageid='".$stageid."' AND fld_active='1' ORDER BY fld_id ASC LIMIT 3,6");
						
						if($qrygetrot->num_rows>0)
						{
							while($row=$qrygetrot->fetch_assoc())
							{
								extract($row);
								$ObjDB->NonQuery("UPDATE itc_class_triad_stagerotmapping SET fld_active='0' WHERE fld_id='".$fld_id."'");
							}
						}
						
						 $maxenddate= $ObjDB->NonQuery("SELECT MAX(fld_enddate) AS tempenddate,MAX(fld_rotation) AS rotcount FROM itc_class_triad_stagerotmapping WHERE fld_schedule_id='".$sid."' AND fld_stageid='".$stageid."' AND fld_active='1'");
						 
						 if($maxenddate->num_rows>0)
						 {
							 $row=$maxenddate->fetch_assoc();
							 extract($row);
						 }
						 
						 $ObjDB->NonQuery("UPDATE itc_class_triad_schedule_insstagemap SET fld_enddate='".$tempenddate."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_id='".$stageid."'");
						 
						 $qrygetrot=$ObjDB->QueryObject("SELECT fld_id FROM itc_class_triad_stagerotmapping WHERE fld_schedule_id='".$sid."' AND fld_stageid>'".$stageid."' AND fld_active='1' ORDER BY fld_id ASC");
						 
						 if($qrygetrot->num_rows>0)
						 {
							 while($row=$qrygetrot->fetch_assoc())
							 {
								 $rotcount++;
								 extract($row);
								 
								 $ObjDB->NonQuery("UPDATE itc_class_triad_stagerotmapping SET fld_rotation='".$rotcount."' WHERE fld_id='".$fld_id."'");
							 }
							 
						 }
					}
					else if($numofrotation==3)
					{
						$ObjDB->NonQuery("UPDATE itc_class_triad_schedule_insstagemap SET fld_numberofrotation='6',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_id='".$stageid."'");
						
						$rotcount=$ObjDB->SelectSingleValueInt("SELECT sum(fld_numberofrotation) FROM itc_class_triad_schedule_insstagemap WHERE fld_schedule_id='".$sid."' AND fld_id<='".$stageid."' AND fld_flag='1' AND fld_numberofrotation<>'0'");
						
						$tempenddate=$ObjDB->SelectSingleValue("SELECT max(fld_enddate) FROM itc_class_triad_stagerotmapping WHERE fld_schedule_id='".$sid."' AND fld_stageid='".$stageid."' AND fld_active='1'");
						
						
						
						 if($rotcount=='')
						 {
							 $rotcount=0;
						 }
						 
						 $count=$rotcount-2;
						 
						 for($i=$count;$i<=$rotcount;$i++)
						 {
							
							$startdate="";
							$startdate=date("Y-m-d",strtotime($tempenddate. "+1 weekdays"));
							$tempenddate="";
							
							
							$enddate=date("Y-m-d",strtotime($startdate. "+6 weekdays"));
							$tempenddate=$enddate;
							 
							 $checkrotcount= $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_class_triad_stagerotmapping WHERE fld_schedule_id='".$sid."'  AND fld_rotation='".$i."' AND fld_stageid='".$stageid."'");
							 
							 if($checkrotcount==0)
							 {                                      
								 $ObjDB->NonQuery("INSERT INTO itc_class_triad_stagerotmapping(fld_schedule_id,fld_stageid,fld_rotation,fld_startdate,fld_enddate,fld_createddate,fld_createdby)VALUES('".$sid."','".$stageid."','".$i."','".$startdate."','".$tempenddate."','".date("Y-m-d H:i:s")."','".$uid."')");
							 }
							 else
							 {
								$ObjDB->NonQuery("UPDATE itc_class_triad_stagerotmapping SET fld_stageid='".$stageid."',fld_startdate='".$startdate."',fld_enddate='".$tempenddate."',fld_active='1',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."'  AND fld_rotation='".$i."' AND fld_stageid='".$stageid."'"); 
								
							 }
						 }
						 
						 $ObjDB->NonQuery("UPDATE itc_class_triad_schedule_insstagemap SET fld_enddate='".$tempenddate."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_id='".$stageid."'");
						 
						 $qrygetrot=$ObjDB->QueryObject("SELECT fld_id FROM itc_class_triad_stagerotmapping WHERE fld_schedule_id='".$sid."' AND fld_stageid>'".$stageid."' ORDER BY fld_id ASC");
						 
						 if($qrygetrot->num_rows>0)
						 {
							 while($row=$qrygetrot->fetch_assoc())
							 {
								 $rotcount++;
								 extract($row);
								 
								 $ObjDB->NonQuery("UPDATE itc_class_triad_stagerotmapping SET fld_rotation='".$rotcount."' WHERE fld_id='".$fld_id."'");
							 }
							 
						 }
						
					}
				
					
					if($adjustflag==1) 
						{
							$qrystage=$ObjDB->NonQuery("SELECT fld_id,fld_stagevalue,fld_stagetype,fld_numberofrotation FROM itc_class_triad_schedule_insstagemap WHERE fld_id>'".$stageid."' AND fld_schedule_id='".$sid."' AND fld_startdate<>'0000-00-00' AND fld_enddate<>'0000-00-00' AND fld_flag='1' order by fld_id ASC");
							
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
									
									$ObjDB->NonQuery("UPDATE itc_class_triad_stagerotmapping SET fld_active='0' WHERE fld_schedule_id='".$sid."' AND fld_stageid='".$fld_id."'");
						 
									 $rotcount=$ObjDB->SelectSingleValueInt("SELECT sum(fld_numberofrotation) FROM itc_class_triad_schedule_insstagemap WHERE fld_schedule_id='".$sid."' AND fld_id<'".$fld_id."' AND fld_flag='1' AND fld_numberofrotation<>'0'");
									 
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
										 
										 $checkrotcount= $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_class_triad_stagerotmapping WHERE fld_schedule_id='".$sid."'  AND fld_rotation='".$i."' AND fld_stageid='".$fld_id."'");
										 
										 if($checkrotcount==0)
										 {                                      
											 $ObjDB->NonQuery("INSERT INTO itc_class_triad_stagerotmapping(fld_schedule_id,fld_stageid,fld_rotation,fld_startdate,fld_enddate,fld_createddate,fld_createdby)VALUES('".$sid."','".$fld_id."','".$i."','".$startdaterot."','".$tempenddaterot."','".date("Y-m-d H:i:s")."','".$uid."')");
										 }
										 else
										 {
											$ObjDB->NonQuery("UPDATE itc_class_triad_stagerotmapping SET fld_stageid='".$fld_id."',fld_startdate='".$startdaterot."',fld_enddate='".$tempenddaterot."',fld_active='1',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."'  AND fld_rotation='".$i."' AND fld_stageid='".$fld_id."'"); 
										 }
									 }
								
								$tempenddate=$tempenddaterot;
								
								}
								
								
								$ObjDB->NonQuery("UPDATE itc_class_triad_schedule_insstagemap set fld_startdate='".$startdate."',fld_enddate='".$tempenddate."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_id='".$fld_id."'");
							
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
				
				$stagerot=$ObjDB->QueryObject("SELECT fld_stagevalue as stagevalue,fld_stagetype as stagetype FROM `itc_class_triad_schedule_insstagemap` WHERE fld_id='".$stageid."' and fld_schedule_id='".$sid."' AND fld_flag='1'");
				
				if($stagerot->num_rows>0)
				{
					$row=$stagerot->fetch_assoc();
					extract($row);
				}
				
				$afstageid=$stageid+1;
				
				$belowstageidsdate=$ObjDB->SelectSingleValue("SELECT fld_startdate FROM itc_class_triad_schedule_insstagemap WHERE fld_id='".$afstageid."' AND fld_schedule_id='".$sid."' AND fld_flag='1'");
				
				$startdate=date("Y-m-d",strtotime($startdate));
				
				if($belowstageidsdate!='0000-00-00' and $belowstageidsdate!='')
				{
					if($startdate > $belowstageidsdate)
					{
						if($stagevalue==1 and $stagetype==1)
						{
							$msg="Orientation can not begin before Teacherled activity";
						}
						else if($stagevalue==2 and $stagetype==2)
						{
							$msg="Triad rotation can not begin before Orientation";
						}
						else if(($stagevalue==3 or $stagevalue==4 or $stagevalue==5) and $stagetype==3)
						{
							$msg="Triad rotation can not begin before Teacherled activity";
						}
						
					}
				}
				
					if($adjustflag==1 or $msg=="success")
					{
							$ObjDB->NonQuery("UPDATE itc_class_triad_schedule_insstagemap set fld_startdate='".date("Y-m-d",strtotime($startdate))."',fld_enddate='".date("Y-m-d",strtotime($enddate))."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_id='".$stageid."'");
							
					}
				
						if($adjustflag==1) 
						{
							$qrystage=$ObjDB->NonQuery("SELECT fld_id,fld_stagevalue,fld_stagetype,fld_numberofrotation FROM itc_class_triad_schedule_insstagemap WHERE fld_id>'".$stageid."' AND fld_schedule_id='".$sid."' AND fld_startdate<>'0000-00-00' AND fld_enddate<>'0000-00-00' AND fld_flag='1' order by fld_id ASC");
							
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
									
									$ObjDB->NonQuery("UPDATE itc_class_triad_stagerotmapping SET fld_active='0' WHERE fld_schedule_id='".$sid."' AND fld_stageid='".$fld_id."'");
						 
									 $rotcount=$ObjDB->SelectSingleValueInt("SELECT sum(fld_numberofrotation) FROM itc_class_triad_schedule_insstagemap WHERE fld_schedule_id='".$sid."' AND fld_id<'".$fld_id."' AND fld_flag='1' AND fld_numberofrotation<>'0'");
									 
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
										 
										 $checkrotcount= $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_class_triad_stagerotmapping WHERE fld_schedule_id='".$sid."'  AND fld_rotation='".$i."' AND fld_stageid='".$fld_id."'");
										 
										 if($checkrotcount==0)
										 {                                      
											 $ObjDB->NonQuery("INSERT INTO itc_class_triad_stagerotmapping(fld_schedule_id,fld_stageid,fld_rotation,fld_startdate,fld_enddate,fld_createddate,fld_createdby)VALUES('".$sid."','".$fld_id."','".$i."','".$startdaterot."','".$tempenddaterot."','".date("Y-m-d H:i:s")."','".$uid."')");
										 }
										 else
										 {
											$ObjDB->NonQuery("UPDATE itc_class_triad_stagerotmapping SET fld_stageid='".$fld_id."',fld_startdate='".$startdaterot."',fld_enddate='".$tempenddaterot."',fld_active='1',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."'  AND fld_rotation='".$i."' AND fld_stageid='".$fld_id."'"); 
										 }
									 }
								
								$tempenddate=$tempenddaterot;
								
								}
								
								
								$ObjDB->NonQuery("UPDATE itc_class_triad_schedule_insstagemap set fld_startdate='".$startdate."',fld_enddate='".$tempenddate."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_id='".$fld_id."'");
							
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
				$qrygetstagedate=$ObjDB->QueryObject("SELECT fld_startdate,fld_enddate FROM itc_class_triad_schedule_insstagemap WHERE fld_id='".$stageid."'");
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
				$qrygetstagedate=$ObjDB->QueryObject("SELECT fld_startdate,fld_enddate FROM itc_class_triad_stagerotmapping WHERE fld_id='".$stageid."'");
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
				
				$abovestageidsdate=$ObjDB->SelectSingleValue("SELECT fld_startdate FROM itc_class_triad_schedule_insstagemap WHERE fld_id='".$prestageid."' AND fld_schedule_id='".$sid."' AND fld_flag='1'");
				
				$belowstageidsdate=$ObjDB->SelectSingleValue("SELECT fld_startdate FROM itc_class_triad_schedule_insstagemap WHERE fld_id='".$afstageid."' AND fld_schedule_id='".$sid."' AND fld_flag='1'");
				
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
							$msg="above~Triad rotation can not begin before Orientation";
						}
						else if(($stagevalue==3 or $stagevalue==4 or $stagevalue==5) and $stagetype==1)
						{
							$msg="above~Teacherled activity can not begin before Triadrotation";
						}
						else if(($stagevalue==3 or $stagevalue==4 or $stagevalue==5) and $stagetype==3)
						{
							$msg="above~Triad rotation can not begin before Teacherled activity";
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
							$msg="below~Triad rotation can not begin before Orientation";
						}
						else if(($stagevalue==3 or $stagevalue==4 or $stagevalue==5) and $stagetype==1)
						{
							$msg="below~Teacherled activity can not begin before Triadrotation";
						}
						else if(($stagevalue==3 or $stagevalue==4 or $stagevalue==5) and $stagetype==3)
						{
							$msg="below~Triad rotation can not begin before Teacherled activity";
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
				
				$stagecount=$ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_class_triad_schedule_insstagemap WHERE fld_id>'".$stageid."' AND fld_schedule_id='".$sid."' AND fld_flag='1' AND fld_startdate<>'0000-00-00' limit 0,1");
				
				$msg='success~'.$stagecount;
				
				$abovestageidsdate=$ObjDB->SelectSingleValue("SELECT fld_startdate FROM itc_class_triad_schedule_insstagemap WHERE fld_id='".$prestageid."' AND fld_schedule_id='".$sid."' AND fld_flag='1'");
				
				$startdate=date("Y-m-d",strtotime($startdate));
				
				$stagefields=$ObjDB->QueryObject("SELECT fld_stagevalue as stagevalue,fld_stagetype as stagetype FROM itc_class_triad_schedule_insstagemap WHERE fld_id='".$stageid."' AND fld_schedule_id='".$sid."' AND fld_flag='1'");
				
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
							$msg="Triad rotation can not begin before Orientation~";
						}
						else if(($stagevalue==3 or $stagevalue==4 or $stagevalue==5) and $stagetype==1)
						{
							$msg="Teacherled activity can not begin before Triadrotation~";
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
				
				
				
				$stagerot=$ObjDB->QueryObject("SELECT fld_stageid as stageid,fld_rotation as rotation FROM `itc_class_triad_stagerotmapping` WHERE fld_id='".$id."' AND fld_active='1'");
				
				if($stagerot->num_rows>0)
				{
					$row=$stagerot->fetch_assoc();
					extract($row);
				}
				
				$stagecount=$ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_class_triad_schedule_insstagemap WHERE fld_id>'".$stageid."' AND fld_schedule_id='".$sid."' AND fld_flag='1' AND fld_startdate<>'0000-00-00' limit 0,1");
				
				$msg='success~'.$stagecount;
				
				$minrotation=$ObjDB->SelectSingleValue("SELECT min(fld_rotation) FROM `itc_class_triad_stagerotmapping` where fld_stageid='".$stageid."' and fld_schedule_id='".$sid."' and fld_active='1'");
				
				$startdate=date("Y-m-d",strtotime($startdate));
				
				$stagedet=$ObjDB->QueryObject("SELECT fld_startdate,fld_stagevalue,fld_stagetype FROM itc_class_triad_schedule_insstagemap WHERE fld_id<'".$stageid."' AND fld_schedule_id='".$sid."' AND fld_flag='1' AND fld_startdate<>'0000-00-00' order by fld_id desc limit 0,1");
				
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
								$msg="Triad rotation can not begin before Orientation~";
							}
							else
							{
								$msg="Triad rotation can not begin before Teacherled activity~";
							}
						}
					}
				}
				else
				{
					$prot=$rotation-1;
					$prestartdate=$ObjDB->SelectSingleValue("SELECT fld_startdate FROM itc_class_triad_stagerotmapping WHERE fld_stageid='".$stageid."' and fld_schedule_id='".$sid."' and fld_active='1' and fld_rotation='".$prot."'");
					
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
				$stagecount=$ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_class_triad_schedule_insstagemap WHERE fld_id>'".$stageid."' AND fld_schedule_id='".$sid."' AND fld_flag='1' AND fld_startdate<>'0000-00-00' limit 0,1");
				
				echo $stagecount;
			}
		}
	    catch(Exception $e)
	    {
		  echo "false";
	    }
	}





	if($oper == "generatetriadold" and $oper != '')
	{
		$numofmodules = isset($method['modules']) ? $method['modules'] : '0';

		 /** 
  * 1. build array position based on number of triads
  * 
  */

 	error_reporting(0);

	$no_of_triad = $numofmodules/3;

	$outerblock = array();
	$finalresult = array();

	for($i=0;$i<$no_of_triad;$i++){ //row
		for($j=0;$j<$no_of_triad;$j++){ //column
			$outerblock[] = "~~".$i."~".$j;
			$finalresult[] = "";
		}
	}

	function array_cleanup_main($array, $todelete) {

		foreach ($array as $key => $value) {

	    	if (array_key_exists($value, $todelete)) {
	            unset($todelete[$value]);
	        } 
	    }

	    return $todelete;
	}

	function array_cleanup($array, $todelete) {

		foreach ($array as $key => $value) {
	    	if (array_key_exists($value, $todelete)) {

	           	$tmpsrch = explode("~", $todelete[$value]);
	          	$searchword = $tmpsrch[2];
	          	$searchword1 = $tmpsrch[3];
	           	
	    		$todelete = array_filter($todelete, function($var) use ($searchword) { 	    			
					
					if(strrpos($var, $searchword,-3) == 2){
						return	false;
					}
					else {
						return true;
					}	
				});

	    		$todelete = array_filter($todelete, function($var) use ($searchword1) { 	    			
					
					if(strpos($var, $searchword1,3) == 4){
						return	false;
					}
					else {
						return true;
					}	
				});	
	           
	        } 
	    }

	    return $todelete;
	}


	/**
	filter pairs to get unique pair
	regarding $searchword, $searchword1
	*/

			function unique_pairs($stud_list, $searchword, $searchword1) {
			$set_pair = array_filter($stud_list, function($var) use ($searchword, $searchword1) {
			$a = strpos($var, $searchword);
			$b = strpos($var, $searchword1);
			if($a===0 or $a===1)
			$a=1;
			if($b===0 or $b===1)
			$b=1;
			return (!$a and !$b);
			});
			return $set_pair;
			}

	/**
	select random values from 15 set of elements
	split the 2 digit no. to single
	*/

			function split_twodigit($stud_list)
			{
				$shuf_students = array_rand($stud_list);
				$selectvalue = $stud_list[$shuf_students];
				$split_arr1 = explode("~", $selectvalue);

				$searchword =(string)$split_arr1[0];;
				$searchword1 =(string)$split_arr1[1];
				return array($searchword, $searchword1,$selectvalue);
			}

	/**
	set pairkeys(01,02,03,04,05,
	12,13,14,15,
	23,24,25,
	34,35,
	45)
	=> 15 set is made to each triad whether using increament or
	decreament number for 6 students.
	*/

			function alter_pairlist($s,$n)
			{
				$pairstud = array();
				if($s < $n)
				{
					for($i = $n; $i>=$s; $i--)
						for($x = $s; $x<=$n; $x++)
							if($i != $x && !isset($array[$x][$i]))
								$array[$i][$x] = '';
					
					/* @var $i type */
					for($i = $s; $i<=$n; $i++)
						for($x = $s; $x<=$n; $x++)
							if(isset($array[$i][$x]))
								array_push($pairstud,$i."~".$x);
					
					$stud_list = $pairstud;
				}
				else
				{
					for($i = $n; $i<=$s; $i++)
						for($x = $n; $x<=$s; $x++)
							if($i != $x && !isset($array[$x][$i]))
								$array[$i][$x] = '';
				
					for($i = $n; $i<=$s; $i++)
						for($x = $n; $x<=$s; $x++)
							if(isset($array[$i][$x]))
								array_push($pairstud,$i."~".$x);					
					$stud_list = $pairstud;
				}
				return $stud_list;
			}

		a:


		$tmpouterblock = $outerblock;
		$assignedpositionfinal = array();

		$start = 0;
		$end = 5;

		for($k=0;$k<$no_of_triad;$k++){
			

			if(!empty($assignedpositionfinal)) {
				$tmpouterblock = array_cleanup_main($assignedpositionfinal, $outerblock);	
			}

			$studentlist = array_slice($mainstudentlist, $start, $end);

			$assignedposition = array();
			$tmpinnerblock = array();
			for ($o=0; $o<$no_of_triad; $o++) { 

				$stud_list = alter_pairlist($start,$end);

				$contetstud = array();
				$row2_contetstud = array();
				$row3_contetstud = array();

				

				/* First row selection starts */

				for($l=0;$l<3;$l++)
				{
					if($cnt == 0)
					{
						$arr1 = split_twodigit($stud_list);
						$searchword = $arr1[0];;
						$searchword1 = $arr1[1];
						$selectvalue = $arr1[2];
						$unmatches =  unique_pairs($stud_list, $searchword, $searchword1);
					}
					
					if($cnt > 0){
						$arr1 = split_twodigit($unmatches);
						$searchword = $arr1[0];;
						$searchword1 = $arr1[1];
						$selectvalue = $arr1[2];
						$unmatches =  unique_pairs($unmatches, $searchword, $searchword1);
							
					}
					 array_push($contetstud,$selectvalue);
						$cnt++; 
						if($cnt == 3)
						$cnt = 0;
				}

				
			/* First row selection ends */

			/* Second row  & Third row selection starts */
				
				for($m=0;$m<3;$m++)
				{
					if($cnt == 0)
					{
					$arr1 = split_twodigit(array($contetstud[$m]));
					$searchword = $arr1[0];;
					$searchword1 = $arr1[1];
					$cellunmatches =  unique_pairs($stud_list, $searchword, $searchword1);
					$filterresults = array_diff($cellunmatches, $contetstud);
					$arr2 = split_twodigit($filterresults);
					$searchword = $arr2[0];;
					$searchword1 = $arr2[1];
					$selectvalue = $arr2[2];
					}
					if($cnt > 0)
					{
						if($cnt == 1)
						$arr1 = split_twodigit($row2_contetstud);
						if($cnt == 2)
						$arr1 = split_twodigit(array($row2_contetstud[0]));
						$searchword = $arr1[0];;
						$searchword1 = $arr1[1];
						$chkrow_contetstud =  unique_pairs($stud_list, $searchword, $searchword1);
						if($cnt == 1)
						{
						$arr1 = split_twodigit(array($contetstud[$m]));
						$searchword = $arr1[0];;
						$searchword1 = $arr1[1];
						}
						if($cnt == 2)
						{
							$arr2 = split_twodigit(array($row2_contetstud[1]));
							$searchword = $arr2[0];;
							$searchword1 = $arr2[1];
							$selectvalue = $arr2[2];
						}
						$cellunmatches =  unique_pairs($chkrow_contetstud, $searchword, $searchword1);
						if($cnt == 1)
						{
							$filterresults = array_diff($cellunmatches, $contetstud);
							$arr2 = split_twodigit($filterresults);
							$searchword = $arr2[0];;
							$searchword1 = $arr2[1];
							$selectvalue = $arr2[2];
							$row2_contetstud =  unique_pairs($row2_contetstud, $searchword, $searchword1);
						}
						if($cnt == 2)
						{
							foreach ($cellunmatches as $value) {
								$selectvalue = $value;
							}
						}
						
					}
						array_push($row2_contetstud,$selectvalue);
						$cnt++; 
						if($cnt == 3)
							$cnt = 0;		
				}


			/* Second row selection  and 
			  Third row selection starts */

				for($n=0;$n<3;$n++)
				{
					$Total_val = array_merge($contetstud, $row2_contetstud);
					$arr1 = split_twodigit(array($row2_contetstud[$n]));
						$searchword = $arr1[0];;
						$searchword1 = $arr1[1];
						//$selectvalue = $arr1[2];
						$chkrow_contetstud =  unique_pairs($stud_list, $searchword, $searchword1);
						$arr2 = split_twodigit(array($contetstud[$n]));
						$searchword = $arr2[0];;
						$searchword1 = $arr2[1];
						$selectvalue = $arr2[2];
						$chkrow_contetstud1 =  unique_pairs($chkrow_contetstud, $searchword, $searchword1);
						foreach ($chkrow_contetstud1 as $value) {
							$selectvalue = $value;
						}
					array_push($row3_contetstud,$selectvalue);
			
				}

			/* Third row selection ends */	
				
				$Result = array_merge($Total_val, $row3_contetstud);
				$Final_res = array_chunk($Result, 3);

				

				if($o == 0) {
					$tmpinnerblock = $tmpouterblock;
				}

				if (empty($tmpinnerblock)) {
					goto a;
					
				}

				

				$placeposition = array_rand($tmpinnerblock);
				


				$assignedposition[] = $placeposition;
				
				if(!empty($assignedposition)) {
					$tmpinnerblock = array_cleanup($assignedposition, $tmpouterblock);	
					
				}

			

					$finalresult[$placeposition] = $Final_res;	
					

			}	

			$assignedpositionfinal = array_merge((array)$assignedpositionfinal, (array)$assignedposition);

			$start = $end+1;
			$end = $end+6;
		}

		$it =  new RecursiveIteratorIterator(new RecursiveArrayIterator($finalresult));
		$l = iterator_to_array($it, false);

		echo json_encode($l);


	}

	if($oper == "generatetriad" and $oper != '')
	{
            $startrot = 1;
            $endrot = isset($method['rotation']) ? $method['rotation'] : '0';
            $module = isset($method['modules']) ? $method['modules'] : '0';
            $student = isset($method['student']) ? $method['student'] : '0';
            $end=$student-1;

		// Return Unique values
                function super_unique($array)
		{
			$result = array_map("unserialize", array_unique(array_map("serialize", $array)));
		
			foreach ($result as $key => $value)
			{
				if ( is_array($value) )
				{
					$result[$key] = super_unique($value);
				}
			}
		
			return array_values($result);
		}
		
		
            
                // if student exist from rotation(column) or modules(row) this function return false 
                function checkarray($arr1,$arr2,$val,$out,$nummod,$startrot,$endrot)
                {
                    $flagfirst="true";
                    $flagsecond="true";

                      for($i=$startrot;$i<=$endrot;$i++)
                      {
                          $array1='';
                          $array2='';
                          if($i!=$arr2) // second array value
                          {
                             $array1=$arr1.",".$i;

                              if($out[$array1][0]==$val OR $out[$array1][1]==$val) // first array 1,2 get 1
                              {

                                  $flagfirst="false";
                                  break;
                              }
                          }

                       }

                        for($i=1;$i<=$nummod;$i++)
                        {
                          $array1='';
                          $array2='';

                          $array2=$i.",".$arr2;
                              if($out[$array2][0]==$val OR $out[$array2][1]==$val) // first array 1,2 get 1
                              {
                                  $flagsecond="false";
                                  break;
                              }
                         }

                       if($flagfirst=="false" OR $flagsecond=="false")
                       {
                           return "false";
                       }
                       else 
                       {
                           return "true";
                       }
                }
             
                // if pair exist from table function return false
                function checkduppair($value,$sval,$out)
                {
                    $topval=$out[$value][0];
                    $bottomval=$sval;
                    $duppairflag="true";

                    foreach($out as $key => $val)
                    {
                        if(($val[0]==$topval and $val[1]==$bottomval) OR ($val[0]==$bottomval and $val[1]==$topval))
                        {
                            $duppairflag="false";
                            break;
                        }
                    }

                    if($duppairflag=="true")
                    {
                        return "true";
                    }
                    else
                    {
                        return "false";
                    }
                }
             
                // move array values
                function moveValueByIndex( array $array, $from=null, $to=null )
                {
                       if ( null === $from )
                       {
                         $from = count( $array ) - 1;
                       }

                       if ( !isset( $array[$from] ) )
                       {
                         throw new Exception( "Offset $from does not exist" );
                       }

                       if ( array_keys( $array ) != range( 0, count( $array ) - 1 ) )
                       {
                         throw new Exception( "Invalid array keys" );
                       }

                       $value = $array[$from];
                       unset( $array[$from] );

                       if ( null === $to )
                       {
                         array_push( $array, $value );
                       } 
                       else 
                       {
                         $tail = array_splice( $array, $to );
                         array_push( $array, $value );
                         $array = array_merge( $array, $tail );
                       }

                       return $array;
                 }
            
                $duppair=array();
		$modulearray = range('1',$module); // output [1,2,3]
		$rotationarray = range($startrot,$endrot); // output [1,2,3]
                
              
                
                foreach($rotationarray as $arrayVal)  // output ["1,1","1,2","1,3","2,1","2,2","2,3","3,1","3,2","3,3"]
		{
			foreach($modulearray as $arrayValue)
			{
                              
				 $duppair[]=$arrayValue.','.$arrayVal;
                                 $origin[]=$arrayValue.','.$arrayVal;
				
			}
                }
                
                
              
                $pairs=array();
                
                if($student>$module)
                {
                    $pairs=  $duppair; // Array ( [0] => 2,1 [1] => 1,1 [2] => 1,2 [3] => 2,2 [4] => 2,1 [5] => 1,1 [6] => 1,2 [7] => 2,2 )
                }
                else
                {
                    $pairs=$duppair; // Array ( [0] => 1,1 [1] => 2,1 [2] => 1,2 [3] => 2,2 ) 
                }
                
                $studentsorigin=range('1',$student); // output [1,2,3]
                
                shuffle($studentsorigin);
                $students=$studentsorigin; 
                
               
            
                
                   $count=0;
                   $loopcount=0;
                   $seatcount=$module*2;
                   $split=$seatcount/2;
                   $topcount=0;
                   $bottomcount=0;
                   $tempcount=array();
                   $array1=array();
                   $array2=array();
                   $cretry=0;
                   $bretry=0;
                   $dretry=0;
                   $zretry=0;
                   $forflag='';
                   $inc=1;
                   $inccount=0;
                   $triadflag='';
                   
                   
                    
                   c:
                   foreach($pairs as $key=>$value)   
                   {
                       $count++;
                       $inccount++;
                       
                       $temppairs[]=$value;
                       
                        if($value[1]!=',')
                        {
                            $firstarray=$value[0].$value[1];
                        }
                        else
                        {
                            $firstarray=$value[0];
                        }

                        if($value[1]==',')
                        {
                            $secondarray=$value[2].$value[3];
                        }
                        else
                        {
                            $secondarray=$value[3].$value[4];
                        }
                                
                         if($inc==1 OR ($inc%2==0 AND $inc%3==1) OR ($inc%2==1 AND $inc%3==1))
                         {
                            $incflag="true";
                            foreach($students as $keys=>$sval)
                            { 
                                if(empty($out[$value][0]) and checkarray($firstarray,$secondarray,$sval,$out,$module,$startrot,$endrot)=="true" and $value[0]!='')
                                 {
                                     $out[$value][0] = $sval;
                                     $outtemp[$value][0] = $sval;
                                     unset($students[$keys]);
                                     $topcount++;
                                     if($firstarray<=3)
                                     {
                                         $array1[]=$sval;
                                     }
                                     else if($firstarray>3 AND $firstarray<=6)
                                     {
                                         $array2[]=$sval;
                                     }
                                     else if($firstarray>6 AND $firstarray<=9)
                                     {
                                         $array3[]=$sval;
                                     }
                                     else if($firstarray>9 AND $firstarray<=12)
                                     {
                                         $array4[]=$sval;
                                     }
                                     else if($firstarray>12 AND $firstarray<=15)
                                     {
                                         $array5[]=$sval;
                                     }
                                     else if($firstarray>15 AND $firstarray<=18)
                                     {
                                         $array6[]=$sval;
                                     }
                                     else if($firstarray>18 AND $firstarray<=21)
                                     {
                                         $array7[]=$sval;
                                     }
                                     else if($firstarray>21 AND $firstarray<=24)
                                     {
                                         $array8[]=$sval;
                                     }
                                 }
                                 else if(empty($out[$value][1]) and ($out[$value][0]!=$sval) and checkarray($firstarray,$secondarray,$sval,$out,$module,$startrot,$endrot)=="true" and $value[1]!='' and checkduppair($value,$sval,$out)=="true")
                                 {
                                     $out[$value][1] = $sval;
                                     $outtemp[$value][1] = $sval;
                                     unset($students[$keys]);
                                     $bottomcount++;
                                     if($firstarray<=3)
                                     {
                                         $array1[]=$sval;
                                     }
                                     else if($firstarray>3 AND $firstarray<=6)
                                     {
                                         $array2[]=$sval;
                                     }
                                     else if($firstarray>6 AND $firstarray<=9)
                                     {
                                         $array3[]=$sval;
                                     }
                                     else if($firstarray>9 AND $firstarray<=12)
                                     {
                                         $array4[]=$sval;
                                     }
                                     else if($firstarray>12 AND $firstarray<=15)
                                     {
                                         $array5[]=$sval;
                                     }
                                     else if($firstarray>15 AND $firstarray<=18)
                                     {
                                         $array6[]=$sval;
                                     }
                                     else if($firstarray>18 AND $firstarray<=21)
                                     {
                                         $array7[]=$sval;
                                     }
                                     else if($firstarray>21 AND $firstarray<=24)
                                     {
                                         $array8[]=$sval;
                                     }
                                     break;
                                 }

                            } // foreach end
                           
                        } // if end
                        else
                        {
                                $incflag="false";
                                
                                if($firstarray<=3)
                                {
                                  $array=$array1;    
                                }
                                else if($firstarray>3 AND $firstarray<=6)
                                {
                                    $array=$array2; 
                                }
                                else if($firstarray>6 AND $firstarray<=9)
                                {
                                    $array=$array3; 
                                }
                                else if($firstarray>9 AND $firstarray<=12)
                                {
                                    $array=$array4; 
                                }
                                else if($firstarray>12 AND $firstarray<=15)
                                {
                                    $array=$array5;
                                }
                                else if($firstarray>15 AND $firstarray<=18)
                                {
                                    $array=$array6;
                                }
                                else if($firstarray>18 AND $firstarray<=21)
                                {
                                    $array=$array7;
                                }
                                else if($firstarray>21 AND $firstarray<=24)
                                {
                                    $array=$array8;
                                }
                                
                                foreach($array as $keys=>$sval)
                                { 
                                    if(empty($out[$value][0]) and checkarray($firstarray,$secondarray,$sval,$out,$module,$startrot,$endrot)=="true" and $value[0]!='')
                                     {
                                         $out[$value][0] = $sval;
                                         $outtemp[$value][0] = $sval;
                                         $topcount++;
                                         
                                     }
                                     else if(empty($out[$value][1]) and ($out[$value][0]!=$sval) and checkarray($firstarray,$secondarray,$sval,$out,$module,$startrot,$endrot)=="true" and $value[1]!='' and checkduppair($value,$sval,$outtemp)=="true")
                                     {
                                         $out[$value][1] = $sval;
                                         $outtemp[$value][1] = $sval;
                                         $bottomcount++;
                                         break;
                                     }
                                } // foreach $array1 end
                            
                        } //else end
                        
                        if($incflag=="false")
                        {
                            
                            if($inccount==3)
                            {
                                
                                $totstucount=$topcount+$bottomcount;
                                    
                                 if($totstucount!=sizeof($array))
                                 {
                                        $topcount=0;
                                        $bottomcount=0;
                                        $start=$firstarray-2;
                                      
                                        for($z=$start;$z<=$firstarray;$z++)
                                        {
                                            $arrayelement=$z.",".$secondarray;
                                            unset($out[$arrayelement][1]);
                                            unset($outtemp[$arrayelement][1]);
                                            unset($out[$arrayelement][0]);
                                            unset($outtemp[$arrayelement][0]);
                                        }
                                        
                                        z:
                                        foreach($temppairs as $key => $value)
                                        {
                                            if($value[1]!=',')
                                            {
                                                $firstarray=$value[0].$value[1];
                                            }
                                            else
                                            {
                                                $firstarray=$value[0];
                                            }

                                            if($value[1]==',')
                                            {
                                                $secondarray=$value[2].$value[3];
                                            }
                                            else
                                            {
                                                $secondarray=$value[3].$value[4];
                                            }
                                            foreach($array as $keys=>$sval)
                                            { 
                                                
                                                if(empty($out[$value][0]) and checkarray($firstarray,$secondarray,$sval,$out,$module,$startrot,$endrot)=="true" and $value[0]!='')
                                                 {
                                                     $out[$value][0] = $sval;
                                                     $outtemp[$value][0] = $sval;
                                                     $topcount++;

                                                 }
                                                 
                                                 if(empty($out[$value][1]) and ($out[$value][0]!=$sval) and checkarray($firstarray,$secondarray,$sval,$out,$module,$startrot,$endrot)=="true" and $value[1]!='' and checkduppair($value,$sval,$outtemp)=="true")
                                                 {
                                                     $out[$value][1] = $sval;
                                                     $outtemp[$value][1] = $sval;
                                                     $bottomcount++;
                                                     break;
                                                 }
                                                 
                                                 
                                            }
                                        }
                                        
                                        $totstucount=$topcount+$bottomcount;
                                        
                                        if($totstucount!=$student)
                                        {
                                               $zretry++;
                                               
                                               if($zretry<=300)
                                               {

                                                    $start=$firstarray-2;
                                      
                                                    for($z=$start;$z<=$firstarray;$z++)
                                                    {
                                                        $arrayelement=$z.",".$secondarray;
                                                        unset($out[$arrayelement][1]);
                                                        unset($outtemp[$arrayelement][1]);
                                                        unset($out[$arrayelement][0]);
                                                        unset($outtemp[$arrayelement][0]);
                                                    }
                                                  
                                                   $bottomcount=0;
                                                   $topcount=0;


                                                  $size='';
                                                  $size=sizeof($array)-1;
                                                  $array = moveValueByIndex($array, 0, $size );
                                                 
                                                  goto z;
                                                  
                                               }
                                               else
                                               {
                                                  $inccount=0;
                                                  $topcount=0;
                                                  $bottomcount=0;
                                                  $zretry=0;
                                                  unset($temppairs); 
                                                  $triadflag="false";
                                               }
                                        }
                                        else
                                        {
                                            $inccount=0;
                                            $topcount=0;
                                            $bottomcount=0;
                                            $zretry=0;
                                            unset($temppairs);
                                        }
                                 }
                                 else
                                 {
                                    $inccount=0;
                                    $topcount=0;
                                    $zretry=0;
                                    $bottomcount=0;
                                 }
                                
                            }
                        }
                        
                        if($count==$module)
                        {
                            
                                if($incflag=="true")
                                {
                                    
                                    $totstucount=$topcount+$bottomcount;
                                    
                                    if($totstucount!=$student)
                                    {
                                      
                                        unset($array1);
                                        unset($array2);
                                        unset($array3);
                                        unset($array4);
                                        unset($array5);
                                        unset($array6);
                                        unset($array7);
                                        unset($array8);
                                        
                                        for($z=1;$z<=$module;$z++)
                                        {
                                            $arrayelement=$z.",".$secondarray;
                                            unset($out[$arrayelement][1]);
                                            unset($out[$arrayelement][0]);
                                            unset($outtemp[$arrayelement][1]);
                                            unset($outtemp[$arrayelement][0]);
                                        }

                                        $topcount=0;
                                        $bottomcount=0;

                                        d:
                                        foreach($temppairs as $key => $value)
                                        {
                                            if($value[1]!=',')
                                            {
                                                $firstarray=$value[0].$value[1];
                                            }
                                            else
                                            {
                                                $firstarray=$value[0];
                                            }

                                            if($value[1]==',')
                                            {
                                                $secondarray=$value[2].$value[3];
                                            }
                                            else
                                            {
                                                $secondarray=$value[3].$value[4];
                                            }

                                            foreach($students as $keys=>$sval)
                                            { 


                                                if(empty($out[$value][0]) and checkarray($firstarray,$secondarray,$sval,$out,$module,$startrot,$endrot)=="true" and $value[0]!='')
                                                {
                                                    $out[$value][0] = $sval;
                                                    $outtemp[$value][0] = $sval;
                                                    $topcount++;
                                                    unset($students[$keys]);
                                                    if($firstarray<=3)
                                                    {
                                                        $array1[]=$sval;
                                                    }
                                                    else if($firstarray>3 AND $firstarray<=6)
                                                    {
                                                        $array2[]=$sval;
                                                    }
                                                    else if($firstarray>6 AND $firstarray<=9)
                                                    {
                                                        $array3[]=$sval;
                                                    }
                                                    else if($firstarray>9 AND $firstarray<=12)
                                                    {
                                                        $array4[]=$sval;
                                                    }
                                                    else if($firstarray>12 AND $firstarray<=15)
                                                    {
                                                        $array5[]=$sval;
                                                    }
                                                    else if($firstarray>15 AND $firstarray<=18)
                                                    {
                                                        $array6[]=$sval;
                                                    }
                                                    else if($firstarray>18 AND $firstarray<=21)
                                                    {
                                                        $array7[]=$sval;
                                                    }
                                                    else if($firstarray>21 AND $firstarray<=24)
                                                    {
                                                        $array8[]=$sval;
                                                    }

                                                }
                                                else if($out[$value][0]!=$sval and checkarray($firstarray,$secondarray,$sval,$out,$module,$startrot,$endrot)=="true" and $value[1]!=''  and (checkduppair($value,$sval,$out)=="true" OR $dretry>=80))
                                                {
                                                    $out[$value][1] = $sval;
                                                    $outtemp[$value][1] = $sval;
                                                    $bottomcount++;
                                                    unset($students[$keys]);
                                                    if($firstarray<=3)
                                                    {
                                                        $array1[]=$sval;
                                                    }
                                                    else if($firstarray>3 AND $firstarray<=6)
                                                    {
                                                        $array2[]=$sval;
                                                    }
                                                    else if($firstarray>6 AND $firstarray<=9)
                                                    {
                                                        $array3[]=$sval;
                                                    }
                                                    else if($firstarray>9 AND $firstarray<=12)
                                                    {
                                                        $array4[]=$sval;
                                                    }
                                                    else if($firstarray>12 AND $firstarray<=15)
                                                    {
                                                        $array5[]=$sval;
                                                    }
                                                    else if($firstarray>15 AND $firstarray<=18)
                                                    {
                                                        $array6[]=$sval;
                                                    }
                                                    else if($firstarray>18 AND $firstarray<=21)
                                                    {
                                                        $array7[]=$sval;
                                                    }
                                                    else if($firstarray>21 AND $firstarray<=24)
                                                    {
                                                        $array8[]=$sval;
                                                    }
                                                    
                                                     break;

                                                }
                                            }
                                        }

                                        $totstucount=$topcount+$bottomcount;

                                        if($totstucount!=$student)
                                        {
                                               $dretry++;
                                               unset($array1);
                                               unset($array2);
                                               unset($array3);
                                               unset($array4);
                                               unset($array5);
                                               unset($array6);
                                               unset($array7);
                                               unset($array8);
                                               
                                               if($dretry<=200)
                                               {

                                                   for($z=1;$z<=$module;$z++)
                                                   {
                                                       $arrayelement=$z.",".$secondarray;
                                                       unset($out[$arrayelement][1]);
                                                       unset($out[$arrayelement][0]);
                                                       unset($outtemp[$arrayelement][1]);
                                                       unset($outtemp[$arrayelement][0]);
                                                   }



                                                   $bottomcount=0;
                                                   $topcount=0;


                                                  $size='';
                                                  $size=sizeof($temppairs)-1;
                                                  $temppairs=super_unique($temppairs);
                                                  $temppairs= moveValueByIndex($temppairs, 0, $size );
                                                  shuffle($studentsorigin);
                                                  $students=$studentsorigin;

                                                  goto d;
                                                  unset($array1);
                                                  unset($array2);
                                                  unset($array3);
                                                  unset($array4);
                                                  unset($array5);
                                                  unset($array6);
                                                  unset($array7);
                                                  unset($array8);
                                               }
                                               else
                                               {
                                                  $topcount=0;
                                                  $bottomcount=0;
                                                  $dretry=0;
                                                  unset($temppairs); 
                                                  $triadflag="false";
                                               }
                                        }
                                        else
                                        {
                                             $topcount=0;
                                             $bottomcount=0;
                                             $dretry=0;
                                             unset($temppairs);
                                        }


                                    }  //totstucount if end
                                    else
                                    {
                                        unset($temppairs);
                                    }


                                } // incflag if end
                                
                                $count=0;
                                $inc++;
                                 if($inc==1 OR ($inc%2==0 AND $inc%3==1) OR ($inc%2==1 AND $inc%3==1))
                                 {
                                     unset($array1);
                                     unset($array2);
                                     unset($array3);
                                     unset($array4);
                                     unset($array5);
                                     unset($array6);
                                     unset($array7);
                                     unset($array8);
                                     unset($array);
                                     unset($outtemp);
                                 }
                                 
                                $studentsorigin = moveValueByIndex($studentsorigin, 0, $end );
                                $studentsorigin = moveValueByIndex($studentsorigin, 0, $end );
                                $students=$studentsorigin;
                                $topcount=0;
                                $bottomcount=0;
                                $inccount=0;
                                unset($temppairs);
                        }// If count end
                        
                } // pairs foreach end
                
               if($triadflag=="false")
               {
                   echo "false";
               }
               else
               {
                  echo json_encode($out);
               }


	}

	@include("footer.php");