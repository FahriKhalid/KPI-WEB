<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;  
use App\Domain\karyawan\Entities\KaryawanLeader;
use App\Domain\karyawan\Entities\Karyawan;

class SinkronKaryawan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pktkpi:sinkron_karyawan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Syncron karyawan from leader';

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
            karyawan::truncate();
        
            $array = [];
            karyawanLeader::chunk(100, function($query) use ($array){
                foreach ($query as $value) {
                    if($value->PERSONNEL_NUMBER != null){
                        $y["NPK"] = $value->PERSONNEL_NUMBER;
                        $y["NamaKaryawan"] = $value->NAME;
                        $y["Email"] = NULL;
                        $y["Subscribe"] = 1;  
                        $array[] = $y; 
                    }  
                } 

                karyawan::insert($array); 
            }); 
            
            $this->info('Syncron karyawan berhasil');
        } catch (Exception $e) {
            $this->info('Syncron karyawan tidak berhasil '. $e->getMessage());
        }
    }
}
