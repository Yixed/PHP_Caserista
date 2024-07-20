<?php

namespace App\Controller;

use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route("/user",name:"profile")]
    public function showUser()
    {

        return new Response('pagina perfil');
    }
    #[Route("/user/seed")]
    public function seedUser(EntityManagerInterface $doctrine)
    {
        $user1 = new User();
        $user1->setEmail("user3@gmail.com");
        $user1->setName("Usuario 3");
        $user1->setPassword(password_hash("user3", PASSWORD_DEFAULT));
        $user1->setPhone(666111333);
        $user1->setRoles(["ROLE_USER"]);

        $user2 = new User();
        $user2->setEmail("admin@gmail.com");
        $user2->setName("Administrator");
        $user2->setPassword(password_hash("admin", PASSWORD_DEFAULT));
        $user2->setPhone(123456789);
        $user2->setRoles(["ROLE_USER","ROLE_ADMIN"]);

        $doctrine->persist($user1);
        $doctrine->persist($user2);
        $doctrine->flush();

        return new Response('añadidos usuarios 1 y 2');
    }

    #[Route("/insert/user",name:"insertUser")]
    public function insertUser(EntityManagerInterface $doctrine, Request $request, userPasswordHasherInterface $hasher)
    {
        $form = $this -> createForm(UserType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userForm = $form->getData();

            $userForm->setPassword($hasher->hashPassword($userForm,$userForm->getPassword()));

            $doctrine->persist($userForm);
            $doctrine->flush();

            $this->addFlash("exito","Registro de usuario completado con exito");
            return $this->redirectToRoute("insertUser");
        }

        return $this->render("User/insertUser.html.twig", ["userForm" => $form]);
    }

    #[Route("/insert/admin",name:"insertAdmin")]
    public function insertAdmin(EntityManagerInterface $doctrine, Request $request, userPasswordHasherInterface $hasher)
    {
        $form = $this -> createForm(UserType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userForm = $form->getData();

            $userForm->setPassword($hasher->hashPassword($userForm,$userForm->getPassword()));
            $userForm->setRoles(["ROLE_USER","ROLE_ADMIN"]);

            $doctrine->persist($userForm);
            $doctrine->flush();

            $this->addFlash("exito","carga de administrador completada");
            return $this->redirectToRoute("insertUser");
        }

        return $this->render("Users/insertUser.html.twig", ["userForm" => $form]);
    }
}