<?php

namespace App\Console\Commands;

use DB;
use App\Models\Car;
use App\Libraries\GmFinancial;
use Illuminate\Console\Command;
use Symfony\Component\Console\Output\ConsoleOutput;

class UpdateGMFCatalog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gmf:sync {carId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync GMF catalog';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $gmf = new GmFinancial();
        $cli = new ConsoleOutput();

        // Clear car packages table
        DB::table('gmf_car_packages')->truncate();

        $cars = $this->argument('carId') ? Car::whereId($this->argument('carId'))->get() : Car::get();

        $cars->each(function($car) use ($gmf, $cli) {
            if ($quote = $gmf->getQuote($car, 10, 60)) {
                $cli->writeln("SUCCESS: {$car->marca} {$car->modelo} {$car->ano} {$car->tipo}");
            } else {
                $cli->writeln("FAIL: {$car->marca} {$car->modelo} {$car->ano} {$car->tipo}");
            }
        });

        return 0;
    }
}
