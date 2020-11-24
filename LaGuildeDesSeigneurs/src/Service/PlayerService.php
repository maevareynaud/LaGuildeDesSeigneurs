<?php

namespace App\Service;

use App\Entity\Character;
use DateTime;
use App\Form\PlayerType;
use App\Entity\Player;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PlayerRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\VarDumper\Cloner\Data;
use LogicException;
use App\Event\PlayerEvent;
use Psr\EventDispatcher\EventDispatcherInterface;


class PlayerService implements PlayerServiceInterface
{
    private $em;
    private $playerRepository;
    private $formFactory;
    private $validator;
    private $dispatcher;




    public function __construct(PlayerRepository $playerRepository, EntityManagerInterface $em, FormFactoryInterface $formFactory, ValidatorInterface $validator, EventDispatcherInterface $dispatcher)
    {
        $this->playerRepository = $playerRepository;
        $this->em = $em;
        $this->formFactory = $formFactory;
        $this->validator = $validator;
        $this->dispatcher = $dispatcher;
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
        $errors = $this->validator->validate($player);
        if (count($errors) > 0) {
            throw new UnprocessableEntityHttpException((string) $errors . 'Missing data for Entity -> ' . json_encode($player->toArray()));
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
        foreach ($players as $player) {
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


        $event = new PlayerEvent($player);
        $this->dispatcher->dispatch($event, PlayerEvent::PLAYER_MODIFIED);

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
