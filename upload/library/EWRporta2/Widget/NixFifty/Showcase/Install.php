<?php

class EWRporta2_Widget_NixFifty_Showcase_Install
{
    protected static function _canBeInstalled(&$error)
    {
        /** @var XenForo_Model_AddOn $addOnModel */
        $addOnModel = XenForo_Model::create('XenForo_Model_AddOn');
        $notInstalled = array();
        $requiredAddOns = array(
            'NFLJ_Showcase'
        );

        foreach ($requiredAddOns AS $addOnId)
        {
            $addOn = $addOnModel->getAddOnById($addOnId);
            if (!$addOn)
            {
                $notInstalled[] = $addOnId;
            }
        }

        if ($notInstalled)
        {
            throw new XenForo_Exception(
                'The following add-on needs to be installed before you can install this widget: ' . implode(',', $notInstalled),
                true
            );
        }

        return true;
    }

    public static function install()
    {
        if (!self::_canBeInstalled($error))
        {
            throw new XenForo_Exception($error, true);
        }
    }
}