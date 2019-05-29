<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    use ContainerAwareTrait;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param $value
     * @return User[] Returns an array of User objects
     */
    public function sortByField($value)
    {
        $sortField = \ltrim($value, '-');
        $order = 'ASC';

        if (!$this->getClassMetadata(User::class)->hasField($sortField))
            $sortField = 'firstName';
        if (\substr($value, 0, 1) === '-')
            $order = 'DESC';

        return $this->createQueryBuilder('u')
            ->orderBy("u.$sortField", $order)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $value
     * @return
     */
    public function search($value)
    {
        $finder = $this->container->get('fos_elastica.finder.app_user.user');

        $results = $finder->find($value);

        return $results;
    }
}
