<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => (int) $this->id,
            'birth_date' => (string) $this->birth_date,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d\TH:i:s\Z'),
            'updated_at' => Carbon::parse($this->updated_at)->format('Y-m-d\TH:i:s\Z')
        ];
    }

    /**
     * Get any additional data that should be returned with the resource array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function with($request)
    {
        return [
            'success' => true,
        ];
    }
}
