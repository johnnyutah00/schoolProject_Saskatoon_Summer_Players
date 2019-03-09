<?php
/**
 * Created by PhpStorm.
 * User: cst237
 * Date: 1/24/2019
 * Time: 1:41 PM
 */

namespace App\Tests\Controller;

use App\Controller\SuggestedShowController;
use App\Entity\SuggestedShow;
use App\Controller\MemberController;
use Doctrine\ORM\EntityManager;
use DMore\ChromeDriver\ChromeDriver;
use Behat\Mink\Mink;
use Behat\Mink\Session;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Symfony\Component\HttpFoundation\Request;


class SuggestedSSPShowControllerTest extends WebTestCase
{

    private $crawler;
    private $client;
    private $form;

    public function setUp()
    {

        // Instantiate the client to test against
        $this->client = static::createClient();

        // Retrieve the page information from the specified route
        $this->client->request('GET', '/show');

        $this->crawler = $this->client->getCrawler();


        $this->loadFixtures(array(
            'App\DataFixtures\MemberLoginTestFixture',
            'App\DataFixtures\SuggestedShowFixtures'
        ));

    }


    /**
     * This test will make sure that the "Suggest a Show" label, textbox, and submit button are visible to a
     * user with the role ROLE_MEMBER
     *
     * @author Kate Zawada and MacKenzie Wilson
     */
    public function testSuggestAShowIsVisibleToAMember()
    {
        $this->client->request('GET', '/login');
        $this->crawler = $this->client->getCrawler();


        $form = $this->crawler->selectButton('login')->form();

        $form['_username']->setValue("member@member.com");
        $form['_password']->setValue("P@ssw0rd");

        //Submit the login
        $this->client->submit($form);

        $this->client->followRedirect();
        $this->crawler = $this->client->getCrawler();

        //Make sure redirected to show page
        $this->assertStatusCode(200, $this->client);
        $this->assertEquals($this->client->getCrawler()->getUri(), "http://localhost/show/");


        //Assert that the label is on the page
        $this->assertEquals($this->crawler->filter("#suggestLabel")->text(), "Want to suggest a show? Please enter a title below");
        //Assert that the textbox is on the page.
        $this->assertEquals($this->crawler->filter("#suggested_show_suggestedTitle")->attr('placeholder'), "Enter a title here");
        //Assert that the submit button is on the
        $this->assertEquals($this->crawler->filter("#suggestButton")->text(), "Make Suggestion");
    }

    /**
     * This test will make sure that the "Suggest a Show" label, textbox, and submit button are visible to a
     * user with the role ROLE_BM
     *
     * @author Kate Zawada and MacKenzie Wilson
     */
    public function testSuggestAShowIsVisibleToABoardMember()
    {
        $this->client->request('GET', '/login');
        $this->crawler = $this->client->getCrawler();


        $form = $this->crawler->selectButton('login')->form();

        $form['_username']->setValue("bmember@member.com");
        $form['_password']->setValue("P@ssw0rd");

        //Submit the login
        $this->client->submit($form);

        $this->client->followRedirect();
        $this->crawler = $this->client->getCrawler();

        //Make sure redirected to show page
        $this->assertStatusCode(200, $this->client);
        $this->assertEquals($this->client->getCrawler()->getUri(), "http://localhost/show/");


        //Assert that the label is on the page
        $this->assertEquals($this->crawler->filter("#suggestLabel")->text(), "Want to suggest a show? Please enter a title below");
        //Assert that the textbox is on the page.
        $this->assertEquals($this->crawler->filter("#suggested_show_suggestedTitle")->attr('placeholder'), "Enter a title here");
        //Assert that the submit button is on the
        $this->assertEquals($this->crawler->filter("#suggestButton")->text(), "Make Suggestion");
    }

    /**
     * This test will make sure that the "Suggest a Show" label, textbox, and submit button are visible to a
     * user with the role ROLE_GM
     *
     * @author Kate Zawada and MacKenzie Wilson
     */
    public function testSuggestAShowIsVisibleToAGeneralManager()
    {
        $this->client->request('GET', '/login');
        $this->crawler = $this->client->getCrawler();


        $form = $this->crawler->selectButton('login')->form();

        $form['_username']->setValue("gmember@member.com");
        $form['_password']->setValue("P@ssw0rd");

        //Submit the login
        $this->client->submit($form);

        $this->client->followRedirect();
        $this->crawler = $this->client->getCrawler();

        //Make sure redirected to show page
        $this->assertStatusCode(200, $this->client);
        $this->assertEquals($this->client->getCrawler()->getUri(), "http://localhost/show/");


        //Assert that the label is on the page
        $this->assertEquals($this->crawler->filter("#suggestLabel")->text(), "Want to suggest a show? Please enter a title below");
        //Assert that the textbox is on the page.
        $this->assertEquals($this->crawler->filter("#suggested_show_suggestedTitle")->attr('placeholder'), "Enter a title here");
        //Assert that the submit button is on the
        $this->assertEquals($this->crawler->filter("#suggestButton")->text(), "Make Suggestion");
    }


    /**
     * This test will make sure that the "Suggest a Show" label, textbox, and submit button are not visible to a
     * user who is not logged in to the SSP website
     *
     * @author Kate Zawada and MacKenzie Wilson
     */
    public function testSuggestAShowIsNotVisibleNonMember()
    {
        $this->crawler = $this->client->getCrawler();

        //Assert that the label is on the page
        $this->assertNotEquals($this->crawler->filter("body")->text(), "Want to suggest a show? Please enter a title below");
        //Assert that the textbox is on the page.
        $this->assertNotEquals($this->crawler->filter("body")->attr('placeholder'), "Enter a title here");
        //Assert that the submit button is on the
        $this->assertNotEquals($this->crawler->filter("body")->text(), "Make Suggestion");
    }

    /**
     * This function tests that once the submit button is clicked a new message pops up on the page,
     * and to confirm that there is no error
     *
     * @author Kate Zawada, & MacKenzie Wilson
     */
    public function testShowIsSuccessfullySubmitted()
    {
        $titleToAdd = "CATS";

        // Logging into the website as a ROLE_MEMBER
        $this->client->request('GET', '/login');
        $this->crawler = $this->client->getCrawler();

        $form = $this->crawler->selectButton('login')->form();

        $form['_username']->setValue("member@member.com");
        $form['_password']->setValue("P@ssw0rd");

        //Submit the login
        $this->client->submit($form);

        $this->client->followRedirect();
        $this->crawler = $this->client->getCrawler();

        // Selecting the Suggested Show form
        $this->form = $this->crawler->selectButton('Make Suggestion')->form();

        //Assert that the label is on the page
        $this->assertEquals($this->crawler->filter("#suggestLabel")->text(), "Want to suggest a show? Please enter a title below");
        //Assert that the textbox is on the page.
        $this->assertEquals($this->crawler->filter("#suggested_show_suggestedTitle")->attr('placeholder'), "Enter a title here");
        //Assert that the submit button is on the
        $this->assertEquals($this->crawler->filter("#suggestButton")->text(), "Make Suggestion");

        // Entering a value into the Suggest a Show textbox
        $this->form['suggested_show[suggestedTitle]']->setValue("$titleToAdd");
        $enteredSuggest = new SuggestedShow();
        $enteredSuggest->setSuggestedTitle($titleToAdd);

        // Submits the form
        $this->client->submit($this->form);

        $this->client->request('POST', '/show/');
        $this->crawler = $this->client->getCrawler();

        $this->assertStatusCode(200, $this->client);
        $this->assertEquals($this->client->getCrawler()->getUri(), "http://localhost/show/");
        $this->assertContains("Thank you for entering a suggestion.", $this->crawler->filter(".alert.alert-success")->text());

        //A test to make sure the object is in the database
        $container = $this->client->getContainer();
        $suggestedShow = $container->get('doctrine')->getRepository(SuggestedShow::class)->findOneBy(array('suggestedTitle' => "$titleToAdd"));

        $this->assertEquals($suggestedShow->getSuggestedTitle(), $enteredSuggest->getSuggestedTitle());


    }

    /**
     * This function tests that an error message is shown when trying to submit a blank textbox.
     *
     * @author Kate Zawada and MacKenzie Wilson
     */
    public function testSuggestAShowFieldIsNotSubmittedWhenBlank()
    {
        $this->loadFixtures(array(
            'App\DataFixtures\MemberLoginTestFixture',
            'App\DataFixtures\BlankFixtures'
        ));

        // Logging into the SSP Website
        $this->client->request('GET', '/login');
        $this->crawler = $this->client->getCrawler();

        $form = $this->crawler->selectButton('login')->form();

        $form['_username']->setValue("gmember@member.com");
        $form['_password']->setValue("P@ssw0rd");

        //Submit the login
        $this->client->submit($form);

        $this->client->followRedirect();
        $this->crawler = $this->client->getCrawler();

        ///========= GM Navigates to Suggested Shows page ==========///
        $link = $this->crawler->filter('#SuggestShows')->link();
        $this->client->click($link);

        $this->client->followRedirect();
        $this->crawler = $this->client->getCrawler();

        $this->assertEquals($this->client->getCrawler()->getUri(), "http://localhost/admin/suggested/show/");

        $this->assertStatusCode(200, $this->client);

        // Assert that the page displays "There are no suggested shows at this time." if there are no shows in the database
        $this->assertContains($this->crawler->filter("body > h4")->text(), "There are no suggested shows at this time.");

        //A test to make sure the object is not in the database
        $container = $this->client->getContainer();
        $suggestedShow = $container->get('doctrine')->getRepository(SuggestedShow::class)->findOneBy(array('suggestedTitle' => ""));

        $this->assertTrue(empty($suggestedShow));
    }

    /**
     * This will test that the form will submit when the value entered in the textbox is three characters long
     *
     * @author Kate Zawada and MacKenzie Wilson
     */
    public function testSuggestedShowTitleIsAlmostTooShort()
    {
        $titleToAdd = "CAT";
        // Logging into the SSP Website
        $this->client->request('GET', '/login');
        $this->crawler = $this->client->getCrawler();


        $form = $this->crawler->selectButton('login')->form();

        $form['_username']->setValue("member@member.com");
        $form['_password']->setValue("P@ssw0rd");

        //Submit the login
        $this->client->submit($form);

        $this->client->followRedirect();
        $this->crawler = $this->client->getCrawler();

        // Selecting the Suggested Shows form
        $this->form = $this->crawler->selectButton('Make Suggestion')->form();

        // Setting the textbox to almost too short value
        $this->form['suggested_show[suggestedTitle]']->setValue($titleToAdd);


        // Submits the form
        $this->client->submit($this->form);

        $this->client->request('POST', '/show/');
        $this->crawler = $this->client->getCrawler();

        $this->assertStatusCode(200, $this->client);
        $this->assertEquals($this->client->getCrawler()->getUri(), "http://localhost/show/");
        $this->assertContains("Thank you for entering a suggestion.", $this->crawler->filter(".alert.alert-success")->text());


        //A test to make sure the object is in the database
        $container = $this->client->getContainer();
        $suggestedShow = $container->get('doctrine')->getRepository(SuggestedShow::class)->findOneBy(array('suggestedTitle' => "$titleToAdd"));

        $this->assertEquals($suggestedShow->getSuggestedTitle(), $titleToAdd);

    }

    /**
     * This will test that the page will display an error when the value entered into the Suggested Shows textbox
     * is two characters long
     *
     * @author Kate Zawada and MacKenzie Wilson
     */
    public function testSuggestedShowTitleIsTooShort()
    {
        // Logging into the SSP Website
        $this->client->request('GET', '/login');
        $this->crawler = $this->client->getCrawler();

        $form = $this->crawler->selectButton('login')->form();

        $form['_username']->setValue("member@member.com");
        $form['_password']->setValue("P@ssw0rd");

        //Submit the login
        $this->client->submit($form);

        $this->client->followRedirect();
        $this->crawler = $this->client->getCrawler();

        //Assert that the label is on the page
        $this->assertEquals($this->crawler->filter("#suggestLabel")->text(), "Want to suggest a show? Please enter a title below");
        //Assert that the textbox is on the page.
        $this->assertEquals($this->crawler->filter("#suggested_show_suggestedTitle")->attr('placeholder'), "Enter a title here");
        //Assert that the submit button is on the
        $this->assertEquals($this->crawler->filter("#suggestButton")->text(), "Make Suggestion");

        // Selecting the Suggested Shows form
        $this->form = $this->crawler->selectButton('Make Suggestion')->form();

        // Setting the textbox to almost too short value
        $this->form['suggested_show[suggestedTitle]']->setValue("Ca");

        // Submits the form
        $this->client->submit($this->form);
        $this->crawler = $this->client->getCrawler();

        $this->assertStatusCode(200, $this->client);
        $this->assertContains("Show titles must be three or more characters", $this->crawler->filter('body > div.col-lg-4.col-md-6.col-sm-8.mx-auto > form > div > ul > li')->text());

        //A test to make sure the object is not in the database
        $container = $this->client->getContainer();
        $suggestedShow = $container->get('doctrine')->getRepository(SuggestedShow::class)->findOneBy(array('suggestedTitle' => "Ca"));

        $this->assertTrue(empty($suggestedShow));
    }

    /**
     * This will test that the form will submit when the value entered in the textbox is 255 characters long
     *
     * @author Kate Zawada and MacKenzie Wilson
     */
    public function testSuggestedShowTitleIsAlmostTooLong()
    {
        $titleToAdd = strtoUpper("qwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwert");
        // Logging into the SSP Website
        $this->client->request('GET', '/login');
        $this->crawler = $this->client->getCrawler();


        $form = $this->crawler->selectButton('login')->form();

        $form['_username']->setValue("member@member.com");
        $form['_password']->setValue("P@ssw0rd");

        //Submit the login
        $this->client->submit($form);

        $this->client->followRedirect();
        $this->crawler = $this->client->getCrawler();

        // Selecting the Suggested Shows form
        $this->form = $this->crawler->selectButton('Make Suggestion')->form();

        // Setting the textbox to almost too short value
        $this->form['suggested_show[suggestedTitle]']->setValue($titleToAdd);

        // Submits the form
        $this->client->submit($this->form);

        $this->client->request('POST', '/show/');
        $this->crawler = $this->client->getCrawler();

        $this->assertStatusCode(200, $this->client);
        $this->assertEquals($this->client->getCrawler()->getUri(), "http://localhost/show/");
        $this->assertContains("Thank you for entering a suggestion.", $this->crawler->filter(".alert.alert-success")->text());

        //A test to make sure the object is in the database
        $container = $this->client->getContainer();
        $suggestedShow = $container->get('doctrine')->getRepository(SuggestedShow::class)->findOneBy(array('suggestedTitle' => "$titleToAdd"));

        $this->assertEquals($suggestedShow->getSuggestedTitle(), $titleToAdd);


    }

    /**
     * This will test that the page will display an error when the value entered into the Suggested Shows textbox
     * is 256 characters long
     *
     * @author Kate Zawada and MacKenzie Wilson
     */
    public function testSuggestedShowTitleIsTooLong()
    {
        $titleToAdd = "qqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwert";

        // Logging into the SSP Website
        $this->client->request('GET', '/login');
        $this->crawler = $this->client->getCrawler();


        $form = $this->crawler->selectButton('login')->form();

        $form['_username']->setValue("member@member.com");
        $form['_password']->setValue("P@ssw0rd");

        //Submit the login
        $this->client->submit($form);

        $this->client->followRedirect();
        $this->crawler = $this->client->getCrawler();

        // Selecting the Suggested Shows form
        $this->form = $this->crawler->selectButton('Make Suggestion')->form();

        // Setting the textbox to almost too short value
        $this->form['suggested_show[suggestedTitle]']->setValue($titleToAdd);

        // Submits the form
        $this->client->submit($this->form);
        $this->crawler = $this->client->getCrawler();

        $this->assertStatusCode(200, $this->client);
        $this->assertEquals($this->crawler->filter("body > div.col-lg-4.col-md-6.col-sm-8.mx-auto > form > div > ul > li")->text(), "Show titles must under 255 characters");

        //A test to make sure the object is not in the database
        $container = $this->client->getContainer();
        $suggestedShow = $container->get('doctrine')->getRepository(SuggestedShow::class)->findOneBy(array('suggestedTitle' => strtoupper($titleToAdd)));

        $this->assertTrue(empty($suggestedShow));
    }

    /**
 * This will test to confirm that a General manager with role ROLE_GM can access the Suggested Shows page.
 *
 * @author MacKenzie Wilson & Kate Zawada
 */
    public function testGeneralManagerNavigatesToSuggestedShowsPage()
    {
        // Logging into the SSP Website
        $this->client->request('GET', '/login');
        $this->crawler = $this->client->getCrawler();


        $form = $this->crawler->selectButton('login')->form();

        $form['_username']->setValue("gmember@member.com");
        $form['_password']->setValue("P@ssw0rd");

        //Submit the login
        $this->client->submit($form);

        $this->client->followRedirect();
        $this->crawler = $this->client->getCrawler();


        //Makes sure the Nav bar link is present
        $this->assertContains($this->crawler->filter("#SuggestShows")->text(), "Suggested Shows");
        //Selects and clicks on the Nav bar link
        $link = $this->crawler->filter('#SuggestShows')->link();
        $this->client->click($link);

        $this->client->followRedirect();
        $this->crawler = $this->client->getCrawler();

        $this->assertContains($this->crawler->filter("#suggestTitle")->text(), "Suggested Shows");

    }
    /**
     * This will test the message that shows up when there are no suggested shows in the database
     *
     * @author MacKenzie Wilson & Kate Zawada
     */
    public function testWhenSuggestedShowsDatabaseEmpty()
    {
        // Logging into the SSP Website
        $this->client->request('GET', '/login');
        $this->crawler = $this->client->getCrawler();


        $form = $this->crawler->selectButton('login')->form();

        $form['_username']->setValue("gmember@member.com");
        $form['_password']->setValue("P@ssw0rd");

        //Submit the login
        $this->client->submit($form);

        $this->client->followRedirect();
        $this->crawler = $this->client->getCrawler();


        //Makes sure the Nav bar link is present
        $this->assertContains($this->crawler->filter("#SuggestShows")->text(), "Suggested Shows");
        //Selects and clicks on the Nav bar link
        $link = $this->crawler->filter('#SuggestShows')->link();
        $this->client->click($link);

        $this->client->followRedirect();
        $this->crawler = $this->client->getCrawler();

        $this->assertContains($this->crawler->filter("#suggestTitle")->text(), "Suggested Shows");

    }


    /**
    * This will test that a public user is not able to see the Suggested Shows page in the nav bar and also cannot
    * manually navigate to the Suggested Shows page
    *
    * @author Kate Zawada and MacKenzie Wilson
    */
    public function testPublicCannotSeeOrNavigateToSuggestedShows()
    {
        // User cannot see the "Suggested Shows" nav bar link
        $this->assertNotContains($this->crawler->filter("a")->text(), "Suggested Shows");

        // User cannot manually navigate to the Suggested Shows page -- they will be redirected to the home page
        $this->client->request('GET', '/admin/suggested/show/');
        $this->client->followRedirect();
        $this->crawler = $this->client->getCrawler();

        $this->assertStatusCode(200, $this->client);
        $this->assertEquals($this->client->getCrawler()->getUri(), "http://localhost/login");
    }

    /**
     * This will test that a member is not able to see the Suggested Shows page in the nav bar and also cannot
     * manually navigate to the Suggested Shows page
     *
     * @author Kate Zawada and MacKenzie Wilson
     */
    public function testMemberCannotSeeOrNavigateToSuggestedShows()
    {
        // Logging into the SSP Website
        $this->client->request('GET', '/login');
        $this->crawler = $this->client->getCrawler();


        $form = $this->crawler->selectButton('login')->form();

        $form['_username']->setValue("member@member.com");
        $form['_password']->setValue("P@ssw0rd");

        //Submit the login
        $this->client->submit($form);

        $this->client->followRedirect();
        $this->crawler = $this->client->getCrawler();

        // User cannot see the "Suggested Shows" nav bar link
        $this->assertNotContains($this->crawler->filter("a")->text(), "Suggested Shows");

        // User cannot manually navigate to the Suggested Shows page -- they will be redirected to the home page
        $this->client->request('GET', '/admin/suggested/show');
        $this->client->followRedirect();

        $this->assertStatusCode(200, $this->client);
        $this->assertEquals($this->client->getCrawler()->getUri(), "http://localhost/show/");
    }

    /**
     * This will test that a Board Member is not able to see the Suggested Shows page in the nav bar and also cannot
     * manually navigate to the Suggested Shows page
     *
     * @author Kate Zawada and MacKenzie Wilson
     */
    public function testBoardMemberCannotSeeOrNavigateToSuggestedShows()
    {
        // Logging into the SSP Website
        $this->client->request('GET', '/login');
        $this->crawler = $this->client->getCrawler();


        $form = $this->crawler->selectButton('login')->form();

        $form['_username']->setValue("bmember@member.com");
        $form['_password']->setValue("P@ssw0rd");

        //Submit the login
        $this->client->submit($form);

        $this->client->followRedirect();
        $this->crawler = $this->client->getCrawler();

        // User cannot see the "Suggested Shows" nav bar link
        $this->assertNotEquals($this->crawler->filter("a")->text(), "Suggested Shows");

        // User cannot manually navigate to the Suggested Shows page -- they will be redirected to the home page
        $this->client->request('GET', '/admin/suggested/show');
        $this->client->followRedirect();

        $this->assertStatusCode(200, $this->client);
        $this->assertEquals($this->client->getCrawler()->getUri(), "http://localhost/show/");
    }


    /**
     * This will test that when a member enters in a show name that already exists in the database that duplicate
     * values are not added
     *
     * @author MacKenzie Wilson and Kate Zawada
     */
    public function testDuplicateSuggestedShowTitlesAreNotAddedToDatabase()
    {
        $titleToAdd = "ZORRO";

        ///========= Logging into the SSP Website ==========///
        $this->client->request('GET', '/login');
        $this->crawler = $this->client->getCrawler();


        $form = $this->crawler->selectButton('login')->form();

        $form['_username']->setValue("gmember@member.com");
        $form['_password']->setValue("P@ssw0rd");

        //Submit the login
        $this->client->submit($form);

        $this->client->followRedirect();
        $this->crawler = $this->client->getCrawler();

        $this->form = $this->crawler->selectButton('Make Suggestion')->form();

        ///========= GM Enters Zorro once ==========///
        $this->form['suggested_show[suggestedTitle]']->setValue($titleToAdd);
        // Submits the form
        $this->client->submit($this->form);

        $this->client->request('POST', '/show/');
        $this->crawler = $this->client->getCrawler();

        $this->assertStatusCode(200, $this->client);
        $this->assertContains("Thank you for entering a suggestion.", $this->crawler->filter(".alert.alert-success")->text());


        //A test to make sure the object is in the database
        $container = $this->client->getContainer();
        $suggestedShow = $container->get('doctrine')->getRepository(SuggestedShow::class)->findBy(array('suggestedTitle' => "$titleToAdd"));

        //Asserts to make sure that only one object with the $titleToAdd name exists in the database
        $this->assertEquals(1, count($suggestedShow));

    }

    /**
     * This will check that a general manager can delete a show from the suggested shows page
     *
     * @author MacKenzie Wilson and Kate Zawada
     */
    public function testGeneralManagerCanDeleteSuggestedShows()
        {

            ///========= Logging into the SSP Website ==========///
            $this->client->request('GET', '/login');
            $this->crawler = $this->client->getCrawler();


            $form = $this->crawler->selectButton('login')->form();

            $form['_username']->setValue("gmember@member.com");
            $form['_password']->setValue("P@ssw0rd");

            //Submit the login
            $this->client->submit($form);

            $this->client->followRedirect();
            $this->crawler = $this->client->getCrawler();


            ///========= GM Navigates to Suggested Shows page ==========///
            $link = $this->crawler->filter('#SuggestShows')->link();
            $this->client->click($link);

            $this->client->followRedirect();
            $this->crawler = $this->client->getCrawler();

            $this->assertEquals($this->client->getCrawler()->getUri(), "http://localhost/admin/suggested/show/");

            ///========= GM selects the Zorro show and deletes it ==========///

            //Get Zorro's id from the database
            $container = $this->client->getContainer();
            $suggestedShow = $container->get('doctrine')->getRepository(SuggestedShow::class)->findOneBy(array('suggestedTitle' => "ZORRO"));

            $this->form = $this->crawler->selectButton('title' . $suggestedShow->getId())->form();
            //delete the suggestedshow
            $this->client->submit($this->form);
            $this->crawler = $this->client->getCrawler();

            $this->assertNotContains("ZORRO", $this->crawler->filter("body")->text());

            //A test to make sure the object is not in the database
            $container = $this->client->getContainer();
            $suggestedShow = $container->get('doctrine')->getRepository(SuggestedShow::class)->findBy(array('suggestedTitle' => "ZORRO"));

            $this->assertTrue(empty($suggestedShow));

        }

        public function testCaseSensitiveShowTitles()
        {
            $this->client->request('GET', '/login');
            $this->crawler = $this->client->getCrawler();


            $form = $this->crawler->selectButton('login')->form();

            $form['_username']->setValue("member@member.com");
            $form['_password']->setValue("P@ssw0rd");

            //Submit the login
            $this->client->submit($form);

            $this->client->followRedirect();
            $this->crawler = $this->client->getCrawler();

            //Make sure redirected to show page
            $this->assertStatusCode(200, $this->client);
            $this->assertEquals($this->client->getCrawler()->getUri(), "http://localhost/show/");

            //Assert that the label is on the page
            $this->assertEquals($this->crawler->filter("#suggestLabel")->text(), "Want to suggest a show? Please enter a title below");
            //Assert that the textbox is on the page.
            $this->assertEquals($this->crawler->filter("#suggested_show_suggestedTitle")->attr('placeholder'), "Enter a title here");
            //Assert that the submit button is on the
            $this->assertEquals($this->crawler->filter("#suggestButton")->text(), "Make Suggestion");

            // Selecting the Suggested Shows form
            $this->form = $this->crawler->selectButton('Make Suggestion')->form();

            // Setting the textbox to a blank value
            $this->form['suggested_show[suggestedTitle]']->setValue("Grease");

            // Submits the form
            $this->client->submit($this->form);
            $this->crawler = $this->client->getCrawler();


            // Check to see if "Grease" is not in the database
            $container = $this->client->getContainer();
            $suggestedShow = $container->get('doctrine')->getRepository(SuggestedShow::class)->findOneBy(array('suggestedTitle' => "Grease"));

            $this->assertTrue(empty($suggestedShow));

            // Check to see if "GREASE" is in the database
            $container = $this->client->getContainer();
            $suggestedShow = $container->get('doctrine')->getRepository(SuggestedShow::class)->findOneBy(array('suggestedTitle' => "GREASE"));

            $this->assertTrue(!empty($suggestedShow));

        }
}



