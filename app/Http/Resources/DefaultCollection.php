<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

class DefaultCollection extends ResourceCollection
{
    /**
     * The resource class used in this collection.
     *
     * @var \Illuminate\Support\Collection
     */
    public $resourceClass;

    /**
     * Create a new resource instance.
     *
     * @return void
     */
    public function __construct($resourceClass, $resource)
    {
        parent::__construct($resource);

        $this->resourceClass = $resourceClass;
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        $content = [
            'success' => true,
            'data' => $this->resourceClass::collection($this->collection),
        ];

        if ($this->resource instanceof LengthAwarePaginator) {
            $content['pagination'] = [
                'total' => $this->total(),
                'current_page' => $this->currentPage(),
                'next_page' => $this->hasMorePages() ? $this->currentPage() + 1 : null,
                'last_page' => $this->lastPage(),
                'per_page' => $this->perPage(),
                'is_last_page' => !$this->hasMorePages(),
            ];
        }

        return $content;
    }

    /**
     * @param \Illuminate\Http\Request      $request
     * @param \Illuminate\Http\JsonResponse $response
     */
    public function withResponse($request, $response): void
    {
        $jsonResponse = json_decode($response->getContent(), true);
        unset($jsonResponse['links'], $jsonResponse['meta']);
        $response->setContent(json_encode($jsonResponse));
    }
}