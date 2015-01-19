<?php

namespace CleverAge\Orchestrator\Sources;

use CleverAge\Orchestrator\Service\CachedService;

abstract class CachedSource extends CachedService implements SourceInterface
{
    /**
     * @var array
     */
    protected $cacheLifetime = array(
        'project'       => 86400,
        'branch'        => 300,
        'mergerequest'  => 60,
        'user'          => 86400,
    );

    // ------ PROJECTS ------ \\

    /**
     * @return array<Model\Project>
     */
    public function getProjects(array $ids = array())
    {
        sort($ids);
        $key = 'projects'.(empty($ids) ? '' : '_'.implode('-', $ids));
        return $this->getResource('doGetProjects', func_get_args(), array(
            'cache_key' => $key,
            'cache_lifetime' => $this->cacheLifetime['project']
        ));
    }

    /**
     * @return array<Model\Project>
     */
    abstract protected function doGetProjects(array $ids = array());

    /**
     * @return Model\Project
     */
    public function getProject($id)
    {
        return $this->getResource('doGetProject', func_get_args(), array(
            'cache_key' => 'project_'.$id,
            'cache_lifetime' => $this->cacheLifetime['project']
        ));
    }

    /**
     * @param string $id Project id
     * @return Model\Project
     */
    abstract protected function doGetProject($id);


    // ------ BRANCHES ------ \\

    /**
     * @param Model\Project $project
     * @param string        $id
     * @return Model\Branch
     */
    public function getBranch(Model\Project $project, $id)
    {
        return $this->getResource('doGetBranch', func_get_args(), array(
            'cache_key' => 'branch_'.$project->getId().'_'.$id,
            'cache_lifetime' => $this->cacheLifetime['branch']
        ));
    }

    /**
     * @param Model\Project $project
     * @param string        $id
     * @return Model\Branch
     */
    abstract protected function doGetBranch(Model\Project $project, $id);


    // ------ MERGE REQUESTS ------ \\

    /**
     * @param Model\Project $project
     * @param integer       $page
     * @param integer       $perPage
     * @return array<Model\MergeRequest>
     */
    public function getMergeRequests(Model\Project $project, $limit = 20, $offset = 0)
    {
        return $this->getResource('doGetMergeRequests', func_get_args(), array(
            'cache_key' => 'mergerequests_'.$project->getId().'_'.$limit.'_'.$offset,
            'cache_lifetime' => $this->cacheLifetime['mergerequest']
        ));
    }

    /**
     * @param Model\Project $project
     * @param integer       $page
     * @param integer       $perPage
     * @return array<Model\MergeRequest>
     */
    abstract protected function doGetMergeRequests(Model\Project $project, $limit = 20, $offset = 0);

    /**
     * @param  \CleverAge\Orchestrator\Sources\Model\Project $project
     * @param  string $branchSource
     * @param  string $branchTarget
     * @return \CleverAge\Orchestrator\Sources\Model\MergeRequest|null
     */
    public function getMergeRequestBetween(Model\Project $project, $branchSource, $branchTarget)
    {
        return $this->getResource('doGetMergeRequestBetween', func_get_args(), array(
            'cache_key' => 'mergerequests_'.$project->getId().'_'.$branchSource.'_'.$branchTarget,
            'cache_lifetime' => $this->cacheLifetime['mergerequest']
        ));
    }

    /**
     * @param  \CleverAge\Orchestrator\Sources\Model\Project $project
     * @param  string $branchSource
     * @param  string $branchTarget
     * @return \CleverAge\Orchestrator\Sources\Model\MergeRequest|null
     */
    abstract protected function doGetMergeRequestBetween(Model\Project $project, $branchSource, $branchTarget);

    /**
     * @param Model\MergeRequest $mergeRequest
     * @return Model\MergeRequest
     */
    public function postMergeRequest(Model\MergeRequest $mergeRequest)
    {
        $sourceBranchName = $mergeRequest->getSourceBranchName();
        $targetBranchName = $mergeRequest->getTargetBranchName();

        return $this->getResource('doPostMergeRequest', func_get_args(), array(
            'cache_key' => 'mergerequest_'.$mergeRequest->getProject()->getId().'_'.$sourceBranchName.'_'.$targetBranchName,
            'cache_lifetime' => $this->cacheLifetime['mergerequest']
        ));
    }

    abstract protected function doPostMergeRequest(Model\MergeRequest $mergeRequest);

    // ------ USERS ------ \\

    /**
     * @param boolean|null  $active
     * @param integer       $page
     * @param integer       $perPage
     * @return array<Model\User>
     */
    public function getUsers($active = null, $limit = 20, $offset = 0)
    {
        return $this->getResource('doGetUsers', func_get_args(), array(
            'cache_key' => 'users_'.$this->convertValue($active).'_'.$limit.'_'.$offset,
            'cache_lifetime' => $this->cacheLifetime['user']
        ));
    }

    /**
     * @param boolean|null  $active
     * @param integer       $page
     * @param integer       $perPage
     * @return array<Model\User>
     */
    abstract protected function doGetUsers($active = null, $limit = 20, $offset = 0);

    /**
     * @param string       $username
     * @param boolean|null $active if boolean, get active or not user, if null, returns the user
     * @return Model\User
     */
    public function getUserByUsername($username, $active = null)
    {
        return $this->getResource('doGetUserByUsername', func_get_args(), array(
            'cache_key' => 'user_username_'.$this->convertValue($active).'_'.$username,
            'cache_lifetime' => $this->cacheLifetime['user']
        ));
    }

    /**
     * @param string       $email
     * @param boolean|null $active if boolean, get active or not user, if null, returns the user
     * @return Model\User|null
     */
    abstract protected function doGetUserByEmail($email, $active = null);

    /**
     * @param string       $email
     * @param boolean|null $active if boolean, get active or not user, if null, returns the user
     * @return Model\User
     */
    public function getUserByEmail($email, $active = null)
    {
        return $this->getResource('doGetUserByEmail', func_get_args(), array(
            'cache_key' => 'user_email_'.$this->convertValue($active).'_'.$email,
            'cache_lifetime' => $this->cacheLifetime['user']
        ));
    }

    /**
     * @param string       $username
     * @param boolean|null $active if boolean, get active or not user, if null, returns the user
     * @return Model\User|null
     */
    abstract protected function doGetUserByUsername($username, $active = null);


    // ------ TOOLS ------ \\

    /**
     * @inheritdoc
     */
    protected function fetchResource($method, $arguments)
    {
        return call_user_func_array(array($this, $method), $arguments);
    }
}
