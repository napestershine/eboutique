<?php

namespace App\Tests\Unit\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $user = new User();
        $this->assertNull($user->getId());
        $this->assertNull($user->getEmail());
        $this->assertFalse($user->isVerified());
    }

    public function testSetEmailAndGetEmail(): void
    {
        $user = new User();
        $result = $user->setEmail('test@example.com');
        $this->assertSame($user, $result);
        $this->assertSame('test@example.com', $user->getEmail());
    }

    public function testSetPasswordAndGetPassword(): void
    {
        $user = new User();
        $user->setEmail('test@example.com');
        $result = $user->setPassword('hashed_password');
        $this->assertSame($user, $result);
        $this->assertSame('hashed_password', $user->getPassword());
    }

    public function testGetRolesAlwaysContainsRoleUser(): void
    {
        $user = new User();
        $roles = $user->getRoles();
        $this->assertContains('ROLE_USER', $roles);
    }

    public function testSetRolesAddsCustomRole(): void
    {
        $user = new User();
        $result = $user->setRoles(['ROLE_ADMIN']);
        $this->assertSame($user, $result);
        $roles = $user->getRoles();
        $this->assertContains('ROLE_ADMIN', $roles);
        $this->assertContains('ROLE_USER', $roles);
    }

    public function testGetRolesDeduplicates(): void
    {
        $user = new User();
        $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $roles = $user->getRoles();
        $this->assertCount(2, $roles);
    }

    public function testGetUserIdentifierReturnsEmail(): void
    {
        $user = new User();
        $user->setEmail('test@example.com');
        $this->assertSame('test@example.com', $user->getUserIdentifier());
    }

    public function testGetUserIdentifierReturnsEmptyStringWhenEmailIsNull(): void
    {
        $user = new User();
        $this->assertSame('', $user->getUserIdentifier());
    }

    public function testSetIsVerified(): void
    {
        $user = new User();
        $result = $user->setIsVerified(true);
        $this->assertSame($user, $result);
        $this->assertTrue($user->isVerified());
    }

    public function testEraseCredentialsDoesNotThrow(): void
    {
        $user = new User();
        $user->setEmail('test@example.com');
        $user->setPassword('hashed_password');
        $user->eraseCredentials();
        // eraseCredentials is a no-op in this entity; just verify no exception
        $this->assertSame('hashed_password', $user->getPassword());
    }
}
