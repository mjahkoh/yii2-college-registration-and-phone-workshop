<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;
use app\helpers\Setup;

/**
 * This is the model class for table "images".  
 *
 * @property int $id
 * @property string $filename 
 * @property string $filelocation 
 
 */
class Images extends \yii\db\ActiveRecord
{
    public $imageFiles;
	/**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'images';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [ 
            [['filelocation', 'filename', 'source_file'], 'required'],  
			[['description', 'filelocation', 'filename', 'source_file'], 'string', 'max' => 255],
			[['filename', 'filelocation'], 'string', 'max' => 200],
			[['imageFiles'], 'file', 'skipOnEmpty'=> false, 'extensions' => 'png, jpg, gif, jpeg', 'maxFiles' => 5],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'filename' => 'Filename',
			'filelocation' => 'File Location',
			'imageFiles' => Yii::t('app', 'Image'),
			'description' => Yii::t('app', 'Description'),
			'source_file' => Yii::t('app', 'Source file'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return ImagesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ImagesQuery(get_called_class());
    }
	
    /** @inheritdoc */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
    }
	
	public function setImages(){
	
			$this->imageFiles =  UploadedFile::getInstances($this, 'imageFiles');
			//print_r($model);exit;	
			if ($this->imageFiles) {
				
				/*
				delete previous files associated with this entry. all the image names 
				begin with the company name 
				
				array_map('unlink', glob($uploads . '/' . $companyname . '_.*'));*/
				
				/*
				for multiple files we need to save the individual files  in 
				separate rows in a  companiesFiles modal
				*/
				$uploads = Yii::getAlias('@uploads'); // Alias root_dir/uploads

				$query = [];
				//echo "is it new rec". $this->isNewRecord ."<br>";exit;
				$transaction = Yii::$app->db->beginTransaction();
				try {
					$count = 0;
					/*
					delete previous files associated with this id. all the image names 
					begin with the  name */
					array_map('unlink', glob($uploads . $this->id . '_*'));
					//$description = $this->description;
					foreach ($this->imageFiles as $file) {
						$sourcefile = Setup::trimString($file->name);
						if ($this->isNewRecord) {
							//$sql = "insert into images(source_file)	values ('$sourcefile')";
							//Yii::$app->db->createCommand($sql)->execute();
							
							// INSERT (table name, column values)
							Yii::$app->db->createCommand()->insert('images', [
								'source_file' => $sourcefile
							])->execute();
							$id = Yii::$app->db->getLastInsertID();	
						} else {
							$id = $this->id;
						}
						$baseName = Setup::trimString($file->baseName);
						
						$filename = $id . '_' . $baseName . '.' . $file->extension;
						$filelocation = $uploads . $id . '_' . $baseName . '.' . $file->extension; 
										
						/*
						$sql = "update images set
							filename 			= '$filename',
							source_file 		= '$sourcefile',
							description 		= ' ". $this->description . "',
							filelocation 		= '$filelocation'
							where id = $id";
						Yii::$app->db->createCommand($sql)->execute();
						*/
						Yii::$app->db->createCommand()->update('images', [
							'filename' => $filename, 
							'source_file' => $sourcefile,
							'description' => $this->description,
							'filelocation' => $filelocation
							], "id = $id")->execute();		
								
										
						//echo $uploads . $id . '_' . $file->baseName . '.' . $file->extension."<br>";
						//exit;
						$file->saveAs($uploads  . $id . '_' . $baseName . '.' . $file->extension, false);
						/* 
						- Following only saves the filename of the last image if multiple uploads
						- amend the below statament to suit your needs and save the multiple filename
						*/
						//$this->filename = $logo;
						
						$count++;
					}
					$transaction->commit();
					Yii::$app->session->setFlash('success', "Uploaded {$count} Images successfully.");
					return $id;	
				} catch (Exception $e) {
					$transaction->rollback();
				}
				
			} 
			Yii::$app->session->setFlash('warning', "Their was a problem with Uploading");
			return NULL;			
	}
	
    /* @inheritdoc*/
    public function beforeValidate()
    {
		if (parent::beforeValidate()){
			$this->filename =  "dummyFileName.com";
			if ($this->isNewRecord) {
			
			}
			return true;
		}
		
		return true;	
	}
		
}