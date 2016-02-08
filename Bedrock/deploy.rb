# deploy.rb from https://github.com/roots/bedrock-capistrano modified by FEW Agency, developers@fewagency.se

# FEW-comment: from Capistrano doc:
# Here we'd set the name of the application, ideally in a way that's safe for
# filenames on your target operating system.
set :application, 'APPLICATIONNAME'

# FEW-comment: use the SSH url for the repo from GitHub
set :repo_url, 'git@github.com:USER/REPO.GIT'

# FEW-addition: name of the dir where theme is placed. Not the entire path.
set :theme_directory_name, 'THEME_DIR_NAME'

# FEW-addition. Use in case composer command does not work. Set value to point to where you put composer.phar on remote
# https://discourse.roots.io/t/deploying-wordpress-with-capistrano-screencast/863/25
SSHKit.config.command_map[:composer] = "~/bin/composer.phar"

# FEW-comment: this should be set to the target directory of the deploy on the server.
# So if your site is placed in /home/few/sites/bedrock-test.com/, that is the path to use.
# Make sure the path starts at the root directory and ends with a /
set :deploy_to, -> { "/PATH" }

# FEW-addition. We must change tmp dir since Oderland does not allow us to execute files placed in /tmp/
# Set it to a nice place, preferrably outside any public folders. Should not end with a /
set :tmp_dir, "/PATH"

# Branch options
# Prompts for the branch name (defaults to current branch)
#ask :branch, -> { `git rev-parse --abbrev-ref HEAD`.chomp }

# Hardcodes branch to always be master
# This could be overridden in a stage config file
set :branch, :master

# FEW addition. Disable forward agent for, what appears to be, increased security
set :ssh_options, {
  :forward_agent => false
}

# Use :debug for more verbose output when troubleshooting
set :log_level, :debug

# Apache users with .htaccess files:
# it needs to be added to linked_files so it persists across deploys:
set :linked_files, fetch(:linked_files, []).push('.env', 'web/.htaccess')
#set :linked_files, fetch(:linked_files, []).push('.env')
set :linked_dirs, fetch(:linked_dirs, []).push('web/app/uploads')

namespace :deploy do
  desc 'Restart application'
  task :restart do
    on roles(:app), in: :sequence, wait: 5 do
      # Your restart mechanism here, for example:
      # execute :service, :nginx, :reload
    end
  end
end

# The above restart task is not run by default
# Uncomment the following line to run it on deploys if needed
# after 'deploy:publishing', 'deploy:restart'

namespace :deploy do
  desc 'Update WordPress template root paths to point to the new release'
  task :update_option_paths do
    on roles(:app) do
      within fetch(:release_path) do
        if test :wp, :core, 'is-installed'
          [:stylesheet_root, :template_root].each do |option|
            # Only change the value if it's an absolute path
            # i.e. The relative path "/themes" must remain unchanged
            # Also, the option might not be set, in which case we leave it like that
            value = capture :wp, :option, :get, option, raise_on_non_zero_exit: false
            if value != '' && value != '/themes'
              execute :wp, :option, :set, option, fetch(:release_path).join('web/wp/wp-content/themes')
            end
          end
        end
      end
    end
  end
end

# The above update_option_paths task is not run by default
# Note that you need to have WP-CLI installed on your server
# Uncomment the following line to run it on deploys if needed
# after 'deploy:publishing', 'deploy:update_option_paths'

# FEW-addition
# Code below will make sure that gulp --production is run on deploy and assets uploaded. This assumes
# that you are using the Sage starter theme or anything else that has a production Gulp task.
# after release directory has been created but before symlink is changed.
# This is based on https://gist.github.com/jaywilliams/c0abbec89ef6bc81cb49
# https://discourse.roots.io/t/using-bedrock-sage-to-deploy-with-capistrano-theme-gulp-dist-files/3325/15
# https://discourse.roots.io/t/using-bedrock-sage-to-deploy-with-capistrano-theme-gulp-dist-files/3325/20

namespace :deploy do

  # Theme path
  set :theme_path, Pathname.new('web/app/themes').join(fetch(:theme_directory_name))

  # Local Paths
  set :local_app_path, Pathname.new(Dir.pwd)
  set :local_theme_path, fetch(:local_app_path).join(fetch(:theme_path))
  set :local_dist_path, fetch(:local_theme_path).join('dist')

  task :compile do
    #print " Local theme path #{fetch(:local_theme_path)} "
    run_locally do
      #within fetch(:local_theme_path) do
        #execute 'gulp --production' # http://stackoverflow.com/a/19459221
        # FEW-modification: execute gulp on absolute path
        execute "cd #{fetch(:local_theme_path)}; gulp --production"
      #end
    end
  end

  task :copy do
    on roles(:web) do

      # Remote Paths (Lazy-load until actual deploy)
      set :remote_dist_path, -> { release_path.join(fetch(:theme_path)).join('dist') }

      print " Your local distribution path: #{fetch(:local_dist_path)} "
      print " Your remote distribution path: #{fetch(:remote_dist_path)} "
      print " Uploading files to remote "
      upload! fetch(:local_dist_path).to_s, fetch(:remote_dist_path), recursive: true
    end
  end

  task assets: %w(compile copy)
end

after 'deploy:updated', 'deploy:assets'
