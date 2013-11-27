<?php

namespace CleverAge\Orchestrator\Service\Listeners;

use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpKernel\DataCollector\DataCollectorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Stopwatch\Stopwatch;

use CleverAge\Orchestrator\Events\ServiceEvent;

class DataCollectorListener extends DataCollector implements DataCollectorInterface
{
    /**
     * @var array $profiles Profiled data
     */
    protected $profiles = array();

    /**
     * @var Symfony\Component\Stopwatch\Stopwatch $stopwatch Symfony profiler Stopwatch service
     */
    protected $stopwatch;

    /**
     * @var integer
     */
    protected $counter = 1;

    /**
     * @var Symfony\Component\Stopwatch\StopwatchEvent
     */
    protected $activeProfileEvent;

    /**
     * @param Symfony\Component\Stopwatch\Stopwatch $stopwatch
     */
    public function __construct(Stopwatch $stopwatch = null)
    {
        $this->stopwatch = $stopwatch;
    }

    // ------ LISTENER ------ \\

    /**
     * @param CleverAge\Orchestrator\Events\ServiceEvent $event
     * @return Symfony\Component\Stopwatch\StopwatchEvent
     */
    protected function startProfiling(ServiceEvent $event)
    {
        if ($this->stopwatch instanceof Stopwatch) {
            $this->profiles[$event->getService()->getName()][$this->counter] = array(
                'method'        => $event->getRequestMethod(),
                'parameters'    => $event->getRequestParameters(),
                'duration'      => null,
                'result_count'  => 0,
            );

            return $this->stopwatch->start($event->getService()->getName().'_'.$this->counter);
        }
    }

    /**
     * @param CleverAge\Orchestrator\Events\ServiceEvent
     */
    protected function stopProfiling(ServiceEvent $event)
    {
        if ($this->activeProfileEvent) {
            $this->activeProfileEvent->stop();

            $result = $event->getResource();
            if (is_array($result)) {
                $count = count($result);
            } elseif (is_object($result)) {
                $count = 1;
            } else {
                $count = 0;
            }

            $this->profiles[$event->getService()->getName()][$this->counter]['duration'] = $this->activeProfileEvent->getDuration();
            $this->profiles[$event->getService()->getName()][$this->counter]['result_count'] = $count;

            $this->counter++;

            $this->activeProfileEvent = null;
        }
    }

    public function onServicePreFetch(ServiceEvent $event)
    {
        if (!$event->isResourceSet()) {
            $this->activeProfileEvent = $this->startProfiling($event);
        }
    }

    public function onServicePostFetch(ServiceEvent $event)
    {
        $this->stopProfiling($event);
    }

    // ------ DATA COLLECTOR ------ \\

    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $count = 0;
        $duration = 0;

        foreach ($this->profiles as $profiles) {
            $count += count($profiles);
            foreach ($profiles as $profile) {
                $duration += $profile['duration'];
            }
        }

        $this->data = array(
            'totalRequests' => $count,
            'totalDuration' => $duration,
            'requests'      => $this->profiles
        );
    }

    /**
     * Returns profiled data
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'cleverage.orchestrator.service.collector';
    }
}