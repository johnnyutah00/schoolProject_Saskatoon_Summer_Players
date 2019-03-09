<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class SubscriptionTimeVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        //Check to see what this voter votes on
        //This voter only votes on the "activeMember" string
        if ($attribute == "activeMember")
        {
            return true;
        }

        //Do not vote on anything else
        return false;
    }

    /**
     * @param string $attribute - a year from today's date (in Unix time) -- Has to be an int
     * @param mixed $subject - Doesn't matter what is in here as it is not used.
     * @param TokenInterface $token - Token
     * @return bool
     *
     * @author Dylan Sies, MacKenzie Wilson
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        //Get logged in user
        $user = $token->getUser();

        // if the user is anonymous, grant access
        if (!$user instanceof UserInterface) {
            return true;
        }

        //This represents one year in Unix time.
        $oneYearUnix = 31536000;

        //return true if the user still has subscription (less than one year)
        //if their membership is greater than a year then kick them out
        return $oneYearUnix > (time() - $user->getLastDatePaid()) ? true : false;

    }
}
