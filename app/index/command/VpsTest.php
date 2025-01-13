<?php

namespace app\index\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use app\index\model\User;
use app\index\model\Index;
use app\index\model\Log;
use think\Config;

class VpsTest extends Command
{
    protected $debug = true;

    protected function configure()
    {
        $this->setName("VpsTest")->setDescription("This is Vps Test Command");
    }

    protected function execute(Input $input, Output $output)
    {
        // 获取参数值
        Config::load(APP_PATH . 'index/config.php');
        $log = new Log;
        $index = new Index;
        $user = new User;
        $host = config("app.host");
        $testDebug = config("app.testdebug");
        $tgChannelSckey = config("app.tgchannelsckey");
        $bot_token = config("app.bot_token");
        while (true) {
            $output->writeln(date("Y-m-d h:i:s"));
            $list = $index->alias('a')
                ->join('xm_company c', "c.id = a.companyid")
                ->field("a.*,c.name as companyname,c.url as companyurl")
                ->where(["status" => 1])
                ->select();

            $r = [];
            foreach ($list as $value) {
                $iscf = $value["iscf"];
                if ($iscf == 1) {
                    $curl = cf_curl($value["vurl"], "get");
                    if ($testDebug) {
                        file_put_contents(TEMP_PATH . "/debug.txt", "------[START]------\n" . date("Y-m-d h:i:s") . "\n{$curl}\n------[END]------", FILE_APPEND);
                    }
                } else {
                    $curl = go_curl($value["vurl"], "get");
                    if ($testDebug) {
                        file_put_contents(TEMP_PATH . "/debug.txt", "------[START]------\n" . date("Y-m-d h:i:s") . "\n{$curl['RequestHeader']}\n\n{$curl['ResponseHeader']}\n{$curl['Body']}\n------[END]------", FILE_APPEND);
                    }
                }
                $vf = $value["vf"];
                if ($iscf == 1) {
                    $str = $curl;
                } else {
                    $str = $curl["Body"];
                }
                if (strpos($str, $vf) !== false) {
                    $isinstock = false;
                } else {
                    $isinstock = true;
                }

                $r[] = "{$value['name']} --- " . ($isinstock ? 'true' : 'false');
                if ($isinstock != $value["stock"]) {
                    //如果状态发生必变
                    Index::where("id", $value["id"])->update(["stock" => $isinstock]);
                    Log::create(["indexid"=> $value["id"],"status"=>$isinstock]);
                    //Log::where("indexid", $value["id"])->update(["status" => $isinstock]);
                }
                if ($isinstock != $value["stock"] && $isinstock) {
                    //状态发生改变才发送通知，并且是有货状态，也就是通知只发送一次
                    $p = $user->where("find_in_set({$value['id']},subscribe)")->select();
                    $title = "您关注的{$value['name']}有货啦。";
                    $content = "您关注的{$value['name']}有货啦。\n快来大肆抢购呀。\n测评地址：$host/ceping/{$value['id']}\n购买地址：$host/buy/{$value['id']}";
                    foreach ($p as $v) {
                        if ($v["ftsckey"]) {
                            go_curl("https://sc.ftqq.com/{$v['ftsckey']}.send", "post", ["text" => $title, "desp" => $content]);
                        }
                        if ($v["tgsckey"]) {
                            bot_send($bot_token,$v["tgsckey"],$content);
                        }
                    }
                    if ($tgChannelSckey) {
                        $this->bot_send($bot_token, $tgChannelSckey, $content);
                    }
                }
                sleep(3);
            }
            $output->writeln(implode("\n", $r) . "\nOK");
            $output->writeln("Success !");
            $output->writeln(date("Y-m-d h:i:s"));
            sleep(config("app.testtick"));
        }
    }

    function bot_send($token,$chatID, $messaggio ) {
        echo "sending message to " . $chatID . "\n";

        $url = "https://api.telegram.org/bot" . $token . "/sendMessage?chat_id=" . $chatID;
        $url = $url . "&text=" . urlencode($messaggio);
        $ch = curl_init();
        $optArray = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true
        );
        curl_setopt_array($ch, $optArray);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}


