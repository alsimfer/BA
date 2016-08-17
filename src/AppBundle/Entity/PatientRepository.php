<?php

namespace AppBundle\Entity;

/**
 * PatientRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PatientRepository extends \Doctrine\ORM\EntityRepository
{
	public function findRelevantToUser($user) {
		// If it is a Doctor and a Hospital is defined
		if ($user->getUserGroup()->getId() === 4 && is_null($user->getHospital()) === FALSE) {
			$query = $this->getEntityManager()->createQuery(
				'SELECT p FROM AppBundle:Patient p
	                WHERE p.hospital = :id
	                ORDER BY p.id DESC'
				)->setParameter('id', $user->getHospital()->getId());
		// If it is a Coach
		} else if ($user->getUserGroup()->getId() === 5) {
			$query = $this->getEntityManager()->createQuery(
				'SELECT p FROM AppBundle:Patient p
					WHERE p.sysUser = :id
					ORDER BY p.id DESC'	
				)->setParameter('id', $user->getId());
		} else {
			$query = $this->getEntityManager()->createQuery(
				'SELECT p FROM AppBundle:Patient p
				ORDER BY p.id DESC');
		}
		
		try {
			return $query->getResult();
		} catch (\Doctrine\ORM\NoResultException $e) { return null; } 
	}

	public function findIdsRelevantToUser($user) {
		// If it is a Doctor and a Hospital is defined
		if ($user->getUserGroup()->getId() === 4 && is_null($user->getHospital()) === FALSE) {
			$query = $this->getEntityManager()->createQuery(
				'SELECT p.id FROM AppBundle:Patient p
	                WHERE p.hospital = :id
	                ORDER BY p.id DESC'
				)->setParameter('id', $user->getHospital()->getId());
		// If it is a Coach
		} else if ($user->getUserGroup()->getId() === 5) {
			$query = $this->getEntityManager()->createQuery(
				'SELECT p.id FROM AppBundle:Patient p
					WHERE p.sysUser = :id
					ORDER BY p.id DESC'	
				)->setParameter('id', $user->getId());
		} else {
			$query = $this->getEntityManager()->createQuery(
				'SELECT p.id FROM AppBundle:Patient p
				ORDER BY p.id DESC');
		}
		
		try {
			return $query->getResult();
		} catch (\Doctrine\ORM\NoResultException $e) { return null; } 
	}

}
