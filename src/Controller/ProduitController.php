<?php

namespace App\Controller;

use App\Entity\Produit;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ProduitType;
use Symfony\Component\HttpFoundation\Request;

class ProduitController extends AbstractController
{
    #[Route('/produit', name: 'produit_liste')]
    public function produit(ManagerRegistry $doctrine): Response
    {
        $p = new Produit();
        $form = $this->createForm(ProduitType::class, $p, array(
            'action' => $this->generateUrl('addProduit')
        ));

        //$form = $this->createForm(ProduitType::class, $p);
        $data['form'] = $form->createView();
        $data['produit'] =  $doctrine->getRepository(Produit::class)->findAll();

        return $this->render("produit/listeTableau.html.twig", $data);
    }

    #[Route('/produit/listes', name: 'listes')]
    public function listes(ManagerRegistry $doctrine): Response
    {
        $data['Produit'] = $doctrine->getRepository(Produit::class)->findAll();
        return $this->render("produit/tableau.html.twig", $data);
    }

    #[Route('/produit/getProduit', name: 'getProduit')]
    public function getProduit($id): Response
    {

        return $this->render("produit/listeTableau.html.twig");
    }

    #[Route('/produit/addProduit', name: 'addProduit')]
    public function addProduit(ManagerRegistry $doctrine, Request $request): Response
    {
        /*if(isset($_POST['produit'])) {
            $json = $_POST['produit'];
            var_dump(json_decode($json, true));
        } else {
            echo "Noooooooob";
        }*/
        print_r($this->getUser()->getUserIdentifier());

        $p = new Produit();
        
        $form = $this->createForm(ProduitType::class, $p);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $p = $form->getData();

            $p->setUser($this->getUser());
            $entityManager =  $doctrine->getManager();
            $entityManager->persist($p);
            $entityManager->flush();
        }

        return $this->redirectToRoute('produit_liste');
    }

    #[Route('/produit/delete/{id}', name: 'produit_delete')]
    public function delete(ManagerRegistry $doctrine, Request $request, $id): Response
    {
        $entityManager =  $doctrine->getManager();
        $Produit = $doctrine->getRepository(Produit::class)->find($id);
        if ($Produit != null) {
            $entityManager->remove($Produit);
            $entityManager->flush();
        }
        return $this->redirectToRoute('produit_liste');
    }

    #[Route('/produit/edite/{id}', name: 'produit_edite')]
    public function edite(ManagerRegistry $doctrine, Request $request, $id): Response
    {
        $entityManager =  $doctrine->getManager();
        $p = $doctrine->getRepository(Produit::class)->find($id);
        
        $form = $this->createForm(ProduitType::class, $p, array(
            'action' => $this->generateUrl('produit_update', ['id'=> $id])
        ));

        //$form = $this->createForm(ProduitType::class, $p);
        $data['form'] = $form->createView();
        $data['produit'] =  $doctrine->getRepository(Produit::class)->findAll();

        return $this->render("produit/listeTableau.html.twig", $data);
    }


    #[Route('/produit/update/{id}', name: 'produit_update')] 
    public function update(ManagerRegistry $doctrine, Request $request, $id) : Response
    {
        /*if(isset($_POST['produit'])) {
            $json = $_POST['produit'];
            var_dump(json_decode($json, true));
            return "Noooooooob";
        }*/
        
      $p = new Produit();
        
        $form = $this->createForm(ProduitType::class, $p);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $p = $form->getData();
            $p->setId($id);
            $p->setUser($this->getUser());
            $entityManager =  $doctrine->getManager();
            $product = $entityManager->getRepository(Produit::class)->find($id);
            $product->setLebelle($p->getLebelle());
            $product->setQtStock($p->getQtStock());
            $entityManager->flush();
        }
        return $this->redirectToRoute('produit_liste');
        
    }



}
