<?php
    namespace app\models;
   
    use Yii;
    use yii\base\Model;
    use app\helpers\Setup;
   
    class CodeForm extends Model{
	
        public $code;
       
        public function rules(){
            return [
                [['code'],'required'],
				[['code'], 'verifycode'],
            ];
        }
       
        public function attributeLabels(){
            return [
                'code'=>'Code',
            ];
        }
		
		/*
		verifies wether the serial number in the users, local database is valid
		if so returns true else returns false
		*/
		public function verifycode($attribute, $params)
		{
			$encode =  Setup::encode($this->code);//echo "<br>";
			$sql = "select id from codes where code_hash = '$encode' limit 1" ;
			$codes = Yii::$app->db->createCommand($sql)->queryOne();
			if ($codes['id']){
				return true;
			}	
			$this->addError($attribute, "The Serial Is Invalid");
		}
	
	
		public static function verifyHashCodeInModel($hashcode) {
			$sql = "select id from codes where code_hash = '$hashcode'  limit 1";
			$check = Yii::$app->db->createCommand($sql)->queryOne();
			if ($check['id']) {
				return true;
			}
			return false;
		}
		
					
    } 