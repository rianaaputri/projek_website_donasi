<?php
namespace Database\Seeders;

use App\Models\Campaign;
use Illuminate\Database\Seeder;

class CampaignSeeder extends Seeder
{
    public function run()
    {
        $campaigns = [
            [
                'title' => 'Bantu Korban Banjir',
                'description' => 'Mari bersama-sama membantu korban banjir yang kehilangan tempat tinggal dan membutuhkan bantuan segera.',
                'category' => 'Bencana Alam',
                'target_amount' => 10000000,
                'collected_amount' => 4500000,
                'image' => null,
                'status' => 'active'
            ],
            [
                'title' => 'Beasiswa Anak Yatim',
                'description' => 'Program beasiswa untuk anak-anak yatim yang berprestasi namun kekurangan biaya pendidikan.',
                'category' => 'Pendidikan',
                'target_amount' => 5000000,
                'collected_amount' => 3200000,
                'image' => null,
                'status' => 'active'
            ],
            [
                'title' => 'Bantu Pengobatan Untuk Siti',
                'description' => 'Siti membutuhkan bantuan biaya operasi jantung yang sangat mendesak.',
                'category' => 'Kesehatan',
                'target_amount' => 15000000,
                'collected_amount' => 7600000,
                'image' => null,
                'status' => 'active'
            ]
        ];
        
        foreach ($campaigns as $campaign) {
            Campaign::create($campaign);
        }
    }
}