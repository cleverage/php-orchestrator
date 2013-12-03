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
    );

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
     * @inheritdoc
     */
    protected function fetchResource($method, $arguments)
    {
        return call_user_func_array(array($this, $method), $arguments);
    }
}
