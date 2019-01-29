<?php 

namespace App\Service;

use Doctrine\ORM\EntityManager;


class ArticleService extends AbstractService
{

    public function __construct(EntityManager $em, $entityName)
    {
        $this->em = $em;
        $this->model = $em->getRepository($entityName);
    }

    public function getModel()
    {
        return $this->model;
    }

    public function getArticle($article_id)
    {
        return $this->find($article_id);
    }

    public function getAllArticles()
    {
        return $this->findAll();
    }

    public function addArticle()
    {
        return $this->save();
    }

    public function deleteArticle($id)
    {   
        return $this->delete($this->find($id));
    }



}