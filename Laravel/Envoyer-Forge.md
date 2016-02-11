# Deploying Laravel projects with [Envoyer](https://envoyer.io/) & [Forge](https://forge.laravel.com/)

## Create the server
If you're using Forge for server creation, start at https://forge.laravel.com/servers.

TODO: Set the web directory to `/current/public`

### Swap file needed on low RAM servers
When creating a smaller server (e.g. 512MB RAM) make sure to set up a swap file for memory intensive tasks, like `npm install`.

A swap file can be set up and reused as a Forge recipe (https://forge.laravel.com/recipes) or run in terminal:

```sh
if [ -f /swapfile ]; then
 echo "Swap file already exists."
else
 sudo fallocate -l 1G /swapfile
 sudo chmod 600 /swapfile
 sudo mkswap /swapfile
 sudo swapon /swapfile
 echo "/swapfile none swap sw 0 0" >> /etc/fstab
 echo "vm.swappiness=30" >> /etc/sysctl.conf
 echo "vm.vfs_cache_pressure=50" >> /etc/sysctl.conf
 echo "Swap created and added to /etc/fstab for boot up."
fi
```

## Configure deploy
TODO: Through Envoyer

TODO: Set up health-check URL

### Deployment hooks
TODO: Set up _Linked Folders_ in Envoyer: `node_modules`, `bower_components`

#### After _Install Composer Dependencies_
_npm install_:
```sh
cd {{release}}
npm install --silent
```

_bower install_:
```sh
cd {{release}}
bower install
```

_gulp_:
```sh
cd {{release}}
gulp --production
```

**Caution:** Laravel Elixir unfortunately doesn't make gulp return a non-0 exit code on errors. So if elixir fails, it doesn't stop the deploy (see https://github.com/laravel/elixir/issues/71).

#### After _Activate New Release_
_Database migration_:
```sh
cd {{release}}
php artisan migrate --force
```

## Editing `.env`
...is best done via the _Environment_ tab in Forge's _Site details_, or directly on the server.

**Caution:** Envoyer also has a _Manage Environment_-option for editing `.env`, but that keeps the contents (encrypted by a key) within Envoyer and manual changes made to the file on the server may be overwritten.

## SSL setup
Certificates are managed through the _SSL Certificates_ tab in Forge's _Site details_, for example using [LetsEncrypt](https://letsencrypt.org).
