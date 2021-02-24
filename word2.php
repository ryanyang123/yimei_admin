<?php

header("Content-type:text/html;charset=utf-8");

/**
 * @desc 方法一、生成word文档
 * @param $content
 * @param string $fileName
 */
function createWord($content='',$fileName='new_file.doc'){
    if(empty($content)){
        return;
    }
    $content='<html 
            xmlns:o="urn:schemas-microsoft-com:office:office" 
            xmlns:w="urn:schemas-microsoft-com:office:word" 
            xmlns="http://www.w3.org/TR/REC-html40">
            <meta charset="UTF-8" />'.$content.'</html>';
    if(empty($fileName)){
        $fileName=date('YmdHis').'.doc';
    }
    $fp=fopen($fileName,'wb');
    fwrite($fp,$content);
    fclose($fp);
}

$str = '<h1 style="color:red;">我是h1</h1><h2>我是h2</h2>';
createWord($str);


/**
 * @desc 方法二、生成word文档并下载
 * @param $content
 * @param string $fileName
 */
function downloadWord($content, $fileName='new_file.doc'){

    if(empty($content)){
        return;
    }

    header("Cache-Control: no-cache, must-revalidate");
    header("Pragma: no-cache");
    header("Content-Type: application/octet-stream");
    header("Content-Disposition: attachment; filename=$fileName");

    $html = '<html xmlns:v="urn:schemas-microsoft-com:vml"
         xmlns:o="urn:schemas-microsoft-com:office:office"
         xmlns:w="urn:schemas-microsoft-com:office:word" 
         xmlns:m="http://schemas.microsoft.com/office/2004/12/omml" 
         xmlns="http://www.w3.org/TR/REC-html40">';
    $html .= '<head><meta charset="UTF-8" /></head>';

    echo $html . '<body>'.$content .'</body></html>';

}

$str = '<h4>表头：</h4>
<table border="1">
<tr>
  <th>姓名</th>
  <th>电话</th>
  <th>电话</th>
</tr>
<tr>
  <td>Bill Gates</td>
  <td>555 77 854</td>
  <td>555 77 855</td>
</tr>
</table>';

downloadWord($str, 'abc.doc');
