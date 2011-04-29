<?php
if ((!isset($_GET['id']) || trim($_GET['id']) == ''))
{ die ('Missing record ID!'); }
include('e-store_conn.php');
$w=$_GET['id'];
$x="Select id, productpic from merch where id='" . $w . "'";
$y=mysql_query("$x",$conn);
$id=mysql_result($y,0,"id");
$q=mysql_result($y,0,"productpic");
?>
<html>
<head>
<title><?php echo $q; ?></title>
</head>
<body>
<img src="<?php echo $q; ?>" />
</body>
</html>