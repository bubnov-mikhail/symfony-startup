set :stages,        %w(staging)
set :default_stage, "staging"
set :stage_dir,     "app/config/capifony"
require 'capistrano/ext/multistage'