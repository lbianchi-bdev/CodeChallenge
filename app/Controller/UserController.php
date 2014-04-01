<?php
require APP . 'Vendor' . DS . 'stripe' . DS . 'lib' . DS . 'Stripe.php';

class UserController extends AppController {
	public $helpers = array('Html','Form','Session');
	public $components = array('Session');
	
	private $stripeSecretKey = 'sk_test_lOM03oeI69Tl9LH2b2eulKDX';
	
    function index() {
        $this->set('users', $this->User->find('all'));
    }

    public function add() {
        if ($this->request->is('post')) {
            if ($this->createStripeCustomerFromToken() && $this->User->save($this->request->data)) {
            	$this->User->sendNotification();
            	$this->Session->setFlash('Your user has been saved.');
                $this->redirect(array('action' => 'index'));
            }
        }
    }
    
    private function createStripeCustomerFromToken(){
    	$result = false;
    	
    	// Set your secret key: remember to change this to your live secret key in production
    	// See your keys here https://manage.stripe.com/account
    	Stripe::setApiKey($this->stripeSecretKey);
    	
    	// Create a Customer
    	try {
    		$customer = Stripe_Customer::create(array(
				"card" => 			$this->request->data['User']['stripeToken'],
				"description" => 	$this->request->data['User']['email'])
    		);

    		//Save the new customer id, instead of the one time card token
    		$this->request->data['User']['stripeToken'] = $customer->id;
    		
    		$result = true;

		} catch(Exception $e) {
			$body = $e->getJsonBody();
			$err  = $body['error'];
			$this->Session->setFlash($err['message']);
    	}
    	
    	return $result;
    }
    
}