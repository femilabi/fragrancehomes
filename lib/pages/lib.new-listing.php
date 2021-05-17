<?php
// $APP->setIsJSON(true);
$APP->setTitle("New Property Listing");

if (@$_GET['edit_id'] && !issubmit()) {
    $listing = new DBRow("listings", "id", @$_GET["edit_id"]);
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
                AND is_plan = 0
        ");
        $APP->assign("listing_images", $listing_images);

        // Building Plan Images
        $building_plan_images = $DB->get_query_set("
            SELECT * 
            FROM 
                " . DB_PREFIX . "listing_images 
            WHERE 
                listing_id = " . $listing->get_id() . " 
                AND deleted = 0
                AND is_plan = 1
        ");
        $APP->assign("building_plan_images", $building_plan_images);
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
    $data["other_features"] = @json_encode($data["other_features"]);
    $data["status"] = "available";
    $data["country"] = "Nigeria";

    $listing = new DBRow("listings", "id", @$_GET["edit_id"]);
    if ($listing->exists()) {
        $data["updated_date"] = time();
    } else {
        $data["created_date"] = time();
    }
    $listing->set_data($data);
    if ($listing->save()) {
        $APP->setSessionMsg("Project/Property has been listed successfully", "success");

        if (@$data["p_images"]) {
            $DB->query("UPDATE " . DB_PREFIX . "listing_images SET deleted = 1 WHERE listing_id = " . intval($listing->get_id()) . " AND id NOT IN (" . implode(",", $data["p_images"]) . ")AND is_plan = 0");
        }

        $listing_images = $DB->get_query_set("
            SELECT * 
            FROM " . DB_PREFIX . "listing_images 
            WHERE listing_id = " . intval($listing->get_id()) . " 
                AND deleted = 0
                AND is_plan = 0
        ");
        if (@$listing_images[0]) $default_image = $listing_images[0]["path"];

        if ($files) {
            foreach ($files as $file_index => $file_content) {
                if (stripos($file_index, "p_images") === false) continue;
                $file_upload = upload_file("listing_images", $file_index);
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
        $listing->set("image", @$default_image);


        if (@$data["building_plan_images"]) {
            $DB->query("UPDATE " . DB_PREFIX . "listing_images SET deleted = 1 WHERE listing_id = " . intval($listing->get_id()) . " AND id NOT IN (" . (implode(",", array_column($data["building_plan_images"], "id")) ?: "0") . ") AND is_plan = 1");

            foreach ($data["building_plan_images"] as $index => $b_plan) {
                if (!(@$files["building_plan_images_" . $index] && @$b_plan["name"])) continue;
                $img = new DBRow("listing_images", "id", @$b_plan["id"]);
                if (!$img->exists() || $img->get("listing_id") == $listing->get_id()) {
                    $img->set_data(array(
                        "plan_name" => $b_plan["name"],
                        "listing_id" => $listing->get_id(),
                        "deleted" => 0,
                        "is_plan" => 1,
                        "created_date" => time()
                    ));
                    $file_upload = upload_file("building_plan_images", "building_plan_images_" . $index);
                    if ($file_upload["status"] == "success") {
                        $img->set("path", $file_upload["filename"]);
                    }
                    $img->save();
                }
            }
        }

        if ($listing->save()) {
            redirect(get_current_url());
        }
    } else {
        $APP->setMsg("Request failed. Please try again", "error");
    }
}