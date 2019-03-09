<?php
/**
 * Created by PhpStorm.
 * User: cst229
 * Date: 1/9/2019
 * Time: 2:21 PM
 */

namespace App\DataFixtures;
use App\Entity\Member;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AddMembersFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $mem1 = new Member();
        $mem1->setUserName("Sam@gmail.com");
        $mem1->setPassword(password_hash("P@ssw0rd", PASSWORD_BCRYPT));
        $mem1->setMembershipAgreement(true);
        $mem1->setCity('Saskatoon');
        $mem1->setPostalCode('s7k1a4');
        $mem1->setProvince('SK');
        $mem1->setAddressLineOne('111 There Drive');
        $mem1->setFirstName("Sam");
        $mem1->setLastName("Smith");
        $mem1->setMemberType("Individual");
        $mem1->setMemberOption("Subscription");

        $mem1->setRoles('ROLE_MEMBER');
        $mem1->setLastDatePaid('1548271590');


        $this->setReference('member-alpha', $mem1);

        $mem2 = new Member();
        $mem2->setUserName("SallyJ@gmail.com");
        $mem2->setPassword(password_hash("P@ssw0rd", PASSWORD_BCRYPT));
        $mem2->setMembershipAgreement(true);
        $mem2->setCity('Saskatoon');
        $mem2->setPostalCode('s7k1a4');
        $mem2->setProvince('SK');
        $mem2->setAddressLineOne('111 There Drive');
        $mem2->setFirstName("Sally");
        $mem2->setLastName("Johnson");
        $mem2->setMemberType("Individual");
        $mem2->setMemberOption("Subscription");

        $mem2->setRoles('ROLE_MEMBER');
        $mem2->setLastDatePaid('1548271590');


        $this->setReference('member-alpha', $mem2);

        $mem3 = new Member();
        $mem3->setUserName("BillM@gmail.com");
        $mem3->setPassword(password_hash("P@ssw0rd", PASSWORD_BCRYPT));
        $mem3->setMembershipAgreement(true);
        $mem3->setCity('Saskatoon');
        $mem3->setPostalCode('s7k1a4');
        $mem3->setProvince('SK');
        $mem3->setAddressLineOne('111 There Drive');
        $mem3->setFirstName("Bill");
        $mem3->setLastName("Macguire");
        $mem3->setMemberType("Individual");
        $mem3->setMemberOption("Subscription");

        $mem3->setRoles('ROLE_MEMBER');
        $mem3->setLastDatePaid('1548271590');


        $this->setReference('member-alpha', $mem3);

        $mem4 = new Member();
        $mem4->setUserName("BillH@gmail.com");
        $mem4->setPassword(password_hash("P@ssw0rd", PASSWORD_BCRYPT));
        $mem4->setMembershipAgreement(true);
        $mem4->setCity('Saskatoon');
        $mem4->setPostalCode('s7k1a4');
        $mem4->setProvince('SK');
        $mem4->setAddressLineOne('111 There Drive');
        $mem4->setFirstName("Bill");
        $mem4->setLastName("Hunter");
        $mem4->setMemberType("Individual");
        $mem4->setMemberOption("Subscription");

        $mem4->setRoles('ROLE_MEMBER');
        $mem4->setLastDatePaid('1548271590');


        $this->setReference('member-alpha', $mem4);

        $mem5 = new Member();
        $mem5->setUserName("MaryM@gmail.com");
        $mem5->setPassword(password_hash("P@ssw0rd", PASSWORD_BCRYPT));
        $mem5->setMembershipAgreement(true);
        $mem5->setCity('Saskatoon');
        $mem5->setPostalCode('s7k1a4');
        $mem5->setProvince('SK');
        $mem5->setAddressLineOne('111 There Drive');
        $mem5->setFirstName("Mary");
        $mem5->setLastName("Mills");
        $mem5->setMemberType("Individual");
        $mem5->setMemberOption("Subscription");

        $mem5->setRoles('ROLE_MEMBER');
        $mem5->setLastDatePaid('1548271590');


        $this->setReference('member-alpha', $mem5);

        $mem6 = new Member();
        $mem6->setUserName("AnnieF@gmail.com");
        $mem6->setPassword(password_hash("P@ssw0rd", PASSWORD_BCRYPT));
        $mem6->setMembershipAgreement(true);
        $mem6->setCity('Saskatoon');
        $mem6->setPostalCode('s7k1a4');
        $mem6->setProvince('SK');
        $mem6->setAddressLineOne('111 There Drive');
        $mem6->setFirstName("Annie");
        $mem6->setLastName("Frank");
        $mem6->setMemberType("Individual");
        $mem6->setMemberOption("Subscription");

        $mem6->setRoles('ROLE_MEMBER');
        $mem6->setLastDatePaid('1548271590');


        $this->setReference('member-alpha', $mem6);

        $mem7 = new Member();
        $mem7->setUserName("JohnM@gmail.com");
        $mem7->setPassword(password_hash("P@ssw0rd", PASSWORD_BCRYPT));
        $mem7->setMembershipAgreement(true);
        $mem7->setCity('Saskatoon');
        $mem7->setPostalCode('s7k1a4');
        $mem7->setProvince('SK');
        $mem7->setAddressLineOne('111 There Drive');
        $mem7->setFirstName("John");
        $mem7->setLastName("MacDonald");
        $mem7->setMemberType("Individual");
        $mem7->setMemberOption("Subscription");

        $mem7->setRoles('ROLE_MEMBER');
        $mem7->setLastDatePaid('1548271590');


        $this->setReference('member-alpha', $mem7);


        $mem8 = new Member();
        $mem8->setUserName("testHyphen@gmail.com");
        $mem8->setPassword(password_hash("P@ssw0rd", PASSWORD_BCRYPT));
        $mem8->setMembershipAgreement(true);
        $mem8->setCity('Saskatoon');
        $mem8->setPostalCode('s7k1a4');
        $mem8->setProvince('SK');
        $mem8->setAddressLineOne('111 There Drive');
        $mem8->setFirstName("Hy-phen");
        $mem8->setLastName("Period");
        $mem8->setMemberType("Individual");
        $mem8->setMemberOption("Subscription");

        $mem8->setRoles('ROLE_MEMBER');
        $mem8->setLastDatePaid('1548271590');




        $this->setReference('member-alpha', $mem8);

        $mem9 = new Member();
        $mem9->setUserName("samprj4reset@gmail.com");
        $mem9->setPassword(password_hash("P@ssw0rd", PASSWORD_BCRYPT));
        $mem9->setMembershipAgreement(true);
        $mem9->setCity('Saskatoon');
        $mem9->setPostalCode('s7k1a4');
        $mem9->setProvince('SK');
        $mem9->setAddressLineOne('111 There Drive');
        $mem9->setFirstName("TESTNAME");
        $mem9->setLastName("TESTLASTNAME");
        $mem9->setMemberType("Individual");
        $mem9->setMemberOption("Subscription");

        $mem9->setRoles('ROLE_MEMBER');
        $mem9->setLastDatePaid('1548271590');


        $this->setReference('member-alpha', $mem9);


        $manager->persist($mem1);
        $manager->persist($mem2);
        $manager->persist($mem3);
        $manager->persist($mem4);
        $manager->persist($mem5);
        $manager->persist($mem6);
        $manager->persist($mem7);
        $manager->persist($mem8);
        $manager->persist($mem9);




        $rick = new Member();
        $rick->setUserName("rick@gmail.com");
        $rick->setPassword(password_hash("P@ssw0rd", PASSWORD_BCRYPT));
        $rick->setMembershipAgreement(true);
        $rick->setCity('Saskatoon');
        $rick->setPostalCode('s7k1a4');
        $rick->setProvince('SK');
        $rick->setAddressLineOne('111 There Drive');
        $rick->setFirstName("Rick");
        $rick->setLastName("Caron");
        $rick->setMemberType("Individual");
        $rick->setMemberGroups("Vice President");
        $rick->setMemberOption("Subscription");
        $rick->setCompany("SSP");
        $rick->setRoles("ROLE_GM");
        $this->setReference('member-alpha', $rick);

        $cyril = new Member();
        $cyril->setUserName("cyril@gmail.com");
        $cyril->setPassword(password_hash("P@ssw0rd", PASSWORD_BCRYPT));
        $cyril->setMembershipAgreement(true);
        $cyril->setCity('Saskatoon');
        $cyril->setPostalCode('s7k1a4');
        $cyril->setProvince('SK');
        $cyril->setAddressLineOne('111 There Drive');
        $cyril->setFirstName("Cyril");
        $cyril->setLastName("Coupal");
        $cyril->setMemberType("Individual");
        $cyril->setMemberGroups("Vice President");
        $cyril->setMemberOption("Subscription");
        $cyril->setCompany("SSP");
        $cyril->setRoles("ROLE_GM");
        $this->setReference('member-alpha', $cyril);


        $bryce = new Member();
        $bryce->setUserName("bryce@gmail.com");
        $bryce->setPassword(password_hash("P@ssw0rd", PASSWORD_BCRYPT));
        $bryce->setMembershipAgreement(true);
        $bryce->setCity('Saskatoon');
        $bryce->setPostalCode('s7k1a4');
        $bryce->setProvince('SK');
        $bryce->setAddressLineOne('111 There Drive');
        $bryce->setFirstName("Bryce");
        $bryce->setLastName("Barrie");
        $bryce->setMemberType("Individual");
        $bryce->setMemberGroups("Member");
        $bryce->setMemberOption("Subscription");
        $bryce->setCompany("SSP");
        $bryce->setRoles("ROLE_MEMBER");
        $this->setReference('member-alpha', $bryce);


        $wade = new Member();
        $wade->setUserName("wade@gmail.com");
        $wade->setPassword(password_hash("P@ssw0rd", PASSWORD_BCRYPT));
        $wade->setMembershipAgreement(true);
        $wade->setCity('Saskatoon');
        $wade->setPostalCode('s7k1a4');
        $wade->setProvince('SK');
        $wade->setAddressLineOne('111 There Drive');
        $wade->setFirstName("Wade");
        $wade->setLastName("Lahoda");
        $wade->setMemberType("Individual");
        $wade->setMemberGroups("Member");
        $wade->setMemberOption("Subscription");
        $wade->setCompany("SSP");
        $wade->setRoles("ROLE_MEMBER");
        $this->setReference('member-alpha', $wade);

        $rob = new Member();
        $rob->setUserName("rob@gmail.com");
        $rob->setPassword(password_hash("P@ssw0rd", PASSWORD_BCRYPT));
        $rob->setMembershipAgreement(true);
        $rob->setCity('Saskatoon');
        $rob->setPostalCode('s7k1a4');
        $rob->setProvince('SK');
        $rob->setAddressLineOne('111 There Drive');
        $rob->setFirstName("Rob");
        $rob->setLastName("Miller");
        $rob->setMemberType("Individual");
        $rob->setMemberGroups("Member");
        $rob->setMemberOption("Subscription");
        $rob->setCompany("SSP");
        $rob->setRoles("ROLE_MEMBER");
        $this->setReference('member-alpha', $rob);

        $ron = new Member();
        $ron->setUserName("ron@gmail.com");
        $ron->setPassword(password_hash("P@ssw0rd", PASSWORD_BCRYPT));
        $ron->setMembershipAgreement(true);
        $ron->setCity('Saskatoon');
        $ron->setPostalCode('s7k1a4');
        $ron->setProvince('SK');
        $ron->setAddressLineOne('111 There Drive');
        $ron->setFirstName("Ron");
        $ron->setLastName("New");
        $ron->setMemberType("Individual");
        $ron->setMemberGroups("Board Member");
        $ron->setMemberOption("Subscription");
        $ron->setCompany("SSP");
        $ron->setRoles("ROLE_BM");
        $this->setReference('member-alpha', $ron);

        $ben = new Member();
        $ben->setUserName("ben@gmail.com");
        $ben->setPassword(password_hash("P@ssw0rd", PASSWORD_BCRYPT));
        $ben->setMembershipAgreement(true);
        $ben->setCity('Saskatoon');
        $ben->setPostalCode('s7k1a4');
        $ben->setProvince('SK');
        $ben->setAddressLineOne('111 There Drive');
        $ben->setFirstName("Ben");
        $ben->setLastName("Benson");
        $ben->setMemberType("Individual");
        $ben->setMemberGroups("Board Member");
        $ben->setMemberOption("Subscription");
        $ben->setCompany("SSP");
        $ben->setRoles("ROLE_BM");
        $this->setReference('member-alpha', $ben);

        $kel = new Member();
        $kel->setUserName("kel@gmail.com");
        $kel->setPassword(password_hash("P@ssw0rd", PASSWORD_BCRYPT));
        $kel->setMembershipAgreement(true);
        $kel->setCity('Saskatoon');
        $kel->setPostalCode('s7k1a4');
        $kel->setProvince('SK');
        $kel->setAddressLineOne('111 There Drive');
        $kel->setFirstName("Kel");
        $kel->setLastName("Boechler");
        $kel->setMemberType("Individual");
        $kel->setMemberGroups("Board Member");
        $kel->setMemberOption("Subscription");
        $kel->setCompany("SSP");
        $kel->setRoles("ROLE_BM");
        $this->setReference('member-alpha', $kel);

        $jason = new Member();
        $jason->setUserName("jason@gmail.com");
        $jason->setPassword(password_hash("P@ssw0rd", PASSWORD_BCRYPT));
        $jason->setMembershipAgreement(true);
        $jason->setCity('Saskatoon');
        $jason->setPostalCode('s7k1a4');
        $jason->setProvince('SK');
        $jason->setAddressLineOne('111 There Drive');
        $jason->setFirstName("Jason");
        $jason->setLastName("Schmidt");
        $jason->setMemberType("Individual");
        $jason->setMemberGroups("Board Member");
        $jason->setMemberOption("Subscription");
        $jason->setCompany("SSP");
        $jason->setRoles("ROLE_BM");
        $this->setReference('member-alpha', $jason);

        $ern = new Member();
        $ern->setUserName("ernesto@gmail.com");
        $ern->setPassword(password_hash("P@ssw0rd", PASSWORD_BCRYPT));
        $ern->setMembershipAgreement(true);
        $ern->setCity('Saskatoon');
        $ern->setPostalCode('s7k1a4');
        $ern->setProvince('SK');
        $ern->setAddressLineOne('111 There Drive');
        $ern->setFirstName("Ernesto");
        $ern->setLastName("Basaolto");
        $ern->setMemberType("Individual");
        $ern->setMemberGroups("Board Member");
        $ern->setMemberOption("Subscription");
        $ern->setCompany("SSP");
        $ern->setRoles("ROLE_BM");
        $this->setReference('member-alpha', $ern);






        //////////////////Errored entries///


        //Dont show because company name is not SSP.
        $doNotAppear = new Member();
        $doNotAppear->setUserName("doNotAppear@gmail.com");
        $doNotAppear->setPassword(password_hash("P@ssw0rd", PASSWORD_BCRYPT));
        $doNotAppear->setMembershipAgreement(true);
        $doNotAppear->setCity('Saskatoon');
        $doNotAppear->setPostalCode('s7k1a4');
        $doNotAppear->setProvince('SK');
        $doNotAppear->setAddressLineOne('111 There Drive');
        $doNotAppear->setFirstName("Dont Show");
        $doNotAppear->setLastName("Dont Show");
        $doNotAppear->setMemberType("Individual");
        $doNotAppear->setMemberGroups("Board Member");
        $doNotAppear->setMemberOption("Subscription");
        $doNotAppear->setCompany("Company");
        $doNotAppear->setRoles("ROLE_BM");
        $this->setReference('member-alpha', $doNotAppear);


        //Dont show because First name  is empty.
        $noFirstName = new Member();
        $noFirstName->setUserName("noFirstName@gmail.com");
        $noFirstName->setPassword(password_hash("P@ssw0rd", PASSWORD_BCRYPT));
        $noFirstName->setMembershipAgreement(true);
        $noFirstName->setCity('Saskatoon');
        $noFirstName->setPostalCode('s7k1a4');
        $noFirstName->setProvince('SK');
        $noFirstName->setAddressLineOne('111 There Drive');
        $noFirstName->setFirstName("");
        $noFirstName->setLastName("No First Name");
        $noFirstName->setMemberType("Individual");
        $noFirstName->setMemberGroups("Board Member");
        $noFirstName->setMemberOption("Subscription");
        $noFirstName->setCompany("SSP");
        $noFirstName->setRoles("ROLE_BM");
        $this->setReference('member-alpha', $noFirstName);


        //Dont show because no last name.
        $noLastName = new Member();
        $noLastName->setUserName("noLastName@gmail.com");
        $noLastName->setPassword(password_hash("P@ssw0rd", PASSWORD_BCRYPT));
        $noLastName->setMembershipAgreement(true);
        $noLastName->setCity('Saskatoon');
        $noLastName->setPostalCode('s7k1a4');
        $noLastName->setProvince('SK');
        $noLastName->setAddressLineOne('111 There Drive');
        $noLastName->setFirstName("No Last Name");
        $noLastName->setLastName("");
        $noLastName->setMemberType("Individual");
        $noLastName->setMemberGroups("Board Member");
        $noLastName->setMemberOption("Subscription");
        $noLastName->setCompany("SSP");
        $noLastName->setRoles("ROLE_MEMBER");
        $this->setReference('member-alpha', $noLastName);

        //Dont show because Last name  is empty.
        $noPosition = new Member();
        $noPosition->setUserName("noLastName@gmail.com");
        $noPosition->setPassword(password_hash("P@ssw0rd", PASSWORD_BCRYPT));
        $noPosition->setMembershipAgreement(true);
        $noPosition->setCity('Saskatoon');
        $noPosition->setPostalCode('s7k1a4');
        $noPosition->setProvince('SK');
        $noPosition->setAddressLineOne('111 There Drive');
        $noPosition->setFirstName("No Position");
        $noPosition->setLastName("Specified");
        $noPosition->setMemberType("Individual");
        $noPosition->setMemberGroups("");
        $noPosition->setMemberOption("Subscription");
        $noPosition->setCompany("SSP");
        $noPosition->setRoles("ROLE_MEMBER");
        $this->setReference('member-alpha', $noPosition);


        $manager->persist($rick);
        $manager->persist($cyril);
        $manager->persist($bryce);
        $manager->persist($wade);
        $manager->persist($rob);
        $manager->persist($ern);
        $manager->persist($jason);
        $manager->persist($ron);
        $manager->persist($kel);
        $manager->persist($ben);

        $manager->persist($doNotAppear);
        $manager->persist($noFirstName);
        $manager->persist($noLastName);
        $manager->persist($noPosition);


        $manager->flush();


//        $rick = new Member();
//        $rick->setUserName("rick@gmail.com");
//        $rick->setPassword(password_hash("P@ssw0rd", PASSWORD_BCRYPT));
//        $rick->setMembershipAgreement(true);
//        $rick->setCity('Saskatoon');
//        $rick->setPostalCode('s7k1a4');
//        $rick->setProvince('SK');
//        $rick->setAddressLineOne('111 There Drive');
//        $rick->setFirstName("Rick");
//        $rick->setLastName("Caron");
//        $rick->setMemberType("Individual");
//        $rick->setMemberGroups("Vice President");
//        $rick->setMemberOption("Subscription");
//        $rick->setCompany("SSP");
//        $this->setReference('member-alpha', $rick);
//
//        $cyril = new Member();
//        $cyril->setUserName("cyril@gmail.com");
//        $cyril->setPassword(password_hash("P@ssw0rd", PASSWORD_BCRYPT));
//        $cyril->setMembershipAgreement(true);
//        $cyril->setCity('Saskatoon');
//        $cyril->setPostalCode('s7k1a4');
//        $cyril->setProvince('SK');
//        $cyril->setAddressLineOne('111 There Drive');
//        $cyril->setFirstName("Cyril");
//        $cyril->setLastName("Coupal");
//        $cyril->setMemberType("Individual");
//        $cyril->setMemberGroups("Vice President");
//        $cyril->setMemberOption("Subscription");
//        $cyril->setCompany("SSP");
//        $this->setReference('member-alpha', $cyril);
//
//
//        $bryce = new Member();
//        $bryce->setUserName("bryce@gmail.com");
//        $bryce->setPassword(password_hash("P@ssw0rd", PASSWORD_BCRYPT));
//        $bryce->setMembershipAgreement(true);
//        $bryce->setCity('Saskatoon');
//        $bryce->setPostalCode('s7k1a4');
//        $bryce->setProvince('SK');
//        $bryce->setAddressLineOne('111 There Drive');
//        $bryce->setFirstName("Bryce");
//        $bryce->setLastName("Barrie");
//        $bryce->setMemberType("Individual");
//        $bryce->setMemberGroups("Member");
//        $bryce->setMemberOption("Subscription");
//        $bryce->setCompany("SSP");
//        $this->setReference('member-alpha', $bryce);
//
//
//        $wade = new Member();
//        $wade->setUserName("wade@gmail.com");
//        $wade->setPassword(password_hash("P@ssw0rd", PASSWORD_BCRYPT));
//        $wade->setMembershipAgreement(true);
//        $wade->setCity('Saskatoon');
//        $wade->setPostalCode('s7k1a4');
//        $wade->setProvince('SK');
//        $wade->setAddressLineOne('111 There Drive');
//        $wade->setFirstName("Wade");
//        $wade->setLastName("Lahoda");
//        $wade->setMemberType("Individual");
//        $wade->setMemberGroups("Member");
//        $wade->setMemberOption("Subscription");
//        $wade->setCompany("SSP");
//        $this->setReference('member-alpha', $wade);
//
//        $rob = new Member();
//        $rob->setUserName("rob@gmail.com");
//        $rob->setPassword(password_hash("P@ssw0rd", PASSWORD_BCRYPT));
//        $rob->setMembershipAgreement(true);
//        $rob->setCity('Saskatoon');
//        $rob->setPostalCode('s7k1a4');
//        $rob->setProvince('SK');
//        $rob->setAddressLineOne('111 There Drive');
//        $rob->setFirstName("Rob");
//        $rob->setLastName("Miller");
//        $rob->setMemberType("Individual");
//        $rob->setMemberGroups("Member");
//        $rob->setMemberOption("Subscription");
//        $rob->setCompany("SSP");
//        $this->setReference('member-alpha', $rob);
//
//        $ron = new Member();
//        $ron->setUserName("ron@gmail.com");
//        $ron->setPassword(password_hash("P@ssw0rd", PASSWORD_BCRYPT));
//        $ron->setMembershipAgreement(true);
//        $ron->setCity('Saskatoon');
//        $ron->setPostalCode('s7k1a4');
//        $ron->setProvince('SK');
//        $ron->setAddressLineOne('111 There Drive');
//        $ron->setFirstName("Ron");
//        $ron->setLastName("New");
//        $ron->setMemberType("Individual");
//        $ron->setMemberGroups("Board Member");
//        $ron->setMemberOption("Subscription");
//        $ron->setCompany("SSP");
//        $this->setReference('member-alpha', $ron);
//
//        $ben = new Member();
//        $ben->setUserName("ben@gmail.com");
//        $ben->setPassword(password_hash("P@ssw0rd", PASSWORD_BCRYPT));
//        $ben->setMembershipAgreement(true);
//        $ben->setCity('Saskatoon');
//        $ben->setPostalCode('s7k1a4');
//        $ben->setProvince('SK');
//        $ben->setAddressLineOne('111 There Drive');
//        $ben->setFirstName("Ben");
//        $ben->setLastName("Benson");
//        $ben->setMemberType("Individual");
//        $ben->setMemberGroups("Board Member");
//        $ben->setMemberOption("Subscription");
//        $ben->setCompany("SSP");
//        $this->setReference('member-alpha', $ben);
//
//        $kel = new Member();
//        $kel->setUserName("kel@gmail.com");
//        $kel->setPassword(password_hash("P@ssw0rd", PASSWORD_BCRYPT));
//        $kel->setMembershipAgreement(true);
//        $kel->setCity('Saskatoon');
//        $kel->setPostalCode('s7k1a4');
//        $kel->setProvince('SK');
//        $kel->setAddressLineOne('111 There Drive');
//        $kel->setFirstName("Kel");
//        $kel->setLastName("Boechler");
//        $kel->setMemberType("Individual");
//        $kel->setMemberGroups("Board Member");
//        $kel->setMemberOption("Subscription");
//        $kel->setCompany("SSP");
//        $this->setReference('member-alpha', $kel);
//
//        $jason = new Member();
//        $jason->setUserName("jason@gmail.com");
//        $jason->setPassword(password_hash("P@ssw0rd", PASSWORD_BCRYPT));
//        $jason->setMembershipAgreement(true);
//        $jason->setCity('Saskatoon');
//        $jason->setPostalCode('s7k1a4');
//        $jason->setProvince('SK');
//        $jason->setAddressLineOne('111 There Drive');
//        $jason->setFirstName("Jason");
//        $jason->setLastName("Schmidt");
//        $jason->setMemberType("Individual");
//        $jason->setMemberGroups("Board Member");
//        $jason->setMemberOption("Subscription");
//        $jason->setCompany("SSP");
//        $this->setReference('member-alpha', $jason);
//
//        $ern = new Member();
//        $ern->setUserName("ernesto@gmail.com");
//        $ern->setPassword(password_hash("P@ssw0rd", PASSWORD_BCRYPT));
//        $ern->setMembershipAgreement(true);
//        $ern->setCity('Saskatoon');
//        $ern->setPostalCode('s7k1a4');
//        $ern->setProvince('SK');
//        $ern->setAddressLineOne('111 There Drive');
//        $ern->setFirstName("Ernesto");
//        $ern->setLastName("Basaolto");
//        $ern->setMemberType("Individual");
//        $ern->setMemberGroups("Board Member");
//        $ern->setMemberOption("Subscription");
//        $ern->setCompany("SSP");
//        $this->setReference('member-alpha', $ern);
//
//
//
//
//
//
//        //////////////////Errored entries///
//
//
//        //Dont show because company name is not SSP.
//        $doNotAppear = new Member();
//        $doNotAppear->setUserName("doNotAppear@gmail.com");
//        $doNotAppear->setPassword(password_hash("P@ssw0rd", PASSWORD_BCRYPT));
//        $doNotAppear->setMembershipAgreement(true);
//        $doNotAppear->setCity('Saskatoon');
//        $doNotAppear->setPostalCode('s7k1a4');
//        $doNotAppear->setProvince('SK');
//        $doNotAppear->setAddressLineOne('111 There Drive');
//        $doNotAppear->setFirstName("Dont Show");
//        $doNotAppear->setLastName("Dont Show");
//        $doNotAppear->setMemberType("Individual");
//        $doNotAppear->setMemberGroups("Board Member");
//        $doNotAppear->setMemberOption("Subscription");
//        $doNotAppear->setCompany("Company");
//        $this->setReference('member-alpha', $doNotAppear);
//
//
//        //Dont show because First name  is empty.
//        $noFirstName = new Member();
//        $noFirstName->setUserName("noFirstName@gmail.com");
//        $noFirstName->setPassword(password_hash("P@ssw0rd", PASSWORD_BCRYPT));
//        $noFirstName->setMembershipAgreement(true);
//        $noFirstName->setCity('Saskatoon');
//        $noFirstName->setPostalCode('s7k1a4');
//        $noFirstName->setProvince('SK');
//        $noFirstName->setAddressLineOne('111 There Drive');
//        $noFirstName->setFirstName("");
//        $noFirstName->setLastName("No First Name");
//        $noFirstName->setMemberType("Individual");
//        $noFirstName->setMemberGroups("Board Member");
//        $noFirstName->setMemberOption("Subscription");
//        $noFirstName->setCompany("SSP");
//        $this->setReference('member-alpha', $noFirstName);
//
//
//        //Dont show because no last name.
//        $noLastName = new Member();
//        $noLastName->setUserName("noLastName@gmail.com");
//        $noLastName->setPassword(password_hash("P@ssw0rd", PASSWORD_BCRYPT));
//        $noLastName->setMembershipAgreement(true);
//        $noLastName->setCity('Saskatoon');
//        $noLastName->setPostalCode('s7k1a4');
//        $noLastName->setProvince('SK');
//        $noLastName->setAddressLineOne('111 There Drive');
//        $noLastName->setFirstName("No Last Name");
//        $noLastName->setLastName("");
//        $noLastName->setMemberType("Individual");
//        $noLastName->setMemberGroups("Board Member");
//        $noLastName->setMemberOption("Subscription");
//        $noLastName->setCompany("SSP");
//        $this->setReference('member-alpha', $noLastName);
//
//        //Dont show because Last name  is empty.
//        $noPosition = new Member();
//        $noPosition->setUserName("noLastName@gmail.com");
//        $noPosition->setPassword(password_hash("P@ssw0rd", PASSWORD_BCRYPT));
//        $noPosition->setMembershipAgreement(true);
//        $noPosition->setCity('Saskatoon');
//        $noPosition->setPostalCode('s7k1a4');
//        $noPosition->setProvince('SK');
//        $noPosition->setAddressLineOne('111 There Drive');
//        $noPosition->setFirstName("No Position");
//        $noPosition->setLastName("Specified");
//        $noPosition->setMemberType("Individual");
//        $noPosition->setMemberGroups("");
//        $noPosition->setMemberOption("Subscription");
//        $noPosition->setCompany("SSP");
//        $this->setReference('member-alpha', $noPosition);
//
//
//        $manager->persist($rick);
//        $manager->persist($cyril);
//        $manager->persist($bryce);
//        $manager->persist($wade);
//        $manager->persist($rob);
//        $manager->persist($ern);
//        $manager->persist($jason);
//        $manager->persist($ron);
//        $manager->persist($kel);
//        $manager->persist($ben);
//
//        $manager->persist($doNotAppear);
//        $manager->persist($noFirstName);
//        $manager->persist($noLastName);
//        $manager->persist($noPosition);
//
//        $manager->flush();
//
//
//
//        $manager->flush();
    }
}