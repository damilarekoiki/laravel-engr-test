


## Setup

Same way you would install a typical laravel application.

    composer install

    npm install

    npm run dev

    php artisan serve

The UI is displayed on the root page

## Extra Notes

The BatchClaimsCommand is the command that is meant to run daily to create claim batches

## Batching Algorithm


1. Fetch claims that have not been processed, sorted by total amount, priority level, cost of the specialty
2. Loop through the claims
3. Claims that belong to the same insurer are sorted by preferred batching date
4. Before a claim is added to batch, the insurer's daily processing capacity and maximum batch is checked
5. The daily processing capacity and maximum batch determine whether the claim will be added to batch or batch of next day



