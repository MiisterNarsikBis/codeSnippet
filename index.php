<?php

require_once 'config.php';
require_once 'class/CodeSnippet.php';

$code = new CodeSnippet();


$test = $code->search($bdd, 'php', ['hello']);

dd($test);

/*
foreach ($test as $code) {
    if($code instanceof CodeSnippet) {
        dump($code->getTags());
    }
}
*/
//dd($code->save($bdd));

//$bdd->exec('INSERT INTO code_snippet (langage, tags, code) VALUES ("' . $code->getLangage() . '", "' . $code->getTags() . '", "' . $code->getCode() . '")');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Code Snippet</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-3">
        <div class="col-md-3"></div>
        <div class="col-6">
            <div class="form-group has-search">
                <span class="fa fa-search form-control-feedback"></span>
                <input type="text" class="form-control" placeholder="Search">
            </div>
        </div>
        <div class="col-md-3"></div>
    </div>
</body>

<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script>
    $(document).ready(function(){



    })
</script>
</html>


