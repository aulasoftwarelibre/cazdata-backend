<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use function is_string;

class CazdataUserAddCommand extends Command
{
    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     * @var string
     */
    protected static $defaultName = 'cazdata:user:add';
    private EntityManagerInterface $manager;
    private UserPasswordEncoderInterface $encoder;
    private UserRepository $userRepository;

    public function __construct(EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder, UserRepository $userRepository)
    {
        parent::__construct();
        $this->manager        = $manager;
        $this->encoder        = $encoder;
        $this->userRepository = $userRepository;
    }

    protected function configure() : void
    {
        $this
            ->setDescription('Añadir usuario')
            ->addArgument('username', InputArgument::REQUIRED, 'Nombre de usuario')
            ->addArgument('email', InputArgument::REQUIRED, 'Correo electrónico');
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $io       = new SymfonyStyle($input, $output);
        $username = $input->getArgument('username');
        $email    = $input->getArgument('email');

        if (! is_string($username)) {
            throw new InvalidArgumentException('Se requiere nombre de usuario');
        }

        if (! is_string($email)) {
            throw new InvalidArgumentException('Se requiere dirección de correo');
        }

        $user = $this->userRepository->findOneBy(['username' => $username]);
        if ($user instanceof User) {
            $io->error('El usuario ya existe');

            return 1;
        }

        $helper = $this->getHelper('question');

        $question = new Question('Introduzca la contraseña: ');
        $question->setHidden(true);
        $question->setHiddenFallback(false);
        $plainPassword = $helper->ask($input, $output, $question);

        $user = new User();

        $user->setUsername($username);
        $user->setEmail($email);
        $user->setRoles(['ROLE_ADMIN']);
        $password = $this->encoder->encodePassword($user, $plainPassword);
        $user->setPassword($password);

        $this->manager->persist($user);
        $this->manager->flush();

        $io->success('Usuario creado.');

        return 0;
    }
}
