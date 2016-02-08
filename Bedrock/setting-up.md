#Getting up and running with Bedrock
This is guide aims to aid developers at [FEW](http://fewagency.se) to set up Bedrock in environments that we ofte use. Hopefully, it can be of help to other developers as well but be aware that there are some FEW specific information ahead. 

If you get totally stuck, here are some resources that may help you:
[Roots Discourse](https://discourse.roots.io)
[Capistrano Website with manual](http://capistranorb.com/)
[Screencast on deploying WordPress with Capistrano](https://roots.io/screencasts/deploying-wordpress-with-capistrano/). This video also exist in our shared Dropbox folder. Note that it is getting a bit old and may contain outdated information.

##Bedrock?
Bedrock is created by the good people behind [Sage](http://roots.io/sage) and is described as a "WordPress boilerplate with modern development tools, easier configuration, and an improved folder structure." Read more at 
https://roots.io/bedrock.

##OMG, look at the length of this document!
Fear not. While the guide is pretty lengthy (partly due to usesless paragraphs like this), you will probably onlye have to go through it once per project and then forget about it.

While there is an official guide on how to get up and running with Bedrock, there are some things missing in it. Alos, since we are often using [Oderland](http://oderland.se) for hosting, this guide also describes some extra steps necessary to get stuff up and running in their shared environment. The Oderland-steps may of course also apply to other shared webhosts.

This guide assumes in some places that you are developing a theme based on [Sage](https://roots.io/sage/). If you are not doing that: why are you not doing that? If you still don't want to use Sage, you should probably be able to use this guide anyway and skip the Sage specific parts.

##Setting up Bedrock locally from scratch
These steps should be taken if yo are the first developer to work on a project. If you are supposed to continue work on an existing Bedrock based project, check under "Cloning an existing Bedrock based project".

1. Make sure you have a host and MySQL-database to use for the WP-installation on your computer.
2. The entire project must be hosted on GitHub so either create an empty repo there that you clone to your local machine or create it locally or however you like to do it. The important thing is that the entire project is in the repo. So if you move the content of bedrock-master.zip (see step 4) to the directory "bedrocktest.local", all files in "bedrocktest.local" should be in the repo (the .gitignore of Bedrock, and later on Sage, will keep unwanted files out of the repo).
3. Install [Composer](https://getcomposer.org/) on your local machine if you don't already have it installed.
4. Follow the steps listed here: https://roots.io/bedrock/docs/installing-bedrock/ with the following exceptions:
    - Instead of cloning the git repo, download it as a zip, unzip it and move the content of bedrock-master to the local projects web root. We don't want the git files for Bedrock in our repo.
    - Clarification on the step about doc root: on your local machine, set doc root to `/path/to/site/web/` since you will not be deploying to this install.
    - You must pe positioned in the projects folder (bedrocktest.local in our example) when running `composer install`.
    - When copying the salts from WP Salt Generator, include the ' since the generated salts may contain spaces.

If all has gone according to plans so far and you have entered correct data in the env-file, you should be able to install WordPress by visiting the link stated in step 6 in the Bedrock installation guide. If that is the case, go ahead and install. If it doesn't work, go over the Bedrock steps again to find out where you did wrong.

If you are using Sage and have activated the Sage based them, now might be a good time to exceute the commands listed under [Theme development in Sages' README](https://github.com/roots/sage/blob/master/README.md#theme-development).

After you have activated your own theme, yo can go to `web/wp/app/wp-content/themes` and delete all the themes there. This is not a necessary step since the app-directory is in Bedrocks .gitignore but it does feel good to remove a bunch of unnecessary files from your local environment, doesn't it?

Note that since both Bedrock and Sage comes with its own .gitignore file, we now have two such files. This is totally cool but if you want to you could move Sages' ignore patterns to Bedrocks .gitignore at the root (remember to update the paths to point to the theme).

If you haven't already made a git commit, now may be a good time to do one.

##Cloning an existing Bedrock based project
If you are continuing work on an existing bedrock based project and that project already has remote servers set up, you probably wont have to do much Bedrock or deploy related set up but here's a list of some steps anyways:

1. Follow the steps listed under the [Bedrock install guide](https://roots.io/bedrock/docs/installing-bedrock/) but instead of cloning the Bedrock repo, clone the existing project repo. As for step 4, the project repo probably comes with a theme so you can skip this.
2. Make sure you have SSH access to the remote server by following the steps under "Setting up and SSH connection to a remote server".
3. In the terminal on your local machine, go to the root directory of the project.
4. Run `gem install bundler` or, if that doesnt work, `sudo gem install bundler`. If that fails, make sure you have Ruby installed (which you very most likely have). As sepcified in [the bedrock-capistrano guide](https://github.com/roots/bedrock-capistrano#requirements).
5. Still in the root dir of your project, run `bundle install`.
6. You should now be able to execute step 15 under "Setting up deploys on your local machine" in this guide.

Syncing the database and uploads is outside the scope of this guide.

Make sure that you can SSH to the remote server(s) by following the steps under "Setting up an SSH connection to remote server"

##Setting up site folder on remote server
We need a directory and URL where the site will reside on the remote server(s) so, for Oderland, log in to cPanel and set that up as usual. You can set the doc root as usual for now, we will change that later on. Make sure that you can surf to the URL. While we are at it, set up a database to use as well and keep the username/password for later.

##Setting up an SSH connection to a remote server
Capistrano must be able to SSH to the server(s) to which deploys should be done. If you don't already have SSH access to the server(s), here's how to get that up and running when working on a a shared Oderland server:

1. Log in to cPanel and go to "SSH access" ("SSH-åtkomst")
2. Do one of the following: 
    - Fire up a new browser tab and go to https://www.oderland.se/clients/knowledgebase/102/Ansluta-med-SSH.html (a guide by Oderland in Swedish on how to set up SSH keys in cPanel in their environment) and follow the guide there.
    - Or, better yet, if you already have an existing keypair on your local machine (if you are uncertain, check this guide https://help.github.com/articles/checking-for-existing-ssh-keys/ ):
        1. Open the public key (with the .pub extension) in a texteditor
        2. Copy the contents of the public key
        3. Under SSH Access in cPanel, click "Import key"
        4. Give the key a name, preferrably one that identifies your machine
        5. Paste what you copied in step 2 to the "Public key" field
        6. Leave "private key" and "pass phrase" empty.
        7. Save and go back to "SSH access".
        8. Click on "handle" next to the newly imported key and then authorize it.
        
You should now be able to log in to the server by running the following in the terminal "ssh username@domain.void" where user is the master user name and server.void is the main domain for the Oderland account.        

##Setting up Composer on remote server
We also need to be able to run [Composer](http://getcomposer.org) on the server so let's set that up.

SSH to the server, go to the directory that you want to deploy to (that you set up under "Setting up site folder on remote server") and try running `composer`.

If the command works, you should be able to skip to the next section. If the command does not work, you need to add Composer. This is how we have done it at Oderland:

1. You need to put Composer somewhere on the server, a good idea is to put it in a directory named "bin" in the home directory. So run `mkdir -p ~/bin` which will create a directory named bin if it does not already exist. 
2. Navigate to the bin directory and run `php -r "readfile('https://getcomposer.org/installer');" | php` as described in the getting started guide on http://getcomposer.org.
4. You should now be able to run `php composer.phar about` and get a short info text about Composer in return.

I have tried adding composer as a global command using different versions of `mv composer.phar /usr/local/bin/composer` and adding it to PATH but can not get that to work when running Capistrano locally. So unless we can come up with a better solution we have to make sure to execute step 8 under "Setting up deploys to your local machine".

###Setting up deploys on your local machine

I have chosen [Bedrock-capistrano](https://github.com/roots/bedrock-capistrano) for deploys since Trellis is a bit more than I and Oderland can handle at the moment. Let's set it up using these steps taken from the README of [bedrock-capistrano](https://github.com/roots/bedrock-capistrano/blob/master/README.md):

1. In the terminal on your local machine, go to the root directory of the project ("bedrocktest.local" in our example).
2. Run `gem install bundler` or, if that doesnt work, `sudo gem install bundler`. If that fails, make sure you have Ruby installed (which you very most likely have).
3. Download [bedrock-capistrano](https://github.com/roots/bedrock-capistrano) and unzip it.
4. Carry out step 1-2 under ["Installation/configuration" in the bedrock-capistrano README](https://github.com/roots/bedrock-capistrano/blob/master/README.md#installationconfiguration).
5. Delete the bedrock-capistrano directory if it is in your project directory. There's no need for it anymore.
6. In the root dir of your project on your local machine, run `bundle install`.
7. Replace config/deploy.rb with [our modified deploy.rb](https://github.com/fewagency/best-practices/tree/master/Bedrock/deploy.rb). Search for "FEW" in that file if you want to see what we have changed/added. Make sure that you set correct values for every line starting with "set".
8. If we can not get composer to run globally on remote server, make sure that `SSHKit.config.command_map[:composer] = "~/bin/composer.phar"` has the correct value. If this value differs between servers, cut it from deploy.rb and add it to each file in config/deploy.
9. Carry out step 3 under [Installation/configuration in the bedrock-capistrano README](https://github.com/roots/bedrock-capistrano/blob/master/README.md#installationconfiguration).
    - On the line starting with "server", you need to specify the values that you use when SSH'ing to the remote server. 
    - Note that you, in the files in config/deploy/ can specify what branch you want to use. If you don't specify a branch here, the master branch will be used as set in deploy.rb. See deploy.rb for more info on this. 
10. Now might be a good time to make a git commit.
11. You should now be able to run the command `bundle exec cap <stage> deploy:check` as stated in step 4 in the bedrock-capistrano README. Replace <stage> with either staging or production depending on which environments you have configurated in /config/deploy. Granted that you are using a private repo, you will probably get an error telling you that permission was denied when talking to GitHub. Let's fix that by jumping to "Access private repos" in this guide and when you are done there, redo this step. If no such error ocurred, the server and machine user at GitHub has already been set up for this repo and you may go directly to the next step. Otherwise, see you back here in a bit.
12. Having reached this step, you will probably have run in to an error telling you that .env was not found. So let's fix that in a moment.
12. But first, change the document root to current/web on the remote servers. 
13. Upload a copy of .env.example to `/shared` on the remote server, rename it to .env and enter the correct data in it. Note that you will probably want to change WP_ENV to production even if you are setting things up on a staging server.
14. Also on the remote machine, create an empty .htaccess file in shared/web/.
15. Now, run `bundle exec cap <stage> deploy:check` again. Yoy may run into an error saying "fatal: Not a valid object name", This is due to the fact that you, in staging.rb, have specified a branch name that does not exist in your Git repo. So go ahead and fix that and , hopefully, successfully rerun this step.
15. Having come this far, on your local machine you can now run `bundle exec cap staging deploy` for a complete deploy. Ut should run successfully all the way to where the last output should be a message that a line was written to revision.log. If so, what just happened was:
    - You have just downloaded the git repo to the remote server to a new directory in releases which is named after the current date and time
    - `gulp --production` ran on your local machine and the dist-folder was uploaded to web/app/themes/THEMENAME/ in the newly created release-directory.
    - The symbolic link for current was changed to point to the new release-folder
    - If there were more than five releases, the oldest of them were deleted so there should be a maximum of four old folders and the current release in the release folder.
    - A line has been written to revision.log telling us that you performed a deploy and what revision of the git repo you deployed. 
16. You should now be able to go to http://example.com/wp/wp-admin and set up Wordpress.
17. Activate the theme we want to work with.
18. Visit the frontend to make sure it looks ok.
19. Do a css-change on your local machine, for example setting the body-bg to red in assets/style/main.scss
10. Run `bundle exec cap staging deploy`, wait for it to finish and reload the site on the remote server in your browser. The changes in the css should now be visible. Note that you didn't have to git commit anything when you only change assets since they are uploaded from your local machine.
11. Now make a change in the HTML/PHP code and commit and push the change.
12. Run `bundle exec cap staging deploy` again and when it has finished, you should see the changes on your remote server. 
 
##Add plugins
Plugins should also be handled using Composer. There's a guide on this under "Plugins" at https://roots.io/using-composer-with-wordpress/. Also some reading here about mu-plugins: https://roots.io/bedrock/docs/mu-plugins-autoloader/. Mu-plugins are must-use-plugins and is described here: https://codex.wordpress.org/Must_Use_Plugins .

However, there are some plugins such as Advanced Custom Fields Pro, that are not available as Composer packages. In that case, follow the steps outlined here to create a custom Composer package. We have created http://composerpackages.few.agency that we can use internally to store such packages. Example code for ACF Pro can be found further down in this document.

Below are some lines that will install some nice plugins. Add all of them or just some to require[] in composer.json in the root of your project.

```javascript
"elliot-condon/advanced-custom-fields-pro":"5.3.3.2",
"wpackagist-plugin/w3-total-cache": "dev-trunk",
"wpackagist-plugin/wordpress-seo": "dev-trunk",
"wpackagist-plugin/google-analytics-for-wordpress": "dev-trunk",
"wpackagist-plugin/admin-menu-editor": "dev-trunk"
```
If you included ACF and/or W3TC, read on. Otherwise, you can run `composer update` now.

For ACF to work, you must add the following snippet to repositories[] in your Composer file.

```javascript
{
  "type": "package",
  "package": {
    "name": "elliot-condon/advanced-custom-fields-pro",
    "version": "5.3.3.2",
    "type": "wordpress-plugin",
    "dist": {
      "type": "zip",
      "url": "http://SET_TO_POINT_TO_ACF_AT_OUR_COMPOSER_PACKAGES_SERVER"
    },
    "require" : {
      "fancyguy/webroot-installer": "1.1.0"
    }
  }
}
```

The snippet above should give you a clue on what to do if you run into other plugins without existing composer packages.

If you didn't include W3TC in the plugins, standing in the root of the project on your local machine, run `composer update`.

For W3TC to work, we need to do some extra stuff:

1. As said here: https://github.com/roots/bedrock/issues/38#issuecomment-170091932, add the below lines to ":linked_files" in deploy.rb.

```ruby
'web/app/advanced-cache.php',
'web/app/db.php',
'web/app/object-cache.php'
```

2. Then add these lines to ":linked_dirs"

```ruby
'web/app/uploads',
'web/app/cache',
'web/app/w3tc-config'
```

3. Run `composer update`on your local machine to install W3TC
4. Activate W3C locally if you want to.
5. If you do activate W3TC locally, make sure that the local versions of the files and directories that W3TC creates are ignored in the git repo. Note that these does not always include "w3tc" in the folder/file name so be sure to be extra careful about this.
6. Make sure that you move the lines that W3TC added in web/wp-config.php to the appropriate files in config/. If W3TC should be activated in all environments, simply move it to config/application.php. If it should only be added to for example production, move it to config/environments/production.php.
6. Create the files and folders that should be symlinked to in shares/web/app on the remote servers. These files and folders can just be empty files, W3TC will write to them later on.
7. Push composer-files to the git repo
8. Deploy using Capistrano.
9. Activate W3TC on remote server
10. Enable page cache
11. Visit the site without being logged in.
12. Check shared/web/app/cache/page_enhanced/ and make sure that there are some files and folders there that represent the pages you just visited.

##Access private repos
If you are working with a private repo, you need to be able to connect to it from the server. The steps below requires you to have SSH access to the remote server, so make sure that you have that by following the steps under "Setting up an SSH connection to remote server".
GitHub offers a [couple of solutions for managing deploy keys](https://developer.github.com/guides/managing-deploy-keys/) and we have chosen to use the Machine Users solution. This is mainly because the machine user can be put in a team that can have  read and fork access only to repos but also because a machine user allows us to set up the SSH key on the server once and then connect the machine user to the repos we want to deploy to the server.

We have created a machine user GitHub account who is part of the FEW Organization at GitHub and also a member of the team Machine Users. Login credentials for this user can be found at the usual place.

In order to avoid creating multiple keys for the machine user at a server, let's always use the same name the SSH key for the machine user on every server so we easily can see if the machine user already has a key. The name that the key should have can be found at the same place as the username/password. The name will be referred to as [MACHINE_USER] for the rest of this document.

How to set up SSH key for our machine user (taken in parts from [GitHubs SSH key guide](https://help.github.com/articles/generating-an-ssh-key/)):

1. Start by adding the machine user to your repo:
    - Log on to GitHub with your standard account (the one you created the repo with)
    - Go to the repos main page -> "Settings" -> "Collaborators and teams" and add the team "Machine users". Make sure that the access rights are set to "Read".
    - You should now be able to log on to GitHUb with the machine user account, navigate to the dashboard for FEW and be able to see the private repo in the list.
2. Now it's time to set up an SSH key for the machine user on the remote server. Let's start by SSHing to the remote server using `ssh USERNAME@SERVER.COM`.
3. Run `ls -al ~/.ssh` to list all existing keys.
4. If there already is a key with the name that we are looking for, jump to step 6
5. If a key named [MACHINE_USER] does not already exist, let's create one by running `ssh-keygen -t rsa -b 4096 -C "developers@fewagency.se"`
    - When asked where to store the key, enter `[PATH_TO_SSH_DIR]/[MACHINE_USER]`. For example `/home/fewgenc/.ssh/[MACHINE_USER]`
    - When asked for a password, just press enter. This is beacuse we can't have keys with passwords when Capistrano executes the git command on the server.
6. Now, we need to add the public key to the machine user on GitHub. If a [MACHINE_USER] key already existed, you might want to check if the key already has been connected. If you created a brand new key, skip to step 9, otherwise, go to next step.
7. Get the fingerprint of the key by running `ssh-keygen -lf /path/to/ssh/key` (replace the path with the path to the private version of [MACHINE_USER]).
8. Log on to GitHub with the machine user account and go to "[Settings -> SSH keys](https://github.com/settings/ssh)" and see if there is a finger print in the list matching the one listed in the step above. If there is, jump to step X, otherwise, go to the next step. 
9. Let's get the value of the public key by opening it in VI. Run `vi [PATH_TO_PUBLIC_KEY]`. In our example above it would be `vi /home/fewgenc/.ssh/[MACHINE_USER].pub`. It is *very important* to open the public key (.pub). Copy the content of the key using good old CMD-C.
10. Log on to GitHub with the machine user account and go to "[Settings -> SSH keys](https://github.com/settings/ssh)". Click "New SSH key", enter a name so that we can identify the server, paste the content of the public key in the key field and save.
11. Now, we need to tell the remote server to use our newly created key when communicating with GitHub, so run `vi ~/.ssh/config` to edit the config file.
12. Paste the following lines in VI (if not already present) and write-quit VI (:wq). IdentiyFile must point to the private SSH key for the machine user.

        Host github.com
          Hostname github.com
          IdentityFile ~/.ssh/[MACHINE_USER]
          User git

13. At the remote server, run `ssh -T git@github.com`. The response should be something like "Hi [MACHINE_USER]! You've successfully authenticated, but GitHub does not provide shell access.". If it is not, your best bet may be to start over from step 1 in this list.
14. If step 12 succeeds, run `git ls-remote -h git@github.com:USER/REPO.git` where the last argument should be the same SSH url of the repo to verify that you have access to the repo.

 




