<?php

namespace App\Security\Voter;

use LogicException;
use App\Entity\Character;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CharacterVoter extends Voter
{
    public const CHARACTER_DISPLAY = 'characterDisplay';
    public const CHARACTER_CREATE = 'characterCreate';
    public const CHARACTER_INDEX = 'characterIndex';
    public const CHARACTER_MODIFY = 'characterModify';
    public const CHARACTER_DELETE = 'characterDelete';
    

    private const ATTRIBUTES = array(
        self::CHARACTER_DISPLAY,
        self::CHARACTER_CREATE,
        self::CHARACTER_INDEX,
        self::CHARACTER_MODIFY,
        self::CHARACTER_DELETE,
    );

    /**
     * Est ce que je supporte l'attribut qui est demandé, ici l'attribut est characterDisplay
     */

    protected function supports($attribute, $subject)
    {

        if(null !== $subject){
            return $subject instanceof Character && in_array($attribute, self::ATTRIBUTES);
        }
        return in_array($attribute, self::ATTRIBUTES);
    }

    /**
     * Si l'attribut est supporté, est qu'on a les droit la dessus ? 
     */
    
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        switch ($attribute) {
            case self::CHARACTER_DISPLAY:
            case self::CHARACTER_INDEX:
                // peut envoyer $token et $subject pour tester des conditions
                return $this->canDisplay();
                break;
            case self::CHARACTER_CREATE:
                // peut envoyer $token et $subject pour tester des conditions
                return $this->canCreate();
                break;
            case self::CHARACTER_MODIFY:
                // peut envoyer $token et $subject pour tester des conditions
                return $this->canModify();
                break;
            case self::CHARACTER_DELETE:
                // peut envoyer $token et $subject pour tester des conditions
                return $this->canDelete();
                break;
        }

        throw new LogicException('Invalid attribute: ' . $attribute);
    }

    /**
     * Check if is allowed to display
     */

    public function canDisplay(){
        return true;
    }

     /**
     * Check if is allowed to create
     */

    public function canCreate(){
        return true;
    }

    /**
     * Check if is allowed to modify
     */

    public function canModify(){
        return true;
    }

    /**
     * Check if is allowed to delete
     */

    public function canDelete(){
        return true;
    }
}
