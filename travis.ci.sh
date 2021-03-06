#!/bin/sh

export SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )" # Do not edit this line

export db_host="localhost"                           # MySQL Database Host
export db_user="root"                           # MySQL Database User
export db_pass=""                           # MySQL Database Password
export db_name="tmpdb"                           # MySQL Database Name
export db_test_name="tmpdb-test"                      # MySQL Test Database Name
export base_url="http://store-redirect-extension.mage.dev/"                          # Magento base URL (remember the trailing slash)
export install_sample_data="no"             # Install sample data ('yes' or 'no')

export magento_dir="magento"                # Magento directory name without heading or trailing slashes
export phpunit_filter=""                    # [OPTIONAL] Filter (--filter) for phpunit command

export MAGENTO_VERSION="magento-ce-1.9.1.0" # Which Magento version to install (see n98-magerun install command)

export BASE_DIR="${SCRIPT_DIR}"                          # Absolute path of the directory where composer.json is located
export CI_LIB_DIR="${SCRIPT_DIR}/magento-bash-ci"                        # Absolute path of the directory where are located CI scripts

sh ${CI_LIB_DIR}/ci-install.sh
sh ${CI_LIB_DIR}/ci-test.sh
