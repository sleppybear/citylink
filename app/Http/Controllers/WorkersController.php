<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchWorkersRequest;
use Illuminate\Http\Response;

class WorkersController extends Controller
{
    private const DEFAULT_SEARCH_DEPTH = 1;

    private array $areas = [
        1 => '5-й поселок',
        2 => 'Голиковка',
        3 => 'Древлянка',
        4 => 'Заводская',
        5 => 'Зарека',
        6 => 'Ключевая',
        7 => 'Кукковка',
        8 => 'Новый сайнаволок',
        9 => 'Октябрьский',
        10 => 'Первомайский',
        11 => 'Перевалка',
        12 => 'Сулажгора',
        13 => 'Университетский городок',
        14 => 'Центр',
    ];

    private array $nearby = [
        1 => [2, 11],
        2 => [12, 3, 6, 8],
        3 => [11, 13],
        4 => [10, 9, 13],
        5 => [2, 6, 7, 8],
        6 => [10, 2, 7, 8],
        7 => [2, 6, 8],
        8 => [6, 2, 7, 12],
        9 => [10, 14],
        10 => [9, 14, 12],
        11 => [13, 1, 9],
        12 => [1, 10],
        13 => [11, 1, 8],
        14 => [9, 10],
    ];

    private array $workers = [
        0 => [
            'login' => 'login1',
            'area_name' => 'Октябрьский', //9
        ],
        1 => [
            'login' => 'login2',
            'area_name' => 'Зарека', //5
        ],
        2 => [
            'login' => 'login3',
            'area_name' => 'Сулажгора', //12
        ],
        3 => [
            'login' => 'login4',
            'area_name' => 'Древлянка', //3
        ],
        4 => [
            'login' => 'login5',
            'area_name' => 'Октябрьский'//'Центр', //14
        ],
    ];
    private ?int $currentAreaId = null;
    private array $workersByArea = [];
    private array $areaIdByNames;

    public function __construct()
    {
        $this->areaIdByNames = array_flip($this->areas);

        foreach ($this->workers as $worker) {
            $this->workersByArea[$this->areaIdByNames[$worker['area_name']]][] = $worker['login'];
        }
    }

    public function search(SearchWorkersRequest $request): Response
    {
        $needleAreaName = $request['area'] ?? null;

        if (isset($needleAreaName)) {
            $this->currentAreaId = $this->areaIdByNames[$needleAreaName] ?? null;
        }
        if (isset($this->currentAreaId)) {
            $workers = $this->getWorkers($this->currentAreaId, self::DEFAULT_SEARCH_DEPTH);
        }
        return new Response([
            'data' => $workers ?? null
        ]);
    }

    /**
     * @param int|null $needleAreaId
     * @param int $depth
     * @param array $excludedAreaIds
     * @return array|null
     */
    private function getWorkers(?int $needleAreaId, int $depth, array $excludedAreaIds = []): ?array
    {
        $result = [];
        $needleAreaName = $this->areas[$needleAreaId];

        if (isset($needleAreaId) && array_key_exists($needleAreaId, $this->workersByArea)) {
            $needleAreaWorkers = $this->workersByArea[$needleAreaId];
        }

        $result[$needleAreaName]['area_id'] = $needleAreaId;
        $result[$needleAreaName]['area_workers'] = $needleAreaWorkers ?? null;

        if (!isset($needleAreaWorkers) && $depth !== 0) {
            $nearbyAreaIds = $this->nearby[$this->currentAreaId];

            $excludedAreaIds[] = $needleAreaId;

            $nearbyAreaIds = array_filter($nearbyAreaIds, function (int $nearbyAreaId) use ($excludedAreaIds) {
                return !array_search($nearbyAreaId, $excludedAreaIds);
            });

            foreach ($nearbyAreaIds as $nearbyAreaId) {
                $nearbyWorkersByArea = $this->getWorkers($nearbyAreaId, $depth-1, $excludedAreaIds);

                $result[$needleAreaName]['nearby_workers'][] = $nearbyWorkersByArea;
            }
        }
        return $result;
    }
}
