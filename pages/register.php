<section id="content" class="section-padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-6 col-xs-12">
                <div class="page-login-form box">
                    <h3>
                        <?= $APP->getTitle(); ?>
                    </h3>
                    <form class="login-form" method="POST" action="">
                        <?php $APP->printMsg(); $APP->printErrors(); ?>
                        <div class="form-group">
                            <div class="input-icon">
                                <i class="lni-user"></i>
                                <input type="fullname" class="form-control" name="fullname" placeholder="Full Name" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-icon">
                                <i class="lni-envelope"></i>
                                <input type="text" class="form-control" name="email" placeholder="Email Address" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-icon">
                                <i class="lni-phone"></i>
                                <input type="text" class="form-control" name="phone" placeholder="Phone Number" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-icon">
                                <i class="lni-lock"></i>
                                <input type="password" class="form-control" name="password" placeholder="Password" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-icon">
                                <i class="lni-unlock"></i>
                                <input type="password" class="form-control" name="password2" placeholder="Retype Password" required>
                            </div>
                        </div>
                        <?=make_issubmit()?>
                        <button type="submit" class="btn btn-common log-btn mt-3">Register</button>
                        <p class="text-center">Already have an account?<a href="/login/"> Sign In</a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>