<?php

namespace App\Service;

use DateTime;
use App\Form\CharacterType;
use App\Entity\Character;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CharacterRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Event\CharacterEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use LogicException;
use Psr\EventDispatcher\EventDispatcherInterface;

class CharacterService implements CharacterServiceInterface
{
    private $em;
    private $characterRepository;
    private $formFactory;
    private $validator;
    private $dispatcher;


    public function __construct(CharacterRepository $characterRepository, EntityManagerInterface $em, FormFactoryInterface $formFactory, ValidatorInterface $validator, EventDispatcherInterface $dispatcher)
    {
        $this->characterRepository = $characterRepository;
        $this->em = $em;
        $this->formFactory = $formFactory;
        $this->validator = $validator;
        $this->dispatcher = $dispatcher;
    }

    public function create(String $data)
    {

        //Use with {"kind":"Dame","name":"Eldalótë","surname":"Fleur elfique","caste":"Elfe","knowledge":"Arts","intelligence":120,"life":12,"image":"/images/eldalote.jpg"}
        $character = new Character();

        $this->submit($character, CharacterType::class, $data);

        return $this->createFromHtml($character);

    }

    public function createFromHtml(Character $character)
    {

        $character
            ->setIdentifier(hash('sha1', uniqid()))
            ->setCreation(new DateTime())
            ->setModification(new DateTime())
        ;

        //Dispatch event
        $event = new CharacterEvent($character);
        $this->dispatcher->dispatch($event, CharacterEvent::CHARACTER_CREATED);


        $this->isEntityFilled($character);

        $this->em->persist($character);
        $this->em->flush();


        return $character;
    }


    /**
     * {@inheritdoc}
     */
    public function isEntityFilled(Character $character)
    {
        $errors = $this->validator->validate($character);
        if (count($errors) > 0) {
            throw new UnprocessableEntityHttpException((string) $errors . 'Missing data for Entity -> ' . json_encode($character->toArray()));
        }
    }
   
    /**
     * {@inheritdoc}
     */
    public function submit($character, $formName, $data)
    {
        $dataArray = is_array($data) ? $data : json_decode($data, true);

        //Bad array
        if (null !== $data && !is_array($dataArray)) {
            throw new UnprocessableEntityHttpException('Submitted data is not an array -> ' . $data);
        }

        //Submits form
        //Create form
        $form = $this->formFactory->create($formName, $character, ['csrf_protection' => false]);
        $form->submit($dataArray, false);//With false, only submitted fields are validated

        //Gets errors
        $errors = $form->getErrors();
        foreach ($errors as $error) {
            throw new LogicException('Error ' . get_class($error->getCause()) . ' --> ' . $error->getMessageTemplate() . ' ' . json_encode($error->getMessageParameters()));
        }
    }

    public function getAll()
    {
        $charactersFinal = array();
        $characters = $this->characterRepository->findAll();
        foreach ($characters as $character) {
            $charactersFinal[]=$character->toArray();
        }

        return $charactersFinal;
    }

    public function getByIntelligence(int $intelligence)
    {
        $charactersFinal = array();
        $characters = $this->characterRepository->findAll();
        foreach ($characters as $character) {
            if($character->getIntelligence() >= $intelligence){
                $charactersFinal[]=$character->toArray();
            }
        }
    
        return $charactersFinal;
    }


    public function modify(Character $character, string $data)
    {
        $this->submit($character, CharacterType::class, $data);

        return $this->modifyFromHtml($character);
    }

    public function modifyFromHtml(Character $character)
    {
        $this->isEntityFilled($character);
        $character
            ->setModification(new \DateTime())
        ;

        //tell Doctrine you want to save the Character (no queries yet)
        $this->em->persist($character);

        //actually executes the queries (i.e. the INSERT query)
        $this->em->flush();

        return $character;
    }
    

    public function delete(Character $character)
    {
        $this->em->remove($character);
        $this->em->flush();

        return true;
    }
}
