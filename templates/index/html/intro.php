<section id="intro" class="section-intro" ng-app="APP" ng-controller="APPCTRL">
    <div class="search-container">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="intro-sub-heading">Find Your Dream Apartment!</h4>
                    <h2>The Number One Plug<br> For Best Properties</h2>
                    <div class="content">
                        <form id="form-filter" method="get" action="/listings/">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12">
                                    <div class="form-group">
                                        <input class="form-control" name="q" type="text" placeholder="Enter Property Name or Keyword or Adress">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-xs-12">
                                    <div class="search-category-container">
                                        <label class="styled-select">
                                            <select class="classic" name="filter[action]">
                                                <option value="">Any Action</option>
                                                <option value="sale" <?= $APP->get("filter_action") == "sale" ? "selected" : "" ?>>For Sale</option>
                                                <option value="rent" <?= $APP->get("filter_action") == "rent" ? "selected" : "" ?>>For Rent</option>
                                                <option value="shortlet" <?= $APP->get("filter_action") == "shortlet" ? "selected" : "" ?>>For Short Let</option>
                                            </select>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-xs-12">
                                    <div class="search-category-container">
                                        <label class="styled-select">
                                            <select class="classic" name="filter[type]">
                                                <option value="">All Types</option>
                                                <?php echo Table::load_combo_from_table("listing_categories", '{title}', $APP->get('filter_type'), 'title'); ?>
                                            </select>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-12 col-xs-12">
                                    <div class="search-category-container">
                                        <label class="styled-select">
                                            <select class="classic" ng-model="state" name="filter[state]">
                                                <option value="" selected>All States</option>
                                                <option ng-repeat="(l_state, cities) in state_locations" value="{{l_state}}" ng-selected="(state==l_state)">{{l_state}}</option>
                                            </select>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-xs-12">
                                    <div class="search-category-container">
                                        <label class="styled-select">
                                            <select class="classic" ng-model="city" name="filter[state_city]">
                                                <option value="" selected>All Cities</option>
                                                <option ng-repeat="city in state_locations[state]" value="{{city}}" ng-selected="(city==state_city)">{{city}}</option>
                                            </select>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="row range-slider">
                            <div class="col-lg-3 col-md-6 col-xs-12">
                                <div class="search-category-container">
                                    <label class="styled-select">
                                        <input type="number" name="filter[bedroom]" value="<?= $APP->get("filter_bedroom") ?>" class="form-control" placeholder="Bedrooms">
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-xs-12">
                                <div class="search-category-container">
                                    <label class="styled-select">
                                        <input type="number" name="filter[min_price]" value="<?= $APP->get("filter_min_price") ?>" class="form-control" placeholder="Minimum Price">
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-xs-12">
                                <div class="search-category-container">
                                    <label class="styled-select">
                                        <input type="number" name="filter[max_price]" value="<?= $APP->get("filter_max_price") ?>" class="form-control" placeholder="Maximum Price">
                                    </label>
                                </div>
                            </div>
                            <!-- <div class="col-lg-6 col-md-12 col-xs-12">
                                <input type="text" id="range" value="" name="price" />
                            </div> -->
                            <div class="col-lg-3 col-md-12 col-xs-12">
                                <div class="text-right btn-section">
                                    <button type="submit" name="action" value="filter" class="btn btn-common" onclick="$('#form-filter').submit();"><i class="lni-search"></i> Search</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    app.controller('APPCTRL', function($scope, $http) {
        $scope.state_locations = <?= json_encode(get_available_locations()) ?: '{}' ?>;
        $scope.state = '<?= $APP->get("filter_state") ?>';
        $scope.state_city = '<?= $APP->get("filter_city") ?>';
    });
</script>