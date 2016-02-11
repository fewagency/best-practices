# Tips for deploying Laravel projects using [Envoyer](https://envoyer.io/) & [Forge](https://forge.laravel.com/)

## Create the server
If you're using Forge for server creation, start at (https://forge.laravel.com/servers).
TODO: Set the web directory to `/current/public`

### Swap file on low RAM servers
When creating a smaller server (e.g. 512MB RAM) make sure to create a swap file afterwards.
This can be set up and reused as a Forge recipe (https://forge.laravel.com/recipes) or run in terminal:

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

If no swap file is created `npm install` will probably fail later.

## Configure deploy
TODO: Through Envoyer

## Editing `.env` on the server
The .env-file is best edited via the _Environment_ tab in Forge's _Site details_, or directly on the server.
*Caution:* Envoyer also has an option for editing `.env`, but then the content is kept (encrypted by a key) within Envoyer and manual changes made to the file on the server may be overwritten.

## SSL setup
Certificates are managed through the _SSL Certificates_ tab in Forge's _Site details_, for example using [LetsEncrypt])https://letsencrypt.org).
