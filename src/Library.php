<?php

function printErrorMessageAndExit(string $message): void {
    // Use bold red color
    // https://gist.github.com/fnky/458719343aabd01cfb17a3a4f7296797#color-codes
    fwrite(STDERR, "\x1b[1;31m");

    fwrite(STDERR, "$message" . PHP_EOL);

    // Reset color.
    fwrite(STDERR, "\x1b[0m");

    exit(1);
}

function printInfoMessage(string $message): void {
    // Use green color
    fwrite(STDOUT, "\x1b[32m");

    fwrite(STDOUT, "$message" . PHP_EOL);

    // Reset color.
    fwrite(STDOUT, "\x1b[0m");
}

function printNoticeMessage(string $message): void {
    // Use cyan color
    fwrite(STDOUT, "\x1b[36m");

    fwrite(STDOUT, "$message" . PHP_EOL);

    // Reset color.
    fwrite(STDOUT, "\x1b[0m");
}

function copyRecursive(string $source, string $destination): void {
    if (!file_exists($destination)) {
        if (!mkdir($destination)) {
            printErrorMessageAndExit(
                sprintf('Failed to create directory: %s', $destination)
            );
        }
    }

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator(
            $source,
            RecursiveDirectoryIterator::SKIP_DOTS
                | RecursiveDirectoryIterator::FOLLOW_SYMLINKS
        ),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($iterator as $fullPath => $splFileInfo) {
        $relativePathOfSourceItem = str_replace($source, '', $splFileInfo->getPathName());

        if ($splFileInfo->isDir()) {
            if (!file_exists($destination . $relativePathOfSourceItem)) {
                if (!mkdir($destination . $relativePathOfSourceItem)) {
                    printErrorMessageAndExit(
                        sprintf(
                            'Failed to create directory: %s',
                            $destination . $relativePathOfSourceItem,
                        )
                    );
                }
            }
        } else {
            if (!copy($fullPath, $destination . $relativePathOfSourceItem)) {
                printErrorMessageAndExit(
                    sprintf(
                        'Failed to copy "%s" to "%s"',
                        $fullPath,
                        $destination . $relativePathOfSourceItem,
                    )
                );
            }
        }
    }
}
