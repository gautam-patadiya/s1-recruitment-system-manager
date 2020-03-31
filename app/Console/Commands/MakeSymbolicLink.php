<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeSymbolicLink extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ats:make-symbolic-link';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Used to create symbolic links for vue js files';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $cLink = public_path('public');
        File::delete($cLink);
        $cTarget = base_path('front/public');
        symlink($cTarget, $cLink);
    }
}
