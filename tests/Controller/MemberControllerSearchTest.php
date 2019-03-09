<?php
/**
 * Created by PhpStorm.
 * User: Cory
 * Date: 2019-01-08
 * Time: 5:09 PM
 */

namespace App\Tests\Controller;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use DMore\ChromeDriver\ChromeDriver;
use Behat\Mink\Mink;
use Behat\Mink\Session;

class Test extends WebTestCase
{
    public function setUp()
    {
        $this->loadFixtures(array(
            'App\DataFixtures\AuditionDetailsFixtures',
            'App\DataFixtures\AllShowsFixtures',
            'App\DataFixtures\AppFixtures',
            'App\DataFixtures\AddMembersFixtures'
        ));
    }

    /** IMPORTANT - MUST RUN BEFORE TESTS ARE RUN
     *  From command prompt:
     * 1) php bin/console server:run
     * 2) start chrome --disable-gpu --headless --remote-debugging-address=0.0.0.0 --remote-debugging-port=9222
     * Test partially entered name displays members whose first name’s contains entered letters
     * @throws
     */
    public function testPartiallyEnteredTextFirstName()
    {
        // Load ChromeDriver
        $mink = new Mink(array(
            'partialFirstName' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        // set the default session name
        $mink->setDefaultSessionName('partialFirstName');

        // visit homepage
        $mink->getSession()->visit('http://localhost:8000/search_member');

        /** @var ChromeDriver $driver */
        $driver = $mink->getSession()->getDriver();

        // Test partially entered letters
        $driver->setValue('//*[@id="searchName"]', 'Sa');

        // Click button to search
        $driver->click('//*[@id="searchMemberButton"]');

        // Checking that the two users in the DB are being displayed, as well as their username
        $firstMemberFName = $driver->getHtml('/html/body/div[1]/p[1]');
        $this->assertEquals("Sam", $firstMemberFName);
        $firstMemberLName = $driver->getHtml('/html/body/div[1]/p[2]');
        $this->assertEquals("Smith", $firstMemberLName);

        $firstMemberUserName = $driver->getHtml('/html/body/div[1]/p[3]');
        $this->assertEquals('Sam@gmail.com', $firstMemberUserName);

        $secondMemberFName = $driver->getHtml('/html/body/div[1]/p[4]');
        $this->assertEquals("Sally", $secondMemberFName);
        $secondMemberLName = $driver->getHtml('/html/body/div[1]/p[5]');
        $this->assertEquals("Johnson", $secondMemberLName);

        $secondMemberUserName = $driver->getHtml('/html/body/div[1]/p[6]');
        $this->assertEquals('SallyJ@gmail.com', $secondMemberUserName);

    }

    /**
     * Board member only enters a single name and webpage displays all members with the same first name
     * /////// CLASSES OR IDS HAVEN'T BEEN SET, WILL NEED TO CHANGE FOR ALL TESTS ////////////
     * @throws
     */
    public function testNameMatchesFirstName()
    {
        // Load ChromeDriver
        $mink = new Mink(array(
            'matchFirstName' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        // set the default session name
        $mink->setDefaultSessionName('matchFirstName');

        // visit homepage
        $mink->getSession()->visit('http://localhost:8000/search_member');

        /** @var ChromeDriver $driver */
        $driver = $mink->getSession()->getDriver();

        // Board member enter a full name in
        $driver->setValue('//*[@id="searchName"]', 'Bill');
        $driver->click('//*[@id="searchMemberButton"]');

        // Verifying that two users with the first name Bill are displayed with their username
        $firstMemberFName = $driver->getHtml('/html/body/div[1]/p[1]');
        $this->assertEquals("Bill", $firstMemberFName);

        $firstMemberLName = $driver->getHtml('/html/body/div[1]/p[2]');
        $this->assertEquals("Macguire", $firstMemberLName);

        $firstMemberUserName = $driver->getHtml('/html/body/div[1]/p[3]');
        $this->assertEquals('BillM@gmail.com', $firstMemberUserName);

        $secondMemberFName = $driver->getHtml('/html/body/div[1]/p[4]');
        $this->assertEquals("Bill", $secondMemberFName);

        $secondMemberLName = $driver->getHtml('/html/body/div[1]/p[5]');
        $this->assertEquals("Hunter", $secondMemberLName);

        $secondMemberUserName = $driver->getHtml('/html/body/div[1]/p[6]');
        $this->assertEquals('BillH@gmail.com', $secondMemberUserName);

    }

    /**
     * Test that webpage displays a single user with the first and last name entered.
     * /////// CLASSES OR IDS HAVEN'T BEEN SET, WILL NEED TO CHANGE FOR ALL TESTS ////////////
     * @throws
     */
    public function testNameMatchesFirstAndLastName()
    {
        // Load ChromeDriver
        $mink = new Mink(array(
            'matchFullName' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        // set the default session name
        $mink->setDefaultSessionName('matchFullName');

        // visit homepage
        $mink->getSession()->visit('http://localhost:8000/search_member');

        /** @var ChromeDriver $driver */
        $driver = $mink->getSession()->getDriver();

        // Board member enters a full first and last name
        $driver->setValue('//*[@id="searchName"]', 'Mary Mills');
        $driver->click('//*[@id="searchMemberButton"]');

        // Verify that only the one user that matches both names appears
        $firstMemberFName = $driver->getHtml('/html/body/div[1]/p[1]');
        $this->assertEquals("Mary", $firstMemberFName);

        $firstMemberLName = $driver->getHtml('/html/body/div[1]/p[2]');
        $this->assertEquals("Mills", $firstMemberLName);

        $firstMemberUserName = $driver->getHtml('/html/body/div[1]/p[3]');
        $this->assertEquals('MaryM@gmail.com', $firstMemberUserName);
    }

    /**
     * Test partially entered name displays members whose last name contain entered letters
     * /////// CLASSES OR IDS HAVEN'T BEEN SET, WILL NEED TO CHANGE FOR ALL TESTS ////////////
     * @throws
     */
    public function testPartiallyEnteredTextLastName()
    {
        // Load ChromeDriver
        $mink = new Mink(array(
            'partialLastName' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        // set the default session name
        $mink->setDefaultSessionName('partialLastName');

        // visit homepage
        $mink->getSession()->visit('http://localhost:8000/search_member');

        /** @var ChromeDriver $driver */
        $driver = $mink->getSession()->getDriver();

        // Board member enters Mac in search field. Two users with the partial last name containing Mac
        // are displayed with their username's
        $driver->setValue('//*[@id="searchName"]', 'Mac');
        $driver->click('//*[@id="searchMemberButton"]');

        $firstMemberFName = $driver->getHtml('/html/body/div[1]/p[1]');
        $this->assertEquals("Bill", $firstMemberFName);

        $firstMemberLName = $driver->getHtml('/html/body/div[1]/p[2]');
        $this->assertEquals("Macguire", $firstMemberLName);

        $firstMemberUserName = $driver->getHtml('/html/body/div[1]/p[3]');
        $this->assertEquals('BillM@gmail.com', $firstMemberUserName);

        $secondMemberFName = $driver->getHtml('/html/body/div[1]/p[4]');
        $this->assertEquals("John", $secondMemberFName);

        $secondMemberLName = $driver->getHtml('/html/body/div[1]/p[5]');
        $this->assertEquals("MacDonald", $secondMemberLName);

        $secondMemberUserName = $driver->getHtml('/html/body/div[1]/p[6]');
        $this->assertEquals('JohnM@gmail.com', $secondMemberUserName);

    }

    /**
     * Test that entered name matches members last name
     * /////// CLASSES OR IDS HAVEN'T BEEN SET, WILL NEED TO CHANGE FOR ALL TESTS ////////////
     * @throws
     */
    public function testNameMatchesLastName()
    {
        // Load ChromeDriver
        $mink = new Mink(array(
            'matchLastName' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        // set the default session name
        $mink->setDefaultSessionName('matchLastName');

        // visit homepage
        $mink->getSession()->visit('http://localhost:8000/search_member');

        /** @var ChromeDriver $driver */
        $driver = $mink->getSession()->getDriver();

        // Board member enter a full name into the field. One user matching the last name will be
        // displayed with their username
        $driver->setValue('//*[@id="searchName"]', 'Frank');
        $driver->click('//*[@id="searchMemberButton"]');

        $firstMemberFName = $driver->getHtml('/html/body/div[1]/p[1]');
        $this->assertEquals("Annie", $firstMemberFName);

        $firstMemberLName = $driver->getHtml('/html/body/div[1]/p[2]');
        $this->assertEquals("Frank", $firstMemberLName);

        $firstMemberUserName = $driver->getHtml('/html/body/div[1]/p[3]');
        $this->assertEquals('AnnieF@gmail.com', $firstMemberUserName);
    }

    /**
     * Test that entered text does not match any member names in database
     * /////// CLASSES OR IDS HAVEN'T BEEN SET, WILL NEED TO CHANGE FOR ALL TESTS ////////////
     * @throws
     */
    public function testNameDoesNotMatch()
    {
        // Load ChromeDriver
        $mink = new Mink(array(
            'noMatch' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        // set the default session name
        $mink->setDefaultSessionName('noMatch');

        // visit homepage
        $mink->getSession()->visit('http://localhost:8000/search_member');

        /** @var ChromeDriver $driver */
        $driver = $mink->getSession()->getDriver();

        // Board member enters characters that don't match anyone in the database
        // Member information isn't displayed. Notification message is displayed.
        $driver->setValue('//*[@id="searchName"]', 'Zak');
        $driver->click('//*[@id="searchMemberButton"]');

        $notification = $driver->getHtml('/html/body/p');
        $this->assertEquals("Did not find any members by that name!", $notification);
    }

    /**
     * Test that upper or lower case does not affect the result.  Characters will be converted and matched
     * /////// CLASSES OR IDS HAVEN'T BEEN SET, WILL NEED TO CHANGE FOR ALL TESTS ////////////
     * @throws
     */
    public function testUpperOrLowerCase()
    {
        // Load ChromeDriver
        $mink = new Mink(array(
            'caseInsensitive' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        // set the default session name
        $mink->setDefaultSessionName('caseInsensitive');

        // visit homepage
        $mink->getSession()->visit('http://localhost:8000/search_member');

        /** @var ChromeDriver $driver */
        $driver = $mink->getSession()->getDriver();

        // Board member enters upper and lower case letters into search.
        // Text is converted and one user matching characters is displayed
        $driver->setValue('//*[@id="searchName"]', 'MaRy MilLs');
        $driver->click('//*[@id="searchMemberButton"]');

        // Verify that only the one user that matches both names appears
        $firstMemberFName = $driver->getHtml('/html/body/div[1]/p[1]');
        $this->assertEquals("Mary", $firstMemberFName);

        $firstMemberLName = $driver->getHtml('/html/body/div[1]/p[2]');
        $this->assertEquals("Mills", $firstMemberLName);

        $firstMemberUserName = $driver->getHtml('/html/body/div[1]/p[3]');
        $this->assertEquals('MaryM@gmail.com', $firstMemberUserName);
    }

}
