<?php

namespace App\Repository;

use App\Entity\Album;
use App\Model\FiltreAlbum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @extends ServiceEntityRepository<Album>
 *
 * @method Album|null find($id, $lockMode = null, $lockVersion = null)
 * @method Album|null findOneBy(array $criteria, array $orderBy = null)
 * @method Album[]    findAll()
 * @method Album[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlbumRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Album::class);
    }

    public function save(Album $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Album $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Query
     */
    public function listeAlbumsComplete(FiltreAlbum $filtre=null): Query
    {
        $query= $this->createQueryBuilder('a')
            ->select('a','s','art','m')
            ->leftJoin('a.styles','s')
            ->leftJoin('a.artiste','art')
            ->leftJoin('a.morceaux','m')
            ->orderBy('a.nom', 'ASC');
            if(!empty($filtre->nom)){
                $query->andWhere('a.nom like :nomCherche')
                        ->setParameter('nomCherche',"%{$filtre->nom}%");
            }
        if(!empty($filtre->artiste)){
            $query->andWhere('a.artiste = :artisteCherche')
                ->setParameter('artisteCherche',$filtre->artiste);
        }
        return $query->getQuery();

    }


//    public function findOneBySomeField($value): ?Album
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
