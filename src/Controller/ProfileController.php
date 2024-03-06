<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\User2Type;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @Route("/profile")
 */
class ProfileController extends AbstractController
{
    /**
     * @Route("", name="app_profile_show", methods={"GET"})
     */
    public function show(): Response
    {   
        $user = $this ->getUser();
        return $this->render('profile/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/edit", name="app_profile_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, UserRepository $userRepository, UserPasswordHasherInterface  $passwordHasher): Response
    {
        $user = $this ->getUser();
        $form = $this->createForm(User2Type::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword') ->getData(); //On récupère le mot de passe qui est en clair dans le form
            $hashPassword = $passwordHasher->hashPassword($user, $plainPassword); //  Hash password avant d'envoyer en BDD
            $user->setPassword($hashPassword);

            $userRepository->add($user, true);

            return $this->redirectToRoute('app_profile_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profile/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
}