<?php
class WordGameController {

    private $command;

    
    public function __construct($command) {
        $this->command = $command;
    }

    public function run() {
        switch($this->command) {
            case "wordle":
                $this->wordle();
                break;
            case "logout":
                $this->destroySession();
            case "login":
            default:
                $this->login();
                break;
        }
    }

    // Clear all the cookies that we've set
    private function destroySession() {          
        session_destroy();
        header("Location: ?command=login");
    }

    // Display the login page (and handle login logic)
    public function login() {        
        if (isset($_POST["email"]) && !empty($_POST["email"])) { /// validate the email coming in
            $_SESSION["name"] = $_POST["name"];
            $_SESSION["email"] = $_POST["email"];
            $_SESSION["guess_count"] = 0;
            $_SESSION["hidden_word"] = $this->loadWord();
            $_SESSION["previous_guesses"] = [];
            header("Location: ?command=wordle");
            return;
        }
        include "login.php";
    }
    

    // Load a question from the API
    private function loadWord() {
        $wordData = explode("\n", file_get_contents("https://www.cs.virginia.edu/~jh2jf/courses/cs4640/spring2022/wordlist.txt"));
        // $input = array("Charemsa","MindGoblin","Candice", "Rhydon", "Rammus", "Stigma", "Bofadese", "Dragon", "Sugondese", "Gulpin", "Sawcon", "Wilma", "WonaPound", "PennyTrading", "Nuddinyore", "Shogun","Lee Gandhi","PlantTulips", "Sawk", "Wendy",);
        $word = $wordData[array_rand($wordData)];
        

        
        // Return the question
        
        //return implode('', $wordData);
        return $word;
    }

    
    public function wordle() {
        // set user information for the page from the cookie
        $data = $_SESSION["previous_guesses"];
        $user = [
            "name" => $_SESSION["name"],
            "email" => $_SESSION["email"],
            "guess_count" => $_SESSION["guess_count"],
            "hidden_word" => strtolower($_SESSION["hidden_word"]),
            "previous_guesses" => $_SESSION["previous_guesses"]
        ];
        // update the question information in cookies
        // setcookie("answer", $user["hidden_word"], time() + 3600);
        if (isset($_POST["answer"])) {
            //Array to store guess, length, how many characters correct etc.
            $guessData = [];
            // echo "INSIDE";
            $answer = strtolower($_POST["answer"]);
            
            $user["guess_count"] += 1; 
            $_SESSION["guess_count"] += 1;
            
            if ($user["hidden_word"] === $answer) {
                // user answered correctly -- perhaps we should also be better about how we
                // verify their answers, perhaps use strtolower() to compare lower case only.
                $guesses = $user["guess_count"];
                $message = "<div class='alert alert-success'><b>$answer</b> was correct! It took you $guesses guesses!</div>";
                echo $message;
            } else { 
                
                if(strlen($_POST["answer"]) === strlen($user["hidden_word"])){
                    $guessData["longshort"] = "just right";
                }
                else if(strlen($_POST["answer"]) < strlen($user["hidden_word"])){
                    $guessData["longshort"] = "too short";
                }
                else{
                    $guessData["longshort"] = "too long";
                }
                $word = $user["hidden_word"];
                $answer_char_freq = array();
                $hidden_word_char_freq = array();
                $higher = max(strlen($answer), strlen($word));
                
                $correctPositions = 0;
                $containsCount = 0;
                
                for($i = 0; $i<=$higher;$i++){
                    if($i < strlen($answer)){
                        $answer_char_freq[$answer[$i]] = array_key_exists($answer[$i], $answer_char_freq) ? $answer_char_freq[$answer[$i]] + 1 : 1;
                    }
                    if($i < strlen($word)){
                        $hidden_word_char_freq[$word[$i]] = array_key_exists($word[$i], $hidden_word_char_freq) ? $hidden_word_char_freq[$word[$i]] + 1 : 1;
                    }
                    if($i < strlen($answer) && ($i < strlen($word))){
                        if($word[$i] === $answer[$i]){
                            $correctPositions += 1;
                        }
                    }
                }
                foreach ($answer_char_freq as $key => $value) {
                    if(array_key_exists($key, $hidden_word_char_freq)){
                        if($hidden_word_char_freq[$key] != 0){
                            $containsCount += 1;
                            $hidden_word_char_freq[$key] = $hidden_word_char_freq[$key] - 1;
                        }
                    }
                }
                
                $message = "<div class='alert alert-danger'><b>$answer</b> was incorrect!</div>";
                echo $message;
                
                $guessData["guess"] = $_POST["answer"];
                $guessData["length"] = strlen($_POST["answer"]);
                $guessData["correctPos"] = $correctPositions;
                $guessData["containsCount"] = $containsCount;
    
                $user["previous_guesses"][] = $guessData;
                $data[] = $guessData;
                $_SESSION["previous_guesses"] = $data;
                $previousGuesses = $_SESSION["previous_guesses"];
            }
        }
        include("wordle.php");
    }
}