<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\DBAL\Types\DateDiff;

use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->save($user, true);
    }
    //en query builder nbr user par genre
    public function countUsersByGender()
    {
        $qb = $this->createQueryBuilder('u')
            ->select('u.genre, COUNT(u.id) as count')
            ->groupBy('u.genre');
    
        return $qb->getQuery()->getResult();
    }

    public function getUsersCountByAgeGroup()
    {
        $users = $this->findAll(); // récupérer tous les utilisateurs
        $ageGroups = [
            '0-17' => 0,
            '18-24' => 0,
            '25-34' => 0,
            '35-44' => 0,
            '45-54' => 0,
            '55-64' => 0,
            '65+' => 0,
        ];
    
        foreach ($users as $user) {
            $dateOfBirth = $user->getDateDeNaissance();
            $age = $dateOfBirth->diff(new \DateTime())->y;
    
            if ($age <= 17) {
                $ageGroup = '0-17';
            } elseif ($age >= 18 && $age <= 24) {
                $ageGroup = '18-24';
            } elseif ($age >= 25 && $age <= 34) {
                $ageGroup = '25-34';
            } elseif ($age >= 35 && $age <= 44) {
                $ageGroup = '35-44';
            } elseif ($age >= 45 && $age <= 54) {
                $ageGroup = '45-54';
            } elseif ($age >= 55 && $age <= 64) {
                $ageGroup = '55-64';
            } else {
                $ageGroup = '65+';
            }
    
            $ageGroups[$ageGroup]++;
        }
    
        $result = [];
        foreach ($ageGroups as $ageGroup => $count) {
            $result[] = [
                'age_group' => $ageGroup,
                'user_count' => $count,
            ];
        }
    
        return $result;
    }
    


//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}