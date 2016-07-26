<?

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class PatientArrangementReferenceRepository extends EntityRepository {

	public function findByArrangement($arrangementId) {
		$query = $this->getEntityManager() ->createQuery(
			'SELECT ref, p FROM AppBundle:PatientArrangementReference ref JOIN ref.patient p
                WHERE ref.arrangement = :id'
			)->setParameter('id', $arrangementId);
		
		try {
			return $query->getResult();
		} catch (\Doctrine\ORM\NoResultException $e) { return null; } 
	}

	public function findByPatient($patientId) {
		$query = $this->getEntityManager() ->createQuery(
			'SELECT ref, a FROM AppBundle:PatientArrangementReference ref JOIN ref.arrangement a
                WHERE ref.patient = :id'
			)->setParameter('id', $patientId);
		
		try {
			return $query->getResult();
		} catch (\Doctrine\ORM\NoResultException $e) { return null; } 
	}
}