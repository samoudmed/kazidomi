<?php

namespace App\Controller;

use App\Entity\Merchant;
use App\Repository\MerchantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use DateTimeImmutable;

class MerchantController extends AbstractController {

    private $entityManager;
    private $merchantRepository;

    public function __construct(EntityManagerInterface $entityManager, MerchantRepository $merchantRepository) {
        $this->entityManager = $entityManager;
        $this->merchantRepository = $merchantRepository;
    }

    #[Route('/merchant/create/{name}', name: 'create_merchant')]
    public function createMerchant(string $name): JsonResponse {

        if (!$name) {
            return new JsonResponse('Name is required');
        }

        $merchant = new Merchant();
        $merchant->setName($name);
        $merchant->setCreatedAt(new DateTimeImmutable());

        $this->entityManager->persist($merchant);
        $this->entityManager->flush();

        return new JsonResponse('Good job! Merchant ' . $merchant->getName() . ' created #' . $merchant->getId());
    }

    #[Route('/merchant/list', name: 'list_merchants')]
    public function listMerchants(): array {

        $merchants = $this->merchantRepository->findAll();
        $merchantData = [];

        foreach ($merchants as $merchant) {
            $merchantData[] = [
                'id' => $merchant->getId(),
                'name' => $merchant->getName(),
            ];
        }

        return $merchantData;
    }

    #[Route('/merchant/get/{id}', name: 'get_merchant')]
    public function getMerchant(int $id): JsonResponse {

        $merchant = $this->merchantRepository->find($id);

        if (!$merchant) {
            return new JsonResponse('Merchant not found');
        }

        return new JsonResponse('Merchant ' . $merchant->getName() . ' created #' . $merchant->getId());
    }

}
