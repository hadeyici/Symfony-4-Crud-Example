<?php
namespace App\Controller ;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use App\Service\ArticleService as ServiceArticle;


class ArticleController extends AbstractController {

    /**
     * @Route("/article", name="article_list")
     * @Method ({"GET"})
     */
    public function article(){

        $article  = new ServiceArticle($this->getDoctrine()->getManager(),Article::class);
        $articles = $article->getAllArticles();
        return $this->render('articles/index.html.twig', array
        ('articles' => $articles));
    }

    /**
     * @Route ("/article/new", name="new_article")
     * Method ({"GET", "POST"})
     */
    public function new(Request $request){
        $article = new Article();

        $form = $this->createFormBuilder($article)
            ->add('title', TextType::class, array('attr' =>array('class' => 'form-control')))
            ->add('body', TextareaType::class, array('required' =>false,
                'attr' =>array('class' =>'form-control')))
            ->add('save', SubmitType::class, array(
                'label' =>'Create',
                'attr' =>array('class'=>'btn btn-primary mt-3')
            ))
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $article = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('article_list');
        }

        return $this->render('articles/new.html.twig',array(
            'form'=>$form->createView()
        ));
    }

    /**
     * @Route ("/article/{id}", name= "article_show")
     * @Method ({"GET"})
     */

    public function show($id){
        $article  = new ServiceArticle($this->getDoctrine()->getManager(),Article::class);
        $article = $article->getArticle($id);
        return $this->render('articles/show.html.twig', array('article' =>$article));

    }

    /**
     * @Route ("/article/update/{id}", name="update_article")
     * Method ({"GET", "POST"})
     */

    public function update(Request $request, $id){

        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        $form = $this->createFormBuilder($article)
            ->add('title', TextType::class, array('attr' =>array('class' => 'form-control')))
            ->add('body', TextareaType::class, array('required' =>false,
                'attr' =>array('class' =>'form-control')))
            ->add('save', SubmitType::class, array(
                'label' =>'Update',
                'attr' =>array('class'=>'btn btn-primary mt-3')
            ))
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $article  = new ServiceArticle($this->getDoctrine()->getManager(),Article::class);
            $article->addArticle();

            return $this->redirectToRoute('article_list');
        }

        return $this->render('articles/update.html.twig',array(
            'form'=>$form->createView()
        ));
    }

    /**
     * @Route ("/article/delete/{id}")
     * @Method ({"DELETE"})
     */

    public function delete(Request $request, $id){

        $article  = new ServiceArticle($this->getDoctrine()->getManager(),Article::class);
        $article->deleteArticle($id);

        return $this->redirectToRoute('article_list');
    }
}

