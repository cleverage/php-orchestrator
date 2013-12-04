<?php

namespace CleverAge\Orchestrator\Sources\Model;

use CleverAge\Orchestrator\Model\Urlisable;
use CleverAge\Orchestrator\Model\RawData;

class MergeRequest extends RawData implements Urlisable
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
     * @var MergeRequestUserInterface
     */
    protected $author;
    /**
     * @var MergeRequestUserInterface
     */
    protected $assignee;

    protected $url;

    /**
     * @var Branch
     */
    protected $sourceBranch;

    /**
     * @var Branch
     */
    protected $targetBranch;

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

    /**
     * @return MergeRequestUserInterface
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @return MergeRequestUserInterface
     */
    public function getAssignee()
    {
        return $this->assignee;
    }

    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return MergeRequestUserInterface
     */
    public function getSourceBranch()
    {
        return $this->sourceBranch;
    }

    /**
     * @return MergeRequestUserInterface
     */
    public function getTargetBranch()
    {
        return $this->targetBranch;
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

    public function setSourceBranch(Branch $sourceBranch)
    {
        $this->sourceBranch = $sourceBranch;
        $this->setSourceBranchName($sourceBranch->getName());
        return $this;
    }

    public function setTargetBranch(Branch $targetBranch)
    {
        $this->targetBranch = $targetBranch;
        $this->setTargetBranchName($targetBranch->getName());
        return $this;
    }

    public function setAuthor(MergeRequestUserInterface $author)
    {
        $this->author = $author;
        return $this;
    }

    public function setAssignee(MergeRequestUserInterface $assignee)
    {
        $this->assignee = $assignee;
        return $this;
    }

    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    public function isOpened()
    {
        return self::STATE_OPENED === $this->getState();
    }

    public function isClosed()
    {
        return self::STATE_CLOSED === $this->getState();
    }

    public function isMerged()
    {
        return self::STATE_MERGED === $this->getState();
    }
}
