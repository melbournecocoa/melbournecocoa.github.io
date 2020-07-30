#!/bin/bash

set -euo pipefail
IFS=$'\n\t'

./tools/hugo/hugo -v -s source/ -d ../build