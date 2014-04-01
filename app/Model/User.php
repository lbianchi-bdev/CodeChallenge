<?php
require APP . 'Plugin' . DS . 'NotificationManager' . DS . 'Model' . DS . 'Notification.php';

class User extends AppModel {
	public $name = 'User';

    public $validate = array(
        'first_name' => array(
            'rule' => 'notEmpty'
        ),
        'last_name' => array(
            'rule' => 'notEmpty'
        ),
	    'email' => array(
	        'notEmpty' => array(
	            'rule' => 'notEmpty',
	            'message' => 'Provide an email address'
	        ),
	        'validEmailRule' => array(
	            'rule' => array('email'),
	            'message' => 'Invalid email address'
	        ),
	        'uniqueEmailRule' => array(
	            'rule' => 'isUnique',
	            'message' => 'Email already registered'
	        )
	    ),
        'stripeToken' => array(
            'rule' => 'notEmpty'
        ),
    );
    
	public $hasMany = [
	    'Notification' => [
	        'foreignKey' => 'object_id',
	        'conditions' => [
	            'Notification.model' => 'User'
	        ]
	    ],
	];
	
	public function sendNotification(){
		$notification = [
		'model' => 'User', // name of the object model
		'object_id' => $this->id, // id of the object
		'property' => 'email', // property of the object that will be used to notify (ex. email, phone, cell)
		'type' => 'EMAIL', // Type of notification, can be EMAIL, PUSH, or SMS
		'data' => json_encode([
				'settings' => 'default', // email settings
				'subject' => 'Welcome!', // email subject
				'template' => 'welcome', // email template
				'emailFormat' => 'html', // email format
				'viewVars' => [ // email vars
				'first_name' => $this->field('first_name'),
				'last_name' => $this->field('last_name'),
				'email' => $this->field('email')
				]
				])
				];
		 
		try {
			$NotificationModel = new Notification();
			$NotificationModel->create();
			$NotificationModel->save($notification);
		} catch (Exception $e) {
			// failure catch
		}
	
	}
	
}