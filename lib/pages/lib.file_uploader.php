<?php 
// A list of permitted file extensions
$allowed = array('png', 'jpg', 'gif','bmp');
$categories = array('post_thumbs');
$file_info = isset($_POST['file_info'])? $_POST['file_info'] : '';
global $file_upl_response;
$file_upl_response = array();

if(isset($_FILES['upl']) && $_FILES['upl']['error'] == 0){
	$extension = pathinfo($_FILES['upl']['name'], PATHINFO_EXTENSION);
	$image_path = SELF_PATH.'images/';
	
	//validate file extension to prevent malicious uploads
	if(!in_array(strtolower($extension), $allowed)){
		$file_upl_response['status'] = 'error';
		$file_upl_response['error'] = 'Invalid file type';
		_exit();
	}
	
	//Check if file info is posted
	if(!is_array($file_info)){
		$file_upl_response['status'] = 'error';
		$file_upl_response['error'] = 'No file upload information provided';
		_exit();
	}
	
	//Validate file category
	if(!isset($file_info['category']) || !in_array($file_info['category'], $categories)){
		$file_upl_response['status'] = 'error';
		$file_upl_response['error'] = 'Invalid or no file category specified';
		_exit();
	}

	//Work on the categories if any logistics
	if(isset($file_info['category'])){
		switch($file_info['category']){
			case 'gallery':
				break;
			case 'slideshow':
				break;
		}
	}
	$path = $image_path.$file_info['category'].'/';
	$filename = $_FILES['upl']['name'];
	$filepath = $path.$filename;

	//Check directory if exists
	if(!file_exists($path)){
		mkdir($path);
	}
	
	//Save file
	$filepath = validate_path($filepath);
	if(move_uploaded_file($_FILES['upl']['tmp_name'], $filepath)){
		$file_upl_response['status'] = 'success';
		$filepath = str_replace(SYS_PATH,'',$filepath);
		$file_upl_response['filename'] = $filepath;
		_exit();
	}
}else{
	$file_upl_response['status'] = 'error';
	$file_upl_response['error'] = 'No file uploaded';
}
_exit();

function _exit(){
	global $file_upl_response;
	echo (json_encode($file_upl_response));
	exit;
}
function validate_path($path){
	$ext = pathinfo($path, PATHINFO_EXTENSION);
	$name = pathinfo($path, PATHINFO_FILENAME);
	$dir = pathinfo($path, PATHINFO_DIRNAME);
	if(file_exists($path)){
		if(strpos($name, '-') === false){
			$name .= '-1';
		}else{
			$fragment = explode('-', $name);
			if(is_numeric($fragment[count($fragment) - 1])){
				$fragment[count($fragment) - 1] += 1;
				$name = implode('-', $fragment);
				if($fragment[count($fragment) - 1] > 80) $name .= '-1';
			}else{
				$name .= '-1';
			}
		}
		$path = $dir.'/'.$name.'.'.$ext;
		return validate_path($path);
	}else{
		return $path;
	}
}
?>