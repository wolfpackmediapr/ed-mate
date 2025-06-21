# Edumate Learning Management System

A modern Learning Management System built with PHP CodeIgniter and Supabase.

## Prerequisites

- PHP 7.4 or higher
- Node.js 14 or higher
- Composer
- Supabase account and project

## Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd edumate-up
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install frontend dependencies**
   ```bash
   npm install
   cd assets && npm install
   ```

4. **Configure Supabase**
   - Create a Supabase project
   - Update your Supabase configuration in `application/config/supabase.php`
   - Import the database schema from `supabase_schema.sql`

## Development

### Start the development server
```bash
npm run dev
```

This will:
- Start the PHP development server on `http://localhost:8000`
- Watch and compile SASS files to CSS
- Automatically reload when you make changes

### Available Scripts

- `npm run dev` - Start both PHP server and SASS watcher
- `npm run dev:assets` - Watch and compile SASS files only
- `npm run dev:server` - Start PHP development server only
- `npm run build` - Build production CSS (compressed)
- `npm run build:watch` - Watch and build compressed CSS

### Frontend Development

The project uses SASS for styling. The main SASS file is located at:
- `assets/sass/main.scss`

Compiled CSS is output to:
- `assets/css/main.css`

### Project Structure

```
├── application/          # CodeIgniter application files
├── assets/              # Frontend assets
│   ├── sass/           # SASS source files
│   ├── css/            # Compiled CSS
│   ├── js/             # JavaScript files
│   └── images/         # Images
├── system/             # CodeIgniter system files
├── supabase/           # Supabase configuration
└── vendor/             # Composer dependencies
```

## Features

- User authentication with Supabase
- Course management
- Lesson creation and management
- File uploads to Supabase storage
- Modern responsive UI
- Real-time updates

## License

ISC 