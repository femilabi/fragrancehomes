<div class="main-container section-padding">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <form action="" class="form-horizontal" method="post" accept-charset="utf-8">
          <fieldset>
            <legend>
              <?php $APP->printMsg(); ?>
            </legend>
            <center>
              <h2>Welcome <?= $APP->get('user_fullname') ?>,</h2>
              <h2 class="text-success">Your account has been created successfully!</h2>
              <h4>But your account awaits activation</h4>
            </center>
            <div class="clearfix">&nbsp;</div>
            <div class="col-lg-10 offset-lg-1">
              <div class="card card-default">
                <div class="card-header">
                  <h5>Click the button below to activate your account?:</h5>
                </div>
                <div class="card-body">
                  <div class="tab-content" style="min-height:180px">
                    <div role="tabcard" class="tab-card active" id="activate_email" style="padding:8px">
                      <span id="bank_amount-help" class="help-block">An activation link will be sent to <strong><?= $APP->get('user_email') ?></strong></span>
                      <span id="bank_amount-help" class="help-block">And you will be required to check your email and click the activation link that will be sent to you.</span>
                      <?php
                      echo make_issubmit();
                      echo make_submit('Send the email', 'success', 'ok', 'activation_method', 'email', 1);
                      ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </fieldset>
        </form>
      </div>
    </div>
  </div>
</div>