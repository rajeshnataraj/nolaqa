<?php
	@include('../includes/table.class.php');
	@include('../includes/comm_func.php');
	
	$userdetqry = $ObjDB->QueryObject("SELECT a.fld_id AS uid, b.fld_profile_name AS sessprofilename, a.fld_username AS uname, CONCAT(a.fld_fname,' ',a.fld_lname) AS name,
    a.fld_password AS password, a.fld_created_by AS createdby FROM itc_user_master AS a, itc_profile_master AS b WHERE a.fld_profile_id=b.fld_id AND (b.fld_delstatus = 0 OR b.fld_delstatus=2) AND a.fld_activestatus='1' AND a.fld_delstatus='0'");

	if($userdetqry->num_rows > 0) {
	?>
    	<table cellspacing="1" cellpadding="5" border="1">
        	<tr>
            	<th>ID</th>
            	<th>Level</th>
                <th>Name</th>
            	<th>Username</th>
                <th>Password</th>
                <th>Enc Password</th>
                <th>createdby</th>
            </tr>
    	<?php	
		while($rowuserdet = $userdetqry->fetch_assoc()) {
			extract($rowuserdet);
		?>	
        	<tr>
            	<td><?php echo $uid; ?></td>
                <td><?php echo $sessprofilename; ?></td>
                <td><?php echo $name; ?></td>
                <td><?php echo $uname; ?></td>
                <td><?php echo fnDecrypt($password,$encryptkey); ?></td>
                <td><?php echo $password; ?></td>
                <td><?php echo $createdby; ?></td>
            </tr>
		<?php
		}
		?>
        </table>
    <?php
	}	
	@include("../includes/footer.php");