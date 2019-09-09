## Prerequisites

- [docker](https://www.docker.com/)
- [docker-compose](https://docs.docker.com/compose/)
- [make](https://www.gnu.org/software/make/)
- `sh`

### Configuration
1. Create `.env` file:`cp .env.example .env`
2. Generate application key: `make generate-key`
3. In `.env` file set generated key to `APP_KEY`
4. Create `docker-compose.yml` file `cp docker-compose.dist.yml docker-compose.yml`
5. Change default database settings (for local environment purpose no action required)

### Setup

#### Build & run application
`make build`

#### Run application
`make run`

#### Optional commands

 - Generate application key `make key-generate`
 - ssh `make ssh`
 - database migration `make migrate`
 - database seed `make seed`
 - docker logs `make docker-logs`
 - fix cache permission `make fix-permissions`
