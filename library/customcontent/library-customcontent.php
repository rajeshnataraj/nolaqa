<?php 
/*------
	Page - library-customcontent
	Description:
		List the customcontent according to the tag well filter.
	
	Actions Performed:	
		Tag well - Shows the customcontent in fullscreen
		
	History:	
		
------*/

	@include("sessioncheck.php");
	
	$sid = isset($method['sid']) ? $method['sid'] : '0';
	
	$sqry='';
	if($sid!=0){
		$sid = explode(',',$sid); // split the id's 
		for($i=0;$i<sizeof($sid);$i++){	
			$id = explode('_',$sid[$i]); //split the id and conditional name
			if($id[1]=='customcontent'){				
				$sqry.= " and a.fld_id =".$id[0];
			}
			else{
				//get mathmodules for the custom tag	
				$itemqry = $ObjDB->QueryObject("SELECT fld_item_id FROM itc_main_tag_mapping WHERE fld_tag_id='".$sid[$i]."' AND fld_access='1' AND fld_tag_type='25'");
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

<section data-type='2home' id='library-customcontent'>
	<!--Script for Tag Well-->
	<script type="text/javascript" charset="utf-8">	
		$.getScript('library/customcontent/library-customcontent.js');
			
        $(function(){				
            var t4 = new $.TextboxList('#form_tags_customcontent', {
                startEditableBit: false,
                inBetweenEditableBits: false,
                plugins: {
                    autocomplete: {
                        onlyFromValues:true,
                        queryRemote: true,
                        remote: { url: 'autocomplete.php', extraParams: "oper=search&tag_type=25&customcontent=1" },
                        placeholder: ''
                    }
                },
                bitsOptions:{editable:{addKeys: [188]}}													
            });																	
            
            t4.addEvent('bitAdd',function(bit) {
                fn_loadcustomcontent();
            });
            
            t4.addEvent('bitRemove',function(bit) {
                fn_loadcustomcontent();
            });					
                
        });	
		
        function fn_loadcustomcontent(){
            var sid = $('#form_tags_customcontent').val();
            $("#customcontentlist").load("library/customcontent/library-customcontent.php?sid="+sid+" #customcontentlist > *");
            removesections('#library-customcontent');
        }
    </script>

    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
                <p class="dialogTitle">Custom content</p>
                <p class="dialogSubTitle">&nbsp;</p>
            </div>
        </div>
        
        <!--Tag For Searching/Selecting-->
        <div class='row rowspacer'>
            <div class='twelve columns'>
            	<p class="<?php if($sessmasterprfid == '10'){ echo "filterLightTitle"; }else { echo "filterDarkTitle"; } ?>">To filter this list, search by <?php if($sessmasterprfid == 2 || $sessmasterprfid == 3) { ?>Tag Name, and <?php } ?>Custom content Name.</p>
                <div class="tag_well">
                	<input type="text" name="form_tags_customcontent" value="" id="form_tags_customcontent" />
              	</div>
            </div>
        </div>
        
        <!--Dispaly New/Saved Modules in Grid view-->
        <div class='row buttons rowspacer' id="customcontentlist">
        	<?php 
			if($sessmasterprfid >= 7 and $sessmasterprfid <=9) { ?>
                <a class='skip btn mainBtn' href='#library-customcontent' id='btnlibrary-customcontent-newcustomcontent' name='0'>
                    <div class='icon-synergy-add-dark'></div>
                    <div class='onBtn'>New Custom<br />Content</div>
                </a>
            <?php 
			}
			
			
				   $qrycustomcontent = $ObjDB->QueryObject("SELECT fld_id as id,fld_contentname as name FROM itc_customcontent_master where fld_createdby='".$uid."' AND fld_delstatus='0'");
			
			while($res=$qrycustomcontent->fetch_assoc()){
				extract($res);
				?>
				<a class='skip btn mainBtn' href='#library-customcontent' id='btnlibrary-customcontent-actions' name="<?php echo $id.",".$name;?>">
					<div class='icon-synergy-modules'></div>
					<div class='onBtn tooltip' title="<?php echo $name; ?>"><?php echo $name; ?></div>
				</a>
				<?php
            }
            ?>
        </div>
    </div>
</section>
<?php
	@include("footer.php");