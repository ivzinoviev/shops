## Deployment
1. Configure .env
+ DB connection
+ Pusher app
+ Work only with database session driver  
2. Run migrations `php artisan migrate --seed`
3. Start webSocket server `php artisan websockets:serve`
4. Run application `php artisan run`
5. Start server `php artisan serve`
