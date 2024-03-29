<?php

namespace App\Service;

use App\Entity\Character;

interface CharacterServiceInterface
{
    
    /**
     * Create the character
     */

    public function create(String $data);

    /**
     * Create all the characters
     */
    public function getAll();

    /**
     * Create all the characters by intelligence level
     */
    public function getByIntelligence(int $intelligence);

    /**
     * Modify a character
     */

    public function modify(Character $character, string $data);

    /**
    * Checks if the netity has been well filled
    */

    public function isEntityFilled(Character $character);

    /**
    * Submits the date to hydrate the object
    */

    public function submit(Character $character, $formName, $data);

    /**
    * Delete a character
    */

    public function delete(Character $character);

    /**
    * Creates the character from html form
    */

    public function createFromHtml(Character $character);

    /**
    * Modifies the character from html form
    */

    public function modifyFromHtml(Character $character);
}
