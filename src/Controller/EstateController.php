<?php

namespace App\Controller;

use App\Entity\Estate;
use App\Entity\Pokemon;
use App\Form\EstateType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EstateController extends AbstractController
{
    #[Route("/buscar",name:"search")]
    public function search(EntityManagerInterface $doctrine)
    {
        $repository = $doctrine -> getRepository(Estate::class);
        $repository = $repository -> findAll();

        return $this->render("estate/estate.html.twig",["estateArray" => $repository]);
    }

    #[Route("/estate/{id}",name: "showEstate", methods: ['GET'])]
    public function showEstate(EntityManagerInterface $doctrine, $id)
    {
        $repository = $doctrine -> getRepository(Estate::class);
        $estate = $repository -> find($id);

        return $this->render("Estate/showEstate.html.twig",["estate" => $estate]);
    }

    #[Route("/insert/estate",name:"insertEstate")]
    public function insertEstate(EntityManagerInterface $doctrine, Request $request)
    {
        $form = $this -> createForm(EstateType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $estateForm = $form->getData();
            $doctrine->persist($estateForm);
            $doctrine->flush();

            $this->addFlash("exito","carga completada");
            return $this->redirectToRoute("insertEstate");
        }

        return $this->render("Estate/insertEstate.html.twig", ["estateForm" => $form]);
    }

    #[Route("/edit/estate/{id}",name:"editEstate")]
    public function editEstate(EntityManagerInterface $doctrine, Request $request, $id)
    {
        $repository = $doctrine -> getRepository(Estate::class);
        $estate = $repository -> find($id);

        $form = $this -> createForm(EstateType::class, $estate);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $estateForm = $form->getData();
            $doctrine->persist($estateForm);
            $doctrine->flush();

            $this->addFlash("exito","carga completada");
            return $this->redirectToRoute("showEstate", ["id" => $id]);
        }

        return $this->render("estate/insertEstate.html.twig", ["estateForm" => $form]);
    }

    #[Route("/delete/estate/{id}",name: "deleteEstate")]
    public function deleteEstate(EntityManagerInterface $doctrine, $id)
    {
        $repository = $doctrine -> getRepository(Estate::class);
        $estate = $repository -> find($id);

        $doctrine->remove($estate);
        $doctrine->flush();

        return $this->redirectToRoute("search");
    }
}
