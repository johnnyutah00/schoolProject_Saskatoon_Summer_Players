<?php
namespace App\Tests;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use App\Entity\SSPShow;
use \DateTime;
use DMore\ChromeDriver\ChromeDriver;
use Behat\Mink\Mink;
use Behat\Mink\Session;
use App\Repository\ShowRepository;

/**
 * Class ShowControllerTest
 * @author Nathan
 * @package App\Tests
 *
 *
 * This class will UI test the Edit Shows Index page, the add new show page, and the edit show page.
 *
 * 2) start chrome --disable-gpu --headless --remote-debugging-address=0.0.0.0 --remote-debugging-port=9222
 *
 */
class ShowUploadImageTest extends WebTestCase
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
 * This will test that an image can successfully be uploaded to a show
 * @author Ankita and Nathan
 * @throws
 */
    public function testThatImageCanBeUploadedToShow()
    {
        $client = static::createClient();
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

        $driver->setValue("//*[@id='show_name']", "A A");
        $driver->setValue("//*[@id='show_budget']", 500); //Budget
        $driver->setValue("//*[@id='show_ticketPrice']", 420); //Ticket
        $driver->selectOption("//*[@id='show_location']", "1"); //Location
        $driver->setValue("//*[@id='show_synopsis']", "A cat saves the world!"); //Synopsis
        $driver->attachFile("//*[@id='show_pictureFile_file']", 'D:\prj4.ssp\ssp_project\test_images\kittycat.jpg');
        $driver->setValue("//*[@id='show_ticketLink']", "https://www.google.com");
        $driver->click("/html/body/form/button"); //submit button

        $this->assertEquals('http://localhost:8000/show/admin/edit_index', $driver->getCurrentUrl());
        $this->assertEquals('A A', $driver->getText("//*[@id='showTable']/tbody/tr[6]/td[1]"));

        $container = $client->getContainer();
        $someShow = $container->get('doctrine')->getRepository(SSPShow::class)->findOneBy(array('name' => "A A"));

        $this->assertFalse(empty($someShow->getPicturePath()));
    }


    /**
     * This will test that an image type is incorrect
     * @author Ankita and Nathan
     * @throws
     */
    public function testThatPageIsNotRedirectedIfTheImageTypeIsNotValid()
    {
        $client = static::createClient();
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

        $mink->getSession()->visit('http://localhost:8000/show/admin/new');

        /** @var ChromeDriver $driver */
        $driver = $mink->getSession()->getDriver();


        $driver->setValue("//*[@id='show_name']", "A A");
        $driver->setValue("//*[@id='show_budget']", 500); //Budget
        $driver->setValue("//*[@id='show_ticketPrice']", 420); //Ticket
        $driver->selectOption("//*[@id='show_location']", "1"); //Location
        $driver->setValue("//*[@id='show_synopsis']", "A cat saves the world!"); //Synopsis
        $driver->attachFile("//*[@id='show_pictureFile_file']", 'D:\prj4.ssp\ssp_project\test_images\document.docx');
        $driver->setValue("//*[@id='show_ticketLink']", "https://www.google.com");

        $driver->click("/html/body/form/button"); //submit button

        $this->assertEquals('http://localhost:8000/show/admin/new', $driver->getCurrentUrl());

        $this->assertEquals($driver->getHtml('//*[@id="show"]/div[7]/ul/li'), 'Please upload a valid image');

    }


    /**
     * This will test that an image size does not exceed the size limit
     * @author Ankita and Nathan
     * @throws
     */
    public function testThatPageIsNotRedirectedIfTheImageSizeIsInvalid()
    {
        $client = static::createClient();
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

        $mink->getSession()->visit('http://localhost:8000/show/admin/new');

        /** @var ChromeDriver $driver */
        $driver = $mink->getSession()->getDriver();

        $driver->setValue("//*[@id='show_name']", "A A");
        $driver->setValue("//*[@id='show_budget']", 500); //Budget
        $driver->setValue("//*[@id='show_ticketPrice']", 420); //Ticket
        $driver->selectOption("//*[@id='show_location']", "1"); //Location
        $driver->setValue("//*[@id='show_synopsis']", "A cat saves the world!"); //Synopsis
        $driver->attachFile("//*[@id='show_pictureFile_file']", 'D:\prj4.ssp\ssp_project\test_images\large.png');
        $driver->setValue("//*[@id='show_ticketLink']", "https://www.google.com");

        $driver->click("/html/body/form/button"); //submit button

        $this->assertEquals('http://localhost:8000/show/admin/new', $driver->getCurrentUrl());

        $this->assertEquals($driver->getHtml('//*[@id="show"]/div[7]/ul/li'), 'The file is too large. Allowed maximum size is 2097152 bytes.');
    }

    /**
     * This will test that an image size does not exceed the size limit
     * @author Ankita and Nathan
     * @throws
     */
    public function testThatEditedShowWithNewPicturePathIsUpdated()
    {
        $client = static::createClient();
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

        $container = $client->getContainer();
        $someShow = $container->get('doctrine')->getRepository(SSPShow::class)->findOneBy(array('name' => 'A cats tale'));
        $oldPath = $someShow->getPicturePath();

        // Visit homepage
        $mink->getSession()->visit('http://localhost:8000/show/admin/edit/?showID=1');

        /** @var ChromeDriver $driver */
        $driver = $mink->getSession()->getDriver();

        $driver->attachFile("//*[@id='show_pictureFile_file']", 'D:\prj4.ssp\ssp_project\test_images\kittycat.jpg');
        $driver->click("/html/body/form/button"); //submit button

        $this->assertEquals('http://localhost:8000/show/admin/edit_index', $driver->getCurrentUrl());

        $client = static::createClient();
        $container = $client->getContainer();
        $someShow2 = $container->get('doctrine')->getRepository(SSPShow::class)->findOneBy(array('name' => 'A cats tale'));

        $this->assertFalse($oldPath==$someShow2->getPicturePath());

    }
}


