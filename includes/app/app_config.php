<?php
//////////////////////////////////////////////////////////////////////////////////////
///// App config that loads default meta and html attributes values into the app /////
///// All metas and html attributes loaded here are writable by the lib scripts  /////
///// This file is not compulsorily included. Copyright MyCyber Media Solutions  /////
//////////////////////////////////////////////////////////////////////////////////////
$metas = array('description' => 'Your description',
				  'rights' => '',
				  'robots' => 'index, follow',
				  'googlebot' => 'index,follow',
				  'country' => 'EN',
				  'language' => 'en-US',
				  'generator' => 'Tumbi PHP',
				  'keywords' => 'keywords'		
		);
$html = array('xmlns' => 'http://www.w3.org/1999/xhtml',
				'xml:lang' => 'en-gb', 
				'dir' => 'ltr',
				'lang' => 'en-gb'
			);
foreach($metas as $name => $content){
	$this->setMeta($name, $content);
}
foreach($html as $name => $value){
	$this->setHTMLAttribute($name, $value);
}
?>