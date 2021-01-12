<?php

class UrlsMemory
{
    const CREATE_SCHEMA_MEMORY =
    "CREATE SCHEMA IF NOT EXISTS aca;";

    const CREATE_TABLE =
    "CREATE TABLE IF NOT EXISTS ACA.LOJA_JOGO_PLATAFORMA(
        NOMEJOGO VARCHAR(40),
        NOMELOJA VARCHAR(40),
        NOMEPLATAFORMA VARCHAR(40),
        VERSAOJOGO VARCHAR(40),
        COPAO VARCHAR(20),
        PRECO VARCHAR(20),
        PRECOCOPAO VARCHAR(20),
        PRIMARY KEY (NOMEJOGO, NOMELOJA, NOMEPLATAFORMA, VERSAOJOGO));";

    private $mLastErrorCode, $mLastErrorMsg;
    private $mErrorCodes, $mErrorMsgs;
    private $mDb; //fundamental!

    const HOST = "localhost";
    const USER = "ACA";
    const PASS = "kkiilleerr";
    const PORT = 3306;

    public function __construct()
    {
        $this->mDb = mysqli_connect(self::HOST, self::USER, self::PASS, "", self::PORT);
        $this->mLastErrorCode = mysqli_connect_errno();
        $this->mLastErrorMsg = mysqli_connect_error();
        $this->mErrorCodes[] = $this->mLastErrorCode;
        $this->mErrorMsgs[] = $this->mLastErrorMsg;

        $this->errorFb();
    } //__construct

    private function errorFb()
    {
        if ($this->mLastErrorCode !== 0) {
            $strMsg = sprintf(
                "Last error code: %d\n%s",
                $this->mLastErrorCode,
                $this->mLastErrorMsg
            );
            echo $strMsg;
        }
    } //errorFb

    private function updateErrors()
    {
        $this->mLastErrorCode = mysqli_errno($this->mDb);
        $this->mLastErrorMsg = mysqli_error($this->mDb);
        $this->mErrorCodes[] = $this->mLastErrorCode;
        $this->mErrorMsgs[] = $this->mLastErrorMsg;
    } //updateError

    public function install()
    {
        if ($this->mDb) {
            $this->mDb->query(self::CREATE_SCHEMA_MEMORY);
            $this->updateErrors();
            $this->errorFb();

            $this->mDb->query(self::CREATE_TABLE);
            $this->updateErrors();
            $this->errorFb();
        } //if
    } //install

    public function insertUrl(
        $pNomeJogo,
        $pNomeLoja,
        $pNomePlataforma,
        $pVersaoJogo,
        $pCopao,
        $pPreco,
        $pPrecoCopao

    ) {
        $q = "INSERT INTO ACA.LOJA_JOGO_PLATAFORMA  VALUES (
            '$pNomeJogo',
            '$pNomeLoja',
            '$pNomePlataforma',
            '$pVersaoJogo',
            '$pCopao',
            '$pPreco',
            '$pPrecoCopao'
            );";

        $this->mDb->query($q);

        $this->updateErrors();
        $this->errorFb();
    } //insertUrl

    public function selectAllUrls()
    {
        $q = "SELECT * FROM ACA.LOJA_JOGO_PLATAFORMA;";

        $r = $this->mDb->query($q);
        $this->updateErrors();
        $this->errorFb();

        $aAllRecords = mysqli_fetch_all($r, MYSQLI_ASSOC);

        return $aAllRecords;
    } //selectAllUrls
}

/*
$o = new UrlsMemory();
$o->install();
$o->insertUrl("DOOM", "GAMEStore", "STEAM", "DELUXE", "5% WTFAF", "100€", "95€");
$o->insertUrl("SKYRIM", "GAMEStore", "STEAM", "DELUXE", "5% WTFAF", "100€", "95€");
$o->insertUrl("DOOM", "GOG", "STEAM", "DELUXE", "5% WTFAF", "100€", "95€");
$o->insertUrl("DOOM", "GAMEStore", "EPIC", "DELUXE", "5% WTFAF", "100€", "95€");
$all = $o->selectAllUrls();
var_dump($all);
*/