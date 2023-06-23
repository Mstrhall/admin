<?php

namespace App\Events;

use App\Entity\Personne;
use Psr\EventDispatcher\EventDispatcherInterface;


use Symfony\Contracts\EventDispatcher\Event;

class AddPersonneEvents extends Event
{
const ADD_PERSONNE_EVENT ='personne.add';
public function __construct(private Personne $personne){


}

    /**
     * @return Personne
     */
    public function getPersonne(): Personne
    {
        return $this->personne;
    }
}