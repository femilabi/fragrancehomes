<?php
$popular_listings = get_popular_listings(3);
?>
<div class="widget">
    <h3 class="sidebar-title">Popular Listings</h3>
    <div id="listing-carousel" class="owl-carousel">
        <?php
        $action_to_full = array(
            "rent" => "Rent",
            "sale" => "Sale",
            "shortlet" => "Short Let",
        );
        foreach ($popular_listings as $p_listing) : ?>
            <div class="item">
                <div class="listing-item">
                    <a href="/property/<?= $p_listing["slug"] ?>" class="listing-img-container">
                        <img src="/<?= $p_listing["image"] ?>" width="100%" alt="<?= $p_listing["image"] ?>">
                        <div class="listing-badges">
                            <span class="featured"><?= $p_listing["state"] ?></span>
                            <span>For <?= $action_to_full[$p_listing["action"]] ?></span>
                        </div>
                        <div class="listing-content">
                            <span class="listing-title"><?= $p_listing["title"] ?> <i><?= price_format($p_listing["price"], true) ?></i></span>
                            <ul class="listing-content">
                                <?php if ($p_listing["landmass"]) : ?>
                                    <li>Area <span><?= @$p_listing["landmass"] ?> sq ft</span></li>
                                <?php endif; ?>
                                <?php if ($p_listing["bedroom"]) : ?>
                                    <li>Bedrooms <span><?= $p_listing["bedroom"] ?></span></li>
                                <?php endif; ?>
                                <?php if ($p_listing["bathroom"]) : ?>
                                    <li>Bathrooms <span><?= $p_listing["bathroom"] ?></span></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
        <!-- <div class="item">
            <div class="listing-item">
                <a href="#" class="listing-img-container">
                    <img src="<?= $APP->getTemplateDir() ?>assets/img/productinfo/listing2.jpg" alt="">
                    <div class="listing-badges">
                        <span class="featured">Featured</span>
                        <span>For Sale</span>
                    </div>
                    <div class="listing-content">
                        <span class="listing-title">Eagle Apartments <i>$275,000</i></span>
                        <ul class="listing-content">
                            <li>Area <span>530 sq ft</span></li>
                            <li>Rooms <span>3</span></li>
                            <li>Beds <span>1</span></li>
                            <li>Baths <span>1</span></li>
                        </ul>
                    </div>
                </a>
            </div>
        </div>
        <div class="item">
            <div class="listing-item">
                <a href="#" class="listing-img-container">
                    <img src="<?= $APP->getTemplateDir() ?>assets/img/productinfo/listing3.jpg" alt="">
                    <div class="listing-badges">
                        <span class="featured">Featured</span>
                        <span>For Sale</span>
                    </div>
                    <div class="listing-content">
                        <span class="listing-title">Eagle Apartments <i>$275,000</i></span>
                        <ul class="listing-content">
                            <li>Area <span>530 sq ft</span></li>
                            <li>Rooms <span>3</span></li>
                            <li>Beds <span>1</span></li>
                            <li>Baths <span>1</span></li>
                        </ul>
                    </div>
                </a>
            </div>
        </div> -->
    </div>
</div>