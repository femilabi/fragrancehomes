<?php

// A list of permitted file extensions
$allowed = array('png', 'jpg', 'gif','bmp');
$file_info = isset($_POST['file_info'])? $_POST['file_info'] : '';
$response = array();

if(isset($_FILES['upl']) && $_FILES['upl']['error'] == 0){

	$extension = pathinfo($_FILES['upl']['name'], PATHINFO_EXTENSION);

	if(!in_array(strtolower($extension), $allowed)){
		$response['status'] = 'error';
		$response['error'] = 'Invalid file type';
		_exit();
	}
	
	$image_path = '../../images/';
	if(!is_array($file_info)){
		$response['status'] = 'error';
		$response['error'] = 'No file upload information provided';
		_exit();
	}
	if(isset($file_info['category'])){
		switch($file_info['category']){
			case 'updates':
				break;
			case 'ad_banners':
				break;
			case 'gallery':
				break;
			case 'slideshow':
				break;
			case 'products':
				break;
			default:
				$response['status'] = 'error';
				$response['error'] = 'Invalid file category specified';
				_exit();
		}
	}
	$path = '../../images/'.$file_info['category'].'/';
	$filename = $_FILES['upl']['name'];
	$filepath = $path.$filename;
	if(!file_exists($path)){
		$response['status'] = 'error';
		$response['error'] = 'Invalid path specified';
		$response['path'] = $path;
		_exit();
	}
	$filepath = validate_path($filepath);
	if(move_uploaded_file($_FILES['upl']['tmp_name'], $filepath)){
		$response['status'] = 'success';
		$filepath = str_replace('../../','',$filepath);
		$response['filename'] = $filepath;
		_exit();
	}
}else{
		$response['status'] = 'error';
		$response['error'] = 'No file uploaded';
}
_exit();

function _exit(){
	global $response;
	die(json_encode($response));
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