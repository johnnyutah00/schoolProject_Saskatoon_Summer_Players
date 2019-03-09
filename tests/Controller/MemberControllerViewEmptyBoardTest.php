<?php

namespace App\Tests\Entity;


use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Class MemberControllerTest
 * @package App\Tests\Entity
 * @author Ankita R, Taylor B
 * * @date 01/10/2019
 *
 * This class contains function tests that will make sure appropriate error messages are shown for a members
 * personal information if the data is invalid.
 */
class MemberControllerViewEmptyBoardTest extends WebTestCase
{
    private $crawler;
    private $client;
    private $form;

    public function setUp()
    {
        $this->loadFixtures(array(
            'App\DataFixtures\AppFixtures' //purge database
        ));

        $this->client = static::createClient();
        $this->client->request('GET', '/about');
    }

    /**
     * This test check to see that a proper error message is displayed if no directoes are specified in the database (ie. due to errors in them being entered)*
     * @authors Taylor, Ankita
     */
    public function testThatPageErrorIsDisplayedIfNoBoardMembersSet()
    {
        $this->crawler = $this->client->getCrawler();
        $crawler= $this->crawler;
        $client = $this->client;

        $this->client->request('GET', '/about');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $text = $crawler->filter('h2')->eq(0)->text();
        $this->assertEquals($text, 'List of Board Members is not available at this time.');
    }
}
