<?php

namespace CleverAge\Orchestrator\Ticketing\Model;

use CleverAge\Orchestrator\Model\RawData;
use CleverAge\Orchestrator\Model\HashUniqId;

class Milestone extends RawData implements HashUniqId
{
    protected $name;
    protected $description;
    /**
     * @var \DateTime
     */
    protected $endAt;
    /**
     * @var \DateTime
     */
    protected $completedAt;

    public function getName()
    {
        return $this->name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getEndAt()
    {
        return $this->endAt;
    }

    public function getCompletedAt()
    {
        return $this->completedAt;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function setEndAt(\DateTime $endAt = null)
    {
        $this->endAt = $endAt;
        return $this;
    }

    public function setCompletedAt(\DateTime $completed = null)
    {
        $this->completed = $completed;
        return $this;
    }

    public function isCompleted()
    {
        return $this->completedAt instanceof \DateTime;
    }

    public function getHashUniqId()
    {
        return strtolower($this->getName());
    }

    public function __toString()
    {
        return $this->getName();
    }
}
