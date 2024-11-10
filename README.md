# Repository Pattern REST API for Microservices

This repository demonstrates how to implement a **REST API** using the **Repository Pattern** in a Laravel-based microservice. The main objective of this project is to showcase how to manage categories efficiently, abstracting the data handling through the repository pattern and utilizing **JsonResource** for API response formatting.

By following the principles of **clean code**, **separation of concerns**, and **scalable design**, this repository presents a maintainable solution for managing categories in a microservice architecture.

## Features

- **Category Management**: Allows CRUD operations on categories.
- **Repository Pattern**: Implemented to decouple the application’s business logic from data persistence.
- **JsonResource**: Uses `JsonResource` to ensure consistent and structured API responses.
- **Microservice-Ready**: Designed with the flexibility to integrate into a microservice environment, with clear separation between layers.

---

## Table of Contents

1. [Installation](#installation)
2. [Technologies](#technologies)
3. [Project Structure](#project-structure)
4. [API Endpoints](#api-endpoints)
5. [Usage](#usage)
6. [Repository Pattern](#repository-pattern)
7. [JsonResource](#jsonresource)
8. [Contributing](#contributing)
9. [License](#license)

---

## Installation

To get started with this repository, follow the instructions below:

### 1. Clone the Repository

```bash
git clone https://github.com/Remonhasan/repository-pattern-microservice-api.git
cd repository-pattern-microservice-api
```

### 2. Install Dependencies

Ensure you have Composer installed. Then, run the following command to install the required dependencies:

```bash
composer install
```

### 3. Configure Environment

Copy the `.env.example` to `.env` and set up your database and application configurations.

```bash
cp .env.example .env
php artisan key:generate
```

Update the `.env` file with your database credentials, for example:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```

### 4. Migrate the Database

Run the database migrations to create the necessary tables:

```bash
php artisan migrate
```

Optionally, you can seed the database with sample data:

```bash
php artisan db:seed
```

### 5. Start the Development Server

Once everything is set up, start the server:

```bash
php artisan serve
```

Your application should now be running at `http://localhost:8000`.

---

## Technologies

This project uses the following technologies:

- **Laravel** (PHP framework)
- **MySQL** (Database)
- **Repository Pattern**
- **JsonResource** (for transforming data into a consistent JSON format)
- **RESTful API** (with standard HTTP methods)

---

## Project Structure

Here's an overview of the project structure:

```
app/
├── Http/
│   ├── Controllers/
│   │   └── CategoryController.php    # Handles requests related to categories
│   └── Resources/
│       └── CategoryResource.php      # API resource for category data transformation
├── Interfaces/
│   └── CategoryInterface.php         # Defines the contract for category repository
├── Repositories/
│   └── CategoryRepository.php        # Implements the repository interface
└── Models/
    └── Category.php                  # The Eloquent model for the category
```

### Breakdown:

- **CategoryController**: This controller handles all HTTP requests related to categories (e.g., create, update, delete, list).
- **CategoryResource**: A `JsonResource` used to transform the category data before sending it as a response, ensuring a consistent output format.
- **CategoryInterface**: An interface that defines the methods for category-related data operations, ensuring loose coupling and better testability.
- **CategoryRepository**: Implements `CategoryInterface` and interacts directly with the database to perform CRUD operations.
- **Category Model**: The Eloquent model that represents the `Category` table in the database.

---

## API Endpoints

### 1. **Get All Categories**

- **URL**: `/api/categories`
- **Method**: `GET`
- **Description**: Retrieves a list of all categories.
- **Response**: A JSON array of categories.

Example:

```json
[
  {
    "id": 1,
    "name": "Category One",
    "description": "Description for Category One"
  },
  {
    "id": 2,
    "name": "Category Two",
    "description": "Description for Category Two"
  }
]
```

### 2. **Create Category**

- **URL**: `/api/categories`
- **Method**: `POST`
- **Description**: Creates a new category.
- **Request Body**:

```json
{
  "name": "New Category",
  "description": "A description for the new category"
}
```

- **Response**: The newly created category data with a 201 status code.

### 3. **Update Category**

- **URL**: `/api/categories/{id}`
- **Method**: `PUT`
- **Description**: Updates an existing category.
- **Request Body**:

```json
{
  "name": "Updated Category Name",
  "description": "Updated description"
}
```

- **Response**: The updated category data.

### 4. **Delete Category**

- **URL**: `/api/categories/{id}`
- **Method**: `DELETE`
- **Description**: Deletes the category by ID.
- **Response**: A success message confirming the deletion.

---

## Usage

The following describes the code implementation and how the repository pattern and `JsonResource` are used in the project:

### **Repository Pattern** Overview

The **Repository Pattern** helps abstract data access logic from the rest of the application. It allows you to work with different data sources (databases, external APIs, etc.) without changing the core application logic.

In this project:

- The **CategoryRepository** is responsible for interacting with the database and performing CRUD operations.
- The **CategoryInterface** defines the contract that the repository must follow, ensuring that the business logic interacts with the data layer in a consistent and decoupled manner.

#### **CategoryInterface**

```php
namespace App\Interfaces;

interface CategoryInterface
{
    public function getAllCategories();
    public function createCategory(array $data);
    public function updateCategory($id, array $data);
    public function deleteCategory($id);
}
```

#### **CategoryRepository**

```php
namespace App\Repositories;

use App\Models\Category;
use App\Interfaces\CategoryInterface;

class CategoryRepository implements CategoryInterface
{
    protected $model;

    public function __construct(Category $category)
    {
        $this->model = $category;
    }

    public function getAllCategories()
    {
        return $this->model->all();
    }

    public function createCategory(array $data)
    {
        return $this->model->create($data);
    }

    public function updateCategory($id, array $data)
    {
        $category = $this->model->findOrFail($id);
        $category->update($data);
        return $category;
    }

    public function deleteCategory($id)
    {
        $category = $this->model->findOrFail($id);
        $category->delete();
        return $category;
    }
}
```

---

### **JsonResource** for API Responses

The **JsonResource** class is used to transform model data into a consistent and well-structured JSON format. It ensures that the API response remains uniform and easy to consume.

#### **CategoryResource**

```php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
        ];
    }
}
```

Using `JsonResource` helps separate the logic for formatting the response from the rest of the application, ensuring that API responses are consistent and easier to modify.

---

## Contributing

We welcome contributions! If you’d like to contribute to this project, please follow these steps:

1. Fork the repository.
2. Create a feature branch (`git checkout -b feature-name`).
3. Make your changes.
4. Commit your changes (`git commit -am 'Add new feature'`).
5. Push to the branch (`git push origin feature-name`).
6. Open a pull request.

Please ensure that your code follows the existing coding conventions and includes appropriate tests.

---

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

This README provides comprehensive documentation for your project, including installation instructions, details about the repository pattern, usage of `JsonResource`, and API endpoints. Feel free to adjust the content to suit your specific project details! Let me know if you need any further changes.
