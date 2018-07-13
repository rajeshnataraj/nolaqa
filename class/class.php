<?php
@include("sessioncheck.php");

$menuid= isset($method['id']) ? $method['id'] : '';
$sid = isset($method['sid']) ? $method['sid'] : '0';
	$sqry='';
	if($sid != 0){
		$sid = explode(',',$sid); // split the id's 
		for($i=0;$i<sizeof($sid);$i++){			
			//get lessons for the custom tag
			$itemqry = $ObjDB->QueryObject("SELECT fld_item_id 
											FROM itc_main_tag_mapping 
											WHERE fld_tag_id='".$sid[$i]."' AND fld_access='1' AND fld_tag_type='21'");
			$sqry = "AND (";
			$j=1;
			while($itemres = $itemqry->fetch_assoc()){
				extract($itemres);
				if($j==$itemqry->num_rows){
					$sqry.=" fld_id=".$fld_item_id.")";
				}
				else{
					$sqry.=" fld_id=".$fld_item_id." or";
				}
				$j++;
			}
		}				
	}
?>
<section data-type='2home' id='class'>
	<script>
        $.getScript("class/newclass/class-newclass-class.js");
        $.getScript("class/newclass/class-newclass-rotationalschedule.js");
        $.getScript("class/newclass/class-newclass-expeditionschedule.js");
        $.getScript("class/newclass/class-newclass-missionschedule.js");
        $.getScript("class/newclass/class-newclass-modexpeditionschedule.js");
        $.getScript("class/newclass/class-newclass-dyad.js");
        $.getScript("class/newclass/class-newclass-triad.js");
        $.getScript("class/newclass/class-newclass-pdschedule.js");
        $(function(){				
            var t4 = new $.TextboxList('#form_tags_class', {
                startEditableBit: false,
                inBetweenEditableBits: false,
                plugins: {
                    autocomplete: {
                        onlyFromValues:true,
                        queryRemote: true,
                        remote: { url: 'autocomplete.php', extraParams: "oper=search&tag_type=21" },
                        placeholder: ''
                    }
                },
                bitsOptions:{editable:{addKeys: [188]}}													
            });																	
            
            t4.addEvent('bitAdd',function(bit) {
                fn_loadclass();
            });
            
            t4.addEvent('bitRemove',function(bit) {
                fn_loadclass();
            });					
                
        });	
    
        function fn_loadclass(){
            var sid = $('#form_tags_class').val();
            $("#classlist").load("class/class.php #classlist > *",{'sid':sid});
            removesections('#class');
        }
    </script>

    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="lightTitle">Class</p>
                <p class="lightSubTitle">&nbsp;</p>
            </div>
        </div>
        
        <div class='row'>
            <div class='twelve columns'>
            	<!--<p class="filterLightTitle">To filter this list, search by Tag Name.</p>-->
                <p class="filterLightTitle">To search for a specific class, search by name, Tag name, or browse through the list below.</p>
                <div class="tag_well">
                	<input type="text" name="form_tags_class" value="" id="form_tags_class" />
              	</div>
            </div>
        </div>
        
        <div class='row rowspacer buttons' id="classlist">
            <a class='skip btn mainBtn' href='#class' id='btnclass-newclass-steps' name='0'>
                <div class='icon-synergy-add-dark'></div>
                <div class='onBtn'>New Class</div>
            </a>
            <a class='skip btn mainBtn' href='#class' id='btnclass-newclass-newarchive' name='0'>
               <div class='icon-synergy-modules'></div>
                <div class='onBtn'>Archive Class</div>
            </a>
            <?php 
            	$qryclass = $ObjDB->QueryObject("SELECT fld_class_name AS classname, fn_shortname(fld_class_name,1) AS shortname, fld_id AS classid, fld_lab AS classtypeid, 
													fld_step_id AS stepid, fld_flag AS flag 
												FROM itc_class_master 
												WHERE fld_delstatus='0' AND fld_archive_class ='0' AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' 
													AND (fld_created_by='".$uid."' OR fld_id IN(SELECT fld_class_id FROM itc_class_teacher_mapping WHERE fld_teacher_id='".$uid."' 
													AND fld_flag='1')) ".$sqry."");
				if($qryclass->num_rows>0){
					while($rowclass = $qryclass->fetch_assoc())
					{
						extract($rowclass);
				
						if($flag==1)
							$stepid=1;
						?>
                        <a class='skip btn mainBtn' href='#class' id='btnclass-newclass-actions' name="<?php echo $classid; ?>">
                            <div class='icon-synergy-class'></div>
                            <div class='onBtn tooltip' title="<?php echo $classname; ?>"><?php echo $shortname; ?></div>
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