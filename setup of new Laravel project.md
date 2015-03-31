# Setup of a new [Laravel](http://laravel.com) project
With [PhpStorm](https://www.jetbrains.com/phpstorm/), [Homestead](https://github.com/laravel/homestead), [Git](http://git-scm.com), [xDebug](http://xdebug.org) (disabled if [Blackfire](https://blackfire.io) is configured in Homestead), [PHPUnit](https://phpunit.de), [Behat](http://behat.org/)

## Prerequisites
Composer needed on your local machine for recommended Homestead install
https://getcomposer.org/doc/00-intro.md#globally

Homestead on your local machine - recommended method: With Composer + PHP Tool
http://laravel.com/docs/5.0/homestead#installation-and-setup

Laravel installer on the Homestead machine
http://laravel.com/docs/5.0/installation#install-laravel


ssh into Homestead
> sudo composer self-update
> composer global update

cd to code/projects folder when in Homestead via ssh, e.g. cd ~/Code
> laravel new PROJECTNAME

Exit Homestead ssh session

Add the new site to your ~/.homestead/Homestead.yaml (> homestead edit) - note the Homestead ip, and your chosen domain name. You may add a database too.

Add the Homestead ip and the chosen domain to your /etc/hosts

Then create a new project in PhpStorm, select Location & set Project type to PHP Empty Project, then OK and click Yes to create a project from existing sources instead (Don’t configure namespace roots at this point).

Create git repository in PhpStorm via VCS > Import into Version Control > Create Git Repository

Edit .gitignore to add:
.env.behat
_ide_helper.php
.idea

Add and commit all files to git

In PhpStorm preferences
- Tools > Vagrant - Set instance folder, e.g. /Users/?/.composer/vendor/laravel/homestead

Load the new Homestead configuration through PhpStorm via Tools > Vagrant > Provision

In PhpStorm preferences:
- Languages & Frameworks > PHP - Set the Interpreter to Homestead (which should be a Vagrant Remote) and leave include paths as everything under vendor will be auto-added by PhpStorm after the next composer update
- Languages & Frameworks > PHP > Servers - Add new with chosen domain, then set path mappings on the main project directory and the public directory, e.g. /home/vagrant/Code/?
- Languages & Frameworks > PHP > PHPUnit - Add Remote Interpreter, Use custom autoloader path ?/vendor/autoload.php and Default configuration file path ?/phpunit.xml in the project directory on the Homestead machine
- Build, Execution, Deployment > Deployment - Edit or add "Homestead", Type SFTP, set SFTP Host and Web server root URL, then switch to Mapping tab and set Deployment path on server
- Project > Directories - Make vendor an Excluded folder
- Other Settings > Laravel Plugin - Enable plugin

Create a PhpStorm Run configuration via Run > Edit configurations of type PHPUnit called "Run PHPUnit" with Test scope Defined in the configuration file.

ssh into Homestead, cd to the project folder
> chmod u+x artisan
> composer require barryvdh/laravel-ide-helper --dev

Edit project’s composer.json to add this in section scripts > post-update-cmd - just before artisan optimize:
"php artisan ide-helper:generate",

Edit app/Providers/AppServiceProvider.php and add this within the register() method:
if (!$this->app->environment('production')) {
  $this->app->register('Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider');
}

ssh into Homestead, cd to the project folder
> composer update

Commit "ide-helper setup"

ssh into Homestead, cd to the project folder
> composer require behat/behat behat/mink-extension laracasts/behat-laravel-extension --dev
> vendor/bin/behat --init
> cp .env.example .env.behat

Create behat.yml in project root and enter:
default:
  extensions:
    Laracasts\Behat: ~
    Behat\MinkExtension:
      default_session: laravel
      laravel: ~

Edit .env.behat to set:
APP_ENV=acceptance
APP_DEBUG=false
CACHE_DRIVER=array
SESSION_DRIVER=array

Edit features/bootstrap/FeatureContext.php and add this at top of file:
use Behat\MinkExtension\Context\MinkContext;
use Laracasts\Behat\Context\DatabaseTransactions;
use Laracasts\Behat\Context\Migrator;
use Laracasts\Behat\Context\Services\MailTrap;
use PHPUnit_Framework_Assert as PHPUnit;
…and add this to the class:
extends MinkContext
use DatabaseTransactions, Migrator;
use MailTrap;

Commit "Behat setup"

Edit tests/TestCase.php and put this before return statement in createApplication():
$app['config']->set('database.default', 'sqlite');
$app['config']->set('database.connections.sqlite.database', ':memory:');
…then override method setUp() and add this after parent::setUp() has been run:
Artisan::call('migrate');

Commit "PHPUnit setup"

Optional: Remove Laravel scaffolding - ssh into Homestead, cd to the project folder
> php artisan fresh

(Caution - This call removes edits from AppServiceProvider::register() that will need to be re-added before committing!)
…and commit "Removed Laravel scaffolding"

Optional: Namespace application - ssh into Homestead, cd to the project folder
> php artisan app:name APPNAME

…and commit "App namespacing"
