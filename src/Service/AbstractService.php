<?php
/**
 * Created by PhpStorm.
 * User: zakcay
 * Date: 28.01.2019
 * Time: 15:26
 */


namespace App\Service;

use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManager;

abstract class AbstractService
{
    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    protected $model;

    protected $em;

    /**
     * @param EntityManager $em
     * @param $entityName
     */
    protected function __construct(EntityManager $em, $entityName)
    {
        $this->em = $em;
        $this->model = $em->getRepository($entityName);
    }

    /**
     * @return array
     */
    protected function findAll()
    {
        return $this->model->findAll();
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     * @param null $limit
     * @param null $offset
     * @return array
     */
    protected function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->model->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @param Criteria $criteria
     * @return \Doctrine\Common\Collections\Collection
     */
    protected function matching(Criteria $criteria)
    {
        return $this->model->matching($criteria);
    }

    /**
     * @param $id
     * @param int $lockMode
     * @param null $lockVersion
     * @return null|object
     */
    protected function find($id, $lockMode = LockMode::NONE, $lockVersion = null)
    {
        return $this->model->find($id, $lockMode, $lockVersion);
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     * @return null|object
     */
    protected function findOneBy(array $criteria, array $orderBy = null)
    {
        return $this->model->findOneBy($criteria, $orderBy);
    }

    protected function getReferenceObject($id)
    {
        return $this->em->getReference($this->model->getClassName(), $id);
    }

    protected function save($object)
    {
        $this->em->persist($object);
        $this->em->flush();
    }

    protected function delete($object)
    {
        $this->em->remove($object);
        $this->em->flush();
    }

    protected function entityManager()
    {
        return $this->em;
    }

    abstract protected function getModel();
}