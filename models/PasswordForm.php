<?php
    namespace app\models;
   
    use Yii;
    use yii\base\Model;
    use app\models\Login;
   
    class PasswordForm extends Model{
        public $oldpass;
        public $newpass;
        public $repeatnewpass;
       
        public function rules(){
            return [
                [['oldpass','newpass','repeatnewpass'],'required'],
                ['oldpass','findPasswords'],
                ['repeatnewpass','compare','compareAttribute'=>'newpass'],
            ];
        }
       
        public function findPasswords($attribute, $params){
            $user = Members::find()->where(['username'=>Yii::$app->user->identity->username])->one();
            $password = $user->password;
            if($password!=$this->oldpass)
                $this->addError($attribute,'Old password is incorrect');
        }
       
        public function attributeLabels(){
            return [
                'oldpass'=>'Old Password',
                'newpass'=>'New Password',
                'repeatnewpass'=>'Repeat New Password',
            ];
        }
    } 