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
    public function __construct(EntityManager $em, $entityName)
    {
        $this->em = $em;
        $this->model = $em->getRepository($entityName);
    }

    /**
     * @return array
     */
    public function findAll()
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
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->model->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @param Criteria $criteria
     * @return \Doctrine\Common\Collections\Collection
     */
    public function matching(Criteria $criteria)
    {
        return $this->model->matching($criteria);
    }

    /**
     * @param $id
     * @param int $lockMode
     * @param null $lockVersion
     * @return null|object
     */
    public function find($id, $lockMode = LockMode::NONE, $lockVersion = null)
    {
        return $this->model->find($id, $lockMode, $lockVersion);
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     * @return null|object
     */
    public function findOneBy(array $criteria, array $orderBy = null)
    {
        return $this->model->findOneBy($criteria, $orderBy);
    }

    public function getReferenceObject($id)
    {
        return $this->em->getReference($this->model->getClassName(), $id);
    }

    public function save($object)
    {
        $this->em->persist($object);
        $this->em->flush();
    }

    public function delete($object)
    {
        $this->em->remove($object);
        $this->em->flush();
    }

    public function entityManager()
    {
        return $this->em;
    }

    abstract protected function getModel();
}