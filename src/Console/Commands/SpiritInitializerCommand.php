<?php

declare(strict_types=1);

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

        // TODO: Got to understand if this is the right way to publish the migrations, because I feel like this is already done
        $this->call('vendor:publish', [
            '--provider' => 'Noxterr\Spirit\SpiritServiceProvider',
            '--tag' => 'migrations',
        ]);

        $this->info("Migrations setup successfully!");
    }
}