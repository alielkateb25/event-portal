<?php
// src/Controller/EventRegistrationController.php

namespace App\Controller;

use App\Entity\Event;
use App\Service\EventRegistrationService;
use Doctrine\ORM\EntityManagerInterface;  // ← Add this import
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EventRegistrationController extends AbstractController
{
    #[Route('/event/{id}/register', name: 'app_event_register', methods: ['POST'])]
    public function register(
        Event $event,
        Request $request,
        EventRegistrationService $registrationService
    ): Response {
        // Handle AJAX or standard form submit
        if ($request->isXmlHttpRequest() || $request->getRequestFormat() === 'json') {
            $result = $registrationService->registerUserForEvent($event);
            $status = $result['success'] ? 200 : 400;
            return $this->json($result, $status);
        }
        
        // Standard form flow
        $result = $registrationService->registerUserForEvent($event);
        
        if ($result['success']) {
            $this->addFlash('success', 'Successfully registered for ' . $event->getTitle());
        } else {
            $this->addFlash('error', implode(', ', $result['errors']));
        }
        
        return $this->redirectToRoute('app_event_show', ['id' => $event->getId()]);
    }
    
    #[Route('/event/{id}/unregister', name: 'app_event_unregister', methods: ['POST'])]
    public function unregister(
        Event $event, 
        Request $request,
        EntityManagerInterface $entityManager  // ← Inject EntityManager
    ): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        
        $user = $this->getUser();
        if ($event->getRegisteredUsers()->contains($user)) {
            $event->removeRegisteredUser($user);
            $entityManager->flush();  // ← Use injected EntityManager
            $this->addFlash('success', 'Registration cancelled.');
        }
        
        return $this->redirectToRoute('app_event_show', ['id' => $event->getId()]);
    }
}