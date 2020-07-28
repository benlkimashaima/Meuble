<?php

namespace ShaimaBundle\Controller;

use ShaimaBundle\Entity\livraison;
use ShaimaBundle\Entity\Meuble;
use ShaimaBundle\Entity\stock;
use ShaimaBundle\Form\AchatType;
use ShaimaBundle\Form\livraisonType;
use ShaimaBundle\Form\MeubleType;
use ShaimaBundle\Form\quantiteType;
use ShaimaBundle\Form\RechercheType;
use ShaimaBundle\Form\stockType;
use ShaimaBundle\Repository\stockRepository;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\Response;




class DefaultController extends Controller
{
    ###BACK
    ##table stock ##

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

##end_table_stock##

##table meuble##
    public function afficheMeubleAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $meuble= $em->getRepository("ShaimaBundle:Meuble")->findAll();
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         *
         */
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $meuble,
            $request->query->getInt('page', 1),

            $request->query->getInt('limit', 4)/*nbre d'éléments par page*/
        );
        return $this->render('ShaimaBundle:template:read_meuble.html.twig',array('meuble'=>$pagination));
    }
    public function AjouterMeubleAction(Request $request)
    { $meuble = new \ShaimaBundle\Entity\Meuble();
        $form = $this->createForm(\ShaimaBundle\Form\MeubleType::class,$meuble);
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()){
            /**
             * @var UploadedFile $file
             *
             */
            $file=$meuble->getImage();
            $fileName =md5(uniqid()).'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('images_directory'), $fileName);


            $meuble->setImage($fileName);

            $entityManager=$this->getDoctrine()->getManager();
            $entityManager->persist($meuble);
            $entityManager->flush();

            $this->addFlash('meuble',
                ' Meuble Added successufly!'

            );
            return $this->redirectToRoute('AfficherMeuble');
        }
        return $this->render('ShaimaBundle:template:add_meuble.html.twig', array("form"=>$form->createView()));}
    public function deleteMeubleAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $meuble=$em->getRepository(Meuble::class)->find($id);
        $em->remove($meuble);
        $em->flush();
        $this->addFlash('meubles',
            ' Meuble Deleted successufly!'

        );
        return $this->redirectToRoute('AfficherMeuble');
    }
    function modifierMeubleAction(Request $request,$id){
        $meuble =new Meuble();
        $em=$this->getDoctrine()->getManager();
        $meuble=$em->getRepository("ShaimaBundle:Meuble")->find($id);

        $Form=$this->createForm(MeubleType::class,$meuble) ;
        $Form->handleRequest($request);



        if($Form->isSubmitted() && $Form->isValid()){

            /**$achat->setLibelle($Meuble->getLibelle());**/


            $entityManager=$this->getDoctrine()->getManager();
            $entityManager->persist($meuble);
            $entityManager->flush();

            $em->flush();

            return $this->redirectToRoute('AfficherMeuble');

        }
        return $this->render('ShaimaBundle:template:modifier_Meuble.html.twig',array('form'=>$Form->createView()));
    }
    public  function pdfAction (  )
    {

        $pdfOptions = new Options();

        $pdfOptions->set('defaultFront', 'Arial');
        $em = $this->getDoctrine()->getManager();
        $meuble = $em->getRepository("ShaimaBundle:Meuble")->findAll();

        $dompdf = new Dompdf ($pdfOptions);
        $html = $this->renderView('ShaimaBundle:template:PDF_meuble.html.twig', array( 'meuble' => $meuble)
        );
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("mypdf.pdf", ["Attachment" => false]);


    }

##end_table_meuble##

##table_livraison
    public function afficheLivraisonAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $Dons= $em->getRepository("ShaimaBundle:Achat")->findAll();
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         *
         */
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $Dons,
            $request->query->getInt('page', 1),

            $request->query->getInt('limit', 4)/*nbre d'éléments par page*/
        );
        return $this->render('ShaimaBundle:template:read_livraison.html.twig',array('Dons'=>$pagination));
    }
##end_table_livraison
##table_mode

    public function afficheMLivraisonAction()
    {
        $em = $this->getDoctrine()->getManager();
        $mode= $em->getRepository("ShaimaBundle:livraison")->findAll();
        return $this->render('ShaimaBundle:template:read_Mlivraison.html.twig',array('mode'=>$mode));
    }
    public function addCommandeAction(Request $request){
        $mode = new \ShaimaBundle\Entity\livraison();
        $form = $this->createForm(\ShaimaBundle\Form\livraisonType::class,$mode);
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($mode);
            $em->flush();

            $this->addFlash('commande',
                ' MODE  Commande  Added successufly!'

            );
            return $this->redirectToRoute('afficheMLivraison');
        }
        return $this->render('ShaimaBundle:template:add_Mlivraison.html.twig', array("form"=>$form->createView()));
    }
    public function deleteMlAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $mode=$em->getRepository(livraison::class)->find($id);
        $em->remove($mode);
        $em->flush();
        $this->addFlash('commandes',
            '  MODE  Commande deleted successufly!'

        );
        return $this->redirectToRoute('afficheMLivraison');
    }
    public function  modifierMLAction(Request $request,$id){
        $mode =new \ShaimaBundle\Entity\livraison();
        $em=$this->getDoctrine()->getManager();
        $mode=$em->getRepository("ShaimaBundle:livraison")->find($id);

        $Form=$this->createForm(livraisonType::class,$mode) ;
        $Form->handleRequest($request);
        if($Form->isSubmitted() && $Form->isValid()){

            $em->flush();

            return $this->redirectToRoute('afficheMLivraison');

        }
        return $this->render('ShaimaBundle:template:modifier_ML.html.twig',array('form'=>$Form->createView()));
    }
    public function afficheNotifAction()
    {
        $em = $this->getDoctrine()->getManager();
        return $this->render('ShaimaBundle:template:Notif.html.twig');
    }
                   ################################


    ##front###

    public function afficheMeubleFAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $meuble= $em->getRepository("ShaimaBundle:Meuble")->findAll();
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         *
         */
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $meuble,
            $request->query->getInt('page', 1),

            $request->query->getInt('limit', 4)/*nbre d'éléments par page*/
        );
        return $this->render('ShaimaBundle:templateF:read_meuble.html.twig',array('Dons'=>$pagination));
    }
    public function AchatAction()
    {
        $em = $this->getDoctrine()->getManager();
        $achat= $em->getRepository("ShaimaBundle:Meuble")->findAll();
        return $this->render('ShaimaBundle:templateF:achat.html.twig',array('achat'=>$achat));
    }
    public function addAchatAction(Request $request,$id){
        $achat = new \ShaimaBundle\Entity\Achat();
        $em = $this->getDoctrine()->getManager();
        $Meuble= $em->getRepository("ShaimaBundle:Meuble")->find($id);
        $achat->setLibelle($Meuble->getLibelle());

        $form = $this->createForm(\ShaimaBundle\Form\AchatType::class,$achat);
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()){
            $em->getRepository("ShaimaBundle:Meuble")->updateCapacityMinus($id);

            $em = $this->getDoctrine()->getManager();
            $em->persist($achat);
            $em->flush();
            $this->addFlash('infLivraison',
                ' New livraison '

            );
            return $this->redirectToRoute('AfficherMeubleF');
        }
        return $this->render('ShaimaBundle:templateF:achat.html.twig', array("form"=>$form->createView()));
    }
    public function MapAction()
    {
        return $this->render('ShaimaBundle:templateF:map.html.twig');
    }
    public function homeAction()
    {
        return $this->render('ShaimaBundle:templateF:home.html.twig');
    }



   ###end_front####



    ###############
    public function indexAction()
    {
        return $this->render('ShaimaBundle:Default:read_type_Meuble.html.twig');
    }
    public function registerAction()
    {
        return $this->render('@FOSUser/Registration/register_content.html.twig');
    }
    public function pageAction()
    {
        return $this->render('ShaimaBundle:Default:page.html.twig');
    }
    public function layoutAction()
    {
        return $this->render('ShaimaBundle::layout.html.twig');
    }
    public function layoutFAction()
    {
        return $this->render('ShaimaBundle::layoutF.html.twig');
    }
    public function indexxAction()
    {
        return $this->render('ShaimaBundle:template:read_type_Meuble.html.twig');
    }
    public function wighAction()
    {
        return $this->render('ShaimaBundle:template:read_meuble.html.twig');
    }


}
