<?php
/**
 * Created by PhpStorm.
 * User: cst229
 * Date: 1/14/2019
 * Time: 3:15 PM
 */

namespace App\Tests\Controller;

use App\DataFixtures\AppFixtures;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use DMore\ChromeDriver\ChromeDriver;
use Behat\Mink\Mink;
use Behat\Mink\Session;


class MemberVolunteerControllerTest extends WebTestCase
{

    public function setUp()
    {
        $this->loadFixtures(array(
            'App\DataFixtures\AuditionDetailsFixtures',
            'App\DataFixtures\AllShowsFixtures',
            'App\DataFixtures\AppFixtures',
            'App\DataFixtures\AddMembersFixtures',
        ));
    }

    /** 1
     * The WebTestCase provides a conveniency method to create an already
     * logged in client using the first parameter of WebTestCase::makeClient()
     *
     * You can log in a user directly from your test method by
     * simply passing an array as the first parameter of WebTestCase::makeClient():
     *
     */
    public function testMemberSuccessfulVolunteer()
    {
        $client = static::createClient();

        $client->request('GET', '/volunteer/');
        $crawler = $client->getCrawler();

        // Target form by using login button
        $loginForm = $crawler->selectButton('Log In')->form();

        // Fill in valid username and password
        $loginForm->setValues([
           '_username' => 'Sam@gmail.com',
            '_password' => 'P@ssw0rd',
        ]);

        // Login
        $client->submit($loginForm);

        // Follow redirect to main page
        $client->followRedirect();
        $crawler = $client->getCrawler();

        $this->assertEquals($client->getCrawler()->getUri(), "http://localhost/volunteer/");

        // Target volunteer submit button
        $volunteerForm = $crawler->selectButton('member_volunteer_Submit')->form();

        // Check off two options and set age
        $volunteerForm['member_volunteer[age]']->setValue(60);
        $volunteerForm['member_volunteer[volunteerOptions][0]']->tick();
        $volunteerForm['member_volunteer[volunteerOptions][2]']->tick();

        // Submit volunteer form
        $client->submit($volunteerForm);
        $client->followRedirect();
        $crawler = $client->getCrawler();
        //Check for the modal content - works as a good test because the modal is never rendered if the form is not successfully submitted
        $successText = $crawler->filter('div.modal-body')->text();
        $this->assertContains("You've Successfully Volunteered!", $successText);
    }

    /** 2
     *
     * Test that the form does not submit when the member has not chosen a field to volunteer in.
     *
     * @authors Taylor, Cory
     */
    public function testMemberVolunteerEmptyPage()
    {
        $client = static::createClient();

        $client->request('GET', '/volunteer/');
        $crawler = $client->getCrawler();

        // Target form by using login button
        $loginForm = $crawler->selectButton('Log In')->form();

        // Fill in valid username and password
        $loginForm->setValues([
            '_username' => 'Sam@gmail.com',
            '_password' => 'P@ssw0rd',
        ]);

        // Login
        $client->submit($loginForm);

        // Follow redirect to main page
        $client->followRedirect();
        $crawler = $client->getCrawler();

        $this->assertEquals($client->getCrawler()->getUri(), "http://localhost/volunteer/");

        // Target volunteer submit button
        $volunteerForm = $crawler->selectButton('member_volunteer_Submit')->form();

        // Submit volunteer form without any selection
        $client->submit($volunteerForm);

        $this->assertContains("You must select at least one option or specify your own.", $client->getResponse()->getContent());
        $this->assertContains("You must enter your age", $client->getResponse()->getContent());
    }

    /** 3
     *
     * Test that the form submits when a member specifies valid additional information.
     *
     * @authors Taylor, Cory
     */
    public function testMemberVolunteerAdditionalInfo()
    {
        $client = static::createClient();

        $client->request('GET', '/volunteer/');
        $crawler = $client->getCrawler();

        // Target form by using login button
        $loginForm = $crawler->selectButton('Log In')->form();

        // Fill in valid username and password
        $loginForm->setValues([
            '_username' => 'Sam@gmail.com',
            '_password' => 'P@ssw0rd',
        ]);

        // Login
        $client->submit($loginForm);

        // Follow redirect to main page
        $client->followRedirect();
        $crawler = $client->getCrawler();

        $this->assertEquals($client->getCrawler()->getUri(), "http://localhost/volunteer/");

        // Target volunteer submit button
        $volunteerForm = $crawler->selectButton('member_volunteer_Submit')->form();

        // Member adds additional information with selection
        $volunteerForm['member_volunteer[age]']->setValue(6);
        $volunteerForm['member_volunteer[volunteerOptions][1]']->tick();
        $volunteerForm['member_volunteer[additionalInfo]']->setValue('I, am a. Veget Arian!');

        // Submit volunteer form
        $client->submit($volunteerForm);

        $client->followRedirect();
        $crawler = $client->getCrawler();

        //Check for the modal content - works as a good test because the modal is never rendered if the form is not successfully submitted
        $successText = $crawler->filter('div.modal-body')->text();
        $this->assertContains("You've Successfully Volunteered!", $successText);

    }

    /** 4
     *
     * Test that the form submits when the member specifies "other" as a volunteer field and provides valid text.
     *
     * @authors Taylor, Cory
     */
    public function testMemberSelectsOtherOptionVolunteer()
    {
        $client = static::createClient();

        $client->request('GET', '/volunteer/');
        $crawler = $client->getCrawler();

        // Target form by using login button
        $loginForm = $crawler->selectButton('Log In')->form();

        // Fill in valid username and password
        $loginForm->setValues([
            '_username' => 'Sam@gmail.com',
            '_password' => 'P@ssw0rd',
        ]);

        // Login
        $client->submit($loginForm);

        // Follow redirect to main page
        $client->followRedirect();
        $crawler = $client->getCrawler();

        $this->assertEquals($client->getCrawler()->getUri(), "http://localhost/volunteer/");

        // Target volunteer submit button
        $volunteerForm = $crawler->selectButton('member_volunteer_Submit')->form();

        // Member adds additional information with selection
        $volunteerForm['member_volunteer[age]']->setValue(6);

        // Member fills in "Other" field and no other selection
        $volunteerForm['member_volunteer[Other]']->setValue('Distribute Flyers');

        // Submit volunteer form
        $client->submit($volunteerForm);
        $client->followRedirect();
        $crawler = $client->getCrawler();

        //Check for the modal content - works as a good test because the modal is never rendered if the form is not successfully submitted
        $successText = $crawler->filter('div.modal-body')->text();
        $this->assertContains("You've Successfully Volunteered!", $successText);
    }

    /** 5
     *
     * Test that the volunteer page is not loaded for a public user and they are redirected to the registration page when the click the register button.
     * start chrome --disable-gpu --headless --remote-debugging-address=0.0.0.0 --remote-debugging-port=9222
     * @authors Taylor, Cory
     * @throws
     */
    public function testPublicVolunteer()
    {

        // Load ChromeDriver
        $mink = new Mink(array(
            'BeforeLogin' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        // set the default session name
        $mink->setDefaultSessionName('BeforeLogin');

        // visit homepage
        $mink->getSession()->visit('http://localhost:8000/volunteer');

        /** @var ChromeDriver $driver */
        $driver = $mink->getSession()->getDriver();

        $isVisible = $driver->isVisible("/html/body/h1");
        $this->assertTrue($isVisible);

        $headerText = $driver->getHtml("/html/body/h1");
        $this->assertEquals("You must log in to access this page.", $headerText);

        // Load client
        $client = static::createClient();

        $client->request('GET', '/volunteer/');
        $crawler = $client->getCrawler();

        // Target  button
        $becomeMemberLink = $crawler->filter("a#becomeMember")->link();
        $crawler = $client->click($becomeMemberLink);
        $crawler = $client->getCrawler();

        $registrationText = $crawler->filter("h1")->text();
        $this->assertEquals("Member Registration", $registrationText);
        $this->assertEquals("http://localhost/registration",$client->getCrawler()->getUri());

    }

    /** 6
     * @authors Taylor, Cory
     * @throws
     */
    public function testMemberAttemptsToVolunteer()
    {
        // Load ChromeDriver
        $mink = new Mink(array(
            'BeforeLogin' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        // set the default session name
        $mink->setDefaultSessionName('BeforeLogin');

        // visit homepage
        $mink->getSession()->visit('http://localhost:8000/volunteer');

        /** @var ChromeDriver $driver */
        $driver = $mink->getSession()->getDriver();

        $isVisible = $driver->isVisible("/html/body/h1");
        $this->assertTrue($isVisible);

        $headerText = $driver->getHtml("/html/body/h1");
        $this->assertEquals("You must log in to access this page.", $headerText);

        $driver->setValue('//*[@id="userNameEmail"]', "Sam@gmail.com");
        $driver->setValue('//*[@id="passWord"]', "P@ssw0rd");
        $driver->click('/html/body/div[1]/form/button');

        // User is not logged in, so do no redirect to member/member_volunteer
        $this->assertEquals($driver->getCurrentUrl(), "http://localhost:8000/volunteer/");

        $headerText = $driver->getText('//*[@id="title"]');
        $this->assertEquals("Volunteer", $headerText);
        $isVisible = $driver->isVisible('//*[@id="title"]');
        $this->assertTrue($isVisible);

        $volunteerButtonText = $driver->getText('//*[@id="member_volunteer_Submit"]');
        $this->assertEquals("Volunteer!", $volunteerButtonText);
        $isVisible = $driver->isVisible('//*[@id="member_volunteer_Submit"]');
        $this->assertTrue($isVisible);

    }

    /** 7
     *
     * Test that the form submits if an option is selected and the 'other' field is empty (as it should be)
     *
     * @authors Taylor, Cory
     */
    public function testOtherFieldEmptySubmit()
    {
        $client = static::createClient();

        $client->request('GET', '/volunteer/');
        $crawler = $client->getCrawler();

        // Target form by using login button
        $loginForm = $crawler->selectButton('Log In')->form();

        // Fill in valid username and password
        $loginForm->setValues([
            '_username' => 'Sam@gmail.com',
            '_password' => 'P@ssw0rd',
        ]);

        // Login
        $client->submit($loginForm);

        // Follow redirect to main page
        $client->followRedirect();
        $crawler = $client->getCrawler();

        $this->assertEquals($client->getCrawler()->getUri(), "http://localhost/volunteer/");

        // Target volunteer submit button
        $volunteerForm = $crawler->selectButton('member_volunteer_Submit')->form();

        // Check off two options and set age
        $volunteerForm['member_volunteer[age]']->setValue(6);
        $volunteerForm['member_volunteer[volunteerOptions][0]']->tick();


        // Submit volunteer form
        $client->submit($volunteerForm);
        $client->followRedirect();
        $crawler = $client->getCrawler();
        //Check for the modal content - works as a good test because the modal is never rendered if the form is not successfully submitted
        $successText = $crawler->filter('div.modal-body')->text();
        $this->assertContains("You've Successfully Volunteered!", $successText);

    }

    /** 8
     *
     * Test that the form does not submit when the member has entered too many characters in the "other" field
     *
     * @authors Taylor, Cory
     */
    public function testTooManyCharsOtherField()
    {
        $client = static::createClient();

        $client->request('GET', '/volunteer/');
        $crawler = $client->getCrawler();

        // Target form by using login button
        $loginForm = $crawler->selectButton('Log In')->form();

        // Fill in valid username and password
        $loginForm->setValues([
            '_username' => 'Sam@gmail.com',
            '_password' => 'P@ssw0rd',
        ]);

        // Login
        $client->submit($loginForm);

        // Follow redirect to main page
        $client->followRedirect();
        $crawler = $client->getCrawler();

        $this->assertEquals($client->getCrawler()->getUri(), "http://localhost/volunteer/");

        // Target volunteer submit button
        $volunteerForm = $crawler->selectButton('member_volunteer_Submit')->form();

        // Member fills in "Other" field with 26 characters
        $volunteerForm['member_volunteer[age]']->setValue(6);
        $volunteerForm['member_volunteer[Other]']->setValue('abcdefghijklmnopqrstuvwxyz');

        // Submit volunteer form
        $client->submit($volunteerForm);
        $crawler = $client->getCrawler();

        // Error message
        $errText = $crawler->filter('div#errors')->text();

        // Text to assert
        $this->assertContains("Please let us know in less than 25 characters!", $errText);
    }

    /** 9
     *
     * Test that the form does not submit when speciel characters are entered in the "other" field
     * @authors Taylor, Cory
     */
    public function testSpecialCharactersOtherField()
    {
        $client = static::createClient();

        $client->request('GET', '/volunteer/');
        $crawler = $client->getCrawler();

        // Target form by using login button
        $loginForm = $crawler->selectButton('Log In')->form();

        // Fill in valid username and password
        $loginForm->setValues([
            '_username' => 'Sam@gmail.com',
            '_password' => 'P@ssw0rd',
        ]);

        // Login
        $client->submit($loginForm);

        // Follow redirect to main page
        $client->followRedirect();
        $crawler = $client->getCrawler();

        $this->assertEquals($client->getCrawler()->getUri(), "http://localhost/volunteer/");

        // Target volunteer submit button
        $volunteerForm = $crawler->selectButton('member_volunteer_Submit')->form();

        // Member fills in "Other" field with 26 characters
        $volunteerForm['member_volunteer[age]']->setValue(6);
        $volunteerForm['member_volunteer[Other]']->setValue('@$%');

        // Submit volunteer form
        $client->submit($volunteerForm);
        $crawler = $client->getCrawler();

        // Error message
        $errText = $crawler->filter('div#errors')->text();

        // Text to assert
        $this->assertContains("Characters like *%^$@& aren't allowed here", $errText);
    }

    /** 10
     *
     * Test that the form does not submit when special characters are entered in the additional information box
     *
     * @authors Taylor, Cory
     */
    public function testSpecialCharactersAdditionalInfo()
    {
        $client = static::createClient();

        $client->request('GET', '/volunteer/');
        $crawler = $client->getCrawler();

        // Target form by using login button
        $loginForm = $crawler->selectButton('Log In')->form();

        // Fill in valid username and password
        $loginForm->setValues([
            '_username' => 'Sam@gmail.com',
            '_password' => 'P@ssw0rd',
        ]);

        // Login
        $client->submit($loginForm);

        // Follow redirect to main page
        $client->followRedirect();
        $crawler = $client->getCrawler();

        $this->assertEquals($client->getCrawler()->getUri(), "http://localhost/volunteer/");

        // Target volunteer submit button
        $volunteerForm = $crawler->selectButton('member_volunteer_Submit')->form();

        // Member fills in "Other" field with 26 characters
        $volunteerForm['member_volunteer[age]']->setValue(6);
        $volunteerForm['member_volunteer[Other]']->setValue('Nice');
        $volunteerForm['member_volunteer[additionalInfo]']->setValue('%^');
        // Submit volunteer form
        $client->submit($volunteerForm);
        $crawler = $client->getCrawler();

        // Error message
        $errText = $client->getResponse()->getContent();

        // Text to assert
        $this->assertContains("Characters like *%^$@&amp; aren&#039;t allowed here", $errText);
    }

    /** 11
     *
     * Test that the form does not submit when too many characters are entered in the additional information box.
     *
     * @authors Taylor, Cory
     */
    public function testTooManyCharsAdditionalInfo()
    {
        $client = static::createClient();

        $client->request('GET', '/volunteer/');
        $crawler = $client->getCrawler();

        // Target form by using login button
        $loginForm = $crawler->selectButton('Log In')->form();

        // Fill in valid username and password
        $loginForm->setValues([
            '_username' => 'Sam@gmail.com',
            '_password' => 'P@ssw0rd',
        ]);

        // Login
        $client->submit($loginForm);

        // Follow redirect to main page
        $client->followRedirect();
        $crawler = $client->getCrawler();

        $this->assertEquals($client->getCrawler()->getUri(), "http://localhost/volunteer/");

        // Target volunteer submit button
        $volunteerForm = $crawler->selectButton('member_volunteer_Submit')->form();

        // Member fills in "Other" field with 26 characters
        $volunteerForm['member_volunteer[age]']->setValue(6);
        $volunteerForm['member_volunteer[Other]']->setValue('Nice');
        $volunteerForm['member_volunteer[additionalInfo]']->setValue(str_repeat('a',251));
        // Submit volunteer form
        $client->submit($volunteerForm);
        $crawler = $client->getCrawler();

        // Error message
        $errText = $client->getResponse()->getContent();

        // Text to assert
        $this->assertContains("Please keep additional information 250 characters or less.", $errText);
    }

    /**
     * 12
     */
    public function testNoAdditionalInfoSubmit()
    {
        $client = static::createClient();

        $client->request('GET', '/volunteer/');
        $crawler = $client->getCrawler();

        // Target form by using login button
        $loginForm = $crawler->selectButton('Log In')->form();

        // Fill in valid username and password
        $loginForm->setValues([
            '_username' => 'Sam@gmail.com',
            '_password' => 'P@ssw0rd',
        ]);

        // Login
        $client->submit($loginForm);

        // Follow redirect to main page
        $client->followRedirect();
        $crawler = $client->getCrawler();

        $this->assertEquals($client->getCrawler()->getUri(), "http://localhost/volunteer/");

        // Target volunteer submit button
        $volunteerForm = $crawler->selectButton('member_volunteer_Submit')->form();

        // Check off two options and set age
        $volunteerForm['member_volunteer[age]']->setValue(6);
        $volunteerForm['member_volunteer[volunteerOptions][0]']->tick();
        $volunteerForm['member_volunteer[volunteerOptions][2]']->tick();

        // Submit volunteer form
        $client->submit($volunteerForm);
        $client->followRedirect();
        $crawler = $client->getCrawler();
        //Check for the modal content - works as a good test because the modal is never rendered if the form is not successfully submitted
        $successText = $crawler->filter('div.modal-body')->text();
        $this->assertContains("You've Successfully Volunteered!", $successText);
    }
}
