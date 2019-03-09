<?php
/**
 * User: cst224
 * Date: 11/15/2018
 * SERVER LOGIC BLOCK
 */

namespace App\Service;

/**
 * Class DocumentCleaner
 * @package App\Service
 *
 * This class will contain the helper functions for documents
 * -- Nathan
 */
class DocumentCleaner
{
    /**
     * This function merely takes in a string of text and removes any errant html or that to clean it before it gets saved to the db --Nathan
     *
     * @param string $text - the text needing to be saved to the db
     * @return string - the cleaned text
     */
    public function cleanText($text)
    {
        return htmlentities(strip_tags($text));
    }

}