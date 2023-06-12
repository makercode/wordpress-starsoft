<?php

interface IDocumentsApi {

    public function getDocumentJson( $documentId );
    public function sendDocument( $documentId );

}
