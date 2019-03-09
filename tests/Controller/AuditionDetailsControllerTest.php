<?php
/**
 * Created by PhpStorm.
 * User: cst237
 * Date: 10/18/2018
 * Time: 3:19 PM
 */

namespace App\Tests;

use App\Entity\AuditionDetails;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class AuditionDetailsControllerTest extends WebTestCase
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
     * This test will make sure that the AuditionDetails page exists
     * Confirms that the route and controller are properly setup
     * Authors: Christopher and Kate
     */
    public function testThatAuditionDetailsPageExists() {
        // Instantiate the client to test against
        $client = static::createClient();

        // Retrieve the page information from the specified route
        $client->request('GET','/auditiondetails/1');

        // Get status code from the client, 200 means connection successful, 500 is error, 404 is not found
        $this->assertEquals(200,$client->getResponse()->getStatusCode());

        // False route to test that the connection will fail
        $client->request('GET','/fakedetails');
        $this->assertEquals(404,$client->getResponse()->getStatusCode());
    }

    /**
     * This tests that when no audition information is available that the title is set to AuditionDetails (our default)
     *  and that the only text contained on the page is 'No Audition Information Found'
     * Authors: Christopher and Kate
     */
    public function testNoAuditionInformationAvailable(){

        // Instantiate the client to test against
        $client = static::createClient();

        // Retrieve the page information from the specified route setup in the "setupBeforeClass"
        $client->request('GET','/auditiondetails/3');

        // Get the HTML information from the client
        $crawler = $client->getCrawler();

        // Filter the crawler to information from the title tag and convert it to text
        $crawlerTitle = $crawler->filter("title")->text();

        // Check that the title is set to "AuditionDetails"
        $this->assertContains('AuditionDetails', $crawlerTitle);

        // Filter the crawler to information from the paragraph tag and convert it to text
        $crawlerPara =  $crawler->filter("p")->text();

        // Check that the single paragraph is set to "No Audition Information Found"
        $this->assertContains('No Audition Information Found', $crawlerPara);
    }

    /**
     * This tests that the proper information will display when the AuditionDetails Object has been set
     * Authors: Christopher and Kate
     */
    public function testAuditionInformationIsAvailable(){
        // Dummy AuditionDetails Object to test against
        $dummyAudition = new AuditionDetails('Footloose','No Audition Information');

        // Instantiate the client to test against
        $client = static::createClient();

        // Retrieve the page information from the specified route setup in the "setupBeforeClass"
        $client->request('GET','/auditiondetails/1');

        // Get the HTML information from the client
        $crawler = $client->getCrawler();

        // Filter the crawler to information from the title tag and convert it to text
        $crawler = $crawler->filter("title")->text();

        // Check that the crawler equals the dummy Object's title value
        $this->assertContains($dummyAudition->getPlayTitle(), $crawler);
    }

    /**
     * This tests the image in 3 different scenarios which were staged in the setUpBeforeClass
     * Authors: Christopher and Kate
     */
    public function testImage(){
        // Instantiate the client to test against
        $client = static::createClient();

        // Retrieve the page information from the specified route for the first scenario (fully filled out object)
        $client->request('GET','/auditiondetails/1');

        // Get the HTML information from the client
        $crawler = $client->getCrawler();

        // Filter the crawler to information from the src attribute
        $crawler = $crawler->filter("img#playTitle")->attr("src");

        // Check that the crawler equals the dummy Object's src text
        $this->assertContains("images", $crawler);

        // Retrieve the page information from the specified route for the second scenario (partially filled out object)
        $client->request('GET','/auditiondetails/2');

        // Get the HTML information from the client
        $crawler = $client->getCrawler();

        // Filter the crawler to information from the src attribute
        $crawler = $crawler->filter("img#playTitle")->attr("src");

        // Check that the crawler equals the dummy Object's src text
        $this->assertContains("images", $crawler);

        // Retrieve the page information from the specified route for the third scenario (empty object)
        $client->request('GET','/auditiondetails/3');

        // Get the HTML information from the client
        $crawler = $client->getCrawler();

        // check that 'img' is not found anywhere on the page
        $this->assertNotContains("img", $crawler->text());

    }

    /**
     * This tests the Details portion of the object in 3 different scenarios which were staged in the setUpBeforeClass
     * Authors: Christopher and Kate
     */
    public function testAuditionDetails(){
        // Instantiate the client to test against
        $client = static::createClient();

        // Retrieve the page information from the specified route for the first scenario (fully filled out object)
        $client->request('GET','/auditiondetails/1');

        // Get the HTML information from the client
        $crawler = $client->getCrawler();

        // Filter the crawler to information from the 'ad' class and convert it to text
        $crawler = $crawler->filter(".ad")->text();

        // Check that the crawler contains Audition Details in 'ad' class
        $this->assertContains("Audition Details", $crawler);

        // Retrieve the page information from the specified route for the second scenario (partially filled out object)
        $client->request('GET','/auditiondetails/2');

        // Get the HTML information from the client
        $crawler = $client->getCrawler();

        //Check that 'audition details' is not displayed within this route
        $this->assertNotContains("Audition Details", $crawler->text());

        // Retrieve the page information from the specified route for the third scenario (empty object)
        $client->request('GET','/auditiondetails/3');

        // Get the HTML information from the client
        $crawler = $client->getCrawler();

        //Check that 'audition details' is not displayed within this route
        $this->assertNotContains("Audition Details", $crawler->text());
    }

    /**
     * This tests the DirectorsMessage portion of the object in 3 different scenarios which were staged in the setUpBeforeClass
     * Authors: Christopher and Kate
     */
    public function testDirectorMessage(){
        // Instantiate the client to test against
        $client = static::createClient();

        // Retrieve the page information from the specified route for the first scenario (fully filled out object)
        $client->request('GET','/auditiondetails/1');

        // Get the HTML information from the client
        $crawler = $client->getCrawler();

        // Filter the crawler to information from the 'dm' class and convert it to text
        $crawler = $crawler->filter(".dm")->text();

        // Check that the crawler contains Director's Message in 'dm' class
        $this->assertContains("Director's Message", $crawler);

        // Retrieve the page information from the specified route for the second scenario (partially filled out object)
        $client->request('GET','/auditiondetails/2');

        // Get the HTML information from the client
        $crawler = $client->getCrawler();

        //Check that 'Director's message' is not displayed within this route
        $this->assertNotContains("Director's Message", $crawler->text());

        // Retrieve the page information from the specified route for the third scenario (empty object)
        $client->request('GET','/auditiondetails/3');

        // Get the HTML information from the client
        $crawler = $client->getCrawler();

        //Check that 'Director's message' is not displayed within this route
        $this->assertNotContains("Director's Message", $crawler->text());
    }

    /**
     * This tests the HowToAudition portion of the object in 3 different scenarios which were staged in the setUpBeforeClass
     * Authors: Christopher and Kate
     */
    public function testHowToAudition(){
        // Instantiate the client to test against
        $client = static::createClient();

        // Retrieve the page information from the specified route for the first scenario (fully filled out object)
        $client->request('GET','/auditiondetails/1');

        // Get the HTML information from the client
        $crawler = $client->getCrawler();

        // Filter the crawler to information from the hta class and convert it to text
        $crawler = $crawler->filter(".hta")->text();

        // Check that the crawler contains How to Audition in 'hta' class
        $this->assertContains("How to Audition", $crawler);

        // Retrieve the page information from the specified route for the second scenario (partially filled out object)
        $client->request('GET','/auditiondetails/2');

        // Get the HTML information from the client
        $crawler = $client->getCrawler();

        //Check that 'How to Audition' is not displayed within this route
        $this->assertNotContains("How to Audition", $crawler->text());

        // Retrieve the page information from the specified route for the third scenario (empty object)
        $client->request('GET','/auditiondetails/3');

        // Get the HTML information from the client
        $crawler = $client->getCrawler();

        //Check that 'How to Audition' is not displayed within this route
        $this->assertNotContains("How to Audition", $crawler->text());
    }

    /**
     * This tests the Synopsis portion of the object in 3 different scenarios which were staged in the setUpBeforeClass
     * Authors: Christopher and Kate
     */
    public function testSynopsis(){
        // Instantiate the client to test against
        $client = static::createClient();

        // Retrieve the page information from the specified route for the first scenario (fully filled out object)
        $client->request('GET','/auditiondetails/1');

        // Get the HTML information from the client
        $crawler = $client->getCrawler();

        // Filter the crawler to information from the syn class and convert it to text
        $crawler = $crawler->filter(".syn")->text();

        // Check that the crawler contains Synopsis in 'syn' class
        $this->assertContains("Synopsis", $crawler);

        // Retrieve the page information from the specified route for the second scenario (partially filled out object)
        $client->request('GET','/auditiondetails/2');

        // Get the HTML information from the client
        $crawler = $client->getCrawler();

        //Check that 'Synopsis' is not displayed within this route
        $this->assertNotContains("Synopsis", $crawler->text());

        // Retrieve the page information from the specified route for the third scenario (empty object)
        $client->request('GET','/auditiondetails/3');

        // Get the HTML information from the client
        $crawler = $client->getCrawler();

        //Check that 'Synopsis' is not displayed within this route
        $this->assertNotContains("Synopsis", $crawler->text());
    }

    /**
     * This tests the Character Summaries portion of the object in 3 different scenarios which were staged in the setUpBeforeClass
     * Authors: Christopher and Kate
     */
    public function testCharacterSummaries(){
        // Instantiate the client to test against
        $client = static::createClient();

        // Retrieve the page information from the specified route for the first scenario (fully filled out object)
        $client->request('GET','/auditiondetails/1');

        // Get the HTML information from the client
        $crawler = $client->getCrawler();

        // Filter the crawler to information from the cs class and convert it to text
        $crawler = $crawler->filter(".cs")->text();

        // Check that the crawler contains Character Summaries in the cs class
        $this->assertContains("Character Summaries", $crawler);

        // Retrieve the page information from the specified route route for the second scenario (partially filled out object)
        $client->request('GET','/auditiondetails/2');

        // Get the HTML information from the client
        $crawler = $client->getCrawler();

        //Check that 'Character Summaries' is not displayed within this route
        $this->assertNotContains("Character Summaries", $crawler->text());

        // Retrieve the page information from the specified route for the third scenario (empty object)
        $client->request('GET','/auditiondetails/3');

        // Get the HTML information from the client
        $crawler = $client->getCrawler();

        //Check that 'Character Summaries' is not displayed within this route
        $this->assertNotContains("Character Summaries", $crawler->text());
    }

    /**
     * This tests the NoteFromDirector portion of the object in 3 different scenarios which were staged in the setUpBeforeClass
     * Authors: Christopher and Kate
     */
    public function testNoteFromDirector(){
        // Instantiate the client to test against
        $client = static::createClient();

        // Retrieve the page information from the specified route for the first scenario (fully filled out object)
        $client->request('GET','/auditiondetails/1');

        // Get the HTML information from the client
        $crawler = $client->getCrawler();

        // Filter the crawler to information from the nfd class and convert it to text
        $crawler = $crawler->filter(".nfd")->text();

        // Check that the crawler contains Note from Director in the 'nfd' class
        $this->assertContains("Note from Director", $crawler);

        // Retrieve the page information from the specified route for the second scenario (partially filled out object)
        $client->request('GET','/auditiondetails/2');

        // Get the HTML information from the client
        $crawler = $client->getCrawler();

        //Check that 'Note form Director' is not displayed within this route
        $this->assertNotContains("Note from Director", $crawler->text());

        // Retrieve the page information from the specified route for the third scenario (empty object)
        $client->request('GET','/auditiondetails/3');

        // Get the HTML information from the client
        $crawler = $client->getCrawler();

        //Check that 'Note form Director' is not displayed within this route
        $this->assertNotContains("Note from Director", $crawler->text());
    }

    /**
     * This tests the Audition Materials portion of the object in 3 different scenarios which were staged in the setUpBeforeClass
     * Authors: Christopher and Kate
     */
    public function testAuditionMaterials(){
        // Instantiate the client to test against
        $client = static::createClient();

        // Retrieve the page information from the specified route for the first scenario (fully filled out object)
        $client->request('GET','/auditiondetails/1');

        // Get the HTML information from the client
        $crawler = $client->getCrawler();

        // Filter the crawler to information from the am class and convert it to text
        $crawler = $crawler->filter(".am")->text();

        // Check that the crawler contains Audition Materials within 'am' class
        $this->assertContains("Audition Materials", $crawler);

        // Retrieve the page information from the specified route for the second scenario (partially filled out object)
        $client->request('GET','/auditiondetails/2');

        // Get the HTML information from the client
        $crawler = $client->getCrawler();

        //Check that 'Audition Materials' is not displayed within this route
        $this->assertNotContains("Audition Materials", $crawler->text());

        // Retrieve the page information from the specified route for the third scenario (empty object)
        $client->request('GET','/auditiondetails/3');

        // Get the HTML information from the client
        $crawler = $client->getCrawler();

        //Check that 'Audition Materials' is not displayed within this route
        $this->assertNotContains("Audition Materials", $crawler->text());
    }
}
