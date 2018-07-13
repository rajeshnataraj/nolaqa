<?php
@include("sessioncheck.php");
@include("UserManager.php");

$tempid = isset($method['id']) ? $method['id'] : '';
$tempid=explode(",",$tempid);
$classid=(int)($tempid[1]);

//Students should not have access to this page
if ($_SESSION['user_profile'] == 10){
    exit;
}

$students = array();

$students_query = "SELECT b.fld_id, b.fld_fname, b.fld_lname, b.fld_username FROM itc_class_student_mapping AS a LEFT JOIN itc_user_master AS b ON a.fld_student_id = b.fld_id WHERE a.fld_class_id = $classid AND a.fld_flag=1";
$students_query_object = $ObjDB->QueryObject($students_query);

while ($row = $students_query_object->fetch_assoc()){
    $students[] = $row;
}

$classname = '';
$qry = $ObjDB->QueryObject("SELECT fld_class_name AS classname 
							FROM itc_class_master 
							WHERE fld_id='".$classid."'");
if($qry->num_rows>0){
    extract($qry->fetch_assoc());
}

?>

<section data-type='#class-newclass' id='class-newclass-passwords'>
	<div class='container'>
    	<div class='row'>
            <p class="dialogTitle">Student Password Management</p>
            <p class="dialogSubTitleLight"><?php echo $classname;?> Student Password Reset</p>
            <br>
        </div>
        <div class="row">
      		<div class='twelve columns'>
                <form action="class/newclass/class-newclass-ajax.php" id="reset-student-passwords-form">
                    <table id="password-reset" class="table table-hover table-striped table-bordered setbordertopradius">
                        <thead class="tableHeadText">
                            <tr>
                                <th>ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Username</th>
                                <th>Password</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($students as $student) {
                            echo '<tr>
                                <td>'.$student["fld_id"].'</td>
                                <td style="text-transform: capitalize;">'.$student["fld_fname"].'</td>
                                <td style="text-transform: capitalize;">'.$student["fld_lname"].'</td>
                                <td class="username" student_id="' . $student["fld_id"] . '">'.$student["fld_username"].'</td>
                                <td class="password"><input type="text" placeholder="Enter new password" name="student_' . $student["fld_id"] . '"/></td>                                
                                <td><button type="button">Save</button></td>
                            </tr>';
                        }?>
                        </tbody>
                    </table>
                    <button type="button" id="reset-all-passwords" class="btn" style="height: 46px;">Generate Passwords</button>
                    <button type="submit" id="save-all" class="btn" style="height: 46px;">Save All Passwords</button>
                </form>
    	</div>
        <div id="save-dialog" style="display: none;">All non-empty password fields will be updated. Do you want to continue?</div>
            <div id="reset-all-passwords-dialog" style="display: none; max-width:420px;">New passwords will be randomly generated for all students in this class. You will have the chance to review, export, and print the new passwords prior to saving. <br><br><b style="display: block; margin: 0 auto; font-weight: bold;">Do you wish to generate new passwords?</b></div>
        <script src="js/dataTables.buttons.min.js"></script>
        <script src="js/buttons.print.min.js"></script>
        <script src="js/buttons.html5.min.js"></script>
        <script src="js/jszip.min.js"></script>

        <script>
        function perform_save(data){
            $.ajax({
                method: "POST",
                url: "class/newclass/class-newclass-resetpasswords-ajax.php",
                data: data,
                success: function(response){
                    console.log(response);
                    if (response == 'success'){
                        swal(
                            'Success!',
                            'Student password(s) have been updated successfully.',
                            'success'
                        );
                    }
                    else{
                        swal(
                            'Oops!',
                            "There were some errors saving the password(s): " + response,
                            'error'
                        );
                    }
                    $('#save-dialog').dialog('close');
                },
                error: function(response){
                    console.log(response);
                    swal(
                        'Oops!',
                        "There were some errors saving the password(s): " + response,
                        'error'
                    );
                    $('#save-dialog').dialog('close');
                }
            });
        }

        $(document).ready(function(){
            var passwords_table = $('#password-reset').DataTable(
                {
                    buttons: [
                        {
                            extend: 'copy',
                            text: '<button type="button">Copy</button>',
                            exportOptions:
                            {
                                columns: [1,2,3,4],
                                orthogonal: 'export'
                            }

                        },
                        {
                            extend:'print',
                            text: '<button type="button">Print</button>',
                            exportOptions:
                                {
                                    columns: [1,2,3,4],
                                    orthogonal: 'export'
                                }
                        },
                        {
                            extend: 'excel',
                            text: '<button type="button">Excel</button>',
                            exportOptions:
                            {
                                columns: [1,2,3,4],
                                orthogonal: 'export'
                            }
                        }
                    ],
                    columns:
                    [
                        { data: 'ID'},
                        { data: 'First Name' },
                        { data: 'Last Name' },
                        { data: 'Username' },
                        {
                            data: 'Password',
                            render:
                                function(data,type,row){
                                    if (type === 'export'){
                                        var test = 'td[student_id="'+ row['ID']+'"]';
                                        var password = $('#password-reset').find(test).parent('tr').find('td.password > input').val();

                                        return password;
                                    }
                                    else{
                                        return data;
                                    }
                                }
                        },
                        { data: 'Save' }
                    ],
                    dom: 'Bfrtip',
                    paging: false,
                    "columnDefs":
                    [
                        {
                            "targets": 0,
                            "visible": false
                        },
                        {
                            "targets": -1,
                            "data": null,
                            "defaultContent": "<button type='button'>Save</button>"
                        }
                    ]
                }
            );


            <?php //For when the user clicks on the save button for a single row?>
            $('#password-reset tbody').on('click', 'button', function (){
                var data = passwords_table.row($(this).parents('tr')).data();

                data = $(this).parents('tr').find('input').serialize();
                perform_save(data);
            });

            $("#reset-student-passwords-form").submit(function( event ) {

                $("#save-dialog").dialog("open");

                event.preventDefault();
            });
        });

        <?php //Initialize the dialog for the save all button ?>
        $("#save-dialog").dialog({
            modal: true, title: 'Are you sure you wish to save the password(s)?', zIndex: 10000, autoOpen: false,
            width: 'auto', resizable: false,
            buttons: {
                Yes: function () {
                    var data = $('#password-reset').find('input:visible').serialize();
                    perform_save(data);
                },
                No: function () {
                    $(this).dialog("close");
                }
            },
            close: function (event, ui) {
                $(this).dialog("close");
            }
        });

        $('#reset-all-passwords').click(function(){
            $('#reset-all-passwords-dialog').dialog("open");
        });

        function get_random_password(length){
            var chars = "abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNP23456789";
            var pass = "";
            for (var x = 0; x < length; x++) {
                var i = Math.floor(Math.random() * chars.length);
                pass += chars.charAt(i);
            }
            return pass;
        }

        function randomize_all_passwords(){
            $('#password-reset').find('input:visible').each(function(){
                $(this).val(get_random_password(10));
            });
        }

        $("#reset-all-passwords-dialog").dialog({
            modal: true, title: 'Generate Passwords', zIndex: 10000, autoOpen: false,
            width: 'auto', resizable: false,
            buttons: {
                Yes: function () {
                    randomize_all_passwords();
                    $(this).dialog("close");
                    swal(
                        'Passwords Generated!',
                        'You are now able to print, and export the list of student usernames and passwords. Make sure to review all passwords prior to saving!',
                        'success'
                    );
                },
                No: function () {
                    $(this).dialog("close");
                }
            },
            close: function (event, ui) {
                $(this).dialog("close");
            }
        });
        </script>
    </div>
</section>
<?php
	@include("footer.php");