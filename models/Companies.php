<?php

namespace app\models;

use Yii;
use app\helpers\Setup;
use yii\web\UploadedFile;

/**
 * This is the model class for table "companies".
 *
 * @property integer $id
 * @property string $company_name
 * @property string $address
 * @property integer $status
 * @property string $date_created
 * @property string $logo
 * @property integer $tel
 * @property integer $mobile
 * @property integer $mobile2
 * @property string $slogan
 * @property string $physical_location
 * @property string $facebook_handle
 * @property string $tweeter_handle
 * @property string $email
 */
class Companies extends \yii\db\ActiveRecord
{

		const KENYA 		= 254;
		const UGANDA 		= 256;
		const TANZANIA 		= 255;
		const RWANDA 		= 257;
		const BURUNDI 		= 258;
        public $country_prefix = [
            self::KENYA     => 'Kenya',
            self::UGANDA 	=> 'Uganda',
			self::TANZANIA 	=> 'Tanzania',
			self::RWANDA 	=> 'Rwanda',
			self::BURUNDI 	=> 'Burundi',
        ];
		public $imageFiles;
		

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'companies';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['company_name','tel', 'tel_prefix', 'email', 'physical_location'], 'required', ],
			[['tel_prefix', 'mobile_prefix', 'mobile2_prefix'] , 'integer', 'min' => 254, 'max' => 258],
            [['status'], 'integer', 'max' => 3],
            [['date_created'], 'safe'],
            [['company_name', 'slogan', 'website_url'], 'string', 'max' => 100],
            [['address', 'physical_location'], 'string', 'max' => 255],
            [['logo'], 'string', 'max' => 200],
            [['facebook_handle', 'tweeter_handle'], 'string', 'max' => 40],
			[['email'], 'email'],
            [['email'], 'string', 'max' => 60],
			[['tel'], 'isPhoneValid'],
			[['imageFiles'], 'file', 'skipOnEmpty'=> false, 'extensions' => 'png, jpg', 'maxFiles' => 5],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Company ID'),
            'company_name' => Yii::t('app', 'Company Name'),
            'address' => Yii::t('app', 'Address'),
            'status' => Yii::t('app', 'Status'),
            'date_created' => Yii::t('app', 'Date Created'),
            'logo' => Yii::t('app', 'Logo'),
            'tel' => Yii::t('app', 'Tel'),
            'mobile' => Yii::t('app', 'Mobile'),
            'mobile2' => Yii::t('app', 'Mobile2'),
            'slogan' => Yii::t('app', 'Slogan'),
            'physical_location' => Yii::t('app', 'Physical Location'),
            'facebook_handle' => Yii::t('app', 'Facebook Handle'),
            'tweeter_handle' => Yii::t('app', 'Tweeter Handle'),
            'email' => Yii::t('app', 'Email'),
            'website_url' => Yii::t('app', 'Website Url'),
			'imageFiles' => Yii::t('app', 'Logo'),
        ];
    }

    /**
     * @inheritdoc
     * @return CompaniesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CompaniesQuery(get_called_class());
    }
	
	// min deposits
	public function isPhoneValid($attribute, $params)
	{
		if ($this->tel && (strlen(trim($this->tel)) < 9 || strlen(trim($this->tel)) > 12)) {
			$this->addError($attribute, "The mobile should be between 9-12 numeric characters");
		}
		if ($this->mobile && (strlen(trim($this->mobile)) < 9 || strlen(trim($this->mobile)) > 12)) {
			$this->addError($attribute, "The home phone should be around 9-12 numeric characters");
		}
		
		if ($this->mobile2 && (strlen(trim($this->mobile2)) < 9 || strlen(trim($this->mobile2)) > 12)) {
			$this->addError($attribute, "The home phone should be around 9-12 numeric characters");
		}
	}
	
	public function afterSave($insert, $changedAttributes )
	{
		parent::afterSave($insert, $changedAttributes );
		//$companyname = $this->company_name;
		//$this->imageFiles =  UploadedFile::getInstances($this, 'imageFiles');
		//print_r($this->imageFiles);exit;	
		
		if ($this->imageFiles) {
			$query = [];
			/*
			delete previous files associated with this entry. all the image names 
			begin with the company name 
			*/
			$uploads = Yii::getAlias('@uploads'); // Alias root_dir/uploads
			$companyname = Setup::trimString($this->company_name);
			array_map('unlink', glob($uploads . '/' . $companyname . '_*'));
			//delete from the database table companies_files
			//$sql = "delete from companies_files where company_id =" . $this->id;
			//Yii::$app->db->createCommand($sql)->execute();
			
			// DELETE (table name, condition)
			Yii::$app->db->createCommand()->delete('companies_files', "company_id = " . $this->id)->execute();
			
			foreach ($this->imageFiles as $file) {
				$baseName = Setup::trimString($file->baseName);
				$filename = "uploads/" . $companyname . '_' . $baseName . '.' . $file->extension;
				/* 
				- Following only saves the filename of the last image if multiple uploads
				- amend the below statament to suit your needs and save the multiple filename
				*/
				
				$file->saveAs($uploads . '/' . $companyname . '_' . $baseName . '.' . $file->extension, false);
				$this->logo = $filename;
				//$query[] = "insert into companies_files(filename, company_id) values ('$filename', " . $this->id .") ";
				
				// INSERT (table name, column values)
				Yii::$app->db->createCommand()->insert('companies_files', [
					'filename' => $filename,
					'company_id' => $this->id
				])->execute();
			}
			
		}	
		
		//verify the serial keys
		Setup::verifySerialKeys();
		return true;
	}
	
	/*
    public function setImages()
    {

		$this->imageFiles =  UploadedFile::getInstances($model, 'imageFiles');
		$companyname = $this->company_name;
		//print_r($model);exit;	
		if ($model->imageFiles) {
			//
			delete previous files associated with this entry. all the image names 
			begin with the company name 
			//
			$uploads = Yii::getAlias('@uploads'); // Alias root_dir/uploads
			array_map('unlink', glob($uploads . '/' . $companyname . '_*'));
			//
			//for multiple files we need to save the individual files  in  //
			//separate rows in a  companiesFiles modal
			//
			$query = "delete from companies_files where company_id =" . $model->id;
			Yii::$app->db->createCommand($query)->execute();
			$query = [];
			foreach ($model->imageFiles as $file) {
				$file->saveAs($uploads . '/' . $companyname . '_' . $file->baseName . '.' . $file->extension, false);
			}
			//for single files
			//
			//$model->imageFiles->saveAs('uploads/'. $companyname. '_'  . '.' . $model->imageFiles->extension, false);
			//$model->logo = "uploads/". $companyname . '_.' . $model->imageFiles->extension;
			//
			
		} else {
			echo "noot uploaded";
			exit;			
		}
	}*/
	
	public function beforeSave($insert)
	{
		if (parent::beforeSave($insert)) {
			// for multiple files, this file saved be last file 
			if ($this->imageFiles) {
				foreach ($this->imageFiles as $file) {
					$baseName = Setup::trimString($file->baseName);
					$companyname = Setup::trimString($this->company_name);
					$logo = "uploads/" . $companyname . '_' . $baseName . '.' . $file->extension;
					$this->logo = $logo ;
				}
			}
			return true;
		} else {
			return false;
		}
	}
	
    /* @inheritdoc*/
    public function beforeValidate()
    {
		if (parent::beforeValidate()){
			$this->imageFiles =  UploadedFile::getInstances($this, 'imageFiles');
			if ($this->isNewRecord) {
			
			}
			return true;
		}
		
		return true;	
	}
	 
}