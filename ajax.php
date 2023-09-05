<?php

require_once 'config.php';
require_once 'class/CodeSnippet.php';

$codeSnippet = new CodeSnippet();

/*
if(isset($_POST['langage']) && isset($_POST['tags']) && isset($_POST['code'])) {
    $codeSnippet->setLangage($_POST['langage']);
    $codeSnippet->setTags($_POST['tags']);
    $codeSnippet->setCode($_POST['code']);

    $codeSnippet->save($bdd);
}
*/

if(isset($_GET['search'])) {
    $_GET['tags'] = explode(',', $_GET['tags']);
    echo json_encode($codeSnippet->search($bdd, $_GET['langage'], $_GET['tags'], true), JSON_THROW_ON_ERROR);
    die;
}
