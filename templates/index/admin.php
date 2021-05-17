<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from preview.uideck.com/items/brittville-demo/listing.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 27 Feb 2019 09:24:54 GMT -->

<head>
    <?php include_once("html/head.php"); ?>
</head>

<body>

    <?php include_once("html/header.php"); ?>

    <?php include_once("html/banner.php"); ?>


    <section class="user-page section-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-5 col-xs-12">
                    <div class="user-profile-box">

                        <div class="header clearfix">
                            <h2>Justyna Michallek</h2>
                            <h4>Real Estate Agent</h4>
                            <img src="<?= $APP->getTemplateDir() ?>assets/img/avatar/avatar-2.jpg" alt="avatar" class="img-fluid profile-img">
                        </div>

                        <div class="detail clearfix">
                            <ul>
                                <li>
                                    <a class="active" href="<?= $APP->getTemplateDir() ?>dashboard.html">
                                        <i class="lni-files"></i> Dashboard
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= $APP->getTemplateDir() ?>user-profile.html">
                                        <i class="lni-user"></i>Profile
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= $APP->getTemplateDir() ?>my-properties.html">
                                        <i class="lni-home"></i>My Properties
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= $APP->getTemplateDir() ?>favorited-properties.html">
                                        <i class="lni-heart"></i>Favorited Properties
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= $APP->getTemplateDir() ?>submit-property.html">
                                        <i class="lni-plus"></i>Submit New Property
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= $APP->getTemplateDir() ?>change-password.html">
                                        <i class="lni-lock"></i>Change Password
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= $APP->getTemplateDir() ?>index-2.html">
                                        <i class="lni-logout"></i>Log Out
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <?= $APP->content(); ?>
            </div>
        </div>
    </section>

    <?php include_once("html/footer.php"); ?>

    <?php include_once("html/copyright.php"); ?>

    <?php include_once("html/back-to-top.php"); ?>

    <?php include_once("html/preloader.php"); ?>

    <?php include_once("html/scripts.php"); ?>
</body>

<!-- Mirrored from preview.uideck.com/items/brittville-demo/listing.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 27 Feb 2019 09:24:54 GMT -->

</html>