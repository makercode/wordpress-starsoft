<?php

interface IDocumentsDatabase {

    public function createTable();
    public function getDocuments();
    public function getDocument( $documentId );
    public function updateDocument( $info, $orderId );
    public function setDocument( $info, $orderId );
}
