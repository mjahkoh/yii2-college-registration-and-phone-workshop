<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\Json;


/**
 * MeterReadingsController implements the CRUD actions for MeterReadings model.
 */
class GeneralController extends Controller
{


	/* 
	parameters
	formname - the form name,
	dropdown - the dropdown to be selected
	id -  the selected value
	*/
	
	public function actionGetOptions() {

		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		if (isset($_REQUEST['formName'])) {
		
			$formName = $_REQUEST['formName'];
			$dropdown = $_REQUEST['dropdown'];
			$selectvalue = $_REQUEST['id'];
			$rows = [];
			
			//print_r($formName);exit;
			switch ($formName) {
				
				/*members form*/
				case "members-form":
				
					switch ($dropdown) {
						
						case "members-state":
							$sql = "select id ,county as text 
								from uscitiesv_countys WHERE state_id = '$selectvalue'";
							break;
				
						case "members-county":
							$sql = "select city_id as id, city as text 
								from uscitysv_citys WHERE county_id = '$selectvalue'";
							break;				
					}
						
					break;
					
				
				case "jobs-form":
				
					switch ($dropdown) {
						
						case "jobs-phone_make_id":
							$sql = "select id ,model as text 
								from phone_models WHERE phone_make_id = '$selectvalue'";
							break;
				
						case "jobs-members-county":
							$sql = "select city_id as id, city as text 
								from uscitysv_citys WHERE county_id = '$selectvalue'";
							break;				
					}
						
					break;
					
				
				case ("jobs-members-form" || "citys-form"):
				
					switch ($dropdown) {
						
						case ("jobs-members-state" || "citys-state"):
							$sql = "select id ,county as text 
								from uscitiesv_countys WHERE state_id = '$selectvalue'";
							break;
				
						case ("jobs-members-county" || "citys-county"):
							$sql = "select city_id as id, city as text 
								from uscitysv_citys WHERE county_id = '$selectvalue'";
							break;				
					}
						
					break;
					
				case "other-form":
				
					switch ($dropdown) {
						
						case "other":
							break;
							
						case "another":
							$sql = "select id, name as text 
								from table WHERE id = '$selectvalue'";
							break;
					}
					break;	
				
				case "departments-form":
					switch ($dropdown) {
						
						case "company_id":
							$sql = "select  id,branch_name as text from branches WHERE 
							companies_company_id = '$selectvalue'";
							break;
				
						case "ward":
							$sql = "select wardid as id, name as text 
								from ward WHERE constituencyid = '$selectvalue'";
							break;
				
					}
					break;
					
				case "citys":
						$selectvalue = $_REQUEST['state'];
						$sql = "select city_id as id,city as text from citys WHERE state_id = '$selectvalue'"; 
						break;
						
				case "1":
						$selectvalue = $_REQUEST['state'];
						$sql = "select * from dfdfsd WHERE state_id = '$selectvalue'";
						break;
						
				case "2":
						$selectvalue = $_REQUEST['state'];
						$sql = "select * from dfdfsd WHERE state_id = '$selectvalue'";
						break;
						
			}
		
			$query = Yii::$app->db->createCommand($sql)->queryAll();
			if (count($query)) {
				foreach ($query as $row) {
					$id = $row['id'];
					$text = $row['text'];
					$rows[] = array("value" => $id, "text" => $text);			
				}
			}
				
			if (count($rows)) {
				echo json_encode($rows);
			} else {
				echo json_encode(NULL);
			}	
			
		} 
		
	}


}