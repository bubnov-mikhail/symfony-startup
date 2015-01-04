set :application, "sitebeat.kelnik.ru"
set :domain,      "84.52.64.39"
set :deploy_to,   "/www/#{application}"
set :app_path,    "app"

default_run_options[:pty] = true

set :repository,  "ssh://git@github.com/bubnovKelnik/sitebeat"
set :scm,         :git
set :branch, "master"

set :ssh_options, {:forward_agent => true, :port => 22}
set :user, "mikessh"
set :password, "greendog"
set :scm_passphrase, "greendog1985"
set :use_sudo, false
set :deploy_via, :remote_cache
set :interactive_mode, false

set :model_manager, "doctrine"

role :web,        domain
role :app,        domain, :primary => true
role :db,         domain

set :shared_files,      ["app/config/parameters.yml"]
set :shared_children,     [app_path + "/logs", web_path + "/uploads" , "vendor"]
set :dump_assetic_assets, true
set :use_composer, true
set :update_vendors, false
set :symfony_env_prod, "prod"

set  :keep_releases,  5
after "deploy", "deploy:cleanup"

logger.level = Logger::MAX_LEVEL

set :writable_dirs,     ["app/cache", "app/logs"]
set :webserver_user,    "www"
set :permission_method, :acl
set :use_set_permissions, false

# wheneverize!
require "whenever/capistrano"
set :whenever_variables, ""
set :whenever_command, "whenever --load-file app/config/schedule.rb --set environment=#{symfony_env_prod}"

# PHP error log - /var/log/httpd-error.log
after "deploy:create_symlink" do
    # Symlink to ckeditor/plugins/autogrow
    run "ln -nfs /www/#{application}/shared/web/bundles/ivoryckeditor/plugins/autogrow /www/#{application}/current/web/bundles/ivoryckeditor/plugins/autogrow"
    # Symlink to ckeditor/plugins/strinsert
    run "ln -nfs /www/#{application}/shared/web/bundles/ivoryckeditor/plugins/strinsert /www/#{application}/current/web/bundles/ivoryckeditor/plugins/strinsert"
end