<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeHelperCommand extends Command
{
    /**
     * Nama command artisan
     */
    protected $signature = 'make:helper {name : Nama helper, contoh: PostCacheHelper}';

    /**
     * Deskripsi command
     */
    protected $description = 'Membuat file helper di app/Helpers';

    /**
     * Jalankan command
     */
    public function handle()
    {
        $name = Str::studly($this->argument('name'));
        $path = app_path("Helpers/{$name}.php");

        if (file_exists($path)) {
            $this->error("Helper {$name} sudah ada!");
            return Command::FAILURE;
        }

        // Pastikan folder Helpers ada
        if (!is_dir(app_path('Helpers'))) {
            mkdir(app_path('Helpers'), 0755, true);
        }

        $stub = <<<PHP
<?php

namespace App\Helpers;

class {$name}
{
    //
}
PHP;

        file_put_contents($path, $stub);

        $this->info("Helper {$name} berhasil dibuat di: app/Helpers/{$name}.php");

        return Command::SUCCESS;
    }
}
