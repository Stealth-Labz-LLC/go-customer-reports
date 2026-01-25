<?php
/**
 * Add noindex,nofollow to campaign/funnel PHP files
 *
 * Usage: php cli/add-noindex.php [--dry-run]
 */

$dryRun = in_array('--dry-run', $argv ?? []);
$dirs = ['cr', 'eb', 'ee25', 'qr', 'sc', 'ss'];
$baseDir = dirname(__DIR__);

echo "Adding noindex,nofollow to campaign files\n";
echo $dryRun ? "DRY RUN\n" : "LIVE RUN\n";
echo str_repeat('-', 50) . "\n\n";

$updated = 0;
$skipped = 0;
$noHead = 0;

foreach ($dirs as $dir) {
    $path = $baseDir . '/' . $dir;
    if (!is_dir($path)) continue;

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($path)
    );

    foreach ($iterator as $file) {
        if ($file->getExtension() !== 'php') continue;

        $content = file_get_contents($file->getPathname());
        $relativePath = str_replace($baseDir . '/', '', $file->getPathname());

        // Already has noindex?
        if (stripos($content, 'noindex') !== false) {
            echo "[SKIP] {$relativePath} - already has noindex\n";
            $skipped++;
            continue;
        }

        // Find <head> tag and insert after it
        if (preg_match('/<head[^>]*>/i', $content, $matches, PREG_OFFSET_CAPTURE)) {
            $headTag = $matches[0][0];
            $headPos = $matches[0][1];
            $insertPos = $headPos + strlen($headTag);

            $noindexTag = "\n    <meta name=\"robots\" content=\"noindex, nofollow\">";
            $newContent = substr($content, 0, $insertPos) . $noindexTag . substr($content, $insertPos);

            if (!$dryRun) {
                file_put_contents($file->getPathname(), $newContent);
            }

            echo "[OK] {$relativePath}\n";
            $updated++;
        } else {
            echo "[NO HEAD] {$relativePath}\n";
            $noHead++;
        }
    }
}

echo "\n" . str_repeat('-', 50) . "\n";
echo "Updated: {$updated} | Skipped: {$skipped} | No head tag: {$noHead}\n";

if ($dryRun) {
    echo "\nRun without --dry-run to apply changes.\n";
}
