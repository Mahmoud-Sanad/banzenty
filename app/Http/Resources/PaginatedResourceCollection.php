<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Resources\MissingValue;

class PaginatedResourceCollection extends ResourceCollection
{

    public $collects = 'Illuminate\Http\Resources\Json\JsonResource';

    /**
     * Create a new resource instance.
     *
     * @param  mixed  $resource
     * @param string|null $name Class name of the resource to be collected
     * @return void
     */
    public function __construct($resource, ?string $name = null)
    {
        if($resource instanceof MissingValue){
            return $resource;
        }
        if(!$name && $first = $resource->first()){
            $name = explode('\\',get_class($first));
            $name = end($name) . 'Resource';
        }
        if($name){
            $this->collects = __NAMESPACE__ . '\\' . $name;
        }

        return parent::__construct($resource);
    }
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        try {
            $per_page = $this->perPage();
            $last_page = $this->lastPage();
        } catch (\BadMethodCallException $e) {
            return parent::toArray($request);
        }

        return [
            'current_page' => $this->currentPage(),
            'data' => parent::toArray($request),
            'from' => $this->firstItem(),
            'to' => $this->lastItem(),
            'total' => $this->total(),
            'per_page' => $per_page,
            'last_page' => $last_page,
            'next_page_url' => $this->nextPageUrl(),
            'prev_page_url' => $this->previousPageUrl(),
            'first_page_url' => $this->url(1),
            'last_page_url' => $this->url($last_page),
            'path' => $this->path(),
        ];
    }
}
