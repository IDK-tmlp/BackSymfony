<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dto\UserRelatedDto;
use App\Repository\UpgradeRepository;
use App\Repository\UserRepository;
use App\Repository\WorkerRepository;
use Symfony\Bundle\SecurityBundle\Security;

class UserRelatedProvider implements ProviderInterface
{
    public function __construct(private UserRepository $userRepository, private Security $security, private WorkerRepository $workerRepository, private UpgradeRepository $upgradeRepository) {
    }
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $user = $this->security->getUser();
        $user = $this->userRepository->find($user);
        $userId = $user->getId();
        $userworkers = $user->getUserWorkers();
        foreach ($userworkers as $worker) {
            $workers[] = $this->workerRepository->find($worker);
        }
        $userupgrades = $user->getUpgrades();
        foreach ($userupgrades as $upgrade) {
            $upgrades[] = $this->upgradeRepository->find($upgrade);
        }
        
        return new UserRelatedDto(id : $userId, userworkers : $workers, userupgrades : $upgrades);
    }
}
