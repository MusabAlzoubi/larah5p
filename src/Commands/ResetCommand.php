<?php

namespace LaraH5P\Commands;

use Illuminate\Console\Command;

class ResetCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'larah5p:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resets LaraH5P tables and data to default state.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->line('');
        $this->info('LaraH5P: Resetting all H5P data...');
        
        $this->call('migrate:refresh', [
            '--path' => 'database/migrations/',
            '--force' => true,
        ]);
        
        $this->info('LaraH5P: Reset complete!');
    }
}
