<!-- Flats & Apartments Houses > Duplexes > Terraces > Townhouses > Bungalows Commercial Property > Offices > Shops > Warehouses > Hotels > Land > Rooms > Boys Quarters -->
<section id="content" class="section-padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-6 col-xs-12">
                <div class="page-login-form box">
                    <h3>
                        Login
                    </h3>
                    <form class="login-form" method="POST" action="">
                        <?php $APP->printMsg(); ?>
                        <div class="form-group">
                            <div class="input-icon">
                                <i class="lni-user"></i>
                                <input type="text" id="sender-email" class="form-control" name="email" placeholder="Username">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-icon">
                                <i class="lni-lock"></i>
                                <input type="password" class="form-control" name="password" placeholder="Password">
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="checkbox" name="rememberme" value="forever"> Keep Me Signed In
                        </div>
                        <?=make_issubmit()?>
                        <button type="submit" class="btn btn-common log-btn">Submit</button>
                        <p class="text-center">Don't have an account? <a href="/register/">Click Here</a></p>
                        <p class="text-center">Forgot Password? <a href="/recover-password/">Click Here</a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>