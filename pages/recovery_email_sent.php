<?php $user = $APP->get('user_data'); ?>
<div class="main-container section-padding">
    <div class="container">
        <div class="row">
            <form action="" class="form-horizontal" method="post" id="MessageIndexForm" accept-charset="utf-8">
                <fieldset>
                    <legend class="text-center">
                        <h2 style="width:100%">Recovery has been processed!</h2>
                        <?php $APP->printMsg(); ?>
                    </legend>
                    <center>
                        <h4 class="text-success">A password recovery link has been sent to <strong><?= @$user['email'] ?></strong></h4>
                        <h4>Check your email and click the recovery link to change or create a new password for your account</h4>
                    </center>
                    <div class="clearfix">&nbsp;</div>
                    <div class="text-center">                    
                        <a class="btn btn-info" href="/recover-password/"><span class="glyphicon glyphicon-repeat" aria-hidden="true"></span> Resen email</a>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>