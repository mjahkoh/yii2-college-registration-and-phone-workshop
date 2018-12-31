<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
	
		$members = [
			['label' => Yii::t('app', 'Authors'), 'url' => ['/authors/index']],
			['label' => Yii::t('app', 'Books'), 'url' => ['/book/index'],
				'items' => [
					['label' => Yii::t('app', 'Index'), 'url' => ['/book/index']],
					['label' => Yii::t('app', 'Index-example-1'), 'url' => ['/book/index-example-1']],
					['label' => Yii::t('app', 'Index-example-2'), 'url' => ['/book/index-example-2']],
					['label' => Yii::t('app', 'Index-example-3'), 'url' => ['/book/index-example-3']],
					['label' => Yii::t('app', 'Index-example-4'), 'url' => ['/book/index-example-4']],
				],
			],	
			['label' => Yii::t('app', 'Games'), 'url' => ['/games/index']],
			['label' => Yii::t('app', 'Games Booked'), 'url' => ['/student-games/index']],
			['label' => Yii::t('app', 'Members'), 'url' => ['/members/index'],
				'items' => [
					['label' => Yii::t('app', 'Coaches'), 'url' => ['/members/index', 'id'=>'1']],
					['label' => Yii::t('app', 'Students'), 'url' => ['/members/index', 'id'=>'2']],
					['label' => Yii::t('app', 'Support Staff'), 'url' => ['/members/index', 'id'=>'3']],
					['label' => Yii::t('app', 'Teaching staff'), 'url' => ['/members/index', 'id'=>'4']],
					['label' => Yii::t('app', 'Reset Password'), 'url' => ['/members/request-password-reset']],
					['label' => Yii::t('app', 'Admin Password'), 'url' => ['/members/set-admin-password']],
					['label' => Yii::t('app', 'Serial code'), 'url' => ['/members/set-code']],
					['label' => Yii::t('app', 'Index'), 'url' => ['/members/index']],
				],
			],	
		
			/*
			['label' => Yii::t('app', 'Register'), 'url' => ['/members/index'],
				'items' => [
					['label' => Yii::t('app', 'Personnel'), 'url' => ['/members/index?id=1']],
					['label' => Yii::t('app', 'Students'), 'url' => ['/members/index?id=2']],
				],
			],	
			*/
			['label' => Yii::t('app', 'Units Booked'), 'url' => ['/units-booked-by-students/index']],
			['label' => Yii::t('app', 'Units'), 'url' => ['/units/index']],
			
		];
	
		
		$setup[] = ['label' => Yii::t('app', 'Company'), 'url' => ['/companies/index']];
		$setup[] = ['label' => Yii::t('app', 'Jobs'), 'url' => ['/jobs/index']];
		$setup[] = ['label' => Yii::t('app', 'Jobs Members'),  
			'items' => [
				['label' => Yii::t('app', 'Clients Only List'), 'url' => ['/jobs-members/index-clientelle' ]],
				['label' => Yii::t('app', 'Staff Members & Clients'), 'url' => ['/jobs-members/index']],
				['label' => Yii::t('app', 'Staff Members List'), 'url' => ['/jobs-members/index-staff-members']],
				['label' => Yii::t('app', 'Staff Members & Clients List'), 'url' => ['/jobs-members/index-both']],
			],
		];
		$setup[] = ['label' => Yii::t('app', 'Phone Makes'), 'url' => ['/phone-makes/index']];
		$setup[] = ['label' => Yii::t('app', 'Phone Models'), 'url' => ['/phone-models/index']];
		$setup[] = ['label' => Yii::t('app', 'Settings'), 'url' => ['/settings/index']];
		$Setup = ['label' => Yii::t('app', 'Setup'),
					'items' => $setup
		];
		
		$lists = [
			['label' => Yii::t('app', 'Branches'), 'url' => ['/branches/index'],
				'items' => [
					['label' => Yii::t('app', 'Branches'), 'url' => ['/branches/index']],
					['label' => Yii::t('app', 'Branches - Modal'), 'url' => ['/branches/index-modal']],
					['label' => Yii::t('app', 'Branches - Modal Ajax'), 'url' => ['/branches/index-modal-ajax']],
					//['label' => Yii::t('app', 'Branches - GridView'), 'url' => ['/branches/index-grid-view']],
				],
			],	
			['label' => Yii::t('app', 'Countys'), 'url' => ['/countys/index']],
			['label' => Yii::t('app', 'Cities - Global Search'), 'url' => ['/citys/index']],
			['label' => Yii::t('app', 'Codes'), 'url' => ['/codes/index']],
			['label' => Yii::t('app', 'Departments'), 'url' => ['/departments/index']],
			['label' => Yii::t('app', 'Events'), 'url' => ['/events/index']],
			['label' => Yii::t('app', 'Games'), 'url' => ['/games/index']],
			['label' => Yii::t('app', 'States'), 'url' => ['/states/index']],
			['label' => Yii::t('app', 'Streams'), 'url' => ['/streams/index']],
			['label' => Yii::t('app', 'Uploading Images'), 'url' => ['/images/index']],
		];
	
		//only the admin can see this
		if (!Yii::$app->user->isGuest &&  Yii::$app->user->identity->username === 'admin'  ) {
			$lists[] = 
				['label' => Yii::t('app', 'Rbac'), 'url' => ['/rbac/index'],
					'items' => [
						['label' => Yii::t('app', 'Add Controller Role'), 'url' => ['/rbac/create-controller-role']],
						['label' => Yii::t('app', 'Controllers'), 'url' => ['/rbac/index']],
						['label' => Yii::t('app', 'Initialise Rights'), 'url' => ['/rbac/init']],
						['label' => Yii::t('app', 'Members'), 'url' => ['/rbac/index-members']],
					],
				];
		}	
			
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Home', 'url' => ['/site/index']],
			['label' => Yii::t('app', 'List A'), 'items' => $members],			
			['label' => Yii::t('app', 'List B'), 'items' => $setup],			
			['label' => Yii::t('app', 'List C'), 'items' => $lists],		
            ['label' => 'About', 'url' => ['/site/about']],
            ['label' => 'Contact', 'url' => ['/site/contact']],
            Yii::$app->user->isGuest ? (
                ['label' => 'Login', 'url' => ['/site/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
			<!-- look for the languages css-->
			<div class="languages">
				<?php
				foreach (Yii::$app->params['languages'] as $key => $language) {
					//echo '<span class = "language" id="' . $key . '">'. "<a href='#'>$language</a>" . ' | </span>';
				}
				?>
			</div>
			
		
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
		
		
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">Oooh, Grandpa was right, hunting bugs is easier and safer than lions. They can hurt. &copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
