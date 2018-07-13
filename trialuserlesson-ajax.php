<?php
@include("sessioncheck.php");	
$oper = isset($_POST['oper']) ? $_POST['oper'] : '';


if($oper == "showtriallessons" and $oper != '')
{
	$lessonid = isset($_POST['lessonid']) ? $_POST['lessonid'] : '';
	$nxtlesson = isset($_POST['nxtlesson']) ? $_POST['nxtlesson'] : '';
	$prelesson = isset($_POST['prelesson']) ? $_POST['prelesson'] : '';
	
	$lessonpath = $ObjDB->SelectSingleValue("select fld_zip_name from itc_ipl_version_track where fld_ipl_id='".$lessonid."' and fld_zip_type='1' and fld_delstatus='0'");
	$foldername= str_replace('.zip','',$lessonpath);
	
	?>
    <iframe src="<?php echo _CONTENTURL_;?>scormlib/rte.php?SCOInstanceID=<?php echo $uid; ?>&lessonid=<?php echo $lessonid; ?>&hostname=<?php echo $_SERVER['SERVER_NAME']; ?>" width="100%" height="100%" style="border:none;margin:0 auto;"></iframe>
    <div class='row right'>
         <div class="tRight">
         <?php if($nxtlesson!=0){?> 
            <input type="button" id="btnstep" style="width: 200px; height: 35px; margin-right: 10%;" value="Next Lesson" onClick="window.location='trialuserlesson.php?id=<?php echo $nxtlesson;?>'" />
            <?php }
            else{
				?>
            		<input type="button" id="btnstep" style="width:200px; height:42px;float:right;" value="Finish" onClick="window.location='index.php'" />
            <?php }
				if($prelesson!=0){?>
                	<input type="button" id="btnstep" style="width: 200px; height: 35px; margin-right: 10%;" value="Previous Lesson" onClick="window.location='trialuserlesson.php?id=<?php echo $prelesson;?>'" />	
                <?php 
				}
				
			?>
         </div>        
    </div>
<?php        
}

@include("footer.php");