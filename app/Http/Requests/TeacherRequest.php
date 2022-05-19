<?php

namespace App\Http\Requests;

class TeacherRequest extends BaseFormRequest
{

    /**
     * Get the validation rules that apply to the post request.
     *
     * @return array
     */
    public function store()
    {
        return [
            'birth_date' => 'required|date_format:Y-m-d',
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
            'birth_date' => 'sometimes|date_format:Y-m-d',
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
        ];
    }
}