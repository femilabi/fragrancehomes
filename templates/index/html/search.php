<div class="search-container">
    <div class="container">
        <div class="content bg-search">
            <form>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-xs-12">
                        <div class="form-group">
                            <input class="form-control" type="text" placeholder="Enter Property Name, Keywords or Adress">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-xs-12">
                        <div class="search-category-container">
                            <label class="styled-select">
                                <select class="classic">
                                    <option>All Actions</option>
                                    <option value="sale" <?=$APP->get("filter_action") == "sale" ? "selected" : "" ?>>For Sale</option>
                                    <option value="rent" <?=$APP->get("filter_action") == "rent" ? "selected" : "" ?>>For Rent</option>
                                    <option value="shortlet" <?=$APP->get("filter_action") == "shortlet" ? "selected" : "" ?>>For Short Let</option>
                                </select>
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-xs-12">
                        <div class="search-category-container">
                            <label class="styled-select">
                                <select class="classic">
                                    <option>All Types</option>
                                    <?php echo Table::load_combo_from_table("listing_categories", '{title} ({total})', $APP->get('filter_type'), 'title'); ?>
                                </select>
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-xs-12">
                        <div class="search-category-container">
                            <label class="styled-select">
                                <select class="classic">
                                    <option>All Cities</option>
                                    <option>New York</option>
                                    <option>California</option>
                                    <option>Washington</option>
                                    <option>Chicago</option>
                                    <option>Phoenix</option>
                                    <option>Birmingham</option>
                                </select>
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-xs-12">
                        <div class="search-category-container">
                            <label class="styled-select">
                                <select class="classic">
                                    <option>Bedrooms</option>
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                    <option>6</option>
                                    <option>7</option>
                                    <option>8</option>
                                </select>
                            </label>
                        </div>
                    </div>
                </div>
            </form>
            <div class="row range-slider">
                <div class="col-lg-3 col-md-12 col-xs-12">
                    <div class="search-category-container">
                        <label class="styled-select">
                            <select class="classic">
                                <option>All Area</option>
                                <option>San Jose</option>
                                <option>Salt Lake City</option>
                                <option>Las Vegas</option>
                                <option>Boston</option>
                                <option>Tampa</option>
                                <option>Orlando</option>
                            </select>
                        </label>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-xs-12">
                    <input type="text" id="range" value="" name="range" />
                </div>
                <div class="col-lg-3 col-md-12 col-xs-12">
                    <div class="text-right btn-section">
                        <button type="button" class="btn btn-common"><i class="lni-search"></i> Search</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>