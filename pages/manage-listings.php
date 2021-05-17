<?php $data = $APP->get('listings_data'); ?>
<div class="main-container section-padding" ng-app="APP" ng-controller="APPCTRL">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12">
                <h2>Manage Listings <a class="btn btn-default float-right" href="/new-listing/">New Listing</a></h2>
                <hr />

                <form method="GET" action="" data-ajax-type="json">
                    <h3 class="heading">Filter</h3>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="filter_type_id">By Type:</label>
                                <select class="form-control" id="filter_type_id" name="filter[type]">
                                    <option value="">All</option>
                                    <?php echo Table::load_combo_from_table('listing_categories', '{title}', $APP->get('filter_type'), 'title'); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="type">By Action:</label>
                                <select class="form-control" id="type" name="filter[action]">
                                    <option value="">All</option>
                                    <option value="sale" <?= $APP->get("filter_action") == "sale" ? "selected" : "" ?>>For Sale</option>
                                    <option value="rent" <?= $APP->get("filter_action") == "rent" ? "selected" : "" ?>>For Rent</option>
                                    <option value="shortlet" <?= $APP->get("filter_action") == "shortlet" ? "selected" : "" ?>>For Short Let</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="type">By State:</label>
                                <select class="form-control" id="type" name="filter[state]" ng-model="state">
                                    <option value="">All</option>
                                    <option ng-repeat="(l_state, cities) in state_locations" value="{{l_state}}" ng-selected="(state==l_state)">{{l_state}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="type">By City:</label>
                                <select class="form-control" id="type" name="filter[city]" ng-model="state_city">
                                    <option value="">All</option>
                                    <option ng-repeat="city in state_locations[state]" value="{{city}}" ng-selected="(city==state_city)">{{city}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="type">By Status:</label>
                                <select class="form-control" id="type" name="filter[status]">
                                    <option value="">All</option>
                                    <?= Table::load_combo_from_enum_list("listings", "status", $APP->get('filter_status')) ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <br>
                                <button type="submit" name="action" value="filter" class="btn btn-primary">Filter</button>
                            </div>
                        </div>
                    </div>
                    <h3 class="heading">Search</h3>
                    <div class="row form-inline">
                        <div class="input-group float-right">
                            <input type="text" class="form-control" name="q" value="<?= $APP->get('search_q'); ?>" placeholder="Search for...">
                            <span class="input-group-btn">
                                <button class="btn btn-primary" name="action" value="search" type="submit"><i class="lni-search" aria-hidden="true"></i></button>
                            </span>
                        </div>
                    </div>
                </form>
                <hr />
                <?php if ($APP->get('search_q')) : ?>
                    <div id="filterInfo" class="alert alert-info">
                        Displaying search results for <strong>&quot;<?= $APP->get('search_q'); ?>&quot;</strong><a href="manage-listings/" class="btn btn-info btn-sm">Clear Search and Filters</a>
                    </div>
                <?php endif; ?>
                <?php $APP->printMsg(); ?>
                <?php
                make_pager();
                $table = new Table();
                $table->set_columns(array(
                    'title' => 'Title',
                    'action' => 'Action',
                    'display_price' => 'Price',
                    'display_image' => 'Image',
                    'address' => 'Address',
                    'state' => 'State',
                    'city' => 'City',
                    'views' => 'Views',
                    'display_status' => 'Status',
                    'created_date' => 'Added on',
                    'updated_date' => 'Updated on'
                ));
                $table->set_data($data);
                $table->set_links(array('title' => array('url' => HOME_DIR . 'new-listing/?edit_id={id}'), 'display_image' => array('url' => HOME_DIR . '{image}')));
                $table->set_date_columns(array('created_date' => 'default', 'updated_date' => 'default'));
                $table->set_search(array(
                    'q' => $APP->get('search_q'),
                    'fields' => array('title', 'content')
                ));
                $table->set_sort(array('title', 'created_date', 'creator_id', 'category_title'));
                $table->set_options(
                    array(
                        array(
                            'text' => 'Edit',
                            'type' => 'primary',
                            'icon' => ' lni-pencil',
                            'url' => HOME_DIR . 'new-listing/?edit_id={id}'
                        ),
                        array(
                            'text' => 'View',
                            'type' => 'info',
                            'target' => '_blank',
                            'icon' => ' lni-eye',
                            'url' => HOME_DIR . 'property/{slug}'
                        ),
                        array(
                            'text' => 'Publish',
                            'type' => 'success',
                            'icon' => ' lni-ok',
                            'url' => HOME_DIR . 'manage-listings/?listing_id={id}&action=publish&redirect=' . urlencode(get_current_url()),
                            'depends_key' => 'status',
                            'depends_value' => 'unavailable'
                        ),
                        array(
                            'text' => 'Unpublish',
                            'type' => 'warning',
                            'icon' => ' lni-minus',
                            'url' => HOME_DIR . 'manage-listings/?listing_id={id}&action=unpublish&redirect=' . urlencode(get_current_url()),
                            'depends_key' => 'status',
                            'depends_value' => 'available'
                        ),
                    )
                );
                $table->set_ajax_src('listings_data');
                echo $table->print_output();
                make_pager();
                ?>
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