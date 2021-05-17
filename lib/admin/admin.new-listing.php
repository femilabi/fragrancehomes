<?php 
$APP->setTitle("New Property Listing");

if (@$_GET['id'] && !issubmit()) {
    $listing = new DBRow("listings", "id", @$_GET["id"]);
    if ($listing->exists()) {
        $APP->assign("listing_data", $listing->get_data());
        // Fetch images attached to this listing
        $listing_images = $DB->get_query_set("
            SELECT * 
            FROM 
                " . DB_PREFIX . "listing_images 
            WHERE 
                listing_id = " . $listing->get_id() . " 
                AND deleted = 0
        ");
        $APP->assign("listing_images", $listing_images);
    } else {
        $APP->setMsg("Project/Project does not exists in the database", "error");
    }
}

if (issubmit()) {
    $data = $_POST;
    $files = $_FILES;
    
    $err = 0;
    $required_fields = array("title", "action", "type", "price", "address", "city", "state", "postal_code", "description");
    foreach ($required_fields as $r) {
        if (!@$data[$r]) {
            $APP->setError($r, "$r field is required");
            $err = 1;
        }
    }

    $data["slug"] = get_valid_table_slug($data["title"], ["listings"]);
    $data["price"] = doubleval($data["price"]);
    $data["landmass"] = intval($data["landmass"]);
    $data["building_age"] = intval($data["building_age"]);
    $data["bedroom"] = intval($data["bedroom"]);
    $data["bathroom"] = intval($data["bathroom"]);
    $data["postal_code"] = intval($data["postal_code"]);
    $data["address"] = strip_tags($data["address"]);
    $data["city"] = strip_tags($data["city"]);
    $data["state"] = strip_tags($data["state"]);
    $data["description"] = strip_tags($data["description"]);
    $data["features"] = json_encode($data["features"]);
    $data["other_features"] = json_encode($data["other_features"]);
    $data["status"] = "available";
    $data["country"] = "Nigeria";

    $listing = new DBRow("listings", "id", @$data["id"]);
    if ($listing->exists()) {
        $data["updated_date"] = time();
    } else {
        $data["created_date"] = time();
    }
    $listing->set_data($data);
    if ($listing->save()) {
        
        $APP->setSessionMsg("Project/Property has been listed successfully", "success");

        if (@$data["images"]) {
            $DB->query("UPDATE " . DB_PREFIX . "listing_images SET deleted = 1 WHERE listing_id = " . intval($listing->get_id()) . " AND id NOT IN (" . implode(",", $data["images"]) . ")");
        }
        
        $listing_images = $DB->get_query_set("
            SELECT * 
            FROM " . DB_PREFIX . "listing_images 
            WHERE listing_id = " . intval($listing->get_id()) . " 
                AND deleted = 0
        ");
        if (@$listing_images[0]) $default_image = $listing_images[0]["path"];

        $total_images = count($listing_images);
        if ($total_images < 4) {
            foreach ($files as $file_id => $file_content) {
                $file_upload = upload_file("listing_images", $file_id);
                if ($file_upload["status"] == "success") {
                    $img = new DBRow("listing_images", "id");
                    $img->set_data(array(
                        "listing_id" => $listing->get_id(),
                        "path" => $file_upload["filename"],
                        "deleted" => 0,
                        "created_date" => time()
                    ));
                    if ($img->save()) {
                        if (!@$default_image) $default_image = $img->get("path");
                    }
                }
            }
        }

        // Update listing images
        if ($default_image) {
            $listing->set("image", $default_image);
        } else {
            $listing->set("available", 0);
            $APP->setSessionMsg("Project/Property has been listed successfully. But an error occurred during images upload", "warning");
        }
        
        if ($listing->save()) {
            redirect(get_current_url());
        }
    } else {
        $APP->setMsg("Request failed. Please try again", "error");
    }
}

$APP->assign("state_locations", get_available_locations());