<?php

namespace App\Service;
use App\Entity\Character;

interface CharacterServiceInterface {
    
    /** 
     * Create the character
     */

    public function create();

    /** 
     * Create all the characters
     */
    public function getAll();

    /**
     * Modify a character
     */

    public function modify(Character $character);

     /**
     * Delete a character
     */

    public function delete(Character $character);

}


