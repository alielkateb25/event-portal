<?php
namespace App\Controller\Api;

use App\Entity\Event;
use App\Entity\Review;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ReviewApiController extends AbstractController
{
    #[Route('/api/events/{id}/reviews', name: 'api_event_reviews', methods: ['GET'])]
    public function list(Event $event): JsonResponse
    {
        $reviews = $event->getReviews()->map(function(Review $r) {
            return [
                'id' => $r->getId(),
                'rating' => $r->getRating(),
                'comment' => $r->getComment(),
                'author' => $r->getUser()->getEmail(),
                'createdAt' => $r->getCreatedAt()->format('Y-m-d H:i:s'),
            ];
        })->toArray();
        
        return $this->json([
            'averageRating' => $event->getAverageRating(),
            'totalReviews' => $event->getReviews()->count(),
            'reviews' => $reviews,
        ]);
    }
    
    #[Route('/api/reviews', name: 'api_review_create', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $em
    ): JsonResponse {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        
        $data = json_decode($request->getContent(), true);
        $event = $em->getRepository(Event::class)->find($data['eventId']);
        
        $review = new Review();
        $review->setRating($data['rating']);
        $review->setComment($data['comment']);
        $review->setEvent($event);
        $review->setUser($this->getUser());
        $review->setCreatedAt(new \DateTimeImmutable());
        
        $em->persist($review);
        $em->flush();
        
        return $this->json(['success' => true, 'reviewId' => $review->getId()], 201);
    }
}