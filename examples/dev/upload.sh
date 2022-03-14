#!/usr/bin/env sh

set -e

# https://github.com/jcputney/scorm-again/tree/227ff6f3b07698beda96dfb58b258e6d60f0c153/SL360_LMS_SCORM_2004
curl -X POST -F "id=example_1234" -F "title=Example 1234" "file=@scorm_packages/example_scorm.zip" http://%host%:9501/upload
