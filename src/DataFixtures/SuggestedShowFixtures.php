<?php

namespace App\DataFixtures;

use App\Entity\SuggestedShow;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class SuggestedShowFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $titleZorro = new SuggestedShow();
        $titleZorro->setSuggestedTitle("ZORRO");

        $titleSoleil = new SuggestedShow();
        $titleSoleil->setSuggestedTitle("LE ROI SOLIEL");

        $titleHonk = new SuggestedShow();
        $titleHonk->setSuggestedTitle("HONK!");

        $manager->persist($titleZorro);
        $manager->persist($titleSoleil);
        $manager->persist($titleHonk);

        $manager->flush();
    }
}
