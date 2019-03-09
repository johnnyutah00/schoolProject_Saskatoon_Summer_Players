<?php

namespace App\DataFixtures;
use App\Entity\SSPShow;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Address;
use \DateTime;

class AllShowsFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     *
     */
    public function load(ObjectManager $manager)
    {
        $currentDate = new DateTime(date('Y-m-d',strtotime('+10 day')));
        $pastDate = new DateTime(date('Y-m-d', strtotime(' -365 day')));
        $pastDate2 = new DateTime(date('Y-m-d', strtotime(' -200 day')));
        $futureDate = new DateTime(date('Y-m-d', strtotime(' +365 day')));
        $futureDate2 = new DateTime(date('Y-m-d', strtotime(' +30 day')));

        $addr = new Address('', 225, "George", "New York", "SK", "S7M2P0");
        $comeFromAwaySynopsis = "Come From Away is based on the true story of when the isolated community of Gander, Newfoundland played host to the world. What started as an average day in a small town turned in to an international sleep-over when 38 planes, carrying thousands of people from across the globe, were diverted to Gander’s air strip on September 11, 2001. Undaunted by culture clashes and language barriers, the people of Gander cheered the stranded travelers with music, an open bar and the recognition that we’re all part of a global family.";
        $hamletSynopsis = "The Tragedy of Hamlet, Prince of Denmark, often shortened to Hamlet, is a tragedy written by William Shakespeare at an uncertain date between 1599 and 1602. Set in Denmark, the play dramatises the revenge Prince Hamlet is called to wreak upon his uncle, Claudius, by the ghost of Hamlet's father, King Hamlet. Claudius had murdered his own brother and seized the throne, also marrying his deceased brother's widow.";
        $sweeneyToddSynopsis = "An infamous tale, Sweeney Todd, an unjustly exiled barber, returns to nineteenth century London, seeking vengeance against the lecherous judge who framed him and ravaged his young wife. The road to revenge leads Todd to Mrs. Lovett, a resourceful proprietress of a failing pie shop, above which, he opens a new barber practice. Mrs. Lovett's luck sharply shifts when Todd's thirst for blood inspires the integration of an ingredient into her meat pies that has the people of London lining up... and the carnage has only just begun!";
        $maryPoppinsSynopsis = "The wind is about to change in 1910 London! Bert, a man of many trades, acquaints us to the troubled Banks family of No. 17 Cherry Tree Lane. The children, Jane and Michael, have driven off yet another nanny with their naughty behavior; their father George is absent in their lives and demands order and precision from his wife Winifred who feels inferior in her role as a wife and mother.
        Mary Poppins suddenly appears on their doorstep as a new nanny at just the right moment. Confident and decisive, she knows she must use common sense and a bit of magic to teach this family to value each other again. Mary Poppins takes the children on a walk to the park where they meet Bert who encourages them to see the magic that Mary can add to everyday life.";
        $christmasStorySynopsis = "A young boy named Ralphie Parker only wants one thing for Christmas: a Red Ryder BB gun. However, he is not sure he will ever make it to Christmas, between his brother Randy and the school bully Scut Farkus. Whenever he tells someone how much he wants it, he/she tells him that he will shoot his eye out and refuses to get it for him. Even a department store Santa Claus tells him the same thing. After Ralphie gets a C+, he gets teased again by Scut. In response, Ralphie beats him very badly while cursing loudly. However, his parents do not get mad at him, and on Christmas morning, he gets the BB gun, since his father had one at that age. When he goes to try it out, the bullet ricochets and knocks off his glasses, which he accidentally steps upon while looking for them. He makes up a story about an icicle, and his parents believe him. In the end, a horde of dogs come in and steal the Christmas turkey, so his family goes out for “Chinese turkey,” or duck.";

        $pastShow = new SSPShow('', 'Come From Away', $pastDate, 100, $addr, $comeFromAwaySynopsis, 'comeFromAway_small.jpg', 'http://www.here.com', $pastDate);
        $pastShow2 = new SSPShow('', 'Hamlet',  $pastDate2, 100, $addr, $hamletSynopsis, 'hamlet_small.jpg', 'http://www.here.com' ,$pastDate2);
        $currentShow = new SSPShow('', 'Sweeney Todd', $currentDate, 100, $addr, $sweeneyToddSynopsis, 'sweeney_small.jpg', 'http://www.here.com', $currentDate);
        $futureShow = new SSPShow('', 'Mary Poppins', $futureDate, 100, $addr, $maryPoppinsSynopsis, 'mary_poppins_small.jpg', 'http://www.here.com', $futureDate);
        $futureShow2 = new SSPShow('', 'Christmas Story', $futureDate2, 100, $addr, $christmasStorySynopsis, 'christmas_story_small.jpg ', 'http://www.here.com', $futureDate2);

        $manager->persist($addr);
        $manager->persist($pastShow);
        $manager->persist($pastShow2);
        $manager->persist($currentShow);
        $manager->persist($futureShow);
        $manager->persist($futureShow2);

        $manager->flush();
    }
}

