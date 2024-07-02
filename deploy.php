<?php
namespace Deployer;

require 'recipe/symfony.php';
require 'contrib/rsync.php';


// Config

set('repository', 'git@github.com:OutznitAli/testproject');

set('git_ssh_command', 'ssh');
set('http_user', 'nobody');

set('default_timeout', 1800);

add('shared_files', []);
add('shared_dirs', ['var/sitemap', 'public/media']);
add('writable_dirs', ['var/sitemap', 'public/media', 'public/media/image']);

set('bin/php', "/opt/cpanel/ea-php81/root/usr/bin/php");
set('bin/composer', "/opt/cpanel/ea-php81/root/usr/bin/php /home/fcamaroc/sites/composer.phar");

set('rsync',[
    'exclude'      => [
        '.git',
        'deploy.php',
    ],
    'exclude-file' => false,
    'include'      => ['public/build'],
    'include-file' => false,
    'filter'       => [],
    'filter-file'  => false,
    'filter-perdir'=> false,
    'flags'        => 'rz', // Recursive, with compress
    'options'      => ['delete'],
    'timeout'      => 60,
]);
set('rsync_src', __DIR__.'/public/build');
set('rsync_dest','{{release_path}}/public/build');


// Hosts
host('shareconseil.com')
    ->set('remote_user', 'fcamaroc')
    ->set('deploy_path', '/home/fcamaroc/sites/test-project');

// Hooks

function whichLocally(string $name): string {
    $nameEscaped = escapeshellarg($name);

    // Try `command`, should cover all Bourne-like shells
    // Try `which`, should cover most other cases
    // Fallback to `type` command, if the rest fails
    $path = runLocally("command -v $nameEscaped || which $nameEscaped || type -p $nameEscaped");
    if (empty($path)) {
      throw new \RuntimeException("Can't locate [$nameEscaped] - neither of [command|which|type] commands are available");
    }

    // Deal with issue when `type -p` outputs something like `type -ap` in some implementations
    return trim(str_replace("$name is", "", $path));
}

task('build', function () {
//    cd('{{release_path}}');
    // var_dump(realpath('.'));
    // runLocally(realpath('.'))->;
    // whichLocally('nvm');
    //runLocally('nvm use v14.21.3');
    runLocally('yarn');
    //runLocally('yarn build');
    runLocally('yarn encore production');
})->once();;


//before('deploy:prepare', 'build');
//after('deploy:vendors', 'build');
after('deploy:vendors', 'rsync');

after('deploy:failed', 'deploy:unlock');
