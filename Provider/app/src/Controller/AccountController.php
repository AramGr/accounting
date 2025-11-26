<?php

namespace App\Controller;

use App\DTO\UpdateBalanceRequest;
use App\Service\BalanceService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/account')]
class AccountController extends AbstractController
{
    public function __construct(
        private readonly BalanceService $balanceService,
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
    ) {
    }

    #[Route('/balance', name: 'account_balance_get', methods: ['GET'])]
    public function getBalance(): JsonResponse
    {
        try {
            $result = $this->balanceService->getBalance();

            return $this->json($result, Response::HTTP_OK);
        } catch (\RuntimeException $e) {
            return $this->json(
                ['error' => $e->getMessage()],
                Response::HTTP_NOT_FOUND
            );
        } catch (\Exception $e) {
            return $this->json(
                ['error' => 'Internal server error'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    #[Route('/balance', name: 'account_balance_update', methods: ['POST'])]
    public function updateBalance(Request $request): JsonResponse
    {
        try {
            $dto = $this->serializer->deserialize(
                $request->getContent(),
                UpdateBalanceRequest::class,
                'json'
            );

            $errors = $this->validator->validate($dto);

            if (count($errors) > 0) {
                $errorMessages = [];
                foreach ($errors as $error) {
                    $errorMessages[$error->getPropertyPath()] = $error->getMessage();
                }

                return $this->json(
                    ['errors' => $errorMessages],
                    Response::HTTP_BAD_REQUEST
                );
            }

            $result = $this->balanceService->updateBalance($dto->amount, $dto->getOperationType());

            return $this->json($result, Response::HTTP_OK);
        } catch (\DomainException $e) {
            return $this->json(
                ['error' => $e->getMessage()],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        } catch (\RuntimeException $e) {
            // Handle concurrent update (409 Conflict) or not found (404)
            $statusCode = 409 === $e->getCode() ? Response::HTTP_CONFLICT : Response::HTTP_NOT_FOUND;

            return $this->json(
                ['error' => $e->getMessage()],
                $statusCode
            );
        } catch (\Exception $e) {
            return $this->json(
                ['error' => 'Internal server error'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
