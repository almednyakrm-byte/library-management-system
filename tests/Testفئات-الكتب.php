<?php

namespace App\Tests\Controller;

use App\Controller\CategoriesController;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Testفئات-الكتب extends TestCase
{
    private $categoriesController;
    private $pdoMock;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock(PDO::class);
        $this->categoriesController = new CategoriesController($this->pdoMock);
    }

    public function testGetCategories(): void
    {
        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM categories')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->categoriesController->getCategories();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCreateCategory(): void
    {
        $request = new Request();
        $request->request->set('name', 'Category Name');
        $request->request->set('description', 'Category Description');

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO categories (name, description) VALUES (:name, :description)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->categoriesController->createCategory($request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testUpdateCategory(): void
    {
        $request = new Request();
        $request->request->set('id', 1);
        $request->request->set('name', 'Updated Category Name');
        $request->request->set('description', 'Updated Category Description');

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('UPDATE categories SET name = :name, description = :description WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->categoriesController->updateCategory($request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDeleteCategory(): void
    {
        $request = new Request();
        $request->request->set('id', 1);

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM categories WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->categoriesController->deleteCategory($request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }
}


This test file covers the following scenarios:

1.  `testGetCategories`: Verifies that the `getCategories` method returns a successful response (200) when querying the `categories` table.
2.  `testCreateCategory`: Tests the `createCategory` method by simulating a POST request with a new category. It verifies that the method returns a successful response (201) and inserts the new category into the database.
3.  `testUpdateCategory`: Simulates a PUT request to update an existing category. It checks that the method returns a successful response (200) and updates the category in the database.
4.  `testDeleteCategory`: Tests the `deleteCategory` method by simulating a DELETE request with a category ID. It verifies that the method returns a successful response (200) and deletes the category from the database.

Note that this test file assumes that the `CategoriesController` class has the following methods:

*   `getCategories`: Retrieves all categories from the database.
*   `createCategory`: Creates a new category in the database.
*   `updateCategory`: Updates an existing category in the database.
*   `deleteCategory`: Deletes a category from the database.

Also, this test file uses the `createMock` method to create mock objects for the `PDO` and `PDOStatement` classes. This allows us to control the behavior of these objects during the test, making it easier to isolate and test the `CategoriesController` class.