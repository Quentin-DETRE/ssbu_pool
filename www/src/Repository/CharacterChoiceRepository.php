<?php

namespace App\Repository;

use App\Entity\CharacterChoice;
use App\Entity\Note;
use App\Entity\User;
use App\Model\Search\CharacterChoiceSearch;
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

    public function getCharacterChoicesByParams(CharacterChoiceSearch $characterChoiceSearch): array
    {
        $qb = $this->createQueryBuilder('cc')
            ->leftJoin('cc.serie', 's')
            ->addSelect('s');

        if ($characterChoiceSearch->getName())
        {
            $qb->andWhere('cc.name LIKE :name')
                ->setParameter('name', '%'.$characterChoiceSearch->getName().'%');
        }
        if ($characterChoiceSearch->getSerie())
        {
            $qb->andWhere('cc.serie = :serie')
                ->setParameter('serie', $characterChoiceSearch->getSerie());
        }
        return $qb->getQuery()->getResult();
    }


    public function findCharacterByIdNote(int $idNote): array
    {
        return $this->createQueryBuilder('cc')
            ->leftJoin('cc.characterCps', 'cp')
            ->leftJoin('cp.notes', 'n')
            ->where('n.id = :idNote')
            ->setParameter('idNote', $idNote)
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
