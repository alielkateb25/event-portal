<?php
// src/Service/EventRegistrationService.php

namespace App\Service;

use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class EventRegistrationService
{
    public function __construct(
        private EntityManagerInterface $em,
        private Security $security
    ) {}
    
    public function registerUserForEvent(Event $event): array 
    {
        $user = $this->security->getUser();
        $errors = [];
        
        if (!$user) {
            $errors[] = 'You must be logged in';
        } elseif ($event->getMaxSeats() && $event->getRegisteredUsers()->count() >= $event->getMaxSeats()) {
            $errors[] = 'Event is fully booked';
        } elseif ($event->getRegisteredUsers()->contains($user)) {
            $errors[] = 'Already registered';
        }
        
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }
        
        $event->addRegisteredUser($user);
        $this->em->flush();
        
        return ['success' => true];
    }
}