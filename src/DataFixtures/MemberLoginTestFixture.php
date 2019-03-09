<?php
/**
 * User: cst234
 * Date: 1/21/2019
 * SERVER LOGIC BLOCK
 */

namespace App\DataFixtures;


use App\Entity\Member;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class MemberLoginTestFixture extends Fixture
{

    /**
     * Load members for loginControllerTests
     * This will load three different members with all three roles of member, board member, and GM
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        //Create member that has a role of member
        $memberMember = new Member();
        $memberMember->setUserName("member@member.com");
        $memberMember->setPassword(password_hash("P@ssw0rd", PASSWORD_BCRYPT));
        $memberMember->setFirstName("Steve");
        $memberMember->setLastName("Nash");
        $memberMember->setRoles("ROLE_MEMBER");
        $memberMember->setMemberOption("Subscription");
        $memberMember->setMemberType("Individual");
        $memberMember->setLastDatePaid(time() - 10000);
        $memberMember->setMembershipAgreement(true);
        $memberMember->setCity("Saskatoon");
        $memberMember->setPostalCode("S7N 2K7");
        $memberMember->setProvince("SK");
        $memberMember->setAddressLineOne("Test");

        $this->setReference('memberMember', $memberMember);


        //Create member that has a role of board member
        $memberBoard = new Member();
        $memberBoard->setUserName("bmember@member.com");
        $memberBoard->setPassword(password_hash("P@ssw0rd", PASSWORD_BCRYPT));
        $memberBoard->setFirstName("Steve");
        $memberBoard->setLastName("Nash");
        $memberBoard->setRoles("ROLE_BM");
        $memberBoard->setMemberOption("Subscription");
        $memberBoard->setMemberType("Individual");
        $memberBoard->setLastDatePaid(time() - 10000);
        $memberBoard->setMembershipAgreement(true);
        $memberBoard->setCity("Saskatoon");
        $memberBoard->setPostalCode("S7N 2K7");
        $memberBoard->setProvince("SK");
        $memberBoard->setAddressLineOne("Test");

        $this->setReference('memberBoard', $memberBoard);

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

        $this->setReference('memberGM', $memberGM);

        $oldMember = new Member();
        $oldMember->setUserName("old@member.com");
        $oldMember->setPassword(password_hash("P@ssw0rd", PASSWORD_BCRYPT));
        $oldMember->setFirstName("Steve");
        $oldMember->setLastName("Nash");
        $oldMember->setRoles("ROLE_MEMBER");
        $oldMember->setMemberOption("Subscription");
        $oldMember->setMemberType("Individual");
        $oldMember->setLastDatePaid(253238400); //Jan 10, 1978
        $oldMember->setMembershipAgreement(true);
        $oldMember->setCity("Saskatoon");
        $oldMember->setPostalCode("S7N 2K7");
        $oldMember->setProvince("SK");
        $oldMember->setAddressLineOne("Test");

        $oneYrOldMember = new Member();
        $oneYrOldMember->setUserName("oneYrOld@member.com");
        $oneYrOldMember->setPassword(password_hash("P@ssw0rd", PASSWORD_BCRYPT));
        $oneYrOldMember->setFirstName("Steve");
        $oneYrOldMember->setLastName("Nash");
        $oneYrOldMember->setRoles("ROLE_MEMBER");
        $oneYrOldMember->setMemberOption("Subscription");
        $oneYrOldMember->setMemberType("Individual");
        $oneYrOldMember->setLastDatePaid(time() - 31536000); //Exactly one year from this date
        $oneYrOldMember->setMembershipAgreement(true);
        $oneYrOldMember->setCity("Saskatoon");
        $oneYrOldMember->setPostalCode("S7N 2K7");
        $oneYrOldMember->setProvince("SK");
        $oneYrOldMember->setAddressLineOne("Test");

        $almostTooOldMember = new Member();
        $almostTooOldMember->setUserName("almostTooOld@member.com");
        $almostTooOldMember->setPassword(password_hash("P@ssw0rd", PASSWORD_BCRYPT));
        $almostTooOldMember->setFirstName("Steve");
        $almostTooOldMember->setLastName("Nash");
        $almostTooOldMember->setRoles("ROLE_MEMBER");
        $almostTooOldMember->setMemberOption("Subscription");
        $almostTooOldMember->setMemberType("Individual");
        $almostTooOldMember->setLastDatePaid(time() - (31536000-1)); //One year plus one day. One year: 24*60*60
        $almostTooOldMember->setMembershipAgreement(true);
        $almostTooOldMember->setCity("Saskatoon");
        $almostTooOldMember->setPostalCode("S7N 2K7");
        $almostTooOldMember->setProvince("SK");
        $almostTooOldMember->setAddressLineOne("Test");




        $manager->persist($memberMember);
        $manager->persist($memberBoard);
        $manager->persist($memberGM);
        $manager->persist($oldMember);
        $manager->persist($oneYrOldMember);
        $manager->persist($almostTooOldMember);


        $manager->flush();
    }
}