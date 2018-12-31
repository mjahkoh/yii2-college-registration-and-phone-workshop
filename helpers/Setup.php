<?php  
namespace app\helpers;
use Yii;
use abhimanyu\sms\components\Sms;
use app\models\Jobs;

use app\helpers\PasswordGenerator;

class Setup {

    const DATE_FORMAT = 'php:Y-m-d';
    const DATETIME_FORMAT = 'php:Y-m-d H:i:s';
    const TIME_FORMAT = 'php:H:i:s';
   
    public static function convert($dateStr, $type='date', $format = null) {
        if ($type === 'datetime') {
              $fmt = ($format == null) ? self::DATETIME_FORMAT : $format;
        }
        elseif ($type === 'time') {
              $fmt = ($format == null) ? self::TIME_FORMAT : $format;
        }
        else {
              $fmt = ($format == null) ? self::DATE_FORMAT : $format;
        }
        return \Yii::$app->formatter->asDate($dateStr, $fmt);
    }
	 
	public static function convertTel($telStr = null) {
		return strlen($telStr)? "0".substr($telStr,0,3)."-".substr($telStr,3,10):NULL;
	}
	
	
	public static function executeQuery($query) {
		if (count($query)){
			$transaction = Yii::$app->db->beginTransaction();
			try {
				foreach ($query as &$value) {
					Yii::$app->db->createCommand($value)->execute();
					//echo "$value<br>";
				}
				$transaction->commit();
			} catch (Exception $e) {
				$transaction->rollBack();
			}			
		}
		////exit;	
	
	}
	
	/*
	sends sms messages
	receives an array with elements consisting of
	tel number of recipient
	message
	subject
	
	public static function sendSms ($params) {
		
		$check = Setup::getCompaniesEmailConfiguration();
		if (is_array($check)) {
				$messages = [];
				
				foreach ($params as $index => $messages) {
					$messages[] = [
						'number'   		=> $params['number'] ,
						'subject'  		=> $params['subject'] ,
						'body'  		=> $params['body'] ,
						'jobid'   		=> $params['jobid'] ,
						'phoneowner' 	=> $check['phoneowner'],

						'carrier' 		=> $check['carrier'] ,
						'host' 			=> $check['host'] , 
						'port' 			=> $check['port'] , 
						'username' 		=> $check['username'] , 
						'password' 		=> $check['password'] , 
						'encryption' 	=> $check['encryption'] , 
						'admin' 		=> $check['email'] , 
					];			
				}
				
				if (count($messages)) {
					Setup::sendTheSms($messages);
				}
				
			return true;
		}
		return false;
	}
	*/
	
	public static function getCompaniesEmailConfiguration () {
		
		$sql = "SELECT carriers.carrier,
		email, tel_prefix, tel, physical_location, 
		transport_options_host as host,
		transport_options_username as username,
		transport_options_password as password,
		transport_options_port as port,
		transport_options_encryption as encryption,
		IF (transport_type =1, 'smtp' , 'php') AS transporttype
		FROM carriers INNER JOIN companies ON carriers.id = companies.email_carrier_id limit 1";
		$check = Yii::$app->db->createCommand($sql)->queryOne();
		if (
			$check['carrier']  &&  
			$check['host']  &&
			$check['tel']  &&
			$check['tel_prefix']  &&
			$check['mobile']  &&
			$check['mobile_prefix']  &&
			$check['physical_location']  &&
			$check['username']  &&
			$check['password']  &&
			$check['port']  &&
			$check['encryption']  &&
			$check['transporttype'] &&
			$check['email']  
		) {
			return $check;
		} else {
			return NULL;
		}
		
	}
	
	/**
	 * Check Internet Connection.
	 *
	 * @author Pierre-Henry Soria <ph7software@gmail.com>
	 * @copyright (c) 2012-2013, Pierre-Henry Soria. All Rights Reserved.
	 * @param string $sCheckHost Default: www.google.com
	 * @return boolean
	 */
	public static function check_internet_connection($sCheckHost = 'www.google.com')
	{
		return (bool) @fsockopen($sCheckHost, 80, $iErrno, $sErrStr, 5);
	}

    public static function generateSerialKeys($noofcharacters)  { 
	
		$last = array();
		$alpha_chars = array();
		$error = null;
		for ($i = 0; $i < 20; $i++) {
			$alpha = PasswordGenerator::getAlphaNumericPassword($noofcharacters);
		   
			if (in_array($alpha, $last)) {
				$error = "Duplicate AlphaNumeric password.";
				//return $error;
			}
			
			if (preg_match("/^[a-zA-Z0-9]{".$noofcharacters."}$/", $alpha) !== 1) {
				$error = "AlphaNumeric regexp failed.";
				//return $error;
			}
	
			$last[] = $alpha;
	
			$alpha_chars = array_merge($alpha_chars, str_split($alpha));
		}
	
		$alpha_chars = array_unique($alpha_chars);
	
		if (count($alpha_chars) !== 62) {
			$error = "Not all AlphaNumeric chars are included.";
			//return error;
		}
	
		if (PasswordGenerator::getCustomPassword("abc", $noofcharacters) !== false) {
			$error = "Improper usage does not return false.";
			//return $error;
		}
	
		for ($i = 0; $i < 1000; $i++) {
			$ints = PasswordGenerator::getRandomInts($i);
			$count = count($ints);
			if ($count != $i) {
				$error = "$i random ints is $count and not $i";
				//return $error;
			}
		}
		
		if (strlen($error)) {
			$return = [
				'error'=>true,
				'message'=>$error,
			];
			return $return ;
		}
		
		return $last;
	
	}
	
	public static function getKeys($noofcharacters) {
		$return = Setup::generateSerialKeys($noofcharacters);
		if (!$return['error'] ) {
				$query = [];
				foreach ($return as $value) {
					$sql = "select code from codes where code=$value limit 1";
					$check = Yii::$app->db->createCommand($sql)->queryOne();
					if ($check['code'] ) {
					} else {
						$code = $check['code'];
						$query[] = "insert into codes (code) values ('$code')" ;
					}
				}
				if (count($query)) {
					executeQuery($query);
				}
				return true;
	
		}
		return $return;
	}

		//crockford32_encode
		public function encode($data) {
			$chars = '0123456789abcdefghjkmnpqrstvwxyz' ;
			$mask = 0b11111 ;
			$dataSize = strlen($data);
			$res = '';
			$remainder = 0 ;
			$remainderSize = 0;
			for ($i = 0; $i < $dataSize; $i++) {
				$b = ord($data[$i]);
				$remainder = ($remainder << 8) | $b;
				$remainderSize += 8;
				while($remainderSize > 4 ) {
					$remainderSize -= 5;
					$c = $remainder & ($mask << $remainderSize);
					$c >>= $remainderSize;
					$res .= $chars[$c];
				}
			}
			if ($remainderSize > 0 ) {
				$remainder <<= ( 5 - $remainderSize);
				$c = $remainder & $mask;
				$res .= $chars[$c];
			}
			return $res;
			
		}
		
		public static function decode($data) {
			$map = [
				'0' => 0 ,
				'O' => 0,
				'o' => 0 ,
				'1' => 1 ,
				'I' => 1 ,
				'i' => 1,
				'L' => 1 ,
				'l' => 1,
				'2' => 2 ,
				'3' => 3 ,
				'4' => 4 ,
				'5' => 5 ,
				'6' => 6 ,
				'7' => 7 ,
				'8' => 8 ,
				'9' => 9 ,
				'A' => 10 ,
				'a' => 10 ,
				'B' => 11 ,
				'b' => 11 ,
				'C' => 12 ,
				'c' => 12 ,
				'D' => 13 ,
				'd' => 13 ,
				'E' => 14 ,
				'e' => 14 ,
				'F' => 15 ,
				'f' => 15 ,
				'G' => 16 ,
				'g' => 16 ,
				'H' => 17 ,
				'h' => 17 ,
				'J' => 18 ,
				'j' => 18 ,
				'K' => 19 ,
				'k' => 19 ,
				'M' => 20 ,
				'm' => 20 ,
				'N' => 21 ,
				'n' => 21 ,
				'P' => 22 ,
				'p' => 22 ,
				'Q' => 23 ,
				'q' => 23 ,
				'R' => 24 ,
				'r' => 24 ,
				'S' => 25 ,
				's' => 25 ,
				'T' => 26 ,
				't' => 26 ,
				'V' => 27 ,
				'v' => 27 ,
				'W' => 28 ,
				'w' => 28 ,
				'X' => 29 ,
				'x' => 29 ,
				'Y' => 30 ,
				'y' => 30 ,
				'Z' => 31 ,
				'z' => 31 ,
			];
			$data = strtolower($data);
			$dataSize = strlen($data);
			$buf = 0;
			$bufSize = 0;
			$res = '';
			for ($i = 0; $i < $dataSize; $i++) {
				$c = $data[$i];
				if (!isset($map[$c])) {
					throw new \Exception ("Unsupported character $c (0x" .bin2hex($c). ") at position $i" );
				}
				$b = $map[$c];
				$buf = ($buf << 5) | $b;
				$bufSize += 5;
				if ($bufSize > 7 ) {
							$bufSize -= 8;
							$b = ($buf & ( 0xff << $bufSize)) >> $bufSize;
							$res .= chr($b);
				}
			}
			return $res;
		}

		 /**/
		 //connect to other database (my company offline server)
		public static function connectToOffLineCompanyServer() {
			$mysqli = new \mysqli(
				Yii::$app->params['db_host'],			//localhost
				Yii::$app->params['db_username'], 

				Yii::$app->params['db_password'], 
				Yii::$app->params['db_database'], 
				Yii::$app->params['db_port']			//25
				);
			if ($mysqli->connect_errno) {
				return false;
			} else {
				return $mysqli;
			}
		}

	public static function sendEmail ($param = []) {
	
		$host = Yii::$app->params['host'];
		$smtpport = Yii::$app->params['smtp_port'];
		$encryption = Yii::$app->params['encryption'];
		$username = Yii::$app->params['username'];
		$password = Yii::$app->params['password'];
		
		$subject = $param['subject'];
		$body = $param['body'];
		$toemail = Yii::$app->params['to_email_phonerepairs'];
		$fromemail = Yii::$app->params['from_email_phonerepairs'];
		
		$transport = \Swift_SmtpTransport::newInstance(
			$host, 
			$smtpport,
			$encryption)
			->setUsername($username)
			->setPassword($password);
		
		$mailer = \Swift_Mailer::newInstance($transport);
		$message = \Swift_Message::newInstance($subject)
		   ->setFrom([$fromemail => 'Admin'])
		   ->setSubject($subject)
		   ->setTo([$toemail => "Normal User"])
		   ->setBody($body, 'text/html');
		
		//if (!$mailer->send($message, $errors))
		if ($mailer->send($message)) {
			return true;
		}	
		return false;
	}
	
	/*
	checks wether the code is entered in the companies table
	*/
	
	public static function validateMyCompanySerialKey() {
		$connection = Setup::connectToOffLineCompanyServer();
		if (is_resource($connection)) {
			$sql= "select id, code from codes where code = '".$this->serial_no."' and company_name = '" . $this->company_name . "'";
			$result = mysqli_query($connection, $sql);
			if ($result != false) {
				$sql = "update companies set code_valid=1 ";
				Yii::$app->db->createCommand($sql)->execute();
			}
		}
		return true;
	}	
	
	public static function sendEmailToMyCompany () {
	
		$sql = "select company_name, facebook_handle, address, email, physical_location, mobile_prefix, mobile , tel_prefix , tel from companies limit 1";
		$rs = Yii::$app->db->createCommand($sql)->queryOne();
		if ($rs['company_name']) {
			$mobileno = NULL;
			if (isset($rs['mobile_prefix']) && isset($rs['mobile']) ) {
				$mobileno = $rs['mobile_prefix'] . $rs['mobile'] . " / " ;
			}
			
			$details= 
			"Company: " . $rs['company_name'] . "<br>" .
			"location: " . $rs['physical_location'] . "<br>" .
			"Tel: " . $rs['tel_prefix'] .  $rs['tel'] . "<br>" .
			"Address: " . $rs['address'] .  "<br>" .
			"Email: " . $rs['email'] . "<br>" .
			"facebook_handle: " . $rs['facebook_handle'] .  "<br>" ;

			$telcontacts = $mobileno . $rs['tel_prefix'] . $rs['tel'];
			$companyname = $rs['company_name'];
			$physicallocation = $rs['physical_location'];
			$subject = $rs['company_name'] .  '-' . $rs['tel_prefix'] .  $rs['tel'] . '-' .  $rs['physical_location'] . " - PhoneRepairs";
			$body = "<h1>" . $rs['company_name'] . "</h1>"."<br>" . $_SERVER['HTTP_USER_AGENT'] . "<br>". $details ;
			
			$param = [
				'subject' => $subject,
				'body' => $body,
				'fromemail' => Yii::$app->params['from_email_phonerepairs'],
				'toemail' => Yii::$app->params['to_email_phonerepairs'],
			];
			
			if (Setup::sendEmail($param)) {
				/*we flaG THE my_company_email_sent and company_name_sent
				 when we successfully send an email to mycompany.com
				*/
				$sql = "update companies set my_company_email_sent = 1 , company_name_sent = '$companyname'";
				Yii::$app->db->createCommand($sql)->execute();
				////print_r($sql);exit;
			}	
			
		}
	
		
	}
	
	/*
	checks to see wether email to mycompany is sent.
	if so returns true, else false
	*/
	public static function sentEmailToMyCompany () {
		$sql = "select id from companies where my_company_email_sent = 1 and company_name_sent = company_name  limit 1";
		$check = Yii::$app->db->createCommand($sql)->queryOne();
		if ( $check['id'] ) {
			return true;
		}
		return false;
	}
	
	public static function verifySerialKeys () {
	
		if (Setup::check_internet_connection()) {
			
			if (Setup::sentEmailToMyCompany() == false) {
				Setup::sendEmailToMyCompany();
			}
			
			Setup::validateMyCompanySerialKey();
		}
	}	

	public static function sendUnsentSms( )
	{
		
		//echo Yii::$app->db->dsn; 
		//echo Yii::getAlias('@sqlitedb');
		//exit;
		$sql = "SELECT jobs.id, phone_models.model,  countrys.currency, members.name, phone_models.model, jobs.problem,
		  countrys.currency, jobs.charges, members.tel, members.tel_prefix	 FROM countrys
		  INNER JOIN jobs ON countrys.international_tel_code = jobs.currency
		  INNER JOIN members ON jobs.client_id = members.id
		  INNER JOIN phone_models ON jobs.phone_model_id = phone_models.id
		  where  jobs.message_status = 0 ";
		$models = Yii::$app->db->createCommand($sql)->queryAll();
		if (count($models)){
			$param = [];
			$companydetails = Jobs::getCompanyDetails();
			if (count($companydetails)) {
				$companyname = $companydetails['companyname'];
				$physicallocation = $companydetails['physicallocation'];
				$telcontacts = $companydetails['telcontacts'];
				
				foreach ($models as $index => $model) {
					$phonemodel = $model['model'];
					//$telcontacts = $model['tel_prefix'].$model['tel'];
					$phoneowner = $model['name'];
					$charges = number_format($model['charges'],2);
					$message = "Job #: ". $model['id'] ." Client: $phoneowner - Phone - $phonemodel : Problem - " . $model['problem'] .". Charges - " 
					.  $model['currency'] . ' '	. $model['charges'] . " - $companyname.  $physicallocation - Tel: $telcontacts";
					
					$to = $model['tel_prefix'] . $model['tel'];	
					$message = $message;
					$id = $model['id'];
					$param[] = [
						'to'=>$to,
						'message'=>$message,
						'id'=>$id
					];
				}
				
				//now retrieve from the sms_messages table
				$sql = "SELECT * from sms_messages where  message_status = 0 ";
				$models = Yii::$app->db->createCommand($sql)->queryAll();
				if (count($models)){
					foreach ($models as $index => $model) {
						$param[] = [
							'to'=>$model['to'],
							'message'=>$model['message'],
							'id'=>$model['id'],
						];
					}
				}

				if (count($param)) {
					Setup::sendSms($param);
					////print_r($param);
					////print_r("<br>");
					//exit;
				}
				
			}
			
		}
		////print_r($charges);////////exit;
	
	}
	
	/*
	sends sms messages -
	parameters - 
		$messages as array with to(phone number) & message
	*/
	public static function sendSms($messages, $model = "Jobs" )
	{

		$smssettings = Setup::getSmsSettings();
		////print_r($smssettings);exit;
		//$url = "https://sms.movesms.co.ke/API/";
		if (count($smssettings)) {
			//$url = url('/')."/API/";
			$url = $smssettings['sms_host'];		 
			$postData = array(
				'action' => $smssettings['sms_action'],
				'username' => $smssettings['sms_username'],
				'api_key' => $smssettings['sms_api_key'],
				'sender' => $smssettings['sms_sender'],
				'msgtype' => $smssettings['sms_msgtype'],
				'dlr' => $smssettings['sms_dlr'],
				//'to' => $to,
				//'message' => $message,
			);
			////print_r($postData);
			foreach ($messages as $index => $params) {
				$postData['to'] = $params["to"];
				$postData['message'] = $params["message"] ;
				//$postData['id'] = $params["id"] ;
				/**/
						ob_start();  						//me
						$out = fopen('php://output', 'w');	//me
				
				$ch = curl_init();

					curl_setopt($ch, CURLOPT_VERBOSE, true);  //me
					//curl_setopt($ch, CURLOPT_STDERR, $out);  //me

				curl_setopt_array($ch, array(
					CURLOPT_URL => $url,
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_POST => true,
					CURLOPT_POSTFIELDS => $postData
	
				));
				curl_setopt($ch, CURLOPT_VERBOSE, 0);
				//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				$output = curl_exec($ch);
				
								fclose($out);  //me
								$debug = ob_get_clean();	 //me
					
								////print_r( 'error:' . $debug);
				if (curl_errno($ch)) {
					$output = curl_error($ch);
					 ////print_r( 'error:' . $output);
					 Yii::$app->session->setFlash('warning', "The sms message(s) were not sent");
				} else {
					if ($model == "Jobs") {
						$sql = "update `jobs` set `message_status` = 1 where `id` = ". $params['id'];
					} else {
						$sql = "update `sms_messages` set `message_status` = 1 where `id` = ". $params['id'];
					}
					Yii::$app->db->createCommand($sql)->execute();
					////print_r( 'success:' . $sql);
					Yii::$app->session->setFlash('success', "The sms message(s) was sent");
				}
				////print_r($postData);
			}
			curl_close($ch);
		}

	}

	public static function getSmsSettings()
	{
			////print_r($param);//print_r('dfdfd');
			//exit;
			$sql = "select id, sms_host, sms_username, sms_api_key, sms_sender, sms_msgtype, sms_dlr, sms_action
			 from sms_settings limit 1";
			$check = Yii::$app->db->createCommand($sql)->queryOne();
			$options =  [];
			foreach ($check as $key => $value) {
				//echo "Key: $key; Value: $value<br />\n";
				if (isset($value)) {
					$options[$key] = $value;
				}
			}
			return $options;
		
	}
	

}	