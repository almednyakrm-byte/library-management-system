<?php

namespace App\Tests;

use App\Auth;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;

class TestAuth extends TestCase
{
    private $auth;
    private $session;
    private $logger;

    protected function setUp(): void
    {
        $this->session = new Session(new MockFileSessionStorage());
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->auth = new Auth($this->session, $this->logger);
    }

    public function testLoginSuccess()
    {
        // Mock database connection
        $dbMock = $this->createMock(\PDO::class);
        $dbMock->method('prepare')
            ->willReturn($this->createMock(\PDOStatement::class));
        $dbMock->method('query')
            ->willReturn($this->createMock(\PDOStatement::class));

        // Set up mock database statement
        $stmtMock = $this->createMock(\PDOStatement::class);
        $stmtMock->method('execute')
            ->willReturn(true);
        $stmtMock->method('fetch')
            ->willReturn(['username' => 'test', 'password' => 'test']);

        $dbMock->method('prepare')
            ->willReturn($stmtMock);

        // Set up auth object with mock database
        $this->auth->setDatabase($dbMock);

        // Test login
        $result = $this->auth->login('test', 'test');
        $this->assertTrue($result);
        $this->assertEquals('test', $this->session->get('username'));
    }

    public function testLoginFailure()
    {
        // Mock database connection
        $dbMock = $this->createMock(\PDO::class);
        $dbMock->method('prepare')
            ->willReturn($this->createMock(\PDOStatement::class));
        $dbMock->method('query')
            ->willReturn($this->createMock(\PDOStatement::class));

        // Set up mock database statement
        $stmtMock = $this->createMock(\PDOStatement::class);
        $stmtMock->method('execute')
            ->willReturn(true);
        $stmtMock->method('fetch')
            ->willReturn(false);

        $dbMock->method('prepare')
            ->willReturn($stmtMock);

        // Set up auth object with mock database
        $this->auth->setDatabase($dbMock);

        // Test login
        $result = $this->auth->login('test', 'wrong');
        $this->assertFalse($result);
        $this->assertNull($this->session->get('username'));
    }

    public function testRegisterSuccess()
    {
        // Mock database connection
        $dbMock = $this->createMock(\PDO::class);
        $dbMock->method('prepare')
            ->willReturn($this->createMock(\PDOStatement::class));
        $dbMock->method('query')
            ->willReturn($this->createMock(\PDOStatement::class));

        // Set up mock database statement
        $stmtMock = $this->createMock(\PDOStatement::class);
        $stmtMock->method('execute')
            ->willReturn(true);

        $dbMock->method('prepare')
            ->willReturn($stmtMock);

        // Set up auth object with mock database
        $this->auth->setDatabase($dbMock);

        // Test register
        $result = $this->auth->register('test', 'test', 'test@example.com');
        $this->assertTrue($result);
        $this->assertEquals('test', $this->session->get('username'));
    }

    public function testRegisterFailure()
    {
        // Mock database connection
        $dbMock = $this->createMock(\PDO::class);
        $dbMock->method('prepare')
            ->willReturn($this->createMock(\PDOStatement::class));
        $dbMock->method('query')
            ->willReturn($this->createMock(\PDOStatement::class));

        // Set up mock database statement
        $stmtMock = $this->createMock(\PDOStatement::class);
        $stmtMock->method('execute')
            ->willReturn(false);

        $dbMock->method('prepare')
            ->willReturn($stmtMock);

        // Set up auth object with mock database
        $this->auth->setDatabase($dbMock);

        // Test register
        $result = $this->auth->register('test', 'test', 'test@example.com');
        $this->assertFalse($result);
        $this->assertNull($this->session->get('username'));
    }
}