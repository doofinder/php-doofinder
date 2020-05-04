<?php
spl_autoload_register('autoload_doofinder_classes');

function autoload_doofinder_classes($className) {
  $libraryPrefix = 'Doofinder\\';
  $libraryDirectory = __DIR__ . '/src/';

  $len = strlen($libraryPrefix);

  // Binary safe comparison of $len first characters
  if (strncmp($libraryPrefix, $className, $len) !== 0) {
    return;
  }

  $classPath = str_replace('\\', '/', substr($className, $len)) . '.php';
  $file = $libraryDirectory . $classPath;

  if (file_exists($file)) {
    require $file;
  }
}
