<?php

include('function.php');

include('views/header.php');

switch ($_GET['page']) {

    // Pagine

    case 'logreg':

        if (empty($_SESSION['id'])) {

            include('views/logreg.php');
    
        } else {
    
            header('location: index.php');
        
        }

    break;

    case 'emailchange':

        if (!empty($_SESSION['id'])) {

            include('views/emailchange.php');
    
        } else {
    
            header('location: index.php');
        
        }
        
    break;

    case 'passwordchange':

        if (!empty($_SESSION['id'])) {

            include('views/passwordchange.php');
    
        } else {
    
            header('location: index.php');
        
        }
        
    break;

    case 'postrecipe':

        if (isAdmin()) {

            include('views/postrecipe.php');
    
        } else {
    
            header('location: index.php');
        
        }
        
    break;

    case 'editrecipe':

        if (isAdmin()) {

            include('views/editrecipe.php');
    
        } else {
    
            header('location: index.php');
        
        }
        
    break;

    case 'recipe':

        include('views/recipe.php');
        
    break;

    case 'category':

        include('views/category.php');
        
    break;

    case 'privacy':

        include('views/privacy.php');
        
    break;

    // Errori

    case '400':

        include('errors/400.php');
        
    break;

    case '401':

        include('errors/401.php');
        
    break;

    case '403':

        include('errors/403.php');
        
    break;

    case '404':

        include('errors/404.php');
        
    break;

    case '500':

        include('errors/500.php');
        
    break;

    // Index

    default:

        include('views/home.php');        

}

include('views/footer.php');

?>