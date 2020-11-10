<?php

namespace App\Service;
use App\Entity\Player;

interface PlayerServiceInterface {
    
    /** 
     * Create the player
     */

    public function create();

    /** 
     * Display all the players
     */
    public function getAll();

    /**
     * Modify a player
     */

    public function modify(Player $player);

     /**
     * Delete a player
     */

    public function delete(Player $player);

}


