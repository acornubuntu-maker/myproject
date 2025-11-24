<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Link;

class LinksSeeder extends Seeder
{
    public function run()
    {
        Link::updateOrCreate(['url' => 'https://laravel.com'], [
            'title' => 'Laravel',
            'description' => 'Laravel framework',
        ]);

        Link::updateOrCreate(['url' => 'https://example.com'], [
            'title' => 'Example',
            'description' => 'Example site',
        ]);
    }
}