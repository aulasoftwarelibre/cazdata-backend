<?php

declare(strict_types=1);

namespace App\Command;

use Kreait\Firebase\Auth;
use Kreait\Firebase\Exception\Auth\UserNotFound;
use Kreait\Firebase\Request\CreateUser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use function is_string;

class CazdataHunterTokenCommand extends Command
{
    protected static $defaultName = 'cazdata:hunter:token';
    private Auth $auth;

    public function __construct(Auth $auth, ?string $name = null)
    {
        parent::__construct($name);

        $this->auth = $auth;
    }

    protected function configure() : void
    {
        $this
            ->setDescription('Get a hunter token from console')
            ->addArgument('identifier', InputArgument::REQUIRED, 'User Identifier');
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $io         = new SymfonyStyle($input, $output);
        $identifier = $input->getArgument('identifier');

        if (! is_string($identifier)) {
            throw new InvalidArgumentException('Se requiere correo electrónico');
        }

        $user  = $this->getUser($identifier);
        $token = $this->auth->createCustomToken($user->uid, $user->jsonSerialize());

        $io->title('User token');
        $io->writeln('<info>' . $token . '</info>');

        $signInResult = $this->auth->signInWithCustomToken($token);
        $io->title('Sign-in token');
        $io->writeln('<info>' . $signInResult->idToken() . '</info>');

        $verifyIdToken = $this->auth->verifyIdToken($signInResult->idToken());
        $io->title('Verified sub');
        $io->writeln('<info>' . $verifyIdToken->getClaim('sub') . '</info>');

        return 0;
    }

    protected function getUser(string $identifier) : Auth\UserRecord
    {
        try {
            $user = $this->auth->getUserByEmail($identifier);
        } catch (UserNotFound $e) {
            $request = CreateUser::new()
                ->withDisplayName('John Doe')
                ->withPhotoUrl('https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&f=y')
                ->withVerifiedEmail($identifier);
            $user    = $this->auth->createUser($request);
        }

        return $user;
    }
}
