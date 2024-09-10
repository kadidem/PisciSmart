<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cycle;
use Carbon\Carbon;
use App\Notifications\CycleEndingNotification;

class CheckCycleEndDate extends Command
{
    protected $signature = 'cycle:check-end-date';
    protected $description = 'Vérifie les cycles dont la date de fin approche';

    public function handle()
    {
        // Vérifier les cycles dont la date de fin est dans les 7 prochains jours
        $cycles = Cycle::where('DateFin', '<=', Carbon::now()->addDays(7))
                        ->where('DateFin', '>=', Carbon::now())
                        ->get();

        // Pour chaque cycle, envoyer une notification
        foreach ($cycles as $cycle) {
            $user = $cycle->pisciculteur; // Assumant que le modèle cycle est lié au pisciculteur
            $user->notify(new CycleEndingNotification($cycle));
        }

        $this->info('Vérification des cycles terminée.');
    }
}

