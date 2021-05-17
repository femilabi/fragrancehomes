<?php
function make_button($text, $url, $type = 'default', $icon = '', $target = '', $attrs = null){
	if(!isset($text) || !isset($url)) return;
	$result = '<a href="'.$url.'" class="btn'.($type? ' btn-'.$type : '').'"'.($target? ' target="'.$target.'"': '');
	if(is_array($attrs)){
		foreach($attrs as $k => $v){
			$result.=' '.$k.'="'.$v.'"';
		}
	}
	$result.='>'.(isset($icon) && $icon? '<span class="fa fa-'.$icon.'" aria-hidden="true"></span> ' : '').$text.'</a>';
	return $result;
}
function make_submit($text, $type = 'default', $icon = '', $name = '', $value = '', $add_issubmit = '', $additional_buttons = ''){
	if(!$text) return;
	$result  = '';
	if($add_issubmit) $result.= '<input name="issubmit" type="hidden" value="1" />';
	$result.= '<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9 col-md-7">
		  <button type="submit" class="btn'.($type? ' btn-'.$type : '').'"'.($name? ' name="'.$name.'"': '').($value? ' value="'.$value.'"': '').'>'.($icon? '<span class="fa fa-'.$icon.'" aria-hidden="true"></span> ' : '').$text.'</button>';
	if(is_array($additional_buttons)){
		foreach($additional_buttons as $btn){
			if(is_array($btn)) $result.= ' '.make_button(@$btn['text'],@$btn['url'],@$btn['type'],@$btn['icon'],@$btn['target']);
		}
	}
	$result.= '</div>
	  </div>';
	return $result;
}
function make_editable($text, $object, $id, $key, $confirm = 0){
	$result = '<span class="editable" data-text="'.$text.'" data-id="'.$id.'" data-object="'.$object.'" data-key="'.$key.'"'.($confirm? ' data-confirm="1"': ''). '>';
	$result.=$text;
	$result.=' <img src="images/icons/edit.png">';
	$result.='</span>';
	return $result;
}
//To make form fields
function make_field($options){
	//assign defaults
	$label 	= isset($options['label'])? $options['label'] : '';	
	$name 	= isset($options['name'])? $options['name'] : '';
	$id 	= isset($options['id'])? $options['id'] : '';
	$type 	= isset($options['type'])? $options['type'] : 'text';
	$value 	= isset($options['value'])? $options['value'] : '';
	$required 	= isset($options['required'])? $options['required'] : '';
	$placeholder 	= isset($options['placeholder'])? $options['placeholder'] : '';
	$help 	= isset($options['help'])? $options['help'] : '';
	$content 	= isset($options['content'])? $options['content'] : '';
	$attrs_string = '';
	if(is_array(@$options['attrs'])){
		$attrs = array();
		foreach($options['attrs'] as $attr => $val){
			$attrs[] = $attr.'="'.$val.'"';
		}
		$attrs_string = implode(' ', $attrs);
	}
	$result='';
	$end='';
	$result.='<div class="form-group">';
	$end = '</div>'.$end;
	if(!($type == 'checkbox' || $type == 'radio')){
		$result.='<label'.($id? ' for="'.$id.'"' : '').' class="col-sm-3 control-label">'.$label.'</label>
			<div class="col-sm-9 col-md-7">';
		$end = '</div>'.$end;
	}else{
		$result.='<div class="col-sm-offset-3 col-sm-9 col-md-7">';
		$end = '</div>'.$end;
	}
	switch($type){
	case 'checkbox':
		$result.='<div class="checkbox">
			<input'.($name? ' name="'.$name.'"': '').($required? ' required': '').($id? ' id="'.$id.'"' : '').($help? ' aria-describedby="'.$id.'-help"' : '').' type="checkbox" '.$attrs_string.($value? ' value="'.$value.'"' : '').'> 
			<label'.($id? ' for="'.$id.'"' : '').'>
			  '. $label.'
			</label>
		  </div>';
		break;
	case 'radio':
		$result.='<div class="radio">
			<label>
			  <input'.($name? ' name="'.$name.'"': '').($required? ' required': '').($id? ' id="'.$id.'"' : '').($help? ' aria-describedby="'.$id.'-help"' : '').' type="radio"'.($value? ' value="'.$value.'" ' : ' ').$attrs_string.'> '. $label.'
			</label>
		  </div>';
		break;
	case 'select':
		$result.='<select class="form-control"'.($name? ' name="'.$name.'"': '').($id? ' id="'.$id.'"' : '').($help? ' aria-describedby="'.$id.'-help"' : '').' '.$attrs_string.'>'.$content.'</select>';
		break;
	case 'static':
		$result.='<p class="form-control-static" '.$attrs_string.'>'.$value.'</p>';
		break;
	case 'textarea':
		$result.='<textarea class="form-control"'.($name? ' name="'.$name.'"': '').($required? ' required': '').($id? ' id="'.$id.'"' : '').($help? ' aria-describedby="'.$id.'-help"' : '').' type="'.$type.'"'.($placeholder? ' placeholder="'.$placeholder.'"' : '').' '.$attrs_string.'>'.$value.'</textarea>';
		break;
	default:
		$result.='<input class="form-control"'.($name? ' name="'.$name.'"': '').($required? ' required': '').($id? ' id="'.$id.'"' : '').($help? ' aria-describedby="'.$id.'-help"' : '').' type="'.$type.'"'.($value? ' value="'.$value.'"' : '').($placeholder? ' placeholder="'.$placeholder.'"' : '').' '.$attrs_string.'>';
		break;
	}
	$result.=($help? '<span id="'.$id.'-help" class="help-block">'.$help.'</span>': '');
	return $result.$end;
}
function make_log($s_table, $s_id, $obj1_table, $obj1_id, $obj2_table, $obj2_id, $memo){
	$log = new DBRow('logs', 'id');
	$log->set('subject_table', $s_table);
	$log->set('subject_id', $s_id);
	$log->set('object1_table', $obj1_table);
	$log->set('object1_id', $obj1_id);
	$log->set('object2_table', $obj2_table);
	$log->set('object2_id', $obj2_id);
	$log->set('memo', $memo);
	
	$log->set('created_date', time());
	$log->set('ip', $_SERVER['HTTP_ADDR']);
	
	return $log->save();
}

function make_pager(){
	global $APP;
	include(GLOBAL_PATH.'contents/pager.php');
}
function print_thumbs($obj, $span = 3, $one_row_scrollable = false){
	if(!is_array($obj)) return;
	$count = intval(12/intval($span));
	$span = 12/$count;
	$result = '';
	for($i = 0, $j = 1; $i < count($obj); $i++, $j++){
		if($j == 1) $result .= '<div class="row">';
		
		$result .= make_thumb($obj[$i], $span);
		
		if($i == count($obj) - 1 || ($j == $count && !$one_row_scrollable)){
			$result .= '</div>';
			if(!$one_row_scrollable) $j = 0;
		}
	}
	echo($result);
}
function make_thumb($thumb, $span = 3){
	if(!is_array($thumb)) return;
	$img = isset($thumb['image'])? $thumb['image'] : '';
	$img_link = isset($thumb['image_link'])? $thumb['image_link'] : '';
	$img_link_target = isset($thumb['image_link_target'])? $thumb['image_link_target'] : '';
	$title = isset($thumb['title'])? $thumb['title'] : '';
	$text = isset($thumb['text'])? $thumb['text'] : '';
	$options = isset($thumb['options'])? $thumb['options'] : '';
	$result = '<div class="col-sm-6 col-md-'.$span.'">
    <div class="thumbnail">'.($img? ($img_link? '<a href="'.$img_link.'"'.($img_link_target? ' target="'.$img_link_target.'"': '').'><img src="'.$img.'" alt=""></a>': '<img src="'.$img.'" alt="">') : '');
	if($title || $text || is_array($options)){
		$result.='
		  <div class="caption">
			'.($title? '<h3>'.$title.'</h3>' :'').
			($text? '<p>'.$text.'</p>' : '');
		 if(is_array($options)){
			 $result.= '<p>';
			 for($i = 0; $i < count($options); $i++){
				if(!is_array($options[$i])) continue;
				$txt = isset($options[$i]['text'])? $options[$i]['text'] : '';
				$url = isset($options[$i]['url'])? $options[$i]['url'] : '';
				$type = isset($options[$i]['type'])? $options[$i]['type'] : '';
				$icon = isset($options[$i]['icon'])? $options[$i]['icon'] : '';
				$target = isset($options[$i]['target'])? $options[$i]['target'] : '';
				$result.= make_button($txt, $url, $type, $icon, $target);
				$result .= ' ';
			 }
			 if(!is_array($options[0])){
				$txt = isset($options['text'])? $options['text'] : '';
				$url = isset($options['url'])? $options['url'] : '';
				$type = isset($options['type'])? $options['type'] : '';
				$icon = isset($options['icon'])? $options['icon'] : '';
				$result.= make_button($txt, $url, $type, $icon);
			 }
			 $result.= '</p>';
		 }
		 $result.='</div>';
	}
	 $result.='</div>
  			</div>';
  return $result;
}
function get_currrent_ad(){
	global $APP;
	$ad = new DBRow('ads', 'id', $APP->getSettings('current_ad'));
	return $ad->get_data();
}
?>