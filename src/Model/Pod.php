<?php

namespace Kubernetes\Client\Model;

class Pod
{
    /**
     * @var ObjectMetadata
     */
    private $metadata;

    /**
     * @var PodSpecification
     */
    private $specification;

    /**
     * @var PodStatus
     */
    private $status;

    /**
     * @param ObjectMetadata   $metadata
     * @param PodSpecification $specification
     */
    public function __construct(ObjectMetadata $metadata, PodSpecification $specification = null)
    {
        $this->metadata = $metadata;
        $this->specification = $specification;
    }

    /**
     * @param string $name
     *
     * @return Pod
     */
    public static function fromName($name)
    {
        return new self(new ObjectMetadata($name));
    }

    /**
     * @return ObjectMetadata
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * @return PodSpecification
     */
    public function getSpecification()
    {
        return $this->specification;
    }

    /**
     * @return PodStatus
     */
    public function getStatus()
    {
        return $this->status;
    }
}
