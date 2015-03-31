# Recipe for setup of a new [Laravel](http://laravel.com) project
With [PhpStorm](https://www.jetbrains.com/phpstorm/), [Homestead](https://github.com/laravel/homestead), [Git](http://git-scm.com), [xDebug](http://xdebug.org) (disabled if [Blackfire](https://blackfire.io) is configured in Homestead), [PHPUnit](https://phpunit.de), [Behat](http://behat.org/)

## Prerequisites
- **Composer** needed on your local machine for recommended Homestead install
https://getcomposer.org/doc/00-intro.md#globally
- **Homestead** on your local machine - recommended method: *With Composer + PHP Tool*
http://laravel.com/docs/5.0/homestead#installation-and-setup
- **Laravel installer** on the Homestead machine
http://laravel.com/docs/5.0/installation#install-laravel

## Steps

### ssh into Homestead from your local machine
> sudo composer self-update

> composer global update

cd to your code/projects folder, e.g. `cd ~/Code`
> laravel new PROJECTNAME

### On your local machine
Add the new site to your ~/.homestead/Homestead.yaml (`homestead edit`) and note the Homestead ip, and your chosen domain name. You may add a database to the config too.

Then add the Homestead ip along with the chosen domain to your **/etc/hosts** file

### In PhpStorm
Create a new project, select the *Location* & set *Project type* to *PHP Empty Project*, then `OK` and click `Yes` to create a project from existing sources instead (Don’t configure namespace roots at this point if offered).

Create the git repository in PhpStorm via VCS > Import into Version Control > Create Git Repository

Edit **.gitignore** to add:

```
.env.behat
_ide_helper.php
.idea
```

Add and commit all files to git

Follow [Configure PhpStorm for a Laravel project](Configure PhpStorm for Laravel project.md)

### ssh into Homestead, cd to the project folder
> chmod u+x artisan

> composer require barryvdh/laravel-ide-helper --dev

### In PhpStorm
Edit **composer.json** to add this in section scripts > post-update-cmd - just before `artisan optimize`:

```json
"php artisan ide-helper:generate",
```

Edit **app/Providers/AppServiceProvider.php** and add this within the *register()* method:

```php
if (!$this->app->environment('production')) {
  $this->app->register('Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider');
}
```

### ssh into Homestead, cd to the project folder
> composer update

Commit "ide-helper setup"

### ssh into Homestead, cd to the project folder
> composer require behat/behat behat/mink-extension laracasts/behat-laravel-extension --dev

> vendor/bin/behat --init

> cp .env.example .env.behat

Create **behat.yml** in project root and enter:

```yml
default:
  extensions:
    Laracasts\Behat: ~
    Behat\MinkExtension:
      default_session: laravel
      laravel: ~
```

Edit **.env.behat** to set:

```
APP_ENV=acceptance
APP_DEBUG=false
CACHE_DRIVER=array
SESSION_DRIVER=array
```

Edit **features/bootstrap/FeatureContext.php** and add this at top of file:

```php
use Behat\MinkExtension\Context\MinkContext;
use Laracasts\Behat\Context\DatabaseTransactions;
use Laracasts\Behat\Context\Migrator;
use Laracasts\Behat\Context\Services\MailTrap;
use PHPUnit_Framework_Assert as PHPUnit;
```

…and add this to the class:

```php
extends MinkContext
```

```php
use DatabaseTransactions, Migrator;
use MailTrap;
```

Commit "Behat setup"

Edit **tests/TestCase.php** and put this before return statement in *createApplication()*:

```php
$app['config']->set('database.default', 'sqlite');
$app['config']->set('database.connections.sqlite.database', ':memory:');
```

…then override method *setUp()* and add this after `parent::setUp()` within:

```php
Artisan::call('migrate');
```

Commit "PHPUnit setup"

### Optional: Remove Laravel scaffolding
ssh into Homestead, cd to the project folder
(Caution - This call removes edits from *AppServiceProvider::register()* that will need to be re-added before committing! - do a diff)
> php artisan fresh

…and commit "Removed Laravel scaffolding"

### Optional: Namespace application
ssh into Homestead, cd to the project folder
> php artisan app:name APPNAME

…and commit "App namespacing"
