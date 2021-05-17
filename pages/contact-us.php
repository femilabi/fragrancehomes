<section id="contact-section" class="section-padding">
	<div class="container">
		<div class="row">
			<div class="col-lg-5 col-md-6">
				<div class="contact-right-area">
					<h2 class="title-">Get In Touch</h2>
					<p>If you are interested in working with us, <br> please get in touch.</p>
					<div class="contact-right">
						<div class="single-contact">
							<div class="contact-icon">
								<i class="lni-map-marker"></i>
							</div>
							<p><?=$APP->getSettings("office_address")?></p>
						</div>
						<div class="single-contact">
							<div class="contact-icon">
								<i class="lni-envelope"></i>
							</div>
							<p><a href="mailto:<?= $APP->getSettings("office_email") ?>"><?= $APP->getSettings("office_email") ?></a></p>
						</div>
						<div class="single-contact">
							<div class="contact-icon">
								<i class="lni-phone-handset"></i>
							</div>
							<p><a href="tel:<?= $APP->getSettings("office_phone_1") ?>"><?= $APP->getSettings("office_phone_1") ?></a></p>
						</div>
					</div>
					<div class="social-icon">
						<a class="facebook" href="#"><i class="lni-facebook-filled"></i></a>
						<a class="twitter" href="#"><i class="lni-twitter-filled"></i></a>
						<a class="instagram" href="#"><i class="lni-instagram-filled"></i></a>
						<a class="linkedin" href="#"><i class="lni-linkedin-filled"></i></a>
					</div>
				</div>
			</div>
			<div class="col-lg-7 col-md-6 form-line">
				<h2>FeedBack</h2>
				<?php $APP->printMsg(); ?>
				<form role="form" method="post" action="" data-toggle="validator">
					<div class="form-group">
						<input type="text" class="form-control" id="name" name="contact[name]" placeholder="Your name" required data-error="Please enter your name">
						<div class="help-block with-errors"></div>
					</div>
					<div class="form-group">
						<input type="email" class="form-control" id="email" name="contact[email]" placeholder="Email Address" required data-error="Please enter your Email">
						<div class="help-block with-errors"></div>
					</div>
					<div class="form-group">
						<input type="tel" class="form-control" id="msg_subject" name="contact[subject]" placeholder="Subject" required data-error="Please enter your message subject">
						<div class="help-block with-errors"></div>
					</div>
					<div class="form-group">
						<textarea class="form-control" rows="5" id="message" name="contact[message]" placeholder="Message" required data-error="Write your message"></textarea>
					</div>
					<?=make_issubmit()?>
					<div class="form-submit">
						<button type="submit" class="btn btn-common" id="form-submit"><i class="fa fa-paper-plane" aria-hidden="true"></i> Send Message</button>
						<!-- <div id="msgSubmit" class="h3 text-center hidden"></div> -->
					</div>
				</form>
			</div>
		</div>
	</div>
</section>


<section id="google-map-area">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<div id="conatiner-map"></div>
			</div>
		</div>
	</div>
</section>