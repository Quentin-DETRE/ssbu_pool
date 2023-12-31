<?php

namespace App\Repository;

use App\Entity\CharacterChoice;
use App\Entity\CharacterCp;
use App\Entity\Note;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Utils;

/**
 * @extends ServiceEntityRepository<Note>
 *
 * @method Note|null find($id, $lockMode = null, $lockVersion = null)
 * @method Note|null findOneBy(array $criteria, array $orderBy = null)
 * @method Note[]    findAll()
 * @method Note[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Note::class);
    }

    public function save(Note $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Note $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByInterationNumber(string $iterationNumber): array {
        return $this->createQueryBuilder('n')
            ->select('n', 'cp', 'cc')
            ->leftJoin('n.characterCp', 'cp')
            ->leftJoin('cp.characterChoice', 'cc')
            ->where('cc.iterationNumber = :iterationNumber')
            ->setParameter('iterationNumber', $iterationNumber)
            ->getQuery()->getResult();
    }

    public function findAllNotesByCharacterCp(CharacterCp $characterCp): array {
        return $this->createQueryBuilder('n')
            ->select('n', 'cp', 'cc')
            ->leftJoin('n.characterCp', 'cp')
            ->leftJoin('cp.characterChoice', 'cc')
            ->where('cc = :characterChoice')
            ->setParameter('characterChoice', $characterCp->getCharacterChoice())
            ->getQuery()->getResult();
    }


    //    /**
    //     * @return Note[] Returns an array of Note objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('n')
    //            ->andWhere('n.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('n.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Note
    //    {
    //        return $this->createQueryBuilder('n')
    //            ->andWhere('n.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
