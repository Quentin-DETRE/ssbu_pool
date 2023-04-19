<?php

namespace App\Repository;

use App\Entity\CharacterChoice;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CharacterChoice>
 *
 * @method CharacterChoice|null find($id, $lockMode = null, $lockVersion = null)
 * @method CharacterChoice|null findOneBy(array $criteria, array $orderBy = null)
 * @method CharacterChoice[]    findAll()
 * @method CharacterChoice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CharacterChoiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CharacterChoice::class);
    }

    public function save(CharacterChoice $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CharacterChoice $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getAllCharacterChoices():array
    {
        return $this->createQueryBuilder('cc')->addSelect('s')
            ->leftJoin('cc.serie', 's')
            ->getQuery()->getResult();
    }

    public function findOneCharacterChoiceAndCharacterCp(User $user, string $in): array
    {
        return $this->createQueryBuilder('cc')->addSelect('cp')
            ->leftJoin('cc.characterCps', 'cp')
            ->where('cp.user = :user')
            ->andWhere('cc.iterationNumber = :in')
            ->setParameters(['user'=> $user, 'in'=>$in])
            ->getQuery()->getResult();

    }

    public function findOneCharacterChoice(string $in): array
    {
        return $this->createQueryBuilder('cc')->addSelect('cp')
            ->leftJoin('cc.characterCps', 'cp')
            ->where('cc.iterationNumber = :in')
            ->setParameter('in', $in)
            ->getQuery()->getResult();

    }

    //    /**
    //     * @return CharacterChoice[] Returns an array of CharacterChoice objects
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

    //    public function findOneBySomeField($value): ?CharacterChoice
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
