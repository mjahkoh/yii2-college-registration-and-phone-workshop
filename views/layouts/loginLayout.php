<?php

/* @var $this \yii\web\View */
/* @var $content string */
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use app\assets\LoginAsset;

LoginAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<!-- <html lang="<?php //Yii::$app->language ?>">-->
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ;?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">

    <div class="container">
		
		<?php if (Yii::$app->session->hasFlash('success')): ?>
		  <div class="alert alert-success role="alert"">
		  <button  type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		  <h4><i class="icon fa fa-check"></i><!-- Saved!--></h4>
		  <?= Yii::$app->session->getFlash('success') ?>
		  </div>
		<?php endif; ?>		
		
		<?php if (Yii::$app->session->hasFlash('warning')): ?>
		  <div class="alert alert-danger role="alert"">
		  <button  type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		  <h4><i class="icon fa fa-check"></i><!-- Saved!--></h4>
		  <?= Yii::$app->session->getFlash('warning') ?>
		  </div>
		<?php endif; ?>		
		<b> This uses the Login Layout Template (views\layouts\loginLayout.php)</b>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">Oooh, Grandpa was right, hunting bugs is cheaper than lions. They do hurt. &copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
