#Bedrock

Bedrock is created by the team behind Sage and is described as "WordPress boilerplate with modern development tools, easier configuration, and an improved folder structure." Read more at 
https://roots.io/bedrock/docs/installing-bedrock/.

##Getting up and running with Bedrock
While there is an official guide on how to get up and running with Bedrock, there are some things missing in it. Since we are often using [Oderland](http://oderland.se) for hosting, this guide also describes some extra steps necessary to get stuff up and running in their shared environment. The Oderland-steps may of course also apply to other shared webhosts.

Fear not. While the guide is pretty lengthy, you will probably onlye have to go through it once per project and then forget about it.

This guide assumes in some places that you are developing a theme based on [Sage](https://roots.io/sage/). If you are not doing that: why are you not doing that?

###Setting up Bedrock locally from scratch
These steps should be taken if yo are the first developer to work on a project. If you are supposed to continue work on an existing Bedrock based project, check under "Cloning an existing Bedrock based project".

1. Make sure you have a MySQL-database to use for the WP-installation.
2. The entire project will be hosted on GitHub so either create an empty repo there that you clone to your local machine or create it locally after step 1 or however you like to do it. The important thing is that the entire project is in the repo. So if you unzip Bedrock (see next step) to the directory "bedrocktest.local", all files in "bedrocktest.local" should be in the repo (the .gitignore of Bedrock, and later on Sage, will keep unwanted files out of the repo).
3. Install [Composer](https://getcomposer.org/) if you don't already have it installed.
4. Follow the steps listed here: https://roots.io/bedrock/docs/installing-bedrock/ with the following exceptions:
    - Instead of cloning the git repo, download it as a zip, unzip it and move the ocntent of bedrock-matser to the local projects web root. We don't want the git files for Bedrock in our repo.
    - Clarification on the step about doc root: on your local machine, set doc root to `/path/to/site/web/` since you will not deploy to it.

If all has gone according to plans so far, you should be able to install WordPress by visiting the link stated in step 6 in the Bedrock installation guide. If that is the case, go ahead and install. If it doesn't work, have fun debugging.

Now might be a good time to exceute the commands listed under [Theme development in Sages' README](https://github.com/roots/sage/blob/master/README.md#theme-development).

After you have activated your own theme, yo can go to `web/wp/app/wp-content/themes` and delete all the themes there. This is not a necessary step since the app-directory is in Bedrocks .gitignore but it sure feels good to remove a bunch of unnecessary files.

Note that since Sage comes with its own .gitignore, we now have two such files including the one that came with Bedrock. This is totally cool but if you want to you could move Sages' ignore patterns to .gitignore at the root (remember to update the paths to point to the theme), it would, as of Sage 8.4.1 look like this:

```
# Theme
 web/app/themes/THEME_DIR_NAME/dist
 web/app/themes/THEME_DIR_NAME/bower_components
 web/app/themes/THEME_DIR_NAME/node_modules
 web/app/themes/THEME_DIR_NAME/npm-debug.log
```

If you haven't already made a git commit, now may be a good time to do one.

###Cloning an existing Bedrock based project
TODO: If you to continue work on an existing Bedrock based project,

###Setting up site folder on remote server
We need a directory and URL where the site will reside on the remote server(s) so, for Oderland, log in to cPanel and set that up as usual. You can set the doc root as usual for now, we will change that later on. Make sure that you can surf to the URL. While we are at it, set up a database to use as well and keep the username/password for later.

###Setting up SSH-connection to remote server
Capistrano must be able to SSH to the server(s) to which deploys should be done. Here's how to get that up and running when working on a a shared Oderland server:

1. Log in to cPanel and go to "SSH access" ("SSH-åtkomst")
2. Do one of the following: 
    - Fire up a new browser tab and go to https://www.oderland.se/clients/knowledgebase/102/Ansluta-med-SSH.html (a guide by Oderland in Swedish on how to set up SSH keys in cPanel in their environment) and follow the guide there.
    - Or, better yet, if you already have an existing keypair (whch doesn't have to be set up on the server you want to conect to) (if you are uncertain, check this guide https://help.github.com/articles/checking-for-existing-ssh-keys/ ):
        1. Open the public key (with the .pub extension) in a texteditor
        2. Copy the contents of the public key
        3. Click "Import key" in cPanel (under "SSH access")
        4. Give the key a name, preferrably one that identifies your machine
        5. Paste what you copied in step 2 to the "Public key" field
        6. Leave "private key" and "pass phrase" empty.
        7. Save and go back to "SSH access".
        8. Click on "handle" next to the newly imported key and then authorize it.
        
You should now be able to log in to the server by running the following in the terminal "ssh username@domain.void" where user is the master user name and server.void is the main domain for the Oderland account.        

###Setting up Composer on remote server
We also need to be able to run [Composer](http://getcomposer.org) on the server so let's set that up.

SSH to the server, go to the directory that you want to deploy to (that you set up under "Setting up site folder on remote server") and try running `composer`.

If the command works and you can , you should be able to skip to the next section. If the command does not work, you need to add Composer. This is how we have done it at Oderland:

1. You need to put Composer somewhere on the server, a good idea is to put it in a directory named "bin" in the home directory. So run `mkdir -p ~/bin` which will create bin if it does not already exist. 
2. Navigate to the bin directory and run `php -r "readfile('https://getcomposer.org/installer');" | php` as described in the getting started guide on http://getcomposer.org.
3. I have tried adding composer as a global command using different versions of `mv composer.phar /usr/local/bin/composer` and adding it to PATH but can not get that to work when running Capistrano. So unless we can come up with a better solution we have to make sure to execute step 8 under "Setting up deploys to your local machine".

###Setting up deploys on your local machine

I have chosen [Bedrock-capistrano](https://github.com/roots/bedrock-capistrano) for deploys since Trellis is a bit more than I and Oderland can handle at the moment. Let's set it up using these steps taken from the README of [bedrock-capistrano](https://github.com/roots/bedrock-capistrano/blob/master/README.md):

1. In the terminal on your local machine, go to the root directory of the project ("bedrocktest.local" in our example).
2. Run `gem install bundler` or, if that doesnt work, `sudo gem install bundler`. If that fails, make sure you have Ruby installed (which you very most likely have).
3. Download bedrock-capistrano and unzip it.
4. Carry out step 1-2 under [Installation/configuration in the bedrock-capistrano README](https://github.com/roots/bedrock-capistrano/blob/master/README.md#installationconfiguration).
5. Delete the bedrock-capistrano directory.
6. Still in the root dir of your project, run `bundle install`.
7. Replace config/deploy.rb with [our modified deploy.rb](https://github.com/fewagency/best-practices/tree/master/Bedrock/deploy.rb). Search for "FEW" in that file if you wantto see what we have changed/added. Make sure that you set correct values for everythingevery lne starting with "set".
8. If we can not get composer to run globally on remote server, make sure that `SSHKit.config.command_map[:composer] = "~/bin/composer.phar"` has the correct value. If this value differs between servers, cut it from deploy.rb and add it to each file in config/deploy.
9. Carry out step 3 under [Installation/configuration in the bedrock-capistrano README](https://github.com/roots/bedrock-capistrano/blob/master/README.md#installationconfiguration).
    - On the line starting with "srever", you need to specify the values that you use when SSH'ing to the remote server. 
    - Note that you, in the config/deploy can specify what branch you want to use. See deploy.rb for info on this. 
10. Now might be a good time to make a git commit.
11. You should now be able to run the command in step 4 in the bedrock-capistrano README. If all is correct the script should exit with an error that .env was not found. So let's fix that in a moment.
12. First, change the document root to current/web on the remote servers. 
13. Upload a copy of .env.example to `/shared` on the remote server, rename it to .env and enter the correct data in it. Note that you will probably want to change WP_ENV to production.
14. Also on the rmeote machine, create an empty .htaccess file in shared/web/.
15. On the local machine if you run `bundle exec cap production deploy` (assuming you have set up the production file in config/deploy), it should run successfully all the way to where the last output should be a message that a line was written to revision.log. If so, what just happened was:
    - You have just downloaded the git repo to the remote server to a directory in releases which is named after the current date and time
    - `gulp --production` ran on your local machine and the dist-folder was uploaded to web/app/themes/THEMENAME/ in the newly created release-directory.
    - The symbolic link for current was changed to point to the new release-folder
    - If there were more than five releases, the oldest of them were deleted so there should be a maximum of four old folders and the current release in the release folder.
    - A line has been written to revision.log telling us that you performed a deploy and what revision of the git repo you deployed. 
16. You should now be able to go to http://example.com/wp/wp-admin and set up Wordpress.
17. Activate the theme we want to work with.
18. Visit the frontend to make sure it looks ok.
19. Do a css-change on your local machine, for example setting the body-bg to red in assets/style/main.scss
10. Execute `bundle exec cap production deploy`, wait for it to finish and reload the site on the remote server in your browser. The changes in the css should now be visible. Note that you didn't have to git commit anything when you only change assets since they are uploaded from your local machine.

TODO: How to add plug-ins
TODO: How to access private repos from Oderland

 




