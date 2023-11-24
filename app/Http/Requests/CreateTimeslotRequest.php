<?php

namespace App\Http\Requests;

use App\Helper;
use App\Http\Rules\TimeslotRule;

class CreateTimeslotRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'timeInterval' => ['required', 'string', new TimeslotRule()],
        ];
    }

    public function getTimeInterval(): array
    {
        return Helper::timeIntervalToArray($this->input('timeInterval'));
    }
}
