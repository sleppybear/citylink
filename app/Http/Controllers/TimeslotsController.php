<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Http\Requests\CreateTimeslotRequest;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class TimeslotsController extends Controller
{
    private array $timeslots = [
//        '09:00-11:00',
//        '11:00-13:00',
//        '15:00-16:00',
//        '17:00-20:00',
//        '20:30-21:30',
//        '21:30-22:30',
        '09:00-11:00',
        '21:30-22:30',
        '20:30-21:30',
        '17:00-20:00',
        '15:00-16:00',
        '11:00-13:00',
        '23:00-01:00',
    ];

    private array $existTimeslots;

    private array $errors = [];

    public function __construct()
    {
        $this->existTimeslots = collect($this->timeslots)->map(function (string $timeslot) {
            return Helper::timeIntervalToArray($timeslot);
        })
            ->sortBy(0)
            ->toArray();
    }

    public function create(CreateTimeslotRequest $request): Response
    {
        $timeInterval = $request->getTimeInterval();

        if (!$this->validateOverlap($timeInterval)) {
            throw new BadRequestHttpException($this->prepareMessage());
        }

        return new Response([
            'result' => 'Интервал добавлен'
        ]);
    }

    private function validateOverlap(array $timeslot): bool
    {
        foreach ($this->existTimeslots as $existTimeslot) {
            if (
                $existTimeslot[0] < $timeslot[0] && $timeslot[0] < $existTimeslot[1]
                || $existTimeslot[0] < $timeslot[1] && $timeslot[1] < $existTimeslot[1]
                || $timeslot [0] < $existTimeslot[0] && $existTimeslot[1] < $timeslot[1]
                || $existTimeslot[0] == $timeslot[0]
                || $existTimeslot[1] == $timeslot[1]
            ) {
                $this->errors[] = $existTimeslot;
            };
        }

        if (count($this->errors) > 0) {
            return false;
        }
        return true;
    }

    private function prepareMessage(): string
    {
        $intervals = collect($this->errors)->map(function (array $error) {
            return $error[0]->format('H:i') . '-' . $error[1]->format('H:i');
        })->implode(', ');

        return "Пересечения с существующими интервалами: " . $intervals;
    }


}
