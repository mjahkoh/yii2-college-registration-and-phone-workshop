<?php
    namespace app\models;
   
    use Yii;
    use yii\base\Model;
    //use app\models\Login;
   
    class AdminPasswordForm extends Model{
	
        public $password;
        public $repeatnewpass;
       
        public function rules(){
            return [
                [['password','repeatnewpass'],'required'],
                ['repeatnewpass','compare','compareAttribute'=>'password'],
            ];
        }
       
        public function attributeLabels(){
            return [
                'password'=>'New Password',
                'repeatnewpass'=>'Repeat New Password',
            ];
        }
		
		public function beforeSave($insert)
		{
			if (parent::beforeSave($insert)) {
				// ...custom code here...
				$this->password = $this->setPassword($this->password);
				$this->status = Members::STATUS_ACTIVE;
				//print_r('password: '.$this->password);
				//print_r('status: '.$this->status);
				//exit;
				return true;
			} else {
				return false;
			}
		}
		
    } 