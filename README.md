## Properos Base

Base package with classes (Api, Base, Helper, Paginator, Theme), controllers and views necessarry to start a project. 

**Register provider on composer.json**
```json
    "autoload": {
    "...": {},
        "psr-4": {
            "App\\": "app/",
            "Properos\\Base\\": "packages/properos/properos-base/src"
        }
    },
```
**Register provider on config/app.php**
```php
    'providers' => [
        "...",
        Properos\Base\BaseServiceProvider::class,
        "..."
    ],
```
**Run**
    composer dump
    php artisan vendor:publish 
Select -> Properos\Base\BaseServiceProvider

**Themes**
It contains a Themes class to allow the use of several themes in the project. The name of theme is declared on env variable "THEME", if this variable isn't declare it going to take a default theme.

**Use themes on routers.**
```php
    Route::get('/', function () {
        return  view('themes.'.\Properos\Base\Classes\Theme::get().'.index')->with(['theme'=>\Properos\Base\Classes\Theme::get()]);
    });
```
**Create env.js**

Add on webpack.mix.js
.js('resources/assets/js/bootstrap.js', 'public/be/js/core.js')
.sass('resources/assets/js/be/sass/app.scss', 'public/be/css')

**Add on resources/assets/bootstrap.js**
```js
    import Helpers from './misc/helpers'
    window.Helpers = Helpers;
```
**Remove from resources/assets/bootstrap.js**
```php
    try {
        window.$ = window.jQuery = require('jquery');

        require('bootstrap');
    } catch (e) {}
```

**Run npm install**

**Define host, database on .env**
```php
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=database_name
    DB_USERNAME=root
    DB_PASSWORD=
```

**Add on app/Providers/AppServiceProvider.php**
```php
    use Illuminate\Support\Facades\Schema;
    
    public function boot()
    {
        Schema::defaultStringLength(191);
    }
```
**Run php artisan migrate**
