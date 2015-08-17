<?php
function send_to_bank($Amount,$CallbackURL,$Email,$Mobile,$config,$subject,$lang)
{
	$show['massage'] = '' ;
	$client = new SoapClient('https://de.zarinpal.com/pg/services/WebGate/wsdl', array('encoding' => 'UTF-8')); 
	$result = $client->PaymentRequest(
						array(
								'MerchantID' 	=> $config['merchent_code'],
								'Amount' 	=> $Amount / 10,
								'Description' 	=> $subject,
								'Email' 	=> $Email,
								'Mobile' 	=> $Mobile,
								'CallbackURL' 	=> $CallbackURL
							)
	);
	
	$result1=$result->Status ;
	$resultgo=$result->Authority ;
	if($result1 == 100)
	{
		$show['Status'] = 1 ;
		$show['massage'] .= $lang['Successful']['send'] ;
		$show['Post_url'] ='https://www.zarinpal.com/pg/StartPay/'.$result->Authority.'/ZarinGate';
		$show['trans_id1'] = '' ;
		$show['trans_id2'] = $result->Authority ;
	} else {
		$show['Status'] = 0 ;
		$show['massage'] .= $lang['error'][$result1] ;
	}
    return $show;
}


function back_from_bank($Amount,$config,$transids,$lang)
{		

	if($_GET['Status'] == 'OK'){
		$client = new SoapClient('https://de.zarinpal.com/pg/services/WebGate/wsdl', array('encoding' => 'UTF-8')); 
		$result = $client->PaymentVerification(
							  	array(
										'MerchantID'	 => $config['merchent_code'] ,
										'Authority' 	 => $_GET['Authority'],
										'Amount'	 => $Amount / 10 
									)
		);
		$tracking_code = $result->RefID;
		$result=$result->Status ;

		if($result == 100){
			$show['Status'] = 1 ;
			$show['massage'] = $lang['Successful']['back'] ;
			$show['trans_id1'] = $tracking_code ;
			$show['trans_id2'] = $_GET['Authority']  ;
		} else {
			$show['Status'] = 0 ;
			$show['massage'] = $lang['error'][$result] ;
			$show['trans_id1'] = $tracking_code ;
			$show['trans_id2'] = $_GET['Authority']  ;
		}
	} else {
		$show['Status'] = 0 ;
		$show['massage'] = $lang['error']['not_back'] ;
		$show['trans_id1'] = $tracking_code ;
		$show['trans_id2'] = $_GET['Authority']  ;
	}
	return $show;
}
?>