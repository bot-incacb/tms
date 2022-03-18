<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class RegisterCommand extends Command
{
    protected $signature = 'register {username*}';

    protected $description = '注册';

    public function handle()
    {
        $users = $this->argument('username');
        $data = [];
        foreach ($users as $user) {
            $password = Str::random();
            User::updateOrCreate([
                'username' => $user,
            ], [
                'password' => $password,
            ]);
            $data[] = [$user, $password];
        }

        $this->table(['用户名', '密码'], $data);
    }
}
