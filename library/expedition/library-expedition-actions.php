<?php 
include("sessioncheck.php");
ini_set('display_errors', true);
error_reporting(E_ALL & ~ E_NOTICE);

$id = isset($method['id']) ? $method['id'] : '';
$id=explode(",",$id);

$expeditionqry = $ObjDB->QueryObject("SELECT fld_exp_name, fld_ui_id, fld_exptype AS exptype
										FROM itc_exp_master 
										WHERE fld_id='".$id[0]."' AND fld_delstatus='0'");
$rowexpedition=$expeditionqry->fetch_assoc();
extract($rowexpedition);
$id[1] = $fld_exp_name;
$expuiid = $fld_ui_id;

//This function takes in an expedition id and returns whether or not the logged in teacher can see the digital logbook toggle button for the
//expedition
function get_digital_logbook_display_status(){
    //Only teachers and teacher admins have access to the toggling button.
    if ($_SESSION['user_profile'] == 8 || $_SESSION['user_profile'] == 9){
        return true;
    }else{
        return false;
    }
}

$display_digital_logbook = get_digital_logbook_display_status();

//A digital logbook will be displayed if
?>
<section data-type='#library-expedition' id='library-expedition-actions'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle"><?php echo $id[1];?></p>
                <p class="dialogSubTitle">&nbsp;</p>
            </div>
        </div>
        
        <div class='row buttons'>
            <?php if($expuiid==1) {?>
            <a class='skip btn mainBtn' href='#library-expedition' id='btnlibrary-expedition-preview' name='<?php echo $id[0];?>'>
                <div class='icon-synergy-view'></div>
                <div class='onBtn'>View</div>
            </a>
            <?php } else {?>
            <a class='skip btn main' href='#library-expedition' onclick="loadiframes1(<?php echo $id[0];?>,<?php echo $uid;?>,<?php echo $exptype;?>)">
                <div class='icon-synergy-view'></div>
                <div class='onBtn'>View</div>
            </a>
            <?php } if($sessprofileid == 2 || $sessprofileid == 3) { ?>
            <a class='skip btn mainBtn' href='#library-expedition' id='btnlibrary-expedition-newexpedition' name='<?php echo $id[0].",0";?>'>
                <div class='icon-synergy-edit'></div>
                <div class='onBtn'>Edit</div>
            </a>
            <a class='skip btn mainBtn' href='#library-expedition' id='btnlibrary-expedition-changeorder' name='<?php echo $id[0];?>'>
                <div class='icon-synergy-edit'></div>
                <div class='onBtn'>Order</div>
            </a>
            <?php }  if($sessprofileid != 6) {  ?>
             <!-- Starts Add Material lists button  -->  
            <a class='skip btn mainBtn' href='#library-expedition' id='btnlibrary-expedition-materiallist' name='<?php echo $id[0].",0";?>'>
                <div class='icon-synergy-edit'></div>
                <div class='onBtn'>Materials List</div>
            </a>
            <?php }  if($sessprofileid == 2 || $sessprofileid == 3) {  ?>
              <!-- Ends Add Material lists button -->   
            <a class='skip btn main' href='#library-expedition' onclick="fn_deleteexpedition(<?php echo $id[0];?>)">
                <div class='icon-synergy-trash'></div>
                <div class='onBtn'>Delete</div>
            </a>
            <a class='skip btn mainBtn' href='#library-expedition' id='btnlibrary-expedition-mytags' name='<?php echo $id[0];?>'>
            <div class='icon-synergy-edit'></div>
            <div class='onBtn'>My Tags</div>
            </a>
            <?php
            	}  
                if($sessprofileid != 6){
                ?>
                    <a class='skip btn mainBtn' href='#library-expedition' id='btnlibrary-expedition-toggle' name='<?php echo $id[0];?>'>
                    <div class='icon-synergy-edit'></div>
                    <div class='onBtn'>Toggle</div>
                    </a>
                <?php } ?>
                <?php
                if($sessprofileid == 8 || $sessprofileid == 9) {  ?>
                    <a class='skip btn mainBtn' href='#library-expedition' id='btnlibrary-expedition-toggleassessment' name='<?php echo $id[0];?>'>
                        <div class='icon-synergy-edit'></div>
                        <div class='onBtn'>Toggle Assessment</div>
                    </a>
                <?php
                }

                if ($sessprofileid == 8 || $sessprofileid == 9){
                    if ($display_digital_logbook) {
                        ?>
                        <a class='skip btn mainBtn' href='#library-expedition'
                           id='btnlibrary-expedition-toggledigitallogbook' name='<?php echo $id[0]; ?>'>
                            <div class='icon-synergy-edit'></div>
                            <div class='onBtn'>Toggle Digital Logbook</div>
                        </a>
                        <?php
                    }
                } ?>

                <!-- extend created by chandru start line -->
                <?php if($sessprofileid == 2 || $sessprofileid == 8 || $sessprofileid == 9) {  ?> 
                    <a class='skip btn mainBtn' href='#library-expedition' id='btnlibrary-expedition-expextend'  name='<?php echo $id[0];?>'>
                        <div class='icon-synergy-edit'></div>
                        <div class='onBtn'>Extend</div>
                    </a>
                <?php } ?>
                <!-- extend created by chandru end line -->

        </div>
    </div>
</section>
<?php
	@include("footer.php");