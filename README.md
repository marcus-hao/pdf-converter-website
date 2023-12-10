# QuickSwitch Website

QuickSwitch is a website that provides conversion between PDF and text files.
This project is prepared for the submission of CAT201 assignment.

## Installation and Running

### Dependencies
Please make sure you have the following prerequisites:

- Docker

If you are running this on a Windows machine, please ensure that you have WSL installed before installing Docker.
Instructions on running Docker can be found on the [Docker Get started page](https://docs.docker.com/get-started/).

### Running the container

Build the image:
```
docker build -t quick-switch:1.0 .
```

Start up a container:
```
# You don't have to start up a shell, but I included it for verbosity when running the container
docker run --rm -it -p 8080:80 quick-switch:1.0 /bin/bash
```

Visit the website at `127.0.0.1:8080` in your browser.