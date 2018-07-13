<?php
@include("sessioncheck.php");

$menuid= isset($method['id']) ? $method['id'] : '';	
	
?>
<section data-type='2home' id='assignment-assignmentengine-test'>
	
    
    <div class='container'>
        <div class='row'>
          <div class='twelve columns'>
          	<p class="dialogTitle">Tests</p>
            <p class="dialogSubTitleLight">&nbsp;Choose any test to begin.</p>
          </div>
        </div>
        
        <div class='row buttons rowspacer'> 
			<?php
                $qrytest = $ObjDB->QueryObject("SELECT a.fld_test_name as testname, fn_shortname(a.fld_test_name,1) as shortname, 
				                                      a.fld_id as testid, a.fld_id, b.fld_id as testmapid FROM `itc_test_master` AS a, 
													  `itc_test_student_mapping` AS b 
											  WHERE a.fld_id=b.`fld_test_id` AND b.fld_student_id ='".$uid."' AND b.fld_flag='1'");
								                            
                if($qrytest->num_rows>0)
                {
                    while($res = $qrytest->fetch_assoc())
                    { 
                        extract($res);
						
						$attempts = $ObjDB->QueryObject("SELECT a.fld_max_attempts AS maxattempts, b.fld_max_attempts AS timeattempted 
						                                FROM `itc_test_master` AS a, `itc_test_student_mapping` AS b 
														WHERE a.fld_id=b.fld_test_id AND  a.fld_id='".$testid."' 
														AND b.fld_student_id='".$uid."' AND b.fld_id='".$testmapid."'  
														AND a.fld_delstatus='0'");
						$row = $attempts->fetch_assoc();
						extract($row);
                    ?>
                        <a class="skip btn <?php if($maxattempts>$timeattempted ){?>mainBtn<?php }else {?>mainBtn dim<?php }?>" href="#<?php echo $sname; ?>" name="<?php echo $testid;?>" id="btnassignment-assignmentengine-gototest" >
                            <div class='icon-synergy-tests'></div>
                            <div class='onBtn' title="<?php echo $testname; ?>"><?php echo $shortname; ?></div>
                        </a>
                   <?php 
                    }
                }
            ?>
     	</div>
    </div>
</section>
<?php
	@include("footer.php");
