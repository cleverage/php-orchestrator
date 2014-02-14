<?php

namespace CleverAge\Orchestrator\Sources\Gitlab;

use Gitlab\Client;
use Gitlab\Exception\RuntimeException;
use CleverAge\Orchestrator\Model\Urlisable;
use CleverAge\Orchestrator\Sources\CachedSource;
use CleverAge\Orchestrator\Sources\Model;
use CleverAge\Orchestrator\Sources\ConverterInterface;

class Gitlab extends CachedSource
{
    const PER_PAGE_MAX      = 100;
    const PER_PAGE_DEFAULT  = 20;

    /**
     * @var \Gitlab\Client
     */
    protected $client;

    /**
     * @var \CleverAge\Orchestrator\Sources\ConverterInterface
     */
    protected $converter;

    public function __construct(Client $client, ConverterInterface $converter = null)
    {
        $this->client = $client;
        $this->converter = $converter ?: new Converter();
    }

    public function getName()
    {
        return 'gitlab';
    }

    /**
     * @return \Gitlab\Client
     */
    protected function getClient()
    {
        return $this->client;
    }

    protected function getPage($limit, $offset)
    {
        return $limit ? ((int) $offset/$limit)+1 : 1;
    }

    /**
     * @inheritdoc
     */
    public function getUrlFor(Urlisable $object)
    {
        if ($object instanceof Model\Project) {
            return $object->getUrl();
        } elseif ($object instanceof Model\Branch) {
            return $object->getProject()->getUrl().'/commits/'.$object->getName();
        } elseif ($object instanceof Model\MergeRequest) {
            return $object->getProject()->getUrl().'/merge_requests/'.$object->getId();
        }

        return substr($this->getClient()->getBaseUrl(), 0, strpos($this->getClient()->getBaseUrl(), '/api/')).'/';
    }

    /**
     * @inheritdoc
     */
    protected function doGetProjects(array $ids = array())
    {
        $projectsApi = $this->getClient()->api('projects')->accessible();

        $projects = array();

        foreach ($projectsApi as $projectApi) {
            $project = $this->converter->convertProjectFromApi($projectApi);
            if (in_array($project->getId(), $ids)) {
                $projects[] = $project;
            }
        }

        return $projects;
    }

    /**
     * @inheritdoc
     */
    protected function doGetProject($id)
    {
        $projectApi = $this->getClient()->api('projects')->show($id);

        return empty($projectApi) ? null : $this->converter->convertProjectFromApi($projectApi);
    }

    /**
     * @inheritdoc
     */
    protected function doGetBranch(Model\Project $project, $id)
    {
        try {
            $branchApi = $this->getClient()->api('repositories')->branch($project->getId(), $id);
        } catch (RuntimeException $e) {
            if ($e->getCode() == 404) {
                return null;
            }
            throw $e;
        }

        $branch = $this->converter->convertBranchFromApi($branchApi);
        $branch->setProject($project);

        return $branch;
    }

    /**
     * @inheritdoc
     */
    protected function doGetMergeRequests(Model\Project $project, $limit = self::PER_PAGE_DEFAULT, $offset = 0)
    {
        $page = $this->getPage($limit, $offset);

        $mrsApi = $this->getClient()->api('merge_requests')->all($project->getId(), $page, $limit);

        $mrs = array();

        foreach ($mrsApi as $mrApi) {
            $mr = $this->converter->convertMergeRequestFromApi($mrApi);
            $mr->setProject($project);
            $mrs[] = $mr;
        }

        return $mrs;
    }

    /**
     * @inheritdoc
     */
    protected function doPostMergeRequest(Model\MergeRequest $mergeRequest)
    {
        $mrApi = $this->getClient()->api('merge_requests')->create(
            $mergeRequest->getProject()->getId(),
            $mergeRequest->getSourceBranchName(),
            $mergeRequest->getTargetBranchName(),
            $mergeRequest->getName(),
            $mergeRequest->getAssignee()->getId()
        );

        $mr = $this->converter->convertMergeRequestFromApi($mrApi);
        $mr
            ->setProject($mergeRequest->getProject())
            ->setSourceBranch($mergeRequest->getSourceBranch())
            ->setTargetBranch($mergeRequest->getTargetBranch())
        ;

        return $mr;
    }

    /**
     * @inheritdoc
     */
    protected function doGetUsers($active = null, $limit = self::PER_PAGE_DEFAULT, $offset = 0)
    {
        $page = $this->getPage($limit, $offset);

        $usersApi = $this->getClient()->api('users')->all($active, $page, $limit);

        $users = array();

        foreach ($usersApi as $userApi) {
            $user = $this->converter->convertUserFromApi($userApi);
            $users[] = $user;
        }

        return $users;
    }

    /**
     * @inheritdoc
     */
    protected function doGetUserByUsername($username, $active = null)
    {
        $users = $this->getUsers($active, self::PER_PAGE_MAX);

        foreach ($users as $user) {
            if ($user->getUsername() == $username) {
                if (!is_bool($active) || $active == $user->getIsEnabled()) {
                    return $user;
                }
                break;
            }
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    protected function doGetUserByEmail($email, $active = null)
    {
        $users = $this->getUsers($active, self::PER_PAGE_MAX);

        foreach ($users as $user) {
            if ($user->getEmail() == $email) {
                if (!is_bool($active) || $active == $user->getIsEnabled()) {
                    return $user;
                }
                break;
            }
        }

        return null;
    }
}
