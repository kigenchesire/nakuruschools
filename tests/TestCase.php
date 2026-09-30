<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    // Applied here (not per test class) so the override below takes precedence over the trait.
    use RefreshDatabase;

    /**
     * Only run the framework and 2026 CMS migrations. The superseded 2024_*
     * migrations from the earlier build conflict with this schema; once they are
     * deleted from database/migrations this override can be removed.
     */
    protected function migrateFreshUsing()
    {
        $paths = array_merge(
            glob(database_path('migrations/0001_*.php')),
            glob(database_path('migrations/2026_*.php')),
        );

        return [
            '--drop-views' => false,
            '--drop-types' => false,
            '--seed' => false,
            '--path' => $paths,
            '--realpath' => true,
        ];
    }
}
