<?php

/**
 * 一款基于wow.js的元素初次加载动画插件
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
            echo "<p class='buttons'><a href='https://keymoe.com/archives/55/'>插件说明</a>
                <a href='https://github.com/Sanakey/WowScroll'>查看更新</a></p>";
            echo "<p>更多说明请点击插件说明或<a href='https://github.com/Sanakey/WowScroll'>点击前往github查看</a>~</p>";

            echo "</div>";
        }
        check_update("1.0");

        // 动画元素
        $elements = new Typecho_Widget_Helper_Form_Element_Text(
            'elements',
            NULL,
            NULL,
            _t('动画作用的元素'),
            _t('动画作用的元素，支持css选择器，请按照css样式class名或者id名输入，如.nav #header div.content等。支持多元素，请用半角逗号隔开')
        );
        $form->addInput($elements);
        
        //  选择动画效果
        $styles = array('bounce','flash','pulse','rubberBand','shake','swing','tada','wobble','jello','heartBeat','bounceIn','bounceInDown','bounceInLeft','bounceInRight','bounceInUp','fadeIn','fadeInDown','fadeInDownBig','fadeInLeft','fadeInLeftBig','fadeInRight','fadeInRightBig','fadeInUp','fadeInUpBig','flip','flipInX','flipInY','lightSpeedIn','rotateIn','rotateInDownLeft','rotateInDownRight','rotateInUpLeft','rotateInUpRight','slideInUp','slideInDown','slideInLeft','slideInRight','zoomIn','zoomInDown','zoomInLeft','zoomInRight','zoomInUp','jackInTheBox','rollIn');
        $styles = array_combine($styles, $styles);
        $animate = new Typecho_Widget_Helper_Form_Element_Select(
            'animate',
            $styles,
            'rubberBand',
            _t('选择动画效果'),
            _t('动画效果请参考<a href="https://daneden.github.io/animate.css/">animate.css官方文档</a>，只筛选了入场动画。')
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
            _t('本插件需要加载animate.css，当前使用的版本为3.7.2。如果你已经引入过animate.css，可以关闭该选项。</br>')
        );
        $form->addInput($loadCss);

        //  jquery
        $jquery = new Typecho_Widget_Helper_Form_Element_Radio(
            'jquery',
            array(
                '0' => _t('否'),
                '1' => _t('是'),
            ),
            '1',
            _t('是否加载Jquery'),
            _t('本插件需要加载jQuery，如果你已经引入过jQuery，请关闭该选项。')
        );
        $form->addInput($jquery);
        

        // 是否启用了pjax
        $pjax = new Typecho_Widget_Helper_Form_Element_Radio(
            'pjax',
            array(
                '0' => _t('否'),
                '1' => _t('是'),
            ),
            '1',
            _t('是否启用了PJAX'),
            _t('如果你启用了pjax，函数将会每次在pjax回调内执行。如果没启用，函数将在页面加载完时执行一次。<b style="color:#f23232">如果你不懂此选项的含义，请按照当前主题是否设置了pjax来设置此选项。</b>')
        );
        $form->addInput($pjax);

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
        $pjax = $options->plugin('WowScroll')->pjax;

        // 如果没输入元素 返回
        $elements = $elements ? explode(',', $elements) : '';
        if (!$elements) return;
        $eleArray = json_encode($elements);
        // print_r($eleArray);
        $path = $options->pluginUrl . '/WowScroll/';
        echo '<script type="text/javascript" src="' . $path . 'js/wow.min.js"></script>';
        $script = '';
        if ($pjax) { //开启pjax
            $script .= 'pjaxCallback()';
        } else {
            $script .= 'windowOnload()';
        }
        echo <<<HTML
            <script type="text/javascript">
                $script;
                // console.log({$eleArray});
                var eleArray = {$eleArray};
                function windowOnload() {
                    $(window).load(function () {
                        // console.log('windowOnload');
                        eleArray.forEach(function(item){
                            $(item.trim()).each(function () {
                                $(this).addClass('wow ' + '{$animate}');
                            });
                        })
            
                    })
                }
                function pjaxCallback() {
                    // console.log('pjaxCallback');
                    $(window).on("load pjax:end", function () {
                        eleArray.forEach(function(item){
                            $(item.trim()).each(function () {
                                $(this).addClass('wow ' + '{$animate}');
                            });
                        })
                    });
                }
                var wow = new WOW({
                      boxClass: 'wow',
                      animateClass: 'animated',
                      offset: 0,
                      mobile: true,
                      live: true
                    });
                wow.init();
            </script>
HTML;
    }
}