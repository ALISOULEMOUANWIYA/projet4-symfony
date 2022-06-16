<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Entity\Produit;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\SortieType;
use Symfony\Component\HttpFoundation\Request;

class SortieController extends AbstractController
{

    #[Route('/sortie', name: 'sortie_liste')]
    public function produit(ManagerRegistry $doctrine): Response
    {
        $s = new Sortie();
        $form = $this->createForm(SortieType::class, $s, array(
            'action' => $this->generateUrl('addSortieProduit')
        ));

        //$form = $this->createForm(ProduitType::class, $p);
        $data['form'] = $form->createView();
        $data['sortie'] =  $doctrine->getRepository(Sortie::class)->findAll();

        return $this->render("sortie/liste.html.twig", $data);
    }


    #[Route('/produit/addSortieProduit', name: 'addSortieProduit')]
    public function addSortieProduit(ManagerRegistry $doctrine, Request $request): Response
    {
        /*if(isset($_POST['produit'])) {
            $json = $_POST['produit'];
            var_dump(json_decode($json, true));
        } else {
            echo "Noooooooob";
        }*/

        $s = new Sortie();
        $form = $this->createForm(SortieType::class, $s);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $s = $form->getData();
            $p=  $doctrine->getRepository(Produit::class)->find($s->getProduit());
            $qSortie = $s->getQteS();
            $s->setUser($this->getUser());
            if ($p->getQtStock() < $s->getQteS()) {
                $s = new Sortie();
                $form = $this->createForm(SortieType::class, $s, array(
                    'action' => $this->generateUrl('addSortieProduit')
                ));
                $data['form'] = $form->createView();
                $data['sortie'] =  $doctrine->getRepository(Sortie::class)->findAll();
                $data['error_message'] = "Le stock disponible est inferieur à ".$qSortie;
                
                return $this->render("sortie/liste.html.twig", $data);
            } else {
                $entityManager =  $doctrine->getManager();
                $entityManager->persist($s);
                $entityManager->flush();

                $stock = $p->getQtStock() - $s->getQteS();
                $p->setQtStock($stock);
                $entityManager->flush();

                return $this->redirectToRoute('sortie_liste');
            }
        }else {
            return $this->redirectToRoute('sortie_liste');
        }

        
    }
}
