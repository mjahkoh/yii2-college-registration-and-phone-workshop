Yii2Powered
Showcase of Yii powered  website samples

DIRECTORY STRUCTURE 
  assets/              contains assets definition
  commands/           contains console commands (controllers)
  components/         contains user defined the application components which you may configure 
  config/             contains application configurations
  controllers/        contains Web controller classes
  helpers/       	  contains generic functions used in the application
  mail/               contains view files for e-mails
  messages/           contains messages sent
  models/             contains model classes
  runtime/            contains files generated during runtime
  utilities/		  contains the database structure 	
  vendor/             contains dependent 3rd-party packages
  views/              contains view files for the Web application
  web/                contains the entry script and Web resources
REQUIREMENTS
The minimum requirement by this project template that your Web server supports PHP 7.0.

INSTALLATION
1. Framework and dependencies
. This sample contains the vendor files but you can upload the latest updates of yii2. If you do not have Composer, you may install it by following the instructions at getcomposer.org.

You can then install this application template using the following command:

composer global require "fxp/composer-asset-plugin"
composer install
2. Configs
There are more .php-orig sample configs in config directory. Copy these to .php without -orig and adjust to your needs.

3. Database
The database structure is in utilities folder (Mysql).

4. features
This collection inludes,
=> Auto Suggest DropDown Search - Citys Model 
=> Adding Classes to Rows in the GridView - Countys, Citys, Branches, Units Booked By Students  Model 
=> Ajax Search With the GridView (By using Pjax ) - Authors, Branches, Book Models etc
=> Batch Insert - Insert multiple models - Members, Student Games Model
=> Calender & Events Planner - Events Model
=> Checkbox - Members model
=> Complex forms (multiple models)  - Jobs Model,
=> DatePicker in the Filter Field GridviewSearch filters - Citys Model  
=> Date Pickers - Members, JobsMembers, Branches, Book Models etc
=> Default sorting - BranchesSearch, MembersSearch Model, Rbac (/rbac/index-members) - Sort by Branch_name (SORT_DESC), date_created (SORT_ASC), fullName (SORT_ASC) and searching on related data
=> File Upload (Multiple file Uploader) - Images, Companies model
=> Global Search - Citys Model
=> Getting Values From a Table Using Ajax - Jobs, Citys, Payments, StudentGames, Members Model
=> Hidden inputs - Jobs , Members Model
=> Kartik Date Picker - Jobs, Members Model
=> ListBox - Members model
=> Layouts - Change the theme by designing a layout and specifying the layout in the controller. Check Controller /site/login. The layout is in /views/loginLayout.php
=> Modal Forms (Popup) - Branches, Jobs - payment models
=> Option Box - Members model
=> Option boxes - Jobs Model
=> Popup windows for Forms - Branches Model, Jobs Models
=> Registration and Login - Members and JobsMembers models. Features include (Menus->List A Members)
	Default username:: admin - password:: admin
	- The admin is initialised after changing their passwords from admin
	- The user can reset their passwords by entering their email addresses
	- Resetting password - Members model
	- Serial No verification - After logging in , a serial prompt screen is shown requiring a serial. sample serial codes are in codes table. The inputed serial is checked against that set in this table and if correct the code_valid and code_verified are flagged (companies model.)
	-  Scenarios
=> Role-Based Access Control (RBAC) - Using the yii2 vendor database tables auth_assignmeent, auth_item, auth_item_child, auth_rule you can Assign User Permissions with a checkbox to grant access/deny access to the five basic controller actions namely (Index, View, Delete, Add, Edit). The Authmanager must be configured in the 	config  file (/config/web.php) as DbManager. Intialise Rbac by running rbac/init in the Menu (List C->Rbac->Initialise Rights). To assign rights, select the member from List C->Rbac->Members, the assign by checking all the rights you wish to grant. You can add more controller actions in config file (/config/params) rbacControllerActions parameter. Controllers that arent be available for rights allocation should be listed in the /config/param.php - omittedControllers parameter. Check the implementation in Authors controller. This sub Menu is only avalailable for The Admin who should be logged in
=> Static dropdown generated from Arrays - Countys Model
=> Sending email - JobsMembers, Members, Companies Model
=> Sending sms messages -  Jobs Model - After saving a job, an sms receipt is sent to the clients phone if their is an internet connection or later when the internet connection is alive
=> Setting and getting Cookies - Cookies model, \views\Branches\cookies=> Dynamic/Static dropdowns - Authors, Members, Jobs, Branches, Citys, Countys, Phone, Companies,  Models etc
=> Submitting Forms Using Ajax - Branches Models
=> Tabular editable forms with or without Activeform - Book - indexExample1 and IndexExample4, MarksMaster - MarksDetails models

Note:: The use of mdm\admin\components\AccessControl deactivates the debug menu. You can comment out the entire 'as access' section in components in /config/web.php to view your debug menus