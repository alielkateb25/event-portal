<?php
namespace App\Controller\Api;

use App\Entity\Event;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class EventApiController extends AbstractController
{
    #[Route('/api/events', name: 'api_events_list', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function list(EventRepository $eventRepository): JsonResponse
    {
        $events = $eventRepository->findAll();
        
        $data = array_map(function($event) {
            return [
                'id' => $event->getId(),
                'title' => $event->getTitle(),
                'description' => $event->getDescription(),
                'eventDate' => $event->getEventDate()->format('Y-m-d H:i:s'),
                'location' => $event->getLocation(),
                'availableSeats' => $event->getMaxSeats() 
                    ? max(0, $event->getMaxSeats() - $event->getRegisteredUsers()->count()) 
                    : '∞',
            ];
        }, $events);
        
        return $this->json($data);
    }
    
    #[Route('/api/events/{id}/reviews', name: 'api_event_reviews', methods: ['GET'])]
    public function reviews(Event $event): JsonResponse
    {
        $reviews = $event->getReviews()->map(function($review) {
            return [
                'rating' => $review->getRating(),
                'comment' => $review->getComment(),
                'author' => $review->getUser()->getEmail(),
                'date' => $review->getCreatedAt()->format('Y-m-d'),
            ];
        })->toArray();
        
        return $this->json([
            'averageRating' => $event->getAverageRating(),
            'totalReviews' => count($reviews),
            'reviews' => $reviews,
        ]);
    }
}