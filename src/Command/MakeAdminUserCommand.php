<?php

namespace App\Command;

use App\Repository\UserRepository;
use App\Service\AdminUserHelperInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'makeAdmin',
    description: 'Add admin role to user',
)]
class MakeAdminUserCommand extends Command
{
    public function __construct(private readonly AdminUserHelperInterface $adminUserHelper,
    private readonly UserRepository $userRepository,
    private readonly EntityManagerInterface $entityManager)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('login', InputArgument::OPTIONAL, 'Login of user you want to make admin.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $login = $input->getArgument('login');

        $user = $this->userRepository->findOneBy(['login' => $login]);
        $user->addRole('ROLE_ADMIN');

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success('The user is now admin');

        return Command::SUCCESS;
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        $io = new SymfonyStyle($input, $output);


        $login = $input->getArgument('login');
        while (!$this->adminUserHelper->verifyLogin($login)) {
            $io->note('The login must be provided and must exists');

            $login = $io->ask('What is the login?');
        }
        $input->setArgument('login', $login);

    }
}
