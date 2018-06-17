# Frugu

This repository contains the new website for [Frugu](https://frugu.net/)

## Dev Usage

### Requirements

- [yarn](https://yarnpkg.com/en/docs/install#mac-stable)
- [composer](https://getcomposer.org/download/)
- [Docker](https://store.docker.com/search?type=edition&offering=community)
- [docker-compose](https://docs.docker.com/compose/install/)

### Dependencies

You can install all dependencies with: `make deps`

And build assets with: `make build`

### Docker

Run `make docker-up` to run all docker images. You'll find logs for the images in `var/log/docker/`.