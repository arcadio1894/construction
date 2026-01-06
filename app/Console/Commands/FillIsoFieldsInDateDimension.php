<?php

namespace App\Console\Commands;

use App\DateDimension;
use Carbon\Carbon;
use Illuminate\Console\Command;

class FillIsoFieldsInDateDimension extends Command
{
    protected $signature = 'date-dimension:fill-iso {--from=} {--to=} {--chunk=2000}';
    protected $description = 'Rellena iso_year, iso_week e iso_day_of_week en date_dimensions usando la fecha';

    public function handle()
    {
        $from  = $this->option('from');
        $to    = $this->option('to');
        $chunk = (int) $this->option('chunk');

        $q = DateDimension::query()->orderBy('date', 'asc');

        if ($from) $q->where('date', '>=', $from);
        if ($to)   $q->where('date', '<=', $to);

        $bar = $this->output->createProgressBar($q->count());
        $bar->start();

        $q->chunkById($chunk, function ($rows) use ($bar) {
            foreach ($rows as $row) {
                $c = Carbon::parse($row->date); // si tu date ya es date y casteado, igual funciona

                $row->iso_year = $c->isoWeekYear;   // año ISO
                $row->iso_week = $c->isoWeek;       // semana ISO (1..53)
                $row->iso_day_of_week = $c->isoWeekday; // 1..7 (lun..dom)

                $row->save();
                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine();
        $this->info('OK: iso_year/iso_week/iso_day_of_week rellenados.');
        return 0;
    }
}
