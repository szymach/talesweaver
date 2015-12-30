<?php

namespace AppBundle\Behat;

use AppBundle\Entity\User;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Mink\Mink;
use Behat\MinkExtension\Context\MinkAwareContext;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use SensioLabs\Behat\PageObjectExtension\Context\PageObjectContext;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

abstract class DefaultContext extends PageObjectContext implements
    SnippetAcceptingContext,
    KernelAwareContext,
    MinkAwareContext
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @var Mink
     */
    private $mink;

    /**
     * @var array
     */
    private $minkParameters = [];

    /**
     * @param Mink $mink Mink session manager
     */
    public function setMink(Mink $mink)
    {
        $this->mink = $mink;
    }

    /**
     * @param array $parameters
     */
    public function setMinkParameters(array $parameters)
    {
        $this->minkParameters = $parameters;
    }

    /**
     * @return array
     */
    protected function getMinkParameters()
    {
        return $this->minkParameters;
    }

    /**
     * {@inheritdoc}
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function getSession($name = null)
    {
        return $this->mink->getSession($name);
    }

    /**
     * @return ContainerInterface
     */
    protected function getContainer()
    {
        return $this->kernel->getContainer();
    }

    /**
     * @param string $serviceId #Service
     * @return object
     */
    protected function getService($serviceId)
    {
        return $this->getContainer()->get($serviceId);
    }

    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->getService('doctrine.orm.entity_manager');
    }

    /**
     * @param string $entityClass #Entity
     * @return EntityRepository
     */
    protected function getEntityRepository($entityClass)
    {
        return $this->getService('doctrine')
            ->getManagerForClass($entityClass)
            ->getRepository($entityClass)
        ;
    }

    /**
     * @return User
     */
    protected function getCurrentUser()
    {
        return $this->getService('security.token_storage')->getToken()->getUser();
    }
}
