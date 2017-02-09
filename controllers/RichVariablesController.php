<?php
/**
 * Rich Variables plugin for Craft CMS
 *
 * RichVariables Controller
 *
 * @author    nystudio107
 * @copyright Copyright (c) 2017 nystudio107
 * @link      https://nystudio107.com
 * @package   RichVariables
 * @since     1.0.0
 */

namespace Craft;

class RichVariablesController extends BaseController
{
    /**
     */
    public function actionIndex()
    {
        $result = array();
        $variablesList = array();

        // Get the global set to use
        $settings = craft()->plugins->getPlugin('richvariables')->getSettings();
        $globalsSet = craft()->globals->getSetByHandle($settings['globalSetHandle']);
        // Grab the first global set if they haven't specified one yet
        if (!$globalsSet) {
            $allGlobalsSetIds = craft()->globals->getAllSetIds();
            if (!empty($allGlobalsSetIds)) {
                //$globalsSet = craft()->globals->getSetById($allGlobalsSetIds[0]);
            }
        }
        if ($globalsSet) {
            // Get the fieldlayout fields used for this global set
            $fieldLayoutFields = $globalsSet->getFieldLayout()->getFields();
            foreach ($fieldLayoutFields as $fieldLayoutField) {
                // Get the actual field, and check that it's type is something we support
                $field = craft()->fields->getFieldById($fieldLayoutField->fieldId);
                switch ($field->type)
                {
                    case "PlainText":
                    case "Number":
                    case "PreparseField_Preparse":
                    case "Date":
                    case "Dropdown":
                        // Add the field title and Reference Tag as per https://craftcms.com/docs/reference-tags
                        $value = $globalsSet->getContent()[$field->handle];
                        $thisVar = array(
                            'title' => $field->name,
                            'text' => "{globalset:" . $globalsSet->attributes['id'] . ":" . $field->handle . "}",
                            );
                        array_push($variablesList, $thisVar);
                        break;

                    case "Table":
                    case "SuperTable":
                        // This may not be possible with native reference tags
                        break;

                    default:
                        // NOP
                        break;
                }
            }
        }

        // Return everything to our JavaScript encoded as JSON
        $result['variablesList'] = $variablesList;
        $result['useIconForMenu'] = $settings['useIconForMenu'];
        $this->returnJson($result);
    }
}