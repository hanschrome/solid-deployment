# Quick script deployment on PHP

This a quick project to automate deployments.

## Features

* Configuration override
* Custom deployment code

~~~
This code sucks, but it works. At least I hope so.
~~~

## Set up

1. Login on your server and create a directory for this script (as advice)
1. cd on that directory
1. git clone [HERE git URL] .
1. Add configuration files on config/ directory
1. Set the replacements on settings.json, key "overrides".
1. Add custom code deployment on custom/ directory
1. Add cron with instruction ´php run.php´

*On summary*: Change custom/ scripts, add configuration files on config/ and configure settings.json. Finally run ´php run.php´.

# Alternatives to this script

1. crontab -e
1. Add ´cd /your/project/directory && git pull´

Easier? yes. Does it work? yes. Is it as cool as this script? no.
