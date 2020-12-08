<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{

    public function Run()
    {
        DB::table('FavoList')->truncate();
        DB::table('Clothes')->truncate();
        DB::table('users')->truncate();

        DB::table('users'->insert([
            'lastname' => "東横",
            'firstname' => "太郎",
            'email' => '1045@gmail.com',
            'VerifyPassword' => bcrypt('1045')
        ])) ;
        DB::table('users'->insert([
            'lastname' => "東横",
            'firstname' => "次郎",
            'email' => '1046@gmail.com',
            'VerifyPassword' => bcrypt('1045')
        ])) ;
        DB::table('users'->insert([
            'lastname' => "東横",
            'firstname' => "三郎",
            'email' => '1047@gmail.com',
            'VerifyPassword' => bcrypt('1045')
        ])) ;
        DB::table('users'->insert([
            'lastname' => "東横",
            'firstname' => "四郎",
            'email' => '1048@gmail.com',
            'VerifyPassword' => bcrypt('1045')
        ])) ;
        
        DB::table('Clothes'->insert([
            'ImageFile' => "11219840665f88e35ce2d153.90786069",
            'email' => '1045@gmail.com',
            'WearType' => "Top"
        ])) ;
        DB::table('Clothes'->insert([
            'ImageFile' => "12750326105f88e2b1d27894.996184259",
            'email' => '1045@gmail.com',
            'WearType' => "Top"
        ])) ;
        DB::table('Clothes'->insert([
            'ImageFile' => "1602402965f890120107193.08735220",
            'email' => '1045@gmail.com',
            'WearType' => "Top"
        ])) ;
        DB::table('Clothes'->insert([
            'ImageFile' => "18030105225f88e372151ed2.03156474",
            'email' => '1045@gmail.com',
            'WearType' => "Top"
        ])) ;
        DB::table('Clothes'->insert([
            'ImageFile' => "9543817415fb5eddba07477.13330793",
            'email' => '1045@gmail.com',
            'WearType' => "Top"
        ])) ;
        DB::table('Clothes'->insert([
            'ImageFile' => "1828725615f8904f5dc3f37.55858831",
            'email' => '1045@gmail.com',
            'WearType' => "Bottom"
        ])) ;
        DB::table('Clothes'->insert([
            'ImageFile' => "20018706055f89005a87ab11.12576120",
            'email' => '1045@gmail.com',
            'WearType' => "Bottom"
        ])) ;
        DB::table('Clothes'->insert([
            'ImageFile' => "6781987215f890380683ea2.50164434",
            'email' => '1045@gmail.com',
            'WearType' => "Bottom"
        ])) ;
        DB::table('Clothes'->insert([
            'ImageFile' => "14294579835f8902f30e1a91.36829885",
            'email' => '1045@gmail.com',
            'WearType' => "Bottom",
        ])) ;
        DB::table('Clothes'->insert([
            'ImageFile' => "20041570685f88e33b0838a5.50538341",
            'email' => '1045@gmail.com',
            'WearType' => "Bottom"
        ])) ;
        DB::table('Clothes'->insert([
            'ImageFile' => "20165632735fb5ee9f5770b2.75176362",
            'email' => '1045@gmail.com',
            'WearType' => "Bottom"
        ])) ;
        
        DB::table('FavoList'->insert([
            'email' => '1045@gmail.com',
            'TopFile' => "14294579835f8902f30e1a91.36829885",
            'BottomFile' => "Bottom"
        ])) ;
    }
}
