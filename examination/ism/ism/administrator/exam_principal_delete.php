<?php
session_start();
if(!session_is_registered(empno))
{
header("location:index.php");
}
if ((!isset($_GET['admin']) || trim($_GET['admin']) == '')&&
	(!isset($_GET['id']) || trim($_GET['id']) == '')&&
	(!isset($_GET['tnkc']) || trim($_GET['tnkc']) == ''))
{
header("location:index.php");
}
include('../connection/conn.php');
$admin=$_GET['admin'];
$id=$_GET['id'];
$tnkc=$_GET['tnkc'];
?>
<SCRIPT LANGUAGE="JavaScript">
var agree=confirm("Are you sure you want to delete this account?");
if (agree)
window.location.href='exam_principal_delete2.php?id=<?php echo $id; ?>&admin=<?php echo $admin; ?>&tnkc=<?php echo $tnkc; ?>';
else
history.go(-1);
</SCRIPT>