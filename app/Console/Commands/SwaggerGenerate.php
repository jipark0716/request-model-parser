<?php

namespace App\Console\Commands;

use L5Swagger\Console\GenerateDocsCommand;

class SwaggerGenerate extends GenerateDocsCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jipark-swagger:generate {documentation?} {--all}';
}
