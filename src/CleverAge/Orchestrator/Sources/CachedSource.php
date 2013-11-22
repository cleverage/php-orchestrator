<?php

namespace CleverAge\Orchestrator\Sources;

use CleverAge\Orchestrator\Cache\CacheCapable;

abstract class CachedSource extends CacheCapable implements SourceInterface
{
    /**
     * @var array
     */
    protected $cacheLifetime = array(
        'project'       => 86400,
        'branch'        => 300,
        'merge_requst'  => 60,
    );

    /**
     * @return array<Model\Project>
     */
    public function getProjects(array $ids = array())
    {
        sort($ids);
        $key = 'projects'.(empty($ids) ? '' : '_'.implode('-', $ids));
        return $this->getCachedRessource($key, 'doGetProjects', func_get_args(), 'project');
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
        return $this->getCachedRessource('branch_'.$project->getId().'_'.$id, 'doGetBranch', func_get_args(), 'branch');
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
        return $this->getCachedRessource('mergerequests_'.$project->getId().'_'.$limit.'_'.$offset, 'doGetMergeRequests', func_get_args(), 'mergerequest');
    }

    /**
     * @param Model\Project $project
     * @param integer       $page
     * @param integer       $perPage
     * @return array<Model\MergeRequest>
     */
    abstract protected function doGetMergeRequests(Model\Project $project, $limit = 20, $offset = 0);
}
