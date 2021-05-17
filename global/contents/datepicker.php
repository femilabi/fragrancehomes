<?php if(!is_array($data)){
		//set date defaults
		$data = array();
		$time = time();
		$data['date_start'] = date('Y-m-d', $time);
		$data['date_start_hr'] = date('h', $time);
		$data['date_start_min'] = date('i', $time);
		$data['date_start_am_pm'] = date('a', $time);
		
		$tomorrow  = mktime(0, 0, 0, date("m")  , date("d")+1, date("Y"));
		$data['date_end'] = date('Y-m-d', $tomorrow);
		$data['date_end_hr'] = date('h', $time);
		$data['date_end_min'] = date('i', $time);
		$data['date_end_am_pm'] = date('a', $time);
		
		$data['set_end_date'] = 0;
}
?>
<div style="display: block;" class="well well-small graybg <?php if($APP->get('datepicker_enddate') == 1) echo('sendlateractions'); ?> hide">
<div class="row-fluid">
<div class="input-append date datepickerdiv" data-date-format="yyyy-mm-dd" data-date="<?php echo($APP->get('datepicker_enddate')? $data['date_end'] : $data['date_start']); ?>">
<input name="picker[date_<?php echo($APP->get('datepicker_enddate')? 'end' : 'start'); ?>]" placeholder="yyyy-mm-dd" class="datepickerdiv" value="<?php echo($APP->get('datepicker_enddate')? $data['date_end'] : $data['date_start']); ?>" id="MessageDispatchtime" required="" type="text"> <span class="add-on"><i class="icon-calendar"></i></span>
</div>
<div class="clearfix">&nbsp;</div>
<div class="">
<select name="picker[date_<?php echo($APP->get('datepicker_enddate')? 'end' : 'start'); ?>_hr]" class="span2" id="MessageDispatchHour">
	<?php for($i = 1; $i < 13; $i ++): ?>
    	<option value="<?php echo(sprintf('%02d',$i)); ?>"<?php if(intval($data['date_'.($APP->get('datepicker_enddate')? 'end' : 'start').'_hr']) == $i) echo('selected="selected"'); ?>><?php echo $i; ?></option>
    <?php endfor; ?>
</select>:
<select name="picker[date_<?php echo($APP->get('datepicker_enddate')? 'end' : 'start'); ?>_min]" class="span2" id="MessageDispatchMin">
	<?php for($j = 0; $j < 60; $j ++): ?>
    	<option value="<?php echo(sprintf('%02d',$j)); ?>"<?php if(intval($data['date_'.($APP->get('datepicker_enddate')? 'end' : 'start').'_min']) == $j) echo('selected="selected"'); ?>><?php echo(sprintf('%02d',$j)) ?></option>
    <?php endfor; ?>
</select>
<select name="picker[date_<?php echo($APP->get('datepicker_enddate')? 'end' : 'start'); ?>_am_pm]" class="span2" id="MessageDispatchMeridian">
    <option value="am"<?php if($data['date_'.($APP->get('datepicker_enddate')? 'end' : 'start').'_am_pm'] == 'am') echo('selected="selected"'); ?>>am</option>
    <option value="pm"<?php if($data['date_'.($APP->get('datepicker_enddate')? 'end' : 'start').'_am_pm'] == 'pm') echo('selected="selected"'); ?>>pm</option>
</select> </div>
</div>
</div>