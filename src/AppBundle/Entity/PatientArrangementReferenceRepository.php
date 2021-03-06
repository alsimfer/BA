<?php

namespace AppBundle\Entity;

/**
 * PatientArrangementReferenceRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PatientArrangementReferenceRepository extends \Doctrine\ORM\EntityRepository
{
	public function findRelevantToUser($user) {
		$qb = $this->getEntityManager()->createQueryBuilder();
		// If it is a Doctor and a Hospital is defined
		if ($user->getUserGroup()->getId() === 4 && is_null($user->getHospital()) === FALSE) {			
		    $qb
		        ->select('ref', 'p', 'm')
		        ->from('AppBundle:PatientArrangementReference', 'ref')
		        ->leftJoin('ref.patient', 'p')
		        ->leftJoin('p.medCheckups', 'm')
		        ->where('m.sysUser = :id')
		        ->setParameter('id', $user->getId())
		        ->orderBy('ref.id', 'DESC');
		// If it is a Coach
		} else if ($user->getUserGroup()->getId() === 5) {			
			$qb
		    	->select('ref', 'p')
		        ->from('AppBundle:PatientArrangementReference', 'ref')
		        ->leftJoin('ref.patient', 'p')
		        ->where('p.sysUser = :id')
		        ->setParameter('id', $user->getId())
		        ->orderBy('ref.id', 'DESC');
		} else {
			$qb
		        ->select('ref')
		        ->from('AppBundle:PatientArrangementReference', 'ref')
		        ->orderBy('ref.id', 'DESC');
		}		

	    try {
	    	return $qb->getQuery()->getResult();			
		} catch (\Doctrine\ORM\NoResultException $e) { return null; } 
	}

	public function findIdsRelevantToUser($user) {
		$qb = $this->getEntityManager()->createQueryBuilder();
		// If it is a Doctor and a Hospital is defined
		if ($user->getUserGroup()->getId() === 4 && is_null($user->getHospital()) === FALSE) {			
		    $qb
		        ->select('ref.id')
		        ->from('AppBundle:PatientArrangementReference', 'ref')
		        ->leftJoin('ref.patient', 'p')
		        ->leftJoin('p.medCheckups', 'm')
		        ->where('m.sysUser = :id')
		        ->setParameter('id', $user->getId())
		        ->orderBy('ref.id', 'DESC');
		// If it is a Coach
		} else if ($user->getUserGroup()->getId() === 5) {			
			$qb
		    	->select('ref.id')
		        ->from('AppBundle:PatientArrangementReference', 'ref')
		        ->leftJoin('ref.patient', 'p')
		        ->where('p.sysUser = :id')
		        ->setParameter('id', $user->getId())
		        ->orderBy('ref.id', 'DESC');
		} else {
			$qb
		        ->select('ref.id')
		        ->from('AppBundle:PatientArrangementReference', 'ref')
		        ->orderBy('ref.id', 'DESC');
		}		

	    try {
	    	return $qb->getQuery()->getResult();			
		} catch (\Doctrine\ORM\NoResultException $e) { return null; } 
	}

	// Find all patients who were registered for the arrangement with $id.
	public function findPatientsByArrangementId($id) {
		$qb = $this->getEntityManager()->createQueryBuilder();
		
	    $qb
	        ->select('pat')
	        ->from('AppBundle:PatientArrangementReference', 'ref')
	        ->leftJoin('ref.arrangement', 'arr')
	        ->leftJoin('arr.patients', 'pat')
	        ->where('arr.id = :id')
	        ->setParameter('id', $id);
		
	    try {
	    	return $qb->getQuery()->getResult();			
		} catch (\Doctrine\ORM\NoResultException $e) { return null; } 
	}
}
