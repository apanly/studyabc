<?php
$config['regex_function']='ereg';
$config['regex_label']='@';
$config['404'] = 'Error_HTTP404';
$config['mappings']['Home_Default'] = array(
    '^/$'
);
$config['mappings']['Resource_CompressedResources'] = array (
    '^/res/[^\/]+/(b|s)/(.*)\.(css|js)$',
    '^/res/(b|s)/(.*)\.(css|js)$'
);
$config['mappings']['Home_RecordWord']=array(
  '^/words$'
);
$config['mappings']['Login_Default']=array(
    '^/login$'
);
$config['mappings']['Login_Forgot']=array(
    '^/login/forgot'
);
$config['mappings']['SignUp_Default']=array(
    '^/signup'
);
$config['mappings']['Home_TinyEnglish']=array(
    '^/tinyenglish'
);
$config['mappings']['Home_EngChi']=array(
    '^/engchi$'
);
$config['mappings']['Home_EngChiDetail']=array(
    '^/engchidetail$'
);

$config['mappings']['KaoShi_Home']=array(
    '^/kaoshi$'
);
$config['mappings']['KaoShi_CET4']=array(
    '^/kaoshi/cet4$'
);
$config['mappings']['KaoShi_CET6']=array(
    '^/kaoshi/cet6$'
);
$config['mappings']['KaoShi_Detail']=array(
    '^/kaoshi/detail$'
);
$config['mappings']['KaoShi_Test']=array(
    '^/kaoshi/test$'
);
$config['mappings']['WordsCard_Detail']=array(
    '^/wordcard$'
);
$config['mappings']['Wan_List']=array(
  '^/wan/list$'
);





