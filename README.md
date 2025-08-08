# Project Setup Instructions

1. **Create Laravel Project**
   ```
   composer create-project laravel/laravel exam-management-system
   ```

2. **Create Model, Migration, and Controller**
   ```
   php artisan make:model Student -mc
   php artisan make:model Room -mc
   php artisan make:model SeatAllocation -mc
   ```

3. **Create Seeders for Room and Student Data**
   ```
   php artisan make:seeder RoomSeeder
   php artisan make:seeder StudentSeeder
   ```

4. **Import All Data into Database**
   - Run migrations:
     ```
     php artisan migrate
     ```
   - Seed the database:
     ```
     php artisan db:seed --class=RoomSeeder
     php artisan db:seed --class=StudentSeeder
     ```

5. **API Endpoints to Check Data**
   - `GET /api/students` - Retrieve all students
   - `GET /api/rooms` - Retrieve all rooms

6. **Run Project**
    ```
    php artisan serve
    ```

7. **Main Page Url to Allocation Seat**
   - Visit: `http://127.0.0.1:8000/seatAllocation` 

