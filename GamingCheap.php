<?php

require_once "AmUtil.php";

class GamingCheap {
    //data member
    private $mGameName; //nome do jogo a pesquisar e.g. "Tekken 7"
    private $mBoardValidUrl; //variavel para validar um Url e.g. "https://www.allkeyshop.com/blog/buy-tekken-7-cd-key-compare-prices/"
    private $mBoardHtmlForValidUrl; //html do url pretendido

    const BASE_URL_PREFIX = "https://www.allkeyshop.com/blog/buy-";
    const BASE_URL_SUFIX = "-cd-key-compare-prices/";

    public function __construct(
        string $pGameName
    )
    {
        $this->mGameName = $pGameName;

        //criar url valido consoante jogo dado
        $this->mBoardValidUrl = $this->buildValidUrl();

        //retornar html da página de resultados do jogo inserido
        $this->mBoardHtmlForValidUrl = $this->buildHtmlOfSearchPage(); //method returns null, but it built the data member with the proper values

        //$this->buildHyperlinksForBoardValidUrlsForImages();
    }//__construct

    public function buildValidUrl()
    {
        $validGameName = strtolower(str_replace(
            " ",
            "-",
            $this->mGameName
        ));
        return self::BASE_URL_PREFIX.$validGameName.self::BASE_URL_SUFIX;
    }//buildValidUrl

    public function buildHtmlOfSearchPage(){
        return AmUtil::consumeUrl($this->mBoardValidUrl);
    }//buildHtmlOfSearchPage
} //GamingCheap
