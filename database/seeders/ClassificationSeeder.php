<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Classification;
class ClassificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

            Classification::create(
            [
                'name'=>'Medication',
            ]);
            Classification::create(
            [
                'name'=>'Babies',
            ]);
            Classification::create(
            [
                'name'=>'Medical Devices',
            ]);
            Classification::create(
            [
                'name'=>'Teeth',
            ]);
            Classification::create(
            [
                'name'=>'Skin care',
            ]);
            Classification::create(
            [
                'name'=>'Vitamins',
            ]);
            Classification::create(
            [
                'name'=>'Painkillers',
            ]);
            Classification::create(
            [
            'name'=>'infalammation',
            ]);
        //
    }
}
