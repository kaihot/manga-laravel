<?php
namespace App\Http\Controllers;
/**
 * Created by PhpStorm.
 * User: kai
 * Date: 2018/02/12
 * Time: 23:54
 */
use App\Http\Controllers\Controller;
use Elasticsearch\ClientBuilder;
use Illuminate\Http\Request;

/**
 * Class TopController
 * @package App\Http\Controllers
 */
class SearchController extends Controller{

    public function __construct()
    {
        $this->ESClient = ClientBuilder::create()->setHosts([
            "http://search-manga-image-sdozidc55hog2xc6ryonyshlja.ap-northeast-1.es.amazonaws.com:80"
        ])->build();
    }

    public function index(Request $request){
        //1.全角スペースを半角スペースに変換
        $query = str_replace('　', ' ', $request->get("query"));
        //2.前後のスペース削除（trimの対象半角スペースのみなので半角スペースに変換後行う）
        $query = trim($query);
        //3.連続する半角スペースを半角スペースひとつに変換
        $query = preg_replace('/\s+/', ' ', $query);
        //姓と名で分割
        $query = explode(' ',$query);
        $should = [];
        foreach ($query as $q){
            $should[] =[
                "term" => [
                    "tags.keywords" => $q
                ]
            ];
            $should[] =
                [
                    "wildcard" => [
                        "plane_tags.raw" => [
                            "value" => "*{$q}*",
                            "boost" => 100
                        ]
                    ],
                ];
            $should[] =
                [
                    "match" =>  [
                        "tags.keywords" => [
                            "query" => $q,
                            "fuzziness" => "AUTO"
                        ]
                    ]
                ];
            $should[] =
                [
                    "match" =>  [
                        "plane_tags" => [
                            "query" => $q,
                            "fuzziness" => "AUTO"
                        ]
                    ]
                ];
            $should[] =
                [
                    "match" =>  [
                        "tag_reading_completion_ja" => [
                            "query" => $q,
                            "fuzziness" => "AUTO"
                        ]
                    ]
                ];
        }

        $params = [
            "index" => "prod",
            "type"  => "image",
            "size" =>999,

            "body" => [
                "query" => [
                    "function_score" => [
                        "query" => [
                            "bool" => [
                                "should" => $should
                            ]
                        ]
                    ]
                ]
            ]
        ];
        $re = $this->ESClient->search($params);
        /** @var TYPE_NAME $images */
        $images = $re["hits"]["hits"];
        foreach ($images as &$image){
            $image["thumbnail"] = env("S3")."/thumbnails/".$image["_id"].".".$image["_source"]["extension"];
        }

        $p_tags = $this->popular_tags();
        return view("search.index", [
            "images" => $images,
            "popular_tags" => $p_tags,
            "query" => $query
        ]);
    }

    /**
     * @return array
     */
    private function popular_tags(){
        $tags = [
            "ヒナまつり",
            "ニセコイ",
            "なもり",
            "ゆるゆり",
            "のんのんびより",
            "うすた京介",
            "ナルト",
            "アホガール",
            "ディーふらぐ",
            "範馬刃牙",
            "名探偵コナン",
            "恋愛ラボ",
            "はじめの一歩",
            "ばらかもん",
            "咲-Saki-",
            "BLEACH",
            "ピューと吹く!ジャガー",
        ];

        return $tags;
    }
}