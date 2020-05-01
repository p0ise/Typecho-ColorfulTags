<?php
/**
 * <a href='https://blog.irow.top/archives/396.html' title='项目主页' target='_blank'>彩色3D标签云插件</a>
 *
 * @package ColorfulTags
 * @author 锋临
 * @version 1.6
 * @link https://www.invelop.cn/
 */
class ColorfulTags_Plugin implements Typecho_Plugin_Interface {
	/* 激活插件方法 */
	public static function activate() {
		Typecho_Plugin::factory('Widget_Archive')->footer = array(__CLASS__, 'render');
	}
	/* 禁用插件方法 */
	public static function deactivate() {
	}
	/* 插件配置方法 */
	public static function config(Typecho_Widget_Helper_Form $form) {
		$css_selector = new Typecho_Widget_Helper_Form_Element_Text('css_selector', NULL, 'tag_cloud', _t('CSS选择器'), _t('填写标签云父容器的CSS选择器路径'));
		$form->addInput($css_selector);
		$is_3d = new Typecho_Widget_Helper_Form_Element_Radio('is_3d', ['0' => _t('否'), '1' => _t('是')], '0', _t('是否启用3D效果'), _t('开启后标签云会围绕3D球体滚动'));
		$form->addInput($is_3d);
		$radius = new Typecho_Widget_Helper_Form_Element_Text(
							'radius', NULL, '80',
							_t('3D标签云半径：'),
							_t('默认为80，如果不是很清楚请勿修改')
						);
		$form->addInput($radius);
		$speed = new Typecho_Widget_Helper_Form_Element_Text(
							'speed', NULL, '11',
							_t('3D旋转速度：'),
							_t('默认为11，如果不是很清楚请勿修改')
						);
		$form->addInput($speed);
		$is_pjax = new Typecho_Widget_Helper_Form_Element_Radio('is_pjax', ['0' => _t('否'), '1' => _t('是')], '0', _t('是否启用了PJAX'), _t('如果你启用了pjax,当切换页面时候，js不会重写绑定事件到新生成的节点上。
			你可以在该项设置中重新加载js函数，以便将事件正确绑定ajax生成的DOM节点上'));
		$form->addInput($is_pjax);
	}
	/* 个人用户的配置方法 */
	public static function personalConfig(Typecho_Widget_Helper_Form $form) {
	}
	/* 插件实现方法 */
	public static function render() {
		/*获取参数*/
		$options = Helper::options();
		$css_selector = $options->plugin('ColorfulTags')->css_selector;
		$is_3d = $options->plugin('ColorfulTags')->is_3d;
		$radius = $options->plugin('ColorfulTags')->radius;
		$speed = $options->plugin('ColorfulTags')->speed;
		$is_pjax = $options->plugin('ColorfulTags')->is_pjax;
		$is_post = $archive->parameter->type=='post';
		$static_src = $options->pluginUrl.'/ColorfulTags';
		$basic_style = <<<css
						<style>
						{$css_selector}>a {
							color: #fff;
							text-align: center;
							text-overflow: ellipsis;
							white-space: nowrap;
							padding: 3px 5px;
							border: 0;
							border-radius: 3px;
							display: inline-block;
							line-height: 18px;
						}
						
						{$css_selector}>a:hover {
							background: #d02f53 !important;
						}
						</style>
css;
		$around_style = <<<css
				<style>
				{$css_selector} {
					position: relative;
					width: 100%;
					padding-top: 100%;
					border: 2px black;
					margin: 0 10px 15px 0
				}
				{$css_selector}>a {
					position: absolute;
					top: 0;
					left: 0;
					color: #fff;
					text-align: center;
					text-overflow: ellipsis;
					white-space: nowrap;
					padding: 3px 5px;
					border: 0;
					border-radius: 3px;
					display: inline-block;
					line-height: 18px
				}
				
				{$css_selector}>a:hover {
					background: #d02f53 !important;
					display: block
				}
				</style>
css;

		if($is_pjax) {
			if($is_post||!$is_3d) {
 				$html = <<<html
 							<!-- Start ColorfulTags -->
 							{$basic_style}
 							<script src="{$static_src}/js/colorfultags.min.js"></script>
 							<script id="colorfultags">
 							console.info("%c彩色标签云-锋临|BLOG.IROW.TOP","line-height:28px;padding:4px;background:#3f51b5;color:#fff;font-size:14px;font-family:Microsoft YaHei;");
 							colorfultags("{$css_selector}");
 							$($(document).on("pjax:complete", function() {
 								colorfultags("{$css_selector}")
 							}));
 							</script>
 							<!-- End ColorfulTags -->
html;
			} else {
 				$html = <<<html
 							<!-- Start ColorfulTags -->
 							{$around_style}
 							<script src="{$static_src}/js/colorfultags.min.js"></script>
 							<script src="{$static_src}/js/around3d.min.js"></script>
 							<script id="#colorfultags">
 							console.info("%c彩色标签云-锋临|BLOG.IROW.TOP","line-height:28px;padding:4px;background:#3f51b5;color:#fff;font-size:14px;font-family:Microsoft YaHei;");
 							colorfultags("{$css_selector}");
 							around3D("{$css_selector}",{$radius}, 200, Math.PI / 180, 1, 1, true, {$speed}, 200, 0, 10, 1);
 							$($(document).on("pjax:complete", function() {
 								colorfultags("{$css_selector}");
 								around3D("{$css_selector}",{$radius}, 200, Math.PI / 180, 1, 1, true, {$speed}, 200, 0, 10, 1)
 							}));
 							</script>
 							<!-- End ColorfulTags -->
html;
			}
		} else {
			if($is_post||!$is_3d) {
 				$html = <<<html
 							<!-- Start ColorfulTags -->
 							{$basic_style}
 							<script src="{$static_src}/js/colorfultags.min.js"></script>
 							<script>
 							console.info("%c彩色标签云-锋临|BLOG.IROW.TOP","line-height:28px;padding:4px;background:#3f51b5;color:#fff;font-size:14px;font-family:Microsoft YaHei;");
 							colorfultags("{$css_selector}");
 							</script>
 							<!-- End ColorfulTags -->
html;
			} else {
 				$html = <<<html
 							<!-- Start ColorfulTags -->
 							{$around_style}
 							<link rel="stylesheet" type="text/css" href="{$static_src}/css/around3d.min.css">
 							<script src="{$static_src}/js/colorfultags.min.js"></script>
 							<script src="{$static_src}/js/around3d.min.js"></script>
 							<script>
 							console.info("%c彩色标签云-锋临|BLOG.IROW.TOP","line-height:28px;padding:4px;background:#3f51b5;color:#fff;font-size:14px;font-family:Microsoft YaHei;");
 							colorfultags("{$css_selector}");
 							around3D("{$css_selector}",{$radius}, 200, Math.PI / 180, 1, 1, true, {$speed}, 200, 0, 10, 1);
 							</script>
 							<!-- End ColorfulTags -->
html;
			}
		}
		echo $html;
	}
}