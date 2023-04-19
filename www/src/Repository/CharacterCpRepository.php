<?php

namespace App\Repository;

use App\Entity\CharacterCp;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CharacterCp>
 *
 * @method CharacterCp|null find($id, $lockMode = null, $lockVersion = null)
 * @method CharacterCp|null findOneBy(array $criteria, array $orderBy = null)
 * @method CharacterCp[]    findAll()
 * @method CharacterCp[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CharacterCpRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CharacterCp::class);
    }

    public function save(CharacterCp $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CharacterCp $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param User $user
     * @return array
     */
    public function findAllCpByUser(User $user):array
    {
        return $this->createQueryBuilder('cp')->addSelect('cc')
            ->leftJoin('cp.characterChoice', 'cc')
            ->leftJoin('cp.user', 'u' )
            ->where("u = :user")
            ->setParameter('user', $user)
            ->getQuery()->getResult();
    }

    public function findCharacterDetail(string $iterationNumber, User $user): array
    {
        return $this->createQueryBuilder('cp')
            ->addSelect('cc', 's')
            ->leftJoin('cp.characterChoice', 'cc')
            ->leftJoin('cp.user', 'u')
            ->leftJoin('cc.serie', 's')
            ->where('cc.iterationNumber = :in')
            ->andWhere('u = :user')
            ->setParameters(['in'=> $iterationNumber, 'user'=> $user ])
            ->getQuery()->getResult();
    }

    public function findOneCharacterCp(Int $id):array
    {
        return $this->createQueryBuilder('cp')->addSelect('n')->addSelect('cc')
            ->leftJoin('cp.notes', 'n')
            ->leftJoin('cp.characterChoice', "cc")
            ->where('cp.id = :id')
            ->setParameter('id', $id)
            ->getQuery()->getResult();
    }
    //    /**
    //     * @return CharacterCp[] Returns an array of CharacterCp objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?CharacterCp
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
