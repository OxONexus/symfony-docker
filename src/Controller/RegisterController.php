<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'register', methods: 'POST')]
    public function index(
        Request $request,
        ManagerRegistry $managerRegistry,
        SerializerInterface $serializer,
        UserPasswordHasherInterface $hasher
    ): Response
    {
        $em = $managerRegistry->getManager();
        $content = json_decode($request->getContent(), true);

        $user = new User();
        $user->setEmail($content['email']);
        $user->setPassword($hasher->hashPassword($user, $content['password']));

        $em->persist($user);
        $em->flush();

        return new Response($serializer->serialize($user, 'json'), Response::HTTP_CREATED);
    }
}
