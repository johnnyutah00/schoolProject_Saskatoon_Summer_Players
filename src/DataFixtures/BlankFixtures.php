<?php
/**
 * User: CST221
 * Date: 1/22/2019
 * SERVER LOGIC BLOCK
 */
namespace App\DataFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class BlankFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $manager->flush();
    }
}
