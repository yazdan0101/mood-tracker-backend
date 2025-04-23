<?php

namespace App\Controller\Api;

use App\Entity\MoodEntry;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class MoodEntryController extends AbstractController
{
    private EntityManagerInterface $em;
    private string $jwtSecret;

    public function __construct(EntityManagerInterface $em, string $jwtSecret)
    {
        $this->em = $em;
        $this->jwtSecret = $jwtSecret;
    }

    #[\Symfony\Component\Routing\Annotation\Route('/api/mood-entries', name: 'api_mood_entries_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        // 1. Authenticate via Bearer token
        $authHeader = $request->headers->get('Authorization');
        if (!preg_match('/Bearer\s+(\S+)/', $authHeader, $matches)) {
            return $this->json(['error' => 'Missing or invalid Authorization header'], 401);
        }
        $token = $matches[1];

        try {
            $payload = JWT::decode($token, new Key($this->jwtSecret, 'HS256'));
        } catch (\Exception $e) {
            return $this->json(['error' => 'Invalid token: ' . $e->getMessage()], 401);
        }

        $user = $this->em->getRepository(User::class)->find($payload->sub);
        if (!$user) {
            return $this->json(['error' => 'User not found'], 401);
        }

        // 2. Parse and validate input data
        $data = json_decode($request->getContent(), true) ?? [];
        $requiredFields = ['moodType', 'occurredAt', 'feelingList', 'sleepQuality', 'activityList', 'bestAboutToday'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || $data[$field] === '') {
                return $this->json(['error' => "Field '$field' is required"], 400);
            }
        }

        if (!is_array($data['feelingList']) || !is_array($data['activityList'])) {
            return $this->json(['error' => 'feelingList and activityList must be arrays'], 400);
        }
        if (!is_string($data['sleepQuality'])) {
            return $this->json(['error' => 'sleepQuality must be a string'], 400);
        }

        // 3. Create MoodEntry entity
        $entry = new MoodEntry();
        $entry->setMoodType((string)$data['moodType'])
            ->setOccurredAt(new \DateTimeImmutable($data['occurredAt']))
            ->setFeelingList($data['feelingList'])
            ->setSleepQuality($data['sleepQuality'])
            ->setActivityList($data['activityList'])
            ->setBestAboutToday((string)$data['bestAboutToday'])
            ->setNote($data['note'] ?? null)
            ->setUser($user);

        // 4. Save to database
        $this->em->persist($entry);
        $this->em->flush();

        return $this->json(['status' => 'created'], 201);
    }
}
