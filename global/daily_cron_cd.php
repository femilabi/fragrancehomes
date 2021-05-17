<?php
date_default_timezone_set('Africa/Lagos');
$start_time = microtime(true); $execution = time();
set_time_limit(0); ignore_user_abort();
$config_path = '/home/coinsvkm/public_html/dashboard/lib/';
//$config_path = dirname(__FILE__).'/lib/';
require_once($config_path.'config.php');
require_once(GLOBAL_PATH.'lib/fns.php');
require_once(LIB_PATH.'po.fns.php');
require_once(CLASS_PATH.'db.php');
require_once(CLASS_PATH.'dbrow.php');
require_once(CLASS_PATH.'user.php');

$DB = new DB();
$STATS = array();


//increase reward growth
//confirm eligible rewards
//cancel rewards for failed requests older than 7 days
update_rewards();
confirm_frozen();
decrease_rewards();
clear_failed_rewards();

$end_time = microtime(true);
$report = "
	Daily cron executed on: <strong>".date('d/m/Y H:i', $execution)."</strong>,
	Number of returned growing rewards: <strong>".@$STATS['returned_updating_rewards']."</strong>,
	Number of increased rewards: <strong>".@$STATS['updated_updating_rewards']."</strong>,
	======================================================================
	Number of returned deacresing rewards: <strong>".@$STATS['returned_dereased_rewards']."</strong>,
	Number of deacresed rewards: <strong>".@$STATS['updated_dereased_rewards']."</strong>,
	======================================================================
	Total frozen rewards: <strong>".@$STATS['returned_frozen_rewards']."</strong>,
	Number of confirmed from frozen rewards: <strong>".@$STATS['confirmed_frozen_rewards']."</strong>,
	======================================================================
	Number of cleared wallet due to old age: <strong>".@$STATS['cleared_rewards']."</strong>,
	Duration of Execution: <strong>".number_format($end_time - $start_time, 4)."</strong> sec
	
	+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	
";
echo nl2br($report);
exit;

function decrease_rewards(){
	global $DB, $STATS;
	$rewards = $DB->get_query_set("
		SELECT 
			r.*,
			rt.growth, rt.freeze_days
		FROM 
			".DB_PREFIX."rewards AS r, 
			".DB_PREFIX."reward_types AS rt
		WHERE
			r.type_id = rt.id
            AND r.type_id = 1
			AND rt.growth > 0
            AND r.current > r.amount
			AND (r.status = 'confirmed' OR r.status = 'frozen')
			AND r.grown_count >= rt.freeze_days
            AND r.recommitted = 0
            AND r.updated_date <= ".(time() - (60*60*24*3))."
		
	");
	$returned_rewards = count($rewards);
	$dereased_rewards = 0;
	if(is_array($rewards)){
		foreach($rewards as $r){
			$rw = new DBRow('rewards','id',$r['id']);
			if($rw->exists()){
				//calculate date
				//if(!(time() > ($rw->get('created_date') + (60*60*24*$r['freeze_days'])))){
					$rw->set('current', $rw->get('current') - (($r['growth']/$r['freeze_days']) * $rw->get('amount')) );
                    if($rw->get('current') < $rw->get('amount')) $rw->set('current', $rw->get('amount'));
					if($rw->save()){
						$dereased_rewards++;
					}
				//}
			}
		}
	}
	
	$STATS['returned_dereased_rewards'] = $returned_rewards;
	$STATS['updated_dereased_rewards'] = $dereased_rewards;
}
function update_rewards(){
	global $DB, $STATS;
	$rewards = $DB->get_query_set("
		SELECT 
			min(r.id) AS id, r.user_id, r.amount, r.request_id, r.type_id, r.created_date, r.release_date, r.status, r.updated_date, r.status_date, r.current, r.grown_count, r.recommitted,
			rt.growth, rt.freeze_days
		FROM 
			".DB_PREFIX."rewards AS r, 
			".DB_PREFIX."reward_types AS rt
		WHERE
			r.type_id = rt.id
			AND rt.growth > 0
			AND (r.status = 'pending' OR r.status = 'frozen')
			AND r.grown_count < rt.freeze_days
		GROUP BY r.user_id, r.type_id
	");
	$returned_rewards = count($rewards);
	$updated_rewards = 0;
	if(is_array($rewards)){
		foreach($rewards as $r){
			$rw = new DBRow('rewards','id',$r['id']);
			if($rw->exists()){
				//calculate date
				//if(!(time() > ($rw->get('created_date') + (60*60*24*$r['freeze_days'])))){
					$rw->set('current', $rw->get('current') + (($r['growth']/$r['freeze_days']) * $rw->get('amount')) );
					$rw->set('grown_count', $rw->get('grown_count') + 1);
                    $rw->set('updated_date', time());
					if($rw->save()){
						$updated_rewards++;
					}
				//}
			}
		}
	}
	
	$STATS['returned_updating_rewards'] = $returned_rewards;
	$STATS['updated_updating_rewards'] = $updated_rewards;
}
function confirm_frozen(){
	global $DB, $STATS;
	$t = get_day_range();
	$today = $t['start'];
	$rewards = $DB->get_query_set("
		SELECT r.*, rt.growth, rt.freeze_days
		FROM ".DB_PREFIX."rewards AS r, ".DB_PREFIX."reward_types AS rt
		WHERE
			r.type_id = rt.id
			AND r.status = 'frozen'
            AND r.type_id != 4
	");
	$rewards_fetched = count($rewards);
	$rewards_confirmed = 0;
	if(is_array($rewards)){
		foreach($rewards as $r){
			$rw = new DBRow('rewards','id',$r['id']);
			if($rw->exists()){
				$d = get_day_range($rw->get('created_date'));
				$then = $d['start'];
				if($today >= ($then + 60*60*24*$r['freeze_days']) && !($r['growth'] > 0 && $r['grown_count'] < $r['freeze_days'])){
					$rw->set('status','confirmed');
					if($rw->save()){
						$rewards_confirmed++;
					}
				}
			}
		}
	}
	$STATS['returned_frozen_rewards'] = $rewards_fetched;
	$STATS['confirmed_frozen_rewards'] = $rewards_confirmed;
}
function clear_failed_rewards(){
	global $DB, $STATS;
	$rewards = $DB->query("
		UPDATE
		".DB_PREFIX."rewards
		SET
			status = 'canceled'
		WHERE
            status = 'pending'
            AND created_date < ".(time() - (60*60*24*30))."
	");
    $STATS['cleared_rewards'] = $DB->affected_rows();
}
?>