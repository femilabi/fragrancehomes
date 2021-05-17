<div class="main-container section-padding">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php $APP->printMsg();
                if (isset($_SESSION['access_denied']) && $_SESSION['access_denied']) {
                    $APP->setMsg('Access denied for the type of user logged in', 'error');
                    $APP->printMsg();
                    unset($_SESSION['access_denied']);
                }
                ?>
                <h1>Request not valid</h1>
                <p>The requested URL was not valid and could not be processed on our platform</p>
                <address>Please go back and try again</address>
                <p><a href="<?= HOME_DIR ?>">Go Back</a></p>
            </div>
        </div>
    </div>
</div>