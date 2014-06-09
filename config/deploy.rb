# config valid only for Capistrano 3.1
lock '3.1.0'

set :application, 'lvac'
set :repo_url, 'git@github.com:LiffeyValleyAC/website.git'

set :deploy_to, '/var/www/lvac'

### set :linked_files, %w{config/database.yml}

namespace :deploy do

  desc 'Install composer dependencies'
  task :composer do
    on roles(:web) do
      within '/var/www/lvac/current' do
        execute :composer, 'install', '--no-dev'
      end
    end
  end

  desc 'Restart php-fpm'
  task :restart_fpm do
    on roles(:web) do
        #execute :service, 'php5-fpm', 'reload'
        execute "sudo /usr/sbin/service php5-fpm reload"
    end
  end

  desc 'Create the sqlite database'
  task :create_db do
    on roles(:web) do
      within '/var/www/lvac/current' do
        execute "php cli/read_dump.php"
      end
    end
  end

  after :finished, :composer
  after :composer, :restart_fpm
  after :restart_fpm, :create_db

end
