<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            DB::beginTransaction();

            $faker = Factory::create();

            for ($i = 0; $i < 10; $i++) {
                $user = new User();
                $user->name = $faker->name;
                $user->email = 'monowar+' . $i . '@executorsinc.com';
                $user->mobile_no = '+880191400000' . $i;
                $user->password = bcrypt('Password100@');
                $user->pin = bcrypt('1234');
                $user->email_verified_at = Carbon::now();
                $user->save();

                $wallet = new Wallet();
                $wallet->user_id = $user->id;
                $wallet->wallet_ac_no = "PWA10000" . $i;
                $wallet->currency_id = random_int(1, 168);
                $wallet->balance = random_int(100000, 1000000);
                $wallet->status = 1;
                $wallet->save();
            }

            DB::commit();

        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
        }
    }
}

