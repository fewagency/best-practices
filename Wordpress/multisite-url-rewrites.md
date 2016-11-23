# URL Rewrites for WordPress Multisite

When setting up WordPress Multisite [the instructions](https://codex.wordpress.org/Create_A_Network#Step_2:_Allow_Multisite)
tells you to add URL rewrite rules, but they're for Apache only...

## Valet
For [Laravel Valet](http://laravel.com/docs/valet) we have created
[a driver file](https://gist.github.com/bjuppa/f11c842fc5e7c5576b3f2c96583f8625)
that you can drop into your `~/.valet/Drivers/`.

## Homestead
[Laravel Homestead's](http://laravel.com/docs/homestead) Nginx configuration can be extended using this nifty trick inspired by [Otley](https://laracasts.com/@otley)'s
[comment](https://laracasts.com/discuss/channels/requests/homestead-provision-deletes-custom-nginx-settings/replies/113240)
in [this Laracasts thread](https://laracasts.com/discuss/channels/requests/homestead-provision-deletes-custom-nginx-settings):

1. Find your homestead `scripts` directory in your local machine.
  It may be `~/Homestead/scripts/` or `~/.composer/vendor/laravel/homestead/scripts/`
  depending on your Homestead installation.
  
2. Edit `serve-laravel.sh` and add `include /etc/nginx/conf.d/$1-custom;` just above the `location ~ \.php$`-block
  and `touch "/etc/nginx/conf.d/$1-custom"` at the end of the file
  (or just before `service nginx restart` - if present).

3. Reprovision Homestead by running `vagrant reload --provision`
  in your homestead directory (where the `Vagrantfile` is).

4. SSH into your Homestead box
  (usually by running `vagrant ssh` in your homestead directory).

5. In the Homestead box, `cd /etc/nginx/conf.d/` and edit the file therein corresponding to your WordPress Multisite.
  [The WordPress documentation for Nginx](https://codex.wordpress.org/Nginx#WordPress_Multisite_Subdirectory_rules)
  gives us the rules to add in the custom rules file:

  ``` nginx
    if (!-e $request_filename) {
        rewrite /wp-admin$ $scheme://$host$uri/ permanent;
        rewrite ^(/[^/]+)?(/wp-.*) $2 last;
        rewrite ^(/[^/]+)?(/.*\.php) $2 last;
    }
  ```

6. Run `sudo service nginx restart` within your Homestead box to reload Nginx after adding custom rules.
