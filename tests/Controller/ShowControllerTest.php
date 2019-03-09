<?php
namespace App\Tests;

use App\Entity\Address;
use App\Entity\SSPShow;
use DMore\ChromeDriver\ChromeDriver;
use Behat\Mink\Mink;
use Behat\Mink\Session;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class ShowControllerTest extends WebTestCase
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

    /**
     * Testing that the webpage only displays shows that are in the future
     * Ensure that the server and Chrome has been run in headless mode.
     *
     * From command prompt:
     * 1) php bin/console server:run
     * 2) start chrome --disable-gpu --headless --remote-debugging-address=0.0.0.0 --remote-debugging-port=9222
     *
     * Author: Cory N and NathanD
     * @throws
     */
    public function testFutureShow()
    {
        // Load ChromeDriver
        $mink = new Mink(array(
            'FutureShowBrowser' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        // set the default session name
        $mink->setDefaultSessionName('FutureShowBrowser');

        // visit homepage
        $mink->getSession()->visit('http://localhost:8000');

        /** @var ChromeDriver $driver */
        $driver = $mink->getSession()->getDriver();

        // Target future show link button
        $driver->click("//a[@id='futureShowsBtn']");

        // Capture image to verify content
        // $driver->captureScreenshot('ShowControllerTestImages/futureShows.png');

        // Verify the correct text is being displayed
        $futureText1 = $driver->getHtml("/html/body/div[3]/div[2]/div[1]/h1");
        $this->assertEquals('Christmas Story', $futureText1);

        // Verify the correct text is being displayed
        $futureText2 = $driver->getHtml("/html/body/div[3]/div[3]/div[1]/h1");
        $this->assertEquals('Mary Poppins', $futureText2);

        // Very the status is correct
        $currentStatus = $driver->getText("/html/body/div[3]/div[1]/div/p");
        $this->assertEquals('Upcoming Shows', $currentStatus);

        // Check to ensure the future shows are visible
        $isVisible = $driver->isVisible("//*[@class='container-fluid futureShow']");
        $this->assertTrue($isVisible);

        // Check to ensure current shows are not visible
        $isNotVisible = $driver->isVisible("//div[@class='container-fluid currentShow']");
        $this->assertFalse($isNotVisible);

        $futureDisplayAttr = $driver->getAttribute("//div[@class='container-fluid futureShow']", 'style');
        $this->assertEquals('', $futureDisplayAttr);

        $currentDisplayAttr = $driver->getAttribute("//div[@class='container-fluid currentShow']", 'style');
        $this->assertEquals('display: none;', $currentDisplayAttr);


    }


    /**
     * Testing that the webpage only displays the current show
     * Author: Cory N and Nathan D
     * @throws
     */
    public function testCurrentShow()
    {
        // Load Mink
        $mink = new Mink(array(
            'CurrentShowBrowser' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        // Set the default session name
        $mink->setDefaultSessionName('CurrentShowBrowser');

        // Visit homepage
        $mink->getSession()->visit('http://localhost:8000/show');

        /** @var ChromeDriver $driver */
        $driver = $mink->getSession()->getDriver();

        // Target future show link button
        $driver->click("//a[@id='currentShowsBtn']");

        // Capture screen shot to verify content
        // $driver->captureScreenshot('ShowControllerTestImages/currentShows.png');

        // Check to ensure text is displayed properly
        $currentText = $driver->getHtml("/html/body/div[2]/div[2]/div[1]/h1");
        $this->assertEquals('Sweeney Todd', $currentText);

        // Check to ensure the current status is correct
        $currentStatus = $driver->getHtml("/html/body/div[2]/div[1]/div/p");
        $this->assertEquals('Now Playing', $currentStatus);

        // Check to ensure the future shows are visible
        $isVisible = $driver->isVisible("//div[@class='container-fluid currentShow']");
        $this->assertTrue($isVisible);

        // Check to ensure current shows are not visible
        $isNotVisible = $driver->isVisible("//div[@class='container-fluid futureShow']");
        $this->assertFalse($isNotVisible);

        // Check to make sure that current shows display style is not set to none
        $currentDisplayAttr = $driver->getAttribute("//div[@class='container-fluid currentShow']", 'style');
        $this->assertEquals('', $currentDisplayAttr);

        $futureDisplayAttr = $driver->getAttribute("//div[@class='container-fluid futureShow']", 'style');
        $this->assertEquals('display: none;', $futureDisplayAttr);
    }

    /**
     * Testing that the webpage only displays shows that have already happened
     * Author: Cory N and Nathan D
     * @throws
     */
    public function testPastShow()
    {
        // Load ChromeDriver
        $mink = new Mink(array(
            'PastShowBrowser' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
        // set the default session name
        $mink->setDefaultSessionName('PastShowBrowser');

        // visit homepage
        $mink->getSession()->visit('http://localhost:8000');

        /** @var ChromeDriver $driver */
        $driver = $mink->getSession()->getDriver();

        // Target future show link button
        $driver->click("//a[@id='prevShowsBtn']");

        // Capture screen shot to verify content
        // $driver->captureScreenshot('ShowControllerTestImages/pastShows.png');

        // Check to ensure text is displayed properly
        $pastText1 = $driver->getHtml("/html/body/div[2]/div[2]/div[1]/h1");
        $pastText2 = $driver->getHtml("/html/body/div[2]/div[3]/div[1]/h1");

        $this->assertEquals('Hamlet', $pastText1);
        $this->assertEquals('Come From Away', $pastText2);

        $currentStatusText = $driver->getHtml("//*[@class='currentStatus']");
        $this->assertEquals('Previous Shows', $currentStatusText);

        // Check to ensure the future shows are visible
        $isVisible = $driver->isVisible("//div[@class='container-fluid pastShow']");
        $this->assertTrue($isVisible);

    }

    /**
     * Tests that a valid show has ticket link, the button is created, clickable, and has the correct link.
     */
    public function testButtonIsTheSameURLAsDBObject()
    {
        //Gets access to the database and writes a dummy show and address entry (address is nessecary for show to be created)
        $client = static::createClient();
        $container = $client->getContainer();
        $entityManager = $container->get('doctrine')->getManager();
        $addr = new Address('', 225, "George", "New Yorks", "SK", "S7M2P0");
        $entityManager ->persist($addr);
        $show = new SSPShow('','Generic Title',new \DateTime(),25.99, $addr ,'','','www.saskpolytech.ca');
        $entityManager ->persist($show);
        $entityManager ->flush();

        //requests the page of the show ID that was created above
        $client->request('GET','/show/'.$show->getId());
        $crawler = $client->getCrawler();

        //Using the show URL, ensure the form is actually redirecting to the correct ticket site provided by the show object
        $this->assertEquals('http://'.$show->getTicketLink(), $crawler->filter('form')->attr('action'));

        //Ensure there are no final errors
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //Click the show button and get the URI of the page that it redirects to. Checks that it's the same as above.
        $form = $crawler->selectButton('Buy Tickets Here!')->form();
        $crawler = $client->submit($form);
        $URI = $crawler->getUri();
        echo $URI;
        $this->assertEquals($URI, 'http://'.$show->getTicketLink());
    }

    /**
     * Tests that if  a valid show does not have a ticket link, the button is created but non clickable (disabled).
     */
    public function testButtonDoesNotHaveLinkIfShowHasNoLink()
    {
        //Gets access to the database and writes a dummy show with *no* ticket link and address entry (address is necessary for show to be created)
        $client = static::createClient();
        $container = $client->getContainer();
        $entityManager = $container->get('doctrine')->getManager();
        $addr = new Address('', 225, "George", "New Yorks", "SK", "S7M2P0");
        $entityManager ->persist($addr);
        $show = new SSPShow('','Generic Title 2',new \DateTime(),675.99, $addr ,'','','');
        $entityManager ->persist($show);
        $entityManager ->flush();

        //requests the page of the show ID that was created above
        $client->request('GET','/show/'.$show->getId());
        $crawler = $client->getCrawler();

        //Crawls the page to see if the button has an attribute of disabled, since there is no ticket link
        $this->assertEquals('disabled', $crawler->filter('button')->attr('disabled'));
        //Ensure there are no final errors
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * Tests that a button is not created if the show doesn't exist
     */
    public function testNoButtonCreatedIfPageDoesntExist()
    {
        //Create client, crawler and request a page for a show that doesn't exist
        $client = static::createClient();
        $client->request('GET','/show/cnsjdfnsdoisdfdds');
        $crawler = $client->getCrawler();

        //Ensure page doesn't exist and no page nor button was generated (button isn't made if the twig is never called ie. Controller never rendered the twig)
        $this->assertEquals(500, $client->getResponse()->getStatusCode());
    }

    public function testNavigationOnShowPage()
    {
        $client = static::createClient();
        $client->request('GET', '/show/');
        $crawler = $client->getCrawler();

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Verify that we're on the "show" page
        $currentURI = $crawler->getUri();
        $this->assertEquals('http://localhost/show/', $currentURI);

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
     * This test will ensure that the user is redirected to the shows page
     * after clicking on the "Our shows" link from the navigation menu
     */
    public function testThatUserIsRedirectedToOurShows()
    {
        // Get client to start on the show page
        $client = static::createClient();
        $client->request('GET', '/');
        $crawler = $client->getCrawler();

        // Target the second list item and verify text is "Our Shows"
        $showLinkText = $crawler->filter('li.nav-item')->eq(1)->text();
        $this->assertEquals('Our Shows', $showLinkText);
        // Verify that the "Our Shows" link redirects properly
        $showLink = $crawler->filter('li.nav-item a')->eq(1)->link();
        $crawler = $client->click($showLink);
        $this->assertEquals('http://localhost/show/', $crawler->getUri());
        $text = $crawler->filter('h1')->eq(0)->text();
        $this->assertEquals('Sweeney Todd',$text);
    }



}
