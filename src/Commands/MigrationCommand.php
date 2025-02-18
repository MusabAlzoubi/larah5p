<?php

namespace LaraH5P\Commands;

use Illuminate\Console\Command;

class MigrationCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'larah5p:migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a migration following the LaraH5P specifications.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->line('');
        $this->info('LaraH5P Creating migration...');
        
        $this->info("Generating migration file...");
        $migrationFile = database_path("migrations/") . date('Y_m_d_His') . "_create_larah5p_tables.php";
        
        if (!file_exists($migrationFile)) {
            file_put_contents($migrationFile, $this->getMigrationStub());
            $this->info("Migration successfully created: " . $migrationFile);
        } else {
            $this->error("Migration file already exists!");
        }
    }

    /**
     * Get the migration stub content.
     *
     * @return string
     */
    protected function getMigrationStub()
    {
        return "<?php\n\nuse Illuminate\\Database\\Migrations\\Migration;\nuse Illuminate\\Database\\Schema\\Blueprint;\nuse Illuminate\\Support\\Facades\\Schema;\n\nclass CreateLaraH5PTables extends Migration\n{\n    public function up()\n    {\n        Schema::create('h5p_contents', function (Blueprint \$table) {\n            \$table->id();\n            \$table->string('title');\n            \$table->timestamps();\n        });\n    }\n\n    public function down()\n    {\n        Schema::dropIfExists('h5p_contents');\n    }\n}";
    }
}
