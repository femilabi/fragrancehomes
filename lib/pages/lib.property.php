<?php
$listing = new DBRow("listings", "id");
$listing->search("slug", $APP->p(1));
if ($listing->exists()) {
    $listing_data = $listing->get_data();
    $APP->setTitle($listing->get("title"));
    $listing_data["features"] = json_decode($listing_data["features"], true);
    $listing_data["other_features"] = json_decode($listing_data["other_features"], true);
    $APP->assign("listing", $listing_data);

    // Fetch all images
    $listing_images = $DB->get_query_set("
        SELECT * 
        FROM " . DB_PREFIX . "listing_images 
        WHERE listing_id = " . $listing->get_id() . " 
        AND deleted = 0
        AND is_plan = 0
    ");
    $APP->assign("listing_images", $listing_images);

    // Fetch all building plan images, 
    $building_plan_images = $DB->get_query_set("
        SELECT * 
        FROM " . DB_PREFIX . "listing_images 
        WHERE listing_id = " . $listing->get_id() . " 
        AND deleted = 0
        AND is_plan = 1
    ");
    $APP->assign("building_plan_images", $building_plan_images);

    // Fetch similar listings
    $search_criterias = array($listing->get("type"), $listing->get("city"), $listing->get("state"), $listing->get("address"), $listing->get("features"));
    $similar_listings = $DB->get_query_set("
        SELECT 
            *, 
            MATCH(type, city, state, address, features) AGAINST('" . implode(" ", $search_criterias) . "' IN NATURAL LANGUAGE MODE) AS relevance
        FROM " . DB_PREFIX . "listings 
        WHERE MATCH(type, city, state, address, features) AGAINST('" . implode(" ", $search_criterias) . "' IN NATURAL LANGUAGE MODE) 
        ORDER BY relevance DESC LIMIT 3
    ");
    $APP->assign("similar_listings", $similar_listings);
} else {
    $APP->setPage("404");
}
?>