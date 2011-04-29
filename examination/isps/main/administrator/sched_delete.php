<?php
session_start();
include('includes/myfunction.php');
if ((!isset($_GET['empno']) || trim($_GET['empno']) == '')&&(!isset($_GET['admin']) || trim($_GET['admin']) == ''))
{ header("location:../../index.php"); }
include('../../includes/conn.php');

$admin=$_GET['admin'];
$empno=$_GET['empno'];

?>
<SCRIPT LANGUAGE="JavaScript">
var agree=confirm("Are you sure you want to delete this account?");
if (agree)
window.location.href='sched_delete2.php?empno=<?php echo $empno; ?>&admin=<?php echo $admin; ?>';
else
history.go(-1);
</SCRIPT>