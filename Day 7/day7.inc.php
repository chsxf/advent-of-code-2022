<?php
final class Folder {
    public $files = [];
    public $folders = [];

    public function __construct(public readonly string $name, public readonly ?Folder $parentFolder = null) { }

    public function computeSize(): int {
        $fileSize = 0;
        foreach ($this->files as $file) {
            $fileSize += $file->size;
        }

        $folderSize = 0;
        foreach ($this->folders as $folder) {
            $folderSize += $folder->computeSize();
        }

        return $fileSize + $folderSize;
    }

    public function dumpSize($indent = 0) {
        $indentStr = str_pad('', $indent, ' ') . ' -';
        printf("%s %s %d\n", $indentStr, $this->name, $this->computeSize());
        foreach ($this->folders as $folder) {
            $folder->dumpSize($indent + 1);
        }
    }

    public function dump($indent = 0) {
        $indentStr = str_pad('', $indent, ' ');
        printf("%s - %s %d\n", $indentStr, $this->name, $this->computeSize());
        foreach ($this->files as $file) {
            printf("%s   %s %d\n", $indentStr, $file->name, $file->size);
        }
        foreach ($this->folders as $folder) {
            $folder->dump($indent + 1);
        }
    }
}

final class File {
    public function __construct(public readonly string $name, public readonly int $size) { }
}

$currentFolder = null;

foreach ($lines as $line) {
    if (preg_match('/^\$ (\S+)(?: (\S+))?$/', $line, $regs)) {
        if ($regs[1] == 'cd') {
            if ($regs[2] == '..') {
                $currentFolder = $currentFolder->parentFolder;
            }
            else {
                $newFolder = new Folder($regs[2], $currentFolder);
                if ($currentFolder !== null) {
                    $currentFolder->folders[] = $newFolder;
                }
                $currentFolder = $newFolder;
            }
        }
    }
    else if (preg_match('/^(\d+) (.+)$/', $line, $regs)) {
        $size = intval($regs[1]);
        $name = $regs[2];
        $currentFolder->files[] = new File($name, $size);
    }
}

while ($currentFolder->parentFolder !== NULL) {
    $currentFolder = $currentFolder->parentFolder;
}

