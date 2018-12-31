<?php
    namespace app\models;
   
    use Yii;
    use yii\base\Model;
    //use app\models\Login;
   
    class Cookies extends Model{
	
        public $cookie_name;
		public $value;
       
        public function rules(){
            return [
                [['cookie_name', 'value'],'required'],
				[['cookie_name', 'value'], 'string', 'max' => 30],
            ];
        }
       
        public function attributeLabels(){
            return [
                'cookie_name'=>'Cookie name',
				'value'=>'Value',
            ];
        }
		
    } 