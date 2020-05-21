<?php

use App\Certification;
use Illuminate\Database\Seeder;

class CertificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Certification::class)->create([
            'company' => 'Microsoft',
            'banner_image' => 'certifications/microsoft.png',
        ]);

        factory(Certification::class)->create([
            'company' => 'Fortinet',
            'banner_image' => 'certifications/Fortinet.png',
        ]);

        factory(Certification::class)->create([
            'company' => 'Juniper Networks',
            'banner_image' => 'certifications/Juniper_Networks.png',
        ]);

        factory(Certification::class)->create([
            'company' => 'Paloalto Networks',
            'banner_image' => 'certifications/paloaltonetworks.png',
        ]);

        factory(Certification::class)->create([
            'company' => 'Cisco',
            'banner_image' => 'certifications/cisco.png',
        ]);

        factory(Certification::class)->create([
            'company' => 'F5 Networks',
            'banner_image' => 'certifications/F5_Networks.png',
        ]);
    }
}
