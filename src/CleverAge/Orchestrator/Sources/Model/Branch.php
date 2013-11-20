<?php

namespace CleverAge\Orchestrator\Sources\Model;


class Branch
{
    protected $name;

    /**
     * @var Commit
     */
    protected $lastCommit;

    /**
     * @var Project
     */
    protected $project;

    public function getName()
    {
        return $this->name;
    }

    public function getLastCommit()
    {
        return $this->lastCommit;
    }

    public function getProject()
    {
        return $this->project;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function setLastCommit(Commit $lastCommit)
    {
        $this->lastCommit = $lastCommit;
        return $this;
    }

    public function setProject(Project $project)
    {
        $this->project = $project;
        return $this;
    }
}
