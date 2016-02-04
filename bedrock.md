#Bedrock

Bedrock is created by the team behind Sage and is described as "WordPress boilerplate with modern development tools, easier configuration, and an improved folder structure." Read more at 
https://roots.io/bedrock/docs/installing-bedrock/
.

##Getting up and running with Bedrock
While there is an official guide on how to get up and running with Bedrock, there are some things missing in it. Since we are often using [Oderland](http://oderland.se) for hosting, this guide also describes some extra steps necessary to get stuff up and running in their shared environment. The Oderland-steps may of course also apply to other shared webhosts.

#Installing Bedrock locally

1. Set up a MySQL-database to use for the WP-installation.
2. The entire project will be hosted on GitHub so either create an empty repo there that you clone to your local machine or create it locally after step 1 or however you like to do it. The important thing is that the entire project is in the repo. So if you unzip Bedrock (see next step) to the directory "bedrocktest.local", all files in "bedrocktest.local" should be added to the repo (the .gitignore of Bedrock, and later on Sage, will keep unwanted files out of the repo).
3. Install [Composer](https://getcomposer.org/) if you don't already have it installed.
4. Follow the steps listed here: https://roots.io/bedrock/docs/installing-bedrock/ with the following exceptions:
    - Instead of cloning the git repo, download it as a zip and unzip it at the local projects web root. No use in getting the git-files for bedrock.
    - Clarification on the step about doc root: on your local machine, set doc root to `/path/to/site/web/` and on machines that you will deploy to, set it to `/path/to/site/current/web/`.

If all has gone according to plans so far, you should be able to install WordPress by visiting the link stated in step 6 in the Bedrock installation guide. If that is the case, go ahead and install. If it doesn't work, have fun debugging.

#Setting up deploys on

I have chosen [Bedrock-capistrano](https://github.com/roots/bedrock-capistrano) for deploys since Trellis is a bit more than I and Oderland can handle at the moment. Let's set it up using these steps taken from the README of [bedrock-capistrano](https://github.com/roots/bedrock-capistrano/blob/master/README.md).

1. In the terminal on your local machine, go to the root directory of the project ("bedrocktest.local" in our example).
2. Run `gem install bundler` or if that doesnt work, `sudo gem install bundler`. If that fails, make sure you have Ruby installed (which you very most likely have).
3. When the command above has been executed, run `gem install bundler`.
4. Carry out step 1-3 under [Installation/configuration](https://github.com/roots/bedrock-capistrano/blob/master/README.md#installationconfiguration)
5. ... edit deploy.rb according to gist (copy my testdeploy.rb)



###Setting up Composer on server
SSH to the server and try running `composer`
If the command does not work, you need to add Composer. This is how we have done it at Oderland:

1. Go to the home directory using `cd ~` or anywhere else where composer.phar can reside. It is probably a good ideas to keep it out of any public folders.
2. Run `php -r "readfile('https://getcomposer.org/installer');" | php` as described at the getting started guide on http://getcomposer.org.
3. I have tried adding composer as a global command using different versions of `mv composer.phar /usr/local/bin/composer` but to no avail. So unless we can come up with a better solution:
4. Let's tell Capistrano where composer resides by adding `SSHKit.config.command_map[:composer] = "~/composer.phar"` to the appropriate deploy-scripts. If the path is the same for all environments, you can add it to `config/deploy.rb` or, if it differs, add it to the files in `config/deploy/` and change the value as needed.
