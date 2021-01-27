<?php

/**
 * Overrides files
 *
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
 * @param string $message
 * @param bool $verbose
 */
function verbose(string $message, $verbose = true) {
    if ($verbose) {
        echo "\n[INFO] " . $message . "\n";
    }
}

/**
 *
 *  Configuration structure:
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

/**
 * Load settings
 */
$settings = json_decode(file_get_contents('settings.json'), 1);

$isSettingsWellFormed = $settings &&
    array_key_exists('absolute_path', $settings) &&
    array_key_exists('directory', $settings) &&
    array_key_exists('git_remote', $settings) &&
    array_key_exists('overrides', $settings) &&
    is_array($settings['overrides']);

if (!$isSettingsWellFormed) {
    die('File settings.json is not well formed! Check original one on github in order to verify it.');
}

$absolutePath = $settings['absolute_path'];
$directory = $settings['directory'];
$gitRemote = $settings['git_remote'];
$overrides = $settings['overrides'];

$temporalProjectDirectory = $absolutePath . '/' . $settings['tmp_dirname'];
$finalProjectDirectory = $absolutePath . '/' . $directory;

/**
 * Run deployment
 */
try {
    verbose('Before script init.');
    /**
     * [Script] Before.
     */
    include 'custom/before.php';
    verbose('Before script end.');

    /**
     * Clone project on temporal directory
     */
    if (file_exists($temporalProjectDirectory)) {
        throw new Exception('Temporal project directory needs to be removed.');
    }

    mkdir($temporalProjectDirectory);

    /**
     * @todo allow to deploy private repositories. Anyway if you are reading it, you'll be able add credentials here!
     */
    exec('git clone ' . $gitRemote . ' ' . $temporalProjectDirectory);

    /**
     * Override configuration
     */
    overrideConfiguration($overrides);

    verbose('Cloned script init.');
    /**
     * [Script] Cloned
     */
    include 'custom/cloned.php';
    verbose('Cloned script end.');

    $backupDirectory = $absolutePath . '/back_' . time();
    rename($finalProjectDirectory, $backupDirectory);

    rename($temporalProjectDirectory, $finalProjectDirectory);

    verbose('Done script init.');
    /**
     * [Script] Done
     */
    include 'custom/done.php';
    verbose('Done script end.');

} catch (\Throwable $throwable) {
    verbose('Error script init.');
    include 'custom/_error.php';
    verbose('Error script end.');
}
