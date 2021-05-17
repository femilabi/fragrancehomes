<?php
$listings_data = $APP->get("listings_data");
?>
<div class="main-container section-padding" ng-app="APP" ng-controller="APPCTRL">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-12 col-xs-12">

                <div class="product-filter">

                    <div class="sort-by">
                        <span>Sort by:</span>
                        <div class="sort-by-select">
                            <select class="classic">
                                <option>Default Order</option>
                                <option>Price Low to High</option>
                                <option>Price High to Low</option>
                                <option>Newest Properties</option>
                                <option>Oldest Properties</option>
                            </select>
                        </div>
                    </div>

                    <div class="layout-switcher">
                        <a href="#" class="list active">
                            <i class="lni-menu"></i>
                        </a>
                        <a href="#" class="grid">
                            <i class="lni-grid"></i>
                        </a>
                    </div>
                    <p class="text-left"><?= intval($APP->get("total_data")) ?> properties found</p>
                </div>

                <div class="listing-container list-layout">
                    <?php foreach ($listings_data as $l_data) : ?>
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
                    <?php endforeach; ?>
                </div>

                <div class="pagination-container">
                    <nav>
                        <?php
                            pager();
                        ?>
                    </nav>
                </div>
            </div>
            <div class="col-lg-4 col-md-12 col-xs-12">
                <div class="sidebar sticky right">
                    <div class="widget">
                        <h3 class="sidebar-title">Find New Property</h3>
                        <form action="" method="get">
                            <div class="row with-forms">
                                <div class="col-md-12">
                                    <select class="classic" name="filter[action]">
                                        <option value="">Any Action</option>
                                        <option value="sale" <?= $APP->get("filter_action") == "sale" ? "selected" : "" ?>>For Sale</option>
                                        <option value="rent" <?= $APP->get("filter_action") == "rent" ? "selected" : "" ?>>For Rent</option>
                                        <option value="shortlet" <?= $APP->get("filter_action") == "shortlet" ? "selected" : "" ?>>For Short Let</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row with-forms">
                                <div class="col-md-12">
                                    <select class="classic" name="filter[type]">
                                        <option value="">All Types</option>
                                        <?php echo Table::load_combo_from_table("listing_categories", '{title}', $APP->get('filter_type'), 'title'); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row with-forms">
                                <div class="col-md-12">
                                    <select class="classic" ng-model="state" name="filter[state]">
                                        <option value="" selected>All States</option>
                                        <option ng-repeat="(l_state, cities) in state_locations" value="{{l_state}}" ng-selected="(state==l_state)">{{l_state}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row with-forms">
                                <div class="col-md-12">
                                    <select class="classic" ng-model="city" name="filter[state_city]">
                                        <option value="" selected>All Cities</option>
                                        <option ng-repeat="city in state_locations[state]" value="{{city}}" ng-selected="(city==state_city)">{{city}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="number" name="bedroom" value="<?= @$APP->get("filter_bedroom") ?>" class="form-control" placeholder="Bedrooms">
                                </div>
                            </div>

                            <div class="price-range">
                                <label>Price Range</label>
                                <div id="area-price">
                                    <input type="number" class="form-control" name="lowest" value="<?= @$APP->get("filter_lowest") ?>" placeholder="Lowest">
                                    <span>to</span>
                                    <input type="number" class="form-control" name="highest" value="<?= @$APP->get("filter_highest") ?>" placeholder="Highest">
                                </div>
                            </div>

                            <div class="more-search-options">
                                <button class="fullwidth btn btn-common" name="action" value="filter">Search</button>
                            </div>
                        </form>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

<script>
    app.controller('APPCTRL', function($scope, $http) {
        $scope.state_locations = <?= json_encode(get_available_locations()) ?: '{}' ?>;
        $scope.state = '<?= $APP->get("filter_state") ?>';
        $scope.state_city = '<?= $APP->get("filter_city") ?>';
    });
</script>