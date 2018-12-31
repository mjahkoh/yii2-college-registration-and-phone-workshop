<?php

namespace app\models;
use yii \behaviors \TimestampBehavior ;
use yii\db\ActiveRecord;
use app\rbac\helpers\RbacHelper;
use kartik\password\StrengthValidator;
use Yii;
use kartik\tree\models\TreeTrait;
/**
 * This is the model class for table "students".
 *
 * @property integer $id
 * @property integer $sex
 * @property integer $marital_status
 * @property integer $mobile
  * @property integer $mobile_prefix
 * @property string $username
 * @property string $email
 * @property integer $state
  * @property integer $county
 * @property integer $city
 * @property string $nickname
 * @property string $pets_name
 //* @property integer $favourite_companion
 * @property string $password_hash
 * @property string $auth_key
 * @property integer $home_phone
 * @property integer $playgames
 */
/*class Members extends \yii\db\ActiveRecord //previous*/
class Members extends UserIdentity	//step 1 of registration
{

		const STATUS_INACTIVE = 0;
		const STATUS_ACTIVE = 1;
        public $statusList = [
            self::STATUS_ACTIVE     => 'Active',
            self::STATUS_INACTIVE 	=> 'In Active',
        ];

		const MARRIED 		= 1;
		const SINGLE 		= 2;
		const DIVORCED 		= 3;
		const WIDOWED 		= 4;
        public $maritalstatus = [
            self::MARRIED     		=> 'Married',
            self::SINGLE 			=> 'Single',
			self::DIVORCED 			=> 'Divorced',
			self::WIDOWED 			=> 'Widowed',
        ];

		public $permissions;

		const SCENARIO_STUDENTS = 'students';  
		const SCENARIO_PERSONNEL = 'personnel';
		const SCENARIO_UPDATE = 'update';
		
		const COACH 			= 1;
		const STUDENT 			= 2;
		const SUPPORT_STAFF 	= 3;
		const TEACHING_STAFF 	= 4;
        public $personnelCategorys = [
            self::COACH     		=> 'Coach',
            self::STUDENT 			=> 'Students',
			self::SUPPORT_STAFF 	=> 'Support Staff',
			self::TEACHING_STAFF 	=> 'Teaching staff',
        ];

    /**
     * We made this property so we do not pull hashed password from db when updating
     * @var string
     */
		public $repeatpassword;
		public $password;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    { 
        return 'members';
    }

	public function scenarios() { 
		$scenarios = parent::scenarios(); 
		$cols = array('firstname', 'middlename', 'surname', 'nickname', 'sex', 'mobile', 'mobile_prefix', 'home_phone_prefix', 'home_phone', 'state', 'county' ,'city', 'marital_status', 'username', 'password' , 'category', 'date_of_birth', 'email'); 
		
		/*the STUDENTS & PERSONNEL scenarios are the same in almost all aspects*/
		$scenarios[self::SCENARIO_STUDENTS] = $scenarios[self::SCENARIO_PERSONNEL] = $cols ; 
		
		// remove password from the array since password cannot be updated here (update scenario)
		if ($key = array_search('password', $cols)) {
			unset($cols[$key]);
		}
		$scenarios[self::SCENARIO_UPDATE] = $cols ; 
		return $scenarios;
	}


    /**
     * @inheritdoc
     */
    public function rules()
    {
		return [
			[['firstname', 'middlename', 'surname', 'nickname', 'sex', 'mobile', 'mobile_prefix', 'home_phone_prefix', 'home_phone', 'state', 'county', 'city', 'marital_status', 'username', 'password', 'category', 'date_of_birth', 'email'] , 'required', 'on' => [self::SCENARIO_STUDENTS, self::SCENARIO_PERSONNEL]],
			[['firstname', 'middlename', 'surname', 'nickname', 'sex', 'mobile', 'mobile_prefix', 'home_phone_prefix', 'home_phone', 'state', 'county', 'city', 'marital_status', 'username', 'category', 'date_of_birth', 'email'] , 'required', 'on' => self::SCENARIO_UPDATE],
            [['sex', 'marital_status', 'mobile', 'mobile_prefix', 'state', 'city', 'home_phone', 'home_phone_prefix', 'playgames', 'category', 'county'], 'integer'],
            [['username', 'email', 'password'], 'string', 'max' => 255],
            [['nickname', 'pets_name', 'firstname', 'middlename', 'surname'], 'string', 'max' => 30],
            [['password_repeat'], 'safe'],
            $this->passwordStrengthRule(),
			['email', 'email'],
            [['auth_key'], 'string', 'max' => 50],
			[['mobile_prefix', 'home_phone_prefix'], 'integer', 'max' => 999],
			['repeatpassword', 'compare', 'compareAttribute'=>'password', 'skipOnEmpty' => false, 'message'=>"Passwords don't match"],
			/*age must be 18 years and above*/
			//['date_of_birth', 'compare', 'compareValue' => 18, 'operator' => '>=', 'type' => 'number'],
			['date_of_birth', 'checkOver18'],
			['username', 'unique', 'targetClass' => 'app\models\Members', 'message' => 'This username is already taken'],
            ['email', 'email'],
			//compare wether personal mobile and home are equal. they should not
			 ['home_phone', 'compare', 'compareAttribute' => 'mobile', 'operator' => '!=',
				 'message'=>"Your Personal no. & Home Phone cannot be the same" ],
			 [['fullName'], 'safe'],
			 [['mobile'], 'isPhoneValid'],
		];
    }

    public function checkOver18($attribute, $params)
    {
		$today = date('Y-m-d') ;
		$selectedDateOfBirth = $this->date_of_birth;	//$selectedDateOfBirth = date('1976-06-09');
		
		$years = date_diff(date_create(), date_create($selectedDateOfBirth));
		
		//check wether its greater than today or less than 18 years
		if ($years->format("%y") < 18 || $selectedDateOfBirth > $today ) {
			$this->addError($attribute, 'Date of Birth is invalid. Must be 18 years old or less than today');
			//echo "Date of birth cant be **";
		}
	}

	public function isPhoneValid($attribute, $params)
	{
		if ($this->mobile && (strlen(trim($this->mobile)) < 9 || strlen(trim($this->mobile)) > 12)) {
			$this->addError($attribute, "The mobile should be between 9-12 numeric characters");
		}
		if ($this->home_phone && (strlen(trim($this->home_phone)) < 9 || strlen(trim($this->home_phone)) > 12)) {
			$this->addError('home_phone', "The home phone should be around 9-12 numeric characters");
		}
	}
	

    /**
     * Set password rule based on our setting value ( Force Strong Password ).
     *
     * @return array Password strength rule
     */
    private function passwordStrengthRule()
    {
        // get setting value for 'Force Strong Password'
        $fsp = Yii::$app->params['fsp'];

        // password strength rule is determined by StrengthValidator 
        // presets are located in: vendor/kartik-v/yii2-password/presets.php
        $strong = [['password'], StrengthValidator::className(), 'preset'=>'normal'];

        // use normal yii rule
        $normal = ['password', 'string', 'min' => 6];

        // if 'Force Strong Password' is set to 'true' use $strong rule, else use $normal rule
        return ($fsp) ? $strong : $normal;
    }    


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'sex' => Yii::t('app', 'Sex'),
            'marital_status' => Yii::t('app', 'Status'),
            'mobile' => Yii::t('app', 'Mobile Phone'),
			'mobile_prefix' => Yii::t('app', 'Mobile phone prefix'),
            'username' => Yii::t('app', 'Username'),
			'firstname' => Yii::t('app', 'First name'),
			'middlename' => Yii::t('app', 'Middle name'),
			'surname' => Yii::t('app', 'Surname'),
            'email' => Yii::t('app', 'Email'),
            'state' => Yii::t('app', 'State'),
            'county' => Yii::t('app', 'County'),
			'city' => Yii::t('app', 'City'),
            'nickname' => Yii::t('app', 'Nickname'),
            'pets_name' => Yii::t('app', 'Pets Name'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'auth_key' => Yii::t('app', 'Auth Key'),
			'repeatpassword' => Yii::t('app', 'Repeat Password'),
            'home_phone' => Yii::t('app', 'Home Phone'),
			'home_phone_prefix' => Yii::t('app', 'Home Phone Prefix'),
            'playgames' => Yii::t('app', 'Play games?'),
			'fullName'=>Yii::t('app', 'Full Name'),
			'category'=>Yii::t('app', 'Category'),
			'date_of_birth'=>Yii::t('app', 'Date of birth'),
			'auth_key' => Yii::t('app', 'Authorisation Key'),
            //'favourite_companion' => Yii::t('app', 'Favourite Companion'),
        ];
    }

    /**
     * @inheritdoc
     * @return MembersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MembersQuery(get_called_class());
    }
	
	public function behaviors ()
	{
		return [
		[
		'class' =>	TimestampBehavior::className(),
		'attributes' => [
				ActiveRecord::EVENT_BEFORE_INSERT => [ 'created_at' , 'updated_at' ],
				ActiveRecord::EVENT_BEFORE_UPDATE => [ 'updated_at' ],
		],
		// if you're using datetime instead of UNIX timestamp:
		// 'value' => new Expression('NOW()'),
		],
		];
	}
	
	
	public function getStates()
	{
		return $this->hasOne(States::className(), ['state_id' => 'state']);
	}
	
	public function getCountys()
	{
		return $this->hasOne(Countys::className(), ['id'=>'county']);
	}
	
	public function getCitys()
	{
		return $this->hasOne(Citys::className(), ['city_id'=>'city']);
	}
	
	/* Getter for person full name */
	public function getFullName() {
		return $this->firstname . ' ' . $this->middlename . ' ' . $this->surname;
	}
	
	public function beforeSave($insert)
	{
		if (parent::beforeSave($insert)) {
			// ...custom code here...'yyyy-MM-dd HH:mm:ss'
			$this->date_of_birth = Yii::$app->formatter->asDate($this->date_of_birth,'yyyy-MM-dd');
			
			if ($this->isNewRecord) {
				$this->status = Members::STATUS_INACTIVE;
				
				//if we are signing up
				$this->setPassword($this->password);
				$this->generateAuthKey();
			}
			
			return true;
		} else {
			return false;
		}
	}

	public function beforeValidate()
	{
		if (parent::beforeValidate()) {
		// ...custom code here...
				
					$this->email = $this->getDummyEmail();
			return true;
		} else {
			return false;
		}
	}
	
	//returns a dummay email from a concatenation of a timestamp & username
	public function getDummyEmail() {
		return Yii::$app->formatter->asTimestamp(date('Y-d-m h:i:s')) . $this->username . "@" . 
				Yii::$app->params['domain'];
	}

	/*
		checks to see wether the admin has initialiased by changing their password
		and entering their serial number if this is set to true  - Yii::$app->params['serialRequired']
		returns FALSE if the admin is not  initialised
	*/
	public static function isAdminInitialised () {
		//$passwordhash = Yii::$app->security->generatePasswordHash('admin');
		//print_r($passwordhash);exit;
		$sql = "select status FROM members WHERE username = 'admin'";
		$status = Yii::$app->db->createCommand($sql)->queryOne();
		if ($status['status'] == 0) {
			return false;
			//echo "mbaya<br>";
		} 
		//echo "sqlmember: $sql";
		//echo "status: flag";exit;
		return true;
	}
	
	
	/*
		the following code is derived from the user model
	*/
	public static function findIdentity ( $id){
		return self:: findOne( $id );	
	}
	
	public static function findIdentityByAccessToken ( $token , $type = null ){
		throw new NotSupportedException ();//didn't implement this method coz I don't have any access token column in my database
	}
	
	public static function findByUsername ( $username ){
		//print_r(self:: findOne([ 'username' => $username ] ));
		return self :: findOne([ 'username' => $username ]);//, 'status' => self::STATUS_ACTIVE
	}
	
    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
                'password_reset_token' => $token,
                'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid.
     * 
     * @param  string $token Password reset token.
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }


	public function getId(){
		return $this -> id;
	}
	
	public function getAuthKey(){
		return $this->auth_key ; //Here I return a value of my auth_key column
	}

	public function validateAuthKey( $authKey ){
		return $this->auth_key === $authKey ;
	}
	
	public function validatePassword ( $password){
		return Yii::$app->getSecurity()->validatePassword($password, $this->password_hash);
	}
	
    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token.
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }
    
    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }


    /**
     * Generates new account activation token.
     */
    public function generateAccountActivationToken()
    {
        $this->account_activation_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes account activation token.
     */
    public function removeAccountActivationToken()
    {
        $this->account_activation_token = null;
    }
	
}
