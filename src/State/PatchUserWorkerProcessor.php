<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\UserWorker;
use App\Repository\UpgradeRepository;
use App\Repository\UserRepository;
use App\Repository\WorkerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class PatchUserWorkerProcessor implements ProcessorInterface
{
    public function __construct(
        private UpgradeRepository $upgradeRepository,
        private UserRepository $userRepository,
        private WorkerRepository $workerRepository,
        private EntityManagerInterface $manager,
        private Security $security
        ){
    }
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        dd($data);
        $user = $this->security->getUser();
        $user = $this->userRepository->find($user);
        $worker = $this->workerRepository->find($uriVariables['id']);
        $userWorker = new UserWorker;
        
        $quantity=1;

        $userWorker->setCalculatedIncome($worker->getBaseIncome())
            ->setQuantity($userWorker->getQuantity() + $quantity)
            ->setIdWorker($worker);

        $user->addUserWorker($userWorker);
        $user->setLastConnection();
        $this->manager->persist($userWorker);
        $this->manager->persist($user);
        $this->manager->flush();
        $this->manager->clear();
    }
}
