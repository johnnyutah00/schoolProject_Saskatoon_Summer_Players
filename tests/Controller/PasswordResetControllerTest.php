<?php
/**
 * Created by PhpStorm.
 * User: cst229
 * Date: 2/4/2019
 * Time: 1:20 PM
 */

use DMore\ChromeDriver\ChromeDriver;
use Behat\Mink\Mink;
use Behat\Mink\Session;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use App\Entity\PasswordReset;
use App\Entity\Member;

class PasswordResetControllerTest extends WebTestCase
{
    private $mink;
    private $driver;
    private $client;

    //NOTE THAT URL IN CREATION OF RESET EMAIL SHOULD BE CHANGED TO ACTUAL WEBSITE URL WHEN PRODUCTION SERVER IS UP

    //start chrome --disable-gpu --headless --remote-debugging-address=0.0.0.0 --remote-debugging-port=9222
    public function setUp()
    {
        $this->loadFixtures(array(
            'App\DataFixtures\AuditionDetailsFixtures',
            'App\DataFixtures\AllShowsFixtures',
            'App\DataFixtures\AppFixtures',
            'App\DataFixtures\AddMembersFixtures',
        ));
    }

    /**
     * 1. Test email exists and successful password change
     * Cory Nagy February 6, 2019
     * @throws
     */
    public function testPasswordChange()
    {

        $client = static::createClient(); //client for accessing db

        // Load Mink
        $mink = new Mink(array(
            'showBrowser' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        // Set the default session name
        $mink->setDefaultSessionName('showBrowser');
        
        $mink->getSession()->visit('http://localhost:8000/');

        /** @var ChromeDriver $driver */
        $driver = $mink->getSession()->getDriver();

        // Target login link button in navigation bar
        $driver->click("//*[@id='loginButton']");

        $driver->click("//*[@id='forgotPasswordLink']");

        $driver->setValue("//*[@id='form_email']", 'Samprj4reset@gmail.com');

        $driver->click("/html/body/form/div/div[2]/button"); //submit password reset

        $this->assertEquals("A password reset request was sent to your email!", $driver->getHtml("//*[@id='passwordResetModal']/div/div/div[2]"));

        $container = $client->getContainer();
        $member = $container->get('doctrine')->getRepository(Member::class)->findOneBy(array('userName' => "samprj4reset@gmail.com"));
        $passwordReset = $container->get('doctrine')->getRepository(PasswordReset::class)->findOneBy(array('member' => $member));

        $hashedValue = $passwordReset->getRecoveryValue();

        $mink->getSession()->visit('http://localhost:8000/password/new?recovervalue=' . $hashedValue);
        $driver = $mink->getSession()->getDriver();

        $driver->setValue("//*[@id='form_password_first']", 'Kitty12');
        $driver->setValue("//*[@id='form_password_second']", 'Kitty12');
        $driver->click("/html/body/form/div/div[2]/button"); //submit

        $this->assertEquals("Your password has been updated!", $driver->getHtml("//*[@id='passwordResetDoneModal']/div/div/div[2]"));

        $client = static::createClient(); //regen the db client for updated data
        $container = $client->getContainer();
        $memberUpdated = $container->get('doctrine')->getRepository(Member::class)->findOneBy(array('userName' => "samprj4reset@gmail.com"));
        $this->assertTrue($member->getPassword() != $memberUpdated->getPassword());

        $passwordReset = $container->get('doctrine')->getRepository(PasswordReset::class)->findOneBy(array('member' => $member));
        $this->assertEquals($passwordReset, null);

        $mink->getSession()->visit('http://localhost:8000/login');
        $driver = $mink->getSession()->getDriver();

        $driver->setValue("//*[@id='_username']", 'samprj4reset@gmail.com'); //mink bypasses the toLowerCase javascript
        $driver->setValue("//*[@id='_password']", 'Kitty12');
        $driver->click("//*[@id='login']");

        $this->assertEquals("http://localhost:8000/show/", $driver->getCurrentUrl());
    }

    /**
     * 2. Test email doesn’t exist and email not sent
     * Cory Nagy February 6, 2019
     * @throws
     */
    public function testEmailDoesNotExist()
    {
        $client = static::createClient(); //client for accessing db

        // Load Mink
        $mink = new Mink(array(
            'showBrowser' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        // Set the default session name
        $mink->setDefaultSessionName('showBrowser');

        $mink->getSession()->visit('http://localhost:8000/');

        /** @var ChromeDriver $driver */
        $driver = $mink->getSession()->getDriver();

        // Target login link button in navigation bar
        $driver->click("//*[@id='loginButton']");

        $driver->click("//*[@id='forgotPasswordLink']");

        $driver->setValue("//*[@id='form_email']", 'gjrotjhoirt@gmail.com');

        $driver->click("/html/body/form/div/div[2]/button"); //submit password reset

        $this->assertTrue(!empty($driver->find("/html/body/form/div/div[1]/div/div/p")));
    }


    /**
     * 3.Test that special characters aren’t accepted in email field.
     * Cory Nagy February 6, 2019
     * @throws
     */
    public function testInvalidEmail()
    {
        $client = static::createClient(); //client for accessing db

        // Load Mink
        $mink = new Mink(array(
            'showBrowser' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        // Set the default session name
        $mink->setDefaultSessionName('showBrowser');

        $mink->getSession()->visit('http://localhost:8000/');

        /** @var ChromeDriver $driver */
        $driver = $mink->getSession()->getDriver();

        // Target login link button in navigation bar
        $driver->click("//*[@id='loginButton']");

        $driver->click("//*[@id='forgotPasswordLink']");

        $driver->setValue("//*[@id='form_email']", '#@%^%#$@#$@#.com');

        $driver->click("/html/body/form/div/div[2]/button"); //submit password reset

        $this->assertEquals("http://localhost:8000/password/reset", $driver->getCurrentUrl());
    }


    /**
     * 4. Test that email without “@” symbol is not valid
     * Cory Nagy February 6, 2019
     * @throws
     */
    public function testMissingEmailSymbol()
    {
        $client = static::createClient(); //client for accessing db

        // Load Mink
        $mink = new Mink(array(
            'showBrowser' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        // Set the default session name
        $mink->setDefaultSessionName('showBrowser');

        $mink->getSession()->visit('http://localhost:8000/');

        /** @var ChromeDriver $driver */
        $driver = $mink->getSession()->getDriver();

        // Target login link button in navigation bar
        $driver->click("//*[@id='loginButton']");

        $driver->click("//*[@id='forgotPasswordLink']");

        $driver->setValue("//*[@id='form_email']", 'gjrotjhoirtgmail.com');

        $driver->click("/html/body/form/div/div[2]/button"); //submit password reset

        $this->assertEquals("http://localhost:8000/password/reset", $driver->getCurrentUrl());
    }


    /**
     * 5. Test that an empty form does not submit
     * Cory Nagy February 6, 2019
     * @throws
     */
    public function testEmptyFormDoesNotSubmit()
    {
        $client = static::createClient(); //client for accessing db

        // Load Mink
        $mink = new Mink(array(
            'showBrowser' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        // Set the default session name
        $mink->setDefaultSessionName('showBrowser');

        $mink->getSession()->visit('http://localhost:8000/');

        /** @var ChromeDriver $driver */
        $driver = $mink->getSession()->getDriver();

        // Target login link button in navigation bar
        $driver->click("//*[@id='loginButton']");

        $driver->click("//*[@id='forgotPasswordLink']");

        $driver->click("/html/body/form/div/div[2]/button"); //submit password reset

        $this->assertEquals("http://localhost:8000/password/reset", $driver->getCurrentUrl());
    }


    /**
     * 6. Test that an email does not exceed max count of 4096
     * Cory Nagy February 6, 2019
     * @throws
     */
    public function testTooLongEmailAddress()
    {
        $client = static::createClient(); //client for accessing db

        // Load Mink
        $mink = new Mink(array(
            'showBrowser' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        // Set the default session name
        $mink->setDefaultSessionName('showBrowser');

        //doesn't break, but no error message is shown

        $mink->getSession()->visit('http://localhost:8000/');

        /** @var ChromeDriver $driver */
        $driver = $mink->getSession()->getDriver();

        // Target login link button in navigation bar
        $driver->click("//*[@id='loginButton']");

        $driver->click("//*[@id='forgotPasswordLink']");

        $test = $driver->getCurrentUrl();
        $driver->setValue("//*[@id='form_email']", str_repeat("a", 5000));

        $driver->click("/html/body/form/div/div[2]/button"); //submit password reset

        $this->assertEquals("http://localhost:8000/password/reset", $driver->getCurrentUrl());
    }


    /**
     * 7. Test that member’s new password doesn’t match after re-entry
     * Cory Nagy February 6, 2019
     * @throws
     */
    public function testIncorrectPasswordReEntry()
    {
        $client = static::createClient(); //client for accessing db

        // Load Mink
        $mink = new Mink(array(
            'showBrowser' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        // Set the default session name
        $mink->setDefaultSessionName('showBrowser');

        $mink->getSession()->visit('http://localhost:8000/');

        /** @var ChromeDriver $driver */
        $driver = $mink->getSession()->getDriver();

        // Target login link button in navigation bar
        $driver->click("//*[@id='loginButton']");

        $driver->click("//*[@id='forgotPasswordLink']");

        $driver->setValue("//*[@id='form_email']", 'Samprj4reset@gmail.com');

        $driver->click("/html/body/form/div/div[2]/button"); //submit password reset

        $this->assertEquals("A password reset request was sent to your email!", $driver->getHtml("//*[@id='passwordResetModal']/div/div/div[2]"));

        $container = $client->getContainer();
        $member = $container->get('doctrine')->getRepository(Member::class)->findOneBy(array('userName' => "samprj4reset@gmail.com"));
        $passwordReset = $container->get('doctrine')->getRepository(PasswordReset::class)->findOneBy(array('member' => $member));

        $hashedValue = $passwordReset->getRecoveryValue();

        $mink->getSession()->visit('http://localhost:8000/password/new?recovervalue=' . $hashedValue);
        $driver = $mink->getSession()->getDriver();

        $driver->setValue("//*[@id='form_password_first']", 'Kitty12');
        $driver->setValue("//*[@id='form_password_second']", 'hjijhty');
        $driver->click("/html/body/form/div/div[2]/button"); //submit

        $this->assertTrue(!empty($driver->find("/html/body/form/div/div[1]/div/div[1]/ul/li")));
        $this->assertEquals("The passwords must match", $driver->getHtml("/html/body/form/div/div[1]/div/div[1]/ul/li"));
    }


    /**
     * 8. Test that member’s new password doesn’t meet length requirements
     * Cory Nagy February 6, 2019
     * @throws
     */
    public function testPasswordTooShort()
    {
        $client = static::createClient(); //client for accessing db

        // Load Mink
        $mink = new Mink(array(
            'showBrowser' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        // Set the default session name
        $mink->setDefaultSessionName('showBrowser');

        $mink->getSession()->visit('http://localhost:8000/');

        /** @var ChromeDriver $driver */
        $driver = $mink->getSession()->getDriver();

        // Target login link button in navigation bar
        $driver->click("//*[@id='loginButton']");

        $driver->click("//*[@id='forgotPasswordLink']");

        $driver->setValue("//*[@id='form_email']", 'Samprj4reset@gmail.com');

        $driver->click("/html/body/form/div/div[2]/button"); //submit password reset

        $this->assertEquals("A password reset request was sent to your email!", $driver->getHtml("//*[@id='passwordResetModal']/div/div/div[2]"));

        $container = $client->getContainer();
        $member = $container->get('doctrine')->getRepository(Member::class)->findOneBy(array('userName' => "samprj4reset@gmail.com"));
        $passwordReset = $container->get('doctrine')->getRepository(PasswordReset::class)->findOneBy(array('member' => $member));

        $hashedValue = $passwordReset->getRecoveryValue();

        $mink->getSession()->visit('http://localhost:8000/password/new?recovervalue=' . $hashedValue);
        $driver = $mink->getSession()->getDriver();

        $driver->setValue("//*[@id='form_password_first']", 'abcde');
        $driver->setValue("//*[@id='form_password_second']", 'abcde');
        $driver->click("/html/body/form/div/div[2]/button"); //submit

        $this->assertTrue(!empty($driver->find("/html/body/form/div/div[1]/div/div[1]/ul/li")));
        $this->assertEquals("Password must contain: An uppercase letter, a lowercase letter, and at least 6 characters",
            $driver->getHtml("/html/body/form/div/div[1]/div/div[1]/ul/li"));
    }

    /**
     * 9. Test that member’s new password doesn’t contain an uppercase letter
     * Cory Nagy February 6, 2019
     * @throws
     */
    public function testPasswordHasNoUpperCase()
    {
        $client = static::createClient(); //client for accessing db

        // Load Mink
        $mink = new Mink(array(
            'showBrowser' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        // Set the default session name
        $mink->setDefaultSessionName('showBrowser');

        $mink->getSession()->visit('http://localhost:8000/');

        /** @var ChromeDriver $driver */
        $driver = $mink->getSession()->getDriver();

        // Target login link button in navigation bar
        $driver->click("//*[@id='loginButton']");

        $driver->click("//*[@id='forgotPasswordLink']");

        $driver->setValue("//*[@id='form_email']", 'Samprj4reset@gmail.com');

        $driver->click("/html/body/form/div/div[2]/button"); //submit password reset

        $this->assertEquals("A password reset request was sent to your email!", $driver->getHtml("//*[@id='passwordResetModal']/div/div/div[2]"));

        $container = $client->getContainer();
        $member = $container->get('doctrine')->getRepository(Member::class)->findOneBy(array('userName' => "samprj4reset@gmail.com"));
        $passwordReset = $container->get('doctrine')->getRepository(PasswordReset::class)->findOneBy(array('member' => $member));

        $hashedValue = $passwordReset->getRecoveryValue();

        $mink->getSession()->visit('http://localhost:8000/password/new?recovervalue=' . $hashedValue);
        $driver = $mink->getSession()->getDriver();

        $driver->setValue("//*[@id='form_password_first']", 'abcdef');
        $driver->setValue("//*[@id='form_password_second']", 'abcdef');
        $driver->click("/html/body/form/div/div[2]/button"); //submit

        $this->assertTrue(!empty($driver->find("/html/body/form/div/div[1]/div/div[1]/ul/li")));
        $this->assertEquals("Password must contain: An uppercase letter, a lowercase letter, and at least 6 characters",
            $driver->getHtml("/html/body/form/div/div[1]/div/div[1]/ul/li"));
    }


    /**
     * 10. Test that an empty new password and re-entry field does not submit
     * Cory Nagy February 6, 2019
     * @throws
     */
    public function testEmptyNewPasswordsDoesNotSubmit()
    {
        $client = static::createClient(); //client for accessing db

        // Load Mink
        $mink = new Mink(array(
            'showBrowser' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        // Set the default session name
        $mink->setDefaultSessionName('showBrowser');

        $mink->getSession()->visit('http://localhost:8000/');

        /** @var ChromeDriver $driver */
        $driver = $mink->getSession()->getDriver();

        // Target login link button in navigation bar
        $driver->click("//*[@id='loginButton']");

        $driver->click("//*[@id='forgotPasswordLink']");

        $driver->setValue("//*[@id='form_email']", 'Samprj4reset@gmail.com');

        $driver->click("/html/body/form/div/div[2]/button"); //submit password reset

        $this->assertEquals("A password reset request was sent to your email!", $driver->getHtml("//*[@id='passwordResetModal']/div/div/div[2]"));

        $container = $client->getContainer();
        $member = $container->get('doctrine')->getRepository(Member::class)->findOneBy(array('userName' => "samprj4reset@gmail.com"));
        $passwordReset = $container->get('doctrine')->getRepository(PasswordReset::class)->findOneBy(array('member' => $member));

        $hashedValue = $passwordReset->getRecoveryValue();

        $mink->getSession()->visit('http://localhost:8000/password/new?recovervalue=' . $hashedValue);
        $driver = $mink->getSession()->getDriver();

        $driver->click("/html/body/form/div/div[2]/button"); //submit

        $this->assertEquals("http://localhost:8000/password/new?recovervalue=".$hashedValue, $driver->getCurrentUrl());
    }


    /**
     * 11. Test that only filling in password without re-entry does not submit
     * Cory Nagy February 6, 2019
     * @throws
     */
    public function testEmptyReEntryDoesNotSubmit()
    {
        $client = static::createClient(); //client for accessing db

        // Load Mink
        $mink = new Mink(array(
            'showBrowser' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        // Set the default session name
        $mink->setDefaultSessionName('showBrowser');

        $mink->getSession()->visit('http://localhost:8000/');

        /** @var ChromeDriver $driver */
        $driver = $mink->getSession()->getDriver();

        // Target login link button in navigation bar
        $driver->click("//*[@id='loginButton']");

        $driver->click("//*[@id='forgotPasswordLink']");

        $driver->setValue("//*[@id='form_email']", 'Samprj4reset@gmail.com');

        $driver->click("/html/body/form/div/div[2]/button"); //submit password reset

        $this->assertEquals("A password reset request was sent to your email!", $driver->getHtml("//*[@id='passwordResetModal']/div/div/div[2]"));

        $container = $client->getContainer();
        $member = $container->get('doctrine')->getRepository(Member::class)->findOneBy(array('userName' => "samprj4reset@gmail.com"));
        $passwordReset = $container->get('doctrine')->getRepository(PasswordReset::class)->findOneBy(array('member' => $member));

        $hashedValue = $passwordReset->getRecoveryValue();

        $mink->getSession()->visit('http://localhost:8000/password/new?recovervalue=' . $hashedValue);
        $driver = $mink->getSession()->getDriver();

        $driver->setValue("//*[@id='form_password_first']", 'Kitty12');
        $driver->click("/html/body/form/div/div[2]/button"); //submit

        $this->assertEquals('http://localhost:8000/password/new?recovervalue=' . $hashedValue, $driver->getCurrentUrl());
    }


    /**
     * 12.Test that only filling in the password re-entry does not submit
     * Cory Nagy February 6, 2019
     * @throws
     */
    public function testEmptyNewPasswordWithReEntryDoesNotSubmit()
    {
        $client = static::createClient(); //client for accessing db

        // Load Mink
        $mink = new Mink(array(
            'showBrowser' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        // Set the default session name
        $mink->setDefaultSessionName('showBrowser');

        $mink->getSession()->visit('http://localhost:8000/');

        /** @var ChromeDriver $driver */
        $driver = $mink->getSession()->getDriver();

        // Target login link button in navigation bar
        $driver->click("//*[@id='loginButton']");

        $driver->click("//*[@id='forgotPasswordLink']");

        $driver->setValue("//*[@id='form_email']", 'Samprj4reset@gmail.com');

        $driver->click("/html/body/form/div/div[2]/button"); //submit password reset

        $this->assertEquals("A password reset request was sent to your email!", $driver->getHtml("//*[@id='passwordResetModal']/div/div/div[2]"));

        $container = $client->getContainer();
        $member = $container->get('doctrine')->getRepository(Member::class)->findOneBy(array('userName' => "samprj4reset@gmail.com"));
        $passwordReset = $container->get('doctrine')->getRepository(PasswordReset::class)->findOneBy(array('member' => $member));

        $hashedValue = $passwordReset->getRecoveryValue();

        $mink->getSession()->visit('http://localhost:8000/password/new?recovervalue=' . $hashedValue);
        $driver = $mink->getSession()->getDriver();

        $driver->setValue("//*[@id='form_password_second']", 'Kitty12');
        $driver->click("/html/body/form/div/div[2]/button"); //submit

        $this->assertEquals('http://localhost:8000/password/new?recovervalue=' . $hashedValue, $driver->getCurrentUrl());
    }


    /**
     * 13.Test that new password doesn’t contain any lowercase letters and doesn’t submit
     * Cory Nagy February 6, 2019
     * @throws
     */
    public function testNoLowerCaseDoesNotSubmit()
    {
        $client = static::createClient(); //client for accessing db

        // Load Mink
        $mink = new Mink(array(
            'showBrowser' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        // Set the default session name
        $mink->setDefaultSessionName('showBrowser');

        $mink->getSession()->visit('http://localhost:8000/');

        /** @var ChromeDriver $driver */
        $driver = $mink->getSession()->getDriver();

        // Target login link button in navigation bar
        $driver->click("//*[@id='loginButton']");

        $driver->click("//*[@id='forgotPasswordLink']");

        $driver->setValue("//*[@id='form_email']", 'Samprj4reset@gmail.com');

        $driver->click("/html/body/form/div/div[2]/button"); //submit password reset

        $this->assertEquals("A password reset request was sent to your email!", $driver->getHtml("//*[@id='passwordResetModal']/div/div/div[2]"));

        $container = $client->getContainer();
        $member = $container->get('doctrine')->getRepository(Member::class)->findOneBy(array('userName' => "samprj4reset@gmail.com"));
        $passwordReset = $container->get('doctrine')->getRepository(PasswordReset::class)->findOneBy(array('member' => $member));

        $hashedValue = $passwordReset->getRecoveryValue();

        $mink->getSession()->visit('http://localhost:8000/password/new?recovervalue=' . $hashedValue);
        $driver = $mink->getSession()->getDriver();

        $driver->setValue("//*[@id='form_password_first']", 'ABCDEF');
        $driver->setValue("//*[@id='form_password_second']", 'ABCDEF');
        $driver->click("/html/body/form/div/div[2]/button"); //submit

        $this->assertTrue(!empty($driver->find("/html/body/form/div/div[1]/div/div[1]/ul/li")));
        $this->assertEquals("Password must contain: An uppercase letter, a lowercase letter, and at least 6 characters",
            $driver->getHtml("/html/body/form/div/div[1]/div/div[1]/ul/li"));
    }
}
