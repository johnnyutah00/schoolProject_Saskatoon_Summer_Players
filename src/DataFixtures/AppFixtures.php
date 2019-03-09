<?php

namespace App\DataFixtures;

use App\Entity\Document;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    //what are fixtures? they are essentially dummy pieces of information that can be auto-loaded into a database for testing

    //load fixtures before loading a web page by going
    //php bin/console doctrine:fixtures:load
    //in the command terminal   - Nathan

    /**
     * AppFixtures constructor
     */
    public function __construct()
    {
    }

    public function load(ObjectManager $manager)
    {
        // creates a dummy terms and conditions document for the db
        $termsDoc = new Document();
        $termsDoc->setTitle('Terms and Conditions');
        $termsDoc->setBody('
        At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias 
        excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem 
        rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis 
        voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae 
        sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus 
        asperiores repellat.
        ');
        $manager->persist($termsDoc);


        $manager->flush();
    }

 /*   public function loadBoardComplete(ObjectManager $manager)
    {
        //$rick = new Member(id: '', firstName: 'Rick', lastName:'Caron',membershipAgreement:1, city:'Saskatoon' );

        $rick1 = new Member('','Rick', 'Caron');

        $manager->persist($rick1);

        $manager->flush();

    }

    public function loadBoardIncomplete(ObjectManager $manager)
    {

    }*/
}
