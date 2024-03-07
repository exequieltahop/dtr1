<?php
    class Main{
        // private name
        private $name;
        // constructor
        public function __construct($name) {
            $this->name = $name;
        }
        // main function
        public function main() {
            echo '<form method="POST" action="loginProcess.php">';
                echo '<label>Username</label>'.'<br>'.'<br>';
                echo '<input type="text" name="username">'.'<br>'.'<br>';
                echo '<label>Password</label>'.'<br>'.'<br>';
                echo '<input type="password" name="password">'.'<br>'.'<br>';
                echo '<button type="submit" name="submit">Login</button>';
                echo '<button type="reset">clear</button>';
            echo '</form>';
        }
    }
    $main = new Main('Exequiel');
    echo $main->main();
?>