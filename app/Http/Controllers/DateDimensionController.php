<?php

namespace App\Http\Controllers;

use App\DateDimension;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class DateDimensionController extends Controller
{
    public function populateDateDimension()
    {
        // Truncate all records
        DateDimension::truncate();

        // Create an empty array and save the transformed input to array
        $dataToInsert = [];

        // Get the date range
        // @NOTE - update the start and end date as per your choice
        $dates = CarbonPeriod::create('2020-01-01', '2040-12-31');

        // For each dates create a transformed data
        foreach ($dates as $date) {

            // Get the quarter details, as ABC has a different quarter system
            // @note - Carbon does not allow to override the quarters
            $quarterDetails = $this->getQuarterDetails($date);

            // Main transformer
            $dataToInsert[] = [
                'date' => $date->format('Y-m-d'),
                'day' => $date->day,
                'month' => $date->month,
                'year' => $date->year,
                'day_name' => $date->dayName,
                'day_suffix' => $this->getDaySuffix($date->day),
                'day_of_week' => $date->dayOfWeek,
                'day_of_year' => $date->dayOfYear,
                'is_weekend' => (int) $date->isWeekend(),
                'week' => $date->week,
                'week_of_month' => $date->weekOfMonth,
                'week_of_year' => $date->weekOfYear,
                'month_name' => strtoupper(substr($date->monthName, 0,1)).substr($date->monthName, 1),
                'month_year' => $date->format('mY'),
                'month_name_year' => strtoupper(substr($date->monthName, 0,1)).substr($date->monthName, 1,2).'-'.$date->year,
                'quarter' => $quarterDetails['value'],
                'quarter_name' => $quarterDetails['name'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Create chunks for faster insertion
        // @note - SQL Server supports a maximum of 2100 parameters.
        $chunks = collect($dataToInsert)->chunk(50);

        // Using chunks insert the data
        foreach ($chunks as $chunk) {
            DateDimension::insert($chunk->toArray());
        }
    }

    public function getMonthsOfYear($year)
    {
        $months = DateDimension::where('year', $year)->distinct()->get(['month', 'month_name']);

        return $months;
    }

    public function getWeeksOfMonthsOfYear($month, $year)
    {
        $weeks = DateDimension::where('year', $year)
            ->where('month', $month)
            ->distinct()->get(['week']);

        return $weeks;
    }

    public function getYearsOfSystem()
    {
        $years = DateDimension::distinct()->get(['year']);
        return $years;
    }

    /**
     * Get Quarter details
     * @OTE - Depending on your companies quarter update the map and logic below
     *
     * @param Carbon $date
     * @return array
     */
    private function getQuarterDetails(Carbon $date)
    {
        $quarterMonthMap = [
            1 => ['value' => 1, 'name' => 'Trim-1'],
            2 => ['value' => 1, 'name' => 'Trim-1'],
            3 => ['value' => 1, 'name' => 'Trim-1'],
            4 => ['value' => 2, 'name' => 'Trim-2'],
            5 => ['value' => 2, 'name' => 'Trim-2'],
            6 => ['value' => 2, 'name' => 'Trim-2'],
            7 => ['value' => 3, 'name' => 'Trim-3'],
            8 => ['value' => 3, 'name' => 'Trim-3'],
            9 => ['value' => 3, 'name' => 'Trim-3'],
            10 => ['value' => 4, 'name' => 'Trim-4'],
            11 => ['value' => 4, 'name' => 'Trim-4'],
            12 => ['value' => 4, 'name' => 'Trim-4'],
        ];

        $output['value'] = $quarterMonthMap[$date->month]['value'];
        $output['name'] = $quarterMonthMap[$date->month]['name'];

        return $output;
    }

    /**
     * Get the Day Suffix
     * Copied logic from - https://www.mssqltips.com/sqlservertip/4054/creating-a-date-dimension-or-calendar-table-in-sql-server/
     *
     * @param $day
     * @return string
     */
    private function getDaySuffix($day)
    {
        if ($day/10 == 1) {
            return "th";
        }
        $right = substr($day, -1);

        if ($right == 1) {
            return 'st';
        }

        if ($right == 2) {
            return 'nd';
        }

        if ($right == 3) {
            return 'rd';
        }

        return 'th';
    }
}
