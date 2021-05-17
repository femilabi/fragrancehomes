<?php
$APP->setTemplate("home");

$latest_listings = $DB->get_query_set("
    SELECT * 
    FROM fh_listings 
    WHERE status = 'available'
    ORDER BY created_date DESC LIMIT 0, 6
");
$APP->assign("latest_listings", $latest_listings);