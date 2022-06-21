<?php

namespace App\Console\Commands;

use App\Models\OpenapiApp;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateAppCommand extends Command
{
    protected $signature = 'create:app {name}';

    protected $description = '创建openapi应用';

    public function handle(): int
    {
        $name = $this->argument('name');
        $username = Str::random();
        $password = Str::random(32);

        // 判断name是否存在
        if (OpenapiApp::where('name', $name)->first()) {
            $this->error('name[' . $name . '] already exists');
            return self::FAILURE;
        }

        // 判断key是否存在
        if (OpenapiApp::where('username', $username)->first()) {
            $this->error('username[' . $username . '] already exists');
            return self::FAILURE;
        }

        OpenapiApp::create([
            'name' => $name,
            'username' => $username,
            'password' => $password,
            'is_enabled' => true,
        ]);

        $this->table(['name', 'username', 'password'], [[$name, $username, $password]]);

        return self::SUCCESS;
    }
}
