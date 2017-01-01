<?php
// This file is directly (and only) used by WP-CLI.
if (! defined('WP_CLI') || ! WP_CLI) {
    return;
}

// Have the remote WP-CLI configure itself to use a custom logger.
WP_CLI::add_hook('before_ssh', function () {
    $option = array_search('--require=src/WpcliLogger.php', $GLOBALS['argv'], true);
    if ($option === false) {
        return;
    }

    $bootstrap = '/tmp/wordhat-wpcli-bootstrap.php';

    // Have remote WP-CLI download the custom logger and verify checksum.
    $command = sprintf(
        'curl %1$s --create-dirs -o %2$s -s -S && CHECKSUM=$(cat %2$s | openssl dgst -sha1 -binary | xxd -p) && [ "$CHECKSUM" = "2dd805188481c5f4034b1a5c1073dcda8eac9261" ] || { echo >&2 "WP-CLI driver SSH bad checksum."; exit 1; }',
        'https://gist.githubusercontent.com/paulgibbs/8b9bae17ba66d03644a8520762f9ca1b/raw/0591a77f09a013cc7bddc2bf139d73b7d50d01e6/wpcli-shouty-logger.php',
        $bootstrap
    );

    // Have local WP-CLI pass instructions to remote WP-CLI.
    putenv("WP_CLI_SSH_PRE_CMD={$command}");

    // Modify `--require` arg to have remote WP-CLI load the custom logger.
    $GLOBALS['argv'][$option] = "--require={$bootstrap}";
    $GLOBALS['argv'][]        = '--debug';
});
