#!/bin/bash

pushd "$(pwd)" > /dev/null
cd web/app/themes/theme/blocks || exit

npx @wordpress/create-block@latest --no-plugin --template ../../../../../scripts/twig-block-template --namespace custom

popd > /dev/null
