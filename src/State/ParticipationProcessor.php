<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class ParticipationProcessor implements ProcessorInterface
{

    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface $persistProcessor,
        private readonly Security $security,
    )
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        assert($data->getPlaytest()->getParticipants()->count() < $data->getPlaytest()->getNbMaxPlayer(), "The Playtest already reach the maximum amount of players");
        foreach ($this->security->getUser()->getParticipations() as $participation) {
            assert($data->getPlaytest()->getEnd() <= $participation->getPlaytest()->getBegin() || $data->getPlaytest()->getBegin() >= $participation->getPlaytest()->getEnd(), "The player already has a playtest on this date");
        }
        $data->setPlayer($this->security->getUser());
        // Handle the state
        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
