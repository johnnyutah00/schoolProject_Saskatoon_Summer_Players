<?php
/**
 * User: CST221
 * Date: 1/24/2019
 * SERVER LOGIC BLOCK
 */

namespace App\DataFixtures;
use App\Entity\OnlinePoll;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AddOnlinePollsFixtures extends fixture
{
    public function load(ObjectManager $manager)
    {
        //Load one new online poll
        $poll = new OnlinePoll();
        $poll->setName("Big Poll");
        $poll->setDescription("This poll is big; please vote.");
        $poll->setOptions(["Option 1","Option 2"]);

        $manager->persist($poll);

        $manager->flush();
    }
}