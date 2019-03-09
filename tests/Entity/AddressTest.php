<?php
/**
 * User: MacKenzie Wilson, Nathan Dyok
 * Date: 10/30/2018
 * SERVER LOGIC BLOCK
 *
 * This is a testing class that will
 */

namespace App\Entity;

use App\Entity\Address;


use Symfony\Bundle\FrameworkBundle\Tests\TestCase;


class AddressTest extends TestCase
{
    private $testAddress;
    private $id = 1;
    private $testHAN = 225;
    private $testStName = 'George';
    private $testCity = 'New York';
    private $testProv = 'Saskatchewan';
    private $testPostalC = 'S7M2P0';

    /**
     * This function tests the getId function in the entity Address.
     *
     * Tests to make sure then when the Address Entity is instantiated we can retrieve the correct id.
     * Tests to make sure the Id is numeric. If the number was not numeric it would cause an issue in the database.
     *
     * @author MacKenzie W, Nathan D
     *
     * Expected: Current Address id.
     */
    public function testGetId()
    {
        $this->testAddress= new Address($this->id, $this->testHAN, $this->testStName, $this->testCity, $this->testProv, $this->testPostalC);

        $this->assertEquals(1, $this->testAddress->getId());

        $this->assertTrue(is_numeric($this->testAddress->getId()));
    }
    /**
     * This function tests the getApartNum function from the Address entity.
     *
     * Tests to make sure that when the object is instatiated the returned value of getApartNum is the same value.
     * Test to make sure that the House/Apartment Number is an int value.
     *
     * @author MacKenzie W, Nathan D
     *
     * Expected: an integer
     */
    public function testGetHouseApartNum()
    {
        $this->testAddress= new Address($this->id, $this->testHAN, $this->testStName, $this->testCity, $this->testProv, $this->testPostalC);

        $this->assertEquals($this->testHAN, $this->testAddress->getHouseApartNum());
        $this->assertTrue(is_int($this->testAddress->getHouseApartNum()));

    }
    /**
     * This function tests the setApartNum function from the Address entity.
     *
     * Tests that a new House/Apartment Number can be set after the object has been instantiated.
     * Tests that the House/Apartment Number cannot be set to a string.
     *
     * @author MacKenzie W, Nathan D
     *
     * Expected: nothing
     */
    public function testSetHouseApartNum()
    {
        $this->testAddress= new Address($this->id, $this->testHAN, $this->testStName, $this->testCity, $this->testProv, $this->testPostalC);

        $this->assertEquals($this->testHAN, $this->testAddress->getHouseApartNum());
        $this->testAddress->setHouseApartNum(123);
        $this->assertEquals(123,$this->testAddress->getHouseApartNum());

        $this->expectException(\TypeError::class);
        $this->testAddress->setHouseApartNum("hello");

    }
    /**
     * This function tests the getStreetName function from the Address entity.
     *
     * Tests to make sure that when the object is instatiated the returned value of getStreetName is the same value.
     * Test to make sure that the Street name is a string.
     *
     * @author MacKenzie W, Nathan D
     *
     * Expected: a string
     */
    public function testGetStreetName()
    {
        $this->testAddress= new Address($this->id, $this->testHAN, $this->testStName, $this->testCity, $this->testProv, $this->testPostalC);

        $this->assertEquals($this->testStName, $this->testAddress->getStreetName());
        $this->assertTrue(is_string($this->testAddress->getStreetName()));
    }


    /**
     * This function tests the setStreetName function from the Address entity.
     *
     * Tests that a new Street Name can be set after the object has been instantiated.
     * Tests that the Street Name is not set to be over 50 characters.
     * Tests that the Street Name cannot be set to only a number.
     *
     * @author MacKenzie W, Nathan D
     *
     * Expected: nothing
     */
    public function testSetStreetName()
    {
        $this->testAddress= new Address($this->id, $this->testHAN, $this->testStName, $this->testCity, $this->testProv, $this->testPostalC);

        $this->assertEquals($this->testStName, $this->testAddress->getStreetName());
        $this->testAddress->setStreetName("hhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh"); //boundary case where set word is 50 chars long
        $this->assertEquals("hhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh", $this->testAddress->getStreetName());

        $this->expectException(\InvalidArgumentException::class);
        $this->testAddress->setStreetName("Hhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh"); //exception case where set name is over 50 characters (amount allowed)

        $this->expectException(\InvalidArgumentException::class);
        $this->testAddress->setStreetName(48957497); //exception case where set name is only numeric (should be a string)

    }

    /**
     * This function tests the getCity function in the entity Address.
     *
     * Tests to make sure then when the Address Entity is instantiated we can retrieve the correct value.
     * Test to make sure that the City name is a string.
     *
     * @author MacKenzie W, Nathan D
     *
     * Expected: a string
     */
    public function testGetCity()
    {
        $this->testAddress= new Address($this->id, $this->testHAN, $this->testStName, $this->testCity, $this->testProv, $this->testPostalC);

        $this->assertEquals($this->testCity, $this->testAddress->getCity());
        $this->assertTrue(is_string($this->testAddress->getCity()));

    }

    /**
     * This function tests the setCity function from the Address entity.
     *
     * Tests that a new City can be set after the object has been instantiated.
     * Tests that the City cannot be set to only a numeric.
     *
     * @author MacKenzie W, Nathan D
     *
     * Expected: nothing
     */
    public function testSetCity()
    {
        $this->testAddress= new Address($this->id, $this->testHAN, $this->testStName, $this->testCity, $this->testProv, $this->testPostalC);

        $this->assertEquals($this->testCity, $this->testAddress->getCity());
        $this->testAddress->setCity("hhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh"); //boundary case where set word is 50 chars long
        $this->assertEquals("hhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh", $this->testAddress->getCity());

        $this->expectException(\InvalidArgumentException::class);
        $this->testAddress->setCity("Hhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh"); //exception case where set name is over 50 characters (amount allowed)

        $this->expectException(\InvalidArgumentException::class);
        $this->testAddress->setCity(48957497); //exception case where set name is only numeric (should be a string)

    }

    /**
     * This function tests the getProvince function in the entity Address.
     *
     * Tests to make sure then when the Address Entity is instantiated we can retrieve the correct Province.
     * Test to make sure that the Province is a string.
     *
     * @author MacKenzie W, Nathan D
     *
     * Expected: a string
     */
    public function testGetProvince()
    {
        $this->testAddress= new Address($this->id, $this->testHAN, $this->testStName, $this->testCity, $this->testProv, $this->testPostalC);

        $this->assertEquals($this->testProv, $this->testAddress->getProvince());
        $this->assertTrue(is_string($this->testAddress->getProvince()));
    }

    /**
     * This function tests the setProvincefunction from the Address entity.
     *
     * Tests that a new Province can be set after the object has been instantiated.
     * Tests that the Province is not aboe 50 chars long
     * Tests that the Province cannot be set to be only a numeric.
     *
     * @author MacKenzie W, Nathan D
     *
     * Expected: nothing
     */
    public function testSetProvince()
    {
        $this->testAddress= new Address($this->id, $this->testHAN, $this->testStName, $this->testCity, $this->testProv, $this->testPostalC);

        $this->assertEquals($this->testProv, $this->testAddress->getProvince());
        $this->testAddress->setProvince("hhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh"); //boundary case where set word is 50 chars long
        $this->assertEquals("hhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh", $this->testAddress->getProvince());

        $this->expectException(\InvalidArgumentException::class);
        $this->testAddress->setProvince("Hhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh"); //exception case where set name is over 50 characters (amount allowed)

        $this->expectException(\InvalidArgumentException::class);
        $this->testAddress->setProvince(48957497); //exception case where set name is only numeric (should be a string)

    }
    /**
     * This function tests the getPostalCode function in the entity Address.
     *
     * Tests to make sure then when the Address Entity is instantiated we can retrieve the correct PostalCode.
     * Test to make sure that the PostalCode is a string.
     *
     * @author MacKenzie W, Nathan D
     *
     * Expected: a string
     */
    public function testGetPostalCode()
    {
        $this->testAddress= new Address($this->id, $this->testHAN, $this->testStName, $this->testCity, $this->testProv, $this->testPostalC);

        $this->assertEquals($this->testPostalC, $this->testAddress->getPostalCode());
        $this->assertTrue(is_string($this->testAddress->getPostalCode()));

    }

    /**
     * This function tests the setProvince function from the Address entity.
     *
     * Tests that a new PostalCode can be set after the object has been instantiated.
     * Tests that the PostalCode is not above 6 chars long
     * Tests that the PostalCode cannot be set to be only a numeric.
     *
     * @author MacKenzie W, Nathan D
     *
     * Expected: nothing
     */
    public function testSetPostalCode()
    {
        $this->testAddress= new Address($this->id, $this->testHAN, $this->testStName, $this->testCity, $this->testProv, $this->testPostalC);

        $this->assertEquals($this->testPostalC, $this->testAddress->getPostalCode());
        $this->testAddress->setPostalCode("S7H200"); //boundary case where set word is 6 chars long
        $this->assertEquals("S7H200", $this->testAddress->getPostalCode());

        $this->expectException(\InvalidArgumentException::class);
        $this->testAddress->setPostalCode("Hello World"); //exception case where set name is over 50 characters (amount allowed)

        $this->expectException(\InvalidArgumentException::class);
        $this->testAddress->setPostalCode(48957497); //exception case where set name is only numeric (should be a string)
    }




}
