<?php

namespace App\Controller;

use App\Entity\SupportTicket;
use App\Form\SupportTicketType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SupportTicketController extends AbstractController
{
    public function __construct(
        private HttpClientInterface $httpClient,
        #[\Symfony\Component\DependencyInjection\Attribute\Autowire(env: 'TICKET_WEBHOOK_URL')]
        private string $webhookUrl,
    ) {}

    #[Route('/', name: 'ticket_redirect')]
    public function index(): Response
    {
        return $this->redirectToRoute('ticket_new');
    }

    #[Route('/ticket/new', name: 'ticket_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $ticket = new SupportTicket();
        $form = $this->createForm(SupportTicketType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($ticket);
            $em->flush();

            try {
                $this->httpClient->request('POST', $this->webhookUrl, [
                    'json' => [
                        'username' => $ticket->getUsername(),
                        'beschreibung' => $ticket->getBeschreibung(),
                    ],
                    'timeout' => 3,
                ])->getStatusCode();
            } catch (\Throwable) {
                // Webhook-Fehler werden ignoriert – Ticket wurde gespeichert
            }

            return $this->redirectToRoute('ticket_success');
        }

        return $this->render('support_ticket/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/ticket/success', name: 'ticket_success', methods: ['GET'])]
    public function success(): Response
    {
        return $this->render('support_ticket/success.html.twig');
    }
}
