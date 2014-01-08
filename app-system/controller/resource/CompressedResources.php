<?php
require_controller("Resource_Resources");
/**
 * 返回压缩的资源内容。
 * 本类在APF_Resource_ResourcesController添加了资源压缩服务等相关操作。
 * added by htlv
 */
class Resource_CompressedResourcesController extends
    Resource_ResourcesController {
    /**
     * 压缩服务服务器地址配置字段
     * @var string
     */
    const CONFIG_N_YUICOMPRESSOR_HOST = "yuicompressor_host";
    /**
     * 压缩服务服务器端口配置字段
     * @var string
     */
    const CONFIG_N_YUICOMPRESSOR_PORT = "yuicompressor_port";
    /**
     * 处理压缩资源内容。调用父类APF_Resource_ResourcesController的handle_request方法，
     * 获取资源内容，在调用压缩服务实现压缩，最终给浏览器返回压缩后的资源内容。
     * @see APF_Resource_ResourcesController::handle_request()
     */
    public function handle_request() {
        // 开启缓冲区
        ob_start();
        parent::handle_request();

        $uri = $_SERVER['REQUEST_URI'];
        // 获取资源内容
        $content = ob_get_contents();
        ob_end_clean();
        // 读取压缩服务配置
        $apf = Dispatcher::getInstance();
        $host = @$apf->get_config(self::CONFIG_N_YUICOMPRESSOR_HOST,
            parent::CONFIG_F_RESOURCE);
        $port = @$apf->get_config(self::CONFIG_N_YUICOMPRESSOR_PORT,
            parent::CONFIG_F_RESOURCE);
        if (!$host || !$port) { // 未配置压缩服务则直接输出原始内容
            echo $content;
            return;
        }
        // 调用压缩服务，输出压缩结果
        $fp = @fsockopen($host, $port);
        if (!$fp) { // 连接失败则直接输出内容
            echo $content;
            return;
        }
        // 发送要压缩的内容
        fwrite($fp, "$uri\n");
        fwrite($fp, $content);
        fwrite($fp, "\n\0\n");
        // 读取并显示压缩后的内容
        while (!feof($fp)) {
            echo fread($fp, 8192);
        }

        fclose($fp);
    }
}