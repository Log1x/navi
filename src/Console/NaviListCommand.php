<?php

namespace Log1x\Navi\Console;

use Illuminate\Console\Command;

class NaviListCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'navi:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List registered navigation menus';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $registered = collect(get_registered_nav_menus());
        $locations = collect(get_nav_menu_locations());
        $menus = collect(wp_get_nav_menus());

        $rows = $registered
            ->map(fn ($label, $value) => $menus->firstWhere('term_id', $locations->get($value)))
            ->map(fn ($menu, $location) => collect([
                $location,
                $menu?->name ?? 'Unassigned',
                $menu?->count ?? 0,
            ])->map(fn ($value) => $menu?->name ? $value : "<fg=red>{$value}</>"));

        $this->table([
            '<fg=blue>Location</>',
            '<fg=blue>Assigned Menu</>',
            '<fg=blue>Menu Items</>',
        ], $rows, tableStyle: 'box');
    }
}
