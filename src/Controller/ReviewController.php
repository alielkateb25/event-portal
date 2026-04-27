<?php
namespace App\Controller;

use App\Entity\Event;
use App\Entity\Review;
use App\Form\ReviewType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ReviewController extends AbstractController
{
    #[Route('/event/{id}/review', name: 'app_review_create', methods: ['GET', 'POST'])]
    public function new(
        Event $event,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        
        // Check user attended the event
        if (!$event->getRegisteredUsers()->contains($this->getUser())) {
            throw $this->createAccessDeniedException('You must attend this event to review it.');
        }
        
        // Check already reviewed
        $existingReview = $entityManager->getRepository(Review::class)->findOneBy([
            'event' => $event,
            'user' => $this->getUser(),
        ]);
        
        if ($existingReview) {
            $this->addFlash('error', 'You have already reviewed this event.');
            return $this->redirectToRoute('app_event_show', ['id' => $event->getId()]);
        }
        
        $review = new Review();
        $review->setEvent($event);
        $review->setUser($this->getUser());
        
        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $review->setCreatedAt(new \DateTimeImmutable());
            $entityManager->persist($review);
            $entityManager->flush();
            
            $this->addFlash('success', 'Thank you for your review!');
            return $this->redirectToRoute('app_event_show', ['id' => $event->getId()]);
        }
        
        return $this->render('review/new.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }
    
    #[Route('/review/{id}/edit', name: 'app_review_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Review $review,
        EntityManagerInterface $entityManager
    ): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        
        // Only owner can edit
        if ($review->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You can only edit your own review.');
        }
        
        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            
            $this->addFlash('success', 'Review updated!');
            return $this->redirectToRoute('app_event_show', ['id' => $review->getEvent()->getId()]);
        }
        
        return $this->render('review/edit.html.twig', [
            'review' => $review,
            'form' => $form,
            'event' => $review->getEvent(),
        ]);
    }
    
    #[Route('/review/{id}', name: 'app_review_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Review $review,
        EntityManagerInterface $entityManager
    ): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        
        // Only owner can delete
        if ($review->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You can only delete your own review.');
        }
        
        // CSRF check
        if ($this->isCsrfTokenValid('delete'.$review->getId(), $request->getPayload()->getString('_token'))) {
            $event = $review->getEvent();
            $entityManager->remove($review);
            $entityManager->flush();
            
            $this->addFlash('success', 'Review deleted.');
            return $this->redirectToRoute('app_event_show', ['id' => $event->getId()]);
        }
        
        return $this->redirectToRoute('app_event_show', ['id' => $review->getEvent()->getId()]);
    }
}