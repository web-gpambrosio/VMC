<html>
<body>
<table width="724" height="62" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="29" height="5"></td>
        <td width="346" height="5"></td>
        <td width="349" height="5"></td>
        </tr>
      <tr>
        <td height="5"></td>
        <td height="18" align="left" valign="top"><span>NAME: </span><strong><span class="black"><?php echo $name; ?></span></strong> </td>
        <td height="5"></td>
      </tr>
      <tr><td height="5"></td>
        <td height="18" align="left" valign="top"><span>POSITION: </span><strong><span class="black"><?php echo $staff; ?></span></strong></td>
        <td width="349" height="5"></td>
        </tr>
      <tr>
      <td height="21" style="border-bottom:3px double #aaaaaa"></td>
      <td style="border-bottom:3px double #aaaaaa" height="21" align="left" valign="top"><div class="admin_label"><em><strong>ADMINISTRATOR</strong></em> <a href="admin_edit.php?empno=<?php echo $empno . '&edit=' . $id; ?>"><span class="green2"><em><strong>(<u>EDIT ACCOUNT</u>)</strong></em></span></a></div></td>
      <td style="border-bottom:3px double #aaaaaa" width="349" height="5"></td>
        </tr>
    </table>
</body>
</html>