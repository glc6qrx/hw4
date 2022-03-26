

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
                <h3>Hello <?=ucfirst($user["name"])?>!</h3>
            </div>
            <div class="row">
                <div class="col-xs-8 mx-auto">
                    <div class="p-5 bg-light border rounded-3">
                        <h2>The word was <?=$answer?></h2>
                        <!-- <p class="text-uppercase"><?=$answer?></p> -->
                        
                        <h2>You took <?=$guesses?> guesses</h2>
                    </div>
                    <form action="?command=wordle" method="post" class="row mt-5">
                        <span class="text-center my-auto">                
                        <button type="submit" class="btn btn-success col-12">Play Again</button>
                        <a href="?command=logout" class="btn btn-danger col-12 mt-3">Exit</a>
                        </span>                    
                    </form>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
    </body>
</html>