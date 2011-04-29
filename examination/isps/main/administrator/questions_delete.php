<?php
session_start();
include('includes/myfunction.php');
include('../../includes/conn.php');
if ((!isset($_GET['admin']) || trim($_GET['admin']) == '')&&(!isset($_GET['id']) || trim($_GET['id']) == ''))
{
header("location:../../index.php");
}
$admin=$_GET['admin'];
$id=$_GET['id'];

?>
<SCRIPT LANGUAGE="JavaScript">
var agree=confirm("Are you sure you want to delete this question?");
if (agree)
window.location.href='questions_delete2.php?id=<?php echo $id; ?>&admin=<?php echo $admin; ?>';
else
history.go(-1);
</SCRIPT>