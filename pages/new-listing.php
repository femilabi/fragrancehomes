<?php
$listing_data = $APP->get("listing_data");
$features = @json_decode($listing_data["features"], true) ?: [];
$listing_images = $APP->get("listing_images");
$building_plan_images = $APP->get("building_plan_images");
?>
<section class="user-page submit-property section-padding" ng-app="APP" ng-controller="APPCTRL">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="submit-form">
                    <form method="POST" action="" enctype="multipart/form-data">
                        <h3 class="heading">Basic Information</h3>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <?php $APP->printMsg();
                                $APP->printErrors(); ?>
                                <input type="hidden" name="id" value="<?= @$listing_data["id"] ?>">
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Property Title (Maximum of 30 Chrarcters)</label>
                                    <input type="text" name="title" value="<?= @$listing_data["title"] ?>" class="form-control" placeholder="Property Title" max="30">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="form-group">
                                    <label>Action</label>
                                    <select class="classic" name="action">
                                        <option value="sale" <?= (@$listing_data["action"] == "sale" ? "selected" : "") ?>>For Sale</option>
                                        <option value="rent" <?= (@$listing_data["action"] == "rent" ? "selected" : "") ?>>For Rent</option>
                                        <option value="shortlet" <?= (@$listing_data["action"] == "shortlet" ? "selected" : "") ?>>For Short Let</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="form-group">
                                    <label>Type</label>
                                    <select class="classic" name="type">
                                        <?= Table::load_combo_from_table("listing_categories", "{title}", @$listing_data["type"], "title") ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="form-group">
                                    <label>Price</label>
                                    <input type="number" name="price" value="<?= @$listing_data["price"] ?>" class="form-control" placeholder="NGN">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="form-group">
                                    <label>Land Size</label>
                                    <input type="number" name="landmass" value="<?= @$listing_data["landmass"] ?>" class="form-control" placeholder="SqFt">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="form-group">
                                    <label>Bedrooms (optional)</label>
                                    <input type="number" name="bedroom" value="<?= @$listing_data["bedroom"] ?>" class="form-control" placeholder="Number of Bedrooms">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="form-group">
                                    <label>Bathrooms (optional)</label>
                                    <input type="number" name="bathroom" value="<?= @$listing_data["bathroom"] ?>" class="form-control" placeholder="Number of Bathrooms">
                                </div>
                            </div>
                        </div>

                        <h3 class="heading">Property Gallery</h3>
                        <div class="row mb-3">
                            <div class="row form-group col-md-6" ng-repeat="img in images">
                                <input type="hidden" name="p_images[]" ng-value="img.id" ng-if="img.id">
                                <div class="col-lg-10 col-md-10" ng-if="!img.id">
                                    <input name="p_images_{{$index}}" type="file" class="form-control" ng-required="!img.id">
                                </div>
                                <div class="col-lg-10 col-md-10" ng-if="img.id">
                                    <img class="img img-responsive" src="/{{img.path}}" alt="{{img.path}}" width="100%">
                                </div>
                                <div class="col-lg-2 col-md-2">
                                    <a class="btn btn-danger btn-sm float-right" ng-click="remove_image($index)"><b>X</b></a>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12">
                                <div class="form-group">
                                    <a class="btn btn-primary btn-sm float-right" ng-click="add_image()">+ ADD NEW IMAGE</a>
                                </div>
                            </div>
                        </div>

                        <h3 class="heading">Building Plans (Optional)</h3>
                        <div class="row mb-3">
                            <div class="row form-group col-md-6" ng-repeat="img in building_plan_images">
                                <input type="hidden" name="building_plan_images[{{$index}}][id]" ng-value="img.id" ng-if="img.id">
                                <div class="col-lg-5 col-md-5" ng-if="!img.id">
                                    <input name="building_plan_images[{{$index}}][name]" type="text" placeholder="Plan Name" class="form-control">
                                </div>
                                <div class="col-lg-5 col-md-5" ng-if="!img.id">
                                    <input name="building_plan_images_{{$index}}" type="file" class="form-control">
                                </div>
                                <div class="col-lg-10 col-md-10" ng-if="img.id">
                                    <h4>{{img.plan_name}}</h4>
                                    <img class="img img-responsive" src="/{{img.path}}" alt="{{img.path}}" width="100%">
                                </div>
                                <div class="col-lg-2 col-md-2">
                                    <a class="btn btn-danger btn-sm float-right" ng-click="remove_plan_image($index)"><b>X</b></a>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12">
                                <div class="form-group">
                                    <a class="btn btn-primary btn-sm float-right" ng-click="add_plan_image()">+ ADD NEW IMAGE</a>
                                </div>
                            </div>
                        </div>

                        <h3 class="heading">Location</h3>
                        <div class="row mb-3">
                            <div class="col-lg-6 col-md-6">
                                <div class="form-group">
                                    <label>Address</label>
                                    <input type="text" name="address" value="<?= @$listing_data["address"] ?>" class="form-control" placeholder="Address" required>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="form-group">
                                    <label>State</label>
                                    <select name="state" ng-model="state" class="classic" required>
                                        <option value="">Choose State</option>
                                        <option ng-repeat="(l_state, cities) in state_locations" value="{{l_state}}" ng-selected="(state==l_state)">{{l_state}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="form-group">
                                    <label>City</label>
                                    <select name="city" class="form-control" ng-model="state_city" required>
                                        <option value="">Choose City</option>
                                        <option ng-repeat="city in state_locations[state]" value="{{city}}" ng-selected="(city==state_city)">{{city}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="form-group">
                                    <label>Postal Code</label>
                                    <input type="text" name="postal_code" value="<?= @$listing_data["postal_code"] ?>" class="form-control" placeholder="Postal Code" required>
                                </div>
                            </div>
                        </div>
                        <h3 class="heading">Detailed Information</h3>
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <div class="form-group message">
                                    <label>Project/Property Description</label>
                                    <textarea class="form-control" name="description" placeholder="Description" required><?= @$listing_data["description"] ?></textarea>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4">
                                <div class="form-group">
                                    <label>Building Age (optional)</label>
                                    <input type="number" name="building_age" value="<?= @$listing_data["building_age"] ?>" class="form-control" placeholder="Building Age (In Years)">
                                </div>
                            </div>
                            <!-- <div class="col-lg-4 col-md-4">
                                <div class="form-group">
                                    <label>Bedrooms (optional)</label>
                                    <input type="number" name="bedroom" class="form-control" placeholder="Number of Bedrooms">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4">
                                <div class="form-group">
                                    <label>Bathrooms (optional)</label>
                                    <input type="number" name="bathroom" class="form-control" placeholder="Number of Bathrooms">
                                </div>
                            </div> -->
                        </div>
                        <h3 class="heading">Features (optional)</h3>
                        <div class="row mb-3">
                            <div class="col-lg-4 col-md-4">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="Air Conditioning" id="air-condition" name="features[]" <?= (in_array("Air Conditioning", $features) ? "checked" : "") ?>>
                                        <label class="form-check-label" for="air-condition">Air Conditioning</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="Swimming Pool" id="free-parking" name="features[]" <?= (in_array("Swimming Pool", $features) ? "checked" : "") ?>>
                                        <label class="form-check-label" for="free-parking">Swimming Pool</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="Central Heating" id="swimming-pool" name="features[]" <?= (in_array("Central Heating", $features) ? "checked" : "") ?>>
                                        <label class="form-check-label" for="swimming-pool">Central Heating</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="Laundry Room" id="laundry-room" name="features[]" <?= (in_array("Laundry Room", $features) ? "checked" : "") ?>>
                                        <label class="form-check-label" for="laundry-room">Laundry Room</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="Gym" id="window-covering" name="features[]" <?= (in_array("Gym", $features) ? "checked" : "") ?>>
                                        <label class="form-check-label" for="window-covering">Gym</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="Window Covering" id="places" name="features[]" <?= (in_array("Window Covering", $features) ? "checked" : "") ?>>
                                        <label class="form-check-label" for="places">Window Covering</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="Alarm" id="alarm" name="features[]" <?= (in_array("Alarm", $features) ? "checked" : "") ?>>
                                        <label class="form-check-label" for="alarm">Alarm</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="Central Heating" id="central-heating" name="features[]" <?= (in_array("Central Heating", $features) ? "checked" : "") ?>>
                                        <label class="form-check-label" for="central-heating">Central Heating</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr />
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <h6 class="text-info">Need more information to display. Follow the process below</h6>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Section Title" ng-model="section_title">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <select class="classic" ng-model="section_description_type">
                                        <option>Choose Information Type</option>
                                        <option value="text">Description by Text</option>
                                        <option value="key-value">Description by Key-Value</option>
                                        <option value="list">Description by List</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <a class="btn btn-primary float-right" ng-click="add_section()" ng-disabled="!(section_title.length > 0 && section_description_type.length > 0)"><i class="lni-plus"></i> ADD NEW SECTION</a>
                            </div>
                        </div>
                        <hr />
                        <div class="row mb-3" ng-repeat="(title, s) in sections">
                            <div class="col-md-8">
                                <h3 class="heading">{{title}}</h3>
                            </div>
                            <div class="col-md-4">
                                <a class="btn btn-danger float-right" ng-click="remove_section(title)">X REMOVE SECTION</a>
                            </div>
                            <div class="col-md-12">
                                <input type="hidden" name="other_features[{{title}}][type]" value="{{s.type}}">
                                <div class="form-group" ng-if="s.type == 'text'">
                                    <textarea name="other_features[{{title}}][description]" ng-model="sections[title]['description']" class="form-control"></textarea>
                                </div>
                                <div class="row form-group" ng-if="s.type == 'key-value'" ng-repeat="(id, d) in s.description">
                                    <div class="col-md-5">
                                        <input name="other_features[{{title}}][description][{{id}}][key]" ng-model="sections[title]['description'][id]['key']" type="text" class="form-control" placeholder="Key">
                                    </div>
                                    <div class="col-md-5">
                                        <input name="other_features[{{title}}][description][{{id}}][value]" ng-model="sections[title]['description'][id]['value']" type="text" class="form-control" placeholder="Value">
                                    </div>
                                    <div class="col-md-2">
                                        <a class="btn btn-danger btn-sm float-right" ng-click="remove_section_description(title, id)" ng-if="!(id == 0)">X</a>
                                    </div>
                                </div>
                                <div class="row form-group" ng-if="s.type == 'list'" ng-repeat="(id, d) in s.description">
                                    <div class="col-md-10">
                                        <input name="other_features[{{title}}][description][{{id}}]" ng-model="sections[title]['description'][id]" type="text" class="form-control" placeholder="Enter Information Here">
                                    </div>
                                    <div class="col-md-2">
                                        <a class="btn btn-danger btn-sm float-right" ng-click="remove_section_description(title, id)" ng-if="!(id == 0)">X</a>
                                    </div>
                                </div>
                                <div class="form-group" ng-if="!(s.type == 'text')">
                                    <a class="btn btn-primary btn-sm float-right" ng-click="add_section_description(title)">+</a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 mt-3">
                                <?= make_issubmit() ?>
                                <button type="submit" class="btn btn-common">Preview</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>


<script>
    app.controller('APPCTRL', function($scope, $http) {
        $scope.images = <?= @$listing_images ? json_encode($listing_images) : '[]' ?>;
        $scope.building_plan_images = <?= @$building_plan_images ? json_encode($building_plan_images) : '[]' ?>;
        $scope.state_locations = <?= json_encode(get_available_locations()) ?: '{}' ?>;
        $scope.state = '<?= @$listing_data["state"] ?>';
        $scope.state_city = '<?= @$listing_data["city"] ?>';
        $scope.sections = <?= (@$listing_data["other_features"] ?: '{}') ?>;

        $scope.add_section = function() {
            if ($scope.section_title.length > 0 && $scope.section_description_type.length > 0) {
                if (!$scope.sections[$scope.section_title]) {
                    $scope.sections[$scope.section_title] = {
                        type: $scope.section_description_type,
                        description: ($scope.section_description_type == "text" ? "" : [])
                    }

                    if (!($scope.section_description_type == "text")) {
                        $scope.add_section_description($scope.section_title);
                    }
                }
            }

            $scope.section_title = '', $scope.section_description_type = '';
        }

        $scope.remove_section = function(title) {
            if ($scope.sections[title]) delete $scope.sections[title];
        }

        $scope.add_section_description = function(title) {
            $scope.sections[title].description.push({});
        }

        $scope.remove_section_description = function(title, desc_index) {
            if ($scope.sections[title].description[desc_index]) {
                $scope.sections[title].description.splice(desc_index, 1);
            }
        }

        $scope.new_image = function() {
            return {
                id: ''
            }
        }

        $scope.add_image = function() {
            $scope.images.push($scope.new_image());
        }

        $scope.remove_image = function(index) {
            $scope.images.splice(index, 1);
            if ($scope.images.length < 1) $scope.add_image();
        }

        if ($scope.images.length < 1) $scope.add_image();

        $scope.new_image = function() {
            return {
                id: ''
            }
        }

        $scope.new_plan_image = function() {
            return {
                id: '',
                name: ''
            }
        }

        $scope.add_plan_image = function() {
            $scope.building_plan_images.push($scope.new_plan_image());
        }

        $scope.remove_plan_image = function(index) {
            $scope.building_plan_images.splice(index, 1);
            if ($scope.building_plan_images.length < 1) $scope.add_plan_image();
        }

        if ($scope.building_plan_images.length < 1) $scope.add_plan_image();
    });
</script>