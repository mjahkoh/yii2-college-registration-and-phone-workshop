<?php
use yii\helpers\Url ;
/* @var $this yii\web\View */

$this->title = 'My Yii2 Example application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h2>Yii2 examples!</h2>

        <p class="lead">Ideal for beginners, this is a collection of sample models drawn from a simple college system with registration and RBAC features. Its also has samples of Phone workshop that registers clients and issues job cards as well as collects payments. The default username - admin, password - admin. Check the serial in the database table codes. The database structure is in the utilities folder</p>

        <p><a class="btn btn-lg btn-success" href="http://www.yiiframework.com">Get started with Yii</a></p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h1><a href="<?= Url::toRoute(["companies/index"]) ;?>">Companies Model</a></h1>

                <strong>Dropdowns, Multiple file Uploader</strong>
 
				<p>Static dropdowns derived from an array and multiple file uploader based on kartik FileInput Widget.</p>

                <p><a class="btn btn-default" href="<?= Url::toRoute(["companies/index"]) ;?>">Companies &raquo;</a></p><br />
<br />

                <h1><a href="<?= Url::toRoute(["citys/index"]) ;?>">Citys Model</a></h1>

                <strong>Auto Suggest Dropdowns, Global Search, Classes to Rows in the GridView</strong>
 
				<p>Auto Suggest DropDown Search (create view), Global Search, Adding Classes to Rows in the GridView.</p>

                <p><a class="btn btn-default" href="<?= Url::toRoute(["citys/index"]) ;?>">Citys &raquo;</a></p>
				
				
                <h1><a href="<?= Url::toRoute(["departments/index"]) ;?>">Departments Model</a></h1>

                <strong>Dependant Dropdowns - Company to Branches</strong>
 
				<p>Dependant Dropdowns - Company to Branches</p>

                <p><a class="btn btn-default" href="<?= Url::toRoute(["departments/index"]) ;?>">Departments &raquo;</a></p>
				
				
                <h1><a href="<?= Url::toRoute(["branches/index"]) ;?>">Branches Model</a></h1>

                <strong>Default sorting by Branch_name (SORT_DESC), date_created (SORT_ASC) and searching on related data</strong>
 
				<p><a href="<?= Url::toRoute(["branches/index-modal"]) ;?>">Modal Branches</a></p>
				<p><a href="<?= Url::toRoute(["branches/index-modal-ajax"]) ;?>">Ajax Modal Branches</a></p>
                <p><a class="btn btn-default" href="<?= Url::toRoute(["branches/index"]) ;?>">branches &raquo;</a></p>
				
                <h1><a href="<?= Url::toRoute(["phone-makes/index"]) ;?>">Phone Makes Model</a></h1>
                <p><a class="btn btn-default" href="<?= Url::toRoute(["phone-makes/index"]) ;?>">Phone Makes &raquo;</a></p>
				
                <h1><a href="<?= Url::toRoute(["phone-models/index"]) ;?>">Phone Models Model</a></h1>
                <p><a class="btn btn-default" href="<?= Url::toRoute(["phone-models/index"]) ;?>">Phone models &raquo;</a></p>
				
            </div>
			
			
            <div class="col-lg-4">
                <h1><a href="<?= Url::toRoute(["members/index"]) ;?>">Members Model</a></h1>

                <strong>Dynamic dropdownsLists, Dependant dropdownlists,  Static dropdownlists, Option Box, ListBox, Kartik Date Picker and Checkbox</strong>
 
				<p>During registration, when the email is not entered the system makes a dummy email address. You cannot change the username or email. The password can only be resetted in <a href="<?= Url::toRoute(["/index.php/members/request-password-reset"  ]);?>">changePassword</a> and the <a href="<?= Url::toRoute(["/index.php/members/set-admin-password"  ]);?>">Admin password</a> must be set on <a href="<?= Url::toRoute(["/index.php/site/initialize"  ]);?>">intitialization</a> and the serial code entered. The default Username is admin and Password is admin<br />
An email is sent to the Email provided to verify the serial codes if their is an internet connection and will always check whether their is a connection on initialisation if the serial number is unverified. The remote database should have the serial numbers to check against while the  local database have the codes encoded (Setup::encode). If the serial number is legitimate codeverified is flaged. If the serial is validated on the remote database , code_valid is flagged , both are in Companies model.<br />
Three scenarios are envisaged, Personnel , students and Update. In update scenario you cannot change the password neither email. It also shows how to retrieve information from table and display it on the form via ajax (_form.php) using javascript val function.</p>

                <br />
<br />

                <h1><a href="<?= Url::toRoute(["jobs/index"]) ;?>">Jobs Model</a></h1>
                <strong>Dynamic Dropdown, Complex forms (multiple models), Hidden inputs, Kartik Date Picker, Option boxes</strong>
				<p>Saves multiple models using Transactions (Jobs & MembersJobs) and sends a jobs card confirmation sms to client</p>
                <p><a class="btn btn-default" href="<?= Url::toRoute(["jobs/index"]) ;?>">Jobs &raquo;</a></p>
				
                <h1><a href="<?= Url::toRoute(["units/index"]) ;?>">Units Model</a></h1>
                <p><a class="btn btn-default" href="<?= Url::toRoute(["units/index"]) ;?>">Units models &raquo;</a></p>
				
            </div>
			
			
            <div class="col-lg-4">
                <h1><a href="<?= Url::toRoute(["marks-master/index"]) ;?>">MarksMaster - MarksDetails Models</a></h1>

                <p>Complex Forms - Master - Detail Multiple forms, Kartik Tabular Form.</p>

                <p><a class="btn btn-default" href="<?= Url::toRoute(["marks-master/index"]) ;?>">Marks Master &raquo;</a></p>
				
				
                <h1><a href="<?= Url::toRoute(["authors/index"]) ;?>">Authors Model</a></h1>
                <strong>Static Dropdowns</strong>
				<p>Static Dropdowns</p>
                <p><a class="btn btn-default" href="<?= Url::toRoute(["authors/index"]) ;?>">Authors &raquo;</a></p>
				
                <h1><a href="<?= Url::toRoute(["states/index"]) ;?>">States Model</a></h1>
                <strong>Static Dropdowns</strong>
				<p>53 USA  States</p>
                <p><a class="btn btn-default" href="<?= Url::toRoute(["states/index"]) ;?>">States &raquo;</a></p>
				
                <h1><a href="<?= Url::toRoute(["countys/index"]) ;?>">Countys Model</a></h1>
                <strong>DropDown, Adding Classes to Rows in the GridView, generating dropdown from a Public Array declared in the countys model(paymenttype)</strong>
				<p>3,000+ USA Countys</p>
				<p><a class="btn btn-default" href="<?= Url::toRoute(["countys/index"]) ;?>">Countys &raquo;</a></p>
				
                <h1><a href="<?= Url::toRoute(["citys/index"]) ;?>">Citys Model</a></h1>
				<strong>Dropdowns, Global Search, Classes to Rows</strong>
				<p>Auto Suggest DropDown Search (create view), Global Search, Adding Classes to Rows in the GridView. 38,000+ American Citys</p>
                <p><a class="btn btn-default" href="<?= Url::toRoute(["citys/index"]) ;?>">Citys &raquo;</a></p>
				
                <h1><a href="<?= Url::toRoute(["units-booked-by-students/index"]) ;?>">Units Booked By Students Model</a></h1>
				<strong>Dropdowns, Global Search, Classes to Rows</strong>
				<p>Auto Suggest DropDown Search (create view), Global Search, Adding Classes to Rows in the GridView. 38,000+ American Citys</p>
                <p><a class="btn btn-default" href="<?= Url::toRoute(["units-booked-by-students/index"]) ;?>">Units Booked By Students Model &raquo;</a></p>
				
				
				
            </div>
        </div>

    </div>
</div>
