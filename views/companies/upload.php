<?php
use yii\helpers\Url ;
use yii\web\JsExpression;
?>

    <?php
	
	
echo \xj\Dropzone::widget([
    'url' => ['upload', 'id' => $model->id],
    'id' => 'album-upload',
    'jsOptions' => [
        'previewTemplate' => '<div class="dz-size" data-dz-size></div>',
        'success' => new JsExpression("
		function(file, data) {
			console.log(data);
			if (data.error) {
				alert(data.msg);
			} else {
				alert(data.fileUrl);
				alert(data.id);
		//etc...
				console.log(file);
			}
		}"
		
		),
    ],
    'warpOptions' => ['id' => 'album-upload-dropzone'],
    'formOptions' => ['class' => 'album-upload-dropzone dz-clickable'],
]);	
	
/*	
    echo \kato\DropZone::widget([
       'autoDiscover' => false,
	   'options' => [
           'maxFilesize' => '2',
		   'url'=> Yii::getAlias('@uploads'),
       ],
       'clientEvents' => [
           'complete' => "function(file){console.log(file)}",
           'removedfile' => "function(file){alert(file.name + ' is removed')}"
       ],
   ]);


echo \kato\DropZone::widget([
       'autoDiscover' => false,
       'options' => [
		   'init' => new JsExpression("function(file){alert( ' is removed')}"),
		   //'url'=> Url::toRoute(['index.php/companies/upload']),
		   'url'=> 'index.php/companies/upload',
		   'maxFilesize' => '2',
		   'addRemoveLinks' =>true,
		   'acceptedFiles' =>'image/*',    
       ],
       'clientEvents' => [
           'complete' => "function(file){console.log(file)}",
          // 'removedfile' => "function(file){alert(file.name + ' is removed')}"
           'removedfile' => "function(file){
		    	  alert('Delete this file?');
				  $.ajax({
					   url: '". Url::toRoute(['index.php/companies/remove-files']) ."',
					   type: 'GET',
					   data: { 'filetodelete': file.name}
				  });

           }"
       ],    
]); 
	   
	echo \kato\DropZone::widget([
       'autoDiscover' => false,
       'options' => [
		    'init' => new \yii\web\JsExpression("function(file){alert( ' is removed')}"),
		    'url'=> Url::toRoute(['index.php/companies/upload']) ,
			'maxFilesize' => '2',
			'addRemoveLinks' =>true,
			'acceptedFiles' =>'image/*',    
       ],
	   'clientEvents' => [
		  'complete' => "function(file){console.log(file)}",
		  // 'removedfile' => "function(file){alert(file.name + ' is removed')}"
		  'removedfile' => "function(file){alert('Delete this file?')}",
	   ],   
	]); 
	   
   
	echo \kato\DropZone::widget([
       'autoDiscover' => false,
       'options' => [
		    'init' => new \yii\web\JsExpression('function(file){alert( ' is removed')}'),
		    'url'=> Url::toRoute(['index.php/companies/upload']) ,
			'maxFilesize' => '2',
			'addRemoveLinks' =>true,
			'acceptedFiles' =>'image/*',    
       ],
       'clientEvents' => [
          'complete' => 'function(file){console.log(file)}',
          // 'removedfile' => 'function(file){alert(file.name + ' is removed')}'
          'removedfile' => 'function(file){alert('Delete this file?')}',
          $.ajax({
               url: Url::toRoute(['index.php/companies/remove-files']),
               type: 'GET',
               data: {'filetodelete': file.name}
          });

           }'
       ],    ]); ?>   
	   
*/	   
	   
	   ?>   
  
  
  
  