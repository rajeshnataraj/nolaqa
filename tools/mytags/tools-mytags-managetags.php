<?php 
	@include("sessioncheck.php");
	$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '0';
	$id = explode('~',$id);
	$ids=explode(',',$id[0]);
	$items = '';
	for($j=0;$j<sizeof($ids);$j++){
		if($j==sizeof($ids)-1){
			$items.=$ids[$j];
		}
		else{
			$items.=$ids[$j].","; 
		}
	}
	$tags=explode(',',$id[1]);	
?> 
<script type="text/javascript" charset="utf-8">			
	$(function(){				
		var t4 = new $.TextboxList('#form_tags_manage', 
		{
			unique: true, plugins: {autocomplete: {}},
			bitsOptions:{editable:{addKeys: [188]}}	});
		<?php for($i=0;$i<sizeof($tags);$i++){
				$qry = $ObjDB->QueryObject("select fld_id, fld_tag_name from itc_main_tag_master where fld_id='".$tags[$i]."'");
				$restag = $qry->fetch_assoc();
				extract($restag);?>
				t4.add('<?php echo $ObjDB->EscapeStr($fld_tag_name); ?>','<?php echo $fld_id; ?>');
		<?php }?>			
		t4.getContainer().addClass('textboxlist-loading');				
		$.ajax({url: 'autocomplete.php', data: 'oper=new', dataType: 'json', success: function(r){
			t4.plugins['autocomplete'].setValues(r);
			t4.getContainer().removeClass('textboxlist-loading');					
		}});
		t4.addEvent('bitBoxAdd',function(bit) {
			fn_managelist(bit.getValue(),0);
		});
		
		t4.addEvent('bitBoxRemove',function(bit) {					
			fn_managelist(bit.getValue(),1);
		});							
	});			
</script>
<section data-type='2home' id='tools-mytags-managetags'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Batch Edit Labels</p>
            	<p class="dialogSubTitleLight">Change how all of the above items are labled.</p>
            </div>
        </div>
        
        <div class='row rowspacer'>
            <div class='twelve columns'>                
                <div class="tag_well">
                	<input type="text" name="test3" value="" id="form_tags_manage" />
                </div>
            </div>
        </div>          
    </div>  
    <input type="hidden" id="items" value="<?php echo $items;?>" />
</section>
<?php
	@include("footer.php");
