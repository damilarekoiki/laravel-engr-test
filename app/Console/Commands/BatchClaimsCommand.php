<?php

namespace App\Console\Commands;

use App\Contracts\ClaimInterface;
use App\Notifications\SendBatchClaimsNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class BatchClaimsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'batch:claims';

    private ClaimInterface $claimRepository;

    public function __construct(ClaimInterface $claimRepository)
    {
        parent::__construct();
        $this->claimRepository = $claimRepository;
    }

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Batch all the claims to be processed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $this->info('Batching...');

        $batchedClaims = $this->claimRepository->batchClaims();

        $this->info('Batching successful');


        $notified_emails = [];
        foreach($batchedClaims as $batchedClaim) {
            if(in_array($batchedClaim['insurer_email'], $notified_emails)) {
                continue;
            }
            array_push($notified_emails, $batchedClaim['insurer_email']);
            Notification::route('mail', $batchedClaim['insurer_email'])->notify(new SendBatchClaimsNotification());
        }
    }
}
