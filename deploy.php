<?php
namespace Deployer;

require 'recipe/symfony4.php';

// Project name
set('application', 'lesPlates_permanences');

// Project repository
set('repository', 'git@github.com:cirdan/les-plates-permanences.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true); 

// Shared files/dirs between deploys 
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server 
add('writable_dirs', []);


// Hosts

host('draft.permanences.lesplates.fr')
    ->stage('prod')
    ->set('deploy_path', '/opt/permanences.lesplates.fr');
    
// Tasks

task('build', function () {
    run('cd {{release_path}} && build');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'database:migrate');

