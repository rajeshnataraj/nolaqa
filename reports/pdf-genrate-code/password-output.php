<?php 
@include("table.class.php");
@include("comm_func.php");
$method = $_REQUEST;

$id = isset($method['id']) ? $method['id'] : '0';
$id = explode(",",$id);

$studentid = $id[1];
$classid = $id[2];
$school = $id[3];
$individualid = $id[4]
?>
<style>
	.title
	{
		font-size:35px; font-weight:bold;
	}
	.trgray
	{
		font-size:30px; background-color:#CCCCCC; font-weight:normal; 
	}
	.trclass
	{
		font-size:30px; background-color:#FFFFFF; font-weight:normal;
	}
	.tdleft{
		border-top:1px solid #b4b4b4; border-left:1px solid #b4b4b4; border-bottom:1px solid #b4b4b4;
	}
	
	.tdmiddle{
		border-top:1px solid #b4b4b4; border-bottom:1px solid #b4b4b4;
	}
	.tdright{
		border-top:1px solid #b4b4b4; border-right:1px solid #b4b4b4; border-bottom:1px solid #b4b4b4;
	}
</style>
<table cellpadding="0" cellspacing="0" >
    <tr class="title">
        <th>Student Name</th>
        <th>User Name</th>
        <th>Password</th>
    </tr>

    <tbody>
        <?php 
		$qrystu = '';
        if(($studentid=='' or $studentid=='undefined') and $classid!='')//For Students under a particular Class
        {
        	$qrystu = "SELECT CONCAT(a.fld_fname,' ',a.fld_lname) AS studentname, a.fld_username AS username, a.fld_password AS stupassword 
						FROM itc_user_master AS a 
						LEFT JOIN itc_class_student_mapping AS b ON a.fld_id=b.fld_student_id 
						WHERE a.fld_activestatus='1' AND a.fld_delstatus='0' AND b.fld_class_id='".$classid."' AND b.fld_flag='1' 
						ORDER BY a.fld_lname";
        }
        else if($studentid==0 and ($classid=='' or $classid=='undefined'))//For Students under a particular School
        {
        	$qrystu = "SELECT CONCAT(fld_fname,' ',fld_lname) AS studentname, fld_username AS username, fld_password AS stupassword 
						FROM itc_user_master 
						WHERE fld_delstatus='0' AND fld_activestatus='1' AND fld_profile_id='10' 
								AND fld_school_id='".$school."' AND fld_user_id='".$individualid."' 
						ORDER BY fld_lname";
        }
        else//For a Single Student
        {
       		$qrystu = "SELECT CONCAT(fld_fname,' ',fld_lname) AS studentname, fld_username AS username, fld_password AS stupassword 
						FROM itc_user_master 
						WHERE fld_delstatus='0' AND fld_id='".$studentid."' 
						ORDER BY fld_lname";
        }
        $qry = $ObjDB->QueryObject($qrystu);
		
        if($qry->num_rows > 0){ //Executes if student is available
            $cnt=0;
            while($row=$qry->fetch_assoc()){
                extract($row);
                $stupassword = fnDecrypt($stupassword, $encryptkey);//Used to decrypt the password
                ?>	
                <tr class="<?php if($cnt==0) { ?>trgray<?php } else if($cnt==1) { ?>trclass<?php }?>">
                    <td class="tdleft"><?php echo $studentname; ?></td>
                    <td class="tdmiddle"><?php echo $username; ?></td>
                    <td class="tdright"><?php echo $stupassword; ?></td>
                </tr>
                <?php
                if($cnt==0)
                    $cnt=1;
                else if($cnt==1)
                    $cnt=0;
            }
        }
        else { //Executes if there is no students
        ?>
            <tr class="trclass">
                <td colspan="3">No Students Available...</td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>