# Configure [PhpStorm](https://www.jetbrains.com/phpstorm/) for a [Laravel](http://laravel.com) project

In PhpStorm preferences:
* Tools > Vagrant - Set instance folder, e.g. /Users/?/.composer/vendor/laravel/homestead

Exit preferences and load the new Homestead configuration via Tools > Vagrant > Provision

In PhpStorm preferences:
* Languages & Frameworks > PHP - Set the *Interpreter* to your Homestead interpreter. If not in list, create new *Remote* interpreter of type *Vagrant* with the instance folder path from step above. Leave include paths empty (everything under vendor will be auto-added by PhpStorm after the next composer update).
* Languages & Frameworks > PHP > Servers - Add new with chosen domain, then set path mappings on the main project directory and the public directory, e.g. /home/vagrant/Code/?
* Languages & Frameworks > PHP > PHPUnit - *Add Remote Interpreter*, *Use custom autoloader* path ?/vendor/autoload.php and *Default configuration file* path ?/phpunit.xml in the project directory on the Homestead machine
* (Optional) Build, Execution, Deployment > Deployment - Edit or add "Homestead", Type *SFTP*, set *SFTP Host* and *Web server root URL*, then switch to the *Mapping* tab and set *Deployment path on server*. (Unconfirmed: this may help with some path mappings in other PhpStorm tools)
* (Optional) Project > Directories - Make **vendor** an *Excluded folder*
* Other Settings > Laravel Plugin - Enable plugin (If not already installed, go to Plugins and search for "Laravel" and install it and restart PhpStorm first)

Exit preferences and create a PhpStorm Run configuration via Run > Edit configurations of type *PHPUnit* called "Run PHPUnit" with *Test scope* *Defined in the configuration file*.
