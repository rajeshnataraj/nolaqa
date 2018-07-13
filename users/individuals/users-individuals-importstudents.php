<?php
/* updated by: Vijayalakshmi PHP Programmer

updated on:21/11/2014(Selecting class name)


*/
@include("sessioncheck.php");

$oper = isset($method['oper']) ? $method['oper'] : '';
$editid = isset($method['id']) ? $method['id'] : '';
?>
    <script language="javascript" type="text/javascript">
        $.getScript("users/individuals/users-individuals-importstudents.js");

        <?php $timestamp = time();?>
        $('#file_upload').uploadify({
            'formData'     : {
                'timestamp' : '<?php echo $timestamp;?>',
                'token'     : '<?php echo md5('nanonino' . $timestamp);?>',
                'oper'      : 'importstudents'
            },
            'height': 40,
            'width':185,
            'fileSizeLimit' : '2MB',
            'swf'      : 'uploadify/uploadify.swf',
            'uploader' : 'uploadify/uploadify_user.php',
            'multi':false,
            'buttonText' : 'Select File',
            'removeCompleted' : true,
            'fileTypeExts' : '*.xls; *.xlsx; *.csv;',
            'onUploadSuccess' : function(file, data, response) {

                fn_importstudents(data);
            },
            'onUploadProgress' : function(file, bytesUploaded, bytesTotal, totalBytesUploaded, totalBytesTotal) {
                $('#userphoto').addClass('dim');
            }

        });

    </script>

    <section data-type='#users-individuals' id='users-individuals-importstudents'>
        <div class='container'>
            <div class='row'>
                <div class='twelve columns'>
                    <p class="dialogTitle">Import Students</p>
                    <p class="dialogSubTitleLight">To import a student or students, select the class first, and then click "Select File".</p>
                </div>
            </div>
            <div class='row formBase'>

                <div class='eleven columns centered insideForm'>

                    <!-- starts to select class name -->
                    <div class='seven columns' style="">
                        Select Class Name
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
                                <input type="hidden" name="selectclass" id="selectclass" value="" />
                                <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                    <span class="selectbox-option input-medium" data-option="" id="searchclass"  style="width:275px;">Select Class</span>
                                    <b class="caret1"></b>
                                </a>
                                <div class="selectbox-options">
                                    <input type="text" class="selectbox-filter"  placeholder="Search Class ">
                                    <ul role="options" style="width:270px;">
                                        <li><a tabindex="-1"  href="#" data-option="">Select Class</a></li>
                                        <input type="hidden" name="classids" id="classids" value="" />
                                        <?php
                                        $qryclass = $ObjDB->QueryObject("SELECT fld_class_name AS classname, fn_shortname(fld_class_name,1) AS shortname, fld_id AS classid, fld_lab AS classtypeid, 
                          fld_step_id AS stepid, fld_flag AS flag 
                        FROM itc_class_master 
                        WHERE fld_delstatus='0' AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' 
                          AND (fld_created_by='".$uid."' OR fld_id IN(SELECT fld_class_id FROM itc_class_teacher_mapping WHERE fld_teacher_id='".$uid."' 
                          AND fld_flag='1')) group by classname");
                                        if($qryclass->num_rows>0){
                                            $j=1;
                                            while($rowclassdetails = $qryclass->fetch_assoc())
                                            {
                                                extract($rowclassdetails);
                                                ?>
                                                <li><a tabindex="-1"  href="#" data-option="<?php echo $classid;?>"><?php echo $classname; ?></a></li>
                                                <?php
                                                $j++;
                                                ?>
                                                <input type="hidden" name="classids" id="classids" value="<?php echo $classid; ?>" />
                                            <?php  }
                                        }
                                        else
                                        { ?>
                                            <div class="wizardReportData">No Classes</div><?php
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                            </dt>
                        </dl>
                    </div>



                    <!-- ends to select class name -->
                    <form name="form1" id="form1" enctype="multipart/form-data">
                        <div class="row rowspacer">
                            <div class="row rowspacer" style="float:left;">
                                <input id="chkboxuser" type="checkbox" value="" name="chkbox" checked>Auto generate username
                                <input id="chkboxpass" type="checkbox" value="" name="chkbox">Auto generate password
                            </div>
                            <div class="row rowspacer" style="float:left;">
                                <?php
                                // Developer: Barney
                                // Date: 2017-02-17
                                // Description: Changed text for instructions
                                // Ticket: #23009
                                ?>
                                <ul>
                                    <li>Required columns: First Name, Last Name, Username, and Password. The rest of the fields are optional.</li>
                                    <li>Selecting Auto generate username, and Auto generate password, or both, will overwrite any Username and Password you are attempting to import.</li>
                                </ul>
                            </div>
                            <div class="six" style="float:left;cursor:pointer;font-weight:bold;" onclick="fn_link();">Click here to download a sample import spreadsheet.</div>
                            <div class="row rowspacer" style="float:left;"> Import New Students: </div>
                            <div class="three columns" style="margin-top:0px; margin-left:0px;">
                                <div><a id="file_upload"> </a></div>
                                <br />(File type: .xls, .xlsx, .csv)
                            </div>
                        </div>
                        <div class="row rowspacer" id="duplicate">
                        </div>
                    </form>
                    <input type="hidden" id="hidlisttype" name="hidlisttype" value="all" />
                </div>
            </div>
        </div>
    </section>
<?php
@include("footer.php");