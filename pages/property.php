<?php
$listing = $APP->get("listing");
$listing_images = $APP->get("listing_images");
$building_plan_images = $APP->get("building_plan_images");
$similar_listings = $APP->get("similar_listings");
?>
<div id="content" class="section-padding">
    <div class="container">
        <div class="property-details">
            <div class="row">
                <div class="col-lg-4 col-md-12 col-xs-12">
                    <div class="info">
                        <h3><?= $listing["title"] ?> <span class="badge">sale</span></h3>
                        <p class="room-type"><?= $listing["type"] ?></p>
                        <p class="address"><i class="lni-map-marker"></i> <?= ($listing["address"] . ", " . $listing["city"] . ", " . $listing["state"] . ", " . $listing["country"] . ". ") ?></p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 col-xs-12">
                    <div class="details">
                        <?php if ($listing["bedroom"]) : ?>
                            <div class="details-listing">
                                <p>Bedrooms</p>
                                <h5><?= $listing["bedroom"] ?></h5>
                            </div>
                        <?php endif; ?>
                        <?php if ($listing["bathroom"]) : ?>
                            <div class="details-listing">
                                <p>Bathrooms</p>
                                <h5><?= $listing["bathroom"] ?></h5>
                            </div>
                        <?php endif; ?>
                        <?php if ($listing["landmass"]) : ?>
                            <div class="details-listing">
                                <p>Size (Sq.ft)</p>
                                <h5><?= $listing["landmass"] ?></h5>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 col-xs-12">
                    <div class="others">
                        <ul>
                            <li><span><?= price_format($listing["price"], true) ?></span></li>
                            <li><a href="#"><i class="lni-bookmark-alt"></i></a></li>
                            <li><a href="#"><i class="lni-heart"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">

            <div class="col-lg-8 col-md-12 col-xs-12">
                <?php if ($listing_images) : ?>
                    <div class="property-slider">
                        <div id="property-slider" class="owl-carousel owl-theme">
                            <?php foreach ($listing_images as $img) : ?>
                                <div class="item">
                                    <img src="/<?= $img["path"] ?>" alt="<?= $listing["title"] ?>">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="inner-box property-dsc">
                    <h2 class="desc-title">Property Description</h2>
                    <p><?= $listing["description"] ?></p>
                </div>

                <?php if ($listing["features"]) : ?>
                    <div class="inner-box featured">
                        <h2 class="desc-title">Features</h2>
                        <ul class="property-features checkboxes">
                            <?php foreach ($listing["features"] as $f) : ?>
                                <li><i class="lni-check-box"></i> <?= $f ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if ($listing["other_features"]) : ?>
                    <?php foreach ($listing["other_features"] as $ft_name => $other_ft) : ?>
                        <div class="inner-box featured">
                            <h2 class="desc-title"><?= $ft_name ?></h2>
                            <?php if ($other_ft["type"] == "key-value") : ?>
                                <ul class="property-features">
                                    <?php foreach ($other_ft["description"] as $ft) : ?>
                                        <li><?= $ft["key"] ?>: <span><?= $ft["value"] ?></span></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php elseif ($other_ft["type"] == "list") : ?>
                                <?php foreach ($other_ft["description"] as $ft) : ?>
                                    <li> <?= $ft ?> </li>
                                <?php endforeach; ?>
                            <?php elseif ($other_ft["type"] == "text") : ?>
                                <p><?= $listing["description"] ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <?php if ($building_plan_images) : ?>
                    <div class="inner-box accordion-fp">
                        <h2 class="desc-title">Building Plans</h2>
                        <div id="accordion">
                            <?php foreach ($building_plan_images as $index => $bp_img) : ?>
                                <div class="card">
                                    <div class="card-header" id="heading<?= $index ?>">
                                        <h3 class="accordion-heading">
                                            <button class="accordion-title" data-toggle="collapse" data-target="#collapse<?= $index ?>" aria-expanded="true" aria-controls="collapse<?= $index ?>">
                                                <?= $bp_img["plan_name"] ?>
                                            </button>
                                        </h3>
                                    </div>
                                    <div id="collapse<?= $index ?>" class="collapse show" aria-labelledby="heading<?= $index ?>" data-parent="#accordion">
                                        <div class="card-body">
                                            <div class="thumb">
                                                <img class="img-fluid" src="/<?= @$bp_img["path"] ?>" alt="<?= @$bp_img["path"] ?>" width="100%">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <!-- <div class="card">
                            <div class="card-header" id="headingTwo">
                                <h3 class="accordion-heading">
                                    <button class="accordion-title collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        Second Floor
                                    </button>
                                </h3>
                            </div>
                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                                <div class="card-body">
                                    <div class="thumb">
                                        <img class="img-fluid" src="<?= $APP->getTemplateDir() ?>assets/img/productinfo/floor-thumb-2.jpg" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="headingThree">
                                <h3 class="accordion-heading">
                                    <button class="accordion-title collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        Third Floor
                                    </button>
                                </h3>
                            </div>
                            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                                <div class="card-body">
                                    <div class="thumb">
                                        <img class="img-fluid" src="<?= $APP->getTemplateDir() ?>assets/img/productinfo/floor-thumb-3.jpg" alt="">
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        </div>
                    </div>
                <?php endif; ?>
                <!-- <div class="inner-box location-map">
                    <h2 class="desc-title">Location On Map</h2>
                    <div id="conatiner-map"></div>
                </div> -->
            </div>

            <aside id="sidebar" class="col-lg-4 col-md-12 col-xs-12 right-sidebar">

                <!-- <div class="widget mb2">
                    <button class="widget-button"><i class="lni-printer"></i></button>
                    <button class="widget-button "><i class="lni-star"></i></button>
                    <button class="widget-button"><i class="lni-zip"></i></button>
                    <div class="clearfix"></div>
                </div> -->

                <div class="widget mt3">
                    <div class="agent-inner">
                        <div class="agent-title">
                            <div class="agent-photo">
                                <a href="#"><img src="<?= $APP->getTemplateDir() ?>assets/img/productinfo/agent.jpg" alt=""></a>
                            </div>
                            <div class="agent-details">
                                <h3><a href="#">Simon Heqburn</a></h3>
                                <span><i class="lni-phone-handset"></i>(123) 123-456</span>
                            </div>
                        </div>
                        <input type="text" class="form-control" placeholder="Your Email">
                        <input type="text" class="form-control" placeholder="Your Phone">
                        <p>I'm interested in this property [ID 123456] and I'd like to know more details.</p>
                        <button class="btn btn-common fullwidth mt-4">Send Message</button>
                    </div>
                </div>

                <?php include_once(TEMPLATE_PATH . "index/html/featured-properties-widget.php"); ?>

                <!-- <div class="widget">
                    <h3 class="sidebar-title">Mortgage Calculator</h3>
                    <form class="mortgage-calc">
                        <p class="tip-content">Set This Property Price</p>
                        <div class="calc-input">
                            <input type="text" class="form-control" name="amount" placeholder="Sale Price" required="">
                            <label>$</label>
                        </div>
                        <div class="calc-input">
                            <input type="text" class="form-control" placeholder="Down Payment">
                            <label class="fa fa-usd">$</label>
                        </div>
                        <div class="calc-input">
                            <input type="text" class="form-control" placeholder="Loan Term (Years)" required="">
                            <label class="lni-calendar"></label>
                        </div>
                        <div class="calc-input">
                            <input type="text" class="form-control" placeholder="Interest Rate" required="">
                            <label>%</label>
                        </div>
                        <button class="btn btn-common">Calculate</button>
                    </form>
                </div> -->

                <?php include_once(TEMPLATE_PATH . "index/html/social-media-widget.php"); ?>
            </aside>

        </div>
    </div>
    <?php if ($similar_listings) : ?>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="desc-title">Similar Properties</h2>
                </div>
                <?php foreach ($similar_listings as $sl_data) : ?>
                    <div class="col-lg-4 col-md-6 col-xs-12">
                        <div class="property-main">
                            <div class="property-wrap">
                                <div class="property-item">
                                    <div class="item-thumb">
                                        <a class="hover-effect" href="/property/<?= @$sl_data["slug"] ?>">
                                            <img class="img-fluid img-responsive" width="100%" src="/<?= @$sl_data["image"] ?>" alt="">
                                        </a>
                                        <div class="label-inner">
                                            <?php if (@$sl_data["action"] == "sale") : ?>
                                                <span class="label-status label bg-red">For Sale</span>
                                            <?php elseif (@$sl_data["action"] == "rent") : ?>
                                                <span class="label-status label">For Rent</span>
                                            <?php elseif (@$sl_data["action"] == "shortlet") : ?>
                                                <span class="label-status label bg-yellow">For Short Let</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="item-body">
                                        <h3 class="property-title"><a href="/property/<?= @$sl_data["slug"] ?>"><?= @$sl_data["title"] ?></a></h3>
                                        <div class="adderess">
                                            <i class="lni-map-marker"></i>
                                            <?= ($sl_data["address"] . ", " . $sl_data["city"] . ", " . $sl_data["state"] . ", " . $sl_data["country"] . ".") ?>
                                        </div>
                                        <div class="pricin-list">
                                            <div class="property-price">
                                                <span><?= price_format($sl_data["price"], true) ?></span>
                                            </div>
                                            <p>
                                                <?php if (@$sl_data["bedroom"]) : ?>
                                                    <span><?= @$sl_data["bedroom"] ?> bds</span> .
                                                <?php endif; ?>
                                                <?php if (@$sl_data["bathroom"]) : ?>
                                                    <span><?= @$sl_data["bathroom"] ?> ba</span> .
                                                <?php endif; ?>
                                                <?php if (@$sl_data["landmass"]) : ?>
                                                    <span><?= @$sl_data["landmass"] ?> Sqft</span>
                                                <?php endif; ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>