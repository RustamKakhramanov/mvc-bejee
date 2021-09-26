<?php


namespace Core;


class Collection
{
    private $data;
    private $attributes;
    protected $preparedData;

    public function __construct($attributes, $data)
    {
        $this->attributes = $attributes;
        $this->data = $data;

        $this->setDataProperties();

        return $this;
    }

    public function setDataProperties()
    {
        foreach ($this->data as $item) {
            $this->preparedData[] = (object)array_intersect_key($item, array_flip($this->attributes));
        }
    }

    public function get()
    {
        return $this->preparedData;
    }

    public function first()
    {
        return $this->get()[0] ?? null;
    }

}