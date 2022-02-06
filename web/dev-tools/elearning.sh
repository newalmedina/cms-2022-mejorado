#!/bin/bash

echo "instalando elearning"

./dev-tools/clean.sh

./packages/clavel/basic/tools/install.sh

./packages/clavel/template001/tools/install.sh

./packages/clavel/elearning/tools/install.sh
