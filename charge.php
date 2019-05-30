<pre>
<?php
session_start();

// Includes required classes
require_once('includes/user.inc.php');
require_once('vendor/stripe/stripe-php/init.php');

// Sets my personal API key to make sure that every purchase goes to my business account.
\Stripe\Stripe::setApiKey('sk_test_2PDY77YQecnXCCzvlaePy98m00km8LjgsH'); //YOUR_STRIPE_SECRET_KEY

// Gets the validation token from the JS script that is inserted right before the user clicks on the "purchase"-button
$token = $_POST['stripeToken'];

// Creates a class and checks if the user exists. And if it does exist, return the information about the user
$userClass = new User();
$userInfo = $userClass->userExist($_SESSION['user'], true);
$userInfo = $userInfo[0];

// Sets the database values to the appropriate values
$name_first = $userInfo['fname'];
$name_last = $userInfo['lname'];
$address = $userInfo['adress'];
$state = $userInfo['state'];
$zip = $userInfo['zip'];
$country = $userInfo['country'];
$phone = $userInfo['phone'];

// Sets the user values to the correct array value
$user_info = [
    'First Name' => $name_first,
    'Last Name' => $name_last,
    'Address' => $address,
    'State' => $state,
    'Zip Code' => $zip,
    'Country' => $country,
    'Phone' => $phone
];

// Checks if the user already is a customer and have used stripe before. If so, set the customer_id to the customer_id fetched from the database
if (!is_null($userInfo['stripe_id'])) {
    $customer_id = $userInfo['stripe_id'];
}
/*
If the customer_id is set, then the true block will run. Otherwise the elseblock will run
--true block (
The code will try to fetch the customer in Stripes database with their information about the customer.
If the Card fails, it will link to another page "errorPage.php"
if there is some other error it will display it as a json object with the apporpriate error.
)
--else block (
The customer is created by sending all information we have to the Stripe client.
which then returns a customer id along with a bunch of information.
If the Card fails, it will link to another page "errorPage.php"
if there is some other error it will display it as a json object with the apporpriate error.
)

After the If statement we will enter another if statement to check if the customer variable is set.
If it is true (
    The code will send a "charge" query to Stripe with that customer.
    If the charge was successfull. Then the code will take the stripe customer_id and update -
    our database with that information.
    And last of all we link the user to the reciept.php page.
)
if it is false (
    Do nothing.
)
*/
if (isset($customer_id)) {
    try {
        // Use Stripe's library to make requests...
        $customer = \Stripe\Customer::retrieve($customer_id);
    } catch (\Stripe\Error\Card $e) {
        // Since it's a decline, \Stripe\Error\Card will be caught
        $body = $e->getJsonBody();
        $err  = $body['error'];

        print('Status is:' . $e->getHttpStatus() . "\n");
        print('Type is:' . $err['type'] . "\n");
        print('Code is:' . $err['code'] . "\n");
        // param is '' in this case
        print('Param is:' . $err['param'] . "\n");
        print('Message is:' . $err['message'] . "\n");
        Header('Location: errorPage.php');
    } catch (\Stripe\Error\RateLimit $e) {
        // Too many requests made to the API too quickly
    } catch (\Stripe\Error\InvalidRequest $e) {
        // Invalid parameters were supplied to Stripe's API
    } catch (\Stripe\Error\Authentication $e) {
        // Authentication with Stripe's API failed
        // (maybe you changed API keys recently)
    } catch (\Stripe\Error\ApiConnection $e) {
        // Network communication with Stripe failed
    } catch (\Stripe\Error\Base $e) {
        // Display a very generic error to the user, and maybe send
        // yourself an email
    } catch (Exception $e) {
        // Something else happened, completely unrelated to Stripe
    }
} else {
    try {
        // Use Stripe's library to make requests...
        $customer = \Stripe\Customer::create(array(
            'email' => $userInfo['email'],
            'source' => $token,
            'metadata' => $user_info,
        ));
    } catch (\Stripe\Error\Card $e) {
        // Since it's a decline, \Stripe\Error\Card will be caught
        $body = $e->getJsonBody();
        $err  = $body['error'];

        print('Status is:' . $e->getHttpStatus() . "\n");
        print('Type is:' . $err['type'] . "\n");
        print('Code is:' . $err['code'] . "\n");
        // param is '' in this case
        print('Param is:' . $err['param'] . "\n");
        print('Message is:' . $err['message'] . "\n");
        Header('Location: errorPage.php');
    } catch (\Stripe\Error\RateLimit $e) {
        // Too many requests made to the API too quickly
    } catch (\Stripe\Error\InvalidRequest $e) {
        // Invalid parameters were supplied to Stripe's API
    } catch (\Stripe\Error\Authentication $e) {
        // Authentication with Stripe's API failed
        // (maybe you changed API keys recently)
    } catch (\Stripe\Error\ApiConnection $e) {
        // Network communication with Stripe failed
    } catch (\Stripe\Error\Base $e) {
        // Display a very generic error to the user, and maybe send
        // yourself an email
    } catch (Exception $e) {
        // Something else happened, completely unrelated to Stripe
    }
}

if (isset($customer)) {

    $charge_customer = true;

    // Save the customer id in your own database!
    // Charge the Customer instead of the card
    try {
        // Use Stripe's library to make requests...
        $charge = \Stripe\Charge::create(array(
            'amount' => 2000,
            'description' => 'Bribes to teacher',
            'currency' => 'sek',
            'customer' => $customer->id,
            'metadata' => $user_info
        ));
    } catch (\Stripe\Error\Card $e) {
        // Since it's a decline, \Stripe\Error\Card will be caught
        $body = $e->getJsonBody();
        $err  = $body['error'];

        print('Status is:' . $e->getHttpStatus() . "\n");
        print('Type is:' . $err['type'] . "\n");
        print('Code is:' . $err['code'] . "\n");
        // param is '' in this case
        print('Param is:' . $err['param'] . "\n");
        print('Message is:' . $err['message'] . "\n");

        $charge_customer = false;
    } catch (\Stripe\Error\RateLimit $e) {
        // Too many requests made to the API too quickly
    } catch (\Stripe\Error\InvalidRequest $e) {
        // Invalid parameters were supplied to Stripe's API
    } catch (\Stripe\Error\Authentication $e) {
        // Authentication with Stripe's API failed
        // (maybe you changed API keys recently)
    } catch (\Stripe\Error\ApiConnection $e) {
        // Network communication with Stripe failed
    } catch (\Stripe\Error\Base $e) {
        // Display a very generic error to the user, and maybe send
        // yourself an email
    } catch (Exception $e) {
        // Something else happened, completely unrelated to Stripe
    }

    if ($charge_customer) {
        if (is_null($userInfo['stripe_id'])) {
            $success = $userClass->setStripeId($customer->id);
            if ($success) {
                Header('Location: reciept.php');
            }
        } else {
            Header('Location: reciept.php');
        }
    }
}
