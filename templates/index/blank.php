<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $APP->getFullTitle() ?></title>

    <link rel="stylesheet" type="text/css" href="<?= $APP->getTemplateDir() ?>assets/css/bootstrap.min.css">
    <script src="<?= $APP->getTemplateDir() ?>assets/js/jquery-min.js"></script>

    <?= $APP->head() ?>
</head>

<body>
    <div class="container">
        <div class="row">
            <?= $APP->content() ?>
        </div>
    </div>
    <script src="<?= $APP->getTemplateDir() ?>assets/js/popper.min.js"></script>
    <script src="<?= $APP->getTemplateDir() ?>assets/js/bootstrap.min.js"></script>
    <script src="<?= $APP->getTemplateDir() ?>assets/js/jquery.mixitup.js"></script>
    <script src="<?= $APP->getTemplateDir() ?>assets/js/jquery.counterup.min.js"></script>
    <script src="<?= $APP->getTemplateDir() ?>assets/js/ion.rangeSlider.js"></script>
    <script src="<?= $APP->getTemplateDir() ?>assets/js/jquery.parallax.js"></script>
    <script src="<?= $APP->getTemplateDir() ?>assets/js/waypoints.min.js"></script>
    <script src="<?= $APP->getTemplateDir() ?>assets/js/wow.js"></script>
    <script src="<?= $APP->getTemplateDir() ?>assets/js/owl.carousel.min.js"></script>
    <script src="<?= $APP->getTemplateDir() ?>assets/js/jquery.slicknav.js"></script>
    <script src="<?= $APP->getTemplateDir() ?>assets/js/nivo-lightbox.js"></script>
    <script src="<?= $APP->getTemplateDir() ?>assets/js/jquery.slicknav.js"></script>
    <script src="<?= $APP->getTemplateDir() ?>assets/js/main.js"></script>
    <script src="<?= $APP->getTemplateDir() ?>assets/js/form-validator.min.js"></script>
    <script src="<?= $APP->getTemplateDir() ?>assets/js/contact-form-script.min.js"></script>

    <?= $APP->scripts(); ?>
</body>

</html>