<?php

namespace App\Controller;

use App\Entity\Entree;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\EntreeType;
use Symfony\Component\HttpFoundation\Request;

class EntreeController extends AbstractController
{

    #[Route('/entree', name: 'entree_liste')]
    public function entree(ManagerRegistry $doctrine): Response
    {
        $e = new Entree();
        $form = $this->createForm(EntreeType::class, $e, array(
            'action' => $this->generateUrl('addEntreProduit')
        ));
        $data['formE'] = $form->createView();

        $data['entree'] =  $doctrine->getRepository(Entree::class)->findAll();

        return $this->render("entree/liste.html.twig", $data);
    }

    #[Route('/entree/addEntreProduit', name: 'addEntreProduit')]
    public function addEntreProduit(ManagerRegistry $doctrine, Request $request): Response
    {
        /*if(isset($_POST['produit'])) {
            $json = $_POST['produit'];
            var_dump(json_decode($json, true));
        } else {
            echo "Noooooooob";
        }*/

        

        $e = new Entree();
        $form = $this->createForm(EntreeType::class, $e);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $e = $form->getData();

            $e->setUser($this->getUser());
            $entityManager =  $doctrine->getManager();
            $entityManager->persist($e);
            $entityManager->flush();
        }

        return $this->redirectToRoute('entree_liste');
    }
}
