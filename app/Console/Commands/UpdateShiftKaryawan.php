<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateShiftKaryawan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pktkpi:updateshift {filename}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update data shift karyawan';

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
        $file = $this->argument('filename').'.csv';
        try {
            $rows = array_map('str_getcsv', file(storage_path('app/public/'.$file)));
            $header = array_shift($rows);
            $csv = array();
            foreach ($rows as $row) {
                $csv[] = array_combine($header, $row);
            }

            DB::beginTransaction();
            foreach ($csv as $data) {
                DB::table('View_OrganizationalAssignment')->where('NPK', $data['NPK'])->update(['Shift' => $data['Shift']]);
            }
            DB::commit();
            $this->info('Data shift updated.');
        } catch (\Exception $e) {
            DB::rollback();
            $this->error($e->getMessage());
        }
    }
}
