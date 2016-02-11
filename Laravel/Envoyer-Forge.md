# Tips for deploying Laravel projects using [Envoyer](https://envoyer.io/) & [Forge](https://forge.laravel.com/)

## Create the server
If using Forge for server setup, go to https://forge.laravel.com/servers

### Servers with low RAM
If selecting a smaller server (e.g. 512MB RAM) make sure to create a swap file afterwards.
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



