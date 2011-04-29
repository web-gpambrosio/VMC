<?php
session_start();
if(!session_is_registered(adminno))
{
header("location:login.php");
}
if ((!isset($_GET['admin']) || trim($_GET['admin']) == '')&&(!isset($_GET['empno']) || trim($_GET['empno']) == ''))
{
header("location:index.php");
}
include('../connection/conn.php');
$admin=$_GET['admin'];
$empno=$_GET['empno'];

?>
<SCRIPT LANGUAGE="JavaScript">
var agree=confirm("Are you sure you want to delete this account?");
if (agree)
window.location.href='admin_crew_delete2.php?empno=<?php echo $empno; ?>&admin=<?php echo $admin; ?>';
else
history.go(-1);
</SCRIPT>