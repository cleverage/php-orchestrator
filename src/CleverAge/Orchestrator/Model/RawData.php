<?php

namespace CleverAge\Orchestrator\Model;

abstract class RawData
{
    /**
     * @var mixed The raw data from original lib
     */
    protected $raw;

    protected $providerSpecific;

    public function getRaw()
    {
        return $this->raw;
    }

    public function getProviderSpecific($attribute = null)
    {
        if (!is_null($attribute) && is_array($this->providerSpecific) && isset($this->providerSpecific[$attribute])) {
            return $this->providerSpecific[$attribute];
        }
        return $this->providerSpecific;
    }

    public function setRaw($raw)
    {
        $this->raw = $raw;
        return $this;
    }

    public function setProviderSpecific(array $providerSpecific)
    {
        $this->providerSpecific = $providerSpecific;
        return $this;
    }

    public function addProviderSpecific($key, $value)
    {
        if (!is_array($this->providerSpecific)) {
            $this->providerSpecific = array();
        }

        $this->providerSpecific[$key] = $value;
        return $this;
    }
}
