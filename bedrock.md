#Bedrock

Bedrock is created by the team behind Sage and is described as "WordPress boilerplate with modern development tools, easier configuration, and an improved folder structure." Read more at 
https://roots.io/bedrock/docs/installing-bedrock/
.

##Getting up and running with Bedrock
While there is an official guide on how to get up and running with Bedrock, there are some things missing in it. Since we are often using [Oderland](http://oderland.se) for hosting, this guide also describes some extra steps necessary to get stuff up and running in their shared environment. The Oderland-steps may of course also apply to other shared webhosts.

Let's get started!

1. Follow the steps listed here: https://roots.io/bedrock/docs/installing-bedrock/ with the following exceptions:
    - Instead of cloning the git repo, download it as a zip and unzip it at the local projects web root.

###Setting up [Composer](https://getcomposer.org/)
SSH to the server and try running `composer`
If the command does not work, you need to add Composer. This is how we have done it at Oderland:

1. Go to the home directory using `cd ~` or anywhere else where composer.phar can reside. It is probably a good ideas to keep it out of any public folders.
2. Run `php -r "readfile('https://getcomposer.org/installer');" | php` as described at the getting started guide on http://getcomposer.org.
3. I have tried adding composer as a global command using different versions of `mv composer.phar /usr/local/bin/composer` but to no avail. So unless we can come up with a better solution:
4. Let's tell Capistrano where composer resides by adding `SSHKit.config.command_map[:composer] = "~/composer.phar"` to the appropriate deploy-scripts. If the path is the same for all environments, you can add it to `config/deploy.rb` or, if it differs, add it to the files in `config/deploy/` and change the value as needed.
