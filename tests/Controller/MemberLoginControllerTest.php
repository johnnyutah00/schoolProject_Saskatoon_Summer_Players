<?php

namespace App\Tests;


use App\DataFixtures\MemberLoginTestFixture;
use Liip\FunctionalTestBundle\Test\WebTestCase;


class MemberLoginControllerTest extends WebTestCase
{
    private $crawler;
    private $client;
    private $form;
    private $roles;


    /**
     * Set up for all tests on login page
     * @author Dylan Sies, MacKenzie Wilson
     */
    public function setUp()
    {

        // Instantiate the client to test against
        $this->client = static::createClient();

        // Retrieve the page information from the specified route
        $this->client->request('GET', '/login');

        $this->crawler = $this->client->getCrawler();

        $this->form = $this->crawler->selectButton('login')->form();

        //Add the three roles to the roles attribute
        //Use for any login check tests.
        $this->roles = array('ROLE_MEMBER', 'ROLE_BM', 'ROLE_GM');

    }


    /**
     * This test will make sure that the MemberLogin page exists
     * Confirms that the route and controller are properly setup
     * @author Dylan Sies, MacKenzie Wilson
     */
    public function testThatMemberLoginPageExists()
    {
        // Get status code from the client, 200 means connection successful, 500 is error, 404 is not found
        $this->assertStatusCode(200, $this->client);
    }

    /**
     * This method will test that the Login Button is displayed on the show page and will redirect the user to the login
     * page when selected
     * Authors: Dylan Sies, & MacKenzie Wilson
     */
    public function testThatLoginButtonIsDisplayedOnShowPageAndRedirects()
    {
        //Confirm that the client is on the show page.
        $this->client->request('GET', '/show');

        $this->client->followRedirect();
        $this->crawler = $this->client->getCrawler();

        //Asserts to make sure the client is on the show page.
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals($this->client->getCrawler()->getUri(), "http://localhost/show/");

        //Asserts to confirm that the login button exists on the page.
        $this->assertContains("Login", $this->crawler->filter('a#loginButton')->text());

        //Selects and clicks on the button
        $link = $this->crawler->filter('#loginButton')->link();
        $this->client->click($link);

        //Assert that we are redirected to the login page
        $this->assertStatusCode(200, $this->client);
        $this->assertEquals($this->client->getCrawler()->getUri(), "http://localhost/login");
    }


    /**
     * This test will make sure that there all login form inputs are of the login page
     * @author Dylan Sies, MacKenzie Wilson
     */
    public function testThatEmailAndPasswordInputsAreOnLoginPage()
    {
        //Assert that the form and its email and username inputs are on the login page
        $this->assertEquals($this->crawler->filter("#_username")->attr("name"), "_username");
        $this->assertEquals($this->crawler->filter("#_password")->attr("name"), "_password");
    }


    /**
     * This test will make sure that there is a button on the login page that directs you to the
     * member registration page
     * @author Dylan Sies, MacKenzie Wilson
     */
    public function testThatRegisterLinkIsOnTheLoginPageAndWorks()
    {

        $this->assertEquals($this->crawler->filter("#register_link")->attr("name"), "register");

        //Select the link
        $regLink = $this->crawler->filter("#register_link")->link();

        //Click on the link to be redirected to the registration page
        $this->client->click($regLink);


        //Assert that we are redirected to the registration page
        $this->assertStatusCode(200, $this->client);
        $this->assertEquals($this->client->getCrawler()->getUri(), "http://localhost/registration");
    }


    /**
     * This test will make sure that a login button is shown at the top of the page
     * for a user that is not logged in
     * @author Dylan
     */
    public function testThatLoginButtonAtTopOfPageAndWorks()
    {
        //Navigate to a valid page that has the navigation bar implemented which will have a login button
        $this->client = static::createClient();

        // Retrieve the page information from the specified route
        $this->client->request('GET', '/show');

        $this->client->followRedirect();

        $this->crawler = $this->client->getCrawler();

        //Check that there is a login button with the id of loginButton
        $this->assertEquals($this->crawler->filter("#loginButton")->attr("name"), "loginButton");


        //Select the link by the name of the login button
        $link = $this->crawler->filter('#loginButton')->link();

        //Assert that the link is pointing to the registration page
        //$this->assertEquals($link->getUri(), "http://localhost/login");
        $this->client->click($link);

        //Assert that we are redirected to the registration page
        $this->assertStatusCode(200, $this->client);
        $this->assertEquals($this->client->getCrawler()->getUri(), "http://localhost/login");
    }


    /**
     * This test will make sure that an error message is shown on the page after
     * a login attempt is made with an incorrect email
     * @author Dylan
     */
    public function testThatErrorMessageShownWhenIncorrectEmail()
    {
        //Load members into database
        $this->loadFixtures([MemberLoginTestFixture::class]);


        $form = $this->crawler->selectButton('login')->form();

        //Set Invalid email that is not in the database
        $form['_username']->setValue("memer@member.com");
        $form['_password']->setValue("P@ssw0rd");

        $this->client->submit($form);

        $this->client->followRedirect();
        $this->crawler = $this->client->getCrawler();

        //Look for error message when email is not correct
        $this->assertEquals($this->crawler->filter("#invalid_credentials")->text(), "Either your email or password is incorrect.");
    }


    /**
     * This test will make sure that an error message is shown on the page after
     * a login attempt is made with an incorrect password
     * @author Dylan
     */
    public function testThatErrorMessageShownWhenIncorrectPassword()
    {
        //Load members into database
        $this->loadFixtures([MemberLoginTestFixture::class]);

        $form = $this->crawler->selectButton('login')->form();

        //Right email but wrong password
        $form['_username']->setValue("member@member.com");
        $form['_password']->setValue("Pssw0rd");

        //Submit the login
        $this->client->submit($form);

        $this->client->followRedirect();
        $this->crawler = $this->client->getCrawler();

        //Look for error message when password is not correct
        $this->assertEquals($this->crawler->filter("#invalid_credentials")->text(), "Either your email or password is incorrect.");
    }


    /**
     * This test will make sure that an error message is shown on the page after
     * a login attempt is made with an incorrect password
     * @author Dylan
     */
    public function testThatErrorMessageShownWhenIncorrectPasswordWrongCase()
    {
        //Load members into database
        $this->loadFixtures([MemberLoginTestFixture::class]);

        $form = $this->crawler->selectButton('login')->form();

        //Right email but wrong password lowercase p
        $form['_username']->setValue("member@member.com");
        $form['_password']->setValue("p@ssw0rd");

        //Submit the login
        $this->client->submit($form);

        $this->client->followRedirect();
        $this->crawler = $this->client->getCrawler();

        //Look for error message when password is not correct
        $this->assertEquals($this->crawler->filter("#invalid_credentials")->text(), "Either your email or password is incorrect.");
    }


    /**
     * This test will make sure the when a member successfully logs in (must be in database)
     * that they are redirected to another page
     * This will need a fixture to load the database, to make sure the member is in the database
     * @author Dylan
     */
    public function testThatASuccessfulLoginRedirectsYou()
    {
        //Load members into database
        $this->loadFixtures([MemberLoginTestFixture::class]);

        $form = $this->crawler->selectButton('login')->form();

        $form['_username']->setValue("member@member.com");
        $form['_password']->setValue("P@ssw0rd");

        //Submit the login
        $this->client->submit($form);

        $this->client->followRedirect();


        //Make sure redirected to show page
        $this->assertStatusCode(200, $this->client);
        $this->assertEquals($this->client->getCrawler()->getUri(), "http://localhost/show/");
    }

    /**
     * This function tests that when the user types there credentials and logs into the webPage,
     * they are stored successfully into the session.
     * @author MacKenzie Wilson & Dylan Sies.
     */
    public function testThatSubmitButtonLogsInUser()
    {
        //Set Variables for userName and password.
        $userName = "member@member.com";

        ////////Attempt to login. ///////////

        //Load members into database
        $this->loadFixtures([MemberLoginTestFixture::class]);

        $form = $this->crawler->selectButton('login')->form();

        $form['_username']->setValue($userName);
        $form['_password']->setValue("P@ssw0rd");

        //Submit the login
        $this->client->submit($form);

        $this->client->followRedirect();
        $this->crawler = $this->client->getCrawler();

        $this->assertStatusCode(200, $this->client);
        $this->assertEquals($this->client->getCrawler()->getUri(), "http://localhost/show/");

        //Get the session from the webPage
        $session = $this->client->getContainer()->get('session');

        //Assert that the userName is in the session. Note: login information is stored in the variable _security_main
        $this->assertContains($userName, $session->get('_security_main'));
        $this->assertFalse(empty($session->get('_security_main')));

    }


    /**
     * This method will test that a logged in user will be logged out when they click the logout button
     * and that they will be logged out of the page
     * @author MacKenzie Wilson & Dylan Sies
     */
    public function testThatLogOutButtonLogsOutUserAndRedirects()
    {
        $userName = "member@member.com";

        //Load members into database
        $this->loadFixtures([MemberLoginTestFixture::class]);

        $form = $this->crawler->selectButton('login')->form();

        //Log user in

        $form['_username']->setValue($userName);
        $form['_password']->setValue("P@ssw0rd");

        //Submit the login
        $this->client->submit($form);

        $this->client->followRedirect();
        $this->crawler = $this->client->getCrawler();

        $this->assertStatusCode(200, $this->client);
        $this->assertEquals($this->client->getCrawler()->getUri(), "http://localhost/show/");

        $sessionLogin = $this->client->getContainer()->get('session');
        $this->assertContains($userName, $sessionLogin->get('_security_main'));

        //LogOut button
        $logout = $this->crawler->filter('a#logoutButton')->link();
        $this->crawler = $this->client->click($logout);

        $this->client->followRedirect();
        $this->crawler = $this->client->getCrawler();

        $this->assertStatusCode(200, $this->client);
        $this->assertEquals($this->client->getCrawler()->getUri(), "http://localhost/");

        $sessionLogOut = $this->client->getContainer()->get('session');
        $this->assertTrue(empty($sessionLogOut->get('_security_main')));

    }


    /**
     * This test will make sure that once a user is successfully logged in, the logout button is on
     * the page
     * @author Dylan Sies & MacKenzie Wilson
     */
    public function testThatLogoutButtonIsOnThePageAfterAuthentication()
    {
        //Load members into database
        $this->loadFixtures([MemberLoginTestFixture::class]);

        $form = $this->crawler->selectButton('login')->form();

        $form['_username']->setValue("member@member.com");
        $form['_password']->setValue("P@ssw0rd");

        //Submit the login
        $this->client->submit($form);

        $this->client->followRedirect();
        $this->crawler = $this->client->getCrawler();

        //Make sure redirected to show page
        $this->assertStatusCode(200, $this->client);
        $this->assertEquals($this->client->getCrawler()->getUri(), "http://localhost/show/");

        $this->assertContains("Logout ", $this->crawler->filter('a#logoutButton')->text());

    }


    /**
     * Test to make sure that the login button is no longer present on the page
     * after the user is successfully logged in.
     * @author MacKenzie Wilson & Dylan Sies
     */
    public function testThatLoginButtonIsNotOnThePageAfterAuthentication()
    {
        //Load members into database
        $this->loadFixtures([MemberLoginTestFixture::class]);

        $form = $this->crawler->selectButton('login')->form();

        $form['_username']->setValue("member@member.com");
        $form['_password']->setValue("P@ssw0rd");

        //Submit the login
        $this->client->submit($form);

        $this->client->followRedirect();
        $this->crawler = $this->client->getCrawler();

        //Make sure redirected to show page
        $this->assertStatusCode(200, $this->client);
        $this->assertEquals($this->client->getCrawler()->getUri(), "http://localhost/show/");


        $this->assertNotContains("Login ", $this->crawler->filter('a#loginButton'));

    }


    /**
     * This test will make sure that the email input field has a type of type="email", this verifies that
     * HTML validation will take place on the client side
     * @author Dylan
     */
    public function testThatEmailInputIsEmailType()
    {
        //Assert that the first input (email) has a type attribute set to email
        $this->assertTrue($this->crawler->filter("#_username")->attr("type") === "email");
    }


    /**
     * This test will make sure that the email input field has a required attribute, this verifies that
     * HTML validation will take place on the client side.
     * @author Dylan Sies & MacKenzie Wilson
     */
    public function testThatEmailInputIsRequired()
    {
        //Assert that the first input (email) has the required attribute
        $this->assertTrue($this->crawler->filter("#_username")->attr("required") === "required");
    }


    /**
     * This test will make sure that the password field has a type of type="password", this verifies that
     * typed letters will not show on the client side because it is a password field.
     * @author Dylan Sies & MacKenzie Wilson
     */
    public function testThatPasswordInputIsPasswordType()
    {
        //Assert that the css selector #_password has a type attribute of password
        $this->assertTrue($this->crawler->filter("#_password")->attr("type") === "password");
    }


    /**
     * This test will make sure that the email input field has a required attribute, this verifies that
     * HTML validation will take place on the client side.
     * @author Dylan Sies & MacKenzie Wilson
     */
    public function testThatPasswordInputIsRequired()
    {
        //Assert that the 2nd input (password) has the required attribute
        $this->assertTrue($this->crawler->filter("#_password")->attr("required") === "required");
    }

    /**
     * This test will ensure that the email input tag on the login page has a max length of 256
     * Authors:  Kate Zawada, Dylan Sies, & MacKenzie Wilson
     */
    public function testThatEmailLengthIsSet()
    {
        $this->assertTrue($this->crawler->filter("#_username")->attr("maxlength") === "256");
    }

    /**
     * This test will ensure that the password input tag on the login page has a max length of 4096
     * Authors:  Kate Zawada, Dylan Sies, & MacKenzie Wilson
     */
    public function testThatPasswordLengthIsSet()
    {
        $this->assertTrue($this->crawler->filter("#_password")->attr("maxlength") === "4096");
    }

    /**
     * This test will confirm that the element with id of _remember_me is of a checkbox type
     * @author MacKenzie Wilson, & Dylan Sies
     */
    public function testThatRememberMeInputIsCkBoxType()
    {
        $this->assertTrue($this->crawler->filter("#_remember_me")->attr("type") === "checkbox");
    }

    /**
     * This function tests that the Remember Me Checkbox successfully keeps the user in the session
     * @author MacKenzie Wilson & Dylan Sies
     */
    public function testThatRememberMeFunctionalityWorks()
    {
        //Load members into database
        $this->loadFixtures([MemberLoginTestFixture::class]);

        $form = $this->crawler->selectButton('login')->form();

        $form['_username']->setValue("member@member.com");
        $form['_password']->setValue("P@ssw0rd");
        $form['_remember_me']->tick();

        //Submit the login form
        $this->client->submit($form);

        $this->client->followRedirect();
        $this->crawler = $this->client->getCrawler();

        //Make sure redirected to show page
        $this->assertStatusCode(200, $this->client);
        $this->assertEquals($this->client->getCrawler()->getUri(), "http://localhost/show/");


        //Redirect to Google.com
        $this->client->request('GET', 'www.google.com');
        //Redirect back to our website.
        $this->client->request('GET', '/show');

        //get the session from the WebPage
        $session = $this->client->getContainer()->get('session');

        //Assert that the userName is in the session. Note: login information is stored in the variable _security_main
        $this->assertContains("member@member.com", $session->get('_security_main'));
        $this->assertFalse(empty($session->get('_security_main')));
    }

    /**
     * This function will test to see if after a user registers that the user is automatically logged into the website.
     * @author MacKenzie Wilson & Dylan Sies
     */
    public function testAfterRegistrationUserLoggedIn()
    {
        //Save login credentials to check
        $userName = "kenz12345@here.com";

        ////////Navigate to the registration page and register ////////////

        $this->client->request('GET', '/registration');

        $this->crawler = $this->client->getCrawler();

        $this->form = $this->crawler->selectButton('Submit')->form();

        // Invalid first name
        $this->form['member[firstName]']->setValue("Maxxx2");
        $this->form['member[lastName]']->setValue("Smitdsah");
        $this->form['member[userName]']->setValue($userName);
        $this->form['member[password][first]']->setValue("P@ssw0rd");
        $this->form['member[password][second]']->setValue("P@ssw0rd");

        //billing registration
        $this->form['member[addressLineOne]']->setValue("123 Fake Street");
        $this->form['member[addressLineTwo]']->setValue("456 Bake Street");
        $this->form['member[city]']->setValue("Saskatoon");
        $this->form['member[postalCode]']->setValue("S4N 4B7");
        $this->form['member[province]']->setValue("SK");
        $this->form['member[company]']->setValue("");
        $this->form['member[phone]']->setValue("");

        //Testing Individual and 1 year paid membership
        $this->form['member[memberType]']->setValue("Individual");
        $this->form['member[memberOption]']->setValue("1-year Paid Membership");
        $this->form['member[membershipAgreement]']->tick();

        $this->client->submit($this->form);

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        ////////Check to see if they are logged in. ////////////
        //Get the session
        $session = $this->client->getContainer()->get('session');
        $test = $session->get('_security_main');
        $this->assertContains($userName, $session->get('_security_main'));
        $this->assertFalse(empty($session->get('_security_main')));
    }

    /**
     * This function tests to see if they try to log into the website and they have an expired account
     * they will be automatically logged out.
     * @author MacKenzie Wilson & Dylan Sies
     */
    public function testUserIsLoggedOutIfMembershipExpires()
    {
        //Load members into database
        $this->loadFixtures([MemberLoginTestFixture::class]);

        $form = $this->crawler->selectButton('login')->form();

        $form['_username']->setValue("old@member.com");
        $form['_password']->setValue("P@ssw0rd");

        //Submit the login
        $this->client->submit($form);

        $this->client->followRedirect();
        $this->client->followRedirect();
        $this->crawler = $this->client->getCrawler();

        //Make sure redirected to show page
        $this->assertStatusCode(302, $this->client);
        $this->assertEquals($this->client->getCrawler()->getUri(), "http://localhost/logout");

        $session = $this->client->getContainer()->get('session');
        $this->assertTrue(empty($session->get('_security_main')));

    }

    /**
     * This function tests to make sure that if the User's last date paid was exactly one year old
     * that they would get logged out automatically.
     * @author MacKenzie Wilson & Dylan Sies
     */
    public function testUserIsLoggedOutIfLastDatePaidIsExactlyOneYearAgo()
    {
        //Load members into database
        $this->loadFixtures([MemberLoginTestFixture::class]);

        $form = $this->crawler->selectButton('login')->form();

        $form['_username']->setValue("oneYrOld@member.com");
        $form['_password']->setValue("P@ssw0rd");

        //Submit the login
        $this->client->submit($form);

        $this->client->followRedirect();
        $this->client->followRedirect();
        $this->crawler = $this->client->getCrawler();

        //Make sure redirected to logout page
        $this->assertStatusCode(302, $this->client);
        $this->assertEquals($this->client->getCrawler()->getUri(), "http://localhost/logout");

        $session = $this->client->getContainer()->get('session');
        $this->assertTrue(empty($session->get('_security_main')));

    }

    /**
     * This test makes sure that if the user is one second short of the expiry date they
     * they should still be logged in.
     * @author Dylan Sies & MacKenzie Wilson
     */
    public function testUserIsLoggedInWhenCloseToExpiryDate()
    {
        //Load members into database
        $this->loadFixtures([MemberLoginTestFixture::class]);

        $form = $this->crawler->selectButton('login')->form();

        $form['_username']->setValue("almostTooOld@member.com");
        $form['_password']->setValue("P@ssw0rd");

        //Submit the login
        $this->client->submit($form);

        $this->client->followRedirect();
        $this->crawler = $this->client->getCrawler();

        //Make sure redirected to logout page
        $this->assertEquals($this->client->getCrawler()->getUri(), "http://localhost/show/");

        $session = $this->client->getContainer()->get('session');
        $this->assertFalse(empty($session->get('_security_main')));
        $this->assertContains("almostTooOld@member.com", $session->get('_security_main'));
    }




}