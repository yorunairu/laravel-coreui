<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;

class AddPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-permissions';
    //menjalankan commands php artisan app:add-permissions

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $permissions = [
            'sales' => ['sales.index', 'sales.create', 'sales.store'],
            'procurement' => ['procurement.index'],
            'finance' => ['finance.index'],
            'Cusprins' => ['customer.index', 'principle.index']
            // 'group_name' => 'sales',
            // 'permissions' => [
            //     'sales.index',

            // ]
        ];

        foreach ($permissions as $groupName => $permissionNames) {
            foreach ($permissionNames as $permissionName) {
                $permission = Permission::updateOrCreate(
                    ['name' => $permissionName],
                    ['group_name' => $groupName]
                );
            }
        }
        // Permission::create(['name' => 'sales.index']);
        $this->info('permission added succesfully');

        return 0;
    }
}
