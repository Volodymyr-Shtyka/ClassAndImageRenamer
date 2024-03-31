<?php

/*
 * This script, AutoRenameTool.php, is specifically designed to facilitate the process of automatically
 * renaming class names and updating image filenames within a project's files, focusing primarily on CSS, HTML,
 * and JavaScript files. It initiates the process by targeting CSS files to gather class names, then systematically
 * updates these class names and image references throughout the project to maintain consistency and ensure
 * uniformity in naming conventions.
 *
 * Purpose:
 * The primary goal of this tool is to aid in refactoring projects by ensuring that class and image filenames
 * are renamed systematically across the project, reducing the potential for errors and improving the clarity
 * and maintainability of the codebase.
 *
 * Precaution:
 * Before running this script, it is highly recommended to temporarily remove or exclude external CSS and JavaScript
 * libraries, such as Bootstrap, jQuery, and similar dependencies from the project directory. This precaution
 * is necessary to prevent unintended renaming within these libraries, which could lead to functionality issues or
 * errors due to missing or incorrectly referenced classes and files. After the script has been executed and the
 * necessary verifications have been made to ensure that the project operates as expected, the libraries can be
 * restored to their original location within the project.
 *
 * It is imperative to perform thorough testing in a controlled environment prior to applying this script to live
 * projects to ensure that all renaming are accurate and that the project integrity is maintained post-execution.
 */

// Retrieves the current working directory where the script is executed.
$directory = getcwd();

/**
 * Generates a random file name, optionally with a specified extension.
 *
 * @param string $extension File extension to append to the generated name.
 * @return string The generated file name with or without an extension.
 */
function generateRandomFileName(string $extension = ''): string
{
    $randomString = substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(10 / strlen($x)))), 1, 10);
    return $randomString . ($extension ? '.' . $extension : '');
}

/**
 * Generates a random class name that starts with a letter to comply with naming rules.
 *
 * @param int $length The desired length of the generated class name.
 * @return string The generated class name.
 */
function generateRandomClassName(int $length = 8): string
{
    // Ensures the first character is a letter.
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = $characters[rand(0, strlen($characters) - 1)];

    // Allows numbers after the first character.
    $characters .= '0123456789';
    for ($i = 1; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

/**
 * Finds all class names in CSS files and replaces them in all HTML, CSS, and JS files.
 *
 * @param string $dir The directory to search in.
 * @param array $classNames Reference to the array mapping old class names to new ones.
 */
function findAndReplaceClassNames(string $dir, array &$classNames)
{
    // Processing CSS files to find class names and map them to new random names.
    $cssFiles = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($cssFiles as $file) {
        if ($file->isFile() && strtolower($file->getExtension()) === 'css') {
            $content = file_get_contents($file->getRealPath());
            // Matches class names in CSS.
            if (preg_match_all('/\.([a-zA-Z0-9_-]+)\s*[{,]/', $content, $matches)) {
                foreach ($matches[1] as $className) {
                    if (!isset($classNames[$className])) {
                        $classNames[$className] = generateRandomClassName();
                        // Replaces the old class name with the new one in CSS content.
                        $content = str_replace('.' . $className, '.' . $classNames[$className], $content);
                    }
                }
                // Saves the changes to the CSS file.
                file_put_contents($file->getRealPath(), $content);
            }
        }
    }

    // Replaces old class names with new ones in HTML and JS files.
    $htmlAndJsFiles = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($htmlAndJsFiles as $file) {
        if ($file->isFile() && in_array(strtolower($file->getExtension()), ['html', 'js'])) {
            $content = file_get_contents($file->getRealPath());
            foreach ($classNames as $oldClass => $newClass) {
                if (strtolower($file->getExtension()) === 'html') {
                    // Replaces class names in HTML class attributes.
                    $content = preg_replace('/class=["\']([^"\']*\b)' . preg_quote($oldClass, '/') . '(\b[^"\']*)["\']/', 'class="$1' . $newClass . '$2"', $content);
                } elseif (strtolower($file->getExtension()) === 'js') {
                    // Replaces class names in JavaScript strings.
                    $content = str_replace("'" . $oldClass . "'", "'" . $newClass . "'", $content);
                    $content = str_replace('"' . $oldClass . '"', '"' . $newClass . '"', $content);
                }
            }
            // Saves the changes to the file.
            file_put_contents($file->getRealPath(), $content);
        }
    }
}

/**
 * Finds and renames image files in the 'img' directory, then updates references in HTML, CSS, and JS files.
 * This ensures that any changes to image filenames are consistently reflected across all files in the project.
 *
 * @param string $dir The base directory of the project where image files are to be searched.
 * @param array &$imageNames A reference to an associative array mapping original filenames to new filenames.
 */
function findAndReplaceImageNames(string $dir, array &$imageNames)
{
    // Define the directory path for images.
    $imgDir = $dir . DIRECTORY_SEPARATOR . 'img';
    // Check if the image directory exists.
    if (!is_dir($imgDir)) {
        return; // Exit the function if the directory does not exist.
    }

    // Iterate through all files in the image directory.
    $imgFiles = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($imgDir));
    foreach ($imgFiles as $file) {
        if ($file->isFile()) {
            // Get the original and extension of the current file.
            $originalName = $file->getFilename();
            $extension = $file->getExtension();
            // Generate a new filename for the image.
            $newName = generateRandomFileName($extension);
            // Map the original filename to the new filename.
            $imageNames[$originalName] = $newName;
            // Rename the actual image file.
            rename($file->getRealPath(), $file->getPath() . DIRECTORY_SEPARATOR . $newName);
        }
    }

    // Now, update references to the renamed images in all HTML, CSS, and JS files.
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($files as $file) {
        if ($file->isFile() && in_array(strtolower($file->getExtension()), ['html', 'css', 'js'])) {
            // Read the current file's content.
            $content = file_get_contents($file->getRealPath());
            // Replace all occurrences of the old filenames with the new filenames.
            foreach ($imageNames as $oldName => $newName) {
                $content = str_replace($oldName, $newName, $content);
            }
            // Save the updated content back to the file.
            file_put_contents($file->getRealPath(), $content);
        }
    }
}

// Initialize arrays to keep track of the old and new names.
$classNames = []; // Mapping of old to new class names.
$imageNames = []; // Mapping of old to new image names.

// Perform the class and image name replacements.
findAndReplaceClassNames($directory, $classNames);
findAndReplaceImageNames($directory, $imageNames);

// Notify that the process is complete.
echo "Class and image names have been updated across CSS, HTML, and JS files.";