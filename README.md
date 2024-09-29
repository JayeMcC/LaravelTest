I use an ubuntu dockerised dev environment to keep the experience consistent across machines.
- To run, open docker desktop and vscode
- In vscode hit F1 and select "Dev containers: Rebuild and Reopen in Container" to spin up a local dev environment running the project here: http://localhost:8000
# I need to update this to use the default ubuntu image at base
I've opted to use swagger via external definitions rendered here: http://localhost:8000/swagger-ui/index.html#/

Notes:

In PHP files I've marked any meta code assessment specific comments with a "#"
- If you search for "# " and filter for only .php files you will find my reasoning behind including features
Standard comments have been made with "//"

Front end setup history:
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

Run the migrations
```
php artisan migrate
```

Run the app
```
php artisan serve

```