job_type :symf_command, '/usr/local/bin/php -q /www/sitebeat.kelnik.ru/current/app/console :task --env=:environment'

every 1.day, :at => '9 am' do
  symf_command "sitebeat:test:run"
end