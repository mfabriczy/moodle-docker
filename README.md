# Moodle on Docker
Initialise a Moodle development environment inside containers using Docker.

The instructions here should be enough to get you started. However, it's advised to have some knowledge in
Docker to debug issues should you need too.

What you'll need before you start
---------------------------------
* **Docker**
* **Docker Compose**
* **Perl** (optional)
  * Needed for the `install_database` script
  * macOS and the various Linux distros have Perl included
* **Ruby**
  * Prerequisite for docker-sync
* **[docker-sync](http://docker-sync.io/)**
  * `gem install docker-sync`

How to install
--------------
Copy docker-config-template.php into your local Moodle directory and rename it to `config.php`.

Set execute permissions on the following scripts:
```
chmod +x moodle-docker install_database
```
In the `config` file, set the `LOCAL_MOODLE_PATH` variable:
```
LOCAL_MOODLE_PATH=<path of your local Moodle folder>
```
**If you are running on macOS**, install the Unison File Synchroniser via Homebrew:
```
brew install unison
brew tap eugenmayer/dockersync
brew install eugenmayer/dockersync/unox
```
_The instructions above are not applicable to Linux_

#### Configure HTTPS

Create the TLS certificate and private key:

```
openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout /tmp/nginx.key -out /tmp/nginx.crt
```

Move the created certificates into the `tls` folder in this project.

#### Create the containers and download the Docker images

```
./moodle-docker start
```
**Note:** First-time initialisation may take awhile (2-10 mins). This is because it's downloading several
images from Docker Hub and propagating the files in the Moodle folder into the containers.

When switching between branches, allow some time for the files to sync across to the containers before using the
`install_database` script. You can view the state of the sync on the terminal tab where you initially ran the command:
`./moodle-docker start`

#### Install a fresh instance of Moodle.
```
./install_database
```

You can now access the Moodle instance by going to `https://localhost` in your browser.

Why docker-sync?
---------------
Running Docker volumes on macOS is slow when a large number of files are involved (Moodle contains a lot of files).

Docker will start a virtual machine to insert the containers into using the currently slow osx file system for volumes.
A combination of these two factors is why this issue exists.

The Docker developers are aware of this and is actively working on it, the long term plan for this project is to remove
docker-sync for a more unified interface once this issue is fixed.

The Unison file synchroniser is a good alternative to propagating files.

Docker on a Linux distro do not have these limitations, as it's run natively on the kernel.

See this [link](https://stories.amazee.io/docker-on-mac-performance-docker-machine-vs-docker-for-mac-4c64c0afdf99) for more info.

Usage
-----
To execute commands within a container:
```
docker exec -i -t <container name> /bin/bash
```

If you specify the `phpfpm` container, you can, for example, run Moodle's `purge_caches.php` script.

To stop the running containers ```CTRL + C```.

You can view the state and name of the containers by entering the following command:

```
docker ps
```

To remove the containers:

```
./moodle-docker clean
```

#### Behat

You will need to enable [File Sharing](https://docs.docker.com/docker-for-mac/osxfs/#namespaces) in Docker by adding the
path of your local Moodle directory (Preferences -> File Sharing).

##### VNC Viewer
To view the progress of Behat tests in a browser - download [VNC Viewer](https://www.realvnc.com/en/connect/download/viewer).

1. Open the application
1. While the containers are running, enter `0.0.0.0:<VNC_PORT>` into the field
1. You will be prompted for a password. The password is `secret`

In the `config` file, you can set the `VNC_PORT`. For example, you can do this if the default port is already in use.

#### Xdebug
Xdebug is installed into the PHP-FPM container, however, it has only been tested and verified to be working on OS X El Capitan
with PHPStorm 2017.3.2.

##### Installation instructions
In the `config` file, set the value of the variable `XDEBUG_REMOTE_ADDR` to the en0 IP address of your local machine.
You can get this value by using `ifconfig` in the terminal.

You must also set that IP address in the `docker-php-ext-xdebug.ini` file.

**Note:** _If your en0 IP address changes, you'll need to change the configuration files, tear down the environment and
then spin it up again._ 

Troubleshooting
---------------
**It's slow**

You may need to allocate more resources to Docker by going to the [Advanced tab](https://docs.docker.com/docker-for-mac/#advanced)
and adjusting the CPU and Memory values.

**The Postgres container does not work?**

Ensure the `pgdata` folder is empty; yes, even hidden files.

**I get the following error "Please move or remove them before you can switch branches"**

Something went wrong when syncing the files to the container, use the command:

```
git clean -f -d
```

Then checkout the branch again.

**Note:** If you have untracked files, the command above **will remove them**; either Git stash
or ignore them.

**If all else fails?**

Remove the containers and reinitialise the environment:
```
./moodle-docker clean
```
```
./moodle-docker start
```

Other:
------
The customised PHP-FPM container can be found in [Docker Hub](https://hub.docker.com/r/mfabriczy/docker-moodle-phpfpm).

Tested on:
----------
* OS X El Capitan
* Ubuntu 17.10