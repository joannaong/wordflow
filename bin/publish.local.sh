#!/bin/sh
export PATH=/usr/local/bin:/usr/bin:$PATH
export LANG=en_US.UTF-8

set -x
(
echo Publish Local Site

#New Build
grunt publish:local:data --force

echo Done
) 2>&1
exit 0