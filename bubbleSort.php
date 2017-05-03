<?php
    function bubbleSort(&$arr){
        for($i = count($arr) - 1; $i > 0; $i--){
            $flag = false;//本次循环，值是否发生了调换
            for($j = 0; $j < $i; $j++){//每次循环把较小的值往右边挪
                if($arr[$j] < $arr[$j + 1]){//如果左边的值比右边的值小，较小值挪到右边，即调换左右值
                    $temp = $arr[$j + 1];//临时变量保存右边的值
                    $arr[$j + 1] = $arr[$j];//左边的值赋值到右边
                    $arr[$j] = $temp;//右边的原值赋值到左边
                    $flag = true;//值发生了调换
                }
            }
            if (!$flag){
                break;//本次循环，值并没有发生调换，证明已经成功排序，可以提前终止程序
            }
        }
    }

    //选择排序法
    function selectSort(&$arr){
        for($i = 0, $count = count($arr) - 1; $i < $count; $i++){//每次循环找最大的值
            $maxIndex = $i;//假设最大的值为第一个值，记录值的下标
            for($j = $i + 1; $j <= $count; $j++){
                if($arr[$j] > $arr[$maxIndex]){//还有更大的值
                    $maxIndex = $j;//记录最大值的下标
                }
            }
            $maxValue = $arr[$maxIndex];//临时变量保存最大值
            if($maxValue != $arr[$i]){//本次循环的第一个值不是最大值，需与最大值互换位置
                $arr[$maxIndex] = $arr[$i];//本次循环第一个值放到本次最大值的位置上
                $arr[$i] = $maxValue;//本次最大值放到本次循环第一个值的位置上
            }
        }
    }

    //快速排序法
    function quickSort($arr){
        $leftCollection = array();
        $rightCollection = array();
        if(!isset($arr[0])) return array();
        $referenceValue = $arr[0];
        foreach($arr as $key => $val){
            if($referenceValue > $val){//比参考值大的，放到右集合
                $rightCollection[] = $val;
            }
            if($referenceValue < $val){//比参考值小的，放到左集合
                $leftCollection[] = $val;
            }
        }
        $leftData = quickSort($leftCollection);//再次处理左边集合
        $leftData[] = $referenceValue;//把参考值放到左集合最后
        $rightData = quickSort($rightCollection);//再次处理右边集合
        return array_merge($leftData, $rightData);
    }

   function float_microtime()
    {
        list($usc,$sec) = explode(" ", microtime());
        return ((float)$usc + (float)$sec);
    }
    $arr = array();
    for($i = 1; $i < 3000; $i++){
        $arr[] = mt_rand(1, 10000);
    }
    $arr1 = $arr;
    $arr2 = $arr;
    $arr3 = $arr;
    $time1 = float_microtime();
    bubbleSort($arr);
    $time2 = float_microtime();
    echo 'bubbleSort-time:',($time2 - $time1),PHP_EOL;
    unset($arr);
    $time1 = float_microtime();
    selectSort($arr1);
    $time2 = float_microtime();
    echo 'selectSort-time:',($time2 - $time1),PHP_EOL;
    unset($arr1);
    $time1 = float_microtime();
    asort($arr2);
    $time2 = float_microtime();
    echo 'asort-time:',($time2 - $time1),PHP_EOL;
    unset($arr2);
    $time1 = float_microtime();
    $a = quickSort($arr3);
    $time2 = float_microtime();
    echo 'quickSort-time:',($time2 - $time1),PHP_EOL;
