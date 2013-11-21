<?php

namespace CleverAge\Orchestrator\Sources;

use CleverAge\Orchestrator\Cache\CacheCapable;

abstract class CachedSource extends CacheCapable implements SourceInterface
{
    /**
     * @var array
     */
    protected $cacheLifetime = array(
        'project'       => 3600,
        'branch'        => 300,
        'merge_requst'  => 60,
    );

    /**
     * @return array<Model\Project>
     */
    public function getProjects()
    {
        return $this->getCachedRessource('projects', 'doGetProjects', array(), 'project');
    }

    /**
     * @return array<Model\Project>
     */
    abstract protected function doGetProjects();

    /**
     * @return Model\Project
     */
    public function getProject($id)
    {
        return $this->getCachedRessource('project_'.$id, 'doGetProject', func_get_args(), 'project');
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
        return $this->getCachedRessource('branch_'.$id, 'doGetBranch', func_get_args(), 'branch');
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
    public function getMergeRequests(Model\Project $project, $page = 1, $perPage = 20)
    {
        return $this->getCachedRessource('mergerequests', 'doGetMergeRequests', func_get_args(), 'mergerequest');
    }

    /**
     * @param Model\Project $project
     * @param integer       $page
     * @param integer       $perPage
     * @return array<Model\MergeRequest>
     */
    abstract protected function doGetMergeRequests(Model\Project $project, $page = 1, $perPage = 20);
}
