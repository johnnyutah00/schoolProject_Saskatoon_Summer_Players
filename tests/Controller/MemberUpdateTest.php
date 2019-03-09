<?php
/**
 * User: CST221
 * Date: 1/24/2019
 * SERVER LOGIC BLOCK
 */

namespace App\Tests\Controller;

use app\Entity\Member;

use DMore\ChromeDriver\ChromeDriver;
use Behat\Mink\Mink;
use Behat\Mink\Session;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use function PHPSTORM_META\elementType;
use WebDriver\Exception;

class MemberUpdateTest extends WebTestCase
{
    private $fixtures;

    /**
     * Set up will load fixtures and by default log you in as a board member
     * @throws
     */
    public function setUp()
    {
        $this->fixtures = $this->loadFixtures(array(
           'App\DataFixtures\AddOnlinePollsFixtures',
            'App\DataFixtures\MemberLoginTestFixture',
            'App\DataFixtures\AddMembersFixtures'
        ));

    }

    /**
     * This will test that a Board Member can see the "View Profile" button and is redirected to their profile page
     * @author Kate Zawada and Christopher Boechler
     *
     * @throws
     */
    public function testThatViewProfileButtonRedirectsProperlyForBM()
    {
        //LOGIN AS A BOARD MEMBER
        $mink = new Mink(array(
            'MemberProfile' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        $mink->setDefaultSessionName('MemberProfile');
        $mink->getSession()->visit('http://localhost:8000/login');
        $driver = $mink->getSession()->getDriver();
        $driver->setValue("//*[@id=\"_username\"]", "bmember@member.com");
        $driver->setValue("//*[@id=\"_password\"]", "P@ssw0rd");
        $driver->click("//*[@id=\"login\"]");

        //click on the link to go to the Profile page
        $driver->click("//a[@id='ViewProfile']");

        //verify that the header is for the correct page
        $profile = $driver->getHtml("xpath");
        $this->assertEquals('Profile', $profile);
    }

    /**
     * This will test that a General Manager can see the "View Profile" button and is redirected to their profile page
     * @author Kate Zawada and Christopher Boechler
     *
     * @throws
     */
    public function testThatViewProfileButtonRedirectsProperlyForGM()
    {
        //LOGIN AS A BOARD MEMBER
        $mink = new Mink(array(
            'MemberProfile' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        $mink->setDefaultSessionName('MemberProfile');
        $mink->getSession()->visit('http://localhost:8000/login');
        $driver = $mink->getSession()->getDriver();
        $driver->setValue("//*[@id=\"_username\"]", "gmember@member.com");
        $driver->setValue("//*[@id=\"_password\"]", "P@ssw0rd");
        $driver->click("//*[@id=\"login\"]");

        //click the link to go to the Profile page
        $driver->click("//a[@id='ViewProfile']");

        //Verify that the header is for the correct page
        $profile = $driver->getHtml("xpath");
        $this->assertEquals('Profile', $profile);
    }

    /**
     * This will test that a Member can see the "View Profile" button and is redirected to their profile page
     * @author Kate Zawada and Christopher Boechler
     *
     * @throws
     */
    public function testThatViewProfileButtonRedirectsProperlyForMember()
    {
        //LOGIN AS A BOARD MEMBER
        $mink = new Mink(array(
            'MemberProfile' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        $mink->setDefaultSessionName('MemberProfile');
        $mink->getSession()->visit('http://localhost:8000/login');
        $driver = $mink->getSession()->getDriver();
        $driver->setValue("//*[@id=\"_username\"]", "member@member.com");
        $driver->setValue("//*[@id=\"_password\"]", "P@ssw0rd");
        $driver->click("//*[@id=\"login\"]");

        //click the link to go to the Profile page
        $driver->click("//a[@id='ViewProfile']");

        //Verify that the header is for the correct page
        $profile = $driver->getHtml("xpath");
        $this->assertEquals('Profile', $profile);
    }

    /**
     * This will test that public (not logged in) users cannot see the "View Profile" button and cannot
     * access the profile page
     * @author Kate Zawada and Christopher Boechler
     *
     * @throws
     */
    public function testThatPublicUserCannotSeeViewProfileButtonOrAccessProfile()
    {
        //LOGIN AS A BOARD MEMBER
        $mink = new Mink(array(
            'MemberProfile' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        $mink->setDefaultSessionName('MemberProfile');
        $mink->getSession()->visit('http://localhost:8000');
        $driver = $mink->getSession()->getDriver();

        //click the link to go to the Profile page
        $isVisible = $driver->isVisible("//*[@id=\"ViewProfile\"]");
        $this->assertFalse($isVisible);

        //visit Profile Page MANUALLY
        $mink->getSession()->visit('http://localhost:8000/member/Member_Profile');

        //compare that the error on the page matches the error we want to display
        $notRedirected = $driver->getHtml("/html/body/div[2]/div/div/p");
        $this->assertEquals('Now Playing', $notRedirected);
    }

    /**
     * This will test that a member can update their first name
     * @author Kate Zawada and Christopher Boechler
     *
     * @throws
     */
    public function testValidFirstNameUpdated()
    {
        //LOGIN AS A MEMBER
        $mink = new Mink(array(
            'MemberProfile' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        $mink->setDefaultSessionName('MemberProfile');
        $mink->getSession()->visit('http://localhost:8000/login');
        $driver = $mink->getSession()->getDriver();
        $driver->setValue("//*[@id=\"_username\"]", "DrewSmith@mail.com");
        $driver->setValue("//*[@id=\"_password\"]", "P@ssw0rd");
        $driver->click("//*[@id=\"login\"]");

        //visit the profile
        $driver->click("//a[@id='Profile']");
        // Click the "Edit Information" button
        $driver->click("EditInfoxPath");

        //enter a valid first name in the first name field
        $driver->setValue("//*[@id=\"XPATH\"]","Megan");

        $driver->click("//*[@id=\"Save\"]/button");

        $profilePage = $driver->getHtml("XPATHPROFILE");

        // Assert that the member's profile page has been updated with the new name
        $this->assertEquals("Megan Smith", $profilePage);

    }

    /**
     * This will test that a member cannot update their first name with an invalid value
     * @author Kate Zawada and Christopher Boechler
     *
     * @throws
     */
    public function testInvalidFirstNameNotUpdated()
    {
        //LOGIN AS A MEMBER
        $mink = new Mink(array(
            'MemberProfile' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        $mink->setDefaultSessionName('MemberProfile');
        $mink->getSession()->visit('http://localhost:8000/login');
        $driver = $mink->getSession()->getDriver();
        $driver->setValue("//*[@id=\"_username\"]", "DrewSmith@mail.com");
        $driver->setValue("//*[@id=\"_password\"]", "P@ssw0rd");
        $driver->click("//*[@id=\"login\"]");

        //visit the profile
        $driver->click("//a[@id='Profile']");
        // Click the "Edit Information" button
        $driver->click("EditInfoxPath");

        //enter a invalid first name in the first name field
        $driver->setValue("//*[@id=\"XPATH\"]","ThisNameIsFarToLongSorryToBeTheOneToTellYou");

        //click away from the field
        $driver->click("xpath");

        //check that error message is visible
        $errorMessage = $driver->isVisible("/html/body/form/div[1]/div[1]/ul/li");
        $this->assertTrue($errorMessage);

        $driver->click("//*[@id=\"Save\"]/button");

        //check that error message is visible after clicking the save button
        $errorMessage2 = $driver->isVisible("/html/body/form/div[1]/div[1]/ul/li");
        $this->assertTrue($errorMessage2);
    }

    /**
     * This will test that a member can update their last name
     * @author Kate Zawada and Christopher Boechler
     *
     * @throws
     */
    public function testValidLastNameUpdated()
    {
        //LOGIN AS A MEMBER
        $mink = new Mink(array(
            'MemberProfile' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        $mink->setDefaultSessionName('MemberProfile');
        $mink->getSession()->visit('http://localhost:8000/login');
        $driver = $mink->getSession()->getDriver();
        $driver->setValue("//*[@id=\"_username\"]", "DrewSmith@mail.com");
        $driver->setValue("//*[@id=\"_password\"]", "P@ssw0rd");
        $driver->click("//*[@id=\"login\"]");

        //visit the profile
        $driver->click("//a[@id='Profile']");
        // Click the "Edit Information" button
        $driver->click("EditInfoxPath");

        //enter a valid last name in the last name field
        $driver->setValue("//*[@id=\"XPATH\"]","Dough");

        $driver->click("//*[@id=\"Save\"]/button");

        $validLastName = $driver->getHtml("XPATHPROFILE");

        // Assert that the member's profile page has been updated with the new name
        $this->assertEquals("Megan Dough", $validLastName);
    }

    /**
     * This will test that a member cannot update their last name with an invalid value
     * @author Kate Zawada and Christopher Boechler
     *
     * @throws
     */
    public function testInvalidLastNameNotUpdated()
    {
        //LOGIN AS A MEMBER
        $mink = new Mink(array(
            'MemberProfile' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        $mink->setDefaultSessionName('MemberProfile');
        $mink->getSession()->visit('http://localhost:8000/login');
        $driver = $mink->getSession()->getDriver();
        $driver->setValue("//*[@id=\"_username\"]", "DrewSmith@mail.com");
        $driver->setValue("//*[@id=\"_password\"]", "P@ssw0rd");
        $driver->click("//*[@id=\"login\"]");

        //visit the profile
        $driver->click("//a[@id='Profile']");
        // Click the "Edit Information" button
        $driver->click("EditInfoxPath");

        //enter a invalid first name in the last name field
        $driver->setValue("//*[@id=\"XPATH\"]","ThisValueIsFarToLongSorryToBeTheOneToTellYou");

        //click away from the field
        $driver->click("xpath");

        //check that error message is visible
        $errorMessage = $driver->isVisible("/html/body/form/div[1]/div[1]/ul/li");
        $this->assertTrue($errorMessage);

        $driver->click("//*[@id=\"Save\"]/button");

        //check that error message is visible after clicking the save button
        $errorMessage2 = $driver->isVisible("/html/body/form/div[1]/div[1]/ul/li");
        $this->assertTrue($errorMessage2);
    }

    /**
     * This will test that a member can update their username (email)
     * @author Kate Zawada and Christopher Boechler
     *
     * @throws
     */
    public function testValidUsernameUpdated()
    {
        //LOGIN AS A MEMBER
        $mink = new Mink(array(
            'MemberProfile' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        $mink->setDefaultSessionName('MemberProfile');
        $mink->getSession()->visit('http://localhost:8000/login');
        $driver = $mink->getSession()->getDriver();
        $driver->setValue("//*[@id=\"_username\"]", "DrewSmith@mail.com");
        $driver->setValue("//*[@id=\"_password\"]", "P@ssw0rd");
        $driver->click("//*[@id=\"login\"]");

        //visit the profile
        $driver->click("//a[@id='Profile']");
        // Click the "Edit Information" button
        $driver->click("EditInfoxPath");

        //enter a valid username in the username field
        $driver->setValue("//*[@id=\"XPATH\"]","MeganDough@mail.com");

        $driver->click("//*[@id=\"Save\"]/button");

        $validEmail = $driver->getHtml("XPATHPROFILE");

        // Assert that the member's profile page has been updated with the new username
        $this->assertEquals("Megan Dough", $validEmail);
    }

    /**
     * This will test that a member cannot update their username (email) with an invalid value
     * @author Kate Zawada and Christopher Boechler
     *
     * @throws
     */
    public function testInvalidUsernameNotUpdated()
    {
        //LOGIN AS A MEMBER
        $mink = new Mink(array(
            'MemberProfile' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        $mink->setDefaultSessionName('MemberProfile');
        $mink->getSession()->visit('http://localhost:8000/login');
        $driver = $mink->getSession()->getDriver();
        $driver->setValue("//*[@id=\"_username\"]", "DrewSmith@mail.com");
        $driver->setValue("//*[@id=\"_password\"]", "P@ssw0rd");
        $driver->click("//*[@id=\"login\"]");

        //visit the profile
        $driver->click("//a[@id='Profile']");
        // Click the "Edit Information" button
        $driver->click("EditInfoxPath");

        //enter a invalid user name in the user name field
        $driver->setValue("//*[@id=\"XPATH\"]","ThisIsNotValid");

        //click away from the field
        $driver->click("xpath");

        //check that error message is visible
        $errorMessage = $driver->isVisible("/html/body/form/div[1]/div[1]/ul/li");
        $this->assertTrue($errorMessage);

        $driver->click("//*[@id=\"Save\"]/button");

        //check that error message is visible after clicking the save button
        $errorMessage2 = $driver->isVisible("/html/body/form/div[1]/div[1]/ul/li");
        $this->assertTrue($errorMessage2);
    }

    /**
     * This will test that a member can update their password
     * @author Kate Zawada and Christopher Boechler
     *
     * @throws
     */
    public function testValidPasswordUpdated()
    {
        //LOGIN AS A MEMBER
        $mink = new Mink(array(
            'MemberProfile' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        $mink->setDefaultSessionName('MemberProfile');
        $mink->getSession()->visit('http://localhost:8000/login');
        $driver = $mink->getSession()->getDriver();
        $driver->setValue("//*[@id=\"_username\"]", "DrewSmith@mail.com");
        $driver->setValue("//*[@id=\"_password\"]", "P@ssw0rd");
        $driver->click("//*[@id=\"login\"]");

        //visit the profile
        $driver->click("//a[@id='Profile']");
        // Click the "Change Password button" button
        $driver->click("ChangePasswordXPATH");

        //enter a valid old password in the old password field
        $driver->setValue("//*[@id=\"XPATH\"]","P@ssw0rd");

        //enter a valid new password
        $driver->setValue("//*[@id=\"XPATH\"]","N3wP@ssw0rd");

        //reenter valid new password
        $driver->setValue("//*[@id=\"XPATH\"]","N3wP@ssw0rd");

        $driver->click("//*[@id=\"Save\"]/button");

        //have the user logout
        $driver->click("LOGOUTXPATH");

        //relogin as the same user but with the new password
        $mink->getSession()->visit('http://localhost:8000/login');
        $driver = $mink->getSession()->getDriver();
        $driver->setValue("//*[@id=\"_username\"]", "DrewSmith@mail.com");
        $driver->setValue("//*[@id=\"_password\"]", "N3wP@ssw0rd");
        $driver->click("//*[@id=\"login\"]");

        //check that the user can see the ViewProfile button
        $isVisible = $driver->isVisible("//*[@id=\"ViewProfile\"]");
        $this->assertTrue($isVisible);


    }

    /**
     * This will test that a member cannot update their password with an invalid value
     * @author Kate Zawada and Christopher Boechler
     *
     * @throws
     */
    public function testInvalidPasswordNotUpdated()
    {
        //LOGIN AS A MEMBER
        $mink = new Mink(array(
            'MemberProfile' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        $mink->setDefaultSessionName('MemberProfile');
        $mink->getSession()->visit('http://localhost:8000/login');
        $driver = $mink->getSession()->getDriver();
        $driver->setValue("//*[@id=\"_username\"]", "DrewSmith@mail.com");
        $driver->setValue("//*[@id=\"_password\"]", "P@ssw0rd");
        $driver->click("//*[@id=\"login\"]");

        //visit the profile
        $driver->click("//a[@id='Profile']");
        // Click the "Change Password button" button
        $driver->click("ChangePasswordXPATH");

        //enter a valid old password in the old password field
        $driver->setValue("//*[@id=\"XPATH\"]","P@ssw0rd");

        //enter a invalid new password
        $driver->setValue("//*[@id=\"XPATH\"]","password");

        //reenter valid new password
        $driver->setValue("//*[@id=\"XPATH\"]","password");

        //check that error message is visible
        $errorMessage = $driver->isVisible("/html/body/form/div[1]/div[1]/ul/li");
        $this->assertTrue($errorMessage);

    }

    /**
     * This will test that the password is not updated when a member incorrectly enters in their old password
     * incorrectly
     * @author Kate Zawada and Christopher Boechler
     *
     * @throws
     */
    public function testThatPasswordIsNotUpdatedIfOldPasswordIsInvalid()
    {
        //LOGIN AS A MEMBER
        $mink = new Mink(array(
            'MemberProfile' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        $mink->setDefaultSessionName('MemberProfile');
        $mink->getSession()->visit('http://localhost:8000/login');
        $driver = $mink->getSession()->getDriver();
        $driver->setValue("//*[@id=\"_username\"]", "DrewSmith@mail.com");
        $driver->setValue("//*[@id=\"_password\"]", "P@ssw0rd");
        $driver->click("//*[@id=\"login\"]");

        //visit the profile
        $driver->click("//a[@id='Profile']");
        // Click the "Change Password button" button
        $driver->click("ChangePasswordXPATH");

        //enter a valid old password in the old password field
        $driver->setValue("//*[@id=\"XPATH\"]","RandomPassword");

        //enter a valid new password
        $driver->setValue("//*[@id=\"XPATH\"]","N3wP@ssw0rd");

        //reenter valid new password
        $driver->setValue("//*[@id=\"XPATH\"]","N3wP@ssw0rd");

        $driver->click("//*[@id=\"Save\"]/button");

        //check that error message is visible
        $errorMessage = $driver->isVisible("/html/body/form/div[1]/div[1]/ul/li");
        $this->assertTrue($errorMessage);
    }

    /**
     * This will test that a member can update their city
     * @author Kate Zawada and Christopher Boechler
     *
     * @throws
     */
    public function testValidCityUpdated()
    {
        //LOGIN AS A MEMBER
        $mink = new Mink(array(
            'MemberProfile' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        $mink->setDefaultSessionName('MemberProfile');
        $mink->getSession()->visit('http://localhost:8000/login');
        $driver = $mink->getSession()->getDriver();
        $driver->setValue("//*[@id=\"_username\"]", "DrewSmith@mail.com");
        $driver->setValue("//*[@id=\"_password\"]", "P@ssw0rd");
        $driver->click("//*[@id=\"login\"]");

        //visit the profile
        $driver->click("//a[@id='Profile']");
        // Click the "Edit Information" button
        $driver->click("EditInfoxPath");

        //enter a valid city in the city field
        $driver->setValue("//*[@id=\"XPATH\"]","Regina");

        $driver->click("//*[@id=\"Save\"]/button");

        $validCity = $driver->getHtml("XPATHPROFILE");

        // Assert that the member's profile page has been updated with the new city
        $this->assertEquals("Regina", $validCity);
    }

    /**
     * This will test that a member cannot update their city with an invalid value
     * @author Kate Zawada and Christopher Boechler
     *
     * @throws
     */
    public function testInvalidCityNotUpdated()
    {
        //LOGIN AS A MEMBER
        $mink = new Mink(array(
            'MemberProfile' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        $mink->setDefaultSessionName('MemberProfile');
        $mink->getSession()->visit('http://localhost:8000/login');
        $driver = $mink->getSession()->getDriver();
        $driver->setValue("//*[@id=\"_username\"]", "DrewSmith@mail.com");
        $driver->setValue("//*[@id=\"_password\"]", "P@ssw0rd");
        $driver->click("//*[@id=\"login\"]");

        //visit the profile
        $driver->click("//a[@id='Profile']");
        // Click the "Edit Information" button
        $driver->click("EditInfoxPath");

        //enter a invalid city in the city field
        $driver->setValue("//*[@id=\"XPATH\"]",
            "ThisValueIsFarToLongSorryToBeTheOneToTellYouThisValueIsFarToLongSorryToBeTheOneToTellYouThisValueIsFarToLongSorryToBeTheOneToTellYouThisValueIsFarToLongSorryToBeTheOneToTellYou");

        //click away from the field
        $driver->click("xpath");

        //check that error message is visible
        $errorMessage = $driver->isVisible("/html/body/form/div[1]/div[1]/ul/li");
        $this->assertTrue($errorMessage);

        $driver->click("//*[@id=\"Save\"]/button");

        //check that error message is visible after clicking the save button
        $errorMessage2 = $driver->isVisible("/html/body/form/div[1]/div[1]/ul/li");
        $this->assertTrue($errorMessage2);
    }

    /**
     * This will test that a member can update their postal code
     * @author Kate Zawada and Christopher Boechler
     *
     * @throws
     */
    public function testValidPostalCodeUpdated()
    {
        //LOGIN AS A MEMBER
        $mink = new Mink(array(
            'MemberProfile' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        $mink->setDefaultSessionName('MemberProfile');
        $mink->getSession()->visit('http://localhost:8000/login');
        $driver = $mink->getSession()->getDriver();
        $driver->setValue("//*[@id=\"_username\"]", "DrewSmith@mail.com");
        $driver->setValue("//*[@id=\"_password\"]", "P@ssw0rd");
        $driver->click("//*[@id=\"login\"]");

        //visit the profile
        $driver->click("//a[@id='Profile']");
        // Click the "Edit Information" button
        $driver->click("EditInfoxPath");

        //enter a valid Postal Code in the Postal Code field
        $driver->setValue("//*[@id=\"XPATH\"]","S9L 3K9");

        $driver->click("//*[@id=\"Save\"]/button");

        $validPostal = $driver->getHtml("XPATHPROFILE");

        // Assert that the member's profile page has been updated with the new city
        $this->assertEquals("S9L 3K9", $validPostal);
    }

    /**
     * This will test that a member cannot update their postal code with an invalid value
     * @author Kate Zawada and Christopher Boechler
     *
     * @throws
     */
    public function testInvalidPostalCodeNotUpdated()
    {
        //LOGIN AS A MEMBER
        $mink = new Mink(array(
            'MemberProfile' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        $mink->setDefaultSessionName('MemberProfile');
        $mink->getSession()->visit('http://localhost:8000/login');
        $driver = $mink->getSession()->getDriver();
        $driver->setValue("//*[@id=\"_username\"]", "DrewSmith@mail.com");
        $driver->setValue("//*[@id=\"_password\"]", "P@ssw0rd");
        $driver->click("//*[@id=\"login\"]");

        //visit the profile
        $driver->click("//a[@id='Profile']");
        // Click the "Edit Information" button
        $driver->click("EditInfoxPath");

        //enter a invalid postal code in the postal code field
        $driver->setValue("//*[@id=\"XPATH\"]",
            "2");

        //click away from the field
        $driver->click("xpath");

        //check that error message is visible
        $errorMessage = $driver->isVisible("/html/body/form/div[1]/div[1]/ul/li");
        $this->assertTrue($errorMessage);

        $driver->click("//*[@id=\"Save\"]/button");

        //check that error message is visible after clicking the save button
        $errorMessage2 = $driver->isVisible("/html/body/form/div[1]/div[1]/ul/li");
        $this->assertTrue($errorMessage2);
    }

    /**
     * This will test that a member can update their province
     * @author Kate Zawada and Christopher Boechler
     *
     * @throws
     */
    public function testValidProvinceUpdated()
    {
        //LOGIN AS A MEMBER
        $mink = new Mink(array(
            'MemberProfile' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        $mink->setDefaultSessionName('MemberProfile');
        $mink->getSession()->visit('http://localhost:8000/login');
        $driver = $mink->getSession()->getDriver();
        $driver->setValue("//*[@id=\"_username\"]", "DrewSmith@mail.com");
        $driver->setValue("//*[@id=\"_password\"]", "P@ssw0rd");
        $driver->click("//*[@id=\"login\"]");

        //visit the profile
        $driver->click("//a[@id='Profile']");
        // Click the "Edit Information" button
        $driver->click("EditInfoxPath");

        //enter a valid Province in the Province field
        $driver->setValue("//*[@id=\"XPATH\"]","AB");

        $driver->click("//*[@id=\"Save\"]/button");

        $province = $driver->getHtml("XPATHPROFILE");

        // Assert that the member's profile page has been updated with the new city
        $this->assertEquals("Alberta", $province);
    }

    /**
     * This will test that a member cannot update their province with an invalid value
     * @author Kate Zawada and Christopher Boechler
     *
     * @throws
     */
    public function testInvalidProvinceNotUpdated()
    {
        //LOGIN AS A MEMBER
        $mink = new Mink(array(
            'MemberProfile' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        $mink->setDefaultSessionName('MemberProfile');
        $mink->getSession()->visit('http://localhost:8000/login');
        $driver = $mink->getSession()->getDriver();
        $driver->setValue("//*[@id=\"_username\"]", "DrewSmith@mail.com");
        $driver->setValue("//*[@id=\"_password\"]", "P@ssw0rd");
        $driver->click("//*[@id=\"login\"]");

        //visit the profile
        $driver->click("//a[@id='Profile']");
        // Click the "Edit Information" button
        $driver->click("EditInfoxPath");

        //enter a invalid province in the province field
        $driver->setValue("//*[@id=\"XPATH\"]",
            "2");

        //click away from the field
        $driver->click("xpath");

        //check that error message is visible
        $errorMessage = $driver->isVisible("/html/body/form/div[1]/div[1]/ul/li");
        $this->assertTrue($errorMessage);

        $driver->click("//*[@id=\"Save\"]/button");

        //check that error message is visible after clicking the save button
        $errorMessage2 = $driver->isVisible("/html/body/form/div[1]/div[1]/ul/li");
        $this->assertTrue($errorMessage2);
    }

    /**
     * This will test that a member can update their company
     * @author Kate Zawada and Christopher Boechler
     *
     * @throws
     */
    public function testValidCompanyUpdated()
    {
        //LOGIN AS A MEMBER
        $mink = new Mink(array(
            'MemberProfile' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        $mink->setDefaultSessionName('MemberProfile');
        $mink->getSession()->visit('http://localhost:8000/login');
        $driver = $mink->getSession()->getDriver();
        $driver->setValue("//*[@id=\"_username\"]", "DrewSmith@mail.com");
        $driver->setValue("//*[@id=\"_password\"]", "P@ssw0rd");
        $driver->click("//*[@id=\"login\"]");

        //visit the profile
        $driver->click("//a[@id='Profile']");
        // Click the "Edit Information" button
        $driver->click("EditInfoxPath");

        //enter a valid Company in the Company field
        $driver->setValue("//*[@id=\"XPATH\"]","TeacherCO");

        $driver->click("//*[@id=\"Save\"]/button");

        $validCompany = $driver->getHtml("XPATHPROFILE");

        // Assert that the member's profile page has been updated with the new company
        $this->assertEquals("TeacherCO", $validCompany);
    }

    /**
     * This will test that a member cannot update their company with an invalid value
     * @author Kate Zawada and Christopher Boechler
     *
     * @throws
     */
    public function testInvalidCompanyNotUpdated()
    {
        //LOGIN AS A MEMBER
        $mink = new Mink(array(
            'MemberProfile' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        $mink->setDefaultSessionName('MemberProfile');
        $mink->getSession()->visit('http://localhost:8000/login');
        $driver = $mink->getSession()->getDriver();
        $driver->setValue("//*[@id=\"_username\"]", "DrewSmith@mail.com");
        $driver->setValue("//*[@id=\"_password\"]", "P@ssw0rd");
        $driver->click("//*[@id=\"login\"]");

        //visit the profile
        $driver->click("//a[@id='Profile']");
        // Click the "Edit Information" button
        $driver->click("EditInfoxPath");

        //enter a invalid company in the company field
        $driver->setValue("//*[@id=\"XPATH\"]",
            "123456789101234567891012345678910123456789101234567891012345678910123456789101234567891012345678910
                    123456789101234567891012345678910123456789101234567891012345678910123456789101234567891012345678910");

        //click away from the field
        $driver->click("xpath");

        //check that error message is visible
        $errorMessage = $driver->isVisible("/html/body/form/div[1]/div[1]/ul/li");
        $this->assertTrue($errorMessage);

        $driver->click("//*[@id=\"Save\"]/button");

        //check that error message is visible after clicking the save button
        $errorMessage2 = $driver->isVisible("/html/body/form/div[1]/div[1]/ul/li");
        $this->assertTrue($errorMessage2);
    }

    /**
     * This will test that a member can update their phone number
     * @author Kate Zawada and Christopher Boechler
     *
     * @throws
     */
    public function testValidPhoneNumberUpdated()
    {
        //LOGIN AS A MEMBER
        $mink = new Mink(array(
            'MemberProfile' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        $mink->setDefaultSessionName('MemberProfile');
        $mink->getSession()->visit('http://localhost:8000/login');
        $driver = $mink->getSession()->getDriver();
        $driver->setValue("//*[@id=\"_username\"]", "DrewSmith@mail.com");
        $driver->setValue("//*[@id=\"_password\"]", "P@ssw0rd");
        $driver->click("//*[@id=\"login\"]");

        //visit the profile
        $driver->click("//a[@id='Profile']");
        // Click the "Edit Information" button
        $driver->click("EditInfoxPath");

        //enter a valid phone in the phone field
        $driver->setValue("//*[@id=\"XPATH\"]","403-987-3456");

        $driver->click("//*[@id=\"Save\"]/button");

        $validPostal = $driver->getHtml("XPATHPROFILE");

        // Assert that the member's profile page has been updated with the new phone
        $this->assertEquals("403-987-3456", $validPostal);
    }

    /**
     * This will test that a member cannot update their phone number with an invalid value
     * @author Kate Zawada and Christopher Boechler
     *
     * @throws
     */
    public function testInvalidPhoneNumberNotUpdated()
    {
        //LOGIN AS A MEMBER
        $mink = new Mink(array(
            'MemberProfile' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        $mink->setDefaultSessionName('MemberProfile');
        $mink->getSession()->visit('http://localhost:8000/login');
        $driver = $mink->getSession()->getDriver();
        $driver->setValue("//*[@id=\"_username\"]", "DrewSmith@mail.com");
        $driver->setValue("//*[@id=\"_password\"]", "P@ssw0rd");
        $driver->click("//*[@id=\"login\"]");

        //visit the profile
        $driver->click("//a[@id='Profile']");
        // Click the "Edit Information" button
        $driver->click("EditInfoxPath");

        //enter a invalid phone number in the phone field
        $driver->setValue("//*[@id=\"XPATH\"]",
            "5");

        //click away from the field
        $driver->click("xpath");

        //check that error message is visible
        $errorMessage = $driver->isVisible("/html/body/form/div[1]/div[1]/ul/li");
        $this->assertTrue($errorMessage);

        $driver->click("//*[@id=\"Save\"]/button");

        //check that error message is visible after clicking the save button
        $errorMessage2 = $driver->isVisible("/html/body/form/div[1]/div[1]/ul/li");
        $this->assertTrue($errorMessage2);
    }

    /**
     * This will test that a member can update their address line one
     * @author Kate Zawada and Christopher Boechler
     *
     * @throws
     */
    public function testValidAddressOneUpdated()
    {
        //LOGIN AS A MEMBER
        $mink = new Mink(array(
            'MemberProfile' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        $mink->setDefaultSessionName('MemberProfile');
        $mink->getSession()->visit('http://localhost:8000/login');
        $driver = $mink->getSession()->getDriver();
        $driver->setValue("//*[@id=\"_username\"]", "DrewSmith@mail.com");
        $driver->setValue("//*[@id=\"_password\"]", "P@ssw0rd");
        $driver->click("//*[@id=\"login\"]");

        //visit the profile
        $driver->click("//a[@id='Profile']");
        // Click the "Edit Information" button
        $driver->click("EditInfoxPath");

        //enter a valid Address into the AddressOne field
        $driver->setValue("//*[@id=\"XPATH\"]","123 Real Rd");

        $driver->click("//*[@id=\"Save\"]/button");

        $validAddress = $driver->getHtml("XPATHPROFILE");

        // Assert that the member's profile page has been updated with the new Address
        $this->assertEquals("123 Real Rd", $validAddress);
    }

    /**
     * This will test that a member cannot update their address line one with an invalid value
     * @author Kate Zawada and Christopher Boechler
     *
     * @throws
     */
    public function testInvalidAddressOneNotUpdated()
    {
        //LOGIN AS A MEMBER
        $mink = new Mink(array(
            'MemberProfile' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        $mink->setDefaultSessionName('MemberProfile');
        $mink->getSession()->visit('http://localhost:8000/login');
        $driver = $mink->getSession()->getDriver();
        $driver->setValue("//*[@id=\"_username\"]", "DrewSmith@mail.com");
        $driver->setValue("//*[@id=\"_password\"]", "P@ssw0rd");
        $driver->click("//*[@id=\"login\"]");

        //visit the profile
        $driver->click("//a[@id='Profile']");
        // Click the "Edit Information" button
        $driver->click("EditInfoxPath");

        //enter a invalid address in the addressOne field
        $driver->setValue("//*[@id=\"XPATH\"]",
            "123456789101234567891012345678910123456789101234567891012345678910123456789101234567891012345678
            910123456789101234567891012345678910123456789101234567891012345678910123456789101234567891012345678910");

        //click away from the field
        $driver->click("xpath");

        //check that error message is visible
        $errorMessage = $driver->isVisible("/html/body/form/div[1]/div[1]/ul/li");
        $this->assertTrue($errorMessage);

        $driver->click("//*[@id=\"Save\"]/button");

        //check that error message is visible after clicking the save button
        $errorMessage2 = $driver->isVisible("/html/body/form/div[1]/div[1]/ul/li");
        $this->assertTrue($errorMessage2);
    }

    /**
     * This will test that a member can update their address line two
     * @author Kate Zawada and Christopher Boechler
     *
     * @throws
     */
    public function testValidAddressTwoUpdated()
    {
        //LOGIN AS A MEMBER
        $mink = new Mink(array(
            'MemberProfile' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        $mink->setDefaultSessionName('MemberProfile');
        $mink->getSession()->visit('http://localhost:8000/login');
        $driver = $mink->getSession()->getDriver();
        $driver->setValue("//*[@id=\"_username\"]", "DrewSmith@mail.com");
        $driver->setValue("//*[@id=\"_password\"]", "P@ssw0rd");
        $driver->click("//*[@id=\"login\"]");

        //visit the profile
        $driver->click("//a[@id='Profile']");
        // Click the "Edit Information" button
        $driver->click("EditInfoxPath");

        //enter a valid address in the AddressTwo field
        $driver->setValue("//*[@id=\"XPATH\"]","7 Max Street");

        $driver->click("//*[@id=\"Save\"]/button");

        $validAddress = $driver->getHtml("XPATHPROFILE");

        // Assert that the member's profile page has been updated with the new AddressTwo
        $this->assertEquals("7 Max Street", $validAddress);
    }

    /**
     * This will test that a member cannot update their address line two with an invalid value
     * @author Kate Zawada and Christopher Boechler
     *
     * @throws
     */
    public function testInvalidAddressTwoNotUpdated()
    {
        //LOGIN AS A MEMBER
        $mink = new Mink(array(
            'MemberProfile' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        $mink->setDefaultSessionName('MemberProfile');
        $mink->getSession()->visit('http://localhost:8000/login');
        $driver = $mink->getSession()->getDriver();
        $driver->setValue("//*[@id=\"_username\"]", "DrewSmith@mail.com");
        $driver->setValue("//*[@id=\"_password\"]", "P@ssw0rd");
        $driver->click("//*[@id=\"login\"]");

        //visit the profile
        $driver->click("//a[@id='Profile']");
        // Click the "Edit Information" button
        $driver->click("EditInfoxPath");

        //enter a invalid address in the addressTwo field
        $driver->setValue("//*[@id=\"XPATH\"]",
            "123456789101234567891012345678910123456789101234567891012345678910123456789101234567891012345678
            910123456789101234567891012345678910123456789101234567891012345678910123456789101234567891012345678910");

        //click away from the field
        $driver->click("xpath");

        //check that error message is visible
        $errorMessage = $driver->isVisible("/html/body/form/div[1]/div[1]/ul/li");
        $this->assertTrue($errorMessage);

        $driver->click("//*[@id=\"Save\"]/button");

        //check that error message is visible after clicking the save button
        $errorMessage2 = $driver->isVisible("/html/body/form/div[1]/div[1]/ul/li");
        $this->assertTrue($errorMessage2);
    }

    /**
     * This will test that a member can save a blank value into an optional field
     * @author Kate Zawada and Christopher Boechler
     *
     * @throws
     */
    public function testBlankValueOptionalField()
    {
        //LOGIN AS A MEMBER
        $mink = new Mink(array(
            'MemberProfile' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        $mink->setDefaultSessionName('MemberProfile');
        $mink->getSession()->visit('http://localhost:8000/login');
        $driver = $mink->getSession()->getDriver();
        $driver->setValue("//*[@id=\"_username\"]", "DrewSmith@mail.com");
        $driver->setValue("//*[@id=\"_password\"]", "P@ssw0rd");
        $driver->click("//*[@id=\"login\"]");

        //visit the profile
        $driver->click("//a[@id='Profile']");
        // Click the "Edit Information" button
        $driver->click("EditInfoxPath");

        //enter a valid address in the AddressTwo field
        $driver->setValue("//*[@id=\"XPATH\"]","");

        $driver->click("//*[@id=\"Save\"]/button");

        $validAddress = $driver->getHtml("XPATHPROFILE");

        // Assert that the member's profile page has been updated with the new AddressTwo
        $this->assertEquals("", $validAddress);
    }

    /**
     * This will test that a member cannot save a blank value into a required field
     * @author Kate Zawada and Christopher Boechler
     *
     * @throws
     */
    public function testBlankValueRequiredField()
    {
        //LOGIN AS A MEMBER
        $mink = new Mink(array(
            'MemberProfile' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        $mink->setDefaultSessionName('MemberProfile');
        $mink->getSession()->visit('http://localhost:8000/login');
        $driver = $mink->getSession()->getDriver();
        $driver->setValue("//*[@id=\"_username\"]", "DrewSmith@mail.com");
        $driver->setValue("//*[@id=\"_password\"]", "P@ssw0rd");
        $driver->click("//*[@id=\"login\"]");

        //visit the profile
        $driver->click("//a[@id='Profile']");
        // Click the "Edit Information" button
        $driver->click("EditInfoxPath");

        //leave the first name field blank
        $driver->setValue("//*[@id=\"XPATH\"]","");

        //click away from the field
        $driver->click("xpath");

        //check that error message is visible
        $errorMessage = $driver->isVisible("/html/body/form/div[1]/div[1]/ul/li");
        $this->assertTrue($errorMessage);

        $driver->click("//*[@id=\"Save\"]/button");

        //check that error message is visible after clicking the save button
        $errorMessage2 = $driver->isVisible("/html/body/form/div[1]/div[1]/ul/li");
        $this->assertTrue($errorMessage2);
    }

    /**
     * CoryN and KateZ
     * March 5, 2019
     * This function will test that:
     * - the card is centered
     * - inputs will be of width 540 when the browser is 1920x1080
     * - labels are displayed to the left of the inputs with their text being right aligned
     * - card header is left aligned
     * - button will be left aligned to input at the bottom of the page
     */
    //start chrome --disable-gpu --headless --remote-debugging-address=0.0.0.0 --remote-debugging-port=9222
    public function testAlignment()
    {

        $mink = new Mink(array(
            'UpdateProfile' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        $mink->setDefaultSessionName('UpdateProfile');
        $mink->getSession()->visit('http://localhost:8000');


        $session =  $mink->getSession();

        $session->wait(60000, '(0 === jQuery.active)');

        $driver = $mink->getSession()->getDriver();

        $page = $mink->getSession()->getPage();

        $mink->getSession()->resizeWindow(1920, 1040);

        $page->find("css", "#loginButton")->click();
        $page->find("css", "#_username")->setValue("samprj4reset@gmail.com");
        $page->find("css", "#_password")->setValue("P@ssw0rd");
        $page->find("css", "#login")->click();

        $page->find("css", "a#updateProfile")->click();

        $xyInputCoor = <<<JS
        $('.form-control').map(function(index, element) {
          return {top : $(element).offset().top, left: $(element).offset().left, width: $(element).outerWidth()};
        }).get();
JS;

        // Store input coordinates in arrays
        $inputXY = $mink->getSession()->evaluateScript($xyInputCoor);

        $xyLabelCoor = <<<JS
        $('label').map(function(index, element) {
          return {top : $(element).offset().top, left: $(element).offset().left, width: $(element).outerWidth(), class: $(element).attr("class")};
        }).get();
JS;

        // Store Label elements in arrays
        $labelXY = $mink->getSession()->evaluateScript($xyLabelCoor);

        $xyButtonCoor = <<<JS
        $('button').map(function(index, element) {
          return {top : $(element).offset().top, left: $(element).offset().left, width: $(element).outerWidth()};
        }).get();
JS;
        // Store Button elements in arrays
        $buttonXY = $mink->getSession()->evaluateScript($xyButtonCoor);


        $xyCardCoor = <<<JS
        $('card').map(function(index, element) {
          return {top : $(element).offset().top, left: $(element).offset().left, right: $(element).outerWidth() + $(element).offset().left, text: $(element).text(),
                    class: $(element).attr("class")};
        }).get();
JS;
        // Store Button elements in arrays
        $cardXY = $mink->getSession()->evaluateScript($xyCardCoor);

        // Get top input element to compare others to
        $xInputVal = $inputXY[0]["left"];
        $xLabelVal = $labelXY[0]["left"];
        $inputWidth = 540;

        for ($i = 0; $i < count($inputXY); $i++)
        {
            // Test that all inputs are left aligned
            $this->assertEquals($xInputVal, $inputXY[$i]["left"]);

            // Each label is in the same row as its input box
            $this->assertEquals($inputXY[$i]["top"], $labelXY[$i]["top"]);

            // Test that all labels are left aligned
            $this->assertEquals($xLabelVal, $labelXY[$i]["left"]);

            // Test that each input is the correct width
            $this->assertEquals($inputWidth, $inputXY[$i]["width"]);

            // Assert that the text is right aligned by checking the class attribute
            $this->assertContains("text-md-right", $labelXY[$i]["class"]);
        }

        // Assert that the button is left aligned with inputs
        $this->assertEquals($xInputVal, $buttonXY[0]["left"]);

        // Assert that the card is in the center of the page
        $this->assertEquals($cardXY[0]["left"], 1920 - $cardXY[0]["right"]);

        // Assert that the card title is "Update Profile" and is left aligned
        $this->assertEquals("Update Profile", $cardXY[0]["text"]);
        $this->assertContains("card-header", $cardXY[0]["class"]);
    }

    /**
     * March 5, 2019
     * This function will check that error messages:
     * - are placed to the right of the input field
     * - contain a class of error_message
     * - are left aligned
     * Kate Z and Cory N
     * start chrome --disable-gpu --headless --remote-debugging-address=0.0.0.0 --remote-debugging-port=9222
     */
    public function testErrorMessasgesDisplayedProperlyDesktop()
    {
        $mink = new Mink(array(
            'UpdateProfile' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        $mink->setDefaultSessionName('UpdateProfile');
        $mink->getSession()->visit('http://localhost:8000');


        $session =  $mink->getSession();

        $session->wait(60000, '(0 === jQuery.active)');

        $driver = $mink->getSession()->getDriver();

        $page = $mink->getSession()->getPage();

        $mink->getSession()->resizeWindow(1920, 1040);

        $page->find("css", "#loginButton")->click();
        $page->find("css", "#_username")->setValue("samprj4reset@gmail.com");
        $page->find("css", "#_password")->setValue("P@ssw0rd");
        $page->find("css", "#login")->click();

        $page->find("css", "a#updateProfile")->click();

        $page->find('css', "member_update_firstName")->setValue("");
        $page->find('css', "member_update_lastName")->setValue("");
        $page->find('css', "member_update_userName")->setValue("");
        $page->find('css', "member_update_addressLineOne")->setValue("");
        $page->find('css', "member_update_province")->setValue("");
        $page->find('css', "member_update_city")->setValue("");
        $page->find('css', "member_update_postalCode")->setValue("");
        $page->find('css', "member_update_phone")->setValue(str_repeat(1, 50));
        $page->find('css', "member_update_addressLineTwo")->setValue(str_repeat("a", 101));
        $page->find('css', "member_update_company")->setValue(str_repeat("a", 101));

        $xyInputCoor = <<<JS
        $('.form-control').map(function(index, element) {
          return {top : $(element).offset().top, left: $(element).offset().left, width: $(element).outerWidth()};
        }).get();
JS;

        // Store input coordinates in arrays
        $inputXY = $mink->getSession()->evaluateScript($xyInputCoor);

        $xyErrorCoor = <<<JS
        $('div.error').map(function(index, element) {
          return {top : $(element).offset().top, left: $(element).offset().left, width: $(element).outerWidth(),  class: $(element).attr("class")};
        }).get();
JS;
        // Store error elements in arrays
        $errorXY = $mink->getSession()->evaluateScript($xyErrorCoor);

        // Get top input element to compare others to
        $errorXVal = $errorXY[0]["left"];

        for ($i = 0; $i < count($errorXY); $i++)
        {
            // Assert that inputs are in the same row as the error message
            $this->assertEquals($inputXY[$i]["top"], $errorXY[$i]["top"]);

            // Test that all errors are left aligned
            $this->assertEquals($errorXVal, $errorXY[$i]["left"]);

            // Test that error field contain a class of error_message
            $this->assertContains("error_message", $errorXY[$i]["class"]);
        }
    }


}
