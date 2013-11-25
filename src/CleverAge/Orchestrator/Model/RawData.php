<?php

namespace CleverAge\Orchestrator\Model;

abstract class RawData
{
    /**
     * @var mixed The raw data from original lib
     */
    protected $raw;

    public function getRaw()
    {
        return $this->raw;
    }

    public function setRaw($raw)
    {
        $this->raw = $raw;
        return $this;
    }
}
