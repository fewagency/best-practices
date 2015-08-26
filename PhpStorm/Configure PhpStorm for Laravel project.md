# Configure [PhpStorm](https://www.jetbrains.com/phpstorm/) for a [Laravel](http://laravel.com) project

In PhpStorm preferences:

* __Tools__ > __Vagrant__ - Set instance folder, e.g. `/Users/???/.composer/vendor/laravel/homestead`

Exit preferences

Load the new Homestead configuration via __Tools__ > __Vagrant__ > __Provision__

In PhpStorm preferences:

* __Languages & Frameworks__ > __PHP__ - Set the *Interpreter* to your Homestead interpreter. If not in list, create new *Remote* interpreter of type *Vagrant* with the instance folder path from step above. Leave include paths empty (everything under vendor will be auto-added by PhpStorm after the next composer update).
* __Languages & Frameworks__ > __PHP__ > __Servers__ - Add new with chosen domain, then set path mappings on the main project directory and the public directory, e.g. `/home/vagrant/Code/???`
* __Languages & Frameworks__ > __PHP__ > __PHPUnit__ - *Add Remote Interpreter*, *Use custom autoloader* path `???/vendor/autoload.php` and *Default configuration file* path `???/phpunit.xml` in the project directory on the Homestead machine
* (Optional) __Build, Execution, Deployment__ > __Deployment__ - Edit or add "Homestead", Type *SFTP*, set *SFTP Host* and *Web server root URL*, then switch to the *Mapping* tab and set *Deployment path on server*. (Unconfirmed: this may help with some path mappings in other PhpStorm tools)
* (Optional) __Project__ > __Directories__ - Make `vendor` an *Excluded folder*
* __Other Settings__ > __Laravel Plugin__ - Enable plugin. (If not already installed, go to __Plugins__ and search for "Laravel", install the plugin and restart PhpStorm, then go back to settings and enable it.)

Exit preferences

For PHPUnit, create a PhpStorm Run configuration via __Run__ > __Edit configurations__ of type *PHPUnit* called "Run PHPUnit" with *Test scope* set to *Defined in the configuration file*. Leave all other options blank.

To use a common code style, make sure your project's [style XML is installed and selected](codestyle.md).