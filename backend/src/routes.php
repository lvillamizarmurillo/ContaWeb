<?php
namespace NexusRouter;


use NexusRouter\Core\Router;

    Router::get('/document-failed', 'Controllers/EmpresaController', 'getDocumentsFailedData');
    Router::get('/document-date/$dateStart/$dateEnd', 'Controllers/EmpresaController', "getDocumentsinDateEspecificData");
    Router::get('/document-from-each-company', 'Controllers/EmpresaController', "getDocumentsFromEachCompanyData");
    Router::get('/document-failed-more-than-three', 'Controllers/EmpresaController', "getDocumentsFailedMoreThanThreeData");
    Router::get('/document-out-range', 'Controllers/EmpresaController', "getDocumentsOutOfRangeData");
    Router::get('/document-out-range', 'Controllers/EmpresaController', "getDocumentstotalInvoiceData");
    Router::get('/number-complete-repeated', 'Controllers/EmpresaController', "getNumberCompleteRepeatedData");
    Router::post('/save-new-document', 'Controllers/EmpresaController', "postSaveNewDocumentData");
    Router::put('/update-document', 'Controllers/EmpresaController', "putSaveDocumentIdData");
    Router::delete('/delete-document', 'Controllers/EmpresaController', "deleteDocumentIdData");


