<?php
namespace App\Service;

use App\Entity\User;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserSecurityService
{
    /**
     * @var UserPasswordHasherInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordHasherInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function setupUser(User $user): void
    {

        $password = $this->passwordEncoder->hashPassword($user, $user->getPassword());

        $user->setPassword($password);
    }
}