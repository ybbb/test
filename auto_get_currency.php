<?php
/**
 * @desc 获取币种信息
 */
require(dirname(dirname(__FILE__)).'/base.php');
class Daemon_Get_Currency_Auto extends Daemon_Base
{
    //private $url = 'http://fin.hqygou.com/inter/currency/get?token=5b201507045717f657c2e93a5ef0b940&sn=haitao';//测试地址
    private $url = 'http://fin.gw-ec.com/inter/currency/get?token=5b201507045717f657c2e93a5ef0b940&sn=haitao';//正式地址
    public function __construct()
    {
        parent::__construct();
        $this->log_suffix = substr(strtoupper(basename(__FILE__)),0,-4);//初始化日志前缀
        $this->url = $this->config->financial->currencyUrl;
        $this->start();
    }

    private function start(){
        $i = 1;
        $j = 1111111111111111111111111111111111111;
        $limit = 100;
        while($i <= $j) {
            //抓取数据
            $sendData = array(
                'per_page' => $limit,  //每一页的记录数
                'page' => $i,//页码
            );
            echo $i.PHP_EOL;
            $i++;
            $response = $this->crypt->curl($sendData,$this->url);
            $rep = json_decode($response,true);
            $this->log->write_custom( "{$response}\r\n",__FILE__,__LINE__,$this->log_suffix,$this->log->INFO);
            //计算需要循环处理的次数
            if(isset($rep['total']) && $rep['total']){
                $j = ceil($rep['total'] / $limit);
                foreach($rep['list'] as $key => $v){
                    $this->saveCurrency($v);
                    unset($rep['list'][$key]);
                }
            }
        }
    }

    /*
     * 保存币种信息
     * @param array $data 币种信息
     * */
    private function saveCurrency($data){
        try {
            $currencyWhere = ' WHERE 1 = 1 ';
            $currencyWhere .= $this->wdb->quoteInto(' AND currency_code = ?', $data['currency']);
            $currency_id = $this->wdb->fetchOne("SELECT currency_id FROM {$this->table->t_oms_currency} {$currencyWhere}");
            $saveData = array(
                'currency_code' => $data['currency'],//币种代码
                'currency_name' => $data['name'],//币种名称
                'currency_symbol' => $data['symbol'],//币种字符
                'is_lock' => $data['status'] == 2 ? 1 : 0,//状态
                'update_time' => date('Y-m-d H:i:s'),
                'update_user' => 'SYSTEM'
            );
            if ($currency_id) {//存在记录，更新数据
                if (empty($data['symbol'])) unset($saveData['currency_symbol']);//币种字符为空的，不更新币种字符
                $this->wdb->update($this->table->t_oms_currency, $saveData, 'currency_id = '.$currency_id);
            } else {//不存在记录，插入新数据
                $saveData['create_user'] = 'SYSTEM';
                $saveData['create_time'] = date('Y-m-d H:i:s');
                $this->wdb->insert($this->table->t_oms_currency, $saveData);
            }
        } catch (Exception $e) {
            $log = $e->getMessage();
            print_r($log);
            $this->log->write_custom("{$log}\r\n", __FILE__, __LINE__, $this->log_suffix,$this->log->INFO);
        }
    }
}
$obj = new Daemon_Get_Currency_Auto();