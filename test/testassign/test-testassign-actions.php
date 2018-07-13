<?php 
@include("sessioncheck.php");

$id = isset($method['id']) ? $method['id'] : '';

$id=explode(",",$id);

$reviewpagetype=$ObjDB->SelectSingleValueInt("SELECT fld_question_type as qtype FROM itc_test_master 
                                            WHERE fld_id='".$id[0]."' AND fld_delstatus='0'");

?>
<section data-type='#test-testassign' id='test-testassign-actions'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle"><?php echo $id[2]." Actions";?></p>
                <p class="dialogSubTitleLight">&nbsp;</p>
            </div>
        </div>
    	
        <?php 
		$qry = $ObjDB->QueryObject("SELECT fld_created_by,fld_ass_type AS asstype, fld_flag FROM `itc_test_master` WHERE fld_id='".$id[0]."' AND fld_delstatus='0'"); 
		$row=$qry->fetch_assoc();
		extract($row);
		$currentuid = $fld_created_by;
		$flag = $fld_flag;
                
                $distprofileidforschool = $ObjDB->SelectSingleValueInt("SELECT fld_profile_id FROM itc_user_master WHERE fld_id='".$currentuid."' AND fld_delstatus='0'");                
                
		?>
		<div class='row buttons'>
            <a class='skip btn mainBtn' href='#test-testassign' <?php if($reviewpagetype == 1){ ?> id='btntest-testassign-testreviewmain' <?php } else{ ?> id='btntest-testassign-testrandomreviewmain' <?php } ?> name='<?php echo $id[0].",".$id[2].","."view";?>'>
                <div class="icon-synergy-view"></div>
                <div class='onBtn'>View</div>
            </a>
            <?php if($currentuid == $uid OR $sessprofileid==2) {?>
            <a class='skip btn mainBtn' href='#test-testassign' id='btntest-testassign-steps' name='<?php echo $id[0].",".$id[1].",".$id[3];?>'>
               <div class="icon-synergy-edit"></div>
                <div class='onBtn'>Edit</div>
            </a>
            <a class='skip btn main' href='#class-class' onclick="fn_deletetest(<?php echo $id[0];?>)">
                <div class="icon-synergy-trash"></div>
                <div class='onBtn'>Delete</div>
            </a>
            <?php } ?>
            <!--/***********Share Pitsco Assessment For Teacher Developed By Mohan M Updated By 19-8-2015 teacher level*****************/-->
                <?php // if($currentuid == '2' AND $sessmasterprfid == '9') {?>
               <!-- <a class='skip btn mainBtn' href='#test-testassign' id='btntest-testassign-steps' name='<?php //echo $id[0].",".$id[1].",".$id[3];?>'>
                   <div class="icon-synergy-edit"></div>
                    <div class='onBtn'>Edit2</div>
                </a> -->
                <?php //} ?>
            
               <!--/***********Share Pitsco Assessment For Teacher Developed By Mohan M Updated By 19-8-2015 School District level and school level pitsco test*****************/-->
                <?php if(($currentuid == '2') AND ($sessmasterprfid == '6' OR $sessmasterprfid == '7')) {?>
                <a class='skip btn mainBtn' href='#test-testassign' id='btntest-testassign-steps' name='<?php echo $id[0].",".$id[1].",".$id[3];?>'>
                   <div class="icon-synergy-edit"></div>
                    <div class='onBtn'>Edit</div>
                </a>
                <?php } ?>
               
               
               <!--/***********Share Pitsco Assessment For Teacher Developed By Mohan M Updated By 19-8-2015 school level district test*****************/-->
                <?php if(($distprofileidforschool == '6' AND ($sessmasterprfid == '7' OR $sessmasterprfid == '9'))) {?>
                <a class='skip btn mainBtn' href='#test-testassign' id='btntest-testassign-steps' name='<?php echo $id[0].",".$id[1].",".$id[3];?>'>
                   <div class="icon-synergy-edit"></div>
                    <div class='onBtn'>Edit</div>
                </a>
                <?php } if($flag==1) { ?>
            
               <a class='skip btn mainBtn' href='#test-testassign' id='btntest-testassign-steps' name='<?php echo $id[0].",".$id[1].",".$id[3].",copy";?>'>
                   <div class="icon-synergy-edit"></div>
                    <div class='onBtn'>Copy</div>
                </a>
            
            <!--/***********Share Pitsco Assessment For Teacher Developed By Mohan M Updated By 19-8-2015*****************/-->
                <?php } 	
			if($sessmasterprfid == 7 or $sessmasterprfid == 8 or $sessmasterprfid == 9 or $sessmasterprfid == 5){ ?>
             <a class='skip btn mainBtn <?php if($flag==0){ ?>dim<?php }?>' href='#test-testassign' id='btntest-testassign-assign' name='<?php echo $id[0].",".$id[1].",".$id[3];?>'>
                <div class="icon-synergy-add-dark"></div>
                <div class='onBtn'>Assign <br/> Assessment</div>
            </a>
            <?php } 
            if($sessmasterprfid == 6 and $currentuid == $uid){ ?>
             <a class='skip btn mainBtn <?php if($flag==0){?>dim<?php }?>' href='#test-testassign' id='btntest-testassign-addtest' name='<?php echo $id[0].",".$id[1].",".$id[3];?>'>
                <div class="icon-synergy-add-dark"></div>
                <div class='onBtn'>Assign <br/> Assessment</div>
            </a>
                <?php }
            
            ?>
            
        </div>
    </div>
    <input type="hidden" id="hidflag" name="hidflag" value="1" />
</section>
<?php
	@include("footer.php");

