<?php 
    namespace app\models;
    use yii\base\Model;
   
    class CreateSms extends Model{
        public $smsmessage;
       
        public function rules(){
            return [
                [['smsmessage'],'required'],
            ];
        }
       
        public function attributeLabels(){
            return [
                'smsmessage' => 'Sms Message',
            ];
        }
    } 