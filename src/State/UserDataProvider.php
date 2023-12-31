<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dto\UserDataDto;
use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;

class UserDataProvider implements ProviderInterface
{
    public function __construct(private UserRepository $userRepository, private Security $security,) {
    }
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $user = $this->security->getUser();
        $user = $this->userRepository->find($user);
        $user->setLastConnection();
        $userId = $user->getId();
        $username = $user->getUserName();
        $money= $user->getMoney();
        $clicIncome = $user->getClicIncome();
        $lastConnection = $user->getLastConnection();
        $userworkers = $user->getUserWorkers();
        $userupgrades = $user->getUpgrades();
        
        return new UserDataDto(id : $userId, username : $username, money : $money, clicIncome : $clicIncome, lastConnection: $lastConnection, userworkers : $userworkers, userupgrades : $userupgrades);
    }
}
