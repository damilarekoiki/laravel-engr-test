


## Setup

Same way you would install a typical laravel application.

    composer install

    npm install

    npm run dev

    php artisan migrate

    php artisan db:seed

    php artisan serve

The UI is displayed on the root page

## Extra Notes

The BatchClaimsCommand is the command that is meant to run daily to create claim batches

Run `php artisan migrate` to create all the necessary tables in the database

Run `php artisan db:seed` to seed all the data into the database

Run `php artisan batch:claims` to batch all the claims

## Batching Algorithm


1. Fetch claims that have not been processed, sorted by total amount, priority level, cost of the specialty
2. Loop through the claims
3. Claims that belong to the same insurer are sorted by preferred batching date
4. Before a claim is added to batch, the insurer's daily processing capacity and maximum batch is checked
5. The daily processing capacity and maximum batch determine whether the claim will be added to batch or batch of next day
6. If insurer's number of batch already equals insurer's maximum_batch_size in db, we skip
7. If insurer's daily processing capacity is exhausted, we push claim to batch of next day
8. Once we generate the batches, we truncate the batched_claims table and insert the newb batch into it


## Mail

The email should appear in laravel.log file in your development environment