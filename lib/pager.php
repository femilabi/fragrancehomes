<?php
if (!@$pager_calculated) {
	$pager_totalpages = ceil($APP->get('pager_total_pages'));
	$pager_currentpage = $APP->get('pager_current_page');
	$url_indicator = 'page';
}
if (is_numeric($pager_totalpages) && $pager_totalpages > 1 && is_numeric($pager_currentpage) && $pager_currentpage > 0) :
	if (!@$pager_calculated) {
		$pager_url = get_current_url();
		$pager_preg = '/' . $url_indicator . '\=[0-9]+/';
		$pager_query = $url_indicator . '={page}';
		if (strpos($pager_url, $url_indicator . '=') !== false) {
			$pager_url = preg_replace($pager_preg, $pager_query, $pager_url);
		} else {
			if (strpos($pager_url, '?') === false) {
				$pager_url .= '?' . $pager_query;
			} else {
				$pager_url .= '&' . $pager_query;
			}
		}
		$pager_calculated = true;
	}
?>


	<ul class="pagination">
		<li class="<?php if ($pager_currentpage == 1) echo ('disabled'); ?> page-item" <?php if ($pager_currentpage == 1) echo (' style="pointer-events: none;"'); ?>><a class="btn btn-common" href="<?php if ($pager_currentpage > 1) echo (str_replace('{page}', ($pager_currentpage - 1), $pager_url));
																																																		else echo ('#'); ?> "><i class="lni-chevron-left"></i> Previous </a></li>
		<li class="page-item"><a class="page-link btn btn-white" href="#"><?= $pager_currentpage ?> / <?= $pager_totalpages ?></a></li>
		<li class="<?php if ($pager_currentpage >= $pager_totalpages) echo ('disabled'); ?> page-item" <?php if ($pager_currentpage >= $pager_totalpages) echo (' style="pointer-events: none;"'); ?>><a class="btn btn-common" href="<?php if ($pager_currentpage < $pager_totalpages) echo (str_replace('{page}', ($pager_currentpage + 1), $pager_url));
																																																										else echo ('#'); ?>">Next <i class="lni-chevron-right"></i></a></li>
	</ul>
<?php endif; ?>