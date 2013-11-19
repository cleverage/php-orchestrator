<?php

namespace CleverAge\Orchestrator\Sources\Model;

class Project
{
    protected $id;
    protected $name;
    protected $description;
    protected $url;
    protected $sshGit;
    protected $httpGit;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getSshGit()
    {
        return $this->sshGit;
    }

    public function getHttpGit()
    {
        return $this->httpGit;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
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

    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    public function setSshGit($sshGit)
    {
        $this->sshGit = $sshGit;

        return $this;
    }

    public function setHttpGit($httpGit)
    {
        $this->httpGit = $httpGit;

        return $this;
    }
}
