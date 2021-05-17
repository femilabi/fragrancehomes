<?php
//used with select to print categories in indented form with the other of childhood
function print_category_option($category_id = 0, $level = 1, $selected = '')
{
    global $DB;
    if (!$category_id) {
        $query = "SELECT * FROM " . DB_PREFIX . "post_categories WHERE parent_id = 0";
        $categories = $DB->get_query_set($query);
        if (is_array($categories)) {
            foreach ($categories as $c) {
                print_category_option($c['id'], 0, $selected);
            }
        }
        return;
    }
    $cat = new DBRow('post_categories', 'id', $category_id);
    if ($cat->exists()) {
        echo ('<option value="' . $category_id . '"' . ($selected && $selected == $category_id ? 'selected' : '') . '>' . str_repeat('- ', $level) . $cat->get('title') . '</option>');
        $query = "SELECT * FROM " . DB_PREFIX . "post_categories WHERE parent_id = '$category_id'";
        $categories = $DB->get_query_set($query);
        if (is_array($categories)) {
            foreach ($categories as $c) {
                print_category_option($c['id'], $level + 1, $selected);
            }
        }
    }
}

function make_code_captcha()
{
    return '<img id="captcha-img" style="display:inline; margin-right:4px" src="' . BASE_DIR . 'secure-image/?hash=' . random_string(12) . '" class="thumbnail" /><button onclick="$(\'#captcha-img\').attr(\'src\',\'' . BASE_DIR . 'secure-image/?hash=\' + randomString(12));" type="button" class="btn btn-default"><span class="glyphicon glyphicon-refresh"></span></button>
					<input class="form-control" style="max-width:210px" type="text" name="captcha_response_field" required="required" id="captcha-text" placeholder="captcha"/>';
}

function make_recaptcha()
{
    return '
		<script src="https://www.google.com/recaptcha/api.js"></script>
		<div class="g-recaptcha" data-sitekey="' . RECAPTCHA_PUB_KEY . '"></div>
	';
}

function validate_recaptcha()
{
    //Validate reCaptcha
    $recaptcha_response = @$_POST['g-recaptcha-response'];
    if (!$recaptcha_response) {
        return;
    }
    $recaptcha_params = array(
        'secret' => RECAPTCHA_PRIV_KEY,
        'response' => $recaptcha_response,
        'remoteip' => $_SERVER['REMOTE_ADDR']
    );
    $response = @json_decode(send_remote_request('https://www.google.com/recaptcha/api/siteverify', '', $recaptcha_params), MYSQL_ASSOC);

    if ((is_array($response) && @$response['success'])) {
        return 1;
    }
}
function validate_code_captcha_()
{
    if (@$_SESSION['current_captcha'] && @$_SESSION['current_captcha'] == @$_POST['captcha_response_field']) {
        return 1;
    }
}

// function validate_image ($file) {
// 	GLOBAL $APP;
// 	$err = 0;
// 	$check = getimagesize($file["tmp_name"]);
//     if($check === false) {
// 		return array('error' => "File is not an image.");
// 	}
// 	if ($file["size"] > 5000000) {
// 		return array('error' => "Image File is too large.");
// 	}
// 	if (!in_array(strtolower(pathinfo($file['name'], PATHINFO_EXTENSION)), array("jpg","jpeg","png","gif"))) {
// 		return array('error' => "Image file type not allowed.");
// 	}
// 	return array('success' => 1);;
// }

// function upload_image ($file, $folder) {
// 	$response = validate_image ($file);
// 	if (@$response['success']) {
// 		$file_ext = pathinfo($file['name'], PATHINFO_EXTENSION);
// 		$new_name = @$file['new_name'] ? $file['new_name'] : basename($file['name'], "." . $file_ext);
// 		$filename = generate_file_name($folder, array("new_name" => $new_name, "ext" => $file_ext));
// 		if (move_uploaded_file($file["tmp_name"], SYS_PATH . $filename)) {
// 			return array("success" => true, "dir" => BASE_DIR . $filename, "path" => @$filename);
// 		}
// 		unset($response['success']);
// 		$response['error'] = "File could not be uploaded";
// 		return $response;
// 	}
// 	return $response;
// }


// function generate_file_name($folder, $file_info = []) {
// 	// var_dump($folder, $file_info); exit;
// 	$file_path = $folder . @$file_info['new_name'] . "." . @$file_info['ext'];
// 	if (!file_exists(SYS_PATH . $file_path)) {
// 		return $file_path;
// 	}
// 	$file_info['new_name']  = @$file_info['new_name'] . "_" . rand(1,1000);
// 	return generate_file_name($folder, $file_info);
// }


function pager()
{
    global $APP;
    include(LIB_PATH . 'pager.php');
}

function upload_file($category, $name)
{
    global $APP, $USER;
    $allowed = array('png', 'jpg', 'jpeg', 'pdf', 'gif');
    $categories = array('post_thumbs', 'user_photo', 'listing_images', 'building_plan_images');
    $file_info = ['category' => $category];
    $file_upl_response = [];
    $file_upl_response = array();
    $size_kb = $APP->getSettings('max_upload_size') ?: 200;
    $allowed_size = 1024 * $size_kb;
    if (isset($_FILES[$name]) && $_FILES[$name]['error'] == 0) {
        $extension = pathinfo($_FILES[$name]['name'], PATHINFO_EXTENSION);
        $image_path = SELF_PATH . 'images/';

        //validate file extension to prevent malicious uploads
        if (!in_array(strtolower($extension), $allowed)) {
            $file_upl_response['status'] = 'error';
            $file_upl_response['error'] = 'Invalid file type';
            return $file_upl_response;
        }

        //Check if file info is posted
        if (!is_array($file_info)) {
            $file_upl_response['status'] = 'error';
            $file_upl_response['error'] = 'No file upload information provided';
            return $file_upl_response;
        }

        //Check if file size is not too large
        if ($_FILES[$name]['size'] > $allowed_size) {
            $file_upl_response['status'] = 'error';
            $file_upl_response['error'] = 'Photo file too large. File should not be more than ' . $size_kb . ' KB';
            return $file_upl_response;
        }

        //Validate file category
        if (!isset($file_info['category']) || !in_array($file_info['category'], $categories)) {
            $file_upl_response['status'] = 'error';
            $file_upl_response['error'] = 'Invalid or no file category specified';
            return $file_upl_response;
        }

        //Work on the categories if any logistics
        if (isset($file_info['category'])) {
            switch ($file_info['category']) {
                case 'gallery':
                    break;
                case 'slideshow':
                    break;
            }
        }
        $path = $image_path . $file_info['category'] . '/';
        // $filename = $_FILES[$name]['name'];
        $filename = generate_file_name($_FILES[$name]['name']);
        $filepath = $path . $filename;

        //Check directory if exists
        if (!file_exists($path)) {
            mkdir($path);
        }

        //Save file

        $filepath = validate_upload_path($filepath);
        if (move_uploaded_file($_FILES[$name]['tmp_name'], $filepath)) {
            $file_upl_response['status'] = 'success';
            $filepath = str_replace(SYS_PATH, '', $filepath);
            $file_upl_response['filename'] = $filepath;
            return $file_upl_response;
        }
    } else {
        $file_upl_response['status'] = 'error';
        $file_upl_response['error'] = 'No file uploaded';
    }
    return $file_upl_response;
}

function generate_file_name($file)
{
    global $USER;
    return time() . "_" . random_string(8) . "." . pathinfo($file, PATHINFO_EXTENSION);
}

function validate_upload_path($path)
{
    $ext = pathinfo($path, PATHINFO_EXTENSION);
    $name = pathinfo($path, PATHINFO_FILENAME);
    $dir = pathinfo($path, PATHINFO_DIRNAME);
    if (file_exists($path)) {
        if (strpos($name, '-') === false) {
            $name .= '-1';
        } else {
            $fragment = explode('-', $name);
            if (is_numeric($fragment[count($fragment) - 1])) {
                $fragment[count($fragment) - 1] += 1;
                $name = implode('-', $fragment);
                if ($fragment[count($fragment) - 1] > 80) $name .= '-1';
            } else {
                $name .= '-1';
            }
        }
        $path = $dir . '/' . $name . '.' . $ext;
        return validate_upload_path($path);
    } else {
        return $path;
    }
}

function get_unique_string($table, $str_len = 32)
{
    $str = random_string(12);
    $row = new DBRow($table, "id");
    $row->search("ref", $str);
    if ($row->exists()) return get_unique_string($table, $str_len);
    return $str;
}

function get_product_categories()
{
    global $APP, $DB;
    if ($APP->get("product_categories")) return $APP->get("product_categories");
    $product_categoies = $DB->get_query_set("
        SELECT 
            c.*,
            (
                SELECT 
                    COUNT(p.id) 
                FROM 
                    " . DB_PREFIX . "products AS p
                WHERE 
                    p.category_id = c.id
                    AND p.available = 1
            ) AS total_available
        FROM 
            " . DB_PREFIX . "categories AS c
    ");
    $APP->assign("product_categories", $product_categoies);
    return $product_categoies;
}

function get_product_data($id)
{
    $product = new DBRow("products", "id", $id);
    if ($product->exists() && $product->get("available")) {
        return $product->get_data();
    }
}

function create_invoice($type, $user_id, $amount, $data)
{
    if (!($type && $user_id && @$data["currency_id"] && $amount >= 0)) return;

    $invoice = new DBRow("invoices", "id");
    if (!in_array($type, ["goods", "service"])) {
        $invoice->set_data(
            array(
                "type" => "other",
                "other_type" => $type
            )
        );
    } else {
        $invoice->set("type", $type);
        $invoice->set("cart_id", @$data["cart_id"]);
    }
    $invoice->set_data(array(
        "ref_id" => generate_invoice_ref_no(),
        "user_id" => $user_id,
        "currency_id" => @$data["currency_id"],
        "amount" => $amount,
        "status" => "pending",
        "created_date" => time(),
        "timeout" => time() + (60 * 60 * 24 * 7)
    ));
    if ($invoice->save()) return $invoice->get_data();
}

function generate_invoice_ref_no()
{
    $ref = "INV-" . random_string(10, false);
    $invoice = new DBRow("invoices", "id");
    $invoice->search("ref_id", $ref);
    if ($invoice->exists()) return generate_invoice_ref_no();
    return strtoupper($ref);
}

function process_invoice($invoice_id, $amount, $currency_id)
{
    if (!($invoice_id && $amount >= 0 && $currency_id)) return;
    $invoice = new DBRow("invoices", "id", $invoice_id);
    if ($invoice->exists() && $invoice->get("status") == "pending" && $invoice->get("currency_id") == $currency_id) {
        $invoice->set("amount_paid", $invoice->get("amount_paid") + $amount);
        $returnable = $invoice->get("amount_paid") - invoice_due_amount($invoice->get_data());
        if ($returnable >= 0) {
            $invoice->set("returnable", $returnable);
            $invoice->set("status", "completed");
        }
        $invoice->set("updated_date", time());
        if ($invoice->save()) {
            if ($invoice->get("type") == "goods") {
                create_new_order($invoice->get_data());
            } elseif ($invoice->get("type") == "service") {
                // Create new service order
                $order = new DBRow("orders", "id");
                $order->set("user_id", $invoice->get("user_id"));
                $order->set("type", "service");
                $order->set("note", $invoice->get("description"));
            } else {
                credit_user($amount, $invoice->get("currency_id"), $invoice->get("user_id"));
            }
            return $invoice->get_id();
        }
    }
}

function get_cart_items($cart_id)
{
    global $DB;
    $cart_items = $DB->get_query_set("SELECT * FROM " . DB_PREFIX . "cart_items WHERE deleted = 0 AND cart_id = " . $cart_id);
    return $cart_items;
}

function invoice_due_amount($invoice_data)
{
    return floatval(
        $invoice_data["amount"] + $invoice_data["shipping_fee"] - ($invoice_data["amount_paid"] + $invoice_data["discount"])
    );
}

function get_unique_order_ref()
{
    $order_ref = "O-" . random_string(10, false);

    $order = new DBRow("orders", "id");
    $order->search("ref_id", $order_ref);
    if (!$order->exists()) return strtoupper($order_ref);
    return get_unique_order_ref();
}

function create_new_order($invoice_data)
{
    global $DB;
    $order = new DBRow("orders", "id");
    $order->set("user_id", @$invoice_data['user_id']);
    $order->set("invoice_id", @$invoice_data['id']);
    $order->set("coupon_code", @$invoice_data['coupon']);
    $order->set("ref_id", get_unique_order_ref());
    $order->set("status", "in_progress");
    $order->set("billing_address", @$invoice_data["billing_address"]);
    $order->set("shipping_address", (@$invoice_data["shipping_address"] ?: @$invoice_data["billing_address"]));
    $order->set("note", @$invoice_data['note']);
    $order->set("created_date", time());
    if ($order->save()) {
        $cart_items = get_cart_items($invoice_data["cart_id"]);
        for ($i = 0; $i < count($cart_items); $i++) {
            $order_item = new DBRow("order_items", "id");
            $order_item->set_data($cart_items[$i]);
            $order_item->set("order_id", $order->get_id());
            if ($order_item->save()) {
                $DB->query("UPDATE " . DB_PREFIX . "products SET total_purchase = total_purchase + " . $order_item->get("quantity") . " WHERE id = " . $order_item->get("product_id"));
            }
        }
        return $order->get_data();
    }
}

function top_purchased_product_categories($limit = null)
{
    global $DB, $APP;

    if ($APP->getSettings("tppcs_last_fetch") + (60 * 3) > time()) {
        $tppcs = json_decode($APP->getSettings("top_purchased_product_categories"), true);
        if ($tppcs) return $tppcs;
    }

    $tppcs = $DB->get_query_set("
        SELECT
            c.id,
            c.slug,
            c.name,
            COUNT(p.total_purchase) AS top_purchased
        FROM 
            " . DB_PREFIX . "categories AS c,
            " . DB_PREFIX . "products AS p
        WHERE
            c.id = p.category_id
        GROUP BY c.id ORDER BY top_purchased" . ($limit ? " LIMIT $limit" : ""));
    $APP->saveSettings("top_purchased_product_categories", json_encode($tppcs));
    $APP->saveSettings("tppcs_last_fetch", time());

    return $tppcs;
}

function rating_widget($rating)
{
    return '
        <li><i class="fa fa-star' . ($rating > 0 ? (($rating >= 0.5 && $rating < 1) ? "-half-o" : "") : "-o") . '"></i></li>
        <li><i class="fa fa-star' . ($rating > 1 ? (($rating >= 1.5 && $rating < 2) ? "-half-o" : "") : "-o") . '"></i></li>
        <li><i class="fa fa-star' . ($rating > 2 ? (($rating >= 2.5 && $rating < 3) ? "-half-o" : "") : "-o") . '"></i></li>
        <li><i class="fa fa-star' . ($rating > 3 ? (($rating >= 3.5 && $rating < 4) ? "-half-o" : "") : "-o") . '"></i></li>
        <li><i class="fa fa-star' . ($rating > 4 ? (($rating >= 4.5 && $rating < 5) ? "-half-o" : "") : "-o") . '"></i></li>
    ';
}

function get_available_locations()
{
    global $DB, $APP;
    if ($APP->get("state_locations")) return $APP->get("state_locations");
    $locations = $DB->get_query_set("SELECT * FROM " . DB_PREFIX . "state_locations");
    $state_locations = [];
    foreach ($locations as $l) {
        $state_locations[$l["state"]] = json_decode($l["cities"], true);
    }
    $APP->assign("state_locations", $state_locations);
    return $state_locations;
}

function get_popular_listings($limit = 3)
{
    global $DB;
    $popular_listings = $DB->get_query_set("
        SELECT * 
        FROM " . DB_PREFIX . "listings 
        WHERE status = 'available' 
        ORDER BY views DESC LIMIT $limit
    ");
    return $popular_listings;
}

function currency_format($amount, $comma = false, $symbol = "₦")
{
    $amount = floatval($amount);
    return $symbol . number_format($amount, 2, '.', $comma ? ',' : '');
}

function price_format($amount, $comma = false, $symbol = "₦")
{
    $amount = explode(".", currency_format($amount, $comma, $symbol));
    return $amount[0];
}

function amount_format($amount, $comma = false, $decimal = 2)
{
    $amount = floatval($amount);
    return number_format($amount, $decimal, '.', $comma ? ',' : '');
}
