<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;  
use App\Domain\karyawan\Entities\KaryawanLeader;
use App\Domain\karyawan\Entities\Karyawan;

class SinkronDummyKaryawan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pktkpi:sinkron_dummy_karyawan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Syncron dummy karyawan';

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

            $data = \DB::table("karyawanDummyUser")->get();
            

        
            $array = [];
            foreach ($data as $value) {
                $karyawan = \DB::table("View_karyawan")->where("NPK", $value->PERSONNEL_NUMBER)->first(); 
                if(!$karyawan){
                    $y["NPK"] = $value->PERSONNEL_NUMBER;
                    $y["NamaKaryawan"] = $value->PERSONNEL_NUMBER;
                    $y["Email"] = NULL;
                    $y["Subscribe"] = 1;  
                    $array[] = $y; 
                }
            } 

            karyawan::insert($array);  
            
            $this->info('Syncron karyawan dummy berhasil');
        } catch (Exception $e) {
            $this->info('Syncron karyawan dummy tidak berhasil '. $e->getMessage());
        }
    }
}
