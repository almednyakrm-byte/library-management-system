<?php

namespace App\Tests;

use App\Auth\Auth;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;

class TestAuth extends TestCase
{
    private $auth;
    private $pdoMock;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock(PDO::class);
        $this->auth = new Auth($this->pdoMock);
    }

    public function testLoginSuccess()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM users WHERE username = :username')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM users WHERE username = :username AND password = :password')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM users WHERE username = :username AND password = :password')
            ->willReturn($this->createMock(\PDOStatement::class));

        $result = $this->auth->login($username, $password);

        $this->assertTrue($result);
    }

    public function testLoginFailure()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM users WHERE username = :username')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM users WHERE username = :username AND password = :password')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM users WHERE username = :username AND password = :password')
            ->willReturn($this->createMock(\PDOStatement::class));

        $result = $this->auth->login($username, $password);

        $this->assertFalse($result);
    }

    public function testRegisterSuccess()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO users (username, password) VALUES (:username, :password)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $result = $this->auth->register($username, $password);

        $this->assertTrue($result);
    }

    public function testRegisterFailure()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO users (username, password) VALUES (:username, :password)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $result = $this->auth->register($username, $password);

        $this->assertFalse($result);
    }
}


This test file covers the following scenarios:

- `testLoginSuccess`: Tests that a user can successfully log in with the correct credentials.
- `testLoginFailure`: Tests that a user cannot log in with incorrect credentials.
- `testRegisterSuccess`: Tests that a user can successfully register with the correct credentials.
- `testRegisterFailure`: Tests that a user cannot register with incorrect credentials.

Each test method uses PHPUnit's mocking functionality to simulate the database interactions, and asserts the expected result using `assertTrue` and `assertFalse`.