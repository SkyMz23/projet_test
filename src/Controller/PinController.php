<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Repository\PinRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PinController extends AbstractController
{
  
   


    /**
     * @Route("/pin", name="app_pin", methods={"GET"})
     */
    public function index(EntityManagerInterface $em): Response
    {  
      

       $repo = $em->getRepository(Pin::class); 
       $pin = $repo->findAll();
        return $this->render('index.html.twig', ['pin' => $pin]);
    } 

     /**
     * @Route("/pin/create", methods={"GET","POST"})
     */
    public function create(Request $request, EntityManagerInterface $em)
    {  
        $form = $this->createFormBuilder()
          ->add('title', TextType::class)
          ->add('description', TextareaType::class)
          ->add('submit', SubmitType::class, ['label' => 'create pin'])
          ->getForm()
          ;
        $form->handleRequest($request); 
        if ($form->isSubmitted() && $form->isValid()){
          $data = $form->getData();
           $pin = new Pin;
           $pin->setTitle($data['title']);
           $pin->setDescription($data['description']);
           $em->persist($pin);
           $em->flush(); 

           return $this->redirectToRoute('app_pin');
        }
        
   
       
        return $this->render('create.html.twig', ['monFormulaire' => $form->createView()]);   
    } 

    /**
     * @Route("/pin/{id<[0-9]+>}")
     */
    public function show(PinRepository $repo, int $id):Response{
      $pin = $repo->find($id);
 
      return $this->render('show.html.twig', compact('pin'));
    }


}
