<?php
/**
 * Copyrights: Deux Huit Huit 2015
 * LICENCE: MIT http://deuxhuithuit.mit-license.org;
*/

if(!defined("__IN_SYMPHONY__")) die("<h2>Error</h2><p>You cannot directly access this file</p>");

/**
 *
 * @author Deux Huit Huit
 * https://deuxhuithuit.com/
 *
 */
class extension_iframe_dashboard extends Extension {

    /**
     * Name of the extension
     * @var string
     */
    const EXT_NAME = 'Iframe Dashboard';

    /**
     * Name of the extension
     * @var string
     */
    const PANEL_NAME = 'Iframe';

    /* ********* DELEGATES ******* */

    public function getSubscribedDelegates()
    {
        return array(
            array(
                'page'      => '/backend/',
                'delegate'  => 'DashboardPanelRender',
                'callback'  => 'dashboard_render_panel'
            ),
            array(
                'page'      => '/backend/',
                'delegate'  => 'DashboardPanelTypes',
                'callback'  => 'dashboard_panel_types'
            ),
            array(
                'page'      => '/backend/',
                'delegate'  => 'DashboardPanelOptions',
                'callback'  => 'dashboard_panel_options'
            ),
        );
    }

    public function dashboard_render_panel($context)
    {
        if ($context['type'] != self::PANEL_NAME) {
            return;
        }
        $config = $context['config'];
        if (empty($config['url'])) {
            $context['panel']->appendChild(new XMLElement('h2', __('No url specified')));
            return;
        }
        $height = isset($config['height']) ? $config['height'] : '225px';
        $i = new XMLElement('iframe', null, array(
            'src' => $config['url'],
            'style' => "width:100%;height:$height;",
            'frameborder' => 'no',
        ));

        $context['panel']->appendChild($i);
    }

    public function dashboard_panel_types($context)
    {
        $context['types'][self::PANEL_NAME] = self::PANEL_NAME;
    }

    public function dashboard_panel_options($context)
    {
        if ($context['type'] != self::PANEL_NAME) {
            return;
        }
        $config = $context['existing_config'];
        if (empty($config)) {
            $handle = General::createHandle(self::EXT_NAME);
            $settings = Symphony::Configuration()->get($handle);
            if (!empty($settings)) {
                $config = $settings;
            }
        }

        $wrapper = new XMLElement('div');
        $fieldset = new XMLElement('fieldset', null, array('class' => 'settings'));
        $fieldset->appendChild(new XMLElement('legend', 'Display Options'));

        $layout = new XMLElement('div', null, ['class' => 'columns two']);

        $label = Widget::Label('Url', Widget::Input('config[url]', $config['url']), 'column');
        $layout->appendChild($label);

        $label = Widget::Label('Height (include units)', Widget::Input('config[height]', $config['height']), 'column');
        $layout->appendChild($label);

        $fieldset->appendChild($layout);
        $wrapper->appendChild($fieldset);

        $context['form'] = $wrapper;
    }

    /* ********* INSTALL/UPDATE/UNINSTALL ******* */

    /**
     * Creates the table needed for the settings of the field
     */
    public function install()
    {
        return true;
    }

    /**
     * This method will update the extension according to the
     * previous and current version parameters.
     * @param string $previousVersion
     */
    public function update($previousVersion = false)
    {
        $ret = true;

        if (!$previousVersion) {
            $previousVersion = '0.0.1';
        }
        return $ret;
    }

    public function uninstall()
    {
        return true;
    }
}
