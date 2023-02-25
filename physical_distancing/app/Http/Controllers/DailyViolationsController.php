<?php

namespace App\Http\Controllers;

use App\Models\DailyViolations;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DailyViolationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $arr_res = array();
        if(!$request->data_input):
            $dt_now = now()->format('Y-m-d');
            for ($i=6; $i >= 0; $i--) { 
                if($i){
                    $var_subtracted_date = date('Y-m-d', strtotime('-'.$i.' days', strtotime($dt_now)));
                }else{
                    $var_subtracted_date = date('Y-m-d');
                }
                $res = DailyViolations::whereDate('date_created', $var_subtracted_date)->select('violations', 'date_created', 'created_at')->get();
                $arr_res[$var_subtracted_date] = $res->sum('violations');
            }
        else:
            for ($i=1; $i <= 12; $i++) { 
               
               $dt = strtotime(date('Y').'-'.$i.'-1');
                $res = DailyViolations::whereDate('date_created', '>=',date('Y-m-01', $dt))->whereDate('date_created', '<=',date('Y-m-t', $dt))->select('violations', 'date_created', 'created_at')->get();
                $arr_res[date('M - Y', $dt)] = $res->sum('violations');
            }
        endif;
        return $arr_res;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DailyViolations  $dailyViolations
     * @return \Illuminate\Http\Response
     */
    public function show(DailyViolations $dailyViolations)
    {
        return $dailyViolations->get();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DailyViolations  $dailyViolations
     * @return \Illuminate\Http\Response
     */
    public function edit(DailyViolations $dailyViolations)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DailyViolations  $dailyViolations
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DailyViolations $dailyViolations)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DailyViolations  $dailyViolations
     * @return \Illuminate\Http\Response
     */
    public function destroy(DailyViolations $dailyViolations)
    {
        //
    }

    public function getMonthYearAndDefaults(Request $request)
    {
        $result_arr = array();
        $month_arr = array();
        for ($i=1; $i <= 12; $i++) { 
               $dt = strtotime(date('Y').'-'.$i.'-1');
               $month_arr[sprintf("%02d", $i)] = date('F', $dt);
        }
        $year_arr = array();
        for ($i=intval(date('Y')); $i >= intval(date('Y')) - 5; $i--) { 
               $year_arr[] = $i;
        }
        $result_arr = [
            'monthly' => $month_arr,
            'yearly' => $year_arr,
            'year_td' => date('Y'),
            'month_td' => date('m')
        ];
        return $result_arr;
    }

    public function getDailyViolations(Request $request)
    {
        $result_arr = array();
        $d= cal_days_in_month(CAL_GREGORIAN,$request->month,$request->year);
        for ($i=1; $i <= $d ; $i++) { 
            $dt = strtotime($request->year.'-'.$request->month.'-'.$i);
            $res = DailyViolations::whereDate('date_created', date('Y-m-d', $dt))->select('violations', 'date_created', 'created_at')->get();
            $result_arr[] = [
                'dt_format' => date('l d, F', $dt),
                'violation' => $res->sum('violations')
            ];
        }
        return $result_arr;
    }
}
