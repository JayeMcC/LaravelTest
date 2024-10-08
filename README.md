I use an ubuntu dockerised dev environment to keep the experience consistent across machines.

-   To run, open Docker Desktop and VSCode
-   In VSCode, open the repo, hit F1, and select "Dev containers: Rebuild and Reopen in Container" to spin up a local dev environment
-   On Windows this will require enabling WSL

This will set up and run the queue worker, the DB, and the site itself.
This readme will be hosted at: http://localhost:8000/

To manually dispatch the welcome email job run the following command:

```
php artisan email:send-welcome {user_id}
```

The DB is automatically seeded, so feel free to log in with the following details, or register to gain your own (I recommend jaye.r.mcc+laravelTestAdmin@gmail.com as it's an admin account):

```
email: jaye.r.mcc+laravelTestAdmin@gmail.com
password: password123

email: jaye.r.mcc+laravelTestUser@gmail.com
password: password123
```

If the above accounts don't work, run:

```
php artisan migrate:fresh --seed
```

In PHP files I've marked any meta code assessment specific comments with a "#"

-   If you search for "# " and filter for only .php files you will find my reasoning behind including features
-   Standard comments have been made with "//"

Requirements and setup history

1. Setup (handled by dockerfile)
   Create a new Laravel project using the latest stable version

```
composer create-project laravel/laravel example-app
```

Set DB to mysql in .env
Set up .devcontainer/docker-compose.yml to run a mysql db

Install Laravel Sanctum

```
php artisan install:api
```

2. API Endpoints
-   Posts and users are explicitly requested, but comments are implied too

4. Database Design

5. Features
-   Pagination on posts, comments, and users

6. Queue implementation
-   Queued job for sending a welcome email

```
app/Jobs/SendWelcomeEmail.php
```

Artisan command to dispatch that job

```
app/Console/Commands/SendWelcomeEmailCommand.php
```

Run by:

```
php artisan email:send-welcome {user_id}
```

Check completed jobs by logging into the mysql container

```
docker exec -it mysql mysql -u user -p
pass: pass
```

And reviewing the job histories table

```
USE test_db;
SELECT * FROM job_histories;
```

6. Validation

7. Testing

8. Documentation
-   I've opted to use swagger via external definitions rendered here: http://localhost:8000/swagger-ui/index.html#/
-   There's a button in the nav of the web app to grab a valid bearer token that makes authenticating the swagger ui easy
-   But you could also just run the login 

Things I've considered out of scope of the requirements, but potentially in the scope of the evaluation criteria:

Security

-   Email confirmation on account creation
-   Password requirements
    -   Minimum length
    -   No composition requirements as they tend to force users into easier to guess patterns
        -   https://markilott.medium.com/how-most-password-policies-make-us-less-secure-69476ca9fe92
    -   Password strength meter so the user can see if they've fallen into common pitfalls
        -   Warning if password is in the top 100 most common passwords
        -   Warning if password is too easy to brute force
-   MFA
-   Account recovery
-   Admin controls
-   Successive login failure protections
    -   After three failed attempts introduce
        -   Captcha
        -   Exponentially increasing timeouts
    -   Admin override to reset the current protection status
-   Rate limiting on APIs/Debouncing

Performance

-   Google lighthouse setup

Testing

-   End to end Playwright tests
-   Unit testing components via a Storybook component library
-   CI/CD via github actions
    -   Lint rules
    -   Test suite
    -   Dependabot

Prod deployment

-   Would point to a prod instance instead of spinning up a DB instance/seeding the DB
-   Would implement secure secrets management (probably via pipeline vars or AWS secrets)

Things out of scope that I did anyway:

Front end setup history:
There was no mention of a front end, but I figured it would be nice to test the features and flow as a user too.
I opted for some react content as that's your current stack.
I would have opted for an SPA, but that would have been more complex.
VSCode won't stop "Configuring Dev Container", it's just because it's running npm dev so the react components build

```
php artisan ui react --auth
```

Install NPM

```
apt install npm
```

Use NPM to install depenencies

```
npm install
```

Build the react assets

```
npm run dev
```

Things I would have liked to have done, but I'd need to sleep on it

API sad path handling: http://localhost:8000/swagger-ui/index.html#/
Exception handler was redirecting api requests to web links
-   Logging out while unauthenticated throws a redirect to welcome page
-   Attempting to hit an auth required page while unauthenticated throws a redirect to the login page
-   Hitting the post update while unauthenticated redirects to /

Linting:

-   Fill in phpdoc

Web:

-   Add pagination to web

Swagger UI:

-   Caching issue, seems to hold onto old files even when told not to by laravel routes
-   Temp fix is to always open in incognito
-   Figure out answer later
