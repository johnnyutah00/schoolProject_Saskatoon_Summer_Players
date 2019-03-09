<?php


namespace App\DataFixtures;
use App\Entity\SSPShow;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Address;
use \DateTime;
use App\Entity\Member;

class AddUpdateDeleteShowFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $currentDate = new DateTime(date('Y-m-d',strtotime('+10 day')));

        $addr = new Address('', 225, "George", "New York", "SK", "S7M2P0");
        $synopsis = "a synopsis";

        $show1 = new SSPShow('', 'A cats tale', $currentDate, 100, $addr, $synopsis, "godly.jpg", 'http://www.here.com', $currentDate, 'new', 500);
        $show2 = new SSPShow('', 'Life in School', $currentDate, 100, $addr, $synopsis, "godly.jpg", 'http://www.here.com', $currentDate, '', 500);
        $show3 = new SSPShow('', 'Bitter cold', $currentDate, 100, $addr, $synopsis, "godly.jpg", 'http://www.here.com', $currentDate, 'sold out', 500);
        $show4 = new SSPShow('', 'Canada Diaries', $currentDate, 100, $addr, $synopsis, "godly.jpg", 'http://www.here.com', $currentDate, '', 500);
        $show5 = new SSPShow('', 'History Archived', $currentDate, 100, $addr, $synopsis, "godly.jpg", 'http://www.here.com', $currentDate, 'archived', 500);

        $manager->persist($addr);

        //Create a member that has a role of GM
        $memberGM = new Member();
        $memberGM->setUserName("gmember@member.com");
        $memberGM->setPassword(password_hash("P@ssw0rd", PASSWORD_BCRYPT));
        $memberGM->setFirstName("Steve");
        $memberGM->setLastName("Nash");
        $memberGM->setRoles("ROLE_GM");
        $memberGM->setMemberOption("Subscription");
        $memberGM->setMemberType("Individual");
        $memberGM->setLastDatePaid(time() - 10000);
        $memberGM->setMembershipAgreement(true);
        $memberGM->setCity("Saskatoon");
        $memberGM->setPostalCode("S7N 2K7");
        $memberGM->setProvince("SK");
        $memberGM->setAddressLineOne("Test");

        $manager->persist($show1);
        $manager->persist($show2);
        $manager->persist($show3);
        $manager->persist($show4);
        $manager->persist($show5);

        $manager->persist($memberGM);

        $manager->flush();
    }
}

