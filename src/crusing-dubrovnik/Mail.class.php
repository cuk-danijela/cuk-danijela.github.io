<?php
	if(!defined("DATE_FORMAT")) { define("DATE_FORMAT", "d.m.Y"); }
	if(!defined("DATE_TIME_FORMAT")) { define("DATE_TIME_FORMAT", "d.m.Y H:i"); }
	if(!defined("DATE_TIME_L_FORMAT")) { define("DATE_TIME_L_FORMAT", "d.m.Y H:i:s"); }
	if(!defined("DATE_FORMAT_TO")) { define("DATE_FORMAT_TO", "d.m.Y 23:59:59"); }
	
	class Mail{
		
		///*
		public $host 					= "mail.krstarenjedubrovnikom.com";
		public $port 					= 26;
		public $username 			= "info@krstarenjedubrovnikom.com";
		public $password 			= "kinvorbud2017";
		public $from 					= "info@krstarenjedubrovnikom.com";
		public $name 					= "krstarenjedubrovnikom.com";
		public $reply_to 			= "info@krstarenjedubrovnikom.com";
		public function __construct($main = false){
		}
		public function fetch($_arr, $r = 0){
			$s = nl2br($_arr['message']);
			return $s;
		}
		public function send($_arr){
			#print_r($_arr); exit();
			#DB :: ppre($_arr);
			date_default_timezone_set("Europe/Belgrade");
			require_once "phpmailer/PHPMailerAutoload.php";
			#require_once "/home//public_html/common/phpmailer/PHPMailerAutoload.php";
			
			$mail = new PHPMailer;
			//Tell PHPMailer to use SMTP
			///*
			$mail->isSMTP();
			//Enable SMTP debugging
			// 0 = off (for production use)
			// 1 = client messages
			// 2 = client and server messages
			
			#$mail->SMTPDebug = 2;
			#$mail->Debugoutput = 'html';
			
			
			//Set the hostname of the mail server
			$mail->Host = $this->host;
			//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
			$mail->Port = $this->port;
			//Set the encryption system to use - ssl (deprecated) or tls
			$mail->SMTPSecure = 'tls';
			//Whether to use SMTP authentication
			$mail->SMTPAuth = true;
			//Username to use for SMTP authentication - use full email address for gmail
			$mail->Username = $this->username;
			//Password to use for SMTP authentication
			$mail->Password = $this->password;
			$mail->CharSet = 'UTF-8';
			//Set who the message is to be sent from
			$mail->setFrom($this->from, $this->name);
			//*/
			
			//Set an alternative reply-to address
			if(isset($_arr['reply_to'])){
				$this->reply_to = $_arr['reply_to'];
			}
			if(isset($_arr['reply_to_name'])){
				$this->name = $_arr['reply_to_name'];
			}
			
			/*
			$mail->isSendmail();
			//Set who the message is to be sent from
			$mail->setFrom($this->from, $this->name);
			*/
			//Set an alternative reply-to address
			$mail->addReplyTo($this->reply_to, $this->name);
			//Set who the message is to be sent to
			#$_arr['to'] = "marko.nesic.nr@gmail.com";
			#$_arr['to'] = "marko.nesic.nr@hotmail.com";
			#$_arr['to'] = "nesicm89@gmail.com";
			
			$_arr['fullname'] = (isset($_arr['fullname'])) ? $_arr['fullname'] : "";
			if(is_array($_arr['to'])){
				foreach($_arr['to'] as $to){
					$to = trim($to);
					$mail->addAddress($to);
				}
			}else{
				$_arr['to'] = trim($_arr['to']);
				$mail->addAddress($_arr['to'], $_arr['fullname']);
			}
			
			//Set the subject line
			$mail->Subject = $_arr['subject'];
			//Read an HTML message body from an external file, convert referenced images to embedded,
			//convert HTML into a basic plain-text alternative body
			$_arr['type'] = (isset($_arr['type'])) ? $_arr['type'] : "default";
			$body = $this->fetch($_arr);
			#exit($body);
			
			#$body = str_replace("http://localhost/".main::$config->domain, "http://www.".main::$config->domain, $body);
			
			$mail->msgHTML($body, dirname(__FILE__));
			//Replace the plain text body with one created manually
			#$mail->AltBody = 'This is a plain-text message body';
			//Attach an image file
			if(isset($_arr['attach'])){
				foreach($_arr['attach'] as $a){
					$mail->addAttachment($a);
				}
			}
			//send the message, check for errors
			#exit();
			
			if(isset($_arr['js']) && $_arr['js']=="js"){
				if(!$mail->send()){
					return array("response"=>"error");
				}else{
					return array("response"=>"success");
				}
			}
			if(!$mail->send()){
				#echo "Mailer Error: " . $mail->ErrorInfo;
				return false;
			} else {
				#echo "Message sent!";
				return true;
			}
		}
	}
?>