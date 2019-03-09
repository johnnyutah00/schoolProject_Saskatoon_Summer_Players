<?php
/**
 * User: cst224
 * Date: 11/15/2018
 * SERVER LOGIC BLOCK
 */

namespace App\Service;

use App\Service\DocumentCleaner;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class DocumentCleanerTest extends TestCase
{
    /**
     * This will test that bad input passed into the DocumentCleaner will be correctly "cleaned" for db persistence
     */
    public function testCleanTextWithHTMLInInput()
    {
        $initialText = '<body>cats cats cats. who likes <b>cats?</b></body>';
        $cleanText = 'cats cats cats. who likes cats?';

        $cleaner = new DocumentCleaner();

        $this->assertEquals($cleanText, $cleaner->cleanText($initialText));
    }

    /**
     * This will ensure that input that is already good will not be changed when passed through the DocumentCleaner
     */
    public function testCleanTextWithGoodInput()
    {
        $cleanText = 'cats cats cats. who likes cats?';

        $cleaner = new DocumentCleaner();

        $this->assertEquals($cleanText, $cleaner->cleanText($cleanText));
    }
}
