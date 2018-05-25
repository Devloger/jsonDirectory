<?php

include 'Remover.php';

function directoryStructureToJson($location = '', $key = null, $first = 1)
{
    $allDirectories = [];
    $directories = glob($location.'*', GLOB_ONLYDIR);
    foreach($directories as $directory) {
        if(glob($directory.'/*', GLOB_ONLYDIR)) {
            $allDirectories[basename($directory)] = directoryStructureToJson($directory.'/', null, null);
        } else {
            $allDirectories[] = basename($directory);
        }
    }
    return $first ? json_encode($allDirectories) : $allDirectories;
}

function directoryStructureFromJSON($directoryStructure, $location = '')
{
    if (is_string($directoryStructure)) {
        $directoryStructure = json_decode($directoryStructure);
    }
    foreach ($directoryStructure as $key => $directoryElement) {
        if (is_array($directoryElement) || is_object($directoryElement)) {
            if (! file_exists($location . $key)) {
                mkdir($location . $key);
            }
            directoryStructureFromJSON($directoryElement, $location . $key . '/');
        } else {
            if (! file_exists($location . $directoryElement)) {
                mkdir($location . $directoryElement);
            }
        }
    }
}

$structure = directoryStructureToJson('here/');
Remover::allInDirectory('here');
directoryStructureFromJSON($structure, 'here/');
