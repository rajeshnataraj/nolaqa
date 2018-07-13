<?php 
/*------
		Page - library-sosunits
		Description:
		Show the science of speed units list
		
		Actions Performed:
			New unit - Create a new unit
		
		History:
				
------*/
	
	@include("sessioncheck.php");
	$sid = isset($method['sid']) ? $method['sid'] : '0';
	$sqry='';
	if($sid!=0){
		$sid = explode(',',$sid);
		for($i=0;$i<sizeof($sid);$i++){
			$id = explode('_',$sid[$i]);
			if($id[1]=='document'){
				$sqry.= " and c.fld_id =".$id[0];
			}		
			else{				
				$itemqry = $ObjDB->QueryObject("select fld_item_id from itc_main_tag_mapping where fld_tag_id='".$sid[$i]."' and fld_access='1' and fld_tag_type='40'");
				$sqry = "and (";
				$j=1;
				while($itemres = $itemqry->fetch_assoc()){
					extract($itemres);
					if($j==$itemqry->num_rows){
						$sqry.=" c.fld_id=".$fld_item_id.")";
					}
					else{
						$sqry.=" c.fld_id=".$fld_item_id." or";
					}
					$j++;
				}
			}
		}		
	}
?>

<section data-type='2home' id='library-documents'> 
  <script type="text/javascript" charset="utf-8">		
		$.getScript("library/documents/library-documents.js");
		$(function(){				
        	var t4 = new $.TextboxList('#form_tags_documents', {
                	startEditableBit: false,
                	inBetweenEditableBits: false,
                	plugins: {
                    	autocomplete: {
                       		onlyFromValues:true,
                        	queryRemote: true,
                        	remote: { url: 'autocomplete.php', extraParams: "oper=search&tag_type=40&subject=1&course=1&unit=1" },
                        	placeholder: ''
                    	}
                	},
                	bitsOptions:{editable:{addKeys: [188]}}													
            	});																	
            
            	t4.addEvent('bitAdd',function(bit) {
                	fn_loadunit();
            	});
            
            	t4.addEvent('bitRemove',function(bit) {
                	fn_loadunit();
            	});					
        });	
            
        function fn_loadunit(){
            var sid = $('#form_tags_documents').val();
            $("#documentlist").load("library/documents/library-documents.php #documentlist > *",{'sid':sid});
            removesections('#library-documents');
        }
		
		
    </script>
  <div class='container'>
    <div class='row'>
      <div class='twelve columns'>
        <p class="darkTitle">Documents</p>
        <p class="darkSubTitle"></p>
      </div>
    </div>
    <div class='row'>
      <div class='twelve columns'>
        <!--<p class="filterLightTitle">To filter this list, search by
          <?php if($sessmasterprfid==2){?>
          Tag Name and
          <?php }?>
          Document Name.</p>-->
          <p class="filterLightTitle">To see the list of Documents available, search by Document name, 
          <?php if($sessmasterprfid==2){?>
          Tag name, 
          <?php }?>
          or browse through the options below.</p>
        <div class="tag_well">
          <input type="text" name="test3" value="" id="form_tags_documents" />
        </div>
      </div>
    </div>
    <div class='row buttons rowspacer' id="documentlist">
      <?php if($sessmasterprfid == 2) { ?>
      <a class='skip btn mainBtn' href='#library-documents' id='btnlibrary-documents-newdocuments'>
      <div class='icon-synergy-add-dark'></div>
      <div class='onBtn'>New document</div>
      </a>
      <?php 
			}
			if($sessmasterprfid == 2)
			{
                            
				$qry = "SELECT c.fld_id AS docid, c.fld_unit_id AS content_id, c.fld_document_name AS docname, fn_shortname (c.fld_document_name, 1) AS shortname, 
							   c.fld_document_icon AS docicon 
						FROM itc_sosdocument_master AS c 
						WHERE c.fld_delstatus = '0' ".$sqry." 
						GROUP BY c.fld_id ";
			}	
			
			
			$qrytogetallunits = $ObjDB->QueryObject($qry);
			
			if($qrytogetallunits->num_rows>0)
			{
				while($res=$qrytogetallunits->fetch_assoc()){
					extract($res);
                    $contentManager = new contentManager($content_id, 'sos');
                    if($contentManager->disabled) $btn="btnOff";
                    else $btn="mainBtn";
			?>
      <a class='skip btn  <?=$btn;?>' onclick="checkContent(this)" data-category="sos" data-content-id="<?=$content_id;?>" href='#library-documents' id='btnlibrary-documents-actions' name="<?php echo $docid.','.$docname;?>">
      <div class="icon-synergy-lessons"> <img class="thumbimg" src="thumb.php?src=<?php echo __CNTUNITICONPATH__.$docicon; ?>&w=40&h=40&q=100" /> </div>
      <div class='onBtn tooltip' title="<?php echo $docname;?>"><?php echo $shortname; ?></div>
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
