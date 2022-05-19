<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class BaseFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $class = $this->bidValidation();
        $instance = new $class();

        return match($this->method()){
            'POST' => $instance->store(),
            'PUT', 'PATCH' => $instance->update()
        };
    }

    /**
     * Get the validation rules that apply to the get request.
     *
     * @return array
     */
    public function view()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation rules that apply to the post request.
     *
     * @return array
     */
    public function store()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation rules that apply to the put/patch request.
     *
     * @return array
     */
    public function update()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation rules that apply to the delete request.
     *
     * @return array
     */
    public function destroy()
    {
        return [
            //
        ];
    }

    private function bidValidation(): string
    {
        $routeArray = app('request')->route()->getAction();
        $controllerAndAction = class_basename($routeArray['controller']);
        list($controller, $action) = explode('@', $controllerAndAction);
        $className = str_replace('Controller', '', $controller);
        $fileName = Str::ucfirst(Str::camel($className)) . 'Request';
        $className = "App\\Http\\Requests\\{$fileName}";

        if (!class_exists($className)) {
            throw new Exception("The Request file {$fileName} does not exists.");
        }

        return $className;
    }
}