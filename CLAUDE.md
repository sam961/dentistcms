<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to enhance the user's satisfaction building Laravel applications.

## Foundational Context
This application is a Laravel-based Dental Hub with a modern, responsive design. The main Laravel ecosystems package & versions are below. You are an expert with them all. Ensure you abide by these specific packages & versions.

- php - 8.4.1
- laravel/framework (LARAVEL) - v12
- laravel/prompts (PROMPTS) - v0
- laravel/breeze (BREEZE) - v2
- laravel/pint (PINT) - v1
- laravel/sail (SAIL) - v1
- phpunit/phpunit (PHPUNIT) - v11
- tailwindcss (TAILWINDCSS) - v3
- alpinejs (ALPINEJS) - v3

## Application Overview
This is a comprehensive Dental Practice Management System with the following key features:
- **Patient Management**: Registration, profiles, contact information, medical history
- **Dentist Management**: Staff profiles, specializations, schedules
- **Appointment Scheduling**: Real-time availability checking, conflict prevention, multi-step booking
- **Treatment Catalog**: Service offerings with pricing and duration
- **Medical Records**: Visit history, diagnoses, treatments performed
- **Invoice Management**: Billing and payment tracking
- **Modern Dashboard**: Analytics, charts, and quick actions


## Conventions
- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts
- Do not create verification scripts or tinker when tests cover that functionality and prove it works. Unit and feature tests are more important.

## Application Structure & Architecture
- Stick to existing directory structure - don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Styling & Design System
- **ALWAYS use Tailwind CSS for all styling in this project.** Do not use inline styles or custom CSS unless absolutely necessary.
- Use Tailwind utility classes for all styling needs.
- Follow Tailwind CSS best practices and conventions.
- Prefer component composition with Tailwind classes over custom CSS.

### Design System Standards
- **Consistent Layout**: All pages follow the same spacing pattern - remove `py-12` wrappers and `max-w-7xl mx-auto sm:px-6 lg:px-8` containers to match dashboard layout
- **Header Design**: Use modern 3-line headers with icons, descriptions, and action buttons:
  ```html
  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
      <div>
          <h2 class="font-bold text-3xl text-gray-900 leading-tight">
              <i class="fas fa-icon text-blue-600 mr-3"></i>
              Page Title
          </h2>
          <p class="text-gray-600 mt-2">Page description</p>
      </div>
      <a href="..." class="btn-modern btn-primary inline-flex items-center">...</a>
  </div>
  ```
- **Card Styling**: Use `bg-white rounded-2xl shadow-sm` for all cards and containers
- **Success Messages**: Consistent green notification design:
  ```html
  <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4">
      <div class="flex">
          <div class="flex-shrink-0">
              <i class="fas fa-check-circle text-green-400"></i>
          </div>
          <div class="ml-3">
              <p class="text-sm text-green-700">{{ session('success') }}</p>
          </div>
      </div>
  </div>
  ```
- **Navigation**: Modern button styling with icons and consistent placement using `btn-modern` classes
- **Form Design**: Multi-step forms with progress indicators where appropriate (especially for appointment booking)

## Frontend Bundling
- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `npm run build`, `npm run dev`, or `composer run dev`. Ask them.

## Replies
- Be concise in your explanations - focus on what's important rather than explaining obvious details.

## Documentation Files
- You must only create documentation files if explicitly requested by the user.


=== boost rules ===

## Laravel Boost
- Laravel Boost is an MCP server that comes with powerful tools designed specifically for this application. Use them.

## Artisan
- Use the `list-artisan-commands` tool when you need to call an Artisan command to double check the available parameters.

## URLs
- Whenever you share a project URL with the user you should use the `get-absolute-url` tool to ensure you're using the correct scheme, domain / IP, and port.

## Tinker / Debugging
- You should use the `tinker` tool when you need to execute PHP to debug code or query Eloquent models directly.
- Use the `database-query` tool when you only need to read from the database.

## Reading Browser Logs With the `browser-logs` Tool
- You can read browser logs, errors, and exceptions using the `browser-logs` tool from Boost.
- Only recent browser logs will be useful - ignore old logs.

## Searching Documentation (Critically Important)
- Boost comes with a powerful `search-docs` tool you should use before any other approaches. This tool automatically passes a list of installed packages and their versions to the remote Boost API, so it returns only version-specific documentation specific for the user's circumstance. You should pass an array of packages to filter on if you know you need docs for particular packages.
- The 'search-docs' tool is perfect for all Laravel related packages, including Laravel, Inertia, Livewire, Filament, Tailwind, Pest, Nova, Nightwatch, etc.
- You must use this tool to search for Laravel-ecosystem documentation before falling back to other approaches.
- Search the documentation before making code changes to ensure we are taking the correct approach.
- Use multiple, broad, simple, topic based queries to start. For example: `['rate limiting', 'routing rate limiting', 'routing']`.
- Do not add package names to queries - package information is already shared. For example, use `test resource table`, not `filament 4 test resource table`.

### Available Search Syntax
- You can and should pass multiple queries at once. The most relevant results will be returned first.

1. Simple Word Searches with auto-stemming - query=authentication - finds 'authenticate' and 'auth'
2. Multiple Words (AND Logic) - query=rate limit - finds knowledge containing both "rate" AND "limit"
3. Quoted Phrases (Exact Position) - query="infinite scroll" - Words must be adjacent and in that order
4. Mixed Queries - query=middleware "rate limit" - "middleware" AND exact phrase "rate limit"
5. Multiple Queries - queries=["authentication", "middleware"] - ANY of these terms


=== php rules ===

## PHP

- Always use curly braces for control structures, even if it has one line.

### Constructors
- Use PHP 8 constructor property promotion in `__construct()`.
    - <code-snippet>public function __construct(public GitHub $github) { }</code-snippet>
- Do not allow empty `__construct()` methods with zero parameters.

### Type Declarations
- Always use explicit return type declarations for methods and functions.
- Use appropriate PHP type hints for method parameters.

<code-snippet name="Explicit Return Types and Method Params" lang="php">
protected function isAccessible(User $user, ?string $path = null): bool
{
    ...
}
</code-snippet>

## Comments
- Prefer PHPDoc blocks over comments. Never use comments within the code itself unless there is something _very_ complex going on.

## PHPDoc Blocks
- Add useful array shape type definitions for arrays when appropriate.

## Enums
- Typically, keys in an Enum should be TitleCase. For example: `FavoritePerson`, `BestLake`, `Monthly`.


=== laravel/core rules ===

## Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using the `list-artisan-commands` tool.
- If you're creating a generic PHP class, use `artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

### Database
- Always use proper Eloquent relationship methods with return type hints. Prefer relationship methods over raw queries or manual joins.
- Use Eloquent models and relationships before suggesting raw database queries
- Avoid `DB::`; prefer `Model::query()`. Generate code that leverages Laravel's ORM capabilities rather than bypassing them.
- Generate code that prevents N+1 query problems by using eager loading.
- Use Laravel's query builder for very complex database operations.

### Model Creation
- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `list-artisan-commands` to check the available options to `php artisan make:model`.

### APIs & Eloquent Resources
- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.
- **Appointment API**: The `/api/appointments/available-slots` endpoint handles real-time availability checking with duration-based conflict detection. When editing appointments, always pass `exclude_appointment` parameter to prevent self-conflicts.

### Controllers & Validation
- Always create Form Request classes for validation rather than inline validation in controllers. Include both validation rules and custom error messages.
- Check sibling Form Requests to see if the application uses array or string based validation rules.

### Queues
- Use queued jobs for time-consuming operations with the `ShouldQueue` interface.

### Authentication & Authorization
- Use Laravel's built-in authentication and authorization features (gates, policies, Sanctum, etc.).

### URL Generation
- When generating links to other pages, prefer named routes and the `route()` function.

### Configuration
- Use environment variables only in configuration files - never use the `env()` function directly outside of config files. Always use `config('app.name')`, not `env('APP_NAME')`.

### Testing
- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] <name>` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

### Vite Error
- If you receive an "Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest" error, you can run `npm run build` or ask the user to run `npm run dev` or `composer run dev`.


=== laravel/v12 rules ===

## Laravel 12

- Use the `search-docs` tool to get version specific documentation.
- Since Laravel 11, Laravel has a new streamlined file structure which this project uses.

### Laravel 12 Structure
- No middleware files in `app/Http/Middleware/`.
- `bootstrap/app.php` is the file to register middleware, exceptions, and routing files.
- `bootstrap/providers.php` contains application specific service providers.
- **No app\Console\Kernel.php** - use `bootstrap/app.php` or `routes/console.php` for console configuration.
- **Commands auto-register** - files in `app/Console/Commands/` are automatically available and do not require manual registration.

### Database
- When modifying a column, the migration must include all of the attributes that were previously defined on the column. Otherwise, they will be dropped and lost.
- Laravel 11 allows limiting eagerly loaded records natively, without external packages: `$query->latest()->limit(10);`.

### Models
- Casts can and likely should be set in a `casts()` method on a model rather than the `$casts` property. Follow existing conventions from other models.
- **Important**: The Appointment model has special time handling - `appointment_time` should NOT be cast as datetime to avoid format() errors. Keep it as a string and use `\Carbon\Carbon::parse()` when needed.
- **Model Attributes**: Patient and Dentist models include `$appends = ['full_name']` to ensure full_name is included in JSON output.

## Appointment System
This application features a sophisticated appointment booking system with real-time availability checking.

### Key Features
- **Multi-step Booking Process**: 3-step appointment creation and editing (Basic Info → Time Selection → Review)
- **Real-time Availability**: Live checking of available time slots based on dentist schedules
- **Conflict Prevention**: Duration-based overlap detection prevents double-bookings
- **Time Slot Management**: 30-minute intervals from 9 AM to 5 PM, weekends excluded
- **Status Management**: Tag-based status filtering (scheduled, confirmed, in_progress, completed, cancelled, no_show)
- **Calendar Integration**: Flatpickr calendar widget for date selection

### Technical Implementation
- **API Endpoint**: `/api/appointments/available-slots` with parameters: `dentist_id`, `date`, `duration`, `exclude_appointment`
- **Alpine.js Integration**: Dynamic forms with step navigation and real-time updates
- **Availability Logic**: Comprehensive overlap checking in `AppointmentAvailabilityController`
- **Database Structure**: Appointments table with foreign keys to patients, dentists, and treatments
- **Form Validation**: Proper date validation, duration constraints, and required field checking

### Important Notes
- Always use `exclude_appointment` parameter when editing to prevent self-conflicts
- Appointment times are stored as strings, not datetime objects
- Weekend appointments are blocked by default
- Duration-based conflict detection considers entire appointment duration, not just start time


=== pint/core rules ===

## Laravel Pint Code Formatter

- You must run `vendor/bin/pint --dirty` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test`, simply run `vendor/bin/pint` to fix any formatting issues.


=== phpunit/core rules ===

## PHPUnit Core

- This application uses PHPUnit for testing. All tests must be written as PHPUnit classes. Use `php artisan make:test --phpunit <name>` to create a new test.
- If you see a test using "Pest", convert it to PHPUnit.
- Every time a test has been updated, run that singular test.
- When the tests relating to your feature are passing, ask the user if they would like to also run the entire test suite to make sure everything is still passing.
- Tests should test all of the happy paths, failure paths, and weird paths.
- You must not remove any tests or test files from the tests directory without approval. These are not temporary or helper files, these are core to the application.

### Running Tests
- Run the minimal number of tests, using an appropriate filter, before finalizing.
- To run all tests: `php artisan test`.
- To run all tests in a file: `php artisan test tests/Feature/ExampleTest.php`.
- To filter on a particular test name: `php artisan test --filter=testName` (recommended after making a change to a related file).
</laravel-boost-guidelines>