<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FixPatientJsonFields extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'patients:fix-json-fields';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix allergies, chronic_diseases, medications_used fields to be plain array of strings';

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
     * @return int
     */
    public function handle()
    {
        $this->info('Düzeltme başlatıldı...');
        $count = 0;
        foreach (Patient::all() as $patient) {
            $updated = false;
            foreach ([
                'allergies',
                'chronic_diseases',
                'medications_used',
            ] as $field) {
                $data = $patient->$field;
                if (is_string($data)) {
                    $decoded = json_decode($data, true);
                    $data = $decoded !== null ? $decoded : $data;
                }
                if (is_array($data) && isset($data[0]['value'])) {
                    $data = array_map(function($item) {
                        return is_array($item) && isset($item['value']) ? $item['value'] : $item;
                    }, $data);
                    $patient->$field = $data;
                    $updated = true;
                }
            }
            if ($updated) {
                $patient->save();
                $count++;
            }
        }
        $this->info("$count hasta kaydı düzeltildi.");
        return 0;
    }
}
