<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Domain\karyawan\Entities\MasterPositionLeader;
use App\Domain\karyawan\Entities\MasterPosition;

class SinkronMasterPosition extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pktkpi:sinkron_master_position';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data master position from leader';

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
     * @return mixed
     */
    public function handle()
    {
        try {
            MasterPosition::truncate();
        
            $array = [];
            MasterPositionLeader::chunk(100, function($query) use ($array){
                foreach ($query as $value) {
                    $y["PositionID"] = $value->PositionID;
                    $y["PositionTitle"] = $value->PositionTitle;
                    $y["PositionAbbreviation"] = $value->PositionAbbreviation;
                    $y["KodeUnitKerja"] = $value->KodeUnitKerja; 
                    $y["StatusAktif"] = $value->StatusAktif; 
                    $array[] = $y; 
                }  

                MasterPosition::insert($array); 
            }); 
            $this->info('Syncron master posisi berhasil');
        } catch (Exception $e) {
            $this->info('Syncron master posisi tidak berhasil '. $e->getMessage());
        }
    }
}
