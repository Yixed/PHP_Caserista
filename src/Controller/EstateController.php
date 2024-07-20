<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class EstateController extends AbstractController
{
    #[Route("/busqueda",name:"search")]
    public function search()
    {

        return $this->render("estate/estate.html.twig");
    }
}
