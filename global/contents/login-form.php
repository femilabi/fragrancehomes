<?php
	$auth = session_auth();
	if($auth && isset($_SESSION['signup_redirected']) && $_SESSION['signup_redirected']){
		unset($_SESSION['signup_redirected']);
		$APP->setMsg('<strong>Congratulations!</strong> Your account has been created successfully', 'success');
		$APP->printMsg();
	}
?>
<div id="box_login" class="row-fluid">
	<link rel="stylesheet" type="text/css" href="<?=CDN_DIR?>css/signin.css">
    <div class="">
        <div class="box_wrapper">
            <div class="box">
                <div>
                    <div class="head">
                        <h4><?php echo($auth? "Welcome ". $USER->get('username'): "Log in to your account"); ?></h4>
                    </div>
                    <?php if($auth): ?>
                    	<div class="social">
                            <p><a href="dashboard/" class="btn btn-block btn-large btn-info" title="Where you can see all user menus and operations">Dashboard</a></p>
                            <br>
							<p><a href="create-event/" class="btn btn-block btn-large" title="Create an event page">Create event</a></p>
							<br>
                            <p><a href="create-wedding/" class="btn btn-block btn-large" title="Create a wedding page">Create Wedding page</a></p>
                            <br>
                            <p><a href="logout/" class="btn btn-block btn-large btn-danger" title="Log out your account">Log out</a></p>
                            <br>
                            <h5 class="text-info">
                            	To beome a <a href="vendor-apply" class="text-error" title="Vendors can offer services and sell goods on this Platform">Vendor</a> on XMEvent please kindly contact us with the credentials of your company attached.
                            </h5>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if(!$auth): ?>
                <div class="form">
                    <form action="<?php echo(HOME_DIR) ?>login" method="post" id="UserLoginForm" accept-charset="utf-8">
                        <input name="username" title="Enter your email address" placeholder="Username" id="UserEmail" required type="text">
                        <input name="password" title="Enter your password" placeholder="Password" value="" maxlength="50" id="UserUserpass" required type="password">
                        <div class="remember">
                            <div class="left">
                                <input name="remember" id="remember" title="Remember me (stay logged in for 2 weeks) until you logout" value="1" type="checkbox"> 
                                <label for="remember">Remember me</label>
                                <input name="issubmit" value="1" type="hidden">
                            </div>
                            <div class="right">
                                <a href="contact-us" title="Reset your lost or forgotten password.">Forgot your password?</a> 
                            </div>
                        </div>
                        <div class="submit">
                            <input title="Login now" class="btn" value="Sign In" type="submit">
                        </div>
                    </form>
                </div>
            	<div class="clearfix">&nbsp;</div>
                <p class="already">
                    Don't have an account yet?<br>
                    <strong><a href="register" title="Create your free account now." class="btn btn-large btn-primary">Get Started Now</a></strong><br />
                    It's Free!
                </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>