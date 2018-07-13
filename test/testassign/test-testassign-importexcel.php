<?php
/* updated by: Chandrasekar PHP Programmer

updated on:31/05/2016(Selecting answer type)


*/
@include("sessioncheck.php");

$oper = isset($method['oper']) ? $method['oper'] : '';
$editid = isset($method['id']) ? $method['id'] : '';
?>
<script language="javascript" type="text/javascript">
	$.getScript("test/testassign/test-testassign-quscreation.js");
	
	<?php $timestamp = time();?>
	$('#file_upload').uploadify({
				'formData'     : {
					'timestamp' : '<?php echo $timestamp;?>',
					'token'     : '<?php echo md5('nanonino' . $timestamp);?>',
					'oper'      : 'importquestionbank' 
				},
				 'height': 40,
				 'width':185,
				//'fileSizeLimit' : '5MB',
				'swf'      : 'uploadify/uploadify.swf',
				'uploader' : 'uploadify/uploadify_user.php',
				'multi':false,
				'buttonText' : 'Select File',
				'removeCompleted' : true,
				'fileTypeExts' : '*.xls; *.xlsx; *.csv;',
				'onUploadSuccess' : function(file, data, response) {

					fn_importexcelsheet(data);
				 },
				 'onUploadProgress' : function(file, bytesUploaded, bytesTotal, totalBytesUploaded, totalBytesTotal) {
				   $('#userphoto').addClass('dim');   
				}
				
			});
	
	$('#multiplechoice').hide();
	
</script>

<section data-type='#users-individuals' id='users-individuals-importstudents'>
<div class='container'>
	<div class='row formBase'>
		<div class='eleven columns centered insideForm'>
			<form name="form1" id="form1" enctype="multipart/form-data">
				<!-- starts to select answer type -->
				<div class='seven columns' style="">
				Select Answer Type
				<br/>
					<dl class='field row'>   
						<dt class='dropdown'>
							<style>
							.dropdown .caret1
							{
							float: left;
							margin-top: 10px;
							}
							.selectbox-options
							{
							width:59%;
							}
							.selectbox .selectbox-toggle{
							width:59%;
							}
							</style>   
							<div class="selectbox">
								<input type="hidden" name="selectanswer" id="selectanswer" value="" onChange="fn_loadanswertype(this.value)"/>
								<a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
									<span class="selectbox-option input-medium" data-option="" id="searchanswer"  style="width:275px;">Select Answer </span>
									<b class="caret1"></b>
								</a>
								<div class="selectbox-options">
									<input type="text" class="selectbox-filter"  placeholder="Search Answer ">
									<ul role="options" style="width:270px;">
										<li><a tabindex="-1"  href="#" data-option="">Select Answer</a></li>
										<input type="hidden" name="answerids" id="answerids" value="" />
										<?php
											$qryanswer = $ObjDB->QueryObject("SELECT fld_id as answerid,fld_answer_types as answertype FROM itc_question_answer_types");
											 if($qryanswer->num_rows>0)
											 {
												 $j=1;
												 while($rowanswerdetails = $qryanswer->fetch_assoc())
												 {
													extract($rowanswerdetails);
													if($answerid == '1')
													{?>
														 <li><a tabindex="-1"  href="#" data-option="<?php echo $answerid;?>"><?php echo $answertype; ?></a></li>
														 <?php
														 $j++;
														 ?>
														 <input type="hidden" name="answerids" id="answerids" value="<?php echo $answerid; ?>" />
													<?php
													}
												 }
											 }
											 else
											 { ?>
												<div class="wizardReportData">No Answer</div><?php
											 }
										?>
									</ul>
								</div>
							</div>
						</dt>                                       
					</dl>
				</div>
				<!-- ends to select amswer type -->
				<div id="multiplechoice">
					<div class="row rowspacer" style="float:left;"> Import New Excel Sheet: </div>
					<div class="three columns" style="margin-top:0px; margin-left:0px;">
						<div><a id="file_upload"> </a></div>
						<br />(File type: .xls, .xlsx) 
					</div>
					<div class="six" style="float:left"> Please <a href="Import_multichoice.xls" style="font-weight:bold">click here to download sample file</a> to import the Questions. The fields Question Text, Choice Answer, Correct Answer(s) are the required and other fields are optional. </div>
				</div>
			</form>
			<input type="hidden" id="hidlisttype" name="hidlisttype" value="all" />
		</div>
	</div>
</div>
</section>
<?php
	@include("footer.php");