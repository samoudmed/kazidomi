# Kazidomi: Merchant

Kazidomi: Merchant is a Symfony console application for managing merchants. It provides commands to create, list, and fetch merchant details.

## Features

- **Create a Merchant**: Add a new merchant with a specified name.
- **List Merchants**: List all registered merchants.
- **Fetch a Merchant**: Retrieve details of a merchant by their ID.

## Requirements

- PHP 8.2 or higher
- Composer
- Symfony CLI

## Installation

1. **Clone the repository**:

    ```sh
    git clone git@github.com:yourusername/kazidomi-merchant.git
    cd kazidomi-merchant
    ```

2. **Install dependencies**:

    ```sh
    composer install
    ```

3. **Set up the environment variables**:

    Create a `.env` file in the root directory of the project and configure your database connection and other environment variables:

    ```ini
    DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name"
    ```

4. **Run database migrations**:

    ```sh
    php bin/console doctrine:migrations:migrate
    ```

## Usage

### Create a Merchant

To create a new merchant, run the following command:

```sh
php bin/console kazidomi:merchant create "Merchant Name"

### List Merchants

To list all merchants, run the following command:

```sh
php bin/console kazidomi:merchant list

### Fetch a Merchant

To fetch a merchant by their ID, run the following command:

```sh
php bin/console kazidomi:merchant fetch 1
