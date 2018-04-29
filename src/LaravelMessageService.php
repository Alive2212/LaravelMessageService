<?php
/**
 * Created by PhpStorm.
 * User: alive
 * Date: 4/28/18
 * Time: 3:34 AM
 */

namespace Alive2212\LaravelMessageService;


class LaravelMessageService
{

    /**
     * @var bool
     */
    static $runsMigrations = true;

    /**
     * @return bool
     */
    public function runsMigrations()
    {
        // TODO check migration was copied into projects or not
        return false;
    }

    /**
     * Configure Package to not register its migrations.
     *
     * @return static
     */
    public static function ignoreMigrations()
    {
        static::$runsMigrations = false;

        return new static;
    }

}