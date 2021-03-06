<?php

namespace AppBundle\Entity;

/**
 * ProgressRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProgressRepository extends \Doctrine\ORM\EntityRepository
{
	public function getAllAsArray() {
		$qb = $this->getEntityManager()->createQueryBuilder();
	    $qb
	        ->select('progress.weight', 'progress.dateTime', 'patient.id')
	        ->from('AppBundle:Progress', 'progress')
	        ->leftJoin('progress.patient', 'patient')
	        ->orderBy('progress.dateTime', 'ASC');	        		

	    try {
	    	return $qb->getQuery()->getArrayResult();			
		} catch (\Doctrine\ORM\NoResultException $e) { return null; } 
	}

	public function getForGraph($id) {
		$qb = $this->getEntityManager()->createQueryBuilder();
	    $qb
	        ->select(
	        	'progress.weight', 
	        	'progress.dateTime', 	        	
	        	'sum(progress.weight) AS sum_weight', 
	        	'count(progress.weight) AS sum_people',
	        	'sum(progress.weight) / count(progress.weight) AS mean'	        	
	        )
	        ->addSelect('(SELECT p.weight
                FROM AppBundle:Progress p
                LEFT OUTER JOIN p.patient pat
                WHERE pat.id = :id AND p.dateTime = progress.dateTime
                ) AS patient_weight'
   			)
	        ->from('AppBundle:Progress', 'progress')
	        ->setParameter('id', $id)
	        ->groupBy('progress.dateTime')
	        ->orderBy('progress.dateTime', 'ASC');	        		

	    try {
	    	return $qb->getQuery()->getArrayResult();			
		} catch (\Doctrine\ORM\NoResultException $e) { return null; } 
	}

	public function findOneByCoachingId($id)
	{
	    $query = $this->getEntityManager()
	        ->createQuery(
	            'SELECT p, c FROM AppBundle:Progress p
	            JOIN p.coaching c
	            WHERE p.id = :id'
	        )->setParameter('id', $id);

	    try {
	        return $query->getSingleResult();
	    } catch (\Doctrine\ORM\NoResultException $e) {
	        return null;
	    }
	}
	
	// SELECT 
	// 	weight,
	// 	patient_id,
	// 	date_time,
	// 	sum(weight) AS sum_weight,
	// 	count(patient_id) AS sum_people,
	// 	sum(weight)/count(patient_id) AS mean,
	// 	(SELECT
	// 		weight AS sub_weight
	// 		FROM progress as sub
	// 		WHERE patient_id = 
	// 			AND date_time = grouped.date_time) AS patient_weight

	// 	FROM progress AS grouped
		
	// 	GROUP BY date_time
	
}

