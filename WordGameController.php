<?php
class WordGameController {

    private $command;

    
    public function __construct($command) {
        $this->command = $command;
    }

    public function run() {
        switch($this->command) {
            case "wordle":
                $this->wordle(True);
                break;
            case "wordle2":
                $this->wordle(False);
                break;
            case "logout":
                $this->destroyCookies();
            case "login":
            default:
                $this->login();
                break;
        }
    }

    // Clear all the cookies that we've set
    private function destroyCookies() {          
        setcookie("correct", "", time() - 3600);
        setcookie("name", "", time() - 3600);
        setcookie("email", "", time() - 3600);
        setcookie("guesses", "", time() - 3600);
        setcookie("word", "", time() - 3600);
        setcookie("previous", "", time() - 3600);
    }

    // Display the login page (and handle login logic)
    public function login() {
        if (isset($_POST["email"]) && !empty($_POST["email"])) { /// validate the email coming in
            setcookie("name", $_POST["name"], time() + 3600);
            setcookie("email", $_POST["email"], time() + 3600);
            setcookie("guesses", 0, time() + 3600);
            setcookie("word", $this->loadWord(), time() + 3600);
            setcookie("previous", json_encode([]), time() + 3600);
            header("Location: ?command=wordle");
            return;
        }

        include "login.php";
    }
    

    // Load a question from the API
    private function loadWord() {
        //$wordData = json_decode(
        //    file_get_contents("https://random-word-api.herokuapp.com/word")
        //   , true);
        $input = array("Charemsa","MindGoblin","Candice", "Rhydon", "Rammus", "Stigma", "Bofadese", "Dragon", "Sugondese", "Gulpin", "Sawcon", "Wilma", "WonaPound", "PennyTrading", "Nuddinyore", "Shogun","Lee Gandhi","PlantTulips", "Sawk", "Wendy",);
        $wordData = $input[array_rand($input)];
        

        
        // Return the question
        
        //return implode('', $wordData);
        return $wordData;
    }

    
    public function wordle($setup) {
        // set user information for the page from the cookie
        $data = json_decode($_COOKIE['previous'], true);
        $user = [
            "name" => $_COOKIE["name"],
            "email" => $_COOKIE["email"],
            "guesses" => $_COOKIE["guesses"],
            "word" => $_COOKIE["word"],
            "previous" => $data
        ];

        // load the question
        //if($setup){
        //    echo "load word";
        //    $this->question = $this->loadWord();
        //}
        //$question = $this->question;

        //echo "OUTSIDE";
        //echo $question;
        
        // if the user submitted an answer, check it

        // update the question information in cookies
        setcookie("answer", $user["word"], time() + 3600);

        if (isset($_POST["answer"])) {
            //Array to store guess, length, how many characters correct etc.
            $guessData = [];



            echo "INSIDE";
            $answer = $_POST["answer"];
            
            
            $user["guesses"] += 1; 
            setcookie("guesses", $_COOKIE["guesses"] + 1, time() + 3600);

            
            
            

            

            if ( $user["word"] == $answer) {
                // user answered correctly -- perhaps we should also be better about how we
                // verify their answers, perhaps use strtolower() to compare lower case only.
                
                $guesses = $_COOKIE["guesses"] + 1;
                $message = "<div class='alert alert-success'><b>$answer</b> was correct! It took you $guesses guesses!</div>";

                // Update the score
                 

                // Update the cookie: won't be available until next page load (stored on client)
                
                setcookie("correct", "", time() - 3600);

               
                
            } else { 
                
                if(strlen($_POST["answer"]) == strlen($user["word"])){
                    $guessData["longshort"] = "just right";
                }
                else if(strlen($_POST["answer"]) < strlen($user["word"])){
                    $guessData["longshort"] = "too short";
                }
                else{
                    $guessData["longshort"] = "too long";
                }

                $answerArr = str_split($answer);
                $containsCount = 0;

                

                $message = "<div class='alert alert-danger'><b>$answer</b> was incorrect!</div>";
                
            }

            $guessData["guess"] = $_POST["answer"];
            $guessData["length"] = strlen($_POST["answer"]);
            

            $user["previous"][] = $guessData;
            $data[] = $guessData;
            setcookie("previous", json_encode($data), time() + 3600);
            $previousGuesses = json_decode($_COOKIE['previous'], true);
        }
       

       

        include("wordle.php");
    }

   // public function wordle() {
    
    //}
}