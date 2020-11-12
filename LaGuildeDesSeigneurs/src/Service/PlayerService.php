<?php

namespace App\Service;

use App\Entity\Character;
use DateTime;
use App\Entity\Player;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PlayerRepository;
use Symfony\Component\VarDumper\Cloner\Data;

class PlayerService implements PlayerServiceInterface 
{

    private $em;
    private $playerRepository;

    public function __construct(PlayerRepository $playerRepository, EntityManagerInterface $em)
    {
        $this->playerRepository = $playerRepository;
        $this->em = $em;
    }

    public function create() 
    {
        $player = new Player(); 
        $player
            ->setFirstname('Maeva')
            ->setLastname('Reynaud')
            ->setEmail('maevareynaud@hotmailfr')
            ->setMirian(10)
            ->setCreation(new \DateTime())
            ->setModification(new \DateTime())
            ->setIdentifier(hash('sha1', uniqid()))
            ->setCharacterPlayed(5);
        ;

        //tell Doctrine you want to save the Player (no queries yet)
        $this->em->persist($player);

        //actually executes the queries (i.e. the INSERT query)
        $this->em->flush();

        return $player;
    }

    public function getAll()
    {
        $playersFinal = array();
        $players = $this->playerRepository->findAll();
        foreach($players as $player){
            $playersFinal[]=$player->toArray();
        }

        return $playersFinal;
    }

    public function modify(Player $player)
    {
        $player
            ->setFirstname('Elise')
            ->setLastname('Valloire')
            ->setEmail('elise@hotmailfr')
            ->setMirian(20)
            ->setModification(new \DateTime())
        ;

        //tell Doctrine you want to save the Player (no queries yet)
        $this->em->persist($player);

        //actually executes the queries (i.e. the INSERT query)
        $this->em->flush();

        return $player;
    }

    public function delete(Player $player)
    {
        $this->em->remove($player);
        $this->em->flush();

        return true;
    }

    
}