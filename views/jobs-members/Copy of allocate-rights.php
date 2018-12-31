<?php
namespace app\models;
use yii\base\Model;
use Yii;

use yii\helpers\Html;

use app\models\TreeSample;
//use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model app\models\Companies */
/* @var $form yii\widgets\ActiveForm */
$this->title = Yii::t('app', 'Members');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="allocate-rights-form">

		<div style="width: 100%; overflow: hidden;">
			<div style="width: 600px; float: left;">
			<div style="width: 600px; display: table-cell;"> 
			<?php				
			
			$isAdmin = true ;
			$topRootAsHeading = true;
			$fontAwesome = true;
			$enableCache = true;
			$softDelete = true;
			
			use kartik\tree\TreeView;
			/*
			echo TreeView::widget([
				// single query fetch to render the tree
				'query'             => TreeSample::find()->addOrderBy('root, lft'), 
				'headingOptions'    => ['label' => 'Categories'],
				'isAdmin'           => false,                       // optional (toggle to enable admin mode)
				'displayValue'      => 1,                           // initial display value
				//'softDelete'      => true,                        // normally not needed to change
				//'cacheSettings'   => ['enableCache' => true]      // normally not needed to change
			]);*/
			
			echo \kartik\tree\TreeView::widget([
				'query' => TreeSample::find()->addOrderBy('root, lft'),
				'headingOptions' => ['label' => 'Store'],
				'rootOptions' => ['label'=>'<span class="text-primary">Products</span>'],
				'topRootAsHeading' => $topRootAsHeading, // this will override the headingOptions
				'fontAwesome' => $fontAwesome,
				'isAdmin' => $isAdmin,
				'iconEditSettings'=> [
					'show' => 'list',
					'listData' => [
						'folder' => 'Folder',
						'file' => 'File',
						'mobile' => 'Phone',
						'bell' => 'Bell',
					]
				],
				'softDelete' => $softDelete,
				'cacheSettings' => ['enableCache' => $enableCache],
				'options'=>[
					'disabled' => true, 
					'visible' => false,
				],
			]);			
			
			?>				
			</div>
			<div class="help-block"></div></div>
			<div style="margin-left: 620px;"> <div style="display: table-cell;"> 
				gridview	
			</div>
			<div class="help-block"></div></div>
		</div>
	
</div>
