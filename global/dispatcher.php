<?php error_reporting(E_ALL);
date_default_timezone_set('Africa/Lagos');
$start_time = microtime(true); $execution = time();

set_time_limit(0); ignore_user_abort();
$config_path = '/home/coinsvkm/trustfundglobal/dashboard/lib/';
//$config_path = dirname(__FILE__).'/lib/';
require_once($config_path.'config.php');
require_once(GLOBAL_PATH.'lib/fns.php');
require_once(LIB_PATH.'po.fns.php');
require_once(CLASS_PATH.'db.php');
require_once(CLASS_PATH.'dbrow.php');
require_once(CLASS_PATH.'user.php');

$DB = new DB();
$STATS = array();

perform_matches();
fail_timedouts();
automatic_reph();

$end_time = microtime(true);
$report = "
	Dispatcher executed on: <strong>".date('d/m/Y H:i', $execution)."</strong>,
	Number of GHs queried: <strong>".@$STATS['fetched_ghs']."</strong>,
	Number of GHs worked on: <strong>".@$STATS['worked_ghs']."</strong>,
	Number PHs queried: <strong>".@$STATS['fetched_phs']."</strong>,
	Number PHs crawled: <strong>".@$STATS['worked_phs']."</strong>,
	Number Orders created: <strong>".@$STATS['orders_created']."</strong>,
	======================================================================
	Number of Failed orders worked on: <strong>".@$STATS['failed_orders']."</strong>,
	Number of total blocked users: <strong>".@$STATS['blocked_users']."</strong>,
	======================================================================
	Number of non recommitted earnings found: <strong>".@$STATS['total_non_recommited_rewards']."</strong>,
	Number of PH automatically created: <strong>".@$STATS['total_ph_created']."</strong>,
	======================================================================
	Duration of Execution: <strong>".number_format($end_time - $start_time, 4)."</strong> sec
	
	+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	
";
echo nl2br($report);
exit;

//====================================================================================================================
function fail_timedouts(){
	global $DB, $STATS;
	$now = time();
	$blocked_users = 0;
	//Make timed out orders fail
	$failed_orders = $DB->get_query_set("SELECT id, ph_id FROM ".DB_PREFIX."orders WHERE status = 'pending' AND timeout_date < ".$now);
	$STATS['failed_orders'] = count($failed_orders);
	$DB->query("UPDATE ".DB_PREFIX."orders SET status = 'canceled' WHERE status = 'pending' AND timeout_date < ".$now);
	//work on the requests and users
	if(is_array($failed_orders)){
		foreach($failed_orders as $o){
			$ph = new DBRow('requests','id',$o['ph_id']);
			if($ph->exists()){
				$user = new User($ph->get('user_id'));
				if($user->exists()){
					$user->set('blocked',1);
					$user->set('blocked_memo',
						'Your account has been blocked because you have failed to fulfill your order to provide help.<br/>
						Order ID: '.get_display_id($o['id'], 'order').'<br/>
						On Request ID: '.get_display_id($ph->get_id(),'request').'<br/>
						<a href="'.BASE_DIR.'support/">Raise support ticket</a>'
					);
					if($user->save()){
						$blocked_users++;
					}
				}
				$ph->set('status','canceled');
				$ph->save();
			}
		}
	}
	$STATS['blocked_users'] = $blocked_users;
}
function perform_matches(){
	global $DB, $STATS;
	$is_weekend = 1?1:1;
	$week_number = intval(date('w', time()));
	$time_hrs = $week_number == 5? 96 : ($week_number == 6 || $week_number == 0? 72 : 48);
	$timeout = 60 * 60 * 24;//$time_hrs
	$orders_created = 0;
	//fetch available ghs
	$ghs = $DB->get_query_set("
		SELECT
			r.*,
			IFNULL(SUM(o.amount),0) AS matched_amount,
			u.referrer_id, u.mentor_id, u.total_ph, u.joindate
		FROM 
			".DB_PREFIX."requests AS r
		LEFT JOIN
			".DB_PREFIX."orders AS o 
		ON 
			o.gh_id = r.id AND o.status != 'canceled'
		LEFT JOIN
			".DB_PREFIX."users AS u 
		ON 
			u.id = r.user_id
		WHERE
			r.type = 'gh'
			AND (r.status = 'pending' OR r.status = 'processed')
			
		GROUP BY r.id
	");
	$STATS['fetched_ghs'] = count($ghs);
	$ghs = sanitize_reqtuests($ghs);
	$STATS['worked_ghs'] = count($ghs);
	
	//var_dump($DB->get_error());
	//var_dump($ghs);
	$phs;
	$phs_fetched = false;
	if(is_array($ghs)){
		foreach($ghs as $gh){
			//$matched_query = $DB->get_query_set("SELECT SUM(amount) AS total FROM ".DB_PREFIX."orders WHERE gh_id = ".$gh['id']." AND status != 'canceled'");
			//$matched_amount = @$matched_query['total']? $matched_query['total']: 0;
			$to_be_matched = $gh['remainder_amount'];
			$processed = $gh['status'] == 'processed';
			if($to_be_matched > 0){
				if(!$phs_fetched){
					$phs = $DB->get_query_set("
						SELECT
							r.*,
							IFNULL(SUM(o.amount),0) AS matched_amount,
							u.referrer_id, u.mentor_id, u.total_ph, u.joindate
						FROM 
							".DB_PREFIX."requests AS r
						LEFT JOIN
							".DB_PREFIX."orders AS o 
						ON 
							o.ph_id = r.id AND o.status != 'canceled'
						LEFT JOIN
							".DB_PREFIX."users AS u 
						ON 
							u.id = r.user_id
						WHERE 
							r.type = 'ph'
							AND (r.status = 'pending' OR r.status = 'processed')
						GROUP BY r.id
					");
					$STATS['fetched_phs'] = count($phs);
					$phs = sanitize_reqtuests($phs);
					$STATS['worked_phs'] = count($phs);
					$phs_fetched = true;
					//var_dump($DB->get_error());
					//var_dump($phs);
				}
				$phs = sort_for_gh($phs, $gh);
				if(is_array($phs)){
					for($i = 0; $i < count($phs); $i++){
						if($phs[$i]['remainder_amount'] && $to_be_matched && $phs[$i]['points'] > -1000){
							$order = new DBRow('orders','id');
							$matching_amount = $phs[$i]['remainder_amount'] > $to_be_matched? $to_be_matched : $phs[$i]['remainder_amount'];
							$now = time();
							$data = array(
								'created_date' => $now,
								'ph_id' => $phs[$i]['id'],
								'gh_id' => $gh['id'],
								'amount' => $matching_amount,
								'status' => 'pending',
								'timeout_date' => $now + $timeout
							);
							$order->set_data($data);
							if($order->save()){
								$orders_created++;
								//update gh
									if(!$processed){
										$DB->query("UPDATE ".DB_PREFIX."requests SET status = 'processed' WHERE id = ".$gh['id']." LIMIT 1");
										$processed = true;
									}
									$to_be_matched-=$matching_amount;
								//update ph
									if($phs[$i]['status'] != 'processed'){
										$DB->query("UPDATE ".DB_PREFIX."requests SET status = 'processed' WHERE id = ".$phs[$i]['id']." LIMIT 1");
										$processed = true;
									}
									$phs[$i]['status'] = 'processed';
									$phs[$i]['remainder_amount'] -= $matching_amount;
								//send notifications
									$ph_user = new User(@$phs[$i]['user_id']);
									if($ph_user->exists()){
										$replacements = $ph_user->get_data();
										@send_email_template(array($ph_user->get('email')=> $ph_user->get('fullname')), 'provide_help', $replacements);
									}
							}
						}
						if($to_be_matched <= 0) break;
					}
				}else break;
			}
		}
	}
	$STATS['orders_created'] = $orders_created;
}
function sort_for_gh($phs, $gh){
	if(is_array($phs) && count($phs)){
		$p = array();
		for($i = 0; $i < count($phs); $i++){
			if($phs[$i]['user_id'] == $gh['user_id']){
				$phs[$i]['points'] = -2000; // a value that will never be considered while matching
			}elseif((
				$phs[$i]['referrer_id'] == $gh['referrer_id'] ||
				$phs[$i]['referrer_id'] == $gh['user_id'] ||
				$phs[$i]['user_id'] == $gh['referrer_id'])){//captures no go areas
					$points = 0;
					//mentor relationship
					if($phs[$i]['mentor_id'] == $gh['mentor_id'] || $phs[$i]['mentor_id'] == $gh['user_id'] || $phs[$i]['user_id'] == $gh['mentor_id']){
						$points-=10;
					}
					$now = time();
					//amount
					if($phs[$i]['remainder_amount'] == $gh['remainder_amount']){
						$points+=10;
					}elseif($phs[$i]['remainder_amount'] < $gh['remainder_amount']){
						$points+=7;
					}elseif($phs[$i]['remainder_amount'] > $gh['remainder_amount']){
						$points+=2;
					}
					//duration
					if($now - $phs[$i]['created_date'] <= 60*60*24*5){
						$points+=0;
					}elseif($now - $phs[$i]['created_date'] <= 60*60*24*10){
						$points+=5;
					}else{//for greater than 10 days
						$points+=10;
					}
					//new memeber duration additional
					if(!$phs[$i]['total_ph']){
						if($now - $phs[$i]['created_date'] <= 60*60*24*5){
							$points+=2;
						}elseif($now - $phs[$i]['created_date'] <= 60*60*24*10){
							$points+=8;
						}else{//for greater than 10 days
							$points+=10;
						}
					}
					//recently matched or processed
					if($phs[$i]['status'] == 'processed'){
						$points+=10;
					}
					//loyality
					if($now - $phs[$i]['joindate'] > 60*60*24*100){
						$points-=4;
					}
					$phs[$i]['points'] = $points;
			}else{//the nogos
				$phs[$i]['points'] = -500; // a value that will never be considered while matching
			}
		}
		return array_order_by($phs, 'points', 'desc');
	}
}
function sanitize_reqtuests($arr){
	if(!is_array($arr)) return $arr;
	$r = array();
	for($i = 0; $i < count($arr); $i++){
		if((floatval($arr[$i]['amount']) - floatval($arr[$i]['matched_amount'])) > 0){
			$r[] = $arr[$i];
			$r[count($r) - 1]['remainder_amount'] = floatval($arr[$i]['amount']) - floatval($arr[$i]['matched_amount']);
		}
		
	}
	return $r;
}
function automatic_reph(){
	global $DB, $STATS;
	$suspected = $DB->get_query_set("
		SELECT r.*, u.blocked, rq.created_date AS request_date, rq.status AS request_status
		FROM 
			".DB_PREFIX."rewards AS r,
			".DB_PREFIX."users AS u,
			".DB_PREFIX."requests AS rq
		WHERE
			r.type_id = 1
			AND r.user_id = u.id
			AND r.recommitted = 0
			AND u.blocked = 0
			AND rq.id = r.request_id
			AND rq.status = 'confirmed'
			AND rq.type = 'ph'
	");
	$total_non_recommited_rewards = is_array($suspected)? count($suspected) : 0;
	$total_ph_created = 0;
	if(is_array($suspected)){
		foreach($suspected as $r){
			$ph_after = $DB->get_query_set("
				SELECT * 
				FROM 
					".DB_PREFIX."requests 
				WHERE 
					user_id = ".$r['user_id']." 
					AND id > ".$r['request_id']." 
					AND ((type = 'ph' AND (status = 'pending' OR status = 'processed' OR status = 'confirmed')) OR (type = 'gh' AND (status = 'pending' OR status = 'processed')))
			");
			if(!(is_array($ph_after) && count($ph_after)) && $r['request_date'] < (time() - 60*60*12)){
				$new_ph = new DBRow('requests','id');
				$ph_data = array(
					'created_date' => time(),
					'user_id' => $r['user_id'],
					'amount' => $r['amount'],
					'type' => 'ph',
					'status' => 'pending'
				);
				$new_ph->set_data($ph_data);
				if($new_ph->save()){
					$total_ph_created++;
				}
			}
		}
	}
	$STATS['total_non_recommited_rewards'] = $total_non_recommited_rewards;
	$STATS['total_ph_created'] = $total_ph_created;
}
?>