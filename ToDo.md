Essential:

-   Email and queue functionality
-   Test a rebuild of the project with ubuntu raw image
-   test_user_can_log_out_and_token_is_deleted

API sad path handling: http://localhost:8000/swagger-ui/index.html#/

-   Exception handler was redirecting
-   Logging out while unauthenticated throws a redirect to welcome page
-   Attempting to hit an auth required page while unauthed throws a redirect to the login page
-   Hitting the post update while unauthed redirects to /

Linting:

-   Fill in phpdoc

Web:

-   Add pagination to web

Swagger:

-   Caching issue, seems to hold onto old files even when told not to by laravel routes
-   Temp fix is to always open in incognito
-   Figure out answer later
