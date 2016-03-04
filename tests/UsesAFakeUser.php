<?php

trait UsesAFakeUser
{

    /**
     * Fake user
     *
     * @var array
     */
    protected static $user;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::$user = self::$luno->users->create([
            'username' => self::$faker->userName,
            'name'     => self::$faker->name,
            'email'    => self::$faker->email,
            'password' => self::$faker->password,
        ]);
    }

    public static function tearDownAfterClass()
    {
        self::$luno->users->destroy(self::$user['id']);
    }
}