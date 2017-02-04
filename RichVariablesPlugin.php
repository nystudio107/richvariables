<?php
namespace Craft;

/**
 * Rich Variables plugin
 */
class RichVariablesPlugin extends BasePlugin
{
	public function getName()
	{
		return 'Rich Variables';
	}

    public function getDescription()
    {
        return Craft::t('Allows you to easily use Craft Globals as variables in Rich Text fields');
    }

    public function getDocumentationUrl()
    {
        return 'https://github.com/nystudio107/richvariables/blob/master/README.md';
    }

    public function getReleaseFeedUrl()
    {
        return 'https://raw.githubusercontent.com/nystudio107/richvariables/master/releases.json';
    }

	public function getVersion()
	{
		return '1.0.0';
	}

    public function getSchemaVersion()
    {
        return '1.0.0';
    }

	public function getDeveloper()
	{
		return 'nystudio107';
	}

	public function getDeveloperUrl()
	{
		return 'https://nystudio107.com';
	}

	public function init()
	{
		if (!craft()->isConsole())
		{
			if (craft()->request->isCpRequest())
			{
                craft()->templates->includeCssResource('richvariables/css/richvariables.css');
                craft()->templates->includeJsResource('richvariables/js/foreachpolyfill.js');
                craft()->templates->includeJsResource('richvariables/js/richvariables.js');
			}
		}
	}

    protected function defineSettings()
    {
        return array(
            'globalSetHandle' => array(AttributeType::String, 'label' => 'Global Set', 'default' => ''),
        );
    }

    public function getSettingsHtml()
    {
        // Get all of the globals sets
        $globalsHandles = array();
        $allGlobalsSets = craft()->globals->getAllSets();
        foreach ($allGlobalsSets as $globalsSet) {
            $globalsHandles[$globalsSet->handle] = $globalsSet->name;
        }
        // Render our settings template
        return craft()->templates->render('richvariables/RichVariables_Settings', array(
            'settings' => $this->getSettings(),
            'globalsSets' => $globalsHandles,
        ));
    }

    public function prepSettings($settings)
    {
        // Modify $settings here...

        return $settings;
    }

}
