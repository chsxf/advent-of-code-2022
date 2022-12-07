<?php
require_once('../common.inc.php');
$lines = loadLines(__FILE__);

require_once('day7.inc.php');

define('TOTAL_DISK_SIZE', 70000000);
define('NEEDED_SIZE', 30000000);

$remainingSpace = TOTAL_DISK_SIZE - $currentFolder->computeSize();

$selectedFolders = [];
function identifyFolders(Folder $folder) {
    global $selectedFolders, $remainingSpace;

    $size = $folder->computeSize();
    if ($size + $remainingSpace > NEEDED_SIZE) {
        $selectedFolders[$folder->name] = $size;
    }

    foreach ($folder->folders as $childFolder) {
        identifyFolders($childFolder);
    }
}
identifyFolders($currentFolder);

var_dump(min($selectedFolders));