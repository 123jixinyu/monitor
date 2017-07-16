<?php

namespace App\Http\Controllers;

use App\Entities\MonitorLog;
use App\Entities\UserMonitor;
use App\Repository\ChartRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;

class ChartController extends Controller
{
    public function index(ChartRepository $chartRepository, Request $request)
    {
        $data = $chartRepository->getDataForWeek();
        $x_data = [];
        for ($i = config('monitor.day') - 1; $i >= 0; $i--) {
            $x_data[] = date('Y-m-d', strtotime("-" . $i . "day"));
        }
        $y_data = [];
        array_map(function ($item) use ($data, &$y_data) {
            $result = $data->where('date', $item)->first();
            if ($result) {
                $y_data[] = $result->count;
            } else {
                $y_data[] = 0;
            }
        }, $x_data);
        $monitorLogs = MonitorLog::where('user_id', Auth::user()->id)->where('status', UserMonitor::STATUS_EXCEPTION)->paginate();

        return view('charts.index')->with([
            'x_data' => json_encode($x_data, JSON_UNESCAPED_UNICODE),
            'y_data' => json_encode($y_data, JSON_UNESCAPED_UNICODE),
            'monitorLogs' => $monitorLogs
        ]);
    }
}
