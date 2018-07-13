<?php 
@include("sessioncheck.php");

$id = isset($method['id']) ? $method['id'] : '';
$id=explode(",",$id);
$rubid=$id[1];

$expeditionqry = $ObjDB->QueryObject("SELECT fld_mis_name, fld_ui_id
                                            FROM itc_mission_master 
                                            WHERE fld_id='".$id[0]."' AND fld_delstatus='0'");
$rowexpedition=$expeditionqry->fetch_assoc();
extract($rowexpedition);
$id[1] = $fld_mis_name;
$expuiid = $fld_ui_id;

$tablename="itc_mis_rubric_master";

$rubricnameid=$ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_mis_rubric_name_master WHERE fld_mis_id='".$id[0]."' AND fld_delstatus='0' AND fld_created_by='".$uid."'");
$viewcount=$ObjDB->SelectSingleValueInt("SELECT count(*) FROM itc_mis_rubric_master WHERE fld_mis_id='".$id[0]."' AND fld_delstatus='0'");
$count = $ObjDB->SelectSingleValueInt("SELECT count(*) FROM $tablename WHERE fld_mis_id='".$id[0]."' AND fld_delstatus='0'");
 $count1 = $ObjDB->SelectSingleValueInt("SELECT count(*) FROM itc_mis_rubric_master WHERE fld_mis_id='".$id[0]."' AND fld_delstatus='0'");
$rubricname=$ObjDB->SelectSingleValue("SELECT fld_rub_name FROM itc_mis_rubric_name_master WHERE fld_mis_id='".$id[0]."' AND fld_id='".$rubid."' AND fld_delstatus='0'");

?>
<section data-type='#library-missionrubric' id='library-missionrubric-actions'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
                <p class="dialogTitle"><?php echo $rubricname; ?></p>
                <p class="dialogSubTitle">&nbsp;</p>
            </div>
        </div>
        
        <div class='row buttons'>
         
            <?php 
            if($sessprofileid == 9 || $sessprofileid == 8) { // Teacher and Teacher Admin Level ?> 
                <a class='skip btn mainBtn <?php if($viewcount==0){ echo 'dim'; }?>' href='#library-missionrubric' id='btnlibrary-missionrubric-preview' name='<?php echo $id[0].",".$rubid;?>'>
                   <div class='icon-synergy-view'></div>
                   <div class='onBtn'>View</div>
                </a> <?php

            if($count==0){      ?>
                    <a class='skip btn mainBtn' href='#library-missionrubric' id='btnlibrary-missionrubric-graderubric' name='<?php echo $id[0].",".$rubid;?>'>
                <div class='icon-synergy-edit'<?php if($count==0) echo 'icon-synergy-add-dark'; else  echo 'icon-synergy-view';  ?>></div>
                <div class='onBtn'>Edit</div>
                </a>
                <?php 
                }
               
            }   ?>
            
            
      <?php     if($sessprofileid == 9 || $sessprofileid == 8) {  // Teacher and Teacher Admin Level
                if($count!=0){
                ?>
                    <a class='skip btn mainBtn <?php if($sessprofileid != 2){if($count==0){ echo 'dim'; }}?>' href='#library-missionrubric' id='btnlibrary-missionrubric-graderubric' name='<?php echo $id[0].",".$rubid;?>'>
                <div class='icon-synergy-edit'<?php if($count==0) echo 'icon-synergy-add-dark'; else  echo 'icon-synergy-view';  ?>></div>
                        <div class='onBtn'>Edit</div>
            </a>
                <?php }    
                }
              if($sessprofileid == 2 || $sessprofileid == 6 || $sessprofileid == 7 ){ //pitscoadmin Level
                 if($viewcount!=0) {   //rubric ?>
                        <a class='skip btn mainBtn <?php if($viewcount==0){ echo 'dim'; }?>' href='#library-missionrubric' id='btnlibrary-missionrubric-preview' name='<?php echo $id[0].",".$rubid;?>'>
                           <div class='icon-synergy-view'></div>
                           <div class='onBtn'>View</div>
                        </a> 
                <?php }   ?>
                    <a class='skip btn mainBtn' href='#library-missionrubric' id='btnlibrary-missionrubric-graderubric' name='<?php echo $id[0].",".$rubid;?>'>
                        <div class='icon-synergy-edit'<?php if($count==0) echo 'icon-synergy-add-dark'; else  echo 'icon-synergy-add-dark';  ?>></div>
                        <div class='onBtn'>Edit</div>
            </a>  
			<?php 
			if($sessprofileid == 2)
			{
			$rubricname=str_replace(' ', '_', $rubricname); ?>
			<a class='skip btn mainBtn' href='#library-missionrubric'  onClick="fn_downloadpdf(<?php echo $id[0]; ?>,<?php echo $rubid; ?>,'<?php echo $rubricname.'_'; ?>');">
                        <div class='icon-synergy-edit'<?php if($count==0) echo 'icon-synergy-add-dark'; else  echo 'icon-synergy-add-dark';  ?>></div>
                        <div class='onBtn'>Download to Print / Save</div>
            </a>  
            
             
           <?php 
				}
              	}  
			?>
                  
         
                  
        <?php
            if($sessprofileid == 2)
            {
               $delcount=1;
            }
            else 
            {
                $delcount=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_mis_rubric_name_master where fld_id='".$rubid."' AND fld_profile_id='".$sessprofileid."' AND fld_mis_id='".$id[0]."' AND fld_created_by='".$uid."' AND fld_delstatus='0' ");
            }
        
        
            if($delcount == '1'){?>      
              <a class='skip btn main' href='#library-missionrubric' onclick="fn_deleterubric(<?php echo $id[0].",".$rubid;?>)">
                <div class='icon-synergy-trash'></div>
                <div class='onBtn'>Delete</div>
              </a> <?php 
            } 
        ?>
<!-- Content tagging -->
                
            <?php
            if($sessprofileid == 2)
            {
               $ownrubriccount=1;
            }
            else if($sessprofileid == 9 || $sessprofileid == 6 || $sessprofileid == 7  )//pitscoadmin  or Teacher Level
            {
               $ownrubriccount=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_mis_rubric_name_master where fld_id='".$rubid."' AND fld_profile_id='".$sessprofileid."' AND fld_mis_id='".$id[0]."' AND fld_created_by='".$uid."' AND fld_delstatus='0' ");
            }
            
             if($ownrubriccount == '1'){ ?>
            <a class='skip btn mainBtn' href='#library-missionrubric' id='btnlibrary-missionrubric-mytags' name='<?php echo $id[0].",".$rubid;?>'>
                <div class='icon-synergy-edit'></div>
                <div class='onBtn'>My Tags</div>
            </a>
                
            <?php }                 
             ?>
            
<!-- Download to Print / Save --> 
			<?php  
			if($sessprofileid == 9 || $sessprofileid == 6 || $sessprofileid == 7 || $sessprofileid == 8  )
			{	 // teacher / teacher admin / school admin/District Admin
				if($viewcount!=0) 
				{   ?>
					<?php $rubricname=str_replace(' ', '_', $rubricname); ?>
					<a class='skip btn mainBtn <?php if($viewcount==0){ echo 'dim'; }?>' href='#library-missionrubric' onClick="fn_downloadpdf(<?php echo $id[0]; ?>,<?php echo $rubid; ?>,'<?php echo $rubricname.'_'; ?>');" >
						<div class='icon-synergy-view'></div>
						<div class='onBtn'>Download to Print / Save</div>
					</a> 
					<?php 
				}                
			} ?>
                
        </div>
    </div>
</section>
<?php
    @include("footer.php");