<?php

namespace CleverAge\Orchestrator\Sources;

interface SourceInterface
{
    /**
     * @param mixed object from CleverAge\Orchestrator\Sources\Model\*
     * @return string
     */
    public function getUrlFor($object);

    /**
     * @return array<CleverAge\Orchestrator\Sources\Model\Project>
     */
    public function getProjects();

    /**
     * @param int $id
     * @return CleverAge\Orchestrator\Sources\Model\Project
     */
    public function getProject($id);

    /**
     * @param CleverAge\Orchestrator\Sources\Model\Project $project
     * @param string|int $branch branch identifier
     *
     * @return CleverAge\Orchestrator\Sources\Model\Branch|null
     */
    public function getBranch(Model\Project $project, $branch);

    /**
     * @param \CleverAge\Orchestrator\Sources\Model\Project $project
     * @param int $page
     * @param int $perPage
     * @return array<\CleverAge\Orchestrator\Sources\Model\MergeRequest>
     */
    public function getMergeRequests(Model\Project $project, $page = 1, $perPage = 20);
}
