<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;
use yii\db\ActiveRecord;
use kartik\password\StrengthValidator;
/**
 * This is the model class for table "staff".
 *
 * @property integer $id
 * @property string $name
 * @property integer $status
 * @property string $password_reset_token
 */
class JobsMembers extends \yii\db\ActiveRecord implements IdentityInterface
{

	/*
    const PREFIX_KENYA   = 254; 
    const PREFIX_UGANDA = 256;
    const PREFIX_TANZANIA  = 255; 
    const PREFIX_RWANDA   = 250; 
    const PREFIX_BURUNDI = 257;

	public $telprefix = [
		self::PREFIX_KENYA     => 'Kenya',
		self::PREFIX_UGANDA 	=> 'Uganda',
		self::PREFIX_TANZANIA 	=> 'Tanzania',
		self::PREFIX_RWANDA 	=> 'Rwanda',
		self::PREFIX_BURUNDI 	=> 'Burundi',
	];
	*/
//////////////////////////////////

    const STATUS_ACTIVE   = 10; 
    const STATUS_INACTIVE = 1;
    const STATUS_DELETED  = 0; 
	
	public $staffstatus = [
		self::STATUS_ACTIVE     => 'Active',
		self::STATUS_INACTIVE 	=> 'In-Active',
		self::STATUS_DELETED 	=> 'Deleted',
	];
	
	public $repeatpassword;
	public $permissions;
	public $password;
	public $telephone;
	public $memberscat;
	public $memberscat2;
	public $sendsmsmessage;
	public $memberssmsid;
	
    const STAFF_MEMBER  = 1;
    const CLIENTELLE 	= 2;
    const BOTH  		= 3; 
	public $memberscategory = [
		self::STAFF_MEMBER     => 'Staff',
		self::CLIENTELLE 	=> 'Client',
		self::BOTH 	=> 'All',
	];


	const SCENARIO_STAFF_MEMBER = 'Staff Member'; //ALL rules will apply to pass field, 	when using 'signin' scenario return $scenarios; 
	const SCENARIO_CLIENTELLE = 'Clientelle';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'jobs_members';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['name', 'tel', 'username', 'password', 'email' , 'tel_prefix'], 'required', 'on' => self::SCENARIO_STAFF_MEMBER],
			[['name', 'tel', 'tel_prefix'], 'required', 'on' => self::SCENARIO_CLIENTELLE],
			[['status', 'tel','mobile', 'category', 'national_id'], 'integer'],
            [['name', 'auth_key'], 'string', 'max' => 50],
			[['username', 'password'], 'string', 'max' => 30],
			[['password_hash'], 'string', 'max' => 60],
			[['national_id'], 'integer', 'max' => 9999999999],
		 	['email','email'],
			['username', 'unique', 'targetClass' => 'app\models\JobsMembers', 'message' => 'This username is already taken'],
			[['tel'], 'isPhoneValid'],
            ['repeatpassword','compare','compareAttribute'=>'password'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'status' => Yii::t('app', 'Status'),
			'tel' => Yii::t('app', 'Tel'),
			'mobile' => Yii::t('app', 'Mobile'),
			'category' => Yii::t('app', 'Category'),
			'auth_key' => Yii::t('app', 'Authorisation Key'),
			'email' => Yii::t('app', 'Email'),
			'repeatpassword' => Yii::t('app', 'Confirm Password'),
			'tel_prefix' => Yii::t('app', 'Tel Prefix'),
			'Mobile_prefix' => Yii::t('app', 'Mobile Prefix'),
			'username' => Yii::t('app', 'Username'),
			'password' => Yii::t('app', 'Password'),
			'national_id' => Yii::t('app', 'National Id'),
			'telephone'=>Yii::t('app', 'Telephone'),
			'memberscat2'=>Yii::t('app', 'memberscat2'),
			'sendsmsmessage'=>Yii::t('app', 'Send Message'),
        ];
    }

	public function scenarios() { 
		$scenarios = parent::scenarios(); 
		$scenarios[self::SCENARIO_STAFF_MEMBER] = ['name', 'tel', 'username', 'email', 'password'] ; 
		$scenarios[self::SCENARIO_CLIENTELLE] = ['name', 'tel'] ; 
		return $scenarios;
	}

	public function beforeSave($insert)
	{
		if (parent::beforeSave($insert)) {
			// ...custom code here...
			if ($this->isNewRecord) {
				$this->status = JobsMembers::STATUS_INACTIVE;
				
				//if we are signing up
				$this->setPassword($this->password);
				$this->generateAuthKey();
			}
			
			return true;
		} else {
			return false;
		}
	}
	
	//returns FALSE if the admin is initialised
	public static function checkAdminStatus () {
		//$passwordhash = Yii::$app->security->generatePasswordHash('admin');
		////print_r($passwordhash);exit;
		$flag = true;
		$sql = "select status FROM members WHERE username = 'admin' and status = ". JobsMembers::STATUS_INACTIVE;// and password_hash = '$passwordhash'
		$status = Yii::$app->db->createCommand($sql)->queryOne();
		if ($status['status']) {
			$flag = false;
		} 
		//echo "sql: $sql";
		//echo "status: $flag";exit;
		return $flag;
	}
	
	public function getTelPrefix () {
		return $this->tel_prefix;
	}

    /**
     * @inheritdoc
     * @return MembersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MembersQuery(get_called_class());
    }
	
	public function isPhoneValid($attribute, $params)
	{
		if ($this->tel && strlen(trim($this->tel)) != 9) {
			$this->addError($attribute, "The Tel should be 9 numeric characters");
		}
		if ($this->mobile && strlen(trim($this->mobile)) != 9) {
			$this->addError('mobile', "The mobile should be 9 numeric characters");
		}
		
	}
	
	public static function findIdentity ( $id){
		return self:: findOne( $id );	
	}
	
	public static function findIdentityByAccessToken ( $token , $type = null ){
		throw new NotSupportedException ();//didn't implement this method coz I don't have any access token column in my database
	}
	
	public static function findByUsername ( $username ){
		////print_r(self:: findOne([ 'username' => $username ] ));
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
		//echo "db password: ".$this->password_hash ."<br>";
		//echo "typed password: ".md5($password)." <br>";
		return Yii::$app->getSecurity()->validatePassword($password, $this->password_hash);
		//return $this->password === $password ;
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
     * Set password rule based on our setting value (Force Strong Password).
     *
     * @return array Password strength rule.
     
    private function passwordStrengthRule()
    {
        // get setting value for 'Force Strong Password'
        $fsp = Yii::$app->params['fsp'];

        // password strength rule is determined by StrengthValidator 
        // presets are located in: vendor/kartik-v/yii2-password/presets.php
        $strong = [['password'], StrengthValidator::className(), 'preset'=>'normal'];

        // normal yii rule
        $normal = ['password', 'string', 'min' => 6];

        // if 'Force Strong Password' is set to 'true' use $strong rule, else use $normal rule
        return ($fsp) ? $strong : $normal;
    }
	*/

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