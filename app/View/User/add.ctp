<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>

<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">
	// This identifies your website in the createToken call below
	Stripe.setPublishableKey('pk_test_xVpnIKNMpauLCEyRfNWDpS6O');
	
	//Intercept submit and retrieve token from Stripe
	jQuery(function($) {
	  $('#userForm').submit(function(event) {
	    var $form = $(this);
		
		//hide error messages
		$form.find('#credit-card-error').hide();
	    // Disable the submit button to prevent repeated clicks
	    $form.find('.submit input').prop('disabled', true);
	
	    Stripe.card.createToken($form, stripeResponseHandler);
	
	    // Prevent the form from submitting with the default action
	    return false;
	  });
	});
	
	//Add token to form and complete process
	var stripeResponseHandler = function(status, response) {
	  var $form = $('#userForm');
	
	  if (response.error) {
	    // Show the errors on the form
	    $form.find('#credit-card-error').text(response.error.message).show();
	    $form.find('.submit input').prop('disabled', false);
	  } else {
	    // token contains id, last4, and card type
	    var token = response.id;
	    // Insert the token into the form so it gets submitted to the server
	    $form.append($('<input type="hidden" name="data[User][stripeToken]" />').val(token));
	    // and submit
	    $form.get(0).submit();
	  }
	};
</script>

<h1>Add User</h1>
<?php
echo $this->Form->create('User', array('id' => 'userForm'));
echo $this->Form->input('first_name');
echo $this->Form->input('last_name');
echo $this->Form->input('email');
?>
  <div id="credit-card-error" class="message" style="display:none;"></div>

  <div class="input text" title="for test values, check https://stripe.com/docs/testing">
    <label>Card Number</label>
    <input type="text" size="20" data-stripe="number"/>
  </div>

  <div class="input text">
    <label>CVC</label>
    <input type="text" size="4" data-stripe="cvc"/>
  </div>

  <div class="input text">
    <label>Expiration Month</label>
    <input type="text" size="2" data-stripe="exp-month"/>
  </div>
  
  <div class="input text">
    <label>Expiration Year</label>
    <input type="text" size="4" data-stripe="exp-year"/>
  </div>

<?php
echo $this->Form->end('Save User');
?>