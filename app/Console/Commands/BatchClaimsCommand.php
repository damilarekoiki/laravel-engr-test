<?php

namespace App\Console\Commands;

use App\Contracts\ClaimInterface;
use Illuminate\Console\Command;

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
        $this->claimRepository->batchClaims();

    }
}
