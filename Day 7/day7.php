<?php
require_once('../common.inc.php');
$lines = loadLines(__FILE__);

require_once('day7.inc.php');

$selectedFolders = [];
function identifyFolders(Folder $folder) {
    global $selectedFolders;

    $size = $folder->computeSize();
    if ($size <= 100000) {
        $selectedFolders[$folder->name] = $size;
    }

    foreach ($folder->folders as $childFolder) {
        identifyFolders($childFolder);
    }
}
identifyFolders($currentFolder);

$totalSize = array_sum($selectedFolders);
var_dump($totalSize);