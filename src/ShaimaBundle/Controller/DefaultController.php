<?php

namespace ShaimaBundle\Controller;

use ShaimaBundle\Entity\stock;
use ShaimaBundle\Form\RechercheType;
use ShaimaBundle\Form\stockType;
use ShaimaBundle\Repository\stockRepository;
use StockBundle\Entity\Don;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ShaimaBundle:Default:read_type_Meuble.html.twig');
    }
    public function pageAction()
    {
        return $this->render('ShaimaBundle:Default:page.html.twig');
    }
    public function layoutAction()
    {
        return $this->render('ShaimaBundle::layout.html.twig');
    }
    public function indexxAction()
    {
        return $this->render('ShaimaBundle:template:read_type_Meuble.html.twig');
    }
    public function wighAction()
    {
        return $this->render('ShaimaBundle:template:read_meuble.html.twig');
    }
    public function afficheStockAction()
    {
        $em = $this->getDoctrine()->getManager();
        $stocks= $em->getRepository("ShaimaBundle:stock")->findAll();
        return $this->render('ShaimaBundle:template:read_type_Meuble.html.twig',array('stocks'=>$stocks));
    }
    public function deleteStockAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $stocks=$em->getRepository(stock::class)->find($id);
        $em->remove($stocks);
        $em->flush();
        $this->addFlash('infos',
            ' Stock deleted successufly!'

        );
        return $this->redirectToRoute('AfficherStock');
    }
    public function ajouterStockAction(Request $request){
        $Stock = new \ShaimaBundle\Entity\stock();
        $form = $this->createForm(\ShaimaBundle\Form\stockType::class,$Stock);
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($Stock);
            $em->flush();

            $this->addFlash('info',
                ' Stock Added successufly!'

            );
            return $this->redirectToRoute('AfficherStock');
        }
        return $this->render('ShaimaBundle:template:add_type_Meuble.html.twig', array("form"=>$form->createView()));
    }
    function  modifierStockAction(Request $request,$id){
        $stock =new \ShaimaBundle\Entity\stock();
        $em=$this->getDoctrine()->getManager();
        $stock=$em->getRepository("ShaimaBundle:stock")->find($id);

        $Form=$this->createForm(stockType::class,$stock) ;
        $Form->handleRequest($request);
        if($Form->isSubmitted() && $Form->isValid()){

            $em->flush();

            return $this->redirectToRoute('AfficherStock');

        }
        return $this->render('ShaimaBundle:template:modifier_type_Meuble.html.twig',array('form'=>$Form->createView()));
    }
    public function rechercheLibelleAction(Request $request)
    {
        $Dons = new stock();
        $form = $this->createForm(\ShaimaBundle\Form\RechercheType::class, $Dons);
        $form = $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $Dons = $this->getDoctrine()
                ->getRepository(stock::class)
                ->findBy(array('type' => $Dons->getType()));

        } else {
            $Dons = $this->getDoctrine()
                ->getRepository(stock::class)
                ->findAll();
        }
        return $this->render('ShaimaBundle:template:read_type_Meuble.html.twig\'',
            array('form' => $form->createView(), 'Dons' => $Dons));
    }
    public function afficheMeubleAction()
    {
        $em = $this->getDoctrine()->getManager();
        $Dons= $em->getRepository("ShaimaBundle:Meuble")->findAll();
        return $this->render('ShaimaBundle:template:read_meuble.html.twig',array('Dons'=>$Dons));
    }
}
