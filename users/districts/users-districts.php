<?php 
/*------
	Page - users-district
	Description:
		List the districts avilable in the system
	History:	
		
------*/
	@include("sessioncheck.php");
	$sid = isset($method['sid']) ? $method['sid'] : '0';
        $did = isset($method['did']) ? $method['did'] : '0';
	$sqry='';
	if($sid!=0){
		$sid = explode(',',$sid);
		for($i=0;$i<sizeof($sid);$i++){		
			$itemqry = $ObjDB->QueryObject("SELECT fld_item_id 
										FROM itc_main_tag_mapping 
										WHERE fld_tag_id='".$sid[$i]."' AND fld_access='1' AND fld_tag_type='15'");
			$sqry = "and (";
			$j=1;
			while($itemres = $itemqry->fetch_assoc()){
				extract($itemres);
				if($j==$itemqry->num_rows){
					$sqry.="a.fld_id=".$fld_item_id.")";
				}
				else{
					$sqry.="a.fld_id=".$fld_item_id." or ";
				}
				$j++;
			}
		}
	}
        
        if($did!=0){
	$did = explode(',',$did);
		for($i=0;$i<sizeof($did);$i++){		
			$itemqry = $ObjDB->QueryObject("SELECT fld_id FROM itc_user_master 
								            WHERE fld_id='".$did[$i]."' AND fld_delstatus='0'");
			$sqry = "and (";
			$j=1;
			while($itemres = $itemqry->fetch_assoc()){
				extract($itemres);
				if($j==$itemqry->num_rows){
					$sqry.=" a.fld_id=".$fld_id.")";
				}
				else{
					$sqry.=" a.fld_id=".$fld_id." or";
				}
				$j++;
			}
		}
	}
?>
<script type="text/javascript" charset="utf-8">		
	$(function(){				
		var t4 = new $.TextboxList('#form_tags_dist', {
			startEditableBit: false,
			inBetweenEditableBits: false,
			plugins: {
				autocomplete: {
					onlyFromValues:true,
					queryRemote: true,
					remote: { url: 'autocomplete.php', extraParams: "oper=search&tag_type=15" },
					placeholder: ''
				}
			},
			bitsOptions:{editable:{addKeys: [188]}}													
		});																	
		
		t4.addEvent('bitAdd',function(bit) {
			fn_loaddist();
		});
		
		t4.addEvent('bitRemove',function(bit) {
			fn_loaddist();
		});					
			
	});	
	/*
		fn_loaddist()
		function to load the districts based on the search
	*/
	function fn_loaddist(){
		var sid = $('#form_tags_dist').val();
		$("#distlist").load("users/districts/users-districts.php #distlist > *",{'sid':sid});
		removesections('#users-districts');
	}
        
        
        
        $(function(){				
		var t4 = new $.TextboxList('#form_tags_distname', {
			startEditableBit: false,
			inBetweenEditableBits: false,
			plugins: {
				autocomplete: {
					onlyFromValues:true,
					queryRemote: true,
					remote: { url: 'autocomplete.php', extraParams: "oper=searchdistname&tag_type=15" },
					placeholder: ''
				}
			},
			bitsOptions:{editable:{addKeys: [188]}}													
		});																	
		
		t4.addEvent('bitAdd',function(bit) {
			fn_loaddistname();
		});
		
		t4.addEvent('bitRemove',function(bit) {
			fn_loaddistname();
		});					
			
	});	
	/*
		fn_loaddist()
		function to load the districts based on the search
	*/
	function fn_loaddistname(){
		var sid = $('#form_tags_distname').val();
		$("#distlist").load("users/districts/users-districts.php #distlist > *",{'did':sid});
		removesections('#users-districts');
                
        }
        
        /* for radio button options in title  */
		$("input[name='types']").click(function() {  
                    var test = $(this).val();
                    $("div.sdesc").hide();
                    $("#types" + test).show(); 
                });
</script>

<section data-type='#users' id='users-districts'>
  <div class='container'>
    <div class='row'>
      <div class='twelve columns'>
      	<p class="dialogTitle">Districts</p>
        <p class="dialogSubTitleLight">&nbsp;</p>
      </div>
    </div>
    
    <?php 
          if($sessmasterprfid == 2) 
          { 
     ?>
        <div class="row" id="RadioGroup">
        <div class='twelve columns'>
            <font style="color:white">Sort/filter the Districts list using: 
            <input type="radio" id="tag" name="types"  value="5" />Tag
            <input type="radio" id="search" name="types" checked="checked" value="6" />District Name
            </font>
             &nbsp;&nbsp;
	</div>
    </div> 
<?php }  ?>
    
    <div class='sdesc row' id="types5" style="display:none;">
        <div class='twelve columns'>
	        <p class="filterLightTitle">To filter this list, search by Tag Name.</p>
            <div class="tag_well">
                <input type="text" name="test3" value="" id="form_tags_dist" />
            </div>
        </div>
    </div>
    
      <div class='sdesc row' id="types6" >
        <div class='twelve columns'>
	        <p class="filterLightTitle">To filter this list, search by District Name.</p>
            <div class="tag_well">
                <input type="text" name="test3" value="" id="form_tags_distname" />
            </div>
        </div>
    </div>
    
    <div class='row rowspacer buttons' id="distlist">
        <a class='skip btn mainBtn' href='#users-newdistrict' id='btnusers-districts-newdistrict' name="0">
            <div class='icon-synergy-add-dark'></div>
            <div class='onBtn'>New<br />District</div>
        </a>
      	<?php 
			$qry = $ObjDB->QueryObject("SELECT a.fld_district_name AS districtname, a.fld_id AS distid, 
											fn_shortname(fld_district_name,1) AS shortname 
										FROM itc_district_master AS a, `itc_user_master` AS b 
										WHERE a.fld_district_admin_id=b.fld_id AND a.fld_delstatus='0' AND b.fld_delstatus='0' ".$sqry." order by districtname,shortname");
            while($rowdist=$qry->fetch_assoc())
            {
				extract($rowdist);	
		?>
				<a class='skip btn mainBtn' href='#users-newdistrict' id='btnusers-districts-districts_actions' name="<?php echo $distid.",".$districtname;?>">
                	<div class="icon-synergy-district">
                    </div>
                    <div class='onBtn tooltip' title="<?php echo $districtname;?>"><?php echo $shortname; ?></div>
				</a>
		<?php
        	}
      	?>
    </div>
  </div>
</section>
<?php
	@include("footer.php");
