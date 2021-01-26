<?php

/**
 * @param array $overrides
 */
function overrideConfiguration($overrides = []) {
    for ($i = 0; $i < count($overrides); $i++) {
        file_put_contents($overrides[$i]['origin'],
            file_get_contents($overrides[$i]['replace'])
        );
    }
}

/**
{
    "absolute_path": "",
    "directory": "",
    "git_remote": "",
    "overrides": [
        {
            "origin": "",
            "replace": ""
        }
    ]
}
 */
$settings = json_decode(file_get_contents('settings.json'), 1);

$absolutePath = $settings['absolute_path'];
$directory = $settings['directory'];
$gitRemote = $settings['git_remote'];
$overrides = $settings['overrides'];

mkdir($absolutePath . '/tmp_project');

exec('git clone ' . $gitRemote . ' ' . $absolutePath . '/tmp_project');

overrideConfiguration($overrides);

exec('cd ' . $absolutePath . '/tmp_project && php composer.phar install');

rename($absolutePath . '/' . $directory, $absolutePath . '/back_' . time());

rename($absolutePath . '/tmp_project', $absolutePath . '/' . $directory);
