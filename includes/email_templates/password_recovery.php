<?php include(INCLUDE_PATH.'email_templates/includes/header.php'); ?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
 <td>
  <h3>Dear {fullname},</h3>
 </td>
</tr>
<tr>
 <td>
  <p>You have requested to reset your account password</p>
  <p>Click the link below to reset your account password</p>
  <p><a href="{recovery_link}">{recovery_link}</a></p>
  <p>If you are unable to click the link, copy it to the address bar of your web browser.</p>
  <p style="color:#D40000">NOTE: If you did not request for this, contact the support immediately.</p>
  <p><small>
      {website}
  </small></p>
 </td>
</tr>
</table>
<?php include(INCLUDE_PATH.'email_templates/includes/footer.php'); ?>