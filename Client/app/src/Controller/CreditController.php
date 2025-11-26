<?php

namespace App\Controller;

use App\Service\CreditService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/credit')]
class CreditController extends AbstractController
{
    public function __construct(
        private readonly CreditService $creditService
    ) {
    }

    #[Route('/add', name: 'credit_add', methods: ['POST'])]
    public function addCredit(): JsonResponse
    {
        try {
            $result = $this->creditService->addCredit();

            return $this->json($result, Response::HTTP_OK);
        } catch (\RuntimeException $e) {
            return $this->json(
                ['error' => $e->getMessage()],
                $e->getCode() ?: Response::HTTP_BAD_REQUEST
            );
        } catch (\Exception $e) {
            return $this->json(
                ['error' => 'Internal server error'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    #[Route('/remove', name: 'credit_remove', methods: ['POST'])]
    public function removeCredit(): JsonResponse
    {
        try {
            $result = $this->creditService->removeCredit();

            return $this->json($result, Response::HTTP_OK);
        } catch (\RuntimeException $e) {
            return $this->json(
                ['error' => $e->getMessage()],
                $e->getCode() ?: Response::HTTP_BAD_REQUEST
            );
        } catch (\Exception $e) {
            return $this->json(
                ['error' => 'Internal server error'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
