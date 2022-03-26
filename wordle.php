

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">  
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="your name">
        <meta name="description" content="include some description about your page">  
        <title>Word Game</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous"> 
    </head>
    <body>
        <div class="container" style="margin-top: 15px;">
            <div class="row col-xs-8">
                <h1>CS4640 Extreme Wordle Game</h1>
                <h3>Hello <?=ucfirst($user["name"])?>! Guesses: <?=$user["guess_count"]?></h3>
            </div>
            <div class="row">
                <div class="col-xs-8 mx-auto">
                <form action="?command=wordle" method="post" class="row p-3">
                    <span class="h-10 my-auto col-md-10">
                        <input type="text" class="form-control" id="answer" name="answer" placeholder="Type your word here">
                    </span>
                    <span class="text-center col-md-2 my-auto">                
                        <button type="submit" class="btn btn-primary col-12">Submit</button>
                    </span>                    
                </form>
                <div class="p-5 bg-light border rounded-3">
                    <h2>Previous Guesses</h2>
                    <!-- <p class="text-uppercase"><?=$user["hidden_word"]?></p> -->
                    
                    <?php 
                        foreach($user["previous_guesses"] as $guess) {
                            echo "<h2 class='text-capitalize'>", $guess["guess"], "</h2>";
                            echo "<ul class = 'text-uppercase'>
                                    <li>Length: ", $guess["length"], "</li>
                                    <li>", $guess["longshort"], " in comparison</li>
                                    <li>Number of characters in the correct position: ", $guess["correctPos"], "</li>",
                                    "<li>Number of Characters contained in correct word (duplicates not included): ", $guess["containsCount"], '</li>
                                    </ul><br>';
                        } 
                    ?>
                   
                    <input type="hidden" name="questionid" value="<?=$question["id"]?>"/>
                    </div>
                    <a href="?command=endgame" class="mt-3 btn btn-danger col-12">End Game</a>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
    </body>
</html>