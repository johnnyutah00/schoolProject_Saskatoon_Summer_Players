<?php

namespace App\Tests\Entity;


use Liip\FunctionalTestBundle\Test\WebTestCase;
use DMore\ChromeDriver\ChromeDriver;
use Behat\Mink\Mink;
use Behat\Mink\Session;
/**
 * Class MemberControllerTest
 * @package App\Tests\Entity
 * @author Ankita R, Taylor B
 * * @date 01/10/2019
 *
 * This class contains function tests that will make sure appropriate error messages are shown for a members
 * personal information if the data is invalid.
 */
class MemberControllerViewBoardTest extends WebTestCase
{
    private $crawler;
    private $client;
    private $form;

    public function setUp()
    {
        $this->client = static::createClient();
        $this->client->request('GET', '/about');
    }

    /**
     * This test ensures that the About pages exists and loads without an error. it does not test for any content (later tests do this)
     * @authors Taylor, Ankita
     */
    public function testThatAboutPageExists()
    {
        $this->crawler = $this->client->getCrawler();

        $client = $this->client;
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * This test check to see that the page has been loaded, is accessible, and a header displaying the title "Board of Directors" is shown.
     * @authors Taylor, Ankita
     */
    public function testThatContentIsOnAboutUsPage()
    {
        $this->loadFixtures(array(
            'App\DataFixtures\AuditionDetailsFixtures',
            'App\DataFixtures\AllShowsFixtures',
            'App\DataFixtures\AppFixtures',
            'App\DataFixtures\AddMembersFixtures',
            'App\DataFixtures\MemberLoginTestFixture'

        ));

        $this->crawler = $this->client->getCrawler();
        $crawler= $this->crawler;
        $client = $this->client;
        $this->client->request('GET', '/about');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $text = $crawler->filter('h1')->eq(0)->text();
        $this->assertEquals($text, 'Board Members');
    }

    /**
     * This test checks to see that only members of the board are displayed, since board members and actual members
     * are stored as the same object in the DB. It checks four previously made fixtures and compares the page contents.
     * Test fails if there are too many members displayed, or not enough.
     * @authors Taylor, Ankita
     */
    public function testThatOnlyBoardOfDirectorsIsLoaded()
    {
        $this->loadFixtures(array(
            'App\DataFixtures\AuditionDetailsFixtures',
            'App\DataFixtures\AllShowsFixtures',
            'App\DataFixtures\AppFixtures',
            'App\DataFixtures\AddMembersFixtures'
        ));

        $this->crawler = $this->client->getCrawler();
        $crawler= $this->crawler;
        $client = $this->client;


        $this->client->request('GET', '/about');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $text = $crawler->filter('h3')->eq(0)->text();
        $this->assertEquals($text, 'Rick Caron');

        $text = $crawler->filter('h3')->eq(1)->text();
        $this->assertEquals($text, 'Cyril Coupal');

        $text = $crawler->filter('h3')->eq(2)->text();
        $this->assertEquals($text, 'Bryce Barrie');

        $text = $crawler->filter('h3')->eq(3)->text();
        $this->assertEquals($text, 'Wade Lahoda');



        $this->assertCount(10, $crawler->filter('h3'));
    }


    /**
     * Checks if the board member exists on the About Us page.
     * @throws \Behat\Mink\Exception\DriverException
     * @throws \Behat\Mink\Exception\UnsupportedDriverActionException
     */
    public function testThatUserIsDisplayedOnThePage()
    {
        // Load ChromeDriver
        $mink = new Mink(array(
            'MemberInformation' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        // set the default session name
        $mink->setDefaultSessionName('MemberInformation');

        // visit homepage
        $mink->getSession()->visit('http://localhost:8000');

        /** @var ChromeDriver $driver */
        $driver = $mink->getSession()->getDriver();

        // Target future show link button
        $driver->click("//a[@id='aboutus']");

        // Capture image to verify content
       // $driver->captureScreenshot('ShowControllerTestImages/futureShows.png');

        // Verify the correct text is being displayed
        $futureText1 = $driver->getHtml("/html/body/div[1]/div/div[9]/h3");
        $this->assertEquals('Kel Boechler', $futureText1);
    }

}
