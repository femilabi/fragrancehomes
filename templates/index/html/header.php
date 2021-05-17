<?php
function toggle_url_active_class($lib)
{
    global $APP;

    return ($APP->getLib() == $lib ? "active" : "");
}
?>
<header id="header-wrap">
    <div class="top-bar">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 col-md-9 col-xs-12">
                    <ul class="links clearfix">
                        <li><a href="tel:<?= $APP->getSettings("office_phone_1") ?>"><i class="lni-phone-handset"></i><?= $APP->getSettings("office_phone_1") ?></a></li>
                        <li><a href="mailto:<?= $APP->getSettings("office_email") ?>"><i class="lni-envelope"></i> <?= $APP->getSettings("office_email") ?></a></li>
                        <li><a href="#"><i class="lni-map-marker"></i> <?= $APP->getSettings("office_short_address") ?></a></li>
                    </ul>
                </div>
                <div class="col-lg-5 col-md-3 col-xs-12">
                    <div class="header-top-right float-right">
                        <?php if (session_auth()) { ?>
                            <?php if ($USER->get("role") == "admin") { ?>
                                <a href="/admin/" class="header-top-button">Admin Dashboard</a>
                            <?php } else { ?>
                                <a href="/dashboard/" class="header-top-button">My Dashboard</a>
                            <?php } ?>
                        <?php } else { ?>
                            <a href="/login/" class="header-top-button">Log In</a>
                            <a href="/register/" class="header-top-button white-bg">Sign Up</a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg navbar-light bg-white" data-toggle="sticky-onscroll">
        <div class="container">
            <div class="navbar-header">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-navbar" aria-controls="main-navbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                    <span class="lin-menu"></span>
                </button>
                <a class="navbar-brand" href="/"><img src="<?= $APP->getTemplateDir() ?>assets/img/logo.png" alt="<?= ADMIN_NAME ?>"></a>
            </div>
            <div class="collapse navbar-collapse" id="main-navbar">
                <ul class="navbar-nav mr-auto w-100 justify-content-center">
                    <li class="nav-item <?= toggle_url_active_class("index"); ?>">
                        <a class="nav-link" href="/">
                            Home
                        </a>
                    </li>
                    <li class="nav-item dropdown <?= toggle_url_active_class("listings"); ?>">
                        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Properties <i class="fa fa-angle-down"></i>
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item <?= ((!$APP->get("filter_action") && $APP->getLib() == "listings") ? 'active' : '') ?>" href="/listings/">All Properties</a>
                            <a class="dropdown-item <?= ($APP->get("filter_action") == 'sale' ? 'active' : '') ?>" href="/listings/?filter[action]=sale">For Sale</a>
                            <a class="dropdown-item <?= ($APP->get("filter_action") == 'rent' ? 'active' : '') ?>" href="/listings/?filter[action]=rent">For Rent</a>
                            <a class="dropdown-item <?= ($APP->get("filter_action") == 'shortlet' ? 'active' : '') ?>" href="/listings/?filter[action]=shortlet">For Short Let</a>
                        </div>
                    </li>
                    <li class="nav-item <?= toggle_url_active_class("faq"); ?>">
                        <a class="nav-link" href="/faq">
                            FAQs
                        </a>
                    </li>
                    <li class="nav-item <?= toggle_url_active_class("blog"); ?>">
                        <a class="nav-link" href="/blog">
                            Blog
                        </a>
                    </li>
                    <li class="nav-item <?= toggle_url_active_class("about-us"); ?>">
                        <a class="nav-link" href="/about-us">
                            About Us
                        </a>
                    </li>
                    <li class="nav-item <?= toggle_url_active_class("contact-us"); ?>">
                        <a class="nav-link" href="/contact-us">
                            Contact Us
                        </a>
                    </li>
                </ul>
                <div class="search-add float-right">
                    <form method="get" action="/listings/">
                        <div class="form-group">
                            <input type="search" name="q" placeholder="Search Here">
                            <button type="submit" class="search-btn"><span class="lni-search"></span></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <ul class="mobile-menu">
            <li>
                <a class="<?= toggle_url_active_class("index"); ?>" href="/">
                    Home
                </a>
            </li>
            <li>
                <a href="#">
                    Properties
                </a>
                <ul class="dropdown">
                    <li><a class="<?= (($APP->getLib() == "listing" && !$APP->p(1)) ? 'active' : '') ?>" href="/listingss/">All Properties</a></li>
                    <li><a class="<?= ($APP->p(1) == 'for-sale'  ? 'active' : '') ?>" href="/listingss/for-sale/">For Sale</a></li>
                    <li><a class="<?= ($APP->p(1) == 'for-rent'  ? 'active' : '') ?>" href="/listingss/for-rent/">For Rent</a></li>
                    <li><a class="<?= ($APP->p(1) == 'for-shortlet'  ? 'active' : '') ?>" href="/listingss/for-shortlet/">For Short Let</a></li>
                </ul>
            </li>
            <li>
                <a class="<?= toggle_url_active_class("faq"); ?>" href="/faq">
                    FAQs
                </a>
            </li>
            <li>
                <a class="<?= toggle_url_active_class("blog"); ?>" href="/blog">
                    Blog
                </a>
            </li>
            <li>
                <a class="<?= toggle_url_active_class("about-us"); ?>" href="/about-us">
                    About Us
                </a>
            </li>
            <li>
                <a class="<?= toggle_url_active_class("contact-us"); ?>" href="/contact-us">
                    Contact Us
                </a>
            </li>
        </ul>
    </nav>
    <div class="clearfix"></div>
</header>