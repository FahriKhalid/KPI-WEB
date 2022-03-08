<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Domain\Karyawan\Entities\Karyawan;
use App\Domain\Karyawan\Entities\MasterPosition;
use App\Domain\Karyawan\Entities\OrganizationalAssignment;
use App\Domain\KPI\Entities\UnitKerja;
use App\Domain\User\Entities\User;
use App\Domain\User\Entities\UserPrivilege;

class UpdateKaryawan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pktkpi:updatekaryawan {filename}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update data karyawan from Csv File';

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
            if (DB::getDriverName() != 'sqlite' && DB::getDriverName() != 'sqlsrv') {
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            }
            /**
             * indexing
             * 0: NPK
             * 1: Personel Number
             * 2: PositionID
             * 3: Position Name
             * 4: Grade
             * 5: Kode Unit Kerja
             * 6: Unit Kerja
             * 7: Pos Abbreviation
             */
            foreach ($csv as $data) {
                // karyawan
                Karyawan::updateOrCreate(['NPK' => $data['Personnel No.']], ['NamaKaryawan' => $data['Personnel Number'], 'email' => null]);

                // unit kerja
                UnitKerja::updateOrCreate(['CostCenter' => $data['Kode Unit Kerja']], ['Deskripsi' => $data['Unit Kerja'], 'Aktif' => 1]);

                // position
                $position = MasterPosition::updateOrCreate(
                    ['PositionID' => $data['PositionID']],
                    ['PositionTitle' => $data['Position Name'], 'KodeUnitKerja' => $data['Kode Unit Kerja'], 'PositionAbbreviation' => $data['Pos. Abbreviation']]
                );

                // organization
                OrganizationalAssignment::updateOrCreate(
                    ['NPK' => $data['Personnel No.']],
                    ['Grade' => $data['Grade'], 'PositionID' => $position->PositionID]
                );
            }

            DB::commit();
            if (DB::getDriverName() != 'sqlite' && DB::getDriverName() != 'sqlsrv') {
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            }
            $this->info('Data updated.');
        } catch (\Exception $e) {
            DB::rollback();
            $this->error($e->getMessage());
        }
    }
}
