<?php

namespace App\Http\Requests;

class SearchWorkersRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'area' => ['required', 'string'],
        ];
    }
}
