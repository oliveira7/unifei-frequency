<?php

namespace App\Repositories;

use Illuminate\Container\Container as App;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class DefaultRepository
{
    private $app;

    private $model;

    private $fqcn;

    public function __construct(App $app)
    {
        $modelFQCN = str_replace('\\Repositories\\', '\\Models\\', static::class);
        $this->fqcn = preg_replace('@Repository$@', '', $modelFQCN);

        $this->app = $app;
    }

    public function find($id, $columns = ['*'])
    {
        return $this->getModel()->find($id, $columns);
    }

    public function findBy($attribute, $value, $columns = ['*'])
    {
        return $this->getModel()->where($attribute, '=', $value)->first($columns);
    }

    public function all($columns = ['*'])
    {
        return $this->query()->select($columns)->paginate();
    }

    public function allWithoutPagination($columns = ['*'])
    {
        return $this->query()->select($columns)->get();
    }

    public function create(array $data)
    {
        $resource = $this->getModel();
        $resource->fill($data);
        $resource->save();

        return $resource;
    }

    public function update(array $data, $id, $attribute = 'id')
    {
        $resource = $this->getModel()::find($id);
        $resource->fill($data);

        return $resource->save();
    }

    public function delete($id)
    {
        return $this->getModel()->destroy($id);
    }

    public function getModel()
    {
        $model = $this->app->make($this->fqcn);

        if (!$model instanceof Model) {
            throw new \RuntimeException("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $model;
    }

    public function query(): Builder
    {
        return $this->getModel()->newQuery();
    }

    public function queryWithoutScope($scope)
    {
        return $this->getModel()->newQueryWithoutScope($scope);
    }

    protected function hasFilter($filters, $name)
    {
        return \array_key_exists($name, $filters) && $name;
    }

    protected function filter($filters, $name, callable $callback): void
    {
        if (!$this->hasFilter($filters, $name)) {
            return;
        }

        $callback($filters[$name]);
    }
}
