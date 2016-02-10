#W3 Total Cache with Bedrock

W3TC and other cache plugins are sometimes frowned upon by people who recommends using Varnish or Memcached instead. Unfortunately it is not always possible to use those systems, fore example when in a shared environment.
 
Using W3TC with Bedrock does require a bit of work but it is probably worth it in order to have a faster website.  

##Install
1. Add the following line to composer.json `"wpackagist-plugin/w3-total-cache": "dev-trunk"` and run `composer update`.
2. Your should now be able to activate the plugin on your local machine.
3. After successfully activating the plugin, set the settings that you would like the remote servers to have for the plugin.
4. In web/app, there should now be a couple of new files and directories such as cache/, w3tc-config/, advanced-cache.php. Which W3TC files there are depend on the settings you have done so make sure to check all files in web/app/ to make sure that you don't miss any. Copy the files that you have found to /shared/web/app on the remote server(s). For the folders, simply create an empty web/app/cache and web/app/w3tc-config folder on the server, W3TC will write to them later on. 
5. We need to symlink the files and folders when deploying so add the following lines (based in discussion here https://github.com/roots/bedrock/issues/38#issuecomment-170091932 ) to deploy.rb (or only to the config/deploy/*.rb where W3TC is activated)
 Add all the files that you have found to to :linked_files, like this
 
        'web/app/advanced-cache.php'
 
6. Now, we need to do the same with the folders. Add them to ":linked_dirs" like this:
 
        'web/app/cache',
        'web/app/w3tc-config'
        
6. Make sure that all the files and folders that you have just uploaded to /shared/web/app are added to .gitignore in order to keep them out of the repo.  
7. Open up /web/wp-config.php on yout local machine and cut the line holding the WP_CACHE constant.
8. Paste the cut snippet to either config/application.php or to the files in config/environments/ representing the environments where you want W3TC activated.
9. Push the updated composer.json file to the GitHub repo. This should be the only file in the repo that you have edited since step 1.
10. eploy using Capistrano.
11. Activate W3TC in Wordpress on the remote server.
12. Back in Wp Admin on your local machine, go to "General Settings" for W3TC and scroll to teh bottom where you can export config. Do that.
13. Import config on remote servers by navigating to the same spot as you did above. 
14. Visit the remote site *without being logged in*.
15. Check shared/web/app/cache/page_enhances/ and make sure that some files have been created there. You can also check the last lines of source code of a page to see that W3TC is running. Try reloading it and make sure that the timestamp does not change on every reload.

##Clear cache on deploy
We will want to clear the cache on every deploy. Especially if we are using Sage that changes the file name on the CSS/JS files for every deploy and deletes the old files.
 
Here's how to do that:
 
1. Add this snippet to deploy.rb:

        namespace :deploy do
        
         task :bustw3tccache do
           on roles(:app) do
             execute "rm -rf #{fetch(:deploy_to)}/shared/web/app/cache/*"
           end
         end
        
        end

2. Add these line to the end of deploy.rb: 

        after 'deploy:assets', 'deploy:bustw3tccache'
        after 'deploy:assetsonly', 'deploy:bustw3tccache'
        after 'deploy:rollback', 'deploy:bustw3tccache'
 
