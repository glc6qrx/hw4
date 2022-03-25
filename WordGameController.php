<?php
session_start();
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
        unset($_SESSION["name"]);
        unset($_SESSION["email"]);
        unset($_SESSION["guesses"]);
        unset($_SESSION["word"]);
        unset($_SESSION["previous"]);
        session_destroy();
        header("Location: ?command=login");
        
    }

    // Display the login page (and handle login logic)
    public function login() {
        if (isset($_POST["email"]) && !empty($_POST["email"])) { /// validate the email coming in
            $_SESSION["name"] = $_POST["name"];
            $_SESSION["email"] = $_POST["email"];
            $_SESSION["guesses"] = 0;
            $_SESSION["word"] = $this->loadWord();
            $_SESSION["previous"] = [];
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
        $data = $_SESSION['previous'];
        $user = [
            "name" => $_SESSION["name"],
            "email" => $_SESSION["email"],
            "guesses" => $_SESSION["guesses"],
            "word" => $_SESSION["word"],
            "previous" => $_SESSION["previous"]
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
            $_SESSION["guesses"] += 1;

            
            
            

            

            if ( $user["word"] == $answer) {
                // user answered correctly -- perhaps we should also be better about how we
                // verify their answers, perhaps use strtolower() to compare lower case only.
                
                $guesses = $_SESSION["guesses"] + 1;
                $message = "<div class='alert alert-success'><b>$answer</b> was correct! It took you $guesses guesses!</div>";

                // Update the score
                 

                // Update the cookie: won't be available until next page load (stored on client)
                

               
                
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

                $word = $user["word"];

                $answerFreq = array();
                $wordFreq = array();

                $higher = max(strlen($answer), strlen($word));
                

                $correctPositions = 0;
                $containsCount = 0;
                
                for($i = 0; $i<=$higher;$i++){
                    if($i < strlen($answer)){
                        if(array_key_exists($answer[$i], $answerFreq)){
                            $answerFreq[$answer[$i]] = $answerFreq[$answer[$i]] + 1;
                        }
                        else{
                            $answerFreq[$answer[$i]] = 1;
                        }
                    }
                    if($i < strlen($word)){
                        if(array_key_exists($word[$i], $wordFreq)){
                            $wordFreq[$word[$i]] = $wordFreq[$word[$i]] + 1;
                        }
                        else{
                            $wordFreq[$word[$i]] = 1;
                        }
                    }
                    if($i < strlen($answer) && ($i < strlen($word))){
                        if($word[$i] == $answer[$i]){
                            $correctPositions += 1;
                        }
                    }
                }
                
                
                
                foreach ($answerFreq as $key => $value) {
                    if(array_key_exists($key, $wordFreq)){
                        if($wordFreq[$key] != 0){
                            $containsCount += 1;
                            $wordFreq[$key] = $wordFreq[$key] - 1;
                        }
                    }
                }

                

                $message = "<div class='alert alert-danger'><b>$answer</b> was incorrect!</div>";
                
            }

            $guessData["guess"] = $_POST["answer"];
            $guessData["length"] = strlen($_POST["answer"]);
            $guessData["correctPos"] = $correctPositions;
            $guessData["containsCount"] = $containsCount;

            $user["previous"][] = $guessData;
            $data[] = $guessData;
            $_SESSION["previous"] = $data;
            $previousGuesses = $_SESSION['previous'];



        }
       

        include("wordle.php");
    }

   // public function wordle() {
    
    //}
}