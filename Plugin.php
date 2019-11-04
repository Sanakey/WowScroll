<?php
/**
 * 一款基于wow.js的动画插件<br />(请勿与其它同类插件同时启用，以免互相影响)<br />
 *
 * @package WowScroll
 * @author Sanakey
 * @version 1.0.0
 * @link https://keypoi.com
 */
class WowScroll_Plugin implements Typecho_Plugin_Interface {
     /**
     * 激活插件方法,如果激活失败,直接抛出异常
     * 
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate() {
        Typecho_Plugin::factory('Widget_Archive')->header = array(__CLASS__, 'header');
        Typecho_Plugin::factory('Widget_Archive')->footer = array(__CLASS__, 'footer');
        return "插件启动成功";
    }

    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     * 
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate(){ return "插件禁用成功"; }

    /**
     * 获取插件配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form){

        // 插件信息与更新检测
        function check_update($version)
        {
            echo "<style>.info{text-align:center; margin:20px 0;} .info > *{margin:0 0 15px} .buttons a{background:#467b96; color:#fff; border-radius:4px; padding: 8px 10px; display:inline-block;}.buttons a+a{margin-left:10px}</style>";
            echo "<div class='info'>";
            echo "<h2>WowScroll动感元素插件 (" . $version . ")</h2>";
            echo "<p>By: <a href='https://github.com/Sanakey'>Sanakey</a></p>";
            echo "<p class='buttons'><a href='https://keymoe.com/archives/31/'>项目说明</a>
                <a href='https://github.com/Sanakey/WowScroll'>检查更新</a></p>";
            echo "<p>更多说明请点击项目说明或<a href='https://github.com/Sanakey/WowScroll'>点击前往github查看</a>~</p>";

            echo "</div>";
        }
        check_update("1.0");

        // 动画元素
        $elements = new Typecho_Widget_Helper_Form_Element_Text(
            'elements',
            NULL,
            NULL,
            _t('动画作用的元素'),
            _t('动画作用的元素，请输入css样式class名或者id名，如.nav #header，支持多元素，请用半角逗号','隔开')
        );
        $form->addInput($elements);
        
        //  选择动画效果
        $styles = array('bounce','flash','pulse','rubberBand','shake','swing','tada','wobble','jello','heartBeat');
        $styles = array_combine($styles, $styles);
        $animate = new Typecho_Widget_Helper_Form_Element_Select(
            'animate',
            $styles,
            'rubberBand',
            _t('选择动画效果'),
            _t('动画效果请参考<a href=“https://daneden.github.io/animate.css/”>animate.css官方文档</a>')
        );
        $form->addInput($animate->addRule('enum', _t('必须选择一个动画效果'), $styles));

        // 是否加载animate.css
        $loadCss = new Typecho_Widget_Helper_Form_Element_Radio(
            'loadCss',
            array(
                '0' => _t('否'),
                '1' => _t('是'),
            ),
            '1',
            _t('是否加载animate.css'),
            _t('如果你已经引入过animate.css，关闭该选项。')
        );
        $form->addInput($loadCss);

        //  CheckBox框
        $jquery = new Typecho_Widget_Helper_Form_Element_Checkbox(
            'jquery', 
            array('jquery' => '是否加载jQuery'), 
            false,
            _t('Jquery设置'), 
            _t('本插件需要加载jQuery，如果已经引用加载过JQuery，则可以勾选。')
        );
        $form->addInput($jquery);

    }

    /**
     * 个人用户的配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}

    /**
     * 插件实现方法
     * 
     * @access public
     * @return void
     */
    public static function render() {
        
    }

    /**
     * 在header页头输出相关代码
     *
     * @access public
     * @param unknown header
     * @return void
     */
    public static function header() {
        // $path = Helper::options()->pluginUrl . '/KirinShiKi/';
        // echo '<link rel="stylesheet" type="text/css" href="' . $path . 'css/kirin.css" />';
        // $path = Helper::options()->pluginUrl . '/KirinShiKi/';
        // echo '<script type="text/javascript" src="' . $path . 'js/kirin.js"></script>';

        //  获取用户配置
        $options = Helper::options();
        $loadCss = $options->plugin('WowScroll')->loadCss;
        $jquery = $options->plugin('WowScroll')->jquery;
        // 输出css文件
        $path = $options->pluginUrl . '/WowScroll/';
        if ($loadCss) {
            echo '<link rel="stylesheet" type="text/css" href="' . $path . 'css/animate.min.css" />';
        }
        if (!$jquery) {
            echo '<script type="text/javascript" src="' . $path . 'js/jquery.min.js"></script>';
        }
        
        // 输出js
        // $pjax = $options->plugin('KirinShiKi')->pjax;
        // $script = '<script>';
        // if ($pjax) { //开启pjax
        //     $script .= '$(document).on("ready pjax:end", ' . 'function() {needpjax()});';
        // } else {
        //     $script .= '$(document).ready(function() {setHref(getHref());colorfulTags();});';
        // }
        // $script .= '</script>';
    }

    /**
     * 在页脚footer输出相关代码
     *
     * @access public
     * @param unknown footer
     * @return void
     */
    public static function footer() {
        //  获取用户配置
        $options = Helper::options();
        $animate = $options->plugin('WowScroll')->animate;
        $elements = $options->plugin('WowScroll')->elements;
        $path = $options->pluginUrl . '/WowScroll/';
        echo '<script type="text/javascript" src="' . $path . 'js/wow.min.js"></script>';
        echo <<<HTML
            <script type="text/javascript">
                $(document).ready(function () {
                    $('{$elements}').each(function () {
                        $(this).addClass('wow ' + '{$animate}')
                    })
                })
                wow = new WOW({
                      boxClass: 'wow',      // default
                      animateClass: 'animated', // default
                      offset: 0,          // default
                      mobile: true,       // default
                      live: true        // default
                    })
                wow.init();
            </script>
        HTML;
    }
}
