<?php 
@include("sessioncheck.php");

$date = date("Y-m-d H:i:s");
$oper = isset($method['oper']) ? $method['oper'] : '';

/*****this opertion  performs to show the form ****/
if($oper=='deststmtform' and  $oper!='')
{
   	error_reporting(0);
	$exp_id= isset($method['exp_id']) ? $method['exp_id'] : '';
	$dest_id= isset($method['dest_id']) ? $method['dest_id'] : '0';
	$rowid= isset($method['rowid']) ? $method['rowid'] : '0';
	$rubid= isset($method['rubid']) ? $method['rubid'] : '0';
	$type= isset($method['type']) ? $method['type'] : '';
	$qryforgetdestdetails= isset($method['destname']) ? $method['destname'] : '';

	$empdestid= isset($method['empdestid']) ? $method['empdestid'] : '0';

	//new line
	$indrubricdet = isset($method['indrubricdet']) ? $method['indrubricdet'] : ''; 

	$rubdetailstemp = explode('^',$indrubricdet);

	$category = '';
	$four = '';
	$three = '';
	$two = '';
	$one = '';
	$zer = '';
	$weight = '';
	$score= '';
	
	if($type=='1')
	{

		$category = substr($rubdetailstemp[0],1);
		$four = substr($rubdetailstemp[1],1);
		$three = substr($rubdetailstemp[2],1);
		$two = substr($rubdetailstemp[3],1);
		$one = substr($rubdetailstemp[4],1);
		$zer = substr($rubdetailstemp[5],1);
		$wei = substr($rubdetailstemp[6],1);
		$weight = substr($wei, 1);
		$sco = substr($rubdetailstemp[7],1);
		$score= rtrim($sco, "<m>");
	}
	elseif($type=='2')
	{
		$category = substr($rubdetailstemp[0],1);
		$four = substr($rubdetailstemp[1],1);
		$three = substr($rubdetailstemp[2],1);
		$two = substr($rubdetailstemp[3],1);
		$one = substr($rubdetailstemp[4],1);
		$zer = substr($rubdetailstemp[5],1);
		$wei = substr($rubdetailstemp[6],1);
		$weight = substr($wei, 1);
		$sco = substr($rubdetailstemp[7],1);
		$score= rtrim($sco, "<m>");
	}
	elseif($type=='3')
	{
		$category = substr($rubdetailstemp[0],1);
		$four = substr($rubdetailstemp[1],1);
		$three = substr($rubdetailstemp[2],1);
		$two = substr($rubdetailstemp[3],1);
		$one = substr($rubdetailstemp[4],1);
		$zer = substr($rubdetailstemp[5],1);
		$wei = substr($rubdetailstemp[6],1);
		$weight = substr($wei, 1);
		$sco = substr($rubdetailstemp[7],1);
		$score= rtrim($sco, "<m>");
	}
        
//placeholders array
$placeholders = array('<br><br><br><br>','<br><br>','<br><br><br>','<br>');//'>', '<'
//replace values array
$replace = array('','','','');//'greater than', 'less than'
$category = str_replace($placeholders, $replace, $category);
$category = str_replace(',', '', $category);
$four = str_replace($placeholders, $replace, $four);
$three = str_replace($placeholders, $replace, $three);
$two = str_replace($placeholders, $replace, $two);
$one = str_replace($placeholders, $replace, $one);
$zer = str_replace($placeholders, $replace, $zer);      
        
        
          
        
	?>
<script type="text/javascript" charset="utf-8">		
    $.getScript("library/rubric/library-rubric.js");
</script>
    <style>textarea{width: 200px; max-width: 450px; max-height: 80px; }
     textarea{width:105%;}
    </style>
    <div class="eleven columns">
        <form name="expdestform" id="expdestform" >
            <div class="row" style="min-width:400px;">
               <center><span style="font-size:24px;" class="darkTitle"><?php echo $qryforgetdestdetails; ?></span></center>
            </div>
           <div class="row" style="min-width:400px;">  
               <div class="four columns">
                   Category : <span style="color:red" >*</span>
                   <dl class="field row">
                       <dt class='textarea'> 
                         <textarea placeholder='category' id="txtdestcategoryname" name="txtdestcategoryname" tabindex="1" ><?php echo $category; ?></textarea>
                       </dt> 
                   </dl>
               </div>
               <div class="four columns">
                   4 : <span style="color:red" >*</span>
                   <dl class="field row">
                      <dt class="text">
                      <textarea placeholder='4' id="txtdest4" name="txtdest4"   tabindex="2" ><?php echo $four; ?></textarea>
                      </dt> 
                   </dl>
               </div>
               <div class="four columns">
                   3 : <span style="color:red" >*</span>
                   <dl class="field row">
                      <dt class="text">
                      <textarea placeholder='3' id="txtdest3" name="txtdest3"  tabindex="3" ><?php echo $three;  ?></textarea>
                      </dt> 
                   </dl>
               </div>
           </div> 
        <div class="row" style="min-width:400px;">  
               <div class="four columns">
                   2 : <span style="color:red" >*</span>
                   <dl class="field row">
                       <dt class='textarea'>
                           <textarea placeholder='2' id="txtdest2" name="txtdest2"  tabindex="4"><?php echo htmlentities($two); ?></textarea>
                      </dt> 
                   </dl>
               </div>
               <div class="four columns">
                 1 :   <span style="color:red" >*</span>
                   <dl class="field row">
                      <dt class="text">
                      <textarea placeholder='1' id="txtdest1" name="txtdest1"  tabindex="5" ><?php echo htmlentities($one); ?></textarea>
                      </dt> 
                   </dl>
               </div>
               <div class="four columns">
                   0: <span style="color:red" >*</span>
                   <dl class="field row">
                      <dt class="text">
                      <textarea placeholder='0' id="txtdest0" name="txtdest0"  tabindex="6" ><?php echo htmlentities($zer); ?></textarea>
                      </dt> 
                   </dl>
               </div>
           </div> 
            
            <div class="row " style="min-width:400px;">  
               <div class="six columns">
                  Weight:  <span style="color:red" >*</span>
                    <dl class="field row">
                        <dt class='text'>
                            <input placeholder='Weight' type='text' name="txtdestweight" id="txtdestweight" value="<?php echo $weight; ?>"  tabindex="7" maxlength="3"  onchange="calculate()" onkeypress="return isNumber(event)" />
                        </dt>
                    </dl>
               </div>
               <div class="six columns">
                   Score : <span style="color:red" >*</span>
                   <dl class="field row">
                     <dt class='text'>
                     <input placeholder='Score' type='text' name="txtdestscore" id="txtdestscore" value="<?php echo $score; ?>" readonly=""  tabindex="8"   onchange="calculate()"/>
                    </dt> 
                   </dl>
               </div>
              
           </div> 
           
            <div class="row rowspacer" style="min-width:400px;">
               <div style="margin-left:<?php if($type==1){echo '129px'; }else {echo '135px';} ?>;margin-right:10px;" >
                    <?php if($type==1){ ?>
                         <input style="margin-right:10px;width:90px" onclick="fn_tempsavedestexpform1('<?php echo ($exp_id); ?>','<?php echo $dest_id; ?>','<?php echo $rowid; ?>','<?php echo $type; ?>','<?php echo $rubid; ?>','<?php echo $uid; ?>','<?php echo $rcount; ?>','<?php echo $empdestid; ?>','<?php echo $score; ?>','<?php echo $qryforgetdestdetails; ?>');" type="button" class="module-extend-button" id="saveextend_btn" value="Ok" /> 
                         <input style="margin-right:10px;width:90px" onclick="fn_deletedestexpform('<?php echo ($exp_id); ?>','<?php echo $dest_id; ?>','<?php echo $rowid; ?>','<?php echo $type; ?>','<?php echo $rubid; ?>','<?php echo $uid; ?>','<?php echo $score; ?>');" type="button" class="module-extend-button" id="saveextend_btn" value="Delete" /> 
                    <?php   } 
                    elseif($type==2){ ?>
                         <input style="margin-right:10px;width:90px" onclick="fn_tempsavedestexpform1('<?php echo ($exp_id); ?>','<?php echo $dest_id; ?>','<?php echo $rowid; ?>','<?php echo $type; ?>','<?php echo $rubid; ?>','<?php echo $uid; ?>','<?php echo $rcount; ?>','<?php echo $empdestid; ?>','<?php echo $score; ?>','<?php echo $qryforgetdestdetails; ?>');" type="button" class="module-extend-button" id="saveextend_btn" value="Ok" /> 
                         <input style="margin-right:10px;width:90px" onclick="fn_deletedestexpform('<?php echo ($exp_id); ?>','<?php echo $dest_id; ?>','<?php echo $rowid; ?>','<?php echo $type; ?>','<?php echo $rubid; ?>','<?php echo $uid; ?>','<?php echo $score; ?>');" type="button" class="module-extend-button" id="saveextend_btn" value="Delete" /> 
                    <?php   }
                    elseif($type==3){ ?>
                         <input style="margin-right:10px;width:90px" onclick="fn_tempsavedestexpform1('<?php echo ($exp_id); ?>','<?php echo $dest_id; ?>','<?php echo $rowid; ?>','<?php echo $type; ?>','<?php echo $rubid; ?>','<?php echo $uid; ?>','<?php echo $rcount; ?>','<?php echo $empdestid; ?>','<?php echo $score; ?>','<?php echo $qryforgetdestdetails; ?>');" type="button" class="module-extend-button" id="saveextend_btn" value="Ok" /> 
                         <input style="margin-right:10px;width:90px" onclick="fn_deletedestexpform('<?php echo ($exp_id); ?>','<?php echo $dest_id; ?>','<?php echo $rowid; ?>','<?php echo $type; ?>','<?php echo $rubid; ?>','<?php echo $uid; ?>','<?php echo $score; ?>');" type="button" class="module-extend-button" id="saveextend_btn" value="Delete" /> 
                    <?php   }
                    else
					{
                        $startval=$ObjDB->SelectSingleValueInt("SELECT fld_destination_id FROM itc_exp_rubric_master WHERE fld_exp_id='".$exp_id."' and fld_delstatus='0' and fld_rubric_id='".$rubid."'");
                       
                        if($startval == '')
						{
                            $startval=$ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_exp_rubric_dest_master WHERE fld_exp_id='".$exp_id."' AND fld_rubric_name_id='".$rubid."' and fld_delstatus='0' ");
					 	}
						else
						{
							$startval=0;
						}						
                         
                        $destcountempty=$ObjDB->SelectSingleValueInt("SELECT count(fld_destination_id) FROM itc_exp_rubric_master WHERE fld_exp_id='".$exp_id."' AND fld_destination_id='".$dest_id."' and fld_delstatus='0' and fld_rubric_id='".$rubid."'");
                        if($destcountempty=='0')
						{
                             ?>
                                  <input type="hidden" name="emptyhiddestcount" id="emptyhiddestcount" value=<?php echo "1";?> />
                            <?php
                        }
                        else
						{
                             ?>
                                  <input type="hidden" name="emptyhiddestcount" id="emptyhiddestcount" value=<?php echo "0";?> />
                            <?php
                         }
					 	if($startval == '')
						{
							$startval=0;
						}                      
                        $countdest=$ObjDB->QueryObject("SELECT fld_destination_id as destinationid FROM itc_exp_rubric_master WHERE fld_destination_id between $startval and $dest_id and fld_exp_id='".$exp_id."' and fld_delstatus='0' and fld_rubric_id='".$rubid."' group by fld_destination_id");
                        if($countdest->num_rows > 0){
                            while($row=$countdest->fetch_assoc()){
                                extract($row);
                                $destids[]=$destinationid;

                            }
                        }
                        ?>
                         <input style="margin-right:10px;width:90px" onclick="fn_tempsavedestexpform('<?php echo ($exp_id); ?>','<?php echo $dest_id; ?>','<?php echo $rowid; ?>','<?php echo $type; ?>','<?php echo $rubid; ?>','<?php echo $uid; ?>','<?php echo $empdestid; ?>','<?php echo $score; ?>','<?php echo $qryforgetdestdetails; ?>');" type="button" class="module-extend-button" id="saveextend_btn" value="Ok" /> 
                    	<?php 
					} 	?>
					<input type="button" style="width:90px" onclick="fn_canceldestform();" class="module-extend-button"   value="Cancel"   /> 
					<input type="hidden" name="hiddestcount" id="hiddestcount" value=<?php echo json_encode($destids);?> />
               </div>
           </div>
        </form>
    </div>
<script type="text/javascript" language="javascript" >
    $("#expdestform").validate({
    ignore: "",
    errorElement: "dd",
    errorPlacement: function(error, element) {
            $(element).parents('dl').addClass('error');
            error.appendTo($(element).parents('dl'));
            error.addClass('msg');
            window.scroll(0,($('dd').offset().top)-50);
    },
    rules: {
                txtdestcategoryname: { required: true },
                txtdest4: { required: true,},
                txtdest3: { required: true },
                txtdest2: { required: true},
                txtdest1: { required: true },
                txtdest0: { required: true },
                txtdestweight: { required: true },               

        },

        messages: {
                txtdestcategoryname: { required: "please enter category" },
                txtdest4: { required: "please enter 4" },
                txtdest3: { required: "please enter 3"},
                txtdest2: { required: "please enter 2" },
                txtdest1: { required: "please enter 1" },
                txtdest0: { required: "please enter 0"},
                txtdestweight: { required: "please enter weight" },                

        } ,
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

    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
               return false;
            }
            return true;
    }        

    function calculate(){
        document.expdestform.txtdestscore.value = 4 * (document.expdestform.txtdestweight.value -0);
    }                     
</script>
<?php  	
}

/*--- Delete the rubric statement ---*/
if($oper=="deletedestexpform" and $oper != " " )
{
    try
    {
		$expid= isset($method['exp_id']) ? $method['exp_id'] : '';
		$destid= isset($method['dest_id']) ? $method['dest_id'] : '0';
		$type= isset($method['type']) ? $method['type'] : '';
		$rowid= isset($method['rowid']) ? $method['rowid'] : '0';

		$validate_assetid=true;
		if($rowid!=0)  $validate_assetid=validate_datatype($rowid,'int');
		if($validate_assetid)			
			  $a=0;
		else
			echo "fail";	
    }
    catch(Exception $e)
    {
            echo "fail";
    }

    echo "success";
}

/*--- Save and Update the Rubric ---*/
if($oper=="saveasrubric")
{
    try
	{
        $expid = isset($method['expid']) ? $method['expid'] : '0'; 
        $rubname = isset($method['rubname']) ? $method['rubname'] : ''; 
        $rubricnameid = isset($method['rubricid']) ? $method['rubricid'] : '0'; 
        $rubricdet = isset($method['rubricdet']) ? $method['rubricdet'] : '';  //existing rubric statement:
        $addrubricdet = isset($method['addrubricdet']) ? $method['addrubricdet'] : ''; //Add aditional category :
       	$addrubricdet1 = isset($method['addrubricdet1']) ? $method['addrubricdet1'] : ''; //if a destination is empty :
        $updateid = isset($method['updatedid']) ? $method['updatedid'] : '0'; 
        $ownnrubname = isset($method['ownrubricname']) ? $method['ownrubricname'] : '';  
        $rubnametemp = explode('~',$rubricdet);
        $addrubnametemp = explode('~',$addrubricdet);
        $addempdestrubnametemp = explode('~',$addrubricdet1);
        
        if($updateid=='0')
		{
			$maxid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_exp_rubric_name_master (fld_rub_name, fld_exp_id, fld_created_by, fld_created_date, fld_district_id, fld_school_id, fld_user_id,fld_profile_id) 
											VALUES ('".$rubname."', '".$expid."', '".$uid."', '".$date."','".$sendistid."','".$schoolid."','".$indid."','".$sessmasterprfid."')");

        }
        else
		{
            $ObjDB->NonQuery("UPDATE itc_exp_rubric_name_master SET fld_rub_name='".$ownnrubname."', fld_updated_by='".$uid."', 
								 fld_updated_date='".$date."' WHERE fld_exp_id='".$expid."' AND fld_id='".$rubricnameid."'");
			
			$ObjDB->NonQuery("UPDATE itc_exp_rubric_dest_master 
								 SET fld_delstatus='1', fld_deleted_by='".$uid."', fld_deleted_date='".date("Y-m-d H:i:s")."'
								 WHERE fld_rubric_name_id='".$rubricnameid."' AND fld_exp_id='".$expid."'");
			
            $ObjDB->NonQuery("UPDATE itc_exp_rubric_master
								 SET fld_delstatus='1', fld_deleted_by='".$uid."', fld_deleted_date='".date("Y-m-d H:i:s")."'
								 WHERE fld_rubric_id='".$rubricnameid."' AND fld_exp_id='".$expid."'");
			
			$maxid=$rubricnameid;
        }

	/*************Existing rubric statement start***********/
        for($i=0;$i<sizeof($rubnametemp);$i++) 
        {
            $rtemp = explode('^',$rubnametemp[$i]);

			for($j=0;$j<sizeof($rtemp);$j++)
			{
				$category = substr($rtemp[0], 1);
				$four = substr($rtemp[1], 1);
				$three = substr($rtemp[2], 1);
				$two = substr($rtemp[3], 1);
				$one = substr($rtemp[4], 1);
				$zer = substr($rtemp[5], 1);
				$weig = substr($rtemp[6], 1);
				$weight = substr($weig, 1);
				$score = substr($rtemp[7], 1);
				$destid = substr($rtemp[8], 1);
				$rubrowid=substr($rtemp[9], 1);
				$destname=substr($rtemp[10], 1);
				
				$placeholders = array("<br />","<br>");
				//replace values array
				$replace = array("","");
				$category = str_replace($placeholders, $replace, $category);
				$four = str_replace($placeholders, $replace, $four);
				$three = str_replace($placeholders, $replace, $three);
				$two = str_replace($placeholders, $replace, $two);
				$one = str_replace($placeholders, $replace, $one);
				$zer = str_replace($placeholders, $replace, $zer);
				
			}

			if($destid!='')
			{
				if($destname!="undefined")
				{
					$destcount = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_exp_rubric_dest_master 
														WHERE fld_rubric_name_id='".$maxid."' AND fld_exp_id='".$expid."' 
														AND fld_dest_name='".$ObjDB->EscapeStr($destname)."' ");
					if($destcount==0)
					{
						$newrowdestid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_exp_rubric_dest_master (fld_rubric_name_id, fld_exp_id, fld_dest_name, fld_created_by, fld_created_date) 
								VALUES ('".$maxid."', '".$expid."', '".$ObjDB->EscapeStr($destname)."', '".$uid."', '".date("Y-m-d H:i:s")."')");

					}
					else
					{
						$ObjDB->NonQuery("UPDATE itc_exp_rubric_dest_master SET fld_dest_name='".$ObjDB->EscapeStr($destname)."',
										fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."',fld_delstatus='0' 
									WHERE fld_rubric_name_id='".$maxid."' AND fld_exp_id='".$expid."' AND fld_id='".$destcount."' ");//update already exist
						$newrowdestid=$destcount;
					}					
					if($updateid=='1')
					{
						if($category!="undefined")
						{
							$destcount = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_exp_rubric_master 
																		WHERE fld_rubric_id='".$maxid."' AND fld_exp_id='".$expid."' 
																		AND fld_destination_id='".$newrowdestid."'  ");
							if($destcount==0)
							{
								$newid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_exp_rubric_master ORDER BY fld_id DESC LIMIT 1");
								$newid=$newid+1;

								$newrowid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_exp_rubric_master (`fld_rubric_id`,`fld_exp_id`, `fld_destination_id`, `fld_category`, `fld_four`, `fld_three`, `fld_two`, `fld_one`, `fld_zer`, `fld_weight`, `fld_score`, `fld_created_by`, `fld_created_date`,`newid`) 
											VALUES ('".$maxid."', '".$expid."', '".$newrowdestid."', '".$category."', '".$four."', '".$three."', '".$two."', '".$one."', '".$zer."', '".$weight."', '".$score."', '".$uid."', '".date("Y-m-d H:i:s")."', '".$newid."')");//insert if not exist 

							}
							else
							{
								$ObjDB->NonQuery("UPDATE itc_exp_rubric_master 
											SET fld_category='".$category."', fld_four='".$four."', fld_three='".$three."', fld_two='".$two."',
											fld_one='".$one."', fld_zer='".$zer."', fld_weight='".$weight."', fld_score='".$score."',
											fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."', fld_delstatus='0' 
											WHERE fld_id='".$rubrowid."' AND fld_exp_id='".$expid."' AND fld_destination_id='".$newrowdestid."' ");//update already exist
							}
						}
					}
					else
					{
						if($category!="undefined")
						{
							$newid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_exp_rubric_master ORDER BY fld_id DESC LIMIT 1");
							$newid=$newid+1;

							$newrowid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_exp_rubric_master (`fld_rubric_id`,`fld_exp_id`, `fld_destination_id`, `fld_category`, `fld_four`, `fld_three`, `fld_two`, `fld_one`, `fld_zer`, `fld_weight`, `fld_score`, `fld_created_by`, `fld_created_date`,`newid`) 
																VALUES ('".$maxid."', '".$expid."', '".$newrowdestid."', '".$category."', '".$four."', '".$three."', '".$two."', '".$one."', '".$zer."', '".$weight."', '".$score."', '".$uid."', '".date("Y-m-d H:i:s")."', '".$newid."')");//insert if not exist 



							/*********Content Tagging Start*******/
							$tagcount = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_main_tag_mapping WHERE fld_item_id='".$rubrowid."' AND fld_tag_type='34' AND fld_access='1'");
							if($tagcount !='0')
							{
								$qrytag = $ObjDB->QueryObject("SELECT a.fld_id AS tagid
																	  FROM itc_main_tag_master AS a, itc_main_tag_mapping AS b 
																		  WHERE a.fld_id=b.fld_tag_id AND b.fld_tag_type='34' 
																				AND b.fld_access='1' 
																						AND a.fld_delstatus='0' AND b.fld_item_id='".$rubrowid."'");
								if($qrytag->num_rows > 0)
								{
									while($row=$qrytag->fetch_assoc())
									{
										extract($row);
									  $ObjDB->NonQuery("INSERT INTO itc_main_tag_mapping (fld_tag_id,fld_tag_type,fld_item_id,fld_access,fld_lesson_flag) 
															VALUES ('".$tagid."','34','".$newrowid."','1','0')");//insert if not exist

									}
								}
							}
							/*********Content Tagging End********/
						}
					}
				}
			}
		}
	/*************Existing rubric statement start*************/
        
   	/*************Add additional category start************/
        for($i=0;$i<sizeof($addrubnametemp);$i++) 
        {
            $addrtemp = explode('^',$addrubnametemp[$i]);          
			for($j=0;$j<sizeof($addrtemp);$j++)
			{
				$category1 = substr($addrtemp[0], 1);
				$four1 = substr($addrtemp[1], 1);
				$three1 = substr($addrtemp[2], 1);
				$two1 = substr($addrtemp[3], 1);
				$one1 = substr($addrtemp[4], 1);
				$zer1 = substr($addrtemp[5], 1);
				$wei = substr($addrtemp[6], 1);
				$weight1 = substr($wei, 1);
				$score1 = substr($addrtemp[7], 1);
				$destid1 = substr($addrtemp[8], 1);
				$destname=substr($addrtemp[9], 1);
				
				$placeholders = array("<br />","<br>");
				//replace values array
				$replace = array("","");
				$category1 = str_replace($placeholders, $replace, $category1);
				$four1 = str_replace($placeholders, $replace, $four1);
				$three1 = str_replace($placeholders, $replace, $three1);
				$two1 = str_replace($placeholders, $replace, $two1);
				$one1 = str_replace($placeholders, $replace, $one1);
				$zer1 = str_replace($placeholders, $replace, $zer1);
			}

			if($destid1!='')
			{
				if($destname!="undefined")
				{
					$destcount = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_exp_rubric_dest_master 
														WHERE fld_rubric_name_id='".$maxid."' AND fld_exp_id='".$expid."' 
														AND fld_dest_name='".$ObjDB->EscapeStr($destname)."'");
					if($destcount==0)
					{
						$newrowdestid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_exp_rubric_dest_master (fld_rubric_name_id, fld_exp_id, fld_dest_name, fld_created_by, fld_created_date) 
								VALUES ('".$maxid."', '".$expid."', '".$ObjDB->EscapeStr($destname)."', '".$uid."', '".date("Y-m-d H:i:s")."')");

					}
					else
					{
						$ObjDB->NonQuery("UPDATE itc_exp_rubric_dest_master SET fld_dest_name='".$ObjDB->EscapeStr($destname)."',
										fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."',fld_delstatus='0' 
									WHERE fld_rubric_name_id='".$maxid."' AND fld_exp_id='".$expid."' AND fld_id='".$destcount."' ");//update already exist
						$newrowdestid=$destcount;
					}

									
					if($updateid=='1')
					{
						$newid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_exp_rubric_master ORDER BY fld_id DESC LIMIT 1");
						$newid=$newid+1;

						$ObjDB->NonQuery("INSERT INTO itc_exp_rubric_master (`fld_rubric_id`,`fld_exp_id`, `fld_destination_id`, `fld_category`, `fld_four`, `fld_three`, `fld_two`, `fld_one`, `fld_zer`, `fld_weight`, `fld_score`, `fld_created_by`, `fld_created_date`,`newid`) 
										VALUES ('".$rubricnameid."', '".$expid."', '".$newrowdestid."', '".$category1."', '".$four1."', '".$three1."', '".$two1."', '".$one1."', '".$zer1."', '".$weight1."', '".$score1."', '".$uid."', '".date("Y-m-d H:i:s")."', '".$newid."')");//insert if not exist
					}
					else
					{
						$newid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_exp_rubric_master ORDER BY fld_id DESC LIMIT 1");
						$newid=$newid+1;
						$ObjDB->NonQuery("INSERT INTO itc_exp_rubric_master (`fld_rubric_id`,`fld_exp_id`, `fld_destination_id`, `fld_category`, `fld_four`, `fld_three`, `fld_two`, `fld_one`, `fld_zer`, `fld_weight`, `fld_score`, `fld_created_by`, `fld_created_date`,`newid`) 
										VALUES ('".$maxid."', '".$expid."', '".$newrowdestid."', '".$category1."', '".$four1."', '".$three1."', '".$two1."', '".$one1."', '".$zer1."', '".$weight1."', '".$score1."', '".$uid."', '".date("Y-m-d H:i:s")."', '".$newid."')");//insert if not exist
					}
				}
			}
	 	}
	/*************Add additional category End**************/
         
 	/*************if a destination is empty :**********/
        for($i=0;$i<sizeof($addempdestrubnametemp);$i++) 
        {
            $addempdesttemp = explode('^',$addempdestrubnametemp[$i]);    
			for($j=0;$j<sizeof($addrtemp);$j++)
			{
				$category1 = substr($addempdesttemp[0], 1);
				$four1 = substr($addempdesttemp[1], 1);
				$three1 = substr($addempdesttemp[2], 1);
				$two1 = substr($addempdesttemp[3], 1);
				$one1 = substr($addempdesttemp[4], 1);
				$zer1 = substr($addempdesttemp[5], 1);
				$wei = substr($addempdesttemp[6], 1);
				$weight1 = substr($wei, 1);
				$score1 = substr($addempdesttemp[7], 1);
				$destid1 = substr($addempdesttemp[8], 1);
				$destname= substr($addempdesttemp[9], 1);
			}

			if($destid1!='')
			{
				if($destname!="undefined")
				{
					$destcount = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_exp_rubric_dest_master 
														WHERE fld_rubric_name_id='".$maxid."' AND fld_exp_id='".$expid."' 
														AND fld_dest_name='".$ObjDB->EscapeStr($destname)."' ");
					if($destcount==0)
					{
						$newrowdestid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_exp_rubric_dest_master (fld_rubric_name_id, fld_exp_id, fld_dest_name, fld_created_by, fld_created_date) 
								VALUES ('".$maxid."', '".$expid."', '".$ObjDB->EscapeStr($destname)."', '".$uid."', '".date("Y-m-d H:i:s")."')");

					}
					else
					{
						$ObjDB->NonQuery("UPDATE itc_exp_rubric_dest_master SET fld_dest_name='".$ObjDB->EscapeStr($destname)."',
										fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."',fld_delstatus='0' 
									WHERE fld_rubric_name_id='".$maxid."' AND fld_exp_id='".$expid."' AND fld_id='".$destcount."' ");//update already exist
						$newrowdestid=$destcount;
					}					
					if($updateid=='1')
					{
						$newid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_exp_rubric_master ORDER BY fld_id DESC LIMIT 1");
						$newid=$newid+1;

						$ObjDB->NonQuery("INSERT INTO itc_exp_rubric_master (`fld_rubric_id`,`fld_exp_id`, `fld_destination_id`, `fld_category`, `fld_four`, `fld_three`, `fld_two`, `fld_one`, `fld_zer`, `fld_weight`, `fld_score`, `fld_created_by`, `fld_created_date`,`newid`) 
										VALUES ('".$rubricnameid."', '".$expid."', '".$newrowdestid."', '".$category1."', '".$four1."', '".$three1."', '".$two1."', '".$one1."', '".$zer1."', '".$weight1."', '".$score1."', '".$uid."', '".date("Y-m-d H:i:s")."', '".$newid."')");//insert if not exist

					}
					else
					{
						$newid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_exp_rubric_master ORDER BY fld_id DESC LIMIT 1");
						$newid=$newid+1;

						$ObjDB->NonQuery("INSERT INTO itc_exp_rubric_master (`fld_rubric_id`,`fld_exp_id`, `fld_destination_id`, `fld_category`, `fld_four`, `fld_three`, `fld_two`, `fld_one`, `fld_zer`, `fld_weight`, `fld_score`, `fld_created_by`, `fld_created_date`,`newid`) 
										VALUES ('".$maxid."', '".$expid."', '".$newrowdestid."', '".$category1."', '".$four1."', '".$three1."', '".$two1."', '".$one1."', '".$zer1."', '".$weight1."', '".$score1."', '".$uid."', '".date("Y-m-d H:i:s")."', '".$newid."')");//insert if not exist

					}
				}
			}
	 	}
		/*************if a destination is empty :**********/
	   	echo "success"; 
    } 
    catch (Exception $ex) 
	{
        echo "fail";
    }
}

/*--- Check Rubric Name ---*/
if($oper=="checkrubricname")
{
	
    $expid = isset($method['uid']) ? $method['uid'] : '0'; 
    $txtrubricname = (isset($method['txtrubricname']) ?  fnEscapeCheck($method['txtrubricname']) : '');

	$count = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_exp_rubric_name_master 
                                              WHERE MD5(LCASE(REPLACE(fld_rub_name,' ','')))='".$txtrubricname."' 
											   AND fld_delstatus='0' AND fld_id<>'".$expid."'");

    if($count == 0){ echo "true"; }	else { echo "false"; }
        
}

/*--- Delete the rubric ---*/
if($oper=="deleterubric" and $oper != " " )
{
    try
    {
		$expid= isset($method['exp_id']) ? $method['exp_id'] : '0';
		$rowid= isset($method['rowid']) ? $method['rowid'] : '0';

		$validate_assetid=true;
		if($rowid!=0)  $validate_assetid=validate_datatype($rowid,'int');
		if($validate_assetid)
			$ObjDB->NonQuery("UPDATE itc_exp_rubric_name_master
								 SET fld_delstatus='1', fld_deleted_by='".$uid."', fld_deleted_date='".date("Y-m-d H:i:s")."'
								 WHERE fld_id='".$rowid."' AND fld_exp_id='".$expid."'");
		else
			echo "fail";	

    }
    catch(Exception $e)
    {
            echo "fail";
    }

    echo "success";
}

/*********** ADD New Section code start here ************/
if($oper=='addnewsectiondestination' and  $oper!='')
{
	$expid= isset($method['exp_id']) ? $method['exp_id'] : '';
	$rubid= isset($method['rubid']) ? $method['rubid'] : '0';
	$destid= isset($method['editid']) ? $method['editid'] : '0';
	$txtdestsecname= isset($method['destname']) ? $method['destname'] : '';
	$editornew= isset($method['editornew']) ? $method['editornew'] : '';
	$lastrowid= isset($method['lastrowid']) ? $method['lastrowid'] : '';
	$flag= isset($method['flag']) ? $method['flag'] : '';	
	
	if($editornew=='0')
	{ 
		
	}
	else
	{
		$txtdestsecname='';
		$destid = $lastrowid;
	}
	
	?>
	<script type="text/javascript" charset="utf-8">		
		$.getScript("library/rubric/library-rubric.js");
	</script>

	<div class="four columns">
         <div class="row rowspacer" style="min-width:400px;">
            <center><span style="font-size:24px;" class="darkTitle">New Section</span></center>
         </div>
         <div class="row rowspacer" style="min-width:400px;">  
            <form name="expdestsecform" id="expdestsecform" >
                <div class="eleven columns" style="float: left; font-weight: bold; font-size: 15px;margin-left:15px" >
                    Section Title : <span style="color:red" >*</span>
                    <dl class="field row">
                       <dt class="text">
                          <input type="text" onblur="$(this).valid();" value="<?php echo htmlentities($txtdestsecname); ?>" name="txtdestsecname" id="txtdestsecname" placeholder="Section Title" />
                       </dt> 
                    </dl>
                </div>
            </form>
         </div>     
        <div class="row rowspacer" style="min-width:400px;">
			<?php 
			if($editornew=='0')
			{ 	?> <div style="margin-left:155px;margin-right:10px;" >
				<input style="margin-right:10px;width:90px" onclick="fn_tempsavedestsecform(<?php echo ($expid); ?>,<?php echo $rubid; ?>,<?php echo $destid; ?>,<?php echo $editornew; ?>,<?php echo $flag; ?>);" type="button" class="module-extend-button"   value="Update"   /> 
				<input style="margin-right:10px;width:90px" onclick="fn_tempdeletedestsecform(<?php echo ($expid); ?>,<?php echo $rubid; ?>,<?php echo $destid; ?>,<?php echo $editornew; ?>);" type="button" class="module-extend-button"   value="Delete"   /> 
				<input type="button" style="width:90px" onclick="fn_canceldestform();" class="module-extend-button"   value="Cancel"   /> 
				</div><?php  
			} 
			else
			{ 	?> <div style="margin-left:220px;margin-right:10px;" >
				<input style="margin-right:10px;width:90px" onclick="fn_tempsavedestsecform(<?php echo ($expid); ?>,<?php echo $rubid; ?>,<?php echo $destid; ?>,<?php echo $editornew; ?>,<?php echo $flag; ?>);" type="button" class="module-extend-button"   value="Ok"   /> 
				<input type="button" style="width:90px" onclick="fn_canceldestform();" class="module-extend-button"   value="Cancel"   /> 
				<?php 
			} 	?> </div>
        </div>
		<input type="hidden" name="hiddestid" id="hiddestid" value=<?php echo $destid;?> />
    </div>

	<script type="text/javascript" language="javascript" >
		$("#expdestsecform").validate({
		ignore: "",
		errorElement: "dd",
		errorPlacement: function(error, element) 
		{
				$(element).parents('dl').addClass('error');
				error.appendTo($(element).parents('dl'));
				error.addClass('msg');
				window.scroll(0,($('dd').offset().top)-50);
		},
		rules:
		{
			txtdestsecname: { required: true },
		},

		messages: 
		{
			txtdestsecname: { required: "please enter section name" },
		} ,
		highlight: function(element, errorClass, validClass) 
		{
			$(element).parents('dl').addClass(errorClass);
			$(element).addClass(errorClass).removeClass(validClass);
		},
		unhighlight: function(element, errorClass, validClass)
		{
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
/*********** ADD New Section code start here ************/

/**********Download PDF COde start here*********/
if($oper=="download")
{
	for ($x = 0; $x <= 100000000; $x++)
	{

	}
	echo "success";
}
/**********Download PDF COde start here*********/

       
@include("footer.php");