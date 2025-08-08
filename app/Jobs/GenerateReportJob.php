<?php

namespace App\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    public $reportName;

    public function __construct($reportName)
    {
        $this->reportName = $reportName;
    }

    public function handle(): void
    {
        // Simulate heavy work
        // sleep(2);
        Log::info("Report generated: {$this->reportName}");
    }
}
