<?php
// Reads key=value settings from the git-ignored .env file at the repo root.
// Secrets live only in that file - never in source, never in the repo.

function env_all()
{
    static $vars = null;
    if ($vars !== null) {
        return $vars;
    }

    $vars = [];
    $path = __DIR__ . '/../../.env';
    if (!is_readable($path)) {
        return $vars;
    }

    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#') {
            continue;
        }
        $pos = strpos($line, '=');
        if ($pos === false) {
            continue;
        }
        $key = trim(substr($line, 0, $pos));
        $val = trim(substr($line, $pos + 1));
        $quoted = strlen($val) > 1 && ($val[0] === '"' || $val[0] === "'");
        if ($quoted && substr($val, -1) === $val[0]) {
            $val = substr($val, 1, -1);
        }
        $vars[$key] = $val;
    }
    return $vars;
}

function env($key, ...$default)
{
    $vars = env_all();
    if (array_key_exists($key, $vars) && $vars[$key] !== '') {
        return $vars[$key];
    }
    if ($default) {
        return $default[0];
    }
    error_log("WAPH-Team10 required setting $key is missing from .env");
    http_response_code(500);
    exit('Configuration error.');
}

function env_bool($key, $default)
{
    $val = strtolower((string) env($key, $default ? 'true' : 'false'));
    return in_array($val, ['1', 'true', 'yes', 'on'], true);
}
