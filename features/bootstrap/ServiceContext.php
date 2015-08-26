<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Kubernetes\Client\Model\ObjectMetadata;
use Kubernetes\Client\Model\Service;
use Kubernetes\Client\Model\ServicePort;
use Kubernetes\Client\Model\ServiceSpecification;

class ServiceContext implements Context
{
    /**
     * @var ClientContext
     */
    private $clientContext;

    /**
     * @var NamespaceContext
     */
    private $namespaceContext;

    /**
     * @var Service
     */
    private $service;

    /**
     * @BeforeScenario
     */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        $this->clientContext = $scope->getEnvironment()->getContext('ClientContext');
        $this->namespaceContext = $scope->getEnvironment()->getContext('NamespaceContext');
    }

    /**
     * @AfterScenario @deleteService
     */
    public function deleteService()
    {
        $this->getRepository()->delete($this->service);
    }

    /**
     * @When I create a service
     */
    public function iCreateAService()
    {
        $specification = new ServiceSpecification([
            'select-with' => 'a-label'
        ], [
            new ServicePort('web', 80, 'TCP')
        ]);

        $this->service = new Service(new ObjectMetadata('my-service'), $specification);
        $this->getRepository()->create($this->service);
    }

    /**
     * @When I create a service with some non-string values in selector
     */
    public function iCreateAServiceWithSomeNonStringValuesInSelector()
    {
        $specification = new ServiceSpecification([
            'select-with' => 'a-label',
            'boolean' => false
        ], [
            new ServicePort('web', 80, 'TCP')
        ]);

        $this->service = new Service(new ObjectMetadata('my-service'), $specification);
        $this->getRepository()->create($this->service);
    }

    /**
     * @Then the service should exists
     */
    public function theServiceShouldExists()
    {
        $serviceName = $this->service->getMetadata()->getName();

        if (!$this->getRepository()->exists($serviceName)) {
            throw new \RuntimeException(sprintf(
                'The service "%s" do not exists',
                $serviceName
            ));
        }
    }

    /**
     * @return \Kubernetes\Client\Repository\ServiceRepository
     */
    private function getRepository()
    {
        $client = $this->clientContext->getClient()->getNamespaceClient(
            $this->namespaceContext->getNamespace()
        );

        return $client->getServiceRepository();
    }
}