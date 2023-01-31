#!/usr/bin/env sh

set -e

root="`dirname "$0"`/.."
libs="$root/.."

checkAlreadyInstalled() {
    if [ `ls "$libs" | wc -l` != "1" ]; then
        echo "Already installed"
        exit 1
    fi
}

installComposerLibrary() {
    (mkdir -p "$libs/$1" && cd "$libs/$1" && composer require "$2" --ignore-platform-reqs)
}

installLibrary() {
    (mkdir -p "$libs/$1" && cd "$libs/$1" && wget -O - "$2" | tar -xz --strip-components=1)
}

checkAlreadyInstalled

installLibrary flux-file-storage-api https://github.com/fluxfw/flux-file-storage-api/archive/refs/tags/v2023-01-30-1.tar.gz

installLibrary flux-rest-api https://github.com/fluxfw/flux-rest-api/archive/refs/tags/v2023-01-30-1.tar.gz

installLibrary flux-scorm-player-api https://github.com/fluxfw/flux-scorm-player-api/archive/refs/tags/v2023-01-31-2.tar.gz

installComposerLibrary mongo-php-library mongodb/mongodb:1.15.0

installLibrary scorm-again https://registry.npmjs.org/scorm-again/-/scorm-again-1.7.1.tgz
