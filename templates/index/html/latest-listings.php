<?php
$latest_listings = $APP->get("latest_listings");
?>
<section class="latest-property section-padding">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title-header text-center">
                    <p>Explore</p>
                    <h2 class="section-title">Latest Listings</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 wow fadeIn" data-wow-delay="0.8s">
                <div id="latest-property" class="owl-carousel">
                    <?php foreach ($latest_listings as $l_data) : ?>
                        <div class="item">
                            <div class="property-main">
                                <div class="property-wrap">
                                    <div class="property-item">
                                        <div class="item-thumb">
                                            <a class="hover-effect" href="/property/<?= @$l_data["slug"] ?>">
                                                <img class="img-fluid img-responsive" width="100%" src="/<?= @$l_data["image"] ?>" alt="">
                                            </a>
                                            <div class="label-inner">
                                                <?php if (@$l_data["action"] == "sale") : ?>
                                                    <span class="label-status label bg-red">For Sale</span>
                                                <?php elseif (@$l_data["action"] == "rent") : ?>
                                                    <span class="label-status label">For Rent</span>
                                                <?php elseif (@$l_data["action"] == "shortlet") : ?>
                                                    <span class="label-status label bg-yellow">For Short Let</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="item-body">
                                            <h3 class="property-title"><a href="/property/<?= @$l_data["slug"] ?>"><?= @$l_data["title"] ?></a></h3>
                                            <div class="adderess">
                                                <i class="lni-map-marker"></i>
                                                <?= ($l_data["address"] . ", " . $l_data["city"] . ", " . $l_data["state"] . ", " . $l_data["country"] . ".") ?>
                                            </div>
                                            <div class="pricin-list">
                                                <div class="property-price">
                                                    <span><?= price_format($l_data["price"], true) ?></span>
                                                </div>
                                                <p>
                                                    <?php if (@$l_data["bedroom"]) : ?>
                                                        <span><?= @$l_data["bedroom"] ?> bds</span> .
                                                    <?php endif; ?>
                                                    <?php if (@$l_data["bathroom"]) : ?>
                                                        <span><?= @$l_data["bathroom"] ?> ba</span> .
                                                    <?php endif; ?>
                                                    <?php if (@$l_data["landmass"]) : ?>
                                                        <span><?= @$l_data["landmass"] ?> Sqft</span>
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
        </div>
    </div>
</section>