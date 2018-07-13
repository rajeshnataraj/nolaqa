<?php 
@include("sessioncheck.php");

$classid = isset($method['id']) ? $method['id'] : '0';

if($classid!=0)
{	
	$qry = $ObjDB->QueryObject("SELECT fld_step_id AS stepid, fld_flag AS flag 
								FROM itc_class_master 
								WHERE fld_id='".$classid."' AND fld_delstatus='0'");
	if($qry->num_rows>0){
		$row = $qry->fetch_assoc();
		extract($row);
	}
}
else
{
	$flag=0;
	$stepid=1;
}
?>
<section data-type='' id='class-newclass-steps'>
    <div class='container'>
    	<div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">New Class Wizard</p>
            	<p class="dialogSubTitleLight">Create your new class by following the steps below. To back up, click the step name to which you'd like to return.</p>
            </div>
        </div>
        <div class='row buttons'>
        	<a style="float:left;" class='mainBtn' href='#class-newclass' id='btnclass-newclass-classdetails' name='<?php echo $classid;?>,0'>
                <div class="step-first active-first" id="classdetails">
                    <div style="width:110px; margin:0 auto;">
                         New Class<br /> Details
                    </div>
                </div>
            </a>
            <a style="float:left" class='mainBtn<?php if($stepid<2 && $flag!=1) {?> dim <?php }?>' href='#class-newclass' id='btnclass-newclass-addpeople' name='<?php echo $classid;?>,0'>
                <div class="step-mid" id="people">
                    <div style="width:110px; margin:0 auto;">
                        Add<br /> People
                    </div>
                </div>
            </a>
            <a style="float:left" class='mainBtn<?php if($stepid<3 && $flag!=1) {?> dim <?php }?>' href='#class-newclass' id='btnclass-newclass-scheduledetails' name='<?php echo $classid;?>,0'>
                <div class="step-mid" id="schedule">
                    <div style="width:110px; margin:0 auto;">
                        Add<br /> Schedules
                    </div>
                </div>
            </a>
            <a style="float:left;" class='mainBtn<?php if($stepid<4 && $flag!=1) {?> dim <?php }?>' href='#class-newclass' id='btnclass-newclass-review' name='<?php echo $classid;?>,0'>
                <div class="step-last" id="review">
                    <div style="width:110px; margin:0 auto;">
                        Review Your<br /> New Class
                    </div>
                </div>
            </a>   
        </div>
	</div>
    <script language="javascript">
		var val = <?php echo $classid; ?>;
		var flg=0;
		if(<?php echo $stepid?>==1)
		{			
			setTimeout('showpageswithpostmethod("class-newclass-classdetails","class/newclass/class-newclass-classdetails.php","id='+val+","+flg+'");',1000);	
		}
		if(<?php echo $stepid?>==2)
		{			
			setTimeout('showpageswithpostmethod("class-newclass-addpeople","class/newclass/class-newclass-addpeople.php","id='+val+","+flg+'");',1000);
		}
		if(<?php echo $stepid?>==3)
		{			
			setTimeout('showpageswithpostmethod("class-newclass-scheduledetails","class/newclass/class-newclass-scheduledetails.php","id='+val+","+flg+'");',1000);
		}
		if(<?php echo $stepid?>==4)
		{
			setTimeout('showpageswithpostmethod("class-newclass-review","class/newclass/class-newclass-review.php","id='+val+","+flg+'");',1000);
		}
    </script>
    <input type="hidden" value="<?php echo $classid;?>" id="hidclassid" />
    <input type="hidden" value="0" id="classtypeval" />
</section>
<?php
	@include("footer.php");