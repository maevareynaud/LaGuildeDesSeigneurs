<?php

namespace App\Service;

use App\Entity\Character;
use DateTime;
use App\Form\PlayerType;
use App\Entity\Player;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PlayerRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\VarDumper\Cloner\Data;
use LogicException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;



class PlayerService implements PlayerServiceInterface 
{

    private $em;
    private $playerRepository;
    private $formFactory;


    public function __construct(PlayerRepository $playerRepository, EntityManagerInterface $em, FormFactoryInterface $formFactory)
    {
        $this->playerRepository = $playerRepository;
        $this->em = $em;
        $this->formFactory = $formFactory;
    }

    public function create(String $data) 
    {
        $player = new Player(); 
        $player
            ->setCreation(new \DateTime())
            ->setModification(new \DateTime())
            ->setIdentifier(hash('sha1', uniqid()))
        ;
        $this->submit($player, PlayerType::class, $data);
        $this->isEntityFilled($player);

        //tell Doctrine you want to save the Player (no queries yet)
        $this->em->persist($player);

        //actually executes the queries (i.e. the INSERT query)
        $this->em->flush();

        return $player;
    }

    /**
     * {@inheritdoc}
     */
    public function isEntityFilled(Player $player)
    {
        if (null === $player->getFirstname() ||
            null === $player->getLastname() ||
            null === $player->getIdentifier() ||
            null === $player->getCreation() ||
            null === $player->getModification() ||
            null === $player->getEmail()) {
            throw new UnprocessableEntityHttpException('Missing data for Entity -> ' . json_encode($player->toArray()));
        }
    }
   
    /**
     * {@inheritdoc}
     */
    public function submit($player, $formName, $data)
    {
        $dataArray = is_array($data) ? $data : json_decode($data, true);

        //Bad array
        if (null !== $data && !is_array($dataArray)) {
            throw new UnprocessableEntityHttpException('Submitted data is not an array -> ' . $data);
        }

        //Submits form
        //Create form
        $form = $this->formFactory->create($formName, $player, ['csrf_protection' => false]);
        $form->submit($dataArray, false);//With false, only submitted fields are validated

        //Gets errors
        $errors = $form->getErrors();
        foreach ($errors as $error) {
            throw new LogicException('Error ' . get_class($error->getCause()) . ' --> ' . $error->getMessageTemplate() . ' ' . json_encode($error->getMessageParameters()));
        }
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

    public function modify(Player $player, String $data)
    {
        $this->submit($player, PlayerType::class, $data);
        $this->isEntityFilled($player);
        $player

            ->setModification(new \DateTime())
        ;

        //tell Doctrine you want to save the Character (no queries yet)
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