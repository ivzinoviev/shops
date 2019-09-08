## Deployment (dev)
1. Configure .env
+ DB connection
+ Pusher app
+ Work only with database session driver  
2. `composer install` `yarn install`
3. Run migrations `php artisan migrate --seed`
4. Build front `yarn dev`
5. Start webSocket server `php artisan websockets:serve`
6. Run application `php artisan run`
7. Start server `php artisan serve`
