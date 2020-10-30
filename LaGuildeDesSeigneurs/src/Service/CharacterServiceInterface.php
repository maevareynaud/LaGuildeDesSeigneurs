<?php

namespace App\Service;

interface CharacterServiceInterface {
    
    /** 
     * Create the character
     */

    public function create();

    /** 
     * Create all the characters
     */
    public function getAll();
}


