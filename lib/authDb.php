
<?php
/***
 * developer: sampath liyanage
 * phone no: +94778514847
 */

include_once "dbCon.php";

/**
 *contains functions needed to interact with database
 *for authentication component
 */
class Auth_DB extends DB_connection
{
    public function __construct()
    {
        parent::__construct("authManager", "NuVnyAJtNEj43GLd");
    }

    /*
     *for creating users
     *@input=> username:string, passwd:string, email:string, gToken:string
     *@output=> if query changed the database:bool
     */
    public function createUser($username, $passwd, $email)
    {
        //encrypt password
        $passwd = md5($passwd);
        $stmt   = $this->prepareSqlStmt("INSERT INTO user (user_name, password, email) VALUES(?,?,?)");
        $stmt->bind_param('sss', $username, $passwd, $email);
        return $stmt->execute();
    }
    
    /*
     *for checking if a user name exists
     *@input=> username:string
     *@output=> if username exists:bool
     */
    public function isUserExist($username)
    {
        $stmt = $this->prepareSqlStmt("SELECT COUNT(*) FROM user WHERE user_name=?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $row    = $result->fetch_row();
        if ($row[0] == 1) {
            return true;
        } else {
            return false;
        }
    }
    
    /*
     *for checking if an email address exists
     *@input=> email address:string
     *@output=> if email address exists:bool
     */
    public function isEmailExist($email)
    {
        $stmt = $this->prepareSqlStmt("SELECT COUNT(*) FROM user WHERE email=?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row    = $result->fetch_row();
        if ($row[0] == 1) {
            return true;
        } else {
            return false;
        }
    }
    
    /*
     *for checking if a user name exists
     *@input=> username:string, passwdHash:stirng
     *@output=> if username,passwdhash matches:bool
     */
    public function authenticate($username, $passwdHash)
    {
        $stmt = $this->prepareSqlStmt("SELECT COUNT(*) FROM user WHERE user_name=? AND password=?");
        $stmt->bind_param('ss', $username, $passwdHash);
        $stmt->execute();
        $result = $stmt->get_result();
        $row    = $result->fetch_row();
        if ($row[0] == 1) {
            return true;
        } else {
            return false;
        }
    }
    
    /*
     *for getting user id, when name given
     *@input=> username:string, password hash:string
     *@output=> int:userId
     */
    public function getUserId($username, $passwdHash)
    {
        $stmt = $this->prepareSqlStmt("SELECT id FROM user WHERE user_name=? AND password=?");
        $stmt->bind_param('ss', $username, $passwdHash);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }
    
    /*
     *empty the table
     *for testing only
     */
    public function deleteAllusers()
    {
        $stmt = $this->prepareSqlStmt("DELETE FROM user");
        return $stmt->execute();
    }
}
?>
