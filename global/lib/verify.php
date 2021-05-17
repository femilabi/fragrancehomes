<?php
function isValidName($stext)
{
	$rename = "/^([[:alnum:]]+[\._]{0,1})+([[:alnum:]]+)$/i";
	return preg_match($rename, $stext);
}
function isValidSlug($stext)
{
	//$rename = "/^([[:alnum:]]+[_\-]{0,1})+([[:alnum:]]+)$/i";
	$rename = "/^[a-z0-9]+(?:[_\-][a-z0-9]+)*$/";
	return preg_match($rename, $stext);
}
function isValidEmail($stext)
{
	//$remail = "/^([[:alnum:]]+\.{0,1})*[[:alnum:]]+@([[:alnum:]]+\.{0,1})*([[:alnum:]]+\.[[:alnum:]]+)$/i";
	//return preg_match($remail, $stext);
	return filter_var($stext, FILTER_VALIDATE_EMAIL);
}
function isValidDate($stext)
{
	$redate = "/(0{0,1}[1-9]|[12][0-9]|3[01])[\/\-](0{0,1}[1-9]|1[0-2])[\/\-]((19|20)[[:digit:]]{2})/";
	return preg_match($redate, $stext);
}
function contains_url($text)
{
	$regex = "((https?|ftp)\:\/\/)?"; // SCHEME 
	$regex .= "([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?"; // User and Pass 
	$regex .= "([a-z0-9-.]*)\.([a-z]{2,10})"; // Host or IP 
	$regex .= "(\:[0-9]{2,5})?"; // Port
	$regex .= "(\/([a-zA-Z0-9+\$_-]\.?)+)*\/?"; // Path 
	$regex .= "(\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?"; // GET Query 
	$regex .= "(#[a-z_.-][a-z0-9+\$_.-]*)?"; // Anchor 

	return preg_match("/$regex/", $text);
}
function isUrl($text)
{
	$regex = "((https?|ftp)\:\/\/)?"; // SCHEME 
	$regex .= "([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?"; // User and Pass 
	$regex .= "([a-z0-9-.]*)\.([a-z]{2,10})"; // Host or IP 
	$regex .= "(\:[0-9]{2,5})?"; // Port 
	$regex .= "(\/([a-zA-Z0-9+\$_-]\.?)+)*\/?"; // Path 
	$regex .= "(\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?"; // GET Query 
	$regex .= "(#(\/?[a-z0-9\$_\.-]+)*\/?)?"; // Anchor 

	return preg_match("/^$regex$/", $text);
}
function remove_accent($str)
{
	$a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ');
	$b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o');
	return str_replace($a, $b, $str);
}

function post_slug($str)
{
	return trim(strtolower(preg_replace(
		array('/[^a-zA-Z0-9 -]+/', '/[ -]+/', '/^-|-$/'),
		array('-', '-', ''),
		remove_accent($str)
	)));
}
function get_valid_table_slug($str, $table, $prefered_suffix = '')
{
	$slug = post_slug($str);
	return verify_table_slug($slug, $table, $prefered_suffix);
}
function verify_table_slug($slug, $tables, $prefered_suffix = '')
{
	global $slug_reserved_words;
	if (!$slug || !$tables) return;
	if (is_array($slug_reserved_words) && in_array($slug, $slug_reserved_words)) return;
	$exists = 0;

	//check for existence
	if (is_array($tables)) {
		foreach ($tables as $table) {
			$t = new DBRow($table, 'id');
			$t->search('slug', $slug);
			if ($t->exists()) {
				$exists = 1;
				break;
			}
		}
	} else {
		$t = new DBRow($tables, 'id');
		$t->search('slug', $slug);
		if ($t->exists()) $exists = 1;
	}

	//recalculate or return
	if ($exists) {
		if ($prefered_suffix && !(strlen($slug) > strlen($prefered_suffix) && substr($slug, - (strlen($prefered_suffix) + 1)) == '-' . $prefered_suffix)) return verify_table_slug($slug . '-' . $prefered_suffix, $tables);
		if (strpos($slug, '-') === false) {
			$slug .= '-1';
		} else {
			$fragments = explode('-', $slug);
			if (is_numeric($fragments[count($fragments) - 1])) {
				$fragments[count($fragments) - 1] += 1;
				$slug = implode('-', $fragments);
				if ($fragments[count($fragments) - 1] > 99) $slug .= '-1';
			} else {
				$slug .= '-1';
			}
		}
		return verify_table_slug($slug, $tables);
	} else {
		return $slug;
	}
}
function isValidJSON($data = NULL)
{
	if (!empty($data)) {
		@json_decode($data);
		return (json_last_error() === JSON_ERROR_NONE);
	}
	return false;
}
