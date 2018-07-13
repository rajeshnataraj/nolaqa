<?php 
@include("sessioncheck.php");

$id = isset($method['id']) ? $method['id'] : '';
$id=explode(",",$id);

$expeditionqry = $ObjDB->QueryObject("SELECT fld_mis_name, fld_ui_id, fld_mistype AS exptype
										FROM itc_mission_master 
										WHERE fld_id='".$id[0]."' AND fld_delstatus='0'");
$rowexpedition=$expeditionqry->fetch_assoc();
extract($rowexpedition);
$id[1] = $fld_exp_name;
$expuiid = $fld_ui_id;

?>
<section data-type='#library-mission' id='library-mission-actions'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle"><?php echo $id[1];?></p>
                <p class="dialogSubTitle">&nbsp;</p>
            </div>
        </div>
        
        <div class='row buttons'>
            <?php if($expuiid==1) {?>
            <a class='skip btn mainBtn' href='#library-mission' id='btnlibrary-mission-preview' name='<?php echo $id[0];?>'>
                <div class='icon-synergy-view'></div>
                <div class='onBtn'>View</div>
            </a>
            <?php } else {?>
            <a class='skip btn main' href='#library-mission' onclick="loadiframes1(<?php echo $id[0];?>,<?php echo $uid;?>,<?php echo $exptype;?>)">
                <div class='icon-synergy-view'></div>
                <div class='onBtn'>View</div>
            </a>
            <?php } if($sessprofileid == 2 || $sessprofileid == 3) { ?>
            <a class='skip btn mainBtn' href='#library-mission' id='btnlibrary-mission-newmission' name='<?php echo $id[0].",0";?>'>
                <div class='icon-synergy-edit'></div>
                <div class='onBtn'>Edit</div>
            </a>
            <?php }  if($sessprofileid != 6) {  ?>            
            <?php }  if($sessprofileid == 2 || $sessprofileid == 3) {  ?>
              <!-- Ends Add Material lists button -->   
            <a class='skip btn main' href='#library-mission' onclick="fn_deletemission(<?php echo $id[0];?>)">
                <div class='icon-synergy-trash'></div>
                <div class='onBtn'>Delete</div>
            </a>
            <a class='skip btn mainBtn' href='#library-mission' id='btnlibrary-mission-mytags' name='<?php echo $id[0];?>'>
            <div class='icon-synergy-edit'></div>
            <div class='onBtn'>My Tags</div>
            </a>
            <?php
            	}  
                if($sessprofileid != 6){
                ?>
                    <a class='skip btn mainBtn' href='#library-mission' id='btnlibrary-mission-toggle' name='<?php echo $id[0];?>'>
                    <div class='icon-synergy-edit'></div>
                    <div class='onBtn'>Toggle</div>
                    </a>
                <?php } ?>
                <?php if($sessprofileid == 8 || $sessprofileid == 9) {  ?>
                <?php } ?>
                <?php 
                if(($sessprofileid == 2 or $sessprofileid == 3) and $exptype == '1') {  ?>
            <?php
            }  ?>

        </div>
    </div>
</section>
<?php
	@include("footer.php");