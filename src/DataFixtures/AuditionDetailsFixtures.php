<?php
/**
 * Created by PhpStorm.
 * User: cst229
 * Date: 1/7/2019
 * Time: 2:11 PM
 */

namespace App\DataFixtures;
use App\Entity\AuditionDetails;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;


class AuditionDetailsFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $auditionDetailOne= new AuditionDetails('Footloose','images/Die.png','Audition is OCT/1/2018'
            ,'Thank you so much for your interest in Saskatoon Summer Player’s fall production of Footloose: The Musical! '
            ,'Go to our building and apply in person!'
            ,'A play about music and dancing'
            ,'Lyle – Chuck’s buddy, tough guy attitude

                                                                    Travis – Chuck’s buddy, tough guy attitude

                                                                    Jeter – Willard’s country friend, Ren’s friend too

                                                                    Garvin – Willard’s country friend, Ren’s friend too

                                                                    Bickle – Willard’s country friend, Ren’s friend too

                                                                    Cowboy Bob – lead vocalist at the Bar-B-Que'
            ,'Hi, y\'all'
            ,'Hats');

        $auditionDetailTwo = new AuditionDetails('AuditionDetails','');

        $auditionDetailThree = new AuditionDetails('','');

        //persist call tells doctrine to 'manage' the object
        $manager ->persist($auditionDetailOne);
        $manager ->persist($auditionDetailTwo);
        $manager ->persist($auditionDetailThree);
        //doctrine looks through all of the objects that it is managing to see if they need to be persisted to the database
        $manager ->flush();
    }

//<?php
///**
// * Created by PhpStorm.
// * User: cst229
// * Date: 1/7/2019
// * Time: 2:11 PM
// */
//
//namespace App\DataFixtures;
//use App\Entity\AuditionDetails;
//use Doctrine\Bundle\FixturesBundle\Fixture;
//use Doctrine\Common\Persistence\ObjectManager;
//use App\Entity\Address;
//use \DateTime;
//
//class AuditionDetailsFixtures extends Fixture
//{
//    /**
//     * @param ObjectManager $manager
//     */
//    public function load(ObjectManager $manager)
//    {
//        $auditionDetailOne= new AuditionDetails('Footloose','images/Die.png','Audition is OCT/1/2018'
//            ,'Thank you so much for your interest in Saskatoon Summer Player’s fall production of Footloose: The Musical! '
//            ,'Go to our building and apply in person!'
//            ,'A play about music and dancing'
//            ,'Lyle – Chuck’s buddy, tough guy attitude
//
//                                                                    Travis – Chuck’s buddy, tough guy attitude
//
//                                                                    Jeter – Willard’s country friend, Ren’s friend too
//
//                                                                    Garvin – Willard’s country friend, Ren’s friend too
//
//                                                                    Bickle – Willard’s country friend, Ren’s friend too
//
//                                                                    Cowboy Bob – lead vocalist at the Bar-B-Que'
//            ,'Hi, y\'all'
//            ,'Hats');
//
//        $auditionDetailTwo = new AuditionDetails('AuditionDetails','');
//
//        $auditionDetailThree = new AuditionDetails('','');
//
//        //persist call tells doctrine to 'manage' the object
//        $manager ->persist($auditionDetailOne);
//        $manager ->persist($auditionDetailTwo);
//        $manager ->persist($auditionDetailThree);
//        //doctrine looks through all of the objects that it is managing to see if they need to be persisted to the database
//        $manager ->flush();
//    }
}