<?php
namespace Deployer;

require 'recipe/common.php';

// Project name
set('application', 'app_name');

// Project repository
set('repository', 'ssh://git@gitlab.com/user/repository');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true); 

// Shared files/dirs between deploys 
set('shared_files', [
    '.env',
    'public/.htaccess',
]);
set('shared_dirs', [
    'public/assets'
]);

// Writable dirs by web server 
set('writable_dirs', [
    'public/assets'
]);
set('allow_anonymous_stats', false);

// Hosts

host('dev')
    ->hostname('hostname.example.com')
    ->user('user')
    ->set('deploy_path', '~/{{application}}');    
    

// Tasks

desc('Deploy your project');
task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:writable',
    'deploy:vendors',
    'ss4:build',
    'deploy:clear_paths',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
    'success'
]);

task('ss4:build', '
    ./vendor/silverstripe/framework/sake dev/build flush=1
')->desc('Run dev/build?flush=1');

// [Optional] If deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');