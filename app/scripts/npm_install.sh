#! /usr/bin/env bash

cd $PWD/$(dirname "$0")/../../

assets_folder=web/assets/npm
if [ ! -d "${assets_folder}" ]
then
    mkdir -p ${assets_folder}
    mkdir -p ${assets_folder}/css
    mkdir -p ${assets_folder}/js
fi

# Bootstrap
cp node_modules/bootstrap/dist/css/bootstrap.min.css ${assets_folder}/css/
cp node_modules/bootstrap/dist/css/bootstrap-theme.min.css ${assets_folder}/css/
cp node_modules/bootstrap/dist/js/bootstrap.min.js ${assets_folder}/js/
cp -r node_modules/bootstrap/fonts ${assets_folder}

# DataTables
cp node_modules/datatables.net-bs/css/dataTables.bootstrap.css ${assets_folder}/css/
cp node_modules/datatables.net-bs/js/dataTables.bootstrap.js ${assets_folder}/js/
cp node_modules/datatables.net/js/jquery.dataTables.js ${assets_folder}/js/

# jQuery
cp node_modules/jquery/dist/jquery.min.js ${assets_folder}/js/
