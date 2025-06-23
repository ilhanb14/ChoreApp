# ChoreBusters

Welcome to **ChoreBusters**, a Laravel-powered web application. Make a family and add members to easily assign and view your chores. Earn points and exchange them for your own rewards!

---

## running the app

### 1. Clone the repository
```bash
git clone https://github.com/ilhanb14/ChoreApp.git
cd ChoreApp
```

### 2. Install the dependencies
```bash
composer install
npm install
```

### 3. Copy and configure the environment-variables
```bash
cp .env.example .env
```
Update .env with your database and mail credentials.

### 4. Generate an application key
```bash
php artisan key:generate
```

### 5. Migrate and seed the database
```bash
php artisan migrate --seed
```

### 6. Run the app
```bash
composer run dev
```