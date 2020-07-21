<?php

namespace ShaimaBundle\Controller;

use ShaimaBundle\Entity\Meuble;
use ShaimaBundle\Entity\stock;
use ShaimaBundle\Form\MeubleType;
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
        return $this->render('ShaimaBundle:template:read_type_Meuble.html.twig',
            array('form' => $form->createView(), 'Dons' => $Dons));
    }
    public function afficheMeubleAction()
    {
        $em = $this->getDoctrine()->getManager();
        $Dons= $em->getRepository("ShaimaBundle:Meuble")->findAll();
        return $this->render('ShaimaBundle:template:read_meuble.html.twig',array('Dons'=>$Dons));
    }
    public function AjouterMeubleAction(Request $request)
    { $Stock = new \ShaimaBundle\Entity\Meuble();
        $form = $this->createForm(\ShaimaBundle\Form\MeubleType::class,$Stock);
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()){
            /**
             * @var UploadedFile $file
             *
             */
            $file=$Stock->getImage();
            $fileName =md5(uniqid()).'.'.$file->guessExtension();
           $file->move(
                $this->getParameter('images_directory'), $fileName);


            $Stock->setImage($fileName);

            $entityManager=$this->getDoctrine()->getManager();
            $entityManager->persist($Stock);
            $entityManager->flush();

            $this->addFlash('info',
                ' Stock Added successufly!'

            );
            return $this->redirectToRoute('AfficherMeuble');
        }
        return $this->render('ShaimaBundle:template:add_meuble.html.twig', array("form"=>$form->createView()));}
























        public function deleteMeubleAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $Dons=$em->getRepository(Meuble::class)->find($id);
        $em->remove($Dons);
        $em->flush();
        $this->addFlash('infos',
            ' Meuble Deleted successufly!'

        );
        return $this->redirectToRoute('AfficherMeuble');
    }
    function modifierMeubleAction(Request $request,$id){
        $Don =new Meuble();
        $em=$this->getDoctrine()->getManager();
        $Don=$em->getRepository("ShaimaBundle:Meuble")->find($id);

        $Form=$this->createForm(MeubleType::class,$Don) ;
        $Form->handleRequest($request);
        if($Form->isSubmitted() && $Form->isValid()){
            /**
             * @var UploadedFile $file
             *
             */
            $file=$Don->getImage();
            $fileName =md5(uniqid()).'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('images_directory'), $fileName);


            $Don->setImage($fileName);

            $entityManager=$this->getDoctrine()->getManager();
            $entityManager->persist($Don);
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
        $Dons = $em->getRepository("ShaimaBundle:Meuble")->findAll();

        $dompdf = new Dompdf ($pdfOptions);
        $html = $this->renderView('ShaimaBundle:template:PDF_meuble.html.twig', array( 'Dons' => $Dons)
        );
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("mypdf.pdf", ["Attachment" => false]);


    }
}
