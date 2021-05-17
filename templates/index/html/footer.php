<footer id="footer" class="footer-area section-padding">
    <div class="container">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                    <h3 class="footer-titel">Our Company</h3>
                    <ul class="footer-link">
                        <li><a href="/about-us/">About Us</a></li>
                        <li><a href="/contact-us/">Contact Us</a></li>
                        <li><a href="/faq/">FAQs</a></li>
                        <li><a href="/blog/">Latest News</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                    <h3 class="footer-titel">Properties</h3>
                    <ul class="footer-link">
                        <li><a href="/listings/">All Properties</a></li>
                        <li><a href="/listings/?filter[action]=sale">For Sale</a></li>
                        <li><a href="/listings/?filter[action]=rent">For Rent</a></li>
                        <li><a href="/listings/?filter[action]=shortlet">For Short Let</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <h3 class="footer-titel">Contact <span>Info</span></h3>
                    <ul class="address">
                        <li>
                            <a href="#"><i class="lni-map-marker"></i> <?= $APP->getSettings("office_address") ?></a>
                        </li>
                        <li>
                            <a href="tel:<?= $APP->getSettings("office_phone_1") ?>"><i class="lni-phone-handset"></i> <?= $APP->getSettings("office_phone_1") ?></a>
                        </li>
                        <li>
                            <a href="tel:<?= $APP->getSettings("office_email") ?>"><i class="lni-envelope"></i> <?= $APP->getSettings("office_email") ?></a>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                    <h3 class="footer-titel">Subscribe <span>on Our News</span></h3>
                    <form method="post" id="subscribe-form" name="subscribe-form" class="validate">
                        <div class="form-group is-empty">
                            <input type="email" value="" name="Email" class="form-control" id="EMAIL" placeholder="Email address" required="">
                            <button type="submit" name="subscribe" id="subscribes" class="btn btn-common sub-btn"><i class="lni-envelope"></i></button>
                            <div class="clearfix"></div>
                        </div>
                    </form>
                    <div class="social-icon">
                        <a class="facebook" href="<?=$APP->getSettings("facebook_handle")?>"><i class="lni-facebook-filled"></i></a>
                        <a class="twitter" href="<?=$APP->getSettings("twitter_handle")?>"><i class="lni-twitter-filled"></i></a>
                        <a class="instagram" href="<?=$APP->getSettings("instagram_handle")?>"><i class="lni-instagram-filled"></i></a>
                        <a class="linkedin" href="<?=$APP->getSettings("linkedin_handle")?>"><i class="lni-linkedin-filled"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>