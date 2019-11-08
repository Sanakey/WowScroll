# WowScroll 
这是一款typecho插件，一款基于`wow.js`开发的元素初次加载动画插件，主要功能就是给元素添加好看的入场动画。

## 食用方法 
* 点击右上角绿色的`Clone or download`，`Download ZIP`, 解压文件。
* 重命名文件夹为`WowScroll`
* 将`WowScroll`文件夹上传至typecho的插件`usr/plugins`目录
* 登录后台启用`WowScroll`插件即可正确食用

## 配置说明
* 正确启用插件后，首先需要设置动画作用的元素，请用css选择器语法在框内填写你要添加动画的元素。
比如我想让handsome主题首页的文章出现初次加载动画，可以输入`#post-panel .panel`。

* 本插件支持输入多个选择器，使用时请用半角逗号`,`隔开。比如`#post-panel .panel, h2`，这样文章和所有h2标签都会添加入场动画。

**目前插件使用的`animate.css`版本是3.7.2，如果你未加载过或者使用的版本低于3.7.2，请开启`animate.css`的加载。**

* handsome主题内置的animate版本较低，建议插件配置开启`animate.css`的加载，否则会出现部分动画失效的情况。

* 关于动画的种类与预览效果，[查看animate.css文档](https://daneden.github.io/animate.css)

## 更新
### 1.1.0
* 添加完全随机动画模式
* 添加统一随机动画模式

## 效果
预览效果: [Sanakeyの小站](https://keymoe.com)

## 感谢
[wow.js](https://github.com/matthieua/WOW)

[animate.css](https://github.com/daneden/animate.css)

