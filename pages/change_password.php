<?php $user = $APP->get('user_data'); ?>
<form action="" class="form-horizontal" method="post" id="MessageIndexForm" accept-charset="utf-8">
	<fieldset>
		<legend>
			<h2 style="width:100%">Change password</h2>
			<?php $APP->printMsg(); ?>
		</legend>
		<div class="col-lg-10 col-lg-offset-1">
			<h5 class="text-success">Create a new password for your account below</h5>
			<?php
			echo make_field(array(
				'label' => 'Account:',
				'name' => 'form[username]',
				'id' => 'username',
				'value' => @$user['email'],
				'type' => 'static',
				'help' => 'Not your account? <a href="recover-password/">Restart account recovery process</a>'
			));
			echo make_field(array(
				'label' => 'Enter password:',
				'placeholder' => 'New Password',
				'name' => 'form[password]',
				'id' => 'password',
				'required' => 1,
				'type' => 'password'
			));
			echo make_field(array(
				'label' => 'Re-type password:',
				'placeholder' => 'Re-type Password',
				'name' => 'form[password2]',
				'id' => 'password2',
				'required' => 1,
				'type' => 'password'
			));
			echo make_field(array(
				'label' => '<strong>Answer security question:</strong><br/>' . @$user['secure_q'],
				'placeholder' => 'Security Answer...',
				'name' => 'form[secure_answer]',
				'id' => 'secure_answer',
				'required' => 1,
				'type' => 'password'
			));
			echo '<hr/>';

			echo make_field(array(
				'label' => '',
				'id' => 'recaptcha',
				'type' => 'static',
				'value' => make_recaptcha()
			));
			echo make_submit('Set Password', 'primary', 'ok', 'form[action]', 'change_password', 1);
			?>
		</div>
		<div class="clearfix">&nbsp;</div>
		No, I have my password, <a class="" href="login/"> Login Here</a>
	</fieldset>
</form>

<section id="content" class="section-padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-6 col-xs-12">
                <div class="page-login-form box">
                    <h3>
                        Change Password
                    </h3>
                    <form class="login-form" method="POST" action="">
                        <?php $APP->printMsg(); ?>
						<h6 class="text-success">Create a new password for your account below</h6>
                        <div class="form-group">
                            <div class="input-icon">
                                <i class="lni-user"></i>
                                <input type="text" id="sender-email" class="form-control" name="username" value="<?=@$user["email"]?>" placeholder="Username" readonly>
                            </div>
                        	<p class="text-center">Not your account? <a class="" href="/recover-password/"> Restart account recovery process</a></p>
                        </div>
						<br/>
                        <div class="form-group">
                            <div class="input-icon">
                                <i class="lni-lock"></i>
                                <input type="password" class="form-control" placeholder="Enter Password" name="password">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-icon">
                                <i class="lni-lock"></i>
                                <input type="password" class="form-control" placeholder="Re-Type Password" name="password2">
                            </div>
                        </div>
                        <button class="btn btn-common log-btn">Set Password</button>
                        <p class="text-center">No, I have my password, <a class="" href="/login/"> Login Here</a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>