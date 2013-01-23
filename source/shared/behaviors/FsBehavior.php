<?php
namespace shared\behaviors;

use Exception, ErrorException, CException,
    RecursiveIteratorIterator, RecursiveDirectoryIterator;

/**
 * @property array                    $safeLocations;
 */
class FsBehavior extends \CBehavior
{

    //<editor-fold desc="public $safeLocations = 'root.runtime';">
    protected $_safeLocations;

    public function setSafeLocations($safeLocations) {
        if (!is_array($safeLocations)) {
            $safeLocations = array($safeLocations);
        }

        $safeLocations = array_filter($safeLocations);

        $preparedLocations = array();
        foreach ($safeLocations as $location) {
            $realLocation = $location[0] != '/' ? \Yii::getPathOfAlias($location) : $location;

            if (!$realpath = realpath($realLocation)) {
                throw new FSException(__METHOD__ . ": Unable to resolve $location to real path.");
            }

            $preparedLocations[] = $realpath;
        }

        $this->_safeLocations = array_filter($preparedLocations);
    }

    public function getSafeLocations() {
        if (!$this->_safeLocations) {
            $this->setSafeLocations(Yii()->params->itemAt('safeLocations') ? : 'root.runtime');
        }
        return $this->_safeLocations;
    }

    //</editor-fold>

    /**
     * Checks whether $path manipulations are safe
     *
     * @param $path
     *
     * @throws FSException
     */
    protected function checkPathSafety($path) {
        $args = is_array($path) ? $path : array($path);

        foreach ($args as $path) {
            $isPathSafe = false;
            foreach ($this->safeLocations as $safe) {
                if (strpos($path, $safe) === 0) {
                    $isPathSafe = true;
                    break;
                }
            }

            if (!$isPathSafe) {
                $message = "Unsafe path <$path>";
                $this->log($message, \CLogger::LEVEL_ERROR);
                throw new FSException($message);
            }
        }
    }

    /**
     * Ensures directory existence, creates recursively if needed
     *
     * @param $target
     *
     * @throws FSException
     * @return void
     */
    public function fsDir($target) {
        $this->checkPathSafety($target);
        $this->log("Ensuring directory <$target> exists and is accessible");

        if (file_exists($target)) {
            $this->fsCheckPaths($target, array('is_dir', 'is_writable', 'is_readable', 'is_executable'));
            return;
        }

        $path = $target;
        $stack = array();

        do {
            $stack[] = basename($path);
            $path = dirname($path);
        } while (!is_dir($path));

        try {
            $this->fsCheckPaths($path, array('is_dir', 'is_writable', 'is_executable'));
        } catch (FSException $e) {
            throw new FSException("Can not proceed for <$target> because it's base path {$e->getMessage()}");
        }
        foreach (array_reverse($stack) as $item) {
            $path .= DIRECTORY_SEPARATOR . $item;
            try {
                mkdir($path, 0777);
            } catch (ErrorException $e) {
                if (!is_dir($path)) {
                    throw new FSException("Failed to create <$target> on stage <$path> because of: {$e->getMessage()}", 0, $e);
                }
            }
        }
    }

    /**
     * Safely rename file
     * Both source's and destination's dirs should exist
     *
     * @param $source
     * @param $destination
     */
    public function fsRename($source, $destination) {
        $this->log("Renaming $source -> $destination");
        $this->checkPathSafety($source, $destination);

        if (is_dir($source)) {
            $this->fsCopy($source, $destination);
            $this->fsRemove($source);
        } else {
            $this->fsCheckPaths($source);
            $this->fsCheckPaths(dirname($destination), array('is_dir', 'is_executable', 'is_writable'));
            rename($source, $destination);
        }
    }

    public function fsFileAge($file) {
        $this->fsCheckPaths($file);
        return time() - filemtime($file);
    }

    public function fsCopy($source, $destination) {
        $this->log("Copying $source -> $destination");
        $this->checkPathSafety($source, $destination);
        $this->fsCheckPaths($source);
        $this->fsCheckPaths(dirname($destination), array('is_dir', 'is_executable', 'is_writable'));

        if (is_dir($source)) {
            \CFileHelper::copyDirectory($source, $destination);
        } elseif (is_file($source)) {
            copy($source, $destination);
        }
    }

    /**
     * "Hides" file or dir with optional suffix
     *
     * Example: file.zip => .file.zip.suffix
     *
     * @param      $source
     * @param null $suffix
     */
    public function fsHide($source, $suffix = null) {
        $this->log("Hiding $source");
        $dirname = dirname($source);
        $basename = basename($source);
        $to = "{$dirname}/.{$basename}" . ($suffix ? ".$suffix" : null);

        $this->fsRename($source, $to);
    }

    /**
     * Safely remove file
     *
     * @param string $source
     *
     * @return bool
     */
    public function fsRemove($source) {
        $this->log("Removing $source");
        $this->checkPathSafety($source);

        if (is_dir($source)) {
            /** @var $files \SplFileInfo[] */
            $files = $this->fsGetDirectoryIterator($source);

            foreach ($files as $file) {
                $this->rm($file);
            }

            $this->rm($source);
        } elseif (file_exists($source)) {
            $this->rm($source);
        }
    }

    private function rm($path) {
        $path = (string)$path;
        try {
            if (is_dir($path)) {
                rmdir($path);
            } elseif (file_exists($path)) {
                unlink($path);
            }
        } catch (ErrorException $e) {
            if (file_exists($path)) {
                throw $e;
            }
        }
    }

    public function fsGetDirectoryIterator($directory) {
        $this->fsCheckPaths($directory, array('is_dir', 'is_readable'));

        return new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
    }

    private function log($msg, $level = \CLogger::LEVEL_TRACE) {
        $msg = "[FS] $msg";
        try {
            if ($this->owner instanceof \CComponent) {
                $this->owner->log($msg, $level);
            } else {
                throw new FSException("Could not log through the owner");
            }

        } catch (\Exception $e) {
            \Yii::log($msg, $level, 'console.behaviors.fs');
        }
    }

    public function fsCheckPaths($paths, $callbacks = array('file_exists', 'is_readable')) {
        if (!is_array($paths)) {
            $paths = array($paths);
        }

        if (!is_array($callbacks)) {
            $callbacks = array($callbacks);
        }

        if ($callbacks != array_filter(
            $callbacks, function ($item) {
                return is_callable($item);
            }
        )
        ) {
            throw new FSException('not all callbacks are callable');
        }

        foreach ($paths as $file) {
            foreach ($callbacks as $callback) {
                if (!call_user_func($callback, $file)) {
                    throw new FSException("$file did not pass {$this->stringifyCallable($callback)} check");
                }
            }
        }
    }

    public function fsCalculateSize($files) {
        $this->fsCheckPaths($files);
        return array_reduce(
            $files, function ($current, $file) {
                return $current + filesize($file);
            }, 0
        );
    }

    private function stringifyCallable($callable) {
        if (is_string($callable)) {
            return $callable;
        }

        if (is_object($callable)) {
            return get_class($callable);
        }

        if (is_array($callable)) {
            list($class, $method) = $callable;
            $class = $this->{__FUNCTION__}($class);
            return "$class::$method";
        }

        return 'callable';
    }
}

class FSException extends CException
{
}
