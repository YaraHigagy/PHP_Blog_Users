<?php
require 'MysqlAdapter.php';
require 'database_config.php';

class User extends MysqlAdapter
{
    //Set the table name
    private $_table = 'users';

    public function __construct()
    {
        //Add from the database configuration file
        global $config;

        //Call the parent constructor
        parent::__construct($config);
    }

    /**
     * List All Users
     * @return array Returns every user row as array of associative array
     */
    public function getUsers()
    {
        $this->select($this->_table);
        return $this->fetchAll();
    }

    /**
     * Show one user
     * @param int $user_id
     * @return array Return a user row as associative array
     */
    public function getUser($user_id)
    {
        $this->select($this->_table, 'id = '.$user_id);
        return $this->fetch();
    }

    /**
     * Add New User
     * @param array $user_data Associative array containing column and value
     * @return int Returns the id of the user inserted
     */
    public function addUser($user_data)
    {
        return $this->insert($this->_table, $user_data);
    }

    /**
     * Update existing user
     * @param array $user_data Associative array containing column and value
     * @param int $user_id
     * @return int Number of affected rows
     */
    public function updateUser($user_data, $user_id)
    {
        return $this->update($this->_table, $user_data, 'id = '.$user_id);
    }

    /**
     * Delete existing user
     * @param int $user_id
     * @return int Number of affected rows
     */
    public function deleteUser($user_id)
    {
        return $this->delete($this->_table, 'id = '.$user_id);
    }

    /**
     * Search existing users
     * @param string $keyword
     * @return array Returns every user row as array of associative array
     */
    public function searchUsers($keyword)
    {
        $this->select($this->_table, "name LIKE '%$keyword%' OR email like '%$keyword%'");
        return $this->fetchAll();
    }

    /**
     * Login existing user
     * @param string $user_email
     * @param int $user_id
     * @return int Number of affected rows
     */
    public function login_check($user_email, $user_password)
    {
        // $this->login($this->_table, 'email = '.$user_email, 'password = '.$user_password);
        $this->login($this->_table, $user_email, $user_password);
        return $this->fetchAll();
    }
}
