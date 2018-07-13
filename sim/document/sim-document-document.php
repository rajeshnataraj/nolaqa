<?php
/*------
	Page - sim
	Description:
		Showing the menus related with sim / accounts
		new page created by chandru 
	History:	
		
------*/
@include("sessioncheck.php");

//error_reporting(E_ALL);
//ini_set("display_errors", "1");

$id= isset($method['id']) ? $method['id'] : '';
$ids = explode(',',$id);
$proid = $ids[0];
$catid = $ids[2];
$listicon = $ids[3];
if($catid == " Power "){
	$catid = 1;
}
if($ids[1]!='')
{
	$productname = $ids[1];
}
else
{
	$productname = '';
}

$sid= isset($method['sid']) ? $method['sid'] : '0';
$sqry='';
if($sid!=0){
	$sid = explode(',',$sid);
	for($i=0;$i<sizeof($sid);$i++){	
		$id = explode('_',$sid[$i]);
		if($id[1]=='document'){				
			$sqry.= " AND fld_id =".$id[0];
		}
		else{	
			$itemqry = $ObjDB->QueryObject("SELECT fld_item_id FROM itc_main_tag_mapping 
										   WHERE fld_tag_id='".$sid[$i]."' 
										   AND fld_access='1' AND fld_tag_type='42'");
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
}


?>
<section data-type='#sim-document-document' id='sim-document-document'>
	<script type="text/javascript" charset="utf-8">	
		$.getScript('sim/document/sim-document-document.js');
        $(function(){				
            var t4 = new $.TextboxList('#form_tags_document', {
                startEditableBit: false,
                inBetweenEditableBits: false,
                plugins: {
                    autocomplete: {
                        onlyFromValues:true,
                        queryRemote: true,
                        remote: { url: 'autocomplete.php', extraParams: "oper=search&tag_type=42&document=<?php echo $proid.'~'.$catid; ?>" },
                        placeholder: ''
                    }
                },
                bitsOptions:{editable:{addKeys: [188]}}													
            });																	
            
            t4.addEvent('bitBoxAdd',function(bit) {
                fn_loaddocument();
            });
            
            t4.addEvent('bitRemove',function(bit) {
                fn_loaddocument();
            });					
                
        });	
		
        function fn_loaddocument(){
          
			var sid = $('#form_tags_document').val();
			var pid	= $('#proid').val();
			var catid = $('#cid').val();
			var ids = pid+","+""+","+catid;
			$("#simdocument").load("sim/document/sim-document-document.php #simdocument > *",{'sid':sid,'id':ids});
			removesections('#sim-document-newdocument');
        }
    </script>
	
	
	<div class='container'>
		<div class='row'>
			<div class='twelve columns'>
				<p class="dialogTitle" style="float:left"><?php echo $productname; ?> Documents</p>
				<?php 
					if($sessmasterprfid == 2 or $sessmasterprfid == 8 or $sessmasterprfid == 9)
					{ ?>
						<span style="float:right; cursor: pointer;">
							<image src="img/list.png" width="28"  class="mouse tooltip" id="listview" title="View Document List" onclick="fn_listview('<?php echo $proid.','.$catid.','.$sid;?>');"/>
							<image src="img/user.png" width="28"  class="mouse tooltip" id="iconview" title="View Document Icons" onclick="fn_iconview();"/>
						</span>
			  <?php } ?>
				<p class="dialogSubTitleLight">&nbsp;</p>
			</div>
		</div>
		<div class="sdesc" id="Types6">
			<div class='row' style="padding-bottom:20px;">
				<div class='twelve columns'>
					<p class="filterLightTitle" style="color:#48708a;">To filter this list, search by Document.</p>
					<dl class='field row'>
						<dt>
							<div class="tag_well">
								<input type="text" name="form_tags_document" value="" id="form_tags_document" />
							</div>
						</dt>               
					</dl>
				</div>
			</div>
		</div>
		<div class='row buttons' <?php if($listicon == '1') { ?> style="display:none;" <?php } ?> id="simdocument">
			<?php if($sessmasterprfid == 2) 
			{ ?>
				<div id="large_icon_studentlist" style="float:left;">
					<a class='skip btn mainBtn' href='#sim-document-newdocument' id='btnsim-document-newdocument' name='0,<?php echo $catid.",".$proid ?>'>
						<div class="icon-synergy-add-dark"></div>
						<div class='onBtn'>New Document</div>
					</a>
				</div>
			<?php 
				if($sid!='0')
				{
					$document=$ObjDB->NonQuery("SELECT fld_id as id,fn_shortname(fld_document_name, 1) as docname,fld_document_name AS fullname FROM itc_sim_document WHERE fld_cat_id='".$catid."' AND fld_pro_id='".$proid."' ".$sqry." AND fld_delstatus='0' GROUP BY id ORDER BY docname ASC ");
				}
				else
				{
					$document=$ObjDB->NonQuery("SELECT fld_id as id,fn_shortname(fld_document_name, 1) as docname,fld_document_name AS fullname FROM itc_sim_document WHERE fld_cat_id='".$catid."' AND fld_pro_id='".$proid."' AND fld_global_status='0' AND fld_delstatus='0' GROUP BY id ORDER BY docname ASC");
					
					$globaldoc=$ObjDB->NonQuery("SELECT fld_id as id,fn_shortname(fld_document_name, 1) as docname,fld_document_name AS fullname FROM itc_sim_document WHERE fld_cat_id='".$catid."' AND fld_global_status='1' AND fld_delstatus='0' GROUP BY id ORDER BY docname ASC");
				}
 
				
				if($document->num_rows>0) 
				{	
					while($rowdoc=$document->fetch_assoc())
					{
						extract($rowdoc);
					?>
					<div id="large_icon_studentlist" style="float:left;">
						<a class='skip btn mainBtn' href='#sim-document-action' id='btnsim-document-action' name='<?php echo $proid.",".$catid.",".$id.",".$listicon;?>'>
							<div class="icon-documents" style="padding-top:10%;"></div>
							<div class='onBtn' title="<?php echo $fullname; ?>"><?php echo $docname; ?></div> 
						</a>
					</div>
					<?php

					}
				}
				
				if($globaldoc->num_rows>0) 
				{	
					while($rowglobal=$globaldoc->fetch_assoc())
					{
						extract($rowglobal);
					?>
					<div id="large_icon_studentlist" style="float:left;">
						<a class='skip btn mainBtn' href='#sim-document-action' id='btnsim-document-action' name='<?php echo $proid.",".$catid.",".$id.",".$listicon;?>'>
							<div class="icon-global_documents" style="padding-top:10%;"></div>
							<div class='onBtn' title="<?php echo $fullname; ?>" ><?php echo $docname; ?></div> 
						</a>
					</div>
					<?php

					}
				}
			}
			else
			{ 
				if($sid!='0')
				{
					$document=$ObjDB->NonQuery("SELECT fld_id as id,fn_shortname(fld_document_name, 1) as docname,fld_document_name AS fullname FROM itc_sim_document WHERE fld_cat_id='".$catid."' AND fld_pro_id='".$proid."' ".$sqry." AND fld_delstatus='0' GROUP BY id ORDER BY docname ASC ");
				}
				else
				{
					$document=$ObjDB->NonQuery("SELECT fld_id as id,fn_shortname(fld_document_name, 1) as docname,fld_document_name AS fullname FROM itc_sim_document WHERE fld_cat_id='".$catid."' AND fld_pro_id='".$proid."' AND fld_global_status='0' AND fld_delstatus='0' GROUP BY id ORDER BY docname ASC");
					
					$globaldoc=$ObjDB->NonQuery("SELECT fld_id as id,fn_shortname(fld_document_name, 1) as docname,fld_document_name AS fullname FROM itc_sim_document WHERE fld_cat_id='".$catid."' AND fld_global_status='1' AND fld_delstatus='0' GROUP BY id ORDER BY docname ASC");
				}

				
				if($document->num_rows>0) 
				{	
					while($rowdoc=$document->fetch_assoc())
					{
						extract($rowdoc);
					?>
					<div id="large_icon_studentlist" style="float:left;">
						<a class='skip btn main'  href='#sim-document'  onclick="fn_viewdocument('<?php echo $id;?>');">
							<div class="icon-documents" style="padding-top:10%;"></div>
							<div class='onBtn' title="<?php echo $fullname; ?>"><?php echo $docname; ?></div> 
						</a>
					</div>
					<?php

					}
				}
				
				if($globaldoc->num_rows>0) 
				{	
					while($rowglobal=$globaldoc->fetch_assoc())
					{
						extract($rowglobal);
					?>
					<div id="large_icon_studentlist" style="float:left;">
						<a class='skip btn main'  href='#sim-document'  onclick="fn_viewdocument('<?php echo $id;?>');">
							<div class="icon-global_documents" style="padding-top:10%;"></div>
							<div class='onBtn' title="<?php echo $fullname; ?>" ><?php echo $docname; ?></div> 
						</a>
					</div>
					<?php

					}
				}	
			}
				
			?>
		</div>
		<div class="row" id="simdocumenticon" <?php if($listicon == '1') { ?> style="display:block;" <?php } ?> style="display:none;">
			<div class='span10 offset1'>
				 <table class='table table-hover table-striped table-bordered setbordertopradius' id="mytable" >
					<thead class='tableHeadText' >
						<tr style="cursor:default;">
							<th <?php if($sessmasterprfid == 2) {?>width="85%"<?php } else {?>width="100%"<?php } ?>>Document name</th>
							<?php if($sessmasterprfid == 2) {?>
							<th class='centerText'>actions</th><?php }?>
						</tr>
					</thead>
					<tbody>
						<tr class="mainBtn" id="btnsim-document-newdocument" name="0,<?php echo $catid.",".$proid; ?>,1">
							<?php if($sessmasterprfid == 2) {?>
							<td colspan="6" class="createnewtd" style="border-left:none;"> <span class="icon-synergy-create small-icon"></span><span>&nbsp;&nbsp;&nbsp;Add a new document </span></td>
							<?php } ?>
						</tr>
					</tbody>
				</table>
				<?php 
				if($sid!='0')
				{
					$document1=$ObjDB->NonQuery("SELECT fld_id as id,fn_shortname(fld_document_name, 1) as docname,fld_document_name AS fullname FROM itc_sim_document WHERE fld_cat_id='".$catid."' AND fld_pro_id='".$proid."' AND fld_delstatus='0' GROUP BY id ORDER BY docname ASC ".$sqry." ");
				}
				else
				{
					$document1=$ObjDB->NonQuery("SELECT fld_id as id,fn_shortname(fld_document_name, 1) as docname,fld_document_name AS fullname FROM itc_sim_document WHERE fld_cat_id='".$catid."' AND fld_pro_id='".$proid."' AND fld_global_status='0' AND fld_delstatus='0' GROUP BY id ORDER BY docname ASC");
					
					$globaldoc1=$ObjDB->NonQuery("SELECT fld_id as id,fn_shortname(fld_document_name, 1) as docname,fld_document_name AS fullname FROM itc_sim_document WHERE fld_cat_id='".$catid."' AND fld_global_status='1' AND fld_delstatus='0' GROUP BY id ORDER BY docname ASC");
				}
				?>
				<div style="max-height:400px;width:100%;overflow:auto;" id="tablecontents3" >
					<table style="margin-bottom:0px;" class='table table-hover table-striped table-bordered bordertopradiusremove' id="mytable">
						<tbody>
							<?php							
							if($document1->num_rows>0) 
							{	
								while($rowdoc=$document1->fetch_assoc())
								{
									extract($rowdoc);
									?>
									<tr>
										<?php if($sessmasterprfid == 2) 
										{?>
											<td width="85%"><span class="icon-documents small-icon"></span><span>&nbsp;&nbsp;&nbsp;<?php echo $fullname; ?></span></td>
											<?php 
										} 
										else 
										{?>
											<td width="100%" onclick="fn_viewdocument('<?php echo $id;?>');"><span class="icon-documents small-icon"></span><span>&nbsp;&nbsp;&nbsp;<?php echo $fullname; ?></span></td>
											<?php 
										} 
										if($sessmasterprfid == 2) 
										{?>
											<td class='centerText'>                                
												<div class="icon-synergy-view mainBtn tooltip" href='#sim-document' onclick="fn_viewdocument('<?php echo $id;?>');" style="float:left; font-size:21px;padding-right: 10px;" title="View"></div>
												<div class="icon-synergy-edit mainBtn tooltip" href='#sim-document-newdocument' id="btnsim-document-newdocument" title="Edit" name="<?php echo $id.",".$catid.",".$proid;?>,1" style="float:left; font-size:18px;padding-right: 10px;"></div>
												<div class="icon-synergy-trash tooltip" title="Delete" style="float:left; font-size:18px;padding-right: 10px;" href='#sim-document' onclick="fn_deletedocument(<?php echo $id.",".$proid.",".$catid;?>,1)"></div>    
											</td>
											<?php 
										} ?>
									</tr>
									<?php 
								}
							}
							if($globaldoc1->num_rows>0) 
							{	
								while($rowdoc=$globaldoc1->fetch_assoc())
								{
									extract($rowdoc);
									?>
									<tr>
										<?php if($sessmasterprfid == 2) 
										{?>
											<td width="85%"><span class="icon-global_documents small-icon"></span><span>&nbsp;&nbsp;&nbsp;<?php echo $fullname; ?></span></td>
											<?php 
										} 
										else 
										{?>
											<td width="100%" onclick="fn_viewdocument('<?php echo $id;?>');"><span class="icon-global_documents small-icon"></span><span>&nbsp;&nbsp;&nbsp;<?php echo $fullname; ?></span></td>
											<?php 
										} 
										if($sessmasterprfid == 2) 
										{?>
											<td class='centerText'>                                
												<div class="icon-synergy-view mainBtn tooltip" href='#sim-document' onclick="fn_viewdocument('<?php echo $id;?>');" style="float:left; font-size:21px;padding-right: 10px;" title="View"></div>
												<div class="icon-synergy-edit mainBtn tooltip" href='#sim-document-newdocument' id="btnsim-document-newdocument" title="Edit" name="<?php echo $id.",".$catid.",".$proid;?>,1" style="float:left; font-size:18px;padding-right: 10px;"></div>
												<div class="icon-synergy-trash tooltip" title="Delete" style="float:left; font-size:18px;padding-right: 10px;" href='#sim-document' onclick="fn_deletedocument(<?php echo $id.",".$proid.",".$catid;?>,1)"></div>    
											</td>
											<?php 
										} ?>
									</tr>
									<?php 
								}
							}?>
							
						</tbody>
					</table>
				</div>

			</div>
		</div>
		<input type="hidden" name="proid" id="proid" value="<?php echo $proid;?>">
		<input type="hidden" name="cid" id="cid" value="<?php echo $catid;?>">
	</div>
</section>
<?php
	@include("footer.php");
