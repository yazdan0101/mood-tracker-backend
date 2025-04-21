<?php
namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegisterController extends AbstractController
{
    private EntityManagerInterface $em;
    private UserPasswordHasherInterface $hasher;
    private ValidatorInterface $validator;

    public function __construct(
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher,
        ValidatorInterface $validator
    ) {
        $this->em = $em;
        $this->hasher = $hasher;
        $this->validator = $validator;
    }

    public function register(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true) ?? [];
    
        $user = new User();
        $user->setUsername($data['username'] ?? '');
        $user->setPassword(
            $this->hasher->hashPassword($user, $data['password'] ?? '')
        );
    
        try {
            $this->em->persist($user);
            $this->em->flush();
            return $this->json(['status' => 'user_created'], 201);
        } catch (\Exception $e) {
            // Log the full exception for us to inspect
            file_put_contents(
                $this->getParameter('kernel.project_dir').'/var/log/register_error.txt',
                $e->__toString()
            );
            throw $e;  // re‑throw so you still get a 500
        }
    }
    
}
