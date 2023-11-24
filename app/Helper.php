<?php

namespace App;

use DateTime;

class Helper
{
    public static function sort(array $items): array
    {
        if (count($items) < 2) {
            return $items;
        }

        $mid = [array_shift($items)];
        $left = [];
        $right = [];

        foreach ($items as $item) {
            match (true) {
                $item > $mid[0] => $right[] = $item,
                $item < $mid[0] => $left[] = $item,
                $item === $mid[0] => $mid[] = $item,
            };
        }

        return [...(self::sort($left)), ...$mid, ...(self::sort($right))];
    }

    public function find()
    {

    }

    public static function timeIntervalToArray(string $timeInterval): array
    {
        $timeslotArr = explode('-', $timeInterval);

        /** @var DateTime[] $timeslot */
        $timeslot = [
            DateTime::createFromFormat('H:i', $timeslotArr[0]),
            DateTime::createFromFormat('H:i', $timeslotArr[1]),
        ];

        if ($timeslot[0] > $timeslot[1]) {
            $timeslot[0]->modify('- 1day');
        }

        return $timeslot;
    }
}
