<?php
namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Firebase\JWT\JWT;

class AuthController extends AbstractController
{
    private EntityManagerInterface $em;
    private UserPasswordHasherInterface $hasher;

    public function __construct(EntityManagerInterface $em, UserPasswordHasherInterface $hasher)
    {
        $this->em = $em;
        $this->hasher = $hasher;
    }
    public function login(Request $request): JsonResponse
{
    $data = json_decode($request->getContent(), true) ?? [];
    $username = $data['username'] ?? '';
    $password = $data['password'] ?? '';

    $user = $this->em->getRepository(User::class)
        ->findOneBy(['username' => $username]);

    if (!$user) {
        return $this->json(['error' => 'User not found', 'provided_username' => $username], 404);
    }

    $hashed = $user->getPassword();
    $isValid = $this->hasher->isPasswordValid($user, $password);

    return $this->json([
        'db_username'      => $user->getUsername(),
        'db_hashed_pass'   => $hashed,
        'provided_password'=> $password,
        'password_valid?'  => $isValid,
    ]);
}


}
