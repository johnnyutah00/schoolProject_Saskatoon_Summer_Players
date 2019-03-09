<?php

namespace App\Tests\Entity;

use App\DataFixtures\AppFixtures;
use Liip\FunctionalTestBundle\Test\WebTestCase;
//use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class MemberControllerTest
 * @package App\Tests\Entity
 * @author Dylan S, Cory N
 * @date 10/25/2018
 *
 * This class contains function tests that will make sure appropriate error messages are shown for a members
 * personal information if the data is invalid.
 */
class MemberControllerTest extends WebTestCase
{
    private $crawler;
    private $client;
    private $form;

    public function setUp()
    {

        $this->client = static::createClient();

        $this->client->request('GET', '/registration');

        $this->crawler = $this->client->getCrawler();

        $this->form = $this->crawler->selectButton('Submit')->form();

        $form = $this->form;
        $crawler = $this->crawler;
        $client = $this->client;


        // Invalid first name
        $form['member[firstName]']->setValue("Maxxx2");
        $form['member[lastName]']->setValue("Smitdsah");
        //$form['member[userName]']->setValue("James" . rand(1, 999999));
        $form['member[userName]']->setValue("Cor" . rand(1,999999) . "@here.com");
        $form['member[password][first]']->setValue("Cory1111");
        $form['member[password][second]']->setValue("Cory1111");

        //billing registration
        $form['member[addressLineOne]']->setValue("123 Fake Street");
        $form['member[addressLineTwo]']->setValue("456 Bake Street");
        $form['member[city]']->setValue("Saskatoon");
        $form['member[postalCode]']->setValue("S4N 4B7");
        //$form['member[country]']->setValue("CA");
        $form['member[province]']->setValue("SK");
        $form['member[company]']->setValue("ajlfalshfklja");
        $form['member[phone]']->setValue("");

        //Testing Individual and 1 year paid membership
        $form['member[memberType]']->setValue("Individual");
        $form['member[memberOption]']->setValue("1-year Paid Membership");
        $form['member[membershipAgreement]']->tick();
    }

    /**
     * This test will make sure that the member registration page exists
     * @author Dylan S, Cory N
     */
    public function testThatMemberRegPageExists()
    {

        $client = static::createClient();

        $client->request('GET', '/registration');


        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     *This test will assert that the form elements are on the registration page
     * @author Dylan S, Cory N
     */
    public function testThatFormElementsAreOnPage()
    {
        $client = $this->client;

        //Assert that the form and its elements are on the registration page
        $this->assertContains('form', $client->getResponse()->getContent());
        $this->assertContains('id="member_firstName"', $client->getResponse()->getContent());
        $this->assertContains('id="member_lastName"', $client->getResponse()->getContent());
        $this->assertContains('id="member_userName"', $client->getResponse()->getContent());
        $this->assertContains('id="member_password_first"', $client->getResponse()->getContent());

        $this->assertContains('id="member_addressLineOne"', $client->getResponse()->getContent());
        $this->assertContains('id="member_addressLineTwo"', $client->getResponse()->getContent());
        $this->assertContains('id="member_city"', $client->getResponse()->getContent());
        $this->assertContains('id="member_postalCode"', $client->getResponse()->getContent());
        $this->assertContains('id="member_province"', $client->getResponse()->getContent());
        $this->assertContains('id="member_company"', $client->getResponse()->getContent());
        $this->assertContains('id="member_phone"', $client->getResponse()->getContent());

    }


    /**
     * This function will test that an error message is shown when the firstname field is too short / blank
     * @author Dylan S, Cory N
     */
    public function testFirstNameTooShort()
    {
        $crawler= $this->crawler;
        $form= $this->form;
        $client = $this->client;

        $this->assertEquals('New Member', $crawler->filter('title')->text());

        // Invalid first name
        $form['member[firstName]']->setValue("");

        $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('<li>First name must be between 1-20 characters', $client->getResponse()->getContent());
    }

    /**
     * This function will test that an error message is shown when the firstname field is over 20 characters
     * @author Dylan S, Cory N
     */
    public function testFirstNameTooLong()
    {
        $crawler= $this->crawler;
        $form= $this->form;
        $client = $this->client;

        $this->assertEquals('New Member', $crawler->filter('title')->text());

        // firstname Beyond 20 Characters
        $form['member[firstName]']->setValue("plokijuhygtfrdeswaqzx");

        $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('<li>First name must be between 1-20 characters', $client->getResponse()->getContent());
    }

    /**
     * This will test that the user is redirected to a new page after entering a valid first name
     * @author Dylan S, Cory N
     */
    public function testFirstNameIsValid()
    {
        $crawler= $this->crawler;
        $form= $this->form;
        $client = $this->client;

        $this->assertEquals('New Member', $crawler->filter('title')->text());

        // Valid first name
        $form['member[firstName]']->setValue("Maxxx2");

        $client->submit($form);

        $client->followRedirect();

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //Assert that we are redirected to a new page and that the page contains the success message
        $this->assertEquals($client->getCrawler()->getUri(), "http://localhost/successful_registration");
        $this->assertContains('Registration success', $client->getResponse()->getContent());
    }


    /**
     * This will test that the user is redirected to a new page after entering a valid last name
     * Dylan S and Cory N
     */
    public function testIsValidLastName()
    {
        $crawler= $this->crawler;
        $form= $this->form;
        $client = $this->client;

        $this->assertEquals('New Member', $crawler->filter('title')->text());

        $form['member[lastName]']->setValue("Smitty99");

        $client->submit($form);
        $client->followRedirect();

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //Assert that we are redirected to a new page and that the page contains the success message
        $this->assertEquals($client->getCrawler()->getUri(), "http://localhost/successful_registration");
        $this->assertContains('Registration success', $client->getResponse()->getContent());
    }

    /**
     * This function will test that an error message is shown when the lastname field is over 20 characters
     * @author Dylan S, Cory N
     */
    public function testLastNameTooLong()
    {
        $crawler= $this->crawler;
        $form= $this->form;
        $client = $this->client;

        $this->assertEquals('New Member', $crawler->filter('title')->text());

        $form['member[lastName]']->setValue("qwertyuiopasdfghjklzx");

        $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('<li>Last name must be between 1-20 characters', $client->getResponse()->getContent());
    }

    /**
     * This function will test that an error message is shown when the lastname field is blank
     * @author Dylan S, Cory N
     */
    public function testLastNameTooShort()
    {
        $crawler= $this->crawler;
        $form= $this->form;
        $client = $this->client;

        $this->assertEquals('New Member', $crawler->filter('title')->text());

        $form['member[lastName]']->setValue("");

        $client->submit($form);


        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('<li>Last name must be between 1-20 characters without spaces</li>', $client->getResponse()->getContent());
    }

    /**
     * This function will test that an error message is shown when an invalid UserName/email has been entered
     *@author Cory N and Dylan S
     */
    public function testUserNameWithSymbol()
    {
        $crawler= $this->crawler;
        $form= $this->form;
        $client = $this->client;

        $this->assertEquals('New Member', $crawler->filter('title')->text());

        $form['member[userName]']->setValue("abc" . rand(1, 999999) . "gmail.com");

        $client->submit($form);


        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('<li>Email must be in standard email format</li>', $client->getResponse()->getContent());
    }

    /**
     * This function will test that an error message is shown when an invalid UserName/email has been entered, no suffix
     *@author Cory N and Dylan S
     */
    public function testUserNameSuffix()
    {
        $crawler= $this->crawler;
        $form= $this->form;
        $client = $this->client;

        $this->assertEquals('New Member', $crawler->filter('title')->text());

        // Entry is missing the suffix .com, .ca , etc...
        $form['member[userName]']->setValue("abc" . rand(1, 999999) . "@gmail");

        $client->submit($form);


        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('<li>Email must be in standard email format</li>', $client->getResponse()->getContent());
    }

    /**
     * This will test that the user is redirected to a new page after entering a valid UserName/email
     * Dylan S and Cory N
     */
    public function testUserNameValid()
    {
        $crawler= $this->crawler;
        $form= $this->form;
        $client = $this->client;

        $this->assertEquals('New Member', $crawler->filter('title')->text());

        // Valid email
        $form['member[userName]']->setValue("abc" . rand(1, 999999) . "@gmail.com");

        $client->submit($form);
        $client->followRedirect();

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //Assert that we are redirected to a new page and that the page contains the success message
        $this->assertEquals($client->getCrawler()->getUri(), "http://localhost/successful_registration");
        $this->assertContains('Registration success', $client->getResponse()->getContent());
    }

    /**
     * This will test that the user is redirected to a new page after entering a valid UserName/email
     * Dylan S and Cory N
     */
    public function testUserNameTooLong()
    {
        $crawler= $this->crawler;
        $form= $this->form;
        $client = $this->client;


        $this->assertEquals('New Member', $crawler->filter('title')->text());


        $form['member[userName]']->setValue("abcplokijuhygtfrdeswaqzsxcdvfgbhnjmklopiuytgftr" . rand(100000, 999999) . "@gmail.com");
        $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());


        $this->assertContains('<li>Email exceeds 50 characters', $client->getResponse()->getContent());
    }

    /**
     * This will test that the user is redirected to a new page after entering a valid UserName/email
     * Dylan S and Cory N
     */
    public function testUserNameRequired()
    {
        $crawler= $this->crawler;
        $form= $this->form;
        $client = $this->client;

        $this->assertEquals('New Member', $crawler->filter('title')->text());


        $form['member[userName]']->setValue("");

        $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());


        $this->assertContains('<li>Email is required', $client->getResponse()->getContent());
    }

    /**
     * Test that email/Username is unique
     * Cory N and Dylan S
     */
    public function testUserNameIsUnique()
    {
        $crawler= $this->crawler;
        $form= $this->form;
        $client = $this->client;


        $this->assertEquals('New Member', $crawler->filter('title')->text());


        $form['member[userName]']->setValue("mymail@gmail.com");

        $client->submit($form);

        $form['member[userName]']->setValue("mymail@gmail.com");

        $client->submit($form);



        $this->assertEquals(200, $client->getResponse()->getStatusCode());


        $this->assertContains('<li>A user with that email already exists', $client->getResponse()->getContent());
    }

    /**
     * This function will test that an error message is shown when a password is too short
     *@author Cory N and Dylan S
     */
    public function testPasswordTooShort()
    {
        $crawler= $this->crawler;
        $form= $this->form;
        $client = $this->client;

        $this->assertEquals('New Member', $crawler->filter('title')->text());


        $form['member[password][first]']->setValue("12345");
        $form['member[password][second]']->setValue("12345");


        $client->submit($form);


        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('<li>Password must consist of 6-20 alpha characters',
            $client->getResponse()->getContent());
    }

    /**
     * This function will test that an error message is shown when a password exceeds the allowed length
     *@author Cory N and Dylan S
     */
    public function testPasswordTooLong()
    {
        $crawler= $this->crawler;
        $form= $this->form;
        $client = $this->client;

        $this->assertEquals('New Member', $crawler->filter('title')->text());


        // Password is beyond 4096 characters
        $passToMatch = "";
        for ($i=0; $i<4098; $i++)
        {
            $passToMatch .= "g";
        }

        $form['member[password][first]']->setValue($passToMatch);
        $form['member[password][second]']->setValue($passToMatch);


        $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('<li>Password must consist of 6-20 alpha characters',
            $client->getResponse()->getContent());
    }

    /**
     * This function will test that an error message is shown when a password without a capital letter has been entered
     * has been entered
     *@author Cory N and Dylan S
     */
    public function testNoUpperCaseInPassword()
    {
        $crawler= $this->crawler;
        $form= $this->form;
        $client = $this->client;

        $this->assertEquals('New Member', $crawler->filter('title')->text());


        // Missing an uppercase
        $form['member[password][first]']->setValue("chocolate1");
        $form['member[password][second]']->setValue("chocolate1");


        $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('<li>Password must consist of one upper-case, one-lower-case, one number',
            $client->getResponse()->getContent());
    }

    /**
     * This function will test that an error message is shown when a password without a number has been entered
     * has been entered
     *@author Cory N and Dylan S
     */
    public function testNoNumberInPassword()
    {
        $crawler= $this->crawler;
        $form= $this->form;
        $client = $this->client;

        $this->assertEquals('New Member', $crawler->filter('title')->text());

        // Missing a number
        $form['member[password][first]']->setValue("Chocolate");
        $form['member[password][second]']->setValue("Chocolate");

        //Testing Individual and 1 year paid membership
        $form['member[memberType]']->setValue("Individual");
        $form['member[memberOption]']->setValue("1-year Paid Membership");

        $client->submit($form);


        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('<li>Password must consist of one upper-case, one-lower-case, one number',
            $client->getResponse()->getContent());
    }

    /**
     * This function will test that an error message is shown when a password without a capital letter has been entered
     * has been entered
     *@author Cory N and Dylan S
     */
    public function testNoLowerCaseInPassword()
    {
        $crawler= $this->crawler;
        $form= $this->form;
        $client = $this->client;

        $this->assertEquals('New Member', $crawler->filter('title')->text());

        $form = $crawler->selectButton('Submit')->form();


        $form['member[password][first]']->setValue("MAXXY34");
        $form['member[password][second]']->setValue("MAXXY34");


        $client->submit($form);


        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('<li>Password must consist of one upper-case, one-lower-case, one number',
            $client->getResponse()->getContent());
    }


    /**
     * This will test that the user is redirected to a new page after entering a valid password
     * Dylan S and Cory N
     */
    public function testValidPassword()
    {
        $crawler= $this->crawler;
        $form= $this->form;
        $client = $this->client;

        $this->assertEquals('New Member', $crawler->filter('title')->text());

        // Valid Password
        $form['member[password][first]']->setValue("Cory007");
        $form['member[password][second]']->setValue("Cory007");

        $client->submit($form);
        $client->followRedirect();

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //Assert that we are redirected to a new page and that the page contains the success message
        $this->assertEquals($client->getCrawler()->getUri(), "http://localhost/successful_registration");
        $this->assertContains('Registration success', $client->getResponse()->getContent());
    }

    /**
     * This functional test will ensure that all items appear on the
     * navigation menu and links are properly redirected from the registration
     * page
     */
    public function testNavigationOnRegistrationPage()
    {
        $client = static::createClient();
        $client->request('GET', '/registration');
        $crawler = $client->getCrawler();

        $this->assertEquals(200, $client->getResponse()->getStatusCode());


        // Verify that we're on the "registration" page
        $currentURI = $crawler->getUri();
        $this->assertEquals('http://localhost/registration', $currentURI);

        // Target the first list item and verify text is "Home"
        $homeLinkText = $crawler->filter('li.nav-item')->eq(0)->text();
        $this->assertEquals('Home', $homeLinkText);

        $homeText = $crawler->filter('li.nav-item')->eq(0)->text();
        $this->assertEquals('Home', $homeText);
        // Verify that the "Home" link is in the navigation bar on the current page
        $homeLink = $crawler->filter('li.nav-item a')->eq(0)->link();
        $this->assertEquals('http://localhost/', $homeLink->getUri());

        $showText = $crawler->filter('li.nav-item')->eq(1)->text();
        $this->assertEquals('Our Shows', $showText);
        // Verify that the "SSPShow" link is in the navigation bar on the current page
        $showLink = $crawler->filter('li.nav-item a')->eq(1)->link();
        $this->assertEquals('http://localhost/show/', $showLink->getUri());

        $registrationText = $crawler->filter('li.nav-item')->eq(2)->text();
        $this->assertEquals('Member Registration', $registrationText);
        // Verify that the "Registration" link is in the navigation bar on the current page
        $regLink = $crawler->filter('li.nav-item a')->eq(2)->link();
        $this->assertEquals('http://localhost/registration', $regLink->getUri());

        $newsText = $crawler->filter('li.nav-item')->eq(3)->text();
        $this->assertEquals('News Letter', $newsText);
        // Verify that the "News Letter" link is in the navigation bar on the current page
        $newsLink = $crawler->filter('li.nav-item a')->eq(3)->link();
        $this->assertEquals('http://localhost/newsletter', $newsLink->getUri());

        $contactText = $crawler->filter('li.nav-item')->eq(4)->text();
        $this->assertEquals('Volunteer', $contactText);
        // Verify that the "Contact Us" link is in the navigation bar on the current page
        $homeLink = $crawler->filter('li.nav-item a')->eq(4)->link();
        $this->assertEquals('http://localhost/volunteer/', $homeLink->getUri());

        $aboutText = $crawler->filter('li.nav-item')->eq(5)->text();
        $this->assertEquals('About Us', $aboutText);
        // Verify that the "About Us" link is in the navigation bar on the current page
        $homeLink = $crawler->filter('li.nav-item a')->eq(5)->link();
        $this->assertEquals('http://localhost/about', $homeLink->getUri());

    }

    /**
     * This test will ensure that the logo redirects back
     * to the home page after clicking on it
     */
    public function testThatLogoRedirects()
    {
        $this->loadFixtures(array(
            'App\DataFixtures\AuditionDetailsFixtures',
            'App\DataFixtures\AllShowsFixtures',
            'App\DataFixtures\AppFixtures',
            'App\DataFixtures\AddMembersFixtures'
        ));
        // Get client to start on the show page
        $client = static::createClient();
        $client->request('GET', '/');
        $crawler = $client->getCrawler();

        // Verify that we're on the home page
        $currentURI = $crawler->getUri();
        $this->assertEquals('http://localhost/', $currentURI);

        // Verify that the logo link redirects properly to home page
        $logo = $crawler->filter('a.navbar-brand')->link();
        $crawler = $client->click($logo);
        $this->assertEquals('http://localhost/', $crawler->getUri());
        $targetText = $crawler->filter('h1')->eq(0)->text();
        $this->assertEquals('Sweeney Todd', $targetText);
    }

    /**
     * This test will make sure tha the donate button will redirect
     * the user to https://www.canadahelps.org/en/dn/12346
     * after clicking on the button from the navigation menu
     */
    public function testThatDonationButtonRedirects()
    {
        // Get client to start on the show page
        $client = static::createClient();
        $client->request('GET', '/show/');
        $crawler = $client->getCrawler();

        // Target donate button in navbar by its ID
        $donateLink = $crawler->filter('a#navDonateButton')->link();
        $crawler = $client->click($donateLink);
        $this->assertEquals('https://www.canadahelps.org/en/dn/12346', $crawler->getUri());

    }



    /**
     * This test will ensure that the user is redirected to the registration page
     * after clicking on the "New Member" link from the navigation menu
     */
    public function testThatUserIsRedirectedToRegistrationPage()
    {
        // Get client to start on the registration page
        $client = static::createClient();
        $client->request('GET', '/');
        $crawler = $client->getCrawler();

        // Target the third list item and verify text is "Member Registration"
        $memberLinkText = $crawler->filter('li.nav-item')->eq(2)->text();
        $this->assertEquals('Member Registration', $memberLinkText);
        // Verify that the "Member Registration" link redirects properly
        $memberLink = $crawler->filter('li.nav-item a')->eq(2)->link();
        $crawler = $client->click($memberLink);
        $this->assertEquals('http://localhost/registration', $crawler->getUri());
        $text = $crawler->filter('h1')->eq(0)->text();
        $this->assertEquals('Member Registration',$text);

    }



    /****************************** Story 2c Payment Information*********************************/
    /**
     *This test will assert that the form elements are on the registration page
     * @author Kate and MacKenzie
     */
    public function testThatMemberTypeAndPaymentOptionElementsAreOnPage()
    {
        $client = static::createClient();

        $client->request('GET', '/registration');

        //Assert that the form and its elements are on the registration page
        $this->assertContains('form', $client->getResponse()->getContent());
        $this->assertContains('id="member_memberType"', $client->getResponse()->getContent());
        $this->assertContains('id="member_memberOption"', $client->getResponse()->getContent());
        //$this->assertContains('id="member_currency"', $client->getResponse()->getContent());
        //$this->assertContains('id="member_paymentType"', $client->getResponse()->getContent());
    }


    /**
     * This test will make sure that all valid options are on the page for the user to select (memberType and memberOption)
     * @author Christopher & Dylan
     */
    public function testThatMemberTypeAndPaymentOptionsAreOnPage()
    {
        $client = static::createClient();

        $client->request('GET', '/registration');


        $this->assertContains('form', $client->getResponse()->getContent());

        //Test that all options are available to be selected on the page
        $this->assertContains('<option value="Individual">Individual</option>', $client->getResponse()->getContent());
        $this->assertContains('<option value="Family">Family</option>', $client->getResponse()->getContent());

        $this->assertContains('<option value="1-year Paid Membership">1-year Membership</option>', $client->getResponse()->getContent());
        $this->assertContains('<option value="Subscription">Auto renew 1-year Membership</option>', $client->getResponse()->getContent());
    }


    /**
     * Tests that the system will submit the form when a valid memberType and memberOption are entered
     * @author Dylan and Chris
     */
    public function testValidMemberTypeAndValidMemberOption()
    {
        $crawler= $this->crawler;
        $form= $this->form;
        $client = $this->client;

        //Testing Individual and 1 year paid membership
        $form['member[memberType]']->setValue("Individual");
        $form['member[memberOption]']->setValue("1-year Paid Membership");

        $client->submit($form);

        $client->followRedirect();

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //Assert that we are redirected to a new page and that the page contains the success message
        $this->assertEquals($client->getCrawler()->getUri(), "http://localhost/successful_registration");
        $this->assertContains('Registration success', $client->getResponse()->getContent());
    }

    /**
     * Tests that the system will submit the form when a valid memberType and memberOption are entered
     * @author Dylan and Chris
     */
    public function testValidMemberTypeAndValidMemberOption2()
    {
        $crawler= $this->crawler;
        $form= $this->form;
        $client = $this->client;

        //Testing Individual and 1 year paid membership with auto-renew (subscription)
        $form['member[memberType]']->setValue("Family");
        $form['member[memberOption]']->setValue("Subscription");

        $client->submit($form);

        $client->followRedirect();

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //Assert that we are redirected to a new page and that the page contains the success message
        $this->assertEquals($client->getCrawler()->getUri(), "http://localhost/successful_registration");
        $this->assertContains('Registration success', $client->getResponse()->getContent());
    }

    /**
     * Assert that an invalid entry for Member List displays an error and does not redirect
     * @author Kate and MacKenzie // Dylan and Chris
     */
    public function testInvalidMemberType()
    {
        $this->expectException(\InvalidArgumentException::class);

        $crawler= $this->crawler;
        $form= $this->form;
        $client = $this->client;

        $form = $crawler->selectButton('Submit')->form();

        //memberType is invalid
        $form['member[memberType]']->setValue("Invalid");
        $form['member[memberOption]']->setValue("Subscription");


        $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('<li>This value is not valid.', $client->getResponse()->getContent());
    }

    /**
     * Assert that an invalid entry for Membership Option displays an error and does not redirect
     * @author Kate and MacKenzie // Dylan and Chris
     */
    public function testInvalidMemberOption()
    {
        $this->expectException(\InvalidArgumentException::class);

        $crawler= $this->crawler;
        $form= $this->form;
        $client = $this->client;

        $form = $crawler->selectButton('Submit')->form();

        //memberoption is invalid
        $form['member[memberType]']->setValue("Family");
        $form['member[memberOption]']->setValue("Invalid");

        $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('<li>This value is not valid.', $client->getResponse()->getContent());
    }

    /**
     * Assert that an invalid entry for Membership Option and Member List displays errors and does not redirect
     * @author Dylan and Chris
     */
    public function testInvalidMemberOptionAndInvalidMemberType()
    {
        $this->expectException(\InvalidArgumentException::class);

        $crawler= $this->crawler;
        $form= $this->form;
        $client = $this->client;
        $form = $crawler->selectButton('Submit')->form();

        //Everything is invalid
        $form['member[memberType]']->setValue("Invalid");
        $form['member[memberOption]']->setValue("Invalid");

        $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('<li>This value is not valid.', $client->getResponse()->getContent());
    }


    /******************************************** 2B -- Billing Information *******************************************/

    /**
     * Assert that the addressLineOne field is required and can not be left empty. Error message should be shown.
     *
     * @author Dylan S, Christopher B
     */
    public function testaddressLineOneRequired()
    {
        $crawler= $this->crawler;
        $form= $this->form;
        $client = $this->client;


        //addressLineOne is left empty
        $form['member[addressLineOne]']->setValue("");

        $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains("Address Line One is required", $client->getResponse()->getContent());
    }

    /**
     * Assert that AddressLineOne field accepts a value one under the maximum value of 100 characters
     * @author Dylan S, Christopher B
     */
    public function testaddressLineOneAlmostTooLong()
    {
        $crawler= $this->crawler;
        $form= $this->form;
        $client = $this->client;


        //addressLineOne is almost too long
        $form['member[addressLineOne]']->setValue("thistextiswaytooolongitsohuldprobablynotgooverabout100charactersmaybeleftstryitoutmyguyyesyesyes");

        $client->submit($form);

        $client->followRedirect();

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //Assert that we are redirected to a new page and that the page contains the success message
        $this->assertEquals($client->getCrawler()->getUri(), "http://localhost/successful_registration");
        $this->assertContains('Registration success', $client->getResponse()->getContent());
    }

    /**
     * Assert that an error message is displayed when the addressLineOne is too long
     * @author Dylan S, Christopher B
     */
    public function testaddressLineOneTooLong()
    {
        $crawler= $this->crawler;
        $form= $this->form;
        $client = $this->client;



        //addressLineOne too long
        $form['member[addressLineOne]']->setValue("thistextiswaytooolongitsohuldprobablynot gooverabout100charactersmaybeleftstryitout myguyyesyesyesyesd");


        $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains("Billing address must be under 100 characters", $client->getResponse()->getContent());
    }


    /**
     * Assert that addressLineTwo field accepts a value one under the maximum value of 100 characters
     * @author MacKenzie W, Kate Z
     */
    public function testaddressLineTwoAlmostTooLong()
    {
        $crawler= $this->crawler;
        $form= $this->form;
        $client = $this->client;


        $form['member[addressLineTwo]']->setValue("thistextiswaytooolongitsohuldprobablynotgooverabout100charactersmaybeleftstryitoutmyguyyesyesyesyes");

        $client->submit($form);

        $client->followRedirect();

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //Assert that we are redirected to a new page and that the page contains the success message
        $this->assertEquals($client->getCrawler()->getUri(), "http://localhost/successful_registration");
        $this->assertContains('Registration success', $client->getResponse()->getContent());
    }

    /**
     * Assert that an error message is displayed when the addressLineTwo is too long
     * @author Kate Z, MacKenzie W
     */
    public function testaddressLineTwoTooLong()
    {
        $crawler= $this->crawler;
        $form= $this->form;
        $client = $this->client;

        $form = $crawler->selectButton('Submit')->form();

        $form['member[addressLineTwo]']->setValue("thistextiswaytooolongitsohuldprobablynot gooverabout100charactersmaybeleftstryitout myguyyesyesyesyesd");

        $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains("AddressLineTwo must be under 100 characters", $client->getResponse()->getContent());
    }

    /**
     * Assert that an error message is shown when nothing is in the City field because it is required
     * @author Dylan S, Christopher B
     */
    public function testCityRequired()
    {
        $crawler= $this->crawler;
        $form= $this->form;
        $client = $this->client;

        $form = $crawler->selectButton('Submit')->form();

        $form['member[city]']->setValue("");

        $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains("City is required", $client->getResponse()->getContent());
    }

    /**
     * Assert that you are redirected to a new page if the city field is almost too long but still ok
     * @author Dylan S, Christopher B
     */
    public function testCityAlmostTooLong()
    {
        $crawler= $this->crawler;
        $form= $this->form;
        $client = $this->client;


        $form['member[city]']->setValue("ajlfalshfkljadhfkasdfhk fdafhjlasdfhlkjsadhfjklashflkshfjlsdahfksdfflafdsakjlfhksfhahfaksfhlaskhfjk");


        $client->submit($form);

        $client->followRedirect();

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //Assert that we are redirected to a new page and that the page contains the success message
        $this->assertEquals($client->getCrawler()->getUri(), "http://localhost/successful_registration");
        $this->assertContains('Registration success', $client->getResponse()->getContent());
    }

    /**
     * Assert that there is an error message that is shown when the City field is too long
     * @author Dylan S, Christopher B
     */
    public function testCityTooLong()
    {
        $crawler= $this->crawler;
        $form= $this->form;
        $client = $this->client;

        $form = $crawler->selectButton('Submit')->form();

        $form['member[city]']->setValue("thistextiswaytooolongitsohuldprobablynot gooverabout100charactersmaybeleftstryitout myguyyesyesyesyesd");

        $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains("City must be under 100 characters", $client->getResponse()->getContent());
    }

    /**
     * Assert that there is an error message shown when there is nothing in the postal code field because it is required
     * @author Dylan S, Christopher B
     */
    public function testPostalCodeRequired()
    {
        $crawler= $this->crawler;
        $form= $this->form;
        $client = $this->client;

        $form = $crawler->selectButton('Submit')->form();

        $form['member[postalCode]']->setValue("");

        $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains("Postal Code is required", $client->getResponse()->getContent());
    }

    /**
     * Assert that an error message is shown when the postal code does not contain a number
     * @author Dylan S, Christopher B
     */
    public function testPostalCodeDoesNotContainANumber()
    {
        $crawler= $this->crawler;
        $form= $this->form;
        $client = $this->client;

        $form = $crawler->selectButton('Submit')->form();

        $form['member[postalCode]']->setValue("ABCABC");

        $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains("Postal Code should be in the format of A2A3B3 or A2A 3B3", $client->getResponse()->getContent());
    }
    /**
     * Assert that an error message is shown when the postal code does not contain a letter
     * @author Dylan S, Christopher B
     */
    public function testPostalCodeDoesNotContainALetter()
    {
        $crawler= $this->crawler;
        $form= $this->form;
        $client = $this->client;

        $form['member[postalCode]']->setValue("222333");


        $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains("Postal Code should be in the format of A2A3B3 or A2A 3B3", $client->getResponse()->getContent());
    }

    /**
     * Assert that you are redirected to the success page when the postal code is almost too long but not quite
     * Boundary test
     * @author Dylan S, Christopher B
     */
    public function testPostalCodeIsAlmostTooLong()
    {
        $crawler= $this->crawler;
        $form= $this->form;
        $client = $this->client;

        $form['member[postalCode]']->setValue("S4N 4B7");

        $client->submit($form);

        $client->followRedirect();

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //Assert that we are redirected to a new page and that the page contains the success message
        $this->assertEquals($client->getCrawler()->getUri(), "http://localhost/successful_registration");
        $this->assertContains('Registration success', $client->getResponse()->getContent());
    }

    /**
     * Assert that an error message is shown when the postal code is too long
     * @author Dylan S, Christopher B
     */
    public function testPostalCodeIsTooLong()
    {
        $crawler= $this->crawler;
        $form= $this->form;
        $client = $this->client;

        $form = $crawler->selectButton('Submit')->form();

        $form['member[postalCode]']->setValue("A7N 0H07");

        $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains("Postal Code should be in the format of A2A3B3 or A2A 3B3", $client->getResponse()->getContent());
    }

    /**
     * Assert that an error message is shown when the postal code is too Short
     * @author Dylan S, Christopher B
     */
    public function testPostalCodeIsTooShort()
    {
        $crawler= $this->crawler;
        $form= $this->form;
        $client = $this->client;

        $form = $crawler->selectButton('Submit')->form();

        $form['member[postalCode]']->setValue("A7N0H");

        $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains("Postal Code should be in the format of A2A3B3 or A2A 3B3", $client->getResponse()->getContent());
    }


    /**
     * Assert that you are redirected to the success page when you select the valid value is entered
     * @author Dylan S, Christopher B, MacKenzie W, Kate Z
     */
    public function testProvinceValidValue()
    {
        $crawler= $this->crawler;
        $form= $this->form;
        $client = $this->client;

        $form['member[province]']->setValue("NL");

        $client->submit($form);

        $client->followRedirect();

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //Assert that we are redirected to a new page and that the page contains the success message
        $this->assertEquals($client->getCrawler()->getUri(), "http://localhost/successful_registration");
        $this->assertContains('Registration success', $client->getResponse()->getContent());
    }

    /**
     * Assert that an error message is shown when you enter an invalid province
     * @expectedException InvalidArgumentException
     * @author Dylan S, Christopher B, Kate Z, MacKenzie W
     */
    public function testProvinceInvalidValue()
    {
        $crawler= $this->crawler;
        $form= $this->form;
        $client = $this->client;

        $form['member[province]']->setValue("aj");


        $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains("Need to enter Province", $client->getResponse()->getContent());

    }
    /**
     * Assert that an error message is shown when you enter an invalid province
     * @expectedException InvalidArgumentException
     * @author Dylan S, Christopher B, Kate Z, MacKenzie W
     */
    public function testProvinceBlankValue()
    {
        $crawler= $this->crawler;
        $form= $this->form;
        $client = $this->client;

        $form = $crawler->selectButton('Submit')->form();

        $form['member[province]']->setValue("");

        $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains("Need to enter Province", $client->getResponse()->getContent());

    }


    /**
     * Assert that you are redirected to the success page when you leave the company field blank because it is not required
     * @author Dylan S, Christopher B
     */
    public function testCompanyNotRequired()
    {
        $crawler= $this->crawler;
        $form= $this->form;
        $client = $this->client;



        $form['member[company]']->setValue("");

        $client->submit($form);

        $client->followRedirect();

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //Assert that we are redirected to a new page and that the page contains the success message
        $this->assertEquals($client->getCrawler()->getUri(), "http://localhost/successful_registration");
        $this->assertContains('Registration success', $client->getResponse()->getContent());
    }

    /**
     * Assert that an error message is shown when the company field is too long
     * @author Dylan S, Christopher B
     */
    public function testCompanyTooLong()
    {
        $crawler= $this->crawler;
        $form= $this->form;
        $client = $this->client;

        $form = $crawler->selectButton('Submit')->form();

        $form['member[company]']->setValue(str_repeat('a', 101));

        $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains("Company is invalid", $client->getResponse()->getContent());
    }

    /**
     * Assert that you are redirected to the success page when you enter the maximum amount of chracters for the company field
     * @author Dylan S, Christopher B
     */
    public function testCompanyAlmostTooLong()
    {
        $crawler= $this->crawler;
        $form= $this->form;
        $client = $this->client;


        $form['member[company]']->setValue(str_repeat('a', 100));

        $client->submit($form);

        $client->followRedirect();

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //Assert that we are redirected to a new page and that the page contains the success message
        $this->assertEquals($client->getCrawler()->getUri(), "http://localhost/successful_registration");
        $this->assertContains('Registration success', $client->getResponse()->getContent());
    }

    /**
     * Assert that you are redirected to the success page when you leave the phone field blank because it is not required
     * @author Dylan S, Christopher B
     */
    public function testPhoneNotRequired()
    {
        $crawler= $this->crawler;
        $form= $this->form;
        $client = $this->client;


        $form['member[phone]']->setValue("");

        $client->submit($form);

        $client->followRedirect();

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //Assert that we are redirected to a new page and that the page contains the success message
        $this->assertEquals($client->getCrawler()->getUri(), "http://localhost/successful_registration");
        $this->assertContains('Registration success', $client->getResponse()->getContent());
    }


    /**
     * Assert that an error message is shown when the phone number is too long
     * @author Dylan S, Christopher B
     */
    public function testPhoneTooLong()
    {
        $crawler= $this->crawler;
        $form= $this->form;
        $client = $this->client;

        $form = $crawler->selectButton('Submit')->form();

        $form['member[phone]']->setValue("(293) 456-78999");

        $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains("Please enter a 10-digit phone number", $client->getResponse()->getContent());
    }

    /**
     * Assert that an error message is shown when the phone number is too short
     * @author Dylan S, Christopher B
     */
    public function testPhoneTooShort()
    {
        $crawler= $this->crawler;
        $form= $this->form;
        $client = $this->client;

        $form['member[phone]']->setValue("(293) 456-789");

        $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains("Please enter a 10-digit phone number", $client->getResponse()->getContent());
    }


    /**
     * Assert that an error message is shown if there are any invalid characters in the phone number
     * @author Dylan S, Christopher B
     */
    public function testPhoneContainsInvalidCharacters()
    {
        $crawler= $this->crawler;
        $form= $this->form;
        $client = $this->client;

        $form['member[phone]']->setValue("(293) 456-78A9");

        $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains("Please enter a 10-digit phone number", $client->getResponse()->getContent());
    }

    /**
     * This test will make sure that a public user can successfully navigate to the terms and conditions page by clicking on the appropriate link
     * @author Nathan, Taylor
     */
    public function testThatTermsAndConditionsPageAccessibleThroughRegistrationPage()
    {
        $crawler= $this->crawler;
        $client = $this->client;

        $link = $crawler->selectLink('Terms and Conditions')->link();
        $crawler = $client->click($link);

        //Assert that the link goes to the terms and conditions page
        $this->assertEquals($crawler->getUri(), "http://localhost/document/1");
        $text = $crawler->filter('h1')->eq(0)->text();
        $this->assertEquals($text, 'Terms and Conditions');
    }

    /**
     * This test will make sure that the terms and conditions page exists
     * @author Nathan, Taylor
     */
    public function testThatTermsAndConditionsPageExists()
    {

        $client = static::createClient();
        $container = $client->getContainer();
        $doctrine = $container->get('doctrine');
        $entityManager = $doctrine->getManager();
        $fixture = new AppFixtures();
        $fixture->load($entityManager);

        $client->request('GET', '/document/1');


        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //compare title on page to expected one
    }

    /**
     * This will test that the user is successfully redirected after a valid member registration is filled out and submitted (agree to terms checkbox selected)
     * @author Nathan, Taylor
     */
    public function testTermsCheckboxSelected()
    {
        $crawler= $this->crawler;
        $form= $this->form;
        $client = $this->client;

        $client->submit($form);

        $client->followRedirect();

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //Assert that we are redirected to a new page and that the page contains the success message
        $this->assertEquals($client->getCrawler()->getUri(), "http://localhost/successful_registration");
        $this->assertContains('Registration success', $client->getResponse()->getContent());
    }

    /**
     * This will test that the user is not redirected after a valid member registration is filled out and submitted (agree to terms checkbox not selected)
     * @author Nathan, Taylor
     */
    public function testFormNotSubmittedIfCheckboxUnchecked()
    {
        $crawler= $this->crawler;
        $form= $this->form;
        $client = $this->client;
        //deselect the membership tick
        $form['member[membershipAgreement]']->untick();
        $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //Assert that we are still on the registration page and the form has not been submitted
        $this->assertEquals($client->getCrawler()->getUri(), "http://localhost/registration");
        $this->assertContains('Member Registration', $client->getResponse()->getContent());
    }

    /**
     *This test will test to see if the checkbox was generated on the page
     * @author Nathan, Taylor
     */
    public function testThatTermsCheckboxIsOnPage()
    {
        $client = static::createClient();

        //get the registration page
        $client->request('GET', '/registration');

        $crawler = $client->getCrawler();

        $this->assertContains('id="member_membershipAgreement"', $client->getResponse()->getContent());
    }


}
