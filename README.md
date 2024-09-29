I use an ubuntu dockerised dev environment to keep the experience consistent across machines.
- To run, open docker desktop and vscode
- In vscode, open the repo, hit F1, and select "Dev containers: Rebuild and Reopen in Container" to spin up a local dev environment
- On Windows this will require enabling WSL

This will set up and run the queue worker, the DB, and the site itself.
To manually dispatch the welcome email job run the following:
```
php artisan email:send-welcome {user_id}
```

The DB is automatically seeded, so feel free to log in with the following details, or register to gain your own:
```
user: bingo@bongo.com.au
pass: pass
```

Notes:

In PHP files I've marked any meta code assessment specific comments with a "#"
- If you search for "# " and filter for only .php files you will find my reasoning behind including features
Standard comments have been made with "//"

Requirements and setup history

1) Setup (handled by dockerfile)
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

2) API Endpoints
Posts and users are explicitly requested, but comments are implied too

3) Database Design
Migrations
Model relationships

4) Features
Pagination on posts, comments, and users

5) Queue implementation
Queued job for sending a welcome email
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

6) Validation
Request validation for all endpoints
HTTP status codes and error messages

7) Testing
PHPUnit tests for at least two of the implemented endpoints
- I ended up doing this for all of them as that's how I like to code

8) Documentation
I've opted to use swagger via external definitions rendered here: http://localhost:8000/swagger-ui/index.html#/

Things considered out of scope of the requirements, but potentially in the scope of the evaluation criteria:
Security
- Email confirmation on account creation
- Password requirements
  - Minimum length
  - No composition requirements as they tend to force users into easier to guess patterns
    - https://markilott.medium.com/how-most-password-policies-make-us-less-secure-69476ca9fe92
  - Password strength meter so the user can see if they've fallen into common pitfalls
    - Warning if password is in the top 100 most common passwords
    - Warning if password is too easy to brute force

- MFA
- Account recovery
- Admin controls
- Successive login failure protections
  - After three failed attempts introduce
    - Captcha
    - Exponentially increasing timeouts
  - Admin override to reset the current protection status

Performance
- Google lighthouse setup

Testing
- End to end Playwright tests
- Unit testing components via a Storybook component library
- CI/CD via github actions
  - Lint rules
  - Test suite
  - Dependabot

Things out of scope that I did anyway:

Front end setup history:
There was no mention of a front end, but I figured it would be nicer to test the features and flow as a user.
I opted for some react content as that's your current stack.

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
