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

class ImportKaryawan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pktkpi:importkaryawan {filename}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data karyawan from Csv File';

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
            if (\Illuminate\Support\Facades\DB::getDriverName() != 'sqlite' && \Illuminate\Support\Facades\DB::getDriverName() != 'sqlsrv') {
                \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            }
            Karyawan::truncate();
            OrganizationalAssignment::truncate();
            UnitKerja::truncate();
            User::truncate();
            MasterPosition::truncate();
            UserPrivilege::truncate();

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
                // Karyawan
                $karyawan = new Karyawan();
                $karyawan->NPK = $data['Personnel No.'];
                $karyawan->NamaKaryawan = $data['Personnel Number'];
                $karyawan->Email = null;
                $karyawan->save();

                // unit kerja
                $unitkerja = UnitKerja::firstOrCreate(
                    ['CostCenter' => $data['Kode Unit Kerja'], 'Deskripsi' => $data['Unit Kerja'], 'Aktif' => 1],
                    ['CostCenter' => $data['Kode Unit Kerja'], 'Deskripsi' => $data['Unit Kerja'], 'Aktif' => 1]
                );

                // position
                $position = MasterPosition::create(
                    [
                        'PositionID' => $data['PositionID'],
                        'PositionTitle' => $data['Position Name'],
                        'KodeUnitKerja' => $data['Kode Unit Kerja'],
                        'PositionAbbreviation' => $data['Pos. Abbreviation']
                    ]
                );

                // organization
                $organization = OrganizationalAssignment::firstOrCreate(
                    ['NPK' => $data['Personnel No.'], 'Grade' => $data['Grade'], 'PositionID' => $position->PositionID],
                    ['NPK' => $data['Personnel No.'], 'Grade' => $data['Grade'], 'PositionID' => $position->PositionID]
                );

                // user
                $user = User::firstOrCreate(
                    ['NPK' => $data['Personnel No.'], 'username' => $data['Personnel No.']],
                    ['NPK' => $data['Personnel No.'], 'username' => $data['Personnel No.'], 'password' => bcrypt('123456'), 'IDRole' => 3]
                );

                // user privileges
                UserPrivilege::create(['IDUser' => $user->ID, 'IDRole' => 3]);
            }
            DB::commit();
            if (\Illuminate\Support\Facades\DB::getDriverName() != 'sqlite' && \Illuminate\Support\Facades\DB::getDriverName() != 'sqlsrv') {
                \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            }
        } catch (\Exception $e) {
            DB::rollback();
            $this->error($e->getMessage());
        }
    }
}
