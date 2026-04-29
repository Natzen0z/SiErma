<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Risk;
use App\Models\Mitigation;

class RiskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = file_get_contents(database_path('data/risks.json'));
        $data = json_decode($json, true);

        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        DB::table('mitigations')->truncate();
        DB::table('risks')->truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        foreach ($data as $riskData) {
            $mitigations = $riskData['mitigations'] ?? [];
            unset($riskData['mitigations']);
            // Remove appended attributes that don't belong in insert
            unset($riskData['awal_skor'], $riskData['awal_level'], $riskData['residual_skor'], $riskData['residual_level']);
            
            // Format arrays back to json strings if needed
            if (isset($riskData['shared_with']) && is_array($riskData['shared_with'])) {
                $riskData['shared_with'] = json_encode($riskData['shared_with']);
            }

            $riskId = DB::table('risks')->insertGetId($riskData);

            foreach ($mitigations as $mitigation) {
                // Ensure risk_id is correctly mapped
                $mitigation['risk_id'] = $riskId;
                DB::table('mitigations')->insert($mitigation);
            }
        }
    }
}
