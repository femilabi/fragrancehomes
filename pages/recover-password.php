<section id="content" class="section-padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-6 col-xs-12">
                <div class="page-login-form box">
                    <h3>
						Forgot your password?
                    </h3>
                    <form class="login-form" method="POST" action="">
                        <?php $APP->printMsg(); ?>
						<p>Enter your details below and a recovery link will be sent to your email</p>
                        <div class="form-group">
                            <div class="input-icon">
                                <i class="lni-user"></i>
                                <input type="text" id="sender-email" class="form-control" name="form[username]" placeholder="Email">
                            </div>
                        </div>
                        <?=make_issubmit()?>
                        <button class="btn btn-common log-btn" name="form[action]" value="generate">Recover</button>
						<p class="text-center">No, I have password. <a href="/login/">Login Here</a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>