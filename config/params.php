<?php

return [
    //------------------------//
    // SYSTEM SETTINGS
    //------------------------//
 
    /**
     * Registration Needs Activation.
     *
     * If set to true, upon registration, users will have to activate their accounts using email account activation.
     */
    'rna' => false,

    /**
     * Login With Email.
     *
     * If set to true, users will have to login using email/password combo.
     */
    'lwe' => false, 

    /**
     * Force Strong Password.
     *
     * If set to true, users will have to use passwords with strength determined by StrengthValidator.
     */
    'fsp' => false,

    /**
     * Set the password reset token expiration time.
     */
    'user.passwordResetTokenExpire' => 3600,

    /**
     * Set the list of usernames that we do not want to allow to users to take upon registration or profile change.
     */
    'user.spamNames' => 'admin|superadmin|creator|thecreator|username',

    //------------------------//
    // EMAILS
    //------------------------//

    /**
     * Email used in contact form.
     * Users will send you emails to this address.
     */
    'adminEmail' => 'info@mail.localserver.com', 

    /**
     * Email used in sign up form, when we are sending email with account activation link.
     * You will send emails to users from this address.
     */
    'supportEmail' => 'master@mail.localserver.com',
	
	'domain' => 'example.com',
	
	'serialRequired' => TRUE,
	
	//gmail cconfiguration settings
    'host' 			=> 'smtp.gmail.com',	//127.0.0.1
    'username' 		=> 'gmail-username',			//root
    'password' 		=> 'gmail-password',	
    'smtp_port' 	=> '587',	//25
	'ssl_port' 		=> '465',	//25
	'encryption' 	=> 'tls',
	
	//offline database connection parameters
    'db_host' 		=> '127.0.0.1',	//127.0.0.1
    'db_username' 	=> 'admin',			//root
    'db_password' 	=> 'password',
    'db_database' 	=> 'database-name',
    'db_port' 		=> '3306',
	
    'from_email_phonerepairs' 	=> 'from@gmail.com',
    'to_email_phonerepairs' 	=> 'to@gmail.com',
	
	'languages'	=>	[
		'sw'=> 'Swahili',
		'fr'=> 'french',
		'ru'=> 'russian',
		'hi'=> 'hindi',
		'en'=> 'english',
	],
	
	//rbac controller parameters
	'rbacControllerActions' => [
								"view",
								"update",
								"index",
								"create",
								"delete"
	],
	
	//list of  controller that arent available for rights allocation
	'omittedControllers' => [
		'admin',
		'site',
		'rbac'
	],
];
