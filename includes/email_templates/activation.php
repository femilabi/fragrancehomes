<?php include(INCLUDE_PATH.'email_templates/includes/header.php'); ?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
 <td>
  <h3>Dear {fullname},</h3>
 </td>
</tr>
<tr>
 <td>
  <p>Congratulations, your account was created on {website} platform.</p>
  <p>Click on the link below to get your account activated</p>
  <p><a href="{activation_link}">{activation_link}</a></p>
  <p>If you are unable to click the link, copy it to the address bar of your web browser.</p>
  <h3>Your registration details are as follows:</h3>
  <p>Email: {email}
    <br/>Password: (as specified on the website)
  </p>
  <p><small>
      {website}
  </small></p>
 </td>
</tr>
</table>
<?php include(INCLUDE_PATH.'email_templates/includes/footer.php'); ?>