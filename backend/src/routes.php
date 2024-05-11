<?php
namespace NexusRouter;

use NexusRouter\Core\Router;
    Router::get('/welcome', function(){
        echo 'I love Nexus Router!';
    });
    Router::get('/document-failed', 'Controllers/EmpresaController', "getDocumentsFailedData");
    Router::get('/document-date/$dateStart/$dateEnd', 'Controllers/EmpresaController', "getDocumentsinDateEspecificData");
    Router::get('/document-from-each-company', 'Controllers/EmpresaController', "getDocumentsFromEachCompanyData");
    Router::get('/document-failed-more-than-three', 'Controllers/EmpresaController', "getDocumentsFailedMoreThanThreeData");
    Router::get('/document-out-range', 'Controllers/EmpresaController', "getDocumentsOutOfRangeData");
    Router::get('/document-out-range', 'Controllers/EmpresaController', "getDocumentstotalInvoiceData");
    Router::get('/number-complete-repeated', 'Controllers/EmpresaController', "getNumberCompleteRepeatedData");
    Router::post('/save-new-document', 'Controllers/EmpresaController', "postSaveNewDocumentData");
    Router::put('/update-document', 'Controllers/EmpresaController', "putSaveDocumentIdData");
    Router::delete('/delete-document', 'Controllers/EmpresaController', "deleteDocumentIdData");



















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



