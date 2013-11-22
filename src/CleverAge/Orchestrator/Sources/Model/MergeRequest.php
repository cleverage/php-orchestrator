<?php

namespace CleverAge\Orchestrator\Sources\Model;

use CleverAge\Orchestrator\Model\Urlisable;

class MergeRequest implements Urlisable
{
    const STATE_OPENED = 'opened';
    const STATE_MERGED = 'merged';
    const STATE_CLOSED = 'closed';

    protected $id;
    protected $globalId;
    protected $name;

    /**
     * @var Project
     */
    protected $project;
    protected $state;
    protected $sourceBranchName;
    protected $targetBranchName;
    /**
     * @var MergeRequestUser
     */
    protected $author;
    /**
     * @var MergeRequestUser
     */
    protected $assignee;

    protected $url;

    public function getId()
    {
        return $this->id;
    }

    public function getGlobalId()
    {
        return $this->globalId;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getProject()
    {
        return $this->project;
    }

    public function getState()
    {
        return $this->state;
    }

    public function getSourceBranchName()
    {
        return $this->sourceBranchName;
    }

    public function getTargetBranchName()
    {
        return $this->targetBranchName;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function getAssignee()
    {
        return $this->assignee;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setGlobalId($globalId)
    {
        $this->globalId = $globalId;
        return $this;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setProject(Project $project)
    {
        $this->project = $project;
        return $this;
    }

    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }

    public function setSourceBranchName($sourceBranchName)
    {
        $this->sourceBranchName = $sourceBranchName;
        return $this;
    }

    public function setTargetBranchName($targetBranchName)
    {
        $this->targetBranchName = $targetBranchName;
        return $this;
    }

    public function setAuthor(MergeRequestUser $author)
    {
        $this->author = $author;
        return $this;
    }

    public function setAssignee(MergeRequestUser $assignee)
    {
        $this->assignee = $assignee;
        return $this;
    }

    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }
}
