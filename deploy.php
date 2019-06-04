<?php

namespace Deployer;

require_once __DIR__ . '/vendor/in10/deployment/recipes/in10.recipe.php';

// Configuration
set('application', '[PROJECTNAME]');
set('repository', 'git@github.com:IN10/[PROJECT_GIT_NAME].git');

// Define stages
host('test')
    ->hostname('fotomuseum-api.t05.in10projecten.nl')
    ->user('forge')
    ->stage('test')
    ->set('keep_releases', 2)
    ->set('deploy_path', '~/fotomuseum-api.t05.in10projecten.nl');

// host('acceptance')
//   ->hostname('[host]')
//   ->user('[username]')
//   ->stage('acceptance')
//   ->set('deploy_path', '~/www');

// host('production')
//   ->hostname('[host]')
//   ->user('[username]')
//   ->stage('production')
//   ->set('deploy_path', '~/www');

// Define any custom tasks for this project below
task('build:frontend', function () {})->local();
