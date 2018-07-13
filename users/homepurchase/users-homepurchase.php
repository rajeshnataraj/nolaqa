<?php 
/*------
	Page - users-home purchase
	Description:
		List the home avilable in the system
	History:	
------*/
	@include("sessioncheck.php");
	$sid = isset($method['sid']) ? $method['sid'] : '0';
        $hpid = isset($method['hpid']) ? $method['hpid'] : '0';
        
	$sqry='';
	if($sid!=0){
		$sid = explode(',',$sid);
		for($i=0;$i<sizeof($sid);$i++){		
			$itemqry = $ObjDB->QueryObject("SELECT fld_item_id FROM itc_main_tag_mapping 
										WHERE fld_tag_id='".$sid[$i]."' AND fld_access='1' AND fld_tag_type='16'");
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
        
        if($hpid!=0){
	$hpid = explode(',',$hpid);
		for($i=0;$i<sizeof($hpid);$i++){		
			$itemqry = $ObjDB->QueryObject("SELECT fld_id FROM itc_user_master 
								            WHERE fld_id='".$hpid[$i]."' AND fld_delstatus='0'");
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
		var t4 = new $.TextboxList('#form_tags_home', {
			startEditableBit: false,
			inBetweenEditableBits: false,
			plugins: {
				autocomplete: {
					onlyFromValues:true,
					queryRemote: true,
					remote: { url: 'autocomplete.php', extraParams: "oper=search&tag_type=16" },
					placeholder: ''
				}
			},
			bitsOptions:{editable:{addKeys: [188]}}													
		});																	
		
		t4.addEvent('bitAdd',function(bit) {
			fn_loadhome();
		});
		
		t4.addEvent('bitRemove',function(bit) {
			fn_loadhome();
		});					
			
	});	

	function fn_loadhome(){
				var sid = $('#form_tags_home').val();
				$("#homelist").load("users/homepurchase/users-homepurchase.php? #homelist > *",{'sid':sid});
				removesections('#users-homepurchase');
			}
                        
                        
                        $(function(){				
		var t4 = new $.TextboxList('#form_tags_homename', {
			startEditableBit: false,
			inBetweenEditableBits: false,
			plugins: {
				autocomplete: {
					onlyFromValues:true,
					queryRemote: true,
					remote: { url: 'autocomplete.php', extraParams: "oper=searchhomename&tag_type=16" },
					placeholder: ''
				}
			},
			bitsOptions:{editable:{addKeys: [188]}}													
		});																	
		
		t4.addEvent('bitAdd',function(bit) {
			fn_loadhomename();
		});
		
		t4.addEvent('bitRemove',function(bit) {
			fn_loadhomename();
		});					
			
	});	

	function fn_loadhomename(){
				var sid = $('#form_tags_homename').val();
				$("#homelist").load("users/homepurchase/users-homepurchase.php #homelist > *",{'hpid':sid});
				removesections('#users-homepurchase');
			}
                        
                         /* for radio button options in title  */
		$("input[name='types']").click(function() {  
                    var test = $(this).val();
                    $("div.sdesc").hide();
                    $("#types" + test).show(); 
                });
</script>

<section data-type='#users' id='users-homepurchase'>
  <div class='container'>
    <div class='row'>
      <div class='twelve columns'>
      	<p class="darkTitle">Home Purchase</p>
        <p class="darkSubTitle">&nbsp;</p>
      </div>
    </div>
    
    <?php 
          if($sessmasterprfid == 2) 
          { 
     ?>
        <div class="row" id="RadioGroup">
        <div class='twelve columns'>
            <font>Sort/filter the Home Purchase list using: 
            <input type="radio" id="tag" name="types"  value="5" />Tag
            <input type="radio" id="search" name="types" checked="checked" value="6" />Home Purchase Name
            </font>
             &nbsp;&nbsp;
	</div>
    </div> 
<?php }  ?>
      
      
    <div class='sdesc row' style="padding-bottom:20px;display:none;" id="types5">
        <div class='twelve columns'>
			<p class="filterDarkTitle">To filter this list, search by Tag Name.</p>
            <div class="tag_well">
	            <input type="text" name="test3" value="" id="form_tags_home" />
            </div>
        </div>
    </div>
    
    <div class='sdesc row' style="padding-bottom:20px;" id="types6">
        <div class='twelve columns'>
			<p class="filterDarkTitle">To filter this list, search by Home Purchase Name.</p>
            <div class="tag_well">
	            <input type="text" name="test3" value="" id="form_tags_homename" />
            </div>
        </div>
    </div>
    
    <div class='row buttons' id="homelist">
      <a class='skip btn mainBtn' href='#users-newhomepurchase' id='btnusers-homepurchase-newhomepurchase' name="0">
        <div class="icon-synergy-add-dark"></div>
        <div class='onBtn'>New Home<br />Purchase</div>
      </a>
      
      	<?php  
            $qryselecthome = $ObjDB->QueryObject("SELECT fld_id AS id, CONCAT(fld_fname,' ',fld_lname) AS indvname,
													 fn_shortname(CONCAT(fld_fname,' ',fld_lname),1) AS shortname, fld_profile_pic AS photo 
												FROM itc_user_master 
												WHERE fld_profile_id= '5' AND fld_delstatus='0' ".$sqry." order by indvname,shortname");
            while($row = $qryselecthome->fetch_assoc())
            {
				extract($row);
	 	?>
				
                <a class='skip btn mainBtn' href='#users-homepurchase' id='btnusers-homepurchase-homepurchase_actions' name="<?php echo $id.",".$indvname;?>">
                    <div class="icon-synergy-home-purchase">
                    	<?php if($photo != "no-image.png"){ ?>
                    	<img class="thumbimg" src="thumb.php?src=<?php echo __CNTPPPATH__.$photo; ?>&w=40&h=40&q=100" />
                        <?php } ?>
                    </div>
                    <div class='onBtn tooltip' title="<?php echo $indvname;?>"><?php echo $shortname;?></div>
                </a>
		<?php
			}
		?>
    </div>
  </div>
</section>
<?php
	@include("footer.php");
