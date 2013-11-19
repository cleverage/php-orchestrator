<?php

namespace CleverAge\Orchestrator\Sources;

interface SourceInterface
{
    /**
     * @return array<CleverAge\Orchestrator\Sources\Model\Project>
     */
    public function getProjects();

    /**
     * @param int $id
     * @return CleverAge\Orchestrator\Sources\Model\Project
     */
    public function getProject($id);
}
