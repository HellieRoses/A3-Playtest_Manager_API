<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class PlayTestProcessor implements ProcessorInterface
{

    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface $persistProcessor,
        private readonly Security $security
    )
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        //Check if the video game owner is the same company as the one connected
        assert($data->getVideoGame()->getCompany() == $this->security->getUser(),"The Video Game must be created by this company");
        // Define the company connected as the creator of the event
        $data->setCompany($this->security->getUser());
        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);

    }
}
