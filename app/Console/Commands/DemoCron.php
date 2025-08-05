<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\User;

class DemoCron extends Command
{
    protected $signature = 'demo:cron';
    protected $description = 'Fetch and store users from an API';

    public function handle()
    {
        info("Cron Job running at " . now());
        $response = Http::get('https://jsonplaceholder.typicode.com/users');
        $users = $response->json();

        if (!empty($users)) {
            foreach ($users as $user) {
                if (!User::where('email', $user['email'])->exists()) {
                    User::create([
                        'name' => $user['name'],
                        'email' => $user['email'],
                        'password' => bcrypt('123456789')
                    ]);
                }
            }
        }
        $this->info('Demo Cron Job executed successfully!');
    }
}