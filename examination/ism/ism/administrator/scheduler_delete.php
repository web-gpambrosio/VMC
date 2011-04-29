<?php
session_start();
if(!session_is_registered(adminno))
{
header("location:login.php");
}
if ((!isset($_GET['adminno']) || trim($_GET['adminno']) == '')&&(!isset($_GET['empno']) || trim($_GET['empno']) == ''))
{
header("location:index.php");
}
include('../connection/conn.php');
$admin=$_GET['adminno'];
$empno=$_GET['empno'];
?>
<SCRIPT LANGUAGE="JavaScript">
var agree=confirm("Are you sure you want to delete this account?");
if (agree)
window.location.href='scheduler_delete2.php?empno=<?php echo $empno; ?>&adminno=<?php echo $admin; ?>';
else
history.go(-1);
</SCRIPT>