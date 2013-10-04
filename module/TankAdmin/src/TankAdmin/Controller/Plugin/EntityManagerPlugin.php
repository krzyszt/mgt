<?php
namespace TankAdmin\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin,
    Doctrine\ORM\EntityManager;

class EntityManagerPlugin extends AbstractPlugin {
    
    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $em;
    
    /**
     * Set entityManager.
     *
     * @param EntityManager $em
     */
    public function setEntityManager(EntityManager $em) {
//        $this->em = $em;
    }

    /**
     * Get entityManager.
     *
     * @return EntityManager
     */
    public function getEntityManager() {
       
        if (null === $this->em) {
            $this->em = $this->getController()->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        }
        return $this->em;
    }
    
}
