<?php
/*
	Created By - Muthukumar. D
	Page - library-diagmastery
	Description:
		Show the Tags textbox, New Diagmastery and Saved Diagmastery(Diagmastery name) buttons.
	
	Actions Performed:
		Tag - Used to filter the details
		New Diagmastery - Redirects to Diagmastery details form - library-diagmastery-testdetails.php
		Save Diagmastery - Redirects to Diagmastery actions form - library-diagmastery-actions.php
	History:
*/
@include("sessioncheck.php");
$sid = isset($method['sid']) ? $method['sid'] : '0';
$sqry = '';
	if($sid != 0){
		$sid = explode(',',$sid); // split the id's 
		for($i=0;$i<sizeof($sid);$i++){
			$id = explode('_',$sid[$i]); //split the id and conditional name
			
			if($id[1] == 'unit'){	// check the conditional name and concatenate the field name according to it.			 
				$sqry.= " and b.fld_unit_id =".$id[0];
			}			
			else if($id[1] == 'lesson'){
				$sqry.= " and b.fld_id =".$id[0];
			}
			else{
				//get lessons for the custom tag
				$itemqry = $ObjDB->QueryObject("SELECT fld_item_id 
												FROM itc_main_tag_mapping 
												WHERE fld_tag_id='".$sid[$i]."' AND fld_access='1' AND fld_tag_type='22'");
				$sqry = "and (";
				$j=1;
				while($itemres = $itemqry->fetch_assoc()){
					extract($itemres);
					if($j==$itemqry->num_rows){
						$sqry.=" a.fld_id=".$fld_item_id.")";
					}
					else{
						$sqry.=" a.fld_id=".$fld_item_id." or";
					}
					$j++;
				}
			}
		}		
	}
?>
<section data-type='2home' id='library-diagmastery'>
	<!--Script for Tag Well-->
	<script language="javascript" type="text/javascript" charset="utf-8">		
        $.getScript("library/diagmastery/library-diagmastery-diagmas.js");	
		
		$(function(){				
            var t4 = new $.TextboxList('#form_tags_diag', {
                startEditableBit: false,
                inBetweenEditableBits: false,
                plugins: {
                    autocomplete: {
                        onlyFromValues:true,
                        queryRemote: true,
                        remote: { url: 'autocomplete.php', extraParams: "oper=search&unit=1&lesson=1&tag_type=22" },
                        placeholder: ''
                    }
                },
                bitsOptions:{editable:{addKeys: [188]}}													
            });																	
            
            t4.addEvent('bitAdd',function(bit) {
                fn_loaddiagmas();
            });
            
            t4.addEvent('bitRemove',function(bit) {
                fn_loaddiagmas();
            });					
                
        });	
		
        function fn_loaddiagmas(){
            var sid = $('#form_tags_diag').val();
            $("#diaglist").load("library/diagmastery/library-diagmastery.php #diaglist > *",{'sid':sid});
            removesections('#library-diagmastery');
        }
    </script>

    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Diag/Mastery</p>
                <p class="dialogSubTitle">&nbsp;</p>
            </div>
        </div>
        
        <!--Tag For Searching/Selecting-->
        <div class='row rowspacer'>
            <div class='twelve columns'>
            	<!--<p class="filterLightTitle">To filter this list, search by <?php if($sessmasterprfid==2 || $sessmasterprfid==3){?>Tag Name, <?php }?>Unit Name, and Lesson Name.</p>-->
                <p class="filterLightTitle">Search by Unit name, Lesson name<?php if($sessmasterprfid==2 || $sessmasterprfid==3){?>, or Tag name <?php }?>.</p>
                <div class="tag_well">
                	<input type="text" name="form_tags_diag" value="" id="form_tags_diag" />
              	</div>
            </div>
        </div>
        
        <!--Display New/Saved Diagmastery-->
        <div class='row buttons rowspacer' id='diaglist'>
            <a class='skip btn mainBtn' href='#library-diagmastery' id='btnlibrary-diagmastery-steps' name='0,1'>
                <div class='icon-synergy-add-dark'></div>
                <div class='onBtn'>New<br />Diag Mastery</div>
            </a>
            <?php 
            $qrydiagmas = $ObjDB->QueryObject("SELECT a.fld_id AS diagid, a.fld_step_id AS stepid, a.fld_access AS flag, 
											 CONCAT(b.fld_ipl_name,'',c.fld_version) AS diagmasname,
											 fn_shortname(CONCAT(b.fld_ipl_name,' ',c.fld_version),1) AS shortname 
											 FROM itc_diag_question_mapping AS a 
											 LEFT JOIN itc_ipl_master AS b ON a.fld_lesson_id=b.fld_id 
											 LEFT JOIN itc_ipl_version_track AS c ON c.fld_ipl_id=b.fld_id 
											 WHERE a.fld_delstatus='0' AND b.fld_delstatus='0' AND b.fld_access='1' AND c.fld_zip_type='1' AND c.fld_delstatus='0' ".$sqry."
											 ORDER BY diagmasname ASC");
											
			if($qrydiagmas->num_rows>0){
				while($rowdiagmas = $qrydiagmas->fetch_assoc())
				{
					extract($rowdiagmas);
					if($flag==1)
						$stepid=1;
					?>
					<a class='skip btn mainBtn' href='#library-diagmastery' id='btnlibrary-diagmastery-actions' name="<?php echo $diagid.",".$stepid.",".$diagmasname; ?>">
						<div class='icon-synergy-diag-mastery'></div>
						<div class='onBtn' title="<?php echo $diagmasname; ?>"><?php echo $shortname; ?></div>
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