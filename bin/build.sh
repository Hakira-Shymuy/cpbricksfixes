#!/bin/sh

PLUGIN_SLUG="cp-bricks-fixes"
PROJECT_PATH=$(pwd)
SOURCE_PATH="${PROJECT_PATH}/src"
PLUGIN_FILE="${SOURCE_PATH}/${PLUGIN_SLUG}.php"
DIST_PATH="${PROJECT_PATH}/dist"
OUTPUT_PATH="$DIST_PATH/$PLUGIN_SLUG"
XDEBUG_MODE=off

# echo "Install dev dependencies..."
# npm ci || exit "$?"

echo "Generating build directory..."
rm -rf "$OUTPUT_PATH" || exit "$?"
mkdir -p "$OUTPUT_PATH" || exit "$?"

echo "Copying all files to the build directory..."
rsync -rc "$SOURCE_PATH/" "$OUTPUT_PATH/" || exit "$?"

cd "$OUTPUT_PATH" || exit "$?"

echo "Installing build dependencies..."
# npm ci --omit-dev || exit "$?"
composer install --no-dev --quiet --prefer-dist --no-progress --no-interaction || exit "$?"

echo "Removing development files..."
while IFS= read -r pattern
do
    # Trim leading and trailing whitespace
    pattern=$(echo "$pattern" | sed -e 's/^[[:space:]]*//' -e 's/[[:space:]]*$//')

    # Skip empty lines and comments
    [ -z "$pattern" ] && continue
    case "$pattern" in \#*) continue ;; esac

	echo "Removing $pattern"
    if echo "$pattern" | grep -q "/"
    then
        # It's a path pattern
        find . -type d -path "*/${pattern%/}" -prune -exec rm -rf {} +
    else
        # It's a file pattern
        find . -name "$pattern" -type f -delete
    fi
done < "$PROJECT_PATH/.distignore"

echo "Generating zip file..."
cd "$DIST_PATH" || exit "$?"
PLUGIN_FILE_CONTENTS=$(cat "$PLUGIN_FILE") || exit "$?"
PLUGIN_VERSION=$(echo "$PLUGIN_FILE_CONTENTS" | grep -oE 'Version:\s*[0-9]+\.[0-9]+\.[0-9]+(-[0-9A-Za-z.-]+)?(\+[0-9A-Za-z.-]+)?' | awk '{print $2}') || exit "$?"
echo "Plugin version: $PLUGIN_VERSION"
BUILD_FILENAME="$PLUGIN_SLUG-$(echo "$PLUGIN_VERSION" | sed 's/+/-/g').zip"
zip -q -r -9 "${BUILD_FILENAME}" "$PLUGIN_SLUG/" || exit "$?"
echo "${BUILD_FILENAME} file generated!"

echo "Clean up..."
rm -rf "$OUTPUT_PATH" || exit "$?"

echo "Build done!"
