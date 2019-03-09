<?php
/**
 * User: MacKenzie Wilson, Nathan Dyok
 * Date: 10/16/2018
 * For Saskatoon Summer Players Website.
 *
 * This file contains all the required tests for the SSPShow Entity.
 */

namespace App\tests\Entity;

use App\Entity\Address;
use App\Entity\SSPShow;

use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Component\Validator\Constraints\Date;

class ShowTest extends TestCase
{
    //a SSPShow object to tests
    public $testShow;

    //variables
    private $testName = "CST Play";
    private $testTicketLink = "https://phpunit.dc.com";
    private $testLocation;
    private $testPicturePath = "cat";
    private $testTicketPrice = 34.55;
    private $testSynopsis = "testing";


    /**
     * This function tests the getId function in the entity SSPShow.
     *
     * Tests to make sure then when the SSPShow Entity is instantiated we can retrieve the correct id.
     * Tests to make sure the Id is numeric. If the number was not numeric it would cause an issue in the database.
     *
     * @author MacKenzie W, Nathan D
     *
     * Expected: Current SSPShow id.
     */
    public function testGetId()
    {
        $testDate = new \DateTime();
        $this->testLocation = new Address(1, 225, "George", "New Yorks", "SK", "S7M2P0");
        $this->testShow = new SSPShow(1, $this->testName, $testDate, $this->testTicketPrice, $this->testLocation, $this->testSynopsis, $this->testPicturePath, $this->testTicketLink);

        $this->assertEquals(1, $this->testShow->getId());

        $this->assertTrue(is_numeric($this->testShow->getId()));
    }

    /**
     * This function tests the getName function in entity SSPShow.
     *
     * Tests to see if when the show object is instantiated the correct name is retrieved.
     * Tests to confirm that when the getName function is called it returns a string.
     *
     * @author MacKenzie W, Nathan D
     *
     * Expected: Current SSPShow Name.
     */
    public function testGetName()
    {
        $testDate = new \DateTime();
        $this->testLocation = new Address(1, 225, "George", "New Yorks", "SK", "S7M2P0");
        $this->testShow = new SSPShow(1, $this->testName, $testDate, $this->testTicketPrice, $this->testLocation, $this->testSynopsis, $this->testPicturePath, $this->testTicketLink);

        $this->assertEquals($this->testName, $this->testShow->getName());

        $this->assertTrue(is_string($this->testShow->getName()));
    }

    /**
     * This function tests the setName function in the SSPShow Entity.
     *
     * Tests to see that after the object is instantiated and the Name is set to something different it will
     * Tests to see the maximum amount of characters that name can have can be set.
     * Tests to make sure that the name cannot be set above the maximum character limit.
     * Tests to make sure that the name cannot be only numbers.
     *
     * @author MacKenzie W, Nathan D
     *
     * Expected: Nothing.
     */
    public function testSetName()
    {
        $testDate = new \DateTime();
        $this->testLocation = new Address(1, 225, "George", "New Yorks", "SK", "S7M2P0");
        $this->testShow = new SSPShow(1, $this->testName, $testDate, $this->testTicketPrice, $this->testLocation, $this->testSynopsis, $this->testPicturePath, $this->testTicketLink);

        $testMe = $this->testShow;
        $this->assertEquals($this->testName, $this->testShow->getName());
        $testMe->setName("HelloWorld");
        $this->assertEquals("HelloWorld", $this->testShow->getName());

        $testMe->setName("hhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh"); //boundary case where set word is 50 chars long
        $this->assertEquals("hhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh", $this->testShow->getName());

        $testMe->setName("Hhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh"); //exception case where set name is over 50 characters (amount allowed)

        $testMe->setName(48957497); //exception case where set name is numeric (should be a string)

        $this->testName = "CSTPlay";
    }

    /**
     * This function tests the getDate funciton in the entity SSPShow.
     *
     * Tests to see if when the object is instantiated the correct Date is returned.
     * Tests to see that the result from getDate is an instance of the object DateTime
     *
     * @author MacKenzie W, Nathan D
     *
     * Expected: DateTime object
     */
    public function testGetDate()
    {
        $testDate = new \DateTime();
        $this->testLocation = new Address(1, 225, "George", "New Yorks", "SK", "S7M2P0");
        $this->testShow = new SSPShow(1, $this->testName, $testDate, $this->testTicketPrice, $this->testLocation, $this->testSynopsis, $this->testPicturePath, $this->testTicketLink);

        $this->assertEquals($testDate, $this->testShow->getDate());

        $result = $this->testShow->getDate();
        $this->assertTrue($result instanceof \DateTime);
    }

    /**
     * This function tests to see if the setDate function in the entity SSPShow is working properly
     *
     * Tests that a new date can be set after the object has been instantiated.
     * Tests that a string cannot be entered as a date.
     * Tests that a date cannot be in the past.
     *
     * @author MacKenzie W, Nathan D
     *
     * Expected: nothing
     */
    public function testSetDate()
    {
        $testDate = new \DateTime("2018-05-05");
        $this->testLocation = new Address(1, 225, "George", "New Yorks", "SK", "S7M2P0");
        $this->testShow = new SSPShow(1, $this->testName, $testDate, $this->testTicketPrice, $this->testLocation, $this->testSynopsis, $this->testPicturePath, $this->testTicketLink);

        $this->assertEquals($testDate, $this->testShow->getDate());
        $this->testShow->setDate(new \DateTime("2019-05-05"));
        $this->assertEquals(new \DateTime("2019-05-05"), $this->testShow->getDate());





        $this->testShow->setDate(new \DateTime("2016-01-16"));
    }

    /**
     * This function tests the getTicketPrice function from the SSPShow entity.
     *
     * Tests to make sure that when the object is instatiated the returned value of getTicketPrice is the same value.
     * Test to make sure that the Ticket price is a numeric value.
     *
     * @author MacKenzie W, Nathan D
     *
     * Expected: A decimal number
     */
    public function testGetTicketPrice()
    {
        $testDate = new \DateTime();
        $this->testLocation = new Address(1, 225, "George", "New Yorks", "SK", "S7M2P0");
        $this->testShow = new SSPShow(1, $this->testName, $testDate, $this->testTicketPrice, $this->testLocation, $this->testSynopsis, $this->testPicturePath, $this->testTicketLink);

        $this->assertEquals($this->testTicketPrice, $this->testShow->getTicketPrice());

        $this->assertTrue(is_numeric($this->testShow->getTicketPrice()));
    }

    /**
     * This function tests to see if the setTicketPrice function in the entity SSPShow is working properly
     *
     * Tests that a new Ticket Price can be set after the object has been instantiated.
     * Tests that a the ticket price cannot be a string.
     *
     * @author MacKenzie W, Nathan D
     *
     * Expected: nothing
     */
    public function testSetTicketPrice()
    {
        $testDate = new \DateTime();
        $this->testLocation = new Address(1, 225, "George", "New Yorks", "SK", "S7M2P0");
        $this->testShow = new SSPShow(1, $this->testName, $testDate, $this->testTicketPrice, $this->testLocation, $this->testSynopsis, $this->testPicturePath, $this->testTicketLink);

        $this->assertEquals($this->testTicketPrice, $this->testShow->getTicketPrice());
        $this->testShow->setTicketPrice(1.23);
        $this->assertEquals(1.23, $this->testShow->getTicketPrice());

    }

    /**
     * This function tests the getTicketPrice function from the SSPShow entity.
     *
     * Tests to make sure that when the object is instatiated the returned value of getTicketLink is the same value.
     * Test to make sure that the Ticket Link is a string.
     *
     * @author MacKenzie W, Nathan D
     *
     * Expected: A Ticket Link string
     */
    public function testGetTicketLink()
    {
        $testDate = new \DateTime();
        $this->testLocation = new Address(1, 225, "George", "New Yorks", "SK", "S7M2P0");
        $this->testShow = new SSPShow(1, $this->testName, $testDate, $this->testTicketPrice, $this->testLocation, $this->testSynopsis, $this->testPicturePath, $this->testTicketLink);

        $this->assertEquals($this->testTicketLink, $this->testShow->getTicketLink());

        $this->assertTrue(is_string($this->testShow->getPicturePath()));
    }

    /**
     * This function tests to see if the setTicketLink function in the entity SSPShow is working properly
     *
     * Tests that a new Ticket link can be set after the object has been instantiated.
     * Tests that an error does not occur when it reaches 50 characters
     * Tests that the string cannot be over 50 characters
     *
     * @author MacKenzie W, Nathan D
     *
     * Expected: nothing
     */
    public function testSetTicketLink()
    {
        $testDate = new \DateTime();
        $this->testLocation = new Address(1, 225, "George", "New Yorks", "SK", "S7M2P0");
        $this->testShow = new SSPShow(1, $this->testName, $testDate, $this->testTicketPrice, $this->testLocation, $this->testSynopsis, $this->testPicturePath, $this->testTicketLink);

        $this->assertEquals($this->testTicketLink, $this->testShow->getTicketLink());
        $this->testShow->setTicketLink("pizza");
        $this->assertEquals("pizza", $this->testShow->getTicketLink());

        $this->testShow->setTicketLink("hhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh"); //boundary case where set link is 100 chars long
        $this->assertEquals("hhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh", $this->testShow->getTicketLink());




    }

    /**
     * This function tests the getLocation function from the SSPShow entity.
     *
     * Tests to make sure that when the object is instatiated the returned value of getLocation is the same object that was placed in.
     * Test to make sure that the Location is an instance of the Address object.
     *
     * @author MacKenzie W, Nathan D
     *
     * Expected: A decimal number
     */
    public function testGetLocation()
    {
        $testDate = new \DateTime();
        $this->testLocation = new Address(1, 225, "George", "New Yorks", "SK", "S7M2P0");
        $this->testShow = new SSPShow(1, $this->testName, $testDate, $this->testTicketPrice, $this->testLocation, $this->testSynopsis, $this->testPicturePath, $this->testTicketLink);

        $this->assertEquals($this->testLocation, $this->testShow->getLocation());

        $this->assertTrue($this->testShow->getLocation() instanceof Address);
    }

    /**
     * This function tests to see if the setLocation function in the entity SSPShow is working properly
     *
     * Tests that a new Location can be set after the object has been instantiated.
     * Tests that the Location cannot be a string or a number.
     *
     * @author MacKenzie W, Nathan D
     *
     * Expected: nothing
     */
    public function testSetLocation()
    {
        $testDate = new \DateTime();
        $this->testLocation = new Address(1, 225, "George", "New Yorks", "SK", "S7M2P0");
        $this->testShow = new SSPShow(1, $this->testName, $testDate, $this->testTicketPrice, $this->testLocation, $this->testSynopsis, $this->testPicturePath, $this->testTicketLink);

        $this->assertEquals($this->testLocation, $this->testShow->getLocation());
        $someAddress = new Address(89, 879, "George", "New Yorks", "SK", "S7M2P0");
        $this->testShow->setLocation($someAddress);
        $this->assertEquals($someAddress, $this->testShow->getLocation());



    }
    /**
     * This function tests the getSynopsis function from the SSPShow entity.
     *
     * Tests to make sure that when the object is instatiated the returned value of getSynopsis is the same value that was placed in.
     * Test to make sure that the synopsis is a string.
     *
     * @author MacKenzie W, Nathan D
     *
     * Expected: a string of indefinate lenght
     */
    public function testGetSynopsis()
    {
        $testDate = new \DateTime();
        $this->testLocation = new Address(1, 225, "George", "New Yorks", "SK", "S7M2P0");
        $this->testShow = new SSPShow(1, $this->testName, $testDate, $this->testTicketPrice, $this->testLocation, $this->testSynopsis, $this->testPicturePath, $this->testTicketLink);

        $this->assertEquals($this->testSynopsis, $this->testShow->getSynopsis());

        $this->assertTrue(is_string($this->testShow->getSynopsis()));
    }
    /**
     * This function tests to see if the setSynopsis function in the entity SSPShow is working properly
     *
     * Tests that a new Ticket link can be set after the object has been instantiated.
     * There is no limit in length of this value
     *
     * @author MacKenzie W, Nathan D
     *
     * Expected: nothing
     */
    public function testSetSynopsis()
    {
        $testDate = new \DateTime();
        $this->testLocation = new Address(1, 225, "George", "New Yorks", "SK", "S7M2P0");
        $this->testShow = new SSPShow(1, $this->testName, $testDate, $this->testTicketPrice, $this->testLocation, $this->testSynopsis, $this->testPicturePath, $this->testTicketLink);


        $this->assertEquals($this->testSynopsis, $this->testShow->getSynopsis());
        $this->testShow->setSynopsis("something happened");
        $this->assertEquals("something happened", $this->testShow->getSynopsis());
    }
    /**
     * This function tests the getPicturePath function from the SSPShow entity.
     *
     * Tests to make sure that when the object is instatiated the returned value of getPicturePath is the same value that was placed in.
     * Test to make sure that the Picture Path is a string.
     *
     * @author MacKenzie W, Nathan D
     *
     * Expected: A decimal number
     */
    public function testGetPicturePath()
    {
        $testDate = new \DateTime();
        $this->testLocation = new Address(1, 225, "George", "New Yorks", "SK", "S7M2P0");
        $this->testShow = new SSPShow(1, $this->testName, $testDate, $this->testTicketPrice, $this->testLocation, $this->testSynopsis, $this->testPicturePath, $this->testTicketLink);

        $this->assertEquals($this->testPicturePath, $this->testShow->getPicturePath());

        $this->assertTrue(is_string($this->testShow->getPicturePath()));
    }

    /**
     * This function tests to see if the setPicturePath function in the entity SSPShow is working properly
     *
     * Tests that a new Picture Path can be set after the object has been instantiated.
     * Tests that an error does not occur when it reaches 100 characters
     * Tests that the string cannot be over 100 characters
     *
     * @author MacKenzie W, Nathan D
     *
     * Expected: nothing
     */
    public function testSetPicturePath()
    {
        $testDate = new \DateTime();
        $this->testLocation = new Address(1, 225, "George", "New Yorks", "SK", "S7M2P0");
        $this->testShow = new SSPShow(1, $this->testName, $testDate, $this->testTicketPrice, $this->testLocation, $this->testSynopsis, $this->testPicturePath, $this->testTicketLink);

        $this->assertEquals($this->testPicturePath, $this->testShow->getPicturePath());
        $this->testShow->setPicturePath("some path yo");
        $this->assertEquals("some path yo", $this->testShow->getPicturePath());

        $this->testShow->setPicturePath("hhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh"); //boundary case where set link is 100 chars long
        $this->assertEquals("hhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh", $this->testShow->getPicturePath());


    }





}
