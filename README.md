# LEPP Container Stack

This repo sets up a LEPP (Linux, Nginx, PostgreSQL, PHP) stack using Docker and Docker Compose. This stack is intended for educational purposes and provides a simple environment to develop and test web applications. It serves as a modern alternative to XAMPP, leveraging Docker containers for better isolation and scalability.

## Prerequisites

-   Git
-   Docker
-   Docker Compose

## Getting Started

1. Clone the repository:

    ```sh
    git clone https://github.com/run2go/lepp.git
    cd lepp
    ```

2. Start the container stack:

    ```sh
    docker-compose up -d
    ```

3. Access the services:
    - Nginx web server: [http://localhost:3000](http://localhost:3000)
    - Adminer (Database management tool): [http://localhost:8080](http://localhost:8080)

## Docker and Docker Compose

Docker is a platform that allows you to develop, ship, and run applications inside containers. Here are some basic Docker commands:

-   **Start a container**: `docker start <container_name>`
-   **Stop a container**: `docker stop <container_name>`
-   **Run a container**: `docker run <image_name>`
-   **Remove a container**: `docker rm <container_name>`

Docker Compose is a tool for defining and running multi-container Docker applications. With Docker Compose, you use a YAML file (`docker-compose.yml`) to configure your application's services, networks, and volumes. This simplifies the management of multiple containers and their dependencies.

## Container Stack

This stack consists of the following services:

-   **Nginx**: A high-performance web server that serves static files and acts as a reverse proxy for the PHP backend.
-   **PHP-FPM**: A FastCGI Process Manager for PHP that handles PHP requests.
-   **PostgreSQL**: A powerful, open-source object-relational database system.
-   **Adminer**: A full-featured database management tool written in PHP, used as a substitute for phpMyAdmin.

## Network

Docker Compose creates a default network for the services defined in the `docker-compose.yml` file. The containers can communicate with each other using their service names as hostnames.

## Nginx Web Server

Nginx is configured to serve multiple sites:

-   **Default/Fallback**:\
    Accessible at [127.0.0.1](http://127.0.0.1:3000), serves a "Hello World" page. This configuration handles all hostnames that aren't handled otherwise, including `127.0.0.1`. Given it takes care of all unhandled addresses, we can also use an address like `test.localhost`
-   **localhost**:\
    Accessible at [localhost](http://localhost:3000), provides a PHP info page. The PHP info page is useful for checking the PHP configuration and installed modules.
-   **php.localhost**:\
    Accessible at [php.localhost](http://php.localhost:3000), serves a minimalistic calculator using PHP to compute the inputs when submitting the form. This demonstrates server-side programming with PHP, which can keep sensitive data hidden from the user.
-   **js.localhost**:\
    Accessible at [js.localhost](http://js.localhost:3000), serves a minimalistic calculator using JavaScript to compute the inputs. This demonstrates client-side programming with JavaScript, which is sent as-is to the client.

## PHP Backend

The PHP-FPM container processes PHP requests forwarded by Nginx. The configuration files for PHP-FPM are located in the `php` directory.

### Check database availability using PHP

We can leverage the `fsockopen` function to see if the postgres database is available from our php container. Here is an example script:

```php
<?php
$host = 'postgres';
$port = 5432;
$timeout = 5;

$connection = @fsockopen($host, $port, $errno, $errstr, $timeout);

if ($connection) {
    echo "PostgreSQL server is available.";
    fclose($connection);
} else {
    echo "PostgreSQL server is not available. Error: $errstr ($errno)";
}
?>
```

Save this script as `db_test.php` in your web root directory and access it via [http://localhost:3000/db_test.php](http://localhost:3000/db_test.php).

Actual database interactions would require a pgsql client as a php module so we can use functions like `pg_connect` directly.

## PostgreSQL Database

The PostgreSQL container provides a database service. The database is accessible via the Adminer tool.

### Accessing PostgreSQL via Adminer

Adminer is a web-based database management tool that allows you to manage your PostgreSQL database. To access Adminer:

1. Open [localhost:8080](http://localhost:8080) in your web browser.
2. Use the following credentials to log in:
    - **System**: PostgreSQL
    - **Server**: postgres
    - **Username**: postgres
    - **Password**: test_password
    - **Database**: postgres

Adminer provides a user-friendly interface to manage your database, run SQL queries, and perform other database operations.

## Volumes

The stack uses Docker volumes to persist data and configuration files. For simplicity, directories on the host system are mounted directly, and no isolated Docker volumes are used.

-   **Logging**: Logging files and folders are mounted to the `log` folder.
    -   `log/nginx/`: Nginx logs
    -   `log/php/`: PHP logs
    -   `log/postgresql/`: PostgreSQL logs
-   **Configuration**:
    -   `docker-compose.yml`: Docker Compose configuration file
    -   `nginx/`: Nginx configuration and web root
        -   `conf.d/`: Nginx site configurations
        -   `web/`: Web root directory
            -   `html/`: Default site
            -   `localhost/`: Localhost site
            -   `*.localhost/`: Subdomain sites
    -   `php/`: PHP configuration files
        -   `php.ini`: Custom PHP configuration
        -   `fastcgi_params`: FastCGI parameters
    -   `postgres/`: PostgreSQL data directory
        -   `data/`: Persisted PostgreSQL data
