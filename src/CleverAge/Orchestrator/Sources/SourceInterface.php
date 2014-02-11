<?php

namespace CleverAge\Orchestrator\Sources;

use CleverAge\Orchestrator\Model\Urlisable;

interface SourceInterface
{
    /**
     * @param CleverAge\Orchestrator\Model\Urlisable $object
     * @return string
     */
    public function getUrlFor(Urlisable $object);

    /**
     * Returns list of project using ids provided (all if no ids).
     * 
     * @param array $ids
     * @return array<CleverAge\Orchestrator\Sources\Model\Project>
     */
    public function getProjects(array $ids = array());

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

    /**
     * @param \CleverAge\Orchestrator\Sources\Model\MergeRequest $mergeRequest
     * @return \CleverAge\Orchestrator\Sources\Model\MergeRequest
     */
    public function postMergeRequest(Model\MergeRequest $mergeRequest);

    /**
     * @param integer       $page
     * @param integer       $perPage
     * @return array<Model\User>
     */
    public function getUsers($limit = 20, $offset = 0);

    /**
     * @param string       $username
     * @param boolean|null $active if boolean, get active or not user, if null, returns the user
     * @return Model\User
     */
    public function getUserByUsername($username, $active = null);

    /**
     * @param string       $email
     * @param boolean|null $active if boolean, get active or not user, if null, returns the user
     * @return Model\User
     */
    public function getUserByEmail($email, $active = null);
}
