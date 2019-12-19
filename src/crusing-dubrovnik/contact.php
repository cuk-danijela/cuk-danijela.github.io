<?php
	define("EMAIL", "info@krstarenjedubrovnikom.com");
	function recaptcha($_arr){
		$ch = curl_init($_arr['url']);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, urldecode(http_build_query($_arr['data'])));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 2);
		$rez = curl_exec($ch);
		return json_decode($rez, true);
	}
	$_arr = $_REQUEST;
	$_tmp = array(
		'response' => "error",
		'text' => _("Error")
	);
	
	$cerror = false;
	if(isset($_arr['g-recaptcha-response']) && $_arr['g-recaptcha-response']!=""){
		$_recaptcha = recaptcha(array(
			'url' => 'https://www.google.com/recaptcha/api/siteverify',
			'data'	=> array(
				'secret' => '6Lc7BR0UAAAAAMY6qEBVTUjOTfrGdPQnPJmUvJO3',
				'response' => $_arr['g-recaptcha-response']
			)
		));
		#print_r($_recaptcha);
		if(!isset($_recaptcha['success'])){
			$cerror = true;
		}else{
			if($_recaptcha['success']==1){
				$cerror = false;
			}else{
				$cerror = true;
			}
		}
		
	}else{
		$cerror = true;
	}
	if($cerror){
		$_tmp['text'] = _("Greška");
		exit(json_encode($_tmp));
	}
	$_mail = array(
		'subject' => _("Poruka sa kontakt forme - krstarenjedubrovnikom.com"),
		'fullname' => "$_arr[fullname]",
		'to' => EMAIL, 
		'message' => "
			<p style=\"margin:0;padding:0;line-height:12px;\">"._("Ime").": <strong> $_arr[name]</strong></p>
			<p style=\"margin:0;padding:0;line-height:12px;\">"._("Email").": <strong> $_arr[email]</strong></p>
			".(str_repeat("- ", 48))."
			<quote><i>$_arr[message]</i></quote>
			".(str_repeat("- ", 48))."
		"
	);
	#exit(main::$Mail->fetch($_mail));
	require_once("Mail.class.php");
	$Mail = new Mail();
	$e = $Mail->send($_mail);
	$_tmp = array(
		'response' => "error",
		'text' => _("Greška")
	);
	var_dump($e);
	if($e){
		$_tmp['response'] = "success";
		$_tmp['text'] = _("Uspešno ste poslali poruku.");
	}
	exit(json_encode($_tmp));
?>