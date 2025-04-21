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
    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true) ?? [];
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';
    
        $user = $this->em->getRepository(User::class)
            ->findOneBy(['username' => $username]);
    
        if (!$user || !$this->hasher->isPasswordValid($user, $password)) {
            return $this->json(['error' => 'Invalid credentials'], 401);
        }
    
        $payload = [
            'sub'      => $user->getId(),
            'username' => $user->getUsername(),
            'iat'      => time(),
            'exp'      => time() + 3600,
        ];
    
        $secret = $this->getParameter('app.jwt_secret');
        $token  = JWT::encode($payload, $secret, 'HS256');
    
        return $this->json(['token' => $token]);
    }
    


}
