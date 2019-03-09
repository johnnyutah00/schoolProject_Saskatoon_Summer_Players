<?php
namespace App\Tests;

use DMore\ChromeDriver\ChromeDriver;
use Behat\Mink\Mink;
use Behat\Mink\Session;
use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Class ShowControllerTest
 * @author Nathan
 * @package App\Tests
 *
 *
 * This class will UI test the Edit Shows Index page, the add new show page, and the edit show page.
 *
 * *************************************************************
 * * From command prompt:
 * 1) php bin/console server:run
 * 2) start chrome --disable-gpu --headless --remote-debugging-address=0.0.0.0 --remote-debugging-port=9222
 *
 */
class AddUpdateDeleteShowTests extends WebTestCase
{
    /**
     * @throws \Behat\Mink\Exception\DriverException
     * @throws \Behat\Mink\Exception\UnsupportedDriverActionException
     */
    public function setUp()
    {
        $this->loadFixtures(array(
            'App\DataFixtures\AddUpdateDeleteShowFixtures'
        ));
    }

    /**
     * This test will make sure that the edit show index page exists
     * @author Nathan and Ankita
     * @throws
     */
    public function testThatEditShowIndexPageExists()
    {
        // Load Mink
        $mink = new Mink(array(
            'showBrowser' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        // Set the default session name
        $mink->setDefaultSessionName('showBrowser');

        $mink->getSession()->visit('http://localhost:8000/login');

        /** @var ChromeDriver $driver */
        $driver = $mink->getSession()->getDriver();

        $driver->setValue("//*[@id='_username']", "gmember@member.com");
        $driver->setValue("//*[@id='_password']", "P@ssw0rd");
        $driver->click("//*[@id='login']"); //login

        $mink->getSession()->visit('http://localhost:8000/show/admin/edit_index');

        /** @var ChromeDriver $driver */
        $driver = $mink->getSession()->getDriver();

        $this->assertEquals(200, $driver->getStatusCode());
    }

    /**
     * This will test that all the correct element (search box, some shows, add new show button) appear on the edit show index page when loaded.
     * @author Nathan
     * @throws
     */
    public function testThatSearchBarAndAddShowButtonAndShowsAreDisplayedOnEditShowIndexPageUponLoad()
    {
        // Load Mink
        $mink = new Mink(array(
            'showBrowser' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        // Set the default session name
        $mink->setDefaultSessionName('showBrowser');

        $mink->getSession()->visit('http://localhost:8000/login');

        /** @var ChromeDriver $driver */
        $driver = $mink->getSession()->getDriver();

        $driver->setValue("//*[@id='_username']", "gmember@member.com");
        $driver->setValue("//*[@id='_password']", "P@ssw0rd");
        $driver->click("//*[@id='login']"); //login

        // Visit homepage
        $mink->getSession()->visit('http://localhost:8000/show/admin/edit_index');

        /** @var ChromeDriver $driver */
        $driver = $mink->getSession()->getDriver();

        $this->assertEquals('Search Shows', $driver->getHtml("//*[@id='showSearchButton']")); //Search Show Button

        $this->assertEquals('Add Show', $driver->getAttribute("//*[@id='addShow']", 'value')); // Add show button


        $isVisible = $driver->isVisible("//*[@id='showTable']");
        $this->assertTrue($isVisible);

    }

    /**
     * This will test that the search box on the edit shows index page works correctly. It will search for a specific show and then test to see if it appears as the
     * first item within the show list.
     * @author Nathan
     * @throws
     */
    public function testThatSearchBarWorksWhenWantingASpecificShow()
    {
        // Load Mink
        $mink = new Mink(array(
            'showBrowser' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        // Set the default session name
        $mink->setDefaultSessionName('showBrowser');

        $mink->getSession()->visit('http://localhost:8000/login');

        /** @var ChromeDriver $driver */
        $driver = $mink->getSession()->getDriver();

        $driver->setValue("//*[@id='_username']", "gmember@member.com");
        $driver->setValue("//*[@id='_password']", "P@ssw0rd");
        $driver->click("//*[@id='login']"); //login

        // Visit homepage
        $mink->getSession()->visit('http://localhost:8000/show/admin/edit_index');

        /** @var ChromeDriver $driver */
        $driver = $mink->getSession()->getDriver();

        $driver->setValue("//*[@id='searchShows']", "A cats tale"); //Search input box
        $driver->click("//*[@id='showSearchButton']"); //Search button

        $this->assertEquals('A cats tale', $driver->getHtml("//*[@id='showTable']/tbody/tr[2]/td[1]"));
    }

    /**
     * This will test that the search box on the edit shows index page works correctly. It will search for a show that does not exist and then check if the appropriate error message is displayed
     * @author Ankita and Nathan
     * @throws
     */
    public function testThatSearchBarWorksWhenWantingAShowThatDoesNotExist()
    {
        // Load Mink
        $mink = new Mink(array(
            'showBrowser' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        // Set the default session name
        $mink->setDefaultSessionName('showBrowser');

        $mink->getSession()->visit('http://localhost:8000/login');

        /** @var ChromeDriver $driver */
        $driver = $mink->getSession()->getDriver();

        $driver->setValue("//*[@id='_username']", "gmember@member.com");
        $driver->setValue("//*[@id='_password']", "P@ssw0rd");
        $driver->click("//*[@id='login']"); //login

        // Visit homepage
        $mink->getSession()->visit('http://localhost:8000/show/admin/edit_index');

        /** @var ChromeDriver $driver */
        $driver = $mink->getSession()->getDriver();

        $driver->setValue("//*[@id='searchShows']", "99999"); //Search input box
        $driver->click("//*[@id='showSearchButton']"); //Search button

        $this->assertEquals('No shows found.', $driver->getHtml("//*[@id='noShows']"));

        $driver->click("//*[@id='showSearchButton']"); //click search again for testing an empty search
        $isVisible = $driver->isVisible("//*[@id='showTable']");
        $this->assertTrue($isVisible);

    }

    /**
     * This will test that clicking the add new show button successfully redirects to the add new show page
     * @author Nathan
     * @throws
     */
    public function testThatAddNewShowButtonRedirectsToAddNewShowPage()
    {
        // Load Mink
        $mink = new Mink(array(
            'showBrowser' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        // Set the default session name
        $mink->setDefaultSessionName('showBrowser');

        $mink->getSession()->visit('http://localhost:8000/login');

        /** @var ChromeDriver $driver */
        $driver = $mink->getSession()->getDriver();

        $driver->setValue("//*[@id='_username']", "gmember@member.com");
        $driver->setValue("//*[@id='_password']", "P@ssw0rd");
        $driver->click("//*[@id='login']"); //login

        // Visit homepage
        $mink->getSession()->visit('http://localhost:8000/show/admin/edit_index');

        /** @var ChromeDriver $driver */
        $driver = $mink->getSession()->getDriver();

        $driver->click("//*[@id='addShow']"); //Add Show Button

        $this->assertEquals('http://localhost:8000/show/admin/new?', $driver->getCurrentUrl());
    }

    /**
     * This will test that the field validation is still working on the add new show page
     * @author Nathan
     * @throws
     */
    public function testThatNewShowPageHasCorrectValidationOnAllFields()
    {
        // Load Mink
        $mink = new Mink(array(
            'showBrowser' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        // Set the default session name
        $mink->setDefaultSessionName('showBrowser');

        $mink->getSession()->visit('http://localhost:8000/login');

        /** @var ChromeDriver $driver */
        $driver = $mink->getSession()->getDriver();

        $driver->setValue("//*[@id='_username']", "gmember@member.com");
        $driver->setValue("//*[@id='_password']", "P@ssw0rd");
        $driver->click("//*[@id='login']"); //login

        // Visit homepage
        $mink->getSession()->visit('http://localhost:8000/show/admin/new?');

        /** @var ChromeDriver $driver */
        $driver = $mink->getSession()->getDriver();

        $driver->setValue("//*[@id='show_name']", "A cats tale"); //name of the show, leaving it blank to check validation.
        $driver->setValue("//*[@id='show_budget']", 500); //Budget
        $driver->setValue("//*[@id='show_ticketPrice']", 420); //Ticket
        $driver->selectOption("//*[@id='show_location']", "1"); //Location
        //$driver->setValue("//*[@id='show_location']", "123 Main"); //Location
        $driver->setValue("//*[@id='show_synopsis']", "A cat saves the world!"); //Synopsis
        $driver->setValue("//*[@id='show_ticketLink']", "girejgoijeroigjer");            //failure caused here do to the ticket link not being a valid URL

        $driver->click("/html/body/form/button"); //submit button
        $this->assertEquals('http://localhost:8000/show/admin/new?', $driver->getCurrentUrl());
    }

    /**
     * This will test that a newly added show appears in the edit shows index list after creation.
     * @author Nathan
     * @throws
     */
    public function testThatNewShowSuccessfullySavesWhenSubmitted()
    {
        // Load Mink
        $mink = new Mink(array(
            'showBrowser' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        // Set the default session name
        $mink->setDefaultSessionName('showBrowser');

        $mink->getSession()->visit('http://localhost:8000/login');

        /** @var ChromeDriver $driver */
        $driver = $mink->getSession()->getDriver();

        $driver->setValue("//*[@id='_username']", "gmember@member.com");
        $driver->setValue("//*[@id='_password']", "P@ssw0rd");
        $driver->click("//*[@id='login']"); //login

        // Visit homepage
        $mink->getSession()->visit('http://localhost:8000/show/admin/new');

        /** @var ChromeDriver $driver */
        $driver = $mink->getSession()->getDriver();


        $driver->setValue("//*[@id='show_name']", "A A"); //name of the show, leaving it blank to check validation.
        $driver->setValue("//*[@id='show_budget']", 500); //Budget
        $driver->setValue("//*[@id='show_ticketPrice']", 420); //Ticket
        $driver->selectOption("//*[@id='show_location']", "1"); //Location
        $driver->setValue("//*[@id='show_synopsis']", "A cat saves the world!"); //Synopsis
        $driver->setValue("//*[@id='show_ticketLink']", "https://www.google.com");            //failure caused here do to the ticket link not being a valid URL

        $driver->click("/html/body/form/button"); //submit button

        $this->assertEquals('http://localhost:8000/show/admin/edit_index', $driver->getCurrentUrl());
        $this->assertEquals('A A', $driver->getText("//*[@id='showTable']/tbody/tr[6]/td[1]"));
    }

    /**
     * This will test that an archived show no longer appears on the edit shows index page after being marked as such.
     * @author Nathan
     * @throws
     */
    public function testThatArchivedShowNoLongerDisplaysOnEditShowIndexPageOrRegularShowsPage()
    {
        // Load Mink
        $mink = new Mink(array(
            'showBrowser' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        // Set the default session name
        $mink->setDefaultSessionName('showBrowser');

        $mink->getSession()->visit('http://localhost:8000/login');

        /** @var ChromeDriver $driver */
        $driver = $mink->getSession()->getDriver();

        $driver->setValue("//*[@id='_username']", "gmember@member.com");
        $driver->setValue("//*[@id='_password']", "P@ssw0rd");
        $driver->click("//*[@id='login']"); //login

        // Visit homepage
        $mink->getSession()->visit('http://localhost:8000/show/admin/edit_index');

        /** @var ChromeDriver $driver */
        $driver = $mink->getSession()->getDriver();

        $driver->click("//*[@id='archive']");

        $isVisible = $driver->find("//*[@id='showTable']/tbody/tr[2]/td[2]");
        $this->assertEquals(1, sizeof($isVisible));
    }

    /**
     * This will test that the correct edit page is loaded and exists when clicking a specific show to edit
     * @author Nathan
     * @throws
     */
    public function testThatClickingEditShowRedirectsToAppropriateEditShowPage()
    {
        // Load Mink
        $mink = new Mink(array(
            'showBrowser' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        // Set the default session name
        $mink->setDefaultSessionName('showBrowser');

        $mink->getSession()->visit('http://localhost:8000/login');

        /** @var ChromeDriver $driver */
        $driver = $mink->getSession()->getDriver();

        $driver->setValue("//*[@id='_username']", "gmember@member.com");
        $driver->setValue("//*[@id='_password']", "P@ssw0rd");
        $driver->click("//*[@id='login']"); //login

        // Visit homepage
        $mink->getSession()->visit('http://localhost:8000/show/admin/edit_index');

        /** @var ChromeDriver $driver */
        $driver = $mink->getSession()->getDriver();

        $driver->click("//*[@id='showTable']/tbody/tr[2]/td[3]/form[1]/button");

        $this->assertEquals('Edit A cats tale', $driver->getHtml("//*[@id='editShowTitle']")); //checks url of new page
    }

    /**
     * This will test that an edited show saves successfully after changes have been submitted. In this specific case, the test will go in and change the name of a show,
     * save it, and then check to see if the new name is listed on the edit show index page
     * @author Nathan
     * @throws
     */
    public function testThatEditedShowCorrectlySavesWhenSubmitted()
    {
        // Load Mink
        $mink = new Mink(array(
            'showBrowser' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        // Set the default session name
        $mink->setDefaultSessionName('showBrowser');

        $mink->getSession()->visit('http://localhost:8000/login');

        /** @var ChromeDriver $driver */
        $driver = $mink->getSession()->getDriver();

        $driver->setValue("//*[@id='_username']", "gmember@member.com");
        $driver->setValue("//*[@id='_password']", "P@ssw0rd");
        $driver->click("//*[@id='login']"); //login

        // Visit homepage
        $mink->getSession()->visit('http://localhost:8000/show/admin/edit_index');

        /** @var ChromeDriver $driver */
        $driver = $mink->getSession()->getDriver();

        $driver->click("//*[@id='showTable']/tbody/tr[2]/td[3]/form[1]/button");

        $driver->setValue("//*[@id='show_budget']", 200);
        $driver->attachFile("//*[@id='show_pictureFile_file']", 'D:\prj4.ssp\ssp_project\test_images\kittycat.jpg');

        $driver->click("/html/body/form/button");

        $this->assertEquals('http://localhost:8000/show/admin/edit_index', $driver->getCurrentUrl());
        $this->assertEquals(200, $driver->getHtml("//*[@id='showTable']/tbody/tr[2]/td[2]"));
    }

    /**
     * This will test that shows that have a status other than "archived" (so "sold out" for example), are still displayed on the edit shows index page.
     * @author Nathan
     * @throws
     */
    public function testThatShowWithStatusOtherThanArchivedGetsDisplayedOnRegularShowPage()
    {
        // Load Mink
        $mink = new Mink(array(
            'showBrowser' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        // Set the default session name
        $mink->setDefaultSessionName('showBrowser');

        $mink->getSession()->visit('http://localhost:8000/login');

        /** @var ChromeDriver $driver */
        $driver = $mink->getSession()->getDriver();

        $driver->setValue("//*[@id='_username']", "gmember@member.com");
        $driver->setValue("//*[@id='_password']", "P@ssw0rd");
        $driver->click("//*[@id='login']"); //login

        // Visit homepage
        $mink->getSession()->visit('http://localhost:8000/show/admin/edit_index');

        /** @var ChromeDriver $driver */
        $driver = $mink->getSession()->getDriver();

        $this->assertEquals("Bitter cold", $driver->getHtml("//*[@id='showTable']/tbody/tr[4]/td[1]"));
    }
}
//>>>>>>> master
