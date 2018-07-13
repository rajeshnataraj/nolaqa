<?php
/*
 * created by - Mohan.M PHP programmer
 * creating for new and edit Materials
 * DB:itc_mis_materials_master
 */
ini_set('display_errors', '0');
@include("sessioncheck.php");

$materialid = isset($method['id']) ? $method['id'] : '0';
$material_icon='';
$materialname='';
$material_desc='';
$uploadmaterialicon = '';

    if($materialid != '' and $materialid!='undefined'){
            $pageTitle = "Edit Material";
            $btnclick = "fn_cancel('library-missionmaterials-actions')";
            $btnvalue = "Update Material";
            $btncancel = "Cancel";
    }
    else{
            $pageTitle = "New Material";
            $btnclick = "fn_cancel('library-missionmaterials')";
            $btnvalue = "Create Material";
            $btncancel = "Cancel";
            $unitsid=0;
    }
    /* The following query used to get the material id,name and material desc from tables */

    $qry_materials_list = $ObjDB->QueryObject("SELECT fld_id AS materialid, fld_materials AS materialname, fld_mat_desc AS material_desc, fld_catalog_url AS catalogurl, fld_thumbimg_url AS material_icon, fld_upload_path AS uploadmaterialicon
                                            FROM itc_mis_materials_master WHERE fld_id='".$materialid."' AND fld_delstatus='0'");

    if($qry_materials_list->num_rows>0)
    {
            $material_details = $qry_materials_list->fetch_assoc();		
            extract($material_details);	
    }
?>
<!--Script for the Tag Well-->
<script language="javascript" type="text/javascript" charset="utf-8">
    $(function(){
        var t4 = new $.TextboxList('#form_tags_mewmaterial', 
        {
                unique: true, plugins: {autocomplete: {}},
                bitsOptions:{editable:{addKeys: [188]}}	});
            <?php 
                if($materialid != '' and $materialid!='undefined'){
                        $qrytag = $ObjDB->QueryObject("SELECT a.fld_id AS tagid,a.fld_tag_name AS tagname 
                                                      FROM itc_main_tag_master AS a, itc_main_tag_mapping AS b 
                                                                                  WHERE a.fld_id=b.fld_tag_id AND b.fld_tag_type='34' 
                                                                                        AND b.fld_access='1' AND a.fld_created_by='".$uid."' 
                                                                                                AND a.fld_delstatus='0' AND b.fld_item_id='".$materialid."'");

                        if($qrytag->num_rows > 0) {

                                while($restag = $qrytag->fetch_assoc()){

                                        extract($restag); ?>
                        t4.add('<?php echo $ObjDB->EscapeStrAll($tagname); ?>','<?php echo $tagid; ?>');				
                      <?php }
                        }
                }
        ?>		
        t4.getContainer().addClass('textboxlist-loading');				
        $.ajax({url: 'autocomplete.php', data: 'oper=new', type:"POST", dataType: 'json', success: function(r){
                t4.plugins['autocomplete'].setValues(r);
                t4.getContainer().removeClass('textboxlist-loading');					
        }});						
    });
</script>

<section data-type='2home' id='library-missionmaterials-newmaterial'>
    <div class='container'>
    	<!--Load the Material Name / New material-->
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle"><?php echo $pageTitle; ?></p>
                <p class="dialogSubTitle">&nbsp;</p>
            </div>
        </div>
        
        <!--Load the material Form-->
        <div class='row formBase rowspacer'>
            <div class='eleven columns centered insideForm'>
                <form id="exp_materialform1" name="exp_materialform1">
                    <div class='row'>
                        <div class='six columns'> <!-- Material Details -->
                            Material<span class="fldreq">*</span>
                            <dl class='field row'>
                                <dt class='text'>
                                    <input placeholder='Material Name' required='' type='text' id="materialname" name="materialname" value="<?php echo $materialname; ?>" onBlur="$(this).valid();">
                                </dt>                                
                            </dl>
                            Catalog URL
                            <dl class='field row'>
                                <dt class='text'>
                                        <input placeholder='Catalog URL' type='text' id="catalogurl" name="catalogurl" value="<?php echo $catalogurl; ?>" onBlur="$(this).valid();">
                                </dt>
                            </dl>

                                <p class='lableRight'>To upload an image you can either provide the url of the material image , </p> 
                                <p class='lableRight'> or upload an image.</p> 
                                 <p></p>


                            Materialimage URL<span class="fldreq">*</span>
                            <dl class='field row'>
                                <dt class='text'>
                                        <input placeholder='Thumbimage URL'  type='text' id="thumbimgurl" name="thumbimgurl" class="sendimg" value="<?php echo $material_icon; ?>" onBlur="$(this).valid();">
                                </dt>
                            </dl>

                             <div class='row rowspacer'>
                                 <div class='seven columns'>  
                                        <p class='lableRight'>Upload your icon using one of the following formats: .jpeg, .bmp, .gif, .png. </p>

                                     <dl class='field row'>

                                        <input id="material_upload"  name="material_upload" type="file" multiple>
                                        <div id="queue"></div>
                                    </dl>
                                </div>

                                 <div class='four columns'>  
                                    <div id="uploadmaterialicon"  style="margin-top:-5px; margin-left:65px;">
                                        <?php if($uploadmaterialicon==''){?>
                                        <div class="iconPreview"></div>
                                        <?php } ?>
                                        <?php if($uploadmaterialicon!=''){?>
                                            <img src="thumb.php?src=<?php echo __CNTMATERIALICONPATH__.$uploadmaterialicon; ?>&w=100&h=106&q=100" />
                                        <?php } ?>
                                    </div> 
                                       <input type="hidden" name="hiduploadfile" id="hiduploadfile" class="sendimg" value="<?php echo $uploadmaterialicon;?>" />

                                 </div>
                            </div>

                        </div>
                        <div class='six columns'> <!-- Textarea - Lesson Description -->
                            Description<span class="fldreq">*</span>
                            <dl class='field row'>
                                <dt class='text'>
                                    <textarea placeholder='Tell us about your new material' required='' id="materialdescription" name="materialdescription" ><?php echo $material_desc; ?></textarea>
                                </dt>                                
                            </dl>	
                        </div>
                    </div>
                    
                    <div class='row rowspacer'> <!-- Tag Well -->
                       <div class='twelve columns'>
                               To create a new tag, type a name and press Enter.
                           <div class="tag_well">
                               <input type="text" name="form_tags_mewmaterial" value="" id="form_tags_mewmaterial" />
                           </div>
                       </div>
                   </div>

                    <div class='row rowspacer' style="padding-top:20px;">
                       <div class='six columns'>
                           <p class='btn primary twelve columns'>
                               <a onclick="<?php echo $btnclick; ?>"><?php echo $btncancel;?></a>
                           </p>
                       </div>
                       <div class='six columns' id="savebtnmaterials" >
                           <p class='btn secondary twelve columns'>
                               <a onclick="fn_creatematerials(<?php echo $materialid;?>,<?php echo $sessmasterprfid; ?>)"><?php echo $btnvalue;?></a>
                           </p>
                       </div>
                   </div>
                </form>
            </div>
        </div>
    </div>
</section>
<script>
    <?php $timestamp = time();?>
        jQuery.validator.addMethod("require_from_group", function (value, element, options) {
        var numberRequired = options[0];
        var selector = options[1];
        var fields = $(selector, element.form);
        var filled_fields = fields.filter(function () {
            // it's more clear to compare with empty string
            return $(this).val() != "";
        });
        var empty_fields = fields.not(filled_fields);
        // we will mark only first empty field as invalid
        if (filled_fields.length < numberRequired && empty_fields[0] == element) {
            return false;
        }
        return true;
        // {0} below is the 0th item in the options field
    }, jQuery.format("'Please type either Materialimage URL / Upload image."));

        $('#material_upload').uploadify({
            'formData'     : {
                    'timestamp' : '<?php echo $timestamp;?>',
                    'token'     : '<?php echo md5('nanonino' . $timestamp);?>',
                    'oper'      : 'materialicon' 
            },
                   'height': 30,
                   'width':300,
                   'fileSizeLimit' : '2MB',
                   'swf'      : 'uploadify/uploadify.swf',
                   'uploader' : '<?php echo _CONTENTURL_; ?>uploadify.php',
                   'multi':false,
                   'buttonText' : 'Upload',
                   'removeCompleted' : true,
                   'fileTypeExts' : '*.gif; *.jpg; *.png; *.jpeg; *.bmp;',
                   'onUploadSuccess' : function(file, data, response) {
                           // alert(data);
                    $("#uploadmaterialicon").show();
                    $('#hiduploadfile').val(data);
                    $('#uploadmaterialicon').html('');
                    $('#uploadmaterialicon').html('<img src="thumb.php?src=<?php echo __CNTMATERIALICONPATH__; ?>'+data+'&w=100&h=106&q=100" />');
                    $('#savebtnmaterials').removeClass('dim');   

        },
           'onUploadError' : function(file, errorCode, errorMsg, errorString) {
            alert('The file ' + file.name + ' could not be uploaded: ' + errorString+'  '+errorMsg+'  '+errorCode);
        },
           'onUploadProgress' : function(file, bytesUploaded, bytesTotal, totalBytesUploaded, totalBytesTotal) {
            $('#savebtnmaterials').addClass('dim');   
        }

        });
        
        $("#exp_materialform1").validate({
            ignore: "",
            errorElement: "dd",
            errorPlacement: function(error, element) {
                    $(element).parents('dl').addClass('error');
                    error.appendTo($(element).parents('dl'));	
                    error.addClass('msg');
            },
            groups: {
            names: "thumbimgurl hiduploadfile"
             }, 
            rules: {
                    materialname: { required: true },                  
                    thumbimgurl: {
                        require_from_group: [1, ".sendimg"],
                        url: true,
                        accept:"jpg|png|bmp|gif|jpeg"
                    },
                    catalogurl: {
                         url: true,
                        
                    },
                    hiduploadfile: {
                        require_from_group: [1, ".sendimg"]
                    }
                    
            }, 
            messages: { 
                    materialname: { required: "Please type Material Name" },                   
                  
            },
            highlight: function(element, errorClass, validClass) {
                    $(element).addClass(errorClass).removeClass(validClass);
            },
            unhighlight: function(element, errorClass, validClass) {
                    if($(element).attr('class') == 'error'){
                            $(element).parents('dl').removeClass(errorClass);
                            $(element).removeClass(errorClass).addClass(validClass);
                    }
            },
            onkeyup: false,
            onblur: true
        });
        
</script>
<?php
	@include("footer.php");