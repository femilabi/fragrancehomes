<form action="" class="form-horizontal" method="post" id="MessageIndexForm" accept-charset="utf-8">
<fieldset>
<legend>
    <h2 style="width:100%"><?=$APP->get('user_activation_method') == 'email'? 'Activation email sent!':'Your code has been sent to you'?></h2>
    <?php $APP->printMsg(); ?>
</legend>
<?php if($APP->get('user_activation_method') == 'email'):?>
<center>
<h4 class="text-success">Your activation link has been sent to you on <strong><?=$APP->get('user_email')?></strong></h4>
<h4>Check your email and click the link received to get activated</h4>
<a class="btn btn-info btn-lg" href="<?=str_replace('step=activation', 'step=activation_method', get_current_url())?>&send_email=1">Resend activation email</a>
</center>
<?php else: ?>
<div class="col-lg-10 col-lg-offset-1">
	<h5>Enter the code you received on <strong>+<?=$APP->get('user_phone')?></strong></h5>
	<?php
	echo make_field(array('label'=> 'Enter Code:',
		'placeholder'=> 'Code...',
		'name' => 'form[code]',
		'id' => 'code',
		'required' => 1,
		'help' => 'Enter the code sent to your mobile. Code will be valid 24 hrs'));
	
	echo make_submit('Activate', 'primary','ok',null,null,1);
	?>
</div>
<?php endif; ?>
<div class="clearfix">&nbsp;</div>
</fieldset>
</form>