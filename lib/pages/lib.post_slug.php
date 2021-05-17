<?php $APP->setIsJSON(true);
 $slug = get_valid_table_slug(@$_GET['title'], array('post_categories','posts'));
 if($slug){
	 echo '{"status":"ok","slug":"'.$slug.'"}';
 }else{
	 echo '{"status":"error","error":"Invalid Title specified"}';
 }
 exit;
?>