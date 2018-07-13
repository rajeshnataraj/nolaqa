<?php 
@include("sessioncheck.php");

$id = isset($method['id']) ? $method['id'] : 0;

$state = '';
$stdid = '';

if($id != '0'){
	
	$qry = $ObjDB->QueryObject("SELECT b.fld_id AS state, a.fld_standard_id AS stdid, b.fld_name AS statename 
								FROM itc_correlation_report_data AS a 
								LEFT JOIN itc_standards_bodies AS b ON a.fld_std_body=b.fld_id 
								WHERE a.fld_id='".$id."'");
	$rowcrp = $qry->fetch_assoc();
	extract($rowcrp);
}
?>
<script language="javascript" type="text/javascript">
	$('#cbasicinfo').removeClass("active-first");
	$('#cselectstandard').addClass("active-mid").parent().removeClass("dim");
	$('#cselectproduct').removeClass("active-mid");
	$('#cviewreport').removeClass("active-last");
	$('#btnstep2').addClass('dim');
	
	<?php if($id != '0' and $state != '') {?>	
		fn_showdocuments('<?php echo $state; ?>',<?php echo $id; ?>,'<?php echo $stdid; ?>');		
	<?php } ?>
</script>

<section data-type='2home' id='reports-correlation-select_standard'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Step 2: Select Standard</p>
				<p class="dialogSubTitleLight">Using the fields below, select your standard. Press &ldquo;Next Step&rdquo; to continue.</p>
                  <div class="row rowspacer"></div>
            </div>
        </div>    
        <div class='row'>
            <div class='twelve columns formBase'>
                <div class='row'>
                    <div class='eleven columns centered insideForm'>
                    	<form id="frmselectstandard" name="frmselectstandard">

                        <div class="row rowspacer">
                            <div class='six columns'>
                            	Select State<span class="fldreq">*</span>
                                <dl class='field row'>   
                                    <dt class='dropdown'>   
                                        <div class="selectbox">
                                            <input type="hidden" name="selectstate" id="selectstate" value="<?php echo $state; ?>" onchange="fn_showdocuments(this.value,<?php echo $id; ?>);" />
                                            <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                                <span class="selectbox-option input-medium" data-option="1"><?php if($state == '') {?>Select State<?php } else { echo $statename; } ?></span>
                                                <b class="caret1"></b>
                                            </a>
                                            <div class="selectbox-options">
                                                <input type="text" class="selectbox-filter" placeholder="Search State" />
                                                <ul role="options">
                                                   <?php 
                                                    $qry = $ObjDB->QueryObject("SELECT fld_id AS stdbid, fld_name AS stdbname from itc_standards_bodies ORDER BY stdbname");
                                                    while($res=$qry->fetch_assoc())
                                                    {
														extract($res);	
													?>
                                                        <li><a tabindex="-1" href="#" data-option="<?php echo $stdbid;?>"><?php echo $stdbname; ?></a></li>
                                                        <?php 
                                                    }?>
                                                </ul>
                                            </div>
                                        </div>
                                    <dt>
                                </dl>                            
                            </div>
                        </div>

                        

                        <div class="row rowspacer">
                            <div class='twelve columns' id="dpdocuments">
                            
                        </div>
                        </div>


                        <div class="row rowspacer">
                        	<div class='twelve columns' id="divdocgrades">
                            
                            </div>
                        </div>
                        <div class="row rowspacer">
			<?php $dimflag='0';?>	
                        	<input class="btn <?php  if($id != '0' and $state == '' ) {?>dim<?php } ?>" type="button" id="btnstep2"  style="width:200px; height:42px;float:right;" value="Next Step" onClick="fn_movenextstep(<?php echo $id; ?>,3);" />
 <?php if($sessmasterprfid==8 or $sessmasterprfid==9 ) {?>
<input class="btn <?php  if($id != '0' and $state == ''and $dimflag='0'){?>dim<?php }?>" type="button" id="reqstbtn"  						style=" margin-right:20px;width:200px;height:42px;float:right;" value="Request Correlation" onClick="fn_correlation(<?php echo $id; ?>);"/>

<?php } ?>
                        </div>
                            
                            <input type="hidden" name="stdid" id="stdid" value="<?php echo $stdid; ?>">
                            <input type="hidden" name="corid" id="corid" value="<?php echo $id; ?>">
                            <input type="hidden" name="state" id="state" value="<?php echo $state; ?>">
                        </form>
                        
                    
                    </div>
               	</div>
        	</div>
     	</div>
 	</div>
</section>     
<?php
	@include("footer.php");
