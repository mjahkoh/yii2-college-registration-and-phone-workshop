<?php

namespace app\models;
use app\helpers\Setup;
use Yii;

/**
 * This is the model class for table "jobs".
 *
 * @property integer $id
 * @property integer $phone_model_id
 * @property string $problem
 * @property integer $client_id
 * @property double $charges
 * @property string $date_job_commenced
 * @property string $date_job_completed
 * @property integer $staff_allocated_id
 * @property integer $status
 */
class Jobs extends \yii\db\ActiveRecord 
{ 
	//['1' => 'In-complete', '2' => 'Complete', '3' => 'Unrepairable']

		public $newclient;
		public $name;
		public $tel;
		public $telprefix;
		public $totalpayments;
		public $balance;
		public $telephone;
		public $companyname;
		public $companyphysicallocation; 
		public $companymobileprefix; 
		public $companymobile ; 
		public $companytelprefix ; 
		public $companytel ; 
		public $sendsmsmessage;
		public $memberssmsid;

		const STATUS_IN_COMPLETE 	= 10;
		const STATUS_COMPLETE 		= 1;
		const STATUS_UN_REPAIRABLE  = 0;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'jobs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['phone_model_id', 'problem', 'currency', 'charges', 'phone_make_id', 'phone_model_id'], 'required'],//, 'client_id', 'tel', 'message'
		    [['phone_make_id', 'phone_model_id', 'client_id', 'staff_allocated_id', 'status', 'tel', 'currency'], 'integer'],
            [['charges', 'totalpayments', 'balance'], 'number'],
            [['date_job_commenced', 'date_job_completed', 'telprefix'], 'safe'],
            [['problem', 'name'], 'string', 'max' => 255],
			[['client_id', 'name', 'tel'], 'isPhoneValid', 'skipOnEmpty' => false, 'skipOnError' => false],
			//[['client_id', 'name', 'tel'], 'isPhoneValid'],
			/*
			['client_id', 'required', 'when' => function($model) {
				return $model->isNewRecord == true ? false : true;
			}],	
			[['name', 'tel'], 'required', 'when' => function($model) {
				return $model->isNewRecord == true ? true : false;
			}],	
			*/
			//[['tel'], 'isPhoneValid'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Job Card #'),
            'phone_make_id' => Yii::t('app', 'Phone Make'),
			'phone_model_id' => Yii::t('app', 'Phone Model'),
            'problem' => Yii::t('app', 'Problem'),
			'newclient' => Yii::t('app', 'New Client?'),
            'client_id' => Yii::t('app', 'Client'),
            'charges' => Yii::t('app', 'Charges'),
            'date_job_commenced' => Yii::t('app', 'Date Job Commenced'),
            'date_job_completed' => Yii::t('app', 'Date Job Completed'),
            'staff_allocated_id' => Yii::t('app', 'Staff Allocated'),
            'status' => Yii::t('app', 'Status'),
			'totalpayments' => Yii::t('app', 'Total Payments'),
			'balance' => Yii::t('app', 'Balance'),
			'tel' => Yii::t('app', 'Tel'),
			'currency' => Yii::t('app', 'Currency'),
			'telprefix' => Yii::t('app', 'Tel prefix'),
			'tel' => Yii::t('app', 'Tel'),
			'message' => Yii::t('app', 'Message'),
			'telephone'=>Yii::t('app', 'Telephone'),
        ];
    }

    /**
     * @inheritdoc
     * @return JobsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new JobsQuery(get_called_class());
    }
	
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhoneModels()
    {
        return $this->hasOne(PhoneModels::className(), ['id' => 'phone_model_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhoneMakes()
    {
        return $this->hasOne(PhoneMakes::className(), ['id' => 'phone_make_id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJobsMembers()
    {
        return $this->hasOne(JobsMembers::className(), ['id' => 'client_id']);
    }
	
	public function getMembersAllocated()
    {
        return $this->hasOne(JobsMembers::className(), ['id' => 'staff_allocated_id']);
    } 
	
    /* @inheritdoc
    public function beforeValidate()
    {
		if (parent::beforeValidate()){
			if ($this->isNewRecord) {
			}
			return true;
		}
		
		return true;	
	}
	 */


	public function isPhoneValid($attribute, $params)
	{
		//echo "isNewRecord";
		////print_r($this->isNewRecord);
		//echo "<br>";
		//echo "newclient";
		////print_r($this->newclient);echo "<br>";
		//echo $this->newclient == true  ? "sawa" : "bado" ;
		////print_r($_POST);
		//$newclient = $_POST['newclient']==true  ? true : false ;
		//echo "<br>";
		//echo "newclient: $newclient";
		//echo ($newclient == true)  ? "sawa" : "bado" ;
		//echo ($this->isNewRecord && $newclient == 1) ? "111sawa" : "22222bado" ;
		//echo "CLIENT ID: ". $this->client_id  ;
		//echo (!$this->isNewRecord && $this->client_id == '') ? "AAAAAAAAsawa" : "BBBBBBBbado" ;
		
		
		$newclient =  false;
		if ($this->isNewRecord && isset($_POST['newclient']) && $_POST['newclient'] == 1 && !$this->client_id ) {
			$newclient = true ;
		} 
		//echo isset($_POST['newclient']) == true  ? 'true' : 'false' ;//exit;
		if ($this->isNewRecord && $newclient) {
			if (strlen(trim($this->tel)) != 9) {
				$this->addError('tel', "The Tel should be 9 numeric characters");
				//return false;
			}
			if (trim($this->name)=='') {
				$this->addError('name', "The Clients name is blank. Kindly enter");
				//return true;
			}
			if (strlen(trim($this->telprefix)) != 3) {
				$this->addError('telprefix', "The Tel prefix should be 3 numeric characters");
				//return true;
			}
			if (trim($this->tel) && trim($this->telprefix)) {
			} else {
				$this->addError('tel', "The Tel and Prefix are required. Kindly enter");
				//return true;
			}
			//return false;
		} 
		
		if ($this->isNewRecord && $newclient == false && $this->client_id == '') {
			$this->addError($attribute, "The Clients name is blank. Kindly enter");
		}
	}
	
	public function beforeSave($insert)
	{
		if (parent::beforeSave($insert)) {
			// ...custom code here...'yyyy-MM-dd HH:mm:ss'
			if (!$this->date_job_commenced) {
				$this->date_job_commenced = date("Y/m/d"); ;
			}
			$this->date_job_commenced = Yii::$app->formatter->asDate($this->date_job_commenced,'yyyy-MM-dd');
			$this->date_job_completed = Yii::$app->formatter->asDate($this->date_job_completed,'yyyy-MM-dd');
			
			return true;
		} else {
			return false;
		}
		
		//we first save the clientelle if its a new guy
	}


	public function getCountrys()
    {
        return $this->hasOne(Countrys::className(), ['international_tel_code' => 'currency']);
    } 
	
	public function afterSave($insert, $changedAttributes )
	{
		parent::afterSave($insert, $changedAttributes );
		//ActiveRecord::refresh();
		//$this->updateAttributes(['message'=>$this->id]);
		//$this->save;
		$message = "The Job was successfully updated";
		if ($insert) {
			$message =  "The Job was successfully added";
		} 
		Yii::$app->session->setFlash('success', $message);
		if (isset($_POST['newclient'])) {
			//get the 
		}
		////print_r($_POST['newclient']);exit;
		//exit;
		//echo "tel:: ".$this->jobsMembers->tel_prefix.$this->jobsMembers->tel;
		////print_r($this->jobsMembers->tel_prefix);
		////print_r($this->jobsMembers->tel);
		////print_r($this->id);
		
		//$this->id
		//sends message 
		
		$to = $this->jobsMembers->tel_prefix.$this->jobsMembers->tel;
		$phonemodel = $this->phoneModels->model;
		//$phoneowner = $this->jobsMembers->name;
		$companydetails = Jobs::getCompanyDetails();
		if (count($companydetails)) {
			$companyname = $companydetails['companyname'];
			$physicallocation = $companydetails['physicallocation'];
			$telcontacts = $companydetails['telcontacts'];
			$phoneowner = $this->jobsMembers->name;
			$charges = number_format($this->charges,2);
			$message = "Job Ref: #: ". $this->id . PHP_EOL . " Client: $phoneowner - Phone - $phonemodel : Problem - " . $this->problem .
				". Charges - " .  $this->countrys->currency .
				" $charges - $companyname.  $physicallocation - Tel: $telcontacts";
			
			//send the messages here
			$param[] =  [
				'id' 		=> $this->id,
				'message' 	=> $message,
				'to'		=> $to,
			];
			Setup::sendSms($param);
			//exit;
		}	/**/

	}
	

	public static function getCompanyDetails( )
	{
		
		$sql = "select company_name, physical_location, mobile_prefix, mobile , tel_prefix , tel from companies limit 1";
		$check = Yii::$app->db->createCommand($sql)->queryOne();
		$mobileno = $telcontacts = NULL;
		$param = [];
		if ( $check['company_name'] ) {
			if (strlen($check['mobile_prefix']) && strlen($check['mobile']) )
			{
				$mobileno = $check['mobile_prefix'] . $check['mobile']  . ' / ';
				//console.log('mobileno is zero' );
			}
			$telprefix = $check['tel_prefix'] ;
			$tel = $check['tel'];
			if ($mobileno == NULL) {
				$telcontacts = $telprefix . $tel;
				//console.log('mobileno is null' );
				//console.log(telcontacts);
			} else {
				//console.log('mobileno isnt null' );
				$telcontacts = $mobileno . $telprefix . $tel;
			}
			$companyname = $check['company_name'];
			$physicallocation = $check['physical_location'];
			
			$param = [
				'companyname' => $companyname,
				'physicallocation' => $physicallocation,
				'telcontacts' => $telcontacts,
			];
			
		}	
		////print_r($param);
		return $param;
			
	}	
	

	public  function sendSmsToMultipleMembers($members, $smsmessage)
	{
		$sql = "select concat(tel_prefix,tel) as tel from jobs_members where id in ($members)";
		$models = Yii::$app->db->createCommand($sql)->queryAll();
		if (count($models)) {	
			foreach ($models as $index => $model) {
				$to = $model['tel'];
				$themessage[] = [
					'to' => $to, 
					'message' => $smsmessage,
				];
				$sql = "insert  into `sms_messages`(`to`,`message`) values ($to, '$smsmessage')";
				Yii::$app->db->createCommand($sql)->execute();
			}
			//send the messages
			Setup::sendSms($themessage, "Members");
			
		}
	}
}