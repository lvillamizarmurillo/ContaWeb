<?php

require_once __DIR__ . '/../model/DocumentModel.php';

class DocumentService {
    private $documentModel;

    public function __construct(DocumentModel $documentModel) {
        $this->documentModel = $documentModel;
    }

    /**
     * Obtiene las empresas que tienen más documentos fallidos que exitosos.
     * Retorna un arreglo de IDs de empresas que cumplen con esta condición.
     */
    public function getCompaniesWithMoreFailedThanSuccessful() {
        return $this->documentModel->getCompaniesWithMoreFailedThanSuccessful();
    }
}
