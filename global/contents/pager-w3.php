<?php 
if(!@$pager_calculated){
	$pager_totalpages = ceil($APP->get('pager_total_pages'));
	$pager_currentpage = $APP->get('pager_current_page');
	$url_indicator = 'page';
}
	if(is_numeric($pager_totalpages) && $pager_totalpages > 1 && is_numeric($pager_currentpage) && $pager_currentpage > 0):
		if(!@$pager_calculated){
			$pager_url = get_current_url();
			$pager_preg = '/'.$url_indicator.'\=[0-9]+/';
			$pager_query = $url_indicator.'={page}';
			if(strpos($pager_url, $url_indicator.'=') !== false){
				$pager_url = preg_replace($pager_preg, $pager_query, $pager_url);
			}else{
				if(strpos($pager_url, '?') === false){
					$pager_url.= '?'.$pager_query;
				}else{
					$pager_url.= '&'.$pager_query;
				}
			}
			$pager_calculated = true;
		}
?>
<div class="w3-container" style="padding-right:0px">
    <ul class="w3-pagination w3-border w3-round w3-right">
      <li class="previous <?php if($pager_currentpage == 1) echo('disabled'); ?>"<?php if($pager_currentpage == 1) echo(' style="pointer-events: none;"'); ?>><a class="w3-border-right" href="<?php if($pager_currentpage > 1) echo(str_replace('{page}', ($pager_currentpage - 1), $pager_url)); else echo('#'); ?>">&#10094; Previous</a></li>
      <!--<li><?php echo("-- $pager_currentpage of $pager_totalpages pages --"); ?></li>-->
      <li class="next <?php if($pager_currentpage >= $pager_totalpages) echo('disabled'); ?>"<?php if($pager_currentpage >= $pager_totalpages) echo(' style="pointer-events: none;"'); ?>><a href="<?php if($pager_currentpage < $pager_totalpages) echo(str_replace('{page}', ($pager_currentpage + 1), $pager_url)); else echo('#'); ?>">Next &#10095;</a></li>
    </ul>
</div>
<?php endif; ?>
