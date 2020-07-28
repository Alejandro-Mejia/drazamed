<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class XdebugCommand extends Command
{
    protected $signature = 'xdebug:test';

    protected $description = 'Testing Xdebug';

    public function handle()
    {
        $this->doSomething('Testing!');
    }

    public function doSomething($string)
    {
        dd($string);
    }
}
