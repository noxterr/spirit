<?php

declare(strict_types=1);

// This will probably be deprecated for \Noxterr\Spirit\Base for authorization services

namespace Noxterr\Spirit\Console\Commands;

use Illuminate\Console\Command;

final class SpiritInitializerCommand extends Command
{
    protected $signature = "spirit:init";

    protected $description = "Setup the migrations needed for the Spirit package";

    protected $type = 'B2 Storage migration';

    public function handle()
    {
        $this->info("Setting up the migrations for the Spirit package...");

        $this->call('vendor:publish', [
            '--provider' => 'Noxterr\Spirit\SpiritServiceProvider',
            '--tag' => 'migrations',
        ]);

        $this->info("Migrations setup successfully!");
    }
}