<?php
@include("sessioncheck.php");
$cid = isset($method['id']) ? $method['id'] : '0';

if($cid==0)
{
	$categoryname='';	
 	$categorycode='';
}
else
{
 	$categoryname = $ObjDB->SelectSingleValue("SELECT fld_category_name FROM itc_nondigicontent_category WHERE fld_id='".$cid."' ANd fld_delstatus='0'");
}

?>

<style>
.text1 {
	background: #fff none repeat scroll 0 0;
	border: 1px solid #b7b7b7;
	border-radius: 4px;
	box-shadow: 0 2px 3px #ccc inset, 0 1px 0 #f4fff6;
	font-size: 14px;
	outline: medium none !important;
	padding: 8px 10px;
	position: relative;
	margin-top:8px;
}
</style>
<script type='text/javascript'>
	$.getScript("library/nondigicontent/library-nondigicontent-category.js");
</script>
<section data-type='#2home' id='library-nondigicontent-newcategory'>
	<div class='container'>
    	<div class='row'>
      		<div class='twelve columns'>
            	<p class="dialogTitle"><?php if($cid == 0){ echo "New Category";} else { echo "Edit Category";} ?></p>
				<p class="dialogSubTitleLight">&nbsp;</p>
        		<h1></h1>
      		</div>
    	</div>
    
        <div class='row formBase'>
            <div class='eleven columns centered insideForm'>
                <form method='post' name="validate" id="validate">
                    <div class="row">
                        <div class="six columns">
                            Category Name<span class="fldreq">*</span> 
                            <dl class='field row'>
                                <dt class='text'>
                                    <input id="catname" name="catname"  placeholder='category name' tabindex="1" type='text' value="<?php echo $categoryname; ?>">
                                </dt>
                            </dl> 
                        </div>
                    </div>
                    <div class="row rowspacer">
                        
                    <?php if($cid != '0')
                    {?>
                        <div class="four columns">
                            <p class='btn primary twelve columns'>
                                <a tabindex="24" onclick="fn_cancel(<?php echo $cid;?>,'<?php echo $categoryname;?>')">Cancel</a>
                            </p>
                        </div>
                            <div class="four columns" id="userphoto">
                                <p class='btn secondary twelve columns'>
                                    <a tabindex="24" onclick="fn_deletecategory(<?php echo $cid;?>)">Delete</a>
                                </p>
                            </div>
                            <div class="four columns" id="userphoto">
                                <p class='btn secondary twelve columns'>
                                        <a tabindex="24" onclick="fn_createcategory(<?php echo $cid;?>)">Finish</a>
                                </p>
                        </div>
                            <?php 
                    }
                    else if($cid == '0')
                    {?>
                        <div class="six columns">
                            <p class='btn primary twelve columns'>
                                <a tabindex="24" onclick="fn_cancel()">Cancel</a>
                            </p>
                        </div>
                        <div class="six columns" id="userphoto">
                            <p class='btn secondary twelve columns'>
                                <a tabindex="24" onclick="fn_createcategory(<?php echo $cid;?>)">Finish</a>
                            </p>
                        </div>
                    <?php
                    }?>
                       
                    </div>
                </form>
            	<input id="hidtxtid" name="hidtxtid" type='hidden' value="0">
            </div>
        </div>
	</div>
</section>
<?php
	@include("footer.php");