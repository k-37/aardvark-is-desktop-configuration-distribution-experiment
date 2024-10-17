#!/usr/bin/env bash

set -o errexit -o errtrace -o nounset -o pipefail

# Move disabled packages from `__apt_packages` to here:
# EXAMPLE_DISABLED_PACKAGE
# mc
# alacritty

__apt_packages='
strawberry
krusader
neovim-qt
tmux
command-not-found
'

sudo apt-get update
sudo apt-get install --assume-yes ${__apt_packages}
