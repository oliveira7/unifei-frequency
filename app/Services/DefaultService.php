<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class DefaultService
{
    protected $repository;

    protected $fileService;

    public function index($requestData, ?Builder $query = null)
    {
        $query ??= $this->repository->query();

        $paginationEnabled = (bool) Arr::get($requestData, 'pagination', 1);
        $strQuery = Arr::get($requestData, 'query', '');
        $sortField = Arr::get($requestData, 'sort_field', null);
        $sortDirection = Arr::get($requestData, 'sort_direction', 'ASC');
        $perPage = (int) Arr::get($requestData, 'per_page', 15);
        $searchableFields = $this->repository->getModel()->searchable ?? [];
        $allowedQueryParams = $this->repository->getModel()->allowedQueryParams ?? [];

        foreach ($allowedQueryParams as $fieldAlias => $fieldParams) {
            $fieldParams = array_pad((array) $fieldParams, 2, '=');
            [$fieldName, $operator] = $fieldParams;

            $fieldAlias = \is_string($fieldAlias) ? $fieldAlias : $fieldName;

            $fieldNameParts = explode('.', $fieldName);
            $fieldNameParts = array_pad((array) $fieldNameParts, -2, null);
            [$relation, $fieldName] = $fieldNameParts;

            $value = Arr::get($requestData, $fieldAlias, '');

            if (mb_strlen($value) > 0) {
                if ($relation) {
                    $query->whereHas($relation, function (Builder $relationQuery) use ($fieldName, $operator, $value): void {
                        $relationQuery->where($fieldName, $operator, $value);
                    });

                    continue;
                }

                if (mb_strtoupper($operator) === 'IN') {
                    $whereInValues = explode(',', $value);
                    $query->whereIn($fieldName, $whereInValues);

                    continue;
                }

                $query->where($fieldName, $operator, $value);
            }
        }

        if (mb_strlen($strQuery) > 0) {
            $searchString = str_replace(' ', '%', $strQuery);

            $query->where(function ($subquery) use ($searchableFields, $searchString, $query): void {
                foreach ($searchableFields as $relation => $field) {
                    if (\is_array($field)) {
                        $relationSearchableFields = $field;

                        $query->whereHas($relation, function (Builder $relationQuery) use ($relationSearchableFields, $searchString): void {
                            $relationQuery->where(function ($relationSubQuery) use ($relationSearchableFields, $searchString): void {
                                foreach ($relationSearchableFields as $field) {
                                    $relationSubQuery->orWhere($field, 'LIKE', "%{$searchString}%");
                                }
                            });
                        });

                        continue;
                    }

                    $subquery->orWhere($field, 'LIKE', "%{$searchString}%");
                }
            });
        }

        if ($sortField) {
            $query->orderBy($sortField, $sortDirection);
        }

        return $paginationEnabled ? $query->paginate($perPage) : $query->get();
    }

    public function show($id)
    {
        return $this->repository->findBy('id', $id);
    }

    public function store($data)
    {
        return $this->repository->create($data);
    }

    public function update($resource, $data)
    {
        $this->repository->update($data, $resource->id);

        return $this->show($resource->id);
    }

    public function destroy($resource)
    {
        return $this->repository->delete($resource->id);
    }

    public function destroyMany($ids)
    {
        return $this->repository->deleteMany($ids);
    }

    protected function updateFileField(FileService $fileService, $data, $fieldName, $resource = null, $relationName = null)
    {
        $oldResource = $resource->{$fieldName} ?? null;

        $fileData = Arr::get($data, $fieldName, null);

        if ($fileData === null) {
            return $data;
        }

        if (empty($fileData)) {
            if ($oldResource) {
                $fileService->destroy($oldResource);
            }

            $data[$fieldName] = '';

            return $data;
        }

        $fileStored = $fileService->store($fileData);

        if ($fileStored) {
            $data[$fieldName] = $fileStored;

            if ($oldResource) {
                $fileService->destroy($oldResource);
            }
        }

        return $data;
    }
}