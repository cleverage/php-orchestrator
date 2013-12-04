<?php

namespace CleverAge\Orchestrator\Sources\Model;

interface CommitAuthorInterface
{
    public function getName();

    public function getEmail();

    public function setName($name);

    public function setEmail($email);
}
