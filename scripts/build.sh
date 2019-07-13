#!/bin/bash

set -euo pipefail
IFS=$'\n\t'

echo "--- Building static site..."
./tools/hugo/hugo -v -s source/ -d ../dist

echo "--- Packaging distributable..."
zip -r9 dist.zip dist