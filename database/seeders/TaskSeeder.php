<?php

namespace Database\Seeders;

use App\Models\Task;
use Database\Seeders\Traits\DisableForeignKeys;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    use TruncateTable, DisableForeignKeys;
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        $this->disableForeignKeys();
        $this->truncate('tasks');
        $tasks = Task::factory(10)->create();
        $this->enableForeignKeys();
    }
}
