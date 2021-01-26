<?php
require_once ("config.php");

class GamesMemory
{
    const CREATE_SCHEMA_MEMORY =
    "CREATE SCHEMA IF NOT EXISTS ACA;";

    const CREATE_TABLE =
    "CREATE TABLE IF NOT EXISTS ACA.LOJA_JOGO_PLATAFORMA(
        NOMEJOGO VARCHAR(40),
        NOMELOJA VARCHAR(40),
        NOMEPLATAFORMA VARCHAR(40),
        VERSAOJOGO VARCHAR(40),
        COPAO VARCHAR(20),
        PRECO VARCHAR(20),
        PRECOCOPAO VARCHAR(20),
        DATAREGISTO VARCHAR(19),
        PRIMARY KEY (NOMEJOGO, NOMELOJA, NOMEPLATAFORMA, VERSAOJOGO));";

    private $mLastErrorCode, $mLastErrorMsg;
    private $mErrorCodes, $mErrorMsgs;
    private $mDb; //fundamental!

    public function __construct()
    {
        $this->mDb = mysqli_connect(HOST, USER, PASS, "", PORT);
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
            //echo $strMsg;
        }
    } //errorFb

    private function updateErrors()
    {
        $this->mLastErrorCode = mysqli_errno($this->mDb);
        $this->mLastErrorMsg = mysqli_error($this->mDb);
        $this->mErrorCodes[] = $this->mLastErrorCode;
        $this->mErrorMsgs[] = $this->mLastErrorMsg;
    } //updateErrors

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

    public function insertGames(
        $pNomeJogo,
        $pNomeLoja,
        $pNomePlataforma,
        $pVersaoJogo,
        $pCopao,
        $pPreco,
        $pPrecoCopao

    ) {
        $data = date("d/m/Y H:i:s");
        $q = "INSERT INTO ACA.LOJA_JOGO_PLATAFORMA  VALUES (
            '$pNomeJogo',
            '$pNomeLoja',
            '$pNomePlataforma',
            '$pVersaoJogo',
            '$pCopao',
            '$pPreco',
            '$pPrecoCopao', 
            '$data'
            );";

        $this->mDb->query($q);

        $this->updateErrors();
        $this->errorFb();
    } //insertGames

    public function selectAllGames()
    {
        $q = "SELECT * FROM ACA.LOJA_JOGO_PLATAFORMA;";

        $r = $this->mDb->query($q);
        $this->updateErrors();
        $this->errorFb();

        $aAllRecords = mysqli_fetch_all($r, MYSQLI_ASSOC);

        return $aAllRecords;
    } //selectAllGames
    
    public function selectUniqueNames()
    {
        $q = "SELECT DISTINCT NOMEJOGO from ACA.LOJA_JOGO_PLATAFORMA;";

        $r = $this->mDb->query($q);
        $this->updateErrors();
        $this->errorFb();

        $aAllRecords = mysqli_fetch_all($r, MYSQLI_ASSOC);

        return $aAllRecords;
    } //selectUniqueNames

    public function alreadyThere(
        $pNomeJogo,
        $pNomeLoja,
        $pNomePlataforma,
        $pVersaoJogo
    ) {
        $q = "SELECT * FROM ACA.LOJA_JOGO_PLATAFORMA WHERE
         NOMEJOGO = '$pNomeJogo' AND
         NOMELOJA = '$pNomeLoja' AND
         NOMEPLATAFORMA = '$pNomePlataforma' AND
         VERSAOJOGO = '$pVersaoJogo';";

        $r = $this->mDb->query($q);
        $this->updateErrors();
        $this->errorFb();

        return mysqli_num_rows($r) > 0;
    } //alreadyThere

    public function update(
        $pNomeJogo,
        $pNomeLoja,
        $pNomePlataforma,
        $pVersaoJogo,
        $pCopao,
        $pPreco,
        $pPrecoCopao
    ) {
        $data = date("d/m/Y H:i:s");
        $q = "UPDATE ACA.LOJA_JOGO_PLATAFORMA SET
        COPAO = '$pCopao',
        PRECO = '$pPreco',
        PRECOCOPAO = '$pPrecoCopao',
        DATAREGISTO = '$data' WHERE
        NOMEJOGO = '$pNomeJogo' AND
        NOMELOJA = '$pNomeLoja' AND
        NOMEPLATAFORMA = '$pNomePlataforma' AND
        VERSAOJOGO = '$pVersaoJogo';";

        $r = $this->mDb->query($q); //retorna true se funcionar false se n
        $this->updateErrors();
        $this->errorFb();
    } //update
}
