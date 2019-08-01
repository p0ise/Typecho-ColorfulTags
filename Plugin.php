<?php

/**
 * <a href='https://blog.irow.top/archives/396.html' title='mainpage' target='_blank'>彩色3D标签云插件</a>
 *
 * @package ColorfulTags
 * @author 承影
 * @version 0.8
 * @link https://blog.irow.top/
 */

class ColorfulTags_Plugin implements Typecho_Plugin_Interface
{
    /* 激活插件方法 */
    public static function activate()
    {
      Typecho_Plugin::factory('Widget_Archive')->footer = array(__CLASS__, 'render');
    }

    /* 禁用插件方法 */
    public static function deactivate(){}

    /* 插件配置方法 */
    public static function config(Typecho_Widget_Helper_Form $form)
    {
      $tagDiv = new Typecho_Widget_Helper_Form_Element_Text(
        'tagDiv', NULL, 'colorfultags',
        _t('标签云容器定位：'),
        _t('这里写上标签云容器的ID，如果没有ID请自行添加')
      );
      $form->addInput($tagDiv);
      $radius = new Typecho_Widget_Helper_Form_Element_Text(
        'radius', NULL, '80',
        _t('标签云半径：'),
        _t('默认为80，如果不是很清楚请勿修改')
      );
      $form->addInput($radius);
      $speed = new Typecho_Widget_Helper_Form_Element_Text(
        'speed', NULL, '11',
        _t('旋转速度：'),
        _t('默认为11，如果不是很清楚请勿修改')
      );
      $form->addInput($speed);

      $jquery = new Typecho_Widget_Helper_Form_Element_Radio('jquery',
          ['0' => _t('不加载'), '1' => _t('加载')],
          '1', _t('是否加载外部jQuery库'), _t('插件需要jQuery库文件的支持，如果已加载就不需要加载了 jquery源是新浪Public Resources on SAE：https://lib.sinaapp.com/js/jquery/1.9.1/jquery-1.9.1.min.js'));
      $pjax = new Typecho_Widget_Helper_Form_Element_Radio('pjax', ['0' => _t('否'), '1' => _t('是')], '0', _t('是否启用了PJAX'), _t('如果你启用了pjax,当切换页面时候，js不会重写绑定事件到新生成的节点上。
你可以在该项设置中重新加载js函数，以便将事件正确绑定ajax生成的DOM节点上'));
      $form->addInput($pjax);
    }

    /* 个人用户的配置方法 */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}

    /* 插件实现方法 */
    public static function render(){
      /*获取参数*/
      $options = Helper::options();
      $tagDiv = $options->plugin('ColorfulTags')->tagDiv;
      $radius = $options->plugin('ColorfulTags')->radius;
      $speed = $options->plugin('ColorfulTags')->speed;
      $pjax = $options->plugin('ColorfulTags')->pjax;
      $src = $options->pluginUrl.'/ColorfulTags/js/3dtags.min.js';

      /*输出彩色标签云*/
      echo "<script src='$src'></script>";

      $script = '<script>';
      if($pjax){
        $script .= 'window.onload = function(){console.info("%c彩色标签云-承影|BLOG.IROW.TOP","line-height:28px;padding:4px;background:#3f51b5;color:#fff;font-size:14px;");};$(document).on("ready pjax:end", function() {colorfultags'."('$tagDiv',$radius,200,Math.PI / 180,1,1,true,$speed,200,0,10,1);});";
      }else{
        $script .= "window.onload = function(){
          colorfultags('$tagDiv',$radius,200,Math.PI / 180,1,1,true,$speed,200,0,10,1);
          console.info(\"%c彩色标签云-承影|BLOG.IROW.TOP\",\"line-height:28px;padding:4px;background:#3f51b5;color:#fff;font-size:14px;\");
        }";
      }
      $script .= '</script>';
      $tagDiv = '#'.$tagDiv;
      $css = <<<css
      <style>
      {$tagDiv}{position:relative;width:240px;height:240px;border: 2px black;}
      {$tagDiv} a{position:absolute;color:#fff;text-align:center;text-overflow:ellipsis;white-space:nowrap;top:0;left:0;padding:3px 5px;border:0}
      {$tagDiv} a:hover{background:#d02f53;display:block}
      {$tagDiv} a:nth-child(n){background:#f60;border-radius:3px;display:inline-block;line-height:18px;margin:0 10px 15px 0}
      {$tagDiv} a:nth-child(2n){background: #45B6F7;}
      {$tagDiv} a:nth-child(3n){background: #15a287;}
      {$tagDiv} a:nth-child(4n){background: #5cb85c;}
      {$tagDiv} a:nth-child(5n){background: #d9534f;}
      {$tagDiv} a:nth-child(6n){background: #567e95;}
      {$tagDiv} a:nth-child(7n){background: #00a67c;}
      {$tagDiv} a:nth-child(8n){background: #b37333;}
      </style>
css;

      echo $css;
      echo $script;
    }

}
