<?php error_reporting(E_ALL);
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
require_once(CLASS_PATH.'app.php');

$DB = new DB();
$APP = new App(false);
$STATS = array();

perform_matches();
fail_timedouts();
confirm_paids();
match_10();

$end_time = microtime(true);
$report = "
	Dispatcher executed on: <strong>".date('d/m/Y H:i', $execution)."</strong>,
	Number of GHs queried: <strong>".@$STATS['fetched_ghs']."</strong>,
	Number of GHs worked on: <strong>".@$STATS['worked_ghs']."</strong>,
	Number PHs queried: <strong>".@$STATS['fetched_phs']."</strong>,
	Number PHs crawled: <strong>".@$STATS['worked_phs']."</strong>,
	Number Orders created: <strong>".@$STATS['orders_created']."</strong>,
	=================================================================================================
	Number of Failed orders worked on: <strong>".@$STATS['failed_orders']."</strong>,
	Number of total blocked users: <strong>".@$STATS['blocked_users']."</strong>,
	Duration of Execution: <strong>".number_format($end_time - $start_time, 4)."</strong> sec,
	=================================================================================================
	Number of users blocked due to recommittment: <strong>".@$STATS['recommit_blocked']."</strong>
	=================================================================================================
	Number of queried orders to be confirmed: <strong>".@$STATS['orders_to_confirm']."</strong>,
	Number of confirmed orders: <strong class=\"text-success w3-text-green\">".@$STATS['confirmed_orders']."</strong>,
	Number of failed orders: <strong>".@$STATS['failed_queried_orders']."</strong>,
	Number of users blocked due to failed confirmations: <strong>".@$STATS['blocked_users_on_hash']."</strong>,
	=================================================================================================
	Number of request returned for match 10: <strong>".@$STATS['spam_ph_fetched']."</strong>,
	Number of orders created for match 10: <strong class=\"text-success w3-text-green\">".@$STATS['match10orders']."</strong>,
	
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
						'Your account has been blocked because you have failed to fulfill your order to provide donation.<br/>
						Order ID: '.get_display_id($o['id'], 'order').'<br/>
						On Request ID: '.get_display_id($ph->get_id(),'request').'<br/>
						<a href="'.BASE_DIR.'support/">Raise support ticket</a>'
					);
					if($user->save()){
						$blocked_users++;
					}
                    
                    //cancel all wallets associated with PD
                    $DB->query("
                        UPDATE ".DB_PREFIX."rewards
                        SET status = 'canceled'
                        WHERE request_id = ".$ph->get_id()."
                    ");
                    
                    //wipe last wallet growth
                    $last_wallet = $DB->get_query_set("
                        SELECT *
                        FROM
                            ".DB_PREFIX."rewards
                        WHERE 
                            user_id = ".$user->get_id()."
                            AND (status = 'frozen' OR status = 'confirmed')
                            AND type_id = 1
                        ORDER BY id DESC
                        LIMIT 1
                    ", true);
                    if(is_array($last_wallet) && @$last_wallet['id']){
                        $wallet = new DBRow('rewards','id', $last_wallet['id']);
                        if($wallet->exists()){
                            $wallet->set('grown_count', 20);
                            $wallet->set('current', $wallet->get('amount'));
                            $wallet->save();
                        }
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
	global $DB, $STATS, $APP;
	$btc_rate = get_btc_rate();
	$is_weekend = 1?1:1;
	$week_number = intval(date('w', time()));
	$time_hrs = $week_number == 5? 96 : ($week_number == 6 || $week_number == 0? 72 : 48);
	$timeout = 60 * 60 * $APP->getSettings('order_timeout');//$time_hrs;
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
			r.type = 'gh' AND r.spam_confirmed = 1
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
							r.type = 'ph' AND r.spam_confirmed = 1
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
								'currency_value' => $matching_amount * $btc_rate,
								'status' => 'pending',
								'timeout_date' => $now + $timeout
							);
							
							//use dummy names
							if($APP->getSettings('dummy_names')){
								$names = $DB->get_query_set("
									SELECT * FROM ".DB_PREFIX."dummy_names
									ORDER BY used_count ASC LIMIT 2
								");
								if(is_array($names) && count($names)){
									foreach($names as $name){
										$DB->query("
											UPDATE ".DB_PREFIX."dummy_names 
											SET used_count = ".($name['used_count'] + 1)." 
											WHERE id = ".$name['id']
										);
									}
									if(@$names[0]['name']) $data['p_fullname'] = $names[0]['name'];
									if(@$names[0]['email']) $data['p_email'] = $names[0]['email'];
									if(@$names[1]['name']) $data['r_fullname'] = $names[1]['name'];
									if(@$names[1]['email']) $data['r_email'] = $names[1]['email'];
								}
							}
							
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
								//send email to referrer
									$referrer = new User($ph_user->get('referrer_id'));
									if($referrer->exists()){
										$replacements = $referrer->get_data();
										$replacements['ph_fullname'] = $ph_user->get('fullname');
										@send_email_template(array($referrer->get('email')=> $referrer->get('fullname')), 'provide_help_referrer', $replacements);
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
			if(!($phs[$i]['user_id'] == $gh['user_id'] ||
				($phs[$i]['referrer_id'] && $phs[$i]['referrer_id'] == $gh['referrer_id']) ||
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
					$points += intval((time() - $phs[$i]['created_date'])/(60*60*24)) * 4; // * 4 for 4 points each
					/*if($now - $phs[$i]['created_date'] <= 60*60*24*5){
						$points+=0;
					}elseif($now - $phs[$i]['created_date'] <= 60*60*24*20){
						$points+=5;
					}else{//for greater than 10 days
						$points+=10;
					}*/
					//new memeber duration additional
					if(!$phs[$i]['total_ph']){
						$points += intval((time() - $phs[$i]['created_date'])/(60*60*24)); // divided by 24hrs meaning 1 point for each day
						/*if($now - $phs[$i]['created_date'] <= 60*60*24*5){
							$points+=2;
						}elseif($now - $phs[$i]['created_date'] <= 60*60*24*10){
							$points+=8;
						}else{//for greater than 10 days
							$points+=10;
						}*/
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
				$phs[$i]['points'] = -1000; // a value that will never be considered while matching
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
function block_recommitters(){
	global $DB, $STATS;
	$defaulters = $DB->query("
		UPDATE ".DB_PREFIX."users SET blocked = 1, recommit_pending = 0, blocked_memo = 'Your account has been blocked because you have failed to recommit' WHERE recommit_pending = 1 AND recommit_timeout < ".time()." AND blocked = 0
	");
	$STATS['recommit_blocked'] = $DB->affected_rows();
}
function confirm_paids(){
	global $DB, $STATS;
	$blocked_users = 0;
	$failed_orders = 0;
	$confirmed_orders = 0;
	$paids = $DB->get_query_set("
		SELECT o.*, u.btc_address
		FROM 
			".DB_PREFIX."orders AS o,
			".DB_PREFIX."requests AS r,
			".DB_PREFIX."users AS u
		WHERE 
			o.status = 'paid' AND o.pop != '' AND o.confirmations < 3
			AND r.id = o.gh_id
			AND u.id = r.user_id
	");
	$STATS['orders_to_confirm'] = count($paids);
	if(is_array($paids)){
		for($i = 0; $i < count($paids); $i ++){
			$o = $paids[$i];
			$success = false;
			$status_text = '';
			$api = file_get_contents('https://blockchain.info/rawtx/'.trim($o['pop']));
			$obj = json_decode($api, true);
			if(is_array($obj)){
				$outs = @$obj['out'];
				if(is_array($outs) && count($outs)){
					foreach($outs as $t){
						if(@$t['addr'] && $t['addr'] == trim($o['btc_address']) && @$t['value'] >= (intval($o['currency_value'] * pow(10,8)))){
							//verify if this transaction id has been confirmed with another order before
							$exists = $DB->get_query_set("SELECT id FROM ".DB_PREFIX."orders WHERE status = 'confirmed' AND pop = '".$o['pop']."'",true);
							if(!@$exists['id']){
								$success = true;
								break;
							}else{
								$status_text = 'Hash already confirmed on order: '.$exists['id'];
							}
						}else{
							if(!@$t['addr']){
								$status_text = ' No addr found ';
							}elseif(@$t['addr'] != trim($o['btc_address'])){
								$status_text = ' wrong wallet address ';
							}elseif(@$t['spent'] != true){
								$status_text =' spend not equals true ';
							}elseif(!(@$t['value'] >= (intval($o['currency_value'] * pow(10,8))))){
								$status_text =' Sent value is lower than the order value ';
							}
						}
					}
				}else{
					$status_text = 'Object "out" was not found';
				}
			}else $status_text = 'ARRAY NOT RETURNED. Response: '.$obj;
			
			$order = new DBRow('orders', 'id', $o['id']);
			if($success){
				$confirmed_orders++;
				$confirmed_date = time();
				$ph_request = new DBRow('requests', 'id', $o['ph_id']);
				$gh_request = new DBRow('requests', 'id', $o['gh_id']);
				$payer_time_diff =  $confirmed_date - $o['created_date'];
				$receiver_time_diff =  $confirmed_date - $o['paid_date'];
				
				$order->set('status', 'confirmed');
				$order->set('confirmed_date',$confirmed_date);
				if($order->save()){
					//spam_confirm ph
						if($ph_request->exists() && !$ph_request->get('spam_confirmed')){
								$ph_request->set('spam_confirmed',1);
								$ph_request->save();
						}
					//confirm PH request if this is the last payment on it else if status is canceled set it to processed
						if($ph_request->exists()){
							$current = $DB->get_query_set("SELECT SUM(amount) AS total FROM ".DB_PREFIX."orders WHERE ph_id = ".$ph_request->get('id')." AND status = 'confirmed'", true);
							if(@$current['total'] >= $ph_request->get('amount')){
								$ph_request->set('status','confirmed');
								$ph_request->save();
								
								//set recommitment
								$ph_user = new User($ph_request->get('user_id'));
								if($ph_user->exists() && $ph_request->get('amount') > $ph_user->get('current_recommit')){
									$ph_user->set('current_recommit', $ph_request->get('amount'));
									$ph_user->save();
								}
								
								//Confirm all rewards associated to the PH request
									if($ph_request->exists()){
										$rewards = $DB->get_query_set(
											"SELECT 
												r.id, t.freeze_days
											FROM 
												".DB_PREFIX."rewards AS r,
												".DB_PREFIX."reward_types AS t
											WHERE 
												r.request_id = ".$ph_request->get_id()." 
												AND r.status = 'pending'
												AND t.id = r.type_id"
										);
										if(is_array($rewards)){
											foreach($rewards as $r){
												$reward = new DBRow('rewards','id',$r['id']);
												if($reward->exists()){
													$status = ($confirmed_date - $reward->get('created_date')) >= (60*60*24*$r['freeze_days'])?(($reward->get('type_id') == 1 && !$reward->get('recommited'))?'frozen':'confirmed'):'frozen';
													$reward->set('status',$status);
													$reward->save();
												}
											}
										}
										//previous rewards shoed be set to recommited and then confirmed if already frozen
										$recommited_rewards = $DB->get_query_set(
											"SELECT 
												r.id, r.amount, t.freeze_days, rq.type
											FROM 
												".DB_PREFIX."rewards AS r,
												".DB_PREFIX."requests AS rq,
												".DB_PREFIX."reward_types AS t
											WHERE 
												r.request_id = rq.id
												AND r.request_id < ".$ph_request->get_id()."
												AND r.user_id = ".$ph_request->get('user_id')."
												AND rq.type = 'ph'
												AND (r.status = 'pending' OR r.status = 'frozen' OR r.status = 'confirmed')
												AND t.id = r.type_id
												AND r.type_id = 1
												AND r.recommitted = 0
											ORDER BY
												id ASC
												"
										);
										if(is_array($recommited_rewards)){
											$remaining_amount = $ph_request->get('amount');
											foreach($recommited_rewards as $r){
												if($r['amount'] <= $remaining_amount){
													$reward = new DBRow('rewards','id',$r['id']);
													if($reward->exists()){
														$reward->set('recommitted',1);
														$remaining_amount -= $r['amount'];
														$reward->save();
														if($remaining_amount < 1) break;
													}
												}
											}
										}
									}
							}elseif($ph_request->get('status') == 'canceled' || $ph_request->get('status') == 'failed'){
								$ph_request->set('status','processed');
								$ph_request->save();
							}
						}
					//confirm GH request if this is the last payment on it
						if($gh_request->exists()){
							$current = $DB->get_query_set("SELECT SUM(amount) AS total FROM ".DB_PREFIX."orders WHERE gh_id = ".$gh_request->get('id')." AND status = 'confirmed'", true);
							if(@$current['total'] >= $gh_request->get('amount')){
								$gh_request->set('status','confirmed');
								$gh_request->save();
							}
						}
					//Create speed bonuses if applicable
					/*	//For PHer
						if($ph_request->exists()){
							if($payer_time_diff <= 60*60*24){
								$p_speed_bonus = new DBRow('rewards','id');
								$p_data = array(
										'user_id' => $ph_request->get('user_id'),
										'amount' => $o['amount'] * 0.025,
										'current' => $o['amount'] * 0.025,
										'request_id' => $ph_request->get_id(),
										'type_id' => 6, //speed bonus for ph
										'created_date' => time(),
										'status' => 'frozen'
								);
								$p_speed_bonus->set_data($p_data);
								$p_speed_bonus->save();
							}
						}
						//For GHer
						if($gh_request->exists()){
							if($receiver_time_diff <= 60*60*1){
								$g_speed_bonus = new DBRow('rewards','id');
								$g_data = array(
										'user_id' => $gh_request->get('user_id'),
										'amount' => $o['amount'] * 0.015,
										'current' => $o['amount'] * 0.015,
										'request_id' => $gh_request->get_id(),
										'type_id' => 7, //speed bonus for ph
										'created_date' => time(),
										'status' => 'frozen'
								);
								$g_speed_bonus->set_data($g_data);
								$g_speed_bonus->save();
							}
						}*/				
					//Add total PH and total GH to users and adjust recommitment values
						$pher = new User($ph_request->get('user_id'));
						if($pher->exists()){
							$pher->set('total_ph', $pher->get('total_ph') + $o['amount']);
							$pher->save();
						}
						$gher = new User($gh_request->get('user_id'));
						if($gher->exists()){
							$gher->set('total_gh', $gher->get('total_gh') + $o['amount']);
							$gher->save();
						}
					//round up
				}
			}else{
				$failed_orders++;
				if($order->exists()){
					if($o['confirmations'] == 2 ){ //order has failed
						$order->set('status_text', $status_text);
						$order->set('status', 'failed');
						$order->set('confirmations', 3);
						$order->set('failed_date', time());
						$ph = new DBRow('requests','id',$o['ph_id']);
						if($ph->exists()){
							$user = new User($ph->get('user_id'));
							if($user->exists()){
								if(!$user->get('blocked')) $user->set('blocked_memo',
									'Your account has been blocked because you have attached a wrong transaction ID to your provide donation order.<br/>
									Order ID: '.get_display_id($o['id'], 'order').'<br/>
									On Request ID: '.get_display_id($ph->get_id(),'request').'<br/>
									<a href="'.BASE_DIR.'support/">Raise support ticket</a>'
								);
								$user->set('blocked',1);
								if($user->save()){
									$blocked_users++;
								}
							}
							$ph->set('status','failed');
							$ph->save();
						}
					}else{
						$order->set('status_text', $status_text);
						$order->set('confirmations', $o['confirmations'] + 1);
					}
					$order->save();
				}
			}
		}
	}
	$STATS['blocked_users_on_hash'] = $blocked_users;
	$STATS['confirmed_orders'] = $confirmed_orders;
	$STATS['failed_queried_orders'] = $failed_orders;
}
function match_10(){
	global $DB, $STATS, $APP;
	$btc_rate = get_btc_rate();
	$phs = $DB->get_query_set("
		SELECT id FROM ".DB_PREFIX."requests WHERE type = 'ph' AND status = 'pending' AND spam_confirmed = 0 AND spam_confirm_due_date < ".time()."
	LIMIT 150");
	$STATS['spam_ph_fetched'] = count($phs);
	$match10orders = 0;
	if(is_array($phs) && count($phs)){
		$eligible_users = $DB->get_query_set("SELECT id FROM ".DB_PREFIX."users WHERE matched_10_count > 0 ORDER BY matched_10_count ASC LIMIT ".count($phs));
		for($i = 0; $i < count($phs); $i++){
			$ph = new DBROw('requests','id',$phs[$i]['id']);
			//timeout
			$week_number = intval(date('w', time()));
			$time_hrs = $week_number == 5? 96 : ($week_number == 6 || $week_number == 0? 72 : 48);
			$timeout = 60 * 60 * $APP->getSettings('order_timeout');//$time_hrs;
			//create the gh request
			$eligible_user = $eligible_users[$i];
			$eligible_user_id = @$eligible_user['id'];
			if($eligible_user_id){
				$gh = new DBRow('requests','id');
				$gh->set_data(array(
					'user_id' => $eligible_user_id,
					'amount' => 0,
					'type' => 'gh',
					'created_date' => time(),
					'status' => 'processed'
				));
				$gh->save();
				$order = new DBRow('orders','id');
				$matching_amount = intval($ph->get('amount') * 0.1);//10%
				if($matching_amount%5) $matching_amount = $matching_amount + (5 - ($matching_amount%5));
				if($matching_amount < 10) $matching_amount = 10;
				if($matching_amount > $ph->get('amount')) $matching_amount = $ph->get('amount');
				$now = time();
				$data = array(
					'created_date' => $now,
					'ph_id' => $ph->get_id(),
					'gh_id' => $gh->get_id(),
					'amount' => $matching_amount,
					'currency_value' => $matching_amount * $btc_rate,
					'status' => 'pending',
					'timeout_date' => $now + $timeout
				);
				$order->set_data($data);
				if($order->save()){
					//increase matched_10_count
					$match10orders++;
					$gh_user = new User($eligible_user_id);
					if($gh_user->exists()){
						$gh_user->set('matched_10_count', $gh_user->get('matched_10_count') + 1);
						$gh_user->save();
					}
					$matched = true;
					//update ph
						if($ph->get('status') != 'processed'){
							$DB->query("UPDATE ".DB_PREFIX."requests SET status = 'processed' WHERE id = ".$ph->get_id()." LIMIT 1");
							$processed = true;
						}
					//send notifications
						$ph_user = new User($ph->get('user_id'));
						if($ph_user->exists()){
							$replacements = $ph_user->get_data();
							@send_email_template(array($ph_user->get('email')=> $ph_user->get('fullname')), 'provide_help', $replacements);
						}
				}
			}
		}
	}
	$STATS['match10orders'] = $match10orders;
	//match first 10%
		//--matching 10% ends
}
?>