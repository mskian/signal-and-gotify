<?php

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Filesystem;

function loadConfig($filePath)
{
    try {
        $fileSystem = new Filesystem();

        if (!$fileSystem->exists($filePath)) {
            throw new FileNotFoundException("Config file not found: $filePath");
        }

        $yaml = file_get_contents($filePath);

        $config = Yaml::parse($yaml);

        if (!isset($config['api']['signal_url']) || !isset($config['api']['gotify_url'])) {
            throw new ParseException("Invalid YAML structure. Missing 'api' section or 'signal_url'/'gotify_url' keys.");
        }

        return $config;
    } catch (FileNotFoundException $e) {
        echo json_encode(['error' => 'Config file not found']);
        exit;
    } catch (ParseException $e) {
        echo json_encode(['error' => 'Invalid YAML file format']);
        exit;
    }
}

function getHomeDirectory()
{
    $home = getenv('HOME');

    if (!empty($home)) {
        return $home;
    }

    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        $userProfile = getenv('USERPROFILE');
        if (!empty($userProfile)) {
            return $userProfile;
        }

        $homeDrive = getenv('HOMEDRIVE');
        $homePath = getenv('HOMEPATH');
        if (!empty($homeDrive) && !empty($homePath)) {
            return $homeDrive . $homePath;
        }
    }

    return '/';
}

$configPath = getHomeDirectory() . DIRECTORY_SEPARATOR . 'pushnotify.yml';
$config = loadConfig($configPath);

$signalUrl = $config['api']['signal_url'];
$gotifyUrl = $config['api']['gotify_url'];
