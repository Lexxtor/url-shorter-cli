<?php

/**
 * Класс предназначен для вызова нескольких его статических функций.
 */
class Shorter
{
    /**
     * @var $database PDO
     */
    private $database;
    /**
     * @var $_instance static
     */
    private static $_instance;

    const short_url_symbols = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    private function __construct () {
        $this->connectToDatabase();
    }

    private function __clone () {}

    /**
     * @return static
     */
    private static function getInstance()
    {
        if (static::$_instance != null) {
            return static::$_instance;
        }

        return static::$_instance = new static;
    }

    /**
     * Сохраняет ссылку и выдаёт её циферно-буквенный ID.
     * @param $url
     * @return string
     */
    public static function short($url)
    {
        static::getInstance()->database->prepare('INSERT INTO url (url) VALUES (?)')->execute([$url]);
        $id = static::getInstance()->database->lastInsertId();
        return static::tokenize($id);
    }

    /**
     * @return array
     */
    public static function getUrls()
    {
        $statement = static::getInstance()->database->prepare('SELECT * FROM url');
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param int $id
     * @return int deleted
     */
    public static function deleteUrl($id)
    {
        $statement = static::getInstance()->database->prepare('DELETE FROM url WHERE id = ?');
        $statement->execute([$id]);
        return $statement->rowCount();
    }

    /**
     * @param $string
     * @return false|int
     */
    public static function isId($string)
    {
        return preg_match('/^[\da-zA-Z]+$/', $string);
    }

    /**
     * @param string $stringId
     * @return string|false
     */
    public static function getUrlByShortId($stringId)
    {
        $intId = static::unTokenize($stringId);
        $statement = static::getInstance()->database->prepare('SELECT url FROM url WHERE id = ?');
        $statement->execute([$intId]);
        return $statement->fetchColumn();
    }

    /**
     * @return array
     */
    private function getConfig()
    {
        return require __DIR__.'/../config.php';
    }

    private function connectToDatabase()
    {
        $config = $this->getConfig();
        $this->database = new PDO(
            "mysql:host=$config[host];dbname=$config[database]",
            $config['user'],
            $config['pass'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]
        );
    }

    /**
     * @param integer $int
     * @return string
     */
    public static function tokenize($int)
    {
        $result = '';
        $n = floor($int/strlen(static::short_url_symbols));
        if ($n > 0)
            $result .= static::tokenize($n);
        $result .= static::short_url_symbols[$int % strlen(static::short_url_symbols)];

        return $result;
    }

    /**
     * @param $string
     * @return int
     */
    public static function unTokenize($string)
    {
        $result = 0;
        $i = strlen($string);
        $string = strrev($string);
        while (isset($string[--$i])) {
            $result += strpos(static::short_url_symbols, $string[$i]) * pow(strlen(static::short_url_symbols), $i);
        }
        return $result;
    }
}