<?php
/**
 * User: CST221
 * Date: 1/24/2019
 * SERVER LOGIC BLOCK
 */

namespace App\Tests\Controller;

use app\Entity\OnlinePoll;

use DMore\ChromeDriver\ChromeDriver;
use Behat\Mink\Mink;
use Behat\Mink\Session;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use WebDriver\Exception;

class OnlinePollControllerTest extends WebTestCase
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
            'App\DataFixtures\MemberLoginTestFixture'
        ))->getReferenceRepository();

    }


    /**
     * Tests that a board member sees the link on the homepage and if they click it, it redirects them
     * to the correct page
     *
     * Author: Christopher Boechler
     *
     * @throws
     */
    public function testBoardMemberOpensOnlinePoll()
    {
        //LOGIN AS A BOARD MEMBER
        $mink = new Mink(array(
            'OnlinePoll' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        $mink->setDefaultSessionName('OnlinePoll');
        $mink->getSession()->visit('http://localhost:8000/login');
        $driver = $mink->getSession()->getDriver();
        $driver->setValue("//*[@id=\"_username\"]", "bmember@member.com");
        $driver->setValue("//*[@id=\"_password\"]", "P@ssw0rd");
        $driver->click("//*[@id=\"login\"]");

        //click the link to go to the poll page
        $driver->click("//a[@id='onlinePoll']");

        //Verify the correct text is being displayed
        $onlinePoll = $driver->getHtml("/html/body/div[1]/h1");
        $this->assertEquals('Online Poll', $onlinePoll);
    }

    /**
     * Tests that a member cannot see the button to go to an online poll. Also tests if they
     * attempt to go to the web page by typing in the url that it informs them that they do not have
     * permission to view this page
     *
     * Author: Christopher Boechler
     *
     * @throws
     */
    public function testMemberOpensOnlineVote()
    {
        //LOGIN AS A MEMBER
        $mink = new Mink(array(
            'OnlinePoll' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        $mink->setDefaultSessionName('OnlinePoll');
        $mink->getSession()->visit('http://localhost:8000/login');
        $driver = $mink->getSession()->getDriver();
        $driver->setValue("//*[@id=\"_username\"]", "member@member.com");
        $driver->setValue("//*[@id=\"_password\"]", "P@ssw0rd");
        $driver->click("//*[@id=\"login\"]");

        //Go to the poll page
        $isVisible = $driver->isVisible("//*[@id=\"onlinePoll\"]");
        $this->assertFalse($isVisible);

        //visit online poll MANUALLY
        $mink->getSession()->visit('http://localhost:8000/board/online_poll');

        //compare that the error on the page matches the error we want to display
        $permissionInvalid = $driver->getHtml("/html/body/div[2]/div/div/p");
        $this->assertEquals('Now Playing', $permissionInvalid);
    }

    /**
     * Tests that a user cannot see the button to go to an online poll. Also tests if they
     * attempt to go to the web page by typing in the url that it informs them that they do not have
     * permission to view this page
     *
     * Author: Christopher Boechler
     *
     * @throws
     */
    public function testUserOpensOnlineVote()
    {
        //Load ChromeDriver
        $mink = new Mink(array(
            'OnlinePoll' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));

        // set the default session name
        $mink->setDefaultSessionName('OnlinePoll');

        //visit homepage
        $mink->getSession()->visit('http://localhost:8000');

        /** @var ChromeDriver $driver */
        $driver = $mink->getSession()->getDriver();

        $isVisible = $driver->isVisible("//*[@id=\"onlinePoll\"]");
        $this->assertFalse($isVisible);

        //visit online poll
        $mink->getSession()->visit('http://localhost:8000/board/online_poll');

        //compare that the error on the page matches the error we want to display
        $permissionInvalid = $driver->getHtml("//*[@id=\"login\"]");
        $this->assertEquals('Login', $permissionInvalid);
    }

    /**
     * Test that when a boardmember clicks to create a new poll that the new poll form appears on the page
     *
     * Author: Christopher Boechler
     * @throws 
     */
    public function testCreateNewPoll()
    {
        //LOGIN AS A BOARD MEMBER
        $mink = new Mink(array(
            'OnlinePoll' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        $mink->setDefaultSessionName('OnlinePoll');
        $mink->getSession()->visit('http://localhost:8000/login');
        $driver = $mink->getSession()->getDriver();
        $driver->setValue("//*[@id='_username']", "bmember@member.com");
        $driver->setValue("//*[@id='_password']", "P@ssw0rd");
        $driver->click("//*[@id='login']");

        $driver->click("//a[@id='onlinePoll']");

        //go to the online poll page
        $onlinePoll = $driver->getHtml("/html/body/div[1]/h1");
        $this->assertEquals('Online Poll', $onlinePoll);

        //click to create a new poll
        $driver->click("/html/body/div[2]/a");

        //check that we are on the create new page
        $creatingPoll = $driver->getText("/html/body/form/div[1]/h1");
        $this->assertEquals('Create new Online Poll', $creatingPoll);
    }

    /**
     * Test that a boardmember is able to delete a poll using the delete poll button. the delete poll button works.
     *
     * Author: Christopher Boechler
     * @throws
     */
    public function testDeleteExistingPoll()
    {
        //LOGIN AS A BOARD MEMBER
        $mink = new Mink(array(
            'OnlinePoll' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        $mink->setDefaultSessionName('OnlinePoll');
        $mink->getSession()->visit('http://localhost:8000/login');
        $driver = $mink->getSession()->getDriver();
        $driver->setValue("//*[@id=\"_username\"]", "bmember@member.com");
        $driver->setValue("//*[@id=\"_password\"]", "P@ssw0rd");
        $driver->click("//*[@id=\"login\"]");

        $driver->click("//a[@id='onlinePoll']");

        //check that the entry we are going to delete is present
        $entry1 = $driver->isVisible("/html/body/div[3]/div[1]/div[1]/h2/u");
        $this->assertTrue($entry1);

        //click the delete button
        $driver->click("/html/body/div[3]/div[1]/div[2]/form/button");

        //ensure that the entry is not present
        $this->assertCount(0,$driver->find("/html/body/div[3]/div[1]/div[1]/h2/u"));
    }

    /**
     * Tests that the poll name is valid
     *
     * Author: Christopher & Dylan
     * @throws
     */
    public function testPollValidName()
    {
        //LOGIN AS A BOARD MEMBER
        $mink = new Mink(array(
            'OnlinePoll' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        $mink->setDefaultSessionName('OnlinePoll');
        $mink->getSession()->visit('http://localhost:8000/login');
        $driver = $mink->getSession()->getDriver();
        $driver->setValue("//*[@id=\"_username\"]", "bmember@member.com");
        $driver->setValue("//*[@id=\"_password\"]", "P@ssw0rd");
        $driver->click("//*[@id=\"login\"]");

        //visit the poll site
        $driver->click("//a[@id='onlinePoll']");
        //create the poll
        $driver->click("/html/body/div[2]/a");

        //enter a valid name in the name field
        $driver->setValue("//*[@id=\"online_poll_Name\"]","ValidName");
        $driver->setValue("//*[@id=\"online_poll_Description\"]", "Desc");
        $driver->setValue("//*[@id=\"form_options_0\"]", "Option1");
        $driver->setValue("//*[@id=\"form_options_1\"]", "Option2");

        $driver->click("//*[@id=\"OptionList\"]/button");

        $validName = $driver->getHtml("/html/body/div[3]/div[1]/div[1]/h2/u");

        $this->assertEquals("ValidName", $validName);
    }

    /**
     * Test that poll name is invalid
     *
     * Author: Christopher & Dylan
     * @throws
     */
    public function testPollInvalidName()
    {
        //LOGIN AS A BOARD MEMBER
        $mink = new Mink(array(
            'OnlinePoll' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        $mink->setDefaultSessionName('OnlinePoll');
        $mink->getSession()->visit('http://localhost:8000/login');
        $driver = $mink->getSession()->getDriver();
        $driver->setValue("//*[@id=\"_username\"]", "bmember@member.com");
        $driver->setValue("//*[@id=\"_password\"]", "P@ssw0rd");
        $driver->click("//*[@id=\"login\"]");

        //visit the poll site
        $driver->click("//a[@id='onlinePoll']");
        //create the poll
        $driver->click("/html/body/div[2]/a");

        //enter a invalid name in the name field
        $driver->setValue("//*[@id=\"online_poll_Name\"]","This is over 200 characters.This is over 200 characters.
        This is over 200 characters.This is over 200 characters.This is over 200 characters.This is over 200 characters.
        This is over 200 characters.This is over 200 characters.");
        $driver->setValue("//*[@id=\"online_poll_Description\"]", "Desc");
        $driver->setValue("//*[@id=\"form_options_0\"]", "Option1");
        $driver->setValue("//*[@id=\"form_options_1\"]", "Option2");


        $driver->click("//*[@id=\"OptionList\"]/button");

        //check that error message is visible
        $errorMessage = $driver->isVisible("/html/body/form/div[1]/div[1]/ul/li");
        $this->assertTrue($errorMessage);
    }

    /**
     * Test that no error message is displayed when typing in a valid description
     *
     * Author: Christopher & Dylan
     * @throws
     */
    public function testPollValidDescription()
    {
        //LOGIN AS A BOARD MEMBER
        $mink = new Mink(array(
            'OnlinePoll' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        $mink->setDefaultSessionName('OnlinePoll');
        $mink->getSession()->visit('http://localhost:8000/login');
        $driver = $mink->getSession()->getDriver();
        $driver->setValue("//*[@id=\"_username\"]", "bmember@member.com");
        $driver->setValue("//*[@id=\"_password\"]", "P@ssw0rd");
        $driver->click("//*[@id=\"login\"]");

        //visit the poll site
        $driver->click("//a[@id='onlinePoll']");
        //create the poll
        $driver->click("/html/body/div[2]/a");

        //enter a valid name in the description field
        $driver->setValue("//*[@id=\"online_poll_Name\"]","ValidName");
        $driver->setValue("//*[@id=\"online_poll_Description\"]", "ValidDesc");
        $driver->setValue("//*[@id=\"form_options_0\"]", "Option1");
        $driver->setValue("//*[@id=\"form_options_1\"]", "Option2");

        $driver->click("//*[@id=\"OptionList\"]/button");

        $validDesc = $driver->getHtml("/html/body/div[3]/div[2]/h4");

        $this->assertEquals("ValidDesc", $validDesc);
    }

    /**
     * Test that when entering an invalid description that the error message is displayed to the bm.
     *
     * Author: Christopher & Dylan
     * @throws
     */
    public function testPollInvalidDescription()
    {
        //LOGIN AS A BOARD MEMBER
        $mink = new Mink(array(
            'OnlinePoll' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        $mink->setDefaultSessionName('OnlinePoll');
        $mink->getSession()->visit('http://localhost:8000/login');
        $driver = $mink->getSession()->getDriver();
        $driver->setValue("//*[@id=\"_username\"]", "bmember@member.com");
        $driver->setValue("//*[@id=\"_password\"]", "P@ssw0rd");
        $driver->click("//*[@id=\"login\"]");


        //visit the poll site
        $driver->click("//a[@id='onlinePoll']");
        //create the poll
        $driver->click("/html/body/div[2]/a");

        //enter a invalid description in the name field
        $driver->setValue("//*[@id=\"online_poll_Name\"]","ValidName");
        $driver->setValue("//*[@id=\"online_poll_Description\"]", "InvalidDesc
        InvalidDescInvalidDescInvalidDescInvalidDescInvalidDescInvalidDescInvalidDescInvalidDesc
        InvalidDescInvalidDescInvalidDescInvalidDescInvalidDescInvalidDescInvalidDescInvalidDesc
        InvalidDescInvalidDescInvalidDescInvalidDescInvalidDescInvalidDescInvalidDescInvalidDesc
        InvalidDescInvalidDescInvalidDescInvalidDescInvalidDescInvalidDescInvalidDescInvalidDesc
        InvalidDescInvalidDescInvalidDescInvalidDescInvalidDescInvalidDescInvalidDescInvalidDesc
        InvalidDescInvalidDescInvalidDescInvalidDescInvalidDescInvalidDescInvalidDescInvalidDesc
        InvalidDescInvalidDescInvalidDescInvalidDescInvalidDescInvalidDescInvalidDescInvalidDesc
        InvalidDescInvalidDescInvalidDescInvalidDescInvalidDescInvalidDescInvalidDescInvalidDesc
        InvalidDescInvalidDescInvalidDescInvalidDescInvalidDescInvalidDescInvalidDescInvalidDesc
        InvalidDescInvalidDescInvalidDescInvalidDescInvalidDescInvalidDescInvalidDescInvalidDesc");
        $driver->setValue("//*[@id=\"form_options_0\"]", "Option1");
        $driver->setValue("//*[@id=\"form_options_1\"]", "Option2");

        $driver->click("//*[@id=\"OptionList\"]/button");

        //check that error message is visible
        $errorMessage = $driver->isVisible("/html/body/form/div[1]/div[2]/ul/li");
        $this->assertTrue($errorMessage);
    }

    /**
     *
     * Test attempting to submit a poll with empty options
     *
     * Author: Christopher and Dylan
     *
     * @throws
     */
    public function testPollEmptyOptions()
    {
        //LOGIN AS A BOARD MEMBER
        $mink = new Mink(array(
            'OnlinePoll' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        $mink->setDefaultSessionName('OnlinePoll');
        $mink->getSession()->visit('http://localhost:8000/login');
        $driver = $mink->getSession()->getDriver();
        $driver->setValue("//*[@id=\"_username\"]", "bmember@member.com");
        $driver->setValue("//*[@id=\"_password\"]", "P@ssw0rd");
        $driver->click("//*[@id=\"login\"]");


        //visit the poll site
        $driver->click("//a[@id='onlinePoll']");
        //create the poll
        $driver->click("/html/body/div[2]/a");

        //enter a invalid name in the name field
        $driver->setValue("//*[@id=\"online_poll_Name\"]","ValidName");
        $driver->setValue("//*[@id=\"online_poll_Description\"]", "ValidDesc");

        //Try to submit the form
        $driver->click("//*[@id=\"OptionList\"]/button");

        //check that error message is visible
        $createOnlinePollPage = $driver->isVisible("/html/body/form/div[1]/h1");
        $this->assertTrue($createOnlinePollPage);
    }

    /**
     *  Test that a valid option is entered into the option field.
     *
     * Author: Christopher & Dylan
     *
     * @throws
     */
    public function testPollValidOption()
    {
        //LOGIN AS A BOARD MEMBER
        $mink = new Mink(array(
            'OnlinePoll' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        $mink->setDefaultSessionName('OnlinePoll');
        $mink->getSession()->visit('http://localhost:8000/login');
        $driver = $mink->getSession()->getDriver();
        $driver->setValue("//*[@id=\"_username\"]", "bmember@member.com");
        $driver->setValue("//*[@id=\"_password\"]", "P@ssw0rd");
        $driver->click("//*[@id=\"login\"]");

        //visit the poll site
        $driver->click("//a[@id='onlinePoll']");
        //create the poll
        $driver->click("/html/body/div[2]/a");

        //enter a invalid name in the name field
        $driver->setValue("//*[@id=\"online_poll_Name\"]","ValidName");
        $driver->setValue("//*[@id=\"online_poll_Description\"]", "ValidDesc");
        $driver->setValue("//*[@id=\"form_options_0\"]", "Option1");
        $driver->setValue("//*[@id=\"form_options_1\"]", "Option2");

        //click the save button
        $driver->click("//*[@id=\"OptionList\"]/button");

        //confirm that the valid entry was submitted
        $onlinePollPage = $driver->getHtml("/html/body/div[3]/div[1]/div[1]/h2/u");
        $this->assertEquals("ValidName",$onlinePollPage);
    }

    /**
     *  Test that a invalid option is entered into the option field, and an error messsage is displayed.
     *
     * Author: Christopher & Dylan
     *
     * @throws
     */
    public function testPollInvalidOption()
    {
        //LOGIN AS A BOARD MEMBER
        $mink = new Mink(array(
            'OnlinePoll' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        $mink->setDefaultSessionName('OnlinePoll');
        $mink->getSession()->visit('http://localhost:8000/login');
        $driver = $mink->getSession()->getDriver();
        $driver->setValue("//*[@id=\"_username\"]", "bmember@member.com");
        $driver->setValue("//*[@id=\"_password\"]", "P@ssw0rd");
        $driver->click("//*[@id=\"login\"]");


        //visit the poll site
        $driver->click("//a[@id='onlinePoll']");
        //create the poll
        $driver->click("/html/body/div[2]/a");

        //enter a invalid option
        $driver->setValue("//*[@id=\"online_poll_Name\"]","ValidName");
        $driver->setValue("//*[@id=\"online_poll_Description\"]", "ValidDesc");
        $driver->setValue("//*[@id=\"form_options_0\"]", "HUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUU
        HUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUHUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUHUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUU
        HUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUHUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUHUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUU
        HUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUHUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUHUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUGE");
        $driver->setValue("//*[@id=\"form_options_1\"]", "Option2");

        //Try to submit the form
        $driver->click("//*[@id=\"OptionList\"]/button");

        //check that error message is visible
        $createOnlinePollPage = $driver->isVisible("/html/body/form/div[1]/h1");
        $this->assertTrue($createOnlinePollPage);
    }

    /**
     * BM attempts to delete an option when there are only two remaining. Two options still remain
     * because a poll can not be created with less than 2 options
     *
     * Author: Christopher & Dylan
     * @throws
     */
    public function testRemovingAnOptionWhenOnlyTwoRemain()
    {
        $client = static::createClient();

        // Retrieve the page information from the specified route
        $client->request('GET', '/login');

        $crawler = $client->getCrawler();

        $form = $crawler->selectButton('login')->form();

        //Set Invalid email that is not in the database
        $form['_username']->setValue("bmember@member.com");
        $form['_password']->setValue("P@ssw0rd");

        $client->submit($form);

        $client->followRedirect();
        $crawler = $client->getCrawler();

        //Go the the new page
        $client->request('GET', '/board/online_poll/new');
        $crawler = $client->getCrawler();

        //Make sure both inputs are on the page
        $this->assertTrue($crawler->filter("#form_options_0") && $crawler->filter("#form_options_1"));

        //Click the delete button, SHOULDN'T WORK
        $link = $crawler->filter("#btnRemove")->link();
        $client->click($link);

        //Make sure both inputs are still there
        $this->assertTrue($crawler->filter("#form_options_0") && $crawler->filter("#form_options_1"));
    }

    /**
     * Test that when you click add option when there are already 10 options that an eleventh option is not added.
     *
     * Author: Christopher & Dylan
     *
     * @throws
     */
    public function testAddingOptionBeyondTen()
    {
        //LOGIN AS A BOARD MEMBER
        $mink = new Mink(array(
            'OnlinePoll' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        $mink->setDefaultSessionName('OnlinePoll');
        $mink->getSession()->visit('http://localhost:8000/login');
        $driver = $mink->getSession()->getDriver();
        $driver->setValue("//*[@id=\"_username\"]", "bmember@member.com");
        $driver->setValue("//*[@id=\"_password\"]", "P@ssw0rd");
        $driver->click("//*[@id=\"login\"]");


        //visit the poll site
        $driver->click("//a[@id='onlinePoll']");
        //create the poll
        $driver->click("/html/body/div[2]/a");

        //click add 9 times
        $driver->click("//*[@id=\"btnAdd\"]");
        $driver->click("//*[@id=\"btnAdd\"]");
        $driver->click("//*[@id=\"btnAdd\"]");
        $driver->click("//*[@id=\"btnAdd\"]");
        $driver->click("//*[@id=\"btnAdd\"]");
        $driver->click("//*[@id=\"btnAdd\"]");
        $driver->click("//*[@id=\"btnAdd\"]");
        $driver->click("//*[@id=\"btnAdd\"]");

        $this->assertTrue($driver->isVisible("//*[@id=\"form_options_9\"]"));

        //11th option shouldnt be added
        $driver->click("//*[@id=\"btnAdd\"]");

        $this->assertCount(0,$driver->find("//*[@id=\"form_options_10\"]"));
    }
}
