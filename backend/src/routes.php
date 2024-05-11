<?php
namespace NexusRouter;

use NexusRouter\Core\Router;

    Router::get('/document-failed', 'Controllers/EmpresaController', "getDocumentsFailedData");
    Router::get('/document-date/$dateStart/$dateEnd', 'Controllers/EmpresaController', "getDocumentsinDateEspecificData");
    Router::get('/document-from-each-company', 'Controllers/EmpresaController', "getDocumentsFromEachCompanyData");
    Router::get('/document-failed-more-than-three', 'Controllers/EmpresaController', "getdocumentsFailedMoreThanThreeData");
    Router::get('/document-out-range', 'Controllers/EmpresaController', "documentsOutOfRangeData");
    Router::get('/document-out-range', 'Controllers/EmpresaController', "getdocumentstotalInvoiceData");
    Router::get('/number-complete-repeated', 'Controllers/EmpresaController', "getnumberCompleteRepeatedData");
    Router::post('/save-new-document', 'Controllers/EmpresaController', "postsaveNewDocumentData");



















# PATH EXAMPLES #
// Simple route
Router::get('/users' , 'Controllers/User');

// Route with value
Router::get('/users/$list', 'Controllers/User');

// Route with value and injected metadata value
Router::get('/users/$list', 'Controllers/User', "getUsers");

# Alternative syntax
Router::get('/users' , 'Controllers/User')
      ->get('/users/$list', 'Controllers/User')
      ->get('/users/$list', 'Controllers/User', "getUsers");


# CALLBACK EXAMPLES #

// Simple callback: : http://localhost/welcome
Router::get('/welcome', function(){
    echo 'I love Nexus Router!';
});

// Singleparam Example: http://localhost/welcome/alexander
Router::get('/welcome/$name', function($param1){
    echo $param1 . ' loves Nexus Router!';
});


// Multiparams Example: http://localhost/welcome/alexander/calderon
Router::get('/welcome/$name/$lastname', function($param1,$param2){
    echo $param1 . ' ' . $param2 . ' loves Nexus Router!';
});



