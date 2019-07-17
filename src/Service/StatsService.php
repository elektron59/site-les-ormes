<?php

namespace App\Service;

use Doctrine\Common\Persistence\ObjectManager;

class StatsService {
    private $manager;

    public function __construct(ObjectManager $manager) {
        $this->manager = $manager;
    }

    public function getStats() {
        $users =    $this->getUsersCount();
        $ads =      $this->getAdsCount();
        $bookings = $this->getBookingsCount();
        $comments = $this->getCommentsCount();

        return compact('users', 'ads', 'bookings', 'comments');

    }

    public function getUsersCount() {
        return $this->manager->createQuery('SELECT COUNT(u) FROM App\Entity\User u')->getSingleScalarResult();
    }

    public function getAdsCount() {
        return $this->manager->createQuery('SELECT COUNT(a) FROM App\Entity\MobilHome a')->getSingleScalarResult();
    }

    public function getBookingsCount() {
        return $this->manager->createQuery('SELECT COUNT(b) FROM App\Entity\Reservation b')->getSingleScalarResult();
    }

    public function getCommentsCount() {
        return $this->manager->createQuery('SELECT COUNT(c) FROM App\Entity\Comment c')->getSingleScalarResult();
    }

    public function getAdsStats($direction){
        return $this->manager->createQuery(
        'SELECT AVG(c.rating) as note, a.nomMh, a.id, u.prenom, u.nom, u.avatar 
        FROM App\Entity\Comment c
        JOIN c.ad a
        JOIN a.auteur u
        GROUP BY a
        ORDER BY note ' . $direction
        )
        ->setMaxResults(5)
        ->getResult();

    }

}