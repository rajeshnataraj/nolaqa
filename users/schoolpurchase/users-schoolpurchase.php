<?php 
/*------
	Page - users-schoolpurchase
	Description:
		List the schoolpurchase avilable in the system
	History:	
------*/
	@include("sessioncheck.php");
	$sid = isset($_REQUEST['sid']) ? $_REQUEST['sid'] : '0';
        $spid = isset($_REQUEST['spid']) ? $_REQUEST['spid'] : '0';
	$sqry='';
	if($sid!=0){
		$sid = explode(',',$sid);
		for($i=0;$i<sizeof($sid);$i++){		
			$itemqry = $ObjDB->QueryObject("SELECT fld_item_id 
										FROM itc_main_tag_mapping 
										WHERE fld_tag_id='".$sid[$i]."' AND fld_access='1' AND fld_tag_type='17'");
			$sqry = "and (";
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
        
        if($spid!=0){
	$spid = explode(',',$spid);
		for($i=0;$i<sizeof($spid);$i++){		
			$itemqry = $ObjDB->QueryObject("SELECT fld_id FROM itc_school_master 
								            WHERE fld_id='".$spid[$i]."' AND fld_delstatus='0'");
			$sqry = "and (";
			$j=1;
			while($itemres = $itemqry->fetch_assoc()){
				extract($itemres);
				if($j==$itemqry->num_rows){
					$sqry.=" fld_id=".$fld_id.")";
				}
				else{
					$sqry.=" fld_id=".$fld_id." or";
				}
				$j++;
			}
		}
	}
?>
<script type="text/javascript" charset="utf-8">		
	$(function(){				
		var t4 = new $.TextboxList('#form_tags_schoolp', {
			startEditableBit: false,
			inBetweenEditableBits: false,
			plugins: {
				autocomplete: {
					onlyFromValues:true,
					queryRemote: true,
					remote: { url: 'autocomplete.php', extraParams: "oper=search&tag_type=17" },
					placeholder: ''
				}
			},
			bitsOptions:{editable:{addKeys: [188]}}													
		});																	
		
		t4.addEvent('bitAdd',function(bit) {
			fn_loadschoolp();
		});
		
		t4.addEvent('bitRemove',function(bit) {
			fn_loadschoolp();
		});					
			
	});	

	function fn_loadschoolp(){
				var sid = $('#form_tags_schoolp').val();
				$("#schoolplist").load("users/schoolpurchase/users-schoolpurchase.php #schoolplist > *",{'sid':sid});
				removesections('#users-schoolpurchase');
			}
                        
                        
               $(function(){				
		var t4 = new $.TextboxList('#form_tags_schoolpurchasename', {
			startEditableBit: false,
			inBetweenEditableBits: false,
			plugins: {
				autocomplete: {
					onlyFromValues:true,
					queryRemote: true,
					remote: { url: 'autocomplete.php', extraParams: "oper=searchschoolpurchasename&tag_type=17" },
					placeholder: ''
				}
			},
			bitsOptions:{editable:{addKeys: [188]}}													
		});																	
		
		t4.addEvent('bitAdd',function(bit) {
			fn_loadschoolpname();
		});
		
		t4.addEvent('bitRemove',function(bit) {
			fn_loadschoolpname();
		});					
			
	});	

	function fn_loadschoolpname(){
				var sid = $('#form_tags_schoolpurchasename').val();
				$("#schoolplist").load("users/schoolpurchase/users-schoolpurchase.php #schoolplist > *",{'spid':sid});
				removesections('#users-schoolpurchase');
			}
                        
                        /* for radio button options in title  */
		$("input[name='types']").click(function() {  
                    var test = $(this).val();
                    $("div.sdesc").hide();
                    $("#types" + test).show(); 
                });
</script>

<section data-type='#users' id='users-schoolpurchase'>
  <div class='container'>
    <div class='row'>
      <div class='twelve columns'>
      	<p class="dialogTitle">School Purchase</p>
        <p class="dialogSubTitleLight">&nbsp;</p>
      </div>
    </div>
    
   <?php 
          if($sessmasterprfid == 2) 
          { 
     ?>
        <div class="row" id="RadioGroup">
        <div class='twelve columns'>
            <font>Sort/filter the schoolpurchase list using: 
            <input type="radio" id="tag" name="types"  value="5" />Tag
            <input type="radio" id="search" name="types" checked="checked" value="6" />School Purchase Name
            </font>
             &nbsp;&nbsp;
	</div>
    </div> 
<?php }  ?>
    
    <div class='sdesc row' style="padding-bottom:20px;display:none;" id="types5">
        <div class='twelve columns'>
	        <p class="filterDarkTitle">To filter this list, search by Tag Name.</p>
            <div class="tag_well">
                <input type="text" name="test3" value="" id="form_tags_schoolp" />
            </div>
        </div>
    </div>
    
   <div class='sdesc row' style="padding-bottom:20px;" id="types6">
        <div class='twelve columns'>
	        <p class="filterDarkTitle">To filter this list, search by School Purchase Name.</p>
            <div class="tag_well">
                <input type="text" name="test3" value="" id="form_tags_schoolpurchasename" />
            </div>
        </div>
    </div>
   
    
    <div class='row buttons' id="schoolplist">
        <a class='skip btn mainBtn' href='#users-newschoolpurchase' id='btnusers-schoolpurchase-newschoolpurchase' name="0">
            <div class='icon-synergy-add-dark'></div>
            <div class='onBtn'>New school<br />purchase</div>
        </a>
      
      	<?php  
            $qry = $ObjDB->QueryObject("SELECT fld_school_name AS shlname, fn_shortname(fld_school_name,1) AS shortname, fld_id AS id, fld_school_logo AS shllogo 
									FROM itc_school_master 
									WHERE fld_delstatus='0' and fld_district_id=0 ".$sqry." order by shlname,shortname");
            while($row=$qry->fetch_assoc())
            {
				extract($row);
		?>		
                <a class='skip btn mainBtn' href='#users-schoolpurchase' id='btnusers-schoolpurchase-schoolpurchase_actions' name="<?php echo $id.",".$shlname;?>">
                	<div class="icon-synergy-school">
                    	<?php if($shllogo != "no-image.png" and $shllogo != ""){ ?>
                    		<img class="thumbimg" src="thumb.php?src=<?php echo __CNTSLPATH__.$shllogo; ?>&w=40&h=40&q=100" />
                        <?php } ?>
                    </div>
                    <div class='onBtn tooltip' title="<?php echo $shlname;?>"><?php echo $shortname; ?></div>
				</a>
            	<?php
            }
            ?>
    </div>
  </div>
</section>
<?php
	@include("footer.php");
