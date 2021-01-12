<?php
require_once "GamingCheap.php";
require_once "SaveInfo.php";

$bot = new GamingCheap($argv[1]);
$info = $bot->receberInfo();

//var_dump($info);

$db = new UrlsMemory();
$db->install();

foreach ($info as $jogo){
    //var_dump($jogo->mCouponText);
    $db->insertUrl(
        $jogo->mGame,
        $jogo->mOnlineStore,
        $jogo->mPlatform,
        $jogo->mGameVersion,
        $jogo->mCouponPercentageAndName,
        $jogo->mPriceWithoutCoupon,
        $jogo->mActualPrice);
}


$all = $db->selectAllUrls();
var_dump($all);