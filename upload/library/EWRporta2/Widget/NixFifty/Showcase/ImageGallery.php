<?php

class EWRporta2_Widget_NixFifty_Showcase_ImageGallery extends XenForo_Model
{
    public function getCachedData($widget, $options)
    {
        /** @var NFLJ_Showcase_Model_Item $scItemModel */
        $scItemModel = $this->getModelFromCache('NFLJ_Showcase_Model_Item');
        /** @var NFLJ_Showcase_Model_Category $scCategoryModel */
        $scCategoryModel = $this->getModelFromCache('NFLJ_Showcase_Model_Category');
        $visitor = XenForo_Visitor::getInstance();

        $items = array();

        if ($visitor['permissions']['nfljsc']['viewShowcase'])
        {
            $categoryIds = empty($options['nixfifty_showcase_imagegallery_category']) ? array(): $options['nixfifty_showcase_imagegallery_category'];

            // These are the cat id's that the viewing user is allowed to "view"
            $viewableCategories = $scCategoryModel->getViewableCategories();
            $viewableCategories = array_keys($viewableCategories);

            // These are the category ID's that are contained in BOTH arrays (to ensure the user only see's content they have permission to view)
            $catIds = array_intersect($categoryIds, $viewableCategories);

            $conditions = array(
                'category_id' => $catIds,
                'featured' => ($options['nixfifty_showcase_imagegallery_order'] == 'featured') ? true : false,
                'rated' => ($options['nixfifty_showcase_imagegallery_order'] == 'rated') ? true : false,
                'reviewed' => ($options['nixfifty_showcase_imagegallery_order'] == 'reviewed') ? true : false,
                'image' => true,
            );

            $items = $scItemModel->getItems($conditions, array(
                'join' => NFLJ_Showcase_Model_Item::FETCH_CATEGORY
                    | NFLJ_Showcase_Model_Item::FETCH_USER,
                'limit' => $options['nixfifty_showcase_imagegallery_limit'],
                'order' => $options['nixfifty_showcase_imagegallery_order'],
                'direction' => 'DESC'
            ));

            $items = $scItemModel->prepareItems($items);
        }

        return array(
            'items' => $items,
        );
    }
}